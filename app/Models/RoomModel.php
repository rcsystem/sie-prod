<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table      = 'cat_meeting_room';
    protected $primaryKey = 'id_room';

    protected $returnType = 'object';


    protected $allowedFields = [
                                'id_room',
                                'meeting_room',
                                'active_status'
                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';




    

   
}
