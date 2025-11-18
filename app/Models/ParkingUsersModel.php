<?php

namespace App\Models;

use CodeIgniter\Model;

class ParkingUsersModel extends Model
{
    protected $table      = 'tbl_parking_users';
    protected $primaryKey = 'id_record';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_record',
        'num_tag',
        'id_user',
        'name',
        'id_depto',
        'depto',
        'ext',
        'qr_location',
        'active_status',
        'id_created',
        'created_at',
        'id_delet',
        'deleted_at',
    ];
}

class parkingUsersBicycleModel extends Model
{
    protected $table      = 'tbl_parking_users_bicycle';
    protected $primaryKey = 'id_record';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_record',
        'num_tag',
        'id_user',
        'name',
        'id_depto',
        'depto',
        'ext',
        'qr_location',
        'active_status',
        'id_created',
        'created_at',
        'id_delet',
        'deleted_at',
    ];
}

class parkingUsersN1Model extends Model
{
    protected $table      = 'tbl_parking_users_N1';
    protected $primaryKey = 'id_record';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_record',
        'num_tag',
        'id_user',
        'name',
        'id_depto',
        'depto',
        'ext',
        'qr_location',
        'active_status',
        'id_created',
        'created_at',
        'id_delet',
        'deleted_at',
    ];
}

class parkingUsersGardenModel extends Model
{
    protected $table      = 'tbl_parking_users_garden';
    protected $primaryKey = 'id_record';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_record',
        'num_tag',
        'id_user',
        'name',
        'id_depto',
        'depto',
        'ext',
        'qr_location',
        'active_status',
        'id_created',
        'created_at',
        'id_delet',
        'deleted_at',
    ];
}

class parkingUsersMotorcycleModel extends Model
{
    protected $table      = 'tbl_parking_users_motorcycle';
    protected $primaryKey = 'id_record';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_record',
        'num_tag',
        'id_user',
        'name',
        'id_depto',
        'depto',
        'ext',
        'qr_location',
        'active_status',
        'id_created',
        'created_at',
        'id_delet',
        'deleted_at',
    ];
}

class parkingUsersN3Model extends Model
{
    protected $table      = 'tbl_parking_users_N3';
    protected $primaryKey = 'id_record';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_record',
        'num_tag',
        'id_user',
        'name',
        'id_depto',
        'depto',
        'ext',
        'qr_location',
        'active_status',
        'id_created',
        'created_at',
        'id_delet',
        'deleted_at',
    ];
}