<?php

namespace App\Models;

use CodeIgniter\Model;

class LiberationRequestModel extends Model
{
    protected $table      = 'tbl_liberation_request';
    protected $primaryKey = 'id';

    protected $returnType = 'object';

    protected $allowedFields = [
        'employee_id',
        'request_status',
        'created_by',
        'status',
        'created_at',
        'completed_at',
        'payroll_type',
        'period',
        'phone_number',
        'company',
        'employee'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function calcularEstadoRequest($requestId)
{
    $items = $this->db->table('tbl_liberation_request_items')
        ->where('request_id', $requestId)
        ->where('status', 1)
        ->get()->getResultArray();

    $allSigned = true;
    $anySigned = false;

    foreach ($items as $it) {
        if ($it['signed'] || $it['owed_amount'] > 0) {
            $anySigned = true;
        } else {
            $allSigned = false;
        }
    }

    if ($allSigned) return 'FIRMADO';
    if ($anySigned) return 'EN PROGRESO';
    return 'PENDIENTE';
}

}
