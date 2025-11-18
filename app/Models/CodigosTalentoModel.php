<?php

namespace App\Models;

use CodeIgniter\Model;

class CodigosTalentoModel extends Model
{
    protected $table      = 'tbl_talent_payment_code';
    protected $primaryKey = 'id_code';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_code',
        'id_request',
        'code',
        'created_at',
        'expires_at',
        'signed',
        'active_status',
        'signed_at',
        'signed_by',

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
   

    
}
