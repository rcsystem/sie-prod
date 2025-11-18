<?php

/**
 * MODULO DE AUTOMOVILES
 * @version 1.1 pre-prod
 * @author Horus Samael Rivas Pedraza <horus.riv.ped@gmail.com>
 * @telefono 56-2439-2632
 */

namespace App\Controllers\Cars;

use App\Controllers\BaseController;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\CarsVehiculesModel;
use App\Models\CarsRequestsModel;
use App\Models\CarsTripShortModel;
use App\Models\CarsTripExtendedModel;
use App\Models\CarsNotificationModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cars extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->vehiculeModel = new CarsVehiculesModel();
        $this->requestModel = new CarsRequestsModel();
        $this->shortModel = new CarsTripShortModel();
        $this->extendedtModel = new CarsTripExtendedModel();
        $this->notificaModel = new CarsNotificationModel();
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function viewAutorize()
    {
        return ($this->is_logged) ? view('cars/view_cars_autorize') : redirect()->to(site_url());
    }

    public function viewVehicle()
    {
        return ($this->is_logged) ? view('cars/view_admin_vehicles') : redirect()->to(site_url());
    }

    public function viewCreateRequest()
    {
        return ($this->is_logged) ? view('cars/view_create') : redirect()->to(site_url());
    }

    public function viewMyRequest()
    {
        return ($this->is_logged) ? view('cars/view_my_request') : redirect()->to(site_url());
    }
    public function viewRequestAll()
    {
        $builder = $this->vehiculeModel->select('*')
            ->where('deleted_at', null)
            ->orderBy('id_car', 'DESC')
            ->limit(1500);
        $dataCars = $builder->get()->getResult();

        $data = ["carros" => $dataCars];

        return ($this->is_logged) ? view('cars/view_cars_request_all', $data) : redirect()->to(site_url());
    }

    public function carsALL()
    {
        $builder = $this->vehiculeModel->select('*')
            ->where('deleted_at', null)
            ->orderBy('id_car', 'DESC')
            ->limit(1500);
        $data = $builder->get()->getResult();

        return json_encode($data);
    }

    public function authorizeAll()
    {
        try {
            $query = $this->db->query("SELECT * FROM tbl_cars_request LIMIT 1500");
            $data = $query->getResult();
            return json_encode($data);
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e;
        }
    }

    public function ALLRequest()
    {
        $id_user = session()->id_user;
        $query = $this->db->query("SELECT * FROM tbl_cars_request WHERE payroll_number IN(
            SELECT payroll_number FROM tbl_stationery_permissions WHERE id_manager = $id_user ) LIMIT 1500");
        $data = $query->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode(false);
    }

    /* public function typeTrip()
    {
        $id_request = trim($this->request->getPost('id_request'));
        $type_trip = trim($this->request->getPost('type_trip'));
        if($type_trip == 1){
            $builder = $this->shortModel->select('*')
            ->where('id_request', $id_request);
        }
        else if($type_trip == 2){
            $builder = $this->extendedtModel->select('*')
            ->where('id_request', $id_request);
        }
        $data = $builder->get()->getResult();
        return ($data) ? json_encode($data) : json_encode(false);


    } */

    public function insetCar()
    {
        $date = date("Y-m-d H:i:s");
        $binder =  '../public/images/carros';
        $model = trim($this->request->getPost('modelo'));
        $placa = trim($this->request->getPost('placas'));


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
        $dataInsertCar = [
            "model" => $model,
            "placa" => $placa,
            "imagen" => $path,
            "created_at" => $date
        ];
        $insertCar = $this->vehiculeModel->insert($dataInsertCar);
        return ($insertCar) ? json_encode(true) : json_encode(false);
    }

    public function dataCarsID()
    {
        $id = trim($this->request->getPost('id_car'));
        $query = $this->db->query("SELECT * FROM tbl_cars_vehicles WHERE id_car = $id")->getRow();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function dataCarsInfo()
    {
        $query = $this->db->query("SELECT * From tbl_cars_vehicles WHERE deleted_at IS NULL")->getResult();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function deleteCar()
    {
        $date = date("Y-m-d H:i:s");
        $id = trim($this->request->getPost('id'));

        $borrarC = ["deleted_at" => $date,];
        $deleteCar = $this->vehiculeModel->update($id, $borrarC);

        return ($deleteCar) ? json_encode(true) : json_encode(false);
    }

    public function insertRequest()
    {
        $type_trip = trim($this->request->getPost('type_trip'));
        //$id_car = trim($this->request->getPost('id_car'));
        $motive = trim($this->request->getPost('motive'));
        if ($type_trip == 1) {
            $date = trim($this->request->getPost('date'));
            $star_time = trim($this->request->getPost('star_time'));
            $end_time = trim($this->request->getPost('end_time'));
        } elseif ($type_trip == 2) {
            $star_date = trim($this->request->getPost('star_date'));
            $star_datetime = trim($this->request->getPost('star_datetime'));
            $end_date = trim($this->request->getPost('end_date'));
            $end_datetime = trim($this->request->getPost('end_datetime'));
        }
        $date_day = date("Y-m-d H:i:s");
        $user = session()->name . " " . session()->surname;
        $data_request = [
            "id_user" => session()->id_user,
            "payroll_number" => session()->payroll_number,
            "name" => $user,
            "id_depto" => session()->id_depto,
            "depto" => session()->departament,
            'area_operativa' => session()->cost_center,
            "position_job" => session()->job_position,
            "type_trip" => $type_trip,
            "motive" => $motive,
            'status' => 1,
            'active_status' => 1,
            "created_at" => $date_day
        ];
        $insertData =  $this->requestModel->insert($data_request);

        $id_request = $this->db->insertID();
        if ($insertData) {

            if ($type_trip == 1) {
                $date_insert = [
                    "id_request" => $id_request,
                    //"id_car" => $id_car,
                    "date" => $date,
                    "star_time" => $star_time,
                    "end_time" => $end_time
                ];
                $this->shortModel->insert($date_insert);
            }

            if ($type_trip == 2) {
                $date_insert = [
                    "id_request" => $id_request,
                    //"id_car" => $id_car,
                    "star_date" => $star_date,
                    "star_datetime" => $star_datetime,
                    "end_date" => $end_date,
                    "end_datetime" => $end_datetime
                ];

                $this->extendedtModel->insert($date_insert);
            }
        }
        $payroll_number = session()->payroll_number;
        $dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user IN 
                (SELECT id_manager FROM tbl_stationery_permissions WHERE payroll_number = $payroll_number)")->getRow();
        $email = changeEmail($dataEmail->email);
        $title = $dataEmail->name . " " . $dataEmail->surname;
        $this->emailNotify($email, $title, $id_request);
        return ($insertData) ? json_encode(true) : json_encode(false);
    }

    public function authorize()
    {
        $id_trip = "";
        $id_request = trim($this->request->getPost('id_folio'));
        $status = trim($this->request->getPost('estado'));
        $id_cars = trim($this->request->getPost('id_cars'));
        $observation = trim($this->request->getPost('observacion'));
        $type_trip = trim($this->request->getPost('tipo'));
        $date_day = date("Y-m-d H:i:s");

        if ($type_trip == 1) {
            $builder = $this->shortModel->select('*')
                ->where('id_request', $id_request);
        } else if ($type_trip == 2) {
            $builder = $this->extendedtModel->select('*')
                ->where('id_request', $id_request);
        }
        $dataTrip = $builder->get()->getRow();
        if ($type_trip == 1) {
            $id_trip = $dataTrip->id_trip_sh;
        } else if ($type_trip == 2) {
            $id_trip = $dataTrip->id_trip_ex;
        }

        $data_request = [
            "status" => $status,
            "id_cars" => $id_cars,
            "observation" => $observation,
            'date_answer' => $date_day,
            'id_answer' => session()->id_user,
        ];
        $upData =  $this->requestModel->update($id_request, $data_request);

        if ($upData && $status == 4) {
            $data_trip = ["authorized" => 1];
            if ($type_trip == 1) {
                $this->shortModel->update($id_trip, $data_trip);
            } else if ($type_trip == 2) {
                $this->extendedtModel->update($id_trip, $data_trip);
            }
        } else if ($upData && $status == 3) {
            $data_trip = ["authorized" => 0];
            if ($type_trip == 1) {
                $this->shortModel->update($id_trip, $data_trip);
            } else if ($type_trip == 2) {
                $this->extendedtModel->update($id_trip, $data_trip);
            }
        }
        if ($status == 2) {
            $email = "olopez@walworth.com.mx";
            $title = "OSCAR DAVID LOPEZ ";
            $this->emailNotify($email, $title, $id_request);
        } else if ($status == 3 || $status == 4) {
            $dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user IN (SELECT id_user FROM tbl_cars_request WHERE id_request = $id_request)")->getRow();
            $email = $dataEmail->email;
            $title = $dataEmail->name . " " . $dataEmail->surname;
            $this->emailNotify($email, $title, $id_request);
        }
        return ($upData) ? json_encode(true) : json_encode(false);
    }

    function authorizeManagement()
    {
        $id_request = trim($this->request->getPost('id_folio'));
        $status = trim($this->request->getPost('estado'));
        $type_trip = trim($this->request->getPost('tipo'));

        $data_request = [
            "status" => $status,
            "date_autorize" => date("Y-m-d H:i:s"),
            'id_authoriza' => session()->id_user,
        ];
        $this->requestModel->update($id_request, $data_request);
    }

    public function dataCars()
    {
        $id_request = $this->request->getPost('id');
        $query = $this->db->query("SELECT model, placa FROM tbl_cars_vehicles WHERE id_car IN (SELECT id_cars FROM tbl_cars_request WHERE id_request = $id_request)")->getRow();
        return json_encode($query);
    }

    public function myRequest()
    {
        $id_user = session()->id_user;
        $data = $this->db->query("SELECT a.*, b.model, b.placa
        FROM tbl_cars_request AS a
        LEFT JOIN tbl_cars_vehicles AS b ON a.id_cars = b.id_car
       WHERE a.active_status = 1
       AND a.id_user = $id_user
       ORDER BY a.id_request DESC
       LIMIT 1500")->getResult();
        /* SELECT a.*, 
CASE WHEN a.id_cars IS NULL THEN "SIN ASIGNAR"  ELSE b.model END AS model ,
CASE WHEN a.id_cars IS NULL THEN "SIN ASIGNAR" ELSE b.placa END AS placa
 FROM tbl_cars_request AS a
JOIN tbl_cars_vehicles AS b 
WHERE a.active_status = 1
AND a.id_user = 1063
ORDER BY a.id_request DESC
LIMIT 1500 */
        return (count($data) > 0) ? json_encode($data) : json_encode(false);
    }
    public function pdfRequestCars($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_cars_request
                                        WHERE
                                        MD5(concat('" . $key . "',id_request))='" . $id_request . "'");
        $dataRequest =  $query->getRow();

        $dataCars = ["imagen" => "", "placa" => "", "modelo" => ""];

        if ($dataRequest->status == 4) {

            $queryCars = $this->db->query("SELECT *
            FROM
            tbl_cars_vehicles
            WHERE
            id_car=$dataRequest->id_cars");
            $dataCars =  $queryCars->getRow();
        }

        if ($dataRequest->type_trip == 1) {
            $query2 = $this->db->query("SELECT *
                                    FROM
                                    tbl_cars_short_trip
                                    WHERE
                                    id_request = $dataRequest->id_request
                                    ");
            $dataTrip =  $query2->getRow();
        }
        if ($dataRequest->type_trip == 2) {
            $query2 = $this->db->query("SELECT *
                                    FROM
                                    tbl_cars_extended_trip
                                    WHERE
                                    id_request = $dataRequest->id_request
                                    ");
            $dataTrip =  $query2->getRow();
        }


        $data = [
            "request" => $dataRequest,
            "trip" => $dataTrip,
            "cars" => $dataCars
        ];
        $html2 = view('pdf/pdf_cars', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Solicitudes');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('solicitudes_' . $id_request . '.pdf', 'I');
    }

    public function emailNotify($email = null, $user = null, $id = null)
    {
        $query = $this->db->query("SELECT * FROM tbl_cars_request WHERE id_request = $id")->getResult();

        if ($query[0]->type_trip == 1) {
            $query0 = $this->db->query("SELECT * FROM tbl_cars_short_trip WHERE id_request = $id")->getResult();
        } else {
            $query0 = $this->db->query("SELECT * FROM tbl_cars_extended_trip WHERE id_request = $id")->getResult();
        }
        $id_car = $query[0]->id_cars;
        if ($id_car != null) {
            $query1 = $this->db->query("SELECT * FROM tbl_cars_vehicles WHERE id_car = $id_car")->getRow();
        } else {
            $query1 = "";
        }

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {
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

            //Recipients
            if ($query[0]->status == 2) {
                $mail->setFrom('notificacion@walworth.com', 'Solicitud|Vehiculo|Authorizado');
                $mail->addAddress('gmendoza@walworth.com.mx', 'Gerardo Mendoza');

                $mail->addBCC('gberriozabal@walworth.com.mx');
            } else if ($query[0]->status == 3) {
                $mail->setFrom('notificacion@walworth.com', 'Solicitud|Vehiculo|Rechazado');
            } elseif ($query[0]->status == 4) {
                $mail->setFrom('notificacion@walworth.com', 'Solicitud|Vehiculo|Respuesta');
                $mail->addAddress('gmendoza@walworth.com.mx');
                $mail->addBCC('gberriozabal@walworth.com.mx');
            } else {
                $mail->setFrom('notificacion@walworth.com', 'Solicitud|Vehiculo');
            }
            // Add a recipient
            $mail->addAddress($email, $user);
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            $mail->isHTML(true);
            $data = ["datas" => $query, "trip" => $query0, "cars" => $query1];
            $email_template = view('notificaciones/cars', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Solicitud de Vehiculo';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function CarsXlsx()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $NombreArchivo = "info_vehiculos_.xlsx";
        if ($data->type == 1) {
            $where = "";
        } else {
            $where = "AND a.id_cars = $data->id_car";
        }
        $reporte = $this->db->query("SELECT a.payroll_number, a.`name`, a.depto, a.position_job, a.motive, a.observation, b.model, b.placa,
            CASE  WHEN a.`status` = 1 THEN 'PENDIENTE' WHEN a.`status` = 2 THEN 'AUTORIZADA' WHEN a.`status` = 3 THEN 'RECHAZADA'WHEN a.`status` = 4 THEN 'COMPLETADA' ELSE 'ERROR' END AS estado,
            CASE  WHEN a.type_trip = 1 THEN s.date  WHEN a.type_trip = 2 THEN e.star_date ELSE 'ERROR' END AS fecha_inicio,
            CASE  WHEN a.type_trip = 1 THEN s.star_time WHEN a.type_trip = 2 THEN e.star_datetime ELSE 'ERROR' END AS hora_inicio,
            CASE  WHEN a.type_trip = 1 THEN s.date WHEN a.type_trip = 2 THEN e.end_date ELSE 'ERROR' END AS fecha_final,
            CASE  WHEN a.type_trip = 1 THEN s.end_time WHEN a.type_trip = 2 THEN e.end_datetime ELSE 'ERROR' END AS hora_final

            FROM tbl_cars_request AS a JOIN tbl_cars_vehicles AS b ON b.id_car = a.id_cars
            LEFT JOIN tbl_cars_short_trip AS s ON s.id_request = a.id_request LEFT JOIN tbl_cars_extended_trip AS e ON e.id_request = a.id_request
        WHERE a.created_at BETWEEN '$data->star_date' AND '$data->end_date' $where")->getResult();

        $cont = 2;
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
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:M1');;
        $sheet->setTitle("Reporte Vehiculos");
        $sheet->getStyle("A1:M1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

        $sheet->getStyle("A1:M1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(12)
            ->getColor()
            ->setRGB('FFFFFF');

        $sheet->setCellValue('A1', 'NOMINA');
        $sheet->setCellValue('B1', 'NOMBRE SOLICITANTE');
        $sheet->setCellValue('C1', 'DEPARTAMENTO');
        $sheet->setCellValue('D1', 'PUESTO');
        $sheet->setCellValue('E1', 'MODELO');
        $sheet->setCellValue('F1', 'PLACAS');
        $sheet->setCellValue('G1', 'FECHA_SALIDA');
        $sheet->setCellValue('H1', 'FECHA_REGRESO');
        $sheet->setCellValue('I1', 'HORA_SALIDA');
        $sheet->setCellValue('J1', 'HORA_REGRESO');
        $sheet->setCellValue('K1', 'ESTADO');
        $sheet->setCellValue('L1', 'OBSERVACIONES');
        $sheet->setCellValue('M1', 'MOTIVO');

        foreach ($reporte as $key => $value) {
            $sheet->setCellValue('A' . $cont, $value->payroll_number);
            $sheet->setCellValue('B' . $cont, $value->name);
            $sheet->setCellValue('C' . $cont, $value->depto);
            $sheet->setCellValue('D' . $cont, $value->position_job);
            $sheet->setCellValue('E' . $cont, $value->model);
            $sheet->setCellValue('F' . $cont, $value->placa);
            $sheet->setCellValue('G' . $cont, date("d/m/Y", strtotime($value->fecha_inicio)));
            $sheet->setCellValue('H' . $cont, date("d/m/Y", strtotime($value->fecha_final)));
            $sheet->setCellValue('I' . $cont, date("H:i:s", strtotime($value->hora_inicio)));
            $sheet->setCellValue('J' . $cont, date("H:i:s", strtotime($value->hora_final)));
            $sheet->setCellValue('K' . $cont, $value->estado);
            $sheet->setCellValue('L' . $cont, $value->motive);
            $sheet->setCellValue('M' . $cont, $value->observation);
            $cont++;
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
}
