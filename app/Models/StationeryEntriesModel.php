<?php

namespace App\Models;

use CodeIgniter\Model;

class StationeryEntriesModel extends Model
{
    protected $table      = 'tbl_stationery_entries';
    protected $primaryKey = 'id';

    protected $returnType = 'object';


    protected $allowedFields = [
                                'id',
                                'id_user',
                                'code_epicor',
                                'id_product',
                                'product',
                                'amount',
                                'observations',
                                'created_at',
                                'active_status',
                                'operation'
                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
   
}
