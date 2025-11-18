<?php

namespace App\Models;

use CodeIgniter\Model;

class DiningRoomModel extends Model
{
    protected $table      = 'tbl_sg_dining_room';
    protected $primaryKey = 'id_event';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_event',
        'id_user',
        'date_event',
        'username',
        'active_status',
        'date_create'
        
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    
   
}
