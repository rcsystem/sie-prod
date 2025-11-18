<?php

namespace App\Models;

use CodeIgniter\Model;

class ToursHSEModels extends Model
{
    protected $table      = 'tbl_ToursHSE_tours';
    protected $primaryKey = 'id_torus';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_torus',
        'active_status',
        'id_user_created',
        'date_created',
        'id_depto',
        'departament',
        'clave_depto',
        'use_epp',
        'use_uniform',
        'use_cel',
        'use_jewelry',
        'tied_hair',
        'order_clean',
        'unsafe_acts',
        'unsafe_conditions',
        'maintenance_work',
        'waste_management',
        'dangerous_works',
        'permiss_works',
        'personal_no_inval',
        'epp_no_inval',
        'qualification',
        'observation',
        'id_manager_confirm',
        'date_manager_confirm',
    ];
}

class incidentsHSEModels extends Model
{
    protected $table      = 'tbl_ToursHSE_incidents';
    protected $primaryKey = 'id_incidents';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_incidents',
        'active_status',
        'type',
        'require_retro',
        'requiere_follow',
        'id_user',
        'name_user',
        'id_depto',
        'departament',
        'clave_depto',
        'severity_level',
        'id_category',
        'description',
        'sanction_message',
        'id_created',
        'created_at',
        'id_response',
        'response_at',
        'response_opc',
        'respsonce_msj',
    ];
}

class imageEvidenceHSEModels extends Model
{
    protected $table      = 'tbl_ToursHSE_image_evidence';
    protected $primaryKey = 'id_item_evidence';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item_evidence',
        'active_status',
        'id_request',
        'type_request',
        'url_image',
    ];
}
