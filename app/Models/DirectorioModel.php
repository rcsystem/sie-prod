<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectorioModel extends Model
{
    protected $table      = 'cat_directorio';
    protected $primaryKey = 'id_directorio';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_directorio',
        'id_user',
        'nombre',
        'apellido',
        'email',
        'extension',
        'departamento',
        'numero_directo',
        'id_user_created',
        'created_at',
        'directorio_status',
        'active_status',

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    //protected $skipValidation = false;

 
    
    public function getDirectorio()
    {
        return $this->where('active_status', 1)->findAll(); // Filtra solo registros con status = 1
    }

    function insertUserData($userdata)
    {
        $this->db->table('tbl_users')->insert($userdata);
    }

   
}
