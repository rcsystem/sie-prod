<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersTempModel extends Model
{
    protected $table      = 'tbl_users_temporary';
    protected $primaryKey = 'id_contract';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_contract',
        'id_user_created',
        'id_user',
        'id_manager',
        'create_contract',
        'type_of_employee',
        'active_status'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

   
}
