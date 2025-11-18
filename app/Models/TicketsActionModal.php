<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketsActionModal extends Model
{
    protected $table      = 'tbl_tickets_accion';
    protected $primaryKey = 'AccionId';
    protected $returnType = 'object';


    protected $allowedFields = [
        'AccionId',
        'Accion_TicketId',
        'Accion_Comentario',
        'Accion_URL',
        'Accion_Nombre',
        'Accion_UsuarioCreacionId',
        'Accion_UsuarioCreacion',
        'Accion_FechaCreacion',
        'active_status',
    ];
}
