<?php

namespace App\Models;

use CodeIgniter\Model;

class HseCarsModel extends Model
{
    protected $table      = 'tbl_hse_cars';
    protected $primaryKey = ' id_auto';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_auto',
        'id_hse_suppliers',
        'modelo',
        'color',
        'placas',
        'created_at',
        'active_status'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

   

   
}
