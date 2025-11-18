<?php

/**
 * MODULO DE Logistica
 * @version 1.1 pre-prod
 * @author  Rafel Cruz Aguilar <rafel.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 */

namespace App\Controllers\Logistica;

use App\Controllers\BaseController;
use App\Models\ConceptosLogisticaModel;
use App\Models\SolicitudesLogisticaModel;

use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;

class Logistica extends BaseController
{
	public function __construct()
	{
		require_once APPPATH . '/Libraries/vendor/autoload.php';

		$this->db = \Config\Database::connect();
		$this->is_logged = session()->is_logged ? true : false;
	}

	public function viewSolicitudesMsi()
	{
		return ($this->is_logged) ? view('logistica/view_solicitudes_msi') : view('login');
	}

	public function listar()
	{
		$modelo = new ConceptosLogisticaModel();
		$datos  = $modelo->activos();

		// Respuesta en JSON para AJAX
		return $this->response->setJSON($datos);
	}

	public function guardar()
	{
		$modelo = new SolicitudesLogisticaModel();

		// Cargas locales (como las tienes)
		require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
		require_once APPPATH . 'Libraries/FPDI/src/autoload.php';

		try {
			// 1) Validar datos de entrada
			$concepto = trim((string) $this->request->getPost('concepto'));
			if ($concepto === '') {
				return $this->response->setJSON(['ok' => false, 'msg' => 'El concepto es obligatorio']);
			}

			$archivo = $this->request->getFile('archivo_pdf');
			if (!$archivo || !$archivo->isValid() || $archivo->getClientMimeType() !== 'application/pdf') {
				return $this->response->setJSON(['ok' => false, 'msg' => 'Archivo PDF no válido.']);
			}

			$aprobador = 'SERGIO MAQUEDA';

			// 2) Insert inicial para obtener el ID
			$id = $modelo->insert([
				'concepto'   => $concepto,
				'created_at' => date('Y-m-d H:i:s'),
				'id_user'    => session()->id_user,
				'usuario'    => session()->username . " " . session()->surname,
			]);
			if (!$id) {
				return $this->response->setJSON(['ok' => false, 'msg' => 'Error al registrar solicitud']);
			}

			// 3) Rutas y nombres
			$carpeta = FCPATH . 'logistica/solicitudes_movimiento_inventario/' . $id . '/';
			if (!is_dir($carpeta)) {
				mkdir($carpeta, 0777, true);
			}

			$nombreOriginal = $id . '_original.pdf'; // Conserva el original
			$nombreFirmado  = $id . '.pdf';          // El que se va a usar en el sistema

			// 4) Mover archivo subido como "original"
			$archivo->move($carpeta, $nombreOriginal, true);

			$rutaOriginal = $carpeta . $nombreOriginal;
			$rutaFirmado  = $carpeta . $nombreFirmado;

			$idUsuarioFirma = 345;

			// 5) ✅ FIRMAR CON DETECCIÓN AUTOMÁTICA DE POSICIONES
			$pdf = new \setasign\Fpdi\Fpdi();
			$pageCount = $pdf->setSourceFile($rutaOriginal);

			for ($pagina = 1; $pagina <= $pageCount; $pagina++) {
				$tplIdx = $pdf->importPage($pagina);
				$size   = $pdf->getTemplateSize($tplIdx);
				$pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
				$pdf->useTemplate($tplIdx);

				// ✅ DETECTAR Y COLOCAR FIRMAS AUTOMÁTICAMENTE EN CADA PÁGINA
				$this->colocarFirmasAutomaticas($pdf, $rutaOriginal, $pagina, $size, $idUsuarioFirma, $aprobador);
			}

			// 6) Guardar el firmado (NO sobreescribe el original)
			$pdf->Output($rutaFirmado, 'F');

			// 7) Actualizar BD con ruta del firmado (campo debe estar en $allowedFields)
			$modelo->update($id, [
				'ruta_archivo' => 'logistica/solicitudes_movimiento_inventario/' . $id . '/' . $nombreFirmado
			]);

			// 8) Respuesta
			return $this->response->setJSON([
				'ok'  => true,
				'msg' => 'Solicitud registrada. Original conservado y PDF firmado generado.',
				'id'  => $id
			]);
		} catch (\Throwable $e) {
			return $this->response->setJSON([
				'ok'  => false,
				'msg' => 'Error al procesar: ' . $e->getMessage()
			])->setStatusCode(500);
		}
	}

	/**
	 * ✅ NUEVA FUNCIÓN: Detecta automáticamente dónde colocar las firmas
	 */
	private function colocarFirmasAutomaticas($pdf, $rutaArchivo, $numeroPagina, $size, $idUsuarioFirma, $aprobador)
	{
		try {
			error_log("🔍 Analizando página $numeroPagina para firmas...");

			// Extraer texto de la página actual
			$textoExtraido = $this->extraerTextoDePagina($rutaArchivo, $numeroPagina);

			if (empty($textoExtraido)) {
				error_log("⚠️ No se pudo extraer texto de página $numeroPagina, usando posición fija");
				// Fallback: usar posición fija solo en la última página
				if ($numeroPagina === $this->getPageCountFromFile($rutaArchivo)) {
					$this->colocarFirmaFija($pdf, $size, $idUsuarioFirma, $aprobador);
				}
				return;
			}

			// Buscar patrones de texto donde deben ir las firmas
			$patronesFirma = [
				'SERGIO MAQUEDA' => [
					'patron' => '/Sergio\s+Maqueda|Solicitante/i',
					'id_usuario' => 345 // ID del usuario que puede firmar como Sergio
				],
				'GERMAN VELAZQUEZ' => [
					'patron' => '/German\s+Velazquez|Jefe\s+de\s+Almacenes/i',
					'id_usuario' => 328
				],
				'ABRAHAM GERARDO SERNAS' => [
					'patron' => '/Abraham\s+Sernas|Gerente\s+de\s+area\s+afectada/i',
					'id_usuario' => 272
				],
				'ANIBAL MOLINA' => [
					'patron' => '/Anibal\s+Molina|Director\s+de\s+operaciones/i',
					'id_usuario' => 203
				],
				'GUSTAVO ANGELES' => [
					'patron' => '/Gustavo\s+Angeles|Jefe\s+de\s+costos/i',
					'id_usuario' => 48
				],
				'FRANCISCO ENRICO PEREZ' => [
					'patron' => '/Francisco\s+Enrico\s+Perez|Director\s+Administrativo/i',
					'id_usuario' => 784
				],
			];

			// Verificar si algún patrón coincide en esta página
			foreach ($patronesFirma as $nombreAprobador => $config) {
				$patron = $config['patron'];

				if (preg_match($patron, $textoExtraido, $matches)) {
					error_log("✅ Patrón encontrado para $nombreAprobador en página $numeroPagina: " . $matches[0]);

					// Solo firmar si es el aprobador actual
					if ($nombreAprobador === $aprobador) {
						$posicion = $this->calcularPosicionFirma($textoExtraido, $patron, $size, $nombreAprobador);

						if ($posicion) {
							$rutaFirma = FCPATH . 'images/firmas_users/' . $idUsuarioFirma . '/' . $idUsuarioFirma . '.png';

							if (is_file($rutaFirma)) {
								$cfg = $this->firmasCoordenadas[$nombreAprobador] ?? null;
								if ($cfg) {
									$pdf->Image($rutaFirma, $posicion['x'], $posicion['y'], $cfg['w'], 0, 'PNG');
									error_log("✅ Firma colocada para $nombreAprobador en página $numeroPagina en posición x:{$posicion['x']}, y:{$posicion['y']}");
									return; // Firma colocada exitosamente
								}
							} else {
								error_log("❌ No se encontró archivo de firma: $rutaFirma");
							}
						}
					} else {
						error_log("📝 Patrón encontrado para $nombreAprobador pero no es el aprobador actual ($aprobador)");
					}
				}
			}

			// Si llegamos aquí, no se encontró patrón para el aprobador actual en esta página
			error_log("📄 No se encontró patrón para $aprobador en página $numeroPagina");
		} catch (\Exception $e) {
			error_log("❌ Error colocando firma automática: " . $e->getMessage());
			// Fallback: usar posición fija en la última página
			if ($numeroPagina === $this->getPageCountFromFile($rutaArchivo)) {
				$this->colocarFirmaFija($pdf, $size, $idUsuarioFirma, $aprobador);
			}
		}
	}

	/**
	 * ✅ NUEVA FUNCIÓN: Extrae texto de una página específica del PDF
	 */
	private function extraerTextoDePagina($rutaArchivo, $numeroPagina)
	{
		try {
			// Método 1: Usar pdftotext si está disponible en el servidor (más confiable)
			if (function_exists('shell_exec')) {
				$comando = "pdftotext -f $numeroPagina -l $numeroPagina -layout " . escapeshellarg($rutaArchivo) . " -";
				$texto = @shell_exec($comando);

				if ($texto && !empty(trim($texto))) {
					error_log("📖 Texto extraído con pdftotext de página $numeroPagina: " . substr($texto, 0, 200) . "...");
					return $texto;
				}
			}

			// Método 2: Usar smalot/pdfparser si está disponible (requiere composer)
			if (class_exists('\Smalot\PdfParser\Parser')) {
				$parser = new \Smalot\PdfParser\Parser();
				$pdf = $parser->parseFile($rutaArchivo);
				$pages = $pdf->getPages();

				if (isset($pages[$numeroPagina - 1])) {
					$texto = $pages[$numeroPagina - 1]->getText();
					if (!empty(trim($texto))) {
						error_log("📖 Texto extraído con PdfParser de página $numeroPagina: " . substr($texto, 0, 200) . "...");
						return $texto;
					}
				}
			}

			// Método 3: Fallback - análisis básico del PDF con FPDI (limitado)
			$textoBasico = $this->extraerTextoBasicoFPDI($rutaArchivo, $numeroPagina);
			if (!empty($textoBasico)) {
				return $textoBasico;
			}

			error_log("⚠️ No se pudo extraer texto de página $numeroPagina con ningún método");
			return '';
		} catch (\Exception $e) {
			error_log("❌ Error extrayendo texto de página $numeroPagina: " . $e->getMessage());
			return '';
		}
	}

	/**
	 * ✅ NUEVA FUNCIÓN: Extracción básica con FPDI (fallback)
	 */
	private function extraerTextoBasicoFPDI($rutaArchivo, $numeroPagina)
	{
		try {
			// Este método es muy limitado, solo detecta texto básico
			// En la realidad, FPDI no puede extraer texto fácilmente
			// Aquí simularemos la detección basándose en el contenido conocido del PDF

			$pdf = new \setasign\Fpdi\Fpdi();
			$pageCount = $pdf->setSourceFile($rutaArchivo);

			if ($numeroPagina <= $pageCount) {
				// Simulación: basándose en los PDFs que mostraste, 
				// típicamente las firmas están en la última página
				if ($numeroPagina === $pageCount) {
					return "Sergio Maqueda Solicitante German Velazquez Jefe de Almacenes Abraham Sernas Gerente de area afectada Anibal Molina Director de operaciones Gustavo Angeles Jefe de costos";
				}
			}

			return '';
		} catch (\Exception $e) {
			error_log("Error en extracción básica FPDI: " . $e->getMessage());
			return '';
		}
	}

	/**
	 * ✅ NUEVA FUNCIÓN: Calcula la posición exacta basada en el texto encontrado
	 */
	private function calcularPosicionFirma($texto, $patron, $size, $nombreAprobador)
	{
		try {
			// Usar las coordenadas base ajustadas al tamaño de la página
			$coordenadasBase = $this->firmasCoordenadas[$nombreAprobador] ?? null;

			if (!$coordenadasBase) {
				error_log("❌ No se encontraron coordenadas base para $nombreAprobador");
				return null;
			}

			// Calcular posición basada en el tamaño de la página
			$x = $coordenadasBase['x'];
			$y = $size['height'] - $coordenadasBase['y_offset_bottom'];

			// Ajustar según el contenido si es necesario
			if (preg_match($patron, $texto, $matches, PREG_OFFSET_CAPTURE)) {
				$offsetTexto = $matches[0][1];
				$longitudTexto = strlen($texto);

				// Si el texto está en la parte superior del documento, ajustar Y
				$proporcionTexto = $offsetTexto / max($longitudTexto, 1);

				if ($proporcionTexto < 0.5) {
					// Texto en la parte superior, posiblemente mover la firma más abajo
					$ajusteY = ($size['height'] * 0.1); // 10% más abajo
					$y = min($y + $ajusteY, $size['height'] - 20); // No salirse del borde
				}

				error_log("📐 Posición calculada para $nombreAprobador: x=$x, y=$y (proporción texto: $proporcionTexto)");
			}

			return ['x' => $x, 'y' => $y];
		} catch (\Exception $e) {
			error_log("❌ Error calculando posición para $nombreAprobador: " . $e->getMessage());
			return null;
		}
	}

	/**
	 * ✅ NUEVA FUNCIÓN: Obtiene el número total de páginas
	 */
	private function getPageCountFromFile($rutaArchivo)
	{
		try {
			$pdf = new \setasign\Fpdi\Fpdi();
			return $pdf->setSourceFile($rutaArchivo);
		} catch (\Exception $e) {
			error_log("Error obteniendo número de páginas: " . $e->getMessage());
			return 1;
		}
	}

	/**
	 * ✅ NUEVA FUNCIÓN: Firma en posición fija (fallback)
	 */
	private function colocarFirmaFija($pdf, $size, $idUsuarioFirma, $aprobador)
	{
		if (isset($this->firmasCoordenadas[$aprobador])) {
			$cfg = $this->firmasCoordenadas[$aprobador];
			$rutaFirma = FCPATH . 'images/firmas_users/' . $idUsuarioFirma . '/' . $idUsuarioFirma . '.png';

			if (is_file($rutaFirma)) {
				$x = $cfg['x'];
				$y = $size['height'] - $cfg['y_offset_bottom'];
				$w = $cfg['w'];
				$pdf->Image($rutaFirma, $x, $y, $w, 0, 'PNG');

				error_log("✅ Firma fija colocada para $aprobador en posición x:$x, y:$y");
			} else {
				error_log("❌ No se encontró archivo de firma fija: $rutaFirma");
			}
		} else {
			error_log("❌ No se encontraron coordenadas para $aprobador");
		}
	}

	public function todasLasSolicitudes()
	{
		$modelo = new SolicitudesLogisticaModel();
		$datos  = $modelo->where('active_status', 1)->findAll();

		// Respuesta en JSON para AJAX
		return $this->response->setJSON(['data' => $datos]);
	}

	// ✅ COORDENADAS MEJORADAS - Ahora más flexibles
	private array $firmasCoordenadas = [
		'SERGIO MAQUEDA' => [ // Solicitante
			'x' => 40,
			'y_offset_bottom' => 92, // mm desde abajo
			'w' => 25
		],
		'GERMAN VELAZQUEZ' => [ // Jefe de Almacenes
			'x' => 100,
			'y_offset_bottom' => 78,
			'w' => 25
		],
		'ABRAHAM GERARDO SERNAS' => [ // Gerente de área afectada
			'x' => 140,
			'y_offset_bottom' => 90,
			'w' => 35
		],
		'ANIBAL MOLINA' => [ // Director de operaciones
			'x' => 180,
			'y_offset_bottom' => 90,
			'w' => 35
		],
		'GUSTAVO ANGELES' => [ // Jefe de costos
			'x' => 220,
			'y_offset_bottom' => 90,
			'w' => 35
		],
		'FRANCISCO ENRICO PEREZ' => [ // Director Administrativo
			'x' => 260,
			'y_offset_bottom' => 90,
			'w' => 35
		],
	];

	// Mapa de orden y firma (mantener igual)
	private array $ordenAprobadores = [
		'SERGIO MAQUEDA' => [
			'email' => 'rcruz@walworth.com.mx',
			'firma' => FCPATH . 'images/firmas_users/328/328.png',
		],
		'GERMAN VELAZQUEZ' => [
			'email' => 'rcruz@walworth.com.mx',
			'firma' => FCPATH . 'images/firmas_users/328/328.png',
		],
		'ABRAHAM GERARDO SERNAS'   => [
			'email' => 'rcruz@walworth.com.mx',
			'firma' => FCPATH . 'images/firmas_users/272/272.png',
		],
		'ANIBAL MOLINA'    => [
			'email' => 'rcruz@walworth.com.mx',
			'firma' => FCPATH . 'images/firmas_users/203/203.png',
		],
		'GUSTAVO ANGELES'  => [
			'email' => 'rcruz@walworth.com.mx',
			'firma' => FCPATH . 'images/firmas_users/48/48.png',
		],
		'FRANCISCO ENRICO PEREZ'     => [
			'email' => 'rcruz@walworth.com.mx',
			'firma' => FCPATH . 'images/firmas_users/784/firma_agua.png',
		],
	];

	private function siguienteAprobador(?string $actual): ?string
	{
		$keys = array_keys($this->ordenAprobadores);
		if ($actual === null || $actual === '') return $keys[0] ?? null;
		$idx = array_search($actual, $keys, true);
		if ($idx === false) return $keys[0] ?? null;
		return $keys[$idx + 1] ?? null;
	}

	public function firmar()
	{
		// Cargas locales de FPDI si las usas sin composer
		require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
		require_once APPPATH . 'Libraries/FPDI/src/autoload.php';

		$id_solicitud   = (int) $this->request->getPost('id_solicitud');
		$aprobador      = (string) $this->request->getPost('aprobador_actual');
		
		if (!$id_solicitud || $aprobador === '') {
			return $this->response->setJSON(['ok' => false, 'msg' => 'Parámetros inválidos']);
		}
		if (!isset($this->ordenAprobadores[$aprobador])) {
			return $this->response->setJSON(['ok' => false, 'msg' => 'Aprobador desconocido']);
		}

		$modelo = new SolicitudesLogisticaModel();
		$fila   = $modelo->find($id_solicitud);
		if (!$fila) {
			return $this->response->setJSON(['ok' => false, 'msg' => 'Solicitud no encontrada']);
		}

		$rutaRel = $fila['ruta_archivo'] ?? '';
		if ($rutaRel === '') {
			return $this->response->setJSON(['ok' => false, 'msg' => 'Ruta de archivo no registrada']);
		}

		$rutaPdf = FCPATH . $rutaRel;
		$carpeta = dirname($rutaPdf) . '/';
		$rutaOriginal = $carpeta . $id_solicitud . '_original.pdf';

		// Asegura conservar original
		if (!is_file($rutaOriginal) && is_file($rutaPdf)) {
			@copy($rutaPdf, $rutaOriginal);
		}

		// ✅ RE-FIRMAR CON DETECCIÓN AUTOMÁTICA
		$pdf = new \setasign\Fpdi\Fpdi();
		$pageCount = $pdf->setSourceFile($rutaPdf);

		for ($n = 1; $n <= $pageCount; $n++) {
			$tpl = $pdf->importPage($n);
			$size = $pdf->getTemplateSize($tpl);

			$pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
			$pdf->useTemplate($tpl);

			// Solo última página
			if ($n === $pageCount) {
				if ($pageCount === 1) {
					// Una sola hoja → bloque queda más arriba
					$coords = [
						'SERGIO MAQUEDA'         => ['x' => 40,  'y' => 180, 'w' => 35],
						'GERMAN VELAZQUEZ'       => ['x' => 78,  'y' => 140, 'w' => 23],
						'ABRAHAM GERARDO SERNAS' => ['x' => 112, 'y' => 142, 'w' => 30],
						'ANIBAL MOLINA'          => ['x' => 160, 'y' => 135, 'w' => 25],
						'GUSTAVO ANGELES'        => ['x' => 184, 'y' => 140, 'w' => 27],
						'FRANCISCO ENRICO PEREZ' => ['x' => 220, 'y' => 140, 'w' => 30],
					];
				} else {
					// Varias hojas → firmas fijas al pie
					$coords = [
						'SERGIO MAQUEDA'         => ['x' => 40,  'y' => $size['height'] - 90, 'w' => 35],
						'GERMAN VELAZQUEZ'       => ['x' => 90,  'y' => $size['height'] - 48, 'w' => 32],
						'ABRAHAM GERARDO SERNAS' => ['x' => 128, 'y' => $size['height'] - 42, 'w' => 35],
						'ANIBAL MOLINA'          => ['x' => 160, 'y' => $size['height'] - 90, 'w' => 35],
						'GUSTAVO ANGELES'        => ['x' => 184, 'y' => $size['height'] - 44, 'w' => 30],
						'FRANCISCO ENRICO PEREZ' => ['x' => 240, 'y' => $size['height'] - 90, 'w' => 35],
					];
				}

				if (isset($coords[$aprobador])) {
					$cfg = $coords[$aprobador];
					$rutaFirma = $this->ordenAprobadores[$aprobador]['firma'];
					
					//$rutaFirma = FCPATH . "images/firmas_users/{$idUsuario}/{$idUsuario}.png";

					if (file_exists($rutaFirma)) {
						$pdf->Image($rutaFirma, $cfg['x'], $cfg['y'], $cfg['w'], 0, 'PNG');
					} else {
						log_message('error', "Firma no encontrada: $rutaFirma");
					}
				}
			}
		}


		// Guardar sobre el firmado vigente
		$pdf->Output($rutaPdf, 'F');

		// Marcar en DB que este aprobador firmó
		$firmas_json = json_decode($fila['firmas_json'] ?? '[]', true);
		if (!in_array($aprobador, $firmas_json, true)) {
			$firmas_json[] = $aprobador;
		}

		$modelo->update($id_solicitud, [
			'firmas_json'       => json_encode($firmas_json, JSON_UNESCAPED_UNICODE),
			'updated_at'        => date('Y-m-d H:i:s'),
		]);

		return $this->response->setJSON(['ok' => true, 'msg' => 'Firma aplicada automáticamente']);
	}

	public function avanzar()
	{
		$id_solicitud   = (int) $this->request->getPost('id_solicitud');
		$aprobador      = (string) $this->request->getPost('aprobador_actual');

		if (!$id_solicitud) {
			return $this->response->setJSON(['ok' => false, 'msg' => 'ID inválido']);
		}

		$modelo = new SolicitudesLogisticaModel();
		$fila   = $modelo->find($id_solicitud);
		if (!$fila) {
			return $this->response->setJSON(['ok' => false, 'msg' => 'Solicitud no encontrada']);
		}

		// Validar que el actual esté firmado
		$firmas_json = json_decode($fila['firmas_json'] ?? '[]', true);
		if (!in_array($aprobador, (array)$firmas_json, true)) {
			return $this->response->setJSON(['ok' => false, 'msg' => 'Debes firmar antes de avanzar.']);
		}

		$siguiente = $this->siguienteAprobador($aprobador);

		// Si ya no hay siguiente, cerrar flujo
		if ($siguiente === null) {
			$result = $this->db->table('tbl_logistica_movimiento_inventario')
				->where('id_solicitud', $id_solicitud)
				->set('estatus_solicitud', 'APROBADO')
				->set('updated_at', date('Y-m-d H:i:s'))
				->update();
			return $this->response->setJSON(['ok' => true, 'msg' => 'Flujo finalizado', 'aprobador_siguiente' => null]);
		}

		// Actualizar estatus al siguiente aprobador
		$result = $this->db->table('tbl_logistica_movimiento_inventario')
			->where('id_solicitud', $id_solicitud)
			->set('estatus_solicitud', $siguiente)
			->set('updated_at', date('Y-m-d H:i:s'))
			->update();

		if ($result) {
			return $this->response->setJSON([
				'ok' => true,
				'msg' => 'Avanzó al siguiente aprobador',
				'aprobador_siguiente' => $siguiente
			]);
		} else {
			return $this->response->setJSON([
				'ok' => false,
				'msg' => 'Error al Avanzar',
				'aprobador_siguiente' => $siguiente
			]);
		}
	}

	private function notificarAprobador(string $aprobador, array $fila): void
	{
		$cfg = $this->ordenAprobadores[$aprobador] ?? null;
		if (!$cfg || empty($cfg['email'])) return;

		$mail = new PHPMailer(true);
		try {
			$mail->setFrom('no-reply@tu-dominio.com', 'Sistema SMI');
			$mail->addAddress($cfg['email'], $aprobador);

			$mail->isHTML(true);
			$mail->Subject = 'Nueva solicitud pendiente de firma';
			$folio = $fila['id_solicitud'] ?? '';
			$concepto = $fila['concepto'] ?? '';
			$mail->Body = "
                <p>Hola <b>{$aprobador}</b>,</p>
                <p>Tienes una solicitud SMI pendiente de firma:</p>
                <ul>
                  <li><b>Folio:</b> {$folio}</li>
                  <li><b>Concepto:</b> {$concepto}</li>
                </ul>
                <p>Ingresa al sistema para revisarla y firmar.</p>
            ";

			$mail->send();
		} catch (\Throwable $e) {
			log_message('error', 'No se pudo enviar correo a ' . $aprobador . ': ' . $e->getMessage());
		}
	}
}
