<?php

namespace App\Models;

use CodeIgniter\Model;

class StationeryPermissionsModel extends Model
{
    protected $table      = 'tbl_stationery_permissions';
    protected $primaryKey = 'id_person';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_person',
        'payroll_number',
        'id_manager',
        'id_director',
        'active_status'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    
   
}
