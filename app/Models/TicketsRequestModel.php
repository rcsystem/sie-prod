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
        'active_status',
        'id_category',
        'id_activity',
        'Ticket_DepartamentoActividadId',
        'Ticket_TecnicoId',
        'Ticket_EstatusId',
        'Ticket_PrioridadId',
        'Ticket_Descripcion',
        'Ticket_Solucion',
        'qualify_service',
        'id_depto',
        'cost_center',
        'Ticket_UsuarioCreacionId',
        'Ticket_UsuarioCreacion',
        'Ticket_FechaCreacion',
        'process_id',
        'date_process',
        'conclud_id',
        'Ticket_FechaConcluido',
        'closed_id',
        'date_closed',
        'date_cancel',
        'motive_cancel',
        'cancel_id',
    ];
}
