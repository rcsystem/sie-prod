<?php

namespace App\Models;

use CodeIgniter\Model;

class ContractsTempModel extends Model
{
    protected $table      = 'tbl_user_type_of_contract';
    protected $primaryKey = 'id_contract';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_contract',
        'id_user',
        'id_depto',
        'depto',
        'job_position',
        'option',
        'type_of_contract_ant',
        'type_of_contract',
        'id_manager',
        'date_reing',
        'date_of_new_entry',
        'date_expiration',
        'date_notification',
        'puesto',
        'tiempo',
        'firm',
        'cause_of_termination',
        'observations',
        'create_contract',
        'contract_status',
        'active_status',
        'direct_authorization',
        'id_direct_authorization',
        'direct_authorization_at',
        'id_delete',
        'delete_at',

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
