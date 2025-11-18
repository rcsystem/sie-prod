<?php

namespace App\Models;

use CodeIgniter\Model;

class TalentAuthorizeModel extends Model
{
    protected $table      = 'tbl_talent_authorize_request';
    protected $primaryKey = 'id_solicitud';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_solicitud',
        'id_solicitante',
        'solicitante',
        'id_autorizador',
        'autorizador',
        'active_status',

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';


    function buscarAutorizador($idUsuario)
    {

        return $this->select('id_autorizador')
                ->where('id_solicitante', $idUsuario)
                ->first()['id_autorizador'] ?? null;
    }
}
