<?php

namespace App\Models;

use CodeIgniter\Model;

class almacenFacturasModel extends Model
{
    protected $table      = 'tbl_almacen_facturas_servicios';
    protected $primaryKey = 'id_request';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_request',
        'id_user',
        'usuario',
        'departamento',
        'obsv_factura',
        'solicitud',
        'created_at',
        'active_status',
        'estatus_activo',

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    //consulta para obtener todas las facturas de servicios por usuario
    public function getFacturasByUser($id_user)
    {
        return $this->where('id_user', $id_user)->findAll();
    }

    //consulta para obtener todas las facturas de servicios activas
    public function getActiveFacturas()
    {
        return $this->where('active_status', 1)->findAll();
    }
}
