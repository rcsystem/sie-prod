<?php

namespace App\Models;

use CodeIgniter\Model;

class ConceptosLogisticaModel extends Model
{
    protected $table            = 'cat_logistica_conceptos';
    protected $primaryKey       = 'id_concepto';
    protected $allowedFields    = ['nombre_concepto', 'active_status'];

    public function activos()
    {
        return $this->where('active_status', 1)->findAll();
    }
}
