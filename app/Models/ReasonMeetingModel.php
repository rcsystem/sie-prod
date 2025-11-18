<?php

namespace App\Models;

use CodeIgniter\Model;

class ReasonMeetingModel extends Model
{
    protected $table      = 'cat_reason_for_the_meeting';
    protected $primaryKey = 'id_reason';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_reason',
        'reason_for_meeting',
        'active_status'
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

       
}
