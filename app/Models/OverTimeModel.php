<?php
namespace App\Models;

use CodeIgniter\Model;

class OverTimeModel extends Model
{
    protected $table      = 'tbl_qhse_overtime';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
   

    protected $allowedFields = [                       
        'id',
        'id_user',
        'name',
        'departament',
        'job',
        'payroll_number',
        'day_you_visit',
        'time_of_entry',
        'departure_time',
        'authorize',
        'id_authorize',
        'created_at',
        'updated_at',
        'active_status'
    ];

    protected $useTimestamps = false;
    //protected $createField = 'created_at';
    //protected $updateField = 'update_at';

    
   
    
}