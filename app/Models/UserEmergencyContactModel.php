<?php

namespace App\Models;

use CodeIgniter\Model;

class UserEmergencyContactModel extends Model
{
    protected $table      = 'tbl_users_emergency_contact';
    protected $primaryKey = 'id_emergencia';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_emergencia',
        'id_datos',
        'num_nomina',
        'contacto_emergencia',
        'tel_emergencia',
        'parentesco_emergencia',
        'created_at',
        'active_status',
        'active_status',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
