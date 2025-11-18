<?php

namespace App\Models;

use CodeIgniter\Model;

class LiberationDepartmentModel extends Model
{
    protected $table      = 'tbl_liberation_departments';
    protected $primaryKey = 'id';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id',
        'request_id',
        'department_id',
        'signed_at',
        'request_status',
        'status',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function addDepartment(array $datos)
    {
        $existe = $this->where([
            'request_id' => $datos['request_id'],
            'department_name' => $datos['department_name'],
        ])->first();

        if ($existe) {
            return $this->update($existe['id'], $datos);
        } else {
            return $this->insert($datos);
        }
    }
}
