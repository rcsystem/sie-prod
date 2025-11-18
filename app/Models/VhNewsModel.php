<?php

namespace App\Models;

use CodeIgniter\Model;

class VhNewsModel extends Model
{
    protected $table      = 'tbl_vh_notificaciones';
    protected $primaryKey = 'id_notifica';

    protected $returnType = 'array';


    protected $allowedFields = [
                                'id_notifica',
                                'id_request',
                                'id_item',
                                'fecha_notifica',
                                'active_status'
                               ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

   
}
