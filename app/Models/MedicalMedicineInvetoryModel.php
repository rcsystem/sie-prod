<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicalMedicineInvetoryModel extends Model
{
    protected $table      = 'tbl_medical_inventory_medicine';
    protected $primaryKey = 'id_medicine';
    protected $returnType = 'object';
   
    protected $allowedFields = [
        'id_medicine',
        'active_substance',
        'trademark',
        'expiration_date',
        'traffic_light',
        'inventory_tablet',
        'stiker',        
        'id_presentation',
        'pieza_caja',
        'piezas',
        'active_status',
        'created_at',
        'id_created',
        'deleted_at',
        'id_deleted'        
    ];
}