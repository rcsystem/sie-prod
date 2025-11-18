<?php
namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table      = 'tbl_system_activitys_it';
    protected $primaryKey = 'id_activity';

    protected $returnType = 'object';
   

    protected $allowedFields = [                       
                                'id_activity',
                                'id_user',
                                'user',
                                'payroll_number',
                                'departament',
                                'activity',
                                'complexity',
                                'homeoffice',
                                'created_at',
                                'activity_date',
                                'activate_status',
                                'edited_at',
                                'deleted_at'
                               ];

    protected $useTimestamps = false;
    

}