<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketsRequestModel extends Model
{
    protected $table      = 'tbl_tickets_request';
    protected $primaryKey = 'TicketId';
    protected $returnType = 'object';

    protected $allowedFields = [
        'TicketId',
        'id_activity',
        'Ticket_DepartamentoActividadId',
        'Ticket_TecnicoId',
        'Ticket_EstatusId',
        'Ticket_PrioridadId',
        'Ticket_Descripcion',
        'Ticket_FechaConcluido',
        'Ticket_Solucion',
        'Ticket_UsuarioCreacionId',
        'Ticket_UsuarioCreacion',
        'Ticket_FechaCreacion',
        'Ticket_UsuarioModificacionId',
        'Ticket_UsuarioModificacion',
        'Ticket_FechaModificacion',
        'date_process',
        'date_closed',
        'date_cancel',
        'active_status',
        'cost_center'

    ];
}
