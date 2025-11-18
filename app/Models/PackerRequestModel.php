<?php

namespace App\Models;

use CodeIgniter\Model;

class PackerRequestModel extends Model
{
    protected $table      = 'tbl_packer_request';
    protected $primaryKey = 'id_request';

    protected $returnType = 'object';


    protected $allowedFields = [
        'id_request',
        'id_user',
        'gather',
        'sending_company',
        'sender_name',
        'sender_name2',
        'area_operative',
        'sender_phone',
        'sender_street',
        'sender_num',
        'sender_col',
        'sender_locality',
        'sender_state',
        'sender_cp',
        'sender_country',
        'sure',
        'cost',
        'observation',
        'shipping_type',
        'recipient_company',
        'recipient_name',
        'recipient_phone',
        'recipient_street',
        'recipient_num',
        'recipient_col',
        'recipient_locality',
        'recipient_state',
        'recipient_cp',
        'recipient_country',
        'status',
        'created_at',
        'autorize_at',
        'pdf_guie',
        'id_answer',
        'answer_at',
    ];

    protected $useTimestamps = false;
}
