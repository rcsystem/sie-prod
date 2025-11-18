<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceInventoryModel extends Model
{
    protected $table      = 'tbl_finance_inventory';
    protected $primaryKey = 'id_activo';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_activo',
        'codigo',
        'descripcion',
        'marca',
        'capacidad',
        'modelo',
        'serie',
        'ubicacion',
        'area',
        'factura',
        'fecha',
        'proveedor',
        'revisado',
        'datos',
        'cuenta_con_factura',
        'ruta_factura',
        'status_activo',
        'imagen_qr',
        'created_at',
        'id_user_create',
        'id_user_update',
        'updated_at',
        'active_status',


    ];

    protected $useTimestamps = true;
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
