<?php
namespace App\Models;

use CodeIgniter\Model;

class StoreRawMaterialModel extends Model
{
    protected $table      = 'tbl_store_raw_material';
    protected $primaryKey = 'id_mp';

    protected $returnType = 'object';
   

    protected $allowedFields = [   
                                'id_mp',
                                'code',
                                'description',
                                'unit_of_measure',
                                'created_at',
                                'id_user',
                                'active_status',    
                                'code_image'    
                                ];

    protected $useTimestamps = false;
    //protected $createField = 'created_at';
    protected $updateField = 'update_at';


}