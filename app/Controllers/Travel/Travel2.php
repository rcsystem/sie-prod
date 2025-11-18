<?php

/**
 * GENERADOR DE VIATICOS Y GASTOS
 * @version 1.1 pre-prod
 * @author Rafael Cruz Aguilar <rafael.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 * 2° @author Horus Samael Rivas Pedraza <horus.riv.ped@gmail.com>
 * @telefono 56-2439-2632
 * Archivo Generador de Repore
 */

namespace App\Controllers\Travel;

use DateTime;
use App\Controllers\BaseController;
use App\Models\travelsRequestModel;
use App\Models\userModel;
use App\Models\travelItemsModel;
use App\Models\servicesTravelModel;
use App\Models\servicesExpensModel;
use App\Models\servicesXmlModel;
use App\Models\servicesAccountModel;
use App\Models\servicesAccountModelCaseSpecial;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use PhpParser\Node\Stmt\TryCatch;

class Travel2 extends BaseController
{
	public function __construct()
	{
		require_once APPPATH . '/Libraries/vendor/autoload.php';
		$this->userModel = new userModel();
		$this->travelsModel = new travelsRequestModel();
		$this->itemModel = new travelItemsModel();
		$this->xmlModel = new servicesXmlModel();
		$this->servicesTravelModel = new servicesTravelModel();
		$this->expensesModel = new servicesExpensModel();
		$this->servicesAccount = new servicesAccountModel();
		$this->servicesAccountSpecial = new servicesAccountModelCaseSpecial;

		$this->db = \Config\Database::connect();
		$this->is_logged = session()->is_logged ? true : false;
		if (!$this->is_logged) {
			return redirect()->to(site_url());
		}
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

	public function viewAccountAuthorization()
	{
		return ($this->is_logged) ? view('travels/view_accounting_authorization') : redirect()->to(site_url());
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
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$tblField = ($type == 1) ? "travel" : "expenses";
		$validacion = $this->db->query("SELECT request_status FROM tbl_services_request_$tblField WHERE active_status = 1 AND MD5(concat('$key',id_request_$tblField)) = '$folio'")->getRow()->request_status;
		if ($validacion != 2) {
			return ($this->is_logged) ? redirect()->to(site_url() . "viajes/mis-solicitudes") : redirect()->to(site_url());
		}
		$data = ["type" => $type, "folio" => $folio];
		return ($this->is_logged) ? view('travels/view_proof_of_expenditure', $data) : redirect()->to(site_url());
	}

	public function viewRequestAuthorize()
	{
		return ($this->is_logged) ? view('travels/view_requests_authorize') : redirect()->to(site_url());
	}

	public function viewReposrts()
	{
		return ($this->is_logged) ? view('travels/view_reports') : redirect()->to(site_url());
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
		try {
			$idRequest = trim($this->request->getPost('folio'));
			$type = trim($this->request->getPost('type'));
			if ($type == 1) {
				$query = $this->db->query("SELECT a.total_travel AS monto, b.daily_amount AS monto_diario, b.roman_num AS icon_lvl,
				IF(c.id_item_travel IS NULL,0,SUM(c.amount)) AS comprobado, IF(c.id_item_travel IS NULL,a.total_travel,a.total_travel - SUM(c.amount)) AS descuento
				FROM tbl_services_request_travel AS a 
				JOIN cat_level_grade AS b ON b.id_level = a.`level`
				LEFT JOIN tbl_services_items_travel AS c ON a.id_request_travel = c.id_travel
				WHERE a.active_status = 1 AND a.id_request_travel = $idRequest
				GROUP BY a.id_request_travel")->getRow();
			} else {
				$query = $this->db->query("SELECT a.total_amount AS monto, b.daily_amount AS monto_diario, b.roman_num AS icon_lvl,
				IF(c.id_item_expenses IS NULL,0,SUM(c.amount)) AS comprobado, IF(c.id_item_expenses IS NULL,a.total_amount,a.total_amount - SUM(c.amount)) AS descuento
				FROM tbl_services_request_expenses AS a 
				JOIN cat_level_grade AS b ON b.id_level = a.level_
				LEFT JOIN tbl_services_items_expenses AS c ON c.id_expenses = a.id_expenses
				WHERE a.active_status = 1 AND a.id_expenses = $idRequest
				GROUP BY a.id_expenses;")->getRow();
			}

			// $query1 = $this->db->query("")->getResult();
			$query1 = null;


			$data = ["encavezado" => $query, "items" => $query1];
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

		$dailyAmount = $this->db->query("SELECT daily_amount FROM cat_level_grade WHERE active_status = 1 AND id_level = $idLevel")->getRow()->daily_amount;
		$query = $this->db->query("SELECT mony_for_day, type_mony FROM cat_travels_country WHERE active_status = 1 AND id_country = $idCountry")->getRow();
		$amount = ($idCountry == 1) ? intval($dailyAmount) : intval($query->mony_for_day);
		$unidAmount = ($idCountry == 1) ? 'MXN' : $query->type_mony;

		$diff = (new DateTime($starDate))->diff(new DateTime($endDate));
		$days = $diff->days;
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
			$title = "Ingrid Cardenas";
			// $email = "icardenas@walworth.com.mx";
			$email = "hrivas@walworth.com.mx";
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

	function dataRequestHeadLetters()
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		// MD5(concat('" . $key . "',id_es))='" . $id_permission . "'
		$folio = $this->request->getPost("folio");
		$type = $this->request->getPost("type");

		if ($type == 1) { // Viaticos
			$query = $this->db->query("SELECT a.id_request_travel AS folio,
				a.total_money AS solicitado, a.card_confirm_money AS cuenta, a.user_name,
				a.verification_money AS comprobado, a.money_daily_for_grade AS monto_diario,
				IF((a.total_money  + 10) < a.card_confirm_money,
					((a.card_confirm_money - a.total_money) + (a.total_money - a.verification_money))
				,a.card_confirm_money - a.verification_money) As descuento,
				(SELECT ct1.roman_num FROM cat_level_grade AS ct1 WHERE ct1.id_level = a.id_grade_level) AS icon_grado
				FROM tbl_services_request_travel AS a 
			WHERE MD5(CONCAT('$key',a.id_request_travel)) = '$folio'")->getRow();
		} else { // Gasto
			$query = $this->db->query("SELECT a.id_request_expenses AS folio, a.user_name,
				a.total_money AS solicitado, a.card_confirm_money AS cuenta,
				a.verification_money AS comprobado, CONCAT(0) AS monto_diario,
				IF((a.total_money  + 10) < a.card_confirm_money,
					((a.card_confirm_money - a.total_money) + (a.total_money - a.verification_money))
				,a.card_confirm_money - a.verification_money) As descuento,
			CONCAT(' ') AS icon_grado
			FROM tbl_services_request_expenses AS a 
			WHERE MD5(CONCAT('$key',a.id_request_expenses)) = '$folio'")->getRow();
		}
		return json_encode($query);
	}

	function listTravelAccount()
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$idRequest = $this->request->getPost("id_request");
		$type = $this->request->getPost("type");
		$data = $this->db->query("SELECT a.id_account_status AS id_item,
				DATE_FORMAT(a.date_transaction,'%d/%m/%Y') AS fecha,
				UPPER(a.location_transaction) AS lugar,
				SUBSTRING_INDEX(b.pdf_travel_routes, '/home/g7lq4y9o2rou/public_html/sie.grupowalworth.com/', -1) AS pdf_travel_routes,
				a.amount, a.divisa, a.transaction_status,
				IF(a.rule_code IS NOT NULL,a.rule_code,'') AS rule,
				CASE
					WHEN a.transaction_status = 1 THEN
						'SIN COMPROBAR'
					WHEN a.transaction_status = 2 THEN
						'COMPROBADA'
					WHEN a.transaction_status = 3 THEN
						'DESCUENTO'
				END as comprobacion_txt,	
				CASE
					WHEN a.transaction_status = 1 THEN
						'warning'
					WHEN a.transaction_status = 2 THEN
						'success'
					WHEN a.transaction_status = 3 THEN
						'warning'
				END as comprobacion_color,				
				CASE 					
					WHEN a.politics_status = 1 THEN
						'A TIEMPO'
					WHEN a.politics_status = 2 THEN
						'ULTIMO DIA'
					WHEN a.politics_status = 3 THEN
						'DESCUENTO'
				END AS estado_txt,
				CASE 
					WHEN a.politics_status = 1 THEN
						'primary'
					WHEN a.politics_status = 2 THEN
						'info'
					WHEN a.politics_status = 3 THEN
						'danger'
				END AS estado_color,
				CASE
					WHEN b.accounting_authorization = 1 THEN
						'EN ESPERA' 
					WHEN b.accounting_authorization = 2 THEN
						'EN REGLA'
					WHEN b.accounting_authorization = 3 THEN
						CONCAT('ACEPTADO ',(SELECT ct1.`name` FROM tbl_users AS ct1 WHERE ct1.id_user = a.id_authorization))
					WHEN b.accounting_authorization = 4 THEN
						CONCAT('RECHAZADO ',(SELECT ct1.`name` FROM tbl_users AS ct1 WHERE ct1.id_user = a.id_authorization))
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
				END AS conta_color
			FROM tbl_services_account_status AS a
			LEFT JOIN  tbl_services_verification_items_travel_expenses AS b ON b.active_status = 1 AND b.id_account_status = a.id_account_status AND b.facture_type = 1
			WHERE a.active_status = 1 
				AND MD5(CONCAT('$key',a.id_request)) = '$idRequest'
				AND a.type = $type
		ORDER BY id_item DESC")->getResult();
		/* DISPARADOR */
		// 	UPDATE tbl_services_account_status SET politics_status = 
		// 	CASE 
		// 		WHEN date_transaction >= LAST_DAY(date_transaction) - INTERVAL 4 DAY AND CURDATE() = LAST_DAY(date_transaction)
		//	 		THEN 2 -- 'Ultimo día'
		// 		WHEN date_transaction >= LAST_DAY(date_transaction) - INTERVAL 4 DAY AND CURDATE() > LAST_DAY(date_transaction)
		//	 		THEN 3 -- 'Fuera de tiempo' 
		//  	WHEN DATEDIFF(date_transaction, CURDATE()) > -5 THEN
		// 			1 -- 'A TIEMPO'
		//		WHEN DATEDIFF(date_transaction, CURDATE()) = -5 THEN
		// 			2 -- 'ULTIMO DIA'
		//  	WHEN DATEDIFF(date_transaction, CURDATE()) < -5 THEN
		// 			3 -- 'FUERA DE TIEMPO'
		//  END 
		//  WHERE active_status=1
		//  AND transaction_status = 1;
		return ($data != null) ? json_encode($data) : json_encode(false);
	}

	function insertTravelAccount()
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

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

		$document = IOFactory::load($guieFile);    //preparamos documento para su lectura
		$sheet = $document->getActiveSheet();  // Obtener la hoja de trabajo
		$Rows = $sheet->getHighestDataRow(); //obtrnemos el numero maxicmo de FILAS que tengan datos

		$this->db->transStart();

		for ($iRow = 2; $iRow <= $Rows; $iRow++) {

			// for ($iColum=0; $iColum < 12 ; $iColum++)  { // automatizar reccorrido de columnas
			$dateTransaction = $sheet->getCellByColumnAndRow(1, $iRow)->getValue();
			$locationTransaction = $sheet->getCellByColumnAndRow(2, $iRow)->getValue();
			$amount = $sheet->getCellByColumnAndRow(3, $iRow)->getValue();
			$divisa = $sheet->getCellByColumnAndRow(4, $iRow)->getValue();
			$amountMxn = $sheet->getCellByColumnAndRow(5, $iRow)->getValue();
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
		} else {
			$this->db->query("UPDATE tbl_services_request_expenses 
				SET card_confirm_money = (SELECT SUM(a.amount) 
					FROM tbl_services_account_status AS a 
					WHERE a.active_status = 1 
					AND a.id_request = $idRequest AND a.type = $type) 
				WHERE active_status = 1 
			AND id_request_expenses = $idRequest");
		}
		// return json_encode(false);
		$result = $this->db->transComplete();
		return ($result) ? json_encode(true) : json_encode(false);
	}

	function insertTravelAccountIndividual()
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';

		$idRequestEncrypt = $this->request->getPost("id_request");
		$type = $this->request->getPost("type");

		if ($type == 1) {
			$query = $this->db->query("SELECT id_request_travel AS id_request, id_user FROM tbl_services_request_travel WHERE MD5(CONCAT('$key',id_request_travel)) = '$idRequestEncrypt'")->getRow();
			$idRequest = $query->id_request;
			$idUser = $query->id_user;
		} else {
			$query = $this->db->query("SELECT id_request_expenses AS id_request, id_user FROM tbl_services_request_expenses WHERE MD5(CONCAT('$key',id_request_expenses)) = '$idRequestEncrypt'")->getRow();
			$idRequest = $query->id_request;
			$idUser = $query->id_user;
		}


		$this->db->transStart();

		// for ($iColum=0; $iColum < 12 ; $iColum++)  { // automatizar reccorrido de columnas
		$dateTransaction = $this->request->getFile("fecha");
		$locationTransaction = $this->request->getFile("lugar");
		$amount = $this->request->getFile("monoto_original");
		$divisa = $this->request->getFile("divisa");
		$amountMxn = $this->request->getFile("monto_mxn");
		$ruleCode = $this->request->getFile("regla_codigo");


		$data = [
			'id_request' => $idRequest,
			'type' => $type,
			'id_user' => $idUser,
			'date_transaction' => $dateTransaction,
			'location_transaction' => $locationTransaction,
			'amount' => $amount,
			'divisa' => $divisa,
			'amount_mxn' => $amountMxn,
			'rule_code' => $ruleCode,
			'id_created' => session()->id_user,
			'created_at' => date("Y-m-d H:i:s"),
		];
		$this->servicesAccount->insert($data);
		/* if ($type == 1) {
			$this->db->query("UPDATE tbl_services_request_travel 
				SET card_confirm_money = (SELECT SUM(a.amount) 
					FROM tbl_services_account_status AS a 
					WHERE a.active_status = 1 
					AND a.id_request = $idRequest AND a.type = $type) 
				WHERE active_status = 1 
			AND id_request_travel = $idRequest");
		} else {
			$this->db->query("UPDATE tbl_services_request_expenses 
				SET card_confirm_money = (SELECT SUM(a.amount) 
					FROM tbl_services_account_status AS a 
					WHERE a.active_status = 1 
					AND a.id_request = $idRequest AND a.type = $type) 
				WHERE active_status = 1 
			AND id_request_expenses = $idRequest");
		} */
		$result = $this->db->transComplete();
		return ($result) ? json_encode(true) : json_encode(false);
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
			$email = "hrivas@walworth.com.mx";
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
			$mail->Username = 'requisiciones@grupowalworth.com';
			$mail->Password = '2contodo';
			$mail->Port = 587;

			if ($dataRequest->if_doc != 1) {
				if ($dataRequest->request_status == 1) {
					$mail->setFrom('notificacion@grupowalworth.com', 'Solicitud|Viaje');
				} else if ($dataRequest->request_status == 2) {
					$mail->setFrom('notificacion@grupowalworth.com', 'Solicitud|Viaje|Autorizada');
					// $mail->addAddress('becarioadmin01@walworth.com.mx', 'Alejandro Huerta');
					// $mail->addAddress('recepcion@walworth.com.mx', 'Gabriela Marin');
				} else if ($dataRequest->request_status == 3) {
					$mail->setFrom('notificacion@grupowalworth.com', 'Solicitud|Viaje|Concluida');
				} else if ($dataRequest->request_status == 6) {
					$mail->setFrom('notificacion@grupowalworth.com', 'Solicitud|Viaje|Cancelada');
				}
			} else {
				$mail->setFrom('notificacion@grupowalworth.com', 'Comprobaciones|Viaje');
				// $mail->addAddress('becarioadmin01@walworth.com.mx', 'Alejandro Huerta');
				// $mail->addAddress('recepcion@walworth.com.mx', 'Gabriela Marin');
				foreach ($dataItem as $key) {
					var_dump($key->document);
					$mail->addAttachment($key->document);
				}
			}
			$mail->addAddress($email, $user);
			$mail->addBCC('hrivas@walworth.com.mx');
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

			$email = $queryNotify->nombre;
			$tittle = $queryNotify->email;
			$this->emailRequestTravel($email, $tittle, $id_request_travel, $typeTravel, 1);
			return ($result) ? json_encode($id_request_travel) : json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function emailRequestTravel($email = null, $tittle = null, $id_request_travel = null, $type = 0, $status = 0)
	{
		try {
			/* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
			$dir_email = ($type == 2) ? 'rcruz@walworth.com.mx' : changeEmail($email);
			$dir_tittle = ($type == 2) ? 'VICTOR MANUEL HERNANDEZ ALVARADO' : $tittle;

			$dir_email = 'rcruz@walworth.com.mx';
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
			$mail->Username = 'requisiciones@walworth.com';
			// SMTP password (This is that emails' password (The email you created earlier) )
			$mail->Password = 'Walworth321$';
			// TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
			$mail->Port = 587;

			//Recipients

			$mail->setFrom('notificacion@grupowalworth.com', 'Modulo de Viáticos & Gastos');

			$mail->addAddress($dir_email, $dir_tittle);
			$mail->addCC('ahuerta@walworth.com.mx', 'ADRIAN ALEJANDRO HUERTA CALDERON');
			// $mail->addCC('gmendoza@walworth.com.mx', 'GERARDO MENDOZA VILLEGAS');
			$mail->addBCC('hrivas@walworth.com.mx');
			$mail->addBCC('rcruz@walworth.com.mx');

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

			$dir_email = 'rcruz@walworth.com.mx';
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
			$mail->Username = 'requisiciones@walworth.com';
			// SMTP password (This is that emails' password (The email you created earlier) )
			$mail->Password = 'Walworth321$';
			// TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
			$mail->Port = 587;

			//Recipients

			$mail->setFrom('notificacion@grupowalworth.com', 'Modulo de Viáticos & Gastos');

			$mail->addAddress($dir_email, $dir_tittle);
			$mail->addCC('ahuerta@walworth.com.mx', 'ADRIAN ALEJANDRO HUERTA CALDERON');
			// $mail->addCC('gmendoza@walworth.com.mx', 'GERARDO MENDOZA VILLEGAS');
			$mail->addBCC('hrivas@walworth.com.mx');
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
				? "AND a.type_travel = 2"
				: "AND a.id_user IN (
				SELECT wt1.id_user 
				FROM tbl_assign_travel_expenses_manager AS wt1 
				WHERE wt1.active_status = 1 
				AND wt1.id_director = $idDirector)";

			$travelALL = $this->db->query("SELECT a.id_request_travel AS folio, a.user_name, a.payroll_number As nomina,  a.request_status,			
					CONCAT('$ ',a.total_money,' ',a.divisa_money) AS total, CONCAT('$access') AS access_direct,
					DATE_FORMAT(a.created_at,'%d/%,/%Y') AS creacion, a.verification_status,
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

	public function requestExpensesAll()
	{
		try {
			$expensesALL = $this->db->query("SELECT a.id_request_expenses AS folio, a.user_name, a.payroll_number AS nomina,
					CONCAT('$ ',a.total_money) AS total, a.request_status, a.verification_status,
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS creado,
					CONCAT(DATE_FORMAT(a.day_star_expenses,'%d/%m/%Y'),'  --  ',DATE_FORMAT(a.day_end_expenses,'%d/%m/%Y')) AS fechas,
					(SELECT st6.color FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS color,
					(SELECT st6.text FROM cat_travels_status AS st6 WHERE st6.type = 1 AND st6.status_ = a.request_status) AS txt
				FROM tbl_services_request_expenses AS a 
					JOIN tbl_users AS b ON a.id_user = b.id_user
				WHERE a.active_status = 1 			 
			ORDER BY folio DESC  ")->getResult();
			return ($expensesALL) ? json_encode($expensesALL) : json_encode(false);
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
			ORDER BY folio DESC ")->getResult();
			return ($travelMy) ?  json_encode($travelMy) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
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
				WHERE id_request_$tbl_field = $query->id_request
			");

			$this->db->query("UPDATE tbl_services_account_status SET active_status = 2 
			WHERE id_account_status = $idItem");

			$result = $this->db->transComplete();
			return ($result) ? json_encode(true) : json_encode(false);
		} catch (Exception $e) {
			return json_encode(false);
		}
	}
	public function requestMyExpenses()
	{
		try {
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
			ORDER BY folio DESC  ")->getResult();
			return ($expensesMy) ? json_encode($expensesMy) : json_encode(false);
		} catch (Exception $e) {
			echo 'Message Error: ', $e->error;
		}
	}

	public function insertRequestExpenses()
	{
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

			$email = $queryNotify->nombre;
			$tittle = $queryNotify->email;

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

	public function subirXML()
	{

		$this->db->transStart();

		$dateFileUp = strval(date("Y-m-d_H:i:s"));
		$date = date("Y-m-d_H:i:s");
		$folio = $this->request->getPost('folio');
		$tipo_gasto = $this->request->getPost('tipo_gasto');
		$total_gatos = $this->request->getPost('total_gatos');
		$tipo_gastos = ($tipo_gasto == 1) ? "Viaticos" : "Gastos";
		$tipo = ($this->request->getPost('tipo_gasto') == 1) ? "V" : "G";
		$binder =  FCPATH . "XML/" . $tipo_gastos . "/" . $tipo . "_folio_" . $folio;
		$nameArchive =  $tipo . "_folio_" . $folio . ".zip";
		$pais_cfdi = '';
		if (!file_exists($binder)) {
			mkdir($binder, 0750, true);
		}

		/* $builder 	= $db->table('files'); */
		$msg = 'Error al subir los archivos.';
		if ($this->request->getFileMultiple('userfile')) {
			foreach ($this->request->getFileMultiple('userfile') as $file) {
				$file->move($binder);

				/* $data = [
					'name' =>  $file->getClientName(),
					'type'  => $file->getClientMimeType(),
					'ext' 	=> $file->getClientExtension(),
					'size' 	=> $file->getSize('kb'),
				]; */

				if ($file->getClientExtension() === 'xml') {


					$url = $binder . "/" . $file->getClientName();
					$xml = simplexml_load_file($url);
					$ns = $xml->getNamespaces(true);
					$xml->registerXPathNamespace('c', $ns['cfdi']);
					$xml->registerXPathNamespace('t', $ns['tfd']);
					//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
					foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
						$version = $cfdiComprobante['Version'];
						$date_cfdi = $cfdiComprobante['Fecha'];
						$sub_total = $cfdiComprobante['SubTotal'];
						$total = $cfdiComprobante['Total'];
						$currency = $cfdiComprobante['Moneda'];
						//$iva = $cfdiComprobante['Importe'];
						$SerieFolio = $cfdiComprobante['Folio'];
					}

					foreach ($xml->xpath('//cfdi:Impuestos') as $Impuestos) {
						$iva = $Impuestos['TotalImpuestosTrasladados'];
					}

					foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
						$rfc_cfdi = $Emisor['Rfc'];
						$name_cfdi = $Emisor['Nombre'];
					}

					foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal) {
						$pais_cfdi = $DomicilioFiscal['Pais'];
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
					}

					$data = [
						"tipo_gasto" => $tipo_gasto,
						"folio" => $folio,
						'fecha_factura' => $date_cfdi,
						'sub_total' => $sub_total,
						'total' => $total,
						'iva' => $iva,
						'moneda' => $currency,
						'rfc' => $rfc_cfdi,
						'pais' => $pais_cfdi,
						'nombre_proveedor' => $name_cfdi,
						'created_at' => $date,
						'nombre' => $SerieFolio,
						'version_cfdi' => $version
					];
					$this->xmlModel->insert($data);
				}
			}


			$add_invoices = $this->xmlModel->addInvoices($folio);
			$resta_total = floatval($total_gatos) - floatval($add_invoices[0]["total"]);
			$data = [
				"total_facturas" => $add_invoices[0]["total"],
				"resta_total" => $resta_total
			];
		}

		$this->db->transComplete();

		$result = ($tipo_gasto == 1) ? $this->servicesTravelModel->verification($folio) : $this->expensesModel->verification($folio);

		return ($result) ? json_encode($data) : json_encode(false);

		$xml = simplexml_load_file(FCPATH . "/XML/37VLZ.xml");
		$ns = $xml->getNamespaces(true);
		$xml->registerXPathNamespace('c', $ns['cfdi']);
		$xml->registerXPathNamespace('t', $ns['tfd']);
		//$xml->registerXPathNamespace('r', $ns['registrofiscal']);


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
			echo "<br />*****************************************************************Comprobante Impuestos Traslados Traslado**********************************************************************<br />";
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
								MD5(concat('" . $key . "',id_expenses))='" . $id_request_expenses . "'");
		$dataRequest = $query->getRow();
		$query0 = $this->db->query("SELECT id_category, amount, `definition`
                            FROM tbl_services_expenses_items
                            WHERE id_expenses = $dataRequest->id_expenses AND active_status = 1");
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

	function dowloadFileAccount()
	{
		$idItem = $this->request->getPost('id_item');
		$query = $this->db->query("SELECT xml_travel_routes, pdf_travel_routes 
			FROM tbl_services_verification_items_travel_expenses WHERE active_status = 1
			AND id_account_status = $idItem 
			ORDER BY created_at DESC")->getRow();
		// var_dump($query);

		$file1 = $query->xml_travel_routes ?? '';
		$file2 = $query->pdf_travel_routes ?? '';
		$archivos = array($file1, $file2);
		// var_dump($archivos);
		$zipNombre = "Comprobantes.zip";

		$zip = new \ZipArchive();
		if ($zip->open($zipNombre, \ZipArchive::CREATE) === TRUE) {
			foreach ($archivos as $archivo) {
				$nombreArchivo = basename($archivo);
				$zip->addFile($archivo, $nombreArchivo);
			}
			$zip->close();
			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename="' . $zipNombre . '"');
			readfile($zipNombre);
			unlink($zipNombre); // Eliminar el archivo zip después de la descarga
		} else {
			return false;
		}
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
		if ($type == 1) {
			$columnTitle = 'A1:Y1';
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
					c.observation, c.facture_type
				FROM tbl_services_request_travel AS a
				JOIN tbl_services_account_status AS b ON a.id_request_travel = b.id_request AND b.active_status = 1 -- AND b.accounting_authorization IN (2,3)
				JOIN tbl_services_verification_items_travel_expenses AS c ON b.id_account_status = c.id_account_status AND c.active_status = 1 -- AND c.accounting_authorization IN (2,3)
				WHERE a.id_request_travel = $request
			")->getResult();

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

			foreach ($reporteSql as $value) {
				if ($value->facture_type == 1) {
					if ($cont > 2) {
						$spreadsheet->getActiveSheet()->getRowDimension("$cont")->setRowHeight(4); // alto de fila
						$spreadsheet->getActiveSheet()->getStyle("A$cont:Y$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5C636A');

						$cont++;
					}
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

				$spreadsheet->getActiveSheet()->getStyle("Y$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FCE5CD');
				$sheet->setCellValue('Y' . $cont, $value->observation);

				$cont++;
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

	function listAccountStatusPendingCheck()
	{
		$data = $this->db->query("SELECT a.id_account_status AS id_item,
			SUBSTRING_INDEX( b.pdf_travel_routes, '/home/g7lq4y9o2rou/public_html/sie.grupowalworth.com/', - 1 ) AS pdf_travel_routes,
			DATE_FORMAT( a.date_transaction, '%d/%m/%Y' ) AS fecha,
			UPPER( a.location_transaction ) AS lugar,
			a.amount, a.divisa, a.accounting_authorization,
			CASE
				WHEN a.type = 1 THEN
					'VIATICOS' 
				WHEN a.type = 2 THEN
					'GASTOS' 
				ELSE 
					'ERROR' 
			END AS tipo_request,
			CASE		
				WHEN a.type = 1 THEN
					(SELECT ct2.user_name FROM tbl_services_request_travel AS ct2 WHERE ct2.id_request_travel = a.id_request)
				WHEN a.type = 2 THEN
					(SELECT ct2.user_name FROM tbl_services_request_expenses AS ct2 WHERE ct2.id_request_expenses = a.id_request)
			END AS user_name,
			CASE
				WHEN a.accounting_authorization = 1 THEN
					'EN ESPERA' 
				WHEN a.accounting_authorization = 3 THEN
					CONCAT('ACEPTADO ',(SELECT ct1.`name` FROM tbl_users AS ct1 WHERE ct1.id_user = a.id_authorization))
				WHEN a.accounting_authorization = 4 THEN
					CONCAT('RECHAZADO ',(SELECT ct1.`name` FROM tbl_users AS ct1 WHERE ct1.id_user = a.id_authorization))
				ELSE
					'NO COMPROBADO'
			END AS conta_txt,
			CASE 
				WHEN a.accounting_authorization = 1 THEN
					'warning' 
				WHEN a.accounting_authorization = 3 THEN
					'success'
				WHEN a.accounting_authorization = 4 THEN
					'danger'
				ELSE
					'secondary'
			END AS conta_color
			FROM tbl_services_account_status AS a
				LEFT JOIN tbl_services_verification_items_travel_expenses AS b 
					ON a.id_account_status = b.id_account_status 
					AND b.active_status = 1 
					AND b.facture_type = 1 
			WHERE a.active_status = 1 
				AND politics_status = 3 
				AND transaction_status = 2
			GROUP BY id_item
			ORDER BY b.created_at DESC
		LIMIT 1000;")->getResult();
		$dataReturn = ($data != null) ? $data : false;
		return json_encode($dataReturn);
	}

	function updateAccountStatusPendingCheck()
	{
		$status = $this->request->getPost("status");
		$idAcountStatus = $this->request->getPost("id_acount");

		$this->db->transStart();
		$updateData = [
			'accounting_authorization' => $status,
			'id_authorization' => session()->id_user,
			'authorization_at' => date("Y-m-d H:i:s"),
		];
		$this->servicesAccount->update($idAcountStatus, $updateData);

		if ($status == 3) {
			$query = $this->db->query("SELECT id_request, `type`, amount 
				FROM tbl_services_account_status 
				WHERE active_status = 1 
			AND id_account_status = $idAcountStatus")->getRow();

			$amount = $query->amount;
			$expTra = ($query->type == 1) ? 'travel' : 'expenses';
			$idRequest = $query->id_request;

			$this->db->query("UPDATE tbl_services_request_$expTra 
				SET verification_money = verification_money + $amount
			WHERE id_request_$expTra = $idRequest");
		}

		$result = $this->db->transComplete();
		return json_encode($result);
	}

	function deletVerificationAccountStatus()
	{
		$idItem = $this->request->getPost("id_item");
		if (!session()->is_logged) {
			return redirect()->to(site_url());
		}
		try {
			$query = $this->db->query("SELECT transaction_status 
				FROM tbl_services_account_status 
				WHERE active_status = 1 
				AND id_account_status = 1
			")->getRow()->transaction_status;
			if ($query == 2) {
				$idUser = session()->id_user;
				$toDay = date("Y-m-d H:i:s");

				$this->db->query("UPDATE tbl_services_verification_items_travel_expenses 
				SET active_status = 2, id_cancel = $idUser, cancel_at = '$toDay'
				WHERE id_account_status = $idItem");
				return json_encode(true);
			} else {
				return json_encode(false);
			}
		} catch (\Exception $e) {
			return json_encode('Ha ocurrido un error en el servidor ' . $e);
		}
	}
}
