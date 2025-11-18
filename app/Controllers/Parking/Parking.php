<?php

/**
 * MODULO DE ESTACIONAMIENTO
 * @version 1.1 pre-prod
 * @author  Horus Samael Rivas Pedraza <horus.riv.ped@gmail.com>
 * @telefono 56-24-39-26-32
 */

namespace App\Controllers\Parking;

use DateTime;
use App\Controllers\BaseController;
use App\Models\ParkingRequestModel;
use App\Models\ParkingUsersItemModel;
use App\Models\ParkingUsersMotionModel;

use App\Models\ParkingUsersModel;
use App\Models\ParkingUsersBicycleModel;
use App\Models\ParkingUsersN1Model;
use App\Models\ParkingUsersGardenModel;
use App\Models\ParkingUsersMotorcycleModel;
use App\Models\ParkingUsersN3Model;

use Spipu\Html2Pdf\Html2Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Margin\Margin;
// use Endroid\QrCode\Encoding\Encoding;
// use Endroid\QrCode\Logo\Logo;
// use Endroid\QrCode\Writer\ValidationException;

class Parking extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->requestModel = new ParkingRequestModel();
        $this->userItemsModel = new ParkingUsersItemModel();
        $this->usersModel = new ParkingUsersModel();
        $this->usersBicycleModel = new ParkingUsersBicycleModel();
        $this->usersN1Model = new ParkingUsersN1Model();
        $this->usersGardenModel = new ParkingUsersGardenModel();
        $this->usersMotorcycleModel = new ParkingUsersMotorcycleModel();
        $this->usersN3Model = new ParkingUsersN3Model();
        $this->usersMotionModel = new ParkingUsersMotionModel();
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function inOutControl()
    {
        return ($this->is_logged) ? view('parking/control_in_out') : redirect()->to(site_url());
    }

    public function adminUsers()
    {
        /* if (session()->id_user == 1063) {
            return ($this->is_logged) ? view('parking/create_record_D') : redirect()->to(site_url());
        } */
        return ($this->is_logged) ? view('parking/create_record') : redirect()->to(site_url());
    }

    public function viewReports()
    {
        return ($this->is_logged) ? view('parking/reports') : redirect()->to(site_url());
    }

    public function viewMovementsVehicles()
    {
        return ($this->is_logged) ? view('parking/movements_vehicles') : redirect()->to(site_url());
    }

    public function viewRegisterMyVehicle()
    {
        $idUser = session()->id_user;
        $query = $this->db->query("SELECT ext FROM tbl_parking_users WHERE id_user = $idUser AND active_status= 1
                UNION
            SELECT ext FROM tbl_parking_users_motorcycle WHERE id_user = $idUser AND active_status= 1
                UNION
            SELECT ext FROM tbl_parking_users_bicycle WHERE id_user = $idUser AND active_status= 1
                UNION
            SELECT ext FROM tbl_parking_users_N3 WHERE id_user = $idUser AND active_status= 1 
                UNION
            SELECT ext FROM tbl_parking_users_garden WHERE id_user = $idUser AND active_status= 1
                UNION
            SELECT ext FROM tbl_parking_users_N1 WHERE id_user = $idUser AND active_status= 1")->getRow();
        $data = ['ext' => $query->ext ?? null];
        return ($this->is_logged) ? view('parking/register_my_vehicles', $data) : redirect()->to(site_url());
    }

    public function viewAssignmentDrawer()
    {
        return ($this->is_logged) ? view('parking/assignment_drawer') : redirect()->to(site_url());
    }

    public function recordALL()
    {
        $datas = $this->db->query("SELECT a.id_record, CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS nombre, b.payroll_number, c.departament, a.ext, a.qr_location, a.created_at
        FROM tbl_parking_users AS a JOIN tbl_users AS b ON a.id_user = b.id_user JOIN cat_departament AS c ON c.id_depto = a.id_depto WHERE a.active_status = 1")->getResult();
        return ($datas) ? json_encode($datas) : json_encode(false);
    }
    public function recordALLH($type = null)
    {
        $tbls = [
            1 => 'tbl_parking_users', 3 => 'tbl_parking_users_bicycle', 6 => 'tbl_parking_users_N1',
            5 => 'tbl_parking_users_garden', 2 => 'tbl_parking_users_motorcycle', 4 => 'tbl_parking_users_N3'
        ];
        $sqlTbl = $tbls[$type];
        $datas = $this->db->query("SELECT a.id_record, num_tag, b.payroll_number, c.departament, a.ext, a.qr_location, a.created_at,
        CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS nombre
        FROM $sqlTbl AS a JOIN tbl_users AS b ON a.id_user = b.id_user JOIN cat_departament AS c ON c.id_depto = a.id_depto WHERE a.active_status = 1")->getResult();
        return ($datas) ? json_encode($datas) : json_encode(false);
    }

    function listTags()
    {
        $type = $this->request->getPost("tbl");
        $tbls = [
            1 => 'tbl_parking_users', 3 => 'tbl_parking_users_bicycle', 6 => 'tbl_parking_users_N1',
            5 => 'tbl_parking_users_garden', 2 => 'tbl_parking_users_motorcycle', 4 => 'tbl_parking_users_N3'
        ];
        $sqlTbl = $tbls[$type];
        $data = $this->db->query("SELECT DISTINCT num_tag
            FROM $sqlTbl
            WHERE active_status = 2
            AND num_tag NOT IN (
                SELECT num_tag
                FROM $sqlTbl
                WHERE active_status = 1
            )
        ")->getResult();
        return json_encode($data);
    }

    /* public function recordData()
    {
        $idRecord = $this->request->getPost('id_record');
        $dataPersonal = $this->db->query("SELECT CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS nombre, b.payroll_number, c.departament, a.ext        
        FROM tbl_parking_users AS a JOIN tbl_users AS b ON a.id_user = b.id_user JOIN cat_departament AS c ON c.id_depto = a.id_depto WHERE a.id_record = $idRecord")->getRow();
        $dataVehicule = $this->db->query("SELECT id_item, type_vehicle, model, color, placas, active_status, date_expiration, location_archive 
        FROM tbl_parking_users_items WHERE id_record = $idRecord AND active_status = 1 AND status_authorize = 2")->getResult();
        $data = ['personal' => $dataPersonal, 'vehicule' => $dataVehicule];
        return ($dataPersonal && $dataVehicule) ? json_encode($data) : json_encode(false);
    } */

    public function recordData()
    {
        $tbls = [
            1 => 'tbl_parking_users', 3 => 'tbl_parking_users_bicycle', 6 => 'tbl_parking_users_N1',
            5 => 'tbl_parking_users_garden', 2 => 'tbl_parking_users_motorcycle', 4 => 'tbl_parking_users_N3'
        ];
        $idRecord = $this->request->getPost('id_record');
        $type = $this->request->getPost('tipo');
        $sqlTbl = $tbls[$type];
        $dataPersonal = $this->db->query("SELECT a.id_user, CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS nombre, b.payroll_number, c.departament, a.ext        
        FROM $sqlTbl AS a JOIN tbl_users AS b ON a.id_user = b.id_user JOIN cat_departament AS c ON c.id_depto = a.id_depto WHERE a.id_record = $idRecord")->getRow();

        $dataVehicule = $this->db->query("SELECT id_item, type_vehicle, model, color, placas, active_status, date_expiration, location_archive 
        FROM tbl_parking_users_items WHERE id_record = $idRecord AND type_vehicle = $type AND active_status = 1 AND status_authorize = 2")->getResult();

        $data = ['personal' => $dataPersonal, 'vehicule' => $dataVehicule];

        return json_encode($data);
    }

    /*  public function pdfRequestMedical($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT * FROM tbl_medical_request WHERE
            MD5(concat('$key',id_request))='$id_request'")->getRow();
        $data = ["request" => $query,];
        $html2 = view('pdf/pdf_medical_disability', $data);
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Incapasidad Médica');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('Consulta_Medica_' . $id_request . '.pdf', 'I');
    } */

    public function xlsxRequest()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $starDate = $data->date_star;
        $endDate = date('Y-m-d', strtotime($data->date_end . ' +1 day'));
        $option = $data->option;
        $typeVehicule = [1 => 'Automoviles', 2 => 'Motocicletas', 3 => 'Bicicletas'];

        if ($option < 4) {
            $NombreArchivo = "Reporte_" . $starDate . "_" . $endDate . ".xlsx";
            $query = $this->db->query("SELECT a.id_request, a.id_record, d.`name`, c.departament, a.location, b.model, b.placas, b.color, a.date_in, a.date_out
                FROM tbl_parking_request  AS a
                    JOIN tbl_parking_users_items AS b ON a.id_item = b.id_item
                    JOIN cat_departament AS c ON a.id_depto = c.id_depto
                    JOIN tbl_parking_users AS d ON a.id_record = d.id_record
                WHERE a.created_at BETWEEN '$starDate' AND '$endDate'
                    AND a.active_status = 1 AND b.type_vehicle = $option")->getResult();

            $cont = 2;
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:M1');
            $sheet->setTitle('Estacionamiento ' . $typeVehicule[$option]);


            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

            // ANCHO DE CELDA
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(11);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(14);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(11);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(55);

            //UBICACION DEL TEXTO
            $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet->getStyle('A1:M1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet->getStyle("A1:M1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet->setCellValue('A1', 'FOLIO');
            $sheet->setCellValue('B1', 'MARBETE');
            $sheet->setCellValue('C1', 'NOMBRE EMPLEADO');
            $sheet->setCellValue('D1', 'DEPARTAMENTO');
            $sheet->setCellValue('E1', 'CAJON');
            $sheet->setCellValue('F1', 'MODELO');
            $sheet->setCellValue('G1', 'PLACAS');
            $sheet->setCellValue('H1', 'COLOR');
            $sheet->setCellValue('I1', 'DIA ENTRADA');
            $sheet->setCellValue('J1', 'HORA ENTRADA');
            $sheet->setCellValue('K1', 'DIA SALIDA');
            $sheet->setCellValue('L1', 'HORA SALIDA');
            $sheet->setCellValue('M1', 'OBSERVACION');

            foreach ($query as $value) {
                $dateOut = ($value->date_out != null) ? date("d/m/Y", strtotime($value->date_out)) : 'NO DEFINIDA';
                $timeOut = ($value->date_out != null) ? date("H:i A", strtotime($value->date_out)) : 'NO DEFINIDA';
                $dateStar = ($value->date_in != '0000-00-00') ? date("d/m/Y", strtotime($value->date_in)) : 'NO DEFINIDA';
                $timeStar = ($value->date_in != '0000-00-00') ? date("H:i A", strtotime($value->date_in)) : 'NO DEFINIDA';

                $sheet->setCellValue('A' . $cont, $value->id_request);
                $sheet->setCellValue('B' . $cont, $value->id_record);
                $sheet->setCellValue('C' . $cont, $value->name);
                $sheet->setCellValue('D' . $cont, $value->departament);
                $sheet->setCellValue('E' . $cont, $value->location ?? 'NO DEFINIDO');
                $sheet->setCellValue('F' . $cont, strtoupper($value->model));
                $sheet->setCellValue('G' . $cont, strval($value->placas) ?? '');
                $sheet->setCellValue('H' . $cont, strtoupper($value->color));
                $sheet->setCellValue('I' . $cont, $dateStar);
                $sheet->setCellValue('J' . $cont, $timeStar);
                $sheet->setCellValue('K' . $cont, $dateOut);
                $sheet->setCellValue('L' . $cont, $timeOut);
                $sheet->setCellValue('M' . $cont, $value->obs ?? '');
                $cont++;
            }
            $sheet->getStyle('A2:B' . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E2:E' . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
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

    public function generateQRCode()
    {
        try {
            $codesDir = '../public/images/qr';
            $idUserCreate = session()->id_user;
            $toDay = date('Y-m-d H:i:s');
            $idUser = $this->request->getPost('id_user');
            $name = $this->request->getPost('nombre');
            $idDepto = $this->request->getPost('id_depto');
            $depto = $this->request->getPost('depto');
            $ext = ($this->request->getPost('ext') != null) ? $this->request->getPost('ext') : 'Sin Informacion';

            $dataRecord = [
                'id_user' => $idUser,
                'name' => $name,
                'id_depto' => $idDepto,
                'ext' => $ext,
                'id_created' => $idUserCreate,
                'created_at' => $toDay,
            ];
            $this->db->transStart();
            $insertRecord = $this->usersModel->insert($dataRecord);
            $idRecord = $this->db->insertID();

            if ($insertRecord) {
                $writer = new PngWriter();
                // Create QR code
                $qrCode = QrCode::create('QRWalworthD2Fsd29ydGg' . $idRecord)
                    // ->setEncoding(new Encoding('UTF-8'))
                    ->setSize(400)
                    ->setMargin(5)
                    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                    ->setForegroundColor(new Color(0, 0, 0))
                    ->setBackgroundColor(new Color(255, 255, 255));

                // Crear una instancia de Label y establecer el tamaño de texto
                $label = Label::create($idRecord)
                    ->setTextColor(new Color(0.28, 0.30, 0.33))
                    ->setFont(new NotoSans(130))
                    ->setAlignment(new LabelAlignmentCenter())
                    ->setMargin(new Margin(15, 0, 15, 0));

                /* 
                // Create generic logo
                $logo = Logo::create('../public/images/QR-icon-180-modified.png')->setResizeToWidth(85)
                // Directly output the QR code
                header('Content-Type: ' . $result->getMimeType());
                // Generate a data URI to include image data inline (i.e. inside an <img> tag)
                $dataUri = $result->getDataUri();
                */

                // ->write(QR, LOGO,  TEXTO)
                $result = $writer->write($qrCode, null, $label);

                // Save it to a file
                $location = '/public/images/qr/qrcode_' . $idRecord . '.png';
                $result->saveToFile($codesDir . '/qrcode_' . $idRecord . '.png');

                $locationQR = ['qr_location' => $location];
                $this->usersModel->update($idRecord, $locationQR);
            }

            $archiveDir = '../public/doc/parking/targ_' . $idRecord . '_user_' . $idUser;
            if (!file_exists($archiveDir)) {
                mkdir($archiveDir, 0777, true);
            }

            $type = [1 => 'Automóvil', 2 => 'Motocicleta', 3 => 'Bicicleta'];

            $arrayTypeVehicle = $this->request->getPost('tipo_vehiculo_');
            $arrayModel = $this->request->getPost('modelo_');
            $arrayColor = $this->request->getPost('color_');
            $arrayPlates = $this->request->getPost('placas_');
            $arrayDateExpiration = $this->request->getPost('vencimiento_');
            $items = $this->request->getPost('items');
            $arrayItems = explode(',', $items);
            for ($i = 0; $i < count($arrayTypeVehicle); $i++) {

                $archive = $this->request->getFile('archivo_' . $arrayItems[$i]);
                $locationArchive = null;
                if ($archive) {
                    $newNameEs = "poliza_" . $type[$arrayTypeVehicle[$i]] . "_vencimiento_" . $arrayDateExpiration[$i];
                    $archive->move($archiveDir,  $newNameEs);
                    $locationArchive = $archiveDir . "/" . $newNameEs;
                }

                $itemData = [
                    'id_record' => $idRecord,
                    'type_vehicle' => $arrayTypeVehicle[$i],
                    'model' => $arrayModel[$i],
                    'color' => $arrayColor[$i],
                    'placas' => $arrayPlates[$i],
                    'date_expiration' => $arrayDateExpiration[$i],
                    'location_archive' => $locationArchive,
                    // 'record_type' => 1,
                    'status_authorize' => 2,
                    'id_created' => $idUserCreate,
                    'created_at' => $toDay,
                ];

                $insertItems = $this->userItemsModel->insert($itemData);

                /* $motionData = [
                    'id_item' => $idItem,
                    'motion' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->usersMotionModel->insert($motionData); */
            }

            $data = ['id' => $idRecord, 'location' => $location];
            $result = $this->db->transComplete();
            return ($result) ? json_encode($data) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode(false);
        }
    }

    public function generateQRCodeH()
    {
        try {
            $typeVehicle = $this->request->getPost('tipo_vehiculo');
            $idUser = $this->request->getPost('id_user');
            $tbls = [
                1 => 'tbl_parking_users', 3 => 'tbl_parking_users_bicycle', 6 => 'tbl_parking_users_N1',
                5 => 'tbl_parking_users_garden', 2 => 'tbl_parking_users_motorcycle', 4 => 'tbl_parking_users_N3'
            ];
            $tbl_ = $tbls[$typeVehicle];

            $query = $this->db->query("SELECT id_record FROM $tbl_ WHERE active_status = 1 AND id_user = $idUser")->getRow();
            if ($query) {
                return json_encode("existente");
            }
            $codesDir = '../public/images/qr';
            $fileQr = [1 => '', 2 => '/Motorcycle', 3 => '/Bicycle', 4 => '/N3', 5 => '/Garden', 6 => '/N1'];
            $tbl = [
                1 => $this->usersModel, 2 => $this->usersMotorcycleModel, 3 => $this->usersBicycleModel,
                4 => $this->usersN3Model, 5 => $this->usersGardenModel, 6 => $this->usersN1Model
            ];
            $idUserCreate = session()->id_user;
            $toDay = date('Y-m-d H:i:s');
            $name = $this->request->getPost('nombre');
            $idDepto = $this->request->getPost('id_depto');
            $depto = $this->request->getPost('depto');
            $typeTag = $this->request->getPost('tipo_marbete') ?? 1;
            $noTag = $this->request->getPost('no_marbete');
            $ext = ($this->request->getPost('ext') != null) ? $this->request->getPost('ext') : 'Sin Informacion';


            $dataRecord = [
                'id_user' => $idUser,
                'name' => $name,
                'id_depto' => $idDepto,
                'ext' => $ext,
                'obs' => $depto,
                'id_created' => $idUserCreate,
                'created_at' => $toDay,
            ];
            $this->db->transStart();

            $insertRecord = $tbl[$typeVehicle]->insert($dataRecord);
            $idRecord = $this->db->insertID();

            if ($insertRecord) {
                // var_dump($typeTag);
                if ($typeTag == 2) {
                    $data =  $this->db->query("SELECT qr_location FROM $tbl_ WHERE num_tag = $noTag;")->getRow();
                    $location = $data->qr_location;
                    $locationQR = ['qr_location' => $location, 'num_tag' => $noTag];
                    $tbl[$typeVehicle]->update($idRecord, $locationQR);
                    $tagNumber = $noTag;
                } else if ($typeTag == 1) {
                    $writer = new PngWriter();
                    // Create QR code
                    $qrCode = QrCode::create('QRWalworthD2Fsd29ydGg' . $fileQr[$typeVehicle] . $idRecord)
                        // ->setEncoding(new Encoding('UTF-8'))
                        ->setSize(400)
                        ->setMargin(5)
                        ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                        ->setForegroundColor(new Color(0, 0, 0))
                        ->setBackgroundColor(new Color(255, 255, 255));

                    // Crear una instancia de Label y establecer el tamaño de texto
                    $label = Label::create($idRecord)
                        ->setTextColor(new Color(0.28, 0.30, 0.33))
                        ->setFont(new NotoSans(130))
                        ->setAlignment(new LabelAlignmentCenter())
                        ->setMargin(new Margin(15, 0, 15, 0));

                    /* 
                // Create generic logo
                $logo = Logo::create('../public/images/QR-icon-180-modified.png')->setResizeToWidth(85)
                // Directly output the QR code
                header('Content-Type: ' . $result->getMimeType());
                // Generate a data URI to include image data inline (i.e. inside an <img> tag)
                $dataUri = $result->getDataUri();
                */

                    // ->write(QR, LOGO,  TEXTO)
                    $result = $writer->write($qrCode, null, $label);

                    // Save it to a file
                    $location = '/public/images/qr' . $fileQr[$typeVehicle] . '/qrcode_' . $idRecord . '.png';
                    $result->saveToFile($codesDir . $fileQr[$typeVehicle] . '/qrcode_' . $idRecord . '.png');

                    $locationQR = ['qr_location' => $location, 'num_tag' => $idRecord];
                    $tagNumber = $idRecord;
                    $tbl[$typeVehicle]->update($idRecord, $locationQR);
                }
            }


            $archiveDir = '../public/doc/parking' . $fileQr[$typeVehicle] . '/targ_' .  $idRecord . '_user_' . $idUser;
            if (!file_exists($archiveDir)) {
                mkdir($archiveDir, 0777, true);
            }

            $type = [1 => 'Automóvil', 2 => 'Motocicleta', 3 => 'Bicicleta', 4 => 'Automóvil_N3', 5 => 'Automóvil_Jardin', 6 => 'Automóvil_N1'];

            $arrayModel = $this->request->getPost('modelo_');
            $arrayColor = $this->request->getPost('color_');
            $arrayPlates = $this->request->getPost('placas_');
            $arrayDateExpiration = $this->request->getPost('vencimiento_');
            $items = $this->request->getPost('items');
            $arrayItems = explode(',', $items);
            for ($i = 0; $i < count($arrayItems); $i++) {

                $archive = $this->request->getFile('archivo_' . $arrayItems[$i]);
                $locationArchive = null;
                if ($archive) {
                    $newNameEs = "poliza_" . $type[$typeVehicle] . "_vencimiento_" . $arrayDateExpiration[$i];
                    $archive->move($archiveDir,  $newNameEs);
                    $locationArchive = $archiveDir . "/" . $newNameEs;
                }

                $itemData = [
                    'id_record' => $idRecord,
                    'num_tag' => $tagNumber,
                    'type_vehicle' => $typeVehicle,
                    'model' => $arrayModel[$i],
                    'color' => $arrayColor[$i],
                    'placas' => $arrayPlates[$i],
                    'date_expiration' => $arrayDateExpiration[$i],
                    'location_archive' => $locationArchive,
                    'id_user' => $idUser,
                    'id_depto' => $idDepto,
                    // 'record_type' => 1,
                    'status_authorize' => 2,
                    'id_created' => $idUserCreate,
                    'created_at' => $toDay,
                ];

                $insertItems = $this->userItemsModel->insert($itemData);

                /* $motionData = [
                    'id_item' => $idItem,
                    'motion' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->usersMotionModel->insert($motionData); */
            }

            $data = ['id' => $idRecord, 'location' => $location];
            $result = $this->db->transComplete();
            return ($result) ? json_encode($data) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function generateNewVehiculeItem()
    {
        try {
            $idUserCreate = session()->id_user;
            $toDay = date("Y-d-m H:i:s");
            $idUser = $this->request->getPost('id_user_modal');
            $typeVehicle = $this->request->getPost('tipo_tbl');
            $idRecord = $this->request->getPost('id_record');

            $fileQr = [1 => '', 2 => '/Motorcycle', 3 => '/Bicycle', 4 => '/N3', 5 => '/Garden', 6 => '/N1'];
            $type = [1 => 'Automóvil', 2 => 'Motocicleta', 3 => 'Bicicleta', 4 => 'Automóvil_N3', 5 => 'Automóvil_Jardin', 6 => 'Automóvil_N1'];

            $archiveDir = '../public/doc/parking' . $fileQr[$typeVehicle] . '/targ_' .  $idRecord . '_user_' . $idUser;
            if (!file_exists($archiveDir)) {
                mkdir($archiveDir, 0777, true);
            }

            $arrayModel = $this->request->getPost('modelo_modal_');
            $arrayColor = $this->request->getPost('color_modal_');
            $arrayPlates = $this->request->getPost('placas_modal_');
            $arrayDateExpiration = $this->request->getPost('vencimiento_modal_');
            $items = $this->request->getPost('items_modal');
            $arrayItems = explode(',', $items);

            $this->db->transStart();
            for ($i = 0; $i < count($arrayItems); $i++) {
                $archive = $this->request->getFile('archivo_modal_' . $arrayItems[$i]);
                $locationArchive = null;
                if ($archive) {
                    $newNameEs = "poliza_" . $type[$typeVehicle] . "_vencimiento_" . $arrayDateExpiration[$i];
                    $archive->move($archiveDir,  $newNameEs);
                    $locationArchive = $archiveDir . "/" . $newNameEs;
                }

                $itemData = [
                    'id_record' => $idRecord,
                    'type_vehicle' => $typeVehicle,
                    'model' => $arrayModel[$i],
                    'color' => $arrayColor[$i],
                    'placas' => $arrayPlates[$i],
                    'date_expiration' => $arrayDateExpiration[$i],
                    'location_archive' => $locationArchive,
                    // 'record_type' => 1,
                    'status_authorize' => 2,
                    'id_created' => $idUserCreate,
                    'created_at' => $toDay,
                ];
                $this->userItemsModel->insert($itemData);
            }
            $result = $this->db->transComplete();

            return json_encode($result);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function userDataForRegister()
    {
        $payrollNumber = $this->request->getPost('nomina');
        $data = $this->db->query("SELECT a.id_user, a.payroll_number AS nomina,
            CONCAT(a.`name`,' ',a.surname,' ',a.second_surname) AS nombre,
            b.departament AS departamento, a.id_departament
        FROM tbl_users AS a
            LEFT JOIN cat_departament AS b ON a.id_departament = b.id_depto
        LEFT JOIN cat_job_position AS c ON a.id_job_position = c.id
        WHERE a.payroll_number = $payrollNumber
            AND a.active_status = 1")->getRow();
        return json_encode($data);
    }

    public function dataTag()
    {
        $type = $this->request->getPost('tipo');
        $tag = $this->request->getPost('marberte');
        $data = $this->db->query("SELECT a.id_item, UPPER(a.model) as model, UPPER(a.color) AS color, UPPER(a.placas) AS placas, b.`name`
            FROM tbl_parking_users_items AS a
                JOIN tbl_parking_users AS b ON a.id_record = b.id_record
            WHERE a.active_status = 1 
                AND a.type_vehicle = $type
        
        AND a.num_tag = $tag")->getResult();
        return json_encode($data);
    }

    public function recordInputOutput($intOut)
    {
        $toDay = date("Y-m-d H:i:s");

        if ($intOut == 1) {
            $numTag = $this->request->getPost("num_marbete");
            $idItem = $this->request->getPost("item_vehiculo");
            $type = $this->request->getPost("tipo");
            $validation = $this->db->query("SELECT id_request FROM tbl_parking_request
            WHERE active_status = 1 AND id_date_out IS NULL
            AND num_tag = $numTag AND id_item = $idItem")->getRow();

            if ($validation) {
                return json_encode('registroExistente');
            }
            
            $query = $this->db->query("SELECT a.id_user, a.id_departament, 
                (SELECT b.departament FROM cat_departament AS b WHERE a.id_departament = b.id_depto) AS departament
                FROM tbl_users AS a WHERE a.id_user IN 
            (SELECT id_user FROM tbl_parking_users_items WHERE id_item = $idItem)")->getRow();

            $insertInData = [
                'type_vehicle' => $type,
                'num_tag' => $numTag,
                'id_item' => $idItem,
                /* 'location' => $location, */
                'id_user' => $query->id_user,
                'id_depto' => $query->id_departament,
                'departament' => $query->departament,
                'date_in' => $toDay,
                'id_date_in' => session()->id_user,
                'created_at' => $toDay,
            ];
            $insert = $this->requestModel->insert($insertInData);
            $result = ($insert) ? true : false;
        }
        if ($intOut == 2) {
            $typeOut = $this->request->getPost("tipo_salida");
            $numTag = $this->request->getPost("num_marbete_salida");
            $idItem = $this->request->getPost("item_vehiculo_salida");
            $obs = $this->request->getPost("obs_salida");
            $insertOutData = [
                'date_out' => $toDay,
                'id_date_out' => session()->id_user,
                'obs' => $obs,
            ];
            $query = $this->db->query("SELECT id_request FROM tbl_parking_request 
            WHERE active_status = 1 AND id_date_out IS NULL
            AND num_tag = $numTag AND id_item = $idItem AND type_vehicle = $typeOut")->getRow();
            if (empty($query)) {
                return json_encode('noRegistro');
            }
            $result = $this->requestModel->update($query->id_request, $insertOutData);
        }
        return json_encode($result);
    }

    public function myVehicles()
    {
        $idUser = session()->id_user;
        $query = $this->db->query("SELECT a.id_item, a.id_record, a.type_vehicle, a.model, a.color, a.placas, a.date_expiration,
            CASE
                WHEN a.type_vehicle = 1 THEN t1.qr_location
                WHEN a.type_vehicle = 2 THEN t2.qr_location
                WHEN a.type_vehicle = 3 THEN t3.qr_location
                WHEN a.type_vehicle = 4 THEN t4.qr_location
                WHEN a.type_vehicle = 5 THEN t5.qr_location
                WHEN a.type_vehicle = 6 THEN t6.qr_location
            END AS qr_location
            FROM tbl_parking_users_items AS a
                LEFT JOIN tbl_parking_users AS t1 ON a.id_record = t1.id_record
                LEFT JOIN tbl_parking_users_motorcycle AS t2 ON a.id_record = t2.id_record
                LEFT JOIN tbl_parking_users_bicycle AS t3 ON a.id_record = t3.id_record
                LEFT JOIN tbl_parking_users_N3 AS t4 ON a.id_record = t4.id_record
                LEFT JOIN tbl_parking_users_garden AS t5 ON a.id_record = t5.id_record
                LEFT JOIN tbl_parking_users_N1 AS t6 ON a.id_record = t6.id_record
            WHERE a.active_status = 1
                AND a.status_authorize = 2
                AND a.id_user = $idUser")->getResult();
        return json_encode($query);
    }

    public function generateRegisterByUser()
    {
        try {
            $idUser = session()->id_user;
            $ext = $this->request->getPost('ext');
            $toDay = date('Y-m-d H:i:s');

            $query = $this->db->query("SELECT id_record FROM tbl_parking_users WHERE id_user = $idUser AND active_status= 1")->getRow();
            $this->db->transStart();
            if ($query === null) {
                $codesDir = '../public/images/qr';
                $name = session()->name . ' ' . session()->surname;
                $idDepto = session()->id_depto;

                $dataRecord = [
                    'id_user' => $idUser,
                    'name' => $name,
                    'id_depto' => $idDepto,
                    'ext' => $ext,
                    'id_created' => session()->id_user,
                    'created_at' => $toDay,
                ];

                $insertRecord = $this->usersModel->insert($dataRecord);
                $idRecord = $this->db->insertID();

                if ($insertRecord) {
                    $writer = new PngWriter();
                    // Create QR code
                    $qrCode = QrCode::create('QRWalworthD2Fsd29ydGg' . $idRecord)
                        // ->setEncoding(new Encoding('UTF-8'))
                        ->setSize(400)
                        ->setMargin(5)
                        ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                        ->setForegroundColor(new Color(0, 0, 0))
                        ->setBackgroundColor(new Color(255, 255, 255));

                    // Crear una instancia de Label y establecer el tamaño de texto
                    $label = Label::create($idRecord)
                        ->setTextColor(new Color(0.28, 0.30, 0.33))
                        ->setFont(new NotoSans(130))
                        ->setAlignment(new LabelAlignmentCenter())
                        ->setMargin(new Margin(15, 0, 15, 0));


                    // ->write(QR, LOGO,  TEXTO)
                    $result = $writer->write($qrCode, null, $label);

                    // Save it to a file
                    $location = '/public/images/qr/qrcode_' . $idRecord . '.png';
                    $result->saveToFile($codesDir . '/qrcode_' . $idRecord . '.png');

                    $locationQR = ['qr_location' => $location];
                    $this->usersModel->update($idRecord, $locationQR);
                }
            } else {
                $idRecord = $query->id_record;
            }

            $archiveDir = '../public/doc/parking/targ_' . $idRecord . '_user_' . $idUser;
            if (!file_exists($archiveDir)) {
                mkdir($archiveDir, 0777, true);
            }
            $type = [1 => 'Automóvil', 2 => 'Motocicleta', 3 => 'Bicicleta'];

            $arrayTypeVehicle = $this->request->getPost('tipo_vehiculo_');
            $arrayModel = $this->request->getPost('modelo_');
            $arrayColor = $this->request->getPost('color_');
            $arrayPlates = $this->request->getPost('placas_');
            $arrayDateExpiration = $this->request->getPost('vencimiento_');
            $items = $this->request->getPost('items');
            $arrayItems = explode(',', $items);
            $arrayIdItems = [];
            for ($i = 0; $i < count($arrayTypeVehicle); $i++) {

                $archive = $this->request->getFile('archivo_' . $arrayItems[$i]);
                $locationArchive = null;
                if ($archive) {
                    $newNameEs = "poliza_" . $type[$arrayTypeVehicle[$i]] . "_vencimiento_" . $arrayDateExpiration[$i];
                    $archive->move($archiveDir,  $newNameEs);
                    $locationArchive = $archiveDir . "/" . $newNameEs;
                }

                $itemData = [
                    'id_record' => $idRecord,
                    'type_vehicle' => $arrayTypeVehicle[$i],
                    'model' => $arrayModel[$i],
                    'color' => $arrayColor[$i],
                    'placas' => $arrayPlates[$i],
                    'date_expiration' => $arrayDateExpiration[$i],
                    'location_archive' => $locationArchive,
                    // 'record_type' => 2,
                    'status_authorize' => 1,
                    'id_created' => $idUser,
                    'created_at' => $toDay,
                ];
                $this->userItemsModel->insert($itemData);

                $idItem = $this->db->insertID();
                array_push($arrayIdItems, $idItem);
                $motionData = [
                    'id_item' => $idItem,
                    'motion' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->usersMotionModel->insert($motionData);
            }
            $this->emailNotify(1, $arrayIdItems);

            $data = ['id' => $idRecord, 'ext' => $ext];
            $result = $this->db->transComplete();
            return ($result) ? json_encode($data) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function deleteItem($tbl = null)
    {
        $idItem = $this->request->getPost("id_item");
        $query = $this->db->query("UPDATE tbl_parking_users_items SET active_status = 2 WHERE id_item = $idItem");
        $motionData = [
            'id_item' => $idItem,
            'motion' => 2,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->usersMotionModel->insert($motionData);
        if ($tbl != null) {
            $this->emailNotify(2, $idItem);
        }
        return json_encode($query);
    }

    public function deleteRegister($type = null)
    {
        /* if (session()->id_user != 1063) {
            return false;
        } */
        $idRquest = $this->request->getPost("id_folio");
        $tbls = [
            1 => 'tbl_parking_users', 3 => 'tbl_parking_users_bicycle', 6 => 'tbl_parking_users_N1',
            5 => 'tbl_parking_users_garden', 2 => 'tbl_parking_users_motorcycle', 4 => 'tbl_parking_users_N3'
        ];
        $sqlTbl = $tbls[$type];
        $idUser = session()->id_user;
        $toDay = date("Y-m-d H:i:s");

        $this->db->transStart();
        $this->db->query("UPDATE $sqlTbl SET active_status = 2, id_delet = $idUser, deleted_at = '$toDay' WHERE id_record = $idRquest");
        $this->db->query("UPDATE tbl_parking_users_items 
            SET active_status = 2, id_deleted = $idUser, deleted_at = '$toDay'
        WHERE id_record = $idRquest AND type_vehicle = $type");
        $result = $this->db->transComplete();

        return json_encode($result);
    }

    public function deleteItems()
    {
        if (session()->id_user != 1063) {
            return false;
        }
        $idItem = $this->request->getPost("id_item");
        $idUser = session()->id_user;
        $toDay = date("Y-m-d H:i:s");

        $this->db->transStart();
        $this->db->query("UPDATE tbl_parking_users_items 
            SET active_status = 2, id_deleted = $idUser, deleted_at = '$toDay'
        WHERE id_item = $idItem");
        $result = $this->db->transComplete();

        return json_encode($result);
    }

    public function dataMovementsVehicles()
    {
        $query = $this->db->query("SELECT a.id_item, b.id_record, a.type_vehicle, a.status_authorize, b.`name`, m.motion,
            CASE
                WHEN a.placas = '' THEN CONCAT(a.model,' | ', a.color)
                ELSE CONCAT(a.model,' | ', a.color,' | ',a.placas)
            END AS datos_vehicule,
            CASE
                WHEN  m.motion = 2 THEN 'BAJA DE VEHÍCULO'
                WHEN  m.motion = 3 THEN 'ACTUALIZACIÓN PÓLIZA'
                ELSE 'REGISTRO DE VEHÍCULO'
            END AS type_movem,
            CASE
                WHEN m.motion = 1 THEN a.created_at
                WHEN  m.motion = 3 THEN m.created_at
                ELSE a.deleted_at
            END AS date_motion
        FROM tbl_parking_users_motion AS m
            JOIN tbl_parking_users_items AS a ON m.id_item = a.id_item
            JOIN tbl_parking_users AS b ON a.id_record = b.id_record
        -- WHERE a.record_type = 2
        ORDER BY m.id DESC
        LIMIT 1000")->getResult();
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function updateStatusAuthorization()
    {
        $statusAuthorize = $this->request->getPost('status_authorize');
        $idItem = $this->request->getPost('id_item');
        $itemData = [
            'status_authorize' => $statusAuthorize,
        ];
        $updateItems = $this->userItemsModel->update($idItem, $itemData);
        return json_encode($updateItems);
    }

    public function emailNotify($type = null, $id = null)
    {
        if ($type == 1) {
            $arrayId = implode(", ", $id);
            $query = $this->db->query("SELECT a.type_vehicle, a.model, a.color, a.placas, b.`name`,
            CONCAT('registrado un nuevo Vehículo.') AS type_movem
            FROM tbl_parking_users_items AS a
                JOIN tbl_parking_users AS b ON a.id_record = b.id_record
            WHERE a.active_status = 1 AND a.id_item IN ($arrayId) ")->getResult();
        } else {
            $query = $this->db->query("SELECT a.type_vehicle, a.model, a.color, a.placas, b.`name`,
            CONCAT('dado de baja un Vehículo.') AS type_movem
            FROM tbl_parking_users_items AS a
                JOIN tbl_parking_users AS b ON a.id_record = b.id_record
            WHERE a.id_item = $id")->getRow();
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
            $mail->Username = 'requisiciones@grupowalworth.com';
            $mail->Password = '2contodo';
            $mail->Port = 587;

            //Recipients
            $titulo = ($type == 1) ? 'Estacionamiento | Nuevo Registro' : 'Estacionamiento | Baja de Vehículo';
            $mail->setFrom('notificacion@grupowalworth.com', $titulo);
            $mail->addAddress('ldominguez@walworth.com.mx', 'LUIS ANGEL DOMINGUEZ');
            // Add a recipient
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');
            $mail->isHTML(true);
            $data = ["item" => $query, 'type' => $type];
            $email_template = view('notificaciones/notify_vehicule_movement', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Solicitud de Vehiculo';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function dataVehiclesDrawer()
    {
        $data = $this->db->query("SELECT id_request, CONCAT(CASE 
            WHEN type_vehicle = 1 THEN 'AUTO'
            WHEN type_vehicle = 2 THEN 'MOTO'
            WHEN type_vehicle = 3 THEN 'BICI'
            END, ' || ',num_tag) AS id_record, `location`
            FROM tbl_parking_request
            WHERE id_date_out IS NULL
                AND created_at = CURDATE()
                AND type_vehicle IN (1,2,3)
        ORDER BY `location` ASC")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function assingDrawer()
    {
        $idRequest = $this->request->getPost('id_request');
        $location = $this->request->getPost('location');
        $updateData = ['location' => $location];
        $result = $this->requestModel->update($idRequest, $updateData);
        return json_encode($result);
    }

    public function qrLocation()
    {
        $idRequest = $this->request->getPost('id_request');
        $query = $this->db->query("SELECT qr_location AS Qr, `name`
        FROM tbl_parking_users WHERE id_record = $idRequest")->getRow();
        return json_encode($query);
    }

    public function updateArchiveExpiration()
    {
        $idUser = session()->id_user;
        $type = [1 => 'Automóvil', 2 => 'Motocicleta', 3 => 'Bicicleta'];
        $idItem = $this->request->getPost('id_item');

        $query = $this->db->query("SELECT id_record, type_vehicle FROM tbl_parking_users_items
        WHERE id_item = $idItem ")->getRow();
        $idRecord = $query->id_record;

        $archiveDir = '../public/doc/parking/targ_' . $idRecord . '_user_' . $idUser;
        if (!file_exists($archiveDir)) {
            mkdir($archiveDir, 0777, true);
        }
        $newDateExpiration = $this->request->getPost('vencimiento_modal');

        $archive = $this->request->getFile('archivo_modal');

        $newNameEs = "poliza_" . $type[$query->type_vehicle] . "_vencimiento_" . $newDateExpiration;
        $archive->move($archiveDir,  $newNameEs);
        $newLocationArchive = $archiveDir . "/" . $newNameEs;

        $this->db->transStart();
        $itemData = [
            'date_expiration' => $newDateExpiration,
            'location_archive' => $newLocationArchive,
        ];

        $this->userItemsModel->UPDATE($idItem, $itemData);

        $motionData = [
            'id_item' => $idItem,
            'motion' => 3,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->usersMotionModel->insert($motionData);

        $result = $this->db->transComplete();
        return json_encode($result);
    }
}
// 484 -> extencion -> lunes
    // raimundo hernandez  