<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalExamRequestModel extends Model
{
    protected $table      = 'tbl_medical_exam_request';
    protected $primaryKey = 'id_reuest';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_request',
        'id_user_attended',
        'payroll_number',
        'name',
        'id_depto',
        'depto',
        'job',
        'type_employe',
        'date_ant_request',
        'antiquity',
        'gender',
        'age',
        'shool',
        'civil_status',
        'exercise',
        'smoking',
        'alcoholism',
        'drug_addiction',
        'health',
        'motive',
        'icm',
        'has',
        'dm',
        'created_at',
        'delete_id',
        'deleted_at',
        'active_status',
    ];
}
