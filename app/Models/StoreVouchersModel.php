<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreVouchersModel extends Model
{
    protected $table      = 'tbl_store_vouchers';
    protected $primaryKey = 'id_vouchers';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_vouchers',
        'id_user',
        'id_user_asign',
        'user',
        'payroll_number',
        'departament',
        'job_position',
        'cost_center',
        'addressee',
        'departures',
        'created_at',
        'estatus',
        'id_user_authorize',
        'authorize_datetime',
        'active_status',
        'type_transfer',
        'obs_request',
        'pw_security',

    ];

    protected $useTimestamps = false;
    //protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
