<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDocumentModel extends Model
{
    protected $table      = 'tbl_users_document';
    protected $primaryKey = 'id_doc';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_doc',
        'id_datos',
        'num_nomina',
        'tipo_document',
        'nombre_original',
        'descripcion',
        'ubicacion',
        'created_at',
        'active_status',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
