<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudAdmModel extends Model
{
    protected $table      = 'tbl_personnel_admin_payment_request';
    protected $primaryKey = 'id_request';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_request',
        'company',
        'month',
        'type_of_payroll',
        'period',
        'date_request',
        'comment',
        'created_at',
        'active_status',
        'id_user_reject',
        'date_reject',
        'application_concept',
        'cuenta_contable',
        'amount',
        'status_request',
        'id_user',
        'id_user_delete',
        'date_delete',
        'rejection_reason',
        'id_user_pago',
        'date_pago',
        'comentario_pago',
        'ruta_pdf',
        'realizada',
        'id_epicor',


    ];

    // Método con JOIN
    public function getSolicitudesParaAprobacion()
    {
        return $this->db->table('tbl_personnel_admin_payment_request a')
            ->select('
        a.id_request,
        a.company,
        a.application_concept,
        a.period,
        a.amount,
        a.date_request,
        a.status_request,
        GROUP_CONCAT(b.file_name SEPARATOR ", ") AS items,
        GROUP_CONCAT(b.file_ruta SEPARATOR ", ") AS rutas,
        GROUP_CONCAT(b.file_type SEPARATOR ", ") AS tipo,
        MAX(CASE WHEN b.file_type = 1 THEN b.firm_status END) AS firm_status_file_1
    ')
            ->join('tbl_personnel_admin_item_request b', 'b.id_request = a.id_request', 'left')
            ->where('a.active_status', 1)
            ->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request')
            ->get()
            ->getResultArray();
    }

    public function getSolicitudesDePago()
    {
        return $this->db->table('tbl_personnel_admin_payment_request a')
            ->select('
        a.id_request,
        a.company,
        a.month,
        a.application_concept,
        a.period,
        a.amount,
        a.date_request,
        a.status_request,
        GROUP_CONCAT(b.file_name SEPARATOR ", ") AS items,
        GROUP_CONCAT(b.file_ruta SEPARATOR ", ") AS rutas,
        GROUP_CONCAT(b.file_type SEPARATOR ", ") AS tipo
    ')
            ->join('tbl_personnel_admin_item_request b', 'b.id_request = a.id_request', 'left')
            ->where('a.active_status', 1)  // Filtrar solo registros con estatus = 1
            ->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request')
            ->get()
            ->getResultArray();
    }

    // Método con JOIN
    public function getSolicitudesParaAutorizacion()
    {
        return $this->db->table('tbl_personnel_admin_payment_request a')
            ->select('
                  a.id_request,
                  a.company,
                  a.application_concept,
                  a.period,
                  a.month,
                  a.amount,
                  a.date_request,
                  a.status_request,
                  GROUP_CONCAT(b.file_name SEPARATOR ", ") AS items,
                  GROUP_CONCAT(b.file_ruta SEPARATOR ", ") AS rutas,
                  GROUP_CONCAT(b.file_type SEPARATOR ", ") AS tipo
              ')
            ->join('tbl_personnel_admin_item_request b', 'b.id_request = a.id_request', 'left')
            ->groupStart()
            ->where('a.status_request', 3)
            ->orWhere('a.status_request', 2)
            ->orWhere('a.status_request', 4)
            ->orWhere('a.status_request', 5)
            ->groupEnd()
            ->where('a.active_status', 1)
            ->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request')
            ->get()
            ->getResultArray();
    }

    public function getVerificarSolicitud($id_request)
    {

        return $this->db->table('tbl_personnel_admin_payment_request a')
            ->select('
        a.id_request,
        a.company,
        a.application_concept,
        a.period,
        a.month,
        a.type_of_payroll,
        a.date_request,
        a.comment,
        a.amount,
        a.status_request,
        b.firm_status')
            ->join('tbl_personnel_admin_item_request b', 'b.id_request = a.id_request', 'left')
            ->where('a.id_request', $id_request)  // Filtrar solo registros con estatus = 1
            ->where('a.active_status', 1)  // Filtrar solo registros con estatus = 1
            ->where('b.file_type', 1)  // Filtrar solo aquellos con file_type = 1
            ->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request')
            ->get()
            ->getResultArray();
    }

    // Método con JOIN
    public function getSolicitudesParaPago()
    {
        return $this->db->table('tbl_personnel_admin_payment_request a')
            ->select('
                     a.id_request,
                     a.company,
                     a.application_concept,
                     a.period,
                     a.month,
                     a.amount,
                     a.date_request,
                     a.status_request,
                     GROUP_CONCAT(b.file_name SEPARATOR ", ") AS items,
                     GROUP_CONCAT(b.file_ruta SEPARATOR ", ") AS rutas,
                     GROUP_CONCAT(b.file_type SEPARATOR ", ") AS tipo
                 ')
            ->join('tbl_personnel_admin_item_request b', 'b.id_request = a.id_request', 'left')
            ->groupStart()
            ->where('a.status_request', 3)
            ->orWhere('a.status_request', 4)
            ->orWhere('a.status_request', 2)

            ->groupEnd()
            ->where('a.active_status', 1)
            ->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request')
            ->get()
            ->getResultArray();
    }

    // Método con JOIN
    public function getSolicitudesPagadas()
    {
        return $this->db->table('tbl_personnel_admin_payment_request a')
            ->select('
                        a.id_request,
                        a.company,
                        a.application_concept,
                        a.period,
                        a.month,
                        a.amount,
                        a.date_request,
                        a.status_request,
                        a.realizada,
                        a.id_epicor,
                        a.created_at,
                        GROUP_CONCAT(b.file_name SEPARATOR ", ") AS items,
                        GROUP_CONCAT(b.file_ruta SEPARATOR ", ") AS rutas,
                        GROUP_CONCAT(b.file_type SEPARATOR ", ") AS tipo
                    ')
            ->join('tbl_personnel_admin_item_request b', 'b.id_request = a.id_request', 'left')
            ->groupStart()
            // ->where('a.status_request', 3)
            ->orWhere('a.status_request', 4)
            // ->orWhere('a.status_request', 2)

            ->groupEnd()
            ->where('a.active_status', 1)
            ->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request')
            ->get()
            ->getResultArray();
    }

    public function getinfoSolicitud($id_request)
    {
        return $this->db->table('tbl_personnel_admin_payment_request a')
            ->select('
    a.id_request,
    a.company,
    a.application_concept,
    a.period,
    a.month,
    a.type_of_payroll,
    a.date_request,
    a.comment,
    a.amount,
    a.date_request,
    a.status_request,
    b.firm_status,
    a.rejection_reason
')
            ->join('tbl_personnel_admin_item_request b', 'b.id_request = a.id_request', 'left')
            ->where('a.id_request', $id_request)  // Filtrar solo registros con estatus = 1
            ->where('a.active_status', 1)  // Filtrar solo registros con estatus = 1
            ->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request')
            ->get()
            ->getResultArray();
    }


    public function updateRequest($id_request, $data)
    {
        $this->db->table('tbl_personnel_admin_payment_request')
            ->where('id_request', $id_request)
            ->update($data);

        return $this->db->affectedRows() > 0; // Retorna true si se actualizó
    }

    public function notificaUsuario($id_request)
    {

        return $this->db->table('tbl_personnel_admin_payment_request a')
            ->select('
        a.id_request,
        a.company,
        a.application_concept,
        a.period,
        a.month,
        a.type_of_payroll,
        a.date_request,
        a.comment,
        a.amount,
        a.status_request,
        b.firm_status,
        a.rejection_reason,
        c.email,
        a.comentario_pago
    ')
            ->join('tbl_personnel_admin_item_request b', 'b.id_request = a.id_request', 'left')
            ->join('tbl_users c', 'c.id_user = a.id_user', 'left') // Ajusta 'a.user_id' si el campo de relación es diferente
            ->where('a.id_request', $id_request)
            ->where('a.active_status', 1)
            ->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request, c.email')
            ->get()
            ->getResultArray();
    }
}
