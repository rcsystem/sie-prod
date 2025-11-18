<?php

namespace App\Controllers\Sheets;
use App\Controllers\BaseController;
use App\Models\userModel;
use App\Models\rolesOperationModel;

class Sheets extends BaseController
{
    private $userModel = null;
    private $googleClient = null;

    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->googleClient = new \Google_Client();
        $this->googleClient->setApplicationName('Google Sheets and PHP');
        $this->googleClient->addScope([\Google_Service_Sheets :: SPREADSHEETS]);

        $this->googleClient->setClientId("381235139070-sg24jfpd5fp70vumsakqiu7orl2s2f72.apps.googleusercontent.com");
        $this->googleClient->setClientSecret("-7Ae5fEyTvQmCCcR7U8vilNV");
        $this->googleClient->setRedirectUri(base_url()."/auth/loginGoogle");
        $this->googleClient->addScope("email");
        $this->googleClient->addScope("profile");
        $this->userModel = new userModel();
        $this->rolesModel = new rolesOperationModel();
        $this->db = \Config\Database::connect();
        helper('secure_password');
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function index()
    {
        $data['googleAuth'] = $this->googleClient->createAuthUrl();
        return (!$this->is_logged) ? view('login', $data) : redirect()->to(base_url() . "/dashboard");
    }
}