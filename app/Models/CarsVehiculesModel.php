<?php

namespace App\Models;

use CodeIgniter\Model;

class CarsVehiculesModel extends Model
{
    protected $table      = 'tbl_cars_vehicles';
    protected $primaryKey = 'id_car';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_car',
        'model',
        'placa',
        'imagen',
        'created_at',
        'deleted_at'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
}
