<?php
namespace App\Models;

use CodeIgniter\Model;

class WpsModel extends Model
{
    protected $table      = 'tbl_wps_material_index';
    protected $primaryKey = 'id_indice';

    protected $returnType = 'array';
   

    protected $allowedFields = [
                                'id_indice',
                                'forma_producto',
                                'especificacion',
                                'grado',
                                'num_asmeix',
                                'grupo_asmeix',
                                'grupo_iso',
                                'resistencia_minima_ksi',
                                'composicion_nominal',
                                'active_status'
        
                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    //protected $createField = 'update_at';

}