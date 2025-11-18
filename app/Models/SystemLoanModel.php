<?php

namespace App\Models;

use CodeIgniter\Model;

class systemLoanModel extends Model
{
    protected $table      = 'tbl_system_loans';
    protected $primaryKey = 'id_loans';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_loans',
        'active_status',
        'request_status',
        'id_user',
        'payroll_number',
        'user_name',
        'id_depto',
        'id_area_operative',
        'id_cost_center',
        'amount_equip',
        'id_equip',
        'equip',
        'obs',
        'id_lend',
        'lend_at',
        'id_reciving',
        'reciving_at',
        'id_deleted',
        'deleted_at',
    ];
}

class systemLoanModelItem extends Model
{
    protected $table      = 'tbl_system_loans_items';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item',
        'active_status',
        'id_loan',
        'id_equip',
        'equip',
        'amount',
        'cost',
    ];
}
