<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryMobiliarioModel extends Model
{
    protected $table      = 'tbl_finance_inventory_mobiliario';
    protected $primaryKey = 'id_activo';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_activo',
        'codigo',
        'descripcion',
        'marca',
        'capacidad',
        'modelo',
        'serie',
        'ubicacion',
        'area',
        'factura',
        'fecha',
        'proveedor',
        'revisado',
        'datos',
        'cuenta_con_factura',
        'ruta_factura',
        'status_activo',
        'imagen_qr',
        'created_at',
        'id_user_create',
        'id_user_update',
        'updated_at',
        'active_status',


    ];

    protected $useTimestamps = true;
    protected $createField = 'created_at';
    protected $updateField = 'updated_at';

 

   
}
