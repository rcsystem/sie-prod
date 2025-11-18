<?php

namespace App\Models;

use CodeIgniter\Model;

class StationeryItemsModel extends Model
{
    protected $table      = 'tbl_stationery_items';
    protected $primaryKey = 'id_request';

    protected $returnType = 'object';


    protected $allowedFields = [
                                'id_request_item',
                                'id_request',
                                'category',
                                'id_product',
                                'quantity',
                                'unit',
                                'created_at',
                                'image',
                                'active_status'
                            ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    
   
}
