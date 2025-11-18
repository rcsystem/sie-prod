<?php

namespace App\Models;

use CodeIgniter\Model;

class VhModel extends Model
{
    protected $table      = 'tbl_vh_suministro';
    protected $primaryKey = 'id_partida';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_partida',
        'partida',
        'codigo',
        'tag_partida',
        'desc_breve',
        'desc_partida',
        'tipo_partida',
        'diametro_partida',
        'clase_partida',
        'figura_walworth',
        'tiempo_entrega',
        'precio_ofertado_mn',
        'created_at',
        'active_status'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';


    function searchItem($id_partida)
    {
        return $this->db->table('tbl_vh_suministro')->getWhere(['codigo' => $id_partida])->getResult();
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
