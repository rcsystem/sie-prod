<?php

namespace App\Controllers\Users;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DeptoRhModel;
use App\Models\RolesModel;
use App\Models\JobPositionModel;
use App\Models\AssignDepartmentsModel;
use App\Models\AuthorizePermissionsModel;
use App\Models\AuthorizePermissionsModelNew;
use App\Models\ManagersModel;
use App\Models\UserPersonalDataModel;
use App\Models\UserEmergencyContactModel;
use App\Models\UserChildrenModel;
use App\Models\UserDocumentModel;
use App\Models\UserParentsModel;
use App\Models\DirectorioModel;
use App\Models\ContractsTempModel;
use App\Models\UsersTempModel;
use App\Models\StationeryPermissionsModel;
use Exception as GlobalException;
use FFI\Exception as FFIException;
use Spipu\Html2Pdf\Html2Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Users extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->userModel = new UserModel();
        $this->assignModel = new AssignDepartmentsModel();
        $this->deptoRhModel = new DeptoRhModel();
        $this->authorizeModelNew = new AuthorizePermissionsModelNew();
        $this->rolesModel = new RolesModel();
        $this->positionModel = new JobPositionModel();
        $this->managerModel = new ManagersModel();
        $this->personalDataModel = new UserPersonalDataModel();
        $this->emergencyContactModel = new UserEmergencyContactModel();
        $this->childrenModel = new UserChildrenModel();
        $this->parentsModel = new UserParentsModel();
        $this->documentModel = new UserDocumentModel();
        $this->contractsTempModel = new ContractsTempModel();
        $this->usersTempModel = new UsersTempModel();
        $this->stationeryModel = new StationeryPermissionsModel();
        helper('secure_password');
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function index()
    {
        $rolesData = $this->rolesModel->where('active_status', 1)->findAll();

        /*  $builder = $this->db->table('cat_departament');
        $builder->select('id_depto, departament,area');
        $builder->where('active_status','1'); */
        $query = $this->db->query("SELECT id_depto, departament,area FROM cat_departament WHERE active_status = 1")->getResultArray();
        // $query = $builder->get()->getResultArray();
        foreach ($query as $key => $value) {
            $groups[$value['area']][$value['id_depto']] = $value['departament'];
        }
        /* $query = $this->db->query("SELECT id_user,`name`,surname,second_surname FROM tbl_users where id_user in 
        ( SELECT DISTINCT id_manager FROM tbl_assign_departments_to_managers_new )ORDER BY `name` ASC");
        $autorizar =  $query->getResultArray(); */
        $query1 = $this->db->query("SELECT id_user,
            CONCAT(`name`, ' ', surname, ' ', second_surname ) AS nombre
            FROM tbl_users
            WHERE id_user IN (
                SELECT DISTINCT id_manager
                FROM tbl_assign_departments_to_managers_new
            )
            ORDER BY surname ASC ")->getResult();


        $areas = $this->db->query("SELECT id_area, area FROM cat_operational_area WHERE active_status = 1")->getResult();
        $centroCostos = $this->db->query("SELECT id_cost_center, clave_cost_center, cost_center FROM cat_cost_center WHERE active_status = 1;")->getResult();
        $jobs = $this->db->query("SELECT id, job FROM cat_job_position WHERE active_status = 1;")->getResult();

        $data = [
            'gerente' => $query1,
            'departament' => $groups,
            'roles' => $rolesData,
            'areas' => $areas,
            'centros' => $centroCostos,
            'puestos' => $jobs,
        ];
        return ($this->is_logged) ?  view('user/admin_users', $data) : redirect()->to(site_url());
    }
    public function info()
    {
        return ($this->is_logged) ?  view('user/my_info_user') : redirect()->to(site_url());
    }

    public function viewInfoUsers()
    {
        return ($this->is_logged) ?  view('user/users_info') : redirect()->to(site_url());
    }
    public function viewContracts()
    {
        return ($this->is_logged) ?  view('user/view_contracts') : redirect()->to(site_url());
    }
    public function viewContractsAll()
    {
        return ($this->is_logged) ?  view('user/view_contracts_all') : redirect()->to(site_url());
    }

    public function departaments()
    {
        return ($this->is_logged) ?  view('admin/departaments') : redirect()->to(site_url());
    }

    public function usersContractsAll()
    {
        return ($this->is_logged) ?  view('user/view_contracts_reports') : redirect()->to(site_url());
    }

    public function viewAuthorizePlant()
    {
        return ($this->is_logged) ?  view('user/view_contracts_plant') : redirect()->to(site_url());
    }

    public function view_directorio()
    {   
        return ($this->is_logged) ?  view('user/view_directorio') : redirect()->to(site_url());
    }

    public function listarDirectorio()
    {   
        $directorioModel = new DirectorioModel();
        $datos = $directorioModel->getDirectorio();
    
        if (!empty($datos)) {
            return $this->response->setJSON($datos);
        } else {
            return $this->response->setJSON(['error' => 'No hay datos disponibles'], 404);
        }
    }
    

    public function primeryContract($id_user = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

        $query = $this->db->query("SELECT b.id_user,b.payroll_number,b.name,b.surname,b.second_surname,b.type_of_employee,c.job,d.departament
            FROM tbl_users AS b LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
            LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
            WHERE MD5(concat('" . $key . "',b.id_user))='" . $id_user . "'")->getResultArray();
        //   var_dump($query);

        $data = ['temporal' => $query];
        return ($this->is_logged) ?  view('user/view_primery_contract', $data) : redirect()->to(site_url());
    }

    public function userContracts($id_user = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT a.id_contract,a.id_user,a.type_of_contract,a.id_manager,a.date_of_new_entry,a.date_expiration,a.date_reing,a.option,
            a.type_of_contract_ant,a.create_contract,a.cause_of_termination,a.observations,
            b.name,b.surname,b.second_surname,c.job,d.departament,b.date_admission,b.type_of_employee,
            CASE  
		        WHEN b.type_of_employee = 1 THEN a.direct_authorization 
		        ELSE ''
            END AS direct_authorization
        FROM tbl_user_type_of_contract AS a 
            LEFT JOIN tbl_users AS b ON  a.id_user = b.id_user
            LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
            LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
        WHERE a.active_status = 1 AND a.direct_authorization <> 2
        AND MD5(concat('" . $key . "',a.id_user))='" . $id_user . "'")->getResultArray();
        $data = ['temporal' => $query];
        return ($this->is_logged) ?  view('user/view_temp_contract', $data) : redirect()->to(site_url());
    }

    public function viewContractsAdmin($id_user = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT `name`, surname, second_surname FROM tbl_users WHERE MD5(concat('" . $key . "',id_user))='" . $id_user . "'")->getRow();
        $name = $query->name . " " . $query->surname . " " . $query->second_surname;
        $data = ['id_md5' => $id_user, "nombre" => $name];
        return ($this->is_logged) ?  view('user/view_all_temp_contract_by_user', $data) : redirect()->to(site_url());
    }

    public function userContractTemp()
    {
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $id_user = $this->request->getPost('id_user');
        $query = $this->db->query("SELECT a.id_contract, a.date_reing, b.type_of_employee,
            DATE_FORMAT(a.date_of_new_entry, '%d / %m / %Y') AS date_of_new_entry,
            DATE_FORMAT(a.date_expiration, '%d / %m / %Y') AS date_expiration, a.`option`,
            CASE 
                WHEN a.`option` = 1 THEN 'PLANTA'
                WHEN a.type_of_contract = 2 THEN '30 DÍAS'
                WHEN a.type_of_contract = 3 THEN '60 DÍAS'
                WHEN a.type_of_contract = 4 THEN '90 DÍAS'
                WHEN a.`option` = 3 THEN 'BAJA'
                ELSE 'ERROR' 
            END AS type_of_contract,
            CASE
                WHEN a.direct_authorization = 1 THEN 'success'
                WHEN a.direct_authorization = 2 THEN 'danger'
                ELSE 'warning'
            END AS color
            FROM tbl_user_type_of_contract AS a
                JOIN tbl_users AS b ON a.id_user = b.id_user
            WHERE a.active_status = 1 
        AND MD5(concat('" . $key . "',a.id_user))='" . $id_user . "'")->getResult();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function userEditContractTempData()
    {
        $idContract = $this->request->getPost('id_contract');
        $query = $this->db->query("SELECT date_of_new_entry, date_expiration, date_reing 
        FROM tbl_user_type_of_contract 
        WHERE id_contract = $idContract")->getRow();
        return json_encode($query);
    }

    public function userEditContractTemp()
    {
        $idContract = $this->request->getPost('folio');
        $dateReing = $this->request->getPost('fecha_recontrato');
        $dateExpiration = $this->request->getPost('fecha_expiracion');
        $dateOfNewEntry = $this->request->getPost('fecha_creacion');
        $dateNotification = date('Y-m-d', strtotime($dateOfNewEntry . ' -15 days'));

        $upData = [
            'date_reing' => $dateReing,
            'date_expiration' => $dateExpiration,
            'date_of_new_entry' => $dateOfNewEntry,
            'date_notification' => $dateNotification,
        ];
        $update = $this->contractsTempModel->update($idContract, $upData);
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function userDeletContractTemp()
    {
        try {
            $idContract = $this->request->getPost("id_folio");
            $deletData = [
                'active_status' => 2,
                'id_delete' => session()->id_user,
                'delete_at' => date("Y-m-d H:i:s"),
            ];
            $update = $this->contractsTempModel->update($idContract, $deletData);
            return json_encode($update);
        } catch (Exception $th) {
            echo $th;
            return json_encode(False);
        }
    }

    public function departamentsAll()
    {
        $deptoData = $this->deptoRhModel->where('active_status', 1)->findAll();

        return (count($deptoData) > 0) ? json_encode($deptoData) : json_encode("error");
    }

    public function infoUsersAll()
    {
        $dataUsers =  $this->personalDataModel->where('active_status', 1)->findAll();
        return (count($dataUsers) > 0) ? json_encode($dataUsers) : json_encode(false);
    }

    public function usersAll()
    {
        //$usersData = $this->userModel->where('active_status', 1)->findAll();

        $usersData = $this->db->query("SELECT a.id_user,a.payroll_number,a.date_admission,a.email,a.name,a.surname,a.second_surname,b.job,c.departament
                                    FROM tbl_users AS a
                                    LEFT JOIN cat_job_position AS b
                                    ON  a.id_job_position = b.id  
                                    LEFT JOIN cat_departament AS c
                                    ON  a.id_departament =  c.id_depto 
                                    WHERE a.active_status = 1");
        $usersData =  $usersData->getResultArray();


        return (count($usersData) > 0) ? json_encode($usersData) : json_encode("error");
    }

    public function userData()
    {
        $payrollNumber = trim($this->request->getPost('num_nomina'));
        $usersData = $this->db->query("SELECT a.id_user, a.`name`,  a.surname, a.second_surname, a.date_admission
            CONCAT(a.`name`, ' ', a.surname, ' ', a.second_surname ) AS nombre,
            FROM tbl_users As a
                JOIN tbl_assign_departments_to_managers_new As b ON a.payroll_number = b.payroll_number
            WHERE a.payroll_number = $payrollNumber")->getResult();
        echo (count($usersData) > 0) ? json_encode($usersData) : json_encode("error");
    }

    public function userInfo()
    {
        $id_user = trim($this->request->getPost('id_user'));

        $query = $this->db->query("SELECT a.name,a.surname,a.second_surname,b.job,c.departament
                                    FROM tbl_users AS a
                                    LEFT JOIN cat_job_position AS b
                                    ON  a.id_job_position = b.id  
                                    LEFT JOIN cat_departament AS c
                                    ON  a.id_departament =  c.id_depto 
                                    WHERE a.id_user = $id_user");
        $usersData =  $query->getResult();

        return ($usersData) ? json_encode($usersData) : json_encode(false);
    }

    public function registerContract()
    {
        try {
            if (session()->is_logged != true) {
                redirect()->to(site_url());
            }

            $id_user_contract = trim($this->request->getPost('id_user'));

            $validate = $this->db->query("SELECT id_contract FROM tbl_user_type_of_contract WHERE active_status = 1 
            AND direct_authorization <> 2 AND `option` IN (1,3) AND id_user = $id_user_contract")->getRow();
            if ($validate != null) {
                return json_encode(true);
            }

            $usersData = $this->db->query("SELECT c.last_contract, a.type_of_employee, j.job, d.departament,
            CONCAT(a.`name`,' ',a.surname) AS nombre, a.payroll_number
            FROM tbl_users AS a
                LEFT JOIN cat_job_position AS j ON a.id_job_position = j.id
                LEFT JOIN cat_departament AS d On a.id_departament = d.id_depto
                JOIN (
                    SELECT jt1.id_user,
                        MAX(jt1.date_expiration) AS last_contract
                    FROM tbl_user_type_of_contract AS jt1
                    WHERE jt1.active_status = 1
                    AND jt1.direct_authorization IN (1,0)
                    GROUP BY jt1.id_user
                ) AS c ON c.id_user = a.id_user
            WHERE a.id_user = $id_user_contract")->getRow();
            $type_of_employee = $usersData->type_of_employee;
            $id_manager = session()->id_user;
            $option = trim($this->request->getPost('opcion'));
            $contract_option = trim($this->request->getPost('contrato'));
            $cause_of_termination = trim($this->request->getPost('causa_baja'));
            $obs = trim($this->request->getPost('observaciones'));
            $create = date("Y-m-d H:i:s");
            $dateNewEntry =  date("Y-m-d", strtotime($usersData->last_contract . ' + 1 days')) ?? date("Y-m-d");

            if ($option != 2) {
                $data = [
                    // 'active_status' => ($option == 3) ? 2 : 1,
                    'contracts' => $option
                ];
                $this->userModel->update($id_user_contract, $data);
            } else {
                $days = [
                    2 => "+ 30 days", // "+1 month",
                    3 => "+ 60 days", // "+2 month",
                    4 => "+ 90 days", //"+3 month"
                ];
                //Incrementando los dias dependiendo de la opcion
                $date_expiration = date("Y-m-d", strtotime($dateNewEntry . $days[$contract_option]));
                $date_notification = date("Y-m-d", strtotime($date_expiration . "- 15 days"));
            }
            $new_date_expiration = $this->db->query("UPDATE tbl_user_type_of_contract 
            SET contract_status = 0
            WHERE id_user = $id_user_contract");

            $dataUser = $this->db->query("SELECT a.id_departament, b.departament, c.job
            FROM tbl_users AS a 
            JOIN cat_departament As b ON a.id_departament = b.id_depto
            JOIN cat_job_position AS c ON a.id_job_position = c.id
            WHERE a.id_user = $id_user_contract")->getRow();

            if ($new_date_expiration) {
                $dataContract = [
                    'id_user' => $id_user_contract,
                    'id_depto' => $dataUser->id_departament,
                    'depto' => $dataUser->departament,
                    'job_position' => $dataUser->job,
                    'option' => $option,
                    'type_of_contract' => $contract_option,
                    'id_manager' => $id_manager, //valor de session()->id_user
                    'date_of_new_entry' => $dateNewEntry,
                    'date_expiration' => $date_expiration ?? '',
                    'date_notification' => $date_notification ?? '',
                    'cause_of_termination' => $cause_of_termination,
                    'observations' => $obs,
                    'create_contract' => $create
                ];
                $insertContract = $this->contractsTempModel->insert($dataContract);

                if ($insertContract) {
                    $this->notificationContract($type_of_employee, $dataContract, $usersData);
                    if ($option == 1 && $type_of_employee == 1) {
                        $this->notificationContractDirector($this->db->insertID(), 1);
                    }
                }
            }
            return ($insertContract) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function registerPrimeryContract()
    {
        try {
            $id_user_contract = trim($this->request->getPost('id_user'));
            $query = $this->db->query("SELECT a.id_user, a.name, a.surname, a.payroll_number,a.date_admission,b.departament,c.job
            FROM tbl_users AS a
            INNER JOIN cat_departament AS b
            ON a.id_departament = b.id_depto
			INNER JOIN cat_job_position AS c
            ON a.id_job_position = c.id
            WHERE a.id_user=$id_user_contract");
            $usersData =  $query->getResultArray();

            $type_of_employee = trim($this->request->getPost('tipo'));
            $reingreso = trim($this->request->getPost('reingreso'));
            $id_manager = session()->id_user;
            $option = trim($this->request->getPost('opcion'));
            $contract_option = trim($this->request->getPost('contrato'));
            $cause_of_termination = trim($this->request->getPost('causa_baja'));
            $obs = trim($this->request->getPost('observaciones'));

            switch ($option) {
                case '1':
                    $data = ["contracts" => 1];

                    $this->userModel->update($id_user_contract, $data);
                    break;
                case '3':
                    $data = ["contracts" => 3];
                    $this->userModel->update($id_user_contract, $data);
                    break;

                default:
                    # code...
                    break;
            }

            switch ($contract_option) {
                case '2':
                    $days = "+ 30 days";
                    break;

                case '3':
                    $days = "+ 60 days";
                    break;
                case '4':
                    $days = "+ 90 days";
                    break;

                default:
                    $days = "+ 30 days";
                    break;
            }

            $date = date("Y-m-d");
            $create = date("Y-m-d H:i:s");
            //Incrementando los dias dependiendo de la opcion
            $mod_date = strtotime($date . $days);
            $date_expiration = date("Y-m-d", $mod_date);
            $day = "- 15 days";
            $mod_date2 = strtotime($date_expiration . $day);
            $date_notification = date("Y-m-d", $mod_date2);

            $dataUser = $this->db->query("SELECT a.id_departament, b.departament, c.job
            FROM tbl_users AS a 
            JOIN cat_departament As b ON a.id_departament = b.id_depto
            JOIN cat_job_position AS c ON a.id_job_position = c.id
            WHERE a.id_user = $id_user_contract")->getRow();

            $dataContract = [
                'id_user' => $id_user_contract,
                'id_depto' => $dataUser->id_departament,
                'depto' => $dataUser->departament,
                'job_position' => $dataUser->job,
                'option' => $option,
                'type_of_contract' => $contract_option,
                'id_manager' => $id_manager,
                'date_reing' => $reingreso,
                'date_of_new_entry' => $date,
                'date_notification' => $date_notification,
                'date_expiration' => $date_expiration,
                'cause_of_termination' => $cause_of_termination,
                'observations' => $obs,
                'create_contract' => $create
            ];

            $insertContract = $this->contractsTempModel->insert($dataContract);

            if ($insertContract) {
                $this->notificationContract($type_of_employee, $dataContract, $usersData);
            }

            return ($insertContract) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function updatePermanentContracts()
    {
        try {
            if (session()->is_logged != true) {
                redirect()->to(site_url());
            }
            $directAuthorization = $this->request->getPost('direct_authorization');
            $idContract = $this->request->getPost('id_contract');
            $updateData = [
                'direct_authorization' => $directAuthorization,
                'id_direct_authorization' => session()->id_user,
                'direct_authorization_at' => date("Y-m-d H:i:s")
            ];
            if ($directAuthorization == 2) {
                $query = $this->db->query("SELECT id_user FROM tbl_user_type_of_contract WHERE id_contract = $idContract")->getRow();
                $userData = ['contracts' => 2];
                $this->userModel->update($query->id_user, $userData);
            }
            $updateContract = $this->contractsTempModel->update($idContract, $updateData);
            $this->notificationContractDirector($idContract, 2);
            return json_encode($updateContract);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function insertDepartament()
    {
        try {
            $departament = trim($this->request->getPost('departamento'));
            $cost_center = trim($this->request->getPost('centro_costo'));
            $area = trim($this->request->getPost('area'));
            $builder =  $this->deptoModel->table('cat_departament');
            $dataDepto = [
                'departament' => $departament,
                'cost_center' => $cost_center,
                'area' => $area
            ];
            $newDepto = $builder->insert($dataDepto);
            return ($newDepto) ? 'ok' : 'error';
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function editDepto($id_depto = null)
    {
        $userData = $this->deptoModel->find($id_depto);
        return ($userData) ? json_encode($userData) : 'error';
    }

    public function registerUser()
    {
        $rolesData = $this->rolesModel->where('active_status', 1)->findAll();
        $builder = $this->db->table('cat_departament');
        $builder->select('id_depto, departament, area');
        $builder->where('active_status', '1');
        $query = $builder->get()->getResultArray();
        foreach ($query as $key => $value) {
            $groups[$value['area']][$value['id_depto']] = $value['departament'];
        }

        $director = $this->db->query("SELECT id_user,`name`,surname,second_surname 
                                        FROM tbl_users 
                                        WHERE id_user IN (SELECT DISTINCT id_director  
                                            FROM tbl_assign_departments_to_managers_new WHERE active_status = 1) 
                                        ORDER BY `name` ASC;")->getResultArray();

        $query = $this->db->query("SELECT id_user,`name`,surname,second_surname 
                                    FROM tbl_users 
                                    WHERE id_user IN ( SELECT DISTINCT id_manager 
                                        FROM tbl_assign_departments_to_managers_new )
                                    ORDER BY `name` ASC");

        $autorizar =  $query->getResultArray();

        $temp = $this->db->query("SELECT id_temporal,tipo_contrato FROM cat_temporary_contracts WHERE active_status = 1");
        $temp_contracts =  $temp->getResult();


        $areas = $this->db->query("SELECT id_area, area FROM cat_operational_area WHERE active_status = 1")->getResult();
        $centroCostos = $this->db->query("SELECT id_cost_center, clave_cost_center, cost_center FROM cat_cost_center WHERE active_status = 1;")->getResult();

        $data = [
            'departament' => $groups,
            'roles' => $rolesData,
            'autorizar' => $autorizar,
            'director' => $director,
            'contratos' => $temp_contracts,
            'areas' => $areas,
            'centros' => $centroCostos,
        ];
        return ($this->is_logged) ?  view('user/register_user', $data) : redirect()->to(site_url());
    }

    public function register_user()
    {
        $company = trim($this->request->getVar('empresa'));
        $name = trim($this->request->getVar('nombre'));
        $surname = trim($this->request->getVar('ape_paterno'));
        $second_surname = trim($this->request->getVar('ape_materno'));
        $email = trim($this->request->getVar('correo'));
        $password = trim($this->request->getVar('password'));
        $payroll_number = trim($this->request->getVar('num_empleado'));
        $date_admission = trim($this->request->getVar('fecha_ingreso'));
        $id_departament = trim($this->request->getVar('depto'));
        $id_rol = trim($this->request->getVar('rol_usuario'));
        $type_of_employee = trim($this->request->getVar('tipo_usuario'));
        $id_job_position = trim($this->request->getVar('puesto'));
        $autoriza = trim($this->request->getVar('autoriza'));
        $director = trim($this->request->getVar('director'));
        $contrato = trim($this->request->getVar('contrato'));
        $termino_contrato = trim($this->request->getVar('termino_contrato'));
        $grado = trim($this->request->getVar('grado'));
        $curp = trim($this->request->getVar('curp'));
        $nss = trim($this->request->getVar('nss'));
        $id_area_operativa = $this->request->getVar('area_operative');
        $id_cost_center = $this->request->getVar('clace_cost');

        $id_registration = session()->id_user;
        $date = date("Y-m-d H:i:s");
        $data = [
            "name" => $name,
            "company" => $company,
            "surname" => $surname,
            "second_surname" => $second_surname,
            "email" => $email,
            "grado" => $grado,
            "curp" => $curp,
            "nss" => $nss,
            "password" => $password,
            "payroll_number" => $payroll_number,
            "date_admission" => $date_admission,
            "id_departament" => $id_departament,
            "id_area_operativa" => $id_area_operativa,
            "id_cost_center" => $id_cost_center,
            "id_rol" => $id_rol,
            "type_of_employee" => $type_of_employee,
            "id_job_position" => $id_job_position,
            "created_at" => $date,
            "contracts" => ($contrato != 1) ? 2 : 1,
            "user_registration" => $id_registration
        ];
        $newUser = $this->userModel->insert($data);
        $id_user = $this->db->insertID();
        $deptos = $this->request->getVar('asignar_depto_gerente');
        if (!empty($deptos)) {
            for ($i = 0; $i < count($deptos); $i++) {
                $data = [
                    "id_departament" => $deptos[$i],
                    "id_manager" => $id_user
                ];
                $this->assignModel->insert($data);
            }
        }

        $dataPermiso = ["id_user" => $id_user, "payroll_number" => $payroll_number, "id_manager" => $autoriza];
        $dataPermisoNew = ["id_user" => $id_user, "payroll_number" => $payroll_number, "id_manager" => $autoriza, "id_director" => $director];
        $this->authorizeModelNew->insert($dataPermisoNew);
        $this->stationeryModel->insert($dataPermiso);


        if ($contrato != 1) {
            $id_user_created = session()->id_user;

            $dataTemp = [
                "id_user" =>  $id_user,
                "id_manager" => $autoriza,
                "id_user_created" => $id_user_created,
                "type_of_employee" => $type_of_employee,
                "create_contract" => $date
            ];

            $this->usersTempModel->insert($dataTemp);

            $days = "- 15 days";

            $dataUser = $this->db->query("SELECT a.id_departament, b.departament, c.job
            FROM tbl_users AS a 
            JOIN cat_departament As b ON a.id_departament = b.id_depto
            JOIN cat_job_position AS c ON a.id_job_position = c.id
            WHERE a.id_user = $id_user")->getRow();

            //Decrementando los dias dependiendo de la opcion
            $mod_date = strtotime($termino_contrato . $days);
            $date_notification = date("Y-m-d", $mod_date);
            $dataContract = [
                "id_user" =>  $id_user,
                'id_depto' => $dataUser->id_departament,
                'depto' => $dataUser->departament,
                'job_position' => $dataUser->job,
                "option" => 2,
                "type_of_contract" => $contrato,
                "id_manager" => $autoriza,
                "date_of_new_entry" => $date_admission,
                "date_expiration" => $termino_contrato,
                "date_notification" => $date_notification,
                "create_contract" => date("Y-m-d H:i:s")
            ];
            $this->contractsTempModel->insert($dataContract);

            $query = $this->db->query("SELECT  a.name,a.surname,a.email,b.job
                                        FROM tbl_users AS a
                                        INNER JOIN cat_job_position AS b 
                                        ON a.id_job_position = b.id
                                        WHERE a.id_user  = $autoriza 
                                        LIMIT 1")->getResultArray();

            $query2 = $this->db->query("SELECT  a.name,a.surname,a.email,b.job
                                        FROM tbl_users AS a
                                        INNER JOIN cat_job_position AS b 
                                        ON a.id_job_position = b.id
                                        WHERE a.id_user  = $id_user 
                                        LIMIT 1")->getResultArray();

            $user_notifica = $query[0]["name"] . " " . $query[0]["surname"];
            $user_email = $query[0]["email"];
            $dataNotifica = [
                "name" => $name,
                "company" => $company,
                "surname" => $surname,
                "job_position" => $query2[0]["job"],
                "second_surname" => $second_surname,
                "payroll_number" => $payroll_number,
                "date_admission" => $date_admission,
                "type_of_contract" => $contrato,
                "date_expiration" => $termino_contrato
            ];

            $this->notificationUserRegistration($user_notifica, $user_email, $dataNotifica, $type_of_employee);
        }
        return ($newUser) ? json_encode($newUser) : json_encode('error');
    }

    public function contractedUsers()
    {
        try {
            $idUser = session()->id_user;
            $sqlWhere = ($idUser == 1063 || $idUser == 1) ? "" : "WHERE a.id_user IN ( SELECT DISTINCT id_user FROM tbl_users_temporary WHERE active_status = 1 AND id_manager = $idUser)";
            $query = $this->db->query("SELECT a.id_user, a.`name`, a.surname, a.payroll_number, b.job, c.`option`,
                CASE
                    WHEN c.`option` = 1 OR c.`option` = 3 THEN 
                        'INDEFINIDO'
                    ELSE 
                        DATE_FORMAT(c.last_contract, '%d / %m / %Y')
                END AS last_contract,
                DATE_FORMAT(a.date_admission, '%d / %m / %Y') AS date_admission,
                CONCAT($idUser) AS id_user_admin,
                CASE
                    WHEN c.`option` = 1 THEN 1
                    WHEN c.`option` = 3 THEN 0
                    WHEN DATEDIFF(c.last_contract, CURDATE()) > 0 
                        AND DATEDIFF(c.last_contract, CURDATE()) < 15 THEN 4
                    WHEN DATEDIFF(c.last_contract, CURDATE()) = 0 THEN 5
                    WHEN DATEDIFF(c.last_contract, CURDATE()) < 0 THEN 3
                    ELSE 2
                END AS id_color
            FROM tbl_users AS a
                INNER JOIN cat_job_position AS b ON a.id_job_position = b.id
                JOIN (SELECT jt1.`option`, jt1.id_user, jt1.date_expiration AS last_contract
                    FROM tbl_user_type_of_contract AS jt1 WHERE jt1.active_status = 1 
					AND jt1.date_of_new_entry = (SELECT MAX(sjt1.date_of_new_entry)
                        FROM tbl_user_type_of_contract AS sjt1
                        WHERE sjt1.active_status = 1 
                        AND sjt1.direct_authorization IN (1,0)
                AND sjt1.id_user = jt1.id_user)) AS c ON c.id_user = a.id_user 
            $sqlWhere
            ORDER BY id_color DESC")->getResult();
            return (count($query)) ? json_encode($query) : json_encode(false);
        } catch (\Exception $e) {
            echo $e;
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function generateMassiveContracts()
    {
        try {
            $adminRequest = [];
            $unionizedRequest = [];
            $days = [2 => 30, 3 => 60, 4 => 90];
            $mount = [
                2 => 1, // 30days , 
                3 => 2, // 60days , 
                4 => 3, // 90days 
            ];
            $typeContract = $this->request->getPost('tipo_contrato');
            $typeTemporal = $this->request->getPost('tipo_temporal') ?? 0;
            $obs = $this->request->getPost('obs') ?? null;
            $low = $this->request->getPost('baja') ?? null;
            $idsContracts = $this->request->getPost('ids_contracts_');
            $count = $this->request->getPost('contador');

            if ($typeTemporal != 0) {
                $sqlExpi = $days[$typeTemporal];
            }

            $this->db->transStart();
            for ($i = 0; $i < $count; $i++) {
                $idUser = $idsContracts[$i];

                $query = $this->db->query("SELECT type_of_employee AS t FROM tbl_users WHERE id_user = $idUser")->getRow();

                $sql = ($typeTemporal != 0) ?
                    ", DATE_ADD(DATE_ADD(date_expiration, INTERVAL 1 DAY), INTERVAL $sqlExpi DAY) AS dateExpiration,
                        DATE_SUB(DATE_ADD(DATE_ADD(date_expiration, INTERVAL 1 DAY), INTERVAL $sqlExpi DAY), INTERVAL 15 DAY) AS dateNotification"
                    : '';

                $db = $this->db->query("SELECT DATE_ADD(date_expiration, INTERVAL 1 DAY) AS date_expiration $sql
                FROM tbl_user_type_of_contract
                WHERE id_user = $idUser
                AND active_status  = 1
                AND direct_authorization IN (1,0)
                ORDER BY date_expiration DESC
                LIMIT 1")->getRow();

                $dataUser = $this->db->query("SELECT a.id_departament, b.departament, c.job
                    FROM tbl_users AS a 
                    JOIN cat_departament As b ON a.id_departament = b.id_depto
                    JOIN cat_job_position AS c ON a.id_job_position = c.id
                WHERE a.id_user = " . $idsContracts[$i])->getRow();

                if (session()->id_user == 1063) { // cambia el ID al tuyo cuando toque contratos masivos
                    $idManager = $this->db->query("SELECT id_manager AS jefe 
                        FROM tbl_users_temporary 
                        WHERE active_status = 1 
                        AND  id_user = " . $idsContracts[$i])
                        ->getRow()->jefe ?? $this->db->query("SELECT id_manager AS jefe 
                        FROM tbl_users_temporary 
                        WHERE id_user = " . $idsContracts[$i] . " LIMIT 1")->getRow()->jefe;
                } else {
                    $idManager = session()->id_user;
                }

                $insertData = [
                    'id_user' => $idsContracts[$i],
                    'id_depto' => $dataUser->id_departament,
                    'depto' => $dataUser->departament,
                    'job_position' => $dataUser->job,
                    'option' => $typeContract,
                    'type_of_contract' => $typeTemporal,
                    'id_manager' => $idManager,
                    'date_of_new_entry' => $db->date_expiration,
                    'date_expiration' => $db->dateExpiration ?? '',
                    'date_notification' => $db->dateNotification ?? '',
                    'cause_of_termination' => $low,
                    'observations' => $obs,
                    'create_contract' => date('Y-m-d H:i:s'),
                ];
                if ($typeContract != 2) {
                    $data = [
                        'active_status' => ($typeContract == 3) ? 2 : 1,
                        'contracts' => $typeContract
                    ];
                    $result = $this->userModel->update($idsContracts[$i], $data);
                }

                $this->contractsTempModel->insert($insertData);
                $idRequest = $this->contractsTempModel->insertID();
                if ($query->t == 1) {
                    $adminRequest[] = $idRequest;
                }
                if ($query->t == 2) {
                    $unionizedRequest[] = $idRequest;
                }
            }

            if (session()->id_user != 1063) { // cambia el ID al tuyo cuando toque contratos masivos
                if ($typeContract != 1 && count($adminRequest) > 0) {
                    $this->notificacionMassive(1, $adminRequest);
                }
                if ($typeContract != 1 && count($unionizedRequest) > 0) {
                    $this->notificacionMassive(2, $unionizedRequest);
                }
                if ($typeContract == 1 && count($adminRequest) > 0) {
                    $this->notificacionMassive(3, $adminRequest);
                }
            }

            $result = $this->db->transComplete();
            return json_encode($result);
        } catch (GlobalException $th) {
            return json_encode($th);
        }
    }

    public function contractedUsersAll()
    {
        try {
            $idUser = session()->id_user;
            $type = [
                267 => 'AND ( a.type_of_employee IN (1,2) AND a.company IN (1,3,4))', // Sindicalizados Walworth
                50 => 'AND ( a.type_of_employee = 1 AND a.company = 2)' // Administrativos Grupo Walworth
            ];
            $sqlData = $type[$idUser] ?? '';
            $query = $this->db->query("SELECT a.id_user, a.payroll_number, b.job, c.`option`, d.departament,
                    CONCAT(a.`name`,' ',a.surname) AS nombre,
                    CASE
                        WHEN c.`option` = 1 OR c.`option` = 3 THEN 
                            'INDEFINIDO'
                        ELSE 
                            DATE_FORMAT(c.last_contract, '%d / %m / %Y')
                    END AS last_contract,
                    DATE_FORMAT(a.date_admission, '%d / %m / %Y') AS date_admission,
                    CASE
                        WHEN c.`option` = 1 THEN 1
                        WHEN c.`option` = 3 THEN 0
                        WHEN DATEDIFF(c.last_contract, CURDATE()) > 0 
                            AND DATEDIFF(c.last_contract, CURDATE()) < 15 THEN 4
                        WHEN DATEDIFF(c.last_contract, CURDATE()) = 0 THEN 5
                        WHEN DATEDIFF(c.last_contract, CURDATE()) < 0 THEN 3
                        ELSE 2
                    END AS id_color
                FROM tbl_users AS a
                    INNER JOIN cat_job_position AS b ON a.id_job_position = b.id
                    INNER JOIN cat_departament AS d ON a.id_departament = d.id_depto
                    JOIN (SELECT jt1.`option`, jt1.id_user, jt1.date_expiration AS last_contract
                        FROM tbl_user_type_of_contract AS jt1 WHERE jt1.active_status = 1 
                        AND jt1.date_of_new_entry = (SELECT MAX(sjt1.date_of_new_entry)
                            FROM tbl_user_type_of_contract AS sjt1
                            WHERE sjt1.active_status = 1 
                            AND sjt1.direct_authorization IN (1,0)
                            AND sjt1.id_user = jt1.id_user)
                        AND jt1.direct_authorization IN (1,0)
                    ) AS c ON c.id_user = a.id_user 
                WHERE a.id_user IN (SELECT DISTINCT id_user FROM tbl_users_temporary WHERE active_status = 1)
                $sqlData
            ORDER BY id_color DESC")->getResult();
            return (count($query)) ? json_encode($query) : json_encode('');
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function contractedUndefined()
    {
        try {
            $query = $this->db->query("SELECT a.id_contract, c.job, d.departament, b.payroll_number, DATE_FORMAT(e.last_contract, '%d / %m / %Y') AS fecha_limite, 
                CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS nombre, a.direct_authorization
                FROM tbl_user_type_of_contract AS a 
                    JOIN tbl_users AS b ON  a.id_user = b.id_user AND type_of_employee = 1
                LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
                LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
                                JOIN (SELECT jt1.id_user, jt1.date_expiration AS last_contract
                    FROM tbl_user_type_of_contract AS jt1 WHERE jt1.active_status = 1 
                    AND jt1.date_of_new_entry = (SELECT MAX(sjt1.date_of_new_entry)
                        FROM tbl_user_type_of_contract AS sjt1
                        WHERE sjt1.active_status = 1 
                        AND sjt1.`option` = 2
                AND sjt1.id_user = jt1.id_user)) AS e ON e.id_user = a.id_user 
                WHERE a.active_status = 1 AND a.`option` = 1 
                -- AND direct_authorization <> 2                                
                AND a.create_contract > '2023-10-10 15:04:16'
                ORDER BY a.id_contract DESC
            LIMIT 1000")->getResult();
            return json_encode($query ?? false);
        } catch (\Exception $e) {
            return (false);
        }
    }

    public function userType()
    {
        $user_type = trim($this->request->getPost('tipo_usuario'));

        $data = $this->db->query("SELECT id, job FROM cat_job_position 
        WHERE active_status = 1 AND type_of_employee = $user_type
        ORDER BY job ASC")->getResult();

        return ($data) ? json_encode($data) : 'error';
    }

    public function assign()
    {
        return ($this->is_logged) ?  view('admin/assign') : redirect()->to(site_url());
    }

    public function userDelete()
    {

        try {
            $id_user = trim($this->request->getPost('id_user'));
            $dataUser = $this->db->query("SELECT payroll_number FROM tbl_users WHERE id_user = $id_user")->getRow();

            $payroll_number = $dataUser->payroll_number;
            $dataPersonal = $this->db->query("SELECT num_nomina FROM tbl_users_personal_data WHERE num_nomina = $payroll_number")->getRowArray();
            if ($dataPersonal) {
                $this->db->query("UPDATE tbl_users_personal_data SET active_status = 2 WHERE num_nomina = $payroll_number");
            }
            $permissions_new = $this->db->query("SELECT id FROM tbl_assign_departments_to_managers_new WHERE payroll_number = $payroll_number OR id_manager = $id_user OR id_director = $id_user")->getRow();
            if ($permissions_new) {
                $this->db->query("UPDATE tbl_assign_departments_to_managers_new SET active_status = 2 WHERE payroll_number = $payroll_number OR id_manager = $id_user OR id_director = $id_user");
            }
            $service = $this->db->query("SELECT id_person FROM tbl_stationery_permissions WHERE payroll_number = $payroll_number")->getRow();
            if ($service) {
                $this->db->query("UPDATE tbl_stationery_permissions SET active_status = 2 WHERE id_person = $service->id_person");
            }
            $reqisitions = $this->db->query("SELECT id_notifica FROM tbl_requisitions_notifica_copy WHERE id_user = $id_user")->getRow();
            if ($reqisitions) {
                $this->db->query("UPDATE tbl_requisitions_notifica_copy SET active_status = 2 WHERE id_notifica = $reqisitions->id_notifica");
            }
            $reqisitionsAcc = $this->db->query("SELECT ID FROM tbl_requisiciones_access WHERE id_user = $id_user")->getRow();
            if ($reqisitionsAcc) {
                $this->db->query("UPDATE tbl_requisiciones_access SET `status` = 2 WHERE ID = $reqisitions->ID");
            }
            if ($this->db->query("SELECT TecnicoId FROM cat_ticket_tecnico WHERE TecnicoId = $id_user")->getRow()) {
                $this->db->query("UPDATE cat_ticket_tecnico SET Tecnico_Activo = 0 WHERE TecnicoId = $id_user");
                $this->db->query("UPDATE tbl_tickets_activity_manager SET active_status = 2 WHERE id_manager = $id_user");
            }

            $data = ['active_status' => 2];
            $result = $this->userModel->update($id_user, $data);
            return ($result) ? json_encode($result) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    /* edite manager
    public function dataUsers()
    {
        $payroll_number = trim($this->request->getPost('payroll_number'));
        $query = $this->db->query("SELECT `name` AS nombre, surname, second_surname, payroll_number FROM tbl_users WHERE payroll_number = $payroll_number;");
        $query1 = $this->db->query("SELECT id_user FROM tbl_users WHERE id_user IN
        (SELECT id_manager FROM tbl_assign_departments_to_managers_new WHERE payroll_number = $payroll_number)");
        $data = [
            'usuario' => $query->getResult(),
            'supervisor' => $query1->getResult()
        ];
        return (count($data) > 0) ? json_encode($data) : json_encode(false);
    } */

    public function updateManager()
    {
        $payroll_number = trim($this->request->getPost('payroll_number'));
        $id_manager = trim($this->request->getPost('id_manager'));
        $data = $this->db->query("SELECT id FROM tbl_assign_departments_to_managers_new WHERE payroll_number = $payroll_number")->getRow();

        if ($data != null) {
            $update = $this->db->query("UPDATE tbl_assign_departments_to_managers_new SET id_manager = $id_manager WHERE payroll_number = $payroll_number");
        } else {

            $update = $this->db->query("INSERT INTO tbl_assign_departments_to_managers_new (payroll_number,id_manager,active_status) VALUES ($payroll_number, $id_manager, 1);");
        }

        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function setRegisterUser()
    {

        $date = date("Y-m-d H:i:s");
        $binder =  '../public/doc/user';

        // Subir el archivo Excel
        if ($guieFile = $this->request->getFile('usuarios_excel')) {
            $originalName = $date . "_" . $guieFile->getClientName();
            $guieFile = $guieFile->move($binder,  $originalName);
            $xlsx = $binder . "/" . $originalName;
        } else {
            return json_encode(['error' => 'No se subió ningún archivo']);
        }

        $document = IOFactory::load($xlsx);    //preparamos documento para su lectura

        $sheet = $document->getSheet(0); // obtenemos la pagina una por una con un for
        $Rows = $sheet->getHighestDataRow(); //obternemos el numero maximo de FILAS que tengan datos

        for ($iRow = 2; $iRow <= $Rows; $iRow++) {
            // for ($iColum=0; $iColum < 12 ; $iColum++)  { // automatizar reccorrido de columnas
             $Numero_nomina = $sheet->getCellByColumnAndRow(1, $iRow);
           // echo "<br>";
            $Nombre = $sheet->getCellByColumnAndRow(2, $iRow);
            $Apellido_p = $sheet->getCellByColumnAndRow(3, $iRow);
            $Apellido_m = $sheet->getCellByColumnAndRow(4, $iRow);
            $Email = $sheet->getCellByColumnAndRow(5, $iRow);
            $Fecha_ingreso = $sheet->getCell('F' . $iRow)->getValue();
            $Puesto = $sheet->getCellByColumnAndRow(7, $iRow);
            $Departamento = $sheet->getCellByColumnAndRow(8, $iRow);
            $Centro_costo = $sheet->getCellByColumnAndRow(9, $iRow);
            $CURP = $sheet->getCellByColumnAndRow(10, $iRow);
            $NSS = $sheet->getCellByColumnAndRow(11, $iRow);
            $Jefe_directo = $sheet->getCellByColumnAndRow(12, $iRow);
            $Area_operativa = $sheet->getCellByColumnAndRow(13, $iRow);
            $Tipo_usuario = $sheet->getCellByColumnAndRow(14, $iRow);
            $Empresa = $sheet->getCellByColumnAndRow(15, $iRow);

            $Password = $sheet->getCellByColumnAndRow(16, $iRow);
            $Rol_usuario = $sheet->getCellByColumnAndRow(17, $iRow);
            $Grado = $sheet->getCellByColumnAndRow(18, $iRow);
            $Director = $sheet->getCellByColumnAndRow(19, $iRow);

              

                // Reemplaza uno o más espacios consecutivos por un solo espacio
                $Area_operativa = preg_replace('/\s+/', ' ', $Area_operativa);

            if (
                $Empresa == "" || $Nombre == "" || $Apellido_p == "" || $Apellido_m == "" || $NSS == "" || $CURP == "" ||
                $Email == "" || $Password == "" || $Numero_nomina == "" || $Fecha_ingreso == "" || $Departamento == "" ||
                $Area_operativa == "" || $Centro_costo == "" || $Tipo_usuario == "" || $Rol_usuario == "" || $Puesto == "" ||
                $Grado == "" ||  $Jefe_directo == "" || $Director == ""
            ) {
                return json_encode($iRow);
            }

            $fecha_ingreso = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($Fecha_ingreso)->format('Y-m-d');

            $tipo_usuario = 1;

            if (mb_strtoupper($Tipo_usuario) == "ADMINISTRATIVO" || mb_strtoupper($Tipo_usuario) == "ADMINISTRATIVOS") {
                $tipo_usuario = 1;
            }

            if (mb_strtoupper($Tipo_usuario) == "SINDICALIZADO" || mb_strtoupper($Tipo_usuario) == "SINDICALIZADOS" || mb_strtoupper($Tipo_usuario) == "AUXILIARES") {
                $tipo_usuario = 2;
            }

            switch (trim($Rol_usuario)) {
                case "ADMINISTRADOR":
                case "Administrador":
                case "administrador":
                    $rol_usuario = 1;
                    break;
                case "SINDICALIZADO":
                case "Sindicalizado":
                case "sindicalizado":
                case "Sindicalizados":
                    $rol_usuario = 2;
                    break;
                case "GERENTE":
                case "Gerente":
                case "gerente":
                    $rol_usuario = 3;
                    break;
                case "DIRECTOR":
                case "Director":
                case "director":
                    $rol_usuario = 4;
                    break;
                case "ADMINISTRATIVO":
                case "Administrativo":
                case "administrativo":
                    $rol_usuario = 5;
                    break;
                default:
                    $rol_usuario = 2;
                    break;
            }

            switch (trim($Empresa)) {
                case "INVAL":
                case "Inval":
                case "inval":
                case "INDUSTRIAL DE VALVULAS":
                case "INDUSTRIAL DE VALVULAS S.A. DE C.V.":
                case "INDUSTRIAL DE VALVULAS S.A. DE C.V":

                    $empresa = 4;
                    break;

                case "WALWORTH":
                case "Walworth":
                case "walworth":
                case "WALWORTH VALVULAS":
                case "WALWORTH VALVULAS S.A. DE C.V.":
                case "WALWORTH VALVULAS S.A. DE C.V":

                    $empresa = 1;
                    break;

                case "GRUPO WALWORTH":
                case "Grupo Walworth":
                case "Grupo walworth":
                case "grupo walworth":
                case "GRUPO WALWORTH S.A. DE C.V.":
                case "GRUPO WALWORTH S.A. DE C.V":

                    $empresa = 2;
                    break;

                default:
                    $empresa = 1;
                    break;
            }


            $jefe_directo = $this->db->query("SELECT id_user FROM tbl_users WHERE payroll_number = $Jefe_directo")->getRow();
            $director = $this->db->query("SELECT id_user FROM tbl_users WHERE payroll_number = $Director")->getRow();
            $departamento = $this->db->query("SELECT id_depto FROM cat_departament WHERE departament = '$Departamento'")->getRow();
            $centro_costo = $this->db->query("SELECT id_cost_center FROM cat_cost_center WHERE clave_cost_center = $Centro_costo")->getRow();
            $area_operativa = $this->db->query("SELECT id_area FROM cat_operational_area WHERE area = '$Area_operativa'")->getRow();
            $puesto = $this->db->query("SELECT id FROM cat_job_position WHERE job = '$Puesto' AND active_status = 1 ")->getRow();

            $Numero_nomina = strval($Numero_nomina);
            $Grado = strval($Grado);
            $NSS = strval($NSS);

           

            $dataUser = [
                'name' => mb_strtoupper($Nombre),
                'surname' => mb_strtoupper($Apellido_p),
                'second_surname' => mb_strtoupper($Apellido_m),
                'email' => strval($Email),
                'password' => strval($Password),
                'payroll_number' => intval($Numero_nomina),
                'date_admission' => $fecha_ingreso,
                'company' => strval($empresa),
                'id_departament' => intval($departamento->id_depto),
                'id_rol' => intval($rol_usuario),
                'id_job_position' => intval($puesto->id),
                'type_of_employee' => $tipo_usuario,
                'created_at' => $date,
                'active_status' => 1,
                'active_password' => 0,
                'user_registration' => session()->id_user,
                'id_cost_center' => $centro_costo->id_cost_center,
                'id_area_operativa' => $area_operativa->id_area,
                'contracts' => 1,
                'grado' => intval($Grado),
                'curp' => strval($CURP),
                'nss' => intval($NSS),

            ];

            //print_r($dataUser);

            //  return;

            $input_User = $this->userModel->insert($dataUser);




            $idUser = $this->db->insertID();
            if ($input_User) {
                $dataManager = [
                    'id_user' => $idUser,
                    'payroll_number' => intval($Numero_nomina),
                    'id_manager' => $jefe_directo->id_user,
                    'id_director' => $director->id_user,
                    'active_status' => 1,
                ];
                $this->managerModel->insert($dataManager);
            }
        }
        return ($input_User) ? json_encode(true) : json_encode(false);
    }

    public function usersMenu()
    {
        try {
            $payroll_number = trim($this->request->getPost('nomina'));
            $query = $this->db->query("SELECT id_user FROM tbl_users WHERE payroll_number = $payroll_number")->getRow();
            $query1 = $this->db->query("SELECT id_user, module_access FROM tbl_users_module WHERE status = 1 AND id_user = $query->id_user")->getResult();
            $query2 = $this->db->table('cat_modules')->select('*')->where('active', 1)->get()->getResult();
            $data = ['user' => $query, 'accesos' => $query1, 'modulos' => $query2];
            return json_encode($data);
        } catch (\Exception $e) {
            return json_encode(false);
        }
    }

    public function accessUpdate()
    {
        $to_day = "'" . date('Y-m-d') . "'";
        $id_user = trim($this->request->getPost('id_user'));
        $accessAll = $this->db->table('cat_modules')->select('id_module')->get()->getResult();
        $access_tbl = $this->db->table('tbl_users_module')->select('module_access')->where('id_user', $id_user)->get()->getResult();
        $array_tbl_access = [];
        foreach ($accessAll as $cat) {
            $access_form[$cat->id_module] = trim($this->request->getPost('menu_' . strval($cat->id_module)));
        }
        /* var_dump($access_form);
        var_dump($access_tbl); */
        foreach ($access_tbl as $tbl) {
            array_push($array_tbl_access, $tbl->module_access);
            var_dump($tbl->module_access);
            var_dump($access_form[$tbl->module_access]);
            if ($access_form[$tbl->module_access] != "") {
                $update = $this->db->query("UPDATE tbl_users_module SET `status` = 1, updated_at = $to_day WHERE id_user = $id_user AND module_access = '$tbl->module_access'");
                // print_r("ACCESO registrado a modulo " . $tbl->module_access . " activo en FORM, no gabra cambio");
            } else {
                $update = $this->db->query("UPDATE tbl_users_module SET `status` = 0, updated_at = $to_day WHERE id_user = $id_user AND module_access = '$tbl->module_access'");
                // print_r("ACCESO registrado a modulo " . $tbl->module_access . " Inactivo en FORM, status cambiara 0");
            }
        }
        $access_no_on_tbl = array_diff($access_form, $array_tbl_access);
        foreach ($access_no_on_tbl as $i) {
            if ($i != "") {
                $update = $this->db->query("INSERT INTO tbl_users_module (id_user, module_access, `status`, created_at) VALUES ( $id_user, $i, 1, $to_day)");
                // print_r("insert ".$access_form[$i]."  a usuario -> ".$id_user);
            }
        }

        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function emergencyContacts()
    {
        $id_datos = $this->request->getPost('id_datos');
        $query = $this->emergencyContactModel->select('*')->where('active_status', 1)->where('id_datos', $id_datos)->get()->getResult();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function personalData()
    {
        $payroll_number = session()->payroll_number;
        $query = $this->personalDataModel->select('*')->where('active_status', 1)->where('num_nomina', $payroll_number)->get()->getRow();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function personalDataSave()
    {
        $id_datos = trim($this->request->getPost('id'));
        $edad = trim($this->request->getPost('edad'));
        $escolaridad = trim($this->request->getPost('escolaridad'));
        $titulo = trim($this->request->getPost('titulo'));
        $diploma = trim($this->request->getPost('diploma'));
        $cursos = trim($this->request->getPost('cursos'));
        $genero = trim($this->request->getPost('genero'));
        $fecha_nacimiento = trim($this->request->getPost('fecha_nacimiento'));
        $edo_civil = trim($this->request->getPost('edo_civil'));
        $nombre_cony = trim($this->request->getPost('nombre_cony'));
        $edad_cony = trim($this->request->getPost('edad_cony'));
        $ocupacion_cony = trim($this->request->getPost('ocupacion_cony'));
        $cel_cony = trim($this->request->getPost('cel_cony'));
        $calle = trim($this->request->getPost('calle'));
        $num_int = trim($this->request->getPost('num_int'));
        $num_ext = trim($this->request->getPost('num_ext'));
        $cp = trim($this->request->getPost('cp'));
        $colonia = trim($this->request->getPost('colonia'));
        $municipio = trim($this->request->getPost('municipio'));
        $estado = trim($this->request->getPost('estado'));
        $num_nomina = session()->payroll_number;
        $sessionUser = session()->id_user;

        $updateData = [
            'edad_usuario' => $edad,
            'genero' => $genero,
            'fecha_nacimiento' => $fecha_nacimiento,
            'estado_civil' => $edo_civil,
            'estado' => $estado,
            'municipio' => $municipio,
            'colonia' => $colonia,
            'codigo_postal' => $cp,
            'calle' => $calle,
            'numero_exterior' => $num_ext,
            'numero_interior' => $num_int,
            'nombre_conyuge' => $nombre_cony,
            'edad_conyuge' => $edad_cony,
            'ocupacion_conyuge' => $ocupacion_cony,
            'tel_conyuge' => $cel_cony,
            'escolaridad' => $escolaridad,
            'lic_ing' => $titulo,
            'diplomados' => $diploma,
            'cursos_externos' => $cursos
        ];

        $update = $this->personalDataModel->update($id_datos, $updateData);
        if ($update) {
            $binder =  '../public/doc/comprobantes/' . session()->id_user;
            if (!file_exists($binder)) {
                mkdir($binder, 0777, true);
            }
            $dateFileUp = strval(date("Y-m-d_H_i_s"));
            $date = date("Y-m-d H:i:s");
            $doc_estudios = $this->request->getFile('doc_estudios');
            if ($doc_estudios != null) {
                $query = $this->db->query("SELECT id_doc FROM tbl_users_document WHERE tipo_document = 2 AND id_datos = $id_datos ORDER BY created_at DESC LIMIT 1")->getRow();
                $upDoc = ['active_status' => 2];
                $this->documentModel->update($query->id_doc, $upDoc);
                $newNameEs = "estudios_" . $sessionUser . "_" . $dateFileUp;
                $nameEs = $doc_estudios->getClientName();
                $doc_estudios = $doc_estudios->move($binder,  $newNameEs);
                $e_estudio = $binder . "/" . $newNameEs;
                $upEstudios = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 2,
                    'descripcion' => "Comprobante de " . $escolaridad,
                    'nombre_original' => $nameEs,
                    'ubicacion' => $e_estudio,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upEstudios);
            }
            $doc_domicilio = $this->request->getFile('doc_domicilio');
            if ($doc_domicilio != null) {
                $query = $this->db->query("SELECT id_doc FROM tbl_users_document WHERE tipo_document = 1 AND id_datos = $id_datos ORDER BY created_at DESC LIMIT 1")->getRow();
                $upDoc = ['active_status' => 2];
                $this->documentModel->update($query->id_doc, $upDoc);
                $newNameDo = "domicilio_" . $sessionUser . "_" . $dateFileUp;
                $nameDo = $doc_domicilio->getClientName();
                $doc_domicilio = $doc_domicilio->move($binder,  $newNameDo);
                $e_domicilio = $binder . "/" . $newNameDo;
                $upDomicilio = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 1,
                    'descripcion' => "Comprobante de Domicilio",
                    'nombre_original' => $nameDo,
                    'ubicacion' => $e_domicilio,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upDomicilio);
            }
        }
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function emergencyContact()
    {
        $payroll_number = session()->payroll_number;
        $query = $this->emergencyContactModel->select('*')->where('active_status', 1)->where('num_nomina', $payroll_number)->get()->getResult();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function emergencyContactSave()
    {
        $id_ = $this->request->getPost('id_');
        $contac_nombre = $this->request->getPost('contac_nombre_');
        $contac_pariente = $this->request->getPost('contac_pariente_');
        $contac_tel = $this->request->getPost('contac_tel_');
        for ($i = 0; $i < count($contac_nombre); $i++) {
            $updateData = [
                'contacto_emergencia' => $contac_nombre[$i],
                'tel_emergencia' => $contac_tel[$i],
                'parentesco_emergencia' => $contac_pariente[$i]
            ];
            $update = $this->emergencyContactModel->update($id_[$i], $updateData);
        }
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function parent()
    {
        $payroll_number = session()->payroll_number;
        $query = $this->childrenModel->select('*')->where('active_status', 1)->where('num_nomina', $payroll_number)->get()->getResult();
        $query0 = $this->parentsModel->select('*')->where('active_status', 1)->where('num_nomina', $payroll_number)->get()->getResult();
        $data = ['hijos' => $query, 'padres' => $query0];
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function parentSave()
    {
        $padres_id = $this->request->getPost('padres_id_');
        $padres_nombre = $this->request->getPost('padres_nombre_');
        $padres_fecha = $this->request->getPost('padres_fecha_');
        $padres_genero = $this->request->getPost('padres_genero_');
        $padres_finado = $this->request->getPost('padres_finado_');
        $padres_edad = $this->request->getPost('padres_edad_');
        for ($i = 0; $i < 2; $i++) {
            $dateParents = [
                'nombre_padres' => $padres_nombre[$i],
                'fecha_nacimiento_padres' => $padres_fecha[$i],
                'genero_padres' => $padres_genero[$i],
                'finado' => $padres_finado[$i],
                'edad' => $padres_edad[$i]
            ];
            $updateParents = $this->parentsModel->update($padres_id[$i], $dateParents);
        }
        $Son = $this->request->getPost('cantidad_hijos');
        if ($Son > 0) {
            $hijos_id = $this->request->getPost('hijos_id_');
            $hijos_nombre = $this->request->getPost('hijos_nombre_');
            $hijos_fecha = $this->request->getPost('hijos_fecha_');
            $hijos_genero = $this->request->getPost('hijos_genero_');
            $hijos_edad = $this->request->getPost('hijos_edad_');
            $id_datos = $this->request->getPost('id_datos');
            $inomina = $this->request->getPost('id_nomina');

            for ($iSon = 0; $iSon < $Son; $iSon++) {
                if ($hijos_id[$iSon] != "") {
                    $dataChildren = [
                        'nombre_hijo' => $hijos_nombre[$iSon],
                        'fecha_nacimiento' => $hijos_fecha[$iSon],
                        'edad_hijo' => $hijos_edad[$iSon],
                        'genero' => $hijos_genero[$iSon],
                    ];
                    $children = $this->childrenModel->update($hijos_id[$iSon], $dataChildren);
                } else {
                    $dataChildren = [
                        'id_datos' => $id_datos,
                        'num_nomina' => $inomina,
                        'nombre_hijo' => $hijos_nombre[$iSon],
                        'fecha_nacimiento' => $hijos_fecha[$iSon],
                        'edad_hijo' => $hijos_edad[$iSon],
                        'genero' => $hijos_genero[$iSon],
                        'created_at' => date("Y-m-d H:i:s"),
                        'active_status' => 1,
                    ];
                    $children = $this->childrenModel->insert($dataChildren);
                }
            }
        } else {
            $children = true;
        }
        return ($updateParents && $children) ? json_encode(true) : json_encode(false);
    }

    public function documents()
    {
        $payrol = session()->payroll_number;
        $estudios = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 2 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $acta = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 3 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $ingles = $this->db->query("SELECT id_doc, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 6 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $curp = $this->db->query("SELECT id_doc, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 7 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $rfc = $this->db->query("SELECT id_doc, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 8 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $cv = $this->db->query("SELECT id_doc, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 9 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $diploma = $this->db->query("SELECT id_doc, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 4 AND num_nomina = $payrol  AND active_status = 1")->getResult();
        $cursos = $this->db->query("SELECT id_doc, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 5 AND num_nomina = $payrol  AND active_status = 1")->getResult();
        $data = [
            "estudios" => $estudios,
            "acta" => $acta,
            "ingles" => $ingles,
            "curp" => $curp,
            "rfc" => $rfc,
            "cv" => $cv,
            "diploma" => $diploma,
            "cursos" => $cursos,
        ];
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function documentsSave()
    {
        $id_datos = $this->request->getPost('id_datos_doc');
        $num_nomina = session()->payroll_number;
        $sessionUser = session()->id_user;
        $dateFileUp = strval(date("Y-m-d_H_i_s"));
        $date = date("Y-m-d H:i:s");
        $binder =  '../public/doc/comprobantes/' . session()->id_user;
        if (!file_exists($binder)) {
            mkdir($binder, 0777, true);
        }

        try {
            $acta = $this->request->getFile('doc_acta');
            if ($acta != null) {
                $idActaOld = $this->request->getPost('id_doc_acta');
                $curp = $this->request->getPost('curp');
                $upActaOld = ['active_status' => 2];
                $this->documentModel->update($idActaOld, $upActaOld);
                $newNameAc = "acta_" . $sessionUser . "_" . $dateFileUp;
                $nameAc = $acta->getClientName();
                $acta = $acta->move($binder,  $newNameAc);
                $e_acta = $binder . "/" . $newNameAc;
                $upActa = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 3,
                    'descripcion' => "Acta de Nacimiento",
                    'nombre_original' => $nameAc,
                    'ubicacion' => $e_acta,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upActa);
            }

            $curp = $this->request->getFile('doc_curp');
            if ($curp != null) {
                $curpData = mb_strtoupper($this->request->getPost('curp'), 'UTF-8');
                $idcurpOld = $this->request->getPost('id_doc_curp');
                $upcurpOld = ['active_status' => 2];
                $this->documentModel->update($idcurpOld, $upcurpOld);
                $newNameCurp = "curp_" . $sessionUser . "_" . $dateFileUp;
                $nameCurp = $curp->getClientName();
                $curp = $curp->move($binder,  $newNameCurp);
                $e_curp = $binder . "/" . $newNameCurp;
                $upcurp = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 7,
                    'descripcion' => $curpData,
                    'nombre_original' => $nameCurp,
                    'ubicacion' => $e_curp,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upcurp);
            }

            $rfc = $this->request->getFile('doc_rfc');
            if ($rfc != null) {
                $rfcData  = mb_strtoupper($this->request->getPost('rfc'), 'UTF-8');
                $idrfcOld = $this->request->getPost('id_doc_rfc');
                $uprfcOld = ['active_status' => 2];
                $this->documentModel->update($idrfcOld, $uprfcOld);
                $newNamerfc = "rfc_" . $sessionUser . "_" . $dateFileUp;
                $namerfc = $rfc->getClientName();
                $rfc = $rfc->move($binder,  $newNamerfc);
                $e_rfc = $binder . "/" . $newNamerfc;
                $uprfc = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 8,
                    'descripcion' => $rfcData,
                    'nombre_original' => $namerfc,
                    'ubicacion' => $e_rfc,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($uprfc);
                $upPersonal = ['rfc' => $rfcData,];
                $this->personalDataModel->update($id_datos, $upPersonal);
            }

            $doc_ingles = $this->request->getFile('doc_ingles');
            if ($doc_ingles != null) {
                $idIngOld = $this->request->getPost('id_doc_ingles');
                if ($idIngOld != "") {
                    $upIngOld = ['active_status' => 2];
                    $this->documentModel->update($idIngOld, $upIngOld);
                }
                $newNameIng = "ing_" . $sessionUser . "_" . $dateFileUp;
                $nameIng = $doc_ingles->getClientName();
                $doc_ingles = $doc_ingles->move($binder,  $newNameIng);
                $e_ing = $binder . "/" . $newNameIng;
                $upIng = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 6,
                    'descripcion' => "Comprobante de Ingles",
                    'nombre_original' => $nameIng,
                    'ubicacion' => $e_ing,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upIng);
            }
            $doc_cv = $this->request->getFile('doc_cv');
            if ($doc_cv != null) {
                $idCvOld = $this->request->getPost('id_doc_cv');
                if ($idCvOld != "") {
                    $upCvOld = ['active_status' => 2];
                    $this->documentModel->update($idCvOld, $upCvOld);
                }
                $newNameCv = "Curriculum_" . $sessionUser . "_" . $dateFileUp;
                $nameCv = $doc_cv->getClientName();
                $doc_cv = $doc_cv->move($binder,  $newNameCv);
                $e_cv = $binder . "/" . $newNameCv;
                $upCv = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 9,
                    'descripcion' => "Curriculum",
                    'nombre_original' => $nameCv,
                    'ubicacion' => $e_cv,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upCv);
            }

            $cursos = $this->request->getPost('id_cusos_'); //tipo 5
            if ($cursos != null) {
                $contC = count($cursos);
                for ($iC = 0; $iC < $contC; $iC++) {
                    $docCurso = "doc_curso_" . strval($cursos[$iC]);
                    $tittleCurso = "curso_" . strval($cursos[$iC]);
                    $doc_curso = $this->request->getFile($docCurso);
                    $curso = $this->request->getPost($tittleCurso);
                    $newNameCurso = "curso_" . $sessionUser . "_" . $dateFileUp;
                    $nameCurso = $doc_curso->getClientName();
                    $doc_curso = $doc_curso->move($binder,  $newNameCurso);
                    $e_curso = $binder . "/" . $newNameCurso;
                    $upCurso = [
                        'id_datos' => $id_datos,
                        'num_nomina' => $num_nomina,
                        'tipo_document' => 5,
                        'descripcion' => "Comprobante de " . $curso,
                        'nombre_original' => $nameCurso,
                        'ubicacion' => $e_curso,
                        'created_at' => $date,
                        'active_status' => 1,
                    ];
                    $this->documentModel->insert($upCurso);
                }
            }

            $estudios = $this->request->getFile('doc_estudios');
            if ($estudios != null) {
                $info = $this->db->query("SELECT escolaridad FROM tbl_users_personal_data WHERE num_nomina = $num_nomina AND active_status = 1")->getRow();
                $idEstudiosOld = $this->request->getPost('id_doc_estudios');
                $upEstudiosOld = ['active_status' => 2];
                $this->documentModel->update($idEstudiosOld, $upEstudiosOld);
                $newNameEst = "estudios_" . $sessionUser . "_" . $dateFileUp;
                $nameEst = $estudios->getClientName();
                $estudios = $estudios->move($binder,  $newNameEst);
                $e_estudios = $binder . "/" . $newNameEst;
                $upEstudios = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 2,
                    'descripcion' => "Comprobante de " . $info->escolaridad,
                    'nombre_original' => $nameEst,
                    'ubicacion' => $e_estudios,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upEstudios);
            }

            $diplomas = $this->request->getPost('id_diploma_'); // tipo 4
            if ($diplomas != null) {
                $contD = count($diplomas);
                for ($iD = 0; $iD < $contD; $iD++) {
                    $docDiploma = "doc_diploma_" . $diplomas[$iD];
                    $tittleDiploma = "diploma_" . $diplomas[$iD];
                    $doc_diploma = $this->request->getFile($docDiploma);
                    $tt_diploma = $this->request->getPost($tittleDiploma);
                    $nameDiploma = $doc_diploma->getClientName();
                    $newNameDiploma = "diploma_" . $sessionUser . "_" . $dateFileUp;
                    $doc_diploma = $doc_diploma->move($binder,  $newNameDiploma);
                    $e_diploma = $binder . "/" . $newNameDiploma;
                    $upDiploma = [
                        'id_datos' => $id_datos,
                        'num_nomina' => $num_nomina,
                        'tipo_document' => 4,
                        'descripcion' => "Comprobante de " . $tt_diploma,
                        'nombre_original' => $nameDiploma,
                        'ubicacion' => $e_diploma,
                        'created_at' => $date,
                        'active_status' => 1,
                    ];
                    $this->documentModel->insert($upDiploma);
                }
            }
            return json_encode(true);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function informationUserALL()
    {
        $id_user = $this->request->getPost('id_user');
        $personal = $this->db->query("SELECT * FROM tbl_users_personal_data WHERE id_datos = $id_user AND active_status = 1")->getRow();
        $parent = $this->db->query("SELECT * FROM tbl_users_parents WHERE id_datos = $id_user AND active_status = 1")->getResult();
        $son = $this->db->query("SELECT * FROM tbl_users_children WHERE id_datos = $id_user AND active_status = 1")->getResult();
        $data = ['personal' => $personal, 'padres' => $parent, 'hijos' => $son];
        return json_encode($data);
    }
    public function checkDocument()
    {
        $payrol = session()->payroll_number;
        $acta = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 3 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $domicilio = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 1 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $estudios = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 2 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $rfc = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 8 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $curp = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 7 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
        $lvl = $this->db->query("SELECT escolaridad FROM tbl_users_personal_data WHERE num_nomina = $payrol")->getRow();
        $data = [
            "acta" => $acta,
            "rfc" => $rfc,
            "curp" => $curp,
            'domicilio' => $domicilio,
            'estudios' => $estudios,
            'lvl_estudio' => $lvl,
        ];
        return json_encode($data);
    }

    public function checkDocumentSave()
    {
        $num_nomina = session()->payroll_number;
        $sessionUser = session()->id_user;
        $query =  $this->db->query("SELECT id_datos FROM tbl_users_personal_data WHERE num_nomina = $num_nomina")->getRow();
        $id_datos = $query->id_datos;
        $dateFileUp = strval(date("Y-m-d_H_i_s"));
        $date = date("Y-m-d_H:i:s");
        $binder =  '../public/doc/comprobantes/' . session()->id_user;
        if (!file_exists($binder)) {
            mkdir($binder, 0777, true);
        }
        try {
            $acta = $this->request->getFile('doc_acta_m');
            $newNameAc = "acta_" . $sessionUser . "_" . $dateFileUp;
            $nameAc = $acta->getClientName();
            $acta = $acta->move($binder,  $newNameAc);
            $e_acta = $binder . "/" . $newNameAc;
            $upActa = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 3,
                'descripcion' => "Acta de Nacimiento",
                'nombre_original' => $nameAc,
                'ubicacion' => $e_acta,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $insertActa = $this->documentModel->insert($upActa);

            $doc_domicilio = $this->request->getFile('doc_domicilio_m');
            $newNameDo = "domicilio_" . $sessionUser . "_" . $dateFileUp;
            $nameDo = $doc_domicilio->getClientName();
            $doc_domicilio = $doc_domicilio->move($binder,  $newNameDo);
            $e_domicilio = $binder . "/" . $newNameDo;
            $upDomicilio = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 1,
                'descripcion' => "Comprobante de Domicilio",
                'nombre_original' => $nameDo,
                'ubicacion' => $e_domicilio,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $insertDomicilio = $this->documentModel->insert($upDomicilio);

            $doc_estudios = $this->request->getFile('doc_estudios_m');
            $escolaridad =  $this->db->query("SELECT escolaridad FROM tbl_users_personal_data WHERE id_datos = $id_datos")->getRow();
            $newNameEs = "estudios_" . $sessionUser . "_" . $dateFileUp;
            $nameEs = $doc_estudios->getClientName();
            $doc_estudios = $doc_estudios->move($binder,  $newNameEs);
            $e_estudio = $binder . "/" . $newNameEs;
            $upEstudios = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 2,
                'descripcion' => "Comprobante de " . $escolaridad->escolaridad,
                'nombre_original' => $nameEs,
                'ubicacion' => $e_estudio,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $insertEstudio = $this->documentModel->insert($upEstudios);

            $docCURP = $this->request->getFile('doc_curp_m'); //tipo 7
            $curp = mb_strtoupper(trim($this->request->getPost('curp_m')), 'UTF-8');
            $newName = "CURP_" . $sessionUser . "_" . $dateFileUp;
            $nameCURP = $docCURP->getClientName();
            $docCURP = $docCURP->move($binder,  $newName);
            $e_CRUP = $binder . "/" . $newName;
            $upCURP = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 7,
                'nombre_original' => $nameCURP,
                'descripcion' => $curp,
                'ubicacion' => $e_CRUP,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $this->documentModel->insert($upCURP);
            $docRFC = $this->request->getFile('doc_rfc_m'); //tipo 8
            $rfc = mb_strtoupper(trim($this->request->getPost('rfc_m')), 'UTF-8');
            $newName = "RFC_" . $sessionUser . "_" . $dateFileUp;
            $nameRFC = $docRFC->getClientName();
            $docRFC = $docRFC->move($binder,  $newName);
            $e_RFC = $binder . "/" . $newName;
            $upRFC = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 8,
                'nombre_original' => $nameRFC,
                'descripcion' => $rfc,
                'ubicacion' => $e_RFC,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $this->documentModel->insert($upRFC);

            $upPersonal = [
                'curp' => $curp,
                'rfc' => $rfc,
            ];
            $this->personalDataModel->update($id_datos, $upPersonal);

            return ($insertActa && $insertDomicilio && $insertEstudio) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function documentsALL()
    {
        $id_datos = intval($this->request->getPost('id_datos'));
        $query = $this->db->query("SELECT * FROM tbl_users_document WHERE id_datos = $id_datos AND active_status = 1")->getResult();
        return ($query) ? json_encode($query) : json_encode(false);
    }


    public function reportGeneralData()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $NombreArchivo = "info_usuario_$data->id_datos.xlsx";
        $cont_emer2 = 11;
        $cont_hijos2 = 15;
        $cont_padres2 = 22;
        $spreadsheet = new Spreadsheet();
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

        $query = $this->db->query("SELECT * FROM tbl_users_personal_data WHERE id_datos = $data->id_datos AND active_status = 1 ")->getRow();

        $sheet = $spreadsheet->getActiveSheet();
        $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
        $sheet->getStyle("A1:K1")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:K1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:K1")->getBorders()->getTop()->setColor($color);
        $spreadsheet->getActiveSheet()->getStyle("A1:K1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');

        $sheet->getStyle("A4:G4")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A4:G4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A4:G4")->getBorders()->getTop()->setColor($color);
        $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');

        $sheet->getStyle("A7:E7")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A7:E7")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A7:E7")->getBorders()->getTop()->setColor($color);
        $spreadsheet->getActiveSheet()->getStyle("A7:E7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');

        $sheet->setTitle("Reporte Datos Generales");

        $sheet->setCellValue('A1', 'NOMINA');
        $sheet->setCellValue('B1', 'NOMBRE(S)');
        $sheet->setCellValue('C1', 'APELLIDO PATERNO');
        $sheet->setCellValue('D1', 'APELLIDO MATERNO');
        $sheet->setCellValue('E1', 'CURP');
        $sheet->setCellValue('F1', 'EDAD');
        $sheet->setCellValue('G1', 'FECHA DE NACIMIENTO');
        $sheet->setCellValue('H1', 'ESCOLARIDAD');
        $sheet->setCellValue('I1', 'TITULO');
        $sheet->setCellValue('J1', 'FECHA INGRESO');
        $sheet->setCellValue('K1', 'RFC');
        $sheet->setCellValue('A4', 'CALLE');
        $sheet->setCellValue('B4', 'NO. INTERIOR');
        $sheet->setCellValue('C4', 'NO. EXTERIOR');
        $sheet->setCellValue('D4', 'COLONIA');
        $sheet->setCellValue('E4', 'MUNICIPIO');
        $sheet->setCellValue('F4', 'ESTADO');
        $sheet->setCellValue('G4', 'CODIGO POSTAL');
        $sheet->setCellValue('A7', 'ESTADO CIVIL');
        $sheet->setCellValue('B7', 'NOMBRE CONYUGE');
        $sheet->setCellValue('C7', 'EDAD');
        $sheet->setCellValue('D7', 'OCUPACION');
        $sheet->setCellValue('E7', 'TELEFONO');

        $nombre_conyuge = ($query->nombre_conyuge != '') ? mb_strtoupper($query->nombre_conyuge) : "-";
        $edad_conyuge = ($query->edad_conyuge != 0) ? $query->edad_conyuge : "-";
        $ocupacion_conyuge = ($query->ocupacion_conyuge != '') ? mb_strtoupper($query->ocupacion_conyuge) : "-";
        $tel_conyuge = ($query->tel_conyuge != '') ? $query->tel_conyuge : "-";
        $sheet->setCellValue('A2', mb_strtoupper($query->num_nomina));
        $sheet->setCellValue('B2', mb_strtoupper($query->nombre));
        $sheet->setCellValue('C2', mb_strtoupper($query->ape_paterno));
        $sheet->setCellValue('D2', mb_strtoupper($query->ape_materno));
        $sheet->setCellValue('E2', mb_strtoupper($query->curp));
        $sheet->setCellValue('F2', $query->edad_usuario);
        $sheet->setCellValue('G2', date("d/m/Y", strtotime($query->fecha_nacimiento)));
        $sheet->setCellValue('H2', mb_strtoupper($query->escolaridad));
        $sheet->setCellValue('I2', mb_strtoupper($query->lic_ing));
        $sheet->setCellValue('J2', date("d/m/Y", strtotime($query->fecha_ingreso)));
        $sheet->setCellValue('K2', mb_strtoupper($query->rfc));
        $sheet->setCellValue('A5', mb_strtoupper($query->calle));
        $sheet->setCellValue('B5', mb_strtoupper($query->numero_interior));
        $sheet->setCellValue('C5', mb_strtoupper($query->numero_exterior));
        $sheet->setCellValue('D5', mb_strtoupper($query->colonia));
        $sheet->setCellValue('E5', mb_strtoupper($query->municipio));
        $sheet->setCellValue('F5', mb_strtoupper($query->estado));
        $sheet->setCellValue('G5', mb_strtoupper($query->codigo_postal));
        $sheet->setCellValue('A8', mb_strtoupper($query->estado_civil));
        $sheet->setCellValue('B8', $nombre_conyuge);
        $sheet->setCellValue('C8', $edad_conyuge);
        $sheet->setCellValue('D8', $ocupacion_conyuge);
        $sheet->setCellValue('E8', $tel_conyuge);

        $sheet->getStyle("A10:C10")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A10:C10")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("A10:C10")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $sheet->getStyle("A10:C10")->getBorders()->getTop()->setColor($color);

        $sheet->setCellValue('A10', 'CONTRATO DE EMERGENCIA');
        $sheet->setCellValue('B10', 'TELÉFONO');
        $sheet->setCellValue('C10', 'PARENTESCO');

        $emergencia = $this->db->query("SELECT  a.contacto_emergencia,a.tel_emergencia,a.parentesco_emergencia FROM tbl_users_emergency_contact AS a WHERE a.id_datos = $data->id_datos AND active_status = 1")->getResult();
        foreach ($emergencia as $key => $value) {

            $sheet->setCellValue('A' . $cont_emer2, mb_strtoupper($value->contacto_emergencia));
            $sheet->setCellValue('B' . $cont_emer2, $value->tel_emergencia);
            $sheet->setCellValue('C' . $cont_emer2, mb_strtoupper($value->parentesco_emergencia));
            $cont_emer2++;
        }

        $sheet->getStyle("A14:D14")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A14:D14")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("A14:D14")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $sheet->getStyle("A14:D14")->getBorders()->getTop()->setColor($color);

        $sheet->setCellValue('A14', 'NOMBRE(S) HIJO(S)');
        $sheet->setCellValue('B14', 'FECHA NACIMIENTO');
        $sheet->setCellValue('C14', 'EDAD');
        $sheet->setCellValue('D14', 'GENERO');

        $hijos = $this->db->query("SELECT  nombre_hijo,fecha_nacimiento,edad_hijo,genero FROM tbl_users_children WHERE id_datos = $data->id_datos AND active_status = 1")->getResult();
        for ($i = 0; $i < 5; $i++) {
            if (isset($hijos[$i]->nombre_hijo)) {
                $sheet->setCellValue('A' . $cont_hijos2, mb_strtoupper($hijos[$i]->nombre_hijo));
                $sheet->setCellValue('B' . $cont_hijos2, date("d/m/Y", strtotime($hijos[$i]->fecha_nacimiento)));
                $sheet->setCellValue('C' . $cont_hijos2, $hijos[$i]->edad_hijo);
                $sheet->setCellValue('D' . $cont_hijos2, mb_strtoupper($hijos[$i]->genero));
                $cont_hijos2++;
            } else {
                $sheet->setCellValue('A' . $cont_hijos2, '-');
                $sheet->setCellValue('B' . $cont_hijos2, '-');
                $sheet->setCellValue('C' . $cont_hijos2, '-');
                $sheet->setCellValue('D' . $cont_hijos2, '-');
                $cont_hijos2++;
            }
        }

        $sheet->getStyle("A21:E21")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A21:E21")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("A21:E21")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
        $sheet->getStyle("A21:E21")->getBorders()->getTop()->setColor($color);

        $sheet->setCellValue('A21', 'NOMBRE DE PADRES');
        $sheet->setCellValue('B21', 'FECHA DE NACIMIENTO');
        $sheet->setCellValue('C21', 'GENERO');
        $sheet->setCellValue('D21', 'ESTADO');
        $sheet->setCellValue('E21', 'EDAD');

        $padres = $this->db->query("SELECT nombre_padres,fecha_nacimiento_padres,genero_padres,finado,edad FROM tbl_users_parents WHERE id_datos = $data->id_datos AND active_status = 1")->getResult();
        foreach ($padres as $key => $value) {

            $sheet->setCellValue('A' . $cont_padres2, mb_strtoupper($value->nombre_padres));
            $sheet->setCellValue('B' . $cont_padres2, date("d/m/Y", strtotime($value->fecha_nacimiento_padres)));
            $sheet->setCellValue('C' . $cont_padres2, mb_strtoupper($value->genero_padres));
            $sheet->setCellValue('D' . $cont_padres2, mb_strtoupper($value->finado));
            $sheet->setCellValue('E' . $cont_padres2, $value->edad);
            $cont_padres2++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save($NombreArchivo);
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . basename($NombreArchivo));
        header("Expires:0");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: public");
        header("Content-Length:" . filesize($NombreArchivo));
        flush();
        readfile($NombreArchivo);
        exit;
    }

    public function pdfUserContract($id_contract = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT a.create_contract, b.type_of_employee, direct_authorization,
        CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS manager,
        cMg.departament AS deptoManager, a.date_expiration,
        CASE 
           WHEN a.`option` = 1 THEN 'PLANTA'
           WHEN a.type_of_contract = 2 THEN '30 DÍAS'
           WHEN a.type_of_contract = 3 THEN '60 DÍAS'
           WHEN a.type_of_contract = 4 THEN '90 DÍAS'
           WHEN a.`option` = 3 THEN 'BAJA'
           ELSE 'ERROR' 
        END AS type_contract,
        CONCAT(bUs.`name`,' ',bUs.surname,' ',bUs.second_surname) AS usuario,
        cUs.departament AS deptoUser,
        bUs.date_admission, a.date_of_new_entry, a.date_reing,
        CASE 
           WHEN a.`option` = 1 THEN ''
           WHEN a.type_of_contract = 2 THEN ', de <b>30 DÍAS</b>'
           WHEN a.type_of_contract = 3 THEN ', de <b>60 DÍAS</b>'
           WHEN a.type_of_contract = 4 THEN ', de <b>90 DÍAS</b>'
           WHEN a.`option` = 3 THEN ''
           ELSE 'ERROR' 
        END AS typeContractOption,
        (SELECT CONCAT(ct1.`name`,' ',ct1.surname,' ',ct1.second_surname) FROM tbl_users AS ct1 WHERE ct1.id_user = a.id_direct_authorization) AS name_director, 
        a.`option`, a.cause_of_termination, a.observations
        FROM tbl_user_type_of_contract AS a
            JOIN tbl_users AS b ON b.id_user = a.id_manager
            JOIN cat_departament AS cMg ON cMg.id_depto = b.id_departament
            JOIN tbl_users AS bUs ON bUs.id_user = a.id_user
            JOIN cat_departament AS cUs ON cUs.id_depto = bUs.id_departament
        WHERE MD5(concat('" . $key . "',a.id_contract))='" . $id_contract . "'")->getRow();

        $data = [
            "contract" => $query,
        ];

        $html2 = view('pdf/pdf_contract_temp', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'USLETTER', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Contrato');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('contracto_temporal.pdf', 'I');
    }

    public function generateContractReportsXlsx()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $fecha_inicio = $data->fecha_inicio;
        $fecha_fin = $data->fecha_fin;
        $categoria = $data->categoria;
        $NombreArchivo = "Contratos_" . $fecha_inicio . "_" . $fecha_fin . ".xlsx";

        $cont = 2;
        $spreadsheet = new Spreadsheet();

        if ($categoria == 4) {
            // $query = $this->db->query("CALL reportContractsByTypeAll('$fecha_inicio','$fecha_fin')")->getResult();
            $query = $this->db->query("SELECT a.id_contract, a.date_expiration, b.`name`,b.surname,b.second_surname,c.job,d.departament, e.`name` AS name_m,e.surname AS surname_m, e.second_surname AS second_surname_m,
            CASE 
             WHEN a.type_of_contract = 2 THEN '30 DÍAS'
             WHEN a.type_of_contract = 3 THEN '60 DÍAS'
             WHEN a.type_of_contract = 4 THEN '90 DÍAS'
             ELSE 'ERROR' 
            END AS type_of_contract,
            CASE 
            WHEN b.type_of_employee = 1 THEN 'ADMINISTRATIVOS'
            WHEN b.type_of_employee = 2 THEN 'SINDICALIZADOS'
            ELSE 'ERROR'
            END AS type_employe
            
            FROM tbl_user_type_of_contract AS a
            LEFT JOIN tbl_users AS b ON  a.id_user = b.id_user
            LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
            LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
            JOIN tbl_users AS e ON a.id_manager = e.id_user
            
            WHERE (a.date_expiration BETWEEN '$fecha_inicio' AND '$fecha_fin') AND a.active_status = 1
            AND (a.type_of_contract = 2 OR a.type_of_contract = 3 OR a.type_of_contract = 4)")->getResult();
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:H1');
            $sheet->setTitle("Todos Contrados Temporales");


            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

            // ANCHO DE CELDA
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(11);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(18);

            //UBICACION DEL TEXTO
            $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet->getStyle('A1:H1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet->getStyle("A1:H1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet->setCellValue('A1', 'FOLIO');
            $sheet->setCellValue('B1', 'TERMINO');
            $sheet->setCellValue('C1', 'NOMBRE EMPLEADO');
            $sheet->setCellValue('D1', 'JEFE DIRECTO');
            $sheet->setCellValue('E1', 'PUESTO');
            $sheet->setCellValue('F1', 'DEPARTAMENTO');
            $sheet->setCellValue('G1', 'TIPO');
            $sheet->setCellValue('H1', 'EMPLEADO');

            foreach ($query as $key => $value) {
                $sheet->setCellValue('A' . $cont, $value->id_contract);
                $sheet->setCellValue('B' . $cont, date("d/m/Y", strtotime($value->date_expiration)));
                $sheet->setCellValue('C' . $cont, $value->name . " " . $value->surname . " " . $value->second_surname);
                $sheet->setCellValue('D' . $cont, $value->name_m . " " . $value->surname_m . " " . $value->second_surname_m);
                $sheet->setCellValue('E' . $cont, $value->job);
                $sheet->setCellValue('F' . $cont, $value->departament);
                $sheet->setCellValue('G' . $cont, $value->type_of_contract);
                $sheet->setCellValue('H' . $cont, $value->type_employe);
                $cont++;
            }
        } else {
            if ($categoria == 1 || $categoria == 2) {
                $type = $categoria;
                $company = 1;
            } else {
                $type = 1;
                $company = 2;
            }
            // $query = $this->db->query("CALL reportContractsByType($sqlData)")->getResult();
            $query = $this->db->query("SELECT a.id_contract, a.date_expiration, b.`name`,b.surname,b.second_surname,c.job,d.departament, e.`name` AS name_m,e.surname AS surname_m, e.second_surname AS second_surname_m,
            CASE 
             WHEN a.type_of_contract = 2 THEN '30 DÍAS'
             WHEN a.type_of_contract = 3 THEN '60 DÍAS'
             WHEN a.type_of_contract = 4 THEN '90 DÍAS'
             ELSE 'ERROR' 
            END AS type_of_contract,
            CASE 
            WHEN b.type_of_employee = 1 THEN 'ADMINISTRATIVOS'
            WHEN b.type_of_employee = 2 THEN 'SINDICALIZADOS'
            ELSE 'ERROR'
            END AS type_employe
            
            FROM tbl_user_type_of_contract AS a
            LEFT JOIN tbl_users AS b ON  a.id_user = b.id_user
            LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
            LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
            JOIN tbl_users AS e ON a.id_manager = e.id_user
            
            WHERE (a.date_expiration BETWEEN '$fecha_inicio' AND '$fecha_fin') AND a.active_status = 1
            AND (a.type_of_contract = 2 OR a.type_of_contract = 3 OR a.type_of_contract = 4)
            AND  b.type_of_employee = $type AND b.company = $company")->getResult();
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:G1');
            $sheet->setTitle("Contrados Temporales");

            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

            // ANCHO DE CELDA
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(11);
            // $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12);

            //UBICACION DEL TEXTO
            $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $spreadsheet->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet->getStyle("A1:G1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet->setCellValue('A1', 'FOLIO');
            $sheet->setCellValue('B1', 'TERMINO');
            $sheet->setCellValue('C1', 'NOMBRE EMPLEADO');
            $sheet->setCellValue('D1', 'JEFE DIRECTO');
            $sheet->setCellValue('E1', 'PUESTO');
            $sheet->setCellValue('F1', 'DEPARTAMENTO');
            $sheet->setCellValue('G1', 'TIPO');
            // $sheet->setCellValue('H1', 'EMPLEADO');

            foreach ($query as $key => $value) {
                $sheet->setCellValue('A' . $cont, $value->id_contract);
                $sheet->setCellValue('B' . $cont, date("d/m/Y", strtotime($value->date_expiration)));
                $sheet->setCellValue('C' . $cont, $value->name . " " . $value->surname . " " . $value->second_surname);
                $sheet->setCellValue('D' . $cont, $value->name_m . " " . $value->surname_m . " " . $value->second_surname_m);
                $sheet->setCellValue('E' . $cont, $value->job);
                $sheet->setCellValue('F' . $cont, $value->departament);
                $sheet->setCellValue('G' . $cont, $value->type_of_contract);
                // $sheet->setCellValue('H' . $cont, $value->type_employe);
                $cont++;
            }
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

    public function notificationContractXlxs($type_of_employee, $dataContract, $dataUser)
    {
        $email = ($type_of_employee == 1) ? "elgarcia@walworth.com.mx" : "eolanda@walworth.com.mx";
        $user = ($type_of_employee == 1) ? "Elizabeth Garcia" : "Elda Olanda";

        $data = [
            "contrato" => $dataContract,
            "usuario" => $dataUser
        ];

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {

            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Server settings
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com.mx', 'Primer|Contrato');
            // Add a recipient
            $mail->addAddress($email, $user);
            // Name is optional
            //$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/primer_contrato', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Generación Primer Contrato';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function notificationUserRegistration($user_notifica, $dir_email, $dataNotifica, $type_of_employee)
    {
        $email_notifica = ($type_of_employee == 1) ? "elgarcia@walworth.com.mx" : "eolanda@walworth.com.mx";

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $email = changeEmail($dir_email);
        // $dir_email = "hrivas@walworth.com.mx"; 
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {

            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Server settings
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            //$mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com.mx', 'Primer|Contrato');
            // Add a recipient
            $mail->addAddress($email, $user_notifica);
            // Name is optional
            //$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC($email_notifica);
            $mail->addBCC('rcruz@walworth.com.mx');
            // $mail->addBCC('hrivas@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/alta_notifica', $dataNotifica);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Alta de Usuario';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function notificationContract($type_of_employee = null, $dataContract, $dataUser)
    {
        $data = [
            "contrato" => $dataContract,
            "usuario" => $dataUser
        ];

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {

            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Server settings
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            //$mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com.mx', 'Contrato Temporal');
            // Add a recipient
            $mail->addAddress("gmartinez@walworth.com.mx", "MARIA GUADALUPE MARTINEZ");
            $mail->addCC("eolanda@walworth.com.mx", "ELDA OLANDA");
            // Name is optional
            //$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/primer_contrato', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Generación Contrato Temporal';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function notificacionMassive($type, $arrayId)
    {
        $texto = implode(', ', $arrayId);
        $query = $this->db->query("SELECT b.payroll_number, b.company, a.date_expiration, a.cause_of_termination , a.observations, a.`option`,
            CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS nombre,
            CONCAT(c.`name`,' ',c.surname,' ',c.second_surname) AS manager,
            CASE
                WHEN a.`option` = 1 THEN 'PLANTA'
                WHEN a.`option` = 3 THEN 'BAJA'
                WHEN a.type_of_contract = 2 THEN 'TEMPORAL DE 30 Dias'
                WHEN a.type_of_contract = 2 THEN 'TEMPORAL DE 60 Dias'
                WHEN a.type_of_contract = 2 THEN 'TEMPORAL DE 90 Dias'
            END AS contrato
        FROM tbl_user_type_of_contract AS a
            JOIN tbl_users AS b ON a.id_user = b.id_user
            JOIN tbl_users AS c ON a.id_manager = c.id_user
        WHERE a.id_contract IN ($texto)")->getResult();

        $data = ['notify' => $query, 'count' => count($arrayId), 'type' => $type];

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {
            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Server settings
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com.mx', 'Contrato Temporal | MASIVO');
            // Add a recipient
            if ($type == 3) {
                $mail->addAddress('vhernandez@walworth.com.mx', 'VICTOR MANUEL HERNANDEZ');
                $mail->addCC("eolanda@walworth.com.mx", "ELDA OLANDA");
                $mail->addCC("gmartinez@walworth.com.mx", "MARIA GUADALUPE MARTINEZ");
            } else {
                $mail->addAddress("eolanda@walworth.com.mx", "ELDA OLANDA");
                $mail->addCC("gmartinez@walworth.com.mx", "MARIA GUADALUPE MARTINEZ");
            }
            // Name is optional
            //$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');

            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment($data["imss"]);         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/notify_contracts_massive', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Generación Contratos Temporales';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function notificationContractDirector($id, $type)
    {
        try {
            $usersData = $this->db->query("SELECT a.id_user, a.id_contract, b.payroll_number, a.depto, 
                a.job_position, a.observations, a.direct_authorization,            
                CONCAT(c.`name`,' ',c.surname) AS manager_name,
                CONCAT(b.`name`,' ',b.surname) AS user_name,
                    CASE 
                        WHEN a.direct_authorization = 1 THEN
                            'Rechazado'
                        WHEN a.direct_authorization = 2 THEN
                            'Aceptado'
                        ELSE 
                            a.direct_authorization
                    END AS authorization
                FROM tbl_user_type_of_contract AS a 
                    JOIN tbl_users  As b ON a.id_user = b.id_user
                    JOIN tbl_users AS c ON c.id_user = a.id_manager
            WHERE a.id_contract =$id")->getRow();

            $contracTempData = $this->db->query("SELECT DATE_FORMAT(jt1.date_expiration,'%d/%m/%Y') AS last_contract
                FROM tbl_user_type_of_contract AS jt1 
                WHERE jt1.active_status = 1 
                AND jt1.id_user = $usersData->id_user
                AND jt1.date_of_new_entry = (SELECT MAX(sjt1.date_of_new_entry)
                    FROM tbl_user_type_of_contract AS sjt1
                    WHERE sjt1.active_status = 1 
                    AND sjt1.`option` = 2
            AND sjt1.id_user = jt1.id_user)")->getRow();

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

            //Server settings
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            //$mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 587;

            //Recipients
            // Add a recipient
            if ($type == 1) {
                $mail->setFrom('requisiciones@walworth.com.mx', 'Contrato Tiempo Indeterminado ');
                $mail->addAddress('vhernandez@walworth.com.mx', 'VICTOR MANUEL HERNANDEZ');
            } else {
                $mail->setFrom('requisiciones@walworth.com.mx', 'Contrato Tiempo Indeterminado | Respuesta');
                $mail->addAddress($usersData->manager_email, $usersData->manager_name);
                $mail->addCC('gmartinez@walworth.com.mx', 'MARIA GUADALUPE MARTINEZ');
                $mail->addCC('eolanda@walworth.com.mx', 'ELDA OLANDA');
            }
            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment($data["imss"]);         // Add attachments
            $data = ["usuario" => $usersData, 'tipo' => $type, 'contracTempData' => $contracTempData];
            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/notify_contract_plant', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Contrato de Tiempo Indeterminado';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
