<?php

namespace App\Models;

use CodeIgniter\Model;

class StationeryInventoryModelVH extends Model 
{
    protected $table      = 'tbl_stationery_inventory_vh';
    protected $primaryKey = 'id_product';
    protected $returnType = 'object';


    protected $allowedFields = [
        'id_product',
        'created_user',
        'description_product',
        'stock_product',
        'id_cat',
        'stock_min',
        'stock_max',
        'created_at',
        'image_product',
        'active_status'
        
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

   
}
