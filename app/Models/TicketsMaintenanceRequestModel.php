<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketsMaintenanceRequestModel extends Model
{
    protected $table      = 'tbl_tickets_maintenance_request';
    protected $primaryKey = 'id_order';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_order',
        'ticket_type',
        'id_tecnico',
        'id_jop_tecnico',
        'jop_tecnico',
        'id_activity',
        'id_fail',
        'id_priority',
        'cause_code',
        'id_machine',
        'id_area',
        'code_machine',
        'equip',
        'description',
        'work_done',
        'id_user',
        'payroll_number',
        'name_user',
        'id_depto',
        'name_depto',
        'id_manager_authorize',
        'id_user_process_star',
        'id_user_process_end',
        'id_manager_accept',
        'id_cancel',
        'part_request',
        'status',
        'created_at',
        'authotize_at',
        'process_star_at',
        'process_end_at',
        'accept_at',
        'cancel_at',
        'motive_cancel',
        'active_status'
    ];
}
