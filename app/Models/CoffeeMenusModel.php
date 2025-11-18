<?php

namespace App\Models;

use CodeIgniter\Model;

class CoffeeMenusModel extends Model
{
    protected $table      = 'tbl_coffee_menus';
    protected $primaryKey = 'special_menu';

    protected $returnType = 'object';


    protected $allowedFields = [
                                'special_menus',
                                'tittle_menu',
                                'imagen_menu',
                                'created_at',
                                'edit_at',
                                'deleted_at',
                                'active'

                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

       
}
