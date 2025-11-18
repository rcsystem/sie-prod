<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificaRequisitionsModel extends Model
{
    protected $table      = 'tbl_requisitions_notifica_copy';
    protected $primaryKey = 'id_notifica';
    protected $returnType = 'object';
    protected $allowedFields = [
        'id_notifica',
        'id_user',
        'id_user_notificar',
        'active_status',

    ];
}
