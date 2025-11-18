<?php

/**
 * MODULO DE PAQUETERIA
 * @version 1.1 pre-prod
 * @author Horus Samael Rivas Pedraza <horus.riv.ped@gmail.com>
 * @telefono 56-2439-2632
 */

namespace App\Controllers\Packer;

use App\Controllers\BaseController;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\CompanyModel;
use App\Models\PackerRequestModel;
use App\Models\PackerItemModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\Exception;

class Packer extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->requestModel = new PackerRequestModel();
        $this->itemModel = new PackerItemModel();
        $this->companyModel = new CompanyModel();
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function viewCreateRequest()
    {
        $applicant_company = $this->companyModel->where('active_status', 1)->findAll();
        $data = [
            "company" => $applicant_company,
        ];
        return ($this->is_logged) ? view('packer/view_packer_request', $data) : redirect()->to(site_url());
    }
    public function viewMyRequest()
    {
        return ($this->is_logged) ? view('packer/view_my_request') : redirect()->to(site_url());
    }
    public function viewAuthorize()
    {
        return ($this->is_logged) ? view('packer/view_authorize') : redirect()->to(site_url());
    }
    public function createRequest()
    {
        $sending_company = trim($this->request->getPost('empresa_solicitante'));
        $sender_name = trim($this->request->getPost('nombre'));
        $sender_name2 = trim($this->request->getPost('solicitante_R'));
        $area_operative = trim($this->request->getPost('puesto_trabajo'));
        $sender_phone = trim($this->request->getPost('telefono_R'));
        $sender_street = trim($this->request->getPost('calle_R'));
        $sender_num = trim($this->request->getPost('numero_R'));
        $sender_col = trim($this->request->getPost('colonia_R'));
        $sender_locality = trim($this->request->getPost('localidad_R'));
        $sender_state = trim($this->request->getPost('estado_R'));
        $sender_country = trim($this->request->getPost('pais_R'));
        $sender_cp = trim($this->request->getPost('cp_R'));
        $recipient_company = trim($this->request->getPost('empresa_destino'));
        $recipient_name = trim($this->request->getPost('nombre_D'));
        $recipient_phone = trim($this->request->getPost('telefono_D'));
        $recipient_street = trim($this->request->getPost('calle_D'));
        $recipient_num = trim($this->request->getPost('numero_D'));
        $recipient_col = trim($this->request->getPost('colonia_D'));
        $recipient_locality = trim($this->request->getPost('localidad_D'));
        $recipient_state = trim($this->request->getPost('estado_D'));
        $recipient_country = trim($this->request->getPost('pais_D'));
        /* $recipient_cp = trim($this->request->getPost('cp_D')); */
        $shipping_type = trim($this->request->getPost('tipo_envio'));
        $sure = trim($this->request->getPost('seguro'));
        $cost = ($sure == 1) ? trim($this->request->getPost('monto')) : "";
        $gather = trim($this->request->getPost('recoleccion'));
        $observation = trim($this->request->getPost('obs'));
        $date = date("Y-m-d H:i:s");

        if ($sending_company ==  "OTRO") {
            $sending_company = trim($this->request->getPost('empresa'));
        }

        $insertData = [
            'id_user' => session()->id_user,
            'sending_company' => $sending_company,
            'sender_name' => $sender_name,
            'sender_name2' => $sender_name2,
            'area_operative' => $area_operative,
            'sender_phone' => $sender_phone,
            'sender_street' => $sender_street,
            'sender_num' => $sender_num,
            'sender_col' => $sender_col,
            'sender_locality' => $sender_locality,
            'sender_state' => $sender_state,
            'sender_country' => $sender_country,
            'sender_cp' => $sender_cp,
            'sure' => $sure,
            'cost' => $cost,
            'gather' => $gather,
            'observation' => $observation,
            'shipping_type' => $shipping_type,
            'recipient_company' => $recipient_company,
            'recipient_name' => $recipient_name,
            'recipient_phone' => $recipient_phone,
            'recipient_street' => $recipient_street,
            'recipient_num' => $recipient_num,
            'recipient_col' => $recipient_col,
            'recipient_locality' => $recipient_locality,
            'recipient_state' => $recipient_state,
            'recipient_country' => $recipient_country,
            'status' => 1,
            'created_at' => $date,
        ];
        // var_dump($insertData);
        $insert = $this->requestModel->insert($insertData);
        $id_request = $this->db->insertID();
        if ($insert) {
            $amount = $this->request->getPost('cantidad_');
            $weight = $this->request->getPost('peso_');
            $base = $this->request->getPost('base_');
            $height = $this->request->getPost('altura_');
            $depth = $this->request->getPost('profundidad_');
            for ($i = 0; $i < count($amount); $i++) {
                $insertItem = [
                    'id_request' => $id_request,
                    'amount' => intval($amount[$i]),
                    'weight' => floatval($weight[$i]),
                    'base' => floatval($base[$i]),
                    'height' => floatval($height[$i]),
                    'depth' => floatval($depth[$i]),
                    'status' => 1,
                    'created_at' => $date
                ];
                $this->itemModel->insert($insertItem);
            }
        }

        $email = "gmendoza@walworth.com.mx";
        $title = "Gerardo Mendoza Villegas";
        $this->emailnotification($email, $title, $insertData, $i);
        return ($insertItem) ? json_encode(true) : json_encode(false);
    }

    public function myRequest()
    {
        $builder = $this->requestModel
            ->select('*')
            ->where('id_user', session()->id_user)
            ->orderBy('id_request', 'DESC')
            ->limit(500);
        $data = $builder->get()->getResult();

        return (count($data) > 0) ? json_encode($data) : json_encode(false);
    }

    public function allRequest()
    {
        $builder = $this->requestModel
            ->select('*')
            ->orderBy('id_request', 'DESC')
            ->limit(1500);
        $data = $builder->get()->getResult();

        return (count($data) > 0) ? json_encode($data) : json_encode(false);
    }

    public function authorize()
    {
        $date = date("Y-m-d H:i:s");
        $dateFile = strval(date("Y-m-d_H_i_s"));
        $binder =  '../public/doc/packer';

        $name = trim($this->request->getPost('usuario'));
        $id_request = trim($this->request->getPost('folio'));
        $coment = trim($this->request->getPost('coment'));
        $status = trim($this->request->getPost('estado'));
        if ($status == 2) {
            $guieFile = $this->request->getFile('guia');
            $originalName = "folio_" . $id_request . "_fecha_" . $dateFile . ".pdf";
            $guieFile = $guieFile->move($binder,  $originalName);
            $path = $binder . "/" . $originalName;
        } else {
            $path = null;
        }
        $updateData = [
            'status' => $status,
            'autorize_at' => $date,
            'pdf_guie' => $path,
            'id_answer' => session()->id_user,
            'answer_at' => date("Y-m-d H:i:s"),
        ];
        $update = $this->requestModel->update($id_request, $updateData);

        $query0 = $this->db->table('tbl_packer_request')->select('id_user')->where('id_request', $id_request)->limit(1)->get()->getRow();
        $query1 = $this->db->table('tbl_users')->select('email')->where('id_user', $query0->id_user)->limit(1)->get()->getRow();
        $email = $query1->email;
        $Data = [
            'status' => $status,
            'autorize_at' => $date,
            'id_request' => $id_request,
            'coment' => $coment
        ];
        $dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user IN 
            (SELECT id_user FROM tbl_packer_request WHERE id_request = $id_request)")->getRow();
        $email = $dataEmail->email;
        $title = $dataEmail->name . " " . $dataEmail->surname;
        $this->emailnotificationAuthorize($email, $title, $Data, $path);
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function pdfRequestPacker($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_packer_request
                                        WHERE
                                        MD5(concat('" . $key . "',id_request))='" . $id_request . "'");
        $dataRequest =  $query->getRow();

        $query2 = $this->db->query("SELECT *
                                    FROM
                                    tbl_packer_item
                                    WHERE
                                    id_request = $dataRequest->id_request
                                    ");
        $dataItem =  $query2->getResult();

        $data = [
            "request" => $dataRequest,
            "item" => $dataItem
        ];
        $html2 = view('pdf/pdf_packer', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Solicitudes');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('solicitudes_' . $id_request . '.pdf', 'I');
    }

    public function xlsxRequestPacker()
    {

        $data = json_decode(stripslashes($this->request->getPost('data')));

        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_packer_request
                                        WHERE
                                        id_request=$data->id_request");
        $dataRequest =  $query->getRow();

        $query2 = $this->db->query("SELECT *
                                    FROM
                                    tbl_packer_item
                                    WHERE
                                    id_request = $dataRequest->id_request
                                    ");
        $dataItem =  $query2->getResult();

        $NombreArchivo = "Solicitud_de_envio_" . $dataRequest->id_request . ".xlsx";

        if ($dataRequest->sure == 1) {
            $seguro = "SI";
            $costo = $dataRequest->cost;
        } else {
            $seguro = "NO";
            $costo = "";
        }
        if ($dataRequest->shipping_type == 1) {
            $tipo = "Dia Siguiente";
        }
        if ($dataRequest->shipping_type == 2) {
            $tipo = "Terrestre";
        }
        if ($dataRequest->gather == 1) {
            $recoleccion = "Se Requiere Recoleccion";
        }
        if ($dataRequest->gather == 2) {
            $recoleccion = "No Necesaria";
        }
        $cont = 10;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Solicitud de Envio");
        // definir ancho de casillas
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(14);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10); // cantidad
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10); // peso
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10); // altura
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10); // base 
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(10); // anchura

        // definir anchura      
        // $spreadsheet->getActiveSheet()->getDefaultRowDimension("A")->setRowHeight(30); // darle alto a todas las filas
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(65); // alto de fila
        $spreadsheet->getActiveSheet()->getRowDimension('16')->setRowHeight(15);
        $spreadsheet->getActiveSheet()->getRowDimension('17')->setRowHeight(5);


        // Determino ubicacion del texto
        $sheet->getStyle('C2:I15')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // definir alineacion de texto HORUZONTAL    
        $sheet->getStyle('A1:I15')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL
        $sheet->getStyle('A1:B15')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D2:D15')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E9:I12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E13:I14')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

        // Borde de tabla        
        $spreadsheet->getActiveSheet()->getStyle('A1:I15')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $spreadsheet->getActiveSheet()->getStyle('A1:I15')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $spreadsheet->getActiveSheet()->getStyle('A1:I15')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $spreadsheet->getActiveSheet()->getStyle('A1:I15')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        // colorear celdas
        $spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        $spreadsheet->getActiveSheet()->getStyle('A17:I17')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E21F1D');

        // font text por grupos
        $sheet->getStyle("B1:I1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(18)
            ->getColor()
            ->setRGB('FFFFFF');

        $sheet->getStyle("A2:A15")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(13)
            ->getColor()
            ->setRGB('#00000F');

        $sheet->getStyle("B2:B15")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(10)
            ->getColor()
            ->setRGB('#00000F');

        $sheet->getStyle("D2:D15")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(10)
            ->getColor()
            ->setRGB('#00000F');

        $sheet->getStyle("A16:I16")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(9)
            ->getColor()
            ->setRGB('#00000F');

        $sheet->setCellValue('B1', 'SOLICITUD DE ENVÍO') // informacion o texto
            ->mergeCells('B1:I1') // definir combinar
        ;

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('./images/MBE.jpg'); // put your path and image here
        $drawing->setCoordinates('A1');
        $drawing->setHeight(60);
        $drawing->setOffsetX(27); // desplazamiento de imagen en x
        $drawing->setOffsetY(13); // desplazamiento de imagen en Y
        /* 
        $drawing->setRotation(25); // inclinacion de imagen
        $drawing->getShadow()->setVisible(true); // efecto sombra
        $drawing->getShadow()->setDirection(45); // direccion de sombra */
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $sheet->setCellValue('A2', 'Remitente')->mergeCells('A2:A8');

        $sheet->setCellValue('B2', 'Empresa');
        $sheet->setCellValue('C2', $dataRequest->sending_company);
        $sheet->setCellValue('B3', 'Nombre');
        $sheet->setCellValue('C3', $dataRequest->sender_name2);
        $sheet->setCellValue('B4', 'Direccion')->mergeCells('B4:B7');
        $sheet->setCellValue('C4', $dataRequest->sender_street . ", " . mb_strtoupper($dataRequest->sender_num, 'UTF-8'));
        $sheet->setCellValue('C5', $dataRequest->sender_locality . ", " . $dataRequest->sender_state);
        $sheet->setCellValue('C6', mb_strtoupper($dataRequest->sender_cp, 'UTF-8'));
        $sheet->setCellValue('C7', $dataRequest->sender_country);
        $sheet->setCellValue('B8', 'Telefono');
        $sheet->setCellValue('C8', $dataRequest->sender_phone);

        $sheet->setCellValue('D2', 'Solicitante');
        $sheet->setCellValue('E2', $dataRequest->sender_name)->mergeCells('E2:I2');
        $sheet->setCellValue('D3', 'Centro de costos');
        $sheet->setCellValue('E3', $dataRequest->area_operative)->mergeCells('E3:I3');
        $sheet->setCellValue('D4', 'Fecha');
        $sheet->setCellValue('E4', $dataRequest->created_at)->mergeCells('E4:I4');
        $sheet->setCellValue('D5', 'Seguro')->mergeCells('D5:D7');
        $sheet->setCellValue('E5', $seguro)->mergeCells('E5:I6');
        $sheet->setCellValue('E7', 'Monto: $');
        $sheet->setCellValue('F7', $costo)->mergeCells('F7:I7');
        $sheet->setCellValue('D8', 'Recoleccion');
        $sheet->setCellValue('E8', $recoleccion)->mergeCells('E8:I8');


        $sheet->setCellValue('A9', 'Destinatario')->mergeCells('A9:A15');

        $sheet->setCellValue('B9', 'Empresa');
        $sheet->setCellValue('C9', $dataRequest->recipient_company);
        $sheet->setCellValue('B10', 'Nombre');
        $sheet->setCellValue('C10', $dataRequest->recipient_name);
        $sheet->setCellValue('B11', 'Direccion')->mergeCells('B11:B14');
        $sheet->setCellValue('C11', $dataRequest->recipient_street . ", " . mb_strtoupper($dataRequest->recipient_num, 'UTF-8'));
        $sheet->setCellValue('C12', $dataRequest->recipient_locality);
        $sheet->setCellValue('C13', $dataRequest->recipient_state);
        $sheet->setCellValue('C14', $dataRequest->recipient_country);
        $sheet->setCellValue('B15', 'Telefono');
        $sheet->setCellValue('C15', $dataRequest->recipient_phone);

        $sheet->setCellValue('D9', 'Descripcion')->mergeCells('D9:D12');
        $sheet->setCellValue('E9', 'Cantidad');
        $sheet->setCellValue('F9', 'Peso');
        $sheet->setCellValue('G9', 'Base');
        $sheet->setCellValue('H9', 'Altura');
        $sheet->setCellValue('I9', 'Ancho');

        foreach ($dataItem as $key => $value) {
            $sheet->getStyle("E" . $cont . ":I" . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue('E' . $cont, $value->amount);
            $sheet->setCellValue('F' . $cont, $value->weight . " Kg");
            $sheet->setCellValue('G' . $cont, $value->base . " cm");
            $sheet->setCellValue('H' . $cont, $value->height . " cm");
            $sheet->setCellValue('I' . $cont, $value->depth . " cm");
            $cont++;
        }

        $sheet->setCellValue('D13', 'Observaciones')->mergeCells('D13:D14');
        $sheet->setCellValue('E13', $dataRequest->observation)->mergeCells('E13:I14');
        $sheet->setCellValue('D15', 'Tipo de Envio');
        $sheet->setCellValue('E15', $tipo)->mergeCells('E15:I15');

        $sheet->setCellValue('A16', '  MBO HOMERO Homero 1507-C COL. Polanco P.P. 11560 Del. Miguel Hidalgo Tel: 55-55-80-48-00 loghomero@mx.mbelatam.com')->mergeCells('A16:I16');


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

    public function emailNotification($email = null, $user = null, $datas = null, $cont = null)
    {
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
            $mail->setFrom('notificacion@walworth.com', 'Solicitud|Paqueteria');
            // Add a recipient
            $mail->addAddress($email, $user);
            $mail->addAddress('gmendoza@walworth.com.mx', 'Gerardo Mendoza Villegas');
            //$mail->addAddress('ahuerta@walworth.com.mx', 'Alejandro Huerta');
            //$mail->addAddress('yvelazquez@walworth.com.mx', 'Samanta Yedani Velazquez');

            // Name is optional
            //$mail->addBCC('icardenas@walworth.com.mx');
            //$mail->addBCC('gberriozabal@walworth.com.mx');
            //$mail->addBCC('hrivas@walworth.com.mx');
            $mail->addBCC('rcruz@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $data = ["datas" => $datas, "cont" => $cont];
            $mail->isHTML(true);
            $email_template = view('notificaciones/notify_packer', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Solicitud de Paqueteria';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function emailnotificationAuthorize($email = null, $user = null, $datas = null, $document = null)
    {
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
            $mail->setFrom('notificacion@walworth.com', 'Solicitud|Paqueteria');
            // Add a recipient
            $mail->addAddress($email, $user);
            // Name is optional
            //$mail->addBCC('icardenas@walworth.com.mx');
            $mail->addBCC('gmendoza@walworth.com.mx');
            //$mail->addBCC('yvelazquez@walworth.com.mx');
           // $mail->addBCC('ahuerta@walworth.com.mx');
            // $mail->addBCC('gberriozabal@walworth.com.mx');
           // $mail->addBCC('hrivas@walworth.com.mx');
            $mail->addBCC('rcruz@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            $mail->addAttachment($document);  // Add attachments ( 'direccion del documento' )

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $data = ["datas" => $datas];
            $mail->isHTML(true);
            $email_template = view('notificaciones/notify_packer_to_user', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Respuesta a Solicitud de Paqueteria';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
