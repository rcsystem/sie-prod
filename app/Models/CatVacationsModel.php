<?php
namespace App\Models;

use CodeIgniter\Model;

class CatVacationsModel extends Model
{
    protected $table      = 'cat_vacation_days';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
   

    protected $allowedFields = [                       
                                'id',
                                'years_in_days',
                                'years',
                                'days',
                                'active_status'
                                ];

    protected $useTimestamps = false;
   

}