<?php

namespace App\Models;

use CodeIgniter\Model;

class HseVolunteeringModel extends Model
{
    protected $table      = 'tbl_hse_volunteering_activity';
    protected $primaryKey = 'id_volunteering';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_volunteering',
        'id_user',
        'user_name',
        'tel_user',
        'payroll_number',
        'obs_volunteering',
        'activity',
        'departament',
        'job_position',
        'applicant',
        'active_status',
        'created_at',
        'type_event',
        'assistance',
        'tipo_evento',
        'img_insignia',
        'event_date',
        'id_user_delete',
        'delete_at',
        
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
  //  protected $updateField = 'updated_at';

    public function getListVolunteering($role)
    {
        return $this->where('type_event', $role)
                    ->where('active_status', 1)
                    ->findAll();
    }
   
}
