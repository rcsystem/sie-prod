<?php

namespace App\Controllers\Qhse;

use App\Controllers\BaseController;

use App\Models\RequisitionsModel;
use App\Models\CompanyModel;
use App\Models\StudiesModel;
use App\Models\PersonnelTypeModel;
use App\Models\ReasonRequisitionModel;
use App\Models\GenderModel;
use App\Models\CivilStatusModel;
use App\Models\DeptoModel;
use App\Models\UserModel;
use App\Models\AssignDepartmentsModel;
use App\Models\VisitQhseModel;
use App\Models\OverTimeModel;
use App\Models\UserOverTimeModel;
use App\Models\HseCarsModel;
use App\Models\ListEppModel;
use App\Models\EppRequestModel;
use App\Models\EppItemsModel;
use App\Models\EppDeparturesModel;
use App\Models\EppEntriesModel;
use App\Models\EppModificationModel;
use App\Models\HseVolunteeringModel;
use App\Models\HseMenusSocialModel;
use App\Models\HseGuestsModel;

use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\I18n\Time;

// Incluye la biblioteca del generador de códigos de barras
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\Types\TypeCode128;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;



class Qhse extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->userModel = new UserModel();
        $this->deptoModel = new DeptoModel();
        $this->visitModel = new VisitQhseModel();
        $this->overTimeModel = new OverTimeModel();
        $this->userOverTimeModel = new UserOverTimeModel();
        $this->requisitionsModel = new RequisitionsModel();
        $this->companyModel = new CompanyModel();
        $this->studiesModel = new StudiesModel();
        $this->personnelTypeModel = new PersonnelTypeModel();
        $this->reasonRequisitionModel = new ReasonRequisitionModel();
        $this->civilStatusModel = new CivilStatusModel();
        $this->genderModel = new GenderModel();
        $this->assignDeptoModel = new AssignDepartmentsModel();
        $this->carsModel = new HseCarsModel();
        $this->listEppModel = new ListEppModel();
        $this->eppRequestModel = new EppRequestModel();
        $this->eppItemsModel = new EppItemsModel();
        $this->eppDepartureModel = new EppDeparturesModel();
        $this->eppEntriesModel = new EppEntriesModel();
        $this->eppModModel = new EppModificationModel();
        $this->volunteeringModel = new HseVolunteeringModel();


        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }


    public function Suppliers()
    {
        $builder = $this->db->table('cat_departament');
        $builder->select('id_depto, departament, area');
        $query = $builder->get()->getResultArray();
        foreach ($query as $key => $value) {
            $groups[$value['area']][$value['id_depto']] = $value['departament'];
        }
        $data = [
            "departament" => $groups
        ];
        return ($this->is_logged) ?  view('qhse/suppliers', $data) : redirect()->to(site_url());
    }
    public function Authorize()
    {
        return ($this->is_logged) ?  view('qhse/authorize') : redirect()->to(site_url());
    }

    public function myPermissions()
    {
        return ($this->is_logged) ?  view('qhse/my_permissions') : redirect()->to(site_url());
    }

    public function overTime()
    {
        return ($this->is_logged) ?  view('qhse/overtime') : redirect()->to(site_url());
    }

    public function DarckTime()
    {
        return ($this->is_logged) ?  view('user/overtime') : redirect()->to(site_url());
    }

    public function viewEquipmentDelivery()
    {
        return ($this->is_logged) ?  view('qhse/equipment') : redirect()->to(site_url());
    }

    public function viewListEquipment()
    {
        return ($this->is_logged) ?  view('qhse/list_articles') : redirect()->to(site_url());
    }

    public function viewListMenus()
    {
        return ($this->is_logged) ?  view('qhse/view_hse_menus') : redirect()->to(site_url());
    }

    public function viewRaceWithCause()
    {
        return ($this->is_logged) ?  view('qhse/view_race_with_a_cause') : redirect()->to(site_url());
    }



    public function viewInventaryEpp()
    {
        $query = $this->db->query("SELECT id_user, CONCAT(`name`,' ',surname,' ',second_surname) AS user_name 
			FROM tbl_users 
			WHERE active_status = 1 
			AND id_user NOT IN (710,1121,1327,1248) 
		ORDER BY surname ASC")->getResult();
        $data = ["users" => $query];
        return ($this->is_logged) ?  view('qhse/equiment_raw_material', $data) : redirect()->to(site_url());
    }

    public function viewInventaryEppB()
    {
        $query = $this->db->query("SELECT id_user, CONCAT(`name`,' ',surname,' ',second_surname) AS user_name 
			FROM tbl_users 
			WHERE active_status = 1 
			AND id_user NOT IN (710,1121,1327,1248) 
		ORDER BY surname ASC")->getResult();
        $data = ["users" => $query];
        return ($this->is_logged) ?  view('qhse/equiment_raw_materialB', $data) : redirect()->to(site_url());
    }


    public function viewRequestepp()
    {
        return ($this->is_logged) ?  view('qhse/table_request_epp') : redirect()->to(site_url());
    }
    public function viewRequestAlm()
    {
        return ($this->is_logged) ?  view('qhse/table_request_alm') : redirect()->to(site_url());
    }

    /* VISTAS DEL MODULO DE RESPONSABILIDAD SOCIAL */

    public function viewVolunteeringEvent()
    {
        return ($this->is_logged) ?  view('qhse/view_volunteering_by_event') : redirect()->to(site_url());
    }

    public function viewListEvent()
    {
        return ($this->is_logged) ?  view('qhse/view_list_volunteering_event') : redirect()->to(site_url());
    }


    public function viewPermanentEvent()
    {
        return ($this->is_logged) ?  view('qhse/view_permanent_by_event') : redirect()->to(site_url());
    }

    public function viewCleanupCampaign()
    {
        return ($this->is_logged) ?  view('qhse/view_cleanup_campaing') : redirect()->to(site_url());
    }

    public function viewReforestar()
    {
        return ($this->is_logged) ?  view('qhse/view_reforestar') : redirect()->to(site_url());
    }

    public function overtimeAuthorize()
    {
        $query = $this->db->query("SELECT * FROM tbl_qhse_overtime 
        WHERE authorize = 2 AND active_status = 1 ORDER BY id DESC ")->getResult();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function overtimeAllUser()
    {
        // Obtén el ID desde el input de forma segura
        $id_overtime = $this->request->getPost('id_');

        // Verifica que el ID sea un número entero válido
        if (!is_numeric($id_overtime)) {
            return json_encode(false);
        }

        // Prepara la consulta de forma segura
        $builder = $this->db->table('tbl_qhse_user_overtime');
        $builder->select('payroll_number, user, id, job, depto');
        $builder->where('id_overtime', $id_overtime);
        $builder->orderBy('id', 'DESC');

        $query = $builder->get()->getResult();
        return $query ? json_encode($query) : json_encode(false);
    }

    public function permits_suppliers_all()
    {
        try {

            $builder = $this->db->table('tbl_qhse_visit_suppliers');
            $builder->select('*');
            $builder->where('active_status', 1);
            $builder->orderBy('id', 'DESC');
            $builder->limit(1100);
            $query = $builder->get()->getResult();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e->error);
        }
    }

    public function permits_suppliers_stay_all()
    {
        try {

            $builder = $this->db->table('tbl_qhse_visit_suppliers');
            $builder->select('*');
            $builder->where('active_status', 1);
            $builder->where('permit_type', 2);
            $builder->orderBy('id', 'DESC');
            $builder->limit(1100);
            $query = $builder->get()->getResult();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e->error);
        }
    }

    public function my_permits_suppliers()
    {
        try {

            $builder = $this->db->table('tbl_qhse_visit_suppliers');
            $builder->select('*');
            $builder->where('id_user', session()->id_user);
            $builder->limit(1100);
            $builder->orderBy('id', 'DESC');
            $query = $builder->get()->getResult();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function overtime_all()
    {
        try {

            $builder = $this->db->table('tbl_qhse_overtime');
            $builder->select('*');
            $builder->where('active_status', 1);
            $builder->limit(1100);
            $builder->orderBy('id', 'DESC');
            $query = $builder->get()->getResult();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    public function my_overtime()
    {
        try {

            $builder = $this->db->table('tbl_qhse_overtime');
            $builder->select('*');
            $builder->where('id_user', session()->id_user);
            $builder->limit(1100);
            $query = $builder->get()->getResult();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function permits_authorize()
    {
        try {
            $id_folio = trim($this->request->getPost('id_folio'));
            $status = trim($this->request->getPost('autorizacion'));
            $date = date("Y-m-d H:i:s");
            $data = ['authorize' => $status, "updated_at" => $date, 'id_authorize' => session()->id_user];

            $this->emailNotificationAuthorize($id_folio, 2);

            return ($this->visitModel->update($id_folio, $data)) ? json_encode("ok") : json_encode('error');
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function overtime_authorize()
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $status = trim($this->request->getPost('autorizacion'));
            $date = date("Y-m-d H:i:s");
            $data = ['authorize' => $status, "updated_at" => $date, 'id_authorize' => session()->id_user];

            $this->emailNotificationAuthorize($id_folio, 1);

            return ($this->overTimeModel->update($id_folio, $data)) ? json_encode("ok") : json_encode('error');
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function suppliers_details()
    {
        $id_folio = $this->request->getPost('id_folio');
        $permits  = $this->visitModel->where('id', $id_folio)->find();
        return (count($permits) > 0) ? json_encode($permits) : json_encode("error");
    }

    public function overtime_details()
    {
        $id_folio = $this->request->getPost('id_folio');
        $permits  = $this->overTimeModel->where('id', $id_folio)->find();
        return (count($permits) > 0) ? json_encode($permits) : json_encode("error");
    }

    public function visit_suppliers()
    {
        try {

            $builder = $this->db->table('tbl_qhse_visit_suppliers');
            $builder->select('*');
            $builder->where('authorize', 2);
            $builder->orderBy('id', 'DESC');
            $builder->limit(1100);
            $query = $builder->get()->getResult();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function visit_suppliers_stay()
    {
        try {

            $builder = $this->db->table('tbl_qhse_visit_suppliers');
            $builder->select('*');
            $builder->where('authorize', 2);
            $builder->where('permit_type', 2);
            $builder->orderBy('id', 'DESC');
            $builder->limit(1100);
            $query = $builder->get()->getResult();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function vg_over_time()
    {
        try {

            $builder = $this->db->table('tbl_qhse_overtime');
            $builder->select('*');
            $builder->where('authorize', 2);
            $builder->limit(1100);
            $query = $builder->get()->getResult();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function emailNotification($dataVisit = null, $id_result = null, $type = null, $receiver = null)
    {

        $dataVisitor = $this->db->query("SELECT visitor, nationality 
        FROM tbl_qhse_visitor 
        WHERE active_status = 1
        AND id_visitor = ?", [$id_result])->getResult();


        $dataCars = $this->db->query("SELECT modelo, color, placas 
            FROM tbl_hse_cars 
            WHERE active_status = 1
        AND id_hse_suppliers = ?", [$id_result])->getResult();

        $data = [
            "datos" => $dataVisit,
            "visitors" => $dataVisitor,
            "cars" => $dataCars
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
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Permiso|Visitantes & Proveedores');
            // Add a recipient
            if ($receiver == 1) {
                $mail->addAddress('gmendoza@walworth.com.mx', 'GERARDO MENDOZA VILLEGAS');
               // $mail->addCC('ahuerta@walworth.com.mx', 'ADRIAN ALEJANDRO HUERTA CALDERON');
            } else if ($receiver == 2) {
                $mail->addAddress('ldominguez@walworth.com.mx', 'LUIS ANGEL DOMINGUEZ CABAÑAS');
                $mail->addAttachment($dataVisit["imss"]);    // Optional name
            }
            //$mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');

            $mail->addBCC('rcruz@walworth.com.mx');
           // $mail->addBCC('hrivas@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            /* if ($dataVisit["imss"] != "") {

                $mail->addAttachment($dataVisit["imss"]);         // Add attachments
            }
            if ($dataVisit["poliza"] != "") {

                $mail->addAttachment($dataVisit["poliza"]);         // Add attachments
            } */

            //Content
            $mail->isHTML(true);
            $plantilla = ($type === 1) ? 'notificaciones/permisos_qhse' : 'notificaciones/permisos_qhse_estadia';
            $email_template = view($plantilla, $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Notificación de Permiso';
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function emailNotificationAuthorize($id_result = null, $type = null)
    {
        if ($type == 1) {
            $query1 = $this->db->query("SELECT * FROM tbl_qhse_overtime WHERE id =" . $id_result);
            $datas =  $query1->getRow();

            $user =  $datas->id_user;
            $name = $datas->name;
            $id =  $datas->id;

            $dataUser = $this->db->query("SELECT * FROM tbl_qhse_user_overtime WHERE id_overtime=" . $id);
            $users =  $dataUser->getResult();
            $query = $this->db->query("SELECT email FROM tbl_users WHERE id_user =" . $user);
            $email =  $query->getResult();
            $email = $email[0]->email;
            $data = ["folio" => $id, "personal" => $users, "request" => $datas];
        } else {

            $query = $this->db->query("SELECT * FROM tbl_qhse_visit_suppliers WHERE id=" . $id_result);
            $dataRequest =  $query->getRow();

            $user =  $dataRequest->id_user;
            $name = $dataRequest->name;
            $id =  $dataRequest->id;

            $query2 = $this->db->query("SELECT visitor,nationality FROM tbl_qhse_visitor WHERE id_visitor=" . $id);
            $dataVisitors =  $query2->getResult();
            $data = [
                "datos" => $dataRequest,
                "visitors" => $dataVisitors
            ];

            $query3 = $this->db->query("SELECT email
                FROM
                tbl_users
                WHERE id_user =" . $user);

            $email =  $query3->getRow();
            $email = $email->email;
        }

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $email = changeEmail($email);

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
            // $mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'SG | Autorización');
            // Add a recipient
            $mail->addAddress($email, $name);
            // Name is optional
            // Add a recipient

            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('vigilancia@walworth.com.mx');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            if ($type == 1) {
                $email_template = view('notificaciones/tiempo_extra_qhse_autorizar', $data);
            } else {
                $email_template = view('notificaciones/permisos_qhse_authorize', $data);
            }
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Autorización de Permiso';
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function emailNotificationOverTime($id_overtime)
    {
        $query = $this->db->query("SELECT b.*
                                        FROM
                                        tbl_qhse_overtime as a
                                        INNER JOIN tbl_qhse_user_overtime as b
                                        ON a.id = b.id_overtime
                                        WHERE a.id=" . $id_overtime);
        $dataPerson =  $query->getResult();
        $query1 = $this->db->query("SELECT *
                                        FROM
                                        tbl_qhse_overtime
                                        WHERE id=" . $id_overtime);

        $dataRequest =  $query1->getRow();
        $data = [
            "request" => $dataRequest,
            "personal" => $dataPerson
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
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Permiso|Horario Obscuro');

            // Add a recipient
            // $mail->addAddress('ldominguez@walworth.com.mx', 'Luis Dominguez');
            $mail->addAddress('gmendoza@walworth.com.mx', 'GERARDO MENDOZA VILLEGAS');
            $mail->addCC('ahuerta@walworth.com.mx', 'ADRIAN ALEJANDRO HUERTA CALDERON');

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');

            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/tiempo_extra_qhse', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Notificación de Horario Obscuro';
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    // public function generatePermissionsANT()
    // {
    //     try {

    //         $user = session()->name . " " . session()->surname;
    //         $departament_user = trim($this->request->getPost('departamento'));
    //         $job = trim($this->request->getPost('puesto'));
    //         $payroll_number = session()->payroll_number;
    //         $suppliers = trim($this->request->getPost('proveedor'));
    //         $num_persons = trim($this->request->getPost('num_personas'));
    //         $visity_persons = trim($this->request->getPost('visita'));
    //         $depto_visit = trim($this->request->getPost('depto'));
    //         $reason_for_visit = trim($this->request->getPost('motivo_visita'));
    //         $visit_of_day = trim($this->request->getPost('dia_visita'));
    //         $time_of_entry = trim($this->request->getPost('hora_entrada'));

    //         $visitor = $this->request->getPost('visitante');
    //         $nationality = $this->request->getPost('nacionalidad');


    //         $epp = $this->request->getPost('epp');
    //         $trabajos = $this->request->getPost('trabajos');
    //         $auto = $this->request->getPost('auto');

    //         $date = date("Y-m-d H:i:s");
    //         $date1 = date("Y-m-d");

    //         $carpeta2 = './doc/qhs_visit';
    //         if ($file_imss = $this->request->getFile('file_seguro_visita')) {
    //             $originalName = $file_imss->getClientName();
    //             $ext = $file_imss->getClientExtension();
    //             $type = $file_imss->getClientMimeType();
    //             $newName = 'visita_' . session()->id_user;
    //             $file_imss = $file_imss->move($carpeta2, "imss_" . $newName . '.' . $ext);
    //             $imss_file = $path = $carpeta2 . "/" . "imss_" . $newName . '.' . $ext;
    //         } else {
    //             $imss_file = "NA";
    //         }

    //         /* $carpeta2 = './uploads/archivos_' . session()->name . '/' . $date1;
    //         if (!file_exists($carpeta2)) {
    //             mkdir($carpeta2, 0777, true);
    //         }
    //         if ($file_poliza = $this->request->getFile('poliza')) {
    //             $originalName2 = $file_poliza->getClientName();
    //             $ext = $file_poliza->getClientExtension();
    //             $type = $file_poliza->getClientMimeType();
    //             $newName2 = $file_poliza->getRandomName();
    //             $file_poliza = $file_poliza->move($carpeta2, "poliza_" . $newName2);
    //             $poliza_file = $path = $carpeta2 . "/" . "poliza_" . $newName2;
    //         } else {
    //             $poliza_file = "NA";
    //         } */

    //         $data = [
    //             "id_user" => session()->id_user,
    //             "name" => $user,
    //             "departament" => $departament_user,
    //             "job" => $job,
    //             "payroll_number" => $payroll_number,
    //             "suppliers" => $suppliers,
    //             "num_persons" => $num_persons,
    //             "person_you_visit" => $visity_persons,
    //             "departament_you_visit" => $depto_visit,
    //             "reason_for_visit" => $reason_for_visit,
    //             "day_you_visit" => $visit_of_day,
    //             "time_of_entry" => $time_of_entry,
    //             "imss" => $imss_file,
    //             // "poliza" => $poliza_file,
    //             "epp" => $epp,
    //             "trabajos" => $trabajos,
    //             "auto" => $auto,
    //             "created_at" => $date
    //         ];
    //         $result = $this->visitModel->insert($data);
    //         $id_result = $this->db->insertID();

    //         $builder =  $this->db->table('tbl_qhse_visitor');
    //         for ($i = 0; $i < count($visitor); $i++) {
    //             $dataVisitor = [
    //                 'id_visitor' => $id_result,
    //                 'visitor' => $visitor[$i],
    //                 'nationality' => $nationality[$i],
    //                 'created_at' => $date
    //             ];
    //             $builder->insert($dataVisitor);
    //         }

    //         if ($auto == 1) {

    //             $modelo = $this->request->getPost('modelo');
    //             $color = $this->request->getPost('color');
    //             $placa = $this->request->getPost('placas');

    //             $dataCars = [
    //                 'id_hse_suppliers' => $id_result,
    //                 'modelo' => $modelo,
    //                 'color' => $color,
    //                 'placas' => $placa,
    //                 'created_at' => $date
    //             ];
    //             $this->carsModel->insert($dataCars);
    //         }

    //         $this->emailNotification($data, $id_result, 1);
    //         if ($epp == 1 || $trabajos == 1) {
    //             $this->emailNotification($data, $id_result, 2);
    //         }
    //         return ($result) ? json_encode('ok') : json_encode('error');
    //     } catch (\Exception $e) {
    //         return ('Ha ocurrido un error en el servidor ' . $e);
    //     }
    // }

    public function generatePermissions()
    {
        try {

            $permit_type = 1;
            $visit_of_day = "";

            $user = session()->name . " " . session()->surname;
            $departament_user = trim($this->request->getPost('departamento'));
            $job = trim($this->request->getPost('puesto'));
            $payroll_number = session()->payroll_number;
            $suppliers = trim($this->request->getPost('proveedor'));
            $num_persons = trim($this->request->getPost('num_personas'));
            $visity_persons = trim($this->request->getPost('visita'));
            $depto_visit = trim($this->request->getPost('depto'));
            $reason_for_visit = trim($this->request->getPost('motivo_visita'));
            $time_of_entry = trim($this->request->getPost('hora_entrada'));

            $visitor = $this->request->getPost('visitante');
            $nationality = $this->request->getPost('nacionalidad');


            $epp = $this->request->getPost('epp');
            $trabajos = $this->request->getPost('trabajos');
            $auto = $this->request->getPost('auto');

            $day = trim($this->request->getPost('dia_visita'));

            $date = date("Y-m-d H:i:s");

            $carpeta2 = './doc/qhs_visit';
            if ($file_imss = $this->request->getFile('file_seguro_visita')) {
                $originalName = $file_imss->getClientName();
                $ext = $file_imss->getClientExtension();
                $type = $file_imss->getClientMimeType();
                $newName = 'visita_' . session()->id_user;
                $file_imss = $file_imss->move($carpeta2, "imss_" . $newName . '.' . $ext);
                $imss_file = $path = $carpeta2 . "/" . "imss_" . $newName . '.' . $ext;
            } else {
                $imss_file = "NA";
            }


            // die(" ");
            $initial_date = "";
            $final_date = "";

            if (empty($day)) {

                $visits_of_days = $this->request->getPost('dias_visitas');
                $dateArray = explode(",", $visits_of_days);

                $newDateStringArray = array(); // Crear un arreglo para las nuevas fechas en formato "Y-m-d"

                foreach ($dateArray as $date) {
                    $dateObj = Time::createFromFormat('d/m/Y', $date);

                    if ($dateObj !== false) {
                        $newDateStringArray[] = $dateObj->format('Y-m-d');
                    }
                }

                $initial_date = $newDateStringArray[0];
                $final_date = $newDateStringArray[1];
                $permit_type = 2;
            } else {
                $dayObj = Time::createFromFormat('d/m/Y', $day);

                if ($dayObj !== false) {
                    $visit_of_day = $dayObj->format('Y-m-d');
                }
            }
            //  die(" ");

            $date = date("Y-m-d H:i:s");
            //   $date1 = date("Y-m-d");

            /*  $carpeta = './uploads/archivos_' . session()->name;

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            if ($file_poliza = $this->request->getFile('poliza')) {
                $originalName2 = $file_poliza->getClientName();
                $ext = $file_poliza->getClientExtension();
                $type = $file_poliza->getClientMimeType();
                $newName2 = $file_poliza->getRandomName();
                $file_poliza = $file_poliza->move($carpeta2, "poliza_" . $newName2);
                $poliza_file = $path = $carpeta2 . "/" . "poliza_" . $newName2;
            } else {
                $poliza_file = "NA";
            } */

            /* "imss" => $imss_file,
                "poliza" => $poliza_file, */
            $data = [
                "id_user" => session()->id_user,
                "name" => $user,
                "departament" => $departament_user,
                "job" => $job,
                "payroll_number" => $payroll_number,
                "suppliers" => $suppliers,
                "num_persons" => $num_persons,
                "person_you_visit" => $visity_persons,
                "departament_you_visit" => $depto_visit,
                "reason_for_visit" => $reason_for_visit,
                "day_you_visit" => $visit_of_day,
                "time_of_entry" => $time_of_entry,
                "epp" => $epp,
                "trabajos" => $trabajos,
                "auto" => $auto,
                "imss" => $imss_file,
                "created_at" => $date,
                "start_date_of_stay" => $initial_date,
                "end_date_of_stay" => $final_date,
                "permit_type" => $permit_type
            ];

            $result = $this->visitModel->insert($data);
            $id_result = $this->db->insertID();

            $builder =  $this->db->table('tbl_qhse_visitor');
            for ($i = 0; $i < count($visitor); $i++) {
                $dataVisitor = [
                    'id_visitor' => $id_result,
                    'visitor' => $visitor[$i],
                    'nationality' => $nationality[$i],
                    'created_at' => $date
                ];
                $builder->insert($dataVisitor);
            }

            if ($auto == 1) {
                $modelo = $this->request->getPost('modelo');
                $color = $this->request->getPost('color');
                $placa = $this->request->getPost('placas');

                $dataCars = [
                    'id_hse_suppliers' => $id_result,
                    'modelo' => $modelo,
                    'color' => $color,
                    'placas' => $placa,
                    'created_at' => $date
                ];
                $this->carsModel->insert($dataCars);
            }

            $this->emailNotification($data, $id_result, $permit_type, 1);
            if ($epp == 1 || $trabajos == 1) {
                $this->emailNotification($data, $id_result, $permit_type, 2);
            }
            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function SaveOverTime()
    {
        try {
            //$numf= $this->folioRequest();

            $payroll_number = trim($this->request->getPost('num_nomina'));
            $user = trim($this->request->getPost('usuario'));
            $departament = trim($this->request->getPost('departamento'));
            $job = trim($this->request->getPost('puesto'));
            $day_you_visit = trim($this->request->getPost('fecha_extra'));
            $time_of_entry = trim($this->request->getPost('hora_entrada'));
            $departure_time = trim($this->request->getPost('hora_salida'));

            $idUser = session()->id_user;
            $builder =  $this->overTimeModel->table('tbl_qhse_overtime');
            $date = date("Y-m-d H:i:s");
            $dataOver = [
                'id_user' => $idUser,
                'name' => $user,
                'departament' => $departament,
                'job' => $job,
                'payroll_number' => $payroll_number,
                'day_you_visit' => $day_you_visit,
                'time_of_entry' => $time_of_entry,
                'departure_time' => $departure_time,
                'created_at' => $date
            ];
            $insertOverTime = $builder->insert($dataOver);
            $id_overtime = $this->db->insertID();
            if ($insertOverTime) {

                $payroll_numer2 = $this->request->getPost('num_nomina_extra');
                $user2 = $this->request->getPost('usuario_extra');
                $job2 = $this->request->getPost('puesto_extra');
                $depto = $this->request->getPost('depto');

                $builder1 =  $this->userOverTimeModel->table('tbl_qhse_user_overtime');

                for ($i = 0; $i < count($payroll_numer2); $i++) {

                    $dataItem = [
                        'id_overtime' => $id_overtime,
                        'payroll_number' => $payroll_numer2[$i],
                        'user' => $user2[$i],
                        'job' => $job2[$i],
                        'depto' => $depto[$i]
                    ];
                    $insertItem = $builder1->insert($dataItem);
                }



                $this->emailNotificationOverTime($id_overtime);

                return ($insertItem) ? json_encode('ok') : json_encode("error");
            } else {
                return json_encode("error");
            }
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }


    public function pdfSeePermitions($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_qhse_visit_suppliers 
                                        WHERE
                                        MD5(concat('" . $key . "',id))='" . $id_request . "'");
        $dataRequest =  $query->getRow();

        $query2 = $this->db->query("SELECT *
                                        FROM
                                        tbl_qhse_visitor
                                        WHERE
                                        MD5(concat('" . $key . "',id_visitor))='" . $id_request . "'");
        $dataVisitors =  $query2->getResult();
        $query3 = $this->db->query("SELECT  modelo,color,placas
                                    FROM
                                    tbl_hse_cars
                                    WHERE MD5(concat('" . $key . "',id_hse_suppliers))='" . $id_request . "'");
        $dataCars =  $query3->getResult();
        $data = [
            "request" => $dataRequest,
            "visitor" => $dataVisitors,
            "cars" => $dataCars
        ];

        $html2 = view('pdf/qhse_permission', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Requisición');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('requisicion_' . $id_request . '.pdf', 'I');
    }

    public function pdfOverTime($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT b.*
                                        FROM
                                        tbl_qhse_overtime as a
                                        INNER JOIN tbl_qhse_user_overtime as b
                                        ON a.id = b.id_overtime
                                        WHERE
                                        MD5(concat('" . $key . "',a.id))='" . $id_request . "'");
        $dataPerson =  $query->getResult();
        $query1 = $this->db->query("SELECT *
                                        FROM
                                        tbl_qhse_overtime
                                        WHERE
                                        MD5(concat('" . $key . "',id))='" . $id_request . "'");
        $dataRequest =  $query1->getRow();
        $data = [
            "request" => $dataRequest,
            "personal" => $dataPerson
        ];

        $html2 = view('pdf/qhse_overtime', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Requisición');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('requisicion_' . $id_request . '.pdf', 'I');
    }


    public function authorizeRequisition()
    {
        try {
            $id_user = session()->id_user;
            $query = $this->db->query("SELECT
            a.*, b.name,b.surname,c.departament
        FROM
            tbl_job_application a
        INNER JOIN tbl_users b ON a.id_user = b.id_user
        INNER JOIN cat_departament c ON b.id_departament = c.id_depto
        WHERE
            area_operativa IN (
                SELECT DISTINCT
                    id_departament
                FROM
                    tbl_assign_departments_to_managers
                WHERE
                    id_director = $id_user)");
            $data = $query->getResult();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }



    public function overtimeXlsx()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $fecha_inicio = $data->fecha_inicio;
        $fecha_fin = $data->fecha_fin;
        $NombreArchivo = "Reporte_" . $fecha_inicio . "_" . $fecha_fin . ".xlsx";
        $query = $this->db->query("SELECT a.*, b.day_you_visit, b.time_of_entry, b.departure_time FROM tbl_qhse_user_overtime AS a
        JOIN tbl_qhse_overtime As b on b.id = a.id_overtime WHERE b.authorize = 2 AND b.day_you_visit BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY b.day_you_visit DESC")->getResult();

        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:F1');
        $sheet->setTitle("Usuarios");


        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

        // ANCHO DE CELDA
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);

        //UBICACION DEL TEXTO
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
        $sheet->getStyle('A1:F1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

        //COLOR DE CELDAS
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

        // FONT-TEXT
        $sheet->getStyle("A1:F1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(10)
            ->getColor()
            ->setRGB('FFFFFF');

        // TITULO DE CELDA
        $sheet->setCellValue('A1', 'NOMINA');
        $sheet->setCellValue('B1', 'NOMBRE EMPLEADO');
        $sheet->setCellValue('C1', 'PUESTO');
        $sheet->setCellValue('D1', 'FECHA');
        $sheet->setCellValue('E1', 'HORA ENTRADA');
        $sheet->setCellValue('F1', 'HORA SALIDA');

        foreach ($query as $key => $value) {
            $sheet->setCellValue('A' . $cont, $value->payroll_number);
            $sheet->setCellValue('B' . $cont, $value->user);
            $sheet->setCellValue('C' . $cont, $value->job);
            $sheet->setCellValue('D' . $cont, date("d/m/Y", strtotime($value->day_you_visit)));
            $sheet->setCellValue('E' . $cont, $value->time_of_entry);
            $sheet->setCellValue('F' . $cont, $value->departure_time);
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

    public function visitXlsx()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $fecha_inicio = $data->fecha_inicio;
        $fecha_fin = $data->fecha_fin;
        $NombreArchivo = "Reporte_" . $fecha_inicio . "_" . $fecha_fin . ".xlsx";
        $query = $this->db->query("SELECT a.*,b.person_you_visit, b.departament_you_visit, b.reason_for_visit, b.day_you_visit, b.time_of_entry 
        FROM tbl_qhse_visitor AS a LEFT JOIN tbl_qhse_visit_suppliers AS b ON a.id_visitor = b.id
        WHERE b.authorize = 2 AND b.day_you_visit BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY b.day_you_visit DESC")->getResult();

        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:G1');
        $sheet->setTitle("Visitantes");


        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

        // ANCHO DE CELDA
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(50);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(22);

        //UBICACION DEL TEXTO
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL
        $sheet->getStyle('A:G')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL
        $sheet->getStyle('D:E')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY); // definir alineacion de texto VERTICAL

        //COLOR DE CELDAS
        $spreadsheet->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

        // FONT-TEXT
        $sheet->getStyle("A1:G1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(10)
            ->getColor()
            ->setRGB('FFFFFF');

        // TITULO DE CELDA
        $sheet->setCellValue('A1', 'VISITANTE');
        $sheet->setCellValue('B1', 'NACIONALIDAD');
        $sheet->setCellValue('C1', 'VISITA A');
        $sheet->setCellValue('D1', 'DEPARTAMENTO');
        $sheet->setCellValue('E1', 'RAZÓN DE VISITA');
        $sheet->setCellValue('F1', 'FECHA');
        $sheet->setCellValue('G1', 'HORA');

        foreach ($query as $key => $value) {
            $sheet->setCellValue('A' . $cont, mb_strtoupper($value->visitor));
            $sheet->setCellValue('B' . $cont, mb_strtoupper($value->nationality));
            $sheet->setCellValue('C' . $cont, mb_strtoupper($value->person_you_visit));
            $sheet->setCellValue('D' . $cont, $value->departament_you_visit);
            $sheet->setCellValue('E' . $cont, $value->reason_for_visit);
            $sheet->setCellValue('F' . $cont, date("d/m/Y", strtotime($value->day_you_visit)));
            $sheet->setCellValue('G' . $cont, $value->time_of_entry);
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

    public function listEpp()
    {
        try {
            $query = $this->listEppModel->ListEppAll();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function inventaryEpp()
    {
        try {
            $query = $this->listEppModel->inventary();

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function listEppName()
    {
        try {
            $item = trim($this->request->getPost('product'));


            $query = $this->listEppModel->listEppName($item);

            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function listStoreArticles()
    {

        try {
            // Obtener el código desde el POST y escapar los caracteres especiales para evitar inyección SQL
            $code = trim($this->request->getPost('codigo'));

            // Preparar la consulta para evitar inyección SQL usando vinculaciones
            $query = "SELECT * FROM tbl_store_raw_material WHERE code = ? AND active_status = 1 LIMIT 1;";
            $data = $this->db->query($query, [$code])->getResult();

            // Retornar la respuesta JSON
            return $this->response->setJSON(($data != null) ? $data : ["error" => "No data found"]);
        } catch (\Exception $e) {
            // Capturar la excepción y retornar un mensaje de error JSON
            return $this->response->setJSON(['error' => 'Ha ocurrido un error en el servidor: ' . $e->getMessage()]);
        }
    }

    public function listStoreItemArticles()
    {

        try {
            // Obtener el código desde el POST y escapar los caracteres especiales para evitar inyección SQL
            $desc = trim($this->request->getPost('description'));

            // Preparar la consulta para evitar inyección SQL usando vinculaciones
            $query = "SELECT * FROM tbl_store_raw_material WHERE description LIKE ? AND active_status = 1;";
            //$data = $this->db->query($query, ["%{$desc}%"])->getResult();
            $data = $this->db->query($query, ["%{$desc}%"])->getResultArray();


            // Retorna el resultado en formato JSON
            return $this->response->setJSON($data);

            // Retornar la respuesta JSON
            //  return $this->response->setJSON(($data != null) ? ['suggestions' => $results] : ["error" => "No data found"]);
        } catch (\Exception $e) {
            // Capturar la excepción y retornar un mensaje de error JSON
            return $this->response->setJSON(['error' => 'Ha ocurrido un error en el servidor: ' . $e->getMessage()]);
        }
    }



    public function insertDeliveryEpp()
    {
        $this->db->transStart();

        // Inicializa el array $epp como vacío
        $epp = [];

        $epp_num_nomina = trim($this->request->getPost('epp_num_nomina'));
        $epp_depto = trim($this->request->getPost('epp_depto'));
        $epp_puesto = trim($this->request->getPost('epp_puesto'));
        $epp_product = $this->request->getPost('epp');
        $epp_centro_costo = trim($this->request->getPost('epp_centro_costo'));

        $epp_usuario = trim($this->request->getPost('epp_usuario'));
        $cantidad = $this->request->getPost('cantidad');
        $id_product = $this->request->getPost('id_product');
        $id_user = $this->request->getPost('id_user');

        $medida = $this->request->getPost('medida');

        $entrega_epp = $this->request->getPost('entrega_equipo');

        $specify = $this->request->getPost('especificar');
        $option = $this->request->getPost('opt1');
        $code = $this->request->getPost('codigo');


        $ProductMin = [];
        $bandera = 0;
        $date = date("Y-m-d H:i:s");


        if ($entrega_epp == "") {

            $caracteres = '0123456789';
            $longitud = 5;
            $codigo = '';
            for ($i = 0; $i < $longitud; $i++) {
                $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
            }

            $data_request = [
                "id_user" => mb_convert_encoding($id_user, 'UTF-8'),
                "payroll_number" => mb_convert_encoding($epp_num_nomina, 'UTF-8'),
                "name" => mb_convert_encoding($epp_usuario, 'UTF-8'),
                "job_position" => mb_convert_encoding($epp_puesto, 'UTF-8'),
                "cost_center" => mb_convert_encoding($epp_centro_costo, 'UTF-8'),
                "departament" => mb_convert_encoding($epp_depto, 'UTF-8'),
                'created_at' => mb_convert_encoding($date, 'UTF-8'),
                'pw_security' => mb_convert_encoding($codigo, 'UTF-8'),
                'option' => mb_convert_encoding($option, 'UTF-8'),
                'specify' => mb_convert_encoding($specify, 'UTF-8'),
                'qr_image' => ''
            ];
        } else {
            $codigo = "Se entrega en persona.";

            $data_request = [
                "id_user" => mb_convert_encoding($id_user, 'UTF-8'),
                "payroll_number" => mb_convert_encoding($epp_num_nomina, 'UTF-8'),
                "name" => mb_convert_encoding($epp_usuario, 'UTF-8'),
                "job_position" => mb_convert_encoding($epp_puesto, 'UTF-8'),
                "cost_center" => mb_convert_encoding($epp_centro_costo, 'UTF-8'),
                "departament" => mb_convert_encoding($epp_depto, 'UTF-8'),
                'created_at' => mb_convert_encoding($date, 'UTF-8'),
                'pw_security' => 1,
                'request_status' => 2,
                'id_user_deliver' => mb_convert_encoding($id_user, 'UTF-8'),
                'specify' => mb_convert_encoding($specify, 'UTF-8'),
                'option' => mb_convert_encoding($option, 'UTF-8'),
                'qr_image' => ''

            ];
        }





        $result = $this->eppRequestModel->insert($data_request);

        $id_request = $this->db->insertID();


        // Asegúrate de que la carpeta exista
        if (!is_dir('../public/uploads/barcodes')) {
            mkdir('../public/uploads/barcodes', 0777, true);
        }
        // mkdir(APPPATH . 'uploads/barcodes/codigo_' . $id_request, 0777, true);

        if (!is_dir('../public/uploads/qrcodes/codigo_' . $id_request)) {
            mkdir('../public/uploads/qrcodes/codigo_' . $id_request, 0777, true);
        }




        $generator = new BarcodeGeneratorPNG();

        // Crear directorio antes de guardar archivos
        $barcodeDir = '../public/uploads/barcodes/codigo_' . $id_request;
        if (!is_dir($barcodeDir)) {
            mkdir($barcodeDir, 0777, true);
        }

        if (!is_dir('../public/uploads/usuario_codigo/usuario_' . $epp_num_nomina)) {
            mkdir('../public/uploads/usuario_codigo/usuario_' . $epp_num_nomina, 0777, true);


            $barcode_num = $generator->getBarcode($epp_num_nomina, $generator::TYPE_CODE_128);
            $barcode_cost = $generator->getBarcode($epp_centro_costo, $generator::TYPE_CODE_128);

            $numberFilePath = '../public/uploads/usuario_codigo/usuario_' . $epp_num_nomina . '/number_' . $epp_num_nomina . '.png';
            $costFilePath = '../public/uploads/usuario_codigo/usuario_' . $epp_num_nomina . '/cost_' . $epp_num_nomina . '.png';

            file_put_contents($numberFilePath, $barcode_num);
            file_put_contents($costFilePath, $barcode_cost);
            // Asegúrate de que las claves del array coinciden con los nombres de las columnas en la base de datos
            $data_code = [
                'payrollnumber_image' => '../public/uploads/usuario_codigo/usuario_' . $epp_num_nomina . '/number_' . $epp_num_nomina . '.png',
                'costcenter_image' => '../public/uploads/usuario_codigo/usuario_' . $epp_num_nomina . '/cost_' . $epp_num_nomina . '.png'
            ];
            $id_user = (int)$id_user; // Asegúrate de que $id_user es un entero
            $this->userModel->update($id_user, $data_code);
        }







        // Generar y guardar códigos de barras
        for ($epps = 0; $epps < count($id_product); $epps++) {

            $jsonStringCode = $code[$epps];
            $jsonStringProduct = $epp_product[$epps];

            $barcode = $generator->getBarcode($jsonStringCode, $generator::TYPE_CODE_128);
            $barcode2 = $generator->getBarcode($jsonStringProduct, $generator::TYPE_CODE_128);

            $codigoFilePath = $barcodeDir . '/codigo_' . $epps . '_' . $id_request . '.png';
            $productoFilePath = $barcodeDir . '/producto_' . $epps . '_' . $id_request . '.png';

            file_put_contents($codigoFilePath, $barcode);
            file_put_contents($productoFilePath, $barcode2);
        }

        // Verificar si $jsonString tiene errores
        $jsonString = json_encode($data_request);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Error en JSON: ' . json_last_error_msg();
        } else {
            /*   $this->eppRequestModel->update($id_request, [
                'barcode_image' => 'public/uploads/barcodes/codigo_' . $id_request . '/codigo_' . $id_request . '.png'
            ]); */
        }


        $data_qr = [
            "payroll_number" => mb_convert_encoding($epp_num_nomina, 'UTF-8'),
            "name" => mb_convert_encoding($epp_usuario, 'UTF-8'),
            "job_position" => mb_convert_encoding($epp_puesto, 'UTF-8'),
            "cost_center" => mb_convert_encoding($epp_centro_costo, 'UTF-8'),
            "departament" => mb_convert_encoding($epp_depto, 'UTF-8'),
            'created_at' => mb_convert_encoding($date, 'UTF-8'),
            // 'epp' =>  $epp,

        ];


        $jsonString = json_encode($data_qr);


        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Error en JSON: ' . json_last_error_msg();
        } else {


            /*   // Crear la instancia del código QR
            $qrCode = new QrCode($jsonString);

            // Especificar el tamaño del QR y otros parámetros opcionales
            $qrCode->setSize(300);

            // Generar la imagen PNG del código QR
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Guardar la imagen del código QR
            // $file = WRITEPATH . `uploads/qrcodes/codigo_{$id_request}/qr_{$id_request}.png`;
            $file = '../public/uploads/qrcodes/codigo_' . $id_request . '/qr_' . $id_request . '.png';

            $result->saveToFile($file); */


            // Actualizar el campo qr_image en la base de datos con la ruta de la imagen
            // $this->eppRequestModel->update($id_request, ['qr_image' => 'public/uploads/qrcodes/codigo_' . $id_request . '/qr_' . $id_request . '.png']);

            //   $this->eppRequestModel->update($id_request, ['barcode_image' => 'public/uploads/barcodes/codigo_' . $id_request . '/codigo_' . $id_request . '.png']);
            // Guarda el archivo PNG del código QR
            /* $file = $qrCodeDir . '/qr_' . $id_request . '.png';
               $result->saveToFile($file); */
        }

        $builder =  $this->db->table('tbl_hse_epp_items');

        for ($epps = 0; $epps < count($id_product); $epps++) {
            $dataVisitor = [
                'id_request' => $id_request,
                'id_product' => $id_product[$epps],
                'product' => $epp_product[$epps],
                'quantity' => $cantidad[$epps],
                'unit' => $medida[$epps],
                'code_store' => $code[$epps],
                'created_at' => $date,
                'barcode_image' =>  $barcodeDir . '/codigo_' . $epps . '_' . $id_request . '.png',
                'product_image' =>  $barcodeDir . '/producto_' . $epps . '_' . $id_request . '.png'


            ];
            $insertItem = $builder->insert($dataVisitor);
        }

        /*  if ($bandera > 0) {
            $this->emailNotificationMinimo($ProductMin);
        } */



        $result = $this->db->transComplete();

        return ($result) ? json_encode($codigo) : json_encode(false);
    }

    public function emailNotificationMinimo($data = null)
    {

        $data = ["request" => $data];

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
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Minimos|Epp');

            /*    $mail->addAddress('szamora@walworth.com.mx', 'Sergio Zamora');
            $mail->addAddress('ldominguez@walworth.com.mx', 'Luis Angel Dominguez'); */
            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Articulos al Minimo';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/epp_stock_minimo', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function inventoryItemEpp()
    {
        $id = trim($this->request->getPost('id_producto'));
        $nom = trim($this->request->getPost('description_product'));
        if ($id != null) {
            $data = $this->listEppModel->select('stock_product')->where('id_product', $id)->first();
        }
        if ($nom != null) {
            $data = $this->listEppModel->select('stock_product')->where('description_product', $nom)->first();
        }
        return ($data != null) ? json_encode($data) : json_encode("error");
    }

    public function editProductEpp($id_product)
    {
        $userData = $this->listEppModel->find($id_product);
        return ($userData) ? json_encode($userData) : json_encode(false);
    }

    public function parametersEpp()
    {
        try {
            $this->db->transStart();

            $id_folio = trim($this->request->getPost('id_folio'));
            $product = trim($this->request->getPost('producto'));
            $maximum = trim($this->request->getPost('maximo'));
            $minimum = trim($this->request->getPost('minimo'));
            $unit_of_measurement = trim($this->request->getPost('unidad_medida'));
            $date = date("Y-m-d H:i:s");

            $dataM = [
                "id_user" => session()->id_user,
                "id_product" => $id_folio,
                "product" => $product,
                "maximum" => $maximum,
                "minimum" => $minimum,
                "unit_of_measurement" => $unit_of_measurement,
                "created_at" => $date
            ];


            $this->eppModModel->insert($dataM);

            $data = ['stock_max' => $maximum, "stock_min" => $minimum, "unit_of_measurement" => $unit_of_measurement];

            $this->db->transComplete();

            return ($this->listEppModel->update($id_folio, $data)) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function entriesEpp()
    {
        try {

            $this->db->transStart();

            $id_folio = trim($this->request->getPost('id_producto'));
            $product = trim($this->request->getPost('producto'));
            $quantity = trim($this->request->getPost('cantidad'));
            $obs = trim($this->request->getPost('observacion'));

            $obs1 = ($obs == "undefined") ? "" : $obs;

            $date = date("Y-m-d H:i:s");

            $dataM = [
                "id_user" => session()->payroll_number,
                "id_product" => $id_folio,
                "product" => $product,
                "amount" => $quantity,
                "operation" => "entrada",
                "observations" => $obs1,
                "created_at" => $date
            ];

            $this->eppEntriesModel->insert($dataM);

            $builder = $this->db->table('tbl_hse_inventary_epp');
            $builder->select('id_product,stock_product');
            $builder->where('id_product', $id_folio);
            $builder->where('active_status', 1);
            $datas = $builder->get()->getResult();

            foreach ($datas as $key => $value) {
                $data = ['stock_product' => $value->stock_product + $quantity];
            }

            $this->db->transComplete();

            return ($this->listEppModel->update($id_folio, $data)) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function departuresEpp()
    {

        try {
            $this->db->transStart();
            $id_folio = trim($this->request->getPost('id_producto'));
            $product = trim($this->request->getPost('producto'));
            $quantity = trim($this->request->getPost('cantidad'));
            $obs = trim($this->request->getPost('observacion'));
            $date = date("Y-m-d H:i:s");

            $obs1 = ($obs == "undefined") ? "" : $obs;


            $data = [
                "id_user" => session()->id_user,
                "id_product" => $id_folio,
                "product" => $product,
                "amount" => $quantity,
                "operation" => "Salida",
                "observations" => $obs1,
                "created_at" => $date
            ];

            $this->eppDepartureModel->insert($data);

            $builder = $this->db->table('tbl_hse_inventary_epp');
            $builder->select('id_product,stock_product');
            $builder->where('id_product', $id_folio);
            $builder->where('active_status', 1);
            $datas = $builder->get()->getResult();

            foreach ($datas as $key => $value) {
                $data = ['stock_product' => $value->stock_product - $quantity];
            }

            $this->db->transComplete();

            return ($this->listEppModel->update($id_folio, $data)) ? json_encode(true) : json_encode('error');
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function deleteEpp()
    {
        try {
            $this->db->transStart();

            $id_product = trim($this->request->getPost('id_producto'));
            $data = ["active_status" => 2, "id_user_delete" => session()->id_user];
            $result = $this->listEppModel->update($id_product, $data);

            $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function listRequestEpp()
    {
        $estados = [1]; // Reemplaza "estado1" y "estado2" con los valores de los estados que deseas comparar
        $resultados = $this->eppRequestModel->whereIn('active_status', $estados)->findAll();

        return json_encode($resultados);
    }
    function listDataValesByUser()
    {
        try {

            $idUser = $this->request->getPost('id_user');
            $query = $this->db->query("SELECT a.id_request, b.id_request_item, b.product, b.quantity, b.unit,a.specify,b.barcode_image,b.product_image,c.payrollnumber_image,d.barcode_eqps,d.barcode_unif
                                        FROM tbl_hse_epp_requests AS a 
                                            INNER JOIN tbl_hse_epp_items AS b ON a.id_request = b.id_request AND b.active_status = 1
                                            LEFT JOIN tbl_users AS c ON a.id_user = c.id_user 
											LEFT JOIN cat_cost_center AS d ON c.id_cost_center = d.id_cost_center 
                                        WHERE  a.active_status = 1 AND a.request_status = 1  
                                        -- AND a.created_at In (SELECT created_at FROM tbl_hse_epp_requests WHERE active_status = 1 AND request_status = 1 AND id_user = $idUser)
                                        AND a.id_user = $idUser")
                ->getResult() ?? false;
            return json_encode($query);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    function voucherList()
    {
        try {

            $idUser = $this->request->getPost('id_user');
            $query = $this->db->query("SELECT
                                          a.id_request, b.id_request_item, b.product, b.quantity, b.unit,a.specify,b.barcode_image,b.product_image,c.payrollnumber_image,d.barcode_eqps,d.barcode_unif  
                                        FROM
                                            tbl_hse_epp_requests AS a
                                            INNER JOIN tbl_hse_epp_items AS b ON a.id_request = b.id_request AND b.active_status = 1 
                                            LEFT JOIN tbl_users AS c ON a.id_user = c.id_user 
	                                        LEFT JOIN cat_cost_center AS d ON c.id_cost_center = d.id_cost_center 
                                        WHERE
                                            a.active_status = 1 
                                            AND a.request_status = 1 
                                            AND a.id_user = $idUser")
                ->getResult() ?? false;

            // Group the items by request_id
            $groupedItems = [];
            foreach ($query as $item) {
                $groupedItems[$item->id_request][] = $item;
            }
            $data = ['groupedItems' => $groupedItems];

            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    function confirmDeliveryEPP()
    {
        $idRequest = $this->request->getPost('id_request');
        $clave = $this->request->getPost('clave');

        if ($this->db->query("SELECT id_request FROM tbl_hse_epp_requests WHERE active_status = 1 
        AND id_request = $idRequest AND pw_security = $clave")->getRow() == NULL) {
            return json_encode("errorClave");
        }
        $arrayIdItem_ = $this->request->getPost('id_item_');
        $arrayCantEntrega_ = $this->request->getPost('cant_entrega_');
        $comentario = $this->request->getPost('comentario');
        $this->db->transStart();
        for ($i = 0; $i < count($arrayIdItem_); $i++) {
            $updateItems = [
                'cant_confirm' => $arrayCantEntrega_[$i],
                'coment' => $comentario,
                'active_status' => 2,
                'delivery_at' => date("Y-m-d H:i:s"),
            ];
            $this->eppItemsModel->update($arrayIdItem_[$i], $updateItems);
        }

        $requestStatus = $this->db->query("SELECT IF(SUM(quantity) = SUM(cant_confirm),2,3) AS if_result 
            FROM tbl_hse_epp_items WHERE active_status = 1 AND id_request = $idRequest")->getRow()
            ->if_result;

        $updateRequest = [
            'request_status' => 2,
            'obs_request' => $comentario,
            'delivery_date' => date("Y-m-d H:i:s"),
        ];
        $this->eppRequestModel->update($idRequest, $updateRequest);

        $result = $this->db->transComplete();
        if ($result) {
            # code...
            return json_encode($idRequest);
        }
    }

    public function viewPdfEpp($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT
                                        a.id_request,
                                        a.created_at,
                                        a.name,
                                        a.payroll_number,
                                        a.departament,
                                        a.obs_request,
                                        a.request_status,
                                        a.specify,
                                        a.option,
                                        a.id_user_deliver,
                                        a.qr_image,
                                        a.cost_center,
                                        b.payrollnumber_image,
                                        b.costcenter_image
                                    FROM
                                        tbl_hse_epp_requests AS a
                                    INNER JOIN tbl_users as b 
                                    ON a.id_user = b.id_user
                                    WHERE
                                   MD5(concat('" . $key . "',id_request))='" . $id_request . "'");
        $dataRequest =  $query->getRow();

        $query2 = $this->db->query("SELECT id_request_item,product,quantity,cant_confirm, code_store,barcode_image,product_image
                                        FROM
                                        tbl_hse_epp_items
                                        WHERE
                                        MD5(concat('" . $key . "',id_request))='" . $id_request . "'");
        $dataItems =  $query2->getResult();


        $data = [
            "request" => $dataRequest,
            "articles" => $dataItems
        ];

        $html2 = view('pdf/pdf_hse_epp', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Requisición');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('requisicion_' . $id_request . '.pdf', 'I');
    }


    public function deleteEpps()
    {
        try {
            $this->db->transStart();

            $id_request = trim($this->request->getPost('folio'));
            $data = ["active_status" => 2, "id_user_delete" => session()->id_user];
            $result = $this->eppRequestModel->update($id_request, $data);

            $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function listArticlesEpp()
    {
        try {
            $builder = $this->db->table('tbl_hse_inventary_epp');
            $builder->select('id_product,description_product');
            $builder->where('active_status', 1);
            $builder->orderBy('id_product', 'DESC');
            //$builder->limit(1100);
            $data = $builder->get()->getResult();

            return (count($data) > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function deleteArticlesEpp()
    {


        $date = date("Y-m-d H:i:s");
        $id_folio = trim($this->request->getPost('folio'));

        $borrarM = ["create_at" => $date, "active_status" => 2, "id_delete_user" => session()->id_user];
        $deleteArticle = $this->listEppModel->update($id_folio, $borrarM);


        return ($deleteArticle) ? json_encode(true) : json_encode(false);
    }
    public function newArticlesEpp()
    {

        $date = date("Y-m-d H:i:s");
        $articles = trim($this->request->getPost('articulo'));
        $unit = trim($this->request->getPost('unidad'));

        $data = [
            "id_user" => session()->id_user,
            "name" => $user,
            "departament" => $departament_user,
            "job" => $job,
            "payroll_number" => $payroll_number,
            "suppliers" => $suppliers,
            "num_persons" => $num_persons,
            "person_you_visit" => $visity_persons,
            "departament_you_visit" => $depto_visit,
            "reason_for_visit" => $reason_for_visit,
            "day_you_visit" => $visit_of_day,
            "time_of_entry" => $time_of_entry,
            "epp" => $epp,
            "trabajos" => $trabajos,
            "auto" => $auto,
            "imss" => $imss_file,
            "created_at" => $date,
            "start_date_of_stay" => $initial_date,
            "end_date_of_stay" => $final_date,
            "permit_type" => $permit_type
        ];

        $result = $this->visitModel->insert($data);
    }


    function reportEpp()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));

        $NombreArchivo = "Reporte.xlsx";

        $query = $this->db->query("SELECT
                                        a.id_request,
                                        a.id_user,
                                        a.payroll_number,
                                        a.NAME,
                                        a.job_position,
                                        a.cost_center,
                                        a.departament,
                                        a.obs_request,
                                        a.request_status,
                                        a.pw_security,
                                        a.created_at,
                                        a.delivery_date,
                                        a.active_status,
                                        a.id_user_delete,
                                        a.id_user_deliver,
                                        a.specify,
                                        a.option,
                                        b.product,
                                        b.quantity,
                                        b.cant_confirm 
                                    FROM
                                        tbl_hse_epp_requests AS a
                                        INNER JOIN tbl_hse_epp_items AS b ON a.id_request = b.id_request 
                                    WHERE
                                        a.created_at BETWEEN '$data->fechaInicio' AND '$data->fechaFin'
                                        AND a.active_status = 1 
                                    ORDER BY
                                        a.id_request ASC")->getResult();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet();
        $sheet->setTitle("Reportes");
        $cont = 6;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(6);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(48);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(32);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(48);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);



        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(5);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(65);
        $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(10);
        $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
        $spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(25);



        $sheet->getStyle("D2")->getFont()->setBold(true)->setName('Calibri')->setSize(20)->getColor()->setRGB('000000');
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('./images/GrupoWalworth-Registrada.png'); // put your path and image here
        $drawing->setCoordinates('A2');
        $drawing->setHeight(60);
        $drawing->setOffsetX(27); // desplazamiento de imagen en x
        $drawing->setOffsetY(13); // desplazamiento de imagen en Y
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $spreadsheet->getActiveSheet()->getStyle('D2:K2')->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $spreadsheet->getActiveSheet()->getStyle('J2:K2')->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $sheet->setCellValue('A2', '')->mergeCells('A2:C2');
        $sheet->setCellValue('D2', 'REPORTE DE ENTREGA EPP')->mergeCells('D2:I2');
        $sheet->setCellValue('J2', "FHSE \n NOM-017-STPS \n Rev. Original")->getStyle('J2')->getAlignment()->setWrapText(true);
        $sheet->mergeCells('J2:K2');

        $sheet->setCellValue('B5', 'FOLIO');
        $sheet->setCellValue('C5', 'N° EMPLEADO');
        $sheet->setCellValue('D5', 'FECHA ENTREGA');
        $sheet->setCellValue('E5', "NOMBRE")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('F5', "PUESTO")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G5', "LINEA / AREA")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('H5', "PRODUCTO")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('I5', "CANTIDAD")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('J5', "CANTIDAD ENTREGADA")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('K5', "MOTIVO")->getStyle('O2')->getAlignment()->setWrapText(true);

        $spreadsheet->getActiveSheet()->getStyle('B5:K5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('c00000');
        // Cambiar el color de la letra
        $sheet->getStyle('B5:K5')->getFont()->getColor()->setARGB('FFFFFF');
        // Poner la letra en negrita
        $sheet->getStyle('B5:K5')->getFont()->setBold(true);

        // Poner un filtro en el rango de celdas
        $sheet->setAutoFilter('B5:K5');

        foreach ($query as $KEY => $value) {

            $colorCelda = ($cont % 2 == 0) ? 'd9d9d9' : 'FFFFFF';

            $sheet->setCellValue('B' . $cont, $value->id_request);
            $spreadsheet->getActiveSheet()->getStyle('B' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);
            $sheet->setCellValue('C' . $cont, $value->payroll_number);
            //$colorCelda = ($value->id_user > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('C' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('D' . $cont, $value->delivery_date);
            //$colorCelda = ($value->payroll_number > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('D' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('E' . $cont, $value->NAME);
            //$colorCelda = ($value->NAME > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('E' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('F' . $cont, $value->job_position);
            //$colorCelda = ($value->job_position > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('F' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('G' . $cont, $value->departament);
            //$colorCelda = ($value->departament > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('G' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('H' . $cont, $value->product);
            $spreadsheet->getActiveSheet()->getStyle('H' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);
            //$colorCelda = ($value->product > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('I' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('I' . $cont, $value->quantity);
            //$colorCelda = ($value->quantity > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('J' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('J' . $cont, $value->cant_confirm);
            $spreadsheet->getActiveSheet()->getStyle('K' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);
            $sheet->setCellValue('K' . $cont, $value->option);

            $cont++;
        }

        $sheet->getStyle('A2:O' . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:O' . $cont)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


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

    /* FUNCIONES DEL MODULO DE RESPONSABILIDAD SOCIAL */


    public function listEvents()
    {

        // Solicitudes Voluntariados estatus 1
        $role = 1;

        $data = $this->volunteeringModel->getListVolunteering($role);

        return json_encode($data);
    }

    public function listEventsPermanent()
    {

        // Solicitudes Voluntariados estatus 1
        $role = 2;

        $data = $this->volunteeringModel->getListVolunteering($role);

        return json_encode($data);
    }

    public function createEventANT()
    {


        $id_user = session()->id_user;

        $num_nomina = trim($this->request->getPost('num_nomina'));
        $usuario = trim($this->request->getPost('usuario'));
        $departamento = trim($this->request->getPost('departamento'));
        $puesto = trim($this->request->getPost('puesto'));
        $tel_contacto = trim($this->request->getPost('tel_contacto'));
        $motivo = trim($this->request->getPost('motivo'));
        // Obtén la matriz de actividades directamente
        $actividades = $this->request->getPost('actividad[]');
        $fechas_eventos = $this->request->getPost('fechas_actividad[]');

        $tipo_evento = trim($this->request->getPost('tipo_evento'));

        switch ($tipo_evento) {
            case 'Acciones Verdes':
                $images = '/public/images/insignias/ambiental.svg';
                break;
            case 'Actividades Deportivas':
                $images = '/public/images/insignias/deportiva.svg';
                break;
            case 'Voluntariado':
                $images = '/public/images/insignias/voluntariado.svg';
                break;

            default:
                $images = '/public/images/insignias/voluntariado.svg';
                break;
        }

        // Asegurar que es array
        if (!is_array($actividades)) {
            $actividades = [$actividades];
        }

        $date = date("Y-m-d H:i:s");

        foreach ($actividades as $actividad) {
            $data = [
                'id_user' => $id_user,
                'user_name' => $usuario,
                'tel_user' => $tel_contacto,
                'payroll_number' => $num_nomina,
                'obs_volunteering' => $motivo,
                'activity' => $actividad, // Solo una actividad por registro
                'departament' => $departamento,
                'job_position' => $puesto,
                'created_at' => $date,
                'tipo_evento' => $tipo_evento,
                'assistance' => 'registrado',
                'img_insignia' => $images,
                'type_event' => 1
            ];

            $this->volunteeringModel->insert($data);
        }

        $this->emailNotificationEvent($data, 1);

        return json_encode($data);
    }

    public function createEventORI()
    {

        $fecha_creacion = date("Y-m-d H:i:s");


        try {
            // Verificar sesión primero
            if (!session()->has('id_user')) {
                return $this->response->setJSON(['error' => 'Sesión no válida'])->setStatusCode(401);
            }

            // Validar datos requeridos
            $requiredFields = ['num_nomina', 'usuario', 'departamento', 'puesto', 'tel_contacto', 'motivo', 'tipo_evento'];
            foreach ($requiredFields as $field) {
                if (empty($this->request->getPost($field))) {
                    return $this->response->setJSON(['error' => "El campo $field es requerido"])->setStatusCode(400);
                }
            }

            // Obtener datos básicos
            $data = [
                'id_user' => session()->id_user,
                'user_name' => trim($this->request->getPost('usuario')),
                'tel_user' => trim($this->request->getPost('tel_contacto')),
                'payroll_number' => trim($this->request->getPost('num_nomina')),
                'obs_volunteering' => trim($this->request->getPost('motivo')),
                'departament' => trim($this->request->getPost('departamento')),
                'job_position' => trim($this->request->getPost('puesto')),
                'tipo_evento' => trim($this->request->getPost('tipo_evento')),
                'created_at' => $fecha_creacion,
                'assistance' => 'registrado',
                'type_event' => 1
            ];

            // Manejar imagen según tipo de evento
            switch ($data['tipo_evento']) {
                case 'Acciones Verdes':
                    $data['img_insignia'] = '/public/images/insignias/ambiental.svg';
                    break;
                case 'Actividades Deportivas':
                    $data['img_insignia'] = '/public/images/insignias/deportiva.svg';
                    break;
                default:
                    $data['img_insignia'] = '/public/images/insignias/voluntariado.svg';
                    break;
            }

            // Obtener actividades y fechas
            $actividades = $this->request->getPost('actividad') ?? [];
            $fechas_eventos = $this->request->getPost('fechas_actividad') ?? [];

            // Validar que haya al menos una actividad
            if (empty($actividades)) {
                return $this->response->setJSON(['error' => 'Debe seleccionar al menos una actividad'])->setStatusCode(400);
            }

            // Validar coincidencia de conteo
            if (count($actividades) !== count($fechas_eventos)) {
                return $this->response->setJSON(['error' => 'Datos inconsistentes: el número de actividades no coincide con las fechas'])->setStatusCode(400);
            }

            $savedData = [];
            $savedIds = [];

            // Procesar cada actividad
            foreach ($actividades as $index => $actividad) {
                $eventData = $data;
                $eventData['activity'] = trim($actividad);
                $eventData['event_date'] = $fechas_eventos[$index] ?? date('Y-m-d'); // Fecha actual por defecto

                // Insertar en la base de datos
                $insertId = $this->volunteeringModel->insert($eventData);
                $insert_id = $this->volunteeringModel->insertID();

                if (!$insertId) {
                    log_message('error', 'Error al insertar evento: ' . print_r($eventData, true));
                    continue; // Continuar con las siguientes aunque falle una
                }

                $eventData['id'] = $insertId;
                $savedData[] = $eventData;
                $savedIds[] = $insertId;
            }

            $personas = $this->request->getPost('personas'); // array
            $tallas = $this->request->getPost('tallas'); // array

            if (is_array($personas) && is_array($tallas)) {
                $modelGuests = new hseGuestsModel();

                foreach ($personas as $index => $persona) {
                    $talla = $tallas[$index] ?? 'error';

                    $dataguests = [
                        "id_user" => session()->id_user,
                        "id_event" => 1, // cuidado: solo último evento
                        "nombre_invitado" => trim($persona),
                        "talla_invitado" => trim($talla),
                        "created_at" => $fecha_creacion
                    ];

                    $modelGuests->insert($dataguests);
                }
            }


            // Verificar si se guardó algo
            if (empty($savedIds)) {
                return $this->response->setJSON(['error' => 'No se pudo guardar ninguna actividad'])->setStatusCode(500);
            }

            // Enviar notificación con el primer registro
            $this->emailNotificationEvent($savedData[0], 1);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Eventos registrados correctamente',
                'data' => $savedData,
                'saved_ids' => $savedIds
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en createEvent: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error interno del servidor'])->setStatusCode(500);
        }
    }

    public function createEvent()
    {
        $fecha_creacion = date("Y-m-d H:i:s");

        try {
            // Verificar sesión primero
            if (!session()->has('id_user')) {
                return $this->response->setJSON(['error' => 'Sesión no válida'])->setStatusCode(401);
            }

            // Validar datos requeridos
            $requiredFields = ['num_nomina', 'usuario', 'departamento', 'puesto', 'tel_contacto', 'motivo', 'tipo_evento'];
            foreach ($requiredFields as $field) {
                if (empty($this->request->getPost($field))) {
                    return $this->response->setJSON(['error' => "El campo $field es requerido"])->setStatusCode(400);
                }
            }

            // Obtener datos básicos
            $data = [
                'id_user' => session()->id_user,
                'user_name' => trim($this->request->getPost('usuario')),
                'tel_user' => trim($this->request->getPost('tel_contacto')),
                'payroll_number' => trim($this->request->getPost('num_nomina')),
                'obs_volunteering' => trim($this->request->getPost('motivo')),
                'departament' => trim($this->request->getPost('departamento')),
                'job_position' => trim($this->request->getPost('puesto')),
                'tipo_evento' => trim($this->request->getPost('tipo_evento')),
                'created_at' => $fecha_creacion,
                'assistance' => 'registrado',
                'type_event' => 1
            ];

            // Manejar imagen según tipo de evento
            switch ($data['tipo_evento']) {
                case 'Acciones Verdes':
                    $data['img_insignia'] = '/public/images/insignias/ambiental.svg';
                    break;
                case 'Actividades Deportivas':
                    $data['img_insignia'] = '/public/images/insignias/deportiva.svg';
                    break;
                default:
                    $data['img_insignia'] = '/public/images/insignias/voluntariado.svg';
                    break;
            }

            // Obtener actividades y fechas
            $actividades = $this->request->getPost('actividad') ?? [];
            $fechas_eventos = $this->request->getPost('fechas_actividad') ?? [];

            // Validar que haya al menos una actividad
            if (empty($actividades)) {
                return $this->response->setJSON(['error' => 'Debe seleccionar al menos una actividad'])->setStatusCode(400);
            }

            // Validar coincidencia de conteo
            if (count($actividades) !== count($fechas_eventos)) {
                return $this->response->setJSON(['error' => 'Datos inconsistentes: el número de actividades no coincide con las fechas'])->setStatusCode(400);
            }

            $savedData = [];
            $savedIds = [];

            // Procesar cada actividad
            foreach ($actividades as $index => $actividad) {
                $eventData = $data;
                $eventData['activity'] = trim($actividad);
                $eventData['event_date'] = $fechas_eventos[$index] ?? date('Y-m-d'); // Fecha actual por defecto

                // Insertar en la base de datos
                $insertId = $this->volunteeringModel->insert($eventData);

                if (!$insertId) {
                    log_message('error', 'Error al insertar evento: ' . print_r($eventData, true));
                    continue; // Continuar con las siguientes aunque falle una
                }

                $eventData['id'] = $insertId;
                $savedData[] = $eventData;
                $savedIds[] = $insertId;
            }

            // Verificar si se guardó algo
            if (empty($savedIds)) {
                return $this->response->setJSON(['error' => 'No se pudo guardar ninguna actividad'])->setStatusCode(500);
            }

            // Procesar invitados
            $personas = $this->request->getPost('personas'); // array
            $tallas = $this->request->getPost('tallas'); // array

            if (is_array($personas) && is_array($tallas)) {
                $modelGuests = new hseGuestsModel();

                // Iterar sobre todos los IDs de eventos
                foreach ($savedIds as $eventId) {
                    foreach ($personas as $index => $persona) {
                        $talla = $tallas[$index] ?? 'error';

                        $dataguests = [
                            "id_user" => session()->id_user,
                            "id_event" => $eventId, // Asociar con el ID del evento actual
                            "nombre_invitado" => trim($persona),
                            "talla_invitado" => trim($talla),
                            "created_at" => $fecha_creacion
                        ];

                        $modelGuests->insert($dataguests);
                    }
                }
            }

            // Enviar notificación con el primer registro
            $this->emailNotificationEvent($savedData[0], 1);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Eventos registrados correctamente',
                'data' => $savedData,
                'saved_ids' => $savedIds
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en createEvent: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error interno del servidor'])->setStatusCode(500);
        }
    }


    public function deleteRequest()
    {
        try {
           // $this->db->transStart();

            $id_request = $this->request->getPost('id_folio');
            $data = ["active_status" => 2, "id_user_delete" => session()->id_user, "delete_at" => date("Y-m-d H:i:s")];
            $result = $this->volunteeringModel->update($id_request, $data);

         //   $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    

    public function pdfSeeRequest($id_event = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query(" SELECT a.* 
                                    FROM tbl_hse_volunteering_activity AS a 
                                    WHERE MD5(concat('" . $key . "',id_volunteering))='" . $id_event . "'");
        $dataEvent =  $query->getRow();

        $query1 = $this->db->query(" SELECT a.* 
                                    FROM tbl_hse_guests AS a 
                                    WHERE MD5(concat('" . $key . "',id_event))='" . $id_event . "'");
        $dataExtra =  $query1->getResult();


        $data = [
            "request" => $dataEvent,
            "extra" => $dataExtra,
        ];
        $html2 = view('pdf/eventos_sociales', $data);
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Permisos');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('evento_' . $id_event . '.pdf', 'I');
    }




    public function createEventPermanent()
    {

        $fecha_creacion = date("Y-m-d H:i:s");


        try {
            // Verificar sesión primero
            if (!session()->has('id_user')) {
                return $this->response->setJSON(['error' => 'Sesión no válida'])->setStatusCode(401);
            }

            // Validar datos requeridos
            $requiredFields = ['num_nomina', 'usuario', 'departamento', 'puesto', 'tel_contacto', 'motivo', 'tipo_evento'];
            foreach ($requiredFields as $field) {
                if (empty($this->request->getPost($field))) {
                    return $this->response->setJSON(['error' => "El campo $field es requerido"])->setStatusCode(400);
                }
            }

            // Obtener datos básicos
            $data = [
                'id_user' => session()->id_user,
                'user_name' => trim($this->request->getPost('usuario')),
                'tel_user' => trim($this->request->getPost('tel_contacto')),
                'payroll_number' => trim($this->request->getPost('num_nomina')),
                'obs_volunteering' => trim($this->request->getPost('motivo')),
                'departament' => trim($this->request->getPost('departamento')),
                'job_position' => trim($this->request->getPost('puesto')),
                'tipo_evento' => trim($this->request->getPost('tipo_evento')),
                'created_at' => $fecha_creacion,
                'assistance' => 'registrado',
                'type_event' => 2
            ];

            // Manejar imagen según tipo de evento
            switch ($data['tipo_evento']) {
                case 'Acciones Verdes':
                    $data['img_insignia'] = '/public/images/insignias/ambiental.svg';
                    break;
                case 'Actividades Deportivas':
                    $data['img_insignia'] = '/public/images/insignias/deportiva.svg';
                    break;
                default:
                    $data['img_insignia'] = '/public/images/insignias/voluntariado.svg';
                    break;
            }

            // Obtener actividades y fechas
            $actividades = $this->request->getPost('actividad') ?? [];
            $fechas_eventos = $this->request->getPost('fechas_actividad') ?? [];

            // Validar que haya al menos una actividad
            if (empty($actividades)) {
                return $this->response->setJSON(['error' => 'Debe seleccionar al menos una actividad'])->setStatusCode(400);
            }

            // Validar coincidencia de conteo
            if (count($actividades) !== count($fechas_eventos)) {
                return $this->response->setJSON(['error' => 'Datos inconsistentes: el número de actividades no coincide con las fechas'])->setStatusCode(400);
            }

            $savedData = [];
            $savedIds = [];

            // Procesar cada actividad
            foreach ($actividades as $index => $actividad) {
                $eventData = $data;
                $eventData['activity'] = trim($actividad);
                $eventData['event_date'] = $fechas_eventos[$index] ?? date('Y-m-d'); // Fecha actual por defecto

                // Insertar en la base de datos
                $insertId = $this->volunteeringModel->insert($eventData);
                $insert_id = $this->volunteeringModel->insertID();

                if (!$insertId) {
                    log_message('error', 'Error al insertar evento: ' . print_r($eventData, true));
                    continue; // Continuar con las siguientes aunque falle una
                }

                $eventData['id'] = $insertId;
                $savedData[] = $eventData;
                $savedIds[] = $insertId;
            }

            $personas = $this->request->getPost('personas'); // array
            $tallas = $this->request->getPost('tallas'); // array

            if (is_array($personas) && is_array($tallas)) {
                $modelGuests = new hseGuestsModel();

                foreach ($personas as $index => $persona) {
                    $talla = $tallas[$index] ?? 'error';

                    $dataguests = [
                        "id_user" => session()->id_user,
                        "id_event" => 1, // cuidado: solo último evento
                        "nombre_invitado" => trim($persona),
                        "talla_invitado" => trim($talla),
                        "created_at" => $fecha_creacion
                    ];

                    $modelGuests->insert($dataguests);
                }
            }


            // Verificar si se guardó algo
            if (empty($savedIds)) {
                return $this->response->setJSON(['error' => 'No se pudo guardar ninguna actividad'])->setStatusCode(500);
            }

            // Enviar notificación con el primer registro
            $this->emailNotificationEvent($savedData[0], 2);


            return $this->response->setJSON([
                'success' => true,
                'message' => 'Eventos registrados correctamente',
                'data' => $savedData,
                'saved_ids' => $savedIds
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en createEvent: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error interno del servidor'])->setStatusCode(500);
        }
    }

    public function emailNotificationEvent($data, $type)
    {
        $data = ["data" => $data];

        if ($type == 1) {
            $recibe = 'Solicitud por Eventos';
            $titulo = 'Notificación por evento';
            $email_template = view('notificaciones/hse_eventos', $data);
        } else if ($type == 2) {
            $recibe = 'Solicitud Permanente';
            $titulo = 'Notificación de evento permanente';
            $email_template = view('notificaciones/hse_eventos_permanentes', $data);
        }

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
            // $mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com', $recibe);
            // Add a recipient
            $mail->addAddress('ldominguez@walworth.com.mx', 'Luis Angel Dominguez');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Luis Angel Dominguez');
            // Name is optional
            // Add a recipient

            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');

            $mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);

            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $titulo;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function pdfEventVoluntering($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_hse_volunteering_activity 
                                        WHERE
                                        MD5(concat('" . $key . "',id_volunteering))='" . $id_request . "'");
        $dataRequest =  $query->getRow();


        $data = ["request" => $dataRequest];

        $html2 = view('pdf/qhse_list_volunteering', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Solicitud');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('solicitud_' . $id_request . '.pdf', 'I');
    }

    public function daysRecord()
    {

        $query = $this->db->query("SELECT id_time,days,record
                                    FROM tbl_hse_time_accident_record
                                    WHERE active_status = 1")->getResult();

        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function saveDaysRecord()
    {


        $counterId = $this->request->getPost('counterId');
        $value = $this->request->getPost('value');

        $sub = ($counterId == "dias-counter") ? 'days = ?' : 'record = ?';

        $query = $this->db->query("UPDATE tbl_hse_time_accident_record
                                    SET $sub
                                    WHERE id_time = ? 
                                ", [$value, 1]);


        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function resetDaysRecord()
    {


        $query = $this->db->query("UPDATE tbl_hse_time_accident_record
                                    SET days = ?
                                    WHERE id_time = ? 
                                ", [0, 1]);


        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function increaseDays()
    {

        $query = $this->db->query("UPDATE tbl_hse_time_accident_record
                                    SET days = days + 1
                                    WHERE id_time = ? 
                                ", [1]);

        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function listMenus()
    {

        $query = $this->db->query("SELECT * 
                                   FROM tbl_hse_menus
                                   WHERE  active_status = 1 ")->getResultArray();


        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function listMenusPermanent()
    {

        $query = $this->db->query("SELECT * 
                                   FROM tbl_hse_menus
                                   WHERE type_menu= 2 AND active_status = 1 ")->getResultArray();


        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function viewListMenu()
    {
        $type_menu = $this->request->getPost('id_menu');

        $query = $this->db->query("SELECT * 
                                   FROM tbl_hse_menus
                                   WHERE type_menu = $type_menu AND active_menu = 1 AND active_status = 1 ")->getResultArray();


        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function updateMenus()
    {
        try {

            $idSocial = $this->request->getPost("id");
            $status = $this->request->getPost("status");

            $updateMenus = $this->db->query("UPDATE tbl_hse_menus
            SET active_menu = ?
            WHERE id_social = ? 
        ", [$status, $idSocial]);
            return ($updateMenus) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function deleteMenu()
    {
        try {
            $idSocial = $this->request->getPost("folio");

            $updateMenus = $this->db->query("UPDATE tbl_hse_menus
            SET active_status = ?
            WHERE id_social = ? 
        ", [2, $idSocial]);
            return ($updateMenus) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function addMenu()
    {

        try {
            $nombre_menu = mb_strtoupper($this->request->getPost("nombre_menu"));
            $tipo_menu = $this->request->getPost("tipo_menu");
            $fecha_evento = $this->request->getPost("fecha_evento");
            $dateToDay = date("Y-m-d H:i:s");
            $id_user = session()->id_user;

            //echo "fecha: ". $nombre_menu;

            // Crear instancia del modelo
            $menusModel = new hseMenusSocialModel();

            // Datos a insertar
            $data = [
                'id_user' => $id_user,
                'menus' => $nombre_menu,
                'type_menu' => $tipo_menu,
                'event_date' => $fecha_evento,
                'active_menu' => 2,
                'created_datetime' => $dateToDay

            ];

            return ($menusModel->insert($data)) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }


    public function  barcodeCentroCosto()
    {

        $query = $this->db->query("SELECT * 
        FROM cat_cost_center
        WHERE  active_status = 1 ")->getResultArray();


        foreach ($query as $key => $value) {

            $cost_center = $value["clave_cost_center"];

            if (!is_dir('../public/uploads/qrcodes/centro_costo_' . $cost_center)) {
                mkdir('../public/uploads/qrcodes/centro_costo_' . $cost_center, 0777, true);
            }


            $generator = new BarcodeGeneratorPNG();

            $costEQPS = "EQPS" . $cost_center;
            $costUNIF = "UNIF" . $cost_center;
            $barcode_eqps = $generator->getBarcode($costEQPS, $generator::TYPE_CODE_128);
            $barcode_unif = $generator->getBarcode($costUNIF, $generator::TYPE_CODE_128);

            $costEQPSFilePath = '../public/uploads/qrcodes/centro_costo_' . $cost_center . '/eqps_' . $cost_center . '.png';
            $costUNIFFilePath = '../public/uploads/qrcodes/centro_costo_' . $cost_center . '/unif_' . $cost_center . '.png';


            file_put_contents($costEQPSFilePath, $barcode_eqps);
            file_put_contents($costUNIFFilePath, $barcode_unif);

            $updateCenter = $this->db->query("UPDATE cat_cost_center
            SET barcode_eqps = ? , barcode_unif = ?
            WHERE clave_cost_center = ? 
        ", [$costEQPSFilePath, $costUNIFFilePath, $cost_center]);
        }


        echo ($updateCenter) ? json_encode(true) : json_encode(false);
    }




    public function viewMyBadges()
    {

        $badges = [];
        $id_user = session()->id_user;

        $insignias = $this->db->query("SELECT id_volunteering,assistance,active_status,created_at,type_event,activity,tipo_evento,img_insignia,created_at,event_date
                                       FROM tbl_hse_volunteering_activity
                                       WHERE id_user = $id_user  
                                       AND active_status = 1 ")->getResultArray();


        foreach ($insignias as $key => $value) {

            if ($value["assistance"] == 'asistio') {

                switch ($value["tipo_evento"]) {
                    case 'Acciones Verdes':
                        $color = 'success';
                        break;
                    case 'Actividades Deportivas':
                        $color = 'primary';
                        break;
                    case 'Voluntariado':
                        $color = 'danger';
                        break;
                    default:
                        $color = 'secondary';
                        break;
                }

                $fecha_formateada = date('d-M-Y', strtotime($value["event_date"]));

                $badges[] =  [
                    'id' => $value["id_volunteering"],
                    'evento' => $value["activity"],
                    'imagen' => $value["img_insignia"],
                    'categoria' => $value["tipo_evento"],
                    'color' => $color,
                    'fecha' => $fecha_formateada
                ];
            }
        }

        return ($this->is_logged) ?  view('qhse/view_my_badges', ['badges' => $badges]) : redirect()->to(site_url());
    }


    public function  validateRequests()
    {

        $folios = $this->request->getPost("folios");

        if (!$folios || !is_array($folios)) {
            return $this->response->setJSON(['error' => 'Datos inválidos']);
        }


        // Actualizar estatus para cada folio
        foreach ($folios as $id) {
            $this->db->table('tbl_hse_volunteering_activity')
                ->where('id_volunteering', $id)
                ->update(['assistance' => 'asistio']); // Cambia el valor según tu lógica
        }

        return $this->response->setJSON(['success' => true, 'mensaje' => 'Folios actualizados correctamente.']);
    }

    public function  validateRequest()
    {

        $folio= $this->request->getPost("id_folio");

        if (!$folio) {
            return $this->response->setJSON(['error' => 'Datos inválidos']);
        }


    
            $this->db->table('tbl_hse_volunteering_activity')
                ->where('id_volunteering', $folio)
                ->update(['assistance' => 'asistio']); // Cambia el valor según tu lógica
        

        return $this->response->setJSON(['success' => true, 'mensaje' => 'Folios actualizados correctamente.']);
    }
}
