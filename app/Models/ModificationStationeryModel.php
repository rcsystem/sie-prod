<?php

namespace App\Models;

use CodeIgniter\Model;

class ModificationStationeryModel extends Model
{
    protected $table      = 'tbl_stationery_modification_parameter_users';
    protected $primaryKey = 'id';

    protected $returnType = 'object';


    protected $allowedFields = [
                                'id',
                                'id_user',
                                'id_product',
                                'product',
                                'maximum',
                                'minimum',
                                'created_at',
                                'active_status',
                                'unit_of_measurement'
                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
   
}
