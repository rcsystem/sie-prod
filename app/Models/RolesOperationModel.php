<?php
namespace App\Models;

use CodeIgniter\Model;

class RolesOperationModel extends Model
{
    protected $table      = 'tbl_rol_operation';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
   

    protected $allowedFields = [
                                'id',
                                'id_rol',
                                'id_operacion'     
                                ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

   
    
   //protected $skipValidation = false;

}