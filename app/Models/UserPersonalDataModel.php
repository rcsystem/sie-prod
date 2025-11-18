<?php
namespace App\Models;

use CodeIgniter\Model;

class UserPersonalDataModel extends Model
{
    protected $table      = 'tbl_users_personal_data';
    protected $primaryKey = 'id_datos';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id_datos',
        'num_nomina',
        'nombre',
        'ape_paterno',
        'ape_materno',
        'edad_usuario',
        'genero',
        'fecha_nacimiento',
        'estado_civil',
        'estado',
        'curp',
        'rfc',
        'municipio',
        'colonia',
        'codigo_postal',
        'calle',
        'numero_exterior',
        'numero_interior',
        'nombre_conyuge',
        'edad_conyuge',
        'ocupacion_conyuge',
        'tel_conyuge',
        'escolaridad',
        'lic_ing',
        'diplomados',
        'cursos_externos',
        'created_at',
        'active_status',
        'fecha_ingreso',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

}