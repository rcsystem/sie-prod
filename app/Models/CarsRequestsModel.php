<?php

namespace App\Models;

use CodeIgniter\Model;

class CarsRequestsModel extends Model
{
    protected $table      = 'tbl_cars_request';
    protected $primaryKey = 'id_request';

    protected $returnType = 'object';


    protected $allowedFields = [

        'id_request',
        "id_user",
        "payroll_number",
        "name",
        "depto",
        'area_operativa',
        "position_job",
        'model',
        'placa',
        'imagen',
        'type_trip',
        'status',
        'motive',
        'created_at',
        'active_status',
        'id_depto',
        'id_cars',
        'observation',
        'date_autorize',
        'id_authoriza',
        'date_answer',
        'id_answer',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
}
