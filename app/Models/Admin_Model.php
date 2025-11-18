<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin_Model extends Model
{
    protected $table      = 'cat_tipocambio';
    protected $primaryKey = 'TipoCambioId';

    protected $returnType = 'object';


    protected $allowedFields = [
        'TipoCambioId',
        'TipoCambio_TipoCambio',
        'TipoCambio_Fecha',
        'TipoCambio_Error',
        'active_status'
    ];

    protected $useTimestamps = false;

    function insertar_tipo_cambio($data)
    {


        return  $this->db->insert('cat_tipocambio', $data);
    }
}
