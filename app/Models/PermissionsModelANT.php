<?php

namespace App\Models;

use CodeIgniter\Model;

class permissionsModel extends Model
{
    protected $table      = 'tbl_entry_and_exit_permits';
    protected $primaryKey = 'id_es';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_es',
        'id_user',
        'id_usuario_autoriza',
        'user',
        'fecha_creacion',
        'tipo_empleado',
        'nombre_solicitante',
        'area_operativa',
        'centro_costo',
        'departamento',
        'num_nomina',
        'hora_salida',
        'fecha_salida',
        'hora_entrada',
        'fecha_entrada',
        'inasistencia_del',
        'inasistencia_al',
        'tipo_permiso',
        'id_turno',
        'turno_permiso',
        'goce_sueldo',
        'observaciones',
        'estatus',
        'num_permiso_mes',
        'acuenta_vacaciones',
        'confirm_hora_entrada',
        'id_update',
        'update_at',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
