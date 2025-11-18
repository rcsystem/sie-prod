<?php

/**
 * MODULO DE INVENTARIO FINANZAS
 * @version 1.1 pre-prod
 * @author  Rafel Cruz Aguilar <rafel.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 */

namespace App\Controllers\Finance;

use ZipArchive;
use App\Controllers\BaseController;
use App\Models\InventoryMobiliarioModel;
use App\Models\FinanceInventoryModel;
use App\Models\SolicitudAdmModel;
use App\Models\ArchivosAdmModel;
use App\Models\ArchivosTalentoModel;
use App\Models\SolicitudTalentoModel;
use App\Models\CodigosTalentoModel;
use App\Models\TalentAuthorizeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;


use Spipu\Html2Pdf\Html2Pdf;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;

class Finance extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->mobiliarioModel = new InventoryMobiliarioModel();
        $this->inventoryModel = new FinanceInventoryModel();
        $this->is_logged = session()->is_logged ? true : false;
        $this->db = \Config\Database::connect();
    }

    public function viewInventory()
    {
        return ($this->is_logged) ? view('finance/finance_inventory') : redirect()->to(site_url());
    }


    public function viewMobiliario()
    {
        return ($this->is_logged) ? view('finance/finance_mobiliario') : redirect()->to(site_url());
    }

    public function viewQrInventory($id_activo)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

        $querys =  $this->db->query("SELECT *
										FROM tbl_finance_inventory
										WHERE
										MD5(concat('" . $key . "',id_activo))='" . $id_activo . "'");
        $datos =  $querys->getResultArray();
        //$result = $query->getResultArray();

        return  view('finance/finance_inventory_qr', ['activos' => $datos]);
    }

    public function viewQrMobiliario($id_activo)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

        $querys =  $this->db->query("SELECT *
										FROM tbl_finance_inventory_mobiliario
										WHERE
										MD5(concat('" . $key . "',id_activo))='" . $id_activo . "'");
        $datos =  $querys->getResultArray();
        //$result = $query->getResultArray();

        return  view('finance/finance_mobiliario_qr', ['activos' => $datos]);
    }


    public function inventoryMobiliarioAll()
    {



        try {

            // Obtener todos los registros con status = 1
            $activos = $this->mobiliarioModel->where('status_activo', 1)->findAll();

            // Verificar si hay datos
            if (empty($activos)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'data' => $activos]);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }


    public function inventoryAll()
    {


        try {

            // Obtener todos los registros con status = 1
            $activos = $this->inventoryModel->where('status_activo', 1)->findAll();

            // Verificar si hay datos
            if (empty($activos)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'data' => $activos]);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }


    public function inactiveInventory()
    {
        try {
            // Obtener todos los registros con status_activo = 2
            $activos = $this->inventoryModel->where('status_activo', 2)->findAll();

            // Verificar si hay datos
            if (empty($activos)) {
                return $this->response->setStatusCode(204)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos en el formato esperado por DataTables
            return $this->response->setStatusCode(200)
                ->setJSON(['data' => $activos]); // Aquí está el cambio clave
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function inactiveInventoryMobiliario()
    {
        try {
            // Obtener todos los registros con status_activo = 2
            $activos = $this->mobiliarioModel->where('status_activo', 2)->findAll();

            // Verificar si hay datos
            if (empty($activos)) {
                return $this->response->setStatusCode(204)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos en el formato esperado por DataTables
            return $this->response->setStatusCode(200)
                ->setJSON(['data' => $activos]); // Aquí está el cambio clave
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function inactiveInventoryItem()
    {


        try {

            // Obtén el ID desde el input de forma segura
            $id_activo = $this->request->getPost('id_activo');
            $data = ['status_activo' => 2];

            // Actualizar el registro con el ID proporcionado
            $result = $this->inventoryModel->update($id_activo, $data);

            // Verificar si hay datos
            if (empty($result)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'data' => true]);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function inactiveInventoryItemMobiliario()
    {


        try {

            // Obtén el ID desde el input de forma segura
            $id_activo = $this->request->getPost('id_activo');
            $data = ['status_activo' => 2];

            // Actualizar el registro con el ID proporcionado
            $result = $this->mobiliarioModel->update($id_activo, $data);

            // Verificar si hay datos
            if (empty($result)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'data' => true]);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function activeInventoryItemMobiliario()
    {


        try {

            // Obtén el ID desde el input de forma segura
            $id_activo = $this->request->getPost('id_activo');
            $data = ['status_activo' => 2];

            // Actualizar el registro con el ID proporcionado
            $result = $this->mobiliarioModel->update($id_activo, $data);

            // Verificar si hay datos
            if (empty($result)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'data' => true]);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function activeInventoryItem()
    {


        try {

            // Obtén el ID desde el input de forma segura
            $id_activo = $this->request->getPost('id_activo');
            $data = ['status_activo' => 1];

            // Actualizar el registro con el ID proporcionado
            $result = $this->inventoryModel->update($id_activo, $data);

            // Verificar si hay datos
            if (empty($result)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'data' => true]);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }



    public function graphAssets()
    {

        // Obtener todos los registros
        $activos = $this->inventoryModel->findAll(); // Esto retorna todos los registros

        // Inicializar variables para contar las respuestas
        $contadorSi = 0;
        $contadorNo = 0;

        // Recorrer los registros y contar cuántos son "sí" y cuántos son "no"
        foreach ($activos as $registro) {
            if (isset($registro['cuenta_con_factura'])) {
                if ($registro['cuenta_con_factura'] == 1) {
                    $contadorSi++;
                } elseif ($registro['cuenta_con_factura'] == 0) {
                    $contadorNo++;
                }
            }
        }

        // Crear el arreglo de resultados
        $resultado = [
            'si' => $contadorSi,
            'no' => $contadorNo
        ];

        // Devolver la respuesta en formato JSON
        return $this->response->setJSON($resultado);
    }


    public function activeQr()
    {
        // Datos que quieres codificar en el código QR
        //$activos = $this->inventoryModel->findAll(); // Esto retorna todos los registros

        // CIFRADO
        //$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

        // Recorrer los registros y generar el código QR
        //foreach ($activos as $registro) {
        // Generar el ID encriptado
        //	$id_encriptado = md5($key . $registro["id_activo"]);

        // Construir la URL que se codificará en el código QR
        $url = '610304';

        // Crear un objeto QrCode con la URL
        $qrCode = new QrCode($url);

        // Personalizar el tamaño del código QR
        $qrCode->setSize(300);

        // Establecer el color del primer plano (foreground) usando la clase Color
        $qrCode->setForegroundColor(new Color(0, 0, 0)); // Negro

        // Establecer el color de fondo usando la clase Color
        $qrCode->setBackgroundColor(new Color(255, 255, 255)); // Blanco

        // Establecer el margen (espaciado alrededor del código QR)
        $qrCode->setMargin(10); // Margen de 10 píxeles

        // Usar el escritor PNG para generar la imagen
        $writer = new PngWriter();

        // Crear la carpeta para guardar la imagen si no existe
        $path = '../public/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true); // Crear la carpeta con permisos
        }

        // Reemplazar "S/N" por "S_N" en el nombre del archivo
        //$codigo_name = str_replace("S/N", "S_N", $registro["codigo"]);

        // Establecer el nombre del archivo
        $fileName = 'qr_610304.png';
        $filePath = $path . '/' . $fileName;

        // Usar el método write() para generar y guardar la imagen
        $result = $writer->write($qrCode);

        // Guardar la imagen en el sistema de archivos
        file_put_contents($filePath, $result->getString());

        // Aquí guardarías la ruta en la base de datos
        //$this->saveQrPathInDatabase($registro["id_activo"], $filePath);

        // Para depuración, puedes dejar los siguientes echo
        //echo $registro["id_activo"] . "<br>";
        echo $filePath . "<br>";
        //}
    }

    public function activeQrMobiliario()
    {
        // Datos que quieres codificar en el código QR
        $activos = $this->mobiliarioModel->findAll(); // Esto retorna todos los registros

        // CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

        // Recorrer los registros y generar el código QR
        foreach ($activos as $registro) {
            // Generar el ID encriptado
            $id_encriptado = md5($key . $registro["id_activo"]);

            // Construir la URL que se codificará en el código QR
            $url = base_url() . '/finanzas/item-mobiliario/' . $id_encriptado;

            // Crear un objeto QrCode con la URL
            $qrCode = new QrCode($url);

            // Personalizar el tamaño del código QR
            $qrCode->setSize(300);

            // Establecer el color del primer plano (foreground) usando la clase Color
            $qrCode->setForegroundColor(new Color(0, 0, 0)); // Negro

            // Establecer el color de fondo usando la clase Color
            $qrCode->setBackgroundColor(new Color(255, 255, 255)); // Blanco

            // Establecer el margen (espaciado alrededor del código QR)
            $qrCode->setMargin(10); // Margen de 10 píxeles

            // Usar el escritor PNG para generar la imagen
            $writer = new PngWriter();

            // Crear la carpeta para guardar la imagen si no existe
            $path = FCPATH . 'finanzas/qr_mobiliario/' . $registro["id_activo"];

            if (!is_dir($path)) {
                mkdir($path, 0777, true); // Crear la carpeta con permisos
            }


            // Normalizar el nombre del archivo
            $codigo_name = str_replace(["/", " "], ["_", ""], $registro["codigo"]);
            $fileName = 'qr_mobiliario_' . $codigo_name . '.png';
            $filePath = $path . '/' . $fileName;

            // Usar el método write() para generar y guardar la imagen
            $result = $writer->write($qrCode);

            // Guardar la imagen en el sistema de archivos
            file_put_contents($filePath, $result->getString());

            // Aquí guardarías la ruta en la base de datos
            //$this->saveQrPathInDatabase($registro["id_activo"], $filePath);

            // Para depuración, puedes dejar los siguientes echo
            //echo $registro["id_activo"] . "<br>";
            echo $filePath . "<br>";
            //}
        }
    }

    public function activeQrDXF()  //generarQR
    {


        // Datos que quieres codificar en el código QR
        $activos = $this->inventoryModel->findAll(); // Esto retorna todos los registros

        // CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

        // Recorrer los registros y generar el código QR
        // Recorrer los registros y generar el código QR
        foreach ($activos as $registro) {
            // Generar ID encriptado
            $id_encriptado = md5($key . $registro["id_activo"]);

            // Construir la URL que se codificará en el QR
            $url = base_url() . '/finanzas/item-inventario/' . $id_encriptado;

            // Crear el QR (versión 2.0.5 usa constructor)
            $qrCode = new QrCode($url);
            $qrCode->setSize(300);

            // Generar el QR como una cadena en formato PNG
            $pngData = $qrCode->writeString();

            // Definir la ruta donde se guardará el QR
            $tempFilePath = WRITEPATH . 'uploads/qr_code_' . $registro["id_activo"] . '.png';

            // Guardar el QR en el servidor
            file_put_contents($tempFilePath, $pngData);

            // Convertir el PNG en una matriz de 0 y 1
            $image = imagecreatefrompng($tempFilePath);
            $width = imagesx($image);
            $height = imagesy($image);
            $matrix = [];

            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $color = imagecolorat($image, $x, $y);
                    $matrix[$y][$x] = ($color == 0) ? 1 : 0; // 1 para negro, 0 para blanco
                }
            }

            // Generar el contenido DXF
            $dxfContent = $this->generateDxf($matrix);

            // Crear carpeta si no existe
            $path = WRITEPATH . 'uploads/finanzas/qr/' . $registro["id_activo"];
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            // Normalizar el nombre del archivo
            $codigo_name = str_replace("/", "_", $registro["codigo"]);
            $fileName = 'qr_activo_' . $codigo_name . '.dxf';
            $filePath = $path . '/' . $fileName;

            // Guardar el DXF en el servidor
            file_put_contents($filePath, $dxfContent);

            echo "QR en DXF guardado en: " . $filePath . "<br>";
        }
    }

    public function activeQrDXFMobiliario()  //generarQR
    {


        // Datos que quieres codificar en el código QR
        $activos = $this->mobiliarioModel->findAll(); // Esto retorna todos los registros

        var_dump($activos);

        // CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

        // Recorrer los registros y generar el código QR
        // Recorrer los registros y generar el código QR
        foreach ($activos as $registro) {
            // Generar ID encriptado
            $id_encriptado = md5($key . $registro["id_activo"]);

            // Construir la URL que se codificará en el QR
            $url = base_url() . '/finanzas/item-mobiliario/' . $id_encriptado;

            // Crear el QR (versión 2.0.5 usa constructor)
            $qrCode = new QrCode($url);
            $qrCode->setSize(300);

            // Generar el QR como una cadena en formato PNG
            $pngData = $qrCode->writeString();

            // Definir la ruta donde se guardará el QR
            $tempFilePath = WRITEPATH . 'uploads/qr_code_' . $registro["id_activo"] . '.png';

            // Guardar el QR en el servidor
            file_put_contents($tempFilePath, $pngData);

            // Convertir el PNG en una matriz de 0 y 1
            $image = imagecreatefrompng($tempFilePath);
            $width = imagesx($image);
            $height = imagesy($image);
            $matrix = [];

            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $color = imagecolorat($image, $x, $y);
                    $matrix[$y][$x] = ($color == 0) ? 1 : 0; // 1 para negro, 0 para blanco
                }
            }

            // Generar el contenido DXF
            $dxfContent = $this->generateDxf($matrix);

            // Crear carpeta si no existe
            $path = WRITEPATH . 'uploads/finanzas/qr_mobiliario/' . $registro["id_activo"];
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            // Normalizar el nombre del archivo
            $codigo_name = str_replace("/", "_", $registro["codigo"]);
            $fileName = 'qr_mobiliario_' . $codigo_name . '.dxf';
            $filePath = $path . '/' . $fileName;

            // Guardar el DXF en el servidor
            file_put_contents($filePath, $dxfContent);

            echo "QR en DXF guardado en: " . $filePath . "<br>";
        }
    }



    private function generateDxf($matrix)
    {
        $size = count($matrix);
        $scale = 5; // Factor de escala

        // Iniciar contenido DXF
        $dxf = "0\nSECTION\n2\nHEADER\n0\nENDSEC\n";
        $dxf .= "0\nSECTION\n2\nTABLES\n0\nENDSEC\n";
        $dxf .= "0\nSECTION\n2\nBLOCKS\n0\nENDSEC\n";
        $dxf .= "0\nSECTION\n2\nENTITIES\n";

        // Recorrer la matriz y dibujar cuadrados en DXF
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                if ($matrix[$y][$x] === 1) {
                    $x1 = $x * $scale;
                    $y1 = $y * $scale;
                    $x2 = ($x + 1) * $scale;
                    $y2 = ($y + 1) * $scale;

                    $dxf .= "0\nLWPOLYLINE\n8\n0\n";
                    $dxf .= "90\n4\n70\n1\n";
                    $dxf .= "10\n$x1\n20\n$y1\n";
                    $dxf .= "10\n$x2\n20\n$y1\n";
                    $dxf .= "10\n$x2\n20\n$y2\n";
                    $dxf .= "10\n$x1\n20\n$y2\n";
                }
            }
        }

        $dxf .= "0\nENDSEC\n0\nEOF";

        return $dxf;
    }



    public function assetRegistration()
    {
        (int)$id_user = session()->id_user;

        // Procesar el archivo subido
        $file = $this->request->getFile('factura');

        // Validar si el archivo es válido
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Archivo inválido o ya ha sido movido'
            ]);
        }

        // Guardar el archivo en la carpeta `writable/uploads`
        $originalName = $file->getName();
        // Reemplazar espacios por guiones bajos
        $newName = str_replace(' ', '_', $originalName);

        $codigo = $this->request->getPost('codigo');

        // Obtener los datos del formulario
        $data = [
            'codigo' => $codigo,
            'descripcion' => $this->request->getPost('descripcion'),
            'marca' => $this->request->getPost('marca'),
            'capacidad' => $this->request->getPost('capacidad'),
            'modelo' => $this->request->getPost('modelo'),
            'serie' => $this->request->getPost('serie'),
            'ubicacion' => $this->request->getPost('ubicacion'),
            'area' => $this->request->getPost('area'),
            'fecha' => $this->request->getPost('fecha'),
            'proveedor' => $this->request->getPost('proveedor'),
            'revisado' => $this->request->getPost('revisado'),
            'datos' => $this->request->getPost('datos'),
            'factura' => $newName,
            'status_activo' => 1,
            'id_user_create' => $id_user,
            'cuenta_con_factura' => 1,
        ];

        // Guardar los datos en la base de datos
        $this->inventoryModel->insert($data);

        // Recuperar el ID del último registro insertado
        $id_result = $this->inventoryModel->insertID();

        // Definir la ruta donde se guardará la factura
        $ruta = FCPATH . "finanzas/facturas/{$id_result}/";

        // Crear la carpeta si no existe
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        // Mover el archivo a la carpeta de destino
        if (!$file->move($ruta, $newName)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo mover el archivo'
            ]);
        }

        // Guardar la ruta de la factura en la base de datos
        $updateData = ['ruta_factura' => "../public/finanzas/facturas/{$id_result}/{$newName}"];
        $resultados = $this->inventoryModel->update($id_result, $updateData);

        // CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $id_encriptado = md5($key . $id_result);

        // Construir la URL que se codificará en el código QR
        $url = base_url() . '/finanzas/item-inventario/' . $id_encriptado;

        // Crear un objeto QrCode con la URL
        $qrCode = new QrCode($url);

        // Personalizar el tamaño del código QR
        $qrCode->setSize(300);

        // Establecer el color del primer plano (foreground) usando la clase Color
        $qrCode->setForegroundColor(new Color(0, 0, 0)); // Negro

        // Establecer el color de fondo usando la clase Color
        $qrCode->setBackgroundColor(new Color(255, 255, 255)); // Blanco

        // Establecer el margen (espaciado alrededor del código QR)
        $qrCode->setMargin(10); // Margen de 10 píxeles

        // Usar el escritor PNG para generar la imagen
        $writer = new PngWriter();

        // Crear la carpeta para guardar la imagen si no existe
        $path = '../public/finanzas/qr/' . $id_result;
        if (!is_dir($path)) {
            mkdir($path, 0777, true); // Crear la carpeta con permisos
        }

        // Reemplazar "S/N" por "S_N" en el nombre del archivo
        $codigo_name = str_replace("/", "_", $codigo);

        // Establecer el nombre del archivo
        $fileName = 'qr_activo_' . $codigo_name . '.png';
        $filePath = $path . '/' . $fileName;

        // Usar el método write() para generar y guardar la imagen
        $result = $writer->write($qrCode);

        // Guardar la imagen en el sistema de archivos
        file_put_contents($filePath, $result->getString());

        // Aquí guardarías la ruta en la base de datos
        $this->saveQrPathInDatabase($id_result, $filePath);


        return $this->response->setJSON($result);
    }

    public function assetRegistrationMobiliario()
    {
        (int)$id_user = session()->id_user;

        // Procesar el archivo subido
        $file = $this->request->getFile('factura');

        // Validar si el archivo es válido
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Archivo inválido o ya ha sido movido'
            ]);
        }

        // Guardar el archivo en la carpeta `writable/uploads`
        $originalName = $file->getName();
        // Reemplazar espacios por guiones bajos
        $newName = str_replace(' ', '_', $originalName);

        $codigo = $this->request->getPost('codigo');

        // Obtener los datos del formulario
        $data = [
            'codigo' => $codigo,
            'descripcion' => $this->request->getPost('descripcion'),
            'marca' => $this->request->getPost('marca'),
            'capacidad' => $this->request->getPost('capacidad'),
            'modelo' => $this->request->getPost('modelo'),
            'serie' => $this->request->getPost('serie'),
            'ubicacion' => $this->request->getPost('ubicacion'),
            'area' => $this->request->getPost('area'),
            'fecha' => $this->request->getPost('fecha'),
            'proveedor' => $this->request->getPost('proveedor'),
            'revisado' => $this->request->getPost('revisado'),
            'datos' => $this->request->getPost('datos'),
            'factura' => $newName,
            'status_activo' => 1,
            'id_user_create' => $id_user,
            'cuenta_con_factura' => 1,
        ];

        // Guardar los datos en la base de datos
        $this->mobiliarioModel->insert($data);

        // Recuperar el ID del último registro insertado
        $id_result = $this->mobiliarioModel->insertID();

        // Definir la ruta donde se guardará la factura
        $ruta = FCPATH . "finanzas/facturas_mobiliario/{$id_result}/";

        // Crear la carpeta si no existe
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        // Mover el archivo a la carpeta de destino
        if (!$file->move($ruta, $newName)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo mover el archivo'
            ]);
        }

        // Guardar la ruta de la factura en la base de datos
        $updateData = ['ruta_factura' => "../public/finanzas/facturas_mobiliario/{$id_result}/{$newName}"];
        $resultados = $this->mobiliarioModel->update($id_result, $updateData);

        // CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $id_encriptado = md5($key . $id_result);

        // Construir la URL que se codificará en el código QR
        $url = base_url() . '/finanzas/item-mobiliario/' . $id_encriptado;

        // Crear un objeto QrCode con la URL
        $qrCode = new QrCode($url);

        // Personalizar el tamaño del código QR
        $qrCode->setSize(300);

        // Establecer el color del primer plano (foreground) usando la clase Color
        $qrCode->setForegroundColor(new Color(0, 0, 0)); // Negro

        // Establecer el color de fondo usando la clase Color
        $qrCode->setBackgroundColor(new Color(255, 255, 255)); // Blanco

        // Establecer el margen (espaciado alrededor del código QR)
        $qrCode->setMargin(10); // Margen de 10 píxeles

        // Usar el escritor PNG para generar la imagen
        $writer = new PngWriter();

        // Crear la carpeta para guardar la imagen si no existe
        $path = '../public/finanzas/qr_mobiliario/' . $id_result;
        if (!is_dir($path)) {
            mkdir($path, 0777, true); // Crear la carpeta con permisos
        }

        // Reemplazar "S/N" por "S_N" en el nombre del archivo
        $codigo_name = str_replace("/", "_", $codigo);

        // Establecer el nombre del archivo
        $fileName = 'qr_mobiliario_' . $codigo_name . '.png';
        $filePath = $path . '/' . $fileName;

        // Usar el método write() para generar y guardar la imagen
        $result = $writer->write($qrCode);

        // Guardar la imagen en el sistema de archivos
        file_put_contents($filePath, $result->getString());

        // Aquí guardarías la ruta en la base de datos
        $this->saveQrPathInDatabase($id_result, $filePath);


        return $this->response->setJSON($result);
    }


    // Función para guardar la ruta en la base de datos
    private function saveQrPathInDatabase($id, $filePath)
    {

        $data = [
            'imagen_qr' => $filePath, // Guardamos solo la ruta relativa

        ];

        // Actualizar el registro con el ID proporcionado
        $result = $this->inventoryModel->update($id, $data);
        if (!$result) {
            log_message('error', "Error al actualizar el registro con ID: $id");
            return false;
        } else {
            log_message('info', "Registro actualizado exitosamente con ID: $id");
            return true;
        }
        return;
    }

    public function editInventory()
    {

        // Procesar el archivo subido
        $id_activo = $this->request->getPost('id_activo');

        try {

            // Obtener todos los registros con status = 1
            $data_activos = $this->inventoryModel->where('id_activo', $id_activo)->findAll();

            // Verificar si hay datos
            if (empty($data_activos)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'data' => $data_activos]);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function editInventoryMobiliario()
    {

        // Procesar el archivo subido
        $id_activo = $this->request->getPost('id_activo');

        try {

            // Obtener todos los registros con status = 1
            $data_mobiliario = $this->mobiliarioModel->where('id_activo', $id_activo)->findAll();

            // Verificar si hay datos
            if (empty($data_mobiliario)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontraron registros.']);
            }

            // Respuesta exitosa con datos
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'data' => $data_mobiliario]);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function updateInventory()
    {

        // Obtener el ID del activo desde el POST
        $id_activo = $this->request->getPost('id_activo');

        try {
            // Verificar si el registro existe
            $activo = $this->inventoryModel->find($id_activo);

            if (empty($activo)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontró el registro.']);
            }

            // Datos para actualizar (recogidos desde el formulario)
            $data = [
                'codigo'      => $this->request->getPost('codigo'),
                'descripcion' => $this->request->getPost('descripcion'),
                'marca'       => $this->request->getPost('marca'),
                'capacidad'   => $this->request->getPost('capacidad'),
                'modelo'      => $this->request->getPost('modelo'),
                'serie'       => $this->request->getPost('serie'),
                'ubicacion'   => $this->request->getPost('ubicacion'),
                'area'        => $this->request->getPost('area'),
                'fecha'       => $this->request->getPost('fecha'),
                'proveedor'   => $this->request->getPost('proveedor'),
                'revisado'    => $this->request->getPost('revisado'),
            ];

            // Actualizar el registro
            $this->inventoryModel->update($id_activo, $data);

            // Respuesta exitosa
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'message' => 'Registro actualizado correctamente.']);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function updateInventoryMobiliario()
    {

        // Obtener el ID del activo desde el POST
        $id_activo = $this->request->getPost('id_activo');

        try {
            // Verificar si el registro existe
            $activo = $this->mobiliarioModel->find($id_activo);

            if (empty($activo)) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['status' => 'error', 'message' => 'No se encontró el registro.']);
            }

            // Datos para actualizar (recogidos desde el formulario)
            $data = [
                'codigo'      => $this->request->getPost('codigo'),
                'descripcion' => $this->request->getPost('descripcion'),
                'marca'       => $this->request->getPost('marca'),
                'capacidad'   => $this->request->getPost('capacidad'),
                'modelo'      => $this->request->getPost('modelo'),
                'serie'       => $this->request->getPost('serie'),
                'ubicacion'   => $this->request->getPost('ubicacion'),
                'area'        => $this->request->getPost('area'),
                'fecha'       => $this->request->getPost('fecha'),
                'proveedor'   => $this->request->getPost('proveedor'),
                'revisado'    => $this->request->getPost('revisado'),
            ];

            // Actualizar el registro
            $this->mobiliarioModel->update($id_activo, $data);

            // Respuesta exitosa
            return $this->response->setStatusCode(200)
                ->setJSON(['status' => 'success', 'message' => 'Registro actualizado correctamente.']);
        } catch (\Exception $e) {
            // Manejar errores generales
            return $this->response->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()]);
        }
    }

    public function downloadData($id_activo)
    {

        // Obtener la ruta de la carpeta basada en el ID
        $folderPath = '../public/finanzas/facturas/' . $id_activo;

        if (!$folderPath || !is_dir($folderPath)) {
            return $this->response->setStatusCode(404, 'Carpeta no encontrada.');
        }

        // Crear un archivo ZIP temporal
        $zip = new ZipArchive();
        $zipFileName = WRITEPATH . 'uploads/carpeta_' . $id_activo . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $this->addSingleFolderToZip($folderPath, $zip);
            $zip->close();

            // Registrar una función para eliminar el archivo después de que se complete la descarga
            register_shutdown_function(function () use ($zipFileName) {
                if (file_exists($zipFileName)) {
                    unlink($zipFileName);
                }
            });

            // Enviar el archivo ZIP al cliente
            return $this->response->download($zipFileName, null)->setFileName('carpeta_' . $id_activo . '.zip');
        } else {
            return $this->response->setStatusCode(500, 'No se pudo crear el archivo ZIP.');
        }
    }

    public function downloadDataMobiliario($id_activo)
    {

        // Obtener la ruta de la carpeta basada en el ID
        $folderPath = '../public/finanzas/facturas_mobiliario/' . $id_activo;

        if (!$folderPath || !is_dir($folderPath)) {
            return $this->response->setStatusCode(404, 'Carpeta no encontrada.');
        }

        // Crear un archivo ZIP temporal
        $zip = new ZipArchive();
        $zipFileName = WRITEPATH . 'uploads/carpeta_mobiliario_' . $id_activo . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $this->addSingleFolderToZip($folderPath, $zip);
            $zip->close();

            // Registrar una función para eliminar el archivo después de que se complete la descarga
            register_shutdown_function(function () use ($zipFileName) {
                if (file_exists($zipFileName)) {
                    unlink($zipFileName);
                }
            });

            // Enviar el archivo ZIP al cliente
            return $this->response->download($zipFileName, null)->setFileName('carpeta_mobiliario_' . $id_activo . '.zip');
        } else {
            return $this->response->setStatusCode(500, 'No se pudo crear el archivo ZIP.');
        }
    }


    private function addSingleFolderToZip($folderPath, $zip)
    {
        // Obtener solo los archivos de la carpeta principal (sin subcarpetas)
        $files = scandir($folderPath);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;

                // Solo agregar archivos, no carpetas
                if (is_file($filePath)) {
                    $zip->addFile($filePath, $file);
                }
            }
        }
    }

    /*******************************
     * RUTAS para ADM. DE PERSONAL *
     *******************************/

    public function viewAdmPago()
    {
        return ($this->is_logged) ? view('finance/view_solicitud_pago') : redirect()->to(site_url());
    }

    public function viewAdmAprobar()
    {
        return ($this->is_logged) ? view('finance/view_aprobar_solicitud') : redirect()->to(site_url());
    }

    public function viewAdmAutorizar()
    {
        return ($this->is_logged) ? view('finance/view_autorizar_solicitud') : redirect()->to(site_url());
    }

    public function viewAdmPagar()
    {
        return ($this->is_logged) ? view('finance/view_pagar_solicitud') : redirect()->to(site_url());
    }

    public function viewAdmPagarFinanzas()
    {
        return ($this->is_logged) ? view('finance/view_pagar_solicitud_blanca') : redirect()->to(site_url());
    }

    public function downloadDatas($id_request)
    {

        // Obtener la ruta de la carpeta basada en el ID
        $folderPath = '../public/pagos_adm/solicitud_' . $id_request;

        if (!$folderPath || !is_dir($folderPath)) {
            return $this->response->setStatusCode(404, 'Carpeta no encontrada.');
        }

        // Crear un archivo ZIP temporal
        $zip = new ZipArchive();
        $zipFileName = WRITEPATH . 'uploads/solicitud_' . $id_request . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $this->addSingleFolderToZip2($folderPath, $zip);
            $zip->close();

            // Registrar una función para eliminar el archivo después de que se complete la descarga
            register_shutdown_function(function () use ($zipFileName) {
                if (file_exists($zipFileName)) {
                    unlink($zipFileName);
                }
            });

            // Enviar el archivo ZIP al cliente
            return $this->response->download($zipFileName, null)->setFileName('solicitud_' . $id_request . '.zip');
        } else {
            return $this->response->setStatusCode(500, 'No se pudo crear el archivo ZIP.');
        }
    }

    private function addSingleFolderToZip2($folderPath, $zip)
    {
        // Obtener solo los archivos de la carpeta principal (sin subcarpetas)
        $files = scandir($folderPath);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;

                // Excluir el archivo 'index.html' y solo agregar archivos, no carpetas
                if ($file !== 'index.html' && is_file($filePath)) {
                    $zip->addFile($filePath, $file);
                }
            }
        }
    }




    public function uploadPdf()
    {
        $pdfFile = $this->request->getFile('pdfFile');


        if ($pdfFile->isValid() && !$pdfFile->hasMoved()) {
            $fileName = $pdfFile->getName();

            // Reemplazar espacios por guiones bajos
            $fileName = str_replace(' ', '_', $fileName);

            $pdfFile->move(FCPATH . 'PDF/pdfs/', $fileName);


            return $this->response->setJSON(['pdfUrl' => base_url('public/PDF/pdfs/' . $fileName)]);
        } else {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No se pudo subir el archivo.']);
        }
    }



    public function signPdf()
    {
        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI/src/autoload.php';

        $pdfUrl = $this->request->getPost('pdfPath');
        $id_request = $this->request->getPost('id_request');

        $id_user = session()->id_user;

        if (empty($pdfUrl) || empty($id_request)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Datos incompletos']);
        }

        $relativePath = parse_url($pdfUrl, PHP_URL_PATH);
        $filePath = FCPATH . ltrim(str_replace('/public', '', $relativePath), '/');

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Archivo PDF no encontrado en el servidor']);
        }

        // **Guardar el nombre y la ruta original antes de la conversión**
        $originalFilePath = $filePath; // Guarda la ruta original antes de modificarla
        $originalFileName = pathinfo($filePath, PATHINFO_FILENAME);
        $originalExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        // Convertir el PDF a un formato compatible usando Ghostscript
        $outputFile = tempnam(sys_get_temp_dir(), 'pdf');
        exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -o $outputFile $filePath");

        if (!file_exists($outputFile)) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al convertir el PDF']);
        }

        $filePath = $outputFile; // Nuevo archivo después de la conversión

        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(StreamReader::createByFile($filePath));

            if (!$pageCount) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al cargar el archivo PDF']);
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, 0, 0, 210, 297);

                $signatureImage = '../public/images/firmas_users/50/50.png';

                /*   if ($id_user == 265) {
                    $signatureImage = '../public/images/firmas_users/62779/62779.png';
                } else {
                    $signatureImage = '../public/images/firmas_users/50/50.png';
                } */



                if (file_exists($signatureImage)) {
                    // Insertar la imagen de la firma
                    $signatureX = 90;  // Posición X de la firma
                    $signatureY = 237; // Posición Y de la firma
                    $signatureWidth = 30;
                    $signatureHeight = 30;
                    $pdf->Image($signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);

                    // Agregar la fecha a la misma altura que la firma
                    $pdf->SetFont('Helvetica', '', 8);
                    $pdf->SetTextColor(0, 0, 0);

                    // Ajustar la posición de la fecha a la misma altura que la firma
                    $xDate = $signatureX + $signatureWidth - 40;  // Colocar la fecha a la derecha de la firma
                    $yDate = $signatureY + 21; // Centrar verticalmente con la firma



                    $pdf->SetXY($xDate, $yDate);
                    $pdf->Cell(10, 5, "Fecha: " . date("Y-m-d H:i:s"), 0, 0, 'L');
                    $pdf->SetXY($xDate, $yDate + 3);
                    // $pdf->Cell(10, 5, utf8_decode("Lic.Ma Alejandra Enriquez Reyes"), 0, 0, 'L');
                } else {
                    return $this->response->setStatusCode(404)->setJSON(['error' => 'La imagen de la firma no existe']);
                }
            }

            $fileNameWithoutExt = str_replace(['_aprobado', '_autorizado'], '', $originalFileName);
            $newFileName = "{$fileNameWithoutExt}_aprobado.{$originalExtension}";

            $saveDirectory = FCPATH . 'pagos_adm/solicitud_' . $id_request . '/';

            if (!is_dir($saveDirectory)) {
                mkdir($saveDirectory, 0777, true);
            }

            $signedPdfPath = $saveDirectory . $newFileName;
            $pdf->Output($signedPdfPath, 'F');

            $newFileName2 = 'pagos_adm/solicitud_' . $id_request . '/' . $newFileName;
            $data = [
                'firm_status' => 2,
                'file_ruta' => $newFileName2,
                'file_name' => $newFileName
            ];
            $file_type = 1;

            $modelItem = new ArchivosAdmModel();
            $signed = $modelItem->updateRequest($id_request, $file_type, $data);

            // **Eliminar el archivo original después de firmarlo**
            if (file_exists($originalFilePath)) {
                unlink($originalFilePath);
            }

            // **Eliminar el archivo temporal generado por Ghostscript**
            if (file_exists($outputFile)) {
                unlink($outputFile);
            }

            return $this->response->setJSON([
                'signedPdfUrl' => base_url('public/pagos_adm/solicitud_' . $id_request . '/' . $newFileName),
                'signed' => $signed
            ]);
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'El PDF usa una compresión no compatible.']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al procesar el PDF: ' . $e->getMessage()]);
        }
    }


    public function signPdfAutorize()
    {
        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI/src/autoload.php';

        $pdfUrl = $this->request->getPost('pdfPath');
        $id_request = $this->request->getPost('id_request');

        if (empty($pdfUrl) || empty($id_request)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Datos incompletos']);
        }

        $relativePath = parse_url($pdfUrl, PHP_URL_PATH);
        $filePath = FCPATH . ltrim(str_replace('/public', '', $relativePath), '/');

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Archivo PDF no encontrado en el servidor']);
        }

        // **Guardar el nombre y la ruta original antes de la conversión**
        $originalFilePath = $filePath; // Guarda la ruta original antes de modificarla
        $originalFileName = pathinfo($filePath, PATHINFO_FILENAME);
        $originalExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        // Convertir el PDF a un formato compatible usando Ghostscript
        $outputFile = tempnam(sys_get_temp_dir(), 'pdf');
        exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -o $outputFile $filePath");

        if (!file_exists($outputFile)) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al convertir el PDF']);
        }

        $filePath = $outputFile; // Nuevo archivo después de la conversión

        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(StreamReader::createByFile($filePath));

            if (!$pageCount) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al cargar el archivo PDF']);
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, 0, 0, 210, 297);

                $signatureImage = '../public/images/firmas_users/784/firma.png';


                if (file_exists($signatureImage)) {
                    //$pdf->Image($signatureImage, 140, 216, 40, 30);

                    // Insertar la imagen de la firma
                    $signatureX = 148;  // Posición X de la firma
                    $signatureY = 238; // Posición Y de la firma
                    $signatureWidth = 25;
                    $signatureHeight = 22;
                    $pdf->Image($signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);

                    // Agregar la fecha a la misma altura que la firma
                    $pdf->SetFont('Helvetica', '', 8);
                    $pdf->SetTextColor(0, 0, 0);


                    // Ajustar la posición de la fecha a la misma altura que la firma
                    $xDate = $signatureX + $signatureWidth - 40;  // Colocar la fecha a la derecha de la firma
                    $yDate = $signatureY + 22; // Centrar verticalmente con la firma
                    $pdf->SetXY($xDate, $yDate);
                    $pdf->Cell(10, 5, "Fecha: " . date("Y-m-d H:i:s"), 0, 0, 'L');
                } else {
                    return $this->response->setStatusCode(404)->setJSON(['error' => 'La imagen de la firma no existe']);
                }
            }

            $fileNameWithoutExt = str_replace(['_aprobado', '_autorizado'], '', $originalFileName);
            $newFileName = "{$fileNameWithoutExt}_autorizado.{$originalExtension}";

            $saveDirectory = FCPATH . 'pagos_adm/solicitud_' . $id_request . '/';

            if (!is_dir($saveDirectory)) {
                mkdir($saveDirectory, 0777, true);
            }

            $signedPdfPath = $saveDirectory . $newFileName;
            $pdf->Output($signedPdfPath, 'F');

            $newFileName2 = 'pagos_adm/solicitud_' . $id_request . '/' . $newFileName;
            $data = [
                'firm_status' => 3,
                'file_ruta' => $newFileName2,
                'file_name' => $newFileName
            ];
            $file_type = 1;

            $modelItem = new ArchivosAdmModel();
            $signed = $modelItem->updateRequest($id_request, $file_type, $data);

            // **Eliminar el archivo original solo si NO es el nuevo autorizado**
            if ($originalFilePath !== $signedPdfPath && file_exists($originalFilePath)) {
                unlink($originalFilePath);
            }

            // **Eliminar el archivo temporal generado por Ghostscript**
            if (file_exists($outputFile)) {
                unlink($outputFile);
            }


            return $this->response->setJSON([
                'signedPdfUrl' => base_url('public/pagos_adm/solicitud_' . $id_request . '/' . $newFileName),
                'signed' => $signed
            ]);
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'El PDF usa una compresión no compatible.']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al procesar el PDF: ' . $e->getMessage()]);
        }
    }

    public function savePaymentRequestAdm()
    {
        $date = date("Y-m-d H:i:s");
        $idUser = session()->id_user;
        $request = $this->request;
        $user_name   = session()->name . " " . session()->surname;
        $xls = '';

        $empresa = $request->getPost('empresa');

        if ($empresa === 'Walworth Valvulas S.A. de C.V.') {
            $quincena = ["TXT"];
        } else {
            $quincena = ["EXCEL", "TXT"];
        }

        if ($empresa === 'Industrial de Valvulas S.A de C.V.') {
            $semanal = [];
        } else {
            $semanal = ["TXT"];
        }

        $validation = \Config\Services::validation();

        // Lista de tipos de archivo por concepto
        $inputsPorConcepto = [
            "NOMINA SEMANAL" => ["TXT"],
            "NOMINA QUINCENAL" => $quincena,
            "PENSION ALIMENTICIA" => ["EXCEL"],
            "FONDO DE AHORRO SEMANAL" => [],
            "FONDO DE AHORRO FINIQUITOS" => [],
            "CAJA DE AHORROS SINDICALIZADOS" => [],
            "DEVOLUCIÓN CAJA DE AHORROS FINIQUITOS" => ["EXCEL"],
            "SUA" => ["SUA"],
            "REPARTO DE UTILIDADES" => ["EXCEL", "TXT"],
            "PAGO DE ARBITRAJES" => ["PDF"],
            "PAGO NOMINA ESQUEMA" => ["PDF", "XML", "EXCEL"],
            "PAGO BECAS" => ["PDF"],
            "PAGO UNIFORMES FUTBOL" => ["PDF"],
            "AYUDA DECEMBRINA" => ["PDF"],
            "REPARTO DE UTILIDADES BAJAS" => ["EXCEL"],
            "PAYNEFITS" => ["PDF"],
        ];

        $concepto = $request->getPost('concepto');

         $archivosRequeridos = $inputsPorConcepto[$concepto] ?? [];
       

         $cuenta_contable = [
            "NOMINA SEMANAL"  => '213159-0000',
            "NOMINA QUINCENAL"  => '213173-0000',
            "PENSION ALIMENTICIA"  => '213155-0000',
            "CUOTA SINDICAL SEMANAL"  => '213154-0000',
            "CUOTA SINDICAL EVENTOS SOCIALES"  => '213154-0000',
            "AYUDA SINDICAL EVENTOS ESPECIALES"  => '213154-0000',
            "FONACOT"  => '213147-0000',
            "FONDO DE AHORRO SEMANAL"  => '213153-0000',
            "FONDO DE AHORRO FINIQUITOS"  => '213153-0000',
            "CAJA DE AHORROS SINDICALIZADOS"  => '213157-0000',
            "VALES DESPENSA"  => 0,
            "PAGO CUOTAS IMSS"  => 0,
            "CAJA DE AHORROS EMPLEADOS"  => '213152-0000',
            "DEVOLUCIÓN CAJA DE AHORROS FINIQUITOS"  => 0,
            "PRESTAMOS NOMINOM"  => '213175-0000',
            "OPTICA OCULAR"  => '213171-0000',
            "PAGO DE FINIQUITOS"  => '213163-0000',
            "PAGARÉS"  => 0,
            "AYUDA POR DEFUNCIÓN"  => '213156-0000',
            "APOYO POR DEFUNCIÓN"  => 0,
            "AYUDA SINDICAL VARIOS"  => 0,
            "TARJETAS VALES DE DESPENSA"  => 0,
            "CAJA DE AHORRO EMPLEADOS"  => '213152-0000',
            "DEVOLUCION FONDO DE AHORRO (BAJA)"  =>  0,
            "REPARTO DE UTILIDADES"  => '213151-0000',
            "PAGO DE ARBITRAJES"  => 0,
            "PAGO NOMINA ESQUEMA"  => 0,
            "PAGO BECAS"  => 0,
            "PAGO UNIFORMES FUTBOL"  => 0,
            "AYUDA DECEMBRINA"  => 0,
            "REPARTO DE UTILIDADES BAJAS"  => '213151-0000',
            "PAYNEFITS"  => '213180-0000',
        ];


        $cuentaContable = $cuenta_contable[$concepto] ?? 0;

        // Definir reglas de validación generales
        //'tipo_nomina'   => 'required',
        //    'periodo'       => 'required',
        $rules = [
            'empresa'       => 'required',
            'concepto'      => 'required',
            'mes_solicitud' => 'required',

            'fecha'         => 'required|valid_date',
            'monto'         => 'required',
            'observaciones' => 'permit_empty',
            'pdfFile'       => 'uploaded[pdfFile]|max_size[pdfFile,2048]|ext_in[pdfFile,pdf]',
        ];

        if (in_array("EXCEL", $archivosRequeridos)) {
            $archivo = $request->getFile('xlsFile');

            // Validar solo si el archivo NO es XLSM
            if ($archivo->isValid() && !$archivo->hasMoved()) {
                $extension = strtolower($archivo->getClientExtension());
                if ($extension !== 'xlsm') {
                    $rules['xlsFile'] = 'uploaded[xlsFile]|max_size[xlsFile,2048]|ext_in[xlsFile,xlsx,xls]';
                }
            }
        }

        if (in_array("TXT", $archivosRequeridos)) {
            $rules['archivo-TXT'] = 'uploaded[archivo-TXT]|max_size[archivo-TXT,1024]|ext_in[archivo-TXT,txt,TXT]';
        }

        if (in_array("SUA", $archivosRequeridos)) {
            $rules['archivo-SUA'] = 'uploaded[archivo-SUA]|max_size[archivo-SUA,2048]|ext_in[archivo-SUA,sua,SUA]';
        }
        if (in_array("PDF", $archivosRequeridos)) {
            $rules['pdfsFile'] = 'uploaded[pdfsFile]|max_size[pdfsFile,2048]|ext_in[pdfsFile,pdf,PDF]';
        }
        if (in_array("XML", $archivosRequeridos)) {
            $rules['xmlFile'] = 'uploaded[xmlFile]|max_size[xmlFile,2048]|ext_in[xmlFile,xml,XML]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        // Guardar solicitud
        $solicitudModel = new SolicitudAdmModel();
        $monto = floatval(str_replace(',', '', $request->getPost('monto')));

        $solicitudData = [
            'company'           => $request->getPost('empresa'),
            'application_concept' => $concepto,
            'cuenta_contable'     => $cuentaContable,
            'month'             => $request->getPost('mes_solicitud'),
            'type_of_payroll'   => $request->getPost('tipo_nomina') || "",
            'period'            => $request->getPost('periodo') || "",
            'date_request'      => $request->getPost('fecha'),
            'amount'            => $monto,
            'comment'           => $request->getPost('observaciones'),
            'created_at'        => $date,
            'id_user'           => $idUser,
        ];



        $solicitudId = $solicitudModel->insert($solicitudData, true);
        if (!$solicitudId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al guardar la solicitud'
            ]);
        }
        if ($request->getPost('tipo_pago') !== null) {
            # code...

            $data = [
                'tipo_pago'       => $this->request->getPost('tipo_pago'),
                'nombre_empresa'  => $this->request->getPost('nombre_empresa'),
                'banco'           => $this->request->getPost('banco'),
                'cuenta'          => $this->request->getPost('cuenta'),
                'clabe'           => $this->request->getPost('clabe'),
                'cantidad'        => (float) str_replace(',', '', $this->request->getPost('monto')),
                'cantidad_letra'  => $this->request->getPost('cantidad_letra'),
                'concepto'        => $this->request->getPost('observaciones'),
                'created_at'      => $date,
                'id_user'         => $idUser,
                'user_name'       => $user_name,
            ];

            // === 2) Guardar solicitud y generar PDF interno ===
            $pdfPaths = $this->generarAdmPDF($data, $solicitudId);
            $solicitudModel->update($solicitudId, [
                'ruta_pdf' => $pdfPaths['path_public']
            ]);

            // 3) Procesar archivos PDF desde un único input multiple name="pdfFiles[]"
            //$pdfFiles = $request->getFiles('pdfFile');
            $pdfFiles = $this->request->getFileMultiple('pdfFile');

            $rutas = [];
            // PDF generado por ti
            if (file_exists($pdfPaths['path_full'])) {
                $rutas[] = $pdfPaths['path_full'];
            }
            $uploadTemp = WRITEPATH . 'uploads/adm_tmp/solicitud_' . $solicitudId . '/';
            if (! is_dir($uploadTemp)) {
                mkdir($uploadTemp, 0755, true);
            }

            foreach ($pdfFiles as $file) {
                if ($file->isValid() && ! $file->hasMoved()) {
                    $randomName = $file->getName();
                    $file->move($uploadTemp, $randomName);
                    $rutas[] = $uploadTemp . $randomName;
                }
            }
            if (empty($rutas)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'No se subieron archivos PDF válidos.'
                ]);
            }
            // 4) Desencriptar PDFs si es necesario
            $rutasParaCombinar = [];
            foreach ($rutas as $path) {
                try {
                    $rutasParaCombinar[] = $this->decryptPdf($path);
                } catch (\RuntimeException $e) {
                    log_message('warning', "decryptPdf falló en $path: " . $e->getMessage());
                    $rutasParaCombinar[] = $path;
                }
            }
            // 5) Combinar PDFs
            try {
                $pdfFinalPublic = $this->combinarPDFAdm($rutasParaCombinar, $solicitudId);
            } catch (\RuntimeException $e) {
                return $this->response->setStatusCode(500)
                    ->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
            }

            // 6) Limpieza de archivos temporales
            /*  foreach (array_merge($rutasParaCombinar) as $tempPath) {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }  */





            // 7) Guardar registro del PDF combinado en BD
            $archivoModel = new ArchivosAdmModel();
            $uploadPath = 'pagos_adm/solicitud_' . $solicitudId . '/';
            $archivoModel->insert([
                'id_request' => $solicitudId,
                'file_name'  => $pdfPaths['file_name'],
                'file_ruta'  => $pdfFinalPublic,
                'file_type'  => 1
            ]);
        } else {
            $archivoModel = new ArchivosAdmModel();
            $uploadPath = 'pagos_adm/solicitud_' . $solicitudId . '/';
            $xls = 'pdfFile';
        }


        $archivos = ['xlsFile', 'archivo-TXT', 'archivo-SUA', 'pdfsFile', 'xmlFile'];

        foreach ($archivos as $input) {
            $archivo = $request->getFile($input);

            if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
                $nombreArchivo = $archivo->getName();
                $extension     = strtolower($archivo->getClientExtension());

                // Asignar tipo basado en el campo (input)
                switch ($input) {
                    case 'pdfFile':
                        $file_type = 1;
                        break;
                    case 'xlsFile':
                        $file_type = 2;
                        break;
                    case 'archivo-TXT':
                        $file_type = 3;
                        break;
                    case 'archivo-SUA':
                        $file_type = 4;
                        break;
                    case 'xmlFile':
                        $file_type = 5;
                        break;
                    case 'pdfsFile':
                        $file_type = 6;
                        break;
                    default:
                        $file_type = 0; // tipo desconocido
                        break;
                }

                $archivo->move(FCPATH . $uploadPath, $nombreArchivo);

                $archivoModel->insert([
                    'id_request' => $solicitudId,
                    'file_name'  => $nombreArchivo,
                    'file_ruta'  => $uploadPath . $nombreArchivo,
                    'file_type'  => $file_type
                ]);
            }
        }

        $this->emailNotificationAdm($solicitudData);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Solicitud guardada con éxito',
            'id'      => $solicitudId
        ]);
    }


    private function generarAdmPDF($data, $idSolicitud)
    {
        $id_user = session()->id_user;

        $html2 = view('pdf/pdf_template_adm', $data);

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Solicitud de Pago');

        $html2pdf->writeHTML($html2);



        switch ($id_user) {
            case 50:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/50/50.png';
                break;
            case 267:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/267/267.png';
                break;
            case 265:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/62779/62779.png';
                break;

            default:
                $signatureImage = '';
                // Si no hay firma específica, puedes dejarlo vacío o asignar una firma predeterminada
                break;
        }

        if (file_exists($signatureImage) && $html2pdf->pdf->PageNo() === 1) {
            $signatureX = 28;    // Ajusta según el diseño
            $signatureY = 238;
            $signatureWidth = 38;
            $signatureHeight = 30;

            $html2pdf->pdf->Image($signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
        }

        // Ruta donde se guardará el PDF
        $pdfDir = FCPATH . 'pagos_adm/solicitud_' . $idSolicitud . '/';
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }

        $pdfFileName = 'solicitud_' . $idSolicitud . '.pdf';
        $pdfFullPath = $pdfDir . $pdfFileName;

        $html2pdf->output($pdfFullPath, 'F');

        return [
            'path_public' => 'public/pagos_adm/solicitud_' . $idSolicitud . '/' . $pdfFileName,
            'path_full'   => $pdfFullPath,
            'file_name'   => $pdfFileName
        ];
    }

    /**
     * Combina PDFs (ya desencriptados si hacía falta) usando FPDI avanzado.
     *
     * @throws \RuntimeException
     */
    private function combinarPDFAdm(array $archivos, int $idSolicitud): string
    {
        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI-master/src/autoload.php'; // O vendor/autoload.php

        $pdf               = new Fpdi();
        $tempReprocessed   = [];

        foreach ($archivos as $file) {
            if (! file_exists($file)) {
                continue;
            }

            try {
                // Primer intento directo
                $reader    = StreamReader::createByFile($file);
                $pageCount = $pdf->setSourceFile($reader);
            } catch (CrossReferenceException $e) {
                // Reprocesamos con qpdf/gs y volvemos a intentar
                log_message('warning', "FPDI falló con {$file}, reprocesando: {$e->getMessage()}");
                $reproc = $this->reprocessPdf($file);
                $tempReprocessed[] = $reproc;

                // Reintento
                $reader    = StreamReader::createByFile($reproc);
                $pageCount = $pdf->setSourceFile($reader);
            }

            // Agregamos sus páginas
            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
        }

        // Guardar resultado
        $dir = FCPATH . "pagos_adm/solicitud_{$idSolicitud}/";
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $name     = "solicitud_{$idSolicitud}.pdf";
        $fullPath = $dir . $name;
        $public   = "pagos_adm/solicitud_{$idSolicitud}/{$name}";
        $pdf->Output($fullPath, 'F');

        // Limpiar temporales de reprocesado
        foreach ($tempReprocessed as $tmp) {
            @unlink($tmp);
        }

        return $public;
    }

    public function getRequests()
    {

        $model = new SolicitudAdmModel();
        //$data = $model->where('active_status', 1)->findAll();
        $data = $model->getSolicitudesDePago(); // Llamamos al método con JOIN

        return $this->response->setJSON(['data' => $data]);
    }

    public function getRequestsAprove()
    {

        $model = new SolicitudAdmModel();
        $data = $model->getSolicitudesParaAprobacion(); // Llamamos al método con JOIN

        return $this->response->setJSON(['data' => $data]);
    }

    public function getRequestsAutorize()
    {

        $model = new SolicitudAdmModel();
        $data = $model->getSolicitudesParaAutorizacion(); // Llamamos al método con JOIN

        return $this->response->setJSON(['data' => $data]);
    }

    public function getRequestsTopay()
    {

        $model = new SolicitudAdmModel();
        $data = $model->getSolicitudesParaPago(); // Llamamos al método con JOIN

        return $this->response->setJSON(['data' => $data]);
    }

    public function getPaidRequests()
    {

        $model = new SolicitudAdmModel();
        $data = $model->getSolicitudesPagadas(); // Llamamos al método con JOIN

        return $this->response->setJSON(['data' => $data]);
    }

    private function emailNotificationAdm($data = null)
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
            $mail->setFrom('requisiciones@walworth.com', 'Solicitud de pago');

            // $mail->addAddress('aenriquez@walworth.com.mx', 'Alejandra Enriquez');
            $mail->addAddress('gmartinez@walworth.com.mx', 'Guadalupe Martinez');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Solicitud de Pago';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/solicitud_pago_adm', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function deletePaymentRequest()
    {
        try {
            $model = new SolicitudAdmModel();
            $this->db->transStart();
            $date = date("Y-m-d H:i:s");
            $id_request = trim($this->request->getPost('id_request'));
            $data = ["active_status" => 2, "id_user_delete" => session()->id_user, "date_delete" => $date];
            $result = $model->update($id_request, $data);

            $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }


    public function rejectPaymentRequest()
    {
        try {
            $model = new SolicitudAdmModel();
            $this->db->transStart();
            $date = date("Y-m-d H:i:s");
            $id_request = trim($this->request->getPost('id_request'));
            $data = ["status_request" => 5, "id_user_reject" => session()->id_user, "date_reject" => $date];
            $result = $model->update($id_request, $data);

            // Obtener el documento desde la base de datos
            $documento = $model->getinfoSolicitud($id_request); // Llamamos al método con JOIN


            $this->enviarNotificacionRechazar($documento);




            $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }


    public function authorizationPdf()
    {

        $id_request = trim($this->request->getPost('id_request'));
        $pdfPath = trim($this->request->getPost('pdfPath'));

        $model = new SolicitudAdmModel();

        // Obtener el documento desde la base de datos
        $documento = $model->getVerificarSolicitud($id_request); // Llamamos al método con JOIN

        if ($documento) {
            // Verificar si el estatus es diferente de 1
            if ($documento[0]['firm_status'] != 1) {

                $date = date("Y-m-d H:i:s");

                $data = ["status_request" => 3];
                $model->update($id_request, $data);

                // Generar notificación por correo
                $this->enviarNotificacionAutorizar($documento[0], $pdfPath);

                // Responder con éxito
                return $this->response->setJSON([
                    'status' => true,
                    'message' => "Se ha solicitado la autorización de la solicitud."
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "El documento no ha sido aprobado, por lo que no se puede solicitar autorización."
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => "Documento no encontrado."
            ]);
        }
    }

    public function payApplicationPdf()
    {

        $id_request = trim($this->request->getPost('id_request'));
        $pdfPath = trim($this->request->getPost('pdfPath'));

        $model = new SolicitudAdmModel();

        // Obtener el documento desde la base de datos
        $documento = $model->getVerificarSolicitud($id_request); // Llamamos al método con JOIN

        if ($documento) {

            // Verificar si el estatus es diferente de 1
            if ($documento[0]['firm_status'] == 3) {

                $date = date("Y-m-d H:i:s");

                $data = ["status_request" => 2];
                $model->update($id_request, $data);

                // Generar notificación por correo
                $this->enviarNotificacionPagarSolicitud($documento[0], $pdfPath);

                // Responder con éxito
                return $this->response->setJSON([
                    'status' => true,
                    'message' => "Se ha Solicitado el Pago de la Solicitud."
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "El Documento no ha sido Firmado por el Autorizador, por lo que no se puede solicitar pagar la solicitud."
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => "Documento no encontrado."
            ]);
        }
    }
    public function uploadReceipt()
    {
        $date = date("Y-m-d H:i:s");
        $idUser = session()->id_user;

        $id_request = $this->request->getPost('id_request');
        $comentario = $this->request->getPost('comentario');
        $file = $this->request->getFile('comprobante');

        if (!$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Archivo no válido']);
        }

        // Guardar archivos
        $archivoModel = new ArchivosAdmModel();


        // Definir la carpeta donde se guardará el archivo
        $newName = $file->getName(); // Mantiene el nombre original
        $uploadPath = 'pagos_adm/solicitud_' . $id_request . '/';
        $file->move(FCPATH . $uploadPath, $newName); // Guarda el archivo en 'writable/uploads/'

        // Guardar en BD
        $archivoModel->insert([
            'id_request' => $id_request,
            'file_name'  => $newName,
            'file_ruta'  => $uploadPath . $newName,
            'file_type'  => 5
        ]);

        $data = [
            'status_request' => 4,
            'id_user_pago' => $idUser,
            'date_pago' => $date,
            'comentario_pago' => $comentario

        ];

        $model = new SolicitudAdmModel();
        $signed = $model->updateRequest($id_request, $data);

        $data = $model->notificaUsuario($id_request);

        $email = $data[0]['email']; // Acceder al campo 'email' del primer resultado

        // Generar notificación por correo
        $this->enviarNotificacionPago($email, $data);
        // Aquí puedes guardar la info en la base de datos si lo necesitas

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Archivo subido correctamente',
            'filePath' => base_url('pagos_adm/solicitud_' . $id_request . '/' . $newName),
            'comentario' => $comentario
        ]);
    }

    private function enviarNotificacionPagarSolicitud($data = null, $pdfUrl = null)
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
            $mail->setFrom('requisiciones@walworth.com', 'Solicitud de pago');

            //$mail->addAddress('aenriquez@walworth.com.mx', 'Alejandra Enriquez');
            //$mail->addAddress('gmartinez@walworth.com.mx', 'Guadalupe Martinez');
            $mail->addAddress('mflores@walworth.com.mx', 'Martha Flores');
            //$mail->addAddress('eperez@walworth.com.mx', 'Enrico Perez');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Pagar Solicitud';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            $mail->addAttachment($pdfUrl);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/solicitar_pago_adm', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    private function enviarNotificacionAutorizar($data = null, $pdfUrl = null)
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
            $mail->setFrom('requisiciones@walworth.com', 'Solicitud de pago');

            $mail->addAddress('eperez@walworth.com.mx', 'Enrico Perez');
            //$mail->addAddress('gmartinez@walworth.com.mx', 'Guadalupe Martinez');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Autorizar Solicitud de Pago';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            $mail->addAttachment($pdfUrl);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/autorizar_pago_adm', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    private function enviarNotificacionRechazar($data = null)
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
            $mail->setFrom('requisiciones@walworth.com', 'Solicitud de pago');

            //$mail->addAddress('aenriquez@walworth.com.mx', 'Alejandra Enriquez');
            $mail->addAddress('gmartinez@walworth.com.mx', 'Guadalupe Martinez');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Solicitud Rechazada';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment($pdfUrl);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/rechazar_pago_adm', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    private function enviarNotificacionPago($email, $data = null)
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
            $mail->setFrom('requisiciones@walworth.com', 'Solicitud Pagada');

            //$mail->addAddress('aenriquez@walworth.com.mx', 'Alejandra Enriquez');
            $mail->addAddress('eperez@walworth.com.mx', 'Enrico Perez');
            //$mail->addAddress('bpedraza@walworth.com.mx', 'Blanca Pedraza');
            $mail->addAddress('dprado@walworth.com.mx', 'David Prado');
            //$mail->addAddress('rcruz@walworth.com.mx', 'Guadalupe Martinez');
            if ($email == 'bmartinez@grupowalworth.com') {
                $email = 'bmartinez@walworth.com.mx';
            }

            $mail->addAddress($email, 'Usuario');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Pago Realizado';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment($pdfUrl);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/solicitud_pagado_adm', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function markAsCompleted()
    {
        // Logic to mark a payment request as completed
        $id = $this->request->getPost('id_request');
        $realizada = $this->request->getPost('realizada');

        $model = new SolicitudAdmModel();
        $model->update($id, ['realizada' => $realizada]);

        return $this->response->setJSON(['status' => 'ok']);
    }
    public function updateIdEpicor()
    {
        // Logic to update the Epicor ID for a payment request
        $id = $this->request->getPost('id_requests');
        $epicorId = $this->request->getPost('id_epicor');

        $model = new SolicitudAdmModel();
        $result = $model->update($id, ['id_epicor' => $epicorId]);

        return ($result) ? $this->response->setJSON(['success' => true, 'message' => 'ID Epicor actualizado correctamente']) : $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar ID Epicor']);
    }

    /*******************************
     * RUTAS para TALENTO *
     *******************************/

    public function viewTalentPago()
    {
        return ($this->is_logged) ? view('finance/view_solicitud_pago_talento') : redirect()->to(site_url());
    }

    public function viewAutorizarPago()
    {
        return ($this->is_logged) ? view('finance/view_autorizar_pago_talento') : redirect()->to(site_url());
    }

    public function viewPagarSolicitudes()
    {
        return ($this->is_logged) ? view('finance/view_pagar_solicitudes_talento') : redirect()->to(site_url());
    }

    public function viewPagoSolicitudesTalento()
    {
        return ($this->is_logged) ? view('finance/view_pago_solicitud_blanca') : redirect()->to(site_url());
    }
    /****************************pruebas de codigo ***************************************************************/

    public function savePaymentRequestTalent()
    {
        // === 1) Datos y validación ===
        $date        = date("Y-m-d H:i:s");
        $idUser      = session()->id_user;
        $user_name   = session()->name . " " . session()->surname;
        $modelAuth   = new TalentAuthorizeModel();
        $solicitudM  = new SolicitudTalentoModel();

        $status_pdf = 1; // 1 = PDF generado, 2 = PDF no generado

        if ($idUser == 863) {
            $status_pdf = 2; // PDF no generado
        }


        $data = [
            'tipo_pago'       => $this->request->getPost('tipo_pago'),
            'nombre_empresa'  => $this->request->getPost('nombre_empresa'),
            'banco'           => $this->request->getPost('banco'),
            'cuenta'          => $this->request->getPost('cuenta'),
            'clabe'           => $this->request->getPost('clabe'),
            'cantidad'        => (float) str_replace(',', '', $this->request->getPost('cantidad')),
            'cantidad_letra'  => $this->request->getPost('cantidad_letra'),
            'concepto'        => $this->request->getPost('concepto'),
            'created_at'      => $date,
            'id_user'         => $idUser,
            'user_name'       => $user_name,
            'status_pdf'      => $status_pdf,
            'empresas'        => $this->request->getPost('empresas'),
        ];

        $validation = \Config\Services::validation();
        $rules = [
            'nombre_empresa'  => 'required',
            'banco'           => 'required',
            'cantidad'        => 'required',
            'cantidad_letra'  => 'required',
            'tipo_pago'       => 'required',
            'concepto'        => 'required',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        // === 2) Guardar solicitud y generar PDF interno ===
        $solicitudM->insert($data);
        $idSolicitud = $solicitudM->insertID();

        $pdfPaths = $this->generarTalentoPDF($data, $idSolicitud);
        $solicitudM->update($idSolicitud, [
            'ruta_pdf' => $pdfPaths['path_public']
        ]);

        // === 3) Subir los archivos del formulario ===
        $files   = $this->request->getFiles();
        $rutas   = [];

        // PDF generado por ti
        if (file_exists($pdfPaths['path_full'])) {
            $rutas[] = $pdfPaths['path_full'];
        }

        // Al menos estos tres; si necesitas más, agrégalos al array
        foreach (['orden_compra', 'factura', 'caratula'] as $key) {
            if (! empty($files[$key]) && $files[$key]->isValid() && ! $files[$key]->hasMoved()) {
                $name = $files[$key]->getRandomName();
                $files[$key]->move(WRITEPATH . 'uploads', $name);
                $rutas[] = WRITEPATH . 'uploads/' . $name;
            }
        }

        // === 4) Desencriptar cada PDF via qpdf ===
        $rutasParaCombinar = [];
        foreach ($rutas as $pdfFile) {
            try {
                $rutasParaCombinar[] = $this->decryptPdf($pdfFile);
            } catch (\RuntimeException $e) {
                log_message('warning', $e->getMessage());
                // si falla, seguimos con el original
                $rutasParaCombinar[] = $pdfFile;
            }
        }

        // === 5) Combinar todos los PDFs desencriptados ===
        try {
            $pdfFinalPublic = $this->combinarPDFs($rutasParaCombinar, $idSolicitud);
        } catch (\RuntimeException $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status'  => 'error',
                    'message' => $e->getMessage()
                ]);
        }

        // === 6) Limpieza de temporales ===
        foreach (array_merge($rutas, $rutasParaCombinar) as $tmp) {
            if (file_exists($tmp) && strpos($tmp, sys_get_temp_dir()) === 0) {
                @unlink($tmp);
            } elseif (file_exists($tmp) && strpos($tmp, WRITEPATH . 'uploads/') === 0) {
                @unlink($tmp);
            }
        }

        // === 7) Notificación al autorizador ===
        $idAuth = $modelAuth->buscarAutorizador($idUser);
        $email  = $this->db
            ->query("SELECT email FROM tbl_users WHERE id_user=? AND active_status=1", [$idAuth])
            ->getRow('email');

        $notifyData = [
            'id_request'      => $idSolicitud,
            'tipo_pago'       => $data['tipo_pago'],
            'nombre_empresa'  => $data['nombre_empresa'],
            'banco'           => $data['banco'],
            'cuenta'          => $data['cuenta'],
            'clabe'           => $data['clabe'],
            'cantidad'        => $data['cantidad'],
            'cantidad_letra'  => $data['cantidad_letra'],
            'concepto'        => $data['concepto'],
            'created_at'      => $date,
            'id_user'         => $idUser,
        ];

        if ($email) {
            $this->enviarNotificacionAutorizarTalento($notifyData, $email);
        } else {
            $solicitudM->update($idSolicitud, ['request_status' => 2]);
            //correo de Enrico Perez
            $this->enviarNotificacionAutorizarTalento($notifyData, 'eperez@walworth.com.mx');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Solicitud guardada con éxito',
            'pdf'     => $pdfFinalPublic
        ]);
    }

    /**
     * Desencripta un PDF usando qpdf y devuelve la ruta temporal.
     *
     * @throws \RuntimeException
     */
    protected function decryptPdf(string $inputPath): string
    {
        $tmp = sys_get_temp_dir() . '/pdf_decrypted_' . basename($inputPath);
        $cmd = sprintf(
            'qpdf --decrypt %s %s 2>&1',
            escapeshellarg($inputPath),
            escapeshellarg($tmp)
        );
        exec($cmd, $out, $ret);
        if ($ret !== 0 || ! file_exists($tmp)) {
            throw new \RuntimeException("qpdf falló desencriptando {$inputPath}: " . implode("\n", $out));
        }
        return $tmp;
    }

    /**
     * Reprocesa un PDF eliminando compresión o seguridad.
     * Primero intenta con qpdf, si falla usa Ghostscript.
     *
     * @throws \RuntimeException
     */
    protected function reprocessPdf(string $inputPath): string
    {
        $tmp = sys_get_temp_dir() . '/pdf_proc_' . uniqid() . '.pdf';

        // 1) Intentamos qpdf
        exec('which qpdf', $out, $ret);
        if ($ret === 0) {
            exec(
                sprintf(
                    'qpdf --qdf --object-streams=disable %s %s 2>&1',
                    escapeshellarg($inputPath),
                    escapeshellarg($tmp)
                ),
                $output,
                $retQ
            );
            if ($retQ === 0 && file_exists($tmp)) {
                return $tmp;
            }
            log_message('warning', "qpdf falló: " . implode("\n", $output));
        }

        // 2) Fallback a Ghostscript
        exec('which gs', $out2, $ret2);
        if ($ret2 === 0) {
            exec(
                sprintf(
                    'gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=%s %s 2>&1',
                    escapeshellarg($tmp),
                    escapeshellarg($inputPath)
                ),
                $output2,
                $retG
            );
            if ($retG === 0 && file_exists($tmp)) {
                return $tmp;
            }
            log_message('warning', "Ghostscript falló: " . implode("\n", $output2));
        }

        throw new \RuntimeException(
            "No se pudo reprocesar PDF “{$inputPath}”: "
                . implode("\n", array_merge($output ?? [], $output2 ?? []))
        );
    }

    /**
     * Combina PDFs (ya desencriptados si hacía falta) usando FPDI avanzado.
     *
     * @throws \RuntimeException
     */
    private function combinarPDFs(array $archivos, int $idSolicitud): string
    {
        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI-master/src/autoload.php'; // O vendor/autoload.php

        $pdf               = new Fpdi();
        $tempReprocessed   = [];

        foreach ($archivos as $file) {
            if (! file_exists($file)) {
                continue;
            }

            try {
                // Primer intento directo
                $reader    = StreamReader::createByFile($file);
                $pageCount = $pdf->setSourceFile($reader);
            } catch (CrossReferenceException $e) {
                // Reprocesamos con qpdf/gs y volvemos a intentar
                log_message('warning', "FPDI falló con {$file}, reprocesando: {$e->getMessage()}");
                $reproc = $this->reprocessPdf($file);
                $tempReprocessed[] = $reproc;

                // Reintento
                $reader    = StreamReader::createByFile($reproc);
                $pageCount = $pdf->setSourceFile($reader);
            }

            // Agregamos sus páginas
            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl  = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
        }

        // Guardar resultado
        $dir = FCPATH . "finanzas/solicitudes_pago/solicitud_{$idSolicitud}/";
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $name     = "solicitud_{$idSolicitud}.pdf";
        $fullPath = $dir . $name;
        $public   = "public/finanzas/solicitudes_pago/solicitud_{$idSolicitud}/{$name}";
        $pdf->Output($fullPath, 'F');

        // Limpiar temporales de reprocesado
        foreach ($tempReprocessed as $tmp) {
            @unlink($tmp);
        }

        return $public;
    }





    /*********************************fin */



    private function combinarPDFsOriginal(array $archivos, $idSolicitud)
    {
        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI/src/autoload.php';

        $pdf = new Fpdi();

        foreach ($archivos as $archivo) {
            if (!file_exists($archivo)) continue;

            $pageCount = $pdf->setSourceFile($archivo);

            for ($i = 1; $i <= $pageCount; $i++) {
                $templateId = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        $pdfDir = FCPATH . '/finanzas/solicitudes_pago/solicitud_' . $idSolicitud . '/';
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0777, true); // Crear la carpeta si no existe
        }

        $pdfFileName = 'solicitud_' . $idSolicitud . '.pdf';
        $pdfFullPath = $pdfDir . $pdfFileName;

        // Guardar el PDF en el servidor
        $pdf->output($pdfFullPath, 'F');

        // Retornar la ruta del PDF
        return 'public/finanzas/solicitudes_pago/solicitud_' . $idSolicitud . '/' . $pdfFileName;
    }

    /**
     * Ejecuta qpdf para quitar cualquier cifrado o restricción.
     *
     * @param  string  $inputPath   Ruta al PDF original.
     * @return string               Ruta al PDF desencriptado.
     * @throws \RuntimeException    Si falla la desencriptación.
     */
    protected function decryptPdfANT(string $inputPath): string
    {
        $decryptedPath = sys_get_temp_dir()
            . '/pdf_decrypted_'
            . basename($inputPath);

        // Montamos el comando qpdf (sin password, asume que solo tiene owner password)
        $cmd = sprintf(
            'qpdf --decrypt %s %s 2>&1',
            escapeshellarg($inputPath),
            escapeshellarg($decryptedPath)
        );

        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0 || ! file_exists($decryptedPath)) {
            throw new \RuntimeException(
                "No se pudo desencriptar PDF “{$inputPath}”: "
                    . implode("\n", $output)
            );
        }

        return $decryptedPath;
    }



    private function combinarPDFsANT(array $archivos, $idSolicitud)
    {
        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI/src/autoload.php';

        try {
            $pdf = new Fpdi();

            foreach ($archivos as $archivo) {
                if (! file_exists($archivo)) {
                    continue;
                }

                // Crea el reader para soportar más esquemas de compresión
                $reader = StreamReader::createByFile($archivo);
                $pageCount = $pdf->setSourceFile($reader);

                for ($i = 1; $i <= $pageCount; $i++) {
                    $templateId = $pdf->importPage($i);
                    $size       = $pdf->getTemplateSize($templateId);

                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($templateId);
                }
            }

            $pdfDir = FCPATH . 'finanzas/solicitudes_pago/solicitud_' . $idSolicitud . '/';
            if (! is_dir($pdfDir)) {
                mkdir($pdfDir, 0777, true);
            }

            $pdfFileName  = 'solicitud_' . $idSolicitud . '.pdf';
            $pdfFullPath  = $pdfDir . $pdfFileName;
            $pdf->Output($pdfFullPath, 'F');

            // Retornamos la ruta pública
            return 'public/finanzas/solicitudes_pago/solicitud_'
                . $idSolicitud . '/' . $pdfFileName;
        } catch (CrossReferenceException $e) {
            // Logging interno
            log_message(
                'error',
                "FPDI CrossReferenceException: {$e->getMessage()}"
            );

            // Devolvemos JSON con status 500 y mensaje claro
            return service('response')
                ->setStatusCode(500, 'Error al combinar PDFs')
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Uno de los archivos PDF utiliza un formato de compresión no soportado.'
                ]);
        }
    }



    private function generarTalentoPDF($data, $idSolicitud)
    {
        $id_user = session()->id_user;

        $html2 = view('pdf/pdf_template', $data);

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Solicitud de Pago');

        $html2pdf->writeHTML($html2);



        switch ($id_user) {
            case 903:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/903/903.png';
                break;
            case 75:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/75/75.png';
                break;
            case 1292:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/1292/1292.png';
                break;
            case 44:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/44/44.png';
                break;
            case 50:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/50/50.png';
                break;
            case 267:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/267/267.png';
                break;
            case 346:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/346/346.png';
                break;
            case 303:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/303/303.png';
                break;
            case 294:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/294/294.png';
                break;
            case 277:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/277/277.png';
                break;
            case 343:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/343/343.png';
                break;
            case 31:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/31/31.png';
                break;
            case 1334:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = '../public/images/firmas_users/1334/1334.png';
                //$nombre = 'Alan Landa - Gerente de TI';
                break;
            case 1152:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = '../public/images/firmas_users/1152/1152.png';
                //$nombre = 'Alan Landa - Gerente de TI';
                break;
            case 863:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = '../public/images/firmas_users/863/863.png';
                // $nombre = 'Alan Landa - Gerente de TI';
                break;
            case 26:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/26/26.png';
                break;
            case 35:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/35/35.png';
                break;
            case 1297:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/1297/1297.png';
                break;
            case 1385:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/1385/1385.png';
                break;
            case 353:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/353/353.png';
                break;
            case 268:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/268/268.png';
                break;
            case 1152:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/1152/1152.png';
                break;
            case 303:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/303/303.png';
                break;
            case 329:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/329/329.png';
                break;
            case 1299:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/1299/1299.png';
                break;
            case 1396:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/1396/1396.png';
                break;
            case 868:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/868/868.png';
                break;
            case 1302:
                // ✅ Insertar firma solo si es la primera página
                $signatureImage = FCPATH . 'images/firmas_users/1302/1302.png';
                break;




            default:
                $signatureImage = '';
                // Si no hay firma específica, puedes dejarlo vacío o asignar una firma predeterminada
                break;
        }

        if (file_exists($signatureImage) && $html2pdf->pdf->PageNo() === 1) {
            $signatureX = 28;    // Ajusta según el diseño
            $signatureY = 238;
            $signatureWidth = 38;
            $signatureHeight = 30;

            $html2pdf->pdf->Image($signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
        }

        // Ruta donde se guardará el PDF
        $pdfDir = FCPATH . 'finanzas/solicitudes_pago/solicitud_' . $idSolicitud . '/';
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }

        $pdfFileName = 'solicitud_' . $idSolicitud . '.pdf';
        $pdfFullPath = $pdfDir . $pdfFileName;

        $html2pdf->output($pdfFullPath, 'F');

        return [
            'path_public' => 'public/finanzas/solicitudes_pago/solicitud_' . $idSolicitud . '/' . $pdfFileName,
            'path_full'   => $pdfFullPath
        ];
    }




    public function getRequestTalent()
    {

        $idUser = session()->id_user;

        // Consulta todos los usuarios subordinados al usuario actual (incluyéndose a sí mismo)
        $builder = $this->db->table('tbl_talent_authorize_request');
        $subQuery = $builder->select('id_solicitante')
            ->where('id_autorizador', $idUser)
            ->get()
            ->getResultArray();

        $ids = array_column($subQuery, 'id_solicitante');
        $ids[] = $idUser; // incluir también al jefe

        // Ahora haces la consulta sobre el modelo original
        $model = new SolicitudTalentoModel();

        if ($idUser == 1) {
            $query = $model->where('active_status', 1);
        } else {
            $query = $model->where('active_status', 1)
                ->whereIn('id_user', $ids);
        }

        $data = $query->findAll();

        return $this->response->setJSON(['data' => $data]);
    }

    public function signPdfTalento()
    {
        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI/src/autoload.php';
        $id_user = session()->id_user;

        $pdfUrl = $this->request->getPost('pdfPath');
        $id_request = $this->request->getPost('id_request');

        if (empty($pdfUrl) || empty($id_request)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Datos incompletos']);
        }

        $relativePath = parse_url($pdfUrl, PHP_URL_PATH);
        $filePath = FCPATH . ltrim(str_replace('/public', '', $relativePath), '/');

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Archivo PDF no encontrado en el servidor']);
        }

        // **Guardar el nombre y la ruta original antes de la conversión**
        $originalFilePath = $filePath;
        $originalFileName = pathinfo($filePath, PATHINFO_FILENAME);
        $originalExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        // Convertir el PDF a un formato compatible usando Ghostscript
        $outputFile = tempnam(sys_get_temp_dir(), 'pdf');
        exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -o $outputFile $filePath");

        if (!file_exists($outputFile)) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al convertir el PDF']);
        }

        $filePath = $outputFile;

        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(StreamReader::createByFile($filePath));

            if (!$pageCount) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al cargar el archivo PDF']);
            }

            $builder = $this->db->table('tbl_talent_payment_request r');

            $result = $builder
                ->select("
                            r.id_request,
                            r.id_user,
                            CASE
                                WHEN r.id_user = {$id_user} THEN 'mismo'
                                ELSE 'diferente'
                            END AS tipo_usuario
                        ")
                ->where('r.id_request', $id_request)
                ->get()
                ->getRow();

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, 0, 0, 210, 297);




                $signatureImage = '';
                $nombre = '';
                switch ($id_user) {

                    case 22:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/22/22.png';
                        $nombre = 'Diego Nava - Jefe de Ventas OM & CSA';
                        break;
                    case 26:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/26/26.png';
                        $nombre = 'Blanca Pedraza - Jefe de Contabilidad e Impuestos';
                        break;
                    case 27:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/27/27.png';
                        //$nombre = 'Karen Rubio - Jefe de Gestion de Talento';
                        break;
                    case 50:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/50/50.png';
                        $nombre = 'Guadalupe Martinez';
                        break;
                    case 75:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/75/75.png';
                        //$nombre = 'Karen Rubio - Jefe de Gestion de Talento';
                        break;
                    case 258:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/258/258.png';
                        $nombre = 'Gabriel Rojas - Gerente de Ventas';
                        break;
                    case 259:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/259/259.png';
                        $nombre = 'Sergio Tlatepa - Gerente de Calidad';
                        break;
                    case 272:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/272/272.png';
                        // $nombre = 'Abraham Sernas - Gerente de Almacenes y Logística';
                        break;
                    case 294:
                        // ✅ Insertar firma solo si es la primera página 1
                        $signatureImage = '../public/images/firmas_users/294/294.png';
                        $nombre = 'David Prado - Encargado de Impuestos';
                        break;
                    case 346:
                        // ✅ Insertar firma solo si es la primera página
                        $signatureImage = '../public/images/firmas_users/346/346.png';
                        $nombre = 'Carlos Meza - Jefe Licitaciones Gobierno';
                        break;
                    case 1283:
                        // ✅ Insertar firma solo si es la primera página
                        $signatureImage = '../public/images/firmas_users/1283/1283.png';
                        $nombre = 'Gerardo Mendoza - Coordinador de SG';
                        break;
                    case 1390:
                        // ✅ Insertar firma solo si es la primera página
                        $signatureImage = '../public/images/firmas_users/1390/1390.png';
                        // $nombre = 'Alan Landa - Gerente de TI';
                        break;

                    case 268:
                        // ✅ Insertar firma solo si es la primera página
                        $signatureImage = '../public/images/firmas_users/268/268.png';
                        $nombre = 'Marco Iñiguez - Gerente de Compras';
                        break;
                    case 353:
                        // ✅ Insertar firma solo si es la primera página
                        $signatureImage = '../public/images/firmas_users/353/353.png';
                        $nombre = 'Zeferino Melgarejo - Jefe de Compras WA';
                        break;
                    case 1385:
                        // ✅ Insertar firma solo si es la primera página
                        $signatureImage = '../public/images/firmas_users/1385/1385.png';
                        $nombre = 'Marisol Prieto - Comprador Sr';
                        break;
                    case 151:
                        // ✅ Insertar firma solo si es la primera página
                        $signatureImage = '../public/images/firmas_users/151/151.png';
                        $nombre = 'Monserrat Sanchez - Director de Control Interno';
                        break;
                    case 1361:
                        // ✅ Insertar firma solo si es la primera página
                        $signatureImage = '../public/images/firmas_users/1361/1361.png';
                        // $nombre = 'María del Carmen - Jefe de Finanzas';
                        break;

                    default:
                        $signatureImage = ''; // Firma predeterminada
                        $nombre = 'Firma no disponible';
                        break;
                }



                if (file_exists($signatureImage)) {

                    if ($result->tipo_usuario == 'mismo') {
                    } else {

                        // Insertar la imagen de la firma
                        $signatureX = 90;  // Posición X de la firma
                        $signatureY = 235; // Posición Y de la firma
                        if ($id_user == 1361) {
                            $signatureY = 240;  // Posición X de la firma
                            $signatureWidth = 45;  // Ajusta según el diseño
                            $signatureHeight = 22;
                        } else {
                            $signatureWidth = 40;  // Ajusta según el diseño
                            $signatureHeight = 40;
                        }


                        $pdf->Image($signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);

                        // Agregar la fecha a la misma altura que la firma
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetTextColor(0, 0, 0);

                        // Ajustar la posición de la fecha a la misma altura que la firma
                        $xDate = $signatureX + $signatureWidth - 50;  // Colocar la fecha a la derecha de la firma}
                        if ($id_user == 1361) {
                            $yDate = $signatureY + 23; // Centrar verticalmente con la firma
                        } else {
                            $yDate = $signatureY + 28; // Centrar verticalmente con la firma
                        }

                        $pdf->SetXY($xDate, $yDate);
                        $pdf->Cell(10, 5, "Fecha: " . date("Y-m-d H:i:s"), 0, 0, 'L');

                        // Agregar la fecha a la misma altura que la firma
                        $pdf->SetFont('Helvetica', '', 8);
                        $pdf->SetTextColor(0, 0, 0);

                        $pdf->SetXY($xDate - 5, $yDate + 3);
                        $pdf->Cell(10, 5, $nombre, 0, 0, 'L');
                    }


                    if ($result->tipo_usuario == 'mismo') {

                        $signatureX = 28;    // Ajusta según el diseño
                        $signatureY = 235;
                        $signatureWidth = 40;
                        $signatureHeight = 40;

                        $pdf->Image($signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);
                    }
                } else {
                    return $this->response->setStatusCode(404)->setJSON(['error' => 'La imagen de la firma no existe']);
                }
            }

            $fileNameWithoutExt = str_replace(['_aprobado', '_autorizado'], '', $originalFileName);
            $newFileName = "{$fileNameWithoutExt}_aprobado.{$originalExtension}";

            $saveDirectory = FCPATH . 'finanzas/solicitudes_pago/solicitud_' . $id_request . '/';

            if (!is_dir($saveDirectory)) {
                mkdir($saveDirectory, 0777, true);
            }

            $signedPdfPath = $saveDirectory . $newFileName;
            $pdf->Output($signedPdfPath, 'F');

            $newFileName2 = 'public/finanzas/solicitudes_pago/solicitud_' . $id_request . '/' . $newFileName;
            $data = [
                'status_pdf' => 2,
                'ruta_pdf' => $newFileName2
            ];

            $modelItem = new SolicitudTalentoModel();
            $signed = $modelItem->updateRequest($id_request, $data);

            // **Eliminar el archivo original si NO tiene _aprobado ni _autorizado**
            if (!preg_match('/(_aprobado|_autorizado)/', $originalFileName)) {
                if (file_exists($originalFilePath)) {
                    unlink($originalFilePath);
                }
            }

            // **Eliminar el archivo temporal generado por Ghostscript**
            if (file_exists($outputFile)) {
                unlink($outputFile);
            }

            return $this->response->setJSON([
                'signedPdfUrl' => base_url('public/finanzas/solicitudes_pago/solicitud_' . $id_request . '/' . $newFileName),
                'signed' => $signed
            ]);
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'El PDF usa una compresión no compatible.']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al procesar el PDF: ' . $e->getMessage()]);
        }
    }

    public function authorizationTalentoPdf()
    {

        $id_request = trim($this->request->getPost('id_request'));
        $pdfPath = trim($this->request->getPost('pdfPath'));

        $model = new SolicitudTalentoModel();

        // Obtener el documento desde la base de datos
        $documento = $model->getVerificarSolicitud($id_request); // Llamamos al método con JOIN

        if ($documento) {
            // Verificar si el estatus es diferente de 1
            if ($documento[0]['status_pdf'] != 1) {

                $date = date("Y-m-d H:i:s");

                $data = ["request_status" => 2];
                $model->update($id_request, $data);

                /* $builder = $this->db->table('tbl_talent_authorize_request ta');
                $subQuery = $builder->select('u.email')
                    ->join('tbl_users u', 'u.id_user = ta.id_autorizador')
                    ->where('ta.id_solicitante', $documento[0]['id_user'])
                    ->where('ta.active_status', 1)
                    ->get()
                    ->getResultArray(); */

                // $autorizadorEmail = 'rcruz@walworth.com.mx';
                $autorizadorEmail = 'eperez@walworth.com.mx';

                $data =  $documento[0];


                // Generar notificación por correo
                if ($autorizadorEmail) {
                    $this->enviarNotificacionAutorizarTalento($data, $autorizadorEmail);
                }


                // Responder con éxito
                return $this->response->setJSON([
                    'status' => true,
                    'message' => "Se ha solicitado la autorización de la solicitud."
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "El documento no ha sido firmado, por lo que no se puede solicitar Autorización."
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => "Documento no encontrado."
            ]);
        }
    }

    private function enviarNotificacionAutorizarTalento($data = null, $email = null)
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
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Autorización de pago');

            if ($email == 'krubio@grupowalworth.com') {
                $email = 'krubio@walworth.com.mx';
            }

            if ($email == 'msanchez@grupowalworth.com') {
                $email = 'msanchez@walworth.com.mx';
            }

            //$mail->addAddress('eperez@walworth.com.mx', 'Enrico Perez');
            $mail->addAddress($email, 'Autorizador');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Autorizar Solicitud de Pago';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');

            //$mail->addBCC('rcruz@walworth.com.mx');

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/solicitud_pagado_talento', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function requestTable()
    {


        $model = new SolicitudTalentoModel();
        $data = $model->getSolicitudesPago(); // Llamamos al método con JOIN

        return $this->response->setJSON(['data' => $data]);
    }

    public function getAuthorizePayments()
    {


        $id_request = trim($this->request->getPost('id_request'));
        $pdfPath = trim($this->request->getPost('pdfPath'));

        $model = new SolicitudAdmModel();

        // Obtener el documento desde la base de datos
        $documento = $model->getVerificarSolicitud($id_request); // Llamamos al método con JOIN

        if ($documento) {
            // Verificar si el estatus es diferente de 1
            if ($documento[0]['firm_status'] == 3) {

                $date = date("Y-m-d H:i:s");

                $data = ["status_request" => 2];
                $model->update($id_request, $data);

                // Generar notificación por correo
                $this->enviarNotificacionPagar($documento[0], $pdfPath);

                // Responder con éxito
                return $this->response->setJSON([
                    'status' => true,
                    'message' => "Se ha Solicitado el Pago de la Solicitud."
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "El Documento no ha sido Firmado por el Autorizador, por lo que no se puede solicitar pagar la solicitud."
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => "Documento no encontrado."
            ]);
        }
    }

    public function signPdfAutorizeEnrico()
    {
        $signModel = new CodigosTalentoModel();

        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI/src/autoload.php';

        $pdfUrl = $this->request->getPost('pdfPath');
        $idSolicitud = $this->request->getPost('id_request');
        $now = date('Y-m-d H:i:s');

        if (empty($pdfUrl) || empty($idSolicitud)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Datos incompletos']);
        }

        // Verificar si el código es correcto

        /* $existing = $signModel
            ->where('id_request', $idSolicitud)
            ->where('signed', 0)
            ->where('expires_at >=', $now)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'No se encontró ninguna solicitud válida']);
        }

        $data = [
            'signed' => 1,
            'signed_at' => $now,
            'signed_by' => session()->id_user,
        ];

        $signed = $signModel->update($existing['id_code'], $data);
 */


        $relativePath = parse_url($pdfUrl, PHP_URL_PATH);
        $filePath = FCPATH . ltrim(str_replace('/public', '', $relativePath), '/');

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Archivo PDF no encontrado en el servidor']);
        }

        // Guardar la ruta original
        $originalFilePath = $filePath;
        $originalFileName = pathinfo($filePath, PATHINFO_FILENAME);
        $originalExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        // Generar el nombre del archivo autorizado
        $fileNameWithoutExt = str_replace(['_aprobado', '_autorizado'], '', $originalFileName);
        $newFileName = "{$fileNameWithoutExt}_autorizado.{$originalExtension}";

        $saveDirectory = FCPATH . 'finanzas/solicitudes_pago/solicitud_' . $idSolicitud . '/';

        if (!is_dir($saveDirectory)) {
            mkdir($saveDirectory, 0777, true);
        }

        $signedPdfPath = $saveDirectory . $newFileName;

        // Si ya existe un archivo autorizado, usarlo como base
        if (file_exists($signedPdfPath)) {
            $filePath = $signedPdfPath;
        }

        // Convertir el PDF a un formato compatible usando Ghostscript
        $outputFile = tempnam(sys_get_temp_dir(), 'pdf') . ".pdf";
        exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -o $outputFile $filePath");

        if (!file_exists($outputFile)) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al convertir el PDF']);
        }

        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(StreamReader::createByFile($outputFile));

            if (!$pageCount) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al cargar el archivo PDF']);
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($templateId, 0, 0, 210, 297);

                $signatureImage = '../public/images/firmas_users/784/firma.png';

                /*  if (file_exists($signatureImage)) {
                    $pdf->Image($signatureImage, 150, 246, 28, 25);
                } else {
                    return $this->response->setStatusCode(404)->setJSON(['error' => 'La imagen de la firma no existe']);
                } */

                if (file_exists($signatureImage)) {
                    // Insertar la imagen de la firma
                    $signatureX = 155;  // Posición X de la firma
                    $signatureY = 240; // Posición Y de la firma
                    $signatureWidth = 25;
                    $signatureHeight = 22;
                    $pdf->Image($signatureImage, $signatureX, $signatureY, $signatureWidth, $signatureHeight);

                    // Agregar la fecha a la misma altura que la firma
                    $pdf->SetFont('Helvetica', '', 10);
                    $pdf->SetTextColor(0, 0, 0);

                    // Ajustar la posición de la fecha a la misma altura que la firma
                    $xDate = $signatureX + $signatureWidth - 40;  // Colocar la fecha a la derecha de la firma
                    $yDate = $signatureY + 22; // Centrar verticalmente con la firma

                    $pdf->SetXY($xDate, $yDate);
                    $pdf->Cell(10, 5, "Fecha: " . date("Y-m-d H:i:s"), 0, 0, 'L');
                } else {
                    return $this->response->setStatusCode(404)->setJSON(['error' => 'La imagen de la firma no existe']);
                }
            }

            // Guardar el nuevo archivo firmado en la misma ruta
            $pdf->Output($signedPdfPath, 'F');

            $newFileName2 = 'public/finanzas/solicitudes_pago/solicitud_' . $idSolicitud . '/' . $newFileName;
            $data = [
                'status_pdf' => 3,
                'request_status' => 2,
                'ruta_pdf' => $newFileName2,
                'name_pdf' => $newFileName
            ];

            $modelItem = new SolicitudTalentoModel();
            $signed = $modelItem->updateRequest($idSolicitud, $data);

            // **Eliminar el archivo original solo si NO es el nuevo autorizado**
            if ($originalFilePath !== $signedPdfPath && file_exists($originalFilePath)) {
                unlink($originalFilePath);
            }

            // **Eliminar el archivo temporal generado por Ghostscript**
            if (file_exists($outputFile)) {
                unlink($outputFile);
            }

            // Enviar notificación
            // $this->emailNotificationPagoTalento($solicitudData);

            return $this->response->setJSON([
                'signedPdfUrl' => base_url('public/finanzas/solicitudes_pago/solicitud_' . $idSolicitud . '/' . $newFileName),
                'signed' => $signed,
                'status' => 'ok',
            ]);
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'El PDF usa una compresión no compatible.']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al procesar el PDF: ' . $e->getMessage()]);
        }
    }

    public function createCode($idSolicitud)
    {
        $signModel = new CodigosTalentoModel();

        $now = date('Y-m-d H:i:s');

        // 1) Buscar un código NO firmado y NO expirado para esta solicitud
        $existingFirm = $signModel->select("tbl_talent_payment_code.*, CONCAT(b.name, ' ', b.surname) AS user_name", false)
            ->join('tbl_users b', 'b.id_user = tbl_talent_payment_code.signed_by', 'left')
            ->where('tbl_talent_payment_code.id_request', $idSolicitud)
            ->where('tbl_talent_payment_code.signed', 1)
            ->orderBy('tbl_talent_payment_code.created_at', 'DESC')
            ->first();

        if ($existingFirm) {
            return $this->response->setJSON([
                'signed_up' => true,
                'user_name' => $existingFirm['user_name'],
            ]);
        }




        // 1) Buscar un código NO firmado y NO expirado para esta solicitud
        $existing = $signModel
            ->where('id_request', $idSolicitud)
            ->where('signed', 0)
            ->where('expires_at >=', $now)
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($existing) {
            // Ya existe un código válido: lo devolvemos directamente
            $codigo = $existing['code'];
            return $this->response->setJSON([
                'reused'    => true
            ]);
        } else {
            // 2) Generar un nuevo código único
            do {
                $codigo = $this->generateAlphaNumCode(6);
                $exists = $signModel->where('code', $codigo)->first();
            } while ($exists);

            // 3) Guardar el nuevo código en BD
            $signModel->insert([
                'id_request' => (int)$idSolicitud,
                'code'       => $codigo,
                'created_at' => $now,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+15 minutes')),
                'signed'     => 0
            ]);
        }

        // 4) Enviar o re-enviar la notificación al correo
        $this->notificacionCrearCodigoTalento($codigo, $idSolicitud);

        // 5) Devolver JSON de éxito con el código y su estado
        return $this->response->setJSON([
            'status'    => 'ok',
            'requestId' => $idSolicitud,
            'code'      => $codigo,
            'expiresAt' => $signModel->where('code', $codigo)->first()['expires_at'],
            'reused'    => $existing ? true : false
        ]);
    }


    /**
     * Genera una cadena alfanumérica de longitud $length
     */
    private function generateAlphaNumCode(int $length = 6): string
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code  = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    private function notificacionCrearCodigoTalento($codigoGenerado = null, $idSolicitud)
    {

        $data = [
            'folio' => $idSolicitud,    // o el folio que corresponda
            'code'  => $codigoGenerado, // tu código aleatorio
            'date'  => date('d/m/Y H:i'),
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
            $mail->setFrom('requisiciones@walworth.com', 'Codigo de autorización.');

            //$mail->addAddress('krubio@grupowalworth.com', 'Karen Rubio');
            $mail->addAddress('eperez@walworth.com.mx', 'Enrico Perez');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Codigo de autorización: ';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment($pdfUrl);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/finanzas_codigo', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }




    public function payApplicationPdfTalento()
    {

        $id_request = trim($this->request->getPost('id_request'));
        $pdfPath = trim($this->request->getPost('pdfPath'));

        $model = new SolicitudTalentoModel();

        // Obtener el documento desde la base de datos
        $documento = $model->getVerificarSolicitud($id_request); // Llamamos al método con JOIN 

        if ($documento) {

            // Verificar si el estatus es diferente de 1
            if ($documento[0]['status_pdf'] == 3) {

                $date = date("Y-m-d H:i:s");

                $data = ["request_status" => 3];
                $model->update($id_request, $data);

                // Generar notificación por correo
                $this->emailNotificationPagoTalento($documento[0], $pdfPath);

                // Responder con éxito
                return $this->response->setJSON([
                    'status' => true,
                    'message' => "Se ha Solicitado el Pago de la Solicitud."
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "El Documento no ha sido Firmado por el Autorizador, por lo que no se puede solicitar pagar la solicitud."
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => "Documento no encontrado."
            ]);
        }
    }



    public function deletePaymentRequestTalent()
    {
        try {
            $modelTalent = new SolicitudTalentoModel();
            $this->db->transStart();
            $date = date("Y-m-d H:i:s");
            $id_request = trim($this->request->getPost('id_request'));
            $data = ["active_status" => 2, "id_user_delete" => session()->id_user, "date_delete" => $date];
            $result = $modelTalent->update($id_request, $data);

            $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }


    public function downloadDataTalent($id_activo)
    {

        // Obtener la ruta de la carpeta basada en el ID
        $folderPath = '../public/finanzas/solicitudes_pago/solicitud_' . $id_activo;

        if (!$folderPath || !is_dir($folderPath)) {
            return $this->response->setStatusCode(404, 'Carpeta no encontrada.');
        }

        // Crear un archivo ZIP temporal
        $zip = new ZipArchive();
        $zipFileName = WRITEPATH . 'uploads/solicitud_talento_' . $id_activo . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $this->addSingleFolderToZip($folderPath, $zip);
            $zip->close();

            // Registrar una función para eliminar el archivo después de que se complete la descarga
            register_shutdown_function(function () use ($zipFileName) {
                if (file_exists($zipFileName)) {
                    unlink($zipFileName);
                }
            });

            // Enviar el archivo ZIP al cliente
            return $this->response->download($zipFileName, null)->setFileName('solicitud_talento_' . $id_activo . '.zip');
        } else {
            return $this->response->setStatusCode(500, 'No se pudo crear el archivo ZIP.');
        }
    }

    public function rejectPaymentRequestTalent()
    {
        try {
            $modelTalent = new SolicitudTalentoModel();
            $this->db->transStart();
            $date = date("Y-m-d H:i:s");
            $id_request = trim($this->request->getPost('id_request'));
            $data = ["request_status" => 5, "id_user_reject" => session()->id_user, "date_reject" => $date];
            $result = $modelTalent->update($id_request, $data);

            // Obtener el documento desde la base de datos
            $documento = $modelTalent->getVerificarSolicitud($id_request); // Llamamos al método con JOIN


            $this->enviarNotificacionRechazarTalento($documento);




            $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    private function enviarNotificacionRechazarTalento($data = null)
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
            $mail->setFrom('requisiciones@walworth.com', 'Solicitud Cancelada');

            $mail->addAddress('krubio@walworth.com.mx', 'Karen Rubio');
            //$mail->addAddress('rcruz@walworth.com.mx', 'Guadalupe Martinez');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Solicitud Rechazada';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment($pdfUrl);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/rechazar_pago_talento', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    private function emailNotificationPagoTalento($data = null)
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
            $mail->setFrom('requisiciones@walworth.com', 'Pagar Solicitud');

            //$mail->addAddress('aenriquez@walworth.com.mx', 'Alejandra Enriquez');
            $mail->addAddress('mflores@walworth.com.mx', 'Martha Flores');
            //$mail->addAddress('gmartinez@walworth.com.mx', 'Guadalupe Martinez');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Realizar Pago de Solicitud';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('rcruz@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/solicitar_pago_talento', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }


    public function getRequestsTopayTalent()
    {

        $model = new SolicitudTalentoModel();
        $data = $model->getSolicitudesParaPagoTalento(); // Llamamos al método con JOIN para solicitudes de talento

        return $this->response->setJSON(['data' => $data]);
    }

    public function uploadReceiptTalento()
    {
        $date = date("Y-m-d H:i:s");
        $idUser = session()->id_user;

        $id_request = $this->request->getPost('id_request');
        $comentario = $this->request->getPost('comentario');
        $file = $this->request->getFile('comprobante');

        if (!$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Archivo no válido']);
        }

        // Guardar archivos
        $archivoModel = new ArchivosTalentoModel();


        // Definir la carpeta donde se guardará el archivo
        $newName = $file->getName(); // Mantiene el nombre original
        $uploadPath = 'finanzas/solicitudes_pago/solicitud_' . $id_request . '/';
        $file->move(FCPATH . $uploadPath, $newName); // Guarda el archivo en 'writable/uploads/'

        // Guardar en BD
        $archivoModel->insert([
            'id_request' => $id_request,
            'file_name'  => $newName,
            'file_ruta'  => $uploadPath . $newName,
            'file_type'  => 5
        ]);

        $data = [
            'request_status' => 4,
            'id_user_pago' => $idUser,
            'date_pago' => $date,
            'comentario_pago' => $comentario

        ];

        $model = new SolicitudTalentoModel();
        $signed = $model->updateRequest($id_request, $data);

        $data = $model->notificaUsuario($id_request);

        $email = $data[0]['email']; // Acceder al campo 'email' del primer resultado

        // Generar notificación por correo
        $this->enviarNotificacionPagoTalento($email, $data);
        // Aquí puedes guardar la info en la base de datos si lo necesitas

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Archivo subido correctamente',
            'filePath' => base_url('finanzas/solicitudes_pago/solicitud_' . $id_request . '/' . $newName),
            'comentario' => $comentario
        ]);
    }

    private function enviarNotificacionPagoTalento($email, $data = null)
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
            $mail->setFrom('requisiciones@walworth.com', 'Solicitud Pagada');


            //$mail->addAddress('bpedraza@walworth.com.mx', 'Blanca Pedraza');
            $mail->addAddress('dprado@walworth.com.mx', 'David Prado');
            $mail->addAddress('ahuerta@walworth.com.mx', 'Alejandro Huerta');

            if ($email == 'bmartinez@grupowalworth.com') {
                $email = 'bmartinez@walworth.com.mx';
            }

            $mail->addAddress($email, 'Usuario');

            $mail->addBCC('rcruz@walworth.com.mx', 'Rafael Cruz');

            $title = 'Pago Realizado';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');


            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/solicitudes_pagadas_talento', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function getPaidRequestsTalento()
    {

        $model = new SolicitudTalentoModel();
        $data = $model->getSolicitudesPagadasTalento(); // Llamamos al método con JOIN

        return $this->response->setJSON(['data' => $data]);
    }

    public function markAsCompletedTalento()
    {
        // Logic to mark a payment request as completed
        $id = $this->request->getPost('id_request');
        $realizada = $this->request->getPost('realizada');

        $model = new SolicitudTalentoModel();
        $model->update($id, ['realizada' => $realizada]);

        return $this->response->setJSON(['status' => 'ok']);
    }
    public function updateIdEpicorTalento()
    {
        // Logic to update the Epicor ID for a payment request
        $id = $this->request->getPost('id_requests');
        $epicorId = $this->request->getPost('id_epicor');

        $model = new SolicitudTalentoModel();
        $result = $model->update($id, ['id_epicor' => $epicorId]);

        return ($result) ? $this->response->setJSON(['success' => true, 'message' => 'ID Epicor actualizado correctamente']) : $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar ID Epicor']);
    }
}
