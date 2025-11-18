<?php

namespace App\Models;

use CodeIgniter\Model;

class LiberationRequestItemModel extends Model
{
    protected $table      = 'tbl_liberation_request_items';
    protected $primaryKey = 'id';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id',
        'request_id',
        'department_id',
        'item_id',
        'delivered',
        'notes',
        'signed',
        'signed_at',
        'status',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function addItem(array $datos)
    {
        // Revisar si ya existe ese depto para ese request
        $existe = $this->where([
            'request_id' => $datos['request_id'],
            'department_name' => $datos['department_name'],
            'item_id' => $datos['item_id'],
        ])->first();

        if ($existe) {
            // Si ya existe, mejor actualizamos
            return $this->update($existe['id'], $datos);
        } else {
            // Si no existe, lo insertamos
            return $this->insert($datos);
        }
    }

    public function calcularEstadoDepartamento($requestId, $departmentId)
{
    $items = $this->where([
        'request_id'     => $requestId,
        'department_id'  => $departmentId,
        'status'         => 1
    ])->findAll();

    $allSigned = true;
    foreach ($items as $it) {
    if (!$it->signed && $it->owed_amount <= 0) {
        $allSigned = false;
        break;
    }
}
    return $allSigned ? 'FIRMADO' : 'EN PROGRESO';
}

}
