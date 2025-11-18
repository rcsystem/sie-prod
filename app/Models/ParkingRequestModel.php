<?php

namespace App\Models;

use CodeIgniter\Model;

class ParkingRequestModel extends Model
{
    protected $table      = 'tbl_parking_request';
    protected $primaryKey = 'id_request';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_request',
        'type_vehicle',
        'num_tag',
        'id_item',
        'location',
        'id_user',
        'id_depto',
        'departament',
        'date_in',
        'id_date_in',
        'date_out',
        'id_date_out',
        'obs',
        'created_at',
        'active_status',
    ];

    protected $useTimestamps = false;
}
