<?php

namespace App\Models;

use CodeIgniter\Model;

class HseMenusSocialModel extends Model
{
    protected $table      = 'tbl_hse_menus';
    protected $primaryKey = 'id_social';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_social',
        'menus',
        'type_menu',
        'active_menu',
        'active_status',
        'created_datetime',
        'event_date',
        'id_user',
        
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    
   
}
