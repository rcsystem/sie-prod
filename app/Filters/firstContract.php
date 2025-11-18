<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\contractsTempModel;

class firstContract implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $this->db = \Config\Database::connect();
         $request = \Config\Services::request();

       
            $id_user= $request->uri->getSegment(3);
               
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';


        $query = $this->db->query("SELECT *
                                    FROM
                                    tbl_user_type_of_contract
                                    WHERE MD5(concat('" . $key . "',id_user))='" . $id_user . "'");
        $data =  $query->getRow();
        
        return ($data == "") ? redirect()->to("https://sie.grupowalworth.com/usuarios/primer-contrato/".$id_user):"";
               
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}