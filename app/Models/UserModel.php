<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'tbl_users';
    protected $primaryKey = 'id_user';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_user',
        'active_status',
        'name',
        'surname',
        'second_surname',
        'email',
        'password',
        'payroll_number',
        'id_area_operativa',
        'id_departament',
        'id_cost_center',
        'id_job_position',
        'date_admission',
        'company',
        'type_of_employee',
        'vacation_days_total',
        'years_worked',
        'id_rol',
        'oauth_id',
        'profile_img',
        'updated_at',
        'created_at',
        'active_password',
        'contracts',
        'firma',
        'user_registration',
        'grado',
        'curp',
        'nss',
        'id_update',
        'id_deleted',
        'deleted_at',
        'payrollnumber_image',
        'costcenter_image',
        'failed_attempts',
        'failed_attempt_time'
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
