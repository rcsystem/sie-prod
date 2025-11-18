<?php

namespace App\Models;

use CodeIgniter\Model;

class inventorySystemRequestModel extends Model
{
    protected $table      = 'tbl_system_inventory_request';
    protected $primaryKey = 'id_request';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id_request',
        'id_request_asignation_equip',
        'responsibility',
        'id_user',
        'payroll_number',
        'name',
        'depto',
        'id_product',
        'amount',
        'id_deliver',
        'created_at',
        'id_collect',
        'collect_at',
        'active_status'        
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
