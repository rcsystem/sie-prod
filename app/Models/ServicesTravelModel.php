<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicesTravelModel extends Model
{
    protected $table      = 'tbl_services_request_travel';
    protected $primaryKey = 'id_request_travel';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_request_travel',
        'active_status',
        'user_name',
        'id_user',
        'payroll_number',
        'id_depto',
        'id_operative_area',
        'clave_cost_center',
        'type_travel',
        'id_country',
        'other_grade',
        'id_grade_level',
        'money_daily_for_grade',
        'days_to_travel',
        'day_star_travel',
        'day_end_travel',
        'location_travel_star',
        'location_travel_end',
        'need_plane',
        'time_plane_go',
        'time_plane_go_back',
        'obs',
        'total_money',
        'divisa_money',
        'change_to_mxn',
        'card_confirm_money',
        'verification_money',
        'request_status',
        'verification_status',
        'created_at',
        'id_autoriced',
        'autoriced_at',
        'id_canceled',
        'canceled_at',
        'motive_cacel',
        'id_deleted',
        'deleted_at',
    ];
}

class servicesExpensModel extends Model
{
    protected $table      = 'tbl_services_request_expenses';
    protected $primaryKey = 'id_request_expenses';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_request_expenses',
        'active_status',
        'user_name',
        'id_user',
        'payroll_number',
        'id_depto',
        'id_operative_area',
        'clave_cost_center',
        'days_to_expenses',
        'day_star_expenses',
        'day_end_expenses',
        'obs',
        'total_money',
        'divisa_money',
        'change_to_mxn',
        'card_confirm_money',
        'verification_money',
        'request_status',
        'verification_status',
        'created_at',
        'id_autoriced',
        'autoriced_at',
        'id_canceled',
        'canceled_at',
        'motive_cacel',
        'id_deleted',
        'deleted_at',
    ];
}

class servicesAccountModel extends Model
{
    protected $table      = 'tbl_services_account_status';
    protected $primaryKey = 'id_account_status';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_account_status',
        'active_status',
        'id_request',
        'type',
        'id_user',
        'tag',
        'date_transaction',
        'location_transaction',
        'amount',
        'divisa',
        'amount_mxn',
        'rule_code',
        'transaction_status',
        'politics_status',
        'accounting_authorization',
        'id_created',
        'created_at',
        'id_deleted',
        'deleted_at',
        'id_authorization',
        'authorization_at',
    ];
}

class servicesAccountModelCaseSpecial extends Model
{
    protected $table      = 'tbl_services_account_status';
    protected $primaryKey = 'id_account_status';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_account_status_item',
        'id_account_status',
        'active_status',
        'id_request',
        'type',
        'id_user',
        'date_transaction',
        'location_transaction',
        'amount',
        'divisa',
        'amount_mxn',
        'rule_code',
        'transaction_status',
        'politics_status',
        'accounting_authorization',
        'id_created',
        'created_at',
        'id_deleted',
        'deleted_at',
    ];
}
