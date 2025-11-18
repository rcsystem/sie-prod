<?php

/**
 * MODULO DE Vigilancia
 * @version 1.1 pre-prod
 * @author  Rafel Cruz Aguilar <rafel.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 */

namespace App\Controllers\Vigilancia;

use App\Controllers\BaseController;

use App\Models\UsuariosEstacionamientoModel;
use App\Models\userModel;

use ZipArchive;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;



use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;

class Vigilancia extends BaseController
{
	public function __construct()
	{
		require_once APPPATH . '/Libraries/vendor/autoload.php';
		$this->is_logged = session()->is_logged ? true : false;
	}
	public function viewListadoEstacionamientos()
	{
		return ($this->is_logged) ? view('vigilancia/view_listado_estacionamientos') : view('login');
	}

	public function viewScannerQr()
	{
		return ($this->is_logged) ? view('vigilancia/view_scanner_qr') : view('login');
	}

	public function guardarVehiculo()
	{
		$vehiculoModel = new UsuariosEstacionamientoModel();

		$num_nomina   = $this->request->getPost('num_nomina');
		$tipo         = $this->request->getPost('tipo'); // auto, moto, bicicleta
		$placa        = $this->request->getPost('placa');

		// 1. Guardamos primero el registro en BD (sin QR)
		$id = $vehiculoModel->insert([
			'num_nomina' => $num_nomina,
			'tipo'       => $tipo,
			'placa'      => $placa,
		]);

		// 2. Generar código único: usuario + tipo + id_vehiculo
		$codigo = $num_nomina . '|' . $tipo . '|' . $id;

		// 3. Generar el QR
		$result = Builder::create()
			->writer(new PngWriter())
			->data($contenido)
			->encoding(new Encoding('UTF-8'))
			->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
			->size(300)
			->margin(10)
			->build(); // 👈 sin RoundBlockSizeMode

		// 4. Guardar el archivo en public/vigilancia/qr
		$nombreArchivo = $num_nomina . '_' . $tipo . '_' . $id . '.png';
		$rutaArchivo   = FCPATH . 'vigilancia/qr/' . $nombreArchivo;
		$result->saveToFile($rutaArchivo);

		// 5. Actualizar la ruta en BD
		$vehiculoModel->update($id, [
			'ruta_imagen_qr' => 'vigilancia/qr/' . $nombreArchivo
		]);

		return $this->response->setJSON([
			"mensaje" => "Vehículo registrado con QR",
			"qr" => base_url('vigilancia/qr/' . $nombreArchivo)
		]);
	}

	public function buscarUsuario()
	{
		$textoUsuario = trim($this->request->getGet('usuario') ?? '');
		$numeroNomina = trim($this->request->getGet('num_nomina') ?? '');

		$bd = \Config\Database::connect();

		$consulta = $bd->table('tbl_users u')
			->select([
				'u.id_user',
				'u.id_rol',
				'u.payroll_number', // número de nómina
				'u.type_of_employee', // tipo de empleado
				"CONCAT_WS(' ', u.name, u.surname, u.second_surname) AS full_name", // nombre completo
				'c.departament AS departament_name' // nombre del depto
			])
			->join('cat_departament c', 'c.id_depto = u.id_departament', 'left')
			->where('u.active_status', 1);

		if ($numeroNomina !== '') {
			$consulta->where('u.payroll_number', (int) $numeroNomina);
		}

		if ($textoUsuario !== '') {
			$consulta->groupStart()
				->like('u.name', $textoUsuario)
				->orLike('u.surname', $textoUsuario)
				->orLike('u.second_surname', $textoUsuario)
				->orLike("CONCAT_WS(' ', u.name, u.surname, u.second_surname)", $textoUsuario)
				->groupEnd();
		}

		$filas = $consulta->limit(10)->get()->getResultArray();

		return $this->response->setJSON([
			'ok'   => true,
			'data' => $filas, // ⚠️ mantenemos las llaves del JSON tal cual espera tu JS
		]);
	}

	public function tblUsuariosEstacionamientos()
	{
		if ($this->request->isAJAX()) {
			$usuarioModel = new UsuariosEstacionamientoModel();
			$data = $usuarioModel->select('id_usuario, nombre_usuario, departamento, id_rol, ruta_imagen_qr, marbete, modelo, color, tipo, placa, created_at')->where('active_status', 1)->findAll();
			$result = array('data' => $data);
			return $this->response->setJSON($result);
		}
	}



	public function altaUsuarioEstacionamiento()
	{
		if (!$this->request->isAJAX() || $this->request->getMethod() !== 'post') {
			return $this->response->setStatusCode(405)
				->setJSON(['ok' => false, 'mensaje' => 'Método no permitido']);
		}

		$datosPost = $this->request->getPost();

		$validador = \Config\Services::validation();
		$validador->setRules([
			'nombre_usuario' => 'required|min_length[3]',
			'num_nomina'     => 'required|integer',
			'departamento'   => 'required',
			'marbete'        => 'required',
			'modelo'         => 'permit_empty|max_length[80]',
			'color'          => 'permit_empty|max_length[40]',
			'tipo'           => 'permit_empty|max_length[40]',
			'placa'          => 'permit_empty|max_length[40]',
			'id_rol'         => 'permit_empty|integer',
		]);

		if (!$validador->run($datosPost)) {
			return $this->response->setStatusCode(422)
				->setJSON(['ok' => false, 'errores' => $validador->getErrors()]);
		}

		$camposPermitidos = [
			'nombre_usuario',
			'tipo_vehiculo',
			'departamento',
			'num_nomina',
			'marbete',
			'modelo',
			'color',
			'placa',
			'id_rol',
			'tipo',
			'id_user',
		];
		$datosLimpios = array_intersect_key($datosPost, array_flip($camposPermitidos));

		$datosLimpios['num_nomina'] = (int) ($datosLimpios['num_nomina'] ?? 0);
		foreach (['nombre_usuario', 'departamento', 'marbete', 'modelo', 'color', 'tipo', 'placa', 'id_rol', 'id_user'] as $k) {
			if (isset($datosLimpios[$k])) $datosLimpios[$k] = trim((string)$datosLimpios[$k]);
		}
		$datosLimpios['created_at'] = date('Y-m-d H:i:s');

		try {
			$modelo = new UsuariosEstacionamientoModel();
			$id = $modelo->insert($datosLimpios, true);
			if (!$id) {
				return $this->response->setStatusCode(500)
					->setJSON(['ok' => false, 'mensaje' => 'No se pudo guardar.']);
			}

			// === Generar QR con JSON ===
			$datosQR = [
				'id'         => $id,
				'nombre'     => $datosLimpios['nombre_usuario'],
				'num_nomina' => $datosLimpios['num_nomina'],
				'marbete'    => $datosLimpios['marbete'],
				'modelo'     => $datosLimpios['modelo'] ?? '',
				'color'      => $datosLimpios['color'] ?? '',
				'tipo'      => $datosLimpios['tipo'] ?? '',
				'placa'      => $datosLimpios['placa'] ?? '',

			];
			$contenido = json_encode($datosQR, JSON_UNESCAPED_UNICODE);

			$result = Builder::create()
				->writer(new PngWriter())
				->data($contenido)
				->encoding(new Encoding('UTF-8'))
				->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
				->size(300)
				->margin(10)
				->build(); // 👈 sin RoundBlockSizeMode

			$ruta = FCPATH . 'images/vigilancia/qr/';
			if (!is_dir($ruta)) mkdir($ruta, 0777, true);

			$nombreArchivo = 'usuario_' . $id . '.png';
			$result->saveToFile($ruta . $nombreArchivo);

			$modelo->update($id, [
				'ruta_imagen_qr' => 'images/vigilancia/qr/' . $nombreArchivo
			]);

			return $this->response->setJSON([
				'ok' => true,
				'id' => $id,
				'qr' => base_url('images/vigilancia/qr/' . $nombreArchivo)
			]);
		} catch (\Throwable $e) {
			return $this->response->setStatusCode(500)
				->setJSON(['ok' => false, 'mensaje' => $e->getMessage()]);
		}
	}

	public function registrarEntradaSalida()
	{
		$data = $this->request->getJSON(true);

		if (!$data || !isset($data['num_nomina'])) {
			return $this->response->setJSON([
				"ok" => false,
				"mensaje" => "Código inválido"
			]);
		}

		$num_nomina = $data['num_nomina'];
		$id_usuario = $data['id'] ?? null;
		$nombre = $data['nombre'] ?? null;
		$marbete    = $data['marbete'] ?? '';
		$modelo     = $data['modelo'] ?? '';
		$color      = $data['color'] ?? '';
		$placa      = $data['placa'] ?? '';
		$vehiculo       = $data['tipo'] ?? '';

		$accesoModel = new \App\Models\AccesosModel();

		// Buscar último registro de HOY para este usuario
		$hoy = date('Y-m-d');
		$ultimo = $accesoModel
			->where('num_nomina', $num_nomina)
			->where('DATE(scanner_at)', $hoy)
			->orderBy('id_estacionamiento', 'DESC')
			->first();

		// Determinar tipo de movimiento
		if (!$ultimo) {
			$tipo = 'ENTRADA'; // primera vez en el día
		} elseif ($ultimo['estado'] === 'ENTRADA') {
			$tipo = 'SALIDA';
		} else {
			$tipo = 'ENTRADA'; // después de una salida
		}

		// Insertar movimiento
		$accesoModel->insert([
			'id_usuario' => $id_usuario,
			'nombre_usuario' => $nombre,
			'num_nomina' => $num_nomina,
			'marbete'    => $marbete,
			'modelo'     => $modelo,
			'color'      => $color,
			'estado'     => $tipo,
			'placa'      => $placa,
			'tipo'     => $vehiculo,
			'scanner_at' => date('Y-m-d H:i:s'),
		]);


		return $this->response->setJSON([
			"ok" => true,
			"mensaje" => "Se registró la $tipo correctamente"
		]);
	}
	public function tblEstacionamientos()
	{
		if ($this->request->isAJAX()) {
			$accesoModel = new \App\Models\AccesosModel();

			$data = $accesoModel
				->select('id_estacionamiento, nombre_usuario, num_nomina, id_rol, marbete, tipo, placa, estado, modelo, color, scanner_at')
				->where('active_status', 1)
				->orderBy('id_estacionamiento', 'DESC')
				->findAll(1500); // 👈 Límite de 1500

			$result = ['data' => $data];
			return $this->response->setJSON($result);
		}
	}

	public function deleteEstacionamiento($id)
	{
		$accesoModel = new \App\Models\AccesosModel();
		$accesoModel->update($id, [
			'active_status' => 0
		]);
		return $this->response->setJSON([
			"ok" => true,
			"mensaje" => "Registro eliminado"
		]);
	}
}
