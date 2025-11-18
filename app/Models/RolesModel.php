<?php
namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model
{
    protected $table      = 'tbl_roles';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
   

    protected $allowedFields = [
                                'id',
                                'id_rol',
                                'active_status'        
                                ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

   
    
   //protected $skipValidation = false;

}