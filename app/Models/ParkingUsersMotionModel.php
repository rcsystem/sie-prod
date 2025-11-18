<?php

namespace App\Models;

use CodeIgniter\Model;

class ParkingUsersMotionModel extends Model
{
    protected $table      = 'tbl_parking_users_motion';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id',
        'id_item',
        'motion',
        'active_status',
        'created_at',
    ];

    protected $useTimestamps = false;
}
