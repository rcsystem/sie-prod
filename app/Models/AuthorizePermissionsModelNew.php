<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthorizePermissionsModelNew extends Model
{
    protected $table      = 'tbl_assign_departments_to_managers_new';
    protected $primaryKey = 'id';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id',
        'id_user',
        'payroll_number',
        'id_manager',
        'id_director',
        'amount_permissions',
        'director_permission',
        'active_status'
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
