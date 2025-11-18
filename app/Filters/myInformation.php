<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserPersonalDataModel;


class myInformation implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $this->personalDataModel = new UserPersonalDataModel();
        $payroll_number = session()->payroll_number;
        $query = $this->personalDataModel->select('*')->where('active_status', 1)->where('num_nomina', $payroll_number)->get()->getRow();
        if ($query == "") {
            return redirect()->route('encuesta/formulario');
        }
        

        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}