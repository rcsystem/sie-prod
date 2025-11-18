<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalConsultationItemsRequestModel extends Model
{
    protected $table      = 'tbl_medical_consultation_items_request';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item',
        'id_request',
        'id_medicine',
        'previous_amount',
        'given_amount',
        'created_at',
    ];
}
