<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RolesOperationModel;
use LDAP\Result;

class Login extends BaseController
{
    private $userModel = null;
    private $googleClient = null;

    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->googleClient = new \Google_Client();
        $this->googleClient->setClientId("381235139070-sg24jfpd5fp70vumsakqiu7orl2s2f72.apps.googleusercontent.com");
        $this->googleClient->setClientSecret("-7Ae5fEyTvQmCCcR7U8vilNV");
        $this->googleClient->setRedirectUri(base_url() . "/auth/loginGoogle");
        $this->googleClient->addScope("email");
        $this->googleClient->addScope("profile");
        $this->userModel = new UserModel();
        $this->rolesModel = new RolesOperationModel();
        $this->db = \Config\Database::connect();
        helper('secure_password');
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function index()
    {
        $data['googleAuth'] = $this->googleClient->createAuthUrl();
        return (!$this->is_logged) ? view('login', $data) : redirect()->to(base_url() . "/dashboard");
    }

    public function loginWithGoogle()
    {
        try {
            $services = false;
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
            if (!isset($token['error'])) {
                $this->googleClient->setAccessToken($token['access_token']);
                session()->set("AccessToken", $token['access_token']);

                $googleService = new \Google_Service_Oauth2($this->googleClient);
                $data = $googleService->userinfo->get();
                $currentDateTime = date('Y-m-d H:i:s');
                $userdata = array();
                $idGoogle = $data['id'];
                $email = $data['email'];

                if (!$this->userModel->isAlreadyRegister($email)) {
                    //El usuario ya inició sesión y desea iniciar sesión nuevamente
                    return redirect()->back()->with('msg', [
                        'type' => 'alert-warning',
                        'body' => 'Usuario no registrado.'
                    ])->withInput();
                }

                $userdata = [
                    'oauth_id' => $idGoogle,
                    //'name' => $data['givenName'],
                    //'surname' => $data['familyName'],
                    'email' => $data['email'],
                    'profile_img' => $data['picture'],
                    'updated_at' => $currentDateTime
                ];
                $this->userModel->updateUserData($userdata, $email);
                // echo "entro update";


                // $builder = $this->db->table('tbl_users a');
                // $builder->select('a.id_user,a.name,a.surname,a.id_rol,a.id_departament,a.active_password,c.job,b.departament,b.clave_depto,a.payroll_number,a.date_admission,a.type_of_employee,id_area_operativa');
                // $builder->join('cat_departament b', 'a.id_departament = b.id_depto', 'left');
                // $builder->join('cat_job_position c', 'a.id_job_position = c.id', 'left');
                // $builder->join('cat_ c', 'a.id_job_position = c.id', 'left');
                // $builder->where('email', $email);
                // $builder->where('a.active_status', 1);
                $query = $this->db->query("SELECT a.id_user, a.`name`, a.surname, a.id_rol, a.id_departament, a.active_password, c.job,
                        b.departament, d.clave_cost_center, a.payroll_number, a.date_admission, a.type_of_employee, a.id_area_operativa, a.grado
                    FROM tbl_users As a
                        JOIN cat_departament As b ON a.id_departament = b.id_depto
                        JOIN cat_job_position AS c On a.id_job_position = c.id
                        JOIN cat_cost_center AS d ON a.id_cost_center = d.id_cost_center
                    WHERE a.email = '$email'
                AND a.active_status = 1")->getResult();
                //d($query);

                foreach ($query as $key => $value) {

                    if ($value->id_rol != 2) {

                        $builder = $this->db->table('tbl_stationery_permissions');
                        $builder->select('*');
                        $builder->where('payroll_number', $value->payroll_number);
                        $query3 = $builder->get()->getResult();

                        if (count($query3) > 0) {
                            $services = true;
                        }
                    }
                    $payroll_number = $value->payroll_number;

                    $id_manager = $value->id_user;
                    $query0 = $this->db->query("SELECT id_person FROM tbl_stationery_permissions WHERE active_status = 1 AND id_manager = $id_manager OR id_director = $id_manager")->getResult();
                    $authorize_services = ($query0 != null) ? true : false;

                    $query01 = $this->db->query("SELECT id FROM tbl_assign_departments_to_managers_new  WHERE active_status = 1 AND id_manager = $id_manager")->getResult();
                    $authorize = ($query01 != null) ? true : false;

                    $query01New = $this->db->query("SELECT id FROM tbl_assign_departments_to_managers_new WHERE active_status = 1 AND id_director = $id_manager")->getResult();
                    $authorizeNew = ($query01New != null) ? true : false;

                    $queryAmount = $this->db->query("SELECT amount from tbl_users AS a
                                                     INNER JOIN cat_per_diem AS b
                                                      ON a.grado = b.grade
                                                      WHERE a.payroll_number = payroll_number")->getRow();
                    $amount = ($queryAmount != null) ? $queryAmount->amount : 0;

                    $query02 = $this->db->query("SELECT ID FROM tbl_requisiciones_access WHERE id_user = $id_manager AND `status` = 1")->getResult();
                    $accesRequisition = ($query02 != null) ? true : false;

                    $query03 = $this->db->query("SELECT Tecnico_AreaId FROM cat_ticket_tecnico WHERE Tecnico_Activo = 1 AND TecnicoId = $id_manager")->getRow();
                    $accessTickets = ($query03 != null) ? $query03->Tecnico_AreaId : false;

                    $query04 = $this->db->query("SELECT Tecnico_AreaId FROM cat_ticket_tecnico WHERE Tecnico_Activo = 1 AND TecnicoId = $id_manager  AND Tecnico_Jefe = 1")->getRow();
                    $managerTickets = ($query04 != null) ? $query04->Tecnico_AreaId : false;

                    $query05 = $this->db->query("SELECT id FROM tbl_assign_manager_tikcet_maintenance WHERE active_status = 1 AND id_manager = $id_manager")->getRow();
                    $authorizeTicketsMante = ($query05 != null) ? true : false;

                    $query06 = $this->db->query("SELECT id_contract from tbl_users_temporary WHERE active_status = 1 AND id_manager = $id_manager")->getRow();
                    $accessContracts = ($query06 != null) ? true : false;

                    $query07 = $this->db->query("SELECT id from tbl_assign_travel_expenses_manager WHERE active_status = 1 AND id_user = $id_manager")->getRow();
                    $accessTravelExpens = ($query07 != null) ? true : false;

                    $query08 = $this->db->query("SELECT id from tbl_assign_travel_expenses_manager WHERE active_status = 1 AND id_director = $id_manager")->getRow();
                    $authorizeTravelExpens = ($query08 != null) ? true : false;


                    session()->set([
                        'id_user' => $value->id_user,
                        'name' => $value->name,
                        'surname' => $value->surname,
                        'profile_google' => $data['picture'],
                        'id_rol' => $value->id_rol,
                        'id_depto' => $value->id_departament,
                        'job_position' => $value->job,
                        'departament' => $value->departament,
                        'payroll_number' => $payroll_number,
                        'date_admission' => $value->date_admission,
                        'type_of_employee' => $value->type_of_employee,
                        'cost_center' => $value->clave_cost_center,
                        'menu_services' => $services,
                        'authorize_services' => $authorize_services,
                        'authorize' => $authorize,
                        'authorizeNew' => $authorizeNew,
                        'access_requisition' => $accesRequisition,
                        'access_tickets' => $accessTickets,
                        'manager_tickets' => $managerTickets,
                        'authorize_tickets_mante' => $authorizeTicketsMante,
                        'per_diem_amount' => $amount,
                        'area_operativa' => $value->id_area_operativa,
                        'grado' => $value->grado,
                        'access_contracts' => $accessContracts,
                        'access_travel_expens' => $accessTravelExpens,
                        'authorize_travel_expens' => $authorizeTravelExpens,
                        'is_logged' => true
                    ]);
                }
            } else {
                session()->set('Error', 'Algo Salió Mal');
                return redirect()->to(base_url());
            }

            //Successfull Login
            return redirect()->to(base_url() . "/dashboard");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }



    public function dashBoard()
    {
        // return ($this->is_logged) ?  view('user/dashboard') : redirect()->to(site_url());

        if ($this->is_logged) {
            if (session()->id_user == 710 /* || session()->id_user == 1063 */) {
                return  view('dashboard/vigilancia_dashboard');
            } elseif (session()->id_user ==  1121) {
                return  view('dashboard/vigilanciavh_dashboard');
            } else {
                $query = $this->db->query("SELECT a.vacation_days_total AS vacation, b.amount_permissions AS permiss,
                    CASE    WHEN c.id_record IS NULL THEN 'NO SOLICITADO'
                        ELSE 'VER MIS VEHICULOS' END AS tag
                FROM tbl_users AS a
                        LEFT JOIN tbl_assign_departments_to_managers_new AS b ON a.payroll_number = b.payroll_number
                        LEFT JOIN tbl_parking_users_items AS c ON a.id_user = c.id_user 
                WHERE a.id_user = " . session()->id_user)->getRow();
                $query1 = $this->db->query("SELECT type_vehicle, COUNT(id_item) AS cantidad
                    FROM tbl_parking_users_items WHERE active_status = 1
                GROUP BY type_vehicle")->getResult();
                $tagData = ['', 0, 0, 0, 0, 0, 0];
                foreach ($query1 as $key) {
                    $tagData[$key->type_vehicle] = $key->cantidad;
                }
                $query2 = $this->db->query("SELECT
                (SELECT COUNT(*) FROM tbl_parking_users WHERE active_status = 1) +
                (SELECT COUNT(*) FROM tbl_parking_users_bicycle WHERE active_status = 1) +
                (SELECT COUNT(*) FROM tbl_parking_users_garden WHERE active_status = 1) +
                (SELECT COUNT(*) FROM tbl_parking_users_motorcycle WHERE active_status = 1) +
                (SELECT COUNT(*) FROM tbl_parking_users_N1 WHERE active_status = 1) +
                (SELECT COUNT(*) FROM tbl_parking_users_N3 WHERE active_status = 1) AS `all`")->getRow();
                


                $insignias = $this->db->query("SELECT 
                                                id_user,
                                                user_name,
                                                departament,
                                                COUNT(*) as total_medallas
                                            FROM tbl_hse_volunteering_activity
                                            WHERE assistance = 'asistio' AND active_status = 1
                                            GROUP BY id_user
                                            ORDER BY total_medallas DESC LIMIT 3")->getResult();

                $data = ['info' => $query, 'tags' => $tagData, 'allTags' => $query2->all, 'insignias' => $insignias];



                return view('dashboard/dashboard', $data);
            }
        } else {
            return  redirect()->to(site_url());
        }
    }

    public function signin()
    {
        if (!$this->validate([
            'email' => 'required',
            'password' => 'required'
        ])) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }



        $services = false;
        try {
            $email = trim($this->request->getPost('email'));
            $password = trim($this->request->getPost('password'));
            $search = (is_numeric($email)) ? 'payroll_number' : 'email';
            $sql = (is_numeric($email)) ? "AND a.payroll_number = $email" : "AND a.email = '$email'";
            $validateUser = $this->userModel->where($search, $email)->first();
            //var_dump($validateUser);
            if (!$validateUser) {
                return redirect()->back()
                    ->with('msg', [
                        'type' => 'alert-warning',
                        'body' => 'Este usuario no se encuentra registrado.'
                    ])->withInput();
            }

            // Chequeo de intentos fallidos
            if ($validateUser['failed_attempts'] >= 3) {
                return redirect()->back()
                    ->with('msg', [
                        'type' => 'alert-danger',
                        'body' => 'Cuenta bloqueada por múltiples intentos fallidos.'
                    ])->withInput();
            }


            /* $throttler = \Config\Services::throttler();
            $allow = $throttler->check("login", 4,MINUTES); */


            //if (verifyPassword($password, $validateUser["password"])) :
            if ($password == $validateUser["password"]) {

                // Reiniciar intentos fallidos en caso de inicio de sesión exitoso
                $this->userModel->update($validateUser['id_user'], [
                    'failed_attempts' => 0,
                    'failed_attempt_time' => null
                ]);


                $value = $this->db->query("SELECT a.id_user, a.`name`, a.surname, a.profile_img, a.id_rol, a.id_departament, a.active_password, c.job,
                        b.departament, d.clave_cost_center, a.payroll_number, a.date_admission, a.type_of_employee, a.id_area_operativa, a.grado
                    FROM tbl_users As a
                        JOIN cat_departament As b ON a.id_departament = b.id_depto
                        JOIN cat_job_position AS c On a.id_job_position = c.id
                        JOIN cat_cost_center AS d ON a.id_cost_center = d.id_cost_center
                WHERE a.active_status = 1 $sql ")->getRow();

                if ($value->id_rol != 2 || $value->id_user == 1269 || $value->id_user == 854 || $value->id_user == 863) {

                    $builder = $this->db->table('tbl_stationery_permissions');
                    $builder->select('*');
                    $builder->where('payroll_number', $value->payroll_number);
                    $query3 = $builder->get()->getResult();

                    if (count($query3) > 0) {
                        $services = true;
                    }
                } else if ($value->id_user == 329) {
                    $services = true;
                }

                $id_manager = $value->id_user;
                $query0 = $this->db->query("SELECT id_person FROM tbl_stationery_permissions WHERE active_status = 1 AND id_manager = $id_manager")->getResult();
                $authorize_services = ($query0 != null) ? true : false;

                $query01 = $this->db->query("SELECT id FROM tbl_assign_departments_to_managers_new WHERE active_status = 1 AND id_manager = $id_manager")->getResult();
                $authorize = ($query01 != null) ? true : false;

                $query01New = $this->db->query("SELECT id FROM tbl_assign_departments_to_managers_new WHERE active_status = 1 AND id_director = $id_manager")->getResult();
                $authorizeNew = ($query01New != null) ? true : false;

                $query02 = $this->db->query("SELECT ID FROM tbl_requisiciones_access WHERE id_user = $id_manager AND `status` = 1")->getResult();
                $accesRequisition = ($query02 != null) ? true : false;

                $query03 = $this->db->query("SELECT Tecnico_AreaId FROM cat_ticket_tecnico WHERE Tecnico_Activo = 1 AND TecnicoId = $id_manager")->getRow();
                $accessTickets = ($query03 != null) ? $query03->Tecnico_AreaId : false;

                $query04 = $this->db->query("SELECT Tecnico_AreaId FROM cat_ticket_tecnico WHERE Tecnico_Activo = 1 AND TecnicoId = $id_manager  AND Tecnico_Jefe = 1")->getRow();
                $managerTickets = ($query04 != null) ? $query04->Tecnico_AreaId : false;

                $query05 = $this->db->query("SELECT id FROM tbl_assign_manager_tikcet_maintenance WHERE active_status = 1 AND id_manager = $id_manager")->getRow();
                $authorizeTicketsMante = ($query05 != null) ? true : false;

                $query06 = $this->db->query("SELECT id_contract from tbl_users_temporary WHERE active_status = 1 AND id_manager = $id_manager")->getRow();
                $accessContracts = ($query06 != null) ? true : false;

                $query07 = $this->db->query("SELECT id from tbl_assign_travel_expenses_manager WHERE active_status = 1 AND id_user = $id_manager")->getRow();
                $accessTravelExpens = ($query07 != null) ? true : false;

                $query08 = $this->db->query("SELECT id from tbl_assign_travel_expenses_manager WHERE active_status = 1 AND id_director = $id_manager")->getRow();
                $authorizeTravelExpens = ($query08 != null) ? true : false;

                session()->set([
                    'id_user' => $value->id_user,
                    'name' => $value->name,
                    'surname' =>  $value->surname,
                    'profile_img' => $value->profile_img,
                    'id_rol' => $value->id_rol,
                    'id_depto' => $value->id_departament,
                    'job_position' => $value->job,
                    'departament' => $value->departament,
                    'payroll_number' => $value->payroll_number,
                    'date_admission' => $value->date_admission,
                    'type_of_employee' => $value->type_of_employee,
                    'cost_center' => $value->clave_cost_center,
                    'menu_services' => $services,
                    'authorize_services' => $authorize_services,
                    'authorize' => $authorize,
                    'authorizeNew' => $authorizeNew,
                    'access_requisition' => $accesRequisition,
                    'access_tickets' => $accessTickets,
                    'manager_tickets' => $managerTickets,
                    'authorize_tickets_mante' => $authorizeTicketsMante,
                    'area_operativa' => $value->id_area_operativa,
                    'grado' => $value->grado,
                    'access_contracts' => $accessContracts,
                    'access_travel_expens' => $accessTravelExpens,
                    'authorize_travel_expens' => $authorizeTravelExpens,
                    'is_logged' => true
                ]);
                return redirect()->to(site_url("dashboard"));
            } else {

                // Incrementar intentos fallidos y registrar la fecha y hora del intento
                $this->userModel->update($validateUser['id_user'], [
                    'failed_attempts' => $validateUser['failed_attempts'] + 1,
                    'failed_attempt_time' => date('Y-m-d H:i:s') // Almacena la hora del intento fallido
                ]);



                return redirect()->back()
                    ->with('msg', [
                        'type' => 'alert-warning',
                        'body' => 'Contraseña no valida.'
                    ])->withInput();
            }

            //return $this->respond('Usuario encontrado');
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function signout()
    {
        session()->destroy();
        return redirect()->route('/');
    }
}
