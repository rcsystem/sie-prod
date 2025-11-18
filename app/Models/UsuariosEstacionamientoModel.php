<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosEstacionamientoModel extends Model
{
    protected $table      = 'tbl_vigilancia_usuarios_estacionamientos';
    protected $primaryKey = 'id_usuario';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_usuario',
        'id_user',
        'num_nomina',
        'nombre_usuario',
        'departamento',
        'id_rol',
        'marbete',
        'modelo',
        'color',
        'tipo',
        'placa',
        'ruta_imagen_qr',
        'created_at',
        'active_status',




    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
   

 

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
