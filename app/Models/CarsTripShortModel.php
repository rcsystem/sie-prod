<?php

namespace App\Models;

use CodeIgniter\Model;

class CarsTripShortModel extends Model
{
    protected $table      = 'tbl_cars_short_trip';
    protected $primaryKey = 'id_trip_sh';

    protected $returnType = 'object';


    protected $allowedFields = [

        'id_trip_sh',
        "authorized",
        "id_request",
        "id_car",
        "date",
        'star_time',
        "end_time"
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
}
