<?php

/**
 * MODULO DE CAFETERIA
 * @version 1.1 pre-prod
 * @author Rafael Cruz Aguilar <rafael.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 * Archivo Generador de Repore
 */

namespace App\Controllers\Supplies;

use App\Controllers\BaseController;
use App\Models\vhModel;
use App\Models\vhOrdenesModel;
use App\Models\vhItemsModel;
use App\Models\vhNewsModel;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Supplies extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->vhModel = new vhModel();
        $this->vhNewsModel = new vhNewsModel();
        $this->vhOrdenesModel = new vhOrdenesModel();
        $this->vhItemsModel = new vhItemsModel();
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function view_request()
    {
        return ($this->is_logged) ? view('supplies/view_generate_order') : redirect()->to(site_url());
    }

    public function view_requestAll()
    {
        return ($this->is_logged) ? view('supplies/view_list_order') : redirect()->to(site_url());
    }

    public function searchItem()
    {
        try {
            /*id de la partida a buscar*/
            $num_item = trim($this->request->getPost('num_partida'));

            $result = $this->vhModel->searchItem($num_item);

            return (count($result) > 0) ? json_encode($result) : json_encode(false);
        } catch (\Throwable $th) {
            return ('Ha ocurrido un error en el servidor ' . $th);
        }
    }

    public function editPurchaseOrder(){
         /*id de la partida a buscar*/
         $id_request = trim($this->request->getPost('id_request'));
         $data = $this->vhOrdenesModel->where('id_request', $id_request)->find();
         return (count($data) > 0) ? json_encode($data) : json_encode(false);

    }

    public function updateOrder(){
        try {
            $id_request = $this->request->getPost('id_request');
            $orden_compra = trim($this->request->getPost('orden_compra'));
            $fecha_formalizacion = trim($this->request->getPost('fecha_formalizacion'));
            $fecha_estatus = trim($this->request->getPost('fecha_estatus'));
            
            $data = ["orden_compra" => $orden_compra,
                     "fecha_formalizacion" => $fecha_formalizacion,
                     "fecha_estatus_trabajo" => $fecha_estatus
                    ];
            $result =  $this->vhOrdenesModel->update($id_request, $data);

            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }

    }

    public function deleteOrder(){
        try {
            $id_request = $this->request->getPost('id_request');
                        
            $data = ["active_status" => 2];
            $result =  $this->vhOrdenesModel->update($id_request, $data);

            $result2 =$this->vhNewsModel->update($id_request,$data);

            return ($result2) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }

    }

    public function listItems()
    {
        try {
            /*id de la partida a buscar*/
            $num_items = trim($this->request->getPost('id_request'));
            $result = $this->vhItemsModel->searchItems($num_items);

            return (count($result) > 0) ? json_encode($result) : json_encode(false);
        } catch (\Throwable $th) {
            return ('Ha ocurrido un error en el servidor ' . $th);
        }
    }

    public function requestAll()
    {
        try {
            $result = $this->vhOrdenesModel->requestAll();

            return (count($result) > 0) ? json_encode($result) : json_encode(false);
        } catch (\Throwable $th) {
            return ('Ha ocurrido un error en el servidor ' . $th);
        }
    }

    public function saveRequest()
    {
        try {
            /*-RECIBIMOS PARAMETROS DE LA ORDEN DE COMPRA Y VARIABLES DE FECHA------------------------------------------------ */
            $user = session()->name . " " . session()->surname;
            $orden_compra = trim($this->request->getPost('orden_compra'));
            $num_partida = $this->request->getPost('num_partida');
            $tipo = $this->request->getPost('tipo');
            $diametro = $this->request->getPost('diametro');
            $clase = $this->request->getPost('clase');
            $tiempo = $this->request->getPost('tiempo');
            $desc = $this->request->getPost('desc');
            $desc_breve = $this->request->getPost('desc_breve');
            $figura = $this->request->getPost('figura');
            $num_piezas = $this->request->getPost('cantidad');

            /*-OPERACION PARA SACAR FECHA DE VENCIMIENTO POR ITEM  Y PODER TENER LA FECHA DE NOTIFICACION---------------------- */

            $date = date("Y-m-d");
            $create = date("Y-m-d H:i:s");

            /*-GUARDAMOS ORDEN DE COMPRA Y RECUPERAMOS EL ID DE LA INSERCION PARA INSERTAR LOS ITEMS CON EL ID---------------- */
            $data = [
                "id_usuario" => session()->id_user,
                "usuario" => $user,
                "orden_compra" => $orden_compra,
                "created_at" => $create
            ];

            $request = $this->vhOrdenesModel->insert($data);
            $id_request = $this->db->insertID();

            /*-FOR QUE GUARDA LOS ITEMS DE CADA ORDEN DE COMPRA ----------------------------------------------------------------*/
            for ($i = 0; $i < count($num_partida); $i++) {

                switch ($tiempo[$i]) {
                    case '90 DIAS':
                        $news1 = "+ 46 days";
                        $news2 = "+ 68 days";
                        $news3 = "+ 79 days";
                        $news4 = "+ 90 days";
                        break;
                    case '120 DIAS':
                        $news1 = "+ 61 days";
                        $news2 = "+ 91 days";
                        $news3 = "+ 111 days";
                        $news4 = "+ 120 days";
                        break;
                    case '150 DIAS':
                        $news1 = "+ 76 days";
                        $news2 = "+ 91 days";
                        $news3 = "+ 121 days";
                        $news4 = "+ 150 days";
                        break;
                    case '160 DIAS':
                        $news1 = "+ 61 days";
                        $news2 = "+ 91 days";
                        $news3 = "+ 111 days";
                        $news4 = "+ 120 days";
                        break;
                    case '180 DIAS':
                        $news1 = "+ 91 days";
                        $news2 = "+ 121 days";
                        $news3 = "+ 161 days";
                        $news4 = "+ 180 days";
                        break;
                }

                //Incrementando los dias dependiendo de la opcion
                $modifica_date1 = strtotime($date . $news1);
                $modifica_date2 = strtotime($date . $news2);
                $modifica_date3 = strtotime($date . $news3);
                $modifica_date4 = strtotime($date . $news4);

                $date_expiration1 = date("Y-m-d", $modifica_date1);
                $date_expiration2 = date("Y-m-d", $modifica_date2);
                $date_expiration3 = date("Y-m-d", $modifica_date3);
                $date_expiration4 = date("Y-m-d", $modifica_date4);


                $datas = [
                    "id_request" => $id_request,
                    "orden_compra" => $orden_compra,
                    "codigo" => $num_partida[$i],
                    "tipo" => $tipo[$i],
                    "diametro" => $diametro[$i],
                    "clase" => $clase[$i],
                    "tiempo" => $tiempo[$i],
                    "desc" => $desc[$i],
                    "desc_breve" => $desc_breve[$i],
                    "figura" => $figura[$i],
                    "num_piezas" => $num_piezas[$i],
                    "created_at" => $create,
                    "fecha_entrega" => $date_expiration4
                ];
                $this->vhItemsModel->insert($datas);
                $id_item = $this->db->insertID();

                $datas1 = ["id_request" => $id_request, "fecha_notifica" => $date_expiration1, "id_item" => $id_item, "num_notificacion" => 1];
                $datas2 = ["id_request" => $id_request, "fecha_notifica" => $date_expiration2, "id_item" => $id_item, "num_notificacion" => 2];
                $datas3 = ["id_request" => $id_request, "fecha_notifica" => $date_expiration3, "id_item" => $id_item, "num_notificacion" => 3];
                $datas4 = ["id_request" => $id_request, "fecha_notifica" => $date_expiration4, "id_item" => $id_item, "num_notificacion" => 4];

                $this->vhNewsModel->insert($datas1);
                $this->vhNewsModel->insert($datas2);
                $this->vhNewsModel->insert($datas3);
                $this->vhNewsModel->insert($datas4);
            }



            //$this->notifyRequest($data, $datas);

            return ($request) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function closeLineItem()
    {
        try {
            $id_item = $this->request->getPost('id_item');
            $fecha_cierre = trim($this->request->getPost('fecha_cierre'));
            $observacion = $this->request->getPost('observacion');

            $data = [
                "fecha_real_entrega" => $fecha_cierre,
                "observaciones" => $observacion,
                "active_status" => 2
            ];

            $result =  $this->vhItemsModel->update($id_item, $data);

            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function notifyRequest()
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
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            $mail->Username = 'requisiciones@walworth.com.mx';
            // SMTP password (This is that emails' password (The email you created earlier) )
            $mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 587;
            //Recipients
            $mail->setFrom('notificacion@grupowalworth.com', 'Genera Requisición');

            // Add a recipient
            $mail->addAddress('cmorales@walworth.com.mx', 'Cecilia Morales');
            $mail->addAddress('hgarcia@walworth.com.mx', 'Humberto Garcia');
            $mail->addAddress('pgomez@walworth.com.mx', 'Patricia Gomez');
            $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');


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
            $email_template = view('notificaciones/vh_suministros_request', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Notificacion Requisición';
            // $mail->send();
            // return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function pdfRequest($id_contract = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $Request = $this->db->query("SELECT * FROM tbl_vh_ordenes_compras WHERE MD5(concat('$key',id_request)) = '$id_contract' ")->getRow();
        $Items = $this->db->query("SELECT * FROM tbl_vh_ordenes_items WHERE id_request = $Request->id_request ")->getResult();
        $data = ["solicitud" => $Request, "valvulas" => $Items, ];

        $html2 = view('pdf/pdf_order_supplies', $data);
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'USLETTER', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Contrato');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('Orden_Suministros.pdf', 'I');
    }

    public function reportExcel(){

        try{

        $ordenes = $this->request->getPost('data');
         // aqui
         $cont = 2;
        // $data = json_decode(stripslashes($this->request->getPost('data')));
       //echo $ordenes;
        $query = $this->db->query("SELECT a.orden_compra,b.codigo,b.desc_breve,b.tipo,b.diametro,b.clase,b.num_piezas,b.tiempo, DATEDIFF(b.fecha_entrega, now())AS dias_restantes,
                                          DATEDIFF(now(),b.fecha_entrega)AS dias_atraso,a.fecha_formalizacion,a.fecha_estatus_trabajo
                                    FROM tbl_vh_ordenes_compras AS a
                                    INNER JOIN tbl_vh_ordenes_items AS b
                                    ON a.id_request = b.id_request
                                    WHERE a.id_request IN ($ordenes)");
         $reporte = $query->getResult();
        // return json_encode($array);

         $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:L1');
            $sheet->getStyle("A1:L1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("A1:L1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle('A1:L1')->getBorders()->getTop()->setColor($color);
            $sheet->getStyle('A1:L1')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

                $NombreArchivo = "permisos.xlsx";
                
                $sheet->setTitle("ordenes_suministros");

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
                /* 
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(50);
                $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true); */

                $sheet->setCellValue('A1', 'ORDEN DE COMPRA');
                $sheet->setCellValue('B1', 'FECHA FORMALIZACION ORDEN');
                $sheet->setCellValue('C1', 'FECHA ESTATUS DE TRABAJO');
                $sheet->setCellValue('D1', 'CODIGO');
                $sheet->setCellValue('E1', 'DESCRIPCION BREVE');
                $sheet->setCellValue('F1', 'TIPO');
                $sheet->setCellValue('G1', 'DIAMETRO');
                $sheet->setCellValue('H1', 'CLASE');
                $sheet->setCellValue('I1', 'NUM PIEZAS');
                $sheet->setCellValue('J1', 'TIEMPO');
                $sheet->setCellValue('K1', 'DIAS RESTANTES');
                $sheet->setCellValue('L1', 'DIAS DE RETRASO');
                /* 
                
                $sheet->setCellValue('M1', 'INASISTENCIA DEL');
                $sheet->setCellValue('N1', 'INASISTENCIA AL');
                $sheet->setCellValue('O1', 'GOCE SUELDO');
                $sheet->setCellValue('P1', 'OBSERVACIONES');
                $sheet->setCellValue('Q1', 'ESTATUS'); */

                foreach ($reporte as $key => $value) {

/* ($cont % 2 == 0)
                        ? $spreadsheet->getActiveSheet()->getStyle('A' . $cont . ':P' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8CCE4')
                        : $spreadsheet->getActiveSheet()->getStyle('A' . $cont . ':P' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9E1F2');
                    $tipo_permiso = ($value->tipo_permiso == null) ? "NO DEFINIDO" : $value->tipo_permiso; */

                    $codigo=strval($value->codigo);
                    $sheet->setCellValue('A' . $cont, $value->orden_compra);
                    $sheet->setCellValue('B' . $cont, $value->fecha_formalizacion)->getStyle('B'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('C' . $cont, $value->fecha_estatus_trabajo)->getStyle('C'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('D' . $cont, $codigo)->getStyle('D'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('E' . $cont, $value->desc_breve);
                    $sheet->setCellValue('F' . $cont, $value->tipo);
                    $sheet->setCellValue('G' . $cont, $value->diametro)->getStyle('G'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('H' . $cont, $value->clase)->getStyle('H'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('I' . $cont, $value->num_piezas)->getStyle('I'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER );
                    $sheet->setCellValue('J' . $cont, $value->tiempo)->getStyle('J'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER );
                    $sheet->setCellValue('K' . $cont, $value->dias_restantes)->getStyle('K'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('L' . $cont, $value->dias_atraso)->getStyle('L'. $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                /*     
                    $sheet->setCellValue('M' . $cont, $value->inasistencia_del);
                    $sheet->setCellValue('N' . $cont, $value->inasistencia_al);
                    $sheet->setCellValue('O' . $cont, $value->goce_sueldo);
                    $sheet->setCellValue('P' . $cont, $value->observaciones);
                    $sheet->setCellValue('Q' . $cont, $value->estatus); */
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
            }catch (Exception $e) {
                echo $e->ErrorInfo;
            }
    }
}
