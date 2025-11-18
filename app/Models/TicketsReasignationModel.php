<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketsReasignationModel extends Model
{
    protected $table      = 'tbl_tickets_reasignation';
    protected $primaryKey = 'ReasignacionId';
    protected $returnType = 'object';


    protected $allowedFields = [
        'ReasignacionId',
        'Reasignacion_TicketId',
        'Reasignacion_Fecha',
        'id_ant',
        'id_new',
        'id_change',
    ];
}
