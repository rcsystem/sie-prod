<?php

namespace App\Models;

use CodeIgniter\Model;

class StationeryRequestsModel extends Model
{
    protected $table      = 'tbl_stationery_requests';
    protected $primaryKey = 'id_request';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_request',
        'id_user',
        'payroll_number',
        'name',
        'email',
        'cost_center',
        'departament',
        'created_at',
        'active_status',
        'obs_stationery',
        'delivery_date',
        'request_status',
        'obs_request',
        'id_authorize',
        'authorize_at',
        'id_answer',
        'answer_at',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
   
}
