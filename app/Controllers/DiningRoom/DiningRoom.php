<?php

/**
 * ARCHIVO MODULO COMEDOR
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */

namespace App\Controllers\DiningRoom;

use App\Controllers\BaseController;

use App\Models\DiningRoomModel;
use CodeIgniter\HTTP\ResponseInterface;



class DiningRoom extends BaseController
{
    public function __construct()
    {
      
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function viewCalendar()
    {
        return ($this->is_logged) ?  view('dining_room/view_dining_room') : redirect()->to(site_url());
    }

    public function obtenerEventos(): ResponseInterface
    {
        $model = new DiningRoomModel();

        // Obtener los eventos de la base de datos
        $eventos = $model->findAll();

        // Formatear eventos para FullCalendar
        $formatoEventos = array_map(function ($evento) {
            return [
                'title' => $evento['username'],
                'start' => $evento['date_event']
            ];
        }, $eventos);

        // Devolver eventos en formato JSON
        return $this->response->setJSON($formatoEventos);
    }

    public function guardarEventos()
{
    $model = new DiningRoomModel();

    $id_user = session()->id_user;
    $date = date("Y-m-d H:i:s");

    // Validar y guardar los datos
    $data = [
        'date_event' => $this->request->getPost('fecha'),
        'username' => $this->request->getPost('usuario'),
        'id_user' => $id_user,
        'date_create' => $date
    ];

    $model->insert($data);

    return $this->response->setStatusCode(200)->setBody('Evento guardado correctamente');
}

}
