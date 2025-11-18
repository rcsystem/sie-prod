<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalExamVisualModel extends Model
{
    protected $table      = 'tbl_medical_exam_item_visual';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item',
        'id_request',
        'visual_acuity',
        'actve_status',
    ];
}
