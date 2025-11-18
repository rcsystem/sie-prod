<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersChildrenModel extends Model
{
    protected $table      = 'tbl_users_children';
    protected $primaryKey = 'id_son';

    protected $returnType = 'object';


    protected $allowedFields = [
                                'id_son',
                                'id_datos',
                                'num_nomina',
                                'nombre_hijo',
                                'edad_hijo',
                                'fecha_nacimiento',
                                'genero',
                                'active_status',
                                'created_at'
                            ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

       
}
