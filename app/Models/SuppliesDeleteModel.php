<?php

namespace App\Models;

use CodeIgniter\Model;

class suppliesDeleteModel extends Model
{
    protected $table      = 'tbl_system_suppliesDelete';
    protected $primaryKey = 'id_item';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_item',
        'id_user',
        'name',
        'id_folio',
        'created_at',
        'active_status'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    
   
}
