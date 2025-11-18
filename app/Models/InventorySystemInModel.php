<?php

namespace App\Models;

use CodeIgniter\Model;

class inventorySystemInModel extends Model
{
    protected $table      = 'tbl_system_inventory_in';
    protected $primaryKey = 'id_in';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id_in',
        'active_status',
        'id_product',
        'amount_in',
        'motive',
        'id_register',
        'epicor_code_produc',
        'epicor_code_requi',
        'cost_unit',
        'created_at',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
