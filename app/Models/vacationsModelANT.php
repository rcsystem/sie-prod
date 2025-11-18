<?php

namespace App\Models;

use CodeIgniter\Model;

class vacationsModel extends Model
{
    protected $table      = 'tbl_vacations';
    protected $primaryKey = 'id_vcns';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_vcns',
        'id_user',
        'nombre_solicitante',
        'id_a_cargo',
        'a_cargo',
        'fecha_registro',
        'tipo_empleado',
        'id_depto',
        'departamento',
        'num_nomina',
        'puesto',
        'fecha_ingreso',
        'num_dias_a_disfrutar',
        'dias_a_disfrutar_del',
        'dias_a_disfrutar_al',
        'regreso',
        'dias_restantes',
        'prima_vacacional',
        'user_authorizes',
        'estatus',
        'id_update',
        'update_at',
        'active_status',
    ];
}

class vacationsItemsModel extends Model
{
    protected $table      = 'tbl_vacations_items';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item',
        'id_vcns',
        'id_user',
        'id_depto',
        'date_vacation',
        'active_status',
        
    ];
}