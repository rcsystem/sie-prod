<?php

/**
 * ARCHIVO MODULO ADMINISTRACION
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

namespace App\Controllers\Admin;

use DateTime;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CatVacationsModel;
use App\Models\ContractsTempModel;
use App\Models\VhNewsModel;
use App\Models\Admin_Model;
use App\Models\MedicalMedicineInvetoryModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use CodeIgniter\I18n\Time;


class Admin extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->Admin_Model = new Admin_Model();
        $this->vhNewsModel = new VhNewsModel();
        $this->vacationModel = new CatVacationsModel();
        $this->contractsTempModel = new ContractsTempModel();
        $this->medicaments = new MedicalMedicineInvetoryModel();
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        return view('login/index');
    }

    public function fac()
    {   
        return view('admin/view_fac');
    }

    public function CalcularVacaciones()
    {

        $builder = $this->db->table('tbl_users');
        $builder->select('id_user, date_admission, years_worked, vacation_days_total');
        $builder->where('active_status', 1);
        //$builder->where('id_user', 592);
        $fechas_ingresos = $builder->get()->getResult();

        $builder = $this->db->table('cat_vacation_days');
        $builder->select('years_in_days,years,days_new');
        $builder->where('active_status', 1);
        $data_days = $builder->get()->getResult();

        foreach ($fechas_ingresos as $key => $value) {
            $date1 = new Time($value->date_admission);
            $fecha_actual = new Time("now");
            $id_users = $value->id_user;
            $dias_laborados = $date1->diff($fecha_actual)->days;
            $anios_laborados = floor($dias_laborados / 365);

            if ($anios_laborados > $value->years_worked) {
                $total_anios = $anios_laborados;
                foreach ($data_days as $key => $value) {
                    $anios = $value->years;
                    if ($anios_laborados == $anios) {

                        $builder = $this->db->table('tbl_users');
                        $builder->set('years_worked', $total_anios, false);
                        $builder->where('id_user', $id_users);
                        $builder->update();

                        $builder = $this->db->table('tbl_users');
                        $builder->set('vacation_days_total', 'vacation_days_total +' . $value->days_new, false);
                        $builder->where('id_user', $id_users);
                        $builder->update();
                    }
                }
            }
        }
    }

    public function notifyContractTemp() {}

    public function notifyContractTempBueno()
    {
        try {
            // AND a.date_expiration BETWEEN CURDATE() AND '2023-10-22'
            $query = $this->db->query("SELECT a.id_contract 
            FROM tbl_user_type_of_contract AS a
            WHERE a.date_notification = CURDATE() -- a.date_expiration BETWEEN CURDATE() AND '2023-10-22'
            AND a.date_expiration IN (
                    SELECT MAX(wt1.date_expiration)
                    FROM tbl_user_type_of_contract AS wt1
                    WHERE wt1.id_contract = a.id_contract
                    AND wt1.type_of_contract IN (2,3,4)
                    GROUP BY wt1.id_user)
                AND a.active_status = 1 
                AND a.type_of_contract IN (2,3,4)                
                AND (
                    SELECT COUNT(*)
                    FROM tbl_user_type_of_contract AS wt2
                    WHERE wt2.id_user = a.id_user
                    AND wt2.`option` IN (1, 3)
                ) = 0
            ORDER BY a.date_expiration DESC")->getResult();
            if ($query) {
                if (1 == 2) {
                    /* $weekDay = date('w', strtotime($dateToDay));
                // $dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
                $diasMas = ['+ 1 days', '0', '0', '0', '0', '0', '+ 2 days'];
                if ($weekDay == 0 || $weekDay == 6) {
                    # dia domingo -> + 1 || # dia sabado -> + 2
                    $newDate = date("Y-m-d", strtotime($dateToDay . $diasMas[$weekDay]));
                    $upDate = ['date_notification' => $newDate,];

                    for ($i = 0; $i < count($query); $i++) {
                        $this->contractsTempModel->update($query[$i]->id_contract, $upDate);
                    }

                    return false; */
                } else {
                    // $query0 = $this->db->query("CALL managerForNotifyContractTemp('$dateToDay')")->getResult();
                    $query0 = $this->db->query("SELECT DISTINCT a.id_manager, b.email AS m_email,
                        CONCAT( b.`name`, ' ', b.surname, ' ', b.second_surname ) AS nombre
                        FROM tbl_users_temporary AS a
                            JOIN tbl_users AS b ON a.id_manager = b.id_user
                        WHERE a.id_user IN (SELECT st1.id_user
                            FROM tbl_user_type_of_contract AS st1
                            WHERE st1.date_notification = CURDATE() -- st1.date_expiration BETWEEN CURDATE() AND '2023-10-22'
                                AND st1.date_expiration IN (
                                    SELECT MAX(wt1.date_expiration)
                                    FROM tbl_user_type_of_contract AS wt1
                                    WHERE wt1.id_contract = st1.id_contract
                                    AND wt1.type_of_contract IN (2,3,4)
                                    GROUP BY wt1.id_user)
                                AND st1.active_status = 1 
                                AND st1.type_of_contract IN (2,3,4)                
                                AND (
                                    SELECT COUNT(*)
                                    FROM tbl_user_type_of_contract AS wt2
                                    WHERE wt2.id_user = st1.id_user
                                    AND wt2.`option` IN (1, 3)
                                ) = 0)
                    AND a.active_status = 1")->getResult();
                    for ($iM = 0; $iM < count($query0); $iM++) {
                        $email = $query0[$iM]->m_email;
                        $toUser = $query0[$iM]->nombre; // . " MANGER DE USUARIOS";
                        $id_manager = $query0[$iM]->id_manager;
                        // $queryData = $this->db->query("CALL notifyContractTempData ('$dateToDay',$id_manager)")->getResult();
                        $queryData = $this->db->query("SELECT a.id_contract, 
                            CASE 
                            WHEN a.type_of_contract = 2 THEN '30 DÍAS'
                            WHEN a.type_of_contract = 3 THEN '60 DÍAS'
                            WHEN a.type_of_contract = 4 THEN '90 DÍAS'
                            ELSE 'ERROR' 
                            END AS type_of_contract,
                            DATE_FORMAT(a.date_expiration,'%d/%m/%Y') AS date_expiration,
                            b.`name`,b.surname,b.second_surname,c.job,d.departament
                            FROM tbl_user_type_of_contract AS a
                                LEFT JOIN tbl_users AS b ON  a.id_user = b.id_user
                                LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
                                LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
                            WHERE a.date_notification = CURDATE() -- a.date_expiration BETWEEN CURDATE() AND '2023-10-22'
                                AND a.date_expiration IN (
                                    SELECT MAX(wt1.date_expiration)
                                    FROM tbl_user_type_of_contract AS wt1
                                    WHERE wt1.id_contract = a.id_contract
                                    AND wt1.type_of_contract IN (2,3,4)
                                    GROUP BY wt1.id_user)
                                AND a.active_status = 1 
                                AND a.type_of_contract IN (2,3,4)                
                                AND (
                                    SELECT COUNT(*)
                                    FROM tbl_user_type_of_contract AS wt2
                                    WHERE wt2.id_user = a.id_user
                                    AND wt2.`option` IN (1, 3)
                                ) = 0
                            AND a.id_manager = $id_manager
                        ORDER BY a.date_expiration ASC")->getResult();
                        $this->emailNotifyContractTemp($email, $toUser, $queryData, 1);
                    }

                    // $query1 = $this->db->query("CALL notifyTypeContractTempData ('$dateToDay',1,1)")->getResult();
                    /* $query1 = $this->db->query("SELECT a.id_contract, 
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
                        END AS type_employe,
                        a.date_expiration, b.`name`,b.surname,b.second_surname,c.job,d.departament, e.`name` AS name_m,e.surname AS surname_m, e.second_surname AS second_surname_m
                        FROM tbl_user_type_of_contract AS a
                        LEFT JOIN tbl_users AS b ON  a.id_user = b.id_user
                        LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
                        LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
                        JOIN tbl_users AS e ON a.id_manager = e.id_user
                        WHERE a.date_notification = '$dateToDay' AND a.active_status = 1
                        AND (a.type_of_contract = 2 OR a.type_of_contract = 3 OR a.type_of_contract = 4)
                        AND b.type_of_employee = 1 AND b.company = 1")->getResult();
                if ($query1) {
                    $emailN = "elgarcia@walworth.com.mx";
                    $toUserN = "ELIZABETH GARCIA REAL JOYA";
                    $this->emailNotifyContractTemp($emailN, $toUserN, $query1, 2);
                } */

                    // $query2 = $this->db->query("CALL notifyTypeContractTempData ('$dateToDay',2,1)")->getResult();
                    $query2 = $this->db->query("SELECT a.id_contract, 
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
                        END AS type_employe,
                        DATE_FORMAT(a.date_expiration,'%d/%m/%Y') AS date_expiration,
                        b.`name`,b.surname,b.second_surname,c.job,d.departament, e.`name` AS name_m,e.surname AS surname_m, e.second_surname AS second_surname_m
                        FROM tbl_user_type_of_contract AS a
                            LEFT JOIN tbl_users AS b ON  a.id_user = b.id_user
                            LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
                            LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
                            JOIN tbl_users AS e ON a.id_manager = e.id_user
                        WHERE a.date_notification = CURDATE()  -- a.date_expiration BETWEEN CURDATE() AND '2023-10-22'
                            AND a.date_expiration IN (
                                SELECT MAX(wt1.date_expiration)
                                FROM tbl_user_type_of_contract AS wt1
                                WHERE wt1.id_contract = a.id_contract
                                AND wt1.type_of_contract IN (2,3,4)
                                GROUP BY wt1.id_user)
                            AND a.active_status = 1 
                            AND a.type_of_contract IN (2,3,4)                
                            AND (
                                SELECT COUNT(*)
                                FROM tbl_user_type_of_contract AS wt2
                                WHERE wt2.id_user = a.id_user
                                AND wt2.`option` IN (1, 3)
                            ) = 0
                        AND (b.type_of_employee IN (1,2) AND b.company IN (1,3,4) )
                    ORDER BY a.date_expiration ASC")->getResult();

                    if ($query2) {
                        $emailN = "eolanda@walworth.com.mx";
                        $toUserN = "ELDA OLANDA SALAZAR";
                        $this->emailNotifyContractTemp($emailN, $toUserN, $query2, 2);
                    }

                    // $query3 = $this->db->query("CALL notifyTypeContractTempData ('$dateToDay',2,2)")->getResult();
                    $query3 = $this->db->query("SELECT a.id_contract, 
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
                        END AS type_employe,
                        DATE_FORMAT(a.date_expiration,'%d/%m/%Y') AS date_expiration,
                        b.`name`,b.surname,b.second_surname,c.job,d.departament, e.`name` AS name_m,e.surname AS surname_m, e.second_surname AS second_surname_m
                        FROM tbl_user_type_of_contract AS a
                            LEFT JOIN tbl_users AS b ON  a.id_user = b.id_user
                            LEFT JOIN cat_job_position AS c ON  c.id = b.id_job_position 
                            LEFT JOIN cat_departament AS d ON  d.id_depto = b.id_departament
                            JOIN tbl_users AS e ON a.id_manager = e.id_user
                        WHERE a.date_notification = CURDATE()  -- a.date_expiration BETWEEN CURDATE() AND '2023-10-22'
                            AND a.date_expiration IN (
                                SELECT MAX(wt1.date_expiration)
                                FROM tbl_user_type_of_contract AS wt1
                                WHERE wt1.id_contract = a.id_contract
                                AND wt1.type_of_contract IN (2,3,4)
                                GROUP BY wt1.id_user)
                            AND a.active_status = 1 
                            AND a.type_of_contract IN (2,3,4)                
                            AND (
                                SELECT COUNT(*)
                                FROM tbl_user_type_of_contract AS wt2
                                WHERE wt2.id_user = a.id_user
                                AND wt2.`option` IN (1, 3)
                            ) = 0
                        AND (b.type_of_employee = 1 AND b.company = 2)
                    ORDER BY a.date_expiration ASC")->getResult();

                    if ($query3) {
                        $emailN = "gmartinez@walworth.com.mx";
                        $toUserN = "MARIA GUADALUPE MARTINEZ RIVERA";
                        $this->emailNotifyContractTemp($emailN, $toUserN, $query3, 2);
                    }
                }
            }
            return 'Exito';
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function consultarEstatusCfdi()
    {
        /* $rfcEmisor = 'EMI123456789';
        $rfcReceptor = 'REC123456789';
        $total = 1234.56;
        $uuid = '123e4567-e89b-12d3-a456-426614174000'; */

        $rfcEmisor = '';
        $rfcReceptor = '';
        $total = 0;
        $uuid = '';
        
        $binder_temp = FCPATH . "XML/temp_xml";
        
        if (!file_exists($binder_temp)) {
            mkdir($binder_temp, 0750, true);
        }
        
        $files = $this->request->getFileMultiple('upload'); // Cambia a getFileMultiple para recibir varios archivos

       // var_dump($files);

        foreach ($files as $file) {
        // $file->move($binder_temp);
            $extension = strtolower($file->getClientExtension());
            
        }


       

        
        if ($files) {
            
            foreach ($files as $file) {
                // Verifica que sea un archivo XML antes de moverlo
                if ( $extension === 'xml' ||  $extension === 'XML') {

                    


                    $file->move($binder_temp);
                    $route_xml = $binder_temp . "/" . $file->getClientName();
                    
                    // Procesa el archivo XML
                    $xml = simplexml_load_file($route_xml);
                    $ns = $xml->getNamespaces(true);
                    $xml->registerXPathNamespace('c', $ns['cfdi']);
                    $xml->registerXPathNamespace('t', $ns['tfd']);
                    
                    // Lee la información del CFDI
                    foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
                        $total = (!empty($cfdiComprobante['Total'])) ? (string) $cfdiComprobante['Total'] : null;
                        
                    }
        
                    
                        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $cfdiTimbreFiscal) {
                            $uuid = (string) $cfdiTimbreFiscal['UUID'];
                        }
                    
        
                    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
                        $rfcEmisor = (!empty($Emisor['Rfc'])) ? (string) $Emisor['Rfc'] : null;
                    }
        
                    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor) {
                        $rfcReceptor = (!empty($Receptor['Rfc'])) ? (string) $Receptor['Rfc'] : null;
                    }
                }
            }
        }
        
        // Imprimir los resultados
        /* echo "RFC Emisor: " . $rfcEmisor . "<br>";
        echo "RFC Receptor: " . $rfcReceptor . "<br>";
        echo "Total: " . $total . "<br>";
        echo "UUID: " . $uuid . "<br>"; */
    
        
       

        // URL del servicio del SAT
        $url = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?wsdl';

        // Parámetros de la consulta (en formato requerido por el SAT)
        $params = [
            'expresionImpresa' => sprintf("?re=%s&rr=%s&tt=%.6f&id=%s", $rfcEmisor, $rfcReceptor, $total, $uuid)
        ];

        // Configurar la petición SOAP
        try {
            $client = new \SoapClient($url);

            // Hacer la consulta al servicio web
            $response = $client->Consulta($params);
            
           
            // Procesar la respuesta
            if ($response->ConsultaResult->Estado === 'Vigente') {
                $result = 'El CFDI es válido y está vigente.';
                $class = "success";
            } elseif ($response->ConsultaResult->Estado === 'Cancelado') {
                $result = 'El CFDI ha sido cancelado.';
                $class = "danger";
            } else {
                $result = 'El CFDI no es válido o no se encontró.';
                $class = "warning";
            }

            $data = ["resultado" => $result, "clase" => $class];

            return ($result) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return 'Error al consultar el estado del CFDI: ' . $e->getMessage();
        }
    }

    

   // public function emailNotifyContractTemp($dir_email = null, $user = null, $arrayData  = null, $type = null) {}

    public function emailNotifyContractTempBueno($dir_email = null, $user = null, $arrayData  = null, $type = null)
    {
        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $email = changeEmail($dir_email);
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
            // $mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('notificacion@walworth.com', 'Contratos Temporales');

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
            $data = ['informacion' =>  $arrayData, 'tipo' => $type];
            $mail->isHTML(true);
            $email_template = view('notificaciones/notify_temporary_contract', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Notificacion Prontos a Expirar';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function notifySupply()
    {
        $dateToDay = date("Y-m-d");
        $query = $this->db->query("SELECT id_notifica FROM tbl_vh_notificaciones WHERE fecha_notifica = '$dateToDay' AND active_status = 1")->getResult();
        if ($query) {
            $weekDay = date('w', strtotime($dateToDay));
            // $dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            $diasMas = ['+ 1 days', '0', '0', '0', '0', '0', '+ 2 days'];
            if ($weekDay == 0 || $weekDay == 6) {
                # dia domingo -> + 1 || # dia sabado -> + 2
                $newDate = date("Y-m-d", strtotime($dateToDay . $diasMas[$weekDay]));
                $upDate = [
                    'fecha_notifica' => $newDate,
                ];
                for ($i = 0; $i < count($query); $i++) {
                    $this->vhNewsModel->update($query[$i]->id_notifica, $upDate);
                }
                return false;
            } else {
                $query2 = $this->db->query("SELECT b.orden_compra,a.codigo,a.fecha_entrega,a.desc_breve,a.num_piezas
                                            FROM tbl_vh_ordenes_items AS a
                                            LEFT JOIN tbl_vh_ordenes_compras AS b
                                            ON a.id_request = b.id_request
                                            WHERE a.id_items IN( SELECT id_item 
                                                                    FROM tbl_vh_notificaciones 
                                                                    WHERE fecha_notifica = '$dateToDay' AND active_status = 1)
                                          ")->getResultArray();


                foreach ($query2 as $key => $value) {
                    $fecha_actual = new Time("now");
                    $fecha_entrega = new Time($value["fecha_entrega"]);
                    $dias = $fecha_entrega->diff($fecha_actual)->days;
                


                    $groups[$value['orden_compra']][$value['codigo']] = $value['desc_breve'] . " | Numero de Piezas: " . $value['num_piezas'] . " | dias restantes: " . $dias;
                }
               


                $data = ["partidas" => $groups];
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
                
                $mail->Port = 25;
                //Recipients
                $mail->setFrom('notificacion@walworth.com', 'Notificacion de Partidas');

                // Add a recipient
                $mail->addAddress('cmorales@walworth.com.mx', 'Cecilia Morales');
                $mail->addAddress('hgarcia@walworth.com.mx', 'Humberto Garcia');
                $mail->addAddress('pgomez@walworth.com.mx', 'Patricia Gomez');
                $mail->addAddress('fgarcia@walworth.com.mx', 'Federico Garcia');



                // Name is optional
                $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
                //$mail->addCC('cc@example.com');
                $mail->addBCC('rcruz@walworth.com.mx');
                $mail->addBCC('hrivas@walworth.com.mx');

            

                $mail->isHTML(true);
                $email_template = view('notificaciones/vh_suministros_notifica', $data);
                $mail->MsgHTML($email_template);                              // Set email format to HTML
                $mail->Subject =  'Notificacion Partidas';
                $mail->send();
                return true;
            } catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
        }
    }

    public function notifyInventorySystem()
    {
        try {
            $query = $this->db->query("SELECT product, amount FROM tbl_system_inventory WHERE amount <= min AND active_status = 1")->getResult();
            if ($query) {
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
                // $mail->Username = 'requisiciones@walworth.com.mx';
                // SMTP password (This is that emails' password (The email you created earlier) )
                // $mail->Password = '2contodo';
                // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
                $mail->Port = 25;
                //Recipients
                $mail->setFrom('notificacion@walworth.com', 'Notificacion de Inventario');

                // Add a recipient
                $mail->addAddress('ggarcia@walworth.com.mx', 'Guillermo Garcia');

                // Name is optional
                $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
                //$mail->addCC('cc@example.com');
                $mail->addBCC('rcruz@walworth.com.mx');
                // $mail->addBCC('hrivas@walworth.com.mx');

                //Attachments (Ensure you link to available attachments on your server to avoid errors)
                //    $mail->addAttachment($data["imss"]);         // Add attachments

                //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

                //Content
                $data = ['producto' => $query];
                $mail->isHTML(true);
                $email_template = view('notificaciones/notify_system_inventory', $data);
                $mail->MsgHTML($email_template);                              // Set email format to HTML
                $mail->Subject =  'Notificacion Sistemas';
                $mail->send();
                return true;
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function permissionsReset()
    {
        $query = $this->db->query("SELECT id FROM tbl_assign_departments_to_managers_new WHERE active_status = 1")->getResult();
        foreach ($query as $value) {
            $this->db->query("UPDATE tbl_assign_departments_to_managers_new
             SET amount_permissions = 0
             WHERE id = $value->id");
        }
    }

    public function updateExpirationDate()
    {
        $query = $this->db->query("SELECT id_medicine, expiration_date FROM tbl_medical_inventory_medicine WHERE active_status = 1")->getResult();
        if ($query) {
            for ($i = 0; $i < count($query); $i++) {
                // var_dump($query[$i]);
                // var_dump($query[$i]->expiration_date);
                $expirationDate = $query[$i]->expiration_date;
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
                    'traffic_light' => $trafficLight
                ];
                // var_dump($medicalData);
                $this->medicaments->update($query[$i]->id_medicine, $medicalData);
            }
        }
    }

    public function notificationOfexpenses()
    {
        $comprobaciones = $this->db->query("SELECT a.user,a.reasons,a.id_expenses,a.start_date,a.end_date,a.total_amount,b.email
                                    FROM tbl_services_request_expenses AS a
                                    LEFT JOIN tbl_users AS b
                                    ON a.id_user = b.id_user
                                    WHERE expense_vouchers = 1")->getResultArray();

        foreach ($comprobaciones as $key => $comprobar) {

            $dateToDay = date("Y-m-d");

            $email = $comprobar["email"];
            $user = $comprobar["user"];
            $date_end = $comprobar["end_date"];

            $weekDay = date('w', strtotime($date_end));
            // $dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            $diasMas = ['+ 1 days', '0', '0', '0', '0', '0', '+ 2 days'];
            if ($weekDay == 0 || $weekDay == 6) {
                # dia domingo -> + 1 || # dia sabado -> + 2
                $date_end = date("Y-m-d", strtotime($date_end . $diasMas[$weekDay]));
            }

            $newDate = date("Y-m-d", strtotime($date_end . '+ 5 days'));

            if ($dateToDay <= $date_end) return;

            if ($dateToDay < $newDate) {

                $data = [
                    'usuario' => $comprobar["user"],
                    'razon' => $comprobar["reasons"],
                    'folio' => $comprobar["id_expenses"],
                    'inicio' => $comprobar["start_date"],
                    'regreso' => $comprobar["end_date"],
                    'total' => $comprobar["total_amount"],
                ];


                sleep(90);
                $this->notifyExpensesAndTravel($email, $user, $data, 1);
            }
        }
    }

    public function travelNotification()
    {
        $comprobaciones = $this->db->query("SELECT
                                            a.id_request_travel,
                                            a.user_name,    
                                            CASE
                                                WHEN a.type_of_travel = 1 THEN 'Nacional'
                                                ELSE 'Internacional'
                                            END AS type_travel,
                                                
                                            CASE
                                                WHEN a.`level` = 1 THEN 'Director General y Directores de Área'
                                                    WHEN a.`level` = 2 THEN 'Gerentes de Jefes de Área'
                                                    WHEN a.`level` = 3 THEN 'Vendedor Sr y Vendedor Jr'
                                                    WHEN a.`level` = 4 THEN 'Resto del Personal'
                                                    WHEN a.`level` = 5 THEN 'Resto del Personal'
                                                    WHEN a.`level` = 6 THEN 'Resto del Personal'
                                                    WHEN a.`level` = 7 THEN 'Resto del Personal'
                                                    WHEN a.`level` = 8 THEN 'Resto del Personal'
                                                    ELSE 'Error'
                                            END AS level_travel,
                                                a.start_of_trip,
                                                a.return_trip,
                                                a.start_time,
                                                a.return_time,
                                                a.trip_origin,
                                                a.trip_destination,
                                                a.airplane,
                                                a.total_travel,
                                            a.trip_details,
                                                b.email
                                            FROM
                                                tbl_services_request_travel AS a
                                            LEFT JOIN tbl_users AS b ON a.id_user = b.id_user
                                            WHERE
                                                a.travel_vouchers = 1")->getResultArray();

        foreach ($comprobaciones as $key => $comprobar) {

            $dateToDay = date("Y-m-d");

            $email = $comprobar["email"];
            $user = $comprobar["user_name"];
            $date_end = $comprobar["return_trip"];

            $weekDay = date('w', strtotime($date_end));
            // $dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            $diasMas = ['+ 1 days', '0', '0', '0', '0', '0', '+ 2 days'];
            if ($weekDay == 0 || $weekDay == 6) {
                # dia domingo -> + 1 || # dia sabado -> + 2
                $date_end = date("Y-m-d", strtotime($date_end . $diasMas[$weekDay]));
            }

            $newDate = date("Y-m-d", strtotime($date_end . '+ 5 days'));

            if ($dateToDay <= $date_end) return;

            if ($dateToDay < $newDate) {

                $data = [
                    'id_request_travel' => $comprobar["id_request_travel"],
                    'user_name' => $comprobar["user_name"],
                    'type_travel' => $comprobar["type_travel"],
                    'level_travel' => $comprobar["level_travel"],
                    'start_of_trip' => $comprobar["start_of_trip"],
                    'return_trip' => $comprobar["return_trip"],
                    'start_time' => $comprobar["start_time"],
                    'return_time' => $comprobar["return_time"],
                    'trip_origin' => $comprobar["trip_origin"],
                    'trip_destination' => $comprobar["trip_destination"],
                    'airplane' => $comprobar["airplane"],
                    'total_travel' => $comprobar["total_travel"],
                    'trip_details' => $comprobar["trip_details"]
                ];

                sleep(90);
                $this->notifyExpensesAndTravel($email, $user, $data, 2);
            }
        }
    }

    public function notifyExpensesAndTravel($email, $user, $data, $tipo)
    {
        try {
            $dir_email = changeEmail($email);

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
            //$mail->Username = 'requisiciones@walworth.com.mx';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $title = ($tipo == 1) ? 'Comprobación de Gastos' : 'Comprobación de Viáticos';
            $mail->setFrom('notificacion@walworth.com', $title);

            // Add a recipient
            $mail->addAddress($dir_email, $user);
            $mail->addCC('ahuerta@walworth.com.mx', "ADRIAN ALEJANDO HUERTA CALDERON");
            $mail->addCC('bpedraza@walworth.com.mx', 'Blanca Estela Pedraza');
			$mail->addCC('dprado@walworth.com.mx', 'David Prado');

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');



            //Content

            $mail->isHTML(true);
            $template = ($tipo === 1) ? 'notificaciones/notification_expenses' : 'notificaciones/notification_travel';
            $email_template = view($template, $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Notificacion Sistemas';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function updateAccountStatementPolicies()
    {
        $this->db->query("UPDATE tbl_services_account_status SET politics_status = 
            CASE 
                WHEN date_transaction = LAST_DAY(date_transaction) 
			    THEN CASE 
					WHEN CURDATE() = LAST_DAY(date_transaction) 
						THEN 1 -- 'A TIEMPO'
					WHEN  DAYOFWEEK(date_transaction) IN (1,6,7) AND DAYOFWEEK(CURDATE()) IN (1,7) AND DAY(CURDATE()) <= 3
						THEN 1 -- 'A TIEMPO'				
					WHEN DAYOFWEEK(date_transaction) IN (1,6,7) AND DAYOFWEEK(CURDATE()) = 2 AND DAY(CURDATE()) <= 3
						THEN 2 -- 'ultimo dia de mes comprobacion, y estoy en el ultimo dia de validacion'
					WHEN DAYOFWEEK(date_transaction) IN (2,3,4,5) AND DAY(CURDATE()) = 1
						THEN 2 -- 'Ultimo dia de mes comprobacion L a J, y estoy en primer dia del siguiente mes'
					WHEN DAYOFWEEK(date_transaction) IN (1,6,7) AND DAY(CURDATE()) > 3
						THEN 3 -- 'ultimo dia de mes comprobacion V o S, y estoy fuera de tiempo'
					WHEN DAYOFWEEK(date_transaction) IN (2,3,4,5) AND DAY(CURDATE()) > 1
						THEN 3 -- 'Ultimo dia de mes comprobacion L a J, y estoy feura de tiempo'
			    END
                WHEN date_transaction >= LAST_DAY(date_transaction) - INTERVAL 5 DAY AND CURDATE() >= LAST_DAY(date_transaction) - INTERVAL 5 DAY
                    THEN 1 -- 'A TIEMPO'
                WHEN date_transaction >= LAST_DAY(date_transaction) - INTERVAL 5 DAY AND CURDATE() = LAST_DAY(date_transaction)
                    THEN 2 -- 'Ultimo día'
                WHEN date_transaction >= LAST_DAY(date_transaction) - INTERVAL 5 DAY AND CURDATE() > LAST_DAY(date_transaction)
                    THEN 3 -- 'Fuera de tiempo' 
                 -- WHEN DATEDIFF(date_transaction, CURDATE()) < -5 THEN 1 -- 'A TIEMPO'
                 -- WHEN DATEDIFF(date_transaction, CURDATE()) = -5 THEN 2 -- 'ULTIMO DIA'
                 -- WHEN DATEDIFF(date_transaction, CURDATE()) < -5 THEN 3 -- 'FUERA DE TIEMPO'
                WHEN DATEDIFF(CURDATE(), date_transaction) - (2 * FLOOR((DATEDIFF(CURDATE(), date_transaction) + WEEKDAY(date_transaction) + 1) / 7)) < 5 
                    THEN 1
                WHEN DATEDIFF(CURDATE(), date_transaction) - (2 * FLOOR((DATEDIFF(CURDATE(), date_transaction) + WEEKDAY(date_transaction) + 1) / 7)) = 5 
                    THEN 2
                ELSE 3
             END 
            WHERE active_status = 1
                AND transaction_status = 1
               -- AND date_transaction >= LAST_DAY(date_transaction) - INTERVAL 5
        AND politics_status < 3");

        /* $query = $this->db->query("UPDATE tbl_services_account_status
        SET politic_status = 
            CASE
                WHEN DATEDIFF(CURDATE(), date_transaction) - (2 * FLOOR((DATEDIFF(CURDATE(), date_transaction) + WEEKDAY(date_transaction) + 1) / 7)) < 5 THEN 1
                WHEN DATEDIFF(CURDATE(), date_transaction) - (2 * FLOOR((DATEDIFF(CURDATE(), date_transaction) + WEEKDAY(date_transaction) + 1) / 7)) = 5 THEN 2
                ELSE 3
            END
        WHERE
            active_status = 1 
            AND transaction_status = 1 
            AND DATE_SUB(LAST_DAY(date_transaction), INTERVAL 5 DAY)
            AND politics_status < 3;"); */
        //   difecio de dias - fines de semana < 5 THEN 1 -- 'A TIEMPO'
        //   difecio de dias - fines de semana = 5 THEN 2 -- 'ULTIMO DIA'
        //   difecio de dias - fines de semana < 5 THEN 3 -- 'FUERA DE TIEMPO'
        /* QUERY PARA SABER QEU FOLIOS VIATICOS ESTAN ACTIVOS  */
        $userTravels = $this->db->query("SELECT a.id_request_travel, a.user_name, b.email
            FROM tbl_services_request_travel AS a 
            JOIN tbl_users AS b on a.id_user = b.id_user
            WHERE a.active_status = 1
            AND a.request_status = 2
            AND CURDATE() BETWEEN a.day_star_travel AND DATE_ADD(a.day_end_travel, INTERVAL 5 DAY)
        AND a.verification_status IN (1,2)")->getResult();

        if ($userTravels) {
            foreach ($userTravels as $value) {
                $this->notifyActiveAccountState($value->id_request_travel, 1, $value->email, $value->user_name);
            }
        }

        /* QUERY PARA SABER QEU FOLIOS GASTOS ESTAN ACTIVOS  */
        $userExpenses = $this->db->query("SELECT a.id_request_expenses, a.user_name, b.email
				FROM tbl_services_request_expenses AS a 
				JOIN tbl_users AS b on a.id_user = b.id_user
				WHERE a.active_status = 1
				AND a.request_status = 2
				AND CURDATE() BETWEEN a.day_star_expenses AND DATE_ADD(a.day_end_expenses, INTERVAL 5 DAY)
			AND a.verification_status IN (1,2)")->getResult();

        if ($userExpenses) {
            foreach ($userExpenses as $value) {
                $this->notifyActiveAccountState($value->id_request_expenses, 2, $value->email, $value->user_name);
            }
        }
    }

    function notifyActiveAccountState($idRequest = null, $typeTraExt = null, $emailUser = null, $nameUser = null)
    {
        try {
            $dirEmail = changeEmail($emailUser);

            $accountInPolitic = $this->db->query(" SELECT
                DATE_FORMAT(a.date_transaction,'%d/%m/%Y') AS fecha,
                UPPER(a.location_transaction) AS lugar,
                CONCAT(a.amount,' ', a.divisa) AS monto,
                (SELECT ct1.text FROM cat_travels_status AS ct1 WHERE ct1.type = 3 AND ct1.status_ = a.politics_status) AS estado_txt,
                (SELECT ct1.color FROM cat_travels_status AS ct1 WHERE ct1.type = 3 AND ct1.status_ = a.politics_status) AS estado_color												
                FROM tbl_services_account_status AS a
                WHERE a.active_status = 1 
                    AND a.id_request = $idRequest
                    AND a.type = $typeTraExt
                    AND a.transaction_status = 1
                    AND a.politics_status < 3
            ORDER BY date_transaction ASC;")->getResult();

            $accountDebt = $this->db->query("SELECT
                DATE_FORMAT(a.date_transaction,'%d/%m/%Y') AS fecha,
                UPPER(a.location_transaction) AS lugar,
                CONCAT(a.amount,' ', a.divisa) AS monto										
                FROM tbl_services_account_status AS a
                WHERE a.active_status = 1 
                    AND a.id_request = $idRequest
                    AND a.type = $typeTraExt
                    AND a.transaction_status = 1
                    AND a.politics_status = 3
            ORDER BY date_transaction ASC;")->getResult();

            // POSIBLE ERROR
            if (empty($accountInPolitic)) {
                return true;
            }

            $data = ["activos" => $accountInPolitic, "deuda" => $accountDebt];

            $mail = new PHPMailer();
            $mail->CharSet = "UTF-8";
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
            //$mail->Username = 'requisiciones@grupowalworth.com';
            //$mail->Password = '2contodo';
            $mail->Port = 25;

            $typeTxt = ($typeTraExt == 1) ? 'Viaticos' : 'Gastos';
            $mail->setFrom('requisiciones@walworth.com', "Comprobación de $typeTxt pendientes");
            $mail->addAddress($dirEmail, $nameUser);
            $mail->addCC('ahuerta@walworth.com.mx', 'Administrador');
            $mail->addCC('bpedraza@walworth.com.mx', 'Blanca Estela Pedraza');
			$mail->addCC('dprado@walworth.com.mx', 'David Prado');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            $mail->isHTML(true);

            $email_template = view('notificaciones/notify_pending_validation', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificaciones Viaticos y Gastos';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function obtener_tipo_cambio()
    {
        // Inicializar la sesión cURL
        $cnx = curl_init();

        // Configurar la URL de la API de Banxico con tu token de acceso
        $url = 'https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/oportuno?token=6f9283f15ae3166770438f3be229a83e89df2ae0a4446e08552105f0bb5b4235';

        // Establecer opciones de la solicitud cURL
        curl_setopt($cnx, CURLOPT_URL, $url);
        curl_setopt($cnx, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cnx, CURLOPT_HTTPGET, true);

        // Ejecutar la solicitud cURL
        $response = curl_exec($cnx);

        // Verificar si hubo algún error en la solicitud
        if (curl_errno($cnx)) {
            $error = curl_error($cnx);
            // Manejar el error según tus necesidades
            echo $dato = "Error en la solicitud: " . $error;

            $data = ['TipoCambio_Error' => $dato];

            $this->Admin_Model->insert($data);
        }

        // Cerrar la sesión cURL
        curl_close($cnx);

        // Procesar la respuesta JSON
        $data = json_decode($response, true);

        // Hacer algo con los datos obtenidos de la API
        // ...
        // Verificar si la respuesta contiene los datos necesarios
        if (isset($data['bmx']['series'][0]['datos'][0]['dato'])) {
            // Iterar sobre el array y obtener el valor del campo "dato"
            foreach ($data['bmx']['series'][0]['datos'] as $item) {
                $dato = $item['dato'];
                $fecha = $item['fecha'];
                $nueva_fecha = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));

                // Obtener el día de la semana (0 para domingo, 6 para sábado)
                $dia_semana = date('w', strtotime(date('Y-m-d')));
                // Restar un día al día de la semana
                //   $dia_semana = ($dia_semana + 6) % 7;

                // Si es sábado (6) o domingo (0), cambiar la fecha por la actual
                if ($dia_semana == 0 || $dia_semana == 6) {
                    $nueva_fecha = date('Y-m-d');
                    // $nueva_fecha = date('Y-m-d', strtotime('-1 day', strtotime($nueva_fecha)));
                }

                $data = [
                    'TipoCambio_TipoCambio' => $dato,
                    'TipoCambio_Fecha' => $nueva_fecha
                ];

                // Hacer algo con los valores obtenidos
                $this->Admin_Model->insert($data);
            }
        } else {
            // La respuesta no contiene los datos esperados
            $dato = "Error: No se encontraron los datos necesarios.";
        }

        // Mostrar la respuesta
        echo $response;
    }

    function closedTickets()
    {
        $this->db->query("UPDATE tbl_tickets_request SET qualify_service = 3, Ticket_EstatusId = 5
        WHERE active_status = 1 AND Ticket_EstatusId = 3 
        AND DATEDIFF(CURDATE(),Ticket_FechaConcluido) > 3;");
    }

    function increaseDays()
    {
        $this->db->query("UPDATE tbl_hse_time_accident_record SET days = days + 1
         WHERE active_status = 1 ;");
    }

    function activeLogin()
    {
        // Define el tiempo límite de 20 minutos atrás
        $limitTime = date('Y-m-d H:i:s', strtotime('-20 minutes'));

        // Encuentra los usuarios con intentos fallidos anteriores al límite de tiempo
        $users = $this->userModel->where('failed_attempts >=', 3)
            ->where('failed_attempt_time <', $limitTime)
            ->findAll();

        foreach ($users as $user) {
            // Restablecer los intentos fallidos a 0
            $this->userModel->update($user['id_user'], [
                'failed_attempts' => 0,
                'failed_attempt_time' => null
            ]);
        }
    }
}
