<?php

/**
 * GENERADOR DE VIATICOS Y GASTOS
 * @version 1.1 pre-prod
 * @author Rafael Cruz Aguilar <rafael.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 * 2° @author Horus Samael Rivas Pedraza <horus.riv.ped@gmail.com>
 * @telefono 56-2439-2632
 * Archivo Generador de Reporte
 */

namespace App\Controllers\Travel;

use DateTime;
use App\Controllers\BaseController;
use App\Models\TravelsRequestModel;
use App\Models\UserModel;
use App\Models\TravelItemsModel;
use App\Models\ServicesTravelModel;
use App\Models\ServicesExpensModel;
use App\Models\ServicesXmlModel;
use App\Models\ServicesAccountModel;
use App\Models\ServicesAccountModelCaseSpecial;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\TryCatch;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class Travel extends BaseController
{
	public function __construct()
	{
		require_once APPPATH . '/Libraries/vendor/autoload.php';
		$this->userModel = new UserModel();
		$this->travelsModel = new TravelsRequestModel();
		$this->itemModel = new TravelItemsModel();
		$this->xmlModel = new ServicesXmlModel();

		$this->servicesTravelModel = new ServicesTravelModel();
		$this->expensesModel = new ServicesExpensModel();
		$this->servicesAccount = new ServicesAccountModel();
		$this->servicesAccountSpecial = new ServicesAccountModelCaseSpecial;

		$this->db = \Config\Database::connect();
		$this->is_logged = session()->is_logged ? true : false;
	}

	public function viewCreateRequest()
	{
		return ($this->is_logged) ? view('travels/requests') : redirect()->to(site_url());
	}
	public function viewMyRequest()
	{
		return ($this->is_logged) ? view('travels/view_my_requests') : redirect()->to(site_url());
	}

	public function viewRequestAll()
	{
		return ($this->is_logged) ? view('travels/view_requests_all') : redirect()->to(site_url());
	}
	public function viewRequestTravel()
	{
		return ($this->is_logged) ? view('travels/view_requests_travel_all') : redirect()->to(site_url());
	}

	public function viewVerification()
	{
		return ($this->is_logged) ? view('travels/view_tabla_folios') : redirect()->to(site_url());
	}

	public function viewAccountStatusByFolio($type, $folio)
	{
		$divisaActive = $this->db->query("SELECT type_mony FROM cat_travels_country WHERE active_status = 1")->getResult();
		$data = ["type" => $type, "folio" => $folio, 'divisa' => $divisaActive];
		return ($this->is_logged) ? view('travels/view_account_status', $data) : redirect()->to(site_url());
	}

	public function viewDataRequest($type, $folio)
	{
		$data = ["type" => $type, "folio" => $folio];
		return ($this->is_logged) ? view('travels/view_proof_of_expenditure', $data) : redirect()->to(site_url());
	}

	public function viewRequestAuthorize()
	{
		return ($this->is_logged) ? view('travels/view_requests_authorize') : redirect()->to(site_url());
	}

	public function viewReposrts()
	{
		$query =  $this->db->query("SELECT  id_user, user_name 
			FROM tbl_services_request_travel 
			WHERE active_status = 1 AND request_status > 1
				UNION 
			SELECT id_user, user_name 
			FROM tbl_services_request_expenses 
			WHERE active_status = 1 AND request_status > 1
		")->getResult();
		$data = ["usuarios" => $query];
		return ($this->is_logged) ? view('travels/view_reports', $data) : redirect()->to(site_url());
	}

	public function myListChecks()
	{
		$idUser = session()->id_user;
		$query = $this->db->query("SELECT a.id_request_travel AS folio,
			CONCAT(a.total_money,' ',a.divisa_money) AS monto,
			DATE_FORMAT(a.day_star_travel,'%d/%m/%Y') AS inicio,
			DATE_FORMAT(a.day_end_travel,'%d/%m/%Y') AS fin,
			(SELECT b.color FROM cat_travels_status AS b WHERE b.type = 2 AND b.status_ = a.verification_status) AS color,
			(SELECT b.text FROM cat_travels_status AS b WHERE b.type = 2 AND b.status_ = a.verification_status) AS txt
			FROM tbl_services_request_travel AS a 
				WHERE a.active_status = 1 
				AND a.request_status = 2 
				AND a.verification_status IN (1,2) 
				AND a.id_user = $idUser
		ORDER BY folio DESC;")->getResult();
		$query1 = $this->db->query("SELECT a.id_request_expenses AS folio,
			a.total_money AS monto,
			DATE_FORMAT(a.day_star_expenses,'%d/%m/%Y') AS inicio,
			DATE_FORMAT(a.day_end_expenses,'%d/%m/%Y') AS fin, 
			(SELECT b.color FROM cat_travels_status AS b WHERE b.type = 2 AND b.status_ = a.verification_status) AS color,
			(SELECT b.text FROM cat_travels_status AS b WHERE b.type = 2 AND b.status_ = a.verification_status) AS txt
			FROM tbl_services_request_expenses AS a
				WHERE a.active_status = 1
				AND a.request_status = 2
				AND a.verification_status IN (1,2)
				AND a.id_user = $idUser
		ORDER BY folio DESC;")->getResult();
		$data = ["viaticos" => $query, "gastos" => $query1];
		return json_encode($data);
	}

	function dataRequestItemsByFolioType()
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		try {
			$idRequest = trim($this->request->getPost('folio'));
			$type = trim($this->request->getPost('type'));

			$data_services = $this->db->query("SELECT a.id_account_status AS id_item, a.rule_code,
					DATE_FORMAT(a.date_transaction,'%d/%m/%Y') AS fecha,
					UPPER(a.location_transaction) AS lugar,
					FORMAT(a.amount,2) AS amount, a.divisa, a.politics_status,	
					(SELECT ct1.text FROM cat_travels_status AS ct1 WHERE ct1.type = 3 AND ct1.status_ = a.politics_status) AS estado_txt,
					(SELECT ct1.color FROM cat_travels_status AS ct1 WHERE ct1.type = 3 AND ct1.status_ = a.politics_status) AS estado_color
				FROM tbl_services_account_status AS a
				WHERE a.active_status = 1 
					AND MD5(CONCAT('$key',a.id_request)) = '$idRequest'
					AND a.type = $type
					AND a.transaction_status = 1
			ORDER BY id_item DESC")->getResult();

			$categories = $this->db->query("SELECT id_category, category
				FROM cat_services_category
				WHERE type= $type AND active_status = 1 
			ORDER BY id_category DESC")->getResult();

			$data = [
				"datos" => $data_services,
				"category" => $categories
			];
			return json_encode($data);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	function listInternationalDegree()
	{
		$grado = session()->grado;
		$query = $this->db->query("SELECT id_country, country FROM cat_travels_country WHERE active_status = 1")->getResult();
		$query1 = $this->db->query("SELECT id_level, `level`, level_name FROM cat_level_grade WHERE active_status = 1 AND id_level < $grado")->getResult();
		$data = ['paises' => $query, 'grados' => $query1];
		return json_encode($data);
	}

	function calculateTravelExpenses()
	{

		$idCountry = $this->request->getPost('id_pais');
		$idLevel = ($this->request->getPost('id_level') == 0) ? session()->grado : $this->request->getPost('id_level');
		$starDate = $this->request->getPost('inicio');
		$endDate = $this->request->getPost('fin');

		$dailyAmount = $this->db->query("SELECT daily_amount FROM cat_level_grade 
			WHERE active_status = 1 AND id_level = $idLevel")
			->getRow()->daily_amount;

		$query = $this->db->query("SELECT mony_for_day, type_mony FROM cat_travels_country 
			WHERE active_status = 1 AND id_country = $idCountry")
			->getRow();
		$amount = ($idCountry == 1) ? intval($dailyAmount) : intval($query->mony_for_day);
		$unidAmount = ($idCountry == 1) ? 'MXN' : $query->type_mony;
		$diff = (new DateTime($starDate))->diff(new DateTime($endDate));
		$days = $diff->days + 1;
		$result = ($amount * (intval($days) - 1)) + ($amount / 2);
		$data = ["dias" => $diff->d, "viaticos" => $result, 'moneda' => $unidAmount];
		return json_encode($data);
	}

	public function insertRequest()
	{
		try {
			$preferred_hotel = "";
			$car_rental_name = "";
			$advance_type = "";
			$amount = "";
			$reason_for_travel = trim($this->request->getPost('motivo_viaje'));
			$estimated_budget = trim($this->request->getPost('presupuesto_viaje'));
			$origin_of_trip = trim($this->request->getPost('origen_viaje'));
			$trip_destination = trim($this->request->getPost('destino_viaje'));
			$trip_start = trim($this->request->getPost('inicio_viaje'));
			$return_trip = trim($this->request->getPost('regreso_viaje'));
			$firma = trim($this->request->getPost('firma_user'));
			$departure_time = trim($this->request->getPost('horario_ida'));
			$return_time = trim($this->request->getPost('horario_regreso'));

			$this->db->transStart();

			$lodging_required = trim($this->request->getPost('hospeda_si'));
			if ($lodging_required == 1) {
				$preferred_hotel = trim($this->request->getPost('hotel'));
			}

			$car_rental = trim($this->request->getPost('auto_si'));
			if ($car_rental == 1) {
				$car_rental_name = trim($this->request->getPost('nom_renta_auto'));
			}
			$request_advance = trim($this->request->getPost('anticipo_si'));
			if ($request_advance == 1) {
				$advance_type = trim($this->request->getPost('tipo_anticipo'));
				$amount = trim($this->request->getPost('total'));
			}

			$observation = trim($this->request->getPost('detalle_viaje'));

			$user_name = session()->name . " " . session()->surname . " " . session()->second_surname;
			$depto = session()->departament;
			$cost_center = session()->cost_center;
			$payroll_number = session()->payroll_number;
			$job_position = session()->job_position;
			$id_user = session()->id_user;
			$time = date('Y-m-d H:i:s');

			$data = [
				"id_user" => $id_user,
				"user_name" => $user_name,
				"depto" => $depto,
				"cost_center" => $cost_center,
				"job_position" => $job_position,
				"payroll_number" => $payroll_number,
				"reason_for_travel" => $reason_for_travel,
				"estimated_budget" => $estimated_budget,
				"origin_of_trip" =>  $origin_of_trip,
				"trip_destination" =>  $trip_destination,
				"trip_start" => $trip_start,
				"return_trip" => $return_trip,
				"departure_time" => $departure_time,
				"return_time" => $return_time,
				"lodging_required" => $lodging_required,
				"preferred_hotel" => $preferred_hotel,
				"car_rental" => $car_rental,
				"car_rental_name" => $car_rental_name,
				"request_advance" => $request_advance,
				"advance_type" => $advance_type,
				"amount" => $amount,
				"observation" => $observation,
				"created_at" => $time,
				'active_status' => 1,
				'firma_user' => $firma,
			];

			$this->travelsModel->insert($data);
			$id_travel = $this->db->insertID();
			if ($request_advance == 1 && $result) {
				$description = $this->request->getPost('cbx_');
				$monto = $this->request->getPost('monto_');
				foreach (array_combine($description, $monto) as $keyDescription => $keyMonto) {
					$insertItem = [
						'id_travel' => $id_travel,
						'description' => $keyDescription,
						'monto' => floatval($keyMonto),
						'status' => 1,
						'created_at' => $time
					];
					$this->itemModel->insert($insertItem);
				};
			}
			$query = $this->travelsModel->find($id_travel);

			$payroll_number = session()->payroll_number;
			$dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user IN 
                (SELECT id_manager FROM tbl_stationery_permissions WHERE payroll_number = $payroll_number)")->getRow();
			$email = $dataEmail->email;
			$title = $dataEmail->name . " " . $dataEmail->surname;

			$data = ["request" => $query, "user" => $title];
			//$this->emailNotification($email, $title, $id_travel);
			$result = $this->db->transComplete();
			return ($result) ? json_encode(true) : json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function myRequest()
	{
		try {

			$builder = $this->db->table('tbl_travels');
			$builder->select('id_travel,
			created_at,
			user_name,
			reason_for_travel,
			trip_destination,
			request_status,
			request_advance');
			$builder->where('id_user', session()->id_user);
			$builder->where("active_status", 1);
			$builder->limit(1500);
			$query = $builder->get()->getResult();

			return json_encode($query);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function requestAll()
	{
		try {

			$builder = $this->db->table('tbl_travels');
			$builder->select('id_travel,
			created_at,
			user_name,
			reason_for_travel,
			trip_destination,
			request_status');
			$builder->where("active_status", 1);
			$builder->limit(1000);
			$query = $builder->get()->getResult();

			return json_encode($query);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function requestAuthorize()
	{

		try {
			$id_manager = session()->id_user;
			$query = $this->db->query("SELECT id_travel,created_at,`user_name`,
			reason_for_travel,trip_destination,request_status
			FROM tbl_travels WHERE payroll_number IN 
			(SELECT payroll_number FROM tbl_stationery_permissions 
            WHERE id_manager = $id_manager) LIMIT 1500");

			$query = $query->getResult();

			return json_encode($query);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function editRequest()
	{
		$id_travel = trim($this->request->getPost('id_viaje'));
		$builder = $this->db->table('tbl_travels');
		$builder->select('*');
		$builder->where("id_travel", $id_travel);
		$builder->where("active_status", 1);
		$builder->limit(1);

		$query = $builder->get()->getResult();
		return ($query) ? json_encode($query) : json_encode(false);
	}

	public function editRequestALL()
	{
		$id_travel = trim($this->request->getPost('id_viaje'));
		$builder = $this->db->table('tbl_travels');
		$builder->select('*');
		$builder->where("id_travel", $id_travel);
		$builder->where("active_status", 1);
		$builder->limit(1);
		$query = $builder->get()->getResult();
		$query0 = $this->db->query("SELECT id_item, `description`, monto FROM tbl_travel_item WHERE id_travel = $id_travel")->getResultObject();

		$data = ['request' => $query, 'items' => $query0];
		return ($query) ? json_encode($data) : json_encode(false);
	}

	public function authorized()
	{
		$id_travel = trim($this->request->getPost('id_viaje'));
		$status = trim($this->request->getPost('estatus'));

		if ($status == 2) {
			$firma = trim($this->request->getPost('firma'));
			$cancel = null;
		} else if ($status == 6) {
			$cancel = trim($this->request->getPost('cancel'));
			$firma = null;
		}

		$upRequest = [
			'request_status' => $status,
			'cancel' => $cancel,
			'firma_manager' => $firma,
		];
		$result = $this->travelsModel->update($id_travel, $upRequest);

		if ($status == 2) {
			$title = "Alejandro Huerta";
			// $email = "icardenas@walworth.com.mx";
			$email = "rcruz@walworth.com.mx";
			$this->emailNotification($email, $title, $id_travel);
		}
		if ($status == 6) {
			$dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user IN 
            (SELECT id_user FROM  tbl_travels WHERE id_travel = $id_travel)")->getRow();
			$email = $dataEmail->email;
			$title = $dataEmail->name . " " . $dataEmail->surname;
			$this->emailNotification($email, $title, $id_travel);
		}
		return ($result) ? json_encode($result) : json_encode(false);
	}

	public function requestApprove()
	{
		$id_travel = $this->request->getPost('id_viaje');
		$presupuesto = $this->request->getPost('presupuesto');
		$status = $this->request->getPost('estado');
		$coment = $this->request->getPost('coment');
		$firma_user = $this->request->getPost('firma_user');

		$upData = [
			'request_status' => $status,
			'cancel' => $coment,
			'firma_admin' => $firma_user,
			'estimated_budget_approve' => $presupuesto,
		];

		$update = $this->travelsModel->update($id_travel, $upData);
		$monto = $this->request->getPost('monto');
		if ($update && $monto != null && $status == 3) {
			$upDataAmount = ['amount_approve' => $monto,];
			$this->travelsModel->update($id_travel, $upDataAmount);
			$id_items = $this->request->getPost('id_item_');
			$items = $this->request->getPost('item_');
			for ($i = 0; $i < count($id_items); $i++) {
				$insertItem = ['monto_approve' => floatval($items[$i]),];
				$this->itemModel->update($id_items[$i], $insertItem);
			}
		}
		$dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user IN 
		(SELECT id_user FROM  tbl_travels WHERE id_travel = $id_travel)")->getRow();
		$email = $dataEmail->email;
		$title = $dataEmail->name . " " . $dataEmail->surname;
		$this->emailNotification($email, $title, $id_travel);

		return ($update) ? json_encode(true) : json_encode(false);
	}

	public function saveDocument()
	{
		try {
			$date = date("Y-m-d H:i:s");
			$binder =  '../public/doc/travel/' . session()->name;
			if (!file_exists($binder)) {
				mkdir($binder, 0777, true);
			}
			$id_item = $this->request->getPost('id_item_');
			$id_travel = $this->request->getPost('id_travel');
			$upTravel = [
				'if_doc' => 1,
			];
			$update = $this->travelsModel->update($id_travel, $upTravel);
			for ($i = 0; $i < count($id_item); $i++) {
				$doc_ = "doc_" . strval($id_item[$i]);
				$imageFile[$i] = $this->request->getFile($doc_);
				$newName = $id_travel . "_" . $imageFile[$i]->getClientName();
				$imageFile = $imageFile->move($binder,  $newName);
				$e_documen = $binder . "/" . $newName;
				$upData = [
					'document' => $e_documen,
					'document_at' => $date,
				];
				$this->itemModel->update($id_item[$i], $upData);
			}
			$title = "Ingrid Cardenas";
			// $email = "icardenas@walworth.com.mx";
			$email = "rcruz@walworth.com.mx";
			$this->emailNotification($email, $title, $id_travel);

			return json_encode($update);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function emailNotification($email = null, $user = null, $id_request = null)
	{
		$query = $this->db->query("SELECT *
                FROM   tbl_travels
                WHERE  id_travel = $id_request");
		$dataRequest =  $query->getRow();

		$query0 = $this->db->query("SELECT *
		FROM   tbl_travel_item
		WHERE  id_travel = $id_request");
		$dataItem =  $query0->getResultObject();

		$query1 = $this->db->query("SELECT `name`, surname, second_surname  
                FROM tbl_users
                WHERE id_user IN 
                    (SELECT id_manager FROM tbl_stationery_permissions 
                    WHERE payroll_number =  $dataRequest->payroll_number)");
		$dataManager =  $query1->getRow();

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
			$mail->isSMTP();
			$mail->SMTPAuth = false;
			$mail->Host = 'localhost';
			/* $mail->Username = 'requisiciones@grupowalworth.com';
			$mail->Password = '2contodo'; */
			$mail->Port = 25;

			if ($dataRequest->if_doc != 1) {
				if ($dataRequest->request_status == 1) {
					$mail->setFrom('notificacion@walworth.com', 'Solicitud|Viaje');
				} else if ($dataRequest->request_status == 2) {
					$mail->setFrom('notificacion@walworth.com', 'Solicitud|Viaje|Autorizada');
					// $mail->addAddress('becarioadmin01@walworth.com.mx', 'Alejandro Huerta');
					// $mail->addAddress('recepcion@walworth.com.mx', 'Gabriela Marin');
				} else if ($dataRequest->request_status == 3) {
					$mail->setFrom('notificacion@walworth.com', 'Solicitud|Viaje|Concluida');
				} else if ($dataRequest->request_status == 6) {
					$mail->setFrom('notificacion@walworth.com', 'Solicitud|Viaje|Cancelada');
				}
			} else {
				$mail->setFrom('notificacion@walworth.com', 'Comprobaciones|Viaje');
				// $mail->addAddress('becarioadmin01@walworth.com.mx', 'Alejandro Huerta');
				// $mail->addAddress('recepcion@walworth.com.mx', 'Gabriela Marin');
				foreach ($dataItem as $key) {
					// var_dump($key->document);
					$mail->addAttachment($key->document);
				}
			}


			if (
				session()->id_user == 905 ||
				session()->id_user == 151 ||
				session()->id_user == 252 ||
				session()->id_user == 250
			) {
				$mail->addAddress('jwaisburd@walworth.com', 'Jacobo Waisburd');
				$mail->addBCC('msanchez@walworth.com.mx');
			} else {
				$mail->addAddress($email, $user);
			}




			$mail->addBCC('rcruz@walworth.com.mx');

			//Content
			$data = ["datas" => $dataRequest, 'items' => $dataItem, 'manager' => $dataManager];
			$mail->isHTML(true);
			$email_template = view('notificaciones/notify_travel', $data);
			$mail->MsgHTML($email_template);                           // Set email format to HTML
			$mail->Subject = 'Solicitud de Viaje';
			$mail->send();
			return true;
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}

	public function pdfRequest($id_request = null)
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$query = $this->db->query("SELECT *
                                        FROM
                                        tbl_travels
                                        WHERE
                                        MD5(concat('" . $key . "',id_travel))='" . $id_request . "'");
		$dataRequest = $query->getRow();
		$query0 = $this->db->query("SELECT *
                                    FROM
									tbl_travel_item
									WHERE  id_travel = $dataRequest->id_travel");
		$dataItem =  $query0->getResultObject();
		$data = [
			"request" => $dataRequest,
			"item" => $dataItem
		];

		$html2 = view('pdf/pdf_travels', $data);

		$html = ob_get_clean();

		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


		$html2pdf->pdf->SetTitle('Solicitud');

		$html2pdf->writeHTML($html2);

		ob_end_clean();
		$html2pdf->output('solicitud_' . $id_request . '.pdf', 'I');
	}

	public function generateReports()
	{
		$data = json_decode(stripslashes($this->request->getPost('data')));

		switch ($data->categoria) {
			case 1:
				$query = $this->db->query("SELECT
											id_request_travel,
											user_name,
											payroll_number,
											cost_center,
											CASE
												WHEN type_of_travel = 1 THEN 'Nacional'
												WHEN type_of_travel = 2 THEN 'Internacional'
												ELSE 'Desconocido'
											END AS type_of_travel,
											hierarchy,
											start_of_trip,
											return_trip,
											start_time,
											return_time,
											trip_origin,
											trip_destination,
											CASE
												WHEN airplane = 1 THEN 'Si'
												WHEN airplane = 2 THEN 'No'
												ELSE 'Desconocido'
											END AS airplane,
											total_travel,
											trip_details,
											
											CASE
												WHEN request_status = 1 THEN 'Pendiente'
												WHEN request_status = 2 THEN 'Autorizado'
													WHEN request_status = 3 THEN 'Cancelado'
												ELSE 'Desconocido'
											END AS request_status,
											
											CASE
												WHEN travel_vouchers = 1 THEN 'Por Comprobar'
												WHEN travel_vouchers = 2 THEN 'Realizado'
												ELSE 'Desconocido'
											END AS travel_vouchers,
											date_authorized,
											created_at
											FROM
												tbl_services_request_travel
											WHERE
												active_status = 1
											AND created_at BETWEEN '$data->fecha_inicio'
											AND '$data->fecha_fin'
											ORDER BY
												created_at DESC");

				break;
			case 2:
				$query = $this->db->query("SELECT
				id_expenses,
				payroll_number,
				`user`,
				cost_center,
				departament,
				reasons,
				start_date,
				end_date,
				total_amount,
				expenses_status,
				expense_vouchers,
				date_authorized
			FROM
				tbl_services_request_expenses
			WHERE active_status = 1 ORDER BY created_at DESC");


			default:
				$reporte = "";
				break;
		}
		$reporte = $query->getResult();
		$NombreArchivo = "requisiciones_viajes.xlsx";
		if ($data->categoria == 1) {

			$cont = 2;
			$cont2 = 0;
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$spreadsheet->getActiveSheet();
			$sheet->setTitle("solicitudes_viaticos");

			// definir ancho de casillas
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
			$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
			/* $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(15);*/

			// definir anchura   
			$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(32);

			// Determino ubicacion del texto
			$sheet->getStyle('A1:S1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A1:S1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			/* $sheet->getStyle('F1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY);*/

			// colorear celdas
			$spreadsheet->getActiveSheet()->getStyle('A1:S1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('14539A');

			// font text por grupos
			$sheet->getStyle("A1:S1")->getFont()->setBold(true)
				->setName('Calibri')
				->setSize(9)
				->getColor()
				->setRGB('FFFFFF');

			$sheet->setCellValue('A1', 'FOLIO');
			$sheet->setCellValue('B1', 'FECHA DE CREACION');
			$sheet->setCellValue('C1', 'NOMBRE DEL SOLICITANTE');
			$sheet->setCellValue('D1', 'NOMINA');
			$sheet->setCellValue('E1', 'CENTRO COSTO');
			$sheet->setCellValue('F1', 'TIPO VIAJE');
			$sheet->setCellValue('G1', 'GERARQUIA');
			$sheet->setCellValue('H1', 'FECHA INICIO');
			$sheet->setCellValue('I1', 'FECHA FIN');
			$sheet->setCellValue('J1', 'HORA SALIDA');
			$sheet->setCellValue('K1', 'HORA REGRESO');
			$sheet->setCellValue('L1', 'ORIGEN VIAJE');
			$sheet->setCellValue('M1', 'DESTINO VIAJE');
			$sheet->setCellValue('N1', 'AVION');
			$sheet->setCellValue('O1', 'RAZON DE VIAJE');
			$sheet->setCellValue('P1', 'ESTADO');
			$sheet->setCellValue('Q1', 'COMPROBACION');
			$sheet->setCellValue('R1', 'TOTAL');
			$sheet->setCellValue('S1', 'FECHA DE AUTORIZACION');

			foreach ($reporte as $key => $value) {

				if ($cont > 0) {
					// colorear celdas
					$spreadsheet->getActiveSheet()->getStyle('A' . ($cont - 1) . ':S' . ($cont - 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('14539A');

					// font text por grupos
					$sheet->getStyle('A' . ($cont - 1) . ':S' . ($cont - 1))->getFont()->setBold(true)
						->setName('Calibri')
						->setSize(9)
						->getColor()
						->setRGB('FFFFFF');

					$sheet->setCellValue('A' . ($cont - 1), 'FOLIO');
					$sheet->setCellValue('B' . ($cont - 1), 'FECHA DE CREACION');
					$sheet->setCellValue('C' . ($cont - 1), 'NOMBRE DEL SOLICITANTE');
					$sheet->setCellValue('D' . ($cont - 1), 'NOMINA');
					$sheet->setCellValue('E' . ($cont - 1), 'CENTRO COSTO');
					$sheet->setCellValue('F' . ($cont - 1), 'TIPO VIAJE');
					$sheet->setCellValue('G' . ($cont - 1), 'GERARQUIA');
					$sheet->setCellValue('H' . ($cont - 1), 'FECHA INICIO');
					$sheet->setCellValue('I' . ($cont - 1), 'FECHA FIN');
					$sheet->setCellValue('J' . ($cont - 1), 'HORA SALIDA');
					$sheet->setCellValue('K' . ($cont - 1), 'HORA REGRESO');
					$sheet->setCellValue('L' . ($cont - 1), 'ORIGEN VIAJE');
					$sheet->setCellValue('M' . ($cont - 1), 'DESTINO VIAJE');
					$sheet->setCellValue('N' . ($cont - 1), 'AVION');
					$sheet->setCellValue('O' . ($cont - 1), 'RAZON DE VIAJE');
					$sheet->setCellValue('P' . ($cont - 1), 'ESTADO');
					$sheet->setCellValue('Q' . ($cont - 1), 'COMPROBACION');
					$sheet->setCellValue('R' . ($cont - 1), 'TOTAL');
					$sheet->setCellValue('S' . ($cont - 1), 'FECHA DE AUTORIZACION');
				}


				$sheet->getStyle('A' . $cont . ':S' . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

				$sheet->setCellValue('A' . $cont, $value->id_request_travel);
				$sheet->setCellValue('B' . $cont, $value->created_at);
				$sheet->setCellValue('C' . $cont, $value->user_name);
				$sheet->setCellValue('D' . $cont, $value->payroll_number);
				$sheet->setCellValue('E' . $cont, $value->cost_center);
				$sheet->setCellValue('F' . $cont, $value->type_of_travel);
				$sheet->setCellValue('G' . $cont, $value->hierarchy);
				$sheet->setCellValue('H' . $cont, $value->start_of_trip);
				$sheet->setCellValue('I' . $cont, $value->return_trip);
				$sheet->setCellValue('J' . $cont, $value->start_time);
				$sheet->setCellValue('K' . $cont, $value->return_time);
				$sheet->setCellValue('L' . $cont, $value->trip_origin);
				$sheet->setCellValue('M' . $cont, $value->trip_destination);
				$sheet->setCellValue('N' . $cont, $value->airplane);
				$sheet->setCellValue('O' . $cont, $value->trip_details);
				$sheet->setCellValue('P' . $cont, $value->request_status);
				$sheet->setCellValue('Q' . $cont, $value->travel_vouchers);
				$sheet->setCellValue('R' . $cont, number_format($value->total_travel, 2));
				$sheet->setCellValue('S' . $cont, $value->date_authorized);





				$cont2 = $cont + 4;

				$query2 = $this->db->query("SELECT * FROM tbl_services_data_xml WHERE tipo_gasto = 1 AND folio = $value->id_request_travel AND active_status = 1 ORDER BY created_at DESC");
				$reportes2 = $query2->getResult();


				// Determino ubicacion del texto
				$sheet->getStyle('A' . ($cont2 - 1) . ':I' . ($cont2 - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A' . ($cont2 - 1) . ':I' . ($cont2 - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				/* $sheet->getStyle('F1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY);*/

				// colorear celdas
				$spreadsheet->getActiveSheet()->getStyle('A' . ($cont2 - 1) . ':I' . ($cont2 - 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('14539A');

				// font text por grupos
				$sheet->getStyle('A' . ($cont2 - 1) . ':I' . ($cont2 - 1))->getFont()->setBold(true)
					->setName('Calibri')
					->setSize(9)
					->getColor()
					->setRGB('FFFFFF');

				$sheet->setCellValue('A' . ($cont2 - 1), 'FOLIO');
				$sheet->setCellValue('B' . ($cont2 - 1), 'CFD´S');
				$sheet->setCellValue('C' . ($cont2 - 1), 'SERIE & FOLIO');
				$sheet->setCellValue('D' . ($cont2 - 1), 'RAZON SOCIAL');
				$sheet->setCellValue('E' . ($cont2 - 1), 'RFC');
				$sheet->setCellValue('F' . ($cont2 - 1), 'FECHA FACTURA');
				$sheet->setCellValue('G' . ($cont2 - 1), 'SUBTOTAL');
				$sheet->setCellValue('H' . ($cont2 - 1), 'IVA');
				$sheet->setCellValue('I' . ($cont2 - 1), 'TOTAL');


				foreach ($reportes2 as $key => $value) {
					$sheet->getStyle('A' . $cont2 . ':I' . $cont2)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

					$sheet->setCellValue('A' . $cont2, $value->folio);
					$sheet->setCellValue('B' . $cont2, $value->created_at);
					$sheet->setCellValue('C' . $cont2, $value->nombre);
					$sheet->setCellValue('D' . $cont2, $value->nombre_proveedor);
					$sheet->setCellValue('E' . $cont2, $value->rfc);
					$sheet->setCellValue('F' . $cont2, $value->fecha_factura);
					$sheet->setCellValue('G' . $cont2, $value->sub_total);
					$sheet->setCellValue('H' . $cont2, $value->iva);
					$sheet->setCellValue('I' . $cont2, $value->total);


					$cont2++;
				}

				$cont = $cont2 + 2;
			}
		}
		if ($data->categoria == 2) {

			$cont = 2;
			$cont2 = 0;
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$spreadsheet->getActiveSheet();
			$sheet->setTitle("solicitud_gastos");

			// definir ancho de casillas
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
			/* $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true); */


			// definir anchura   
			$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(32);



			// Determino ubicacion del texto
			$sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A1:L1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			/* $sheet->getStyle('C1:D1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY);
			$sheet->getStyle('Q1:R1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY); */

			// colorear celdas
			$spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('14539A');


			// font text por grupos
			/* $sheet->getStyle("A1:L1")->getFont()->setBold(true)
				->setName('Calibri')
				->setSize(9)
				->getColor()
				->setRGB('FFFFFF'); */
			$sheet->getStyle("A1:L1")->getFont()->setBold(true)
				->setName('Calibri')
				->setSize(13)
				->getColor()
				->setRGB('FFFFFF');

			//$sheet->setCellValue('A1', 'NOMBRE DEL SOLICITANTE:')->mergeCells('A1:C1');
			$sheet->setCellValue('A1', 'FOLIO');
			$sheet->setCellValue('B1', 'NOMINA');
			$sheet->setCellValue('C1', 'USUARIO');
			$sheet->setCellValue('D1', 'CENTRO COSTO');
			$sheet->setCellValue('E1', 'DEPARTAMENTO');
			$sheet->setCellValue('F1', 'MOTIVO');
			$sheet->setCellValue('G1', 'PRESUPUESTO (ESTIMADO)');
			$sheet->setCellValue('H1', 'PRESUPUESTO (AUTORIZADO)');
			$sheet->setCellValue('I1', 'ORIGEN');
			$sheet->setCellValue('J1', 'FECHA DE SALIDA');
			$sheet->setCellValue('K1', 'HORA DE SALIDA');
			$sheet->setCellValue('L1', 'DESTINO');


			foreach ($reporte as $key => $value) {

				if ($cont > 0) {
					// colorear celdas
					$spreadsheet->getActiveSheet()->getStyle('A' . ($cont - 1) . ':L' . ($cont - 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('14539A');

					// font text por grupos
					$sheet->getStyle('A' . ($cont - 1) . ':L' . ($cont - 1))->getFont()->setBold(true)
						->setName('Calibri')
						->setSize(9)
						->getColor()
						->setRGB('FFFFFF');


					$sheet->getStyle('A' . ($cont - 1) . ':L' . ($cont - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

					$sheet->setCellValue('A' . ($cont - 1), 'FOLIO');
					$sheet->setCellValue('B' . ($cont - 1), 'NOMINA');
					$sheet->setCellValue('C' . ($cont - 1), 'USUARIO');
					$sheet->setCellValue('D' . ($cont - 1), 'CENTRO COSTO');
					$sheet->setCellValue('E' . ($cont - 1), 'DEPARTAMENTO');
					$sheet->setCellValue('F' . ($cont - 1), 'MOTIVO');
					$sheet->setCellValue('G' . ($cont - 1), 'PRESUPUESTO (ESTIMADO)');
					$sheet->setCellValue('H' . ($cont - 1), 'PRESUPUESTO (AUTORIZADO)');
					$sheet->setCellValue('I' . ($cont - 1), 'ORIGEN');
					$sheet->setCellValue('J' . ($cont - 1), 'FECHA DE SALIDA');
					$sheet->setCellValue('K' . ($cont - 1), 'HORA DE SALIDA');
					$sheet->setCellValue('L' . ($cont - 1), 'DESTINO');
				}
				$sheet->setCellValue('A' . $cont, $value->id_expenses);
				$sheet->setCellValue('B' . $cont, $value->payroll_number);
				$sheet->setCellValue('C' . $cont, $value->user);
				$sheet->setCellValue('D' . $cont, $value->cost_center);
				$sheet->setCellValue('E' . $cont, $value->departament);
				$sheet->setCellValue('F' . $cont, $value->reasons);
				$sheet->setCellValue('G' . $cont, $value->start_date);
				$sheet->setCellValue('H' . $cont, $value->end_date);
				$sheet->setCellValue('I' . $cont, $value->total_amount);
				$sheet->setCellValue('J' . $cont, $value->expenses_status);
				$sheet->setCellValue('K' . $cont, $value->expense_vouchers);
				$sheet->setCellValue('L' . $cont, $value->date_authorized);





				$cont++;

				$cont2 = $cont + 4;

				$query2 = $this->db->query("SELECT * FROM tbl_services_data_xml WHERE tipo_gasto = 2 AND folio = $value->id_expenses AND active_status = 1 ORDER BY created_at DESC");
				$reportes2 = $query2->getResult();


				// Determino ubicacion del texto
				$sheet->getStyle('A' . ($cont2 - 1) . ':I' . ($cont2 - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle('A' . ($cont2 - 1) . ':I' . ($cont2 - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				/* $sheet->getStyle('F1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY);*/

				// colorear celdas
				$spreadsheet->getActiveSheet()->getStyle('A' . ($cont2 - 1) . ':I' . ($cont2 - 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('14539A');

				// font text por grupos
				$sheet->getStyle('A' . ($cont2 - 1) . ':I' . ($cont2 - 1))->getFont()->setBold(true)
					->setName('Calibri')
					->setSize(9)
					->getColor()
					->setRGB('FFFFFF');

				$sheet->setCellValue('A' . ($cont2 - 1), 'FOLIO');
				$sheet->setCellValue('B' . ($cont2 - 1), 'CFD´S');
				$sheet->setCellValue('C' . ($cont2 - 1), 'SERIE & FOLIO');
				$sheet->setCellValue('D' . ($cont2 - 1), 'RAZON SOCIAL');
				$sheet->setCellValue('E' . ($cont2 - 1), 'RFC');
				$sheet->setCellValue('F' . ($cont2 - 1), 'FECHA FACTURA');
				$sheet->setCellValue('G' . ($cont2 - 1), 'SUBTOTAL');
				$sheet->setCellValue('H' . ($cont2 - 1), 'IVA');
				$sheet->setCellValue('I' . ($cont2 - 1), 'TOTAL');


				foreach ($reportes2 as $key => $value) {
					$sheet->getStyle('A' . $cont2 . ':I' . $cont2)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

					$sheet->setCellValue('A' . $cont2, $value->folio);
					$sheet->setCellValue('B' . $cont2, $value->created_at);
					$sheet->setCellValue('C' . $cont2, $value->nombre);
					$sheet->setCellValue('D' . $cont2, $value->nombre_proveedor);
					$sheet->setCellValue('E' . $cont2, $value->rfc);
					$sheet->setCellValue('F' . $cont2, $value->fecha_factura);
					$sheet->setCellValue('G' . $cont2, $value->sub_total);
					$sheet->setCellValue('H' . $cont2, $value->iva);
					$sheet->setCellValue('I' . $cont2, $value->total);


					$cont2++;
				}



				$cont = $cont2 + 3;
			}
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


	public function firma()
	{
		$id_user = session()->id_user;
		$query = $this->userModel->select('firma')->where('id_user', $id_user)->get()->getRow();
		return json_encode($query);
	}

	public function saveFirma()
	{
		try {
			$id_user = session()->id_user;
			$binder =  '../public/images/firmas_users/' . session()->id_user;
			if (!file_exists($binder)) {
				mkdir($binder, 0777, true);
			}
			$imageFile = $this->request->getFile('firma_');
			$name = session()->name;
			$newName = $name . $imageFile->getClientName();
			$ext = $imageFile->getClientExtension();
			$type = $imageFile->getClientMimeType();
			$imageFile = $imageFile->move($binder,  $newName);
			$e_signature = $binder . "/" . $newName;
			$upData = [
				'firma' => $e_signature
			];
			$update = $this->userModel->update($id_user, $upData);
			return ($update) ? json_encode($e_signature) : json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function travelExpenses()
	{
		if (!$this->is_logged) {
			redirect()->to(site_url());
		}
		try {
			$typeTravel = $this->request->getPost('tipo_viaje');
			$idCountry = $this->request->getPost('id_pais');
			$otherGrade = $this->request->getPost('jerarquia');
			$idGradeLevel = $this->request->getPost('id_level') ?? session()->grado;
			$dayStarTravel = $this->request->getPost('inicio_viaje');
			$dayEndTravel = $this->request->getPost('regreso_viaje');
			$locationTravelStar = trim($this->request->getPost('origen_viaje'));
			$locationTravelEnd = trim($this->request->getPost('destino_viaje'));
			$needPlane = $this->request->getPost('avion');
			$timePlaneGo = $this->request->getPost('horario_ida');
			$timePlaneGoBack = $this->request->getPost('horario_regreso');
			$totalMoney = $this->request->getPost('total_viaticos');
			$divisaMoney = $this->request->getPost('divisa_viaticos');
			$obs = trim($this->request->getPost('detalle_viaje'));
			$query = $this->db->query("SELECT daily_amount FROM cat_level_grade WHERE id_level = $idGradeLevel")->getRow();
			$diff = (new DateTime($dayStarTravel))->diff(new DateTime($dayEndTravel));
			$days = $diff->days;

			$data = [
				'user_name' => session()->name . ' ' . session()->surname,
				'id_user' => session()->id_user,
				'payroll_number' => session()->payroll_number,
				'id_depto' => session()->id_depto,
				'id_operative_area' => session()->area_operativa,
				'clave_cost_center' => session()->cost_center,
				'type_travel' => $typeTravel,
				'id_country' => $idCountry ?? null,
				'other_grade' => $otherGrade,
				'id_grade_level' => $idGradeLevel,
				'money_daily_for_grade' => $query->daily_amount,
				'days_to_travel' => $days,
				'day_star_travel' => $dayStarTravel,
				'day_end_travel' => $dayEndTravel,
				'location_travel_star' => $locationTravelStar,
				'location_travel_end' => $locationTravelEnd,
				'need_plane' => $needPlane,
				'time_plane_go' => $timePlaneGo ?? null,
				'time_plane_go_back' => $timePlaneGoBack ?? null,
				'obs' => $obs,
				'total_money' => $totalMoney,
				'divisa_money' => $divisaMoney,
				'created_at' => date("Y-m-d H:i:s"),
			];

			$result = $this->servicesTravelModel->insert($data);
			$id_request_travel = $this->db->insertID();
			$queryNotify = $this->db->query("SELECT CONCAT(`name`,' ',surname,' ',second_surname) AS nombre, email 
			FROM tbl_users WHERE id_user IN (SELECT id_director FROM tbl_assign_travel_expenses_manager WHERE active_status AND id_user = " . session()->id_user . ")")->getRow();

			$email = $queryNotify->email;
			$tittle = $queryNotify->nombre;
			$this->emailRequestTravel($email, $tittle, $id_request_travel, $typeTravel, 1);
			return ($result) ? json_encode($id_request_travel) : json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	private function emailRequestTravel($email = null, $tittle = null, $id_request_travel = null, $type = 0, $status = 0)
	{
		try {
			/* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
			/* $dir_email = ($type == 2) ? 'vhernandez@walworth.com.mx' : changeEmail($email);
			$dir_tittle = ($type == 2) ? 'VICTOR MANUEL HERNANDEZ ALVARADO' : $tittle; */

			$dir_email = changeEmail($email);
			$dir_tittle =  $tittle;

			// $dir_email = 'rcruz@walworth.com.mx';
			$query = $this->db->query("SELECT a.id_request_travel AS folio, a.user_name, a.payroll_number As nomina,
					(SELECT st3.departament FROM cat_departament AS st3 WHERE st3.id_depto = a.id_depto) AS departamento,
					(SELECT st4.area FROM cat_operational_area As st4 WHERE st4.id_area = a.id_operative_area) AS area_ope,
					(SELECT job FROM cat_job_position AS st5 WHERE st5.id = b.id_job_position) AS puesto,
					CONCAT('$ ',a.total_money,' ',a.divisa_money) AS total,
					CONCAT(DATE_FORMAT(a.day_star_travel,'%d/%m/%Y'),' -- ',DATE_FORMAT(a.day_end_travel,'%d/%m/%Y')) AS fechas,
					CASE 
						WHEN type_travel =  1 THEN
							'NACIONAL'
						ELSE
							CONCAT('INTERNACIONAL  |  ',(SELECT st1.country FROM cat_travels_country AS st1 WHERE st1.id_country = a.id_country))
					END AS tipo_viaje,
					IF(a.other_grade = 2,'SI','NO') AS dif_nivel,
					(SELECT CONCAT(st2.roman_num,'  ',st2.level_name) FROM cat_level_grade AS st2 WHERE st2.id_level = a.id_grade_level) AS grado,
					UPPER(a.location_travel_star) AS inicio_lugar,
					UPPER(a.location_travel_end) AS final_lugar,
					IF(a.need_plane = 1,'SI','NO') AS avion,
					CONCAT(DATE_FORMAT(a.day_star_travel,'%d/%m/%Y'),'  |  ',DATE_FORMAT(a.time_plane_go,'%H:%i') )AS inicio_avion,
					CONCAT(DATE_FORMAT(a.day_end_travel,'%d/%m/%Y'),'  |  ',DATE_FORMAT(a.time_plane_go_back,'%H:%i')) AS final_avion,
					a.obs,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt
				FROM tbl_services_request_travel AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1 
			AND a.id_request_travel = $id_request_travel")->getRow();

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
			// Set mailer to use SMTP
			$mail->isSMTP();
			// Enable SMTP authentication
			$mail->SMTPAuth = false;
			// Specify main and backup SMTP servers
			$mail->Host = 'localhost';
			// SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
			//$mail->Username = 'requisiciones@walworth.com';
			// SMTP password (This is that emails' password (The email you created earlier) )
			//$mail->Password = 'Walworth321$';
			// TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
			$mail->Port = 25;

			//Recipients

			$mail->setFrom('notificacion@walworth.com', 'Modulo de Viáticos & Gastos');



		 	if (
				session()->id_user == 905 ||
				session()->id_user == 151 ||
				session()->id_user == 252 ||
				session()->id_user == 250
			) {
				//$mail->addAddress('jwaisburd@walworth.com', 'Jacobo Waisburd');
				$mail->addAddress('msanchez@walworth.com.mx', 'Monserrat Sanchez');
			} else {
				$mail->addAddress($dir_email, $dir_tittle);
			} 
			// $mail->addCC('gmendoza@walworth.com.mx', 'GERARDO MENDOZA VILLEGAS');
			//$mail->addBCC('hrivas@walworth.com.mx');
			$mail->addBCC('rcruz@walworth.com.mx');
			//$mail->addCC('ahuerta@walworth.com.mx', 'ADRIAN ALEJANDRO HUERTA CALDERON');
			
			$mail->addCC('dprado@walworth.com.mx', 'David Prado');
			$mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
			//Content

			$mail->isHTML(true);
			$data = ["data" => $query, 'status' => $status];
			$email_template = view('notificaciones/notify_request_travel', $data);
			$mail->MsgHTML($email_template);
			$mail->Subject = 'Solicitud de Viáticos';
			$mail->send();
			return true;
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $e->ErrorInfo;
		}
	}

	public function emailRequestExpenses($email = null, $tittle = null, $id_request_expense = null, $status = 0)
	{
		try {
			/* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
			$dir_email = changeEmail($email);
			$dir_tittle = $tittle;

			// $dir_email = 'rcruz@walworth.com.mx';
			$query = $this->db->query("SELECT a.id_request_expenses AS folio, a.user_name, a.obs, a.payroll_number AS nomina,
					(SELECT st3.departament FROM cat_departament AS st3 WHERE st3.id_depto = a.id_depto) AS departamento,
					(SELECT st4.area FROM cat_operational_area As st4 WHERE st4.id_area = a.id_operative_area) AS area_ope,
					(SELECT job FROM cat_job_position AS st5 WHERE st5.id = b.id_job_position) AS puesto,
					CONCAT('$ ',a.total_money) AS total,
					DATE_FORMAT(a.day_star_expenses,'%d/%m/%Y') AS inicio,
					DATE_FORMAT(a.day_end_expenses,'%d/%m/%Y') AS final,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt
				FROM tbl_services_request_expenses AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1 
			AND a.id_request_expenses = $id_request_expense")->getRow();

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
			// Set mailer to use SMTP
			$mail->isSMTP();
			// Enable SMTP authentication
			$mail->SMTPAuth = false;
			// Specify main and backup SMTP servers
			$mail->Host = 'localhost';
			// SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
			//$mail->Username = 'requisiciones@walworth.com';
			// SMTP password (This is that emails' password (The email you created earlier) )
			//$mail->Password = 'Walworth321$';
			// TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
			$mail->Port = 25;

			//Recipients
			$mail->setFrom('notificacion@walworth.com', 'Modulo de Viáticos & Gastos');

			$mail->addAddress($dir_email, $dir_tittle);
			//$mail->addCC('ahuerta@walworth.com.mx', 'ADRIAN ALEJANDRO HUERTA CALDERON');
			
			$mail->addCC('dprado@walworth.com.mx', 'David Prado');
			if (
				session()->id_user == 905 ||
				session()->id_user == 151 ||
				session()->id_user == 252
			) {
				$mail->addBCC('msanchez@walworth.com.mx');
			}
			// $mail->addCC('gmendoza@walworth.com.mx', 'GERARDO MENDOZA VILLEGAS');
			$mail->addBCC('rcruz@walworth.com.mx');
			$mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
			//Content74

			$mail->isHTML(true);
			$data = ["data" => $query, 'status' => $status];
			$email_template = view('notificaciones/notify_request_expenses', $data);
			$mail->MsgHTML($email_template);
			$mail->Subject = 'Solicitud de Gastos';
			$mail->send();

			return true;
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $e->ErrorInfo;
		}
	}

	function infoTravels()
	{
		try {
			/* $json = file_get_contents('php://input');
			$data = json_decode($json);
			$folio = $data->folio; */
			$folio = $this->request->getPost('folio');
			$data  = $this->servicesTravelModel->dataRequest($folio);
			return ($data) ? $this->response->setJSON($data) :  $this->response->setJSON(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function requestTravelAuthorized()
	{
		try {
			$idDirector = session()->id_user;

			// Director-> 252
			$access = ($idDirector == 252) ? true : false;
			// $sqlType = ($idDirector == 1063) 
			$sqlType = ($idDirector == 252)
				? 'AND a.type_travel = 2 OR a.id_user = 251'
				: "AND a.id_user IN (
				SELECT wt1.id_user
				FROM tbl_assign_travel_expenses_manager AS wt1
				WHERE wt1.active_status = 1
				AND wt1.id_director = $idDirector )";

			$travelALL = $this->db->query("SELECT a.id_request_travel AS folio, a.user_name, a.payroll_number As nomina,  a.request_status,			
					CONCAT('$ ',FORMAT(a.total_money, 2),' ',a.divisa_money) AS total, CONCAT('$access') AS access_direct,
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS creacion, a.verification_status,
					UPPER(a.location_travel_end) AS destino,
					CONCAT(DATE_FORMAT(a.day_star_travel,'%d/%m/%Y'),' -- ',DATE_FORMAT(a.day_end_travel,'%d/%m/%Y')) AS fechas,
					CASE 
						WHEN type_travel = 1 THEN
							'NACIONAL'
						ELSE
							CONCAT('INTERNACIONAL  |  ',(SELECT st1.country FROM cat_travels_country AS st1 WHERE st1.id_country = a.id_country))
					END AS tipo_viaje, a.type_travel,
					(SELECT CONCAT(st2.roman_num,'  ',st2.level_name) FROM cat_level_grade AS st2 WHERE st2.id_level = a.id_grade_level) AS grado,
					IF(a.need_plane = 1,'SI','NO') AS avion,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt
				FROM tbl_services_request_travel AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1 
				$sqlType				 
			ORDER BY folio DESC ")->getResult();
			// $this->servicesTravelModel->requestTravelAuthorized($id_director);
			return ($travelALL) ? json_encode($travelALL) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function requestExpensesAuthorized()
	{
		try {
			$idDirector = session()->id_user;

			$travelALL = $this->db->query("SELECT a.id_request_expenses AS folio, a.user_name, a.payroll_number AS nomina,
					CONCAT('$ ',a.total_money) AS total, a.request_status, a.verification_status,
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS creado,
					CONCAT(DATE_FORMAT(a.day_star_expenses,'%d/%m/%Y'),'  --  ',DATE_FORMAT(a.day_end_expenses,'%d/%m/%Y')) AS fechas,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt
				FROM tbl_services_request_expenses AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1 
				AND a.id_user IN (
					SELECT wt1.id_user 
					FROM tbl_assign_travel_expenses_manager AS wt1 
					WHERE wt1.active_status = 1 
					AND wt1.id_director = $idDirector)			 
			ORDER BY folio DESC ")->getResult();
			// $this->servicesTravelModel->requestTravelAuthorized($id_director);
			return ($travelALL) ? json_encode($travelALL) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function requestTravelAll()
	{
		try {
			$travel_all = $this->db->query("SELECT a.id_request_travel AS folio, a.user_name, a.payroll_number As nomina,  a.request_status,			
					CONCAT('$ ',a.total_money,' ',a.divisa_money) AS total,
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS creacion, a.verification_status,
					UPPER(a.location_travel_end) AS destino,
					CONCAT(DATE_FORMAT(a.day_star_travel,'%d/%m/%Y'),' -- ',DATE_FORMAT(a.day_end_travel,'%d/%m/%Y')) AS fechas,
					CASE 
						WHEN type_travel = 1 THEN
							'NACIONAL'
						ELSE
							CONCAT('INTERNACIONAL  |  ',(SELECT st1.country FROM cat_travels_country AS st1 WHERE st1.id_country = a.id_country))
					END AS tipo_viaje, a.type_travel,
					(SELECT CONCAT(st2.roman_num,'  ',st2.level_name) FROM cat_level_grade AS st2 WHERE st2.id_level = a.id_grade_level) AS grado,
					IF(a.need_plane = 1,'SI','NO') AS avion,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 2 AND st6.status_ = a.verification_status) AS verification_color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 2 AND st6.status_ = a.verification_status) AS verification_txt
				FROM tbl_services_request_travel AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1 				 
			ORDER BY folio DESC ")->getResult();
			return ($travel_all) ?  json_encode($travel_all) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function requestMyTravel()
	{
		try {
			$myId = session()->id_user;
			$travelMy = $this->db->query("SELECT a.id_request_travel AS folio, a.user_name,  a.request_status,			
					CONCAT('$ ',a.total_money,' ',a.divisa_money) AS total,
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS creacion, a.verification_status,
					UPPER(a.location_travel_end) AS destino,
					CONCAT(DATE_FORMAT(a.day_star_travel,'%d/%m/%Y'),' -- ',DATE_FORMAT(a.day_end_travel,'%d/%m/%Y')) AS fechas,
					CASE 
						WHEN type_travel = 1 THEN
							'NACIONAL'
						ELSE
							CONCAT('INTERNACIONAL  |  ',(SELECT st1.country FROM cat_travels_country AS st1 WHERE st1.id_country = a.id_country))
					END AS tipo_viaje, a.type_travel,
					(SELECT CONCAT(st2.roman_num,'  ',st2.level_name) FROM cat_level_grade AS st2 WHERE st2.id_level = a.id_grade_level) AS grado,
					IF(a.need_plane = 1,'SI','NO') AS avion,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 2 AND st6.status_ = a.verification_status) AS verification_color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 2 AND st6.status_ = a.verification_status) AS verification_txt
				FROM tbl_services_request_travel AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1
				AND a.id_user = $myId
			ORDER BY folio DESC ")->getResult();
			return ($travelMy) ?  json_encode($travelMy) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function requestMyExpenses()
	{
		try {
			$myId = session()->id_user;
			$expensesMy = $this->db->query("SELECT a.id_request_expenses AS folio, a.user_name, a.payroll_number AS nomina,
					CONCAT('$ ',a.total_money) AS total, a.request_status, a.verification_status,
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS creado,
					CONCAT(DATE_FORMAT(a.day_star_expenses,'%d/%m/%Y'),'  --  ',DATE_FORMAT(a.day_end_expenses,'%d/%m/%Y')) AS fechas,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 2 AND st6.status_ = a.verification_status) AS verification_color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 2 AND st6.status_ = a.verification_status) AS verification_txt
				FROM tbl_services_request_expenses AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1 
				AND a.id_user = $myId
			ORDER BY folio DESC  ")->getResult();
			return ($expensesMy) ? json_encode($expensesMy) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function requestExpensesAll()
	{
		try {
			$expensesALL = $this->db->query("SELECT a.id_request_expenses AS folio, a.user_name, a.payroll_number AS nomina,
					CONCAT('$ ',a.total_money) AS total, a.request_status, a.verification_status,
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS creado,
					CONCAT(DATE_FORMAT(a.day_star_expenses,'%d/%m/%Y'),'  --  ',DATE_FORMAT(a.day_end_expenses,'%d/%m/%Y')) AS fechas,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 2 AND st6.status_ = a.verification_status) AS verification_color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 2 AND st6.status_ = a.verification_status) AS verification_txt
				FROM tbl_services_request_expenses AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1 			 
			ORDER BY folio DESC  ")->getResult();
			return ($expensesALL) ? json_encode($expensesALL) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function insertRequestExpenses()
	{
		if (!$this->is_logged) {
			redirect()->to(site_url());
		}
		try {
			$obs = trim($this->request->getPost('motivo_gasto'));
			$dayStarExpenses = $this->request->getPost('inicio_gastos');
			$dayEndExpenses = $this->request->getPost('regreso_gastos');
			$totalMoney = $this->request->getPost('monto_gasto');
			$diff = (new DateTime($dayStarExpenses))->diff(new DateTime($dayEndExpenses));
			$days = $diff->days;

			$data = [
				'user_name' => session()->name . ' ' . session()->surname,
				'id_user' => session()->id_user,
				'payroll_number' => session()->payroll_number,
				'id_depto' => session()->id_depto,
				'id_operative_area' => session()->area_operativa,
				'clave_cost_center' => session()->cost_center,
				'days_to_expenses' => $days,
				'day_star_expenses' => $dayStarExpenses,
				'day_end_expenses' => $dayEndExpenses,
				'obs' => $obs,
				'total_money' => $totalMoney,
				'divisa_money' => 'MXN',
				'change_to_mxn' => $totalMoney,
				'created_at' => date('Y-m-d H:i:s'),
			];

			$this->expensesModel->insert($data);
			$id_result = $this->db->insertID();

			$queryNotify = $this->db->query("SELECT CONCAT(`name`,' ',surname,' ',second_surname) AS nombre, email 
			FROM tbl_users WHERE id_user IN (SELECT id_director FROM tbl_assign_travel_expenses_manager WHERE active_status AND id_user = " . session()->id_user . ")")->getRow();

			$email = $queryNotify->email;
			$tittle = $queryNotify->nombre;

			$this->emailRequestExpenses($email, $tittle, $id_result, 1);
			return json_encode($id_result);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	function searchData()
	{
		try {

			$tipo_gasto = $this->request->getPost('tipo_gasto');
			$folio = $this->request->getPost('folio');
			$id_user = session()->id_user;

			if ($tipo_gasto == 1) {
				$estatus = $this->servicesTravelModel->checkRequest($folio, $id_user);
				//$status = $estatus->request_status;
				$status = $estatus->request_status ?? 1;
			} else {
				$estatus = $this->expensesModel->expensesCheckRequest($folio, $id_user);
				//$status = $estatus['expenses_status'];
				$status = $estatus['expenses_status'] ?? 1;
			}

			//var_dump($status);
			if ($status == 1) {
				return $this->response->setJSON(3);
			}

			$data = ($tipo_gasto == 1)
				? $this->servicesTravelModel->dataRequest($folio)
				: $this->expensesModel->dataExpensesRequest($folio);


			return ($data) ? $this->response->setJSON($data) : $this->response->setJSON(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function uploadXml()
	{
		/* $xml = simplexml_load_string(file_get_contents(FCPATH . "/XML/Ejemplo_XML_Dotnet3.3 con Importación.xml"));
        // convert the XML string to JSON
        $jsonData = json_encode($xml, JSON_PRETTY_PRINT);

        echo "<pre>";
        print_r($jsonData); */

		$sessionUser = $info->id_datos;
		$dateFileUp = strval(date("Y-m-d_H:i:s"));
		$date = date("Y-m-d_H:i:s");
		$binder =  '../public/doc/comprobantes/' . session()->id_user;
		if (!file_exists($binder)) {
			mkdir($binder, 0777, true);
		}
		$domicilio = $this->request->getFile('doc_domicilio'); //tipo 1
		$newName = "domicilio_" . $sessionUser . "_" . $dateFileUp;
		$nameDo = $domicilio->getClientName();
		$domicilio = $domicilio->move($binder,  $newName);
		$e_documen = $binder . "/" . $newName;
		$upDomicilio = [
			'id_datos' => $id_datos,
			'num_nomina' => $num_nomina,
			'tipo_document' => 1,
			'nombre_original' => $nameDo,
			'descripcion' => "Comprobante de Domicilio",
			'ubicacion' => $e_documen,
			'created_at' => $date,
			'active_status' => 1,
		];
		$this->documentModel->insert($upDomicilio);
	}

	public function subirNotasXML()
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

		$this->db->transStart();
		$retencion = 0;
		$dateFileUp = date("Y-m-d H:i:s");

		$cont = $this->request->getPost('cont');
		$folioEncript = $this->request->getPost('folio');
		$tipo_gasto = $this->request->getPost('tipo_gasto');
		$tipo = $this->request->getPost('type');
		$id_item = $this->request->getPost('id_item');
		$politics_status = $this->request->getPost('politics_status');
		$comprobar_monto = $this->request->getPost('comprobar_monto');
		$total_acumulado = $this->request->getPost('total_acumulado');
		$credit_note = $this->request->getPost('nota_credito');

		$dias_visita = $this->request->getPost('dias_visita');
		$visitar_estado = $this->request->getPost('visitar_estado');
		$visita_cliente = $this->request->getPost('visita_cliente');


		// Determina la tabla y la columna basadas en el tipo
		$table = ($tipo == 1) ? 'tbl_services_request_travel' : 'tbl_services_request_expenses';
		$column = ($tipo == 1) ? 'id_request_travel' : 'id_request_expenses';

		// Consulta la base de datos
		$query_folio = $this->db->query("SELECT $column AS folio
										 FROM $table
										 WHERE MD5(concat('$key', $column)) = '$folioEncript'")->getRow();

		$folio = $query_folio->folio;

		$rango = 10.0; // Rango permitido
		if ($tipo_gasto != 16 && !($credit_note)) {
			if (!(abs(floatval($comprobar_monto) - $total_acumulado) <= $rango)) {
				return json_encode("Revisar Archivos XML & PDF las cantidades no son similares dentro del rango especificado .");
			}
		}

		$tipo_gastos = ($tipo == 1) ? "Viaticos" : "Gastos";

		$tipo2 = ($tipo == 1) ? "V" : "G";
		$binder =  FCPATH . "XML/" . $tipo_gastos . "/" . $tipo2 . "_folio_" . $folio;
		$binder_temp = FCPATH . "XML/temp_xml";
		$pais_cfdi = '';
		if (!file_exists($binder)) {
			mkdir($binder, 0750, true);
		}

		/* $builder 	= $db->table('files'); */
		$msg = 'Error al subir los archivos.';
		if ($this->request->getFileMultiple('upload')) {

			foreach ($this->request->getFileMultiple('upload') as $file) {
				$file->move($binder_temp);
			}

			foreach ($this->request->getFileMultiple('upload') as $file) {
				//$file->move($binder);
				$name = $file->getClientName();
				//$ext = $file->getClientExtension();
				if ($file->getClientExtension() === 'pdf' || $file->getClientExtension() === 'PDF') {


					// Obtener el valor de $SerieFolio del archivo XML
					$Name = $file->getClientName();
					$filenameWithoutExtension = pathinfo($Name, PATHINFO_FILENAME);
					(string) $xmlName = $binder_temp . "/" . $filenameWithoutExtension . ".xml";
					//$xml = simplexml_load_file($xmlName);

					if (file_exists($xmlName)) {
						$xml = simplexml_load_file($xmlName);
						if ($xml === false) {
							// Manejar errores relacionados con la carga del XML
							echo "Error al cargar el archivo XML: " . libxml_get_last_error()->message;
						}
					}


					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);

					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {

						$SerieFolio = (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio']))
							? current($cfdiComprobante['Serie']) . "-" . current($cfdiComprobante['Folio'])
							: current($cfdiComprobante['Folio']);
					}


					// Usar $SerieFolio como nombre del archivo PDF
					$pdfName = $SerieFolio . '.pdf';
					$route_pdf = $binder . "/" . $pdfName;
					$rpdf = $binder_temp . "/" . $file->getClientName();

					// Mover el archivo PDF a la nueva carpeta con el nuevo nombre
					if (rename($rpdf, $route_pdf)) {
						// Archivo PDF movido exitosamente con el nuevo nombre
					}
				}




				if ($file->getClientExtension() === 'xml' || $file->getClientExtension() === 'XML') {

					$route_xml = $binder_temp . "/" . $file->getClientName();
					$url = $binder_temp . "/" . $file->getClientName();
					$xml = simplexml_load_file($url);
					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);

					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
						$version = (!empty($cfdiComprobante['Version'])) ? current($cfdiComprobante['Version']) : null;
						$date = (!empty($cfdiComprobante['Fecha'])) ? current($cfdiComprobante['Fecha']) : null;
						$sub_total = (!empty($cfdiComprobante['SubTotal'])) ? current($cfdiComprobante['SubTotal']) : null;
						$total = (!empty($cfdiComprobante['Moneda'])) ? current($cfdiComprobante['Total']) : null;
						$currency = (!empty($cfdiComprobante['Total'])) ? current($cfdiComprobante['Moneda']) : null;

						$SerieFolio = (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio'])) ? current($cfdiComprobante['Serie']) . "-" . current($cfdiComprobante['Folio']) : current($cfdiComprobante['Folio']);
					}


					foreach ($xml->xpath('//cfdi:Impuestos') as $Impuestos) {
						$iva = (!empty($Impuestos)) ? current($Impuestos['TotalImpuestosTrasladados']) : $sub_total;
					}

					foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Traslado') as $Traslado) {
						// Supongamos que el porcentaje del IVA se encuentra en el atributo 'TasaOCuota'

						$tasaOCuota = ((string)$Traslado['TasaOCuota'] === '0.160000') ? 16 : 8;
						$tasaOCuota2 = ((string)$Traslado['TasaOCuota'] === '0.160000') ? 0.16 : 0.08;
					}

					if (empty($iva)) {
						$iva = 0;
					}

					foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Retencion') as $Retencion) {

						$importe_retencion = (!empty($Retencion['Importe'])) ? current($Retencion['Importe']) : null;
					}

					foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
						$rfc_cfdi =  (!empty($Emisor['Rfc'])) ? current($Emisor['Rfc']) : null;
						$name_cfdi =  (!empty($Emisor['Nombre'])) ? current($Emisor['Nombre']) : null;
					}

					if ($file->getClientExtension() === 'xml' || $file->getClientExtension() === 'XML') {

						$xmlName = $SerieFolio . '.xml'; // Nombre del archivo PDF
						$route_xml2 = $binder . "/";
						$rxml = $binder_temp . "/" . $file->getClientName();
						// Mover el archivo XML a la nueva carpeta
						if (rename($rxml, $route_xml2 . $xmlName)) {
						}
						$route_xml = $route_xml2 . $xmlName;
						$route_pdf = $binder . "/" . $SerieFolio . '.pdf';
					}

					/* actualizar estado de monoto de targeta ->item */
					$upDataAcount = [
						'transaction_status' => 2,
					];
					$this->servicesAccount->update($id_item, $upDataAcount);


					$observation = "Días: " . $dias_visita . "  Estado: " . $visitar_estado . " Cliente: " . $visita_cliente;

					$tbl_ = ($tipo == 1) ? 'tbl_services_request_travel' : 'tbl_services_request_expenses';
					$colum = ($tipo == 1) ? 'id_request_travel' : 'id_request_expenses';


					if ($politics_status != 3) {
						$update_folio = $this->db->query("UPDATE $tbl_
													SET verification_money = (verification_money + $total_acumulado)
													WHERE
													$colum = $folio");
					}



					/* VALIDAR EL el estado de proceso despues de incerciones y actualizacion */
					$this->db->query("UPDATE $tbl_ 
									  SET verification_status = (CASE WHEN ABS(card_confirm_money - verification_money) <= 10 THEN 3 ELSE 2 END) 
									  WHERE id_request_travel = $folio");

					/*************************************************************************** REGLAS DE VALICACION DE FACTURAS ***************************************************************/

					/*TIPO DE COMPROBACIÓN VIATICOS == 1 */
					if ($tipo == 1) {
						/*tipo de gasto Aeroportuario */
						if ($tipo_gasto == 19) {
							// Divide la fecha en fecha y hora
							list($date_cfdi, $hora) = explode('T', $date);

							$politics = ($politics_status == 3) ? 1 : 2;


							$sub_total = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$revisa_total = round(($sub_total + $iva), 1);
							$total_real = round($revisa_total, 2);
							/* echo "<br>";
								echo $total; */



							$data = [
								"id_to_check" => $tipo_gasto,
								"expense_type" => $tipo,
								"id_request" => $folio,
								"id_user" => session()->id_user,
								'invoice_date' => $date_cfdi,
								'subtotal' => $sub_total,
								'total' => $total_real,
								'iva' => $iva,
								'iva_percentage' => $tasaOCuota,
								'rfc' => $rfc_cfdi,
								'xml_travel_routes' => $route_xml,
								'pdf_travel_routes' => $route_pdf,
								'social_reason' => $name_cfdi,
								'created_at' => $dateFileUp,
								'serie_and_folio' => $SerieFolio,
								'cfdi_version' => $version,
								'id_account_status' => $id_item,
								'accounting_authorization' => $politics,
								'observation' => $observation
							];
							$this->xmlModel->insert($data);


							$ecuacion = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$revisa_total = round(($ecuacion + $iva), 1);
							$total_redondeado = round($revisa_total, 2);


							if ($total_redondeado != $total) {

								$total1 = round(($total - $total_redondeado), 1);
								$iva = 0;
								$tasaOCuota = 0;
								$extra = [
									"id_to_check" => $tipo_gasto,
									"expense_type" => $tipo,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $total1,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'rfc' => $rfc_cfdi,
									'xml_travel_routes' => "",
									'pdf_travel_routes' => "",
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'id_account_status' => $id_item,
									'observation' => $observation
								];
								$this->xmlModel->insert($extra);
							}
						}
					}
				}
			}
		}


		if ($this->request->getFileMultiple('notas')) {

			foreach ($this->request->getFileMultiple('notas') as $notas) {
				$notas->move($binder_temp);
			}



			foreach ($this->request->getFileMultiple('notas') as $notas) {
				//$notas->move($binder);
				//$name = $notas->getClientName();
				//$ext = $file->getClientExtension();
				if ($notas->getClientExtension() === 'pdf' || $notas->getClientExtension() === 'PDF') {


					// Obtener el valor de $SerieFolio del archivo XML
					$Name = $notas->getClientName();
					$filenameWithoutExtension = pathinfo($Name, PATHINFO_FILENAME);
					(string) $xmlName = $binder_temp . "/" . $filenameWithoutExtension . ".xml";
					//$xml = simplexml_load_file($xmlName);

					if (file_exists($xmlName)) {
						$xml = simplexml_load_file($xmlName);
						if ($xml !== false) {
							// Procesar el archivo XML
						} else {
							// Manejar errores relacionados con la carga del XML
							echo "Error al cargar el archivo XML: " . libxml_get_last_error()->message;
						}
					}
					/* else {
						echo "El archivo XML no existe: " . $xmlName;
					} */


					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);

					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {

						$SerieFolio = (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio'])) ? current($cfdiComprobante['Serie']) . "-" . current($cfdiComprobante['Folio']) : current($cfdiComprobante['Folio']);
					}


					// Usar $SerieFolio como nombre del archivo PDF
					$pdfName = $SerieFolio . '.pdf';
					$route_pdf_nota = $binder . "/" . $pdfName;
					$rpdf = $binder_temp . "/" . $notas->getClientName();

					// Mover el archivo PDF a la nueva carpeta con el nuevo nombre
					if (rename($rpdf, $route_pdf_nota)) {
						// Archivo PDF movido exitosamente con el nuevo nombre
					}
				}

				if ($notas->getClientExtension() === 'xml' || $notas->getClientExtension() === 'XML') {

					$route_xml_nota = $binder_temp . "/" . $notas->getClientName();
					$url = $binder_temp . "/" . $notas->getClientName();
					$xml = simplexml_load_file($url);
					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);

					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
						$version = (!empty($cfdiComprobante['Version'])) ? current($cfdiComprobante['Version']) : null;
						$date = (!empty($cfdiComprobante['Fecha'])) ? current($cfdiComprobante['Fecha']) : null;
						$sub_total = (!empty($cfdiComprobante['SubTotal'])) ? current($cfdiComprobante['SubTotal']) : null;
						$total = (!empty($cfdiComprobante['Moneda'])) ? current($cfdiComprobante['Total']) : null;
						$currency = (!empty($cfdiComprobante['Total'])) ? current($cfdiComprobante['Moneda']) : null;

						$SerieFolio = (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio'])) ? current($cfdiComprobante['Serie']) . "-" . current($cfdiComprobante['Folio']) : current($cfdiComprobante['Folio']);
					}


					foreach ($xml->xpath('//cfdi:Impuestos') as $Impuestos) {
						$iva = (!empty($Impuestos)) ? current($Impuestos['TotalImpuestosTrasladados']) : null;
						$ivaNegativa = -1 * $iva;
					}

					foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Traslado') as $Traslado) {
						// Supongamos que el porcentaje del IVA se encuentra en el atributo 'TasaOCuota'

						$tasaOCuota = ((string)$Traslado['TasaOCuota'] === '0.160000') ? 16 : 8;
						$tasaOCuota2 = ((string)$Traslado['TasaOCuota'] === '0.160000') ? 0.16 : 0.08;
					}

					foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Retencion') as $Retencion) {

						$importe_retencion = (!empty($Retencion['Importe'])) ? current($Retencion['Importe']) : null;
					}



					foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
						$rfc_cfdi =  (!empty($Emisor['Rfc'])) ? current($Emisor['Rfc']) : null;
						$name_cfdi =  (!empty($Emisor['Nombre'])) ? current($Emisor['Nombre']) : null;
					}

					if ($notas->getClientExtension() === 'xml' || $notas->getClientExtension() === 'XML') {

						$xmlName = $SerieFolio . '.xml'; // Nombre del archivo PDF
						$route_xml2 = $binder . "/";
						$rxml = $binder_temp . "/" . $notas->getClientName();
						// Mover el archivo XML a la nueva carpeta
						if (rename($rxml, $route_xml2 . $xmlName)) {
						}
						$route_xml_nota = $route_xml2 . $xmlName;
						$route_pdf_nota = $binder . "/" . $SerieFolio . '.pdf';
					}

					/* actualizar estado de monoto de targeta ->item */
					$upDataAcount = [
						'transaction_status' => 2,
					];
					$this->servicesAccount->update($id_item, $upDataAcount);



					$tbl_ = ($tipo == 1) ? 'tbl_services_request_travel' : 'tbl_services_request_expenses';
					$colum = ($tipo == 1) ? 'id_request_travel' : 'id_request_expenses';

					if ($politics_status != 3) {
						$update_folio = $this->db->query("UPDATE $tbl_
													SET verification_money = (verification_money + $total_acumulado)
													WHERE
													$colum = $folio");
					}



					/* VALIDAR EL el estado de proceso despues de incerciones y actualizacion */
					$this->db->query("UPDATE $tbl_ 
									  SET verification_status = (CASE WHEN ABS(card_confirm_money - verification_money) <= 10 THEN 3 ELSE 2 END) 
									  WHERE id_request_travel = $folio");


					/*************************************************************************** REGLAS DE VALICACION DE FACTURAS ***************************************************************/

					/*tipo de gasto Aeroportuario */

					// Divide la fecha en fecha y hora
					list($date_cfdi, $hora) = explode('T', $date);

					$sub_negativo = round((($ivaNegativa / $tasaOCuota2) * 100) / 100, 2);
					$revisa_total = round(($sub_negativo + $ivaNegativa), 1);
					$total_redondeado = round($revisa_total, 2);
					$total1 = round(($ivaNegativa + $sub_negativo), 1);

					$politics = ($politics_status == 3) ? 1 : 2;
					$data = [
						"id_to_check" => $tipo_gasto,
						"expense_type" => $tipo,
						"id_request" => $folio,
						"id_user" => session()->id_user,
						'invoice_date' => $date_cfdi,
						'subtotal' => $sub_negativo,
						'total' => $total1,
						'iva' => $ivaNegativa,
						'iva_percentage' => $tasaOCuota,
						'rfc' => $rfc_cfdi,
						'xml_travel_routes' => $route_xml_nota,
						'pdf_travel_routes' => $route_pdf_nota,
						'social_reason' => $name_cfdi,
						'created_at' => $dateFileUp,
						'serie_and_folio' => $SerieFolio,
						'cfdi_version' => $version,
						'id_account_status' => $id_item,
						'accounting_authorization' => $politics,
						'facture_type' => 1,
						'observation' => $observation
					];
					$this->xmlModel->insert($data);


					$sub_negativo = round((($ivaNegativa / $tasaOCuota2) * 100) / 100, 2);
					$revisa_total = round(($sub_negativo + $ivaNegativa), 1);
					$total_redondeado = round($revisa_total, 2);

					if ($total_redondeado != $total) {

						$total2 = $total + (round(($ivaNegativa + $sub_negativo), 1));
						$total2 = (-1 * $total2);
						$iva = 0;
						$tasaOCuota = 0;
						$extra = [
							"id_to_check" => $tipo_gasto,
							"expense_type" => $tipo,
							"id_request" => $folio,
							"id_user" => session()->id_user,
							'invoice_date' => $date_cfdi,
							'subtotal' => $total2,
							'total' => $total2,
							'iva' => 0.00,
							'iva_percentage' => $tasaOCuota,
							'rfc' => $rfc_cfdi,
							'xml_travel_routes' => "",
							'pdf_travel_routes' => "",
							'social_reason' => $name_cfdi,
							'created_at' => $dateFileUp,
							'serie_and_folio' => $SerieFolio,
							'cfdi_version' => $version,
							'id_account_status' => $id_item,
							'facture_type' => 2,
							'observation' => $observation
						];
						$this->xmlModel->insert($extra);
					}
				}
			}
		}


		$result = $this->db->transComplete();



		return ($result) ? json_encode(true) : json_encode(false);
	}

	public function subirXML()
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

		$this->db->transStart();
		$retencion = 0;
		$dateFileUp = date("Y-m-d H:i:s");

		$lugares = $this->request->getPost('lugares');
		$cantidades = $this->request->getPost('cantidades');
		$fechas = $this->request->getPost('fechas');

		$cont = $this->request->getPost('cont');
		$folioEncript = $this->request->getPost('folio');
		$tipo_gasto = $this->request->getPost('tipo_gasto');
		$tipo = $this->request->getPost('type');
		$id_item = $this->request->getPost('id_item');
		$politics_status = $this->request->getPost('politics_status');
		$comprobar_monto = $this->request->getPost('comprobar_monto');
		$total_acumulado = $this->request->getPost('total_acumulado');
		$credit_note = $this->request->getPost('nota_credito');

		$dias_visita = $this->request->getPost('dias_visita');
		$visitar_estado = $this->request->getPost('visitar_estado');
		$visita_cliente = $this->request->getPost('visita_cliente');

		$trip = $this->request->getPost('propinas');
		$ncr_number = $this->request->getPost('number_ncr');
		$caso_number = $this->request->getPost('number_caso');
		$importeIva = 0;

		if (!empty($trip)) {
			$porciento = ($trip == 10) ? 0.10 : 0.15;
			$porcentaje = ($porciento * $total_acumulado);
			$total_acumulado = $total_acumulado + $porcentaje;
		}

		// Determina la tabla y la columna basadas en el tipo
		$table = ($tipo == 1) ? 'tbl_services_request_travel' : 'tbl_services_request_expenses';
		$column = ($tipo == 1) ? 'id_request_travel' : 'id_request_expenses';

		// Consulta la base de datos
		$query_folio = $this->db->query("SELECT $column AS folio
										 FROM $table
										 WHERE MD5(concat('$key', $column)) = '$folioEncript'")->getRow();


		$folio = $query_folio->folio;

		//$rango = 10.0; // Rango permitido

		// if ($tipo_gasto != 17) {
		/* if (!(abs(floatval($comprobar_monto) - $total_acumulado) <= $rango)) {
			return json_encode("Revisar Archivos XML & PDF las cantidades no son similares dentro del rango especificado .");
		} */

		//} 

		$binder = FCPATH . "XML/Viaticos/V_folio_" . $folio;
		$binder_temp = FCPATH . "XML/temp_xml";

		if (!file_exists($binder)) {
			mkdir($binder, 0750, true);
		}
		$uploadedFiles = $this->request->getFileMultiple('upload');
		if ($uploadedFiles) {

			foreach ($uploadedFiles as $file) {
				$file->move($binder_temp);
			}

			foreach ($uploadedFiles as $file) {

				if ($file->getClientExtension() === 'pdf' || $file->getClientExtension() === 'PDF') {

					// Obtener el valor de $SerieFolio del archivo XML
					$Name = $file->getClientName();
					$filenameWithoutExtension = pathinfo($Name, PATHINFO_FILENAME);

					(string) $xmlName = $binder_temp . "/" . $filenameWithoutExtension . ".xml";

					if (file_exists($xmlName)) {
						$xml = simplexml_load_file($xmlName);
						if ($xml === false) {
							// Manejar errores relacionados con la carga del XML
							echo "Error al cargar el archivo XML: " . libxml_get_last_error()->message;
							return;
						}
					}


					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);

					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {

						// 1. Construyes la serie y folio
						if (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio'])) {
							$SerieFolio = current($cfdiComprobante['Serie'])
								. "-"
								. current($cfdiComprobante['Folio']);
						} elseif (!empty($cfdiComprobante['Folio'])) {
							$SerieFolio = current($cfdiComprobante['Folio']);
						} else {
							$SerieFolio = "";
						}
					}

					// 2. Sanitiza el nombre (reemplaza cualquier "/" o "\" por "-")
					$safeSerieFolio = str_replace(['/', '\\'], '-', $SerieFolio);

					// 3. Ahora úsalo para crear la carpeta o archivo
					$pdfName    = $safeSerieFolio . '.pdf';
					$route_pdf  = $binder . DIRECTORY_SEPARATOR . $pdfName;
					$rpdf       = $binder_temp . DIRECTORY_SEPARATOR . $file->getClientName();

					// 4. Mueves/renombras
					if (rename($rpdf, $route_pdf)) {
						// Éxito
					} else {
						// Manejar error
					}
				}


				if ($file->getClientExtension() === 'xml' || $file->getClientExtension() === 'XML') {

					$route_xml = $binder_temp . "/" . $file->getClientName();
					$url = $binder_temp . "/" . $file->getClientName();
					$xml = simplexml_load_file($url);
					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);

					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
						$version = (!empty($cfdiComprobante['Version'])) ? current($cfdiComprobante['Version']) : null;
						$date = (!empty($cfdiComprobante['Fecha'])) ? current($cfdiComprobante['Fecha']) : null;
						$sub_total = (!empty($cfdiComprobante['SubTotal'])) ? current($cfdiComprobante['SubTotal']) : null;
						$total = (!empty($cfdiComprobante['Moneda'])) ? current($cfdiComprobante['Total']) : null;
						$currency = (!empty($cfdiComprobante['Total'])) ? current($cfdiComprobante['Moneda']) : null;

						if (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio'])) {
							$SerieFolio = current($cfdiComprobante['Serie']) . "-" . current($cfdiComprobante['Folio']);
						} else if (!empty($cfdiComprobante['Folio'])) {
							$SerieFolio = current($cfdiComprobante['Folio']);
						} else {
							$SerieFolio = "";
						}
					}



					if (empty($SerieFolio)) {

						foreach ($xml->xpath('//t:TimbreFiscalDigital') as $cfdiTimbreFiscal) {

							$SerieFolio = current($cfdiTimbreFiscal['UUID']);
						}
					}

					// 2. Sanitiza el nombre (reemplaza cualquier "/" o "\" por "-")
					$SerieFolio = str_replace(['/', '\\'], '-', $SerieFolio);


					foreach ($xml->xpath('//cfdi:Impuestos') as $Impuestos) {
						$iva = (!empty($Impuestos)) ? current($Impuestos['TotalImpuestosTrasladados']) : null;
					}

					if (empty($iva)) {
						$iva = 0.00;
					}

					foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado) {
						// Supongamos que el porcentaje del IVA se encuentra en el atributo 'TasaOCuota'

						if ((string)$Traslado['TasaOCuota'] === '0.160000') {
							$tasaOCuota = 16;
							$tasaOCuota2 = 0.16;
							break;
						} else if ((string)$Traslado['TasaOCuota'] === '0.080000') {
							$tasaOCuota = 8;
							$tasaOCuota2 = 0.08;
							break;
						} else {
							$tasaOCuota = 0;
							$tasaOCuota2 = 0.00;
						}
					}

					if (empty($tasaOCuota)) {
						$tasaOCuota = 0;
						$tasaOCuota2 = 0.00;
					}


					foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Retencion') as $Retencion) {
						$importe_retencion = (!empty($Retencion['Importe'])) ? current($Retencion['Importe']) : null;
					}



					foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
						$rfc_cfdi =  (!empty($Emisor['Rfc'])) ? current($Emisor['Rfc']) : null;
						$name_cfdi =  (!empty($Emisor['Nombre'])) ? current($Emisor['Nombre']) : null;
					}

					if ($tipo_gasto == 19) {
						if ($iva == 0) {
							$sub_total_aero = $sub_total;
							$total_aero = $sub_total;
						} else {
							$sub_total_aero = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$revisa_total = round(($sub_total_aero + $iva), 1);
							$total_aero = round($revisa_total, 2);
						}
					}




					if ($tipo_gasto == 17) {

						$tasaOCuota = $this->request->getPost('iva');

						switch ($tasaOCuota) {
							case 16:
								$tasaOCuota2 = 0.16;
								break;
							case 8:
								$tasaOCuota2 = 0.08;
								break;

							default:
								$tasaOCuota2 = 0.00;
								break;
						}
						$iva = $this->request->getPost('iva_monto');
					}

					if ($tipo_gasto == 16) {
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
							$rfc_cfdi =  (!empty($Emisor['Rfc'])) ? current($Emisor['Rfc']) : null;
							$name_cfdi =  (!empty($Emisor['Nombre'])) ? current($Emisor['Nombre']) : null;
						}
					}



					if ($file->getClientExtension() === 'xml' || $file->getClientExtension() === 'XML') {

						$xmlName = $SerieFolio . '.xml'; // Nombre del archivo PDF
						$route_xml2 = $binder . "/";
						$rxml = $binder_temp . "/" . $file->getClientName();





						// Mover el archivo XML a la nueva carpeta
						if (rename($rxml, $route_xml2 . $xmlName)) {
						}
						$route_xml = $route_xml2 . $xmlName;
						$route_pdf = $binder . "/" . $SerieFolio . '.pdf';
					}


					$observation = "Días: " . $dias_visita . "  Estado: " . $visitar_estado . " Cliente: " . $visita_cliente;

					/* actualizar estado de monoto de targeta ->item */
					$upDataAcount = ['transaction_status' => 2];
					$this->servicesAccount->update($id_item, $upDataAcount);


					$tbl_ = ($tipo == 1) ? 'tbl_services_request_travel' : 'tbl_services_request_expenses';
					$colum = ($tipo == 1) ? 'id_request_travel' : 'id_request_expenses';

					if ($politics_status != 3) {
						$this->db->query("UPDATE $tbl_
											SET verification_money = (verification_money + $total_acumulado)
											WHERE
											$colum = $folio");
					}


					/* VALIDAR EL el estado de proceso despues de incerciones y actualizacion */
					$this->db->query("UPDATE $tbl_ 
									  SET verification_status = (CASE WHEN ABS(card_confirm_money - verification_money) <= 10 THEN 3 ELSE 2 END) 
									  WHERE id_request_travel = $folio");

					// Divide la fecha en fecha y hora
					list($date_cfdi, $hora) = explode('T', $date);

					$politics = ($politics_status == 3) ? 1 : 2;

					$this->db->query("UPDATE tbl_services_account_status SET accounting_authorization = $politics WHERE id_account_status = $id_item");

					$total_real = ($tipo_gasto == 19) ? $total_aero : $total;
					$subtotal_real = ($tipo_gasto == 19) ? $sub_total_aero : $sub_total;

					$data = [
						"id_to_check" => $tipo_gasto,
						"id_request" => $folio,
						"id_user" => session()->id_user,
						'invoice_date' => $date_cfdi,
						'subtotal' => $subtotal_real,
						'total' => $total_real,
						'iva' => $iva,
						'iva_percentage' => $tasaOCuota,
						'rfc' => $rfc_cfdi,
						'xml_travel_routes' => $route_xml,
						'pdf_travel_routes' => $route_pdf,
						'social_reason' => $name_cfdi,
						'created_at' => $dateFileUp,
						'serie_and_folio' => $SerieFolio,
						'cfdi_version' => $version,
						'trip' => $trip,
						'id_account_status' => $id_item,
						'expense_type' => $tipo,
						'accounting_authorization' => $politics,
						'facture_type' => 1,
						'observation' => $observation,
						'ncr_number' => $ncr_number,
						'caso_number' => $caso_number

					];
					$this->xmlModel->insert($data);



					$id_request = $this->db->insertID();



					/*************************************************************************** REGLAS DE VALICACION DE FACTURAS ***************************************************************/

					/*TIPO DE COMPROBACIÓN VIATICOS == 1 */
					if ($tipo == 1) {
						/*tipo de gasto Aeroportuario */
						if ($tipo_gasto == 19) {

							if ($iva == 0) {
								$total_redondeado = $sub_total;
							} else {
								$ecuacion = round((($iva / $tasaOCuota2) * 100) / 100, 2);
								$revisa_total = round(($ecuacion + $iva), 1);
								$total_redondeado = round($revisa_total, 2);
							}




							if ($total_redondeado != $total) {

								$total1 = round(($total - $total_redondeado), 1);
								$iva = 0;
								$tasaOCuota = 0;
								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $total1,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'rfc' => $rfc_cfdi,
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'expense_type' => $tipo,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number
								];
								$this->xmlModel->insert($extra);
							}
						}
						if ($tipo_gasto == 18) {



							$sub_total = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$monto_pago = round(($sub_total + $iva), 1);
							$total_redondeado = round($monto_pago, 2);


							$data = [

								'subtotal' => $sub_total,
								'total' => $monto_pago,


							];
							$this->xmlModel->update($id_request, $data);


							if ($total_redondeado != $total) {

								$total1 = round(($total - $total_redondeado), 1);
								$iva = 0;
								$tasaOCuota = 0;


								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $total1,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'rfc' => $rfc_cfdi,
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'expense_type' => $tipo,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number
								];
								$this->xmlModel->insert($extra);
							}
						}

						if ($tipo_gasto == 18) {

							$ecuacion = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$revisa_total = round(($ecuacion + $iva), 1);
							$total_redondeado = round($revisa_total, 2);

							if ($tasaOCuota == 16 || $tasaOCuota == 8) {
								$retencion = (!empty($importe_retencion)) ? $importe_retencion : 0;
							}



							if (!empty($trip)) {

								$trip = ($trip == 10) ? 0.10 : 0.15;
								$porcentaje =  $total * $trip; // Regla de tres
								$trip = round($porcentaje, 2);  // Quitar los decimales

								$data = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $trip,
									'total' => $trip,
									'iva' => 0,
									'iva_percentage' => "ND",
									'rfc' => $rfc_cfdi,
									'xml_travel_routes' => "",
									'pdf_travel_routes' => "",
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'trip' => $trip,
									'id_account_status' => $id_item,
									'expense_type' => $tipo,
									'accounting_authorization' => $politics,
									'facture_type' => 2,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number

								];
								$this->xmlModel->insert($data);
							}
						}

						if ($tipo_gasto == 17) {



							if ($iva == 0) {
								$sub_total = $total_acumulado;
								$monto_pago = $total_acumulado;
								$total_redondeado = round($monto_pago, 2);
							} else {
								$sub_total = round((($iva / $tasaOCuota2) * 100) / 100, 2);
								$monto_pago = round(($sub_total + $iva), 1);
								$total_redondeado = round($monto_pago, 2);
							}



							$data = [

								'subtotal' => $sub_total,
								'total' => $monto_pago,


							];
							$this->xmlModel->update($id_request, $data);


							if ($total_redondeado != $total) {

								$total1 = round(($total - $total_redondeado), 1);
								$iva = 0;
								$tasaOCuota = 0;


								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $total1,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'rfc' => $rfc_cfdi,
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'expense_type' => $tipo,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number
								];
								$this->xmlModel->insert($extra);
							}
						}
						/*tipo de gasto Alimentos */
						if ($tipo_gasto == 16) {

							$ecuacion = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$revisa_total = round(($ecuacion + $iva), 1);
							$total_redondeado = round($revisa_total, 2);

							if ($tasaOCuota == 16 || $tasaOCuota == 8) {
								$retencion = (!empty($importe_retencion)) ? $importe_retencion : 0;
							}



							if (!empty($trip)) {

								$trip = ($trip == 10) ? 0.10 : 0.15;
								$porcentaje =  $total * $trip; // Regla de tres
								$trip = round($porcentaje, 2);  // Quitar los decimales

								$data = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $trip,
									'total' => $trip,
									'iva' => 0,
									'iva_percentage' => "ND",
									'rfc' => $rfc_cfdi,
									'xml_travel_routes' => "",
									'pdf_travel_routes' => "",
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'trip' => $trip,
									'id_account_status' => $id_item,
									'expense_type' => $tipo,
									'accounting_authorization' => $politics,
									'facture_type' => 2,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number

								];
								$this->xmlModel->insert($data);
							}


							if ($total > $total_redondeado) {
								$total1 = round(($total - $total_redondeado), 1);

								/* if(!empty($importe_retencion)){
								echo	$total1 = $total_redondeado - $importe_retencion;
								} */

								$iva = 0;
								$tasaOCuota = 0;
								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $sub_total,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'social_reason' => $name_cfdi,
									'created_at' => $date,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'expense_type' => $tipo,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number
								];
								$this->xmlModel->insert($extra);
							}

							if (!empty($retencion)) {

								$extra = ["retention" => $retencion];
								$this->xmlModel->update($id_request, $extra);
								$result = $this->db->transComplete();

								$dataNotifycation = [
									'folio' => $folio,
									'lugar' => $lugares,
									'cantidad' => $cantidades,
									'fecha' => $fechas
								];
								$id_user = session()->id_user;
								// Ejecutar la consulta
								$query = $this->db->query("SELECT
																email
															FROM
																tbl_users 
															WHERE
																id_user = $id_user")->getRow();
								$email = $query->email;
								$user = session()->name . " " . session()->surname;

								$this->notifyExpensesAndTravel($email, $user, $dataNotifycation, 1);

								return ($result) ? json_encode(true) : json_encode(false);
							}
						}
					}
				}
			}
		}


		$result = $this->db->transComplete();

		$dataNotifycation = [
			'folio' => $folio,
			'lugar' => $lugares,
			'cantidad' => $cantidades,
			'fecha' => $fechas
		];
		$id_user = session()->id_user;
		// Ejecutar la consulta
		$query = $this->db->query("SELECT
										email
									FROM
										tbl_users 
									
									WHERE
										id_user = $id_user")->getRow();
		$email = $query->email;
		$user = session()->name . " " . session()->surname;

		$this->notifyExpensesAndTravel($email, $user, $dataNotifycation, 1);

		return ($result) ? json_encode(true) : json_encode(false);
	}

	public function subirGastosXML()
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

		$this->db->transStart();
		$retencion = 0;
		$dateFileUp = date("Y-m-d H:i:s");

		$lugares = $this->request->getPost('lugares');
		$cantidades = $this->request->getPost('cantidades');
		$fechas = $this->request->getPost('fechas');

		$cont = $this->request->getPost('cont');
		$folioEncript = $this->request->getPost('folio');
		$tipo_gasto = $this->request->getPost('tipo_gasto');
		$tipo = $this->request->getPost('type');
		$id_item = $this->request->getPost('id_item');
		$politics_status = $this->request->getPost('politics_status');
		$comprobar_monto = $this->request->getPost('comprobar_monto');
		$total_acumulado = $this->request->getPost('total_acumulado');
		$credit_note = $this->request->getPost('nota_credito');
		$visita_cliente = $this->request->getPost('visita_cliente');
		$trip = $this->request->getPost('propinas');
		$ncr_number = $this->request->getPost('number_ncr');
		$caso_number = $this->request->getPost('number_caso');

		$tipo_gastos = "Gastos";

		$tasaOCuota = 0.00;
		$tasaOCuota2 = 0.00;


		if (!empty($trip)) {
			$porciento = ($trip == 10) ? 0.10 : 0.15;
			$porcentaje = ($porciento * $total_acumulado);
			$total_acumulado = $total_acumulado + $porcentaje;
		}

		// Consulta la base de datos
		$query_folio = $this->db->query("SELECT id_request_expenses AS folio
										 FROM tbl_services_request_expenses
										 WHERE MD5(concat('$key', id_request_expenses)) = '$folioEncript'")->getRow();


		$folio = $query_folio->folio;

		// Rango permitido 
		$rango = 10.0;

		//if ($tipo_gasto != 17) {

		/* if (!(abs(floatval($comprobar_monto) - $total_acumulado) < $rango)) {
			return json_encode("Revisar Archivos XML & PDF las cantidades no son similares dentro del rango especificado .");
		} */
		//} 


		$binder = FCPATH . "XML/" . $tipo_gastos . "/G_folio_" . $folio;
		$binder_temp = FCPATH . "XML/temp_xml";

		if (!file_exists($binder)) {
			mkdir($binder, 0750, true);
		}


		if ($this->request->getFileMultiple('upload')) {

			foreach ($this->request->getFileMultiple('upload') as $file) {
				$file->move($binder_temp);
			}

			foreach ($this->request->getFileMultiple('upload') as $file) {

				if ($file->getClientExtension() === 'pdf' || $file->getClientExtension() === 'PDF') {

					// Obtener el valor de $SerieFolio del archivo XML
					$Name = $file->getClientName();
					$filenameWithoutExtension = pathinfo($Name, PATHINFO_FILENAME);
					(string) $xmlName = $binder_temp . "/" . $filenameWithoutExtension . ".xml";

					if (file_exists($xmlName)) {
						$xml = simplexml_load_file($xmlName);
						if ($xml == false) {
							// Manejar errores relacionados con la carga del XML
							echo "Error al cargar el archivo XML: " . libxml_get_last_error()->message;
						}
					}


					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);

					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {


						if (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio'])) {
							$SerieFolio = current($cfdiComprobante['Serie']) . "-" . current($cfdiComprobante['Folio']);
						} else if (!empty($cfdiComprobante['Folio'])) {
							$SerieFolio = current($cfdiComprobante['Folio']);
						} else {
							$SerieFolio = "";
						}
					}

					if (empty($SerieFolio)) {

						foreach ($xml->xpath('//t:TimbreFiscalDigital') as $cfdiTimbreFiscal) {

							$SerieFolio = current($cfdiTimbreFiscal['UUID']);
						}
					}


					// Usar $SerieFolio como nombre del archivo PDF
					$pdfName = $SerieFolio . '.pdf';
					$route_pdf = $binder . "/" . $pdfName;
					$rpdf = $binder_temp . "/" . $file->getClientName();

					// Mover el archivo PDF a la nueva carpeta con el nuevo nombre
					if (rename($rpdf, $route_pdf)) {
						// Archivo PDF movido exitosamente con el nuevo nombre
					}
				}


				if ($file->getClientExtension() === 'xml' || $file->getClientExtension() === 'XML') {

					$route_xml = $binder_temp . "/" . $file->getClientName();
					$url = $binder_temp . "/" . $file->getClientName();

					$content = file_get_contents($url);

					// Convierte el contenido a UTF-8
					$content = mb_convert_encoding($content, 'UTF-8', 'auto');

					// Carga el XML desde el string (no directamente desde el archivo)
					$xml = simplexml_load_string($content);


					//$xml = simplexml_load_file($url);
					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);

					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
						$version = (!empty($cfdiComprobante['Version'])) ? current($cfdiComprobante['Version']) : null;
						$date = (!empty($cfdiComprobante['Fecha'])) ? current($cfdiComprobante['Fecha']) : null;
						$sub_total = (!empty($cfdiComprobante['SubTotal'])) ? current($cfdiComprobante['SubTotal']) : null;
						$total = (!empty($cfdiComprobante['Moneda'])) ? current($cfdiComprobante['Total']) : null;
						$currency = (!empty($cfdiComprobante['Total'])) ? current($cfdiComprobante['Moneda']) : null;

						if (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio'])) {
							$SerieFolio = current($cfdiComprobante['Serie']) . "-" . current($cfdiComprobante['Folio']);
						} else {
							$SerieFolio = (!empty($cfdiComprobante['Folio'])) ? current($cfdiComprobante['Folio']) : '';
						}
					}

					if (empty($SerieFolio)) {

						foreach ($xml->xpath('//cfdi:Complemento//t:TimbreFiscalDigital') as $cfdiTimbreFiscal) {

							$SerieFolio = current($cfdiTimbreFiscal['UUID']);
						}
					}



					foreach ($xml->xpath('//cfdi:Impuestos') as $Impuestos) {
						$iva = (!empty($Impuestos)) ? current($Impuestos['TotalImpuestosTrasladados']) : null;
					}

					if (empty($iva)) {
						$iva = 0.00;
					}

					foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado) {



						// Supongamos que el porcentaje del IVA se encuentra en el atributo 'TasaOCuota'

						if ((string)$Traslado['TasaOCuota'] === '0.160000') {
							$tasaOCuota = 16;
							$tasaOCuota2 = 0.16;
							break;
						} else if ((string)$Traslado['TasaOCuota'] === '0.080000') {
							$tasaOCuota = 8;
							$tasaOCuota2 = 0.08;
							break;
						} else {
							$tasaOCuota = 0.00;
							$tasaOCuota2 = 0.00;
						}
					}


					foreach ($xml->xpath('//cfdi:Impuestos//cfdi:Retencion') as $Retencion) {
						$importe_retencion = (!empty($Retencion['Importe'])) ? current($Retencion['Importe']) : null;
					}



					foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
						$rfc_cfdi =  (!empty($Emisor['Rfc'])) ? current($Emisor['Rfc']) : null;
						$name_cfdi =  (!empty($Emisor['Nombre'])) ? current($Emisor['Nombre']) : null;
					}

					if ($tipo_gasto == 19) {
						$sub_total_aero = round((($iva / $tasaOCuota2) * 100) / 100, 2);
						$revisa_total = round(($sub_total_aero + $iva), 1);
						$total_aero = round($revisa_total, 2);
					}

					if ($tipo_gasto == 8 || $tipo_gasto == 12) {

						$tasaOCuota = $this->request->getPost('iva');

						switch ($tasaOCuota) {
							case 16:
								$tasaOCuota2 = 0.16;
								break;
							case 8:
								$tasaOCuota2 = 0.08;
								break;

							default:
								$tasaOCuota2 = 0;
								break;
						}
						$iva = $this->request->getPost('iva_monto');
					}

					if ($tipo_gasto == 6) {
						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
							$rfc_cfdi =  (!empty($Emisor['Rfc'])) ? current($Emisor['Rfc']) : null;
							$name_cfdi =  (!empty($Emisor['Nombre'])) ? current($Emisor['Nombre']) : null;
						}
					}



					if ($file->getClientExtension() === 'xml' || $file->getClientExtension() === 'XML') {

						$xmlName = $SerieFolio . '.xml'; // Nombre del archivo PDF
						$route_xml2 = $binder . "/";
						$rxml = $binder_temp . "/" . $file->getClientName();
						// Mover el archivo XML a la nueva carpeta
						if (rename($rxml, $route_xml2 . $xmlName)) {
						}
						$route_xml = $route_xml2 . $xmlName;
						$route_pdf = $binder . "/" . $SerieFolio . '.pdf';
					}


					$observation = " Cliente: " . $visita_cliente;

					/* actualizar estado de monoto de targeta ->item */
					$upDataAcount = ['transaction_status' => 2];
					$this->servicesAccount->update($id_item, $upDataAcount);

					if ($politics_status != 3) {
						$this->db->query("UPDATE tbl_services_request_expenses SET verification_money = (verification_money + $total_acumulado) WHERE id_request_expenses = $folio");
					}


					/* VALIDAR EL el estado de proceso despues de incerciones y actualizacion */
					$this->db->query("UPDATE tbl_services_request_expenses 
									  SET verification_status = (CASE WHEN ABS(card_confirm_money - verification_money) <= 10 THEN 3 ELSE 2 END) 
									  WHERE id_request_expenses = $folio");

					// Divide la fecha en fecha y hora
					list($date_cfdi, $hora) = explode('T', $date);

					$politics = ($politics_status == 3) ? 1 : 2;

					$this->db->query("UPDATE tbl_services_account_status SET accounting_authorization = $politics WHERE id_account_status = $id_item");

					$total_real = ($tipo_gasto == 19) ? $total_aero : $total;
					$subtotal_real = ($tipo_gasto == 19) ? $sub_total_aero : $sub_total;

					$data = [
						"id_to_check" => $tipo_gasto,
						"id_request" => $folio,
						"id_user" => session()->id_user,
						'invoice_date' => $date_cfdi,
						'subtotal' => $subtotal_real,
						'total' => $total_real,
						'iva' => $iva,
						'iva_percentage' => $tasaOCuota,
						'rfc' => $rfc_cfdi,
						'xml_travel_routes' => $route_xml,
						'pdf_travel_routes' => $route_pdf,
						'social_reason' => $name_cfdi,
						'created_at' => $dateFileUp,
						'serie_and_folio' => $SerieFolio,
						'cfdi_version' => $version,
						'trip' => 0,
						'id_account_status' => $id_item,
						'expense_type' => $tipo,
						'accounting_authorization' => $politics,
						'facture_type' => 1,
						'observation' => $observation,
						'ncr_number' => $ncr_number,
						'caso_number' => $caso_number

					];
					$this->xmlModel->insert($data);
					$id_request = $this->db->insertID();



					/*************************************************************************** REGLAS DE VALICACION DE FACTURAS ***************************************************************/

					/*TIPO DE COMPROBACIÓN GASTOS == 1 */
					if ($tipo == 2) {
						/*tipo de gasto Aeroportuario */
						if ($tipo_gasto == 19) {

							$ecuacion = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$revisa_total = round(($ecuacion + $iva), 1);
							$total_redondeado = round($revisa_total, 2);

							if ($total_redondeado != $total) {

								$total1 = round(($total - $total_redondeado), 1);
								$iva = 0;
								$tasaOCuota = 0;
								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_travel" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $total1,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'rfc' => $rfc_cfdi,
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'expense_type' => $tipo,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number
								];
								$this->xmlModel->insert($extra);
							}
						}
						if ($tipo_gasto == 6) {
							if ($tasaOCuota2 == 0) {
								$ecuacion = 0;
							} else {

								$ecuacion = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							}

							$revisa_total = round(($ecuacion + $iva), 1);
							$total_redondeado = round($revisa_total, 2);

							if ($tasaOCuota == 16 || $tasaOCuota == 8) {
								$retencion = (!empty($importe_retencion)) ? $importe_retencion : 0;
							}



							if (!empty($trip)) {

								$trip = ($trip == 10) ? 0.10 : 0.15;
								$porcentaje =  $total * $trip; // Regla de tres
								$trip = round($porcentaje, 2);  // Quitar los decimales

								$data = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $trip,
									'total' => $trip,
									'iva' => 0,
									'iva_percentage' => "ND",
									'rfc' => $rfc_cfdi,
									'xml_travel_routes' => "",
									'pdf_travel_routes' => "",
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'trip' => $trip,
									'id_account_status' => $id_item,
									'expense_type' => $tipo,
									'accounting_authorization' => $politics,
									'facture_type' => 0,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number

								];
								$this->xmlModel->insert($data);
							}
						}
						if ($tipo_gasto == 5) {



							$sub_total = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$monto_pago = round(($sub_total + $iva), 1);
							$total_redondeado = round($monto_pago, 2);


							$data = [

								'subtotal' => $sub_total,
								'total' => $monto_pago,


							];
							$this->xmlModel->update($id_request, $data);


							if ($total_redondeado != $total) {

								$total1 = round(($total - $total_redondeado), 1);
								$iva = 0;
								$tasaOCuota = 0;


								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_travel" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $total1,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'rfc' => $rfc_cfdi,
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'expense_type' => $tipo,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number
								];
								$this->xmlModel->insert($extra);
							}
						}

						if ($tipo_gasto == 5) {

							$ecuacion = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							$revisa_total = round(($ecuacion + $iva), 1);
							$total_redondeado = round($revisa_total, 2);

							if ($tasaOCuota == 16 || $tasaOCuota == 8) {
								$retencion = (!empty($importe_retencion)) ? $importe_retencion : 0;
							}



							if (!empty($trip)) {

								$trip = ($trip == 10) ? 0.10 : 0.15;
								$porcentaje =  $total * $trip; // Regla de tres
								$trip = round($porcentaje, 2);  // Quitar los decimales

								$data = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $trip,
									'total' => $trip,
									'iva' => 0,
									'iva_percentage' => "ND",
									'rfc' => $rfc_cfdi,
									'xml_travel_routes' => "",
									'pdf_travel_routes' => "",
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'trip' => $trip,
									'id_account_status' => $id_item,
									'expense_type' => $tipo,
									'accounting_authorization' => $politics,
									'facture_type' => 1,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number

								];
								$this->xmlModel->insert($data);
							}
						}

						if ($tipo_gasto == 8 || $tipo_gasto == 12) {


							if ($iva == 0) {
								$sub_total = round((($tasaOCuota2) * 100) / 100, 2);
							} else {
								$sub_total = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							}

							$monto_pago = round(($sub_total + $iva), 1);
							$total_redondeado = round($monto_pago, 2);


							$data = [

								'subtotal' => $sub_total,
								'total' => $monto_pago,


							];
							$this->xmlModel->update($id_request, $data);


							if ($total_redondeado != $total) {

								$total1 = round(($total - $total_redondeado), 1);
								$iva = 0;
								$tasaOCuota = 0;


								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_travel" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $total1,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'rfc' => $rfc_cfdi,
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'expense_type' => $tipo,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number
								];
								$this->xmlModel->insert($extra);
							}
						}
						/*tipo de gasto Alimentos */
						if ($tipo_gasto == 6) {

							if ($tasaOCuota2 == 0) {
								$ecuacion = 0;
							} else {

								$ecuacion = round((($iva / $tasaOCuota2) * 100) / 100, 2);
							}



							$revisa_total = round(($ecuacion + $iva), 1);
							$total_redondeado = round($revisa_total, 2);

							if ($tasaOCuota == 16 || $tasaOCuota == 8) {
								$retencion = (!empty($importe_retencion)) ? $importe_retencion : 0;
							}



							if (!empty($trip)) {

								$trip = ($trip == 10) ? 0.10 : 0.15;
								$porcentaje =  $total * $trip; // Regla de tres
								$trip = round($porcentaje, 2);  // Quitar los decimales

								$data = [
									"id_to_check" => $tipo_gasto,
									"id_request" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $trip,
									'total' => $trip,
									'iva' => 0,
									'iva_percentage' => "ND",
									'rfc' => $rfc_cfdi,
									'xml_travel_routes' => "",
									'pdf_travel_routes' => "",
									'social_reason' => $name_cfdi,
									'created_at' => $dateFileUp,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'trip' => $trip,
									'id_account_status' => $id_item,
									'expense_type' => $tipo,
									'accounting_authorization' => $politics,
									'facture_type' => 1,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number

								];
								$this->xmlModel->insert($data);
							}



							/* 		if ($comprobar_monto > $total_redondeado) {

								$total1 = round(($comprobar_monto - $total_redondeado), 1);

								$iva = 0;
								$tasaOCuota = 0;
								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_travel" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $sub_total,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									"retention" => $retencion,
									'social_reason' => $name_cfdi,
									'created_at' => $date,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'expense_type' => $tipo,
									'observation' => "primera"
								];
								$this->xmlModel->insert($extra);
							} */

							if ($total > $total_redondeado) {
								$total1 = round(($total - $total_redondeado), 1);

								/* if(!empty($importe_retencion)){
								echo	$total1 = $total_redondeado - $importe_retencion;
								} */

								$iva = 0;
								$tasaOCuota = 0;
								$extra = [
									"id_to_check" => $tipo_gasto,
									"id_travel" => $folio,
									"id_user" => session()->id_user,
									'invoice_date' => $date_cfdi,
									'subtotal' => $sub_total,
									'total' => $total1,
									'iva' => $iva,
									'iva_percentage' => $tasaOCuota,
									'social_reason' => $name_cfdi,
									'created_at' => $date,
									'serie_and_folio' => $SerieFolio,
									'cfdi_version' => $version,
									'facture_type' => 2,
									'id_account_status' => $id_item,
									'expense_type' => $tipo,
									'observation' => $observation,
									'ncr_number' => $ncr_number,
									'caso_number' => $caso_number
								];
								$this->xmlModel->insert($extra);
							}

							if (!empty($retencion)) {

								$extra = ["retention" => $retencion];
								$this->xmlModel->update($id_request, $extra);
								$result = $this->db->transComplete();

								$dataNotifycation = [
									'folio' => $folio,
									'lugar' => $lugares,
									'cantidad' => $cantidades,
									'fecha' => $fechas
								];
								$id_user = session()->id_user;
								// Ejecutar la consulta
								$query = $this->db->query("SELECT
																email
															FROM
																tbl_users 
															
															WHERE
																id_user = $id_user")->getRow();
								$email = $query->email;
								$user = session()->name . " " . session()->surname;

								$this->notifyExpensesAndTravel($email, $user, $dataNotifycation, 2);

								return ($result) ? json_encode(true) : json_encode(false);
							}
						}
					}
				}
			}
		}


		$result = $this->db->transComplete();

		$dataNotifycation = [
			'folio' => $folio,
			'lugar' => $lugares,
			'cantidad' => $cantidades,
			'fecha' => $fechas
		];
		$id_user = session()->id_user;
		// Ejecutar la consulta
		$query = $this->db->query("SELECT
										email
									FROM
										tbl_users 
									
									WHERE
										id_user = $id_user")->getRow();
		$email = $query->email;
		$user = session()->name . " " . session()->surname;

		$this->notifyExpensesAndTravel($email, $user, $dataNotifycation, 2);


		return ($result) ? json_encode(true) : json_encode(false);
	}

	public function subirEfExXML()
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

		$this->db->transStart();

		$date = date("Y-m-d_H:i:s");

		$lugares = $this->request->getPost('lugares');
		$cantidades = $this->request->getPost('cantidades');
		$fechas = $this->request->getPost('fechas');

		$folioEncript = $this->request->getPost('folio');
		$tipo_gasto = $this->request->getPost('tipo_gasto');
		$tipo = $this->request->getPost('type');
		$id_item = $this->request->getPost('id_item');
		$politics_status = $this->request->getPost('politics_status');


		$cantidad = $this->request->getPost('cantidad');
		$proveedor = $this->request->getPost('proveedor');
		$fecha = $this->request->getPost('fecha');
		$ncr_number = $this->request->getPost('number_ncr');
		$caso_number = $this->request->getPost('number_caso');


		$rfc = 'XAXX010101000';
		//die();
		if ($tipo == 1) {
			$query_folio = $this->db->query("SELECT id_request_travel AS folio
									FROM tbl_services_request_travel
									WHERE
									MD5(concat('$key',id_request_travel)) = '$folioEncript' ")->getRow();
		} else if ($tipo == 2) {
			$query_folio = $this->db->query("SELECT id_request_expenses AS folio
									FROM tbl_services_request_expenses
									WHERE
									MD5(concat('$key',id_request_expenses)) = '$folioEncript' ")->getRow();
		}

		$folio = $query_folio->folio;



		$tipo_gastos = ($tipo == 1) ? "Viaticos" : "Gastos";

		$tipo2 = ($tipo == 1) ? "V" : "G";
		$binder =  FCPATH . "XML/" . $tipo_gastos . "/" . $tipo2 . "_folio_" . $folio;


		if (!file_exists($binder)) {
			mkdir($binder, 0750, true);
		}


		if ($this->request->getFileMultiple('upload')) {


			foreach ($this->request->getFileMultiple('upload') as $file) {
				$file->move($binder);
				$name = $file->getClientName();
				//$ext = $file->getClientExtension();
				$route_pdf = $binder . "/" . $name;


				/* actualizar estado de monoto de targeta ->item */
				$upDataAcount = [
					'transaction_status' => 2,
				];
				$this->servicesAccount->update($id_item, $upDataAcount);

				$tbl_ = ($tipo == 1) ? 'tbl_services_request_travel' : 'tbl_services_request_expenses';
				$colum = ($tipo == 1) ? 'id_request_travel' : 'id_request_expenses';

				$update_folio = $this->db->query("UPDATE $tbl_
												SET verification_money = (verification_money + $cantidad)
												WHERE
												$colum = $folio");

				/* VALIDAR EL el estado de proceso despues de incerciones y actualizacion */
				$this->db->query("UPDATE $tbl_ 
								  SET verification_status = (CASE WHEN ABS(card_confirm_money - verification_money) <= 10 THEN 3 ELSE 2 END) 
								  WHERE id_request_travel = $folio");



				$politics = ($politics_status == 3) ? 1 : 2;

				$data = [
					"id_to_check" => $tipo,
					"id_request" => $folio,
					"id_user" => session()->id_user,
					'invoice_date' => $fecha,
					'subtotal' => $cantidad,
					'total' => $cantidad,
					'iva' => 0,
					'iva_percentage' => 0,
					'rfc' => $rfc,
					'xml_travel_routes' => "",
					'pdf_travel_routes' => $route_pdf,
					'social_reason' => $proveedor,
					'created_at' => $date,
					'serie_and_folio' => "",
					'cfdi_version' => "",
					'expense_type' => $tipo_gasto,
					'id_account_status' => $id_item,
					'accounting_authorization' => $politics,
					'ncr_number' => $ncr_number,
					'caso_number' => $caso_number
				];
				$this->xmlModel->insert($data);
			}
		}


		$result = $this->db->transComplete();

		$dataNotifycation = [
			'folio' => $folio,
			'lugar' => $lugares,
			'cantidad' => $cantidades,
			'fecha' => $fechas
		];
		$id_user = session()->id_user;
		// Ejecutar la consulta
		$query = $this->db->query("SELECT
										email
									FROM
										tbl_users 
									
									WHERE
										id_user = $id_user")->getRow();
		$email = $query->email;
		$user = session()->name . " " . session()->surname;

		$this->notifyExpensesAndTravel($email, $user, $dataNotifycation, $tipo);


		return ($result) ? json_encode(true) : json_encode(false);
	}

	public function subirEfExGastosXML()
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

		$this->db->transStart();

		$date = date("Y-m-d_H:i:s");

		$lugares = $this->request->getPost('lugares');
		$cantidades = $this->request->getPost('cantidades');
		$fechas = $this->request->getPost('fechas');

		$folioEncript = $this->request->getPost('folio');
		$tipo_gasto = $this->request->getPost('tipo_gasto');
		$tipo = $this->request->getPost('type');
		$id_item = $this->request->getPost('id_item');
		$politics_status = $this->request->getPost('politics_status');


		$cantidad = $this->request->getPost('cantidad');
		$proveedor = "Vale Azul";
		$fecha = $this->request->getPost('fecha');
		$ncr_number = $this->request->getPost('number_ncr');
		$caso_number = $this->request->getPost('number_caso');

		$rfc = 'XAXX010101000';
		//die();

		$query_folio = $this->db->query("SELECT id_request_expenses AS folio
									FROM tbl_services_request_expenses
									WHERE
									MD5(concat('$key',id_request_expenses)) = '$folioEncript' ")->getRow();


		$folio = $query_folio->folio;



		$tipo_gastos = "Gastos";


		$binder =  FCPATH . "XML/" . $tipo_gastos . "/G_folio_" . $folio;


		if (!file_exists($binder)) {
			mkdir($binder, 0750, true);
		}


		if ($this->request->getFileMultiple('upload')) {


			foreach ($this->request->getFileMultiple('upload') as $file) {
				$file->move($binder);
				$name = $file->getClientName();
				//$ext = $file->getClientExtension();
				$route_pdf = $binder . "/" . $name;


				/* actualizar estado de monoto de targeta ->item */
				$upDataAcount = [
					'transaction_status' => 2,
				];
				$this->servicesAccount->update($id_item, $upDataAcount);

				$this->db->query("UPDATE tbl_services_request_expenses
												SET verification_money = (verification_money + $cantidad)
												WHERE
												id_request_expenses = $folio");

				/* VALIDAR EL el estado de proceso despues de incerciones y actualizacion */
				$this->db->query("UPDATE tbl_services_request_expenses
								  SET verification_status = (CASE WHEN ABS(card_confirm_money - verification_money) <= 10 THEN 3 ELSE 2 END) 
								  WHERE id_request_expenses = $folio");



				$politics = ($politics_status == 3) ? 1 : 2;

				$data = [
					"id_to_check" => $tipo,
					"id_request" => $folio,
					"id_user" => session()->id_user,
					'invoice_date' => $fecha,
					'subtotal' => $cantidad,
					'total' => $cantidad,
					'iva' => 0,
					'iva_percentage' => 0,
					'rfc' => $rfc,
					'xml_travel_routes' => "",
					'pdf_travel_routes' => $route_pdf,
					'social_reason' => $proveedor,
					'created_at' => $date,
					'serie_and_folio' => "",
					'cfdi_version' => "",
					'expense_type' => $tipo,
					'id_account_status' => $id_item,
					'accounting_authorization' => $politics,
					'ncr_number' => $ncr_number,
					'caso_number' => $caso_number
				];
				$this->xmlModel->insert($data);
			}
		}


		$result = $this->db->transComplete();


		$dataNotifycation = [
			'folio' => $folio,
			'lugar' => $lugares,
			'cantidad' => $cantidades,
			'fecha' => $fechas
		];
		$id_user = session()->id_user;
		// Ejecutar la consulta
		$query = $this->db->query("SELECT
										email
									FROM
										tbl_users 
									
									WHERE
										id_user = $id_user")->getRow();
		$email = $query->email;
		$user = session()->name . " " . session()->surname;

		$this->notifyExpensesAndTravel($email, $user, $dataNotifycation, 2);



		return ($result) ? json_encode(true) : json_encode(false);
	}

	public function revisarXML()
	{
		try {
			//code...
			$this->db->transStart();

			$dateFileUp = strval(date("Y-m-d_H:i:s"));
			$date = date("Y-m-d_H:i:s");
			$binder =  FCPATH . "temp_mxl/";

			libxml_use_internal_errors(true);

			if ($this->request->getFileMultiple('userfile')) {
				foreach ($this->request->getFileMultiple('userfile') as $file) {

					$ext = strtolower($file->getClientExtension());
					if ($ext !== 'xml') {
						continue;
					}

					$folio_cfdi = null;
					$nameArchive = $file->getClientName();
					$file->move($binder);

					/* $data = [
					'name' =>  $file->getClientName(),
					'type'  => $file->getClientMimeType(),
					'ext' 	=> $file->getClientExtension(),
					'size' 	=> $file->getSize('kb'),
				]; */

					if ($file->getClientExtension() === 'xml' || $file->getClientExtension() === 'XML') {




						$url = $binder . $file->getClientName();

						if (! is_file($url) || ! is_readable($url)) {
							throw new \Exception("No se puede leer $url");
						}

						$content = file_get_contents($url);

						// Convierte el contenido a UTF-8
						$content = mb_convert_encoding($content, 'UTF-8', 'auto');

						// Carga el XML desde el string (no directamente desde el archivo)
						$xml = simplexml_load_string($content);

						if ($xml === false) {
							$errs = libxml_get_errors();
							libxml_clear_errors();
							$errMsg = array_reduce($errs, function ($carry, $e) {
								return $carry . trim($e->message) . " (línea {$e->line})\n";
							}, "");
							throw new \Exception("XML inválido:\n" . $errMsg);
						}

						//$xml = simplexml_load_file($url);
						$ns = $xml->getNamespaces(true);
						$xml->registerXPathNamespace('c', $ns['cfdi']);
						$xml->registerXPathNamespace('t', $ns['tfd']);


						//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
						foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
							$version = (!empty($cfdiComprobante['Version'])) ? current($cfdiComprobante['Version']) : null;
							$date_cfdi = (!empty($cfdiComprobante['Fecha'])) ? current($cfdiComprobante['Fecha']) : null;
							$sub_total = (!empty($cfdiComprobante['SubTotal'])) ? current($cfdiComprobante['SubTotal']) : null;
							$total = (!empty($cfdiComprobante['Total'])) ? current($cfdiComprobante['Total']) : null;
							$currency = (!empty($cfdiComprobante['Moneda'])) ? current($cfdiComprobante['Moneda']) : null;
							//$iva = $cfdiComprobante['Importe'];

							if (!empty($cfdiComprobante['Serie'] && $cfdiComprobante['Folio'])) {
								$SerieFolio = current($cfdiComprobante['Serie']) . "-" . current($cfdiComprobante['Folio']);
							} else if (!empty($cfdiComprobante['Folio'])) {
								$SerieFolio = current($cfdiComprobante['Folio']);
							} else {
								$SerieFolio = "";
							}
						}

						if (empty($SerieFolio)) {

							foreach ($xml->xpath('//t:TimbreFiscalDigital') as $cfdiTimbreFiscal) {

								$SerieFolio = current($cfdiTimbreFiscal['UUID']);
							}
						}

						foreach ($xml->xpath('//cfdi:Impuestos') as $Impuestos) {
							$iva = (!empty($Impuestos['TotalImpuestosTrasladados'])) ? current($Impuestos['TotalImpuestosTrasladados']) : null;
						}

						if (empty($iva)) {
							$iva = 0;
						}

						foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
							$rfc_cfdi =  (!empty($Emisor['Rfc'])) ? current($Emisor['Rfc']) : null;
							$name_cfdi =  (!empty($Emisor['Nombre'])) ? current($Emisor['Nombre']) : null;
						}
					}
				}

				$data = [
					'folio' => $SerieFolio,
					'fecha_factura' => $date_cfdi,
					'sub_total' => floatval($sub_total),
					'total' => floatval($total),
					'iva' => floatval($iva),
					'moneda' => $currency,
					'rfc' => $rfc_cfdi,
					'nombre_proveedor' => $name_cfdi,
					'created_at' => $date,
					'nombre' => $SerieFolio,
					'version_cfdi' => $version
				];
			}

			// Verificar si el archivo existe antes de intentar borrarlo

			if (file_exists($url)) {
				unlink($url);
			}

			$result = $this->db->transComplete();


			return ($result) ? json_encode($data) : json_encode(false);
		} catch (Exception $e) {
			return json_encode($e);
		}

		$xml = simplexml_load_file(FCPATH . "/XML/37VLZ.xml");
		$ns = $xml->getNamespaces(true);
		$xml->registerXPathNamespace('c', $ns['cfdi']);
		$xml->registerXPathNamespace('t', $ns['tfd']);
		$xml->registerXPathNamespace('r', $ns['registrofiscal']);


		//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
		foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
			echo $cfdiComprobante['Version'];
			echo "<br />";
			echo $cfdiComprobante['Fecha'];
			echo "<br />";
			echo $cfdiComprobante['Sello'];
			echo "<br />";
			echo $cfdiComprobante['SubTotal'];
			echo "<br />";
			echo $cfdiComprobante['Total'];
			echo "<br />";
			echo $cfdiComprobante['Certificado'];
			echo "<br />";
			echo $cfdiComprobante['FormaDePago'];
			echo "<br />";
			echo $cfdiComprobante['NoCertificado'];
			echo "<br />";
			echo $cfdiComprobante['TipoDeComprobante'];
			echo "<br />";
			echo $cfdiComprobante['Moneda'];
			echo "<br />**********************************************************************Comprobante*************************************************************************<br />";
		}
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
			echo $Emisor['Rfc'];
			echo "<br />";
			echo $Emisor['Nombre'];
			echo "<br />**********************************************************************Comprobante Emisor*************************************************************************<br />";
		}
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal) {
			echo $DomicilioFiscal['Pais'];
			echo "<br />";
			echo $DomicilioFiscal['Calle'];
			echo "<br />";
			echo $DomicilioFiscal['Estado'];
			echo "<br />";
			echo $DomicilioFiscal['Colonia'];
			echo "<br />";
			echo $DomicilioFiscal['Municipio'];
			echo "<br />";
			echo $DomicilioFiscal['NoExterior'];
			echo "<br />";
			echo $DomicilioFiscal['CodigoPostal'];
			echo "<br />**********************************************************************Comprobante Emisor DomicilioFiscal*************************************************************************<br />";
		}
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:ExpedidoEn') as $ExpedidoEn) {
			echo $ExpedidoEn['Pais'];
			echo "<br />";
			echo $ExpedidoEn['Calle'];
			echo "<br />";
			echo $ExpedidoEn['Estado'];
			echo "<br />";
			echo $ExpedidoEn['Colonia'];
			echo "<br />";
			echo $ExpedidoEn['NoExterior'];
			echo "<br />";
			echo $ExpedidoEn['CodigoPostal'];
			echo "<br />*****************************************************************Comprobante Emisor ExpedidoEn**********************************************************************<br />";
		}
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor) {
			echo $Receptor['Rfc'];
			echo "<br />";
			echo $Receptor['Nombre'];
			echo "<br />*****************************************************************Comprobante Receptor**********************************************************************<br />";
		}
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio) {
			echo $ReceptorDomicilio['Pais'];
			echo "<br />";
			echo $ReceptorDomicilio['Calle'];
			echo "<br />";
			echo $ReceptorDomicilio['Estado'];
			echo "<br />";
			echo $ReceptorDomicilio['Colonia'];
			echo "<br />";
			echo $ReceptorDomicilio['Municipio'];
			echo "<br />";
			echo $ReceptorDomicilio['NoExterior'];
			echo "<br />";
			echo $ReceptorDomicilio['NoInterior'];
			echo "<br />";
			echo $ReceptorDomicilio['CodigoPostal'];
			echo "<br />*****************************************************************Comprobante Receptor Domicilio**********************************************************************<br />";
		}
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto) {
			echo "<br />";
			echo $Concepto['Unidad'];
			echo "<br />";
			echo $Concepto['Importe'];
			echo "<br />";
			echo $Concepto['Cantidad'];
			echo "<br />";
			echo $Concepto['Descripcion'];
			echo "<br />";
			echo $Concepto['ValorUnitario'];
			echo "<br />*****************************************************************Comprobante Conceptos Concepto**********************************************************************<br />";
			echo "<br />";
		}
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado) {
			echo $Traslado['Tasa'];
			echo "<br />";
			echo $Traslado['Importe'];
			echo "<br />";
			echo $Traslado['Impuesto'];
			echo "<br />************************************************************Comprobante Impuestos Traslados Traslado**********************************************************************<br />";
			echo "<br />";
		}

		//ESTA ULTIMA PARTE ES LA QUE GENERABA EL ERROR
		foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
			echo $tfd['SelloCFD'];
			echo "<br />";
			echo $tfd['FechaTimbrado'];
			echo "<br />";
			echo $tfd['UUID'];
			echo "<br />";
			echo $tfd['NoCertificadoSAT'];
			echo "<br />";
			echo $tfd['Version'];
			echo "<br />*****************************************************************Timbre Fiscal Digital**********************************************************************<br />";
			echo $tfd['SelloSAT'];
		}
	}

	function deletAccountStatus()
	{
		try {
			$idItem = $this->request->getPost("id_item");
			$query = $this->db->query("SELECT transaction_status AS estado, `type`, id_request
				FROM tbl_services_account_status 
				WHERE active_status = 1 
				AND id_account_status = $idItem
			")->getRow();

			$tbl_field = ($query->type == 1) ? "travel" : "expenses";

			$this->db->transStart();

			if ($query->estado == 2) {
				$idUser = session()->id_user;
				$toDay = date("Y-m-d H:i:s");

				$this->db->query("UPDATE tbl_services_verification_items_travel_expenses 
				SET active_status = 2, id_cancel = $idUser, cancel_at = '$toDay'
				WHERE id_account_status = $idItem");

				$this->db->query("UPDATE tbl_services_account_status 
				SET transaction_status = 1
				WHERE id_account_status = $idItem");
				$this->db->query("UPDATE tbl_services_request_$tbl_field 
					SET a.verification_money = a.verification_money - ( SELECT b.amount_mxn 
						FROM tbl_services_account_status AS b 
						WHERE b.active_status = 1 
						AND b.id_account_status = $idItem)
					WHERE id_request_$tbl_field = $query->id_request
				");
			}

			$this->db->query("UPDATE tbl_services_request_$tbl_field AS a
				SET a.card_confirm_money = a.card_confirm_money - ( SELECT b.amount_mxn 
					FROM tbl_services_account_status AS b 
					WHERE b.active_status = 1 
					AND b.id_account_status = $idItem)
				WHERE a.id_request_$tbl_field = $query->id_request
			");

			$this->db->query("UPDATE tbl_services_account_status SET active_status = 2 
			WHERE id_account_status = $idItem");

			$result = $this->db->transComplete();
			return ($result) ? json_encode(true) : json_encode(false);
		} catch (Exception $e) {
			return json_encode(false);
		}
	}


	public function downloadZip()
	{

		$data = json_decode(stripslashes($this->request->getPost('data')));
		$folio = $data->folio;
		$tipo_gastos = ($data->tipo_gasto == 1) ? "Viaticos" : "Gastos";
		$tipo = ($data->tipo_gasto == 1) ? "V" : "G";
		$binder =  FCPATH . "XML/" . $tipo_gastos . "/" . $tipo . "_folio_" . $folio;
		$nameArchive =  $tipo . "_folio_" . $folio . ".zip";

		//Creamos el archivo
		$zip = new \ZipArchive();

		//abrimos el archivo y lo preparamos para agregarle archivos
		$zip->open($nameArchive, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

		//indicamos cual es la carpeta que se quiere comprimir
		$origen = $binder;

		//Ahora usando funciones de recursividad vamos a explorar todo el directorio y a enlistar todos los archivos contenidos en la carpeta
		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($origen),
			\RecursiveIteratorIterator::LEAVES_ONLY
		);

		//Ahora recorremos el arreglo con los nombres los archivos y carpetas y se adjuntan en el zip
		foreach ($files as $name => $file) {
			if (!$file->isDir()) {
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($origen) + 1);

				$zip->addFile($filePath, $relativePath);
			}
		}

		//Se cierra el Zip
		$zip->close();
		//verificamos que exista el archivo e Implementamos la descarga del Zip
		if (file_exists($nameArchive)) {
			header('Content-type: "application/zip"');
			header('Content-Disposition: attachment; filename="' . $nameArchive . '"');
			readfile($nameArchive);
			//Eliminamos el archivo Zip del server una vez descargado
			unlink($nameArchive);
		}
	}

	public function deleteTravels()
	{
		try {
			$folio = $this->request->getPost('folio');
			$data = [
				'active_status' => 2,
				'id_deleted' => session()->id_user,
				'deleted_at' => date("Y-m-d H:i:s"),
			];
			$result = $this->servicesTravelModel->update($folio, $data);

			return ($result) ? json_encode($result) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function deleteExpenses()
	{
		try {
			$folio = $this->request->getPost('folio');
			$data = [
				'active_status' => 2,
				'id_deleted' => session()->id_user,
				'deleted_at' => date("Y-m-d H:i:s"),
			];
			$result = $this->expensesModel->update($folio, $data);
			return ($result) ? json_encode($result) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function pdfRequestTravel($id_request_travel = null)
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$query = $this->db->query("SELECT a.*, b.departament
            FROM tbl_services_request_travel AS a
            JOIN cat_departament AS b ON a.cost_center = b.cost_center
            WHERE 
            MD5(concat('" . $key . "',a.id_request_travel))='" . $id_request_travel . "'");
		$dataRequest = $query->getRow();

		$data = ["request" => $dataRequest];

		$html2 = view('pdf/pdf_request_travel', $data);

		$html = ob_get_clean();

		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


		$html2pdf->pdf->SetTitle('Solicitud de Viaticos');

		$html2pdf->writeHTML($html2);

		ob_end_clean();
		$html2pdf->output('solicitud_viticos_' . $id_request_travel . '.pdf', 'I');
	}

	public function pdfRequestExpenses($id_request_expenses = null)
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$query = $this->db->query("SELECT *
								FROM
								tbl_services_request_expenses
								WHERE
								MD5(concat('" . $key . "',id_request_expenses))='" . $id_request_expenses . "'");
		$dataRequest = $query->getRow();

		$query0 = $this->db->query("SELECT id_category, amount, `definition`
                            FROM tbl_services_verification_items_travel_expenses
                            WHERE id_request_expenses = " . $dataRequest->id_request_expenses . " AND active_status = 1");
		$dataItem =  $query0->getResultObject();
		$data = [
			"request" => $dataRequest,
			"item" => $dataItem
		];

		$html2 = view('pdf/pdf_request_expenses', $data);

		$html = ob_get_clean();

		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


		$html2pdf->pdf->SetTitle('Solicitud de Gastos');

		$html2pdf->writeHTML($html2);

		ob_end_clean();
		$html2pdf->output('solicitud_gastos_' . $id_request_expenses . '.pdf', 'I');
	}

	public function editExpenses()
	{
		try {

			$id_expenses = $this->request->getPost('id_folio');
			$info = $this->expensesModel->requestExpenses($id_expenses);
			$items = $this->itemExpensesModel->requestExpensesItems($id_expenses);

			$data = ['info' => $info, 'items' => $items];

			return (count($info) > 0) ? json_encode($data) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function authorizedExpenses()
	{
		$idExpenses = $this->request->getPost('id_folio');
		$status = $this->request->getPost('status');
		if ($status == 0) {
			$motive = $this->request->getPost('motivo');
			$data = [
				'motive_cacel' => $motive,
				'request_status' => $status,
				'id_canceled' => session()->id_user,
				'canceled_at' => date("Y-m-d H:i:s"),
			];
		} else {
			$data = [
				'request_status' => $status,
				'id_autoriced' => session()->id_user,
				'autoriced_at' => date("Y-m-d H:i:s"),
			];
		}
		$result = $this->expensesModel->update($idExpenses, $data);
		return json_encode($result);
	}

	public function editTravel()
	{
		try {

			$id_travel = $this->request->getPost('id_folio');
			$data = $this->servicesTravelModel->requestTravel($id_travel);
			//var_dump($data);
			return ($data) ? json_encode($data) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function authorizedTravel()
	{
		try {
			$idTravel = $this->request->getPost('id_folio');
			$status = $this->request->getPost('status');
			if ($status == 0) {
				$motive = $this->request->getPost('motivo');
				$data = [
					'motive_cacel' => $motive,
					'request_status' => $status,
					'id_canceled' => session()->id_user,
					'canceled_at' => date("Y-m-d H:i:s"),
				];
			} else {
				$data = [
					'request_status' => $status,
					'id_autoriced' => session()->id_user,
					'autoriced_at' => date("Y-m-d H:i:s"),
				];
			}
			$result = $this->servicesTravelModel->update($idTravel, $data);
			return ($result) ? json_encode($result) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function perDiemTravel()
	{

		return  json_encode($this->servicesTravelModel->perDiemAmount(session()->payroll_number));
	}

	function dataRequestHeadLetters()
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		// MD5(concat('" . $key . "',id_es))='" . $id_permission . "'
		$folio = $this->request->getPost("folio");
		$type = $this->request->getPost("type");

		if ($type == 1) { // Viaticos
			$query = $this->db->query("SELECT a.id_request_travel AS folio, a.user_name, 
				FORMAT(a.money_daily_for_grade, 2) AS monto_diario,
				FORMAT(a.total_money, 2) AS solicitado,
				FORMAT(a.card_confirm_money, 2) AS cuenta,
				FORMAT(a.verification_money, 2) AS comprobado,
				-- FORMAT( IF((a.total_money  + 10) < a.card_confirm_money,
				-- 	((a.card_confirm_money - a.total_money) + (a.total_money - a.verification_money))
				-- ,a.card_confirm_money - a.verification_money) ,2) As descuento,
				CASE
					WHEN FORMAT(
						IF((a.total_money + 10) < a.card_confirm_money,
							((a.card_confirm_money - a.total_money) + (a.total_money - a.verification_money)),
							a.card_confirm_money - a.verification_money
						), 2) < 0 THEN 0.0
					ELSE FORMAT(
						IF((a.total_money + 10) < a.card_confirm_money,
							((a.card_confirm_money - a.total_money) + (a.total_money - a.verification_money)),
							a.card_confirm_money - a.verification_money
						), 2)
				END AS descuento,
				(SELECT ct1.roman_num FROM cat_level_grade AS ct1 WHERE ct1.id_level = a.id_grade_level) AS icon_grado
				FROM tbl_services_request_travel AS a 
			WHERE MD5(CONCAT('$key',a.id_request_travel)) = '$folio'")->getRow();
		} else { // Gasto
			$query = $this->db->query("SELECT a.id_request_expenses AS folio, a.user_name,
				CONCAT(0) AS monto_diario,
				FORMAT(a.total_money, 2) AS solicitado,
				FORMAT(a.card_confirm_money, 2) AS cuenta,
				FORMAT(a.verification_money, 2) AS comprobado,
				-- FORMAT( IF((a.total_money  + 10) < a.card_confirm_money,
				-- 	((a.card_confirm_money - a.total_money) + (a.total_money - a.verification_money))
				-- ,a.card_confirm_money - a.verification_money) ,2) As descuento,
				CASE
					WHEN FORMAT(
						IF((a.total_money + 10) < a.card_confirm_money,
							((a.card_confirm_money - a.total_money) + (a.total_money - a.verification_money)),
							a.card_confirm_money - a.verification_money
						), 2) < 0 THEN 0.0
					ELSE FORMAT(
						IF((a.total_money + 10) < a.card_confirm_money,
							((a.card_confirm_money - a.total_money) + (a.total_money - a.verification_money)),
							a.card_confirm_money - a.verification_money
						), 2)
				END AS descuento,
			CONCAT(' ') AS icon_grado
			FROM tbl_services_request_expenses AS a 
			WHERE MD5(CONCAT('$key',a.id_request_expenses)) = '$folio'")->getRow();
		}
		/* aqui
		
		*/
		return json_encode($query);
	}

	function insertTravelAccount()
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		try {
			$idRequestEncrypt = $this->request->getPost("id_request");
			$type = $this->request->getPost("type");
			$guieFile = $this->request->getFile("archivo");

			if ($type == 1) {
				$query = $this->db->query("SELECT id_request_travel AS id_request, id_user FROM tbl_services_request_travel WHERE MD5(CONCAT('$key',id_request_travel)) = '$idRequestEncrypt'")->getRow();
				$idRequest = $query->id_request;
				$idUser = $query->id_user;
			} else {
				$query = $this->db->query("SELECT id_request_expenses AS id_request, id_user FROM tbl_services_request_expenses WHERE MD5(CONCAT('$key',id_request_expenses)) = '$idRequestEncrypt'")->getRow();
				$idRequest = $query->id_request;
				$idUser = $query->id_user;
			}

			/* $document = IOFactory::load($guieFile);    //preparamos documento para su lectura
			$sheet = $document->getActiveSheet();  // Obtener la hoja de trabajo
			$Rows = $sheet->getHighestDataRow(); //obtrnemos el numero maxicmo de FILAS que tengan datos */

			// Forzar lectura de solo datos
			$reader = IOFactory::createReaderForFile($guieFile);
			$reader->setReadDataOnly(true);
			$document = $reader->load($guieFile);
			$sheet = $document->getActiveSheet();
			$Rows = $sheet->getHighestDataRow();

			// $this->db->transStart();

			for ($iRow = 2; $iRow <= $Rows; $iRow++) {

				// for ($iColum=0; $iColum < 12 ; $iColum++)  { // automatizar reccorrido de columnas
				$dateTransaction = $sheet->getCellByColumnAndRow(1, $iRow)->getValue();
				$locationTransaction = $sheet->getCellByColumnAndRow(2, $iRow)->getValue();
				$amount = $sheet->getCellByColumnAndRow(3, $iRow)->getValue();
				$divisa = $sheet->getCellByColumnAndRow(4, $iRow)->getValue();
				$amountMxn = $sheet->getCellByColumnAndRow(5, $iRow)->getValue();
				$ruleCodes = $sheet->getCellByColumnAndRow(6, $iRow)->getValue();


				if ($dateTransaction == '' || $locationTransaction == '' || $amount == '' || $divisa == '' || $amountMxn == '') {
					return json_encode($iRow);
				}
				$dateReference = new DateTime('1900-01-01');
				$dateTransactionModify = $dateReference->modify("-2 days")->modify("+$dateTransaction days")->format('Y-m-d');
				$data = [
					'id_request' => $idRequest,
					'type' => $type,
					'id_user' => $idUser,
					'date_transaction' => $dateTransactionModify,
					'location_transaction' => $locationTransaction,
					'amount' => $amount,
					'divisa' => $divisa,
					'amount_mxn' => $amountMxn,
					'rule_code' => $ruleCodes,
					'id_created' => session()->id_user,
					'created_at' => date("Y-m-d H:i:s"),
				];
				$this->servicesAccount->insert($data);
			}
			if ($type == 1) {
				$this->db->query("UPDATE tbl_services_request_travel 
				SET card_confirm_money = (SELECT SUM(a.amount) 
					FROM tbl_services_account_status AS a 
					WHERE a.active_status = 1 
					AND a.id_request = $idRequest AND a.type = $type) 
				WHERE active_status = 1 
			AND id_request_travel = $idRequest");
				// aqui 
			} else if ($type == 2) {
				$this->db->query("UPDATE tbl_services_request_expenses 
				SET card_confirm_money = (SELECT SUM(a.amount) 
					FROM tbl_services_account_status AS a 
					WHERE a.active_status = 1 
					AND a.id_request = $idRequest AND a.type = $type) 
				WHERE active_status = 1 
			AND id_request_expenses = $idRequest");
			}
			// return json_encode(false);
			$this->db->query("UPDATE tbl_services_account_status SET politics_status = CASE 
			   WHEN DATEDIFF(date_transaction, CURDATE()) > -5 THEN
				   1
			   WHEN DATEDIFF(date_transaction, CURDATE()) = -5 THEN
				   2
			   WHEN DATEDIFF(date_transaction, CURDATE()) < -5 THEN
				   3
		   END 
		   WHERE active_status = 1
		AND transaction_status = 1");
			// $result = $this->db->transComplete();	
			return json_encode(true);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	function insertTravelAccountMassive()
	{
		// $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		try {
			$guieFile = $this->request->getFile("archivo");

			$document = IOFactory::load($guieFile);    //preparamos documento para su lectura
			$sheet = $document->getActiveSheet();  // Obtener la hoja de trabajo
			$Rows = $sheet->getHighestDataRow(); //obtrnemos el numero maxicmo de FILAS que tengan datos

			$ArrayType = ['Viatico' => 1, 'Gasto' => 2];
			$ArrayTypeSQL = [1 => "travel", 2 => "expenses"];

			$this->db->transStart();

			for ($iRow = 2; $iRow <= $Rows; $iRow++) {

				// for ($iColum=0; $iColum < 12 ; $iColum++)  { // automatizar reccorrido de columnas
				$dateTransaction = $sheet->getCellByColumnAndRow(1, $iRow)->getValue();
				$locationTransaction = $sheet->getCellByColumnAndRow(2, $iRow)->getValue();
				$amount = $sheet->getCellByColumnAndRow(3, $iRow)->getValue();
				$divisa = $sheet->getCellByColumnAndRow(4, $iRow)->getValue();
				$amountMxn = $sheet->getCellByColumnAndRow(5, $iRow)->getValue();
				$tagTypeTxt = $sheet->getCellByColumnAndRow(7, $iRow)->getValue();
				$tagNumber = $sheet->getCellByColumnAndRow(6, $iRow)->getValue();
				$ruleCode = $sheet->getCellByColumnAndRow(8, $iRow)->getValue();
				$type = $ArrayType[$tagTypeTxt] ?? 2;
				if ($dateTransaction == '' || $locationTransaction == '' || $amount == '' || $divisa == '' || $amountMxn == '' || $tagNumber == '') {
					return json_encode($iRow);
				}
				$sqlTblField = $ArrayTypeSQL[$type];

				$dateReference = new DateTime('1900-01-01');
				$dateTransactionModify = $dateReference->modify("-2 days")->modify("+$dateTransaction days")->format('Y-m-d');

				$query = $this->db->query("SELECT st1.id_request_$sqlTblField AS idRequest, st1.id_user AS idUser
					FROM tbl_services_request_$sqlTblField AS st1 
					WHERE st1.active_status = 1 
					AND st1.id_user IN (
						SELECT id_user 
						FROM tbl_assign_travel_expenses_manager 
						WHERE active_status = 1 
						AND tag = $tagNumber) 
					AND st1.created_at IN (
						SELECT MAX(in1.created_at) 
						FROM tbl_services_request_$sqlTblField AS in1 
						WHERE in1.active_status = 1 
						AND in1.id_user = st1.id_user 
						AND request_status = 2
					)
				")->getRow();

				$data = [
					'id_request' => $query->idRequest,
					'type' => $type,
					'id_user' => $query->idUser,
					'tag' => $tagNumber,
					'date_transaction' => $dateTransactionModify,
					'location_transaction' => $locationTransaction,
					'amount' => $amount,
					'divisa' => $divisa,
					'amount_mxn' => $amountMxn,
					'rule_code' => $ruleCode,
					'id_created' => session()->id_user,
					'created_at' => date("Y-m-d H:i:s"),
				];
				$this->servicesAccount->insert($data);
			}


			$this->updateRequestAndStatusAcount($sqlTblField, $query->idRequest, $type);



			$result = $this->db->transComplete();
			return ($result) ? json_encode(true) : json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}
	function nameUserByFile()
	{
		$data = $this->db->query("SELECT  id_user, REPLACE(user_name, ' ', '_') AS user_name FROM tbl_services_request_travel WHERE active_status = 1 AND request_status > 1
		UNION 
		SELECT id_user, REPLACE(user_name, ' ', '_') AS user_name FROM tbl_services_request_expenses WHERE active_status = 1 AND request_status > 1;
		")->getResult();
		return json_encode($data);
	}
	function listTravelAccount()
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$idRequest = $this->request->getPost("id_request");
		$type = $this->request->getPost("type");
		$data = $this->db->query("SELECT a.id_account_status AS id_item,
				DATE_FORMAT(a.date_transaction,'%d/%m/%Y') AS fecha,
				UPPER(a.location_transaction) AS lugar,
				b.pdf_travel_routes, FORMAT(a.amount,2) AS amount, a.divisa, a.transaction_status,
				IF(a.rule_code IS NOT NULL,a.rule_code,'') AS rule,
				(SELECT ct1.text FROM cat_travels_status AS ct1 WHERE ct1.type = 4 AND ct1.status_ = a.transaction_status) AS comprobacion_txt,	
				(SELECT ct1.color FROM cat_travels_status AS ct1 WHERE ct1.type = 4 AND ct1.status_ = a.transaction_status) AS comprobacion_color,				
				(SELECT ct1.text FROM cat_travels_status AS ct1 WHERE ct1.type = 3 AND ct1.status_ = a.politics_status) AS estado_txt,
				(SELECT ct1.color FROM cat_travels_status AS ct1 WHERE ct1.type = 3 AND ct1.status_ = a.politics_status) AS estado_color
				/* CASE
					WHEN b.accounting_authorization = 1 THEN
						'EN ESPERA' 
					WHEN b.accounting_authorization = 2 THEN
						'EN REGLA'
					WHEN b.accounting_authorization = 3 THEN
						'ACEPTADO FELIPE'
					WHEN b.accounting_authorization = 4 THEN
						'RECHAZADO FELIPE'
					ELSE
						'NO COMPROBADO'
				END AS conta_txt,
				CASE 
					WHEN b.accounting_authorization = 1 THEN
						'warning' 
					WHEN b.accounting_authorization IN (2,3) THEN
						'success'
					WHEN b.accounting_authorization = 4 THEN
						'danger'
					ELSE
						'secondary'
				END AS conta_color */
			FROM tbl_services_account_status AS a
			LEFT JOIN  tbl_services_verification_items_travel_expenses AS b ON b.active_status = 1 AND b.id_account_status = a.id_account_status AND b.facture_type = 1
			WHERE a.active_status = 1 
				AND MD5(CONCAT('$key',a.id_request)) = '$idRequest'
				AND a.type = $type
		ORDER BY id_item DESC")->getResult();
		return ($data != null) ? json_encode($data) : json_encode(false);
	}

	function reportXlsxByRequestType()
	{
		$data = json_decode(stripslashes($this->request->getPost('data')));
		$request = $data->request;
		$type = $data->type;
		$nameType = ($type == 1) ? 'viaticos' : 'gastos';
		$cont = 2;
		$spreadsheet = new Spreadsheet();
		$NombreArchivo = "$nameType.xlsx";
		$tbl_field = ($type == 1) ? 'travel' : 'expenses';
		$columnTitle = 'A1:Z1';
		$sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
		$sheet->getStyle("$columnTitle")->getFont()->setBold(true)
			->setName('Calibri')
			->setSize(11)
			->getColor()
			->setRGB('FFFFFF');
		$sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
		$spreadsheet->getActiveSheet()->getStyle("A1:C1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');
		$spreadsheet->getActiveSheet()->getStyle("D1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ED7D31');
		$spreadsheet->getActiveSheet()->getStyle("E1:H1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');
		$spreadsheet->getActiveSheet()->getStyle("I1:W1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B45F06');
		$spreadsheet->getActiveSheet()->getStyle("X1:Y1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');

		$sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

		$reporteSql = $this->db->query("SELECT 
				a.user_name, 
				DATE_FORMAT(b.date_transaction,'%d/%m/%Y') AS charge_date,
				UPPER(b.location_transaction) AS supplier,
				b.amount_mxn,
				c.serie_and_folio,
				c.social_reason,
				c.rfc,
				c.ncr_number,
				c.caso_number,
				DATE_FORMAT(c.invoice_date,'%d/%m/%Y') AS verification_date,
				CONCAT('MXN') as money,
				CONCAT('MEXICO') as country,
				CONCAT('XA-0051') as in_prove,
				CONCAT('51') as id_bank,
				CONCAT('85') as diot,
				CONCAT('0') as no_cheke,
				c.total,
				CASE 
					WHEN c.id_to_check = 19 THEN
						CONCAT('AMEX VIAJES GW')
					WHEN (SELECT cst1.grado FROM tbl_users as cst1 WHERE cst1.id_user = a.id_user) = 1 THEN
						CONCAT('AMEX ',a.user_name)
					ELSE
						CONCAT('CLARA ',a.user_name)
				END as concep,
				CONCAT('FALSE') as manual,
				CONCAT(c.iva_percentage,' %') AS porcentaje,
				c.iva,
				c.retention,
				c.subtotal,
				a.clave_cost_center,
				CONCAT('TRUE') as tipe_pago,
				(SELECT ct1.category FROM cat_services_category AS ct1 WHERE ct1.id_category = c.id_to_check) AS tipo,
				c.observation, c.facture_type,
				(SELECT DISTINCT CONCAT('$',ct2.TipoCambio_TipoCambio) FROM cat_tipocambio AS ct2 WHERE ct2.active_status = 1 AND ct2.TipoCambio_Fecha = b.date_transaction) AS dollar
			FROM tbl_services_request_$tbl_field AS a
			JOIN tbl_services_account_status AS b ON a.id_request_$tbl_field = b.id_request AND b.type = $type AND b.active_status = 1 
			LEFT JOIN tbl_services_verification_items_travel_expenses AS c ON b.id_account_status = c.id_account_status AND c.active_status = 1 
			WHERE a.id_request_$tbl_field = $request
		")->getResult();

		// var_dump($reporteSql);
		$sheet->setTitle($reporteSql[0]->user_name);

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
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(35);
		$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);

		$sheet->setCellValue('A1', 'TITULAR');
		$sheet->setCellValue('B1', 'CHARGE DATA');
		$sheet->setCellValue('C1', 'SUPPLIER NAME');
		$sheet->setCellValue('D1', 'CHARGE AMOUNT');
		$sheet->setCellValue('E1', 'NO. DE FACTURA');
		$sheet->setCellValue('F1', 'PROVEEDOR');
		$sheet->setCellValue('G1', 'RFC');
		$sheet->setCellValue('H1', 'FECHA');
		$sheet->setCellValue('I1', 'MONEDA');
		$sheet->setCellValue('J1', 'PAIS');
		$sheet->setCellValue('K1', 'CUENTA PROVEEDOR');
		$sheet->setCellValue('L1', 'IDENTIFICADOR BANCARIO');
		$sheet->setCellValue('M1', 'TIPO TRAN DIOT');
		$sheet->setCellValue('N1', 'NO. DE CHEQUE');
		$sheet->setCellValue('O1', 'MONTO PAGO');
		$sheet->setCellValue('P1', 'CONCEPTO PAGO');
		$sheet->setCellValue('Q1', 'IMPUESTO MANUAL');
		$sheet->setCellValue('R1', 'IMPUESTO');
		$sheet->setCellValue('S1', 'MONTO IMPUESTO');
		$sheet->setCellValue('T1', 'MONTO RETENCION');
		$sheet->setCellValue('U1', 'SUBTOTAL FACTURA');
		$sheet->setCellValue('V1', 'CENTRO DE COSTOS');
		$sheet->setCellValue('W1', 'TIPO DE PAGO');
		$sheet->setCellValue('X1', 'TIPO DE GASTO');
		$sheet->setCellValue('Y1', 'JUSTIFICACION');
		$sheet->setCellValue('Z1', 'PRESIO USD');
		$sheet->setCellValue('AA1', 'CNR');
		$sheet->setCellValue('AB1', 'NUMERO DE CASO');

		foreach ($reporteSql as $value) {
			if ($value->facture_type == 1 || $value->serie_and_folio == null) {
				/* if ($cont > 2) {
						$spreadsheet->getActiveSheet()->getRowDimension("$cont")->setRowHeight(4); // alto de fila
						$spreadsheet->getActiveSheet()->getStyle("A$cont:Y$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5C636A');

						$cont++;
					} */
				$spreadsheet->getActiveSheet()->getStyle("A$cont:D$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9EAD3');
				$sheet->setCellValue('A' . $cont, $value->user_name);
				$sheet->setCellValue('B' . $cont, $value->charge_date);
				$sheet->setCellValue('C' . $cont, $value->supplier);
				$sheet->setCellValue('D' . $cont, $value->amount_mxn);
			} else {
				$sheet->setCellValue('A' . $cont, '');
				$sheet->setCellValue('B' . $cont, '');
				$sheet->setCellValue('C' . $cont, '');
				$sheet->setCellValue('D' . $cont, '');
			}
			if ($value->rfc != null) {
				$spreadsheet->getActiveSheet()->getStyle("E$cont:H$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('E' . $cont, $value->serie_and_folio);
				$sheet->setCellValue('F' . $cont, $value->social_reason);
				$sheet->setCellValue('G' . $cont, $value->rfc);
				$sheet->setCellValue('H' . $cont, $value->verification_date);

				$spreadsheet->getActiveSheet()->getStyle("I$cont:N$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('I' . $cont, $value->money);
				$sheet->setCellValue('J' . $cont, $value->country);
				$sheet->setCellValue('K' . $cont, $value->in_prove);
				$sheet->setCellValue('L' . $cont, $value->id_bank);
				$sheet->setCellValue('M' . $cont, $value->diot);
				$sheet->setCellValue('N' . $cont, $value->no_cheke);

				$spreadsheet->getActiveSheet()->getStyle("O$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('O' . $cont, $value->total);

				$spreadsheet->getActiveSheet()->getStyle("P$cont:Q$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('P' . $cont, $value->concep);
				$sheet->setCellValue('Q' . $cont, $value->manual);

				$spreadsheet->getActiveSheet()->getStyle("R$cont:V$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('R' . $cont, $value->porcentaje);
				$sheet->setCellValue('S' . $cont, $value->iva);
				$sheet->setCellValue('T' . $cont, $value->retention ?? '');
				$sheet->setCellValue('U' . $cont, $value->subtotal);
				$sheet->setCellValue('V' . $cont, $value->clave_cost_center);

				$spreadsheet->getActiveSheet()->getStyle("W$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('W' . $cont, $value->tipe_pago);

				$spreadsheet->getActiveSheet()->getStyle("X$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('X' . $cont, $value->tipo);

				$spreadsheet->getActiveSheet()->getStyle("Y$cont:AB$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FCE5CD');
				$sheet->setCellValue('Y' . $cont, $value->observation);
				$sheet->setCellValue('Z' . $cont, $value->dollar);
				$sheet->setCellValue('AA' . $cont, $value->ncr_number);
				$sheet->setCellValue('AB' . $cont, $value->caso_number);
			} else {
				$spreadsheet->getActiveSheet()->getStyle("E$cont:Z$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CC5555');
				$sheet->setCellValue('E' . $cont, 'MXN');
				$sheet->setCellValue('F' . $cont, 'DECUENTO');
				$sheet->setCellValue('G' . $cont, 'NO COMPROBADO');
			}
			$cont++;
		}
		// return;
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

	function reportXlsxByDateRequestActive()
	{
		$data = json_decode(stripslashes($this->request->getPost('data')));
		$type = ($data->type == 3) ? '' : "AND b.type = " . $data->type;
		$starDate = $data->star;
		$endDate = $data->end;
		$cont = 2;
		$spreadsheet = new Spreadsheet();
		$NombreArchivo = "VIATICOS_GASTOS.xlsx";
		$columnTitle = 'A1:AB1';
		$sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
		$sheet->getStyle("$columnTitle")->getFont()->setBold(true)
			->setName('Calibri')
			->setSize(11)
			->getColor()
			->setRGB('FFFFFF');
		$sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
		$spreadsheet->getActiveSheet()->getStyle("A1:C1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');
		$spreadsheet->getActiveSheet()->getStyle("D1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ED7D31');
		$spreadsheet->getActiveSheet()->getStyle("E1:H1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');
		$spreadsheet->getActiveSheet()->getStyle("I1:W1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B45F06');
		$spreadsheet->getActiveSheet()->getStyle("X1:Y1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');

		$sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

		$reporteSql = $this->db->query("SELECT 
				CONCAT(a.`name`,' ',a.surname,' ',a.second_surname) AS user_name, 
				DATE_FORMAT(b.date_transaction,'%d/%m/%Y') AS charge_date,
				UPPER(CONVERT(b.location_transaction, CHAR)) AS supplier,
				b.amount_mxn,
				c.serie_and_folio,
				c.social_reason,
				c.rfc,
				c.ncr_number,
				DATE_FORMAT(c.invoice_date,'%d/%m/%Y') AS verification_date,
				CONCAT('MXN') as money,
				CONCAT('MEXICO') as country,
				CONCAT('XA-0051') as in_prove,
				CONCAT('51') as id_bank,
				CONCAT('85') as diot,
				CONCAT('0') as no_cheke,
				c.total,
				CASE 
					WHEN c.id_to_check = 19 THEN
						CONCAT('AMEX VIAJES GW')
					WHEN (SELECT cst1.grado FROM tbl_users as cst1 WHERE cst1.id_user = a.id_user) = 1 THEN
						CONCAT('AMEX ',a.`name`,' ',a.surname,' ',a.second_surname)
					ELSE
						CONCAT('CLARA ',a.`name`,' ',a.surname,' ',a.second_surname)
				END as concep,
				CONCAT('FALSE') as manual,
				CONCAT(c.iva_percentage,' %') AS porcentaje,
				c.iva,
				c.retention,
				c.subtotal,
				(SELECT ct3.clave_cost_center FROM cat_cost_center AS ct3 WHERE ct3.id_cost_center = a.id_cost_center) AS clave_cost_center,
				CONCAT('TRUE') as tipe_pago,
				(SELECT ct1.category FROM cat_services_category AS ct1 WHERE ct1.id_category = c.id_to_check) AS tipo,
				c.observation, c.facture_type,
				(SELECT DISTINCT CONCAT('$',ct2.TipoCambio_TipoCambio) FROM cat_tipocambio AS ct2 WHERE ct2.active_status = 1 AND ct2.TipoCambio_Fecha = b.date_transaction) AS dollar,
				IF(b.type = 1, 'VIATICOS','GASTOS') As type_
			FROM tbl_services_account_status AS b
			JOIN tbl_users AS a ON b.id_user = a.id_user
			LEFT JOIN tbl_services_verification_items_travel_expenses AS c ON b.id_account_status = c.id_account_status AND c.active_status = 1 
			WHERE b.active_status = 1
			$type
			AND b.date_transaction BETWEEN '$starDate' AND '$endDate'
		")->getResult();

		/* echo "SELECT 
		CONCAT(a.`name`,' ',a.surname,' ',a.second_surname) AS user_name, 
		DATE_FORMAT(b.date_transaction,'%d/%m/%Y') AS charge_date,
		UPPER(CONVERT(b.location_transaction, CHAR)) AS supplier,
		b.amount_mxn,
		c.serie_and_folio,
		c.social_reason,
		c.rfc,
		DATE_FORMAT(c.invoice_date,'%d/%m/%Y') AS verification_date,
		CONCAT('MXN') as money,
		CONCAT('MEXICO') as country,
		CONCAT('XA-0051') as in_prove,
		CONCAT('51') as id_bank,
		CONCAT('85') as diot,
		CONCAT('0') as no_cheke,
		c.total,
		CASE 
			WHEN c.id_to_check = 19 THEN
				CONCAT('AMEX VIAJES GW')
			WHEN (SELECT cst1.grado FROM tbl_users as cst1 WHERE cst1.id_user = a.id_user) = 1 THEN
				CONCAT('AMEX ',a.`name`,' ',a.surname,' ',a.second_surname)
			ELSE
				CONCAT('CLARA ',a.`name`,' ',a.surname,' ',a.second_surname)
		END as concep,
		CONCAT('FALSE') as manual,
		CONCAT(c.iva_percentage,' %') AS porcentaje,
		c.iva,
		c.retention,
		c.subtotal,
		(SELECT ct3.clave_cost_center FROM cat_cost_center AS ct3 WHERE ct3.id_cost_center = a.id_cost_center) AS clave_cost_center,
		CONCAT('TRUE') as tipe_pago,
		(SELECT ct1.category FROM cat_services_category AS ct1 WHERE ct1.id_category = c.id_to_check) AS tipo,
		c.observation, c.facture_type,
		(SELECT DISTINCT CONCAT('$',ct2.TipoCambio_TipoCambio) FROM cat_tipocambio AS ct2 WHERE ct2.active_status = 1 AND ct2.TipoCambio_Fecha = b.date_transaction) AS dollar,
		IF(b.type = 1, 'VIATICOS','GASTOS') As type_
	FROM tbl_services_account_status AS b
	JOIN tbl_users AS a ON b.id_user = a.id_user
	LEFT JOIN tbl_services_verification_items_travel_expenses AS c ON b.id_account_status = c.id_account_status AND c.active_status = 1 
	WHERE b.active_status = 1
	$type
	AND b.date_transaction BETWEEN '$starDate' AND '$endDate'
";
		var_dump($reporteSql);
		return false; */
		$sheet->setTitle("$starDate a $endDate");

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
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(35);
		$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);

		$sheet->setCellValue('A1', 'TITULAR');
		$sheet->setCellValue('B1', 'CHARGE DATA');
		$sheet->setCellValue('C1', 'SUPPLIER NAME');
		$sheet->setCellValue('D1', 'CHARGE AMOUNT');
		$sheet->setCellValue('E1', 'NO. DE FACTURA');
		$sheet->setCellValue('F1', 'PROVEEDOR');
		$sheet->setCellValue('G1', 'RFC');
		$sheet->setCellValue('H1', 'FECHA');
		$sheet->setCellValue('I1', 'MONEDA');
		$sheet->setCellValue('J1', 'PAIS');
		$sheet->setCellValue('K1', 'CUENTA PROVEEDOR');
		$sheet->setCellValue('L1', 'IDENTIFICADOR BANCARIO');
		$sheet->setCellValue('M1', 'TIPO TRAN DIOT');
		$sheet->setCellValue('N1', 'NO. DE CHEQUE');
		$sheet->setCellValue('O1', 'MONTO PAGO');
		$sheet->setCellValue('P1', 'CONCEPTO PAGO');
		$sheet->setCellValue('Q1', 'IMPUESTO MANUAL');
		$sheet->setCellValue('R1', 'IMPUESTO');
		$sheet->setCellValue('S1', 'MONTO IMPUESTO');
		$sheet->setCellValue('T1', 'MONTO RETENCION');
		$sheet->setCellValue('U1', 'SUBTOTAL FACTURA');
		$sheet->setCellValue('V1', 'CENTRO DE COSTOS');
		$sheet->setCellValue('W1', 'TIPO DE PAGO');
		$sheet->setCellValue('X1', 'TIPO DE GASTO');
		$sheet->setCellValue('Y1', 'JUSTIFICACION');
		$sheet->setCellValue('Z1', 'PRESIO USD');
		$sheet->setCellValue('AA1', 'TIPO');
		$sheet->setCellValue('AB1', 'NCR');

		foreach ($reporteSql as $value) {
			if ($value->facture_type == 1 || $value->serie_and_folio == null) {
				/* if ($cont > 2) {
						$spreadsheet->getActiveSheet()->getRowDimension("$cont")->setRowHeight(4); // alto de fila
						$spreadsheet->getActiveSheet()->getStyle("A$cont:Y$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5C636A');

						$cont++;
					} */
				$spreadsheet->getActiveSheet()->getStyle("A$cont:D$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9EAD3');
				$sheet->setCellValue('A' . $cont, $value->user_name);
				$sheet->setCellValue('B' . $cont, $value->charge_date);
				$sheet->setCellValue('C' . $cont, $value->supplier);
				$sheet->setCellValue('D' . $cont, $value->amount_mxn);
			} else {
				$sheet->setCellValue('A' . $cont, '');
				$sheet->setCellValue('B' . $cont, '');
				$sheet->setCellValue('C' . $cont, '');
				$sheet->setCellValue('D' . $cont, '');
			}
			if ($value->rfc != null) {
				$spreadsheet->getActiveSheet()->getStyle("E$cont:H$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('E' . $cont, $value->serie_and_folio);
				$sheet->setCellValue('F' . $cont, $value->social_reason);
				$sheet->setCellValue('G' . $cont, $value->rfc);
				$sheet->setCellValue('H' . $cont, $value->verification_date);

				$spreadsheet->getActiveSheet()->getStyle("I$cont:N$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('I' . $cont, $value->money);
				$sheet->setCellValue('J' . $cont, $value->country);
				$sheet->setCellValue('K' . $cont, $value->in_prove);
				$sheet->setCellValue('L' . $cont, $value->id_bank);
				$sheet->setCellValue('M' . $cont, $value->diot);
				$sheet->setCellValue('N' . $cont, $value->no_cheke);

				$spreadsheet->getActiveSheet()->getStyle("O$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('O' . $cont, $value->total);

				$spreadsheet->getActiveSheet()->getStyle("P$cont:Q$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('P' . $cont, $value->concep);
				$sheet->setCellValue('Q' . $cont, $value->manual);

				$spreadsheet->getActiveSheet()->getStyle("R$cont:V$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('R' . $cont, $value->porcentaje);
				$sheet->setCellValue('S' . $cont, $value->iva);
				$sheet->setCellValue('T' . $cont, $value->retention ?? '');
				$sheet->setCellValue('U' . $cont, $value->subtotal);
				$sheet->setCellValue('V' . $cont, $value->clave_cost_center);

				$spreadsheet->getActiveSheet()->getStyle("W$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('W' . $cont, $value->tipe_pago);

				$spreadsheet->getActiveSheet()->getStyle("X$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('X' . $cont, $value->tipo);

				$spreadsheet->getActiveSheet()->getStyle("Y$cont:AA$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FCE5CD');
				$sheet->setCellValue('Y' . $cont, $value->observation);
				$sheet->setCellValue('Z' . $cont, $value->dollar);
				$sheet->setCellValue('AA' . $cont, $value->type_);
				$sheet->setCellValue('AB' . $cont, $value->ncr_number);
			} else {
				$spreadsheet->getActiveSheet()->getStyle("E$cont:AA$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CC5555');
				$sheet->setCellValue('E' . $cont, 'MXN');
				$sheet->setCellValue('F' . $cont, 'DECUENTO');
				$sheet->setCellValue('G' . $cont, 'NO COMPROBADO');
			}
			$cont++;
		}
		// return;
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

	function reportXlsxByUserRequestActive()
	{
		$data = json_decode(stripslashes($this->request->getPost('data')));
		$idUser = $data->id_user;
		$cont = 2;
		$spreadsheet = new Spreadsheet();
		$NombreArchivo = "VIATICOS_GASTOS.xlsx";
		$columnTitle = 'A1:AB1';
		$sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
		$sheet->getStyle("$columnTitle")->getFont()->setBold(true)
			->setName('Calibri')
			->setSize(11)
			->getColor()
			->setRGB('FFFFFF');
		$sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
		$spreadsheet->getActiveSheet()->getStyle("A1:C1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');
		$spreadsheet->getActiveSheet()->getStyle("D1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ED7D31');
		$spreadsheet->getActiveSheet()->getStyle("E1:H1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');
		$spreadsheet->getActiveSheet()->getStyle("I1:W1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B45F06');
		$spreadsheet->getActiveSheet()->getStyle("X1:Y1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('980000');

		$sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

		$reporteSql = $this->db->query("SELECT 
				CONCAT(a.`name`,' ',a.surname,' ',a.second_surname) AS user_name, 
				DATE_FORMAT(b.date_transaction,'%d/%m/%Y') AS charge_date,
				UPPER(CONVERT(b.location_transaction, CHAR)) AS supplier,
				b.amount_mxn,
				c.serie_and_folio,
				c.social_reason,
				c.rfc,
				c.ncr_number,
				DATE_FORMAT(c.invoice_date,'%d/%m/%Y') AS verification_date,
				CONCAT('MXN') as money,
				CONCAT('MEXICO') as country,
				CONCAT('XA-0051') as in_prove,
				CONCAT('51') as id_bank,
				CONCAT('85') as diot,
				CONCAT('0') as no_cheke,
				c.total,
				CASE 
					WHEN c.id_to_check = 19 THEN
						CONCAT('AMEX VIAJES GW')
					WHEN (SELECT cst1.grado FROM tbl_users as cst1 WHERE cst1.id_user = a.id_user) = 1 THEN
						CONCAT('AMEX ',a.`name`,' ',a.surname,' ',a.second_surname)
					ELSE
						CONCAT('CLARA ',a.`name`,' ',a.surname,' ',a.second_surname)
				END as concep,
				CONCAT('FALSE') as manual,
				CONCAT(c.iva_percentage,' %') AS porcentaje,
				c.iva,
				c.retention,
				c.subtotal,
				(SELECT ct3.clave_cost_center FROM cat_cost_center AS ct3 WHERE ct3.id_cost_center = a.id_cost_center) AS clave_cost_center,
				CONCAT('TRUE') as tipe_pago,
				(SELECT ct1.category FROM cat_services_category AS ct1 WHERE ct1.id_category = c.id_to_check) AS tipo,
				c.observation, c.facture_type,
				(SELECT DISTINCT CONCAT('$',ct2.TipoCambio_TipoCambio) FROM cat_tipocambio AS ct2 WHERE ct2.active_status = 1 AND ct2.TipoCambio_Fecha = b.date_transaction) AS dollar,
				IF(b.type = 1, 'VIATICOS','GASTOS') As type_
			FROM tbl_services_account_status AS b
			JOIN tbl_users AS a ON b.id_user = a.id_user
			LEFT JOIN tbl_services_verification_items_travel_expenses AS c ON b.id_account_status = c.id_account_status AND c.active_status = 1 
			WHERE b.active_status = 1
			AND b.id_user = $idUser
		")->getResult();

		// var_dump($reporteSql);
		$sheet->setTitle("VIATICOS Y GASTOS");

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
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('Y')->setWidth(35);
		$spreadsheet->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);

		$sheet->setCellValue('A1', 'TITULAR');
		$sheet->setCellValue('B1', 'CHARGE DATA');
		$sheet->setCellValue('C1', 'SUPPLIER NAME');
		$sheet->setCellValue('D1', 'CHARGE AMOUNT');
		$sheet->setCellValue('E1', 'NO. DE FACTURA');
		$sheet->setCellValue('F1', 'PROVEEDOR');
		$sheet->setCellValue('G1', 'RFC');
		$sheet->setCellValue('H1', 'FECHA');
		$sheet->setCellValue('I1', 'MONEDA');
		$sheet->setCellValue('J1', 'PAIS');
		$sheet->setCellValue('K1', 'CUENTA PROVEEDOR');
		$sheet->setCellValue('L1', 'IDENTIFICADOR BANCARIO');
		$sheet->setCellValue('M1', 'TIPO TRAN DIOT');
		$sheet->setCellValue('N1', 'NO. DE CHEQUE');
		$sheet->setCellValue('O1', 'MONTO PAGO');
		$sheet->setCellValue('P1', 'CONCEPTO PAGO');
		$sheet->setCellValue('Q1', 'IMPUESTO MANUAL');
		$sheet->setCellValue('R1', 'IMPUESTO');
		$sheet->setCellValue('S1', 'MONTO IMPUESTO');
		$sheet->setCellValue('T1', 'MONTO RETENCION');
		$sheet->setCellValue('U1', 'SUBTOTAL FACTURA');
		$sheet->setCellValue('V1', 'CENTRO DE COSTOS');
		$sheet->setCellValue('W1', 'TIPO DE PAGO');
		$sheet->setCellValue('X1', 'TIPO DE GASTO');
		$sheet->setCellValue('Y1', 'JUSTIFICACION');
		$sheet->setCellValue('Z1', 'PRESIO USD');
		$sheet->setCellValue('AA1', 'TIPO');
		$sheet->setCellValue('AB1', 'NCR');

		foreach ($reporteSql as $value) {
			if ($value->facture_type == 1 || $value->serie_and_folio == null) {
				/* if ($cont > 2) {
						$spreadsheet->getActiveSheet()->getRowDimension("$cont")->setRowHeight(4); // alto de fila
						$spreadsheet->getActiveSheet()->getStyle("A$cont:Y$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5C636A');

						$cont++;
					} */
				$spreadsheet->getActiveSheet()->getStyle("A$cont:D$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9EAD3');
				$sheet->setCellValue('A' . $cont, $value->user_name);
				$sheet->setCellValue('B' . $cont, $value->charge_date);
				$sheet->setCellValue('C' . $cont, $value->supplier);
				$sheet->setCellValue('D' . $cont, $value->amount_mxn);
			} else {
				$sheet->setCellValue('A' . $cont, '');
				$sheet->setCellValue('B' . $cont, '');
				$sheet->setCellValue('C' . $cont, '');
				$sheet->setCellValue('D' . $cont, '');
			}
			if ($value->rfc != null) {
				$spreadsheet->getActiveSheet()->getStyle("E$cont:H$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('E' . $cont, $value->serie_and_folio);
				$sheet->setCellValue('F' . $cont, $value->social_reason);
				$sheet->setCellValue('G' . $cont, $value->rfc);
				$sheet->setCellValue('H' . $cont, $value->verification_date);

				$spreadsheet->getActiveSheet()->getStyle("I$cont:N$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('I' . $cont, $value->money);
				$sheet->setCellValue('J' . $cont, $value->country);
				$sheet->setCellValue('K' . $cont, $value->in_prove);
				$sheet->setCellValue('L' . $cont, $value->id_bank);
				$sheet->setCellValue('M' . $cont, $value->diot);
				$sheet->setCellValue('N' . $cont, $value->no_cheke);

				$spreadsheet->getActiveSheet()->getStyle("O$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('O' . $cont, $value->total);

				$spreadsheet->getActiveSheet()->getStyle("P$cont:Q$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('P' . $cont, $value->concep);
				$sheet->setCellValue('Q' . $cont, $value->manual);

				$spreadsheet->getActiveSheet()->getStyle("R$cont:V$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('R' . $cont, $value->porcentaje);
				$sheet->setCellValue('S' . $cont, $value->iva);
				$sheet->setCellValue('T' . $cont, $value->retention ?? '');
				$sheet->setCellValue('U' . $cont, $value->subtotal);
				$sheet->setCellValue('V' . $cont, $value->clave_cost_center);

				$spreadsheet->getActiveSheet()->getStyle("W$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C9DAF8');
				$sheet->setCellValue('W' . $cont, $value->tipe_pago);

				$spreadsheet->getActiveSheet()->getStyle("X$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2CC');
				$sheet->setCellValue('X' . $cont, $value->tipo);

				$spreadsheet->getActiveSheet()->getStyle("Y$cont:AA$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FCE5CD');
				$sheet->setCellValue('Y' . $cont, $value->observation);
				$sheet->setCellValue('Z' . $cont, $value->dollar);
				$sheet->setCellValue('AA' . $cont, $value->type_);
				$sheet->setCellValue('AB' . $cont, $value->ncr_number);
			} else {
				$spreadsheet->getActiveSheet()->getStyle("E$cont:AA$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CC5555');
				$sheet->setCellValue('E' . $cont, 'MXN');
				$sheet->setCellValue('F' . $cont, 'DECUENTO');
				$sheet->setCellValue('G' . $cont, 'NO COMPROBADO');
			}
			$cont++;
		}
		// return;
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

	function deletVerificationAccountStatus()
	{
		$idItem = $this->request->getPost("id_item");
		if (!session()->is_logged) {
			return redirect()->to(site_url());
		}
		try {
			$query = $this->db->query("SELECT transaction_status AS estado, `type`, id_request
				FROM tbl_services_account_status 
				WHERE active_status = 1 
				AND id_account_status = $idItem
			")->getRow();
			if ($query->estado == 2) {
				$this->db->transStart();

				$idUser = session()->id_user;
				$toDay = date("Y-m-d H:i:s");

				$this->db->query("UPDATE tbl_services_verification_items_travel_expenses 
				SET active_status = 2, id_cancel = $idUser, cancel_at = '$toDay'
				WHERE id_account_status = $idItem");

				$this->db->query("UPDATE tbl_services_account_status 
				SET transaction_status = 1
				WHERE id_account_status = $idItem");
				$tbl_field = ($query->type == 1) ? "travel" : "expenses";
				$this->db->query("UPDATE tbl_services_request_$tbl_field AS a
					SET a.verification_money = a.verification_money - ( SELECT b.amount_mxn 
						FROM tbl_services_account_status AS b 
						WHERE b.active_status = 1 
						AND b.id_account_status = $idItem)
					WHERE a.id_request_$tbl_field = $query->id_request
				");

				$result = $this->db->transComplete();
				return ($result) ? json_encode(true) : json_encode(false);
			} else {
				return json_encode(false);
			}
		} catch (\Exception $e) {
			return json_encode('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	function updateRequestAndStatusAcount($sqlTblField, $idRequest, $type)
	{
		// Nonta aqui -- compartir esta funcione en la subida, eliminacion y cancelacion de comprobante

		try {
			$this->db->query("UPDATE tbl_services_request_$sqlTblField
				SET card_confirm_money = (SELECT SUM(a.amount) 
					FROM tbl_services_account_status AS a 
					WHERE a.active_status = 1 
					AND a.id_request = $idRequest AND a.type = $type) 
				WHERE active_status = 1 
				AND id_request_$sqlTblField = $idRequest
			");

			$this->db->query("UPDATE tbl_services_request_$sqlTblField
					SET verification_status = IF (
						ABS( card_confirm_money - verification_money ) <= 10,
					3, 2 ) 
				WHERE active_status = 1 
				AND id_request_$sqlTblField = $idRequest
			");


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
					WHEN date_transaction >= LAST_DAY(date_transaction) - INTERVAL 5 DAY AND CURDATE() = LAST_DAY(date_transaction)
						THEN 2 -- 'Ultimo día'
					WHEN date_transaction >= LAST_DAY(date_transaction) - INTERVAL 5 DAY AND CURDATE() > LAST_DAY(date_transaction)
						THEN 3 -- 'Fuera de tiempo' 
					WHEN DATEDIFF(date_transaction, CURDATE()) > -6
						THEN 1 -- 'A TIEMPO'
					WHEN DATEDIFF(date_transaction, CURDATE()) = -6 
						THEN 2 -- 'ULTIMO DIA'
					WHEN DATEDIFF(date_transaction, CURDATE()) < -6 
						THEN 3 -- 'FUERA DE TIEMPO'
				END 
				WHERE active_status = 1
					AND transaction_status = 1
				AND politics_status < 3
			");
		} catch (\Exception $e) {
			return false;
		}
	}

	function notifyStatusAcounts()
	{
		$userTravels = $this->db->query("SELECT a.id_request_travel, a.user_name, b.email
				FROM tbl_services_request_travel AS a 
				JOIN tbl_users AS b on a.id_user = b.id_user
				WHERE a.active_status = 1
				AND a.request_status = 2
				AND CURDATE() BETWEEN a.day_star_travel AND DATE_ADD(a.day_end_travel, INTERVAL 5 DAY)
			AND a.verification_status IN (1,2)")->getResult();

		// var_dump($userTravels);
		if ($userTravels) {
			foreach ($userTravels as $value) {
				$this->notifyActiveAccountState($value->id_request_travel, 1, $value->email, $value->user_name);
			}
		}

		$userExpenses = $this->db->query("SELECT a.id_request_expenses, a.user_name, b.email
				FROM tbl_services_request_expenses AS a 
				JOIN tbl_users AS b on a.id_user = b.id_user
				WHERE a.active_status = 1
				AND a.request_status = 2
				AND CURDATE() BETWEEN a.day_star_expenses AND DATE_ADD(a.day_end_expenses, INTERVAL 5 DAY)
			AND a.verification_status IN (1,2)")->getResult();

		// var_dump($userExpenses);
		if ($userExpenses) {
			foreach ($userExpenses as $value) {
				$this->notifyActiveAccountState($value->id_request_expenses, 2, $value->email, $value->user_name);
			}
		}
	}

	function notifyActiveAccountState($idRequest = null, $typeTraExt = null, $emailUser = null, $nameUser = null)
	{
		try {
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
			/* $mail->Username = 'requisiciones@grupowalworth.com';
			$mail->Password = '2contodo'; */
			$mail->Port = 25;

			$emailUser = "rcruz@walworth.com.mx";

			$typeTxt = ($typeTraExt == 1) ? 'Viaticos' : 'Gastos';
			$mail->setFrom('requisiciones@walworth.com', "Comprobación de $typeTxt pendientes | PRUEBAS");
			$mail->addAddress($emailUser, $nameUser);
			$mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
			$mail->addBCC('rcruz@walworth.com.mx');
			// $mail->addBCC('hrivas@walworth.com.mx');
			//$mail->addBCC('ahuerta@walworth.com.mx');
			//$mail->addBCC('bpedraza@walworth.com.mx', 'Blanca Estela Pedraza');
			$mail->addBCC('dprado@walworth.com.mx', 'David Prado');
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

	public function notifyExpensesAndTravel($email, $user, $data, $tipo)
	{
		try {
			$dir_email = changeEmail($email);
			$id_user = session()->id_user;
			if ($id_user == 302 || $id_user == 245) {
				$dir_email = 'isantana@walworth.com.mx';
			}


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
			$title = ($tipo == 1) ? 'Viáticos Comprobados' : 'Gastos Comprobados';
			$mail->setFrom('notificacion@walworth.com', $title);

			// Add a recipient
			$mail->addAddress($dir_email, $user);
			// $mail->addCC('ahuerta@walworth.com.mx', "ADRIAN ALEJANDRO HUERTA CALDERON");
			
			$mail->addCC('dprado@walworth.com.mx', 'David Prado');
			if ($id_user == 343) {

				$mail->addCC('isantana@walworth.com.mx');
			}

			// Name is optional
			$mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
			$mail->addBCC('rcruz@walworth.com.mx');
			



			//Content

			$mail->isHTML(true);
			$template = ($tipo === 1) ? 'notificaciones/notification_expenses_checked' : 'notificaciones/notification_travel_checked';
			$email_template = view($template, $data);
			$mail->MsgHTML($email_template);                              // Set email format to HTML
			$mail->Subject =  'Notificacion Sistemas';
			$mail->send();
			return true;
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}

	public function updateAccounts()
	{
		$this->db->transStart();

		//TABLA DE GASTOS
		$this->db->query("UPDATE tbl_services_request_expenses AS a SET 
-- volver a Sumar los motontos totales de Comprobaciones de Gastos
a.verification_money = COALESCE((SELECT SUM(total) FROM tbl_services_verification_items_travel_expenses WHERE active_status = 1 AND expense_type = 2 AND id_request = a.id_request_expenses),0.00),
-- volver a Sumar los motontos totales en MXN de Estados de Cuenta de Gastos
a.card_confirm_money = COALESCE((SELECT SUM(amount_mxn) FROM tbl_services_account_status WHERE active_status = 1 AND type = 2 AND id_request = a.id_request_expenses),0.00)
WHERE active_status = 1;");

		//TABLA DE VIATICOS
		$this->db->query("UPDATE tbl_services_request_travel AS a SET 
-- volver a Sumar los motontos totales de Comprobaciones de Viaticos 
a.verification_money = COALESCE((SELECT SUM(total) FROM tbl_services_verification_items_travel_expenses WHERE active_status = 1 AND expense_type = 1 AND id_request = a.id_request_travel),0.00),
-- volver a Sumar los motontos totales en MXN de Estados de Cuenta de Viaticos
a.card_confirm_money = COALESCE((SELECT SUM(amount_mxn) FROM tbl_services_account_status WHERE active_status = 1 AND type = 1 AND id_request = a.id_request_travel),0.00)
WHERE active_status = 1;");

		// volver a verificar si se ah comprobado todo o esta pendiente 

		$this->db->query("UPDATE tbl_services_request_travel SET verification_status = IF(verification_money = 0, 1,IF(ABS(verification_money - card_confirm_money) <= 10,3,2));");

		$this->db->query("UPDATE tbl_services_request_expenses SET verification_status = IF(verification_money = 0, 1,IF(ABS(verification_money - card_confirm_money) <= 10,3,2));");


		$result = $this->db->transComplete();

		return ($result) ? json_encode(true) : json_encode(false);
	}

	function typeReport()
	{
		$data = json_decode(stripslashes($this->request->getPost('data')));
		if ($data->type == 1) {
			$this->travelComparativeExcel($data);
		} elseif ($data->type == 2) {
			$this->expensesComparativeExcel($data);
		} elseif ($data->type == 3) {
			$this->allComparativeExcel($data);
		}
	}

	function expensesComparativeExcel($data)
	{

		$starDate = $data->star;
		$endDate = $data->end;
		$cont = 2;
		$spreadsheet = new Spreadsheet();
		$NombreArchivo = "GASTOS.xlsx";
		$columnTitle = 'A1:K1';
		$sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
		$sheet->getStyle("$columnTitle")->getFont()->setBold(true)
			->setName('Calibri')
			->setSize(11)
			->getColor()
			->setRGB('FFFFFF');
		$sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00964E');


		$sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

		$dataExpenses = $this->db->query(
			"SELECT  id_request_expenses, user_name, payroll_number, day_star_expenses, day_end_expenses, obs,
					 total_money, divisa_money, card_confirm_money, verification_money, verification_status
			 FROM tbl_services_request_expenses
			 WHERE day_star_expenses 
			 BETWEEN '$starDate' AND '$endDate' AND verification_status > 1;"
		)->getResult();


		$sheet->setTitle("$starDate a $endDate");

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);



		$sheet->setCellValue('A1', 'FOLIO');
		$sheet->setCellValue('B1', 'USUARIO');
		$sheet->setCellValue('C1', 'FECHA INICIO');
		$sheet->setCellValue('D1', 'FECHA FINAL');
		$sheet->setCellValue('E1', 'OBSERVACION');
		$sheet->setCellValue('F1', 'TOTAL');
		$sheet->setCellValue('G1', 'DIVISA');
		$sheet->setCellValue('H1', 'OCUPADO');
		$sheet->setCellValue('I1', 'COMPROBADO');
		$sheet->setCellValue('J1', 'ESTATUS');
		$sheet->setCellValue('K1', 'TIPO');


		foreach ($dataExpenses as $value) {

			$spreadsheet->getActiveSheet()->getStyle("A$cont:K$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9EAD3');
			$sheet->setCellValue('A' . $cont, $value->id_request_expenses);
			$sheet->setCellValue('B' . $cont, $value->user_name);
			$sheet->setCellValue('C' . $cont, $value->day_star_expenses);
			$sheet->setCellValue('D' . $cont, $value->day_end_expenses);
			$sheet->setCellValue('E' . $cont, $value->obs);
			// Establecer el valor numérico en la celda
			$sheet->setCellValue('F' . $cont, $value->total_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('F' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);
			$sheet->setCellValue('G' . $cont, $value->divisa_money);

			// Establecer el valor numérico en la celda
			$sheet->setCellValue('H' . $cont, $value->card_confirm_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('H' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);

			$sheet->setCellValue('I' . $cont, $value->verification_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('I' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);
			$status = ($value->verification_status == 2) ? 'Parcial' : 'Completa';
			$sheet->setCellValue('J' . $cont, $status);
			$sheet->setCellValue('K' . $cont, 'GASTO');

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
	function travelComparativeExcel($data)
	{

		$starDate = $data->star;
		$endDate = $data->end;
		$cont = 2;
		$spreadsheet = new Spreadsheet();
		$NombreArchivo = "VIATICOS.xlsx";
		$columnTitle = 'A1:K1';
		$sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
		$sheet->getStyle("$columnTitle")->getFont()->setBold(true)
			->setName('Calibri')
			->setSize(11)
			->getColor()
			->setRGB('FFFFFF');
		$sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00964E');


		$sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

		$dataExpenses = $this->db->query(
			"SELECT id_request_travel, user_name, day_star_travel, day_end_travel, obs, total_money, divisa_money, 
					card_confirm_money, verification_money, verification_status
				FROM tbl_services_request_travel 
				WHERE day_star_travel 
				BETWEEN '$starDate' AND '$endDate' AND verification_status > 1;"
		)->getResult();


		$sheet->setTitle("$starDate a $endDate");

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);



		$sheet->setCellValue('A1', 'FOLIO');
		$sheet->setCellValue('B1', 'USUARIO');
		$sheet->setCellValue('C1', 'FECHA INICIO');
		$sheet->setCellValue('D1', 'FECHA FINAL');
		$sheet->setCellValue('E1', 'OBSERVACION');
		$sheet->setCellValue('F1', 'TOTAL');
		$sheet->setCellValue('G1', 'DIVISA');
		$sheet->setCellValue('H1', 'OCUPADO');
		$sheet->setCellValue('I1', 'COMPROBADO');
		$sheet->setCellValue('J1', 'ESTATUS');
		$sheet->setCellValue('K1', 'TIPO');


		foreach ($dataExpenses as $value) {

			$spreadsheet->getActiveSheet()->getStyle("A$cont:K$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9EAD3');
			$sheet->setCellValue('A' . $cont, $value->id_request_travel);
			$sheet->setCellValue('B' . $cont, $value->user_name);
			$sheet->setCellValue('C' . $cont, $value->day_star_travel);
			$sheet->setCellValue('D' . $cont, $value->day_end_travel);
			$sheet->setCellValue('E' . $cont, $value->obs);
			// Establecer el valor numérico en la celda
			$sheet->setCellValue('F' . $cont, $value->total_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('F' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);
			$sheet->setCellValue('G' . $cont, $value->divisa_money);

			// Establecer el valor numérico en la celda
			$sheet->setCellValue('H' . $cont, $value->card_confirm_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('H' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);

			$sheet->setCellValue('I' . $cont, $value->verification_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('I' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);
			$status = ($value->verification_status == 2) ? 'Parcial' : 'Completa';
			$sheet->setCellValue('J' . $cont, $status);
			$sheet->setCellValue('K' . $cont, 'VIATICO');

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
	function allComparativeExcel($data)
	{

		$starDate = $data->star;
		$endDate = $data->end;
		$cont = 2;
		$spreadsheet = new Spreadsheet();
		$NombreArchivo = "VIATICOS_GASTOS.xlsx";
		$columnTitle = 'A1:K1';
		$sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
		$sheet->getStyle("$columnTitle")->getFont()->setBold(true)
			->setName('Calibri')
			->setSize(11)
			->getColor()
			->setRGB('FFFFFF');
		$sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
		$spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00964E');


		$sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

		$dataExpenses = $this->db->query(
			"SELECT 
			id_request_travel as id_request, 
			user_name, 
			day_star_travel as day_start, 
			day_end_travel as day_end, 
			obs, 
			total_money, 
			divisa_money, 
			card_confirm_money, 
			verification_money, 
			verification_status,
			'VIATICO' AS definido_status
		FROM 
			tbl_services_request_travel 
		WHERE 
			day_star_travel BETWEEN  '$starDate' AND '$endDate' 
			AND verification_status > 1 
		
		UNION ALL
		
		SELECT 
			id_request_expenses as id_request, 
			user_name, 
			day_star_expenses as day_start, 
			day_end_expenses as day_end, 
			obs, 
			total_money, 
			divisa_money, 
			card_confirm_money, 
			verification_money, 
			verification_status,
			'GASTO' AS definido_status
		FROM 
			tbl_services_request_expenses
		WHERE 
			day_star_expenses BETWEEN  '$starDate' AND '$endDate'
			AND verification_status > 1;"
		)->getResult();


		$sheet->setTitle("$starDate a $endDate");

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);



		$sheet->setCellValue('A1', 'FOLIO');
		$sheet->setCellValue('B1', 'USUARIO');
		$sheet->setCellValue('C1', 'FECHA INICIO');
		$sheet->setCellValue('D1', 'FECHA FINAL');
		$sheet->setCellValue('E1', 'OBSERVACION');
		$sheet->setCellValue('F1', 'TOTAL');
		$sheet->setCellValue('G1', 'DIVISA');
		$sheet->setCellValue('H1', 'OCUPADO');
		$sheet->setCellValue('I1', 'COMPROBADO');
		$sheet->setCellValue('J1', 'ESTATUS');
		$sheet->setCellValue('K1', 'TIPO');

		foreach ($dataExpenses as $value) {

			$spreadsheet->getActiveSheet()->getStyle("A$cont:K$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9EAD3');
			$sheet->setCellValue('A' . $cont, $value->id_request);
			$sheet->setCellValue('B' . $cont, $value->user_name);
			$sheet->setCellValue('C' . $cont, $value->day_start);
			$sheet->setCellValue('D' . $cont, $value->day_end);
			$sheet->setCellValue('E' . $cont, $value->obs);
			// Establecer el valor numérico en la celda
			$sheet->setCellValue('F' . $cont, $value->total_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('F' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);
			$sheet->setCellValue('G' . $cont, $value->divisa_money);

			// Establecer el valor numérico en la celda
			$sheet->setCellValue('H' . $cont, $value->card_confirm_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('H' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);

			$sheet->setCellValue('I' . $cont, $value->verification_money);

			// Obtener la celda para aplicar el formato
			$cell = $sheet->getCell('I' . $cont);

			// Aplicar el formato de número deseado
			$style = [
				'numberFormat' => [
					'formatCode' => '"$"#,##0.00', // Formato con separadores de coma y símbolo de pesos
				],
			];
			$cell->getStyle()->applyFromArray($style);
			$status = ($value->verification_status == 2) ? 'Parcial' : 'Completa';
			$sheet->setCellValue('J' . $cont, $status);
			$sheet->setCellValue('K' . $cont, $value->definido_status);

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


	public function pdfRequestTravelExpenses($id_request = null)
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

		$dataViaticos = $this->db->query("SELECT a.id_request_travel AS folio, a.user_name, a.payroll_number As nomina,  a.request_status,	c.departament,	
					CONCAT('$ ',FORMAT(a.total_money, 2),' ',a.divisa_money) AS total, CONCAT('false') AS access_direct,
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS creacion, a.verification_status,
					UPPER(a.location_travel_end) AS destino,
					CONCAT(DATE_FORMAT(a.day_star_travel,'%d/%m/%Y'),' -- ',DATE_FORMAT(a.day_end_travel,'%d/%m/%Y')) AS fechas,
					CASE 
						WHEN type_travel = 1 THEN
							'NACIONAL'
						ELSE
							CONCAT('INTERNACIONAL  |  ',(SELECT st1.country FROM cat_travels_country AS st1 WHERE st1.id_country = a.id_country))
					END AS tipo_viaje, a.type_travel,
					(SELECT CONCAT(st2.roman_num,'  ',st2.level_name) FROM cat_level_grade AS st2 WHERE st2.id_level = a.id_grade_level) AS grado,
					IF(a.need_plane = 1,'SI','NO') AS avion,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt
				FROM tbl_services_request_travel AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
					JOIN cat_departament AS c ON b.id_departament = c.id_depto
				WHERE a.active_status = 1 AND
				MD5(concat('" . $key . "',id_request_travel))='" . $id_request . "'")->getRow();







		$data = ["request" => $dataViaticos];

		$html2 = view('pdf/pdf_viaticos', $data);
		$html = ob_get_clean();
		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
		$html2pdf->pdf->SetTitle('Viaticos');
		$html2pdf->writeHTML($html2);
		ob_end_clean();
		$html2pdf->output('permiso_' . $id_request . '.pdf', 'I');
	}
}
