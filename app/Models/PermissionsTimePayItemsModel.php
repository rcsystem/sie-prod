<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionsTimePayItemsModel extends Model
{
    protected $table      = 'tbl_entry_and_exit_permits_time_pay_items';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item',
        'id_request',
        'id_user',
        'payroll_number',
        'id_turn',
        'type_pay',
        'day_to_pay',
        'hour_pay',
        'min_pay',
        'hour_in',
        'hour_out',
        'available_used_debit',
        'status_autorize',
        'id_manager_authorize',
        'manager_authorize_date',
        'active_status',
        'created_at',
        'id_delete',
        'delete_at',
        'id_update',
        'update_at',
    ];
}
