<?php

namespace App\Models;

use CodeIgniter\Model;

class CoffeeFoodsModel extends Model
{
    protected $table      = 'tbl_coffee_menus_food';
    protected $primaryKey = 'id_food';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_food',
        'special_menu',
        'description',
        'created_at',
        'edit_at',
        'deleted_at'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
