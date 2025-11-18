<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudesLogisticaModel extends Model
{
    protected $table            = 'tbl_logistica_movimiento_inventario';
    protected $primaryKey       = 'id_solicitud';
    protected $allowedFields    = [
        'id_solicitud',
        'ruta_archivo',
        'concepto',
        'id_user',
        'usuario',
        'created_at',
        'active_status',
        'firmas_json',
        'updated_at'
    ];

    public function activos()
    {
        return $this->where('active_status', 1)->findAll();
    }
}
