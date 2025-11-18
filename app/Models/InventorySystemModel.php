<?php

namespace App\Models;

use CodeIgniter\Model;

class inventorySystemModel extends Model
{
    protected $table      = 'tbl_system_inventory';
    protected $primaryKey = 'id_product';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id_product',
        'product',
        'amount',
        'cost_unit',
        'requi_no',
        'min',
        'active_status'
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
