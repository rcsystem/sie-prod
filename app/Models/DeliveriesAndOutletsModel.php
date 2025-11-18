<?php
namespace App\Models;

use CodeIgniter\Model;

class DeliveriesAndOutletsModel extends Model
{
    protected $table      = 'tbl_deliveries_and_outlets_system';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
   

    protected $allowedFields = [
                                'id',
                                'created_at',
                                'id_user',
                                'deliveries_and_outlets',
                                'supplies',
                                'quantity',
                                'person_who_received',
                                'id_supplies',
                                'observation',
                                'active_status'
                             ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    //protected $createField = 'update_at';

   

}