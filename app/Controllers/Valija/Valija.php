<?php

namespace App\Controllers\Valija;

use App\Controllers\BaseController;

use App\Models\valijaModel;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Valija extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->valijaModel = new valijaModel();
        helper('secure_password');
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }
    public function index()
    {
        return ($this->is_logged) ? view('valija/view_valija') : redirect()->to(site_url());
    }
    public function viewMyRequest()
    {
        return ($this->is_logged) ? view('valija/view_valija_request_user') : redirect()->to(site_url());
    }
    public function viewRequestAll()
    {
        return ($this->is_logged) ? view('valija/view_valija_request_all') : redirect()->to(site_url());
    }

    public function RequestsAll()
    {
        try {
            $builder = $this->db->table('tbl_valija_request');
            $builder->select('*');
            $builder->where('active_status', 1);
            $builder->orderBy('id_valija', 'DESC');
            $builder->limit(1500);
            $data = $builder->get()->getResult();

            return (count($data) > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function valijaRequest()
    {
        try {

            $valija_origin = trim($this->request->getPost('origen'));
            $valija_destination = trim($this->request->getPost('destino'));
            $priority = trim($this->request->getPost('prioridad'));
            $date = trim($this->request->getPost('fecha'));
            $time = trim($this->request->getPost('hora'));
            $observation = trim($this->request->getPost('observacion'));
            $another_origin = trim($this->request->getPost('otro_origen'));
            $another_destination = trim($this->request->getPost('otro_destino'));
            

            $date_created = date("Y-m-d H:i:s");
            $area_operativa = session()->cost_center;
            $id_depto = session()->id_depto;
            $user = session()->name . " " . session()->surname;

            $data_valija = [
                "id_user" => session()->id_user,
                "user_name" => $user,
                "departament" => session()->departament,
                "payroll_number" => session()->payroll_number,
                "job_position" => session()->job_position,
                "type_of_employee" => session()->type_of_employee,
                "origin" => $valija_origin,
                "another_origin" => $another_origin,
                "destination" => $valija_destination,
                "another_destination" => $another_destination,
                "date" => $date,
                "time" => $time,
                "priority" => $priority,
                "observation" => $observation,
                "area_operativa" => $area_operativa,
                "id_depto" => $id_depto,
                "created_at" => $date_created
            ];

            $insertData =  $this->valijaModel->insert($data_valija);

            $email = "olopez@walworth.com.mx";
            $title = "OSCAR DAVID LOPEZ ";
            $this->emailnotification($email, $title, $data_valija, 1);

            return ($insertData) ? json_encode(true) : json_encode('error');
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function RequestsUser()
    {
        try {
            $builder = $this->db->table('tbl_valija_request');
            $builder->select('*');
            $builder->where('id_user', session()->id_user);
            $builder->where('active_status', 1);
            $builder->orderBy('id_valija', 'DESC');
            $builder->limit(1500);
            $data = $builder->get()->getResult();

            return (count($data) > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function editRequests()
    {
        try {
            $id_valija = $this->request->getPost('id_valija');
            $builder = $this->db->table('tbl_valija_request');
            $builder->select('*');
            $builder->where('id_valija', $id_valija);
            $builder->limit(1);
            $result = $builder->get()->getResult();
            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizeRequests()
    {
        try {
            $id_valija = $this->request->getPost('id_valija');
            $estatus = $this->request->getPost('estatus');

            $data = [
                "status" => $estatus, 
                "id_answer" => session()->id_user,
                "answer_at" => date("Y-m-d H:i:s"),
            ];

            $result = $this->valijaModel->update($id_valija, $data);
            if ($result) {


                $builder = $this->db->table('tbl_valija_request');
                $builder->select('id_user,origin,another_origin,destination,another_destination,date,time,priority,observation');
                $builder->where('id_valija', $id_valija);
                $builder->limit(1);
                $resulta = $builder->get()->getResult();
                foreach ($resulta as $key => $value) {
                    $id_usuario = $value->id_user;
                    $data_valija = [
                        "folio" => $id_valija,
                        "status" => $estatus,
                        "origin" => $value->origin,
                        "another_origin" => $value->another_origin,
                        "destination" => $value->destination,
                        "another_destination" => $value->another_destination,
                        "date" => $value->date,
                        "time" => $value->time,
                        "priority" => $value->priority,
                        "observation" => $value->observation,
                    ];
                }




                $builder2 = $this->db->table('tbl_users');
                $builder2->select('id_user,name,surname,email');
                $builder2->where('id_user', $id_usuario);
                $builder2->limit(1);
                $resulta2 = $builder2->get()->getResult();

                foreach ($resulta2 as $key => $values) {
                    $email = $values->email;
                    $user = $values->name . " " . $values->surname;
                }

                $this->emailNotification($email, $user, $data_valija, 2);
            }
            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function generateValijaReportsXlsx()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $fecha_inicio = $data->fecha_inicio;
        $fecha_fin = $data->fecha_fin;
        $NombreArchivo = "Reporte_" . $fecha_inicio . "_" . $fecha_fin . ".xlsx";
        $query = $this->db->query("SELECT * FROM tbl_valija_request WHERE created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'")->getResult();

        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:N1');
        $sheet->setTitle("Solicitudes de Valija");


        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

        // ANCHO DE CELDA
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(34);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(55);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(14);

        //UBICACION DEL TEXTO
        $sheet->getStyle('A1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
        $sheet->getStyle('A1:N1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

        //COLOR DE CELDAS
        $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

        // FONT-TEXT
        $sheet->getStyle("A1:N1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(10)
            ->getColor()
            ->setRGB('FFFFFF');

        // TITULO DE CELDA
        $sheet->setCellValue('A1', 'FOLIO');
        $sheet->setCellValue('B1', 'NOMINA');
        $sheet->setCellValue('C1', 'NOMBRE EMPLEADO');
        $sheet->setCellValue('D1', 'DEPARTAMENTO');
        $sheet->setCellValue('E1', 'PUESTO');
        $sheet->setCellValue('F1', 'ORIGEN');
        $sheet->setCellValue('G1', 'OTRO ORIGEN');
        $sheet->setCellValue('H1', 'DESTINO');
        $sheet->setCellValue('I1', 'OTRO DESTINO');
        $sheet->setCellValue('J1', 'PRIORIDAD');
        $sheet->setCellValue('K1', 'FECHA');
        $sheet->setCellValue('L1', 'HORA');
        $sheet->setCellValue('M1', 'OBSERVACION');
        $sheet->setCellValue('N1', 'ESTADO');

        $stausArray = ['ERROR', 'Pendiente', 'Concluido', 'Cancelado'];
        foreach ($query as $key => $value) {
            $sheet->setCellValue('A' . $cont, $value->id_valija);
            $sheet->setCellValue('B' . $cont, $value->payroll_number);
            $sheet->setCellValue('C' . $cont, $value->user_name);
            $sheet->setCellValue('D' . $cont, $value->departament);
            $sheet->setCellValue('E' . $cont, $value->job_position);
            $sheet->setCellValue('F' . $cont, $value->origin);
            $sheet->setCellValue('G' . $cont, $value->another_origin);
            $sheet->setCellValue('H' . $cont, $value->destination);
            $sheet->setCellValue('I' . $cont, $value->another_destination);
            $sheet->setCellValue('J' . $cont, $value->priority);
            $sheet->setCellValue('K' . $cont, date("d/m/Y", strtotime($value->date)));
            $sheet->setCellValue('L' . $cont, $value->time);
            $sheet->setCellValue('M' . $cont, $value->observation);
            $sheet->setCellValue('N' . $cont, $stausArray[$value->status]);
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
        // aqui
    }


    public function emailNotification($email = null, $user = null, $data = null, $option = null)
    {
        if ($option != 1) {
            /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
            $email = changeEmail($email);
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
            // $mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('notificacion@walworth.com', 'Valija | Solicitud');
            // Add a recipient
            $mail->addAddress($email, $user);
            //$mail->addAddress("icardenas@walworth.com.mx", "Ingrid Cardenas");
            // Name is optional
            //$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC("gmendoza@walworth.com.mx");
            //$mail->addBCC('ahuerta@walworth.com.mx');
            //$mail->addBCC('yvelazquez@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            $mail->addBCC('rcruz@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $datas = ["info" => $data];
            if ($option == 1) {
                $email_template = view('notificaciones/notify_valija', $datas);
                $title = 'Solicitud de Valija';
            } else {
                $email_template = view('notificaciones/notify_valija_respuesta', $datas);
                $title = 'Respuesta de Valija';
            }
            $mail->MsgHTML($email_template);  // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function pdfRequestValija($id_valija = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';


        $query = $this->db->query(
            "SELECT *
                                    FROM
                                    tbl_valija_request
                                    WHERE
                                    MD5(concat('" . $key . "',id_valija))='" . $id_valija . "'"
        );
        $dataValija =   $query->getRow();

        $data = ["request" => $dataValija];

        $html2 = view('pdf/pdf_valija', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Solicitud Valija');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('solicitudes_' . $id_valija . '.pdf', 'I');
    }
}
