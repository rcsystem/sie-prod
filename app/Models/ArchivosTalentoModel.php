<?php

namespace App\Models;

use CodeIgniter\Model;

class ArchivosTalentoModel extends Model
{
    protected $table      = 'tbl_talent_payment_item_request';
    protected $primaryKey = 'id_item';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_item',
        'id_request',
        'file_ruta',
        'file_type',
        'active_status',
        'file_name',
        'firm_status'
    ];

    public function updateRequest($id_request, $type_request, $data)
    {
        $this->db->table('tbl_talent_payment_item_request')
            ->where('id_request', $id_request)
            ->where('file_type', $type_request)
            ->update($data);

        return $this->db->affectedRows() > 0; // Retorna true si se actualizó
    }

   
}
