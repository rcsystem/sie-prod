<?php

/**
 * MODULO DE Liberación
 * @version 1.1 pre-prod
 * @author  Rafel Cruz Aguilar <rafel.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 */

namespace App\Controllers\Liberation;

use ZipArchive;
use App\Controllers\BaseController;

use App\Models\LiberationCommentModel;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;

use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;

use PhpOffice\PhpSpreadsheet\IOFactory;

class Liberation  extends BaseController
{


    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }


    public function viewItems()
    {
        if ($this->is_logged) {
            $departments = $this->db->table('cat_departament')
                ->select('id_depto, departament')
                ->whereIn('active_status', [1, 2])
                ->get()
                ->getResult();

            return view('liberation/view_request_items_all', [
                'departments' => $departments
            ]);
        } else {
            return redirect()->to(site_url());
        }
    }

    public function requestAllItems()
    {
        try {
            $builder = $this->db->table('cat_liberation_items i');
            $builder->select('
                i.id,
                i.name,
                i.description,
                i.status as activo,
                i.department_name as department_name
            ');
            // $builder->join('cat_departament d', 'd.id_depto = i.department_id', 'left');
            $builder->where('i.status', 1);
            $builder->limit(1000);

            $query = $builder->get()->getResult();

            return $this->response->setJSON($query);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Ha ocurrido un error en el servidor: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function saveItem()
    {
        try {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'department_name' => $this->request->getPost('department_id'),
                'status' => 1
            ];

            $this->db->table('cat_liberation_items')->insert($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Ítem creado correctamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function deactivateItem()
    {
        $id_item = $this->request->getPost('id_item');

        if (!$id_item) {
            return $this->response->setJSON(['error' => 'Falta el ID del Item']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $db->table('cat_liberation_items')
            ->where('id', $id_item)
            ->update(['status' => 0]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['error' => 'No se pudo desactivar el Item']);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Se completo la ejecución']);
    }

    public function viewUserRegistration()
    {
        return ($this->is_logged) ? view('liberation/view_request_liberation_all') : redirect()->to(site_url());
    }

    public function viewCreateRequest()
    {
        return ($this->is_logged) ? view('liberation/view_request_liberation') : redirect()->to(site_url());
    }


    public function requestLiberationAll()
    {
        $db = \Config\Database::connect();

        // 1) Agregado por DEPARTAMENTOS (más confiable, tus flows ya lo actualizan)
        $depSub = $db->table('tbl_liberation_departments d')
            ->select("
            d.request_id,
            SUM(CASE WHEN d.request_status IN ('EN PROGRESO','FIRMADO') THEN 1 ELSE 0 END) AS progressed_depts,
            SUM(CASE WHEN d.request_status = 'FIRMADO' THEN 1 ELSE 0 END) AS signed_depts,
            COUNT(*) AS total_depts
        ", false)
            ->where('d.status', 1)
            ->groupBy('d.request_id');

        // 2) Agregado por ITEMS (fallback si el depto todavía no se ha recalculado)
        $itemSub = $db->table('tbl_liberation_request_items li')
            ->select("
            li.request_id,
            SUM(CASE WHEN (COALESCE(li.signed,0)=1 OR COALESCE(li.owed_amount,0)>0) THEN 1 ELSE 0 END) AS progressed_items
        ", false)
            ->where('li.status', 1)
            ->groupBy('li.request_id');

        $builder = $db->table('tbl_liberation_request lr');

        $builder->select([
            'lr.id AS folio',
            'CONCAT(u.name, " ", IFNULL(u.surname, ""), " ", IFNULL(u.second_surname, "")) AS user_name',
            'u.payroll_number AS nomina',
            // Regla de estado global:
            '('
                . 'CASE '
                . "  WHEN lr.request_status = 'FIRMADO' THEN 'FIRMADO' "
                . "  WHEN COALESCE(dep.progressed_depts, 0) > 0 THEN 'EN PROGRESO' "
                . "  WHEN COALESCE(it.progressed_items, 0) > 0 THEN 'EN PROGRESO' "
                . "  ELSE 'PENDIENTE' "
                . 'END'
                . ') AS request_status'
        ], false);

        $builder->join('tbl_users u', 'u.id_user = lr.employee_id', 'left');
        $builder->join("({$depSub->getCompiledSelect()}) dep", 'dep.request_id = lr.id', 'left', false);
        $builder->join("({$itemSub->getCompiledSelect()}) it",  'it.request_id  = lr.id', 'left', false);

        $builder->where('lr.status', 1);
        $builder->orderBy('lr.id', 'DESC');

        $result = $builder->get()->getResult();

        return $this->response->setJSON($result);
    }



    public function getCompanies()
    {
        $companies = $this->db->table('cat_company')
            ->select('id, name_company')
            ->whereIn('id', [1, 3, 4])
            ->get()
            ->getResult();

        return $this->response->setJSON($companies);
    }

    public function getUsersByCompany($company_id = null)
    {
        if (!$company_id) {
            return $this->response->setJSON([]);
        }

        // Traer usuarios activos
        $users = $this->db->table('tbl_users u')
            ->select("
                u.id_user,
                CONCAT(u.name, ' ', IFNULL(u.surname, ''), ' ', IFNULL(u.second_surname, '')) AS name,
                u.payroll_number,
                d.id_depto AS department_id,
                d.departament AS department_name,
                CONCAT(mng.name, ' ', IFNULL(mng.surname, ''), ' ', IFNULL(mng.second_surname, '')) AS direct_manager,
                CASE WHEN eq.id_user IS NOT NULL THEN 'Si' ELSE 'No' END AS equip_asigned
            ")
            ->join('cat_departament d', 'u.id_departament = d.id_depto', 'left')
            ->join('tbl_assign_departments_to_managers_new mgr', 'mgr.payroll_number = u.payroll_number', 'left')
            ->join('tbl_users mng', 'mng.id_user = mgr.id_manager', 'left')
            ->join('tbl_system_equip_assignment eq', 'eq.id_user = u.id_user', 'left')
            ->where('eq.active_status', 1)
            ->where('u.company', $company_id)
            ->where('u.active_status', 1)
            ->groupBy('u.id_user')
            ->get()
            ->getResultArray();

        // Recorrer usuarios y agregar info de equipos
        foreach ($users as &$user) {
            $equipos = $this->db->table('tbl_system_equip_assignment eq')
                ->select("ei.model, ei.marca, ei.no_serial")
                ->join('tbl_system_equip_inventory ei', 'ei.id_equip = eq.id_equip', 'left')
                ->where('eq.id_user', $user['id_user'])
                ->where('eq.active_status', 1)
                ->get()
                ->getResultArray();

            $user['equip_info'] = $equipos ?: []; // siempre array
        }

        return $this->response->setJSON($users);
    }

    public function createLiberationRequest()
    {
        $session = session();
        $createdBy = $session->get('id_user');
        $employeeId = $this->request->getPost('id_user_name');
        $employee = $this->request->getPost('user_name');
        $notificar = $this->request->getPost('notification') ? true : false;
        $tipo_nomina = $this->request->getPost('tipo_nomina');
        $periodo  = $this->request->getPost('periodo');
        $tel = $this->request->getPost('tel');
        $email_status = null;
        $company = $this->request->getPost('company');


        if (!$employeeId) {
            return $this->response->setJSON(['error' => 'Employee ID es requerido'])->setStatusCode(400);
        }

        $result = $this->createLiberationRequestLogic($employeeId, $createdBy, $tipo_nomina, $periodo, $tel, null, $company, $employee);

        if (isset($result['error'])) {
            return $this->response->setJSON(['error' => true, 'message' => $result['error']])->setStatusCode(500);
        }
        if ($notificar) {
            $email_status = $this->notifyLiberacionCreated($result['request_id'], 'NEW');
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Solicitud creada correctamente',
            'request_id' => $result['request_id'],
            'email_status' => $email_status,
        ]);
    }



    protected function createLiberationRequestLogic($employeeId, $createdBy, $tipo_nomina, $periodo, $tel, $id_rol = null, $company, $employee)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $now = date('Y-m-d H:i:s');

            // 1) Crear la solicitud
            $db->table('tbl_liberation_request')->insert([
                'company'      => $company,
                'employee_id'  => $employeeId,
                'employee'     => $employee,
                'created_by'   => $createdBy,
                'payroll_type' => $tipo_nomina,
                'period'       => $periodo,
                'phone_number' => $tel,
                'status'       => 1,
                'request_status' => 'PENDIENTE',   // 👈 inicial explícito
                'created_at'   => $now,
                'updated_at'   => $now,
                'company'      => $company,
            ]);
            $requestId = (int)$db->insertID();

            // 2) Resolver rol si no viene
            if ($id_rol === null) {
                $rowRol = $db->table('tbl_users')
                    ->select('id_rol')
                    ->where('id_user', (int)$employeeId)
                    ->get()->getRow();
                $id_rol = $rowRol ? (int)$rowRol->id_rol : null;
            }

            // 3) Determinar CUÁL "Caja de Ahorros" EXCLUIR según rol
            //    id_rol = 2  => excluir "Caja de Ahorros No Sindicalizados"
            //    id_rol != 2 => excluir "Caja de Ahorros Sindicalizados"
            $excludeDeptName = ($id_rol === 2)
                ? 'Caja de Ahorros No Sindicalizados'
                : 'Caja de Ahorros Sindicalizados';

            // 4) Traer departamentos COLAPSANDO por NOMBRE (evita (NULL,id) duplicados)
            $departments = $db->table('cat_liberation_items')
                ->select('department_name, MAX(department_id) AS department_id', false) // ← clave
                ->where('status', 1)
                ->where('department_name IS NOT NULL', null, false)
                ->groupBy('department_name')
                ->get()
                ->getResult();

            foreach ($departments as $dept) {
                $deptName = (string)$dept->department_name;
                if ($deptName === $excludeDeptName) {
                    continue; // salta el "Caja..." que no aplica
                }

                // Inserta el departamento SOLO si no existe ya en este request
                $existsDept = $db->table('tbl_liberation_departments')
                    ->select('id')
                    ->where('request_id', $requestId)
                    ->where('department_name', $deptName)
                    ->get()->getRow();

                if (!$existsDept) {
                    $deptInsert = [
                        'request_id'      => $requestId,
                        'department_name' => $deptName,
                        'status'          => 1,
                    ];
                    if (!is_null($dept->department_id)) {
                        $deptInsert['department_id'] = (int)$dept->department_id;
                    }
                    $db->table('tbl_liberation_departments')->insert($deptInsert);
                }


                // 5) Insertar ítems SOLO del departamento actual
                $items = $db->table('cat_liberation_items')
                    ->select('id, department_id, department_name')
                    ->where('status', 1)
                    ->where('department_name', $deptName)      // ← autoritativo por nombre
                    ->get()->getResult();

                foreach ($items as $item) {
                    // Idempotencia: no insertes si ya existe ese item en el request
                    $existsItem = $db->table('tbl_liberation_request_items')
                        ->select('id')
                        ->where('request_id', $requestId)
                        ->where('item_id', (int)$item->id)
                        ->get()->getRow();

                    if ($existsItem) {
                        continue;
                    }

                    $db->table('tbl_liberation_request_items')->insert([
                        'request_id'      => $requestId,
                        'item_id'         => (int)$item->id,
                        'department_id'   => isset($item->department_id) ? (int)$item->department_id : null,
                        'department_name' => (string)($item->department_name ?? $deptName),
                        'status'          => 1,
                        'signed'          => 0,
                        'signed_at'       => '0000-00-00 00:00:00', // por tu NOT NULL
                    ]);
                }
            }


            // (Opcional) Mantengo tu llamada original
            $managerDeptId = $this->getManagerDepartmentId($employeeId);

            $db->transComplete();
            if ($db->transStatus() === false) {
                return ['error' => 'Error al guardar la solicitud'];
            }

            return ['request_id' => $requestId];
        } catch (\Exception $e) {
            $db->transRollback();
            return ['error' => $e->getMessage()];
        }
    }






    protected function getManagerDepartmentId($employeeId)
    {
        $db = \Config\Database::connect();

        // Obtener id_manager del empleado
        $manager = $db->table('tbl_assign_departments_to_managers_new')
            ->select('id_manager')
            ->where('id_user', $employeeId)
            ->get()
            ->getRow();

        if (!$manager) {
            return null; // jefe directo no asignado
        }

        // Obtener id_departament del jefe directo
        $managerDept = $db->table('tbl_users')
            ->select('id_departament')
            ->where('id_user', $manager->id_manager)
            ->get()
            ->getRow();

        return $managerDept ? $managerDept->id_departament : null;
    }

    public function deactivateRequest()
    {
        $request_id = $this->request->getPost('request_id');

        if (!$request_id) {
            return $this->response->setJSON(['error' => 'Falta el ID de la solicitud']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $db->table('tbl_liberation_request')
            ->where('id', $request_id)
            ->update(['status' => 0]);

        $db->table('tbl_liberation_departments')
            ->where('request_id', $request_id)
            ->update(['status' => 0]);

        $db->table('tbl_liberation_request_items')
            ->where('request_id', $request_id)
            ->update(['status' => 0]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['error' => 'No se pudo desactivar la solicitud']);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Solicitud y registros relacionados desactivados']);
    }

    public function viewDepartmentRequestLiberation()
    {
        return ($this->is_logged) ? view('liberation/view_request_liberation_department') : redirect()->to(site_url());
    }


    public function requestLiberationDepartmentAll()
    {
        $user_id = session()->id_user;
        $db = \Config\Database::connect();

        // 🔹 Mapeo base de departamentos
        $departamentosUsuarios = [
            'Almacen de Herramientas'           => [328, 272],
            'HSE'                               => [75, 1292],
            'Soporte técnico e infraestructura' => [1390, 356, 1],
            'Servicios generales'               => [1283, 294],
            'Contabilidad'                      => [1283, 294],
            'Mercadotecnia'                     => [44, 152],
            'Metrología'                        => [42, 259],
            'Caja de Ahorros Sindicalizados'    => [455],
            'Caja de Ahorros No Sindicalizados' => [267, 50, 265],
            'Gestión de talento'                => [27, 903],
            'Vigilancia'                        => [710],
            'Servicio Médico'                   => [75, 1292]
        ];

        // 🔹 Departamentos donde el usuario pertenece
        $departamentosPrincipales = array_keys(
            array_filter($departamentosUsuarios, fn($usuarios) => in_array($user_id, $usuarios))
        );

        // 🔹 Subordinados del usuario
        $subordinados = $db->table('tbl_assign_departments_to_managers_new')
            ->select('id_user')
            ->where('id_manager', $user_id)
            ->get()
            ->getResultArray();

        $idsSubordinados = array_unique(array_filter(array_map('intval', array_column($subordinados, 'id_user'))));
        $esJefe = !empty($idsSubordinados);

        // 🔹 Construcción de la consulta principal
        $builder = $db->table('tbl_liberation_departments as ld');
        $builder->select("
        ld.id AS folio,
        lr.id AS folios,
        ld.request_id,
        ld.department_name,
        ld.request_status,
        CONCAT(u.name, ' ', IFNULL(u.surname, ''), ' ', IFNULL(u.second_surname, '')) AS user_name,
        u.payroll_number AS nomina
    ")
            ->join('tbl_liberation_request lr', 'lr.id = ld.request_id', 'inner')
            ->join('tbl_users u', 'u.id_user = lr.employee_id', 'left')
            ->join('tbl_assign_departments_to_managers_new adm', 'adm.id_user = lr.employee_id', 'left')
            ->where('ld.status', 1)
            ->groupStart();

        // ✅ Caso 1: pertenece a un departamento
        if (!empty($departamentosPrincipales)) {
            $builder->whereIn('ld.department_name', $departamentosPrincipales);
        }

        // ✅ Caso 2: es jefe
        // ✅ Caso 2: es jefe → solicitudes de subordinados
        if ($esJefe) {
            $builder->orGroupStart()
                ->where('ld.department_name', 'Jefe de área') // por consistencia visual
                ->whereIn('lr.employee_id', $idsSubordinados)
                ->groupEnd();
        }

        $builder->groupEnd()
            ->orderBy('ld.id', 'DESC');

        $result = $builder->get()->getResult();

        // 🔍 Debug opcional
        // log_message('debug', 'Usuario '.$user_id.' -> Deptos: '.json_encode($departamentosPrincipales).' | Subordinados: '.json_encode($idsSubordinados));

        return $this->response->setJSON($result);
    }





    public function getItemsByRequestAndDepartment()
    {
        $request_id      = $this->request->getPost('request_id');
        $department_name = $this->request->getPost('department_name');

        if (!$request_id || !$department_name) {
            return $this->response->setJSON(['error' => 'Faltan parámetros']);
        }

        $db = \Config\Database::connect();

        // 1) Trae SÓLO los ítems cuyo departamento en cat_liberation_items coincide
        //    (no confiamos en lri.department_name porque hay NULLs/desalineados)
        $items = $db->table('tbl_liberation_request_items AS lri')
            ->select("
        MIN(lri.id)                                  AS request_item_id,
        ci.id                                        AS item_id,
        ci.name                                      AS item_name,
        MAX(COALESCE(lri.signed,0))                  AS signed,
        MAX(COALESCE(lri.delivered,0))               AS delivered,
        MAX(COALESCE(lri.owed_amount,0))             AS owed_amount,
        1                                            AS status
    ", false)
            ->join('cat_liberation_items AS ci', 'ci.id = lri.item_id', 'inner')
            ->where('lri.request_id', $request_id)
            ->where('ci.department_name', $department_name)
            ->where('lri.status', 1)
            ->groupBy('ci.id, ci.name')
            ->orderBy('ci.id', 'ASC')
            ->get()
            ->getResult();

        // 2) Info de la solicitud (igual que ya lo tenías)
        $solicitud = $db->table('tbl_liberation_request AS lr')
            ->select('
                lr.*,
                u.name, u.surname, u.second_surname, u.email AS user_email,
                u.payroll_number, u.id_user AS employee_id
            ')
            ->join('tbl_users u', 'u.id_user = lr.employee_id', 'left')
            ->where('lr.id', $request_id)
            ->get()->getRow();

        if (!$solicitud) {
            return $this->response->setJSON(['error' => 'Solicitud no encontrada']);
        }

        return $this->response->setJSON([
            'request_info' => $solicitud,
            'items'        => $items
        ]);
    }


    public function updateItemSignedANT()
    {
        $data = $this->request->getPost();
        $request_item_id = $data['request_item_id'] ?? null;
        $signed          = isset($data['signed']) ? (int)$data['signed'] : null;
        $owed_amount_raw = $data['owed_amount'] ?? null;

        if ($request_item_id === null || ($signed === null && $owed_amount_raw === null)) {
            return $this->response->setJSON(['error' => 'Faltan parámetros']);
        }

        $db = \Config\Database::connect();
        $session = session();
        $currentUserId = (int)$session->get('id_user');

        // 1) Fila base
        $base = $db->table('tbl_liberation_request_items')
            ->select('id, request_id, item_id, department_id, department_name, signed, owed_amount')
            ->where('id', (int)$request_item_id)
            ->get()->getRow();

        if (!$base) return $this->response->setJSON(['error' => 'Ítem no encontrado']);

        $request_id      = (int)$base->request_id;
        $item_id         = (int)$base->item_id;
        $department_id   = $base->department_id ? (int)$base->department_id : null;
        $department_name = (string)($base->department_name ?? '');

        // 2) Normaliza adeudo y propaga a todas las copias del mismo item en el request
        if ($owed_amount_raw !== null) {
            $ow = (float)str_replace([',', ' '], ['', ''], (string)$owed_amount_raw);
            if ($ow < 0) $ow = 0;
            $db->table('tbl_liberation_request_items')
                ->where('request_id', $request_id)
                ->where('item_id', $item_id)
                ->where('status', 1)
                ->update(['owed_amount' => $ow]);
        }

        // 3) Si vino 'signed', propaga a todas las copias también
        if ($signed !== null) {
            // (opcional) bloquear firmar si hay adeudo > 0
            $maxOw = $db->table('tbl_liberation_request_items')
                ->select('MAX(owed_amount) AS ow')
                ->where('request_id', $request_id)
                ->where('item_id', $item_id)
                ->where('status', 1)
                ->get()->getRow();
            if ((int)$signed === 1 && (float)($maxOw->ow ?? 0) > 0) {
                return $this->response->setJSON(['error' => 'No se puede firmar un ítem con adeudo > 0']);
            }

            /* $db->table('tbl_liberation_request_items')
                ->where('request_id', $request_id)
                ->where('item_id', $item_id)
                ->where('status', 1)
                ->update([
                    'signed'    => (int)$signed,
                    'signed_at' => ((int)$signed === 1) ? date('Y-m-d H:i:s') : '0000-00-00 00:00:00',
                ]); */

            $db->table('tbl_liberation_request_items')
                ->where('id', $request_item_id)    // ⬅️ SOLO ESA FILA
                ->update([
                    'signed'    => (int)$signed,
                    'signed_at' => ((int)$signed === 1) ? date('Y-m-d H:i:s') : '0000-00-00 00:00:00',
                ]);
        }

        // 4) Recalcular estado del ÁREA:
        //    total_items: cantidad de ítems distintos (agrupado por item_id)
        //    total_done_any: ítems con (signed=1 OR owed_amount>0)
        //    total_signed_only: ítems con (signed=1)
        $condDept = $department_id
            ? " AND department_id = {$department_id} "
            : " AND department_name = " . $db->escape($department_name) . " ";

        $sub = "
        SELECT item_id,
               MAX(COALESCE(signed,0))      AS s,
               MAX(COALESCE(owed_amount,0)) AS ow
        FROM tbl_liberation_request_items
        WHERE request_id = {$request_id}
          AND status = 1
          {$condDept}
        GROUP BY item_id
    ";

        $agg = $db->query("
        SELECT
            COUNT(*) AS total_items,
            SUM(CASE WHEN (s = 1 OR ow > 0) THEN 1 ELSE 0 END) AS total_done_any,
            SUM(CASE WHEN (s = 1) THEN 1 ELSE 0 END)           AS total_signed_only
        FROM ({$sub}) x
    ")->getRow();

        $total_items       = (int)($agg->total_items ?? 0);
        $total_done_any    = (int)($agg->total_done_any ?? 0);
        $total_signed_only = (int)($agg->total_signed_only ?? 0);

        // Estado del área:
        // 0 hechos                   => PENDIENTE (sin firma)
        // 1..(n-1) hechos (por check o adeudo) => EN PROGRESO (asigna signed_by si no hay)
        // n firmados (todos con check)         => FIRMADO
        $dept_status = 'PENDIENTE';
        if ($total_items > 0) {
            if ($total_signed_only === $total_items) {
                $dept_status = 'FIRMADO';
            } elseif ($total_done_any > 0) {
                $dept_status = 'EN PROGRESO';
            } else {
                $dept_status = 'PENDIENTE';
            }
        }

        // 5) Actualiza registro del departamento (pone signed_by al primer avance)
        $deptTbl = $db->table('tbl_liberation_departments')->where('request_id', $request_id);
        if ($department_id) $deptTbl->where('department_id', $department_id);
        else                $deptTbl->where('department_name', $department_name);

        if ($dept_status === 'PENDIENTE') {
            $deptTbl->update([
                'request_status' => $dept_status,
                'signed_by'      => null,
                'signed_at'      => null
            ]);
        } else {
            $deptTbl->set('request_status', $dept_status)
                ->set('signed_by', "IF(signed_by IS NULL, {$currentUserId}, signed_by)", false)
                ->set('signed_at', "IF(signed_by IS NULL, NOW(), signed_at)", false)
                ->update();
        }

        // 6) Estado global + posible notificación final
        $newRequestStatus = $this->updateLiberationRequestStatus($request_id);

        /* return $this->response->setJSON([
        'success'        => true,
        'dept_status'    => $dept_status,
        'request_status' => $newRequestStatus,
    ]); */

        return $this->response->setJSON([
            'success'         => true,
            'dept_status'     => $dept_status,
            'request_status'  => $this->updateLiberationRequestStatus($request_id), // ya lo tienes
        ]);
    }

    public function updateItemSigned()
    {
        $data = $this->request->getPost();
        $request_item_id = $data['request_item_id'] ?? null;
        $signed          = isset($data['signed']) ? (int)$data['signed'] : null;
        $owed_amount_raw = $data['owed_amount'] ?? null;

        if ($request_item_id === null || ($signed === null && $owed_amount_raw === null)) {
            return $this->response->setJSON(['error' => 'Faltan parámetros']);
        }

        $db = \Config\Database::connect();
        $session = session();
        $currentUserId = (int)$session->get('id_user');

        // 1) Fila base
        $base = $db->table('tbl_liberation_request_items')
            ->select('id, request_id, item_id, department_id, department_name, signed, owed_amount')
            ->where('id', (int)$request_item_id)
            ->get()->getRow();

        if (!$base) {
            return $this->response->setJSON(['error' => 'Ítem no encontrado']);
        }

        $request_id      = (int)$base->request_id;
        $item_id         = (int)$base->item_id;
        $department_id   = $base->department_id ? (int)$base->department_id : null;
        $department_name = (string)($base->department_name ?? '');

        // 2) Normaliza adeudo y propaga a todas las copias del mismo item en el request
        if ($owed_amount_raw !== null) {
            $ow = (float)str_replace([',', ' '], ['', ''], (string)$owed_amount_raw);
            if ($ow < 0) $ow = 0;

            $db->table('tbl_liberation_request_items')
                ->where('request_id', $request_id)
                ->where('item_id', $item_id)
                ->where('status', 1)
                ->update(['owed_amount' => $ow]);
        }

        // 3) Si vino 'signed', propaga a todas las copias también
        if ($signed !== null) {
            // (opcional) bloquear firmar si hay adeudo > 0
            $maxOw = $db->table('tbl_liberation_request_items')
                ->select('MAX(owed_amount) AS ow')
                ->where('request_id', $request_id)
                ->where('item_id', $item_id)
                ->where('status', 1)
                ->get()->getRow();

            if ((int)$signed === 1 && (float)($maxOw->ow ?? 0) > 0) {
                return $this->response->setJSON(['error' => 'No se puede firmar un ítem con adeudo > 0']);
            }

            // actualizar solo esta fila
            $db->table('tbl_liberation_request_items')
                ->where('id', $request_item_id)
                ->update([
                    'signed'    => (int)$signed,
                    'signed_at' => ((int)$signed === 1) ? date('Y-m-d H:i:s') : '0000-00-00 00:00:00',
                ]);
        }

        // 4) Recalcular estado del área
        $condDept = $department_id
            ? " AND department_id = {$department_id} "
            : " AND department_name = " . $db->escape($department_name) . " ";

        $sub = "
        SELECT item_id,
               MAX(COALESCE(signed,0))      AS s,
               MAX(COALESCE(owed_amount,0)) AS ow
        FROM tbl_liberation_request_items
        WHERE request_id = {$request_id}
          AND status = 1
          {$condDept}
        GROUP BY item_id
    ";

        $agg = $db->query("
        SELECT
            COUNT(*) AS total_items,
            SUM(CASE WHEN (s = 1 OR ow > 0) THEN 1 ELSE 0 END) AS total_done_any,
            SUM(CASE WHEN (s = 1) THEN 1 ELSE 0 END)           AS total_signed_only
        FROM ({$sub}) x
    ")->getRow();

        $total_items       = (int)($agg->total_items ?? 0);
        $total_done_any    = (int)($agg->total_done_any ?? 0);
        $total_signed_only = (int)($agg->total_signed_only ?? 0);

        $dept_status = 'PENDIENTE';
        if ($total_items > 0) {
            if ($total_signed_only === $total_items) {
                $dept_status = 'FIRMADO';
            } elseif ($total_done_any > 0) {
                $dept_status = 'EN PROGRESO';
            }
        }

        // 5) Actualiza registro del departamento
        $deptTbl = $db->table('tbl_liberation_departments')->where('request_id', $request_id);
        if ($department_id) $deptTbl->where('department_id', $department_id);
        else                $deptTbl->where('department_name', $department_name);

        if ($dept_status === 'PENDIENTE') {
            $deptTbl->update([
                'request_status' => $dept_status,
                'signed_by'      => null,
                'signed_at'      => null
            ]);
        } else {
            $deptTbl->set('request_status', $dept_status)
                ->set('signed_by', "IF(signed_by IS NULL, {$currentUserId}, signed_by)", false)
                ->set('signed_at', "IF(signed_by IS NULL, NOW(), signed_at)", false)
                ->update();
        }

        // 6) Estado global del request
        $newRequestStatus = $this->updateLiberationRequestStatus($request_id);

        return $this->response->setJSON([
            'success'        => true,
            'dept_status'    => $dept_status,
            'request_status' => $newRequestStatus,
        ]);
    }




    public function notifyLiberacionCreated($id_request, $type)
    {
        $db = \Config\Database::connect();

        // Obtener información de la solicitud
        $solicitud = $db->table('tbl_liberation_request as lr')
            ->select('
                lr.*,
                u.name, 
                u.surname, 
                u.second_surname, 
                u.email as user_email, 
                u.payroll_number,
                u.id_user as employee_id,
                u.id_rol
            ')
            ->join('tbl_users u', 'u.id_user = lr.employee_id', 'left')
            ->where('lr.id', $id_request)
            ->get()
            ->getRow();

        if (!$solicitud) {
            return false;
        }

        // Obtener información del jefe directo
        $jefeDirecto = $db->table('tbl_assign_departments_to_managers_new mgr')
            ->select('
                mng.name, 
                mng.surname, 
                mng.second_surname, 
                mng.email
            ')
            ->join('tbl_users mng', 'mng.id_user = mgr.id_manager', 'left')
            ->where('mgr.payroll_number', $solicitud->payroll_number)
            ->get()
            ->getRow();

        // Arreglo de datos para la vista
        $dataEmail = [
            'solicitud'   => $solicitud,
            'jefeDirecto' => $jefeDirecto,
        ];
        $jefeEmail = $jefeDirecto->email;

        // $cc = [];


        $cc = []; // empezamos limpio

        // CC según rol del empleado
        if ((int)$solicitud->id_rol === 2) {
            // Sindicalizado => notificar a Florida
            $cc = [
                //Herramientas
                'gvelazquez@walworth.com.mx',
                'aserna@walworth.com.mx',
                //  HSE
                'ldominguez@walworth.com.mx',
                //  Soporte técnico e infraestructura 
                'alanda@walworth.com.mx',
                'ggarcia@walworth.com.mx',
                // Contabilidad y Servicios generales 
                'dprado@walworth.com.mx',
                'gmendoza@walworth.com.mx',
                // Mercadotecnia 
                'hmgarcia@grupowalworth.com',
                'dnavarrete@walworth.com.mx',
                //Metrología
                'lserrano@walworth.com.mx',
                'stlatelpa@walworth.com.mx',
                // Caja De Ahorros Sindicalizados
                '5537487908edu@gmail.com',

                // Caja De Ahorros No Sindicalizados
                'eolanda@walworth.com.mx',
                'gmartinez@walworth.com.mx',

                //Jefe Directo
                //Viene del dataemail

                // Gestión de talento
                'krubio@walworth.com.mx',
                'bmartinez@walworth.com.mx',
                //Vigilancia
                'vigilancia@walworth.com.mx',
                // Servicio Médico
                'kmartinez@walworth.com.mx',
            ];
        } else {
            // No sindicalizado => notificar a Elda, Guadalupe, Alejandra
            $cc = [
                //Herramientas
                'gvelazquez@walworth.com.mx',
                'aserna@walworth.com.mx',
                //  HSE
                'ldominguez@walworth.com.mx',
                //  Soporte técnico e infraestructura 
                'alanda@walworth.com.mx',
                'ggarcia@walworth.com.mx',
                'rcruz@walworth.com.mx',
                // Contabilidad y Servicios generales 
                'dprado@walworth.com.mx',
                'gmendoza@walworth.com.mx',
                // Mercadotecnia 
                'hmgarcia@grupowalworth.com',
                'dnavarrete@walworth.com.mx',
                //Metrología
                'lserrano@walworth.com.mx',

                // Caja De Ahorros No Sindicalizados
                'eolanda@walworth.com.mx',
                'gmartinez@walworth.com.mx',

                //Jefe Directo
                //Viene del dataemail

                // Gestión de talento
                'krubio@walworth.com.mx',
                'bmartinez@walworth.com.mx',
                //Vigilancia
                'vigilancia@walworth.com.mx',
                // Servicio Médico
                'kmartinez@walworth.com.mx',
            ];
        }

        if (strtoupper($type) === 'NEW') {
            $template = 'liberation/notify_liberacion';
            $subject  = 'Nueva Solicitud de Liberación';
        } else { // COMPLETE
            $template = 'liberation/notify_liberacion_complete';
            $subject  = 'Solicitud de Liberación Completada';
        }

        // Enviar: usamos jefe directo como TO (si quieres mantenerlo)
        return $this->sendLiberationEmail($dataEmail, $template, $subject, $cc, $jefeEmail);
    }

    public function updatePhone()
    {
        $idSolicitud = $this->request->getPost('id_solicitud');
        $telefono = $this->request->getPost('telefono');

        if (!$idSolicitud || !$telefono) {
            return $this->response->setJSON(['error' => 'Faltan parámetros']);
        }

        $db = \Config\Database::connect();

        $updated = $db->table('tbl_liberation_request')
            ->where('id', (int)$idSolicitud)
            ->update(['phone_number' => $telefono]);

        if ($updated) {
            return $this->response->setJSON(['ok' => true, 'message' => 'Teléfono actualizado']);
        } else {
            return $this->response->setJSON(['error' => 'No se pudo actualizar el teléfono']);
        }
    }


    private function sendLiberationEmail($dataEmail, $template, $subject, $cc = [], $jefeEmail = null)
    {
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->isSMTP();
        $mail->SMTPAuth = false;
        $mail->Host = 'localhost';
        $mail->Port = 25;

        $mail->setFrom('notificacion@walworth.com', 'Liberaciones');
        $mail->Subject = $subject;

        // Destinatario principal
        $mail->addAddress($jefeEmail, 'Jefe Directo');
        //$mail->addAddress('rcruz@walworth.com.mx', 'Jefe Directo');

        // CC opcionales
        foreach ($cc as $c) {
            $mail->addCC($c);
        }

        //  $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');

        // Contenido HTML
        $mail->isHTML(true);
        $email_template = view($template, $dataEmail);
        $mail->MsgHTML($email_template);

        // Enviar
        try {
            $mail->send();
            log_message('info', "Correo enviado correctamente a Rafael para solicitud ID");
            return [
                'success' => true,
                'error_info' => null
            ];
        } catch (Exception $e) {
            log_message('error', "Error enviando correo para solicitud ID- Error: {$mail->ErrorInfo}");
            return [
                'success' => false,
                'error_info' => $mail->ErrorInfo
            ];
        }
    }

    //Test vista email
    public function pruebasNotify()
    {
        if (!$this->is_logged) {
            return redirect()->to(site_url());
        }

        $id_request = 22; // Hardcodeamos para pruebas

        $db = \Config\Database::connect();

        // Obtener información de la solicitud
        $solicitud = $db->table('tbl_liberation_request as lr')
            ->select('
                lr.*,
                u.name, 
                u.surname, 
                u.second_surname, 
                u.email as user_email, 
                u.payroll_number,
                u.id_user as employee_id
            ')
            ->join('tbl_users u', 'u.id_user = lr.employee_id', 'left')
            ->where('lr.id', $id_request)
            ->get()
            ->getRow();

        if (!$solicitud) {
            return redirect()->back()->with('error', 'Solicitud no encontrada.');
        }

        // Obtener información del jefe directo
        $jefeDirecto = $db->table('tbl_assign_departments_to_managers_new mgr')
            ->select('
                mng.name, 
                mng.surname, 
                mng.second_surname, 
                mng.email
            ')
            ->join('tbl_users mng', 'mng.id_user = mgr.id_manager', 'left')
            ->where('mgr.payroll_number', $solicitud->payroll_number)
            ->get()
            ->getRow();

        // Pasar los datos a la vista
        return view('liberation/notify_liberacion', [
            'solicitud'   => $solicitud,
            'jefeDirecto' => $jefeDirecto
        ]);
    }

    public function pruebasNotifyComplete()
    {
        if (!$this->is_logged) {
            return redirect()->to(site_url());
        }

        $id_request = 22; // Hardcodeamos para pruebas

        $db = \Config\Database::connect();

        // Obtener información de la solicitud
        $solicitud = $db->table('tbl_liberation_request as lr')
            ->select('
                lr.*,
                u.name, 
                u.surname, 
                u.second_surname, 
                u.email as user_email, 
                u.payroll_number,
                u.id_user as employee_id
            ')
            ->join('tbl_users u', 'u.id_user = lr.employee_id', 'left')
            ->where('lr.id', $id_request)
            ->get()
            ->getRow();

        if (!$solicitud) {
            return redirect()->back()->with('error', 'Solicitud no encontrada.');
        }

        // Obtener información del jefe directo
        $jefeDirecto = $db->table('tbl_assign_departments_to_managers_new mgr')
            ->select('
                mng.name, 
                mng.surname, 
                mng.second_surname, 
                mng.email
            ')
            ->join('tbl_users mng', 'mng.id_user = mgr.id_manager', 'left')
            ->where('mgr.payroll_number', $solicitud->payroll_number)
            ->get()
            ->getRow();

        // Pasar los datos a la vista
        return view('liberation/notify_liberacion_complete', [
            'solicitud'   => $solicitud,
            'jefeDirecto' => $jefeDirecto
        ]);
    }

    public function insertRequestLiberationMassive()
    {
        $guieFile = $this->request->getFile("archivo");

        $document = IOFactory::load($guieFile);
        $sheet = $document->getActiveSheet();
        $Rows = $sheet->getHighestDataRow();

        $errors = [];
        $successCount = 0;

        $session = session();
        $createdBy = $session->get('id_user');

        $db = \Config\Database::connect();

        $usuarios = $db->table('tbl_users')
            ->select("CONCAT(name, ' ', surname, ' ', second_surname) AS employee, payroll_number, id_user,id_rol")
            ->get()
            ->getResult();

        $usuariosMap = [];
        foreach ($usuarios as $u) {
            $usuariosMap[$u->payroll_number] = [
                'id_user' => $u->id_user,
                'id_rol'  => $u->id_rol,
                'employee' => $u->employee
            ];
        }

        for ($iRow = 2; $iRow <= $Rows; $iRow++) {
            $payrollNumber = $sheet->getCellByColumnAndRow(1, $iRow)->getValue();
            $notificar     = $sheet->getCellByColumnAndRow(2, $iRow)->getValue();
            $tipo_nomina   = $sheet->getCellByColumnAndRow(3, $iRow)->getValue();
            $periodo       = $sheet->getCellByColumnAndRow(4, $iRow)->getValue();
            $tel           = $sheet->getCellByColumnAndRow(5, $iRow)->getValue();
            $company       = $sheet->getCellByColumnAndRow(6, $iRow)->getValue();

            if (empty($payrollNumber)) {
                $errors[] = "Fila $iRow: Payroll Number vacío.";
                continue;
            }

            if (!isset($usuariosMap[$payrollNumber])) {
                $errors[] = "Fila $iRow: No se encontró usuario con nómina $payrollNumber.";
                continue;
            }

            $id_user = $usuariosMap[$payrollNumber]['id_user'];
            $id_rol = $usuariosMap[$payrollNumber]['id_rol'];
            $employee = $usuariosMap[$payrollNumber]['employee'];

            // Crear la solicitud de liberación
            $result = $this->createLiberationRequestLogic($id_user, $createdBy, $tipo_nomina, $periodo, $tel, $id_rol, $company, $employee);


            if (isset($result['error'])) {
                $errors[] = "Fila $iRow: " . $result['error'];
            } else {
                $successCount++;
                $valorNotificar = strtoupper(trim($notificar));
                if (in_array($valorNotificar, ['VERDADERO', 'SI', '1', 'TRUE'])) {
                    $this->notifyLiberacionCreated($result['request_id'], 'NEW');
                }
            }
        }

        return $this->response->setJSON([
            'successCount' => $successCount,
            'errors' => $errors,
        ]);
    }





    public function updateLiberationRequestStatusANT($request_id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1) Recalcular estatus por departamento a partir de items
        $sql = "
                    SELECT department_name,
                        COUNT(*) AS total_items,
                        SUM(signed) AS total_signed,
                        CASE
                            WHEN COUNT(*) = 0 OR SUM(signed) = 0 THEN 'PENDIENTE'
                            WHEN SUM(signed) = COUNT(*) THEN 'FIRMADO'
                            ELSE 'EN PROGRESO'
                        END AS dept_status
                    FROM tbl_liberation_request_items
                    WHERE request_id = ?
                    GROUP BY department_name
                ";
        $departments = $db->query($sql, [$request_id])->getResult();

        // Actualizar fila por departamento (y firma del área solo cuando quede completo)
        $session = session();
        $currentUserId = $session->get('id_user');

        foreach ($departments as $dept) {
            $updateDept = ['request_status' => $dept->dept_status];

            if ($dept->dept_status === 'FIRMADO') {
                // si quedó completo, quien guardó el último ítem queda como firmante del área
                // BUENO: solo firma si está vacío (atómico, evita carreras)
                $db->table('tbl_liberation_departments')
                    ->set('request_status', $dept->dept_status)
                    ->set('signed_by', "IF(signed_by IS NULL, {$currentUserId}, signed_by)", false)
                    ->set('signed_at', "IF(signed_by IS NULL, NOW(), signed_at)", false)
                    ->where('request_id', $request_id)
                    ->where('department_name', $dept->department_name) // mientras migras a ID
                    ->update();
            } else {
                // si ya no está completo, limpiamos firma del área
                $updateDept['signed_by'] = null;
                $updateDept['signed_at'] = null;
            }

            $db->table('tbl_liberation_departments')
                ->where('request_id', $request_id)
                ->where('department_name', $dept->department_name)
                ->update($updateDept);
        }

        // 2) Calcular estatus global NUEVO
        $statuses = array_map(fn($d) => $d->dept_status, $departments);
        if (count($statuses) === 0) {
            $newRequestStatus = 'PENDIENTE';
        } elseif (count(array_unique($statuses)) === 1 && $statuses[0] === 'FIRMADO') {
            $newRequestStatus = 'FIRMADO';
        } elseif (count(array_unique($statuses)) === 1 && $statuses[0] === 'PENDIENTE') {
            $newRequestStatus = 'PENDIENTE';
        } else {
            $newRequestStatus = 'EN PROGRESO';
        }

        // 3) Leer estatus ANTERIOR para evitar notificaciones duplicadas
        $prev = $db->table('tbl_liberation_request')
            ->select('request_status')
            ->where('id', $request_id)
            ->get()
            ->getRow();

        $prevStatus = $prev ? $prev->request_status : null;

        // 4) Guardar nuevo estatus global
        $db->table('tbl_liberation_request')
            ->where('id', $request_id)
            ->update(['request_status' => $newRequestStatus]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return 'ERROR';
        }

        // 5) Notificar SOLO si hubo transición real a FIRMADO
        if ($newRequestStatus === 'FIRMADO') {
            // respeta tu lógica de destinatarios por rol dentro de notifyLiberacionCreated
            $this->notifyLiberacionCreated($request_id, 'COMPLETE');
        }

        return $newRequestStatus;
    }
    private function getUserRoleId(int $userId): ?int
    {
        $db = \Config\Database::connect();
        $row = $db->table('tbl_users')
            ->select('id_rol')
            ->where('id_user', $userId)
            ->get()->getRow();
        return $row ? (int)$row->id_rol : null;
    }

    private function getRequestTargetUserIdANT(int $requestId): ?int
    {
        $db = \Config\Database::connect();
        // ajusta el campo si tu tabla usa otro nombre (ej. id_user_solicitante)
        $row = $db->table('tbl_liberation_request')
            ->select('id_user')
            ->where('id', $requestId)
            ->get()->getRow();
        return $row ? (int)$row->id_user : null;
    }

    private function getRequestTargetUserId(int $requestId): ?int
    {
        $db = \Config\Database::connect();

        // Preferente: employee_id (coincide con tu INSERT)
        try {
            $row = $db->table('tbl_liberation_request')
                ->select('employee_id')
                ->where('id', $requestId)
                ->get()->getRow();
            if ($row && isset($row->employee_id)) {
                return (int)$row->employee_id;
            }
        } catch (\Throwable $e) {
            // ignorar y probar fallback
        }

        // Fallback: id_user (por compatibilidad con versiones anteriores)
        try {
            $row = $db->table('tbl_liberation_request')
                ->select('id_user')
                ->where('id', $requestId)
                ->get()->getRow();
            if ($row && isset($row->id_user)) {
                return (int)$row->id_user;
            }
        } catch (\Throwable $e) {
            // ignorar
        }

        return null;
    }


    private function getAhorrosDeptNameByRole(?int $roleId): string
    {
        // Regla de negocio:
        // id_rol = 2  => "Caja de Ahorros Sindicalizados"
        // id_rol != 2 => "Caja de Ahorros No Sindicalizados"
        return ($roleId === 2)
            ? 'Caja de Ahorros Sindicalizados'
            : 'Caja de Ahorros No Sindicalizados';
    }



    private function updateLiberationRequestStatusBueno(int $request_id): void
    {
        $db = \Config\Database::connect();

        // --- Depuración de "Caja" no aplicable según rol del usuario del request ---
        $targetUserId   = $this->getRequestTargetUserId($request_id);
        $roleId         = $this->getUserRoleId($targetUserId ?? 0);
        $wantedDeptName = $this->getAhorrosDeptNameByRole($roleId);
        $bothAhorros    = ['Caja de Ahorros Sindicalizados', 'Caja de Ahorros No Sindicalizados'];

        // Elimina cualquier "Caja" que no sea la válida para este request
        $db->table('tbl_liberation_request_items')
            ->where('request_id', $request_id)
            ->whereIn('department_name', $bothAhorros)
            ->where('department_name <>', $wantedDeptName)
            ->delete();

        $db->table('tbl_liberation_departments')
            ->where('request_id', $request_id)
            ->whereIn('department_name', $bothAhorros)
            ->where('department_name <>', $wantedDeptName)
            ->delete();

        // --- Cálculo de estado global (solo con áreas aplicables existentes) ---
        $tot = $db->table('tbl_liberation_departments')
            ->select('COUNT(*) AS total, SUM(request_status = "FIRMADO") AS done')
            ->where('request_id', $request_id)
            ->get()->getRow();

        $reqStatus = 'PENDIENTE';
        if ($tot && (int)$tot->total > 0) {
            if ((int)$tot->done === (int)$tot->total) {
                $reqStatus = 'FIRMADO';
            } elseif ((int)$tot->done > 0) {
                $reqStatus = 'EN PROGRESO';
            }
        }

        $db->table('tbl_liberation_request')
            ->where('id', $request_id)
            ->update(['request_status' => $reqStatus]);
    }

    public function updateLiberationRequestStatus(int $requestId): string
    {
        $db = \Config\Database::connect();

        $items = $db->table('tbl_liberation_request_items')
            ->select('signed, owed_amount')
            ->where('request_id', $requestId)
            ->where('status', 1)
            ->get()->getResultArray();

        $allSigned = true;
        $anySigned = false;

        foreach ($items as $it) {
            $entregado = ((int)$it['signed'] === 1);
            $adeudo    = ((float)$it['owed_amount'] > 0);

            if ($entregado || $adeudo) {
                $anySigned = true;
            } else {
                $allSigned = false;
            }
        }

        if ($allSigned) {
            $nuevoEstado = 'FIRMADO';
        } elseif ($anySigned) {
            $nuevoEstado = 'EN PROGRESO';
        } else {
            $nuevoEstado = 'PENDIENTE';
        }

        // guardar en la tabla principal
        $db->table('tbl_liberation_request')
            ->where('id', $requestId)
            ->update(['request_status' => $nuevoEstado]);

        return $nuevoEstado;
    }



    public function pdfLiberation($request_id = null)
    {
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $JEFE_AREA = 'Jefe de área';

        $departmentOrder = [
            'Almacen de Herramientas',
            'HSE',
            'Soporte técnico e infraestructura',
            'Servicios generales',
            'Contabilidad',
            'Mercadotecnia',
            'Metrología',
            'Caja de Ahorros No Sindicalizados',
            'Caja de Ahorros Sindicalizados',
            $JEFE_AREA,
            'Gestión de talento',
            'Vigilancia',
            'Servicio Médico'
        ];

        // 1) Obtener request + empleado + jefe directo
        $query = $this->db->query("
        SELECT 
            CONCAT(emp.name, ' ', emp.surname, ' ', emp.second_surname) AS employee_full_name,
            emp.email,
            emp.payroll_number,
            emp.id_rol,
            cc.name_company,
            d.departament AS employee_department,
            lr.request_status,
            lr.phone_number,
            lr.id AS request_id,
            lr.created_at AS date,
            CONCAT(mng.name, ' ', IFNULL(mng.surname,''), ' ', IFNULL(mng.second_surname,'')) AS manager_full_name,
            mng.email AS manager_email,
            mng.id_user AS manager_id
        FROM tbl_liberation_request AS lr
        LEFT JOIN tbl_users AS emp  ON lr.employee_id = emp.id_user
        LEFT JOIN cat_company AS cc ON emp.company = cc.id
        LEFT JOIN cat_departament AS d ON emp.id_departament = d.id_depto AND d.active_status = 1
        LEFT JOIN tbl_assign_departments_to_managers_new AS mgr ON mgr.payroll_number = emp.payroll_number
        LEFT JOIN tbl_users AS mng ON mng.id_user = mgr.id_manager
        WHERE MD5(CONCAT('" . $key . "', lr.id)) = '" . $request_id . "'");
        $dataRequest = $query->getRow();
        if (!$dataRequest) {
            throw new \Exception("Solicitud no encontrada o clave inválida.");
        }

        // 2) Firmantes por área desde tbl_liberation_departments
        $deptRows = $this->db->table('tbl_liberation_departments')
            ->select('department_name, signed_by, request_status')
            ->where('request_id', (int)$dataRequest->request_id)
            ->where('status', 1)
            ->get()->getResultArray();

        $deptSigners = [];
        foreach ($deptRows as $r) {
            if (!empty($r['department_name'])) {
                $deptSigners[$r['department_name']] = $r['signed_by']; // puede ser null
            }
        }

        // 3) Ítems agrupados por catálogo (evita duplicados) + adeudo
        $itemsQ = $this->db->query("
        SELECT 
            ci.id                            AS item_id,
            ci.name                          AS item_name,
            lri.department_name              AS dept_name,
            MAX(COALESCE(lri.signed,0))      AS signed,
            MAX(COALESCE(lri.owed_amount,0)) AS owed_amount
        FROM tbl_liberation_request_items AS lri
        INNER JOIN cat_liberation_items   AS ci ON ci.id = lri.item_id
        WHERE lri.request_id = " . (int)$dataRequest->request_id . " 
          AND lri.status = 1
        GROUP BY ci.id, lri.department_name, ci.name
        ORDER BY lri.department_name, ci.id");
        $rows = $itemsQ->getResultArray();

        // 4) Armar estructura por departamento con campos separados
        $departments = [];
        foreach ($rows as $row) {
            $dept = $row['dept_name'] ?: $JEFE_AREA;

            // 🔒 Seguridad extra: no permitir repetir key
            if (!array_key_exists($dept, $departments)) {
                $signedBy = $deptSigners[$dept] ?? ($dept === $JEFE_AREA ? ($dataRequest->manager_id ?? null) : null);
                $departments[$dept] = [
                    'signed_by'  => $signedBy,  // id de usuario (o null)
                    'items'      => [],
                    'all_signed' => false       // se calcula abajo
                ];
            }

            $entregado = ((int)$row['signed'] === 1);
            $adeudo    = (float)$row['owed_amount'];
            $cumplido  = $entregado || ($adeudo > 0);

            $departments[$dept]['items'][] = [
                'name'      => $row['item_name'],
                'entregado' => $entregado,
                'adeudo'    => $adeudo,
                'cumplido'  => $cumplido
            ];
        }

        // === cálculo por departamento ===
        foreach ($departments as $deptName => &$deptData) {
            $items = $deptData['items'];

            $allSigned = true;
            foreach ($items as $it) {
                if (empty($it['cumplido'])) {
                    $allSigned = false;
                    break;
                }
            }

            $deptData['all_signed'] = $allSigned;
        }
        unset($deptData);

        // 5) Regla Sindicalizado/No Sindicalizado
        $deptSind = 'Caja de Ahorros Sindicalizados';
        $deptNo   = 'Caja de Ahorros No Sindicalizados';
        if ((int)$dataRequest->id_rol === 2) {
            unset($departments[$deptNo]);
        } else {
            unset($departments[$deptSind]);
        }

        // 6) Reordenar de acuerdo a $departmentOrder
        $orderedDepartments = [];
        foreach ($departmentOrder as $dName) {
            if (isset($departments[$dName])) {
                $orderedDepartments[$dName] = $departments[$dName];
                unset($departments[$dName]);
            }
        }
        // Cualquier otro depto no listado va al final
        foreach ($departments as $dName => $dData) {
            $orderedDepartments[$dName] = $dData;
        }

        // 6.1) Comentarios (último por departamento)
        $comentariosRows = $this->db->table('tbl_liberation_comment')
            ->select('department_name, comentario_html,')
            ->where('request_id', (int)$dataRequest->request_id)
            ->orderBy('department_name', 'ASC')
            ->orderBy('created_at', 'DESC') // para quedarnos con el último
            ->get()->getResultArray();

        // Quedarse con el último por departamento
        /*       $comentariosDept = [];
        foreach ($comentariosRows as $c) {
            $d = $c['department_name'] ?: 'General';
            if (!isset($comentariosDept[$d])) {
                $comentariosDept[$d] = $c;
            }
        } */

        // En vez de quedarte con solo el último, agrupa todos
        $comentariosDept = [];
        foreach ($comentariosRows as $c) {
            $d = $c['department_name'] ?: 'General';
            $comentariosDept[$d][] = $c;
        }

        // ¿Hay al menos un comentario con contenido real?
     
        $hayComentarios = (empty($comentariosRows)) ? false : true;

echo $hayComentarios;
        // 7) Pasar datos a la vista
        $data = [
            'request'     => $dataRequest,
            'departments' => $orderedDepartments,
            'comentarios'  => $comentariosDept,   //nuevo
            'hayComentarios' => $hayComentarios, //pásalo a la vista
        ];

        // 8) Generar PDF
        $html2 = view('liberation/pdf_liberation', $data);
        $html = ob_get_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Solicitud de liberación');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('liberation_' . $dataRequest->employee_full_name . '.pdf', 'I');
    }




    public function buscarUsuario()
    {
        $textoUsuario = trim($this->request->getGet('usuario') ?? '');
        $numeroNomina = trim($this->request->getGet('num_nomina') ?? '');

        $bd = \Config\Database::connect();

        $consulta = $bd->table('tbl_users u')
            ->select("
			u.id_user,
			CONCAT(u.name, ' ', IFNULL(u.surname, ''), ' ', IFNULL(u.second_surname, '')) AS full_name,
			u.payroll_number,
			d.id_depto AS department_id,
			d.departament AS department,
			CONCAT(mng.name, ' ', IFNULL(mng.surname, ''), ' ', IFNULL(mng.second_surname, '')) AS direct_manager,
			CASE WHEN eq.id_user IS NOT NULL THEN 'Si' ELSE 'No' END AS equip_asigned
		")
            ->join('cat_departament d', 'u.id_departament = d.id_depto', 'left')
            ->join('tbl_assign_departments_to_managers_new mgr', 'mgr.payroll_number = u.payroll_number', 'left')
            ->join('tbl_users mng', 'mng.id_user = mgr.id_manager', 'left')
            ->join('tbl_system_equip_assignment eq', 'eq.id_user = u.id_user AND eq.active_status = 1', 'left')
            ->where('u.active_status', 1);

        if ($numeroNomina !== '') {
            $consulta->where('u.payroll_number', (int) $numeroNomina);
        }

        if ($textoUsuario !== '') {
            $consulta->groupStart()
                ->like('u.name', $textoUsuario)
                ->orLike('u.surname', $textoUsuario)
                ->orLike('u.second_surname', $textoUsuario)
                ->orLike("CONCAT_WS(' ', u.name, u.surname, u.second_surname)", $textoUsuario)
                ->groupEnd();
        }

        $consulta->groupBy('u.id_user');

        $filas = $consulta->limit(10)->get()->getResultArray();

        return $this->response->setJSON([
            'ok'   => true,
            'data' => $filas,
        ]);
    }

    public function saveComment()
    {
        $id   = $this->request->getPost('request_id');
        $area = $this->request->getPost('department_name');
        $html = trim((string)$this->request->getPost('comentario_html'));

        if (!$id || !$area || $html === '') {
            return $this->response->setJSON(['error' => 'Parámetros incompletos'])->setStatusCode(400);
        }

        // ⚠️ Sanitiza el HTML permitido (recomendado usar HTML Purifier o similar)
        // $html = service('htmlpurifier')->purify($html);

        $modelo = new LiberationCommentModel();

        $ok = $modelo->insert([
            'request_id'      => $id,
            'department_name' => $area,
            'comentario_html' => $html,
            'id_user'      => session()->id_user ?? null,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        if (!$ok) {
            return $this->response->setJSON(['error' => 'No se pudo guardar'])->setStatusCode(500);
        }

        return $this->response->setJSON(['success' => true]);
    }
}
