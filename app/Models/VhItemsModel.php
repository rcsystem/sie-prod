<?php

namespace App\Models;

use CodeIgniter\Model;

class VhItemsModel extends Model
{
    protected $table      = 'tbl_vh_ordenes_items';
    protected $primaryKey = 'id_items';

    protected $returnType = 'array';


    protected $allowedFields = [
                                "id_items",
                                "id_request",
                                "codigo",
                                "tipo",
                                "diametro",
                                "clase",
                                "tiempo",
                                "desc",
                                "desc_breve",
                                "figura",
                                "num_piezas",
                                "created_at",
                                "active_status",
                                "fecha_entrega",
                                "fecha_real_entrega",
                                "observaciones"
                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';


    function searchItems($id_request)
    {
        return $this->db->table('tbl_vh_ordenes_items')->getWhere(['id_request' => $id_request])->getResult();
    }

   /* function updateUserData($userdata, $email)
    {
        $this->db->table('tbl_users')->where(['email' => $email])->update($userdata);
    }
    function insertUserData($userdata)
    {
        $this->db->table('tbl_users')->insert($userdata);
    } */

   
}
