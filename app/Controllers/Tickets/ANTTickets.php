<?php

/**
 * MODULO DE TICKETS
 * @version 1.1 pre-prod
 * @author  Horus Samael Rivas Pedraza <horus.riv.ped@gmail.com>
 * @telefono 56-24-39-26-32
 */

namespace App\Controllers\Tickets;

use DateTime;
use App\Controllers\BaseController;
use App\Models\ticketsRequestModel;
use App\Models\ticketsReasignationModel;
use App\Models\ticketsMaintenanceItemsModel;
use App\Models\ticketsActionModal;

use App\Models\ticketsMaintenanceRequestModel;

use Spipu\Html2Pdf\Html2Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Tickets extends BaseController
{

    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->requestModel = new ticketsRequestModel();
        $this->reasignationModel = new ticketsReasignationModel();
        $this->actionModel = new ticketsActionModal();
        $this->maintenanceRequestModel = new ticketsMaintenanceRequestModel();
        $this->maintenanceItemsModel = new ticketsMaintenanceItemsModel();
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function viewTable()
    {
        $company = session()->access_tickets;
        if ($company != null) {
            $query = $this->db->query("SELECT ActividadId, Actividad_Actividad FROM cat_ticket_actividad WHERE Actividad_AreaId = $company AND active_status = 1")->getResult();
            $query1 = $this->db->query("SELECT id_user, CONCAT(`name`,' ',surname,' ',second_surname) AS nombre FROM tbl_users WHERE active_status = 1 ORDER BY `name` ASC")->getResult();
            $query2 = $this->db->query("SELECT TecnicoId, CONCAT(Tecnico_Nombre,' ',Tecnico_ApellidoPaterno) AS nombre FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $company AND Tecnico_Activo = 1 ORDER BY Tecnico_Nombre ASC")->getResult();
            $data = ['actvidad' => $query, 'usuarios' => $query1, 'inge' => $query2];
            return ($this->is_logged) ? view('tickets/tablero', $data) : redirect()->to(site_url());
        } else {
            return ($this->is_logged) ? view('tickets/tablero') : redirect()->to(site_url());
        }
    }

    public function viewVideo()
    {
        return view('video/video');
    }

    public function viewReportes()
    {
        return ($this->is_logged) ? view('tickets/reportes_tickets') : redirect()->to(site_url());
    }

    public function insertTickets()
    {
        $idArea = $this->request->getPost('sel-area');
        $idActivity = $this->request->getPost('sel-actividad');
        $description = $this->request->getPost('txt-descripcion');
        $idUser = (empty($this->request->getPost('sel-usuario'))) ? session()->id_user : $this->request->getPost('sel-usuario');
        $nameUser = (empty($this->request->getPost('name_user'))) ? session()->name . ' ' . session()->surname : $this->request->getPost('name_user');
        $idDepto = (empty($this->request->getPost('id_depto'))) ? session()->id_depto : $this->request->getPost('id_depto');
        $idPriority = (empty($this->request->getPost('sel-agprioridad'))) ? 1 : $this->request->getPost('sel-agprioridad');
        $idStatus = $this->request->getPost('sel-agestatus');

        $idInge = $this->request->getPost('sel-tecnico');
        if (empty($idInge)) {
            if ($idArea == 1) {
                $queryBig = $this->db->query("SELECT a.PersonaActividad_PersonaId, c.Tecnico_Nombre, d.email, SUM( CASE  WHEN b.Ticket_TecnicoId = a.PersonaActividad_PersonaId AND (b.Ticket_EstatusId = 2 OR b.Ticket_EstatusId = 1) AND b.active_status = 1 THEN 1 ELSE 0  END  ) AS NO_Tickets 
                FROM rel_personaactividad AS a 
                LEFT JOIN tbl_tickets_request AS b ON a.PersonaActividad_PersonaId = b.Ticket_TecnicoId
                LEFT JOIN cat_ticket_tecnico AS c ON a.PersonaActividad_PersonaId = c.TecnicoId
                LEFT JOIN tbl_users AS d ON a.PersonaActividad_PersonaId = d.id_user
                WHERE a.PersonaActividad_DepartamentoActividadId IN 
                (SELECT DepartamentoActividadId FROM rel_departamentoactividad WHERE DepartamentoActividad_DepartamentoId = $idDepto  AND DepartamentoActividad_ActividadId = $idActivity AND DepartamentoActividad_Activo = 1)
                AND c.Tecnico_Activo = 1
                GROUP BY a.PersonaActividad_PersonaId
                ORDER BY NO_Tickets ASC LIMIT 1;")->getRow();
                $idInge = $queryBig->PersonaActividad_PersonaId;
                $nameInge = $queryBig->Tecnico_Nombre;
                $emailInge = $queryBig->email;
            } else {
                $queryBig = $this->db->query("SELECT a.id_user, CONCAT(a.`name`,' ',a.surname) AS nombre , a.email,
                SUM( CASE  WHEN b.Ticket_TecnicoId = a.id_user AND (b.Ticket_EstatusId = 2 OR b.Ticket_EstatusId = 1) AND b.active_status = 1 THEN 1 ELSE 0  END  ) AS NO_Tickets 
                FROM tbl_users AS a 
                LEFT JOIN tbl_tickets_request AS b ON a.id_user = b.Ticket_TecnicoId
                LEFT JOIN cat_ticket_tecnico AS c ON a.id_user = c.TecnicoId
                WHERE a.id_user IN 
                (SELECT id_manager FROM tbl_tickets_activity_manager WHERE active_status = 1 AND ActividadId = $idActivity)
                AND c.Tecnico_Activo = 1
                GROUP BY a.id_user
                ORDER BY NO_Tickets ASC LIMIT 1;")->getRow();
                $idInge = $queryBig->id_user;
                $nameInge = $queryBig->nombre;
                $emailInge = $queryBig->email;
            }

            $dataInsert = [
                'id_activity' => $idActivity,
                'id_depto' => $idDepto,
                'Ticket_TecnicoId' => $idInge,
                'Ticket_EstatusId' => 1,
                'Ticket_PrioridadId' => $idPriority,
                'Ticket_Descripcion' => $description,
                'Ticket_UsuarioCreacionId' => $idUser,
                'Ticket_UsuarioCreacion' => $nameUser,
                'Ticket_FechaCreacion' => date('Y-m-d H:i:s'),
                'active_status' => 1,
            ];
        } else {
            $queryInge =  $this->db->query("SELECT CONCAT(`name`,' ',surname) AS nombre , email FROM tbl_users WHERE id_user = $idInge;")->getRow();
            $nameInge = $queryInge->nombre;
            $emailInge = $queryInge->email;
            $dateConclud = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + 5 minute'));
            if ($idStatus == 3) {
                $dataInsert = [
                    'id_activity' => $idActivity,
                    'id_depto' => $idDepto,
                    'Ticket_TecnicoId' => $idInge,
                    'Ticket_EstatusId' => $this->request->getPost('sel-agestatus'),
                    'Ticket_FechaConcluido' => ($this->request->getPost('sel-agestatus') == 3 || $this->request->getPost('sel-agestatus') == 5) ? $dateConclud : null,
                    'Ticket_Solucion' => $this->request->getPost('txt-solucion'),
                    'Ticket_PrioridadId' => $idPriority,
                    'Ticket_Descripcion' => $description,
                    'Ticket_UsuarioCreacionId' => $idUser,
                    'Ticket_UsuarioCreacion' => $nameUser,
                    'Ticket_FechaCreacion' => date('Y-m-d H:i:s'),
                    'active_status' => 1,
                ];
            } else {
                $dataInsert = [
                    'id_activity' => $idActivity,
                    'id_depto' => $idDepto,
                    'Ticket_TecnicoId' => $idInge,
                    'Ticket_EstatusId' => $this->request->getPost('sel-agestatus'),
                    'Ticket_PrioridadId' => $idPriority,
                    'Ticket_Descripcion' => $description,
                    'Ticket_UsuarioCreacionId' => $idUser,
                    'Ticket_UsuarioCreacion' => $nameUser,
                    'Ticket_FechaCreacion' => date('Y-m-d H:i:s'),
                    'active_status' => 1,
                ];
            }
        }


        $insertRequest = $this->requestModel->insert($dataInsert);
        $idRequest = $this->db->insertID();
        if (empty($this->request->getPost('sel-usuario'))) {
            $this->notificarEmail($emailInge, $nameInge, $dataInsert, $idRequest, 1);
        }

        return ($insertRequest) ? json_encode($nameInge) : json_encode(false);
    }

    public function ticketsALL()
    {
        $folio = $this->request->getPost('folio');
        $idUser = session()->id_user;
        $company = session()->access_tickets;

        if ($folio != 'undefined' && session()->access_tickets != false) {
            $data = $this->db->query("SELECT  a.TicketId, a.Ticket_EstatusId , a.Ticket_FechaCreacion, a.Ticket_Descripcion,
            concat(b.Tecnico_nombre,' ',b.Tecnico_apellidoPaterno) AS Tecnico_Nombre , c.Actividad_Actividad 
            FROM tbl_tickets_request AS a JOIN cat_ticket_tecnico AS b ON a.Ticket_TecnicoId = b.TecnicoId
                LEFT JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId 
            WHERE TicketId LIKE '%$folio%'	AND a.active_status = 1 
                AND a.Ticket_TecnicoId IN (SELECT TecnicoId FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $company )
            ORDER BY a.TicketId DESC LIMIT 1000")->getResult();
        } else {
            if (session()->access_tickets == false || session()->access_tickets == 3) {
                // $data = $this->db->query("SELECT  a.TicketId, a.Ticket_EstatusId , a.Ticket_FechaCreacion, a.Ticket_Descripcion,
                // concat(b.Tecnico_nombre,' ',b.Tecnico_apellidoPaterno) AS Tecnico_Nombre , c.Actividad_Actividad 
                // FROM tbl_tickets_request AS a JOIN cat_ticket_tecnico AS b ON a.Ticket_TecnicoId = b.TecnicoId
                //     LEFT JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId 
                // WHERE a.Ticket_UsuarioCreacionId = $idUser AND a.active_status = 1 ORDER BY a.TicketId DESC LIMIT 1000")->getResult();
                $query = $this->db->query("SELECT  a.TicketId, a.Ticket_EstatusId , a.Ticket_FechaCreacion, a.Ticket_Descripcion,
                concat(b.Tecnico_nombre,' ',b.Tecnico_apellidoPaterno) AS Tecnico_Nombre , c.Actividad_Actividad 
                FROM tbl_tickets_request AS a JOIN cat_ticket_tecnico AS b ON a.Ticket_TecnicoId = b.TecnicoId
                    LEFT JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId 
                WHERE a.Ticket_UsuarioCreacionId = $idUser AND a.active_status = 1 ORDER BY a.TicketId DESC LIMIT 1000")->getResult();
                $data = ($query) ? $query : 'nuevoTicket';
            } else {
                $data = $this->db->query("SELECT  a.TicketId, a.Ticket_EstatusId , a.Ticket_FechaCreacion, a.Ticket_Descripcion,
                concat(b.Tecnico_nombre,' ',b.Tecnico_apellidoPaterno) AS Tecnico_Nombre , c.Actividad_Actividad 
                FROM tbl_tickets_request AS a JOIN cat_ticket_tecnico AS b ON a.Ticket_TecnicoId = b.TecnicoId
                    LEFT JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId 
                WHERE a.Ticket_TecnicoId IN (SELECT TecnicoId FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $company ) AND a.active_status = 1
                ORDER BY a.TicketId DESC LIMIT 1000")->getResult();
            }
            /* if ($idUser == 1063) {
            $data = $this->db->query("SELECT  a.TicketId, a.Ticket_EstatusId , a.Ticket_FechaCreacion, a.Ticket_Descripcion,
            concat(b.Tecnico_nombre,' ',b.Tecnico_apellidoPaterno) AS Tecnico_Nombre , c.Actividad_Actividad 
            FROM tbl_tickets_request AS a JOIN cat_ticket_tecnico AS b ON a.Ticket_TecnicoId = b.TecnicoId
                LEFT JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId 
                WHERE a.active_status = 1  ORDER BY a.TicketId DESC LIMIT 1000")->getResult();
        } */
        }
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function searchTickets()
    {
        try {
            $company = session()->access_tickets;
            $sqlPriority = ($this->request->getPost('prioridad') == '') ? '' : 'AND a.Ticket_PrioridadId = ' . $this->request->getPost('prioridad');
            $sqlActivity = ($this->request->getPost('actividad') == '') ? '' : 'AND a.id_activity = ' . $this->request->getPost('actividad');
            $sqlUser = ($this->request->getPost('usuario') == '') ? '' : 'AND a.Ticket_UsuarioCreacionId = ' . $this->request->getPost('usuario');
            $sqlIng = ($this->request->getPost('tecnico') == '') ? '' : 'AND a.Ticket_TecnicoId = ' . $this->request->getPost('tecnico');
            $sqlIng = ($this->request->getPost('tecnico') == '') ? '' : 'AND a.Ticket_TecnicoId = ' . $this->request->getPost('tecnico');
            $sqlDate = "AND a.Ticket_FechaCreacion BETWEEN '" . $this->request->getPost('fecha_inicio') . "' AND '" . date("Y-m-d", strtotime(($this->request->getPost('Fecha_fin')) . "+ 1 days")) . "'";

            $data = $this->db->query("SELECT  a.TicketId, a.Ticket_EstatusId , a.Ticket_FechaCreacion, a.Ticket_Descripcion,
                concat(b.Tecnico_nombre,' ',b.Tecnico_apellidoPaterno) AS Tecnico_Nombre, c.Actividad_Actividad 
                FROM tbl_tickets_request AS a JOIN (SELECT * FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $company) AS b ON a.Ticket_TecnicoId = b.TecnicoId
                LEFT JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId 
                WHERE a.active_status = 1 $sqlPriority $sqlActivity $sqlUser $sqlIng $sqlDate ORDER BY a.TicketId DESC")->getResult();
            return json_encode($data);
        } catch (\Exception $th) {
            return json_encode(false);
        }
    }

    /*public function searchtTicketsData()
    {
        $starDate = $this->request->getPost('date_star');
        $endDate = date("Y-m-d", strtotime(($this->request->getPost('date_end')) . "+ 1 days"));
        $company = session()->access_tickets;
        $data = $this->db->query("SELECT  a.TicketId, a.Ticket_EstatusId , a.Ticket_FechaCreacion, a.Ticket_Descripcion,
         concat(b.Tecnico_nombre,' ',b.Tecnico_apellidoPaterno) AS Tecnico_Nombre , c.Actividad_Actividad 
        FROM tbl_tickets_request AS a JOIN cat_ticket_tecnico AS b ON a.Ticket_TecnicoId = b.TecnicoId
            LEFT JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId 
        WHERE a.Ticket_FechaCreacion BETWEEN '$starDate' AND '$endDate' AND a.active_status = 1 AND a.Ticket_TecnicoId IN (SELECT TecnicoId FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $company )
        ORDER BY a.TicketId DESC")->getResult();

        return ($data) ? json_encode($data) : json_encode(false);
    } */

    public function dataToCreateTicket()
    {
        $idArea = ($this->request->getPost('area') == null) ? session()->access_tickets : $this->request->getPost('area');
        $query = $this->db->query("SELECT ActividadId, Actividad_Actividad FROM cat_ticket_actividad WHERE Actividad_AreaId = $idArea")->getResult();
        $query1 = null;
        if (session()->access_tickets != false) {
            $query1 = $this->db->query("SELECT TecnicoId, CONCAT(Tecnico_Nombre,' ',Tecnico_ApellidoPaterno) AS nombre FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $idArea AND Tecnico_Activo = 1")->getResult();
        }
        $data = ['actividades' => $query, 'ingenieros' => $query1];
        return ($query != null) ? json_encode($data) : json_encode(false);
    }

    public function dataTicket()
    {
        $idRequest = $this->request->getPost('id_requets');
        $query = $this->db->query("SELECT a.Ticket_PrioridadId, a.Ticket_Descripcion,  a.Ticket_Solucion, a.Ticket_UsuarioCreacion, a.Ticket_EstatusId, CONCAT(b.`name`,' ',b.surname) AS nombre, a.Ticket_FechaCreacion, a.Ticket_TecnicoId
        FROM tbl_tickets_request AS a JOIN tbl_users AS b ON a.Ticket_TecnicoId = b.id_user WHERE TicketId = $idRequest")->getRow();
        $date = date("d/m/Y", strtotime($query->Ticket_FechaCreacion));
        $hour = date("H:i", strtotime($query->Ticket_FechaCreacion));
        $query1 = $this->db->query("SELECT * FROM tbl_tickets_accion WHERE Accion_TicketId = $idRequest AND active_status = 1")->getResult();
        $data =  ['info' => $query, 'fecha' => $date, 'hora' => $hour, 'chat' => $query1];
        return ($query) ? json_encode($data) : json_encode(false);
    }

    public function reasigTicket()
    {
        try {
            $idAnt = $this->request->getPost('tecnico_id_ant');
            $idRequest = $this->request->getPost('id_request_reasignar');
            $idNew = $this->request->getPost('reasig-tecnico');
            $dataReasig = [
                'Reasignacion_TicketId' => $idRequest,
                'Reasignacion_Fecha' => date('Y-m-d H:i:s'),
                'id_ant' => $idAnt,
                'id_new' => $idNew,
                'id_change' => session()->id_user,
            ];
            $dataUpRequest = [
                'Ticket_TecnicoId' => $idNew,
            ];
            $insert = $this->reasignationModel->insert($dataReasig);
            $update = $this->requestModel->update($idRequest, $dataUpRequest);
            return ($insert && $update) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode('error: ' . $th);
        }
    }

    public function priorityTicket()
    {
        try {
            $idRequest = $this->request->getPost('id_request_prioridad');
            $idPriority = $this->request->getPost('update-agprioridad');
            $dataUpRequest = [
                'Ticket_PrioridadId' => $idPriority,
            ];
            $update = $this->requestModel->update($idRequest, $dataUpRequest);
            return ($update) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode('error: ' . $th);
        }
    }

    public function statusTicket()
    {
        try {
            $idRequest = $this->request->getPost('id_request_estatus');
            $idStatus = $this->request->getPost('update-agestatus');
            $arrayType = ['error', 'nuevo', 'date_process', 'Ticket_FechaConcluido', 'date_cancel', 'date_closed'];
            if ($idStatus == 3) {
                $solution = $this->request->getPost('txt-solucion-agestatus');
                $dataUpRequest = [
                    'Ticket_EstatusId' => $idStatus,
                    $arrayType[$idStatus] => date('Y-m-d H:i:s'),
                    'Ticket_Solucion' => $solution,
                    'Ticket_FechaModificacion' => date('Y-m-d H:i:s'),
                ];
            } else if ($idStatus == 4) {
                $solution = $this->request->getPost('txt-solucion-agestatus');
                $dataUpRequest = [
                    'Ticket_EstatusId' => $idStatus,
                    $arrayType[$idStatus] => date('Y-m-d H:i:s'),
                    'motive_cancel' => $solution,
                    'Ticket_FechaModificacion' => date('Y-m-d H:i:s'),
                ];
            } else {
                $dataUpRequest = [
                    'Ticket_EstatusId' => $idStatus,
                    'Ticket_UsuarioModificacionId' => session()->id_user,
                    'Ticket_UsuarioModificacion' => session()->name . ' ' . session()->surname,
                    $arrayType[$idStatus] => date('Y-m-d H:i:s'),
                    'Ticket_FechaModificacion' => date('Y-m-d H:i:s'),
                ];
            }

            $update = $this->requestModel->update($idRequest, $dataUpRequest);
            $this->notificarEmailChange($idRequest, 3, $idStatus);
            return ($update) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode('error: ' . $th);
        }
    }

    public function cancelTicketForUser()
    {
        try {
            $idRequest = $this->request->getPost('id_request_cancelar');
            $idStatus = $this->request->getPost('update-agestatus');
            $solution = $this->request->getPost('txt-solucion-agestatus');
            $dataUpRequest = [
                'Ticket_EstatusId' => $idStatus,
                'date_cancel' => date('Y-m-d H:i:s'),
                'motive_cancel' => $solution,
                'Ticket_FechaModificacion' => date('Y-m-d H:i:s'),
            ];

            $update = $this->requestModel->update($idRequest, $dataUpRequest);
            $this->notificarEmailChange($idRequest, 4, $idStatus);
            return ($update) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode('error: ' . $th);
        }
    }

    public function addChat()
    {
        try {
            $idRequest = $this->request->getPost('id_Request');
            $type = $this->request->getPost('coment_type');
            if ($type == 1) {
                $dataInsert = [
                    'Accion_TicketId' => $idRequest,
                    'Accion_Comentario' => $this->request->getPost('new-txt-comentario'),
                    'Accion_UsuarioCreacionId' => session()->id_user,
                    'Accion_UsuarioCreacion' => session()->name . ' ' . session()->surname,
                    'Accion_FechaCreacion' => date('Y-m-d H:i:s'),
                ];
            } else {
                $binder =  '../public/doc/tickets/' . $idRequest;
                if (!file_exists($binder)) {
                    mkdir($binder, 0777, true);
                }
                $image = $this->request->getFile('image');
                $name = $image->getClientName();
                $image = $image->move($binder,  $name);
                $imageUrl = $binder . "/" . $name;
                $dataInsert = [
                    'Accion_TicketId' => $idRequest,
                    'Accion_URL' => $imageUrl,
                    'Accion_Nombre' => $name,
                    'Accion_UsuarioCreacionId' => session()->id_user,
                    'Accion_UsuarioCreacion' => session()->name . ' ' . session()->surname,
                    'Accion_FechaCreacion' => date('Y-m-d H:i:s'),
                ];
            }
            $insert = $this->actionModel->insert($dataInsert);
            $this->notificarEmailChange($idRequest, 2, $type);
            return ($insert) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode('error: ' . $th);
        }
    }

    public function deptoUser()
    {
        $idUser = $this->request->getPost('id');
        $data = $this->db->query("SELECT CONCAT(a.`name`,' ',a.surname,' ',a.second_surname) AS nombre, a.id_departament, b.departament
        FROM tbl_users AS a LEFT JOIN cat_departament AS b ON a.id_departament = b.id_depto WHERE a.id_user = $idUser AND a.active_status = 1")->getRow();
        return ($data != null) ? json_encode($data) : json_encode(false);
    }

    public function notificarEmail($dir_email, $title, $data, $id, $type)
    {
        $query = $this->db->query("SELECT Actividad_ClasificacionId AS class, Actividad_Actividad AS act froM cat_ticket_actividad WHERE ActividadId = " . $data["id_activity"])->getRow();
        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $dir_email = changeEmail($dir_email);
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {
            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // $dir_email = 'hrivas@walworth.com.mx';

            //Server settings
            $mail->isSMTP();
            $mail->SMTPAuth = false;
            $mail->Host = 'localhost';
            $mail->Username = 'requisiciones@walworth.com';
            $mail->Password = 'Walworth321$';
            $mail->Port = 25;
            if ($type == 1) {
                $mail->setFrom('notificacion@grupowalworth.com', 'Nuevo Ticket');
            } else if ($type == 2) {
                $mail->setFrom('notificacion@grupowalworth.com', 'Nueva Acción');
            } else {
                $mail->setFrom('notificacion@grupowalworth.com', 'Cambio de estatus');
            }

            // Add a recipient
            $mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('hrivas@walworth.com.mx', 'Informacion del Sistema');;

            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $datas = ['ticket' => $data, 'actividad' => $query, 'tipo' => $type, 'id' => $id];
            $email_template = view('notificaciones/notify_tickets', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Tickets';
            $mail->send();
            return true;
        } catch (Exception $e) {
            return json_encode(false);
        }
    }

    public function notificarEmailChange($id, $type, $opc)
    {
        $query = $this->db->query("SELECT a.Ticket_PrioridadId, a.Ticket_UsuarioCreacion, c.email, a.id_activity, a.motive_cancel
        FROM tbl_tickets_request AS a  JOIN tbl_users AS b ON a.Ticket_TecnicoId = b.id_user
        JOIN tbl_users AS c ON a.Ticket_UsuarioCreacionId = c.id_user WHERE a.TicketId = $id")->getRow();
        $query1 = $this->db->query("SELECT Actividad_ClasificacionId AS class, Actividad_Actividad AS act froM cat_ticket_actividad WHERE ActividadId = " . $query->id_activity)->getRow();
        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        if ($type == 4) {
            $query2 = $this->db->query("SELECT email, CONCAT(`name`,' ',surname) AS nombre 
            FROM tbl_users 
            WHERE id_user IN (
                SELECT Ticket_TecnicoId 
                FROM tbl_tickets_request 
                WHERE TicketId = $id)")->getRow();
        }
        $email = ($type == 4) ? $query2->email : $query->email;
        $title = ($type == 4) ? $query2->nombre : $query->Ticket_UsuarioCreacion;
        $dir_email = changeEmail($email);
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {
            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // $dir_email = 'hrivas@walworth.com.mx';

            //Server settings
            $mail->isSMTP();
            $mail->SMTPAuth = false;
            $mail->Host = 'localhost';
            $mail->Username = 'requisiciones@walworth.com';
            $mail->Password = 'Walworth321$';
            $mail->Port = 25;
            if ($type == 2) {
                $mail->setFrom('notificacion@grupowalworth.com', 'Nueva Acción');
            } else if ($type == 4) {
                $mail->setFrom('notificacion@grupowalworth.com', 'Cancelacion de Ticket');
            } else {
                $mail->setFrom('notificacion@grupowalworth.com', 'Cambio de estatus');
            }

            // Add a recipient
            $mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('hrivas@walworth.com.mx', 'Informacion del Sistema');


            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $datas = ['ticket' => $query, 'actividad' => $query1, 'tipo' => $type, 'id' => $id, 'opc' => $opc];
            $email_template = view('notificaciones/notify_tickets', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Tickets';
            $mail->send();
            return true;
        } catch (Exception $e) {
            return json_encode(false);
        }
    }

    /* **************** REPORTES IT **************** */
    public function getDataReports()
    {
        try {
            if (session()->manager_tickets != false/*  || session()->id_user == 1063 */ || session()->id_user == 1188 || session()->id_user == 1226) {
                $idArea = (session()->id_user == 1188 || session()->id_user == 1226) ? $this->request->getPost('id_area') : session()->manager_tickets;
                $starDate = $this->request->getPost('star_date');
                $endDate = date("Y-m-d", strtotime($this->request->getPost('end_date') . "+ 1 days"));
                if ($idArea == 3) {
                    $tickets = $this->db->query("SELECT SUM( CASE WHEN `status` = 1 THEN 1 ELSE 0  END  ) AS nuevos,
                    SUM( CASE WHEN `status` = 3 THEN 1 ELSE 0  END  ) AS proceso,
                    SUM( CASE WHEN `status` = 4 THEN 1 ELSE 0  END  ) AS concluido,
                    SUM( CASE WHEN `status` = 0 THEN 1 ELSE 0  END  ) AS cancelado,
                    SUM( CASE WHEN `status` = 5 THEN 1 ELSE 0  END  ) AS cerrado,
                    SUM( CASE WHEN active_status = 1 THEN 1 ELSE 0  END  ) AS total
                    FROM tbl_tickets_maintenance_request
                    WHERE id_activity IN (SELECT DISTINCT ActividadId FROM cat_ticket_actividad WHERE Actividad_AreaId = $idArea) 
                    AND created_at BETWEEN '$starDate' AND '$endDate'
                    AND active_status = 1;")->getRow();

                    if ($tickets->total == null) {
                        return json_encode('DATOS NO EXISTENTES');
                    }
                    $cumplimiento = round((((intval($tickets->cerrado) + intval($tickets->concluido)) / (intval($tickets->total) - intval($tickets->cancelado))) * 100), 2);

                    $actividades = $this->db->query("SELECT a.id_activity, COUNT(a.id_activity) AS cant_tickets, CONCAT('') AS total_horas, c.Actividad_Actividad
                    FROM tbl_tickets_maintenance_request AS a JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
                    WHERE a.active_status = 1 AND (a.`status` = 4 OR a.`status` = 5)
                    AND a.id_tecnico IN ( SELECT TecnicoId FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $idArea)GROUP BY a.id_activity;")->getResultArray();

                    $actFechas = $this->db->query("SELECT a.id_activity, a.process_star_at, a.process_end_at, star_refac, end_refac
                    FROM tbl_tickets_maintenance_request AS a
                    LEFT JOIN (SELECT MAX(CONCAT(date_end,' ',time_end)) AS end_refac, id_order FROM tbl_tickets_maintenance_items GROUP BY id_order) AS b ON a.id_order = b.id_order
                    LEFT JOIN (SELECT MIN(CONCAT(date_star,' ',time_star)) AS star_refac, id_order FROM tbl_tickets_maintenance_items GROUP BY id_order) AS c ON a.id_order = c.id_order
                    WHERE a.id_tecnico IN ( SELECT TecnicoId FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $idArea)
                    AND (`status` = 4 OR `status` = 5)
                    AND a.active_status = 1 ORDER BY a.id_activity DESC")->getResultArray();

                    for ($i = 0; $i < count($actividades); $i++) { // for de las actividades que realiza el ing
                        $hrsTotal =  0; // se reestablece las hrs que se insertaran
                        for ($d = 0; $d < count($actFechas); $d++) { // form para validar las fechas de las actividades del Usuario
                            if ($actividades[$i]['id_activity'] == $actFechas[$d]['id_activity']) {
                                // var_dump($actividades[$i]['id_activity'] . ' == ' . $actFechas[$d]['id_activity']);
                                $fechaInicio = $actFechas[$d]['process_star_at'];
                                $fechaFin = $actFechas[$d]['process_end_at'];
                                $diff = (new DateTime($fechaInicio))->diff(new DateTime($fechaFin));
                                $fecha1 = strtotime($fechaInicio);
                                $fecha2 = strtotime($fechaFin);
                                $finSemana = 0;
                                for ($fecha1; $fecha1 <= $fecha2; $fecha1 = strtotime('+1 day ' . date('Y-m-d', $fecha1))) {
                                    if ((date('D', $fecha1) == 'Sun') || (date('D', $fecha1) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                        $finSemana++;
                                    }
                                }
                                if ($actFechas[$d]['star_refac'] != null && $actFechas[$d]['end_refac'] != null) {
                                    $fechaInicioRefac = $actFechas[$d]['star_refac'];
                                    $fechaFinRefac = $actFechas[$d]['end_refac'];
                                    $diffR = (new DateTime($fechaInicioRefac))->diff(new DateTime($fechaFinRefac));
                                    $fecha3 = strtotime($fechaInicioRefac);
                                    $fecha4 = strtotime($fechaFinRefac);
                                    $finSemanaRefac = 0;
                                    for ($fecha3; $fecha3 <= $fecha4; $fecha3 = strtotime('+1 day ' . date('Y-m-d', $fecha3))) {
                                        if ((date('D', $fecha3) == 'Sun') || (date('D', $fecha3) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                            $finSemanaRefac++;
                                        }
                                    }
                                    $diasRealesRefac = ($diffR->m > 0) ? ($diffR->m * 30) + $diffR->d  : $diffR->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                    $horasTotalesMenosFinSemanaRefac = floatval((($diasRealesRefac - $finSemanaRefac) * 24) + $diffR->h + ($diffR->i / 60));
                                } else {
                                    $horasTotalesMenosFinSemanaRefac = 0.00;
                                }
                                $diasReales = ($diff->m > 0) ? ($diff->m * 30) + $diff->d  : $diff->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                $horasTotalesMenosFinSemana = floatval((($diasReales - $finSemana) * 24) + $diff->h + ($diff->i / 60));
                                // echo $horasTotalesMenosFinSemanaRefac . ' <br> ' . $horasTotalesMenosFinSemana;
                                $hrsTotal = $hrsTotal + floatval($horasTotalesMenosFinSemana - $horasTotalesMenosFinSemanaRefac);
                            }
                        }
                        $actividades[$i]['total_horas'] = round($hrsTotal, 2);
                    }

                    $laboral = $this->db->query("SELECT b.TecnicoId AS id_tecnico, CONCAT(b.Tecnico_Nombre,' ',b.Tecnico_ApellidoPaterno) AS nombre,
                    CASE WHEN a.active_status = 1 THEN 
                    FORMAT(
                        ( SUM( CASE  WHEN a.`status` = 4 THEN 1 ELSE 0  END  )  +  SUM( CASE  WHEN a.`status` = 5 THEN 1 ELSE 0  END  ) ) 
                        /
                        ( SUM( CASE  WHEN a.active_status = 1 THEN 1 ELSE 0  END  )  - SUM( CASE  WHEN a.`status` = 0 THEN 1 ELSE 0  END  ) )
                        *100 ,2) 
                    ELSE 0.00
                    END AS porcentaje, CONCAT(0) AS total_horas
                    FROM cat_ticket_tecnico AS b
                    LEFT JOIN tbl_tickets_maintenance_request AS a ON b.TecnicoId = a.id_tecnico
                    WHERE b.Tecnico_AreaId = 3 AND Tecnico_Activo = 1
                    GROUP BY b.TecnicoId;")->getResultArray();


                    $LaboralFechas = $this->db->query("SELECT a.id_tecnico, a.process_star_at, a.process_end_at, star_refac, end_refac
                    FROM tbl_tickets_maintenance_request AS a
                    LEFT JOIN (SELECT MAX(CONCAT(date_end,' ',time_end)) AS end_refac, id_order FROM tbl_tickets_maintenance_items GROUP BY id_order) AS b ON a.id_order = b.id_order
                    LEFT JOIN (SELECT MIN(CONCAT(date_star,' ',time_star)) AS star_refac, id_order FROM tbl_tickets_maintenance_items GROUP BY id_order) AS c ON a.id_order = c.id_order
                    WHERE a.id_tecnico IN ( SELECT TecnicoId FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $idArea)
                    AND (`status` = 4 OR `status` = 5)
                    AND a.active_status = 1 ORDER BY a.id_tecnico DESC")->getResultArray();

                    for ($i = 0; $i < count($laboral); $i++) { // for de las actividades que realiza el ing
                        $hrsTotal =  0; // se reestablece las hrs que se insertaran
                        for ($d = 0; $d < count($LaboralFechas); $d++) { // form para validar las fechas de las actividades del Usuario
                            if ($laboral[$i]['id_tecnico'] == $LaboralFechas[$d]['id_tecnico']) {
                                $fechaInicio = $LaboralFechas[$d]['process_star_at'];
                                $fechaFin = $LaboralFechas[$d]['process_end_at'];
                                $diff = (new DateTime($fechaInicio))->diff(new DateTime($fechaFin));
                                $fecha1 = strtotime($fechaInicio);
                                $fecha2 = strtotime($fechaFin);
                                $finSemana = 0;
                                for ($fecha1; $fecha1 <= $fecha2; $fecha1 = strtotime('+1 day ' . date('Y-m-d', $fecha1))) {
                                    if ((date('D', $fecha1) == 'Sun') || (date('D', $fecha1) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                        $finSemana++;
                                    }
                                }
                                if ($LaboralFechas[$d]['star_refac'] != null && $LaboralFechas[$d]['end_refac'] != null) {
                                    $fechaInicioRefac = $LaboralFechas[$d]['star_refac'];
                                    $fechaFinRefac = $LaboralFechas[$d]['end_refac'];
                                    $diffR = (new DateTime($fechaInicioRefac))->diff(new DateTime($fechaFinRefac));
                                    $fecha3 = strtotime($fechaInicioRefac);
                                    $fecha4 = strtotime($fechaFinRefac);
                                    $finSemanaRefac = 0;
                                    for ($fecha3; $fecha3 <= $fecha4; $fecha3 = strtotime('+1 day ' . date('Y-m-d', $fecha3))) {
                                        if ((date('D', $fecha3) == 'Sun') || (date('D', $fecha3) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                            $finSemanaRefac++;
                                        }
                                    }
                                    $diasRealesRefac = ($diffR->m > 0) ? ($diffR->m * 30) + $diffR->d  : $diffR->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                    $horasTotalesMenosFinSemanaRefac = floatval((($diasRealesRefac - $finSemanaRefac) * 24) + $diffR->h + ($diffR->i / 60));
                                } else {
                                    $horasTotalesMenosFinSemanaRefac = 0.00;
                                }
                                $diasReales = ($diff->m > 0) ? ($diff->m * 30) + $diff->d  : $diff->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                $horasTotalesMenosFinSemana = floatval((($diasReales - $finSemana) * 24) + $diff->h + ($diff->i / 60));

                                $hrsTotal = $hrsTotal + floatval($horasTotalesMenosFinSemana - $horasTotalesMenosFinSemanaRefac);
                            }
                        }
                        $laboral[$i]['total_horas'] = round($hrsTotal, 2);
                    }
                } else {
                    $tickets = $this->db->query("SELECT SUM( CASE WHEN Ticket_EstatusId = 1 THEN 1 ELSE 0  END  ) AS nuevos,
                    SUM( CASE WHEN Ticket_EstatusId = 2 THEN 1 ELSE 0  END  ) AS proceso,
                    SUM( CASE WHEN Ticket_EstatusId = 3 THEN 1 ELSE 0  END  ) AS concluido,
                    SUM( CASE WHEN Ticket_EstatusId = 4 THEN 1 ELSE 0  END  ) AS cancelado,
                    SUM( CASE WHEN Ticket_EstatusId = 5 THEN 1 ELSE 0  END  ) AS cerrado,
                    SUM( CASE WHEN active_status = 1 THEN 1 ELSE 0  END  ) AS total
                    FROM tbl_tickets_request 
                    WHERE id_activity IN (SELECT DISTINCT ActividadId FROM cat_ticket_actividad WHERE Actividad_AreaId = $idArea) 
                    AND Ticket_FechaCreacion BETWEEN '$starDate' AND '$endDate'
                    AND active_status = 1")->getRow();
                    if ($tickets->total == null) {
                        return json_encode('DATOS NO EXISTENTES');
                    }
                    $cumplimiento = round((((intval($tickets->cerrado) + intval($tickets->concluido)) / (intval($tickets->total) - intval($tickets->cancelado))) * 100), 2);

                    $actividades = $this->db->query("SELECT a.id_activity, b.Actividad_Actividad, count(a.TicketId) AS cant_tickets, CONCAT('') AS total_horas
                    FROM tbl_tickets_request AS a LEFT JOIN cat_ticket_actividad AS b ON a.id_activity = b.ActividadId
                    WHERE a.id_activity IN (SELECT DISTINCT ActividadId FROM cat_ticket_actividad WHERE Actividad_AreaId = $idArea) 
                    AND a.Ticket_FechaCreacion BETWEEN '$starDate' AND '$endDate'
                    AND a.active_status = 1 GROUP BY a.id_activity")->getResultArray();

                    $actFechas = $this->db->query("SELECT a.id_activity, a.Ticket_FechaConcluido, 
                    CASE WHEN b.Reasignacion_Fecha IS NOT NULL THEN b.Reasignacion_Fecha ELSE a.Ticket_FechaCreacion END AS FechaInicio
                    FROM tbl_tickets_request AS a JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
                    LEFT JOIN ( SELECT MAX(Reasignacion_Fecha) AS Reasignacion_Fecha, Reasignacion_TicketId 
                        FROM tbl_tickets_reasignation GROUP BY Reasignacion_TicketId ) AS b ON a.TicketId = b.Reasignacion_TicketId
                    WHERE a.active_status = 1 AND ( a.Ticket_EstatusId = 5 || a.Ticket_EstatusId = 3 ) 
                    AND a.Ticket_FechaConcluido <> '0000-00-00 00:00:00' 
                    AND a.id_activity IN (SELECT DISTINCT ActividadId FROM cat_ticket_actividad WHERE Actividad_AreaId = $idArea) 
                    AND a.Ticket_FechaCreacion BETWEEN '$starDate' AND '$endDate'
                    ORDER BY a.id_activity DESC; ")->getResultArray();
                    for ($i = 0; $i < count($actividades); $i++) { // for de las actividades que realiza el ing
                        $hrsTotal =  0; // se reestablece las hrs que se insertaran
                        for ($d = 0; $d < count($actFechas); $d++) { // form para validar las fechas de las actividades del Usuario
                            if ($actividades[$i]['id_activity'] == $actFechas[$d]['id_activity']) {
                                $fechaInicio = $actFechas[$d]['FechaInicio'];
                                $fechaFin = $actFechas[$d]['Ticket_FechaConcluido'];
                                $diff = (new DateTime($fechaInicio))->diff(new DateTime($fechaFin));
                                $fecha1 = strtotime($fechaInicio);
                                $fecha2 = strtotime($fechaFin);
                                $finSemana = 0;
                                for ($fecha1; $fecha1 <= $fecha2; $fecha1 = strtotime('+1 day ' . date('Y-m-d', $fecha1))) {
                                    if ((date('D', $fecha1) == 'Sun') || (date('D', $fecha1) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                        $finSemana++;
                                    }
                                }
                                $diasReales = ($diff->m > 0) ? ($diff->m * 30) + $diff->d  : $diff->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                $hrsTotal = $hrsTotal + floatval((($diasReales - $finSemana) * 24) + $diff->h + ($diff->i / 60));
                            }
                        }
                        $actividades[$i]['total_horas'] = round($hrsTotal, 2);
                    }

                    $laboral = $this->db->query("SELECT b.TecnicoId,
                    CONCAT(b.Tecnico_Nombre,' ',b.Tecnico_ApellidoPaterno) AS nombre,
                    FORMAT(
                        ( 
                        SUM( CASE  WHEN a.Ticket_EstatusId = 3 THEN 1 ELSE 0  END  ) 
                        + 
                        SUM( CASE  WHEN a.Ticket_EstatusId = 5 THEN 1 ELSE 0  END  )
                        ) 
                        /
                        (
                        SUM( CASE  WHEN a.active_status = 1 THEN 1 ELSE 0  END  ) 
                        -
                        SUM( CASE  WHEN a.Ticket_EstatusId = 4 THEN 1 ELSE 0  END  )
                        )
                        *100
                    ,2) AS porcentaje,
                    CONCAT('') AS total_horas
                    FROM tbl_tickets_request AS a
                    JOIN cat_ticket_tecnico AS b ON a.Ticket_TecnicoId = b.TecnicoId
                    WHERE a.Ticket_TecnicoId IN (SELECT DISTINCT TecnicoId FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $idArea) 
                    AND a.active_status = 1 GROUP BY a.Ticket_TecnicoId 
                    -- AND a.Ticket_FechaCreacion BETWEEN '$starDate' AND '$endDate'
                    ORDER BY nombre ASC")->getResultArray();


                    $LaboralFechas = $this->db->query("SELECT
                            Ticket_TecnicoId,
                            a.Ticket_FechaConcluido,
                            CASE
                        WHEN b.Reasignacion_Fecha IS NOT NULL THEN
                            b.Reasignacion_Fecha
                        ELSE
                            a.Ticket_FechaCreacion
                        END AS FechaInicio
                        FROM
                            tbl_tickets_request AS a
                        JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
                        LEFT JOIN (
                            SELECT
                                MAX(Reasignacion_Fecha) AS Reasignacion_Fecha,
                                Reasignacion_TicketId
                            FROM
                                tbl_tickets_reasignation
                            GROUP BY
                                Reasignacion_TicketId
                        ) AS b ON a.TicketId = b.Reasignacion_TicketId
                        WHERE
                            a.active_status = 1
                        AND (
                            a.Ticket_EstatusId = 5 || a.Ticket_EstatusId = 3
                        )
                        AND a.Ticket_FechaConcluido <> '0000-00-00 00:00:00'
                        AND a.Ticket_TecnicoId IN (
                            SELECT DISTINCT
                                TecnicoId
                            FROM
                                cat_ticket_tecnico
                            WHERE
                                Tecnico_AreaId = $idArea
                        )
                        ORDER BY
                            a.Ticket_TecnicoId DESC ")->getResultArray();

                    for ($i = 0; $i < count($laboral); $i++) { // for de las actividades que realiza el ing
                        $hrsTotal =  0; // se reestablece las hrs que se insertaran
                        for ($d = 0; $d < count($LaboralFechas); $d++) { // form para validar las fechas de las actividades del Usuario
                            if ($laboral[$i]['TecnicoId'] == $LaboralFechas[$d]['Ticket_TecnicoId']) {
                                $fechaInicio = $LaboralFechas[$d]['FechaInicio'];
                                $fechaFin = $LaboralFechas[$d]['Ticket_FechaConcluido'];
                                $diff = (new DateTime($fechaInicio))->diff(new DateTime($fechaFin));
                                $fecha1 = strtotime($fechaInicio);
                                $fecha2 = strtotime($fechaFin);
                                $finSemana = 0;
                                for ($fecha1; $fecha1 <= $fecha2; $fecha1 = strtotime('+1 day ' . date('Y-m-d', $fecha1))) {
                                    if ((date('D', $fecha1) == 'Sun') || (date('D', $fecha1) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                        $finSemana++;
                                    }
                                }
                                // echo $finSemana .'<br>';
                                $diasReales = ($diff->m > 0) ? ($diff->m * 30) + $diff->d  : $diff->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                $hrsTotal = $hrsTotal + floatval((($diasReales - $finSemana) * 24) + $diff->h + ($diff->i / 60));
                                // echo 'se suma las horas al inge : '. $laboral[$i]['TecnicoId'].' -> '.$hrsTotal .'<br>';
                            }
                        }
                        $laboral[$i]['total_horas'] = round($hrsTotal, 2);
                    }
                }
                $data = ['tipo' => 1, 'cantidad_tickets' => $tickets, 'cumplimiento' => $cumplimiento, 'actividades' => $actividades, 'laboral' => $laboral];
            } else if (session()->access_tickets != false) {
                $idUser = session()->id_user;
                if (session()->access_tickets == 3) {
                    $tickets = $this->db->query("SELECT SUM( CASE WHEN `status` = 1 OR `status` = 2 THEN 1 ELSE 0  END  ) AS nuevos,
                            SUM( CASE WHEN `status` = 3 THEN 1 ELSE 0  END  ) AS proceso,
                            SUM( CASE WHEN `status` = 4 THEN 1 ELSE 0  END  ) AS concluido,
                            SUM( CASE WHEN `status` = 0 THEN 1 ELSE 0  END  ) AS cancelado,
                            SUM( CASE WHEN `status` = 5 THEN 1 ELSE 0  END  ) AS cerrado,
                            SUM( CASE WHEN active_status = 1 THEN 1 ELSE 0  END  ) AS total
                            FROM tbl_tickets_maintenance_request
                            WHERE id_tecnico = $idUser AND active_status = 1;")->getRow();
                    if ($tickets->total == null) {
                        return json_encode('DATOS NO EXISTENTES');
                    }
                    $cumplimiento = round((((intval($tickets->cerrado) + intval($tickets->concluido)) / (intval($tickets->total) - intval($tickets->cancelado))) * 100), 2);

                    $actividades = $this->db->query("SELECT a.id_activity, COUNT(a.id_activity) AS cant_tickets, CONCAT('') AS total_horas, c.Actividad_Actividad
                    FROM tbl_tickets_maintenance_request AS a JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
                    WHERE a.active_status = 1 AND (a.`status` = 4 OR a.`status` = 5)
                    AND a.id_tecnico = $idUser GROUP BY a.id_activity")->getResultArray();

                    $actFechas = $this->db->query("SELECT a.id_activity, a.process_star_at, a.process_end_at, star_refac, end_refac
                    FROM tbl_tickets_maintenance_request AS a
                    LEFT JOIN (SELECT MAX(CONCAT(date_end,' ',time_end)) AS end_refac, id_order FROM tbl_tickets_maintenance_items GROUP BY id_order) AS b ON a.id_order = b.id_order
                    LEFT JOIN (SELECT MIN(CONCAT(date_star,' ',time_star)) AS star_refac, id_order FROM tbl_tickets_maintenance_items GROUP BY id_order) AS c ON a.id_order = c.id_order
                    WHERE a.id_tecnico = $idUser 
                    AND (`status` = 4 OR `status` = 5)
                    AND a.active_status = 1 ORDER BY a.id_activity DESC")->getResultArray();

                    for ($i = 0; $i < count($actividades); $i++) { // for de las actividades que realiza el ing
                        $hrsTotal =  0; // se reestablece las hrs que se insertaran
                        for ($d = 0; $d < count($actFechas); $d++) { // form para validar las fechas de las actividades del Usuario
                            if ($actividades[$i]['id_activity'] == $actFechas[$d]['id_activity']) {
                                $fechaInicio = $actFechas[$d]['process_star_at'];
                                $fechaFin = $actFechas[$d]['process_end_at'];
                                $diff = (new DateTime($fechaInicio))->diff(new DateTime($fechaFin));
                                $fecha1 = strtotime($fechaInicio);
                                $fecha2 = strtotime($fechaFin);
                                $finSemana = 0;
                                for ($fecha1; $fecha1 <= $fecha2; $fecha1 = strtotime('+1 day ' . date('Y-m-d', $fecha1))) {
                                    if ((date('D', $fecha1) == 'Sun') || (date('D', $fecha1) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                        $finSemana++;
                                    }
                                }
                                if ($actFechas[$d]['star_refac'] != null && $actFechas[$d]['end_refac'] != null) {
                                    $fechaInicioRefac = $actFechas[$d]['star_refac'];
                                    $fechaFinRefac = $actFechas[$d]['end_refac'];
                                    $diffR = (new DateTime($fechaInicioRefac))->diff(new DateTime($fechaFinRefac));
                                    $fecha3 = strtotime($fechaInicioRefac);
                                    $fecha4 = strtotime($fechaFinRefac);
                                    $finSemanaRefac = 0;
                                    for ($fecha3; $fecha3 <= $fecha4; $fecha3 = strtotime('+1 day ' . date('Y-m-d', $fecha3))) {
                                        if ((date('D', $fecha3) == 'Sun') || (date('D', $fecha3) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                            $finSemanaRefac++;
                                        }
                                    }
                                    $diasRealesRefac = ($diffR->m > 0) ? ($diffR->m * 30) + $diffR->d  : $diffR->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                    $horasTotalesMenosFinSemanaRefac = floatval((($diasRealesRefac - $finSemanaRefac) * 24) + $diffR->h + ($diffR->i / 60));
                                } else {
                                    $horasTotalesMenosFinSemanaRefac = 0.00;
                                }
                                $diasReales = ($diff->m > 0) ? ($diff->m * 30) + $diff->d  : $diff->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                $horasTotalesMenosFinSemana = floatval((($diasReales - $finSemana) * 24) + $diff->h + ($diff->i / 60));

                                $hrsTotal = $hrsTotal + floatval($horasTotalesMenosFinSemana - $horasTotalesMenosFinSemanaRefac);
                            }
                        }
                        $actividades[$i]['total_horas'] = round($hrsTotal, 2);
                    }
                } else {
                    $tickets = $this->db->query("SELECT 
                        SUM( CASE WHEN Ticket_EstatusId = 1 THEN 1 ELSE 0  END  ) AS nuevos,
                        SUM( CASE WHEN Ticket_EstatusId = 2 THEN 1 ELSE 0  END  ) AS proceso,
                        SUM( CASE WHEN Ticket_EstatusId = 3 THEN 1 ELSE 0  END  ) AS concluido,
                        SUM( CASE WHEN Ticket_EstatusId = 4 THEN 1 ELSE 0  END  ) AS cancelado,
                        SUM( CASE WHEN Ticket_EstatusId = 5 THEN 1 ELSE 0  END  ) AS cerrado,
                        COUNT( TicketId ) AS total
                    FROM tbl_tickets_request WHERE Ticket_TecnicoId = $idUser AND active_status = 1")->getRow();
                    if ($tickets->total == null) {
                        return json_encode('DATOS NO EXISTENTES');
                    }
                    $cumplimiento = round((((intval($tickets->cerrado) + intval($tickets->concluido)) / (intval($tickets->total) - intval($tickets->cancelado))) * 100), 2);

                    $actividades = $this->db->query("SELECT a.id_activity, COUNT(a.id_activity) AS cant_tickets, CONCAT('') AS total_horas, c.Actividad_Actividad
                    FROM tbl_tickets_request AS a JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
                    WHERE a.active_status = 1 AND ( a.Ticket_EstatusId = 5 || a.Ticket_EstatusId = 3 )
                    AND a.Ticket_FechaConcluido <> '0000-00-00 00:00:00' AND a.Ticket_TecnicoId = $idUser GROUP BY a.id_activity")->getResultArray();

                    $actFechas = $this->db->query("SELECT a.id_activity, a.Ticket_FechaConcluido, 
                    CASE WHEN b.Reasignacion_Fecha IS NOT NULL THEN b.Reasignacion_Fecha ELSE a.Ticket_FechaCreacion END AS FechaInicio
                    FROM tbl_tickets_request AS a JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
                    LEFT JOIN ( SELECT MAX(Reasignacion_Fecha) AS Reasignacion_Fecha, Reasignacion_TicketId 
                        FROM tbl_tickets_reasignation GROUP BY Reasignacion_TicketId ) AS b ON a.TicketId = b.Reasignacion_TicketId
                    WHERE a.active_status = 1 AND ( a.Ticket_EstatusId = 5 || a.Ticket_EstatusId = 3 ) 
                    AND a.Ticket_FechaConcluido <> '0000-00-00 00:00:00' AND a.Ticket_TecnicoId = $idUser 
                    ORDER BY a.id_activity DESC; ")->getResultArray();
                    for ($i = 0; $i < count($actividades); $i++) { // for de las actividades que realiza el ing
                        $hrsTotal =  0; // se reestablece las hrs que se insertaran
                        for ($d = 0; $d < count($actFechas); $d++) { // form para validar las fechas de las actividades del Usuario
                            if ($actividades[$i]['id_activity'] == $actFechas[$d]['id_activity']) {
                                $fechaInicio = $actFechas[$d]['FechaInicio'];
                                $fechaFin = $actFechas[$d]['Ticket_FechaConcluido'];
                                $diff = (new DateTime($fechaInicio))->diff(new DateTime($fechaFin));
                                $fecha1 = strtotime($fechaInicio);
                                $fecha2 = strtotime($fechaFin);
                                $finSemana = 0;
                                for ($fecha1; $fecha1 <= $fecha2; $fecha1 = strtotime('+1 day ' . date('Y-m-d', $fecha1))) {
                                    if ((date('D', $fecha1) == 'Sun') || (date('D', $fecha1) == 'Sat')) { // se cuentan los fines de semana del inicio hasta el final
                                        $finSemana++;
                                    }
                                }
                                $diasReales = ($diff->m > 0) ? ($diff->m * 30) + $diff->d  : $diff->d; // si el tiempo de dif exite un mes o mas se convierte a dias
                                $hrsTotal = $hrsTotal + floatval((($diasReales - $finSemana) * 24) + $diff->h + ($diff->i / 60));
                            }
                        }
                        $actividades[$i]['total_horas'] = round($hrsTotal, 2);
                    }
                }
                $data = ['tipo' => 2, 'cantidad_tickets' => $tickets, 'cumplimiento' => $cumplimiento, 'actividades' => $actividades];
            }
            return json_encode($data);
        } catch (Exception $e) {
            return json_encode(false);
        }
    }

    /*
    * --------------------------------------------------------------------
    * Rutas para el modulo de TICKETS MATENIMIENTO Creación, Rechazadas, Edición, Actualizacion 
    * --------------------------------------------------------------------
    */

    public function viewTableMaintenance()
    {
        // $company = session()->access_tickets;
        $company = 3;
        $query = $this->db->query("SELECT ActividadId, Actividad_Actividad FROM cat_ticket_actividad WHERE Actividad_AreaId = $company AND active_status = 1")->getResult();
        $query1 = $this->db->query("SELECT id_user, CONCAT(`name`,' ',surname,' ',second_surname) AS nombre FROM tbl_users WHERE active_status = 1 ORDER BY `name` ASC")->getResult();
        $query2 = $this->db->query("SELECT TecnicoId, CONCAT(Tecnico_Nombre,' ',Tecnico_ApellidoPaterno) AS nombre FROM cat_ticket_tecnico WHERE Tecnico_AreaId = $company AND Tecnico_Activo = 1 ORDER BY Tecnico_Nombre ASC")->getResult();
        $query3 = $this->db->query("SELECT id_fail, name_fail FROM cat_tickets_maintenance_code_fail WHERE active_status = 1")->getResult();
        $data = ['actvidad' => $query, 'usuarios' => $query1, 'inge' => $query2, 'codigos' => $query3];
        return ($this->is_logged) ? view('tickets/tablero_mantenimiento', $data) : redirect()->to(site_url());
    }

    public function ticketsMaintenanceALL()
    {
        try {
            $company = session()->access_tickets;
            $idUser = session()->id_user;
            if ($company == 3) {
                $data = $this->db->query("SELECT a.`status` AS estatus, a.id_order, a.description, a.name_user, a.created_at, b.Actividad_Actividad AS actividad, 
            CONCAT(c.Tecnico_Nombre,' ',c.Tecnico_ApellidoPaterno) AS tecnico, a.cancel_at
            FROM tbl_tickets_maintenance_request AS a
            JOIN cat_ticket_actividad As b ON a.id_activity = b.ActividadId
            LEFT JOIN cat_ticket_tecnico AS c ON a.id_tecnico = c.TecnicoId
            WHERE a.active_status = 1 AND a.ticket_type <> 2 LIMIT 1000")->getResult();
            } else if ($idUser == 1283){
                $data = $this->db->query("SELECT a.`status` AS estatus, a.id_order, a.description, a.name_user, a.created_at, b.Actividad_Actividad AS actividad, 
                CONCAT(c.Tecnico_Nombre,' ',c.Tecnico_ApellidoPaterno) AS tecnico, a.cancel_at
                FROM tbl_tickets_maintenance_request AS a
                JOIN cat_ticket_actividad As b ON a.id_activity = b.ActividadId
                LEFT JOIN cat_ticket_tecnico AS c ON a.id_tecnico = c.TecnicoId
                WHERE a.active_status = 1 AND a.ticket_type == 2 LIMIT 1000")->getResult();
            } else {
                $data = $this->db->query("SELECT a.`status` AS estatus, a.id_order, a.description, a.name_user, a.created_at, b.Actividad_Actividad AS actividad, 
            CONCAT(c.Tecnico_Nombre,' ',c.Tecnico_ApellidoPaterno) AS tecnico, a.cancel_at
            FROM tbl_tickets_maintenance_request AS a
            JOIN cat_ticket_actividad As b ON a.id_activity = b.ActividadId
            LEFT JOIN cat_ticket_tecnico AS c ON a.id_tecnico = c.TecnicoId
            WHERE a.active_status = 1 AND (a.id_user = $idUser OR a.payroll_number IN (
                SELECT payroll_number FROM tbl_assign_manager_tikcet_maintenance WHERE active_status = 1 AND id_manager = $idUser ))
            LIMIT 1000")->getResult();
            }
            return json_encode($data);
        } catch (\Exception $th) {
            return json_encode(false);
        }
    }

    public function dataTicketMaintenance()
    {
        // agregar en quiery fecha y hora de inicio y fin de proceso como  AS star y AS end respectivamente
        $idRequest = $this->request->getPost('id_requets');
        $query = $this->db->query("SELECT a.`status`, a.created_at, a.cancel_at, a.motive_cancel, a.id_priority, a.name_user,
        CONCAT(b.Tecnico_Nombre,' ',b.Tecnico_ApellidoPaterno) AS tecnico,
        CONCAT(a.equip,' / ', a.id_machine) AS equipo, c.Actividad_Actividad, d.name_fail, a.description,
       a.cause_code, a.work_done, a.process_star_at AS star, a.process_end_at AS `end`
       FROM tbl_tickets_maintenance_request AS a 
       LEFT JOIN cat_ticket_tecnico AS b ON a.id_tecnico = b.TecnicoId
       JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
       JOIN cat_tickets_maintenance_code_fail AS d ON a.id_fail = d.id_fail
       WHERE a.id_order = $idRequest")->getRow();
        $date = date("d/m/Y", strtotime($query->created_at));
        $dateTimeCancel = date("d/m/Y H:i", strtotime($query->cancel_at));
        $hour = date("H:i", strtotime($query->created_at));
        $query1 = $this->db->query("SELECT id_item, spare_part_order_number AS num_order, code_spare_part, assigned_buyer_name, estimated_delivery_date, date_star, date_end 
        FROM tbl_tickets_maintenance_items 
         WHERE id_order = $idRequest AND active_status = 1")->getResult();
        // $query1 = $this->db->query("SELECT * FROM tbl_tickets_accion WHERE Accion_TicketId = $idRequest AND active_status = 1")->getResult();
        $data =  ['info' => $query, 'fecha' => $date, 'hora' => $hour, 'fecha_cancel' => $dateTimeCancel, 'refaccion' => $query1, 'nivel' => session()->manager_tickets];
        return ($query) ? json_encode($data) : json_encode(false);
    }

    public function insertMaintenance()
    {
        try {
            $idUser = session()->id_user;
            $idArea =  $this->request->getPost('sel-area-equipo');
            $equip =  $this->request->getPost('sel-tipo-equipo');
            $idMachine = ($equip === "OTRO") ?  $this->request->getPost('sel-otro') : $this->request->getPost('sel-clave');
            $idActivity =  $this->request->getPost('sel-mantenimiento');
            $idFail =  $this->request->getPost('sel-codigo');
            $description =  $this->request->getPost('txt-descripcion');
            $toDay = date('Y-m-d H:i:s');

            $dataInsert = [
                'id_activity' => $idActivity,
                'id_fail' => $idFail,
                // 'priority' => $,
                'id_area' => $idArea,
                'id_machine' => $idMachine,
                'equip' => $equip,
                'description' => $description,
                'id_user' => $idUser,
                'payroll_number' => session()->payroll_number,
                'name_user' => session()->name . " " . session()->surname,
                'id_depto' => session()->id_depto,
                'name_depto' => session()->departament,
                'status' => 1,
                'created_at' => $toDay
            ];

            $insert = $this->maintenanceRequestModel->insert($dataInsert);
            if ($insert != true) {
                return json_encode('error de insercion');
            }
            $query = $this->db->query("SELECT name_fail FROM cat_tickets_maintenance_code_fail WHERE id_fail = $idFail")->getRow();
            $query1 = $this->db->query("SELECT Actividad_Actividad FROM cat_ticket_actividad WHERE ActividadId = $idActivity")->getRow();

            $email = $this->db->query("SELECT email, concat(`name`,' ', surname) AS nombre FROM tbl_users WHERE id_user IN 
                (SELECT id_director FROM tbl_assign_manager_tikcet_maintenance WHERE payroll_number = " . session()->payroll_number . ")")->getRow();
            $dir_email = changeEmail($email->email);

            $mail = new PHPMailer();
            $mail->CharSet = "UTF-8";
            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // $dir_email = 'hrivas@walworth.com.mx';

            //Server settings
            $mail->isSMTP();
            $mail->SMTPAuth = false;
            $mail->Host = 'localhost';
            $mail->Username = 'requisiciones@walworth.com';
            $mail->Password = 'Walworth321$';
            $mail->Port = 25;
            $mail->setFrom('notificacion@grupowalworth.com', 'Nuevo Ticket');

            // Add a recipient
            $mail->addAddress($dir_email, $email->nombre);
            // Name is optional
            // $mail->addReplyTo('hrivas@walworth.com.mx', 'Informacion del Sistema');

            // $mail->addBCC('rcruz@walworth.com.mx');
            // $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $datas = ['ticket' => $dataInsert, 'actividad' => $query1, 'falla' => $query, 'tipo' => 1, 'folio' => $this->db->insertID()];
            $email_template = view('notificaciones/notify_tickets_mantenimiento', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Tickets de Mantenimiento';
            $mail->send();
            return ($insert) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode('error: ' . $th);
        }
    }

    public function authorizeMaintenance()
    {
        $idRequest = $this->request->getPost('id_request');
        $idUser = session()->id_user;
        $toDay = date('Y-m-d H:i:s');

        $dataUpdate = [
            'id_manager_authorize' => $idUser,
            'status' => 2,
            'authotize_at' => $toDay
        ];
        $this->notificacionTicketsMaintenance($idRequest, 2);
        $update = $this->maintenanceRequestModel->update($idRequest, $dataUpdate);
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function assigMaintenance()
    {
        $toDay = date('Y-m-d H:i:s');
        $idRequest = $this->request->getPost('id_request');
        $idTecnic =  $this->request->getPost('sel-asignar-tecnico');
        $idPriority =  $this->request->getPost('sel-asignar-prioridad');
        $query = $this->db->query("SELECT id, job FROM cat_job_position WHERE
        id IN ( SELECT id_job_position FROM tbl_users WHERE id_user = $idTecnic )")->getRow();

        $dataUpdate = [
            'id_tecnico' => $idTecnic,
            'id_jop_tecnico' => $query->id,
            'jop_tecnico' => $query->job,
            'id_priority' => $idPriority,
            'status' => 3,
            'id_user_process_star' => session()->id_user,
            'process_star_at' => $toDay
        ];
        $update = $this->maintenanceRequestModel->update($idRequest, $dataUpdate);
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function concludMaintenance()
    {
        $idRequest = $this->request->getPost('id_request');
        $query = $this->db->query("SELECT COUNT(id_item) AS n FROM tbl_tickets_maintenance_items WHERE active_status = 1
        AND date_end IS NULL AND id_order = $idRequest")->getRow();
        if ($query->n == 0) {
            $causeCode =  ($this->request->getPost('sel-concluir-causa') == 'Otro') ?
                $this->request->getPost('sel-concluir-otro-causa')
                :
                $this->request->getPost('sel-concluir-causa');
            $workDone = $this->request->getPost('txt-concluir-realizado');
            $toDay = date('Y-m-d H:i:s');
            $dataUpdate = [
                'cause_code' => $causeCode,
                'work_done' => $workDone,
                'status' => 4,
                'id_user_process_end' => session()->id_user,
                'process_end_at' => $toDay,
            ];
            $update = $this->maintenanceRequestModel->update($idRequest, $dataUpdate);
            $this->notificacionTicketsMaintenance($idRequest, 4);
        } else {
            $update = "pz";
        }
        return json_encode($update);
    }

    public function clossedMaintenance()
    {
        $idRequest = $this->request->getPost('id_request');
        $idUser = session()->id_user;
        $toDay = date('Y-m-d H:i:s');
        $dataUpdate = [
            'id_manager_accept' => $idUser,
            'status' => 5,
            'accept_at' => $toDay,
        ];
        $update = $this->maintenanceRequestModel->update($idRequest, $dataUpdate);
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function cancelMaintenance()
    {
        $idRequest = $this->request->getPost('id_request');
        $motive = $this->request->getPost('txt-cancelar-motivo');
        $idUser = session()->id_user;
        $toDay = date('Y-m-d H:i:s');
        $dataUpdate = [
            'id_cancel' => $idUser,
            'status' => 0,
            'motive_cancel' => $motive,
            'cancel_at' => $toDay,
        ];
        $update = $this->maintenanceRequestModel->update($idRequest, $dataUpdate);
        return ($update) ? json_encode(true) : json_encode(false);
    }

    public function sparePartMaintenance()
    {
        try {
            $idUser = session()->id_user;
            $toDay = date('Y-m-d');
            $toTime = date('H:i:s');
            if ($this->request->getPost('fase-refaccion') == 1) {
                $idRequest = $this->request->getPost('id_request');
                $sparePartOrderNumber = $this->request->getPost('orden_compra');
                $codePz = $this->request->getPost('orden_codigo');
                $nameBuyer = $this->request->getPost('orden_nombre');
                $dateContemplated = $this->request->getPost('orden_fecha');
                $pieceQuantity = $this->request->getPost('cant_pz');
                $priceUnit = $this->request->getPost('costo_unitario');
                $totalPrice = $this->request->getPost('monto');
                $dataInsert = [
                    'id_order' => $idRequest,
                    'spare_part_order_number' => $sparePartOrderNumber,
                    'code_spare_part' => $codePz,
                    'assigned_buyer_name' => $nameBuyer,
                    'estimated_delivery_date' => $dateContemplated,
                    'piece_quantity' => $pieceQuantity,
                    'price_unit' => $priceUnit,
                    'total_price' => $totalPrice,
                    'id_date_star' => $idUser,
                    'date_star' => $toDay,
                    'time_star' => $toTime,
                ];
                $repair = $this->maintenanceItemsModel->insert($dataInsert);
            } else if ($this->request->getPost('fase-refaccion') == 2) {
                $idItem = $this->request->getPost('id_item');
                $dataUpdate = [
                    'id_date_end' => $idUser,
                    'date_end' => $toDay,
                    'time_end' => $toTime,
                ];
                $repair = $this->maintenanceItemsModel->update($idItem, $dataUpdate);
            }
            return ($repair) ? json_encode(true) : json_encode(false);
        } catch (\Exception $th) {
            return json_encode('error: ' . $th);
        }
    }

    public function searchTicketsMaintenance()
    {
        $sqlPriority = ($this->request->getPost('prioridad') == '') ? '' : 'AND a.id_priority = ' . $this->request->getPost('prioridad');
        $sqlActivity = ($this->request->getPost('actividad') == '') ? '' : 'AND a.id_activity = ' . $this->request->getPost('actividad');
        $sqlUser = ($this->request->getPost('usuario') == '') ? '' : 'AND a.id_user = ' . $this->request->getPost('usuario');
        $sqlIng = ($this->request->getPost('tecnico') == '') ? '' : 'AND a.id_tecnico = ' . $this->request->getPost('tecnico');

        $data = $this->db->query("SELECT a.`status` AS estatus, a.id_order, a.description, a.name_user, a.created_at, b.Actividad_Actividad AS actividad, 
        CONCAT(c.Tecnico_Nombre,' ',c.Tecnico_ApellidoPaterno) AS tecnico, a.cancel_at
        FROM tbl_tickets_maintenance_request AS a
        JOIN cat_ticket_actividad As b ON a.id_activity = b.ActividadId
        LEFT JOIN cat_ticket_tecnico AS c ON a.id_tecnico = c.TecnicoId
        WHERE a.active_status = 1 $sqlPriority $sqlActivity $sqlUser $sqlIng ORDER BY a.id_order DESC LIMIT 1000")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function searchTicketsForDateMaintenance()
    {
        $dateStar = $this->request->getPost('date_star');
        $dateEnd = date("Y-m-d", strtotime(($this->request->getPost('date_end')) . "+ 1 days"));
        $data = $this->db->query("SELECT a.`status` AS estatus, a.id_order, a.description, a.name_user, a.created_at, b.Actividad_Actividad AS actividad, 
        CONCAT(c.Tecnico_Nombre,' ',c.Tecnico_ApellidoPaterno) AS tecnico, a.cancel_at
        FROM tbl_tickets_maintenance_request AS a
        JOIN cat_ticket_actividad As b ON a.id_activity = b.ActividadId
        LEFT JOIN cat_ticket_tecnico AS c ON a.id_tecnico = c.TecnicoId
        WHERE a.active_status = 1 AND a.created_at BETWEEN '$dateStar' AND '$dateEnd' ORDER BY a.id_order DESC LIMIT 1000")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function searchFolioTicketsMaintenance()
    {
        $folio = $this->request->getPost('folio');
        $data = $this->db->query("SELECT a.`status` AS estatus, a.id_order, a.description, a.name_user, a.created_at, b.Actividad_Actividad AS actividad, 
        CONCAT(c.Tecnico_Nombre,' ',c.Tecnico_ApellidoPaterno) AS tecnico, a.cancel_at
        FROM tbl_tickets_maintenance_request AS a
        JOIN cat_ticket_actividad As b ON a.id_activity = b.ActividadId
        LEFT JOIN cat_ticket_tecnico AS c ON a.id_tecnico = c.TecnicoId
        WHERE a.active_status = 1 AND  a.id_order LIKE '%$folio%' ORDER BY a.id_order DESC LIMIT 1000")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function dataMachineMaintenance()
    {

        $idArea = $this->request->getPost('id_area');
        $equip = $this->request->getPost('equip');
        if ($idArea != null && $idArea != 'null' && !empty($idArea) && $idArea != '') {
            $query = $this->db->query("SELECT DISTINCT equip FROM cat_tickets_maintenance_machines WHERE active_status = 1 AND id_area = $idArea")->getResult();
        }
        if ($equip != null && $equip != 'null' && !empty($equip) && $equip != '') {
            $query = $this->db->query("SELECT DISTINCT id_machine FROM cat_tickets_maintenance_machines WHERE active_status = 1 AND id_area = $idArea AND equip LIKE '$equip'")->getResult();
        }
        return ($query) ? json_encode($query) : json_encode(false);
    }

    public function notificacionTicketsMaintenance($id, $status)
    {
        try {
            // aqui
            $query = $this->db->query("SELECT a.created_at, b.Actividad_Actividad, name_fail, CONCAT(a.equip,' / ',a.id_machine) AS equipo, a.description, a.id_user, a.equip
                FROM tbl_tickets_maintenance_request As a
                JOIN cat_ticket_actividad AS b ON a.id_activity = b.ActividadId
                JOIN cat_tickets_maintenance_code_fail AS c ON a.id_fail = c.id_fail
            WHERE a.id_order = $id")->getRow();
            $query1 = $this->db->query("SELECT CONCAT(`name`,' ',surname) AS nombre, email
            FROM tbl_users WHERE id_user IN
                (SELECT id_manager FROM tbl_assign_manager_tikcet_maintenance WHERE payroll_number IN 
                    (SELECT payroll_number FROM tbl_users WHERE id_user = $query->id_user))")->getRow();
            $mail = new PHPMailer();
            $mail->CharSet = "UTF-8";
            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            //Server settings
            $mail->isSMTP();
            $mail->SMTPAuth = false;
            $mail->Host = 'localhost';
            $mail->Username = 'requisiciones@walworth.com';
            $mail->Password = 'Walworth321$';
            $mail->Port = 25;

            // Add a recipient
            if ($status == 2) {
                $mail->setFrom('notificacion@grupowalworth.com', 'Ticket Autorizado');
                if ($query->equip == "CNC CONTROL NUMERICO") {
                    $mail->addAddress('hrivas@walworth.com.mx', 'JEFE DE MANTENIMIENTO');
                    // $mail->addAddress('daespinoza@walworth.com.mx', 'DANIEL ESPINOZA MEDINA');  // SOLO CNC/
                } else {
                    $mail->addAddress('hrivas@walworth.com.mx', 'CHICOS DE MANTENIMIENTO');
                    /* $mail->addAddress('rsanchez@walworth.com.mx', 'RUBEN SANCHEZ RUIZ');
                    $mail->addAddress('jmorales@walworth.com.mx', 'JUAN MORALES DOROTEO');
                    $mail->addAddress('mantenimiento@walworth.com.mx', 'CARLOS CHOREÑO'); */
                }
            } else {
                $dir_email = changeEmail($query1->email);
                $mail->setFrom('notificacion@grupowalworth.com', 'Proceso de Ticket Concluido');
                $dir_email = 'hrivas@walworth.com.mx';
                $mail->addAddress($dir_email, $query1->nombre);
            }
            // Name is optional
            // $mail->addReplyTo('hrivas@walworth.com.mx', 'Informacion del Sistema');

            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $datas = ['datos' => $query, 'tipo' => $status, 'folio' => $id];
            $email_template = view('notificaciones/notify_tickets_mantenimiento', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Tickets de Mantenimiento';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function pdfRequestTickets($id = null)
    {
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT a.id_order, 
        a.created_at, a.cancel_at, a.authotize_at, a.process_star_at AS star, a.process_end_at AS `end`, a.accept_at,
        a.motive_cancel,
         a.id_priority, a.name_user, a.name_depto, a.payroll_number, a.jop_tecnico, 
            CONCAT(b.Tecnico_Nombre,' ',b.Tecnico_ApellidoPaterno) AS tecnico,
            CONCAT(a.equip,' / ', a.id_machine) AS equipo, 
            CONCAT(e.`name`,' ',e.surname,' ',e.second_surname) AS manager_authorize,
            CONCAT(f.`name`,' ',f.surname,' ',f.second_surname) AS manager_aceept,
            c.Actividad_Actividad, d.name_fail, a.description, a.cause_code, a.work_done
            FROM tbl_tickets_maintenance_request AS a 
               LEFT JOIN cat_ticket_tecnico AS b ON a.id_tecnico = b.TecnicoId
               JOIN cat_tickets_maintenance_code_fail AS d ON a.id_fail = d.id_fail
               JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
               JOIN tbl_users AS e ON e.id_user = a.id_manager_authorize
               JOIN tbl_users AS f ON f.id_user = a.id_manager_accept
            WHERE MD5(concat('" . $key . "',id_order))='" . $id . "'")->getRow();
        $query1 = $this->db->query("SELECT id_item, spare_part_order_number AS num_order, code_spare_part, 
        assigned_buyer_name, estimated_delivery_date, CONCAT(date_star, time_star) AS date_star, CONCAT(date_end, time_end) AS date_end
            FROM tbl_tickets_maintenance_items WHERE active_status = 1 AND id_order = $query->id_order")->getResult();
        $data = [
            "request" => $query,
            'items' => $query1
        ];
        $html2 = view('pdf/pdf_ticket_mantenimiento', $data);
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('ticket de Mantenimiento');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('ticket_matenimiento' . $id . '.pdf', 'I');
    }
}


/*
                $actividades = $this->db->query("SELECT c.Actividad_Actividad, count(a.TicketId) AS cant_tickets, 
                SUM( CASE WHEN b.Reasignacion_Fecha IS NOT NULL THEN 
                        TIMESTAMPDIFF( MINUTE, b.Reasignacion_Fecha, a.Ticket_FechaConcluido )
                    ELSE
                        TIMESTAMPDIFF( MINUTE, a.Ticket_FechaCreacion, a.Ticket_FechaConcluido )
                    END
                ) / 60 AS total_horas
                FROM tbl_tickets_request AS a JOIN cat_ticket_actividad AS c ON a.id_activity = c.ActividadId
                    LEFT JOIN ( SELECT MAX(Reasignacion_Fecha) AS Reasignacion_Fecha, Reasignacion_TicketId
                        FROM tbl_tickets_reasignation GROUP BY Reasignacion_TicketId ) 
                    AS b ON a.TicketId = b.Reasignacion_TicketId
                WHERE a.active_status = 1
                AND ( a.Ticket_EstatusId = 5 || a.Ticket_EstatusId = 3 )
                AND a.Ticket_FechaConcluido <> '0000-00-00 00:00:00'
                AND a.Ticket_TecnicoId = 1063
                GROUP BY a.id_activity;")->getResult();
*/
