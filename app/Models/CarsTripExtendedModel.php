<?php

namespace App\Models;

use CodeIgniter\Model;

class CarsTripExtendedModel extends Model
{
    protected $table      = 'tbl_cars_extended_trip';
    protected $primaryKey = 'id_trip_ex';

    protected $returnType = 'object';


    protected $allowedFields = [

        'id_trip_ex',
        "authorized",
        "id_request",
        "id_car",
        "star_date",
        'star_datetime',
        "end_datetime",
        'end_date'
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
}
