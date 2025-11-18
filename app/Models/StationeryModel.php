<?php

namespace App\Models;

use CodeIgniter\Model;

class StationeryModel extends Model
{
    protected $table      = 'cat_stationery_category';
    protected $primaryKey = 'id_cat';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_cat',
        'category',
        'active_status'
        
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

   
}
