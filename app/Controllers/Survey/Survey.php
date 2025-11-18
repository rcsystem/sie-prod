<?php

namespace App\Controllers\Survey;

use App\Controllers\BaseController;
use App\Models\userPersonalDataModel;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Survey extends BaseController
{
	public function __construct()
	{
		require_once APPPATH . '/Libraries/vendor/autoload.php';
		$this->personalDataModel = new userPersonalDataModel();
		$this->db = \Config\Database::connect();
		$this->is_logged = session()->is_logged ? true : false;
	}
	public function viewSurvey()
	{
		if ($this->is_logged) {
			if (session()->id_user == 1 || session()->id_user == 1063) {
				return view('survey/form');
			} else {
				$payrol = session()->payroll_number;
				$acta = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 3 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
				$personal = $this->personalDataModel->select('*')->where('active_status', 1)->where('num_nomina', $payrol)->get()->getRow();
				if ($acta == null && $personal == null) {
					return view('survey/form');
				} else if ($acta == null && $personal != null) {
					return view('survey/form_p2');
				} else {
					return view('user/my_info_user');
				}
			}
		} else {
			return redirect()->to(site_url());
		}
	}
	public function viewSurveyPT2()
	{
		if ($this->is_logged) {
			if (session()->id_user == 1 || session()->id_user == 1063) {
				return view('survey/form_p2');
			} else {
				$payrol = session()->payroll_number;
				$acta = $this->db->query("SELECT id_doc, id_datos, descripcion, nombre_original, created_at FROM tbl_users_document WHERE tipo_document = 3 AND num_nomina = $payrol  AND active_status = 1 ORDER BY created_at DESC LIMIT 1")->getRow();
				$personal = $this->personalDataModel->select('*')->where('active_status', 1)->where('num_nomina', $payrol)->get()->getRow();
				if ($acta == null && $personal == null) {
					return view('survey/form');
				} else if ($acta == null && $personal != null) {
					return view('survey/form_p2');
				} else {
					return view('user/my_info_user');
				}
			}
		} else {
			return redirect()->to(site_url());
		}
	}
}
