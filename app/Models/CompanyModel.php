<?php
namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table      = 'cat_company';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
   

    protected $allowedFields = [   
                                'id',
                                'name_company',
                                'active_status'
                                ];

    protected $useTimestamps = false;
    //protected $createField = 'created_at';
    protected $createField = 'update_at';

    /* protected $validationRules =[
    'nameUser' =>'required|alpha_space|min_length[3]|max_length[75]',
    'lastName' =>'required|alpha_space|min_length[3]|max_length[75]',
    'motherLastName'  =>'required|alpha_space|min_length[3]|max_length[75]',
    'dirUser' => 'required|min_length[8]|max_length[250]',
    'telUser'  => 'required|alpha_numeric_space|min_length[10]|max_length[10]',
    'emailUser'  => 'permit_empty|valid_email|max_length[85]',
    'passUser'  => 'required|alpha_space|min_length[3]|max_length[50]'
    ]; */

    /* protected $validationMessages = [
        'correo' =>[
            'valid_email' => 'Estimado usuario, debe ingresar un email valido'
        ]
    ]; */
    
    
   //protected $skipValidation = false;

}