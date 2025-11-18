<?php

namespace App\Models;

use CodeIgniter\Model;

class VhOrdenesModel extends Model
{
    protected $table      = 'tbl_vh_ordenes_compras';
    protected $primaryKey = 'id_request';

    protected $returnType = 'array';


    protected $allowedFields = [
                                "id_request",
                                "id_usuario",
                                "usuario",
                                "orden_compra",
                                "orden_status",
                                "created_at",
                                "fecha_formalizacion",
                                "fecha_estatus_trabajo",
                                "active_status"
                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';


     function requestAll()
    {
        return $this->db->table('tbl_vh_ordenes_compras')->getWhere(['active_status' => 1])->getResult();
    }

   

/*
    function updateUserData($userdata, $email)
    {
        $this->db->table('tbl_users')->where(['email' => $email])->update($userdata);
    }
    function insertUserData($userdata)
    {
        $this->db->table('tbl_users')->insert($userdata);
    } */

   
}
