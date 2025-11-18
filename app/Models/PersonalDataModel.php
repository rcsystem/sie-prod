<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonalDataModel extends Model
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
                                'genero',
                                'edad_usuario',
                                'fecha_nacimiento',
                                'estado_civil',
                                'contacto_emergencia',
                                'tel_emergencia',
                                'parentesco_emergencia',
                                'estado',
                                'municipio',
                                'colonia',
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
                                'codigo_postal'
                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

   
}
