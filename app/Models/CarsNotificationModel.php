<?php

namespace App\Models;

use CodeIgniter\Model;

class CarsNotificationModel extends Model
{
    protected $table      = 'tbl_cars_notification';
    protected $primaryKey = 'id_notifica';

    protected $returnType = 'object';


    protected $allowedFields = [
                                'id_notifica',
                                'id_user',
                                'id_departament',
                                'id_user_manager',
                                'id_user_director',
                                'active_status'
                            ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';




    //protected $skipValidation = false;

   
}
