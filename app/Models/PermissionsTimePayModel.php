<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionsTimePayModel extends Model
{
    protected $table      = 'tbl_entry_and_exit_permits_time_pay';
    protected $primaryKey = 'id_request';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_request',
        'id_user',
        'payroll_number',
        'id_depto',
        'depto',
        'expected_date',
        'total_required',
        'total_pay',
        'used_y_n',
        /* 'status_autorize',
        'id_manager_authorize',
        'manager_authorize_date', */
        'active_status',
        'created_at',
    ];
}