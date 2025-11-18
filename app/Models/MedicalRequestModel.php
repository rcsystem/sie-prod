<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicalRequestModel extends Model
{
    protected $table      = 'tbl_medical_request';
    protected $primaryKey = 'id_request';

    protected $returnType = 'object';
   
    protected $allowedFields = [
            'id_request',
            'user_generate',
            'id_user_generate',
            'user_name',
            'departament',
            'position_job',
            'type_permission',
            'motive',
            'date_out',
            'time_out',
            'date_star',
            'date_end',
            'system',
            'other_system',
            'diagnostic',
            'obs',
            'salary',
            'created_at',
        ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';

}