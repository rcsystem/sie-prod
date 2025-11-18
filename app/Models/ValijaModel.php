<?php

namespace App\Models;

use CodeIgniter\Model;

class ValijaModel extends Model
{
    protected $table      = 'tbl_valija_request';
    protected $primaryKey = 'id_valija';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_valija',
        'id_user',
        'user_name',
        'departament',
        'payroll_number',
        'type_of_employee',
        'job_position',
        'origin',
        'another_origin',
        'destination',
        'another_destination',
        'priority',
        'date',
        'time',
        'status',
        'observation',
        'created_at',
        'active_status',
        'id_answer',
        'answer_at',
        'area_operativa'
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
