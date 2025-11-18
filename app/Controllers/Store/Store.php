<?php

/**
 * ARCHIVO MODULO MATERIA PRIMA
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

namespace App\Controllers\Store;

use App\Controllers\BaseController;

use App\Models\StoreRawMaterialModel;
use App\Models\StoreVouchersModel;
use App\Models\StoreItemsVouchersModel;
use App\Models\StoreFacturasAlmacenModel;
use App\Models\UserModel;
use App\Models\AlmacenFacturasModel;
use App\Models\AlmacenFacturasItemsModel;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use CodeIgniter\I18n\Time;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Incluye la biblioteca del generador de códigos de barras
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\Types\TypeCode128;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;

class Store extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';

        $this->materialModel = new StoreRawMaterialModel();
        $this->vouchersModel = new StoreVouchersModel();
        $this->itemsVouchersModel = new StoreItemsVouchersModel();
        $this->userModel = new UserModel();

        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function scanner()
    {
        return ($this->is_logged) ?  view('scanner/view_scanner') : redirect()->to(site_url());
    }

    public function viewRequests()
    {
        return ($this->is_logged) ?  view('store/view_solicitudes_almacen') : redirect()->to(site_url());
    }

    public function listOfCodes()
    {
        return ($this->is_logged) ?  view('store/listOfCodes') : redirect()->to(site_url());
    }

    public function Departures()
    {

        return ($this->is_logged) ?  view('store/departures') : redirect()->to(site_url());
    }
    public function viewTransfers()
    {
        return ($this->is_logged) ?  view('store/transfers') : redirect()->to(site_url());
    }

    public function viewAuthorizeTransfers()
    {
        return ($this->is_logged) ?  view('store/authorizeTransfers') : redirect()->to(site_url());
    }

    public function viewReports()
    {
        return ($this->is_logged) ?  view('store/view_reports') : redirect()->to(site_url());
    }

    public function viewVales()
    {
        return ($this->is_logged) ?  view('store/view_vales') : redirect()->to(site_url());
    }

    public function tblFacturasAlmacen()
    {
        try {

            $solcitudesAlmacen = new almacenFacturasModel();

            $data = $solcitudesAlmacen->where('active_status', 1)->findAll();

            return ($data > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

  public function solicitudFactura()
{
    try {
        // === 1️⃣ Datos del usuario desde la sesión ===
        $idUsuario    = session()->id_user;
        $nombreUsuario = trim(session()->name . ' ' . session()->surname);
        $departamento  = session()->departament;

        // === 2️⃣ Datos del formulario ===
        $observaciones = trim($this->request->getPost('obsv_factura'));
        $concepto      = trim($this->request->getPost('concepto'));
        $fechaActual   = date('Y-m-d H:i:s');

        // === 3️⃣ Insert principal ===
        $dataSolicitud = [
            'id_user'        => $idUsuario,
            'usuario'        => $nombreUsuario,
            'departamento'   => $departamento,
            'obsv_factura'   => $observaciones,
            'solicitud'      => $concepto,
            'created_at'     => $fechaActual,
            'active_status'  => 1,
            'estatus_activo' => 1,
        ];

        $solicitudesModel = new almacenFacturasModel();
        $solicitudesModel->insert($dataSolicitud);
        $idInsertado = $solicitudesModel->getInsertID();

        if (!$idInsertado) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo guardar la solicitud.']);
        }

        // === 4️⃣ Crear carpeta destino ===
        $rutaDestino = FCPATH . "Almacen/Solicitudes/folio_{$idInsertado}/";

        if (!is_dir($rutaDestino)) {
            mkdir($rutaDestino, 0777, true);
        }

        // === 5️⃣ Procesar archivos subidos ===
      $archivos = $this->request->getFileMultiple('files');

      $solicitudesItemsModel = new almacenFacturasItemsModel();

        if ($archivos && is_array($archivos)) {
            foreach ($archivos as $archivo) {
                if ($archivo->isValid() && !$archivo->hasMoved()) {

                    // Limpieza del nombre original
                    $nombreLimpio = preg_replace('/\s+/', '_', $archivo->getName());
                    $nombreLimpio = preg_replace('/[^A-Za-z0-9._-]/', '', $nombreLimpio);

                    // Mover archivo al destino final
                    $archivo->move($rutaDestino, $nombreLimpio);

                    // Ruta relativa (para BD / acceso web)
                    $rutaRelativa = "Almacen/Solicitudes/folio_{$idInsertado}/" . $nombreLimpio;

                    // Insertar registro en tabla de archivos
                    $solicitudesItemsModel->insert([
                        'id_request'   => $idInsertado,
                        'ruta_archivo' => $rutaRelativa,
                        'created_at'   => $fechaActual,
                    ]);
                }
            }
        }

        // === 6️⃣ Respuesta final ===
        return $this->response->setJSON([
            'status'   => 'ok',
            'message'  => 'Solicitud registrada correctamente',
            'id'       => $idInsertado,
            'archivos' => count($archivos ?? []),
        ]);

    } catch (\Throwable $e) {
        log_message('error', '[ERROR solicitudFactura] ' . $e->getMessage());
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Ha ocurrido un error en el servidor.',
            'error'   => $e->getMessage(),
        ]);
    }
}

public function obtenerArchivos($idSolicitud)
{
    $archivosModel = new AlmacenFacturasItemsModel();

    $archivos = $archivosModel
        ->where('id_request', $idSolicitud)
        ->findAll();

    return $this->response->setJSON($archivos);
}




public function firmar_pdf()
{
    require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
    require_once APPPATH . 'Libraries/FPDI/src/autoload.php';
    $pdfRutaRelativa = $this->request->getPost('pdf');
    $firma = $this->request->getFile('firma');

    if (!$firma->isValid() || !$pdfRutaRelativa) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Datos inválidos.']);
    }

    $pdfRutaCompleta = FCPATH . $pdfRutaRelativa;
    $firmaTemp = $firma->getTempName();

    $pdf = new Fpdi();
    $pageCount = $pdf->setSourceFile($pdfRutaCompleta);

    for ($i = 1; $i <= $pageCount; $i++) {
        $tpl = $pdf->importPage($i);
        $pdf->addPage();
        $pdf->useTemplate($tpl, 0, 0, 210);
        if ($i === $pageCount) {
            $pdf->Image($firmaTemp, 150, 250, 40, 20);
        }
    }

    $nombreFirmado = uniqid("firmado_") . ".pdf";
    $rutaSalida = FCPATH . "Almacen/Solicitudes/firmados/" . $nombreFirmado;
    if (!is_dir(dirname($rutaSalida))) mkdir(dirname($rutaSalida), 0777, true);

    $pdf->Output('F', $rutaSalida);

    return $this->response->setJSON([
        'status'  => 'ok',
        'archivo' => base_url("Almacen/Solicitudes/firmados/" . $nombreFirmado)
    ]);
}







    public function authorizeTransfers()
    {
        try {
            $id_folio = trim($this->request->getPost('id_folio'));
            $date = date("Y-m-d H:i:s");
            $data = [
                'estatus' => 2,
                'id_user_authorize' => session()->id_user,
                'authorize_datetime' => $date
            ];
            $builder = $this->db->table('tbl_store_vouchers');
            $builder->where('id_vouchers', $id_folio);
            $result = $builder->update($data);

            $users = session()->name . " " . session()->surname;

            $builder = $this->db->table('tbl_store_vouchers');
            $builder->select('addressee,created_at,departures');
            $builder->where('id_vouchers', $id_folio);
            $datas = $builder->get()->getResult();

            foreach ($datas as $key => $val) {
                $dataItem = [
                    "user" => $users,
                    "addressee" => $val->addressee,
                    "created_at" => $val->created_at,
                    "departures" => $val->departures,
                    "id_vouchers" => $id_folio
                ];

                $this->NotificationVigilancia($dataItem);
            }



            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function toRefuseTransfers()
    {
        try {
            $id_folio = trim($this->request->getPost('id_folio'));

            $data = [
                'estatus' => 3,
                'id_user_authorize' => session()->id_user
            ];
            $builder = $this->db->table('tbl_store_vouchers');
            $builder->where('id_vouchers', $id_folio);
            $result = $builder->update($data);
            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }



    public function Search()
    {
        try {
            $id_codigo = trim($this->request->getPost('id_codigo'));

            // Consulta para obtener datos del material con el código especificado
            $builder = $this->db->table('tbl_store_raw_material');
            $builder->select('id_mp, code, description, unit_of_measure, code_image');
            $builder->where('code', $id_codigo);
            $builder->where('active_status', 1);
            $data = $builder->get()->getRow();  // Cambiado a getRow para obtener solo un resultado

            // Generar código de barras si no existe la imagen
            if ($data && empty($data->code_image)) {
                $generator = new BarcodeGeneratorPNG();
                $barcodeDir = "../public/uploads/bar_codes/";

                // Crear el directorio si no existe
                if (!is_dir($barcodeDir)) {
                    mkdir($barcodeDir, 0777, true);
                }

                $barcode = $generator->getBarcode($id_codigo, $generator::TYPE_CODE_128);
                $filePath = $barcodeDir . "$id_codigo.png";

                file_put_contents($filePath, $barcode);

                // Actualizar la ruta de la imagen en la base de datos
                $data_code = ['code_image' => $filePath];
                $this->materialModel->update($data->id_mp, $data_code);

                // Actualizar el campo code_image en el objeto $data
                $data->code_image = $filePath;
            }

            // Devolver datos en formato JSON
            return $data ? json_encode($data) : json_encode("error");
        } catch (\Exception $e) {
            return json_encode(['error' => 'Ha ocurrido un error en el servidor', 'details' => $e->getMessage()]);
        }
    }

    public function crearCodigoBarras()
    {
        try {


            // Generar código de barras si no existe la imagen

            $generator = new BarcodeGeneratorPNG();
            $barcodeDir = "../public/";

            // Crear el directorio si no existe
            if (!is_dir($barcodeDir)) {
                mkdir($barcodeDir, 0777, true);
            }

            $codigo_barras = 'UNIF6324';

            $barcode = $generator->getBarcode($codigo_barras, $generator::TYPE_CODE_128);
            $filePath = $barcodeDir . "cb_$codigo_barras.png";

            file_put_contents($filePath, $barcode);


            echo $filePath;
        } catch (\Exception $e) {
            return json_encode(['error' => 'Ha ocurrido un error en el servidor', 'details' => $e->getMessage()]);
        }
    }




    public function saveRawMaterial()
    {
        try {
            $store_num_nomina = trim($this->request->getPost('epp_num_nomina'));
            $store_usuario = trim($this->request->getPost('epp_usuario'));
            $store_depto = trim($this->request->getPost('epp_depto'));
            $store_puesto = trim($this->request->getPost('epp_puesto'));
            $store_centro_costo = trim($this->request->getPost('epp_centro_costo'));
            $store_id_user = trim($this->request->getPost('epp_id_user'));


            $type_tranfer = trim($this->request->getPost('options'));
            $addressee = trim($this->request->getPost('destinatario'));
            $departures = trim($this->request->getPost('tranferir'));
            $departures = trim($this->request->getPost('herramientas'));
            $date = date("Y-m-d H:i:s");


            $caracteres = '0123456789';
            $longitud = 5;
            $codigo = '';
            for ($i = 0; $i < $longitud; $i++) {
                $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
            }


            $data = [
                "id_user" => session()->id_user,
                "addressee" => $addressee,
                "departures" => $departures,
                "created_at" => $date,
                'type_transfer' => $type_tranfer,
                "payroll_number" => $store_num_nomina,
                "user" => $store_usuario,
                "departament" => $store_depto,
                "job_position" => $store_puesto,
                "cost_center" => $store_centro_costo,
                "id_user_asign" => $store_id_user,
                "pw_security" => $codigo
            ];



            $insertData = $this->vouchersModel->insert($data);
            $id_vouchers = $this->db->insertID();

            if ($insertData) {
                $code = $this->request->getPost('codigo');
                $description = $this->request->getPost('articulo');
                $amount = $this->request->getPost('cantidad');
                // $weight = $this->request->getPost('peso');
                $observation = $this->request->getPost('observacion');


                $generator = new BarcodeGeneratorPNG();

                // Crear directorio antes de guardar archivos
                $barcodeDir = '../public/uploads/barcodes_transferencias/transferencia_' . $id_vouchers;
                if (!is_dir($barcodeDir)) {
                    mkdir($barcodeDir, 0777, true);
                }

                if (!is_dir('../public/uploads/usuario_codigo/usuario_' . $store_num_nomina)) {
                    mkdir('../public/uploads/usuario_codigo/usuario_' . $store_num_nomina, 0777, true);


                    $barcode_num = $generator->getBarcode($store_num_nomina, $generator::TYPE_CODE_128);
                    $barcode_cost = $generator->getBarcode($store_centro_costo, $generator::TYPE_CODE_128);

                    $numberFilePath = '../public/uploads/usuario_codigo/usuario_' . $store_num_nomina . '/number_' . $store_num_nomina . '.png';
                    $costFilePath = '../public/uploads/usuario_codigo/usuario_' . $store_num_nomina . '/cost_' . $store_num_nomina . '.png';

                    file_put_contents($numberFilePath, $barcode_num);
                    file_put_contents($costFilePath, $barcode_cost);
                    // Asegúrate de que las claves del array coinciden con los nombres de las columnas en la base de datos
                    $data_code = [
                        'payrollnumber_image' => '../public/uploads/usuario_codigo/usuario_' . $store_num_nomina . '/number_' . $store_num_nomina . '.png',
                        'costcenter_image' => '../public/uploads/usuario_codigo/usuario_' . $store_num_nomina . '/cost_' . $store_num_nomina . '.png'
                    ];
                    $id_users = (int) $store_id_user; // Asegúrate de que $id_user es un entero
                    $this->userModel->update($id_users, $data_code);
                }



                $builder =  $this->db->table('tbl_store_items_vouchers');

                for ($i = 0; $i < count($amount); $i++) {


                    $barcode = $generator->getBarcode($code[$i], $generator::TYPE_CODE_128);
                    $barcode2 = $generator->getBarcode($description[$i], $generator::TYPE_CODE_128);
                    //$barcode3 = $generator->getBarcode($weight[$i], $generator::TYPE_CODE_128);

                    $codigoFilePath = $barcodeDir . '/codigo_' . $i . '_' . $id_vouchers . '.png';
                    $articuloFilePath = $barcodeDir . '/articulo_' . $i . '_' . $id_vouchers . '.png';
                    $ubicacionFilePath = $barcodeDir . '/ubicacion_' . $i . '_' . $id_vouchers . '.png';

                    file_put_contents($codigoFilePath, $barcode);
                    file_put_contents($articuloFilePath, $barcode2);
                    // file_put_contents($ubicacionFilePath, $barcode3);

                    $dataItem = [
                        'items' => $id_vouchers,
                        'code' => $code[$i],
                        'article' => $description[$i],
                        'amount' => $amount[$i],
                        //'weight' => $weight[$i],
                        'observation' => $observation[$i],
                        'created_at' => $date,
                        'barcode_image' => $codigoFilePath,
                        'article_image' => $articuloFilePath,
                        'weight_image' => $ubicacionFilePath

                    ];
                    $insertItem = $builder->insert($dataItem);
                }

                $title = session()->name . " " . session()->surname;

                $dataItem = [
                    "user" => $title,
                    "addressee" => $addressee,
                    "created_at" => $date,
                    "departures" => $departures,
                    "id_vouchers" => $id_vouchers
                ];

                //   $this->emailNotification($dataItem);

                return ($insertItem) ? json_encode($codigo) : json_encode(false);
            }
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function materialList()
    {
        try {
            // $data = $this->db->query(" CALL materialListALL ")->getResult();
            $data = $this->db->query("SELECT * FROM tbl_store_raw_material WHERE active_status = 1 ORDER BY id_mp DESC;")->getResult();
            return ($data != null) ? json_encode($data) : json_encode("error");
            //code...
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function newCode()
    {
        try {
            $code = $this->request->getPost('codigo');
            $description = $this->request->getPost('descripcion');
            $unit_of_measure = $this->request->getPost('unidad_medida');
            $date = date("Y-m-d H:i:s");
            $data = [
                "id_user" => session()->id_user,
                "code" => $code,
                "description" => $description,
                "unit_of_measure" => $unit_of_measure,
                "created_at" => $date
            ];

            $insertData = $this->materialModel->insert($data);

            return ($insertData) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function codeExists()
    {
        try {
            $code = trim($this->request->getPost('codigo'));

            $builder = $this->db->table('tbl_store_raw_material');
            $builder->select('id_mp,code');
            $builder->where('code', $code);
            $builder->where('active_status', 1);
            $data = $builder->get()->getResult();

            return (count($data) > 0) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function transfersList()
    {
        try {

            $builder = $this->db->table('tbl_store_vouchers a');
            $builder->select('a.*,b.name,b.surname,b.second_surname,');
            $builder->join('tbl_users b', 'b.id_user = a.id_user', 'left');
            $builder->where('a.estatus <>', 3);
            $builder->limit(10000);
            $data = $builder->get()->getResult();

            return ($data > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function searchCode()
    {
        try {
            $code = $this->request->getPost('codigo');
            $startDate =  $this->request->getPost('desde');
            $endDate = $this->request->getPost('hasta');
            // $endDate1 = strtotime($endDate."+ 1 days");
            $query = $this->db->query("SELECT c.*,b.name,b.surname,b.second_surname,a.code,a.article,a.amount,a.weight
            FROM
            tbl_store_items_vouchers  AS a
            INNER JOIN tbl_store_vouchers AS c
            ON   c.id_vouchers = a.items
            INNER JOIN tbl_users AS b
            ON   b.id_user = c.id_user 
            WHERE a.created_at BETWEEN '" . $startDate . "'  AND '" . $endDate . "' AND a.code ='" . $code . "'");
            $data =  $query->getResult();

            return ($data > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function deleteCode()
    {
        try {
            $code = $this->request->getPost('id_code');

            $data = ["active_status" => 2];
            return ($this->materialModel->update($code, $data)) ? true : false;
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function vouchersExcel($id_vouchers = null)
    {

        $NombreArchivo = "Folio_" . $id_vouchers . ".xlsx";
        //CIFRADO
        //$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

        $query = $this->db->query("SELECT
                                    a.*, b.name,
                                    b.surname,
                                    c.code,
                                    c.article,
                                    c.amount,
                                    c.observation,
                                    c.weight
                                    FROM
                                        tbl_store_vouchers AS a
                                    LEFT JOIN tbl_users AS b ON b.id_user = a.id_user
                                    LEFT JOIN tbl_store_items_vouchers AS c ON a.id_vouchers = c.items
                                    WHERE
                                    a.id_vouchers=" . $id_vouchers);
        $data =  $query->getResult();


        //var_dump($data);

        $items = 1;
        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle("Transferencia");

        $sheet->setCellValue('A1', 'ITEM');
        $sheet->setCellValue('B1', 'FOLIO');
        $sheet->setCellValue('C1', 'ARTICULO');
        $sheet->setCellValue('D1', 'FECHA DE TRANSACCION');
        $sheet->setCellValue('E1', 'CODIGO');
        $sheet->setCellValue('F1', 'CANTIDAD');
        $sheet->setCellValue('G1', 'OBSERVACIONES');
        $sheet->setCellValue('H1', 'NAVE DE ORIGEN');
        $sheet->setCellValue('I1', 'NAVE DE DESTINO');
        $sheet->setCellValue('J1', 'USUARIO');


        foreach ($data as $key => $value) {
            $celdaA = 'A' . $cont;
            $celdaB = 'B' . $cont;
            $celdaC = 'C' . $cont;
            $celdaD = 'D' . $cont;
            $celdaE = 'E' . $cont;
            $celdaF = 'F' . $cont;
            $celdaG = 'G' . $cont;
            $celdaH = 'H' . $cont;
            $celdaI = 'I' . $cont;
            $celdaJ = 'J' . $cont;


            switch ($value->addressee) {
                case 1:
                    $origen = "Nave 1";
                    break;
                case 2:
                    $origen = "Nave 4";
                    break;
                case 3:
                    $origen = "Nave 3";
                    break;

                default:
                    $origen = "ERROR";
                    break;
            }
            switch ($value->departures) {
                case 1:
                    $destino = "Nave 1";
                    break;
                case 2:
                    $destino = "Nave 4";
                    break;
                case 3:
                    $destino = "Nave 3";
                    break;
                case 4:
                    $destino = "Villahermosa";
                    break;
                case 5:
                    $destino = "Century";
                    break;

                default:
                    $destino = "ERROR";
                    break;
            }
            $usuario = $value->name . " " . $value->surname;
            $sheet->setCellValue($celdaA, $items);
            $sheet->setCellValue($celdaB, $value->id_vouchers);
            $sheet->setCellValue($celdaC, $value->article);
            $sheet->setCellValue($celdaD, $value->created_at);
            $sheet->setCellValue($celdaE, $value->code);
            $sheet->setCellValue($celdaF, $value->amount);
            $sheet->setCellValue($celdaG, $value->observation);
            $sheet->setCellValue($celdaH, $origen);
            $sheet->setCellValue($celdaI, $destino);
            $sheet->setCellValue($celdaJ, $usuario);
            $cont++;
            $items++;
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

    public function pdfTransfers($id_vouchers)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $imagen = [];
        $querys =  $this->db->query("SELECT *
                                     FROM tbl_store_vouchers
                                     WHERE
                                    MD5(concat('" . $key . "',id_vouchers))='" . $id_vouchers . "'");
        $datos =  $querys->getRow();
        $id_image = $datos->id_user_asign;
        if ($id_image != '') {
            $images = $this->db->query("SELECT payrollnumber_image, costcenter_image
                                    FROM
                                    tbl_users
                                    WHERE
                                    id_user = $id_image");
            $imagen =  $images->getRow();
        }

        if ($datos->id_user_authorize == 0) {
            $query = $this->db->query("SELECT a.*,b.name,b.surname
            FROM
            tbl_store_vouchers  AS a
            INNER JOIN tbl_users AS b
            ON   a.id_user = b.id_user 
            WHERE
            MD5(concat('" . $key . "',a.id_vouchers))='" . $id_vouchers . "'");
            $dataVouchers =  $query->getRow();
        } else {
            $query = $this->db->query("SELECT a.*,b.name,b.surname,c.name as nombre,c.surname as apellido,c.payroll_number
            FROM
            tbl_store_vouchers  AS a
            INNER JOIN tbl_users AS b
            ON   a.id_user = b.id_user 
            INNER JOIN tbl_users AS c
            ON   a.id_user_authorize = c.id_user 
            WHERE
            MD5(concat('" . $key . "',a.id_vouchers))='" . $id_vouchers . "'");
            $dataVouchers =  $query->getRow();
        }



        $query2 = $this->db->query("SELECT b.*,d.*
                                    FROM
                                    tbl_store_items_vouchers AS b
                                    INNER JOIN tbl_store_raw_material AS d
                                    ON   b.code = d.code
                                    WHERE d.active_status = 1 AND
                                    MD5(concat('" . $key . "',b.items))='" . $id_vouchers . "'");
        $datas =  $query2->getResultArray();


        $data = [
            "imagen" => $imagen,
            "data" => $datos,
            "request" => $dataVouchers,
            "item" => $datas
        ];

        $html2 = view('pdf/pdf_store_transfers', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Transferecias');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('transferencia_' . $id_vouchers . '.pdf', 'I');
    }


    public function generarPdf()
    {
        // Obtener las fechas del formulario
        $fecha_inicio = $this->request->getPost('fecha_inicio');
        $fecha_fin = $this->request->getPost('fecha_fin');

        // Consulta SQL para obtener los datos
        $sql = "SELECT a.user, a.payroll_number, a.authorize_datetime, a.cost_center,
                       b.amount, b.code, b.article
                FROM tbl_store_vouchers AS a
                INNER JOIN tbl_store_items_vouchers AS b
                ON a.id_vouchers = b.items  -- Asegúrate de que esta sea la clave foránea correcta
                WHERE a.active_status = 1 
                 -- AND a.estatus = 2 
                AND a.created_at BETWEEN ? AND ?";

        // Ejecutar la consulta
        $query = $this->db->query($sql, [$fecha_inicio, $fecha_fin]); // Protege contra SQL Injection
        $datos = $query->getResultArray();

        // Procesar los datos de la consulta
        $registros = [];
        foreach ($datos as $value) {
            $fecha = date("Y-m-d", strtotime($value['authorize_datetime']));
            $registros[] = [
                'cantidad' => $value["amount"],
                'codigo' => $value["code"],
                'descripcion' => $value["article"],
                'nombre_trabajador' => $value["user"],
                'no_empleado' => $value["payroll_number"],
                'area' => $value["cost_center"],
                'ubicacion' => 'acomodo', // Valor fijo
                'firma' => 'firma', // Valor fijo
                'fecha' => $fecha,
            ];
        }

        // Dividir los registros en bloques
        $grupos = array_chunk($registros, 25);

        try {
            // Crear el PDF
            $html2pdf = new Html2Pdf('P', 'Letter', 'es', true, 'UTF-8');
            $html2pdf->pdf->SetTitle('Vale de Salida'); // Establecer el título del PDF

            foreach ($grupos as $grupo) {
                // Dividir cada grupo en dos tablas
                $tabla1 = array_slice($grupo, 0, 25);
                //  $tabla2 = array_slice($grupo, 5, 5);

                // Pasar los datos a la vista para cada grupo
                $data['tabla1'] = $tabla1;
                //  $data['tabla2'] = $tabla2;
                $html = view('pdf/pdf_store_vale', $data); // Generar el HTML para las tablas

                // Escribir el HTML en el PDF
                $html2pdf->writeHTML($html);
            }

            // Crear la ruta de guardado en el directorio 'public/PDF/almacen/'
            $pdfFilename = 'vale_salida_' . uniqid() . '.pdf';
            $pdfPath = ROOTPATH . 'public/PDF/almacen/' . $pdfFilename;

            // Asegúrate de que la carpeta existe y tiene permisos de escritura
            if (!is_dir(ROOTPATH . 'public/PDF/almacen/')) {
                mkdir(ROOTPATH . 'public/PDF/almacen/', 0775, true);
            }

            // Guardar el PDF en el archivo
            $html2pdf->output($pdfPath, 'F'); // 'F' guarda el archivo en el servidor

            // Devolver la URL del PDF (accesible públicamente)
            $pdfUrl = base_url('public/PDF/almacen/' . $pdfFilename);

            return $this->response->setJSON([
                'status' => 'success',
                'pdf_url' => $pdfUrl, // URL pública para acceder al PDF
            ]);
        } catch (Html2PdfException $e) {
            // Manejar errores
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al generar el PDF: ' . $e->getMessage(),
            ]);
        }
    }




    public function emailNotification($data)
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
            $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('requisiciones@grupowalworth.com', 'Notificaciones');
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            //$mail->addAddress('rcruz@walworth.com.mx','Rafael Cruz');
            $mail->addAddress('gvelazquez@walworth.com.mx', 'German Velazquez');
            $mail->addAddress('eocana@walworth.com.mx', 'Enrique Ocaña');
            $mail->addAddress('ohernandez@walworth.com.mx', 'Odilon Hernandez');
            $mail->addAddress('smaqueda@walworth.com.mx', 'Sergio Maqueda');
            $mail->addAddress('mrubio@walworth.com.mx', 'Miguel Angel Rubio');
            $mail->addAddress('jresendiz@walworth.com.mx', 'Juan Reséndiz');

            //$mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/transferencias', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Transferencia';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function NotificationVigilancia($data)
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
            $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('requisiciones@grupowalworth.com', 'Notificaciones');
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            //$mail->addAddress('rcruz@walworth.com.mx','Rafael Cruz');
            $mail->addAddress('vigilancia@walworth.com.mx', 'Vigilancia');




            //$mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/transferencia_vigilancia', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Transferencia';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function generateReports()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $NombreArchivo = "almacen_mp.xlsx";

        $query = $this->db->query("SELECT 
         a.id_vouchers AS folio ,
         a.created_at AS created_at ,
         b.`code` AS codigo, 
         b.amount AS cantidad, 
         b.article AS descripcion,
         b.weight AS ubicacion,
         b.observation AS observacion,
        CASE  
               WHEN a.addressee = 1 THEN 'NAVE 1' 
               WHEN a.addressee = 3 THEN 'NAVE 3' 
               WHEN a.addressee = 2 THEN 'NAVE 4'
        END AS nave_salida,
        CASE  
                WHEN a.departures = 1 THEN 'NAVE 1' 
                WHEN a.departures = 3 THEN 'NAVE 3' 
                WHEN a.departures = 2 THEN 'NAVE 4'
            WHEN a.departures = 4 THEN 'VillaHermosa'
        END AS destino,
        c.`name` AS nombre1,  c.surname AS apep1, c.second_surname AS apem1,
        d.`name` AS nombre2,  d.surname AS apep2 , d.second_surname AS apem2
        FROM tbl_store_vouchers AS a
            LEFT JOIN tbl_store_items_vouchers AS b ON a.id_vouchers = b.items
            LEFT JOIN tbl_users AS c ON a.id_user = c.id_user
            LEFT JOIN tbl_users AS d ON a.id_user_authorize = d.id_user
        WHERE a.created_at BETWEEN '" . $data->fecha_inicio . "' AND '" . $data->fecha_fin . "'
        ");

        $reporte = $query->getResult();
        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:K1');
        $spreadsheet->getActiveSheet();
        $sheet->setTitle("requisiciones");

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(26);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(11);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40);


        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        // Determino ubicacion del texto
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:K1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        // color de celdas        
        $spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
        // font text por grupos
        $sheet->getStyle("A1:K1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor()
            ->setRGB('FFFFFF');


        $sheet->setCellValue('A1', 'FOLIO');
        $sheet->setCellValue('B1', 'FECHA DE TRANSACCION');
        $sheet->setCellValue('C1', 'CODIGO');
        $sheet->setCellValue('D1', 'CANTIDAD');
        $sheet->setCellValue('E1', 'DESCRIPCION');
        $sheet->setCellValue('F1', 'UBICACION');
        $sheet->setCellValue('G1', 'OBSERVACION');
        $sheet->setCellValue('H1', 'NAVE SALIDA');
        $sheet->setCellValue('I1', 'DESTINO');
        $sheet->setCellValue('J1', 'USARIO GENERA');
        $sheet->setCellValue('K1', 'USUARIO AUTORIZA');

        foreach ($reporte as $key => $value) {
            $fecha = date("d-m-Y", strtotime($value->created_at));

            $sheet->setCellValue('A' . $cont, $value->folio);
            $sheet->setCellValue('B' . $cont, $fecha);
            $sheet->setCellValue('C' . $cont, $value->codigo);
            $sheet->setCellValue('D' . $cont, $value->cantidad);
            $sheet->setCellValue('E' . $cont, $value->descripcion);
            $sheet->setCellValue('F' . $cont, $value->ubicacion);
            $sheet->setCellValue('G' . $cont, $value->observacion);
            $sheet->setCellValue('H' . $cont, $value->nave_salida);
            $sheet->setCellValue('I' . $cont, $value->destino);
            $sheet->setCellValue('J' . $cont, $value->nombre1 . " " . $value->apep1 . " " . $value->apem1);
            $sheet->setCellValue('K' . $cont, $value->nombre2 . " " . $value->apep2 . " " . $value->apem2);

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

    function listDataValesByUser()
    {
        try {

            $idUser = $this->request->getPost('id_user');
            $query = $this->db->query("SELECT a.id_vouchers,a.type_transfer, 
                                                b.code,
                                                b.id_vouchers as items,
                                                b.article,
                                                b.amount,
                                                b.weight,
                                                b.observation,
                                                b.barcode_image,
                                                b.article_image,
                                                b.weight_image,
                                                c.payrollnumber_image,
                                                d.barcode_eqps,
                                                d.barcode_unif
                                        FROM tbl_store_vouchers AS a 
                                            INNER JOIN tbl_store_items_vouchers AS b ON a.id_vouchers = b.items AND b.active_status = 1
                                            LEFT JOIN tbl_users AS c ON a.id_user = c.id_user 
											LEFT JOIN cat_cost_center AS d ON c.id_cost_center = d.id_cost_center 
                                        WHERE  a.active_status = 1 AND a.estatus = 1
                                        AND a.id_user_asign = $idUser ORDER BY a.id_vouchers DESC ")
                ->getResult() ?? false;

            // Group the items by request_id
            $groupedItems = [];
            foreach ($query as $item) {
                $groupedItems[$item->id_vouchers][] = $item;
            }

            $data = ['groupedItems' => $groupedItems];

            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
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



    function confirmDeliveryVale()
    {
        $idRequest = $this->request->getPost('id_request');
        $clave = $this->request->getPost('clave');
        $id_user = session()->id_user;

        if ($this->db->query("SELECT id_vouchers FROM tbl_store_vouchers WHERE active_status = 1 
        AND id_vouchers = $idRequest AND pw_security = $clave")->getRow() == NULL) {
            return json_encode("errorClave");
        }
        $arrayIdItem_ = $this->request->getPost('id_item_');
        $arrayCantEntrega_ = $this->request->getPost('cant_entrega_');
        $comentario = $this->request->getPost('comentario');
        $this->db->transStart();
        for ($i = 0; $i < count($arrayIdItem_); $i++) {
            $updateItems = [
                'amount_confirm' => $arrayCantEntrega_[$i],
                'coment' => $comentario,
                'active_status' => 2,
                'delivery_at' => date("Y-m-d H:i:s"),
            ];
            $this->itemsVouchersModel->update($arrayIdItem_[$i], $updateItems);
        }

        $requestStatus = $this->db->query("SELECT IF(SUM(amount) = SUM(amount_confirm),2,3) AS if_result 
            FROM tbl_store_items_vouchers WHERE active_status = 1 AND id_vouchers = $idRequest")->getRow()
            ->if_result;

        $updateRequest = [
            'estatus' => 2,
            'id_user_authorize' => $id_user,
            'obs_request' => $comentario,
            'authorize_datetime' => date("Y-m-d H:i:s"),
        ];
        $this->vouchersModel->update($idRequest, $updateRequest);

        $result = $this->db->transComplete();
        if ($result) {

            return json_encode($idRequest);
        }
    }

    public function Workbeat()
    {
        // Ruta al archivo JSON en public
        $rutaArchivo = FCPATH . 'DATAWORKBEAT.json';

        // Leer contenido
        $contenido = file_get_contents($rutaArchivo);

        // Convertir a arreglo asociativo
        $empleados = json_decode($contenido, true);

        // Ejemplo: sumar concepto DLAB
        $totalDiasLaborados = $this->sumarPorConcepto($empleados, "DLAB");

        // Ejemplo: sumar concepto TOTALPER
        $totalPercepciones = $this->sumarPorConcepto($empleados, "TOTALPER");

        // Pasar resultados a la vista
        return view('store/nomina', [
            'totalDiasLaborados' => $totalDiasLaborados,
            'totalPercepciones' => $totalPercepciones,
        ]);
    }

    private function sumarPorConcepto(array $data, string $claveConcepto): float
    {
        $total = 0;
        foreach ($data as $empleado) {
            foreach ($empleado['Conceptos'] as $concepto) {
                if (strtoupper($concepto['Concepto']) === strtoupper($claveConcepto)) {
                    $total += $concepto['Importe'];
                }
            }
        }
        return $total;
    }
}
