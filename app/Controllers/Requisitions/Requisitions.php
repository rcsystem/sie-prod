<?php

namespace App\Controllers\Requisitions;

use App\Controllers\BaseController;
use App\Models\RequisitionsModel;
use App\Models\CompanyModel;
use App\Models\StudiesModel;
use App\Models\PersonnelTypeModel;
use App\Models\ReasonRequisitionModel;
use App\Models\GenderModel;
use App\Models\CivilStatusModel;
use App\Models\DeptoModel;
use App\Models\AssignDepartmentsModel;
use App\Models\NotificaRequisitionsModel;

use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Requisitions extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->requisitionsModel = new RequisitionsModel();
        $this->companyModel = new CompanyModel();
        $this->studiesModel = new StudiesModel();
        $this->personnelTypeModel = new PersonnelTypeModel();
        $this->reasonRequisitionModel = new ReasonRequisitionModel();
        $this->civilStatusModel = new CivilStatusModel();
        $this->genderModel = new GenderModel();
        $this->deptoModel = new DeptoModel();
        $this->assignDeptoModel = new AssignDepartmentsModel();
        $this->notificaModel = new NotificaRequisitionsModel();

        helper('secure_password');
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }


    public function index()
    {
        return ($this->is_logged) ?  view('manager/authorize_requisitions') : redirect()->to(site_url());
    }
    public function generate()
    {
        if ($this->is_logged) {
            $gender  = $this->genderModel->where('active_status', 1)->findAll();
            $level_of_study = $this->studiesModel->where('active_status', 1)->findAll();
            $applicant_company = $this->companyModel->where('active_status', 1)->findAll();
            $personnel_type = $this->personnelTypeModel->where('active_status', 1)->findAll();
            $reason_for_the_requisition = $this->reasonRequisitionModel->where('active_status', 1)->findAll();
            $civil_status  = $this->civilStatusModel->where('active_status', 1)->findAll();
            $builder = $this->db->table('cat_departament');
            $builder->select('id_depto, departament, area');
            $builder->where('active_status', 1);
            $query = $builder->get()->getResultArray();

            foreach ($query as $key => $value) {
                $groups[$value['area']][$value['id_depto']] = $value['departament'];
            }
            $data = [
                "company" => $applicant_company,
                "level_of_study" => $level_of_study,
                "personnel_type" => $personnel_type,
                "reason_for_the_requisition" => $reason_for_the_requisition,
                "civil_status" => $civil_status,
                "gender" => $gender,
                "departament" => $groups,
            ];
            return view('manager/generate_requisition', $data);
        } else {
            return redirect()->to(site_url());
        }
    }

    public function searchCostCenter()
    {
        $id_depto = trim($this->request->getPost('id_depto'));
        $cost_center = $this->deptoModel->where('id_depto', $id_depto)->first();
        $data = ["cost_center" => $cost_center["clave_depto"]];
        return json_encode($data);
    }

    public function requisitionsAll()
    {
        return ($this->is_logged) ?  view('manager/requisition_all') : redirect()->to(site_url());
    }

    public function requisitions_all()
    {
        $builder = $this->db->table('tbl_job_application a');
        $builder->select('a.id_folio,a.fecha_creacion,a.estatus,a.puesto_solicitado,b.name,b.surname,c.departament,d.departament AS depto');
        $builder->join('tbl_users b', 'a.id_user = b.id_user', 'left');
        $builder->join('cat_departament c', 'c.clave_depto = a.centro_costos', 'left');
        $builder->join('cat_departament d', 'd.id_depto = b.id_departament', 'left');
        $builder->where('estatus_activo', 1);
        $builder->limit(1500);
        $query = $builder->get()->getResult();

        return json_encode($query);
    }

    public function insertRequisition()
    {
        $applicant_company = trim($this->request->getPost('empresa_solicitante'));
        $required_position = trim($this->request->getPost('puesto_solicitado'));
        $cost_centre = trim($this->request->getPost('centro_costo'));
        $wanted_people = trim($this->request->getPost('personas_requeridas'));
        $operational_area = trim($this->request->getPost('area_operativa'));
        $operational_areas = trim($this->request->getPost('area_operativas'));
        $degree_studies = trim($this->request->getPost('grado_estudios'));
        $type_studies = trim($this->request->getPost('tipo_estudio'));
        $personal_type = trim($this->request->getPost('tipo_personal'));
        $reason = trim($this->request->getPost('motivo'));
        $immediate_boss = trim($this->request->getPost('jefe_inmediato'));
        $replacement = trim($this->request->getPost('remplazo'));
        $starting_wage = trim($this->request->getPost('salario_inicial'));
        $final_wage = trim($this->request->getPost('salario_final'));
        $quotation = trim($this->request->getPost('cotizacion'));
        $period = trim($this->request->getPost('periodo'));
        $gender_wanted = trim($this->request->getPost('genero_requerido'));
        $years_experience = trim($this->request->getPost('anios_experiencia'));
        $civil_status = 'Indistinto';
        $rolar_shifts = trim($this->request->getPost('rolar_turnos'));
        $minimum_age = trim($this->request->getPost('edad_minima'));
        $maximum_age = trim($this->request->getPost('edad_maxima'));
        $deal_clients = trim($this->request->getPost('trato_clientes'));
        $personal_handling = trim($this->request->getPost('manejo_personal'));
        $license = trim($this->request->getPost('licencia'));
        $start_time = trim($this->request->getPost('horario_inicial'));
        $final_time = trim($this->request->getPost('horario_final'));
        $working_day = trim($this->request->getPost('jornada'));
        $first_knowledge = trim($this->request->getPost('primer_conocimiento'));
        $second_knowledge = trim($this->request->getPost('segundo_conocimiento'));
        $third_knowledge = trim($this->request->getPost('tercer_conocimiento'));
        $knowledge_room = trim($this->request->getPost('cuarto_conocimiento'));
        $fifth_knowledge = trim($this->request->getPost('quinto_conocimiento'));
        $first_competition = trim($this->request->getPost('primer_competencia'));
        $second_competition = trim($this->request->getPost('segunda_competencia'));
        $third_competition = trim($this->request->getPost('tercer_competencia'));
        $fourth_competition = trim($this->request->getPost('cuarta_competencia'));
        $fifth_competition = trim($this->request->getPost('quinta_competencia'));
        $first_activity = trim($this->request->getPost('primer_actividad'));
        $second_activity = trim($this->request->getPost('segunda_actividad'));
        $third_activity = trim($this->request->getPost('tercer_actividad'));
        $fourth_activity = trim($this->request->getPost('cuarta_actividad'));
        $fifth_activity = trim($this->request->getPost('quinta_actividad'));

        $id_user = session()->id_user;
        $date = date("Y-m-d H:i:s");


        $dataRequisition = [
            'id_user' => $id_user,
            'fecha_creacion' => $date,
            'empresa_solicitante' => $applicant_company,
            'centro_costos' => $cost_centre,
            'area_operativa' => $operational_area,
            'area_operativas' => $operational_areas,
            'tipo_de_personal' => $personal_type,
            'puesto_solicitado' => $required_position,
            'personas_requeridas' => $wanted_people,
            'grado_estudios' => $degree_studies,
            'tipo_estudios' => $type_studies,
            'motivo_requisicion' => $reason,
            'jefe_inmediato' => $immediate_boss,
            'colaborador_reemplazo' => $replacement,
            'cotizacion' => $quotation,
            'periodo' => $period,
            'salario_inicial' => $starting_wage,
            'salario_final' => $final_wage,
            'genero_requerido' => $gender_wanted,
            'estado_civil' => $civil_status,
            'edad_minima' => $minimum_age,
            'edad_maxima' => $maximum_age,
            'licencia_conducir' => $license,
            'anios_experiencia' => $years_experience,
            'rolar_turno' => $rolar_shifts,
            'trato_cli_prov' => $deal_clients,
            'manejo_personal' => $personal_handling,
            'jornada' => $working_day,
            'horario_inicial' => $start_time,
            'horario_final' => $final_time,
            'conocimiento_1' => $first_knowledge,
            'conocimiento_2' => $second_knowledge,
            'conocimiento_3' => $third_knowledge,
            'conocimiento_4' => $knowledge_room,
            'conocimiento_5' => $fifth_knowledge,
            'competencia_1' => $first_competition,
            'competencia_2' => $second_competition,
            'competencia_3' => $third_competition,
            'competencia_4' => $fourth_competition,
            'competencia_5' => $fifth_competition,
            'actividad_1' => $first_activity,
            'actividad_2' => $second_activity,
            'actividad_3' => $third_activity,
            'actividad_4' => $fourth_activity,
            'actividad_5' => $fifth_activity,
        ];

        $result = ($this->requisitionsModel->insert($dataRequisition)) ? json_encode($dataRequisition) : json_encode('error');
        $id_folio = $this->db->insertID();
        $username = session()->name . " " . session()->surname;
        $data = [
            "id_folio" => $id_folio,
            "username" => $username,
            "tipo_personal" => $personal_type,
            "personas_requeridas" => $wanted_people,
            "motivo" => $reason,
            "puesto" => $required_position
        ];




        $builder = $this->db->table('tbl_requisitions_notifica_copy');
        $builder->select('id_user_notificar');
        $builder->where('id_user', $id_user);
        $dataUser = $builder->get()->getResult();

        foreach ($dataUser as $key => $value) {  //$value->id_user_notificar

            $builder = $this->db->table('tbl_users');
            $builder->select('name,surname,email');
            $builder->where('id_user', $value->id_user_notificar);
            $datas = $builder->get()->getResult();

            foreach ($datas as $key => $val) {
                $dir_email = $val->email;
                $title = $val->name . " " . $val->surname;

                $this->emailNotificationRequisitions($dir_email, $title, $data);
            }
        }




        return $result;
    }



    public function notificar($id_folio = null)
    {
        $username = session()->name . " " . session()->surname;

        $builder = $this->db->table('tbl_job_application');
        $builder->select('*');
        $builder->where('id_folio', $id_folio);
        $dataRequest = $builder->get()->getResult();

        foreach ($dataRequest as $key => $value) {
            $data = [
                "id_folio" => $value->id_folio,
                "username" => 'Wenseslao Matias',
                "tipo_personal" => $value->tipo_de_personal,
                "personas_requeridas" => $value->personas_requeridas,
                "motivo" => $value->motivo_requisicion,
                "puesto" => $value->puesto_solicitado
            ];
        }

        $builder = $this->db->table('tbl_requisitions_notifica_copy');
        $builder->select('id_user_notificar');
        $builder->where('id_user', 347);
        $dataUser = $builder->get()->getResult();

        foreach ($dataUser as $key => $value) {
            $builder = $this->db->table('tbl_users');
            $builder->select('name,surname,email');
            $builder->where('id_user', $value->id_user_notificar);
            $datas = $builder->get()->getResult();
            foreach ($datas as $key => $val) {
                $dir_email = $val->email;
                $title = $val->name . " " . $val->surname;

                $this->notificarEmail($dir_email, $title, $data);
            }
        }
    }

    public function notificarEmail($dir_email, $title, $data)
    {



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
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            //$mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Sistema de Requisiciones');
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            $mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Information Team');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('krubio@walworth.com.mx');
            $mail->addBCC('rcruz@walworth.com.mx');
            
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/requisiciones', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Personal';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function emailNotificationRequisitions($dir_email = null, $title = null, $data)
    {

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $dir_email = changeEmail($dir_email);


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
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Sistema de Requisiciones');
            // Add a recipient
            //$mail->addAddress('rcruz@walworth.com.mx', $title);
            $mail->addAddress($dir_email, $title);
            $mail->addBCC('krubio@walworth.com.mx');

            $mail->addCC('msanchez@walworth.com.mx');

            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Información del Sistema');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('fintegral@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/requisiciones', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Personal';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function assignAreas()
    {

        try {
            $id_user = trim($this->request->getPost('id_user'));
            $builder = $this->db->table('tbl_requisitions_notifica');
            $builder->select('area_operativa');
            $builder->where('id_user', $id_user);
            $builder->where('active_status', 1);
            $data = $builder->get()->getResultArray();
            return (count($data) > 0) ? json_encode($data) : json_encode("error");
        } catch (Exception $e) {
            echo 'Message Error: ', $e;
        }
    }


    public function assign_areas()
    {

        try {
            $id_user = trim($this->request->getPost('id_user'));
            $areas = trim($this->request->getPost('areas'));
            $result = false;

            $array2 = explode(',', $areas);

            $cuenta_areas = count($array2);

            $builder = $this->db->table('tbl_requisitions_notifica');
            $builder->select('area_operativa');
            $builder->where('id_user', $id_user);
            $arrayas = $builder->get()->getResultArray();
            //var_dump($arrayas);



            if ($cuenta_areas > count($arrayas)) {

                if ($cuenta_areas == 1) {
                    $builder = $this->db->table('tbl_requisitions_notifica');
                    $builder->select('area_operativa');
                    $builder->where('id_user', $id_user);
                    $builder->where('area_operativa', $areas);
                    $arrayas2 = $builder->get()->getResultArray();
                    if (count($arrayas2) == 0) {
                        $data = ["id_user" => $id_user, "area_operativa" => $areas];
                        $result = $this->notificaModel->insert($data);
                    }
                }
                if ($cuenta_areas >= 1) {
                    foreach ($array2 as $item) {
                        if (!in_array($item, $arrayas[0])) {
                            $data = ["id_user" => $id_user, "area_operativa" => $item];
                            $result = $this->notificaModel->insert($data);
                        }
                    }
                }
            } else {
                foreach ($arrayas as $item) {

                    if (!in_array($item["area_operativa"], $array2)) {
                        // echo $item["area_operativa"];

                        $builder = $this->db->table('tbl_requisitions_notifica');
                        $builder->where('id_user', $id_user);
                        $builder->where('area_operativa', $item["area_operativa"]);
                        $result = $builder->delete();
                    }
                }
            }

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (Exception $e) {
            echo 'Message Error: ', $e;
        }
    }

    public function updateRequisition()
    {
        $reason = trim($this->request->getPost('motivo'));
        $replacement = trim($this->request->getPost('remplazo'));
        $quotation = trim($this->request->getPost('cotizacion'));
        $period = trim($this->request->getPost('periodo'));
        $gender = trim($this->request->getPost('genero'));
        $marital_status = trim($this->request->getPost('estado_civil'));
        $minimum_age = trim($this->request->getPost('edad_minima'));
        $maximum_age = trim($this->request->getPost('edad_maxima'));
        $rolar_shifts = trim($this->request->getPost('rolar'));
        $license = trim($this->request->getPost('licencia'));
        $years_experience = trim($this->request->getPost('experiencia'));
        $deal_clients = trim($this->request->getPost('trato'));
        $management = trim($this->request->getPost('manejo'));
        $working_day = trim($this->request->getPost('jornada'));
        $company = trim($this->request->getPost('empresa'));
        $cost_center = trim($this->request->getPost('centro_costo'));
        $operational_area = trim($this->request->getPost('area_operativa'));
        $id_folio = trim($this->request->getPost('id_folio'));
        $personal_type = trim($this->request->getPost('tipo_personal'));
        $requested_position = trim($this->request->getPost('puesto_solicitado'));
        $persons_required = trim($this->request->getPost('personas_requeridas'));
        $starting_salary = trim($this->request->getPost('salario_inicial'));
        $final_salary = trim($this->request->getPost('salario_final'));
        $starting_time = trim($this->request->getPost('horario_inicial'));
        $final_schedule = trim($this->request->getPost('horario_final'));
        $immdiate_boss = trim($this->request->getPost('jefe_inmediato'));
        $level_of_study = trim($this->request->getPost('estudios'));
        $status = trim($this->request->getPost('status'));
        $id_user = session()->id_user;
        $date = date("Y-m-d H:i:s");


        $dataRequisition = [
            'empresa_solicitante' => $company,
            'centro_costos' => $cost_center,
            'area_operativas' => $operational_area,
            'grado_estudios' => $level_of_study,
            'motivo_requisicion' => $reason,
            'colaborador_reemplazo' => $replacement,
            'cotizacion' => $quotation,
            'periodo' => $period,
            'genero_requerido' => $gender,
            'estado_civil' => $marital_status,
            'edad_minima' => $minimum_age,
            'edad_maxima' => $maximum_age,
            'licencia_conducir' => $license,
            'anios_experiencia' => $years_experience,
            'rolar_turno' => $rolar_shifts,
            'trato_cli_prov' => $deal_clients,
            'manejo_personal' => $management,
            'jornada' => $working_day,
            'tipo_de_personal' => $personal_type,
            'puesto_solicitado' => $requested_position,
            'personas_requeridas' => $persons_required,
            'salario_inicial' => $starting_salary,
            'salario_final' => $final_salary,
            'horario_inicial' => $starting_time,
            'jefe_inmediato' => $immdiate_boss,
            'horario_final' => $final_schedule,
            'estatus' => $status,
            'date_answer' => date("Y-m-d H:i:s"),
        ];
        //echo json_encode($dataRequisition);
        return ($this->requisitionsModel->update($id_folio, $dataRequisition)) ? json_encode("ok") : json_encode('error');
    }
    /* 
    public function asignation()
    {
        $builder = $this->db->table('tbl_users');
        $builder->select('id_user,name,surname');
        $builder->where('id_rol', 4);
        $dataUser = $builder->get()->getResult();
        $dataAreas = $this->deptoModel->where('active_status', 1)->findAll();
        $datainfo = ["usuarios" => $dataUser, "areas_operativas" => $dataAreas];

        return ($this->is_logged) ? view('system/asignation', $datainfo) : redirect()->to(site_url());
    } 
    */
    public function asignation()
    {
        $query = $this->db->query("SELECT id_user, `name`, second_surname, surname FROM tbl_users WHERE id_user IN (SELECT DISTINCT id_user_notificar FROM tbl_requisitions_notifica_copy);")->getResult();
        $data = ['manager' => $query];
        return ($this->is_logged) ? view('manager/asignation_manager', $data) : redirect()->to(site_url());
    }

    public function dataAsignation()
    {
        $nomina = $this->request->getPost('nomina');
        $query0 = $this->db->query("SELECT a.id_notifica, a.id_user_notificar, b.`name`, b.surname, b.second_surname FROM tbl_requisitions_notifica_copy AS a JOIN tbl_users AS b ON a.id_user = b.id_user WHERE b.payroll_number = $nomina")->getRow();
        return ($query0) ? json_encode($query0) : json_encode(false);
    }

    public function dataAsignationEdit()
    {
        $id_notifica = $this->request->getPost('registro_modal');
        $id_user_notificar = $this->request->getPost('id_manager');
        $updateData = ['id_user_notificar' => $id_user_notificar];
        $update = $this->notificaModel->update($id_notifica, $updateData);
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function requestsPerUser()
    {
        return ($this->is_logged) ?  view('manager/request_per_user') : redirect()->to(site_url());
    }

    public function PerUser()
    {
        $id_user = session()->id_user;
        $data_user = $this->requisitionsModel->request_per_user($id_user);
        return json_encode($data_user);
    }

    public function editRequisition()
    {
        $id_folio = $this->request->getPost('id_folio');

        $builder = $this->db->table('tbl_job_application a');
        $builder->select('a.*,c.departament,d.departament AS depto');
        $builder->join('tbl_users b', 'a.id_user = b.id_user', 'left');
        $builder->join('cat_departament c', 'c.clave_depto = a.centro_costos', 'left');
        $builder->join('cat_departament d', 'd.id_depto = b.id_departament', 'left');
        $builder->where('id_folio', $id_folio);
        $query = $builder->get()->getResult();

        return (count($query) > 0) ? json_encode($query) : json_encode("error");
    }
    public function edit_Requisition()
    {
        $id_folio = $this->request->getPost('id_folio');
        $builder = $this->db->table('tbl_job_application a');
        $builder->select('a.id_folio,a.fecha_creacion,a.estatus,b.name,a.tipo_de_personal,a.puesto_solicitado,personas_requeridas,a.motivo_requisicion,b.surname,c.departament,d.departament AS depto');
        $builder->join('tbl_users b', 'a.id_user = b.id_user', 'left');
        $builder->join('cat_departament c', 'c.clave_depto = a.centro_costos', 'left');
        $builder->join('cat_departament d', 'd.id_depto = b.id_departament', 'left');
        $builder->where('id_folio', $id_folio);
        $builder->limit(1);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }

    public function authorizeRequisitionANT()
    {
        try {
            $id_user = session()->id_user;
            if ($id_user == 171) {
                $query = $this->db->query("SELECT a.*, b.name,b.surname,c.departament
                FROM
                    tbl_job_application a
                INNER JOIN tbl_users b ON a.id_user = b.id_user
                INNER JOIN cat_departament c ON b.id_departament = c.id_depto
                WHERE
                a.area_operativa = 71 OR
                    a.id_user IN (
                        SELECT DISTINCT
                            id_user
                        FROM
                            tbl_requisitions_notifica_copy
                        WHERE
                            id_user_notificar = $id_user) LIMIT 1500");
            } else {

                $query = $this->db->query("SELECT a.*, b.name,b.surname,c.departament
                FROM
                    tbl_job_application a
                INNER JOIN tbl_users b ON a.id_user = b.id_user
                INNER JOIN cat_departament c ON b.id_departament = c.id_depto
                WHERE
                    a.id_user IN (
                        SELECT DISTINCT
                            id_user
                        FROM
                            tbl_requisitions_notifica_copy
                        WHERE
                            id_user_notificar = $id_user) LIMIT 1500");
            }

            $data = $query->getResult();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizeRequisition()
    {
        $id_user = session()->id_user;
        try {
            if ($id_user == 1 || $id_user == 1390 || $id_user == 27) {
                $query = $this->db->query("SELECT a.*, b.name,b.surname,c.departament
                FROM
                    tbl_job_application a
                INNER JOIN tbl_users b ON a.id_user = b.id_user
                INNER JOIN cat_departament c ON b.id_departament = c.id_depto
                WHERE
                  estatus_activo = 1 LIMIT 1500");
            } else {

                $query = $this->db->query("SELECT a.*, b.name,b.surname,c.departament
                FROM
                    tbl_job_application a
                INNER JOIN tbl_users b ON a.id_user = b.id_user
                INNER JOIN cat_departament c ON b.id_departament = c.id_depto
                WHERE
                  a.id_user IN (
                        SELECT DISTINCT
                            id_user
                        FROM
                            tbl_requisitions_notifica_copy
                        WHERE
                            id_user_notificar = $id_user) LIMIT 1500");
            }


            $data = $query->getResult();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorize_Requisition()
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $status = $this->request->getPost('estatus');
            $estatus = ($status == 1) ? 'Autorizada' : 'Rechazada';
            $data = [
                'estatus' => $estatus
            ];
            $builder = $this->db->table('tbl_job_application');
            $builder->where('id_folio', $id_folio);
            $result = $builder->update($data);
            return ($result) ? json_encode($result) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function pdfSeeRequisition($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT a.*,
                                        c.departament,
                                        d.departament AS depto,
                                        b.name,
                                        b.surname
                                        FROM
                                        tbl_job_application a
                                        LEFT JOIN tbl_users b ON a.id_user = b.id_user
                                        LEFT JOIN cat_departament c ON a.area_operativa = c.id_depto
                                        LEFT JOIN cat_departament d ON b.id_departament = d.id_depto
                                        WHERE
                                        MD5(concat('" . $key . "',id_folio))='" . $id_request . "'");
        $dataRequest =  $query->getRow();
        $data = [
            "request" => $dataRequest
        ];

        $html2 = view('pdf/request', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Requisición');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('requisicion_' . $dataRequest->id_folio . '.pdf', 'I');
    }
}
