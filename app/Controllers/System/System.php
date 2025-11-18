<?php

/**
 * ARCHIVO MODULO SISTEMAS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR: HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Models\SuppliesModel;
use App\Models\SuppliesDeleteModel;
use App\Models\UserModel;
use App\Models\TicketsModel;
use App\Models\ActivityModel;
use App\Models\DeptoModel;
use App\Models\InventorySystemModel;
use App\Models\InventorySystemRequestModel;
use App\Models\InventorySystemInModel;

use App\Models\SystemEquipModels;
use App\Models\InventoryEquipModel;

use App\Models\SystemLoanModel;
use App\Models\SystemLoanModelItem;

use App\Models\DeliveriesAndOutletsModel;
use Exception as GlobalException;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class System extends BaseController
{
	public function __construct()
	{
		require_once APPPATH . '/Libraries/vendor/autoload.php';
		$this->userModel = new UserModel();
		$this->suppliesModel = new SuppliesModel();
		$this->suppliesDelete = new SuppliesDeleteModel();
		$this->deptoModel = new DeptoModel();
		$this->ticketsModel = new TicketsModel();
		$this->activityModel = new ActivityModel();
		$this->deliOutModel = new DeliveriesAndOutletsModel();

		$this->equipAssignmentModel = new SystemEquipModels();
		$this->equipamentModel = new InventoryEquipModel();

		$this->inventoryModel = new InventorySystemModel();
		$this->inventoryRequestModel = new InventorySystemRequestModel();
		$this->inventoryInModel = new InventorySystemInModel();
		$this->loanModel = new SystemLoanModel();
		$this->loanModelItem = new SystemLoanModelItem();

		$this->db = \Config\Database::connect();
		$this->is_logged = session()->is_logged ? true : false;
		if (!$this->is_logged) {
			return redirect()->to(site_url());
		}
	}

	public function index()
	{
		return ($this->is_logged) ? view('system/supplies') : redirect()->to(site_url());
	}

	public function viewMaintenanceAll()
	{
		return ($this->is_logged) ? view('system/maintenance_calendar') : redirect()->to(site_url());
	}

	public function viewLoan()
	{
		$query = $this->db->query("SELECT id_user, CONCAT(`name`,' ',surname,' ',second_surname) AS user_name 
			FROM tbl_users 
			WHERE active_status = 1 
			AND id_user NOT IN (710,1121,1327,1248) 
		ORDER BY surname ASC")->getResult();
		$data = ["usuarios" => $query];
		return ($this->is_logged) ? view('system/loan_system', $data) : redirect()->to(site_url());
	}

	public function timeLine()
	{
		$data = $this->deliOutModel->where('active_status', 1)->findAll();
		return ($this->is_logged) ? view('system/TimeLine', $data) : redirect()->to(site_url());
	}

	public function suppliesAll()
	{
		$supplies_all = $this->suppliesModel->where('active_status', 1)->findAll();
		return (count($supplies_all) > 0) ? json_encode($supplies_all) : json_encode("error");
	}

	public function editSupplies($id_supplies = null)
	{
		$data_supplies = $this->suppliesModel->find($id_supplies);
		return (count($data_supplies) > 0) ? json_encode($data_supplies) : json_encode("error");
	}

	public function viewEquipamentAdmin()
	{
		return ($this->is_logged) ? view('system/equipment_asignation') : redirect()->to(site_url());
	}

	public function viewReports()
	{
		return ($this->is_logged) ? view('system/equipment_report') : redirect()->to(site_url());
	}

	public function viewInventory()
	{
		$dataEquipos = $this->inventoryModel->where('active_status', 1)->get()->getResult();
		$users = $this->db->query("SELECT id_user, CONCAT(`name`,' ',surname,' ',second_surname) AS `user` FROM tbl_users WHERE active_status = 1 ORDER BY `name` ASC")->getResult();
		$groups = $this->db->table('cat_departament')->select('id_depto, departament, area')->where('active_status', 1)->get()->getResultArray();
		$data = [
			"productos" => $dataEquipos,
			"departament" => $groups,
			"usuarios" => $users,
		];
		return ($this->is_logged) ? view('system/inventario', $data) : redirect()->to(site_url());
	}

	public function productData()
	{
		$id_product = $this->request->getPost('id_product');
		$key = $this->request->getPost('key');
		if ($id_product == null && $key == null) {
			$data = $this->inventoryModel->where('active_status', 1)->where('amount >', 0)->findAll();
		} else if ($id_product != null && $key == null) {
			$data = $this->db->query("SELECT amount, cost_unit FROM tbl_system_inventory WHERE id_product = $id_product")->getRow();
		} elseif ($id_product == null && $key != null) {
			$name = strip_tags($key);
			$result = $this->db->query("SELECT * FROM tbl_system_inventory WHERE active_status = 1 AND product LIKE '%$name%' ORDER BY id_product DESC");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$data = '<div><a class="suggest-element" data="' . utf8_encode($row['name']) . '" id="product' . $row['id_product'] . '">' . utf8_encode($row['name']) . '</a></div>';
				}
			}
		}
		return ($data) ? json_encode($data) : json_encode(false);
	}

	public function productDataInventory()
	{
		$data = $this->inventoryModel->where('active_status', 1)->findAll();
		return ($data) ? json_encode($data) : json_encode(false);
	}

	public function productIn()
	{
		try {
			$id_sys = session()->id_user;
			$to_day = date("Y-m-d H:i:s");
			$type =  $this->request->getPost("tipo");
			$code = $this->request->getPost("codigo_entrada");
			$requi = $this->request->getPost("requisicion_entrada");
			$cost_unit = $this->request->getPost("costo_equipo");

			if ($type != null) {
				$stock = $this->request->getPost("cantidad_entrada");
				$id_product = $this->request->getPost("id_articulos");
				$origin_stock = $this->request->getPost("cantidad");
				$dataUp = ['amount' => intval($stock) + intval($origin_stock),];
				$this->inventoryModel->update($id_product, $dataUp);

				$cost_unit = $this->db->query("SELECT cost_unit AS costo FROM tbl_system_inventory 
				WHERE active_status = 1 AND id_product = $id_product")->getRow()->costo ?? '';
			} else {
				$product = $this->request->getPost("nombre_suministro");
				$min = $this->request->getPost("stock_min");
				$stock = $this->request->getPost("stock");
				$dataProduct = [
					'product' => $product,
					'amount' => $stock,
					'cost_unit' => $cost_unit,
					'min' => $min,
				];
				$this->inventoryModel->insert($dataProduct);
				$id_product = $this->db->insertID();
			}
			$dataIn = [
				'id_product' => $id_product,
				'amount_in' => $stock,
				'id_register' => $id_sys,
				'epicor_code_produc' => $code,
				'epicor_code_requi' => $requi,
				'cost_unit' => $cost_unit,
				'created_at' => $to_day,
			];
			$this->inventoryInModel->insert($dataIn);
			return json_encode(true);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function productDelet()
	{
		$id_product = $this->request->getPost('product_');
		$status = ['active_status' => 2,];
		$upProduct = $this->inventoryModel->update($id_product, $status);
		return ($upProduct) ? json_encode(true) : json_encode(false);
	}

	public function productOut()
	{
		try {
			$to_day = date("Y-m-d H:i:s");
			$id_sys = session()->id_user;
			$id_user_ = $this->request->getPost('id_user');
			$id_user = ($id_user_ != null) ? $id_user_ : "";
			$payroll_number_ = $this->request->getPost('ID_');
			$payroll_number = ($payroll_number_ != null) ? $payroll_number_ : "";
			$user_ = $this->request->getPost('user');
			$user = ($user_ != null) ? $user_ : "";
			$depto = $this->request->getPost('depto');
			$id_product_ = $this->request->getPost('product_');
			$amount_ = $this->request->getPost('cantidad_');
			$amount_origin_ = $this->request->getPost('amount_');
			$responsibility_ = $this->request->getPost('responsiba_');
			for ($iP = 0; $iP < count($id_product_); $iP++) {
				$new_amount = intval($amount_origin_[$iP]) - intval($amount_[$iP]);
				$dataProduct = [
					'amount' => $new_amount,
				];
				$dataRequest = [
					'responsibility' => $responsibility_[$iP] ?? 0,
					'id_user' => $id_user,
					'payroll_number' => $payroll_number,
					'name' => $user,
					'depto' => $depto,
					'id_product' => $id_product_[$iP],
					'amount' => $amount_[$iP],
					'id_deliver' => $id_sys,
					'created_at' => $to_day,
				];
				$upProduct = $this->inventoryModel->update($id_product_[$iP], $dataProduct);
				$inRequest = $this->inventoryRequestModel->insert($dataRequest);
			}
			return ($upProduct && $inRequest) ? json_encode(true) : json_encode(false);
		} catch (\Exception $e) {
			return (json_encode($e));
		}
	}

	public function productEdit()
	{
		$id_product = $this->request->getPost('id_article');
		$product = $this->request->getPost('description');
		$min = $this->request->getPost('stock_min');
		$data = [
			'product' => $product,
			'min' => $min,
		];
		$upProduct = $this->inventoryModel->update($id_product, $data);
		return ($upProduct) ? json_encode(true) : json_encode(false);
	}

	public function outletSupplies()
	{
		try {
			$id_supplies = trim($this->request->getPost('id_articulo'));
			$supplies = trim($this->request->getPost('nombre_articulo'));
			$person_who_received = trim($this->request->getPost('persona_recibe'));
			(int)$quantity = trim($this->request->getPost('cantidad_salida'));
			$observation = trim($this->request->getPost('observacion_salida'));
			$date = date("Y-m-d H:i:s");
			$builder =  $this->deliOutModel->table('tbl_deliveries_and_outlets_system');

			$data = [
				"id_user" => session()->id_user,
				"created_at" => $date,
				"deliveries_and_outlets" => 2,
				"supplies" => $supplies,
				"quantity" => $quantity,
				"person_who_received" => $person_who_received,
				"id_supplies" => $id_supplies,
				"observation" => $observation
			];

			$builder->insert($data);

			$infoSupplies = $this->suppliesModel->where('id_supplies', $id_supplies)->find();
			//d($infoSupplies);
			foreach ($infoSupplies as $key => $value) {
				(int)$stock = $value["stock_supplies"];
				(int)$stock_min = $value["stock_min"];
				$supplies =  $value["description_supplies"];
				if ($stock >= $quantity) {
					if ($stock > 0) {
						$total = $stock - $quantity;
						$data = ['stock_supplies' => $total];
						$this->suppliesModel->update($id_supplies, $data);
						if ($total <= $stock_min) {
							$datas = ['supplies' => $supplies, 'stock' => $total];
							if ($value["supply_category"] == 1) {

								$builder = $this->db->table('tbl_supplies_inventory');
								$builder->select('description_supplies, stock_supplies');
								$builder->where('stock_supplies <', 3);
								$builder->where('supply_category', 1);
								$datainfos = $builder->get()->getResult();
								$datainfo = ["stocks" => $datainfos];
								$this->emailNotificationToners($datainfo);
							} else {
								$datas = ['supplies' => $supplies, 'stock' => $total];
								$this->emailNotification($datas);
							}
						}
						return json_encode($total);
					} else {
						return "Error";
					}
				} else {
					return "Exede";
				}
			}
			//$id_oc=$builder->insertID();
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}
	public function inputSupplies()
	{
		try {
			$id_supplies = trim($this->request->getPost('id_articulo'));
			$supplies = trim($this->request->getPost('nombre_entrada'));
			(int)$quantity = trim($this->request->getPost('cantidad_entrada'));
			$observation = trim($this->request->getPost('observacion_entrada'));

			$date = date("Y-m-d H:i:s");
			$builder =  $this->deliOutModel->table('tbl_deliveries_and_outlets_system');

			$data = [
				"id_user" => session()->id_user,
				"created_at" => $date,
				"deliveries_and_outlets" => 1,
				"supplies" => $supplies,
				"quantity" => $quantity,
				"id_supplies" => $id_supplies,
				"observation" => $observation
			];

			$builder->insert($data);

			$infoSupplies = $this->suppliesModel->where('id_supplies', $id_supplies)->find();
			//d($infoSupplies);
			foreach ($infoSupplies as $key => $value) {
				(int)$stock = $value["stock_supplies"];

				$total = $stock + $quantity;
				$data = ['stock_supplies' => $total];
				$result = $this->suppliesModel->update($id_supplies, $data);
				echo ($result == true) ? json_encode($result) : "error";
			}
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function quantityOfSupplies()
	{
		$id_supplies = trim($this->request->getPost('id_articulo'));
		$infoSupplies = $this->suppliesModel->where('id_supplies', $id_supplies)->find();
		foreach ($infoSupplies as $key => $value) {
			(int)$total = $value["stock_supplies"];
			echo ($total > 0) ? "alcanza" : "error";
		}
	}

	public function updateSupplies()
	{
		try {
			$id_supplies = trim($this->request->getPost('id_articulo'));
			$supplies = trim($this->request->getPost('nombre_articulo'));
			(int)$stock_max = trim($this->request->getPost('stock_max'));
			(int)$stock_min = trim($this->request->getPost('stock_min'));


			$builder =  $this->suppliesModel->table('tbl_supplies_inventory');

			$data = [
				"description_supplies" => $supplies,
				"stock_min" => $stock_min,
				"stock_max" => $stock_max
			];

			$result = $builder->update($id_supplies, $data);

			return ($result) ? "ok" : "error";
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function newSupplies()
	{
		try {
			$supplies = trim($this->request->getPost('nombre_suministro'));
			(int)$stock_max = trim($this->request->getPost('stock_max'));
			(int)$stock_min = trim($this->request->getPost('stock_min'));

			$date = date("Y-m-d H:i:s");
			$id_user = session()->id_user;
			$builder =  $this->suppliesModel->table('tbl_supplies_inventory');

			$data = [
				"description_supplies" => $supplies,
				"stock_min" => $stock_min,
				"stock_max" => $stock_max,
				"created_at" => $date,
				"created_user" => $id_user
			];
			//var_dump( $data);
			$result = $builder->insert($data);

			return ($result) ? "ok" : "error";
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function viewEquipment()
	{
		$dataEquipos = $this->db->query("SELECT id, type_product FROM cat_system_equip_type WHERE active_status = 1")->getResult();
		$data = ["tipos" => $dataEquipos];
		return ($this->is_logged) ? view('system/equipment', $data) : redirect()->to(site_url());
	}

	public function viewTickets()
	{
		return ($this->is_logged) ? view('system/create_tickets') : redirect()->to(site_url());
	}

	public function myTickets()
	{
		return ($this->is_logged) ? view('system/myTickets') : redirect()->to(site_url());
	}

	public function Tickets()
	{
		return ($this->is_logged) ? view('system/ticketsAll') : redirect()->to(site_url());
	}

	public function searchUser()
	{
		$payroll_number = trim($this->request->getPost('num_nomina'));
		$builder = $this->db->table('tbl_users a');
		$builder->select('a.id_user,a.name,a.email,a.surname,a.second_surname,a.payrollnumber_image,b.departament,b.clave_depto,c.job');
		$builder->join('cat_departament b', 'a.id_departament = b.id_depto', 'left');
		$builder->join('cat_job_position c', 'a.id_job_position = c.id', 'left');
		$builder->where('payroll_number', $payroll_number);
		$builder->limit(1);
		$data = $builder->get()->getResult();

		return (count($data) > 0) ? json_encode($data) : json_encode('error');
	}

	public function generateTicket()
	{
		$payroll_number = trim($this->request->getPost('num_nomina'));
		$complejidad = trim($this->request->getPost('complejidad'));
		$home = trim($this->request->getPost('homeoffice'));
		$id_user = trim($this->request->getPost('id_usuario'));
		$user = trim($this->request->getPost('usuario'));
		$depto = trim($this->request->getPost('depto'));
		$job_position = trim($this->request->getPost('puesto'));
		$activity = trim($this->request->getPost('actividad'));
		$activity_date = trim($this->request->getPost('fecha_ticket'));
		$email = trim($this->request->getPost('email_ticket'));
		$date = date("Y-m-d H:i:s");
		$date1 = date("Y-m-d");


		$carpeta = './images/firmas_' . session()->name;

		if (!file_exists($carpeta)) {
			mkdir($carpeta, 0777, true);
		}

		$carpeta2 = './images/firmas_' . session()->name . '/' . $date1;

		if (!file_exists($carpeta2)) {
			mkdir($carpeta2, 0777, true);
		}

		$file = $this->request->getPost('firma');

		$imageInfo = explode(";base64,", $file);
		$imgExt = str_replace('data:image/', '', $imageInfo[0]);
		$file = str_replace(' ', '+', $imageInfo[1]);
		$imageName = "firma-" . time() . "." . $imgExt;
		$path = $carpeta2 . "/" . $imageName;
		$ruta = file_put_contents($path, base64_decode($file));

		$data = [
			"id_user" => $id_user,
			"id_user_it" => session()->id_user,
			"payroll_number" => $payroll_number,
			"user" => $user,
			"complexity" => $complejidad,
			"homeoffice" => $home,
			"departament" => $depto,
			"position" => $job_position,
			"activity" => $activity,
			'img_firm' => $path,
			"activity_date" => $activity_date,
			"email_user" => $email,
			"created_at" => $date
		];

		$result = $this->ticketsModel->insert($data);
		return ($result) ? "ok" : "error";
	}

	public function ticket_it()
	{

		$complejidad = trim($this->request->getPost('complejidad_it'));
		$home = trim($this->request->getPost('homeoffice_it'));
		$activity = trim($this->request->getPost('actividad_it'));
		$date = date("Y-m-d H:i:s");
		$activity_date = trim($this->request->getPost('fecha_actividad'));

		$data = [
			"id_user" => session()->id_user,
			"payroll_number" => session()->payroll_number,
			"user" => session()->name . " " . session()->surname,
			"complexity" => $complejidad,
			"homeoffice" => $home,
			"departament" => session()->departament,
			"activity" => $activity,
			"activity_date" => $activity_date,
			"created_at" => $date
		];

		$result = $this->activityModel->insert($data);
		return ($result) ? "ok" : "error";
	}

	public function create_ticket_it()
	{

		$usuario_it = trim($this->request->getPost('usuario_it'));
		$complejidad = trim($this->request->getPost('complejidad_it'));
		$home = trim($this->request->getPost('homeoffice_it'));
		$activity = trim($this->request->getPost('actividad_it'));
		$date = date("Y-m-d H:i:s");
		$activity_date = trim($this->request->getPost('fecha_actividad'));

		$data = [
			"id_user" => session()->id_user,
			"payroll_number" => session()->payroll_number,
			"user" => $usuario_it,
			"complexity" => $complejidad,
			"homeoffice" => $home,
			"departament" => session()->departament,
			"activity" => $activity,
			"activity_date" => $activity_date,
			"created_at" => $date
		];

		$result = $this->activityModel->insert($data);
		return ($result) ? json_encode(true) : json_encode("error");
	}

	public function newPassword()
	{
		/* $password = ($this->request->getPost('password1')) ? trim($this->request->getPost('password1')) : session()->payroll_number;
		$nuevo_password = trim($this->request->getPost('password2')); */
		$idUser = session()->id_user;
		$query = $this->db->query("SELECT `password` AS pw FROM tbl_users WHERE id_user = $idUser")->getRow();
		$password = ($this->request->getPost('nueva_pw'));
		if ($password ===  $query->pw) {
			return json_encode("repeated_pw");
		}
		$data = [
			'active_password' => 0,
			'password' => $password
		];
		$result = $this->userModel->update(session()->id_user, $data);
		return json_encode($result);
	}

	public function activePassword()
	{
		$builder = $this->db->table('tbl_users');
		$builder->select('active_password,type_of_employee');
		$builder->where('id_user', session()->id_user);
		$data = $builder->get()->getResult();

		return json_encode($data);
	}

	public function my_Tickets()
	{

		$builder = $this->db->table('tbl_tickets_it a');
		$builder->select('a.*,b.id_user,b.name,b.surname');
		$builder->join('tbl_users b', 'a.id_user_it = b.id_user', 'left');
		$builder->where('a.id_user_it', session()->id_user);
		$data = $builder->get()->getResult();

		return json_encode($data);
	}

	public function ticketsAll()
	{
		$builder = $this->db->table('tbl_tickets_it a');
		$builder->select('a.*,b.id_user,b.name,b.surname');
		$builder->join('tbl_users b', 'a.id_user_it = b.id_user', 'left');
		$builder->limit(5000);
		$data = $builder->get()->getResult();
		return json_encode($data);
	}

	public function ticketsAllIt()
	{
		$dataActivitys = $this->activityModel->where('activate_status', 1)->findAll();
		return json_encode($dataActivitys);
	}

	public function deleteCostCenter($id_depto = null)
	{
		try {
			$data = ['active_status' => 0];
			$result = $this->deptoModel->update($id_depto, $data);
			return ($result) ? json_encode("ok") : json_encode("error");
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function userEdit()
	{
		try {
			$idUser = trim($this->request->getPost('id_user'));

			//$dataUser = $this->userModel->where('id_user', $id_user)->find();
			$query = $this->db->query("SELECT id_user, `name`, surname, second_surname, curp, nss, grado,
				email, date_admission, type_of_employee, id_area_operativa, id_departament, id_cost_center,
				id_job_position, vacation_days_total, years_worked, `password`, payroll_number, id_job_position,
				contracts
				FROM tbl_users 
				WHERE id_user = $idUser 
			AND active_status = 1")->getRow();
			$payrollNumber = $query->payroll_number;
			$query1 = $this->db->query("SELECT id_manager FROM tbl_assign_departments_to_managers_new WHERE active_status = 1 AND payroll_number = $payrollNumber")->getRow();
			$query2 = $this->db->query("SELECT id_manager FROM tbl_stationery_permissions WHERE active_status = 1 AND payroll_number = $payrollNumber")->getRow();
			$query3 = $this->db->query("SELECT id_manager FROM tbl_users_temporary WHERE active_status = 1 AND id_user = $idUser")->getRow();
			$query4 = $this->db->query("SELECT id_user_notificar AS id_manager FROM tbl_requisitions_notifica_copy WHERE active_status = 1 AND id_user = $idUser")->getRow();

			$data = [
				'info' => $query,
				'PyV' => $query1->id_manager ?? '',
				'Papeleria' => $query2->id_manager ?? '',
				'Contrato' => $query3->id_manager ?? '',
				'Requicicion' => $query4->id_manager ?? '',
			];
			return json_encode($data);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function updateUser()
	{
		try {
			$idUser = $this->request->getPost('id_usuario');
			$payrollNumber = $this->request->getPost('nomina_modal');
			$name = $this->request->getPost('nombre');
			$surname = $this->request->getPost('apellido_p');
			$secondSurname = $this->request->getPost('apellido_m');
			$email = $this->request->getPost('email');
			$curp = $this->request->getPost('curp');
			$nss = $this->request->getPost('nss');
			$dateAdmission = $this->request->getPost('fecha_admision');
			$typeOfEmployee = $this->request->getPost('tipo_empleado');
			$idDepartament = $this->request->getPost('depto');
			$idDepartament = $this->request->getPost('depto');
			$idDepartament = $this->request->getPost('depto');
			$idJobPosition = $this->request->getPost('puesto');
			$vacationDaysTotal = $this->request->getPost('dias_vacaciones');
			$yearsWorked = $this->request->getPost('anios_laborados');
			$password = $this->request->getPost('password');
			$idManagerPyV = $this->request->getPost('id_manager_PyV');
			$idManagerS = $this->request->getPost('id_manager_papeleria');
			$idManagerC = $this->request->getPost('id_manager_contrato');
			$idManagerR = $this->request->getPost('id_manager_requicicion');
			$areaOperative = $this->request->getPost('area_operative');
			$claceCost = $this->request->getPost('clace_cost');
			$grado = $this->request->getPost('grado');

			$data = [
				'name' => $name,
				'surname' => $surname,
				'second_surname' => $secondSurname,
				'payroll_number' => $payrollNumber,
				'email' => $email,
				'date_admission' => $dateAdmission,
				'type_of_employee' => $typeOfEmployee,
				'vacation_days_total' => $vacationDaysTotal,
				'years_worked' => $yearsWorked,
				'password' => $password,
				'id_area_operativa' => $areaOperative,
				'id_cost_center' => $claceCost,
				'id_departament' => $idDepartament,
				'id_job_position' => $idJobPosition,
				'grado' => $grado,
				'curp' => $curp,
				'nss' => $nss,
				'id_update' => session()->id_user,
				'update_at' => date("Y-m-d H:i:s"),
			];
			$this->db->transStart();

			$this->userModel->update($idUser, $data);
			if ($idManagerPyV != '') {
				$this->db->query("UPDATE tbl_assign_departments_to_managers_new SET id_manager = $idManagerPyV WHERE payroll_number = $payrollNumber AND active_status = 1");
			}
			if ($idManagerS != '') {
				$this->db->query("UPDATE tbl_stationery_permissions SET id_manager = $idManagerS WHERE payroll_number = $payrollNumber AND active_status = 1");
			}
			if ($idManagerC != '') {
				$this->db->query("UPDATE tbl_users_temporary SET id_manager = $idManagerC WHERE id_user = $idUser AND active_status = 1");
			}
			if ($idManagerR  != '') {
				$this->db->query("UPDATE tbl_requisitions_notifica_copy SET id_user_notificar = $idManagerR  WHERE id_user = $idUser AND active_status = 1");
			}

			$result = $this->db->transComplete();
			return json_encode($result);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function userDataCard()
	{
		$idUser = trim($this->request->getPost('id_user'));
		$data = $this->db->query("SELECT a.`name`, CONCAT(a.surname,' ',a.second_surname) AS apellidos, b.departament,
			a.payroll_number, a.curp, a.nss, c.job
			FROM tbl_users AS a 
			JOIN cat_departament AS b ON a.id_departament = b.id_depto
			JOIN cat_job_position As c ON a.id_job_position = c.id
		WHERE a.id_user = $idUser ")->getRow();
		return json_encode($data);
	}

	public function userDelete()
	{

		try {
			$id_user = trim($this->request->getPost('id_user'));
			$dataUser = $this->db->query("SELECT payroll_number FROM tbl_users WHERE id_user = $id_user")->getRow();

			$this->db->transStart();

			$payroll_number = $dataUser->payroll_number;
			$dataPersonal = $this->db->query("SELECT num_nomina FROM tbl_users_personal_data WHERE num_nomina = $payroll_number")->getRowArray();
			if ($dataPersonal) {
				$this->db->query("UPDATE tbl_users_personal_data SET active_status = 2 WHERE num_nomina = $payroll_number");
			}

			$permissions_new = $this->db->query("SELECT id FROM tbl_assign_departments_to_managers_new WHERE payroll_number = $payroll_number OR id_manager = $id_user OR id_director = $id_user")->getRow();
			if ($permissions_new) {
				$this->db->query("UPDATE tbl_assign_departments_to_managers_new SET active_status = 2 WHERE payroll_number = $payroll_number OR id_manager = $id_user OR id_director = $id_user");
			}

			$service = $this->db->query("SELECT id_person FROM tbl_stationery_permissions WHERE payroll_number = $payroll_number")->getRow();
			if ($service) {
				$this->db->query("UPDATE tbl_stationery_permissions SET active_status = 2 WHERE id_person = $service->id_person");
			}

			$reqisitions = $this->db->query("SELECT id_notifica FROM tbl_requisitions_notifica_copy WHERE id_user = $id_user")->getRow();
			if ($reqisitions) {
				$this->db->query("UPDATE tbl_requisitions_notifica_copy SET active_status = 2 WHERE id_notifica = $reqisitions->id_notifica");
			}

			$reqisitionsAcc = $this->db->query("SELECT ID,id_user FROM tbl_requisiciones_access WHERE id_user = $id_user")->getRow();
			//var_dump($reqisitionsAcc);
			if ($reqisitionsAcc) {
				//echo "RAFA: ". $reqisitionsAcc->ID;
				$this->db->query("UPDATE tbl_requisiciones_access SET `status` = 2 WHERE ID = $reqisitionsAcc->ID");
			}

			$accessTravelExpences = $this->db->query("SELECT id FROM tbl_assign_travel_expenses_manager WHERE id_user = $id_user")->getRow();
			if ($accessTravelExpences) {
				$this->db->query("UPDATE tbl_assign_travel_expenses_manager SET active_status = 2 WHERE id = $accessTravelExpences->id");
			}

			if ($this->db->query("SELECT TecnicoId FROM cat_ticket_tecnico WHERE TecnicoId = $id_user")->getRow()) {
				$this->db->query("UPDATE cat_ticket_tecnico SET Tecnico_Activo = 0 WHERE TecnicoId = $id_user");
				$this->db->query("UPDATE tbl_tickets_activity_manager SET active_status = 2 WHERE id_manager = $id_user");
			}

			$data = [
				'active_status' => 2,
				'id_deleted' => session()->id_user,
				'deleted_at' => date('Y-m-d H:i:s'),
			];
			$this->userModel->update($id_user, $data);

			$result = $this->db->transComplete();

			return ($result) ? json_encode($result) : json_encode("error");
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function my_activitys()
	{
		$dataActivitys = $this->activityModel->where('id_user', session()->id_user)->where('activate_status', 1)->findAll();

		return json_encode($dataActivitys);
	}

	public function deleteActivity()
	{
		$id_ticket = trim($this->request->getPost('id'));
		$date = date("Y-m-d H:i:s");
		$dateUpdate = [
			'deleted_at' => $date,
			'activate_status' => 2
		];
		$result = $this->activityModel->update($id_ticket, $dateUpdate);

		return ($result) ? json_encode(true) : json_encode(false);
	}

	public function editActivity()
	{
		$id_ticket = trim($this->request->getPost('id'));
		$activity = trim($this->request->getPost('actividad'));
		$date = date("Y-m-d H:i:s");
		$dateUpdate = [
			'edited_at' => $date,
			'activity' => $activity
		];
		$result = $this->activityModel->update($id_ticket, $dateUpdate);

		return ($result) ? json_encode(true) : json_encode(false);
	}

	public function suppliesDelete()
	{
		try {
			$id_folio = trim($this->request->getPost('id_folio'));
			$data = ['active_status' => 2];
			$result = $this->suppliesModel->update($id_folio, $data);
			$date = date("Y-m-d H:i:s");
			$data = [
				"id_user" => session()->id_user,
				"created_at" => $date,
				"id_folio" => $id_folio,
				"name" => session()->name . " " . session()->surname,
			];
			$this->suppliesDelete->insert($data);
			return ($result == true) ? json_encode($result) : json_encode("error");
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function insertEquipament()
	{
		try {
			$typeEquip = trim($this->request->getPost('tipo'));
			$marca = trim($this->request->getPost('marca'));
			$no_serial = trim($this->request->getPost('no_serie'));
			$model = trim($this->request->getPost('modelo'));
			$features = trim($this->request->getPost('caracteristicas'));
			$processorData = $this->request->getPost("procesador");
			$memoryData = $this->request->getPost("memoria");
			$hardDriveData = $this->request->getPost("disco_duro");
			$approximateCost = $this->request->getPost("costo_equipo");
			$dmf = $this->request->getPost("dmf");

			if ($this->db->query("SELECT id_equip, no_serial 
					FROM tbl_system_equip_inventory 
					WHERE active_status = 1 
					AND no_serial LIKE '$no_serial' 
					-- AND type_equip = $typeEquip
				")->getRow()) {
				return json_encode('Duplicado');
			}
			$id_ = $this->db->query("SELECT COUNT(id_equip) + 1 AS id_by_type FROM tbl_system_equip_inventory WHERE type_equip = $typeEquip")->getRow()->id_by_type;
			$insertData = [
				'id_by_type_equip' => $id_,
				'type_equip' => $typeEquip,
				'marca' => $marca,
				'no_serial' => $no_serial,
				'model' => $model,
				'processor_data' => $processorData,
				'memory_data' => $memoryData,
				'hard_drive_data' => $hardDriveData,
				'date_manofacture' => $dmf,
				'approximate_cost' => $approximateCost,
				'features' => $features,
				'status_equip' => 1,
				'created_at' => date('Y-m-d H:i:s'),
				'active_status' => 1,
				'id_created' => session()->id_user,
			];
			$insert = $this->equipamentModel->insert($insertData);
			$idEquip = $this->equipamentModel->insertID();
			$this->db->query("UPDATE tbl_system_equip_inventory AS a 
				SET a.label_equip = (
					SELECT CONCAT('WAL',(
						SELECT UPPER(b.code_txt) 
						FROM cat_system_equip_type AS b 
						WHERE a.type_equip = b.id),
					'-', LPAD(a.id_by_type_equip, 4, '0'))
				)
			WHERE a.id_equip = $idEquip ");
			return ($insert) ? json_encode(true) : json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function userData()
	{
		try {
			$payroll_number = trim($this->request->getPost('ID'));
			$id_user = trim($this->request->getPost('ID_U'));
			if ($payroll_number) {
				$data = $this->db->query("SELECT a.id_user ,a.payroll_number AS nomina, a.`name` AS nombre, a.surname AS apep, a.second_surname AS apem, b.departament AS departamento, 
					CONCAT(a.`name`,' ',a.surname,' ',a.second_surname) AS nombre_completo,
					c.job AS puesto, b.clave_depto AS costos, a.id_departament, CASE WHEN a.type_of_employee = 1 THEN 'ADMINISTRATIVO' WHEN a.type_of_employee = 2 												 THEN 'SINDICALIZADO' ELSE 'ERROR' END AS tipo
					FROM tbl_users AS a 
					LEFT JOIN cat_departament AS b ON a.id_departament = b.id_depto 
					LEFT JOIN cat_job_position AS c ON a.id_job_position = c.id 											
					WHERE a.payroll_number = $payroll_number 
				AND a.active_status = 1")->getRow();
			} else if ($id_user) {
				$data = $this->db->query("SELECT a.id_user ,a.payroll_number AS nomina, a.`name` AS nombre, a.surname AS apep, a.second_surname AS apem, b.departament AS departamento,
					CONCAT(a.`name`,' ',a.surname,' ',a.second_surname) AS nombre_completo,
					c.job AS puesto,b.clave_depto AS costos, a.id_departament, CASE WHEN a.type_of_employee = 1 THEN 'ADMINISTRATIVO' WHEN a.type_of_employee = 2 													THEN 'SINDICALIZADO' ELSE 'ERROR' END AS tipo
					FROM tbl_users AS a 
					LEFT JOIN cat_departament AS b ON a.id_departament = b.id_depto 
					LEFT JOIN cat_job_position AS c ON a.id_job_position = c.id 											
					WHERE a.id_user = $id_user 
				AND a.active_status = 1")->getRow();
			}
			return ($data) ? json_encode($data) : json_encode(false);
		} catch (GlobalException $e) {
			return json_encode(false);
		}
	}

	public function userDataNew()
	{
		try {
			$payroll_number = trim($this->request->getPost('payroll_number')) ?? null;
			$id_user = trim($this->request->getPost('id_user')) ?? null;

			$sqlPayroll = ($payroll_number) ? "AND a.payroll_number = $payroll_number" : "";
			$sqlUser = ($id_user) ? "AND a.id_user = $id_user" : "";

			$data = $this->db->query("SELECT
				a.id_user,
				a.payroll_number,
				a.id_departament,
				b.departament,
				CONCAT( a.`name`, ' ', a.surname, ' ', a.second_surname ) AS nombre_completo,
				a.id_job_position,
				c.job,
				a.id_cost_center,
				(SELECT ct1.clave_cost_center FROM cat_cost_center AS ct1 WHERE ct1.id_cost_center = a.id_cost_center) AS centro_costos,
				a.id_area_operativa,
				(SELECT ct2.area FROM cat_operational_area AS ct2 WHERE ct2.id_area = a.id_area_operativa) AS area_operativa,
				CASE
					WHEN a.type_of_employee = 1 THEN
					'ADMINISTRATIVO' 
					WHEN a.type_of_employee = 2 THEN
					'SINDICALIZADO' ELSE 'ERROR' 
				END AS tipo 
				FROM tbl_users AS a
				LEFT JOIN cat_departament AS b ON a.id_departament = b.id_depto
				LEFT JOIN cat_job_position AS c ON a.id_job_position = c.id 
			WHERE a.active_status = 1 $sqlPayroll $sqlUser")->getRow();

			return ($data) ? json_encode($data) : json_encode(false);
		} catch (GlobalException $e) {
			return json_encode(false);
		}
	}

	function insertRequestLoan()
	{
		try {
			$payrollNumber = $this->request->getPost('nomina');
			$idUser = $this->request->getPost('usuario');
			$arrayAmountEquip = $this->request->getPost('cantidad_equipo_');
			$arrayEquip = $this->request->getPost('equipo_');
			$arrayCosto = $this->request->getPost('costo_');

			$query = $this->db->query("SELECT CONCAT(`name`,' ',surname,' ',second_surname) AS user_name,
				id_area_operativa, id_departament, id_cost_center 
			FROM tbl_users WHERE active_status = 1 AND id_user = $idUser")->getRow();
			// aqui
			$dataInsert = [
				'request_status' => 1,
				'id_user' => $idUser,
				'payroll_number' => $payrollNumber,
				'user_name' => $query->user_name,
				'id_depto' => $query->id_departament,
				'id_area_operative' => $query->id_area_operativa,
				'id_cost_center' => $query->id_cost_center,
				'id_lend' => session()->id_user,
				'lend_at' => date("Y-m-d H:i:s"),
			];
			$this->db->transStart();

			$this->loanModel->insert($dataInsert);
			$idRequest = $this->db->insertID();

			for ($i = 0; $i < count($arrayEquip); $i++) {
				if (is_numeric($arrayEquip)) {
					$query = $this->db->query("SELECT product FROM tbl_system_inventory WHERE id_product = " . $arrayEquip[$i])->getRow();
					$equipoName = $query->product;
					$idEquip = $arrayEquip[$i];
				} else {
					$equipoName = $arrayEquip[$i];
					$idEquip = null;
				}

				$dataInsertItem = [
					'id_loan' => $idRequest,
					'id_equip' => $idEquip,
					'equip' => $equipoName,
					'amount' => $arrayAmountEquip[$i],
					'cost' => $arrayCosto[$i],
				];
				$this->loanModelItem->insert($dataInsertItem);
			}

			$result = $this->db->transComplete();
			return json_encode($result);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	function listRequestLoanALL()
	{
		$data = $this->db->query("SELECT a.id_loans AS folio, IF(a.request_status = 1,'En Prestamo','Recibida') AS estado,
				IF(a.request_status = 1,'warning','success') AS color,
				a.user_name, a.payroll_number AS nomina, b.departament, a.equip,
				DATE_FORMAT(a.lend_at,'%d/%m/%Y %H:%i') AS incio,
				IF(a.request_status = 1,'',DATE_FORMAT(a.reciving_at,'%d/%m/%Y %H:%i')) AS fin
			FROM tbl_system_loans AS a 
				JOIN cat_departament As b ON a.id_depto = b.id_depto
			WHERE a.active_status = 1
		ORDER BY a.id_loans DESC")->getResult();
		return ($data) ? json_encode($data) : json_encode(false);
	}

	function updateDateReturn()
	{
		try {
			$idRequest = $this->request->getPost('id_request');
			$data = [
				'request_status' => 2,
				'id_reciving' => session()->id_user,
				'reciving_at' => date("Y-m-d H:i:s"),
			];
			$this->loanModel->update($idRequest, $data);
			return json_encode(true);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	function deleteRequestLoan()
	{
		try {
			$idRequest = $this->request->getPost('id_folio');
			$data = [
				'id_deleted' => session()->id_user,
				'deleted_at' => date("Y-m-d H:i:s"),
				'active_status' => 2
			];
			$result = $this->loanModel->update($idRequest, $data);
			return ($result) ? json_encode(true) : json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function turnsByTypeUser()
	{
		$idUser = $this->request->getPost('id_user');
		$turns = $this->db->query("SELECT id, name_turn 
		FROM cat_turns WHERE type_of_employee IN (SELECT type_of_employee FROM tbl_users WHERE id_user = $idUser)
		AND active_status = 1")->getResult();
		return ($turns) ? json_encode($turns) : json_encode(false);
	}

	public function requestDataCollectionAllocation()
	{
		try {
			$rule = $this->request->getPost('rule');
			$idEquip = trim($this->request->getPost('id_equip'));
			$labelEquip = trim($this->request->getPost('label_equip'));
			$sqlId = ($idEquip != null) ? "AND no_serial = '$idEquip'" : '';
			$sqlLabel = ($labelEquip != null) ? "AND label_equip = '$labelEquip' " : '';

			$dataRequest = $this->db->query("SELECT label_equip, no_serial, id_equip, 
				UPPER(marca) AS marca, UPPER(model) AS modelo, features, features,
				status_equip
				FROM tbl_system_equip_inventory 
				WHERE active_status = 1
			$sqlId $sqlLabel")->getRow();

			$query = $this->db->query("SELECT a.id_request, a.id_user, CONCAT('192.1.' , a.IP_group , '.' , a.IP_number) AS ip, a.pc_user, a.pc_pw,
				(SELECT CONCAT(ct1.`name`,' ',ct1.surname,' ',ct1.second_surname) 
					FROM tbl_users AS ct1 
					WHERE a.id_user = ct1.id_user
				) AS user_name
				FROM tbl_system_equip_assignment AS a
				WHERE a.active_status = 1 
					AND a.id_equip = $dataRequest->id_equip
					AND a.collector_at IS NULL
			ORDER BY a.assigner_at DESC")->getRow();

			$data = ['Equip' => $dataRequest, 'Request' => $query];
			return json_encode($data);
		} catch (GlobalException $e) {
			return json_encode($e);
		}
	}

	public function equipamentData()
	{
		$id_equip = trim($this->request->getPost('id'));
		$data = $this->db->table('tbl_system_equip_inventory a')
			->select("a.id_equip, a.type_equip, a.marca, a.no_serial, a.model, a.features, a.status_equip, DATE_FORMAT(a.created_at,'%d/%m/%Y  |  %H:%i') AS created_at, b.type_product,
			a.label_equip,a.processor_data,a.memory_data,a.approximate_cost,a.hard_drive_data, a.date_manofacture,
			CASE 
				WHEN LOCATE('TB', a.hard_drive_data) > 0 THEN CAST(SUBSTRING(a.hard_drive_data, 1, LOCATE('TB', a.hard_drive_data) - 1) AS DECIMAL)
				WHEN LOCATE('GB', a.hard_drive_data) > 0 THEN CAST(SUBSTRING(a.hard_drive_data, 1, LOCATE('GB', a.hard_drive_data) - 1) AS DECIMAL)
				ELSE NULL
			END AS dato_numerico,
			CASE 
				WHEN LOCATE('TB', a.hard_drive_data) > 0 THEN 'TB'
				WHEN LOCATE('GB', a.hard_drive_data) > 0 THEN 'GB'
				ELSE NULL
			END AS unidad,
			CASE 
				WHEN LOCATE('SSD', a.hard_drive_data) > 0 THEN 'SSD'
				WHEN LOCATE('HDD', a.hard_drive_data) > 0 THEN 'HDD'
				ELSE NULL
			END AS tipo_de_disco,
			c.txt, c.color")
			->join('cat_system_equip_type b', 'a.type_equip = b.id')
			->join('cat_system_equip_status c', 'a.status_equip = c.id_equip_status')
			->where('id_equip', $id_equip)->get()->getRow();
		return ($data) ? json_encode($data) : json_encode(false);
	}

	public function equipamentDelivery()
	{
		// return json_encode(false);

		try {
			$typeProsses = $this->request->getPost("tipo_proceso");
			$idRequest = 0;
			$this->db->transStart();

			if ($typeProsses == 1) {
				$idEquip = $this->request->getPost("id_equipo");
				$idUser = $this->request->getPost("id_user");
				$ipGroup = $this->request->getPost("ip_segmento");
				$ipNumber = $this->request->getPost("ip_id");
				$sesionUser = $this->request->getPost("sesion_nombre");
				$sesionPw = $this->request->getPost("sesion_pw");
				$coment_asig = $this->request->getPost("coment_asig");
				$statusEquip = $this->request->getPost("condicion");

				$dataEquip = $this->db->query("SELECT no_serial, memory_data, hard_drive_data FROM tbl_system_equip_inventory WHERE id_equip = $idEquip")->getRow();
				$dataUser = $this->db->query("SELECT a.payroll_number, a.id_area_operativa, a.id_cost_center, a.id_departament, 
					(SELECT ct1.departament FROM cat_departament AS ct1 WHERE ct1.id_depto = a.id_departament) AS departament, a.id_job_position
				FROM tbl_users AS a WHERE a.id_user = $idUser")->getRow();
				$addIP = ($ipNumber != null) ? "192.1." . $ipGroup . "." . $ipNumber : "";
				$addUser = ($sesionUser != null) ? $sesionUser . ", " . $sesionPw : "";
				$addComent = (!empty($addIP) && !empty($addUser)) ? " (" . $addIP . ", " . $addUser . ")"  : "";

				$dataInsert = [
					'id_equip' => $idEquip,
					'status_equip' => $statusEquip,
					'serial_number' => $dataEquip->no_serial,
					'assing_memory_data' => $dataEquip->memory_data,
					'assing_hard_drive_data' => $dataEquip->hard_drive_data,
					'id_user' => $idUser,
					'payroll_number' => $dataUser->payroll_number,
					'id_area_operative' => $dataUser->id_area_operativa,
					'id_cost_center' => $dataUser->id_cost_center,
					'id_depto' => $dataUser->id_departament,
					'depto' => $dataUser->departament,
					'id_job' => $dataUser->id_job_position,
					'coment' => $coment_asig . $addComent,
					'IP_group' => $ipGroup,
					'IP_number' => $ipNumber,
					'pc_user' => $sesionUser,
					'pc_pw' => $sesionPw,
					'id_assigner' => session()->id_user,
					'assigner_at' => date('Y-m-d H:i:s'),
				];
				/* 
				if (session()->id_user == 1063) {
					return json_encode(false);
				} */
				$this->equipAssignmentModel->insert($dataInsert);
				$idRequest = $this->db->insertID();

				$dataUpdate = [
					'type_asignation' => 2,
					'status_equip' => 2,
				];
				$this->equipamentModel->update($idEquip, $dataUpdate);
			} else if ($typeProsses == 2) {
				$idEquipAnt = $this->request->getPost("id_equipo_ant");
				$idRequestAnt = $this->request->getPost("id_request_ant");
				$comentCollect = $this->request->getPost("coment_asig");
				$query = $this->db->query("SELECT hard_drive_data, memory_data, system_operative 
				FROM tbl_system_equip_inventory WHERE id_equip = $idEquipAnt ")->getRow();

				$dataUpdate = [
					'request_status' => 2,
					'collec_memory_data' => $query->memory_data,
					'collec_hard_drive_data' => $query->hard_drive_data,
					'collec_so' => $query->system_operative,
					'coment_collect' => $comentCollect,
					'id_collector' => session()->id_user,
					'collector_at' => date("Y-m-d H:i:s"),
				];

				$this->equipAssignmentModel->update($idRequestAnt, $dataUpdate);
				$this->db->query("UPDATE tbl_system_equip_inventory SET status_equip = 1 WHERE id_equip = $idEquipAnt ");

				$idEquip = $this->request->getPost("id_equipo_new");
				$idUser = $this->request->getPost("id_user");
				$ipGroup = $this->request->getPost("ip_segmento");
				$ipNumber = $this->request->getPost("ip_id");
				$sesionUser = $this->request->getPost("sesion_nombre");
				$sesionPw = $this->request->getPost("sesion_pw");
				$coment_asig = $this->request->getPost("coment_asig");
				$statusEquip = $this->request->getPost("condicion");

				$dataEquip = $this->db->query("SELECT no_serial, memory_data, hard_drive_data FROM tbl_system_equip_inventory WHERE id_equip = $idEquip")->getRow();
				$dataUser = $this->db->query("SELECT a.payroll_number, a.id_area_operativa, a.id_cost_center, a.id_departament, 
					(SELECT ct1.departament FROM cat_departament AS ct1 WHERE ct1.id_depto = a.id_departament) AS departament, a.id_job_position
				FROM tbl_users AS a WHERE a.id_user = $idUser")->getRow();
				$addIP = ($ipNumber != null) ? "192.1." . $ipGroup . "." . $ipNumber : "";
				$addUser = ($sesionUser != null) ? $sesionUser . ", " . $sesionPw : "";
				$addComent = (!empty($addIP) && !empty($addUser)) ? " (" . $addIP . ", " . $addUser . ")"  : "";

				$dataInsert = [
					'id_equip_renovation' => $idEquipAnt,
					'id_equip' => $idEquip,
					'status_equip' => $statusEquip,
					'serial_number' => $dataEquip->no_serial,
					'assing_memory_data' => $dataEquip->memory_data,
					'assing_hard_drive_data' => $dataEquip->hard_drive_data,
					'id_user' => $idUser,
					'payroll_number' => $dataUser->payroll_number,
					'id_area_operative' => $dataUser->id_area_operativa,
					'id_cost_center' => $dataUser->id_cost_center,
					'id_depto' => $dataUser->id_departament,
					'depto' => $dataUser->departament,
					'id_job' => $dataUser->id_job_position,
					'coment' => $coment_asig . $addComent,
					'IP_group' => $ipGroup,
					'IP_number' => $ipNumber,
					'pc_user' => $sesionUser,
					'pc_pw' => $sesionPw,
					'id_assigner' => session()->id_user,
					'assigner_at' => date('Y-m-d H:i:s'),
				];
				$this->equipAssignmentModel->insert($dataInsert);
				$idRequest = $this->db->insertID();

				$this->db->query("UPDATE tbl_system_equip_inventory SET status_equip = 2 WHERE id_equip = $idEquip ");
			} else if ($typeProsses == 3) {
				$coment = $this->request->getPost("coment_asig");
				$arrayEquip = $this->request->getPost("equipo_");
				$arraySupply = $this->request->getPost("suministro_");
				if ($arrayEquip) {
					for ($i = 0; $i < count($arrayEquip); $i++) {
						$data = explode(",", $arrayEquip[$i]);
						$idRequest = $data[0];
						$idEquip = $data[1];

						$query = $this->db->query("SELECT memory_data, hard_drive_data, system_operative FROM tbl_system_equip_inventory WHERE id_equip = $idEquip")->getRow();

						$updateDataRequest = [
							'request_status' => 2,
							'collec_memory_data' => $query->memory_data,
							'collec_hard_drive_data' => $query->hard_drive_data,
							'collec_so' => $query->system_operative,
							'coment_collect' => $coment,
							'id_collector' => session()->id_user,
							'collector_at' => date("Y-m-d H:i:s"),
						];
						$this->equipAssignmentModel->update($idRequest, $updateDataRequest);

						$updateDataEquip = ['status_equip' => 1];
						$this->equipamentModel->update($idEquip, $updateDataEquip);
					}
				}

				if ($arraySupply) {
					for ($i = 0; $i < count($arraySupply); $i++) {
						$data = explode(",", $arraySupply[$i]);
						$idRequest = $data[0];
						$idProduct = $data[1];
						$amountProduct = $data[2];
						$updateDataRequest = [
							'id_collect' => session()->id_user,
							'collect_at' => date("Y-m-d"),
						];
						$this->inventoryRequestModel->update($idRequest, $updateDataRequest);

						$insertDataIn = [
							'id_product' => $idProduct,
							'amount_in' => $amountProduct,
							'motive' => 'Recoleccion desde modulo de Equipos, Asignar equipos',
							'id_register' => session()->id_user,
							'created_at' => date("Y-m-d H:i:s"),
						];
						$this->inventoryInModel->insert($insertDataIn);
						$this->db->query("UPDATE tbl_system_inventory 
							SET amount = amount + $amountProduct 
						WHERE id_product = $idProduct");
					}
				}
				$idRequest = 'recolectados';
			} else if ($typeProsses == 4) {
				$coment = $this->request->getPost("coment_asig");
				$idEquip = $this->request->getPost("id_equipo");
				$idRequest = $this->request->getPost("id_request_ant");

				$query = $this->db->query("SELECT memory_data, hard_drive_data, system_operative 
					FROM tbl_system_equip_inventory WHERE id_equip = $idEquip")
					->getRow();

				$updateDataRequest = [
					'request_status' => 2,
					'collec_memory_data' => $query->memory_data,
					'collec_hard_drive_data' => $query->hard_drive_data,
					'collec_so' => $query->system_operative,
					'coment_collect' => $coment,
					'id_collector' => session()->id_user,
					'collector_at' => date("Y-m-d H:i:s"),
				];
				$this->equipAssignmentModel->update($idRequest, $updateDataRequest);

				$updateDataEquip = ['status_equip' => 1];
				$this->equipamentModel->update($idEquip, $updateDataEquip);
				$idRequest = 'recolectados';
			}

			// $aaa = $this->request->getPost("aaa");

			$result = $this->db->transComplete();
			return ($result) ? json_encode($idRequest) : json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	function listExistingPzEquip()
	{
		$typeEquip = $this->request->getPost('type_equip');
		$query = $this->db->query("SELECT DISTINCT processor_data FROM tbl_system_equip_inventory WHERE processor_data != '' AND type_equip = $typeEquip")->getResult();
		$query1 = $this->db->query("SELECT DISTINCT marca FROM tbl_system_equip_inventory WHERE marca != '' AND type_equip = $typeEquip")->getResult();
		$query2 = $this->db->query("SELECT DISTINCT model FROM tbl_system_equip_inventory WHERE model != '' AND type_equip = $typeEquip")->getResult();
		$data = ['processors' => $query, 'marcas' => $query1, 'model' => $query2];
		return json_encode($data);
	}

	function openPDFRequest($idRequestEncrupt = null)
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$this->db->query("SET lc_time_names = 'es_ES'");
		$query = $this->db->query(" SELECT LPAD(a.id_request, 5, '0') AS folio, depto AS departamento, 
				CONCAT(DATE_FORMAT(assigner_at, '%d - '), UPPER(MONTHNAME(assigner_at)), DATE_FORMAT(assigner_at, ' - %Y')) AS fecha_entrega,
				CONCAT('INDEFINIDO') AS vencimiento, CONCAT('INDUSTRIAL DE VALVULAS S.A. DE C.V.') AS empresa,
				b.marca, b.model AS modelo, a.serial_number AS no_serie, a.coment AS observaciones,
				CONCAT('$',b.approximate_cost) AS costo_aprox,
				a.status_equip AS condicion_equipo,
				(SELECT CONCAT( ct1.`name`, ' ', ct1.surname, ' ', ct1.second_surname ) FROM tbl_users AS ct1 WHERE ct1.id_user = a.id_user ) AS responsable,
				(SELECT ct3.type_product FROM cat_system_equip_type AS ct3 WHERE ct3.id = b.type_equip) AS tipo_equipo,
				b.label_equip AS etiqueta_equipo,
				(SELECT CONCAT( ct2.`name`, ' ', ct2.surname, ' ', ct2.second_surname ) FROM tbl_users AS ct2 WHERE ct2.id_user = a.id_assigner ) AS entrega
			FROM tbl_system_equip_assignment AS a 
				JOIN tbl_system_equip_inventory AS b ON a.id_equip = b.id_equip
		WHERE MD5(concat('$key',a.id_request))= '$idRequestEncrupt'")->getRow();
		$data = [
			"request" => $query,
		];

		$html2 = view('pdf/pdf_equip_responsive_asign', $data);
		ob_get_clean();
		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
		$html2pdf->pdf->SetTitle('Asignacion');
		$html2pdf->writeHTML($html2);
		ob_end_clean();
		$html2pdf->output('Responsiva_Asignacion_' . $query->folio . '.pdf', 'I');
	}

	public function equipamentReception()
	{
		$to_day = date('Y-m-d H:i:s');
		// $no_serial = trim($this->request->getPost('equipo_reco'));
		$status = trim($this->request->getPost('opcion_reco'));
		$coment_recep = trim($this->request->getPost('obs_reco'));
		$id_equip = $this->request->getPost('id_equip');
		$id_request = $this->request->getPost('id_request');
		// var_dump($id_equip, $id_request, $status, $coment_recep);
		$dataUpdate = [
			'coment_recep' => $coment_recep,
			'confir' => 0,
			'date_reception' => $to_day
		];
		$this->historyPeopleModel->update($id_request, $dataUpdate);
		$dataEquipUpdate = [
			'status' => $status
		];
		$statusUp = $this->equipamentModel->update($id_equip, $dataEquipUpdate);
		// equipamentModel = new systemInventoryEquipModel();
		return ($statusUp) ? json_encode(true) : json_encode(false);
	}

	public function listEquipamentALL()
	{
		// $data = $this->db->query("CALL equipamentALL")->getResult();
		$data = $this->db->query("SELECT a.id_equip, a.no_serial, c.txt, c.color, a.label_equip,
				UPPER(b.type_product) AS type_equip, UPPER(a.marca) AS marca, UPPER(a.model) AS modelo
			FROM tbl_system_equip_inventory AS a 
				JOIN cat_system_equip_type AS b ON a.type_equip = b.id
				JOIN cat_system_equip_status AS c ON a.status_equip = c.id_equip_status
			WHERE a.active_status = 1
		ORDER BY a.id_created DESC")->getResult();
		return ($data) ? json_encode($data) : json_encode(false);
	}

	public function equipamentHistoryById()
	{
		try {
			$idEquip = trim($this->request->getPost('id'));
			$data = $this->db->query("SELECT
				a.id_request AS folio,
				DATE_FORMAT( a.assigner_at, '%d/%m/%Y' ) AS fechaInicio,
				IF( a.collector_at IS NOT NULL, DATE_FORMAT( a.collector_at, '%d/%m/%Y' ), 'EN POSECIÓN' ) AS fechaFinal,
				CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS usuario,
				b.payroll_number AS nomina,
				c.departament AS departamento,
				d.job AS puesto,
				a.coment AS comentario 
				FROM tbl_system_equip_assignment AS a
					JOIN tbl_users AS b ON a.id_user = b.id_user
					JOIN cat_departament AS c ON b.id_departament = c.id_depto
					JOIN cat_job_position AS d ON b.id_job_position = d.id 
				WHERE a.id_equip = $idEquip
			ORDER BY a.assigner_at DESC;")->getResult();
			return ($data) ? json_encode($data) :  json_encode(false);
		} catch (\Exception $e) {
			return ('Ha ocurrido un error en el servidor ' . $e);
		}
	}

	public function equipamentHistoryByPayroll()
	{
		$ID_ = trim($this->request->getPost('ID_buscar'));
		// $data = $this->db->query("CALL equipamentHistoryByPayroll ($ID_)")->getResult();
		$data = $this->db->query("SELECT a.id_request AS folio, a.date_delivery AS fechaInicio, a.date_reception AS fechaFinal,
					b.marca AS marca, b.model AS modelo, c.type_product AS tipo, b.no_serial AS no_serie
			FROM tbl_system_equip_history_people AS a
				JOIN tbl_system_equip_inventory AS b ON a.id_equip = b.id_equip
			JOIN cat_system_equip_type AS c ON b.type = c.id
			WHERE a.payroll_number = $ID_
		ORDER BY a.date_delivery DESC")->getResult();

		return ($data) ? json_encode($data) : json_encode(false);
	}

	public function equipamentUpdate()
	{

		$id_equip = trim($this->request->getPost('folio_'));
		$status = trim($this->request->getPost('estado_'));
		$no_serial = trim($this->request->getPost('no_serie_'));
		$type = trim($this->request->getPost('tipo_'));
		$marca = trim($this->request->getPost('marca_'));
		$model = trim($this->request->getPost('modelo_'));
		$processor_data = trim($this->request->getPost('procesador_'));
		$memory_data = trim($this->request->getPost('memoria_'));
		$hard_drive_data = trim($this->request->getPost('disco_duro_'));
		$approximate_cost = trim($this->request->getPost('costo_equipo_'));
		$features = trim($this->request->getPost('caracteristicas_'));
		$dmf = trim($this->request->getPost('dmf_'));

		$updateData = [
			'type' => $type,
			'marca' => $marca,
			'no_serial' => $no_serial,
			'model' => $model,
			'features' => $features,
			'status' => $status,
			'processor_data' => $processor_data,
			'memory_data' => $memory_data,
			'hard_drive_data' => $hard_drive_data,
			'date_manofacture' => $dmf,
			'approximate_cost' => $approximate_cost,
			'id_updated' => session()->id_user,
			'updated_at' => date("Y-m-d H:i:s"),
		];
		$update = $this->equipamentModel->update($id_equip, $updateData);
		return ($update) ? json_encode(true) : json_encode(false);
	}

	public function equipAsigHisroty()
	{
		// $data = $this->db->query("CALL equipAsigHistory ()")->getResult();
		$data = $this->db->query("SELECT a.id_request AS folio, a.serial_number, b.model, DATE_FORMAT(a.assigner_at,'%d/%m/%Y %H:%i') AS assigner_at, DATE_FORMAT(a.collector_at,'%d/%m/%Y %H:%i') AS collector_at,
			CONCAT(c.`name`,' ',c.surname) AS user_name, a.payroll_number, b.label_equip, a.id_equip
			FROM tbl_system_equip_assignment a 
				JOIN tbl_system_equip_inventory b on a.id_equip = b.id_equip 
				JOIN tbl_users c on a.id_user = c.id_user
			WHERE a.active_status = 1 ORDER BY a.id_request DESC LIMIT 1500;")->getResult();
		return ($data) ? json_encode($data) : json_encode(false);

		return json_encode(false);
	}

	public function dataAsigUpdate()
	{
		$id_request = trim($this->request->getPost('folio_'));
		$confir = trim($this->request->getPost('estado_'));
		$confirUpdate = ['confir' => $confir];
		$update = $this->historyPeopleModel->update($id_request, $confirUpdate);
		$id_user = trim($this->request->getPost('id_user_'));

		if ($imageFile = $this->request->getFile('firma_')) {
			$binder =  '../public/images/firmas_users';

			$newName = $id_user . "_" . $imageFile->getClientName();
			$ext = $imageFile->getClientExtension();
			$type = $imageFile->getClientMimeType();
			$newName = $imageFile->getRandomName();
			$imageFile = $imageFile->move($binder,  $newName);
			$e_signature = $binder . "/" . $newName;
			$insertData = [
				'id_user' => $id_user,
				'e_signature' => $e_signature
			];
			$this->db->table('tbl_system_equip_signatures')->insert($insertData);
		}
		return ($update) ? json_encode(true) : json_encode(false);
	}

	public function dataAsig()
	{
		$id_request = trim($this->request->getPost('id'));
		$query = $this->db->query("SELECT a.id_request AS folio, a.no_serial AS id_equipo, a.payroll_number AS nomina, a.confir AS confirmar, a.id_user,
		b.`name` AS nombre, b.surname AS apep, b.second_surname AS apem,
		c.`name` As nombre1, c.surname AS apep1, c.second_surname AS apem1
		FROM tbl_system_equip_history_people AS a
		LEFT JOIN tbl_users AS b ON a.id_user = b.id_user
		LEFT JOIN tbl_users AS c ON a.id_user_delivery = c.id_user
		WHERE a.id_request = $id_request")->getRow();

		$query1 = $this->db->query("SELECT * FROM tbl_system_equip_signatures
		WHERE id_user =  $query->id_user")->getRow();

		$data = ['data' => $query, 'firma' => $query1];
		return ($data) ? json_encode($data) : json_encode(false);
	}

	public function xlsxReports()
	{
		// $data = json_decode(stripslashes($this->request->getPost('data')));
		$NombreArchivo = "repostes_Equipos.xlsx";
		$cont = 2;
		//  AND a.date_delivery BETWEEN '$data->fechaInicio' AND '$data->fechaFin'

		// $query = $this->db->query("CALL reportAsignateALL ('$data->fechaInicio','$data->fechaFin')")->getResult();
		$query = $this->db->query("SELECT
				a.label_equip, (SELECT ct1.type_product FROM cat_system_equip_type AS ct1 WHERE ct1.id = a.type_equip) AS equip,
				a.marca, a.model, 
				CAST(a.no_serial AS CHAR) AS no_serial,
				CASE 
					WHEN a.status_equip  = 1 THEN
						'ALMACENADO'
					WHEN a.status_equip  = 2 THEN
						'ASIGNADO'
					WHEN a.status_equip  = 3 THEN
						'REFACCIONES'
						WHEN a.status_equip  = 4 THEN
						'OBSOLETO'
					ELSE
						'ERROR'
				END As equip_status,
				(SELECT CONCAT(ct2.`name`,' ',ct2.surname,' ',ct2.second_surname) FROM tbl_users AS ct2 WHERE ct2.id_user = b.id_user) AS user_name,
				b.payroll_number,
				b.depto, 
				(SELECT ct3.area FROM cat_operational_area AS ct3 WHERE ct3.id_area = b.id_area_operative) AS area,
				DATE_FORMAT(assigner_at,'%d/%m/%Y %H:%i') As assigner,
				(SELECT CONCAT(ct4.`name`,' ',ct4.surname) FROM tbl_users AS ct4 WHERE ct4.id_user = b.id_assigner) AS user_assigner
			FROM tbl_system_equip_inventory AS a 
				LEFT JOIN tbl_system_equip_assignment AS b ON a.id_equip = b.id_equip AND b.active_status = 1 AND a.status_equip  = 2
			WHERE a.active_status = 1
				AND a.id_equip <> 1
		ORDER BY a.label_equip ASC")->getResult();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:L1');
		$spreadsheet->getActiveSheet();
		$sheet->setTitle("Asignacion de Equipos");

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


		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		// Determino ubicacion del texto
		$sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1:L1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		// color de celdas        
		$spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
		// font text por grupos
		$sheet->getStyle("A1:L1")->getFont()->setBold(true)
			->setName('Calibri')
			->setSize(11)
			->getColor()
			->setRGB('FFFFFF');


		$sheet->setCellValue('A1', 'ETIQUETA');
		$sheet->setCellValue('B1', 'TIPO DE EQUIPO');
		$sheet->setCellValue('C1', 'MARCA');
		$sheet->setCellValue('D1', 'MOELO');
		$sheet->setCellValue('E1', 'SERIE');
		$sheet->setCellValue('F1', 'ESTADO');
		$sheet->setCellValue('G1', 'RESPONSABLE');
		$sheet->setCellValue('H1', 'NOMINA');
		$sheet->setCellValue('I1', 'DEPARTAMENTO');
		$sheet->setCellValue('J1', 'AREA');
		$sheet->setCellValue('K1', 'FECHA ENTREGA');
		$sheet->setCellValue('L1', 'QUIEN ENTREGO');

		foreach ($query as $key => $value) {

			$sheet->setCellValue('A' . $cont, $value->label_equip);
			$sheet->setCellValue('B' . $cont, $value->equip);
			$sheet->setCellValue('C' . $cont, $value->marca);
			$sheet->setCellValue('D' . $cont, $value->model);
			$sheet->setCellValue('E' . $cont, strval($value->no_serial));
			$sheet->setCellValue('F' . $cont, $value->equip_status);
			$sheet->setCellValue('G' . $cont, $value->user_name);
			$sheet->setCellValue('H' . $cont, $value->payroll_number);
			$sheet->setCellValue('I' . $cont, $value->depto);
			$sheet->setCellValue('J' . $cont, $value->area);
			$sheet->setCellValue('K' . $cont, $value->assigner);
			$sheet->setCellValue('L' . $cont, $value->user_assigner);

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
		exit;;
	}

	public function pdfRequestEquip($id_request = null)
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$query = $this->db->query("SELECT a.*, b.`name`, b.surname, b.second_surname
		FROM tbl_system_equip_history_people AS a
		JOIN tbl_users AS b ON a.id_user_delivery = b.id_user           
		WHERE MD5(concat('" . $key . "',a.id_request))='" . $id_request . "'");
		$dataRequest =  $query->getRow();

		$query1 = $this->db->query("SELECT a.*, b.departament 
			FROM tbl_users AS a
			JOIN cat_departament AS b ON a.id_departament = b.id_depto
			WHERE a.id_user = $dataRequest->id_user ");
		$dataUsers =  $query1->getRow();

		$query2 = $this->db->query("SELECT a.*, b.type_product 
			FROM tbl_system_equip_inventory AS a
			JOIN cat_system_equip_type AS b ON a.type = b.id
        	WHERE a.id_equip = $dataRequest->id_equip");
		$dataEquip =  $query2->getRow();

		$query3 = $this->db->query("SELECT e_signature FROM tbl_system_equip_signatures
		WHERE id_user = $dataRequest->id_user");
		$dataFirma =  $query3->getRow();

		$data = [
			"request" => $dataRequest,
			"users" => $dataUsers,
			"equip" => $dataEquip,
			'firma' => $dataFirma
		];
		$html2 = view('pdf/pdf_system_equip', $data);
		$html = ob_get_clean();
		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
		$html2pdf->pdf->SetTitle('Solicitudes');
		$html2pdf->writeHTML($html2);
		ob_end_clean();
		$html2pdf->output('solicitudes_' . $id_request . '.pdf', 'I');
	}

	public function productXlsx()
	{
		$data = json_decode(stripslashes($this->request->getPost('data')));
		$fecha_inicio = $data->fecha_inicio;
		$fecha_fin = $data->fecha_fin;
		$NombreArchivo = "Reporte_" . $fecha_inicio . "_" . $fecha_fin . ".xlsx";
		if (intval($data->type) == 1) {
			$query = $this->db->query("SELECT a.id_in, b.product, a.amount_in, CONCAT(c.`name`,' ',	c.surname) AS user_register, a.created_at  FROM tbl_system_inventory_in AS a
			JOIN tbl_system_inventory AS b ON a.id_product = b.id_product JOIN tbl_users AS c ON a.id_register = c.id_user
			WHERE a.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin' AND a.active_status = 1 ORDER BY a.created_at DESC;")->getResult();

			$cont = 2;
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:F1');
			$sheet->setTitle("Entradas de Producto");


			$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

			// ANCHO DE CELDA
			$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
			$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
			$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(11);
			$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);

			//UBICACION DEL TEXTO
			$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
			$sheet->getStyle('A1:F1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

			//COLOR DE CELDAS
			$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

			// FONT-TEXT
			$sheet->getStyle("A1:F1")->getFont()->setBold(true)
				->setName('Calibri')
				->setSize(10)
				->getColor()
				->setRGB('FFFFFF');

			// TITULO DE CELDA
			$sheet->setCellValue('A1', 'FOLIO');
			$sheet->setCellValue('B1', 'PRODUCTO');
			$sheet->setCellValue('C1', 'CANTIDAD');
			$sheet->setCellValue('D1', 'REGISTRADO POR');
			$sheet->setCellValue('E1', 'FECHA');
			$sheet->setCellValue('F1', 'HORA');

			foreach ($query as $key => $value) {
				$sheet->setCellValue('A' . $cont, $value->id_in);
				$sheet->setCellValue('B' . $cont, $value->product);
				$sheet->setCellValue('C' . $cont, $value->amount_in);
				$sheet->setCellValue('D' . $cont, $value->user_register);
				$sheet->setCellValue('E' . $cont, date("d/m/Y", strtotime($value->created_at)));
				$sheet->setCellValue('F' . $cont, date("H:i:s", strtotime($value->created_at)));
				$cont++;
			}
		} else {
			$query = $this->db->query("SELECT a.id_request, a.payroll_number, a.`name`,a.depto, b.product, a.amount, CONCAT(c.`name`,' ',c.surname) AS user_deliver, a.created_at
			FROM tbl_system_inventory_request AS a JOIN tbl_system_inventory AS b ON a.id_product = b.id_product
			 JOIN tbl_users AS c ON a.id_deliver = c.id_user WHERE a.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin' AND a.active_status = 1 ORDER BY a.created_at DESC")->getResult();

			$cont = 2;
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:I1');
			$sheet->setTitle("Salidas de Producto");


			$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(20); // alto de fila

			// ANCHO DE CELDA
			$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);
			$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
			$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(45);
			$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(11);
			$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(11);

			//UBICACION DEL TEXTO
			$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
			$sheet->getStyle('A1:I1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

			//COLOR DE CELDAS
			$spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

			// FONT-TEXT
			$sheet->getStyle("A1:I1")->getFont()->setBold(true)
				->setName('Calibri')
				->setSize(10)
				->getColor()
				->setRGB('FFFFFF');

			// TITULO DE CELDA
			$sheet->setCellValue('A1', 'FOLIO');
			$sheet->setCellValue('B1', 'NOMINA');
			$sheet->setCellValue('C1', 'NOMBRE EMPLEADO');
			$sheet->setCellValue('D1', 'DEPARTAMENTO');
			$sheet->setCellValue('E1', 'PRODUCTO');
			$sheet->setCellValue('F1', 'CANTIDAD');
			$sheet->setCellValue('G1', 'ENTREGADO POR:');
			$sheet->setCellValue('H1', 'FEHA');
			$sheet->setCellValue('I1', 'HORA');

			foreach ($query as $key => $value) {
				$nomina = ($value->payroll_number != 0) ? $value->payroll_number : "";
				$sheet->setCellValue('A' . $cont, $value->id_request);
				$sheet->setCellValue('B' . $cont, $nomina);
				$sheet->setCellValue('C' . $cont, $value->name);
				$sheet->setCellValue('D' . $cont, $value->depto);
				$sheet->setCellValue('E' . $cont, $value->product);
				$sheet->setCellValue('F' . $cont, $value->amount);
				$sheet->setCellValue('G' . $cont, $value->user_deliver);
				$sheet->setCellValue('H' . $cont, date("d/m/Y", strtotime($value->created_at)));
				$sheet->setCellValue('I' . $cont, date("H:i:s", strtotime($value->created_at)));
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

	public function pdfTickets($id_tickets = null)
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$query = $this->db->query("SELECT a.*,
										c.departament AS depto,
                                        b.name,
                                        b.surname
                                        FROM
                                        tbl_tickets_it a
                                        LEFT JOIN tbl_users b ON a.id_user_it = b.id_user
										LEFT JOIN cat_departament c ON b.id_departament = c.id_depto
                                        WHERE
                                        MD5(concat('" . $key . "',id_ticket))='" . $id_tickets . "'");
		$dataRequest =  $query->getRow();
		$data = [
			"request" => $dataRequest
		];

		$html2 = view('pdf/pdf_tickets', $data);

		$html = ob_get_clean();

		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


		$html2pdf->pdf->SetTitle('Ticket');

		$html2pdf->writeHTML($html2);

		ob_end_clean();
		$html2pdf->output('tickets_' . $id_tickets . '.pdf', 'I');
	}

	public function pdfActivitys($id_activity = null)
	{
		//CIFRADO
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$query = $this->db->query("SELECT * FROM
                                        tbl_system_activitys_it
                                        WHERE
                                        MD5(concat('" . $key . "',id_activity))='" . $id_activity . "'");
		$dataActivity =  $query->getRow();
		$data = [
			"request" => $dataActivity
		];

		$html2 = view('pdf/pdf_activity', $data);

		$html = ob_get_clean();

		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


		$html2pdf->pdf->SetTitle('Actividades');

		$html2pdf->writeHTML($html2);

		ob_end_clean();
		$html2pdf->output('actividad_' . $id_activity . '.pdf', 'I');
	}

	public function emailNotificationToners($datainfo)
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
			$mail->Username = 'requisiciones@grupowalworth.com';
			// SMTP password (This is that emails' password (The email you created earlier) )
			$mail->Password = '2contodo';
			// TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
			$mail->Port = 587;

			//Recipients
			$mail->setFrom('requisiciones@grupowalworth.com', 'Notificación|Suministros');
			// Add a recipient
			$mail->addAddress('enoriega@walworth.com.mx', 'Edna Noriega');
			$mail->addCC('csanchez@walworth.com.mx', 'Cristopher Sanchez');
			//$mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
			// Name is optional
			//$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
			$mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
			//$mail->addCC('cc@example.com');

			$mail->addBCC('rcruz@walworth.com.mx');
			//Attachments (Ensure you link to available attachments on your server to avoid errors)
			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

			//Content
			$mail->isHTML(true);
			$email_template = view('notificaciones/suministros_toner', $datainfo);
			$mail->MsgHTML($email_template);                              // Set email format to HTML
			$mail->Subject =  'Notificación de Suministros';
			$mail->send();
			//echo 'Message has been sent';
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
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
			// Enable verbose debug output
			//$mail->SMTPDebug = 2;
			// Set mailer to use SMTP
			$mail->isSMTP();
			// Enable SMTP authentication
			$mail->SMTPAuth = false;
			// Specify main and backup SMTP servers
			$mail->Host = 'localhost';
			// SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
			$mail->Username = 'requisiciones@grupowalworth.com';
			// SMTP password (This is that emails' password (The email you created earlier) )
			$mail->Password = '2contodo';
			// TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
			$mail->Port = 587;

			//Recipients
			$mail->setFrom('requisiciones@grupowalworth.com', 'Notificación|Suministros');
			// Add a recipient
			$mail->addAddress('csanchez@walworth.com.mx', 'Cristopher Sanchez');
			$mail->addAddress('enoriega@walworth.com.mx', 'Edna Noriega');

			// Name is optional
			//$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
			$mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
			//$mail->addCC('cc@example.com');

			$mail->addBCC('rcruz@walworth.com.mx');
			//Attachments (Ensure you link to available attachments on your server to avoid errors)
			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

			//Content
			$mail->isHTML(true);
			$email_template = view('notificaciones/suministros', $data);
			$mail->MsgHTML($email_template);                              // Set email format to HTML
			$mail->Subject =  'Notificación de Suministros';
			$mail->send();
			//echo 'Message has been sent';
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}

	public function checkFromActive()
	{
		$id_form = $this->request->getPost("id");
		$view = false;
		$status = $this->db->query("SELECT active_status FROM tbl_active_form WHERE id_form = $id_form")->getRow();
		// if ($id_form == 1 && (session()->id_user == 1063 || session()->id_user == 90 || session()->id_user == 1156 || session()->id_user == 1)) {
		if ($id_form == 1 && (session()->id_user == 1063 || session()->id_user == 1178 || session()->id_user == 1283 || session()->id_user == 1)) {
			$view = true;
		}
		$data = ['status' => $status, 'view' => $view];
		return json_encode($data);
	}

	public function enableDisableForm()
	{
		$id_form = $this->request->getPost("id");
		$active_status = $this->request->getPost("active_status");
		$status = $this->db->query("UPDATE tbl_active_form SET active_status = $active_status WHERE id_form = $id_form");
		return ($status) ? json_encode(true) : json_encode(false);
	}

	public function viewRegisterEquipment()
	{
		$query = $this->db->query("SELECT id_user, CONCAT(`name`,' ',surname,' ',second_surname) AS nombre FROM tbl_users WHERE active_status = 1
		ORDER BY `name` ASC")->getResult();
		$data = ['data' => $query];
		return ($this->is_logged) ? view('system/equipment_v1', $data) : redirect()->to(site_url());
	}

	public function insertTeamsAssignUser()
	{
		try {
			return json_encode(true);
			$toDay = date('Y-m-d H:i:s');
			$idUserForm = $this->request->getPost('id_user');
			$payrollNumber = $this->request->getPost('num_nomina');
			$depto = $this->request->getPost('depto');
			$idDepto = $this->request->getPost('id_depto');
			$arrayTypeEquip = $this->request->getPost('tipo_equipo_');
			$arrayEquipModel = $this->request->getPost('equipo_model_');
			$arrayEquipBrand = $this->request->getPost('equipo_marca_');
			$arrayIpG = $this->request->getPost('equipo_ip_g_');
			$arrayIp = $this->request->getPost('equipo_ip_n_');
			$arrayPcUser = $this->request->getPost('equipo_usuario_');
			$arrayPcPasword = $this->request->getPost('equipo_pw_');
			$this->db->transStart();
			for ($i = 0; $i < count($arrayTypeEquip); $i++) {
				$insertDataEquip = [
					'type' => $arrayTypeEquip[$i],
					'marca' => $arrayEquipModel[$i],
					'model' => $arrayEquipBrand[$i],
					'type_asignation' => 2,
					'status' => 1,
					'created_at' => $toDay,
					'id_created' => session()->id_user,
				];

				$this->equipamentModel->insert($insertDataEquip);
				$idEquip = $this->db->insertID();

				$insertDataAsiggnament = [
					'id_equip' => $idEquip,
					'id_user' => $idUserForm,
					'payroll_number' => $payrollNumber,
					'id_depto' => $idDepto,
					'deptop' => $depto,
					'IP_group' => $arrayIpG[$i],
					'IP_number' => $arrayIp[$i],
					'pc_user' => $arrayPcUser[$i],
					'pc_pw' => $arrayPcPasword[$i],
					'id_assigner' => session()->id_user,
					'assigner_at' => $toDay,
				];
				$this->equipAssignmentModel->insert($insertDataAsiggnament);
			}
			$result = $this->db->transComplete();
			return json_encode($result);
		} catch (Exception $th) {
			return json_encode($th);
		}
	}

	function listEquipsStored()
	{
		//OR (active_status = 2 AND DATEDIFF (deleted_at, NOW()) <=3)

		$adminRule = (session()->id_user == 1063 || session()->id_user == 1)
			? ""
			: '';

		$option = $this->request->getPost("option");
		$query = null;
		if ($option) {
			$query = $this->db->query("SELECT label_equip 
			FROM tbl_system_equip_inventory 
			WHERE active_status = 1 
			AND status_equip = $option
		ORDER BY label_equip ASC")->getResult();
		}
		$query = $this->db->query("SELECT id_user, CONCAT(`name`,' ',surname,' ',second_surname) AS user_name 
			FROM tbl_users
			WHERE 
			$adminRule
			 id_user NOT IN (1226,710,1121,1327)			
		ORDER BY `name` ASC")->getResult();
		$data = ['lbl_equips' => $query, 'usuarios' => $query];
		return json_encode($data);
	}

	function listEquipsAcesoris()
	{
		$query = $this->db->query("SELECT id_equip, CONCAT(label_equip,' | ',model) AS equipo 
			FROM tbl_system_equip_inventory 
			WHERE active_status = 1 
			AND status_equip = 1 
			AND type_equip IN (
				SELECT id 
				FROM cat_system_equip_type 
				WHERE active_status = 1
				AND accesory = 1
			)
		ORDER BY label_equip ASC")->getResult();
		$data = ($query != null) ? $query : false;
		return json_encode($data);
	}

	function listRequestInventory()
	{
		$query =  $this->db->query("SELECT a.id_request, a.`name`, DATE_FORMAT(a.created_at,'%d/%m/%Y | %H:%i') AS created,
			(SELECT ct1.product FROM tbl_system_inventory AS ct1 WHERE ct1.id_product = a.id_product ) AS product
			FROM tbl_system_inventory_request AS a 
			WHERE a.active_status = 1 
				AND a.responsibility = 1
		")->getResult() ?? false;
		return json_encode($query);
	}

	function openPDFRequestInventory($idRequestEncrupt = null)
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$query = $this->db->query("SELECT LPAD(a.id_request, 5, '0') AS folio, depto AS departamento, 
				DATE_FORMAT(a.created_at, '%d / %m / %Y ') AS fecha_entrega,
				CONCAT('INDEFINIDO') AS vencimiento, CONCAT('INDUSTRIAL DE VALVULAS S.A. DE C.V.') AS empresa,
				UPPER(b.product) AS product, 
				CONCAT('$',FORMAT(b.cost_unit,2)) AS cost,
				a.amount,
				a.`name` AS responsable,
				(SELECT CONCAT( ct2.`name`, ' ', ct2.surname, ' ', ct2.second_surname ) FROM tbl_users AS ct2 WHERE ct2.id_user = a.id_deliver ) AS entrega
			FROM tbl_system_inventory_request AS a 
			LEFT JOIN tbl_system_inventory AS b ON b.id_product = a.id_product 
		WHERE MD5(concat('$key',a.id_request))= '$idRequestEncrupt'")->getRow();
		$data = [
			"request" => $query,
		];

		$html2 = view('pdf/pdf_equip_responsive_invt', $data);
		$html = ob_get_clean();
		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
		$html2pdf->pdf->SetTitle('suministro');
		$html2pdf->writeHTML($html2);
		ob_end_clean();
		$html2pdf->output('Responsiva_Suministro_' . $query->folio . '.pdf', 'I');
	}

	function listEquipByUserRecolet()
	{
		$idUser = $this->request->getPost('id_user');
		try {
			$query = $this->db->query("SELECT a.id_request, a.id_equip,
					b.label_equip, CONCAT(b.marca,' | ',b.model) AS datos,
					a.serial_number, b.approximate_cost, DATE_FORMAT(a.assigner_at,'%d/%m/%Y') AS entrega
				FROM tbl_system_equip_assignment AS a
					JOIN tbl_system_equip_inventory AS b ON a.id_equip = b.id_equip
				WHERE a.active_status = 1 
					AND a.collector_at IS NULL 
					AND a.id_user = $idUser")
				->getResult();

			$query1 = $this->db->query("SELECT a.id_request, a.id_product, a.amount, b.product, b.cost_unit, (a.amount * b.cost_unit) AS cost_total, 
					DATE_FORMAT(a.created_at,'%d/%m/%Y') AS fecha 
				FROM tbl_system_inventory_request AS a 
					JOIN tbl_system_inventory AS b ON a.id_product = b.id_product
				WHERE a.active_status = 1 
					AND a.responsibility = 1 
					AND collect_at IS NULL
					AND id_user = $idUser")
				->getResult();

			// $query2 = $this->db->query("")->getResult();
			$date = ['equipos' => $query, 'suministros' => $query1, /* 'prestamos' => $query2 */];
			return json_encode($date);
		} catch (Exception $th) {
			return json_encode($th);
		}
	}

	function openPDFRequestLoan($idRequestEncrupt = null)
	{
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		$query = $this->db->query("SELECT LPAD(a.id_loans, 5, '0') AS folio, a.id_loans, a.user_name AS responsable,
				(SELECT departament FROM cat_departament WHERE id_depto = a.id_depto) AS departamento,
				a.amount_equip,
				a.equip,
				CONCAT('$',a.costo) AS costo,
				DATE_FORMAT(a.lend_at,'%d/%m/%Y') AS entrega,
				DATE_FORMAT(a.reciving_at,'%d/%m/%Y') AS recepcion,
				(SELECT CONCAT(`name`,' ',surname,' ',second_surname) FROM tbl_users WHERE id_user = a.id_lend) AS entregado_por
			FROM tbl_system_loans AS a 
			WHERE MD5(concat('$key',a.id_loans))= '$idRequestEncrupt'")
			->getRow();
		echo "SELECT LPAD(a.id_loans, 5, '0') AS folio, a.id_loans, a.user_name AS responsable,
			(SELECT departament FROM cat_departament WHERE id_depto = a.id_depto) AS departamento,
			a.amount_equip,
			a.equip,
			CONCAT('$',a.costo) AS costo,
			DATE_FORMAT(a.lend_at,'%d/%m/%Y') AS entrega,
			DATE_FORMAT(a.reciving_at,'%d/%m/%Y') AS recepcion,
			(SELECT CONCAT(`name`,' ',surname,' ',second_surname) FROM tbl_users WHERE id_user = a.id_lend) AS entregado_por
		FROM tbl_system_loans AS a 
		WHERE MD5(concat('$key',a.id_loans))= '$idRequestEncrupt'";
		var_dump($query);
		$query1 = $this->db->query("SELECT equip, amount, CONCAT('$',cost) AS costo
			FROM tbl_system_loans_items
			WHERE active_status = 1 AND id_loan = " . $query->id_loans)
			->getResult();

		$data = [
			"request" => $query,
			"items" => $query1,
		];

		$html2 = view('pdf/pdf_equip_responsive_prestamo', $data);
		ob_get_clean();
		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
		$html2pdf->pdf->SetTitle('Prestamo
		');
		$html2pdf->writeHTML($html2);
		ob_end_clean();
		$html2pdf->output('Responsiva_prestamo_' . $query->folio . '.pdf', 'I');
	}

	function insertMaintenance()
	{
		$idUser = $this->request->getPost('id_user');
		$usuario = $this->request->getPost('usuario_mantto');
		$equipo = $this->request->getPost('equipo_mantto');
		$tipo2 = $this->request->getPost('tipo_mantto');
		$idEquipo = $this->request->getPost('id_equipo_mantto');
		$fechaMantto = $this->request->getPost('fecha_mantto');
		$comentario = $this->request->getPost('observaciones');
		$departamento = $this->request->getPost('departamento_mantto');
		$nombre_tecnico = $this->request->getPost('nombre_tecnico');
		$id_mantto = $this->request->getPost('id_mantto');


		if (empty($idEquipo) || empty($fechaMantto) || empty($comentario)) {
			return json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
		}

		// Validar que no exista un mantenimiento previo para el mismo equipo en la misma fecha
		$existing = $this->db->query("SELECT * FROM tbl_system_date_maintenance 
			WHERE id_equip = ? AND fecha_mantto = ?", [$idEquipo, $fechaMantto])->getRow();

		if ($existing) {
			return json_encode(['success' => false, 'message' => 'Ya existe un mantenimiento registrado para este Equipo en esta fecha.']);
		}
		switch ($tipo2) {
			case 'Preventivo':
				$tipo = 1; // Preventivo
				break;
			case 'Correctivo':
				$tipo = 2; // Correctivo
				break;
			default:
				$tipo = 1; // Por defecto, asignar Preventivo si no se especifica
				// Puedes lanzar un error o manejarlo de otra manera si es necesario
				break;
		}

		// Insertar el mantenimiento en la base de datos
		$data = [
			'id_equip' => $idEquipo,
			'id_user' => $idUser,
			'usuario' => $usuario,
			'status_mantto' => $tipo,
			'status_mantto2' => $tipo2,
			'equipo' => $equipo,
			'fecha_mantto' => $fechaMantto,
			'mantto_obsv' => $comentario,
			'id_user_created' => session()->id_user,
			'created_at' => date('Y-m-d H:i:s'),
			'departamento' => $departamento,
			'nombre_tecnico' => $nombre_tecnico,
		];

		$this->db->table('tbl_system_date_maintenance')->insert($data);

		return $this->response->setJSON([
			'success' => true,
			'message' => 'Mantenimiento registrado exitosamente.'
		]);
	}

	public function maintenanceData()
	{

		$request = service('request');

		$start = $request->getGet('start');
		$end = $request->getGet('end');

		// Opcional: convertir fechas si necesitas trabajar con ellas en formato Y-m-d
		$fechaInicio = date('Y-m-d', strtotime($start));
		$fechaFin = date('Y-m-d', strtotime($end));

		/* 	$query = $this->db->table('tbl_system_date_maintenance a')
			->select("a.id_mantto, a.fecha_mantto, a.mantto_obsv,a.status_mantto, c.label_equip, c.model, c.marca, LOWER(CONCAT(d.name, ' ', d.surname)) AS pc_user")
			->join('tbl_system_equip_assignment b', 'a.id_equip = b.id_equip')
			->join('tbl_system_equip_inventory c', 'b.id_equip = c.id_equip')
			->join('tbl_users d', 'b.id_user = d.id_user')
			->where('a.active_status', 1)
			->where('b.request_status', 1)
			->where('b.active_status', 1)
			->where('a.fecha_mantto >=', $fechaInicio)
			->where('a.fecha_mantto <=', $fechaFin)
			->orderBy('a.fecha_mantto', 'DESC')
			->get()
			->getResult(); */

		$query = $this->db->table('tbl_users d')
			->select("a.id_mantto, a.fecha_mantto, a.mantto_obsv, a.status_mantto,a.obsv_cancelation, c.label_equip, c.model, c.marca, LOWER(CONCAT(d.name, ' ', d.surname)) AS pc_user, a.nombre_tecnico")
			->join('tbl_system_equip_assignment b', 'd.id_user = b.id_user')
			->join('tbl_system_equip_inventory c', 'b.serial_number = c.no_serial')
			->join('tbl_system_date_maintenance a', 'a.id_equip = b.id_equip')
			->where('a.active_status', 1)
			->where('b.request_status', 1)
			->where('b.active_status', 1)
			->where('a.fecha_mantto >=', $fechaInicio)
			->where('a.fecha_mantto <=', $fechaFin)
			->orderBy('a.fecha_mantto', 'DESC')
			->get()
			->getResult();


		return $this->response->setJSON($query);
	}

	public function usersData()
	{
		$searchTerm = $this->request->getPost('search'); // Obtener término de búsqueda

		$builder = $this->db->table('tbl_system_equip_assignment a');
		$builder->select("a.id_equip as id, u.id_user, CONCAT(u.name, ' ', u.surname, ' - ', c.label_equip) AS text, c.label_equip, d.departament as departamento");
		$builder->join('tbl_users u', 'u.id_user = a.id_user');
		$builder->join('tbl_system_equip_inventory c', 'c.id_equip = a.id_equip');
		$builder->join('cat_departament d', 'd.id_depto = u.id_departament');
		$builder->groupStart();
		$builder->where("CONCAT(u.name, ' ', u.surname) LIKE '%$searchTerm%'");
		$builder->orLike('a.serial_number', $searchTerm);
		$builder->groupEnd();
		$builder->where('a.assing_memory_data !=', '');
		$builder->where('a.request_status', 1);
		$builder->where('u.active_status', 1);
		$builder->limit(10);
		// $builder->get()->getResultArray();

		// Formatear resultados para Select2
		$results = [];
		foreach ($builder->get()->getResultArray() as $row) {
			$results[] = [
				'id' => $row['id'],
				'text' => $row['text'],
				'id_user' => $row['id_user'],
				'label_equip' => $row['label_equip'],
				'departamento' => $row['departamento']
			];
		}

		return $this->response->setJSON($results);
	}

	public function updateMaintenance()
	{
		$id_mantto = $this->request->getPost('id_mantto');
		$fechaMantto = $this->request->getPost('fecha_mantto');
		$tipo2 = $this->request->getPost('tipo_mantto');
		$nombreTecnico = $this->request->getPost('nombre_tecnico');
		//$comentario = $this->request->getPost('observaciones');

		if (empty($id_mantto) || empty($fechaMantto) || empty($tipo2)) {
			return json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
		}

		switch ($tipo2) {
			case 'Preventivo':
				$tipo = 1; // Preventivo
				break;
			case 'Correctivo':
				$tipo = 2; // Correctivo
				break;

			default:
				$tipo = 0; // Otro
				break;
		}

		$existing = $this->db->query("SELECT * FROM tbl_system_date_maintenance 
	WHERE id_mantto = ? ", [$id_mantto])->getRow();

		if (!$existing) {
			return json_encode(['success' => false, 'message' => 'No se encontró el mantenimiento especificado.']);
		}

		// Preparar datos para insertar nuevo mantenimiento
		$data = [
			'id_equip' => $existing->id_equip,
			'id_user' => $existing->id_user,
			'usuario' => $existing->usuario,
			'status_mantto' => $tipo,
			'status_mantto2' => $tipo2,
			'fecha_mantto' => $fechaMantto,
			'mantto_obsv' => $this->request->getPost('observaciones') ?? '',
			'id_user_created' => session()->id_user,
			'created_at' => date('Y-m-d H:i:s'),
		];
		// Solo agrega 'nombre_tecnico' si tiene un valor válido
		if (!empty($nombreTecnico)) {
			$data['nombre_tecnico'] = $nombreTecnico;
		}

		// Insertar nuevo mantenimiento
		//$this->db->table('tbl_system_date_maintenance')->update($id_mantto, $data);

		// Actualizar registro anterior
		$this->db->table('tbl_system_date_maintenance')->where('id_mantto', $id_mantto)->update($data);



		return $this->response->setJSON([
			'success' => true,
			'message' => 'Mantenimiento cancelado'
		]);
	}
	public function cancelMaintenance()
	{

		$id_mantto = $this->request->getPost('id_mantto');
		$comentario = $this->request->getPost('obsv_mantto');

		if (empty($id_mantto) || empty($comentario)) {
			return json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
		}


		// Preparar datos para insertar nuevo mantenimiento
		$data = [

			'status_mantto' => 4,
			'status_mantto2' => "Cancelado",
			'obsv_cancelation' => $comentario,
			'id_user_cancel' => session()->id_user,
			'created_at' => date('Y-m-d H:i:s'),
			'active_status' => 1 // Marcar como cancelado
		];

		// Actualizar registro anterior
		$this->db->table('tbl_system_date_maintenance')->where('id_mantto', $id_mantto)->update($data);

		return $this->response->setJSON([
			'success' => true,
			'message' => 'Mantenimiento cancelado'
		]);
	}

	public function listMaintenance()
	{

		$query = $this->db->table('tbl_system_date_maintenance a')
			->select("
			a.id_mantto,
        CASE MONTH(a.fecha_mantto)
        WHEN 1 THEN 'Enero'
        WHEN 2 THEN 'Febrero'
        WHEN 3 THEN 'Marzo'
        WHEN 4 THEN 'Abril'
        WHEN 5 THEN 'Mayo'
        WHEN 6 THEN 'Junio'
        WHEN 7 THEN 'Julio'
        WHEN 8 THEN 'Agosto'
        WHEN 9 THEN 'Septiembre'
        WHEN 10 THEN 'Octubre'
        WHEN 11 THEN 'Noviembre'
        WHEN 12 THEN 'Diciembre'
    END AS mes,
        c.label_equip AS numero_inventario,
		c.model,
        CONCAT(d.name, ' ', d.surname) AS pc_user,
        a.departamento,
        DATE_FORMAT(a.fecha_mantto, '%d/%m/%Y') AS fecha_programada,
        a.status_mantto,
        a.nombre_tecnico AS tecnico_asignado,
		a.file_name,
		a.file_path
    ")
			->join('tbl_system_equip_assignment b', 'a.id_equip = b.id_equip')
			->join('tbl_system_equip_inventory c', 'b.serial_number = c.no_serial')
			->join('tbl_users d', 'b.id_user = d.id_user')
			->where('a.active_status', 1)
			->where('b.request_status', 1)
			->where('b.active_status', 1)
			->orderBy('a.fecha_mantto', 'ASC')  // Orden ascendente por fecha
			->get()
			->getResult();

		return $this->response->setJSON($query);
	}

	public function openPDFRequestMaintenance($id_mantto = null)
	{
		//$id_mantto = $this->request->getVar('id_mantto');
		$key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
		if (empty($id_mantto)) {
			return redirect()->back()->with('error', 'No se encontró el mantenimiento.');
		}



		$dataMantto = $this->db->query("SELECT 
									a.id_mantto, 
									a.fecha_mantto, 
									a.mantto_obsv, 
									a.status_mantto,
									a.status_mantto2,
									a.obsv_cancelation,
									a.nombre_tecnico,
									a.departamento, 
									a.created_at,
									c.label_equip, 
									c.model, 
									c.marca, 

									LOWER(CONCAT(d.name, ' ', d.surname)) AS pc_user
								FROM 
									tbl_users d
								JOIN 
									tbl_system_equip_assignment b ON d.id_user = b.id_user
								JOIN 
									tbl_system_equip_inventory c ON b.serial_number = c.no_serial
								JOIN 
									tbl_system_date_maintenance a ON a.id_equip = b.id_equip
								WHERE 
									a.active_status = 1
									AND b.request_status = 1
									AND b.active_status = 1
									AND  MD5(concat('" . $key . "',id_mantto))='" . $id_mantto . "'")->getRow();


		// Redirigir a la vista del PDF
		$data = [
			"request" => $dataMantto,

		];
		$html2 = view('pdf/sistemas_mantto', $data);
		$html = ob_get_clean();
		$html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
		$html2pdf->pdf->SetTitle('Mantenimiento');
		$html2pdf->writeHTML($html2);
		ob_end_clean();
		$html2pdf->output('mantenimiento_' . $id_mantto . '.pdf', 'I');
	}

	public  function subirPdfMantto()
	{
		$id_mantto = $this->request->getPost('id_mantto');
		$archivo = $this->request->getFile('pdf');
		$binder =  "../public/sistemas/$id_mantto";
		// Verificar si el directorio existe, si no, crearlo
		if (!is_dir($binder)) {
			mkdir($binder, 0755, true);
		}

		if ($archivo && $archivo->isValid()) {
			// Mover el archivo a la ubicación deseada
			$archivo->move($binder, $archivo->getName());

			// Aquí puedes guardar la información del archivo en la base de datos si es necesario
			// Por ejemplo, podrías guardar el nombre del archivo y la ruta en una tabla de tu base de datos
			$data = [
				'file_name' => $archivo->getName(),
				'file_path' => $binder . '/' . $archivo->getName(),
				'uploaded_at' => date('Y-m-d H:i:s'),
				'id_user_uploaded' => session()->id_user,
			];
			$this->db->table('tbl_system_date_maintenance')
         ->update($data, ['id_mantto' => $id_mantto]);  // specify column for WHERE
			// Retornar una respuesta JSON indicando que la carga fue exitosa

			return $this->response->setJSON([
				'success' => true,
				'message' => 'Archivo subido exitosamente'
			]);
		}

		return $this->response->setJSON([
			'success' => false,
			'message' => 'Error al subir el archivo'
		]);
	}
	public function deleteMaintenance(){
		$id_mantto = $this->request->getPost('id_mantto');
		
		if (empty($id_mantto)) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'ID de mantenimiento no válido'
			]);
		}

		// Eliminar el mantenimiento de la base de datos
		$this->db->table('tbl_system_date_maintenance')
			->where('id_mantto', $id_mantto)
			->update(['active_status' => 0]);

		return $this->response->setJSON([
			'success' => true,
			'message' => 'Mantenimiento eliminado exitosamente'
		]);
	}
}