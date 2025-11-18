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
use App\Models\inventoryMobiliarioModel;
use App\Models\financeInventoryModel;
use App\Models\SolicitudAdmModel;


//use setasign\Fpdi\Tcpdf\Fpdi;

use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Fpdi;

class AdminPersonal extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->mobiliarioModel = new inventoryMobiliarioModel();
        $this->inventoryModel = new financeInventoryModel();
        $this->is_logged = session()->is_logged ? true : false;
        $this->db = \Config\Database::connect();
    }

    public function viewPercepcionesDeducciones()
    {
        return ($this->is_logged) ? view('finance/view_percepciones_deducciones') : redirect()->to(site_url());
    }
}