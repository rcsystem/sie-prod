<?php

namespace App\Models;

use CodeIgniter\Model;

class CatLiberationItemModel extends Model
{
    protected $table      = 'cat_liberation_items';
    protected $primaryKey = 'id';

    protected $returnType = 'object';

    protected $allowedFields = [
        'id',
        'department_id',
        'name',
        'description',
        'status',
    ];

    protected $useTimestamps = false;
}
