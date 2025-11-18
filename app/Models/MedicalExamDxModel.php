<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalExamDxModel extends Model
{
    protected $table      = 'tbl_medical_exam_item_dx';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item',
        'id_request',
        'dx',
        'id_system',
        'actve_status',
    ];
}
