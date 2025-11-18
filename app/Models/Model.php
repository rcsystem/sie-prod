<?php

namespace App\Models;

use CodeIgniter\Model;

class Model extends Model
{
    protected $table      = 'tbl_users';
    protected $primaryKey = 'id_user';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_user',
        'name',
        'surname',
        'second_surname',
        'email',
        'password',
        'payroll_number',
        'date_admission',
        'id_departament',
        'id_rol',
        'oauth_id',
        'profile_img',
        'updated_at',
        'created_at',
        'active_status'
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

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

    function isAlreadyRegister($email)
    {
        return ($this->db->table('tbl_users')->getWhere(['email' => $email])->getRowArray() > 0) ? true : false;
    }
    function updateUserData($userdata, $email)
    {
        $this->db->table('tbl_users')->where(['email' => $email])->update($userdata);
    }
    function insertUserData($userdata)
    {
        $this->db->table('tbl_users')->insert($userdata);
    }

   
}
