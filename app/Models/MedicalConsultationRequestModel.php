<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicalConsultationRequestModel extends Model
{
    protected $table      = 'tbl_medical_consultation_request';
    protected $primaryKey = 'id_request';

    protected $returnType = 'object';
   
    protected $allowedFields = [
        'id_request',
        'id_user_attended',
        'name_attended',
        'payroll_number',
        'id_depto',
        'depto',
        'job',
        'name',
        'gender',
        'age',
        'lvl_schooling',
        'manager_name',
        'specific_antiquity',
        'general_antiquity',
        'turn',
        'plant',
        'type_atention',
        'id_procedures',
        'id_system',
        'id_classification',
        'id_type_of_injury',
        'id_anatomical_area',
        'allergies',
        'diagnosis',
        'next_appointment',
        'phone',
        'obs',
        'status',
        'calification',
        'inability',
        'created_at',
        'update_at',
        'active_status',
        'common_motive',
        ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';

}