<?php

/**
 * MODULO DE CAFETERIA
 * @version 1.1 pre-prod
 * @author Rafael Cruz Aguilar <rafael.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 * Archivo Generador de Repore
 */

namespace App\Controllers\Coffee;

use App\Controllers\BaseController;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\RoomModel;
use App\Models\ReasonMeetingModel;
use App\Models\CoffeeBreakModel;
use App\Models\ItemCoffeeModel;
use App\Models\CoffeeMenusModel;
use App\Models\CoffeeFoodsModel;


class Coffee extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->roomModel = new RoomModel();
        $this->reasonModel = new ReasonMeetingModel();
        $this->coffeeModel = new CoffeeBreakModel();
        $this->itemCoffeeModel = new ItemCoffeeModel();
        $this->coffeeMenuModel = new CoffeeMenusModel();
        $this->coffeeFoodModel = new CoffeeFoodsModel();

        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }
    public function viewGenerate()
    {
        $meeting_room  = $this->roomModel->where('active_status', 1)->findAll();
        $reason  = $this->reasonModel->where('active_status', 1)->findAll();
        $data = [
            "meeting" => $meeting_room,
            "reason" => $reason
        ];
        return ($this->is_logged) ? view('coffee/view_coffee_create', $data) : redirect()->to(site_url());
    }

    public function viewMyRequest()
    {
        return ($this->is_logged) ? view('coffee/view_coffee_mylist') : redirect()->to(site_url());
    }

    public function viewAuthorize()
    {
        return ($this->is_logged) ? view('coffee/view_coffee_authorize') : redirect()->to(site_url());
    }
    public function viewAuthorizeJames()
    {
        return ($this->is_logged) ? view('coffee/view_coffee_authorize_talent') : redirect()->to(site_url());
    }

    public function viewManageMenu()
    {
        return ($this->is_logged) ? view('coffee/view_coffee_menus_admin') : redirect()->to(site_url());
    }

    public function insertItem()
    {
        try {

            $meeting_room = trim($this->request->getPost('sala_coffee'));
            $reason_coffee = trim($this->request->getPost('motivo_coffee'));
            $fecha_coffee = trim($this->request->getPost('fecha_coffee'));
            $horario_coffee = trim($this->request->getPost('horario_coffee'));
            $no_personas = trim($this->request->getPost('no_personas'));
            $coffee_observaciones = trim($this->request->getPost('coffee_observaciones'));
            $menu_especial = trim($this->request->getPost('menu_especial'));
            $date = date("Y-m-d H:i:s");
            $user = session()->name . " " . session()->surname;


            if ($meeting_room == 4) {
                $data_coffee = [
                    "id_user" => session()->id_user,
                    "payroll_number" => session()->payroll_number,
                    "name" => $user,
                    "depto" => session()->departament,
                    'area_operativa' => session()->cost_center,
                    "position_job" => session()->job_position,
                    "meeting_room" => $meeting_room,
                    "reason_meeting" => $reason_coffee,
                    "date" => $fecha_coffee,
                    "horario" => $horario_coffee,
                    "num_person" => $no_personas,
                    "observations" => $coffee_observaciones,
                    "menu_especial" => $menu_especial,
                    "status" => 5,
                    "created_at" => $date
                ];
            } else {
                $data_coffee = [
                    "id_user" => session()->id_user,
                    "payroll_number" => session()->payroll_number,
                    "name" => $user,
                    "depto" => session()->departament,
                    'area_operativa' => session()->cost_center,
                    "position_job" => session()->job_position,
                    "meeting_room" => $meeting_room,
                    "reason_meeting" => $reason_coffee,
                    "date" => $fecha_coffee,
                    "horario" => $horario_coffee,
                    "num_person" => $no_personas,
                    "observations" => $coffee_observaciones,
                    "menu_especial" => $menu_especial,
                    "created_at" => $date
                ];
            }



            $insertData =  $this->coffeeModel->insert($data_coffee);

            $id_coffee = $this->db->insertID();

            if ($insertData) {

                $menu = json_decode(json_encode($this->request->getPost('menu_coffee')));

                $builder =  $this->db->table('tbl_coffee_items');

                for ($i = 0; $i < count($menu); $i++) {

                    $dataItem = [
                        'id_coffee' => $id_coffee,
                        'product' => $menu[$i]
                    ];
                    $builder->insert($dataItem);
                }
                if ($menu_especial != "") {

                    $query1 = $this->coffeeFoodModel->select('description')
                        ->where('special_menu', $menu_especial)->where('deleted_at', null);
                    $foods = $query1->get()->getResult();
                    $builder2 =  $this->db->table('tbl_coffee_menu_requisiton');

                    foreach ($foods as $key => $value) {
                        $foodRequisition = [
                            'id_coffee' => $id_coffee,
                            'description' => $value->description
                        ];
                        $builder2->insert($foodRequisition);
                    }
                }
            }

            if ($meeting_room == 4) {
                $title = "Karen Rubio";
                $mail = "krubio@grupowalworth.com";
            } else {
                $title = "Gerardo Mendoza";
                $mail = "gmendoza@walworth.com.mx";
            }

            $this->emailnotification($mail, $title, $id_coffee);

            if ($menu_especial != 0) {
                $title2 = "Gerardo Mendoza";
                $mail = "gmendoza@walworth.com.mx";
                //  $mail = "hrivas@walworth.com.mx";

                $this->emailnotificationMenu($mail, $title2, $id_coffee);
            }
            return ($insertData) ? json_encode(true) : json_encode('error');
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function myRequest()
    {
        try {
            $builder = $this->db->table('tbl_coffee_break');
            $builder->select('*');
            $builder->where('id_user', session()->id_user);
            $builder->where('active_status', 1);
            $builder->where('status !=', 4);
            $builder->orderBy('id_coffee', 'DESC');
            $builder->limit(1500);
            $data = $builder->get()->getResult();

            return (count($data) > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function cancel()
    {
        $cancel_at = date('Y-m-d');
        $id_coffee = trim($this->request->getPost('folio'));
        $reason_cancel = trim($this->request->getPost('razon'));

        $updateCancel = [
            'status' => 4,
            'reason_cancel' => $reason_cancel,
            'cancel_at' => $cancel_at
        ];

        $update = $this->coffeeModel->update($id_coffee, $updateCancel);

        $query = $this->db->table('tbl_coffee_break')->select('menu_especial')->where('id_coffee', $id_coffee)->get()->getRow();
        $title = "Gerardo Mendoza";
        $email = "gmendoza@walworth.com.mx";
            /*  $email = "bcuevas@walworth.com.mx";
        $title = "Adriana Cuevas" */;
        // $email = "hrivas@walworth.com.mx";
        $this->emailnotification($email, $title, $id_coffee);

        if ($query->menu_especial != 0) {
            $title2 = "Gerardo Mendoza";
            $mail = "gmendoza@walworth.com.mx";
            // $mail = "hrivas@walworth.com.mx";

            $this->emailnotification($mail, $title2, $id_coffee);
        }
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function requestAll()
    {
        try {
            $builder = $this->db->table('tbl_coffee_break');
            $builder->select('*');
            $builder->where('active_status', 1);
            $builder->where('status !=', 5);
            $builder->orderBy('id_coffee', 'DESC');
            $builder->limit(1100);
            $data = $builder->get()->getResult();

            return (count($data) > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function requestJames()
    {
        try {
            $builder = $this->db->table('tbl_coffee_break');
            $builder->select('*');
            $builder->where('active_status', 1);
            $builder->where('meeting_room', 4);

            $builder->orderBy('id_coffee', 'DESC');
            $builder->limit(1100);
            $data = $builder->get()->getResult();

            return (count($data) > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function requestAuthorize()
    {
        $id_folio = trim($this->request->getPost('id_folio'));
        $status = trim($this->request->getPost('estado'));


        $date = date("Y-m-d H:i:s");
        $dataUpdate = [
            "status" => $status,
            "date_authorize" => $date,
            "id_authorize" => session()->id_user,
        ];

        $res = $this->coffeeModel->update($id_folio, $dataUpdate);
        if ($status == 6) {
            /*     $email = "bcuevas@walworth.com.mx";
                $title = "Adriana Cuevas"; */
            $title = "Gerardo Mendoza";
            $email = "gmendoza@walworth.com.mx";
            $this->emailNotification($email, $title, $id_folio);
        } else {
            $dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user IN 
                (SELECT id_user FROM tbl_coffee_break WHERE id_coffee = $id_folio)")->getRow();
            $email = $dataEmail->email;
            $title = $dataEmail->name . " " . $dataEmail->surname;
            $this->emailNotification($email, $title, $id_folio);
        }
        return ($res == true) ? $res : false;
    }

    public function pdfRequestCoffee($id_coffee = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_coffee_break
                                        WHERE
                                        MD5(concat('" . $key . "',id_coffee))='" . $id_coffee . "'");
        $dataCoffee =  $query->getRow();

        $query2 = $this->db->query("SELECT *
                                        FROM
                                        tbl_coffee_items
                                        WHERE
                                        MD5(concat('" . $key . "',id_coffee))='" . $id_coffee . "'");
        $dataItems =  $query2->getResult();

        $query3 = $this->db->query("SELECT *
                                    FROM
                                    tbl_coffee_menus
                                    WHERE
                                    special_menu = $dataCoffee->menu_especial");
        $dataMenu =  $query3->getRow();

        $query4 = $this->db->query(
            "SELECT *
                                    FROM
                                    tbl_coffee_menu_requisiton
                                    WHERE
                                    id_coffee = $dataCoffee->id_coffee"
        );
        $dataFood =  $query4->getResult();

        $data = [
            "request" => $dataCoffee,
            "items" => $dataItems,
            "menu" => $dataMenu,
            "comida" => $dataFood
        ];
        $html2 = view('pdf/pdf_coffee', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Solicitudes');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('solicitudes_' . $id_coffee . '.pdf', 'I');
    }

    public function emailNotification($email = null, $user = null, $id = null)
    {

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $email = changeEmail($email);

        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_coffee_break
                                        WHERE
                                        id_coffee = $id");
        $dataCoffee =  $query->getRow();

        $query3 = $this->db->query("SELECT *
                                    FROM
                                    tbl_coffee_menus
                                    WHERE
                                    special_menu = $dataCoffee->menu_especial");
        $dataMenu =  $query3->getRow();

        $query4 = $this->db->query(
            "SELECT *
            FROM
            tbl_coffee_menu_requisiton
            WHERE
            id_coffee = $id"
        );
        $dataFood =  $query4->getResult();
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
            // $mail->Username = 'requisiciones@walworth.com.mx';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;

            //Recipients
            if ($dataCoffee->status == 4 || $dataCoffee->status == 7) {
                $mail->setFrom('notificacion@walworth.com', 'Solicitud | Cafetería | Cancelada');
            } else {
                $mail->setFrom('notificacion@walworth.com', 'Solicitud | Cafetería');
            }

            // Add a recipient
            $mail->addAddress($email, $user);
            // Add more recipients if needed
            if ($email == 'gmendoza@walworth.com.mx') {
                $mail->addCC('eshernandez@walworth.com.mx', 'Esther Hernandez');
            }

            // Name is optional
            //$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
           // $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $data = ['datas' =>  $dataCoffee, 'menu' => $dataMenu, 'comida' => $dataFood];
            $mail->isHTML(true);
            $email_template = view('notificaciones/coffee', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Solicitud de Cafetería';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }


    public function emailNotificationMenu($email = null, $user = null, $id = null)
    {
        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $email = changeEmail($email);

        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_coffee_break
                                        WHERE
                                        id_coffee = $id");
        $dataCoffee =  $query->getRow();

        $query3 = $this->db->query("SELECT *
                                    FROM
                                    tbl_coffee_menus
                                    WHERE
                                    special_menu = $dataCoffee->menu_especial");
        $dataMenu =  $query3->getRow();

        $query4 = $this->db->query(
            "SELECT *
            FROM
            tbl_coffee_menu_requisiton
            WHERE
            id_coffee = $id"
        );
        $dataFood =  $query4->getResult();
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
            //$mail->Username = 'requisiciones@walworth.com.mx';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;

            //Recipients
            if ($dataCoffee->status == 4) {
                $mail->setFrom('notificacion@walworth.com', 'Solicitud | Cafetería | Cancelar');
            } else {
                $mail->setFrom('notificacion@walworth.com', 'Solicitud | Cafetería');
            }

            // Add a recipient
            $mail->addAddress($email, $user);

            if ($email == 'gmendoza@walworth.com.mx') {
                $mail->addCC('eshernandez@walworth.com.mx', 'Esther Hernandez');
            }
            // Name is optional
            //$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            //$mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $data = ['datas' =>  $dataCoffee, 'menu' => $dataMenu, 'comida' => $dataFood];
            $mail->isHTML(true);
            $email_template = view('notificaciones/coffee', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Solicitud de Cafetería';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function createNewMenu()
    {
        $date = date("Y-m-d H:i:s");
        $binder =  '../public/images/menus';
        $tittle = trim($this->request->getPost('titulo'));



        if ($imageFile = $this->request->getFile('imagen')) {
            $originalName = $imageFile->getClientName();
            $ext = $imageFile->getClientExtension();
            $type = $imageFile->getClientMimeType();
            $newName = $imageFile->getRandomName();
            $imageFile = $imageFile->move($binder,  $originalName);
            $path = $binder . "/" . $originalName;
        } else {
            $path = "NA";
        }
        $dataInsertMenu = [
            "tittle_menu" => $tittle,
            "imagen_menu" => $path,
            "created_at" => $date,
            "active" => 1
        ];
        $insertMenu = $this->coffeeMenuModel->insert($dataInsertMenu);
        $special_menu = $this->db->insertID(); // usar la misma id
        if ($insertMenu) { // validar si se insertaron datos tbl menus
            $food_dish = $this->request->getPost('comida');

            foreach ($food_dish as $key => $value) {

                $dataInsertFood = [
                    'special_menu' => $special_menu,
                    'description' => $value,
                    'created_at' => $date
                ];
                $insertItem = $this->coffeeFoodModel->insert($dataInsertFood);
            }
        }
        return ($insertItem) ? json_encode(true) : json_encode(false);
    }

    public function MenusAll()
    {
        $builder = $this->db->table('tbl_coffee_menus');
        $builder->select('special_menu, tittle_menu, imagen_menu');
        $builder->where('active', 1);
        $data = $builder->get()->getResult();

        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }

    public function DeleteMenu()
    {
        $date = date("Y-m-d H:i:s");
        $id = trim($this->request->getPost('id'));

        $borrarM = ["deleted_at" => $date,  "active" => 2];
        $deleteMenu = $this->coffeeMenuModel->update($id, $borrarM);

        $borrarF = ["deleted_at" => $date];
        $deleteFood = $this->coffeeFoodModel->update($id, $borrarF);

        return ($deleteMenu && $deleteFood) ? json_encode(true) : json_encode(false);
    }

    public function dateEditMenuALL()
    {
        $id = trim($this->request->getPost('id'));
        $builder = $this->db->table('tbl_coffee_menus a')
            ->select('a.special_menu, a.tittle_menu, a.imagen_menu, b.id_food, b.description')
            ->join('tbl_coffee_menus_food b', 'b.special_menu = a.special_menu')
            ->where('a.special_menu', $id)
            ->where('b.deleted_at', NULL);

        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }

    public function editMenu()
    {
        $date = date("Y-m-d H:i:s");
        $special_menu = $this->request->getPost('folio_edit');
        /*--------por si qiuere actualizar imagen Y/o TITULO--------- 
        $binder =  '../public/images/menus'; 
        $tittle = trim($this->request->getPost('titulo_edit'));

        if ($imageFile = $this->request->getFile('imagen')) {
            $originalName = $imageFile->getClientName();
            $ext = $imageFile->getClientExtension();
            $type = $imageFile->getClientMimeType();
            $newName = $imageFile->getRandomName();
            $imageFile = $imageFile->move($binder,  $originalName);
            $path = $binder . "/" . $originalName;
        } else {
            $path = "NA";
        } 
        $dataEditMenu = [
            "tittle_menu" => $tittle,
             "imagen_menu" => $path, 
            "edit_at" => $date,
            "active" => 1
        ];
        $editMenu = $this->coffeeMenuModel->update($special_menu, $dataEditMenu);
 
        if ($editMenu) {  validar si se insertaron datos tbl menus
            */
        $food_id =  $this->request->getPost('food_edit');
        $food_dish = $this->request->getPost('comida_edit');
        foreach (array_combine($food_dish, $food_id) as $value => $idValue) {

            if ($idValue == "") {
                $dataEditFood = [
                    'special_menu' => $special_menu,
                    'description' => $value,
                    'ceatedt_at' => $date
                ];
                $editItem = $this->coffeeFoodModel->insert($dataEditFood);
            } else {
                $dataEditFood = [
                    'description' => $value,
                    'edit_at' => $date
                ];

                $editItem = $this->coffeeFoodModel->update($idValue, $dataEditFood);
            }
        }

        return ($editItem) ? json_encode(true) : json_encode(false);
    }
    public function printMenu()
    {

        $builder = $this->db->table('tbl_coffee_menus')
            ->select('special_menu,tittle_menu,imagen_menu')
            ->where('active', 1);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }
    public function printFood()
    {
        $special_menu = trim($this->request->getPost('special_menu'));

        $builder = $this->db->table('tbl_coffee_menus_food')
            ->select('id_food,description,special_menu')
            ->where('special_menu', $special_menu)
            ->where('deleted_at', NULL);

        $data = $builder->get()->getResult();

        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }

    public function editFood()
    {
        try {
            $date = date("Y-m-d H:i:s");
            $id_food = $this->request->getPost('id_comida');
            for ($i = 0; $i < count($id_food); $i++) {
                $datafoods = ['deleted_at' => $date];
                $deletItem = $this->coffeeFoodModel->update($id_food[$i], $datafoods);
            }

            return ($deletItem) ? json_encode(true) : json_encode("error");
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
