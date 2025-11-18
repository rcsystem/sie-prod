<?php
namespace App\Models;

use CodeIgniter\Model;

class UserOverTimeModel extends Model
{
    protected $table      = 'tbl_qhse_user_overtime';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
   

    protected $allowedFields = [                       
                                'id',
                                'id_overtime',
                                'payroll_number',
                                'user',
                                'job',
                                'depto',
                                'active_status'
                                ];

    protected $useTimestamps = false;
    //protected $createField = 'created_at';
    //protected $updateField = 'update_at';

    

}