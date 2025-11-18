<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionsModel extends Model
{
    protected $table      = 'tbl_entry_and_exit_permits';
    protected $primaryKey = 'id_es';
    protected $returnType = 'array';


    protected $allowedFields = [
        'id_es',
        'id_user',
        'id_usuario_autoriza',
        'autorized_at',
        'user',
        'fecha_creacion',
        'tipo_empleado',
        'nombre_solicitante',
        'area_operativa',
        'centro_costo',
        'id_depto',
        'departamento',
        'num_nomina',
        'hora_salida',
        'fecha_salida',
        'hora_entrada',
        'fecha_entrada',
        'inasistencia_del',
        'inasistencia_al',
        'id_tipo_permiso',
        'tipo_permiso',
        'id_turno',
        'turno_permiso',
        'goce_sueldo',
        'observaciones',
        'estatus',
        'num_permiso_mes',
        'acuenta_vacaciones',
        'confirm_hora_entrada',
        'confirm_hora_salida',
        'id_pay_time',
        'pago_deuda',
        'id_pago_tiempo',
        'hora_permiso',
        'minuto_permiso',
        'hora_vigilancia',
        'minutos_vigilancia',
        'url_evidence',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}

class permissionsInasistenceModel extends Model
{
    protected $table      = 'tbl_entry_and_exit_permits_items';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item',
        'id_es',
        'id_user',
        'inasistencia_fecha',
        'estatus',
        'active_status',
    ];
}

class permissionsSpecialModel extends Model
{
    protected $table      = 'tbl_days_special_permiss';
    protected $primaryKey = 'id_day_festive';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_day_festive',
        'active_status',
        'enabled_status',
        'type_permiss',
        'day_permiss',
        'motive',
        'obs',
        'active_in',
        'active_out',
        'active_absence',
        'time_permis_h',
        'time_permis_i',
        'max_time',
        'id_created',
        'created_at',
        'id_deleted',
        'deleted_at',
    ];
}
