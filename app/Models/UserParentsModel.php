<?php

namespace App\Models;

use CodeIgniter\Model;

class UserParentsModel extends Model
{
    protected $table      = 'tbl_users_parents';
    protected $primaryKey = 'id_padres';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_padres',
        'id_datos',
        'num_nomina',
        'nombre_padres',
        'fecha_nacimiento_padres',
        'genero_padres',
        'finado',
        'edad',
        'created_at',
        'active_status',

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
