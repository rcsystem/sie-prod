<?php
namespace App\Models;

use CodeIgniter\Model;

class TicketsModel extends Model
{
    protected $table      = 'tbl_tickets_it';
    protected $primaryKey = 'id_ticket';

    protected $returnType = 'array';
   

    protected $allowedFields = [
                                'id_ticket',
                                'id_user_it',
                                'id_user',
                                'user',
                                'payroll_number',
                                'departament',
                                'position',
                                'activity',
                                'img_firm',
                                'complexity',
                                'homeoffice',
                                'created_at',
                                'activity_date',
                                'email_user',
                                'active_status'
                                ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    //protected $createField = 'update_at';

}