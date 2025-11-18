<?php

namespace App\Models;

use CodeIgniter\Model;

class PackerItemModel extends Model
{
    protected $table      = 'tbl_packer_item';
    protected $primaryKey = 'id_item';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_item',
        'id_request',
        'amount',
        'weight',
        'base',
        'height',
        'depth',
        'status',
        'created_at',
        'deleted_at'
    ];

    protected $useTimestamps = false;
}
