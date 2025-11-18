<?php

namespace App\Models;

use CodeIgniter\Model;

class HseGuestsModel extends Model
{
    protected $table      = 'tbl_hse_guests';
    protected $primaryKey = 'id_invitados';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_invitados',
        'id_user',
        'id_event',
        'nombre_invitado',
        'talla_invitado',
        'created_at',
        'active_status',

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';


    protected $skipValidation = false;


   
}
