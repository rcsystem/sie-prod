<?php

namespace App\Models;

use CodeIgniter\Model;

class TravelItemsModel extends Model
{
    protected $table      = 'tbl_travel_item';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';
    protected $allowedFields = [
       'id_item',
       'id_travel',
       'description',
       'monto',
       'monto_approve',
       'status',
       'document',
       'document_at',
       'created_at'
    ];

    protected $useTimestamps = false;
}
