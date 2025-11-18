<?php

namespace App\Models;

use CodeIgniter\Model;

class almacenFacturasItemsModel extends Model
{
    protected $table      = 'tbl_almacen_facturas_servicios_items';
    protected $primaryKey = 'id_item';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_items',
        'id_request',
        'ruta_archivo',
        'active_status',
        'created_at'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

   
}
