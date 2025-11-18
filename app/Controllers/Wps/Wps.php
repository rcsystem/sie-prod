<?php

/**
 * ARCHIVO MODULO SYSTEMAS
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

namespace App\Controllers\Wps;

use App\Controllers\BaseController;
use App\Models\userModel;
use App\Models\wpsModel;
use App\Models\wpsPermanentUnionsModel;

class Wps extends BaseController
{
	public function __construct()
	{
		$this->userModel = new userModel();
        $this->wpsModel = new wpsModel();
        $this->wpsUnionsModel = new wpsPermanentUnionsModel();

		$this->is_logged = session()->is_logged ? true : false;
	}

    public function baseMaterial(){
        $datas = $this->wpsModel->findAll();
        $data =["materialBase" =>$datas];
        return ($this->is_logged) ? view('wps/wps_base_material',$data) : redirect()->to(site_url());
    }
    public function permanentUnions(){
        $datas = $this->wpsUnionsModel->findAll();
        $data =["uniones" =>$datas];
        return ($this->is_logged) ? view('wps/wps_uniones',$data) : redirect()->to(site_url());
    }

	
}
