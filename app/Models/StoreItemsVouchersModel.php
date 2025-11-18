<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreItemsVouchersModel extends Model
{
    protected $table      = 'tbl_store_items_vouchers';
    protected $primaryKey = 'id_vouchers';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_vouchers',
        'items',
        'code',
        'article',
        'amount',
        'amount_confirm',
        'weight',
        'observation',
        'alm_commet',
        'barcode_image',
        'article_image',
        'weight_image',
        'created_at',
        'active_status',
        'type_transfer'

    ];

    protected $useTimestamps = false;
    //protected $createField = 'created_at';
    protected $updateField = 'update_at';
}
