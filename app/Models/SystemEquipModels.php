<?php

namespace App\Models;

use CodeIgniter\Model;

class systemEquipModels extends Model
{
    protected $table      = 'tbl_system_equip_assignment';
    protected $primaryKey = 'id_request';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_request',
        'active_status',
        'id_equip_renovation',
        'request_status',
        'id_equip',
        'status_equip',
        'serial_number',
        'assing_memory_data',
        'assing_hard_drive_data',
        'assing_so',
        'collec_memory_data',
        'collec_hard_drive_data',
        'collec_so',
        'id_user',
        'payroll_number',
        'id_area_operative',
        'id_cost_center',
        'id_depto',
        'depto',
        'id_job',
        'coment',
        'coment_collect',
        'IP_group',
        'IP_number',
        'pc_user',
        'pc_pw',
        'id_assigner',
        'assigner_at',
        'id_collector',
        'collector_at',
    ];
}

class inventoryEquipModel extends Model
{
    protected $table      = 'tbl_system_equip_inventory';
    protected $primaryKey = 'id_equip';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_equip',
        'active_status',
        'id_by_type_equip',
        'type_equip',
        'label_equip',
        'marca',
        'no_serial',
        'model',
        'processor_data',
        'memory_data',
        'hard_drive_data',
        'date_manofacture',
        'system_operative',
        'approximate_cost',
        'features',
        'type_asignation',
        'status_equip',
        'id_created',
        'created_at',
        'id_deleted',
        'deleted_at',
        'id_updated',
        'updated_at',
    ];
}
