<?php

namespace App\Models;

use CodeIgniter\Model;

class ParkingUsersItemModel extends Model
{
    protected $table      = 'tbl_parking_users_items';
    protected $primaryKey = 'id_item';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_item',
        'id_user',
        'id_record',
        'num_tag',
        'type_vehicle',
        'model',
        'color',
        'placas',
        'date_expiration',
        'location_archive',
        'record_type',
        'id_depto',
        'status_authorize',
        'active_status',
        'id_created',
        'created_at',
        'id_deleted',
        'deleted_at',
    ];

    protected $useTimestamps = false;
}
