<?php
namespace App\Models;

use CodeIgniter\Model;

class VisitQhseModel extends Model
{
    protected $table      = 'tbl_qhse_visit_suppliers';
    protected $primaryKey = 'id';
    protected $returnType = 'object';   

    protected $allowedFields = [
        'id',
        'id_user',
        'name',
        'departament',
        'job',
        'payroll_number',
        'person_you_visit',
        'num_persons',
        'person_you_visit',
        'suppliers',
        'departament_you_visit',
        'reason_for_visit',
        'day_you_visit',
        'time_of_entry',
        'departure_time',
        'entry_time',
        'imss',
        'authorize',
        'id_authorize',
        'poliza',
        'created_at',
        'updated_at',
        'active_status',
        'auto',
        'epp',
        'trabajos',
        'start_date_of_stay',
        'end_date_of_stay',
        'permit_type'
    ];


    protected $useTimestamps = false;
    protected $createField = 'created_at';
    //protected $createField = 'update_at';

}