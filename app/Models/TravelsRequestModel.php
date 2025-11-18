<?php

namespace App\Models;

use CodeIgniter\Model;

class TravelsRequestModel extends Model
{
    protected $table      = 'tbl_travels';
    protected $primaryKey = 'id_travel';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_travel',
        'id_user',
        'user_name',
        'depto',
        'cost_center',
        'job_position',
        'payroll_number',
        'reason_for_travel',
        'estimated_budget',
        'estimated_budget_approve',
        'origin_of_trip',
        'trip_destination',
        'trip_start',
        'return_trip',
        'lodging_required',
        'car_rental',
        'preferred_hotel',
        'car_rental_name',
        'request_advance',
        'amount',
        'amount_approve',
        'if_doc',
        'observation',
        'request_status',
        'created_at',
        'deleted_at',
        'active_status',
        'advance_type',
        'return_time',
        'departure_time',
        'cancel',
        'cancel_at',
        'firma_user',
        'firma_manager',
        'firma_admin',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
