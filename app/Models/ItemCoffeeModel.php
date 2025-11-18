<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemCoffeeModel extends Model
{
    protected $table      = 'tbl_coffe_items';
    protected $primaryKey = 'id_item_coffe';

    protected $returnType = 'object';


    protected $allowedFields = [
                                'id_item_coffe',
                                'id_coffe',
                                'product',
                                'active_status'
                                 ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';



    //protected $skipValidation = false;


   
}
