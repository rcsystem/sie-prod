<?php

/**
 * MODULO DE SERVICIO MEDICO
 * @version 1.1 pre-prod
 * @author  Horus Samael Rivas Pedraza <horus.riv.ped@gmail.com>
 * @telefono 56-24-39-26-32
 */

namespace App\Controllers\Medical;

use DateTime;
use App\Controllers\BaseController;
use App\Models\PermissionsModel;
use App\Models\MedicalRequestModel;
use App\Models\MedicalConsultationRequestModel;
use App\Models\MedicalMedicineInvetoryModel;
use App\Models\MedicalConsultationItemsRequestModel;
use App\Models\PermissionsInasistenceModel;
use App\Models\MedicalExamRequestModel;
use App\Models\MedicalExamVisualModel;
use App\Models\MedicalExamDxModel;

use App\Models\VacationsModel;
use App\Models\VacationsItemsModel;

use CodeIgniter\I18n\Time;
use Spipu\Html2Pdf\Html2Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Medical extends BaseController
{

    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->permissionsModel = new PermissionsModel();
        $this->medicalRequest = new MedicalRequestModel();
        $this->medicalConsultation = new MedicalConsultationRequestModel();
        $this->medicalItems = new MedicalConsultationItemsRequestModel();
        $this->medicaments = new MedicalMedicineInvetoryModel();
        $this->examRequest = new MedicalExamRequestModel();
        $this->examVisual = new MedicalExamVisualModel();
        $this->examDx = new MedicalExamDxModel();
        $this->vacationModel = new VacationsModel();
        $this->vacationItemsModel = new VacationsItemsModel();
        $this->permissionsInasistenceModel = new PermissionsInasistenceModel();
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function index()
    {
        // return ($this->is_logged) ? view('medical/medical_index') : redirect()->to(site_url());
        return ($this->is_logged) ? view('medical/medical_inability') : redirect()->to(site_url());
    }

    public function viewRequestAll()
    {
        return ($this->is_logged) ? view('medical/medical_request_all') : redirect()->to(site_url());
    }

    public function consultation()
    {
        $query = $this->db->query("SELECT * FROM cat_medical_type_of_injury WHERE active_status = 1;")->getResult();
        $query1 = $this->db->query("SELECT * FROM cat_medical_procedures WHERE active_status = 1;")->getResult();
        $query2 = $this->db->query("SELECT * FROM cat_medical_anatomical_area WHERE active_status = 1;")->getResult();
        $query3 = $this->db->query("SELECT * FROM cat_medical_classification WHERE active_status = 1;")->getResult();
        $query4 = $this->db->query("SELECT * FROM cat_medical_system WHERE active_status = 1;")->getResult();
        $data = ['type_of_injury' => $query, 'procedures' => $query1, 'anatomical_area' => $query2, 'classification' => $query3, 'system' => $query4];
        return ($this->is_logged) ? view('medical/medical_consultation', $data) : redirect()->to(site_url());
    }

    public function viewMedicalExam()
    {
        $query4 = $this->db->query("SELECT * FROM cat_medical_system WHERE active_status = 1;")->getResult();
        $data = ['system' => $query4];
        return ($this->is_logged) ? view('medical/medical_exam', $data) : redirect()->to(site_url());
    }

    public function viewInventoriMedical()
    {
        $query = $this->db->query("SELECT id_presentation AS id, presentation FROM cat_medical_presentation WHERE active_status = 1")->getResult();
        $data = ["medicament" => $query];
        return ($this->is_logged) ? view('medical/medical_inventory_medicament', $data) : redirect()->to(site_url());
    }

    public function viewReports()
    {

        $groups = $this->db->table('cat_departament')->select('id_depto, departament')->get()->getResult();
        $query = $this->db->query("SELECT * FROM cat_medical_classification WHERE active_status = 1;")->getResult();
        $query1 = $this->db->query("SELECT * FROM cat_medical_system WHERE active_status = 1;")->getResult();
        $query2 = $this->db->query("SELECT id, name_turn FROM cat_turns WHERE active_status = 1 AND type_of_employee = 1")->getResult();
        $query3 = $this->db->query("SELECT id, name_turn FROM cat_turns WHERE active_status = 1 AND type_of_employee = 2")->getResult();
        $data = ['depto' => $groups, 'classification' => $query, 'system' => $query1, 'turnoS' => $query3, 'turnoA' => $query2];
        return ($this->is_logged) ? view('medical/medical_reports', $data) : redirect()->to(site_url());
    }

    public function generateRequest()
    {
        try {



            $userGenerathor = session()->id_user;
            $nameUserGenerathor = session()->name . ' ' . session()->surname . ' ' . session()->second_surname;
            $id_user = $this->request->getPost("id_user");
            $name = $this->request->getPost("nombre");
            $positionJob = $this->request->getPost("puesto");
            $depto = $this->request->getPost("depto");
            $IDdepto = $this->request->getPost("id_depto");
            $cost = $this->request->getPost("c_costos");
            $typeEmplyoment = $this->request->getPost("tipo_empleado");
            $payroll = $this->request->getPost("nomina");
            $salary = $this->request->getPost("sueldo");
            $IdTypePermiss = $this->request->getPost("tipo_permiso");
            $motive = $this->request->getPost("motivo");
            $dateOut = $this->request->getPost("fecha_salida");
            $timeOut = $this->request->getPost("hora_salida");
            $inasistencia_inicio_bruto = $this->request->getPost("fecha_inicio");
            $system = $this->request->getPost("sistemas");
            $otherSystem = $this->request->getPost("otro_sistema");
            $diagnostic = $this->request->getPost("diagnostico");
            $obs = $this->request->getPost("observaciones");
            $idTurn = $this->request->getPost('turno');
            $dateEntry = $this->request->getPost("fecha_entrada");
            $timeEntry = $this->request->getPost("hora_entrada");

            $tipo_permiso = "SERVICIO MEDICO";
            $id_tipo_permiso = 5;

            if ($motive == "Atención psicológica") {

                $tipo_permiso = "ATENCIÓN PSICOLÓGICA";
                $id_tipo_permiso = 8;
            }


            $turnQuery = $this->db->query("SELECT name_turn FROM cat_turns WHERE id = $idTurn")->getRow();
            $typePermiss = ["error", "Salida A Cuenta  de Vacaciones", "Permiso Otorgado por la Empresa", "Pago de tiempo", "Falta justificada", "Home Office", "A Cuenta de Vacaciones"];

            $h = null;
            $i = null;
            $dateStar = "0000-00-00";
            $dateEnd = "0000-00-00";
            if ($timeOut != "") {
                $outSql = (date('w', strtotime($timeOut)) == 6) ? "hour_out_saturday" : "hour_out"; // selecciona que hora de Salida taera dependiendo si el permiso es en dia sabado o no
                $outHour = $this->db->query("SELECT $outSql AS h FROM cat_turns WHERE id = $idTurn")->getRow();
                $time1  = (strlen($outHour->h) <= 5) ? substr($outHour->h, 0, 5) . ':00' : $outHour->h;
                $time2  = (strlen($timeOut) <= 5) ? substr($timeOut, 0, 5) . ':00' : $timeOut;
                $datetime1 = DateTime::createFromFormat('H:i:s', $time1);
                $datetime2 = DateTime::createFromFormat('H:i:s', $time2);
                $interval = $datetime1->diff($datetime2);
                $h = $interval->h;
                $i = $interval->i;
            }

            if ($inasistencia_inicio_bruto != null) {
                $arrayInasistencias = explode(', ', $inasistencia_inicio_bruto);
                $dateStar = min($arrayInasistencias);
                $dateEnd = max($arrayInasistencias);
            }

            $this->db->transStart();

            $dataPermissions = [
                "id_user" => $id_user,
                "id_usuario_autoriza" => $userGenerathor,
                "user" => $name,
                "fecha_creacion" => date("Y-m-d"),
                "tipo_empleado" => $typeEmplyoment,
                "nombre_solicitante" => $name,
                'centro_costo' => $cost,
                "area_operativa" => $IDdepto,
                "departamento" => $depto,
                "num_nomina" => $payroll,
                "hora_salida" => ($timeOut != null) ? $timeOut  : "00:00:00",
                "fecha_salida" => ($dateOut != null) ? $dateOut  : "0000-00-00",
                "hora_entrada" => ($timeEntry != null) ? $timeEntry  : "00:00:00",
                "fecha_entrada" => ($dateEntry != null) ? $dateEntry  : "0000-00-00",
                "inasistencia_del" => $dateStar,
                "inasistencia_al" => $dateEnd,
                "goce_sueldo" => $salary,
                "observaciones" => $motive . '. ' . $obs,
                "id_turno" => $idTurn,
                "estatus" => "Autorizada",
                "id_tipo_permiso" => $id_tipo_permiso,
                "tipo_permiso" =>  $tipo_permiso,
                'turno_permiso' => $turnQuery->name_turn,
                "num_permiso_mes" => 0,
                'hora_permiso' => $h,
                'minuto_permiso' => $i,
            ];

            $this->permissionsModel->insert($dataPermissions);
            $id_es = $this->db->insertID();

            if ($inasistencia_inicio_bruto != '') {
                $arrayInasistencias = explode(', ', $inasistencia_inicio_bruto);
                for ($i = 0; $i < count($arrayInasistencias); $i++) {
                    $allowedFields = [
                        'id_es' => $id_es,
                        'id_user' => session()->id_user,
                        'inasistencia_fecha' => $arrayInasistencias[$i],
                        'estatus' => 1,
                    ];
                    $this->permissionsInasistenceModel->insert($allowedFields);
                }
            }

            if ($IdTypePermiss == 1) { // && $userGenerathor == 1063
                $admission = $this->db->query("SELECT date_admission, vacation_days_total,
                    vacation_days_total - 1 AS rest_vacation_days_total
                    FROM tbl_users 
                WHERE id_user = $id_user")->getRow();

                $dataVacation = [
                    'id_user' => $id_user,
                    'nombre_solicitante' => $name,
                    'fecha_registro' => date("Y-m-d H:i:s"),
                    'tipo_empleado' => strtolower($typeEmplyoment),
                    'id_a_cargo' => 0,
                    'a_cargo' => '',
                    'id_depto' => $IDdepto,
                    'departamento' => $depto,
                    'num_nomina' => $payroll,
                    'puesto' => $positionJob,
                    'fecha_ingreso' => $admission->date_admission,
                    'num_dias_a_disfrutar' => 1,
                    'regreso' => date('Y-m-d', strtotime("+1 Days", strtotime(date('Y-m-d')))),
                    'dias_restantes' => $admission->rest_vacation_days_total,
                    'user_authorizes' => $userGenerathor,
                    'estatus' => "Autorizada",
                ];

                $this->vacationModel->insert($dataVacation);
                $id_vcns = $this->db->insertID();

                $dateVacationItem = [
                    'id_vcns' => $id_vcns,
                    'id_user' => $id_user,
                    'id_depto' => $IDdepto,
                    'date_vacation' => date("Y-m-d"),
                    'status' =>  2,
                ];
                $this->vacationItemsModel->insert($dateVacationItem);

                $this->db->query("UPDATE tbl_users SET vacation_days_total = vacation_days_total - 1 WHERE id_user = $id_user");
            }

            $dataMedical = [
                'user_generate' => $nameUserGenerathor,
                'id_user_generate' => $userGenerathor,
                'user_name' => $name,
                'departament' => $depto,
                'position_job' => $positionJob,
                'type_permission' => $typePermiss[$IdTypePermiss],
                'motive' => $motive,
                'date_out' => ($dateOut != null) ? $dateOut  : "0000-00-00",
                'time_out' => ($timeOut != null) ? $timeOut  : "00:00:00",
                'date_star' => $dateStar,
                'date_end' => $dateEnd,
                'system' => $system,
                'other_system' => ($system == "OTRO") ? $otherSystem : '',
                'diagnostic' => $diagnostic,
                'obs' => $obs,
                'salary' => $salary,
                'created_at' => date("Y-m-d H:i:s"),
            ];
            $this->medicalRequest->insert($dataMedical);


            $dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user IN 
                (SELECT id_manager FROM tbl_assign_departments_to_managers_new WHERE payroll_number = $payroll)")->getRow();
            $email = $dataEmail->email;
            $title = $dataEmail->name . " " . $dataEmail->surname;

            $dir_email = changeEmail($email);
            // $dir_email = "hrivas@walworth.com.mx"; // solo Desarrollo
            $mail = new PHPMailer();
            $mail->CharSet = "UTF-8";

            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->isSMTP();
            $mail->SMTPAuth = false;
            $mail->Host = 'localhost';
            //$mail->Username = 'requisiciones@walworth.com';
            //$mail->Password = 'Walworth321$';
            $mail->Port = 25;
            $mail->setFrom('notificacion@walworth.com', 'Sistema de Permisos | Servicio Medico');
            $mail->addAddress($dir_email, $title);
            if (session()->id_user !=  1) {
                $mail->addAddress('eolanda@walworth.com.mx', 'ELDA OLANDA SALAZAR');
                // $mail->addAddress('elgarcia@walworth.com.mx', 'ELIZABETH GARCIA REAL JOYA');
                $mail->addAddress('gmartinez@walworth.com.mx', 'MARIA GUADALUPE MARTINEZ RIVERA');
            }
            $mail->addReplyTo('rruz@walworth.com.mx', 'Informacion del Sistema');
            $mail->addBCC('rcruz@walworth.com.mx');
            //$mail->addBCC('hrivas@walworth.com.mx');
            $mail->isHTML(true);
            $datas = ['notify' => $dataPermissions, 'puesto' => $positionJob, 'permiso' => $IdTypePermiss];
            $email_template = view('notificaciones/notify_medical_permisos', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Permisos y Vacaciones';
            $mail->send();

            $result = $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function generateConsultationRequest()
    {
        try {
            $userGenerathor = session()->id_user;
            $nameUserGenerathor = session()->name . ' ' . session()->surname . ' ' . session()->second_surname;
            $payroll_number = $this->request->getPost('nomina');
            $depto = $this->request->getPost('depto');
            $idDepto = $this->request->getPost('id_depto');
            $job = $this->request->getPost('puesto');
            $name = $this->request->getPost('nombre');
            $gender = $this->request->getPost('genero');
            $age = $this->request->getPost('edad');
            $lvl_schooling = $this->request->getPost('escolaridad');
            $manager_name = $this->request->getPost('supervisor');
            $specific_antiquity = $this->request->getPost('antiguedad');
            $general_antiquity = $this->request->getPost('antiguedad_general');
            $id_turn = $this->request->getPost('turno');
            $plant = $this->request->getPost('planta');
            $type_atention = $this->request->getPost('tipo_atencion');
            $id_procedures = $this->request->getPost('procedimientos');
            $id_system = $this->request->getPost('sistema');
            $id_classification = $this->request->getPost('clasificacion');
            $id_type_of_injury = $this->request->getPost('tipo_lesion');
            $id_anatomical_area = $this->request->getPost('anatomical_area');
            $allergies = trim($this->request->getPost('alergias'));
            $diagnosis = trim($this->request->getPost('diagnostico'));
            $next_appointment = $this->request->getPost('cita');
            $phone = $this->request->getPost('telefono');
            $commonMotive = $this->request->getPost('motivo_comun');
            $obs = trim($this->request->getPost('observaciones'));
            $product = $this->request->getPost('product_');

            $consultationData = [
                'id_user_attended' => $userGenerathor,
                'name_attended' => $nameUserGenerathor,
                'payroll_number' => $payroll_number,
                'id_depto' => $idDepto,
                'depto' => $depto,
                'job' => $job,
                'name' => $name,
                'gender' => $gender,
                'age' => $age,
                'lvl_schooling' => $lvl_schooling,
                'manager_name' => $manager_name,
                'specific_antiquity' => $specific_antiquity,
                'general_antiquity' => $general_antiquity,
                'turn' => $id_turn,
                'plant' => $plant,
                'type_atention' => $type_atention,
                'id_procedures' => $id_procedures,
                'id_system' => $id_system,
                'id_classification' => $id_classification,
                'id_type_of_injury' => $id_type_of_injury,
                'id_anatomical_area' => $id_anatomical_area,
                'allergies' => $allergies,
                'diagnosis' => $diagnosis,
                'next_appointment' => $next_appointment,
                'phone' => $phone,
                'common_motive' => $commonMotive,
                'obs' => $obs,
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s"),
            ];
            $insertConsult = $this->medicalConsultation->insert($consultationData);
            $id_request = $this->db->insertID();
            if ($insertConsult && $product != NULL) {
                $cantOrg = $this->request->getPost('cant_org_');
                $cantidad = $this->request->getPost('cantidad_');
                for ($i = 0; $i < count($product); $i++) {
                    $cantNew = intval($cantOrg[$i]) - intval($cantidad[$i]);
                    $itemData = [
                        'id_request' => $id_request,
                        'id_medicine' => $product[$i],
                        'previous_amount' => $cantOrg[$i],
                        'given_amount' => $cantidad[$i],
                        'created_at' => date("Y-m-d H:i:s"),
                    ];
                    $medicineData = [
                        'piezas' => $cantNew,
                    ];
                    $this->medicalItems->insert($itemData);
                    $this->medicaments->update($product[$i], $medicineData);
                }
            }
            return ($insertConsult) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function generateExam()
    {
        try {
            if ($this->is_logged != true) {
                return redirect()->to(site_url());
            }
            $idUser = $this->request->getPost("id_user");
            $payroll = $this->request->getPost("nomina");
            $name = $this->request->getPost("nombre");
            $idDepto = $this->request->getPost("id_depto");
            $depto = $this->request->getPost("depto");
            $job = $this->request->getPost("puesto");
            $typeEmploye = $this->request->getPost("tipo_empleado");
            $antRequest = $this->request->getPost("examen_ant");
            $antiquity = $this->request->getPost("antiguedad_general");
            $gender = $this->request->getPost("genero");
            $age = $this->request->getPost("edad");
            $school = $this->request->getPost("escolaridad");
            $civilStatus = $this->request->getPost("estado_civil");
            $exercise = $this->request->getPost("ejercicio");
            $smoking = $this->request->getPost("tabaquismo");
            $alcoholism = $this->request->getPost("alcoholismo");
            $drugAddiction = $this->request->getPost("toxicomanias");
            $health = $this->request->getPost("grado_salud");
            $motive = $this->request->getPost("motivo_comun");
            $icm = $this->request->getPost("imc");
            $has = $this->request->getPost("has");
            $dm = $this->request->getPost("dm");

            $dataExam = [
                'id_user_attended' => session()->id_user,
                'payroll_number' => $payroll,
                'name' => $name,
                'id_depto' => $idDepto,
                'depto' => $depto,
                'job' => $job,
                'type_employe' => $typeEmploye,
                'date_ant_request' => $antRequest,
                'antiquity' => $antiquity,
                'gender' => $gender,
                'age' => $age,
                'shool' => $school,
                'civil_status' => $civilStatus,
                'exercise' => $exercise,
                'smoking' => $smoking,
                'alcoholism' => $alcoholism,
                'drug_addiction' => $drugAddiction,
                'health' => $health,
                'motive' => $motive,
                'icm' => $icm,
                'has' => $has,
                'dm' => $dm,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->examRequest->insert($dataExam);
            $idRequest = $this->db->insertID();

            $visual_ = $this->request->getPost("visual_");
            for ($iV = 0; $iV < 4; $iV++) {
                if ($visual_[$iV] != '') {
                    $dataVisual = [
                        'id_request' => $idRequest,
                        'visual_acuity' => $visual_[$iV],
                    ];
                    $this->examVisual->insert($dataVisual);
                }
            }

            $dx_ = $this->request->getPost("dx_");
            $systema_ = $this->request->getPost("sistema_"); // llegan campos vacios
            for ($iDx = 0; $iDx < 4; $iDx++) {
                if ($dx_[$iDx] != '' && $systema_[$iDx] != '') {
                    $dataDx = [
                        'id_request' => $idRequest,
                        'dx' => $dx_[$iDx],
                        'id_system' => $systema_[$iDx],
                    ];
                    $this->examDx->insert($dataDx);
                }
            }
            return ($idRequest != null && isset($idRequest)) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    public function RequestAll()
    {
        $medicalRequest = $this->medicalRequest->where('active_status', 1)->findAll();
        return (count($medicalRequest) > 0) ? json_encode($medicalRequest) : json_encode(false);
    }

    public function consultMedicALL()
    {
        $medicalConsultRequest = $this->medicalConsultation->where('active_status', 1)->findAll();
        return (count($medicalConsultRequest) > 0) ? json_encode($medicalConsultRequest) : json_encode(false);
    }

    public function consultExamALL()
    {
        $medicalExamRequest = $this->examRequest->where('active_status', 1)->findAll();
        return (count($medicalExamRequest) > 0) ? json_encode($medicalExamRequest) : json_encode(false);
    }

    public function userData()
    {
        try {
            $payroll_number = trim($this->request->getPost('ID'));
            $query = $this->db->query("	SELECT a.id_user ,a.payroll_number AS nomina, CONCAT(a.`name`,' ',a.surname,' ',a.second_surname) AS nombre, b.departament AS departamento,
            c.job AS puesto, b.clave_depto AS costos, a.id_departament,
                CASE WHEN a.type_of_employee = 1 THEN 'ADMINISTRATIVO' WHEN a.type_of_employee = 2 THEN 'SINDICALIZADO' ELSE 'ERROR' END AS tipo, a.type_of_employee AS id_tipo,
                d.genero, d.edad_usuario,CONCAT(e.name,' ',e.surname,' ',e.second_surname) AS supervisor, e.id_user AS id_supervisor, d.escolaridad, a.date_admission, d.estado_civil
            FROM tbl_users AS a
            LEFT JOIN cat_departament AS b ON a.id_departament = b.id_depto 
            LEFT JOIN cat_job_position AS c ON a.id_job_position = c.id
            LEFT JOIN tbl_users_personal_data AS d ON a.payroll_number = d.num_nomina
            LEFT JOIN( tbl_assign_departments_to_managers_new AS aa JOIN tbl_users AS e ON aa.id_manager = e.id_user) ON a.payroll_number = aa.payroll_number
                WHERE a.payroll_number = $payroll_number AND a.active_status = 1 AND a.active_status = 1;")->getRow();
            if ($query == null) {
                return json_encode(false);
            }
            $toDay = new DateTime(date("Y-m-d"));
            $diff = $toDay->diff(new DateTime($query->date_admission));
            $tiempo = ['y' => $diff->y, 'm' => $diff->m, 'd' => $diff->d];
            $query1 = $this->db->query("SELECT id AS turn, name_turn FROM cat_turns WHERE type_of_employee = $query->id_tipo AND active_status = 1")->getResult();
            $data = ['data' => $query, 'date' => $tiempo, 'turn' => $query1];
            return json_encode($data);
        } catch (\Exception $e) {
            return json_encode(false);
        }
    }

    public function dataMedicine()
    {
        $idMedic = $this->request->getPost('id_medicamento');
        if ($idMedic != null) {
            $data = $this->db->query("SELECT * FROM tbl_medical_inventory_medicine WHERE id_medicine = $idMedic")->getRow();
        } else {
            $data = $this->db->query("SELECT id_medicine AS id_medicamento, active_substance AS activo, piezas FROM tbl_medical_inventory_medicine WHERE active_status = 1 AND inventory_tablet = 2 ")->getResult();
        }
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function dataInventoryMedicine()
    {
        $sql = ($this->request->getPost('type') == 2) ? "AND inventory_tablet = 2" : "";
        $query = $this->db->query("SELECT * FROM tbl_medical_inventory_medicine WHERE active_status = 1 $sql")->getResult();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function insertMedicine()
    {
        try {
            $idUser = session()->id_user;
            $toDay = date('Y-m-d H:i:s');
            $activeSubstance = trim($this->request->getPost("sustancia_activa"));
            $tradename = trim($this->request->getPost("nombre_comercial"));
            $expirationDate = $this->request->getPost("fecha_caducidad");
            $idPresentation = $this->request->getPost("presentacion");
            $PzCaja = $this->request->getPost("pz_caja");
            $piezaCaja = ($PzCaja == 0 || $PzCaja == null) ? 1 : $PzCaja;
            $stiker = $this->request->getPost("identificador");
            $amount = $this->request->getPost("catidad");

            $diff = (new DateTime(date("Y-m-d")))->diff(new DateTime($expirationDate));
            if ($diff->y == 0 && $diff->m == 0) {
                $trafficLight = 5;
            } elseif ($diff->y == 0 && $diff->m < 3 && $diff->m > 0) {
                $trafficLight = 4;
            } elseif ($diff->y == 0 && $diff->m >= 3 && $diff->m < 6) {
                $trafficLight = 3;
            } elseif (($diff->y == 0 && $diff->m >= 6) || ($diff->y == 1 && $diff->m == 0 && $diff->d == 0)) {
                $trafficLight = 2;
            } elseif ($diff->y >= 1) {
                $trafficLight = 1;
            } else {
                $trafficLight = 0;
            }
            $medicalData = [
                'active_substance' => $activeSubstance,
                'trademark' => $tradename,
                'expiration_date' => $expirationDate,
                'traffic_light' => $trafficLight,
                'stiker' => $stiker,
                'id_presentation' => intval($idPresentation),
                'pieza_caja' => intval($piezaCaja),
                'piezas' => intval($piezaCaja),
                'created_at' => $toDay,
                'id_created' => $idUser,
            ];
            // var_dump($medicalData);
            $insert = $amount;
            for ($i = 0; $i < $amount; $i++) {
                $insertMedical = $this->medicaments->insert($medicalData);
                if ($insertMedical) {
                    $insert--;
                }
            }
            return ($insert == 0) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return json_encode($e);
        }
    }

    function deletedMedicine()
    {
        try {
            $idMedicine = $this->request->getPost("product_");
            $data = ['active_status' => 2, 'deleted_at' => date('Y-m-d'), 'id_deleted' => session()->id_user];
            $updateMedical = $this->medicaments->update($idMedicine, $data);
            return ($updateMedical) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function updateMedicine()
    {
        try {
            $idMedicine = $this->request->getPost("id");
            $status = $this->request->getPost("status");
            $data = ['inventory_tablet' => $status];
            $updateMedical = $this->medicaments->update($idMedicine, $data);
            return ($updateMedical) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function dataConsultMedic()
    {
        $idRequest = $this->request->getPost("id_request");
        $data = $this->db->query("SELECT created_at, calification, inability, `status` AS estado FROM tbl_medical_consultation_request WHERE id_request = $idRequest")->getRow();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function updateConsultMedic()
    {
        try {
            $idRequest = $this->request->getPost('id_request');
            $calification = $this->request->getPost('calificacion_accidente');
            $inability = $this->request->getPost('tipo_incapacidad');
            $status = $this->request->getPost('estado');
            $data = [
                'calification' => $calification,
                'inability' => $inability,
                'status' => $status
            ];
            $update = $this->medicalConsultation->update($idRequest, $data);
            return ($update) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return json_encode($e);
        }
    }

    public function pdfRequestMedical($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT * FROM tbl_medical_request WHERE
            MD5(concat('$key',id_request))='$id_request'")->getRow();
        $data = ["request" => $query,];
        $html2 = view('pdf/pdf_medical_disability', $data);
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Incapasidad Médica');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('Consulta_Medica_' . $id_request . '.pdf', 'I');
    }

    public function pdfConsultRequestMedical($id_request = null)
    {
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT * FROM tbl_medical_consultation_request 
        WHERE MD5(concat('$key',id_request))='$id_request'")->getRow();
        $query1 = $this->db->query("SELECT * FROM tbl_medical_consultation_items_request 
        WHERE id_request = $query->id_request")->getRow();
        $data = ["request" => $query, 'item' => $query1];
        $html2 = view('pdf/pdf_medical_consult', $data);
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Consulta Medica');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('Consulta_Medica_' . $id_request . '.pdf', 'I');
    }

    public function xlsxRequest()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $fecha_inicio = $data->fecha_inicio;
        $fecha_fin = date('Y-m-d', strtotime($data->fecha_fin . ' + 1 days'));
        $NombreArchivo = "Reporte_" . $fecha_inicio . "_" . $fecha_fin . ".xlsx";
        $query = $this->db->query("SELECT * FROM tbl_medical_request WHERE created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'")->getResult();

        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:P1');
        $sheet->setTitle("Consultas Medicas");


        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

        // ANCHO DE CELDA
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(55);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(55);

        //UBICACION DEL TEXTO
        $sheet->getStyle('A1:P1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
        $sheet->getStyle('A1:P1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

        //COLOR DE CELDAS
        $spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

        // FONT-TEXT
        $sheet->getStyle("A1:P1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(10)
            ->getColor()
            ->setRGB('FFFFFF');

        // TITULO DE CELDA
        $sheet->setCellValue('A1', 'FOLIO');
        $sheet->setCellValue('B1', 'ATENDIDO POR');
        $sheet->setCellValue('C1', 'FECHA DE CRACION');
        $sheet->setCellValue('D1', 'NOMBRE EMPLEADO');
        $sheet->setCellValue('E1', 'DEPARTAMENTO');
        $sheet->setCellValue('F1', 'PUESTO');
        $sheet->setCellValue('G1', 'TIPO DE PERMISO');
        $sheet->setCellValue('H1', 'MOTIVO');
        $sheet->setCellValue('I1', 'FECHA DE SALIDA');
        $sheet->setCellValue('J1', 'HORA DE SALIDA');
        $sheet->setCellValue('K1', 'INASISTENCIA DEL');
        $sheet->setCellValue('L1', 'IN ASISTENCIAS HASTA');
        $sheet->setCellValue('M1', 'SISTEMA');
        $sheet->setCellValue('N1', 'SALARIO');
        $sheet->setCellValue('O1', 'DIAGNOSTICO');
        $sheet->setCellValue('P1', 'OBSERVACION');

        // $stausArray = ['ERROR','Pendiente','Concluido','Cancelado'];
        foreach ($query as $key => $value) {
            $dateOut = ($value->date_out != '0000-00-00') ? date("d/m/Y", strtotime($value->date_out)) : '';
            $timeOut = ($value->time_out != '00:00:00') ? date("H:i:s", strtotime($value->time_out)) : '';
            $dateStar = ($value->date_star != '0000-00-00') ? date("d/m/Y", strtotime($value->date_star)) : '';
            $dateEnd = ($value->date_end != '0000-00-00') ? date("d/m/Y", strtotime($value->date_end)) : '';
            $system = ($value->system != 'OTRO') ? $value->system : strtoupper($value->other_system);
            $sheet->setCellValue('A' . $cont, $value->id_request);
            $sheet->setCellValue('B' . $cont, $value->user_generate);
            $sheet->setCellValue('C' . $cont, date("d/m/Y H:i:s", strtotime($value->created_at)));
            $sheet->setCellValue('D' . $cont, $value->user_name);
            $sheet->setCellValue('E' . $cont, $value->departament);
            $sheet->setCellValue('F' . $cont, $value->position_job);
            $sheet->setCellValue('G' . $cont, $value->type_permission);
            $sheet->setCellValue('H' . $cont, $value->motive);
            $sheet->setCellValue('I' . $cont, $dateOut);
            $sheet->setCellValue('J' . $cont, $timeOut);
            $sheet->setCellValue('K' . $cont, $dateStar);
            $sheet->setCellValue('L' . $cont, $dateEnd);
            $sheet->setCellValue('M' . $cont, $system);
            $sheet->setCellValue('N' . $cont, $value->salary);
            $sheet->setCellValue('O' . $cont, $value->diagnostic);
            $sheet->setCellValue('P' . $cont, $value->obs);
            $cont++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($NombreArchivo);
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . basename($NombreArchivo));
        header("Expires:0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length:" . filesize($NombreArchivo));
        flush();
        readfile($NombreArchivo);
        exit;
    }

    public function xlsxConsultRequest()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $option = $data->option;
        if ($option == "error") {
            return json_encode(false);
        }
        $dateStar = $data->date_star;
        $dateEnd = date('Y-m-d', strtotime($data->date_end . ' + 1 days'));
        $type = $data->type;
        $NombreArchivo = "Reporte_" . $dateStar . "_" . $dateEnd . ".xlsx";
        if ($type == 1) {
            $sql = "";
        } else {
            $typeArray = ['error', '', 'payroll_number', 'turn', 'id_depto', 'type_atention', 'id_classification', 'id_system'];
            $sql =  "AND a." . $typeArray[$type] . " = '$option'";
        }

        $query = $this->db->query("SELECT a.*, b.name_turn, c.procedures, d.system, e.classification,
        CASE WHEN a.id_type_of_injury = 0 THEN '' ELSE f.injury END AS injury, 
        CASE WHEN a.id_anatomical_area = 0 THEN '' ELSE g.anatomical_area END AS anatomical_area,
        CASE WHEN a.`status` = 1 THEN 'PROCESO' WHEN a.`status` = 2 THEN 'FINALIZADO' ELSE 'ERROR' END AS estado,
        CASE WHEN b.type_of_employee = 1 THEN 'ADMINISTRATIVO' WHEN b.type_of_employee = 2 THEN 'SINDICALIZADO' ELSE 'ERROR' END AS empleado
        FROM tbl_medical_consultation_request AS a 
        LEFT JOIN cat_turns AS b ON a.turn = b.id
        LEFT JOIN cat_medical_procedures AS c On a.id_procedures = c.id_procedures
        LEFT JOIN cat_medical_system AS d ON a.id_system = d.id_system
        LEFT JOIN cat_medical_classification AS e ON a.id_classification = e.id_classification
        LEFT JOIN cat_medical_type_of_injury AS f ON a.id_type_of_injury = f.id_injury
        LEFT JOIN cat_medical_anatomical_area AS g ON a.id_anatomical_area = g.id_anatomical_area
        WHERE a.created_at BETWEEN '$dateStar' AND '$dateEnd' $sql ORDER BY a.id_request DESC")->getResult();
        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:AA1');
        $sheet->setTitle("Consultas Medicas");


        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(25); // alto de fila

        // ANCHO DE CELDA
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('Z')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);

        //UBICACION DEL TEXTO
        $sheet->getStyle('A1:AA1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
        $sheet->getStyle('A1:AA1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

        //COLOR DE CELDAS
        $spreadsheet->getActiveSheet()->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $spreadsheet->getActiveSheet()->getStyle('P1:AA1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3FC3EE');

        // FONT-TEXT
        $sheet->getStyle("A1:O1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(10)
            ->getColor()
            ->setRGB('FFFFFF');
        $sheet->getStyle("P1:AA1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(10)
            ->getColor()
            ->setRGB('000000');

        // TITULO DE CELDA
        $sheet->setCellValue('A1', 'FOLIO');
        $sheet->setCellValue('B1', 'ATENDIDO POR');
        $sheet->setCellValue('C1', 'FECHA DE CRACION');
        $sheet->setCellValue('D1', 'NOMBRE PACIENTE');
        $sheet->setCellValue('E1', 'GENERO');
        $sheet->setCellValue('F1', 'EDAD');
        $sheet->setCellValue('G1', 'NIVEL ESCOLAR');
        $sheet->setCellValue('H1', 'NOMINA');
        $sheet->setCellValue('I1', 'PUESTO');
        $sheet->setCellValue('J1', 'DEPARTAMENTO');
        $sheet->setCellValue('K1', 'SUPERVISOR');
        $sheet->setCellValue('L1', 'ANTIGÜEDAD');
        $sheet->setCellValue('M1', 'EMPLEADO');
        $sheet->setCellValue('N1', 'TURNO');
        $sheet->setCellValue('O1', 'PLANTA');
        $sheet->setCellValue('P1', 'TIPO ATENCION');
        $sheet->setCellValue('Q1', 'PROCEDIMIENTO');
        $sheet->setCellValue('R1', 'SISTEMA');
        $sheet->setCellValue('S1', 'CLASIFICACION');
        $sheet->setCellValue('T1', 'TIPO DE FRACTURA');
        $sheet->setCellValue('U1', 'AREA ANATOMICA');
        $sheet->setCellValue('V1', 'MOTIVO COMUN');
        $sheet->setCellValue('W1', 'FECHA CITA');
        $sheet->setCellValue('X1', 'TELEFONO');
        $sheet->setCellValue('Y1', 'ALERGIAS');
        $sheet->setCellValue('Z1', 'DIAGNOSTICO');
        $sheet->setCellValue('AA1', 'OBSERVACION');


        $comunMotiveArray = ['', 'ESTRES LABORAL', 'ESTRES PERSONAL', 'EGRONOMIA'];
        foreach ($query as $key => $value) {
            $cite = ($value->next_appointment != '0000-00-00') ? date("d/m/Y", strtotime($value->next_appointment)) : '';
            $nomina = ($value->payroll_number == 0) ? '' : $value->payroll_number;
            $employe = ($value->empleado == 'ERROR') ? '' : $value->empleado;
            $nameTurn = ($value->name_turn == NULL) ? '' : $value->name_turn;
            $plant = ($value->plant == NULL) ? '' : $value->plant;
            $sheet->setCellValue('A' . $cont, $value->id_request);
            $sheet->setCellValue('B' . $cont, $value->name_attended);
            $sheet->setCellValue('C' . $cont, date("d/m/Y", strtotime($value->created_at)));
            $sheet->setCellValue('D' . $cont, $value->name);
            $sheet->setCellValue('E' . $cont, $value->gender);
            $sheet->setCellValue('F' . $cont, $value->age . ' años');
            $sheet->setCellValue('G' . $cont, $value->lvl_schooling);
            $sheet->setCellValue('H' . $cont, $nomina);
            $sheet->setCellValue('I' . $cont, $value->job);
            $sheet->setCellValue('J' . $cont, $value->depto);
            $sheet->setCellValue('K' . $cont, $value->manager_name);
            $sheet->setCellValue('L' . $cont, $value->general_antiquity);
            $sheet->setCellValue('M' . $cont, $employe);
            $sheet->setCellValue('N' . $cont, $nameTurn);
            $sheet->setCellValue('O' . $cont, $plant);
            $sheet->setCellValue('P' . $cont, $value->type_atention);
            $sheet->setCellValue('Q' . $cont, $value->procedures);
            $sheet->setCellValue('R' . $cont, $value->system);
            $sheet->setCellValue('S' . $cont, $value->classification);
            $sheet->setCellValue('T' . $cont, $value->injury);
            $sheet->setCellValue('U' . $cont, $value->anatomical_area);
            $sheet->setCellValue('V' . $cont, $comunMotiveArray[$value->common_motive]);
            $sheet->setCellValue('W' . $cont, $cite);
            $sheet->setCellValue('X' . $cont, $value->phone);
            $sheet->setCellValue('Y' . $cont, $value->allergies);
            $sheet->setCellValue('Z' . $cont, $value->diagnosis);
            $sheet->setCellValue('AA' . $cont, $value->obs);
            $cont++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($NombreArchivo);
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . basename($NombreArchivo));
        header("Expires:0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length:" . filesize($NombreArchivo));
        flush();
        readfile($NombreArchivo);
        exit;
    }
}
