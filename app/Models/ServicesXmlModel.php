<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicesXmlModel extends Model
{
    protected $table      = 'tbl_services_verification_items_travel_expenses';
    protected $primaryKey = 'id_item_verification';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_item_verification',
        'active_status',
        'id_account_status',
        'id_request',
        'id_user',
        'id_to_check',
        'cfdi_version',
        'serie_and_folio',
        'social_reason',
        'rfc',
        'invoice_date',
        'iva_percentage',
        'subtotal',
        'retention',
        'iva',
        'total',
        'observation',
        'trip',
        'facture_type',
        'xml_travel_routes',
        'pdf_travel_routes',
        'expense_type',
        'created_at',
        'accounting_authorization',
        'id_accounting_authorization',
        'accounting_authorization_at',
        'id_cancel',
        'cancel_at',
        'ncr_number',
        'caso_number'
        
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    public function addInvoices($folio)
    {
        $query = $this->db->query("SELECT SUM(total) AS total FROM tbl_services_data_xml WHERE folio = $folio");
        return $query->getResultArray();
    }
}
