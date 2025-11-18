<?php

namespace App\Models;

use CodeIgniter\Model;

class EppRequestModel extends Model
{
    protected $table      = 'tbl_hse_epp_requests';
    protected $primaryKey = 'id_request';

    protected $returnType = 'array';


    protected $allowedFields = [
                                'id_request',
                                'id_user',
                                'payroll_number',
                                'name',
                                'email',
                                'job_position',
                                'cost_center',
                                'departament',
                                'request_status',
                                'created_at',
                                'obs_request',
                                'obs_stationery',
                                'active_status',
                                'delivery_date',
                                'pw_security',
                                'id_user_delete',
                                'id_user_deliver',
                                'specify',
                                'option',
                                'code_store',
                                'qr_image'
                            ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    
    function isAlreadyRegister($email)
    {
        return ($this->db->table('tbl_users')->getWhere(['email' => $email])->getRowArray() > 0) ? true : false;
    }
    function updateUserData($userdata, $email)
    {
        $this->db->table('tbl_users')->where(['email' => $email])->update($userdata);
    }
    function insertUserData($userdata)
    {
        $this->db->table('tbl_users')->insert($userdata);
    }

   
}

class eppItemsModel extends Model
{
    protected $table      = 'tbl_hse_epp_items';
    protected $primaryKey = 'id_request_item';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_request_item',
        'active_status',
        'id_request',
        'category',
        'id_product',
        'product',
        'quantity',
        'unit',
        'cant_confirm',
        'coment',
        'created_at',
        'delivery_at',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';


    function isAlreadyRegister($email)
    {
        return ($this->db->table('tbl_users')->getWhere(['email' => $email])->getRowArray() > 0) ? true : false;
    }
    function updateUserData($userdata, $email)
    {
        $this->db->table('tbl_users')->where(['email' => $email])->update($userdata);
    }
    function insertUserData($userdata)
    {
        $this->db->table('tbl_users')->insert($userdata);
    }
}

class eppEntriesModel extends Model
{
    protected $table      = 'tbl_hse_epp_entries';
    protected $primaryKey = 'id';

    protected $returnType = 'array';


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

class eppDeparturesModel extends Model
{
    protected $table      = 'tbl_hse_epp_departures';
    protected $primaryKey = 'id';

    protected $returnType = 'array';


    protected $allowedFields = [
                                'id',
                                'id_user',
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

  

    function isAlreadyRegister($email)
    {
        return ($this->db->table('tbl_users')->getWhere(['email' => $email])->getRowArray() > 0) ? true : false;
    }
    function updateUserData($userdata, $email)
    {
        $this->db->table('tbl_users')->where(['email' => $email])->update($userdata);
    }
    function insertUserData($userdata)
    {
        $this->db->table('tbl_users')->insert($userdata);
    }

   
}

class eppModificationModel extends Model
{
    protected $table      = 'tbl_hse_modification_epp_users';
    protected $primaryKey = 'id';

    protected $returnType = 'array';


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