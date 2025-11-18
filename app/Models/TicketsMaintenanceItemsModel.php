<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketsMaintenanceItemsModel extends Model
{
    protected $table      = 'tbl_tickets_maintenance_items';
    protected $primaryKey = 'id_item';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item',
        'id_order',
        'spare_part_order_number',
        'code_spare_part',
        'assigned_buyer_name',
        'estimated_delivery_date',
        'piece_quantity',
        'price_unit',
        'total_price',
        'id_date_star',
        'date_star',
        'time_star',
        'id_date_end',
        'date_end',
        'time_end',
        'active_status',
    ];
}
