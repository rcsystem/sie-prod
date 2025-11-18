<?php

/**
 * ARCHIVO MODULO RECORRIDOS HSE
 * AUTOR:HORUS SAMAEL RIVAS PEDRAZA
 * EMAIL:horus.riv.ped@gmail.com
 */

namespace App\Controllers\ToursHSE;


use App\Controllers\BaseController;

use App\Models\ToursHSEModels;
use App\Models\incidentsHSEModels;
use App\Models\imageEvidenceHSEModels;

use PHPMailer\PHPMailer\PHPMailer;
use Spipu\Html2Pdf\Html2Pdf;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;


class ToursHSE extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->toursRequestModel = new ToursHSEModels();
        $this->imageRequestModel = new imageEvidenceHSEModels();
        $this->incidentRequestModel = new incidentsHSEModels();

        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }
    function viewSafetyToursForm()
    {
        $query = $this->db->query("SELECT id_depto, departament FROM cat_departament WHERE active_status IN (1,3)  AND id_manager IS NOT NULL")->getResult();
        $data = ['departamentos' => $query];
        return ($this->is_logged) ? view('toursHSE/view_safety_tours_form', $data) : redirect()->to(site_url());
    }

    function viewFollowIncidentsCondition()
    {
        return ($this->is_logged) ? view('toursHSE/view_follow_conditions_tbl') : redirect()->to(site_url());
    }

    function viewFollowIncidentsActivitys()
    {
        return ($this->is_logged) ? view('toursHSE/view_follow_activitys_tbl') : redirect()->to(site_url());
    }

    function viewReportsAll()
    {
        $listDepto = (session()->id_user == 1063 || session()->id_user == 1 || session()->id_user == 75)
            ? "AND id_manager IS NOT NULL UNION SELECT 0, 'TODOS'"
            : "AND id_manager = " . session()->id_user;

        $this->db->query("SET lc_time_names = 'es_ES'");
        $query = $this->db->query("SELECT id_depto, departament FROM cat_departament WHERE active_status = 1 $listDepto ORDER BY id_depto;")->getResult();
        $query1 = $this->db->query("SELECT DATE_FORMAT( a.TipoCambio_Fecha, '%Y-%m' ) AS orden, 
            CONCAT(UPPER(MONTHNAME(TipoCambio_Fecha)),' ',DATE_FORMAT(a.TipoCambio_Fecha, '%Y'))  AS mes
            FROM cat_tipocambio AS a
            WHERE a.TipoCambio_Fecha BETWEEN '2024-01-01' AND NOW()
            GROUP BY orden
        ORDER BY a.TipoCambio_Fecha ASC;")->getResult();
        $data = ["departamentos" => $query, "fechas" => $query1];
        return ($this->is_logged) ? view('toursHSE/view_reports_ToursHSE', $data) : redirect()->to(site_url());
    }

    function searchRequestListByUsers()
    {
        $idUser = $this->request->getPost('id_user');
        $query = $this->db->query("SELECT DATE_FORMAT( created_at, '%d/%m/%Y' ) AS fecha,
            DATE_FORMAT( created_at, '%H:%i' ) AS hora,
            (SELECT CONCAT(`name`,' ',surname) FROM tbl_users AS ct1 WHERE ct1.id_user = id_created) AS responsable,
            CASE 
                WHEN severity_level = 1 THEN '#28A745'
                WHEN severity_level = 2 THEN '#FFC107'
                WHEN severity_level = 3 THEN '#DC3545'
                ELSE 'black'
            END AS color
            FROM tbl_ToursHSE_incidents 
            WHERE active_status = 1 
                AND type = 1 
                AND id_user = $idUser
                AND created_at > DATE_SUB(NOW(), INTERVAL 6 MONTH)
        ORDER BY created_at DESC")->getResult();
        return json_encode($query);
    }

    function searchCategoryList()
    {
        $typeCategory = $this->request->getPost("type_category");
        try {
            $query = $this->db->query("SELECT id_category, txt_category 
            FROM cat_toursHSE_categorys 
            WHERE active_status = 1 
        AND type_category = $typeCategory")->getResult();
            return json_encode($query);
        } catch (\Exception $e) {
            return json_encode(False);
        }
    }

    function getALLIncidentesConditions()
    {
        $query = $this->db->query("SELECT a.id_incidents, a.departament, a.description, 
            DATE_FORMAT(a.created_at,'%d/%m/%Y | %H:%i') AS created, a.response_opc, a.response_at, b.txt_category,c.url_image
            FROM tbl_ToursHSE_incidents AS a 
            JOIN cat_toursHSE_categorys AS b ON a.id_category = b.id_category
            LEFT JOIN tbl_ToursHSE_image_evidence AS c ON a.id_incidents = c.id_request AND c.type_request = 2
            WHERE a.active_status = 1 AND a.type = 2 AND requiere_follow = 1")
            ->getResult() ?? false;
        return json_encode($query);
    }

    function getALLIncidentesActivitys()
    {
        $query = $this->db->query("SELECT a.id_incidents, a.name_user, a.description, 
            DATE_FORMAT(a.created_at,'%d/%m/%Y | %H:%i') AS created, a.response_opc, a.response_at, b.txt_category
            FROM tbl_ToursHSE_incidents AS a 
            JOIN cat_toursHSE_categorys AS b ON a.id_category = b.id_category
            WHERE a.active_status = 1 AND a.type = 1 AND requiere_follow = 1")
            ->getResult() ?? false;
        return json_encode($query);
    }

    function insertToursRquest()
    {
        try {
            $idDepto = $this->request->getPost('id_departamento_recorrido');
            $useEpp = $this->request->getPost('uso_epp');
            $useUniform = $this->request->getPost('uso_uniforme');
            $useCel = $this->request->getPost('uso_celular');
            $useJewelry = $this->request->getPost('uso_bisuteria');
            $tiedHair = $this->request->getPost('cabello_recogido');
            $orderClean = $this->request->getPost('orden_limpieza');
            $unsafeActs = $this->request->getPost('actos_inseguros');
            $unsafeConditions = $this->request->getPost('condiciones_inseguras');
            $maintenanceWork = $this->request->getPost('trabajos_mantenimiento');
            $wasteManagement = $this->request->getPost('manejo_residuos');
            $dangerousWorks = $this->request->getPost('trabajos_peligrosos');
            $permissWorks = $this->request->getPost('permiso_trabajo');
            $personalNoInval = $this->request->getPost('personal_ajeno_inval');
            $eppNoInval = $this->request->getPost('epp_ajeno_inval');
            $qualification = $this->request->getPost('calificacion');
            $observation = $this->request->getPost('observacion');
            $query = $this->db->query("SELECT a.departament AS departamento, a.clave_depto,
                CONCAT(b.`name`,' ',b.surname,' ', b.second_surname) AS name_manager, email
                FROM cat_departament AS a
                LEFT JOIN tbl_users AS b ON a.id_manager = b.id_user
            WHERE id_depto = $idDepto")->getRow();

            $data = [
                'id_user_created' => session()->id_user,
                'date_created' => date("Y-m-d H:i:s"),
                'id_depto' => $idDepto,
                'departament' => $query->departamento,
                'clave_depto' => $query->clave_depto,
                'use_epp' => $useEpp,
                'use_uniform' => $useUniform,
                'use_cel' => $useCel,
                'use_jewelry' => $useJewelry,
                'tied_hair' => $tiedHair,
                'order_clean' => $orderClean,
                'unsafe_acts' => $unsafeActs,
                'unsafe_conditions' => $unsafeConditions,
                'maintenance_work' => $maintenanceWork,
                'waste_management' => $wasteManagement,
                'dangerous_works' => $dangerousWorks,
                'permiss_works' => $permissWorks,
                'personal_no_inval' => $personalNoInval,
                'epp_no_inval' => $eppNoInval,
                'qualification' => $qualification,
                'observation' => $observation,
            ];
            $this->db->transStart();
            $this->toursRequestModel->insert($data);
            $idRequest = $this->db->insertID();

            $image = $this->request->getFile('foto_recorrido_1') ?? null;
            $image2 = $this->request->getFile('foto_recorrido_2') ?? null;
            $imageUrl = null;
            $imageUrl2 = null;

            if ($image->getSize() > 0 || $image2->getSize() > 0) {
                $binder =  '../public/images/recorridosHSE/recorrido_' . $idRequest;
                if (!file_exists($binder)) {
                    mkdir($binder, 0777, true);
                }
                if ($image->getSize() > 0) {
                    $name = $image->getClientName();
                    $image = $image->move($binder,  $name);
                    $imageUrl = $binder . "/" . $name;
                    $dataImage = [
                        'id_request' => $idRequest,
                        'type_request' => 1,
                        'url_image' => $imageUrl,
                    ];
                    $this->imageRequestModel->insert($dataImage);
                }

                if ($image2->getSize() > 0) {
                    $name2 = $image2->getClientName();
                    $image2 = $image2->move($binder,  $name2);
                    $imageUrl2 = $binder . "/" . $name2;
                    $dataImage2 = [
                        'id_request' => $idRequest,
                        'type_request' => 1,
                        'url_image' => $imageUrl2,
                    ];
                    $this->imageRequestModel->insert($dataImage2);
                }
            }
            $dirEmail = changeEmail($query->email);

            $dirTitle = $query->name_manager;

            $mail = new PHPMailer();
            $mail->CharSet = "UTF-8";

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->isSMTP();
            $mail->SMTPAuth = false;
            $mail->Host = 'localhost';
            //$mail->Username = 'requisiciones@walworth.com';
            //$mail->Password = 'Walworth321$';
            $mail->Port = 25;
            $mail->setFrom('notificacion@walworth.com', 'Recorridos HSE');

            $mail->addAddress($dirEmail, $dirTitle);
            $mail->addCC('ldominguez@walworth.com.mx', 'LUIS ANGEL DOMINGUEZ');
            $mail->addCC('mlozano@walworth.com.mx', 'MARIANO LOZANO');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');;

            $mail->addBCC('rcruz@walworth.com.mx');
           // $mail->addBCC('hrivas@walworth.com.mx');
            if ($imageUrl) {
                $mail->addAttachment($imageUrl, 'evidencia_1.jpg');
            }
            if ($imageUrl2) {
                $mail->addAttachment($imageUrl2, 'evidencia_2.jpg');
            }

            $mail->isHTML(true);
            $datas = ['recorrido' => $data,];
            $email_template = view('notificaciones/notify_torusHSE_tours', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Recorridos HSE';
            $mail->send();

            $result = $this->db->transComplete();
            return json_encode($result);
        } catch (\Exception $e) {
            return json_encode($e);
        }
    }

    function insertIncidentRquest()
    {
        try {
            $type = $this->request->getPost('tipo_reporte');
            $requireSeguimiento = $this->request->getPost('opc_seguimiento');
            $requireRetro = $this->request->getPost('opc_retro_jefe');
            $idUser = ($this->request->getPost('id_usuario') != '') ? $this->request->getPost('id_usuario') : null;
            $nameUser = ($this->request->getPost('id_usuario') != '') ? $this->db->query("SELECT CONCAT(`name`,' ',surname,' ',second_surname) AS user_name FROM tbl_users WHERE id_user = $idUser")->getRow()->user_name : null;
            $idDepto = ($this->request->getPost('id_departamento') != '') ? $this->request->getPost('id_departamento') : $this->db->query("SELECT id_departament AS idDeptoSql FROM tbl_users WHERE id_user = $idUser")->getRow()->idDeptoSql;
            $severityLevel = ($this->request->getPost('valor_gravedad') != '') ? $this->request->getPost('valor_gravedad') : NULL;
            $idCategory = $this->request->getPost('tipo_incidencia');
            $description = $this->request->getPost('descripcion');
            $sanctionMessage = $this->request->getPost('correo_rh');
            if ($idDepto == 0) {
                $depto = trim($this->request->getPost('otro_depto'));
                $clave = 0000;
                $email = 'ldominguez@walworth.com.mx';
                $nameManager = 'LUIS ANGEL DOMINGUEZ';
            } else {
                $query = $this->db->query("SELECT a.departament AS departamento, a.clave_depto,
                CONCAT(b.`name`,' ',b.surname,' ', b.second_surname) AS name_manager, email
                FROM cat_departament AS a
                LEFT JOIN tbl_users AS b ON a.id_manager = b.id_user
            WHERE id_depto = $idDepto")->getRow()
                    ??  $this->db->query("SELECT a.departament AS departamento, a.clave_depto,
                    CONCAT(b.`name`,' ',b.surname,' ', b.second_surname) AS name_manager, email
	                FROM tbl_assign_ToursHSE_manager AS c
                    LEFT JOIN tbl_users AS b ON c.id_manager = b.id_user
	                JOIN cat_departament AS a ON (SELECT id_departament FROM tbl_users WHERE id_user = c.id_user) = a.id_depto
	                WHERE c.id_user = $idUser
	        AND c.active_status = 1")->getRow();
                $depto = $query->departamento;
                $clave = $query->clave_depto;
                $email = $query->email;
                $nameManager = $query->name_manager;
            }

            $data = [
                'type' => $type,
                'requiere_follow' => $requireSeguimiento,
                'require_retro' => $requireRetro,
                'id_user' => $idUser,
                'name_user' => $nameUser,
                'id_depto' => $idDepto,
                'departament' => $depto,
                'clave_depto' => $clave,
                'severity_level' => $severityLevel,
                'id_category' => $idCategory,
                'description' => $description,
                'sanction_message' => $sanctionMessage,
                'id_created' => session()->id_user,
                'created_at' => date("Y-m-d H:i:s"),
            ];
            $this->db->transStart();
            $this->incidentRequestModel->insert($data);
            $idRequest = $this->db->insertID();

            $image = $this->request->getFile('foto_incidencia');
            $binder =  '../public/images/recorridosHSE/incidencia_' . $idRequest;
            if (!file_exists($binder)) {
                mkdir($binder, 0777, true);
            }
            $name = $image->getClientName();
            $image = $image->move($binder,  $name);
            $imageUrl = $binder . "/" . $name;
            $dataImage = [
                'id_request' => $idRequest,
                'type_request' => 2,
                'url_image' => $imageUrl,
            ];
            $this->imageRequestModel->insert($dataImage);

            $result = $this->db->transComplete();

             $this->notifyIncidentManagerDepartament($email, $nameManager, $idRequest, $imageUrl);
            if (strlen($sanctionMessage) > 5) {
                $this->notifyIncidentManagerDepartament(null, null, $idRequest, $imageUrl, true);
            } 
            return json_encode($result);
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    function updateIncidentRquestResponse()
    {
        try {
            $idRequest = $this->request->getPost('id_incidencia');
            $responseOpc = $this->request->getPost('respuesta_opc');
            $description = $this->request->getPost('descripcion');

            /* $query = $this->db->query("SELECT a.departament AS departamento,
                CONCAT(b.`name`,' ',b.surname,' ', b.second_surname) AS name_manager, email
                FROM cat_departament AS a
                LEFT JOIN tbl_users AS b ON a.id_manager = b.id_user
            WHERE id_depto = $idDepto")->getRow(); */

            $data = [
                'id_response' => session()->id_user,
                'response_at' => date("Y-m-d H:i:s"),
                'response_opc' => $responseOpc,
                'respsonce_msj' => $description,
            ];
            $this->db->transStart();
            $this->incidentRequestModel->update($idRequest, $data);
            $image = $this->request->getFile('foto_incidencia_respuesta');
            if ($image->getSize() > 0) {
                $binder =  '../public/images/recorridosHSE/incidencia_' . $idRequest;
                if (!file_exists($binder)) {
                    mkdir($binder, 0777, true);
                }
                $name = $image->getClientName();
                $image = $image->move($binder,  $name);
                $imageUrl = $binder . "/" . $name;
                $dataImage = [
                    'id_request' => $idRequest,
                    'type_request' => 3,
                    'url_image' => $imageUrl,
                ];
                $this->imageRequestModel->insert($dataImage);
            }

            $result = $this->db->transComplete();
            return json_encode($result);
        } catch (\Exception $e) {
            return json_encode($e);
        }
    }

    function notifyIncidentManagerDepartament($email = null, $tittle = null, $idRequest = null, $directionUrl = null, $messageRH = false)
    {

        $imageUrl = site_url() . str_replace("../", "", $directionUrl);
        $query = $this->db->query("SELECT a.name_user, a.departament, a.description, a.type,a.clave_depto,
            IF(LENGTH(a.sanction_message) > 10, a.sanction_message,null) AS sancion,
            IF(a.type = 1,'ACTIVIDAD INSEGURA','CONDICION INSEGURA') AS tipo,
            IF(a.require_retro = 1,'SI','NO') AS retro,
            CASE 
                WHEN a.severity_level = 1 THEN 'LEVE'
                WHEN a.severity_level = 2 THEN 'MEDIA'
                WHEN a.severity_level = 3 THEN 'GRAVE'
                ELSE
                    ' --- '
            END AS nivel,
            (SELECT ct1.txt_category FROM cat_toursHSE_categorys AS ct1 WHERE ct1.id_category = a.id_category) AS categoria,
            DATE_FORMAT(created_at,'%d/%m/%Y') AS dia,
            DATE_FORMAT(created_at,'%H:%i') AS hora
        FROM tbl_ToursHSE_incidents AS a WHERE a.id_incidents = $idRequest")->getRow();

        $claveDepto = $query->clave_depto;

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->isSMTP();
        $mail->SMTPAuth = false;
        $mail->Host = 'localhost';
        //$mail->Username = 'requisiciones@walworth.com';
        // $mail->Password = 'Walworth321$';
        $mail->Port = 25;

        if ($claveDepto == 6309) {
            $mail->addCC('aarroyo@walworth.com.mx', 'ADOLFO ARROYO');
        }

        if ($messageRH) {
            $mail->addAddress('aenriquez@walworth.com.mx', 'ALEJANDRA ENRIQUEZ');
            $mail->addCC('eolanda@walworth.com.mx', 'ELDA OLANDA');
            $mail->addCC('gmartinez@walworth.com.mx', 'MARIA GUADALUPE MARTINEZ');
            $mail->setFrom('notificacion@walworth.com', 'Incidencias HSE | Sanción');
        } else {
            $dirEmail = changeEmail($email);
            $dirTitle = $tittle;
            $mail->addAddress($dirEmail, $dirTitle);
            $mail->setFrom('notificacion@walworth.com', 'Incidencias HSE');
        }
        $mail->addCC('ldominguez@walworth.com.mx', 'LUIS ANGEL DOMINGUEZ');
        $mail->addCC('mlozano@walworth.com.mx', 'MARIANO LOZANO');
        $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');

        $mail->addBCC('rcruz@walworth.com.mx');
        //$mail->addBCC('hrivas@walworth.com.mx');
        if ($imageUrl) {
            $mail->addAttachment($imageUrl, 'evidencia_' . $idRequest . '.jpg');
        }

        $mail->isHTML(true);
        $datas = ['incidencia' => $query, 'imagen' => $imageUrl, 'RH' => $messageRH];
        $email_template = view('notificaciones/notify_torusHSE_incident', $datas);
        $mail->MsgHTML($email_template);
        $mail->Subject =  'Notificación de Recorridos HSE';
        $mail->send();
    }

    function getAllRequestIncidents()
    {
        $idDepto = ($this->request->getPost('departamento') == 0) ? '' : "AND id_depto = " . $this->request->getPost('departamento');
        $rangeDate = $this->request->getPost('fechas_reporte');
        $dates = explode(' a ', $rangeDate);
        $starDate = $dates[0];
        $endDate = $dates[1];
        $this->db->query("SET lc_time_names = 'es_ES'");
        $query = $this->db->query("SELECT DATE_FORMAT( a.TipoCambio_Fecha, '%Y-%m' ) AS orden, 
            CONCAT(UPPER(MONTHNAME(TipoCambio_Fecha)),' ',DATE_FORMAT(a.TipoCambio_Fecha, '%Y'))  AS mes,
            IFNULL(
            (
                SELECT COUNT(b.id_incidents) FROM tbl_ToursHSE_incidents AS b 
                WHERE b.active_status = 1
                AND b.type = 1
                $idDepto
                AND	DATE_FORMAT( b.created_at, '%Y-%m' ) = DATE_FORMAT( a.TipoCambio_Fecha, '%Y-%m' )
            )
            ,0) AS actos,
            IFNULL(
            (
                SELECT COUNT(c.id_incidents) FROM tbl_ToursHSE_incidents AS c
                WHERE c.active_status = 1
                AND c.type = 2
                $idDepto
                AND	DATE_FORMAT( c.created_at, '%Y-%m' ) = DATE_FORMAT( a.TipoCambio_Fecha, '%Y-%m' )
            )
            ,0) AS condiciones
            FROM cat_tipocambio AS a
            WHERE a.TipoCambio_Fecha BETWEEN '$starDate' AND '$endDate' 
            GROUP BY orden
        ORDER BY a.TipoCambio_Fecha ASC;")->getResult();
        $data = ["data" => $query,];
        return json_encode($data);
    }

    function listRequestIncidentsByTypeAndDate()
    {
        try {
            $sqlDepto = ($this->request->getPost('depto') != 0) ? "AND a.id_depto = " . $this->request->getPost('depto') : "";
            $type = $this->request->getPost('tipo');
            $date = $this->request->getPost('mes');
            $query = $this->db->query("SELECT a.id_incidents, a.name_user, a.departament, a.type,
                DATE_FORMAT(a.created_at,'%d/%m/%Y') AS fecha,
                DATE_FORMAT(a.created_at,'%H:%i') AS hora,
                CASE 
                        WHEN a.severity_level = 1 THEN 'rgba(40, 167, 69, 0.3)'
                        WHEN a.severity_level = 2 THEN 'rgba(255, 193, 7, 0.3)'
                        WHEN a.severity_level = 3 THEN 'rgba(220, 53, 69, 0.3)'
                        ELSE 'rgba(208, 208, 208, 0.3)'
                END AS lvl_color,
                CASE 
                        WHEN a.severity_level = 1 THEN 'rgba(40, 167, 69, 1)'
                        WHEN a.severity_level = 2 THEN 'rgba(255, 193, 7, 1)'
                        WHEN a.severity_level = 3 THEN 'rgba(220, 53, 69, 1)'
                        ELSE 'black'
                END AS lvl_border,
                CASE 
                        WHEN a.severity_level = 1 THEN 'LEVE'
                        WHEN a.severity_level = 2 THEN 'MEDIA'
                        WHEN a.severity_level = 3 THEN 'ALTO'
                        ELSE 'N/A'
                END AS lvl_txt,
                (SELECT ct1.txt_category FROM cat_toursHSE_categorys AS ct1 WHERE ct1.id_category = a.id_category) AS categoria
                                        
                FROM tbl_ToursHSE_incidents AS a
                WHERE a.active_status = 1 AND a.type = $type 
                $sqlDepto
                AND DATE_FORMAT(a.created_at,'%Y-%m') = '$date'
            ORDER BY a.created_at ASC")->getResult();
            return json_encode($query);
        } catch (\Throwable $th) {
            return json_encode($th);
        }
    }

    function pdfRequest($idRequestEncrupt = null)
    {
        echo $idRequestEncrupt;
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query(" SELECT LPAD(a.id_incidents, 5, '0') AS folio, a.type, a.id_incidents,
            IF(a.type = 1,'ACTO INSEGURO','CONDICION INSEGURA') AS tipo,
            IF(type = 2, 'N/A',IF(a.require_retro = 1, 'SI' ,'NO')) AS retro,
            a.requiere_follow,
            IF(a.requiere_follow = 1, 'SI' ,'NO') AS seguimiento,
            a.name_user AS usuario,
            a.departament AS departamento,
            CASE 
                WHEN a.severity_level = 1 THEN 'LEVE'
                WHEN a.severity_level = 2 THEN 'MODERADO'
                WHEN a.severity_level = 3 THEN 'GRAVE'
                ELSE 'N/A'
            END AS nivel,
            (SELECT ct1.txt_category FROM cat_toursHSE_categorys AS ct1 WHERE ct1.id_category = a.id_category) AS categoria,
            a.description AS descripcion,
            (SELECT CONCAT(ct2.`name`,' ',ct2.surname,' ',ct2.second_surname) FROM tbl_users AS ct2 WHERE ct2.id_user = a.id_created ) AS alta,
            DATE_FORMAT(a.created_at,'%d/%m/%Y') AS fecha_cracion,
            DATE_FORMAT(a.created_at,'%H:%i') AS hora_cracion,
            IFNULL((SELECT CONCAT(ct3.`name`,' ',ct3.surname,' ',ct3.second_surname) FROM tbl_users AS ct3 WHERE ct3.id_user = a.id_created ),0) AS user_seguimiento,
            DATE_FORMAT(a.response_at,'%d/%m/%Y %H:%i') AS fecha_segimiento,
            IF(a.response_opc = 1, 'SI' ,'NO') AS seguimiento_opc,
            a.respsonce_msj
            FROM tbl_ToursHSE_incidents AS a 
		WHERE MD5(concat('$key',a.id_incidents))= '$idRequestEncrupt'")->getRow();

        $query1 = $this->db->query("SELECT url_image FROM tbl_ToursHSE_image_evidence WHERE active_status = 1
            AND id_request = " . $query->folio . "
            AND type_request = 2
        LIMIT 2;")->getRow();

        $query2 = $this->db->query("SELECT url_image FROM tbl_ToursHSE_image_evidence WHERE active_status = 1
            AND id_request = " . $query->folio . "
            AND type_request = 3
        LIMIT 2;")->getRow();

        $data = [
            "request" => $query,
            "imgAlt" => $query1,
            "imgSeg" => $query2,
        ];

        $html2 = view('pdf/pdf_ToursHSE_request', $data);
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Permisos');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('Responsiva_Suministro_' . $query->folio . '.pdf', 'I');
    }

    function XlsxRequestIncidentsByDeptoAndDate()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        // $dateStar = $data->fechaInicio;
        // $dateEnd = $data->fechaFinal;
        $dateYearMount = $data->fechaMes;
        $sqlDepto = ($data->Departamento == 0) ? "AND a.id_manager IS NOT NULL" : "AND a.id_depto = " . $data->Departamento;
        $NombreArchivo = "Reporte_" . $dateYearMount . ".xlsx";

        $this->db->query("SET lc_time_names = 'es_ES'");
        // LEFT JOIN tbl_ToursHSE_incidents AS b ON a.id_depto = b.id_depto AND b.active_status = 1 AND b.created_at BETWEEN '$dateStar' AND '$dateEnd'
        // LEFT JOIN tbl_ToursHSE_tours AS c ON a.id_depto = c.id_depto AND c.active_status = 1 AND c.date_created BETWEEN '$dateStar' AND '$dateEnd'

        $query = $this->db->query("SELECT a.departament, 
                CONCAT(UPPER(MONTHNAME('$dateYearMount-01')),' ',DATE_FORMAT('$dateYearMount-01', '%Y'))  AS mes,
                FLOOR(RAND() * 6) AS incidente,
                FLOOR(RAND() * 6) AS accidente,
                SUM(IF(b.id_category = 1,1,0)) AS uso_epp,
                SUM(IF(b.id_category = 2,1,0)) AS uniforme,
                SUM(IF(b.id_category = 3,1,0)) AS bisuteria,
                SUM(IF(b.id_category = 4,1,0)) AS cabello,
                SUM(IF(b.id_category = 5,1,0)) AS celular,
                SUM(IF(b.id_category = 6,1,0)) AS actos,
                SUM(IF(b.id_category = 7,1,0)) AS condiciones,
                CONCAT(ROUND(IFNULL(SUM(IF(c.order_clean = 1,1,0))/COUNT(c.id_torus) * 100,0), 0),' %') AS limpieza,
                SUM(IF(c.dangerous_works = 1,1,0)) AS trabajos_peligrosos,
                SUM(IF(c.maintenance_work = 1,1,0)) AS mantenimiento,
                CONCAT(ROUND(IFNULL(SUM(IF(c.waste_management = 1,1,0))/COUNT(c.id_torus) * 100,0), 0),' %') AS residuos
            FROM cat_departament AS a 
                LEFT JOIN tbl_ToursHSE_incidents AS b ON a.id_depto = b.id_depto AND b.active_status = 1 AND DATE_FORMAT(b.created_at,'%Y-%m') = '$dateYearMount'
                LEFT JOIN tbl_ToursHSE_tours AS c ON a.id_depto = c.id_depto AND c.active_status = 1 AND DATE_FORMAT(c.date_created,'%Y-%m') = '$dateYearMount'
            WHERE a.active_status = 1 
                $sqlDepto	
            GROUP BY a.id_depto
		ORDER BY a.id_depto ASC")->getResult();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet();
        $sheet->setTitle("Reporte " . $query[0]->mes);
        $cont = 9;

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(38);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);


        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(5);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(65);
        $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(10);
        $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
        $spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(25);

        $sheet->getStyle('A2:O8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:O8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle("D2")->getFont()->setBold(true)->setName('Calibri')->setSize(20)->getColor()->setRGB('000000');
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('./images/GrupoWalworth-Registrada.png'); // put your path and image here
        $drawing->setCoordinates('A2');
        $drawing->setHeight(60);
        $drawing->setOffsetX(27); // desplazamiento de imagen en x
        $drawing->setOffsetY(13); // desplazamiento de imagen en Y
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $spreadsheet->getActiveSheet()->getStyle('D2:N2')->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $spreadsheet->getActiveSheet()->getStyle('O2:P2')->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $sheet->setCellValue('A2', '')->mergeCells('A2:C2');
        $sheet->setCellValue('D2', 'REPORTE SEMANAL DE INSPECCIONES DIARIAS HSE')->mergeCells('D2:N2');
        $sheet->setCellValue('O2', "Código: FHSE-27 \n Rev. Original")->getStyle('O2')->getAlignment()->setWrapText(true);

        $sheet->mergeCells('O2:P2');


        $sheet->getStyle("L4")->getFont()->setBold(false)->setName('Calibri')->setSize(24)->getColor()->setRGB('000000');
        $sheet->getStyle("O4")->getFont()->setBold(true)->setName('Calibri')->setSize(24)->getColor()->setRGB('000000');

        $spreadsheet->getActiveSheet()->getStyle('L4:P4')->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $sheet->setCellValue('L4', 'Mes de Reporte ')->mergeCells('L4:N4');
        $sheet->setCellValue('O4', $query[0]->mes)->mergeCells('O4:P4');


        $sheet->getStyle("B5")->getFont()->setBold(true)->setName('Calibri')->setSize(20)->getColor()->setRGB('000000');
        $sheet->setCellValue('B5', 'Incumplimientos durante las Inspecciones del mes en areas productivas')->mergeCells('B5:P5');

        $spreadsheet->getActiveSheet()->getStyle('E6:O7')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000'],],],
            'alignment' => ['wrapText' => true,],
        ]);
        $spreadsheet->getActiveSheet()->getStyle('B8:O8')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000'],],],
            'alignment' => ['wrapText' => true,],
        ]);


        $spreadsheet->getActiveSheet()->getStyle('E6:O6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C00000');
        $spreadsheet->getActiveSheet()->getStyle('E7:O7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C00000');
        $spreadsheet->getActiveSheet()->getStyle('B8:O8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C00000');
        $sheet->getStyle("B6")->getFont()->setBold(true)->setName('Calibri')->setSize(20)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("B6:O8")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->setCellValue('E6', '¿Qué incumplimientos se  evaluan?')->mergeCells('E6:O6');
        $sheet->setCellValue('E7', 'Personal')->mergeCells('E7:J7');
        $sheet->setCellValue('K7', 'Del Área')->mergeCells('K7:O7');

        $sheet->setCellValue('B8', 'Área');
        $sheet->setCellValue('C8', 'INCIDENTES');
        $sheet->setCellValue('D8', 'ACCIDENTES');
        $sheet->setCellValue('E8', "Uso \n inadecuado de \n EPP")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('F8', "Uso \n inadecuado \n uniforme")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G8', "Uso de \n Bisutería")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('H8', "No cumple \n con Cabello \n Recogido")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('I8', "Uso de \n Celular")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('J8', "Actos \n Inseguras")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('K8', "Condiciones \n Inseguras")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('L8', "Limpieza \n y Orden")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('M8', "Trabajos \n Peligrosos")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('N8', "Trabajos de \n Mantto")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('O8', "Segregación \n correcta de \n Residuos")->getStyle('O3')->getAlignment()->setWrapText(true);

        foreach ($query as $KEY => $value) {

            $sheet->setCellValue('B' . $cont, $value->departament);
            $sheet->setCellValue('C' . $cont, $value->incidente);
            $colorCelda = ($value->incidente > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('C' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('D' . $cont, $value->accidente);
            $colorCelda = ($value->accidente > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('D' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('E' . $cont, $value->uso_epp);
            $colorCelda = ($value->uso_epp > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('E' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('F' . $cont, $value->uniforme);
            $colorCelda = ($value->uniforme > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('F' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('G' . $cont, $value->bisuteria);
            $colorCelda = ($value->bisuteria > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('G' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('H' . $cont, $value->cabello);
            $colorCelda = ($value->cabello > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('H' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('I' . $cont, $value->celular);
            $colorCelda = ($value->celular > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('I' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('J' . $cont, $value->actos);
            $colorCelda = ($value->actos > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('J' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('K' . $cont, $value->condiciones);
            $colorCelda = ($value->condiciones > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('K' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('L' . $cont, $value->limpieza);

            $sheet->setCellValue('M' . $cont, $value->trabajos_peligrosos);
            $colorCelda = ($value->trabajos_peligrosos > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('M' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('N' . $cont, $value->mantenimiento);
            $colorCelda = ($value->mantenimiento > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('N' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('O' . $cont, $value->residuos);
            $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(19);
            $cont++;
        }
        $endFirstTable = $cont - 1; // final de tabl
        $spreadsheet->getActiveSheet()->getStyle('B9:O' . $endFirstTable)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000'],],],
            'alignment' => ['wrapText' => true,],
        ]);
        $sheet->getStyle("B9:O" . $endFirstTable)->getFont()->setBold(true)->setName('Calibri')->setSize(9)->getColor()->setRGB('000000');
        $sheet->getStyle('B9:B' . $endFirstTable)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C9:O' . $endFirstTable)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B9:O' . $endFirstTable)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $cont += 2;
        $sheet->setCellValue('B' . $cont, 'Accidentes/Incidentes por Área')->mergeCells("B$cont:O$cont");
        $spreadsheet->getActiveSheet()->getStyle("B$cont:O$cont")->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );

        $cont++;
        $spreadsheet->getActiveSheet()->getStyle("B$cont:O$cont")->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(250);
        $sheet->setCellValue('B' . $cont, '')->mergeCells("B$cont:O$cont");

        $cont += 2;
        $sheet->setCellValue('B' . $cont, 'Incumplimientos identificadas por Áreas')->mergeCells("B$cont:O$cont");
        $spreadsheet->getActiveSheet()->getStyle("B$cont:O$cont")->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );

        $cont++;
        $spreadsheet->getActiveSheet()->getStyle("B$cont:O$cont")->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(250);
        $sheet->setCellValue('B' . $cont, '')->mergeCells("B$cont:O$cont");


        $cont += 2;
        $sheet->getStyle('D' . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D' . $cont)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D' . $cont, "Reporte Histórico Semanal - Mensual (" . $query[0]->mes . ")")->mergeCells("D$cont:L$cont");
        $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(25);
        $spreadsheet->getActiveSheet()->getStyle("D$cont:L$cont")->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );

        $cont += 2;
        $spreadsheet->getActiveSheet()->getStyle("D$cont:L$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C00000');
        $sheet->getStyle("D$cont:L$cont")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $spreadsheet->getActiveSheet()->getStyle("D$cont:L$cont")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000'],],],
            'alignment' => ['wrapText' => true,],
        ]);

        $sheet->setCellValue('D' . $cont, 'Semana');
        $sheet->setCellValue('E' . $cont, "Uso \n inadecuado de \n EPP")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('F' . $cont, "Uso \n inadecuado de \n uniforme")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G' . $cont, "Uso de \n Bisutería")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('H' . $cont, "No cumple con \n con Cabello \n Recogido")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('I' . $cont, "Uso de \n Celular")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('J' . $cont, "Actos \n Inseguras")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('K' . $cont, "Condiciones \n Inseguras")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('L' . $cont, "Total");
        $cont++;
        $query = $this->db->query(
            "SELECT CONCAT('Semana ',WEEK(a.TipoCambio_Fecha)) AS semana,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 1 AND WEEK(created_at) = WEEK(a.TipoCambio_Fecha)) AS uso_epp,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 2 AND WEEK(created_at) = WEEK(a.TipoCambio_Fecha)) AS uniforme,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 3 AND WEEK(created_at) = WEEK(a.TipoCambio_Fecha)) AS bisuteria,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 4 AND WEEK(created_at) = WEEK(a.TipoCambio_Fecha)) AS cabello,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 5 AND WEEK(created_at) = WEEK(a.TipoCambio_Fecha)) AS celular,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 6 AND WEEK(created_at) = WEEK(a.TipoCambio_Fecha)) AS actos,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 7 AND WEEK(created_at) = WEEK(a.TipoCambio_Fecha)) AS condiciones,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category IN (1,2,3,4,5,6,7) AND WEEK(created_at) = WEEK(a.TipoCambio_Fecha)) AS total
            FROM cat_tipocambio AS a
            WHERE DATE_FORMAT(a.TipoCambio_Fecha,'%Y-%m') = '$dateYearMount'
            GROUP BY WEEK(a.TipoCambio_Fecha)"
        )->getResult();
        // aqui
        $starSecondTable = $cont;
        foreach ($query as $value) {
            $sheet->setCellValue('D' . $cont, $value->semana);
            $sheet->setCellValue('E' . $cont, $value->uso_epp);
            $colorCelda = ($value->uso_epp > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('E' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('F' . $cont, $value->uniforme);
            $colorCelda = ($value->uniforme > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('F' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('G' . $cont, $value->bisuteria);
            $colorCelda = ($value->bisuteria > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('G' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('H' . $cont, $value->cabello);
            $colorCelda = ($value->cabello > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('H' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('I' . $cont, $value->celular);
            $colorCelda = ($value->celular > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('I' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('J' . $cont, $value->actos);
            $colorCelda = ($value->actos > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('J' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('K' . $cont, $value->condiciones);
            $colorCelda = ($value->condiciones > 0) ? 'FDD45F' : '61B34C';
            $spreadsheet->getActiveSheet()->getStyle('K' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $sheet->setCellValue('L' . $cont, $value->total);
            $colorCelda = 'CEA5D9';
            $spreadsheet->getActiveSheet()->getStyle('L' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

            $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(19);
            $cont++;
        }
        $endSecondTable = $cont - 1;
        $spreadsheet->getActiveSheet()->getStyle('D' . $starSecondTable . ':L' . $endSecondTable)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000'],],],
            'alignment' => ['wrapText' => true,],
        ]);
        $sheet->getStyle("D" . $starSecondTable . ":L" . $endSecondTable)->getFont()->setBold(true)->setName('Calibri')->setSize(9)->getColor()->setRGB('000000');
        $sheet->getStyle('D' . $starSecondTable . ':D' . $endSecondTable)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E' . $starSecondTable . ':L' . $endSecondTable)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E' . $starSecondTable . ':L' . $endSecondTable)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $cont += 2;
        $sheet->getStyle('B' . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B' . $cont)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('B' . $cont, "Reporte de Incumplimientos identificados por concepto (Uso de EPP, Uso de uniforme, uso de bisuteria, uso de cabello recogido, uso de celular, etc)")->mergeCells("B$cont:O$cont");
        $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(25);
        $spreadsheet->getActiveSheet()->getStyle("B$cont:O$cont")->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $cont++;
        $spreadsheet->getActiveSheet()->getStyle("B$cont:O$cont")->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );
        $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(250);
        $sheet->setCellValue('B' . $cont, '')->mergeCells("B$cont:O$cont");

        $cont += 2;
        $sheet->getStyle('D' . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D' . $cont)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D' . $cont, "Comparativo incumplimientos vs años anteriores")->mergeCells("D$cont:L$cont");
        $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(25);
        $spreadsheet->getActiveSheet()->getStyle("D$cont:L$cont")->applyFromArray(
            ['borders' => ['outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['rgb' => '000000'],
            ],],]
        );

        $cont += 2;
        $spreadsheet->getActiveSheet()->getStyle("D$cont:L$cont")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C00000');
        $sheet->getStyle("D$cont:L$cont")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $spreadsheet->getActiveSheet()->getStyle("D$cont:L$cont")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000'],],],
            'alignment' => ['wrapText' => true,],
        ]);

        $sheet->setCellValue('D' . $cont, 'AÑO');
        $sheet->setCellValue('E' . $cont, "Uso \n inadecuado de \n EPP")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('F' . $cont, "Uso \n inadecuado de \n uniforme")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G' . $cont, "Uso de \n Bisutería")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('H' . $cont, "No cumple con \n con Cabello \n Recogido")->getStyle('O3')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('I' . $cont, "Uso de \n Celular")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('J' . $cont, "Actos \n Inseguras")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('K' . $cont, "Condiciones \n Inseguras")->getStyle('O2')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('L' . $cont, "Total");
        $cont++;
        $query = $this->db->query(
            "SELECT DISTINCT YEAR(a.TipoCambio_Fecha) AS fecha,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 1 AND YEAR(created_at) = YEAR(a.TipoCambio_Fecha)) AS uso_epp,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 2 AND YEAR(created_at) = YEAR(a.TipoCambio_Fecha)) AS uniforme,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 3 AND YEAR(created_at) = YEAR(a.TipoCambio_Fecha)) AS bisuteria,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 4 AND YEAR(created_at) = YEAR(a.TipoCambio_Fecha)) AS cabello,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 5 AND YEAR(created_at) = YEAR(a.TipoCambio_Fecha)) AS celular,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 6 AND YEAR(created_at) = YEAR(a.TipoCambio_Fecha)) AS actos,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category = 7 AND YEAR(created_at) = YEAR(a.TipoCambio_Fecha)) AS condiciones,
                (SELECT COUNT(*) FROM tbl_ToursHSE_incidents WHERE active_status = 1 AND id_category IN (1,2,3,4,5,6,7) AND YEAR(created_at) = YEAR(a.TipoCambio_Fecha)) AS total
            FROM cat_tipocambio AS a
            WHERE DATE_FORMAT(a.TipoCambio_Fecha,'%Y') = DATE_FORMAT('$dateYearMount-01','%Y')
            GROUP BY WEEK(a.TipoCambio_Fecha)"
        )->getResult();
        // aqui
        $starSecondTable = $cont;

        foreach ($query as $value) {
            foreach ($query as $value) {
                $sheet->setCellValue('D' . $cont, $value->fecha);
                $sheet->setCellValue('E' . $cont, $value->uso_epp);
                $colorCelda = ($value->uso_epp > 0) ? 'FDD45F' : '61B34C';
                $spreadsheet->getActiveSheet()->getStyle('E' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

                $sheet->setCellValue('F' . $cont, $value->uniforme);
                $colorCelda = ($value->uniforme > 0) ? 'FDD45F' : '61B34C';
                $spreadsheet->getActiveSheet()->getStyle('F' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

                $sheet->setCellValue('G' . $cont, $value->bisuteria);
                $colorCelda = ($value->bisuteria > 0) ? 'FDD45F' : '61B34C';
                $spreadsheet->getActiveSheet()->getStyle('G' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

                $sheet->setCellValue('H' . $cont, $value->cabello);
                $colorCelda = ($value->cabello > 0) ? 'FDD45F' : '61B34C';
                $spreadsheet->getActiveSheet()->getStyle('H' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

                $sheet->setCellValue('I' . $cont, $value->celular);
                $colorCelda = ($value->celular > 0) ? 'FDD45F' : '61B34C';
                $spreadsheet->getActiveSheet()->getStyle('I' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

                $sheet->setCellValue('J' . $cont, $value->actos);
                $colorCelda = ($value->actos > 0) ? 'FDD45F' : '61B34C';
                $spreadsheet->getActiveSheet()->getStyle('J' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

                $sheet->setCellValue('K' . $cont, $value->condiciones);
                $colorCelda = ($value->condiciones > 0) ? 'FDD45F' : '61B34C';
                $spreadsheet->getActiveSheet()->getStyle('K' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

                $sheet->setCellValue('L' . $cont, $value->total);
                $colorCelda = 'CEA5D9';
                $spreadsheet->getActiveSheet()->getStyle('L' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($colorCelda);

                $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(19);
                $cont++;
            }
            $spreadsheet->getActiveSheet()->getRowDimension($cont)->setRowHeight(19);
            $cont++;
        }
        $endSecondTable = $cont - 1;
        $spreadsheet->getActiveSheet()->getStyle('D' . $starSecondTable . ':L' . $endSecondTable)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000'],],],
            'alignment' => ['wrapText' => true,],
        ]);
        $sheet->getStyle("D" . $starSecondTable . ":L" . $endSecondTable)->getFont()->setBold(true)->setName('Calibri')->setSize(9)->getColor()->setRGB('000000');
        $sheet->getStyle('D' . $starSecondTable . ':D' . $endSecondTable)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E' . $starSecondTable . ':L' . $endSecondTable)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E' . $starSecondTable . ':L' . $endSecondTable)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        // Definir arreglos para los datos de la gráfica
        /*  
            $departamentos = [];
            $incidentes = [];
            $accidentes = [];

            // Extraer datos del array de objetos
            foreach ($query as $dato) {
                $departamentos[] = $dato->departament;
                $incidentes[] = $dato->incidente;
                $accidentes[] = $dato->accidente;
            }
            // Crear objeto de gráfico de barras
            $chart = new Chart(
                'bar', // Tipo de gráfico
                null, // Título
                null, // Estilo
                null, // Mostrar leyenda
                $departamentos // Etiquetas de eje X
            );
            // var_dump($chart);
            // var_dump(new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $incidentes, null, count($incidentes)));
            // return;
            // Configurar datos para la gráfica
            $series1 = new DataSeries(
                DataSeries::TYPE_BARCHART, // Tipo de serie de datos
                null, // Etiquetas de eje X
                ['incidentes'], // Etiquetas de eje Y
                [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $incidentes, null, count($incidentes))], // Valores de la serie de datos
                $departamentos // Etiquetas de serie de datos
            );

            $series2 = new DataSeries(
                DataSeries::TYPE_BARCHART, // Tipo de serie de datos
                null, // Etiquetas de eje X
                ['accidentes'], // Etiquetas de eje Y
                [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $accidentes, null, count($accidentes))], // Valores de la serie de datos
                $departamentos // Etiquetas de serie de datos
            );
            $chart->setTopLeftPosition('A10');
            $chart->setBottomRightPosition('K27');

            $chart->setPlotArea('A10:' . $sheet->getHighestColumn() . '27', $chart);

            $chart->setSeries([$series1, $series2]);

            // Agregar el gráfico a la hoja de cálculo
            $sheet->addChart($chart); 
        */

        $writer = new Xlsx($spreadsheet);
        $writer->save($NombreArchivo);
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . basename($NombreArchivo));
        header("Expires:0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length:" . filesize($NombreArchivo));
        flush();
        readfile($NombreArchivo);
        exit;
    }
}
