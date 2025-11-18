<?php

namespace App\Models;

use CodeIgniter\Model;

class AccesosModel extends Model
{
    protected $table      = 'tbl_vigilancia_acceso_estacionamientos';
    protected $primaryKey = 'id_estacionamiento';

    protected $returnType = 'array';


    protected $allowedFields = [
                        'id_estacionamiento',
                        'nombre_usuario',
                        'num_nomina',
                        'scanner_at',
                        'id_usuario',
                        'id_rol',
                        'marbete',
                        'estado',
                        'active_status',

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

   

   
}
