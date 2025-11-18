<?php
namespace App\Models;

use CodeIgniter\Model;

class LiberationCommentModel extends Model
{
    protected $table      = 'tbl_liberation_comment';
    protected $primaryKey = 'id_comentario';

    protected $returnType = 'array';

    protected $allowedFields = [
        'request_id', 'department_name', 'comentario_html', 'id_user', 'created_at','active_status'
    ];

    public $useTimestamps = false;
    protected $createField = 'created_at';
    
}