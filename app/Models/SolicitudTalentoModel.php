<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudTalentoModel extends Model
{
    protected $table      = 'tbl_talent_payment_request';
    protected $primaryKey = 'id_request';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_request',
        'tipo_pago',
        'nombre_empresa',
        'banco',
        'cuenta',
        'clabe',
        'cantidad',
        'concepto',
        'cantidad_letra',
        'request_status',
        'ruta_pdf',
        'status_pdf',
        'id_user',
        'user_name',
        'created_at',
        'active_status',
        'name_pdf',
        'id_user_delete',
        'date_delete',
        'id_user_reject',
        'date_reject',
        'id_user_pago',
        'comentario_pago',
        'date_pago',
        'realizada',
        'id_epicor',
        'empresas',




    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    public function updateRequest($id_request,  $data)
    {
        $this->db->table('tbl_talent_payment_request')
            ->where('id_request', $id_request)
            ->update($data);

        return $this->db->affectedRows() > 0; // Retorna true si se actualizó
    }

    public function getVerificarSolicitud($id_request)
    {

        return $this->db->table('tbl_talent_payment_request')
            ->select('
        id_request,
        tipo_pago,
        nombre_empresa,
        banco,
        cuenta,
        concepto,
        clabe,
        cantidad,
        cantidad_letra,
        id_user,
        user_name,
        created_at,
        active_status,
        ruta_pdf,
        status_pdf,')
            ->where('id_request', $id_request)  // Filtrar solo registros con estatus = 1
            ->where('active_status', 1)  // Filtrar solo registros con estatus = 1
            ->get()
            ->getResultArray();
    }

    // Método con JOIN
    public function getSolicitudesPago()
    {
        return $this->db->table('tbl_talent_payment_request')
            ->select('
            id_request,
            tipo_pago,
            nombre_empresa,
            banco,
            cuenta,
            CONCAT(
        UPPER(LEFT(LOWER(concepto), 1)),
        SUBSTRING(LOWER(concepto), 2)
    ) AS concepto,
            clabe,
            cantidad,
            request_status,
            cantidad_letra,
            id_user,
            user_name,
            created_at,
            active_status,
            ruta_pdf,
            status_pdf')
            ->whereIn('request_status', [2, 3, 4]) // Correcto: array de valores
            ->where('active_status', 1)
            ->get()
            ->getResultArray();
    }



    // Método con JOIN
    public function getSolicitudesParaPagoTalento()
    {
        return $this->db->table('tbl_talent_payment_request a')
            ->select('
                    id_request,
                    tipo_pago,
                    nombre_empresa,
                    banco,
                    cuenta,
                    clabe,
                    cantidad,
                     CONCAT(
        UPPER(LEFT(LOWER(a.concepto), 1)),
        SUBSTRING(LOWER(a.concepto), 2)
    ) AS concepto,
                    cantidad_letra,
                    request_status,
                    ruta_pdf,
                    status_pdf,
                    id_user,
                    user_name,
                    created_at,
                    active_status,
                    name_pdf,
                    id_user_delete,
                    date_delete,
                    id_user_reject,
                    date_reject,
                        ')

            ->orWhere('request_status', 4)
            ->orWhere('request_status', 3)

            ->where('a.active_status', 1)
            //->groupBy('a.id_request, a.company, a.application_concept, a.period, a.amount, a.status_request')
            ->get()
            ->getResultArray();
    }


    public function notificaUsuario($id_request)
    {

        return $this->db->table('tbl_talent_payment_request a')
            ->select('
                    a.id_request,
                    a.tipo_pago,
                    a.nombre_empresa,
                    a.banco,
                    a.cuenta,
                    a.clabe,
                    a.cantidad,
                    CONCAT(
        UPPER(LEFT(LOWER(a.concepto), 1)),
        SUBSTRING(LOWER(a.concepto), 2)
    ) AS concepto,
                    a.cantidad_letra,
                    a.request_status,
                    a.ruta_pdf,
                    a.status_pdf,
                    a.id_user,
                    a.user_name,
                    a.created_at,
                    a.active_status,
                    a.name_pdf,
                    a.id_user_delete,
                    a.date_delete,
                    a.id_user_reject,
                    a.date_reject,
                    a.id_user_pago,
                    a.comentario_pago,
                    a.date_pago,
                    c.email,

    ')
            ->join('tbl_talent_payment_item_request b', 'b.id_request = a.id_request', 'left')
            ->join('tbl_users c', 'c.id_user = a.id_user', 'left') // Ajusta 'a.user_id' si el campo de relación es diferente
            ->where('a.id_request', $id_request)
            ->where('a.active_status', 1)
            ->groupBy('a.nombre_empresa, a.concepto, a.cantidad, a.request_status, c.email')
            ->get()
            ->getResultArray();
    }

    public function getSolicitudesPagadasTalento()
    {
        return $this->db->table('tbl_talent_payment_request a')
            ->select('
                a.id_request,
                a.tipo_pago,
                a.nombre_empresa,
                a.banco,
                a.cuenta,
                a.clabe,
                a.realizada,
                a.id_epicor,
                a.cantidad,
                 CONCAT(
        UPPER(LEFT(LOWER(a.concepto), 1)),
        SUBSTRING(LOWER(a.concepto), 2)
    ) AS concepto_formatted,
                a.cantidad_letra,
                a.request_status,
                a.ruta_pdf,
                a.status_pdf,
                a.id_user,
                a.user_name,
                a.created_at,
                a.active_status,
                a.name_pdf,
                a.id_user_delete,
                a.date_delete,
                a.id_user_reject,
                a.date_reject,
                a.id_user_pago,
                a.comentario_pago,
                a.date_pago,
                a.empresas
               

')
            ->join('tbl_talent_payment_item_request b', 'b.id_request = a.id_request', 'left')
            // ->join('tbl_users c', 'c.id_user = a.id_user', 'left') // Ajusta 'a.user_id' si el campo de relación es diferente
            ->orWhere('a.request_status', 4)
            ->where('a.active_status', 1)
            // ->groupBy('a.nombre_empresa, a.concepto, a.cantidad, a.request_status, c.email')
            ->get()
            ->getResultArray();
    }
}
