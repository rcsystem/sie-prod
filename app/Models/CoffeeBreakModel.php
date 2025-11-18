<?php

namespace App\Models;

use CodeIgniter\Model;

class CoffeeBreakModel extends Model
{
    protected $table      = 'tbl_coffee_break';
    protected $primaryKey = 'id_coffee';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_coffee',
        'id_user',
        'name',
        'depto',
        'payroll_number',
        'area_operativa',
        'position_job',
        'meeting_room',
        'reason_meeting',
        'date',
        'horario',
        'num_person',
        'observations',
        'created_at',
        'status',
        'active_status',
        'menu_especial',
        'date_authorize',
        'id_authorize',
        'reason_cancel',
        'cancel_at',
        
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

       
}
