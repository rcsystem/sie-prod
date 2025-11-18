<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketsServiceModel extends Model
{
    protected $table      = 'tbl_tickets_service_request';
    protected $primaryKey = 'TicketId';
    protected $returnType = 'object';

    protected $allowedFields = [
        'TicketId',
        'active_status',
        'id_activity',
        'Ticket_TecnicoId',
        'Ticket_EstatusId',
        'Ticket_PrioridadId',
        'Ticket_Descripcion',
        'Ticket_Solucion',
        'Ticket_UsuarioCreacionId',
        'Ticket_UsuarioCreacion',
        'id_depto',
        'Ticket_FechaCreacion',
        'id_process',
        'id_conclud',
        'id_closed',
        'date_process',
        'date_conclud',
        'date_closed',
        'date_cancel',
        'motive_cancel',
        'id_cancel',
        'qualify_service',
        'cost_center'
    ];
}
