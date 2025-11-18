<?php

namespace App\Models;

use CodeIgniter\Model;

class UserChildrenModel extends Model
{
    protected $table      = 'tbl_users_children';
    protected $primaryKey = 'id_son';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_son',
        'id_datos',
        'num_nomina',
        'nombre_hijo',
        'fecha_nacimiento',
        'edad_hijo',
        'genero',
        'created_at',
        'active_status',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
