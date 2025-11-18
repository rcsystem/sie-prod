<?php
namespace App\Models;

use CodeIgniter\Model;

class ManagersModel extends Model
{
    protected $table      = 'tbl_assign_departments_to_managers_new';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'id_user',
        'payroll_number',
        'id_manager',
        'id_director',
        'active_status'
    ];
    protected $useTimestamps = false;
}