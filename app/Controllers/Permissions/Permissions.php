<?php

/**PROD
 * GENERADOR DE REPORTE ENTRADAS Y SALIDAS & VACACIONES
 * @version 1.1 pre-prod
 * @author Rafael Cruz Aguilar <rafael.cruz.aguilar1@gmail.com>
 * @editor Horus Samael Rivas Pedraza <horus.riv.ped@gmail.com>
 * @telefono 55-65-42-96-49
 * Archivo Generador de Reporte
 */

namespace App\Controllers\Permissions;

use DateTime;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use Spipu\Html2Pdf\Html2Pdf;
use App\Models\VacationsModel;
use App\Models\VacationsItemsModel;
use App\Models\DeptoModel;
use App\Models\DeptoRhModel;
use App\Models\UserPersonalDataModel;
use App\Models\UserChildrenModel;
use App\Models\UserDocumentModel;
use App\Models\PermissionsModel;
use App\Models\PermissionsSpecialModel;
use App\Models\PermissionsInasistenceModel;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Models\PermissionsTimePayModel;
use App\Models\PermissionsTimePayItemsModel;


class Permissions extends BaseController
{

    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->model = new UserModel();
        $this->vacationModel = new VacationsModel();
        $this->vacationItemsModel = new VacationsItemsModel();
        $this->deptoModel = new DeptoModel();
        $this->deptoRhModel = new DeptoRhModel();
        $this->personalDataModel = new UserPersonalDataModel();
        $this->childrenModel = new UserChildrenModel();
        $this->permissionsModel = new PermissionsModel();
        $this->permissionsInasistenceModel = new PermissionsInasistenceModel();
        $this->documentModel = new UserDocumentModel();
        $this->specialModel = new PermissionsSpecialModel();
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;

        $this->timePayModel = new PermissionsTimePayModel();
        $this->timePayItemsModel = new PermissionsTimePayItemsModel();
    }

    /* public function index()
    {
        return ($this->is_logged) ? view('permissions/permissions') : redirect()->to(site_url());
    } */

    public function create()
    {
        if ($this->is_logged) {
            // $areaOperative = session()->area_operativa;
            $typeEmploye = session()->type_of_employee;
            $idDepto = session()->id_depto;
            $idUser = session()->id_user;
            $builder = $this->db->table('tbl_users');
            $builder->select('vacation_days_total,second_surname');
            $builder->where('id_user', session()->id_user);
            $dias_vacaciones = $builder->get()->getResult();
            $turns = $this->db->query("SELECT id AS turn, name_turn FROM cat_turns WHERE type_of_employee = $typeEmploye AND active_status=1")->getResult();

            if ((session()->authorizeNew == true || session()->id_user == 252)) {
                $companions = $this->db->query("SELECT id_user,
                    CONCAT( `name`, ' ', surname, ' ', second_surname ) AS nombre 
                    FROM tbl_users 
                    WHERE active_status = 1 
                        AND ( id_user IN ( 
                            SELECT id_user 
                            FROM tbl_assign_departments_to_managers_new 
                            WHERE active_status = 1 AND id_manager = $idUser 
                            ) 
                            OR id_departament = $idDepto ) 
                        AND id_user NOT IN ($idUser,1248,1226,710,1121,1327)
                    UNION
                    SELECT id_user,
                    CONCAT( `name`, ' ', surname, ' ', second_surname ) AS nombre 
                    FROM tbl_users 
                    WHERE active_status = 1 
                    AND id_user IN (
                        SELECT id_user_charge_of AS list_users
                        FROM cat_users_charge_of
                        WHERE active_status = 1
                        AND id_user = $idUser
                    )
                ")->getResult();
            } else {
                $companions = $this->db->query("SELECT id_user,
                    CONCAT( `name`, ' ', surname, ' ', second_surname ) AS nombre 
                    FROM tbl_users 
                    WHERE active_status = 1 
                        AND id_departament = $idDepto 
                        AND id_user NOT IN ($idUser,1248,1226,710,1121,1327)
                    UNION
                    SELECT id_user,
                    CONCAT( `name`, ' ', surname, ' ', second_surname ) AS nombre 
                    FROM tbl_users 
                    WHERE active_status = 1 
                    AND id_user IN (SELECT id_user_charge_of AS list_users
                        FROM cat_users_charge_of
                        WHERE active_status = 1
                        AND id_user = $idUser 
                    )
                ")->getResult();
            }

            $data = ["dias_vacaciones" => $dias_vacaciones, "turnos" => $turns, 'compañeros' => $companions];

            if (session()->id_user == 1063 || session()->id_user == 1248) {
                // return  view('permissions/permissionsDes', $data);
            } else {
            }
            return  view('permissions/permissions', $data);
        } else {
            redirect()->to(site_url());
        }
    }

    public function generateNew()
    {
        try {
            $payroll_number = session()->payroll_number;
            $type_permis = trim($this->request->getPost('tipo_permiso'));
            $permissions = $this->db->query("SELECT amount_permissions FROM tbl_assign_departments_to_managers_new WHERE id_user = " . session()->id_user)->getRow();
            if ($type_permis == 2 && $permissions->amount_permissions >= 5) {
                return json_encode(false);
            }
            $hora_salida = trim($this->request->getPost('permiso_autoriza_salida'));
            $dia_salida_bruto = trim($this->request->getPost('permiso_dia_salida'));
            $tipo_empleado = trim($this->request->getPost('permiso_tipo_empleado'));
            $hora_entrada = trim($this->request->getPost('permiso_autoriza_entrada'));
            $dia_entrada_bruto = $this->request->getPost('permiso_dia_entrada');
            $fecha_actual = new Time("now");
            $typeEmployement = trim($this->request->getPost('tipo_empleado'));
            $turn = trim($this->request->getPost('turno'));
            $inasistencia_inicio_bruto = trim($this->request->getPost('permiso_inasistencia'));
            $observaciones = trim($this->request->getPost('permiso_observaciones'));
            $goce_sueldo = trim($this->request->getPost('goce_sueldo'));

            $date_format = 'd/m/Y';

            if ($dia_salida_bruto != "") {
                $date_obj = DateTime::createFromFormat($date_format, $dia_salida_bruto);
                if ($date_obj->format($date_format) != $dia_salida_bruto) {
                    return json_encode('Formato');
                }
            }
            if ($dia_entrada_bruto != "") {
                $date_obj2 = DateTime::createFromFormat($date_format, $dia_entrada_bruto);
                //echo "Rc: ".$date_format;
                if ($date_obj2->format($date_format) != $dia_entrada_bruto) {
                    return json_encode('Formato');
                }
            }

            $dia_salida = ($dia_salida_bruto != "") ? DateTime::createFromFormat('d/m/Y', $dia_salida_bruto)->format('Y-m-d') : $dia_salida_bruto;
            $dia_entrada = ($dia_entrada_bruto != "") ? DateTime::createFromFormat('d/m/Y', $dia_entrada_bruto)->format('Y-m-d') : $dia_entrada_bruto;

            $limitDayOut = date("Y-m-d");
            $limitDayIn = (session()->type_of_employee == 1) ? date("Y-m-d") : date("Y-m-d", strtotime(date("Y-m-d") . " +1 day"));

            if ($type_permis == 2) {
                if ($dia_salida != "" && $dia_entrada == "") {
                    if ($dia_salida < $limitDayOut) {
                        return json_encode('limitDay');
                    }
                }

                if ($dia_entrada != "" && $dia_salida == "") {
                    if ($dia_entrada < $limitDayIn) {
                        return json_encode('limitDay');
                    }
                }
            }

            $diaValidarQuery = ($dia_entrada != "") ? $dia_entrada : $dia_salida;
            $days = $this->db->query("SELECT UPPER(motive) AS motive, time_permis_h AS h, time_permis_i AS i, DATE_FORMAT(max_time,'%H:%i') AS max_time, requires_list FROM tbl_days_special_permiss WHERE active_status = 1 AND enabled_status = 1  AND type_permiss = 1 AND day_permiss = '$diaValidarQuery'")->getRow();
            if ($type_permis == 4 && $days == null) {
                return json_encode('NoFestivo');
            }

            $daysTraffic = $this->db->query("SELECT max_time FROM tbl_days_special_permiss WHERE active_status = 1 AND enabled_status = 1  AND type_permiss = 2 AND day_permiss = '$dia_entrada'")->getRow();
            if ($type_permis == 6 && $daysTraffic == null) {
                return json_encode('NoTrafico');
            }

            if ($type_permis == 7) {
                $daysWomen = $this->db->query(
                    "SELECT a.id_es FROM tbl_entry_and_exit_permits AS a 
                    JOIN tbl_entry_and_exit_permits_items AS b ON a.id_es = b.id_es AND b.active_status = 1
                    WHERE a.active_status = 1
                    AND ( a.fecha_salida = '2024-03-08' OR a.fecha_entrada = '2024-03-08' OR b.inasistencia_fecha = '2024-03-08')
                    AND a.id_tipo_permiso = 4
                    AND a.id_user = " . session()->id_user
                )->getRow();
                if ($daysWomen != null) {
                    return json_encode('festivoYaGenerado');
                }
            }

            $time1 = '';
            $time2 = '';
            $id_pago_horas = null;
            $tipo_pago = null;
            $evidencia_foto = null;
            $date = new DateTime(date('Y-m-d'));

            if ($type_permis == 2) {
                if ($hora_entrada != "" &&  $hora_salida != "" && $hora_entrada > $hora_salida) {
                    $diffTime = (new DateTime($hora_salida))->diff(new DateTime($hora_entrada));
                    $diffIn = $date->diff(new DateTime($dia_entrada));
                    $diffOut = $date->diff(new DateTime($dia_salida));

                    if ($diffIn->m != 0 || $diffIn->y != 0 || $diffOut->m != 0 || $diffOut->y != 0) {
                        return json_encode("mes");
                    }

                    if (strtotime($diffTime->h . ":" . $diffTime->i . ":" . $diffTime->s) > strtotime("03:00:00") || $dia_entrada != $dia_salida) {
                        return json_encode("In&Out");
                    }
                    $time1 = $hora_entrada;
                    $time2 = $hora_salida;
                } else {

                    if ($hora_entrada != "") {
                        $diffIn = $date->diff(new DateTime($dia_entrada));

                        if ($diffIn->m != 0 || $diffIn->y != 0) {
                            return json_encode("mes");
                        }

                        $inSql = (date('w', strtotime($dia_entrada)) == 6) ? "hour_in_saturday" : "hour_in"; // selecciona que hora de entrada taera dependiendo si el permiso es en dia sabado o no
                        $inHour = $this->db->query("SELECT name_turn, $inSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow(); // nombre ter turno 
                        $limitHourIn = strtotime('+3 hour', strtotime($inHour->h));
                        // var_dump(date("H:i:s",$limitHourIn));
                        if (strtotime(date($hora_entrada, time())) > $limitHourIn) {
                            return json_encode("Entrada");
                        }
                        $time1 = $hora_entrada;
                        $time2 = $inHour->h;
                    }

                    if ($hora_salida != "") {
                        $diffOut = $date->diff(new DateTime($dia_salida));

                        if ($diffOut->m != 0 || $diffOut->y != 0) {
                            return json_encode("mes");
                        }

                        $outSql = (date('w', strtotime($dia_salida)) == 6) ? "hour_out_saturday" : "hour_out"; // selecciona que hora de Salida taera dependiendo si el permiso es en dia sabado o no
                        $outHour = $this->db->query("SELECT name_turn, $outSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow();
                        $limitHourOut = strtotime('-3 hour', strtotime($outHour->h));
                        // var_dump(date("H:i:s",$limitHourOut));
                        if (strtotime(date($hora_salida, time())) < $limitHourOut) {
                            return json_encode("Salida");
                        }
                        $time1 = $outHour->h;
                        $time2 = $hora_salida;
                    }
                }
            } else if ($type_permis == 4) {

                if ($hora_entrada != "") {
                    $inSql = (date('w', strtotime($dia_entrada)) == 6) ? "hour_in_saturday" : "hour_in"; // selecciona que hora de entrada taera dependiendo si el permiso es en dia sabado o no
                    $inHour = $this->db->query("SELECT name_turn, $inSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow(); // nombre ter turno 
                    if ($days->max_time != null) {
                        if ($days->max_time < $hora_entrada) {
                            return json_encode("excesoTiempoTrafico");
                        }
                        $time1 = $hora_entrada;
                        $time2 = $inHour->h;
                    } else {
                        $limitHourIn = strtotime("+$days->h hour +$days->i minutes", strtotime($inHour->h));
                        if (strtotime(date($hora_entrada, time())) > $limitHourIn) {
                            return json_encode("excesoTiempoFestivo");
                        }
                    }
                    $time1 = $hora_entrada;
                    $time2 = $inHour->h;
                }

                if ($hora_salida != "") {
                    if ($days->max_time != null) {
                        if ($days->max_time > $hora_salida) {
                            return json_encode("excesoTiempoTrafico");
                        }
                        $inSql = (date('w', strtotime($dia_entrada)) == 6) ? "hour_out_saturday" : "hour_out"; // selecciona que hora de entrada taera dependiendo si el permiso es en dia sabado o no
                        $outHour = $this->db->query("SELECT name_turn, $inSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow(); // nombre ter turno 
                        $time1 = $outHour->h;
                        $time2 = $hora_salida;
                    } else {
                        $outSql = (date('w', strtotime($dia_salida)) == 6) ? "hour_out_saturday" : "hour_out"; // selecciona que hora de Salida taera dependiendo si el permiso es en dia sabado o no
                        $outHour = $this->db->query("SELECT name_turn, $outSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow();
                        $limitHourOut = strtotime("-$days->h hour -$days->i minutes", strtotime($outHour->h));
                        if (strtotime(date($hora_salida, time())) < $limitHourOut) {
                            return json_encode("excesoTiempoFestivo");
                        }
                        $time1 = $outHour->h;
                        $time2 = $hora_salida;
                    }
                }
            } else if ($type_permis == 6) {
                if ($daysTraffic->max_time < $hora_entrada) {
                    return json_encode("excesoTiempoTrafico");
                }
                $inSql = (date('w', strtotime($dia_entrada)) == 6) ? "hour_in_saturday" : "hour_in"; // selecciona que hora de entrada taera dependiendo si el permiso es en dia sabado o no
                $inHour = $this->db->query("SELECT name_turn, $inSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow(); // nombre ter turno 
                $time1 = $hora_entrada;
                $time2 = $inHour->h;
            } else if ($type_permis == 7) {
                $imagen =  $this->request->getFile('evidencias');
                $binder =  '../public/images/evidenciaPermisosEspeciales/2024-03-08';
                if (!file_exists($binder)) {
                    mkdir($binder, 0777, true);
                }
                $newName = "Evidencia_2024_03_08_usuario_" . session()->id_user . ".jpg";
                $nameDo = $imagen->getClientName();
                $imagen = $imagen->move($binder,  $newName);
                $evidencia_foto = $binder . "/" . $newName;
                echo $evidencia_foto . "<br>";
                echo "SELECT a.id_es FROM tbl_entry_and_exit_permits AS a 
                JOIN tbl_entry_and_exit_permits_items AS b ON a.id_es = b.id_es AND b.active_status = 1
                WHERE a.active_status = 1
                AND ( a.fecha_salida = '2024-03-08' OR a.fecha_entrada = '2024-03-08' OR b.inasistencia_fecha = '2024-03-08')
                AND a.id_tipo_permiso = 4
                AND a.id_user = " . session()->id_user;
            } else if ($type_permis == 8) {

                if ($hora_entrada != "") {
                    $diffIn = $date->diff(new DateTime($dia_entrada));

                    /*  if ($diffIn->m != 0 || $diffIn->y != 0) {
                        return json_encode("mes");
                    } */

                    $inSql = (date('w', strtotime($dia_entrada)) == 6) ? "hour_in_saturday" : "hour_in"; // selecciona que hora de entrada taera dependiendo si el permiso es en dia sabado o no
                    $inHour = $this->db->query("SELECT name_turn, $inSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow(); // nombre ter turno 
                    $limitHourIn = strtotime('+4 hour', strtotime($inHour->h));
                    // var_dump(date("H:i:s",$limitHourIn));
                    if (strtotime(date($hora_entrada, time())) > $limitHourIn) {
                        return json_encode("Entrada");
                    }
                    $time1 = $hora_entrada;
                    $time2 = $inHour->h;
                }

                if ($hora_salida != "") {
                    $diffOut = $date->diff(new DateTime($dia_salida));

                    if ($diffOut->m != 0 || $diffOut->y != 0) {
                        return json_encode("mes");
                    }

                    $outSql = (date('w', strtotime($dia_salida)) == 6) ? "hour_out_saturday" : "hour_out"; // selecciona que hora de Salida taera dependiendo si el permiso es en dia sabado o no
                    $outHour = $this->db->query("SELECT name_turn, $outSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow();
                    $limitHourOut = strtotime('-4 hour', strtotime($outHour->h));
                    // var_dump(date("H:i:s",$limitHourOut));
                    if (strtotime(date($hora_salida, time())) < $limitHourOut) {
                        return json_encode("Salida");
                    }
                    $time1 = $outHour->h;
                    $time2 = $hora_salida;
                }
                if ($hora_entrada != "") {
                    $diffIn = $date->diff(new DateTime($dia_entrada));

                    if ($diffIn->m != 0 || $diffIn->y != 0) {
                        return json_encode("mes");
                    }

                    $inSql = (date('w', strtotime($dia_entrada)) == 6) ? "hour_in_saturday" : "hour_in"; // selecciona que hora de entrada taera dependiendo si el permiso es en dia sabado o no
                    $inHour = $this->db->query("SELECT name_turn, $inSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow(); // nombre ter turno 
                    $limitHourIn = strtotime('+4 hour', strtotime($inHour->h));
                    // var_dump(date("H:i:s",$limitHourIn));
                    if (strtotime(date($hora_entrada, time())) > $limitHourIn) {
                        return json_encode("Entrada");
                    }
                    $time1 = $hora_entrada;
                    $time2 = $inHour->h;
                }

                if ($hora_salida != "") {
                    $diffOut = $date->diff(new DateTime($dia_salida));

                    if ($diffOut->m != 0 || $diffOut->y != 0) {
                        return json_encode("mes");
                    }

                    $outSql = (date('w', strtotime($dia_salida)) == 6) ? "hour_out_saturday" : "hour_out"; // selecciona que hora de Salida taera dependiendo si el permiso es en dia sabado o no
                    $outHour = $this->db->query("SELECT name_turn, $outSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow();
                    $limitHourOut = strtotime('-3 hour', strtotime($outHour->h));
                    // var_dump(date("H:i:s",$limitHourOut));
                    if (strtotime(date($hora_salida, time())) < $limitHourOut) {
                        return json_encode("Salida");
                    }
                    $time1 = $outHour->h;
                    $time2 = $hora_salida;
                }
            } else {
                if ($hora_entrada != "" &&  $hora_salida != "" && $hora_entrada > $hora_salida) {
                    $diffTime = (new DateTime($hora_salida))->diff(new DateTime($hora_entrada));
                    $diffIn = $date->diff(new DateTime($dia_entrada));
                    $diffOut = $date->diff(new DateTime($dia_salida));
                    $time1 = $hora_salida;
                    $time2 = $hora_entrada;
                } else {
                    if ($hora_entrada != "") {
                        $inSql = (date('w', strtotime($dia_entrada)) == 6) ? "hour_in_saturday" : "hour_in"; // selecciona que hora de entrada taera dependiendo si el permiso es en dia sabado o no
                        $inHour = $this->db->query("SELECT name_turn, $inSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow(); // nombre ter turno 
                        $time1 = $hora_entrada;
                        $time2 = $inHour->h;
                    }

                    if ($hora_salida != "") {
                        $outSql = (date('w', strtotime($dia_salida)) == 6) ? "hour_out_saturday" : "hour_out"; // selecciona que hora de Salida taera dependiendo si el permiso es en dia sabado o no
                        $outHour = $this->db->query("SELECT name_turn, $outSql AS h FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow();
                        $time1 = $outHour->h;
                        $time2 = $hora_salida;
                    }
                }
            }

            if ($inasistencia_inicio_bruto != '') {
                $arrayInasistencias = explode(', ', $inasistencia_inicio_bruto);
                $inasistencia_inicio = min($arrayInasistencias);
                $inasistencia_final = max($arrayInasistencias);
                $h = null;
                $i = null;
            } else {
                $time1 = (strlen($time1) <= 5) ? substr($time1, 0, 5) . ':00' : $time1;
                $time2 = (strlen($time2) <= 5) ? substr($time2, 0, 5) . ':00' : $time2;
                $datetime1 = DateTime::createFromFormat('H:i:s', $time1);
                $datetime2 = DateTime::createFromFormat('H:i:s', $time2);
                $interval = $datetime1->diff($datetime2);
                $h = $interval->h;
                $i = $interval->i;
                $inasistencia_inicio = '';
                $inasistencia_final = '';
                $arrayInasistencias = [];
            }

            $turnName = $this->db->query("SELECT name_turn AS n FROM cat_turns WHERE type_of_employee = $typeEmployement AND id = $turn")->getRow();
            $amount = 0;

            if ($type_permis == 2) {
                $amount = intval($permissions->amount_permissions) + 1;
            } else {
                $goce_sueldo = 'SI';
            }

            $typePermissObject = [
                1 => "LABORAL",
                2 => "PERSONAL",
                4 => $days->motive ?? '',
                6 => "POR TRAFICO",
                7 => "DIA DE LA MUJER",
                8 => "ATENCIÓN PSICOLÓGICA",
            ];

            $this->db->transStart();

            // Validacion de tiempo
            if ($type_permis == 2 && session()->type_of_employee == 2 && $goce_sueldo == "SI") {
                $typePayTime = $this->request->getPost('tipo_pago_tiempo');
                $totalMinutos = $interval->h * 60 + $interval->i;
                if ($typePayTime == 2) {
                    $id_pago_horas = 0;
                    $tipo_pago = 2;
                } else {
                    $arrayIdPayTime = $this->request->getPost('id_item_');
                    if ($arrayIdPayTime === null) {
                        return json_encode('pagoHoras');
                    };
                    $id_pago_horas = implode(',', $arrayIdPayTime);
                    $query = $this->db->query("SELECT hour_pay, min_pay 
                        FROM tbl_entry_and_exit_permits_time_pay_items
                        WHERE active_status = 1 
                            AND status_autorize = 2 
                            AND id_item IN ($id_pago_horas)
                        ORDER BY id_item ASC")->getResult();
                    $hoursArray = [];
                    $minutesArray = [];
                    foreach ($query as $key) {
                        array_push($hoursArray, $key->hour_pay);
                        array_push($minutesArray, $key->min_pay);
                    }
                    $timePayMinutos =  (array_sum($hoursArray) * 60) + array_sum($minutesArray);
                    if ($totalMinutos > $timePayMinutos) {
                        return json_encode('pagoHoras');
                    }

                    foreach ($arrayIdPayTime as $key) {
                        $updateData = ['available_used_debit' => 2,];
                        $this->timePayItemsModel->update($key, $updateData);
                    }
                    $tipo_pago = 1;
                }
            }

            if ($type_permis == 8) {
                $result = 8;
            } else {
                $result = 0;
            }


            $data_permissions = [
                "id_user" => session()->id_user,
                "user" => session()->name . " " . session()->surname,
                "fecha_creacion" => $fecha_actual,
                "tipo_empleado" => $tipo_empleado,
                "nombre_solicitante" => session()->name . " " . session()->surname,
                'centro_costo' => session()->cost_center,
                "area_operativa" => session()->area_operativa,
                "id_depto" => session()->id_depto,
                "departamento" => session()->departament,
                "num_nomina" => $payroll_number,
                "hora_salida" => $hora_salida,
                "fecha_salida" => $dia_salida,
                "hora_entrada" => $hora_entrada,
                "fecha_entrada" => $dia_entrada,
                "inasistencia_del" => $inasistencia_inicio,
                "inasistencia_al" => $inasistencia_final,
                "id_tipo_permiso" => ($type_permis == 7 || $type_permis == 8) ? 4 : $type_permis,
                "tipo_permiso" => $typePermissObject[$type_permis],
                "id_turno" => $turn,
                'turno_permiso' => $turnName->n,
                "goce_sueldo" => $goce_sueldo,
                "observaciones" => $observaciones,
                "num_permiso_mes" => $amount,
                'id_pago_tiempo' => $id_pago_horas,
                'hora_permiso' => $h,
                'minuto_permiso' => $i,
                'pago_deuda' => $tipo_pago,
                'id_usuario_autoriza' => ($type_permis == 4 || $type_permis == 6 || $type_permis == 7 || $type_permis == 8) ? 1327 : 0,
                'estatus' => ($type_permis == 4 || $type_permis == 6 || $type_permis == 7 || $type_permis == 8) ? 'Autorizada' : 'Pendiente',
                'url_evidence' => $evidencia_foto,
            ];
            // if (session()->id_user != 1063) {
            // $insertPermiss = 
            $this->permissionsModel->insert($data_permissions);
            $id_es = $this->db->insertID();
            // } else {
            //     return json_encode(false);
            // }

            if ($inasistencia_inicio_bruto != '') {
                $arrayInasistencias = explode(', ', $inasistencia_inicio_bruto);
                for ($i = 0; $i < count($arrayInasistencias); $i++) {
                    $allowedFields = [
                        'id_es' => $id_es,
                        'id_user' => session()->id_user,
                        'inasistencia_fecha' => $arrayInasistencias[$i],
                        'estatus' => 1,
                    ];
                    $this->permissionsInasistenceModel->insert($allowedFields);
                }
            }
            if ($type_permis == 2) {
                //  AUMENTAR CANTIDAD DE PERMISOS 
                $this->db->query("UPDATE tbl_assign_departments_to_managers_new SET amount_permissions = $amount WHERE id_user = " . session()->id_user);
            }

            if ($type_permis == 4) {
                if ($days->requires_list == 1) {
                    $this->db->query("UPDATE cat_list_special_permiss SET active_status = 2 WHERE payroll_number = $payroll_number");
                }
            }

            $result = $this->db->transComplete();

            // if (session()->id_user != 1063) {
            if ($result) {
                if ($type_permis == 2) {
                    if ($amount == 4) {
                        // email a DIRECTOR
                        $dataEmail = $this->db->query("SELECT email, name, surname FROM tbl_users WHERE id_user IN 
                            (SELECT id_director FROM tbl_assign_departments_to_managers_new WHERE id_user = " . session()->id_user . ")")->getRow();
                        $title_specific = $dataEmail->name . " " . $dataEmail->surname;
                        $email_specific = $dataEmail->email;
                        $this->notificarEmail($email_specific, $title_specific, $data_permissions);
                    } else if ($amount == 5) {

                       /* $email_specific = 'vhernandez@walworth.com.mx';
                        $title_specific = 'VICTOR MANUEL HERNANDEZ';

                        if ($payroll_number == 123123) {
                            $email_specific = 'hrivas@walworth.com.mx';
                        }
                        $this->notificarEmail($email_specific, $title_specific, $data_permissions); */

                        // email a DIRECTOR
                        $dataEmail = $this->db->query("SELECT email, name, surname FROM tbl_users WHERE id_user IN 
                            (SELECT id_director FROM tbl_assign_departments_to_managers_new WHERE id_user = " . session()->id_user . ")")->getRow();
                        $title_specific = $dataEmail->name . " " . $dataEmail->surname;
                        $email_specific = $dataEmail->email;
                        $this->notificarEmail($email_specific, $title_specific, $data_permissions);


                    }
                } else {
                    $amount = true;
                }
                //  email a manager
                $dataEmail = $this->db->query("SELECT email, name, surname FROM tbl_users WHERE id_user IN 
                (SELECT id_manager FROM tbl_assign_departments_to_managers_new WHERE id_user = " . session()->id_user . ")")->getRow();
                $email = $dataEmail->email;
                $title = $dataEmail->name . " " . $dataEmail->surname;
                $this->notificarEmail($email, $title, $data_permissions);
            }
            // }

            return ($result) ? json_encode($amount) : json_encode(false);
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    public function departamentsAll()
    {
        // $deptoData = $this->deptoRhModel->where('active_status', 1)->findAll();
        $deptoData = $this->deptoModel->where('active_status', 1)->findAll();
        return (count($deptoData) > 0) ? json_encode($deptoData) : json_encode("error");
    }

    public function directionsAll()
    {
        $directions = $this->db->query("SELECT direction, id_responsible FROM cat_direction ORDER BY direction ASC")->getResult();
        return ($directions) ? json_encode($directions) : json_encode(false);
    }

    public function personalData()
    {
        /*********************PRIMERA PARTE DEL FOMULARIO DATOS DEL USUARIO ***************************/
        $num_nomina = trim($this->request->getPost('num_nomina'));
        $nombre_usuario = trim($this->request->getPost('nombre_usuario'));
        $ape_paterno = trim($this->request->getPost('ape_paterno'));
        $ape_materno = trim($this->request->getPost('ape_materno'));
        $fecha_ingreso = trim($this->request->getPost('fecha_ingreso'));
        $genero = trim($this->request->getPost('genero'));
        $edad_usuario = trim($this->request->getPost('edad_usuario'));
        $fecha_nacimiento = trim($this->request->getPost('fecha_nacimiento'));
        $curp = trim($this->request->getPost('curp'));
        $rfc = trim($this->request->getPost('rfc'));
        $estado_civil = trim($this->request->getPost('estado_civil'));

        /*********************SEGUNDA PARTE DEL FOMULARIO DATOS EMERGENCIA ***************************/
        $parentesco = $this->request->getPost('parentesco');
        $contacto_emergencia = $this->request->getPost('contacto_emergencia');
        $tel_contacto = $this->request->getPost('tel_contacto');

        /*********************TERCERA PARTE DEL FOMULARIO DATOS DOMICILIO ***************************/
        $estado = trim($this->request->getPost('estado'));
        $municipio = trim($this->request->getPost('municipio'));
        $colonia = trim($this->request->getPost('colonia'));
        $calle = trim($this->request->getPost('calle'));
        $codigo_postal = trim($this->request->getPost('codigo_postal'));
        $num_exterior = trim($this->request->getPost('num_exterior'));
        $num_interior = trim($this->request->getPost('num_interior'));

        /*********************CUARTA PARTE DEL FOMULARIO DATOS CONYUGE ***************************/
        $conyuge = trim($this->request->getPost('conyuge'));
        $edad_conyuge = trim($this->request->getPost('edad_conyuge'));
        $ocupacion_conyuge = trim($this->request->getPost('ocupacion_conyuge'));
        $tel_conyuge = trim($this->request->getPost('tel_conyuge'));

        /*********************QUINTA PARTE DEL FOMULARIO DATOS HIJOS ***************************/
        $hijo = $this->request->getPost('hijo');
        $hijo_fecha = $this->request->getPost('hijo_fecha');
        $hijo_genero = $this->request->getPost('hijo_genero');
        $hijo_edad = $this->request->getPost('hijo_edad');

        $padres = $this->request->getPost('padres');
        $padres_fecha = $this->request->getPost('padres_fecha');
        $padres_genero = $this->request->getPost('padres_genero');
        $padres_edad = $this->request->getPost('padres_edad');
        $estatus_padres = $this->request->getPost('estatus_padres');

        /*********************SEXTA PARTE DEL FOMULARIO COMPETENCIAS***************************/
        $escolaridad = trim($this->request->getPost('escolaridad'));
        $lic_ing = trim($this->request->getPost('tipo_estudio'));
        $date = date("Y-m-d H:i:s");
        $data = [
            "num_nomina" => $num_nomina,
            "nombre" => strtoupper($nombre_usuario),
            "ape_paterno" => strtoupper($ape_paterno),
            "ape_materno" => strtoupper($ape_materno),
            "edad_usuario" => strtoupper($edad_usuario),
            "genero" => strtoupper($genero),
            "fecha_nacimiento" => $fecha_nacimiento,
            "estado_civil" => strtoupper($estado_civil),
            "curp" => strtoupper($curp),
            "rfc" => strtoupper($rfc),
            "estado" => strtoupper($estado),
            "municipio" => strtoupper($municipio),
            "colonia" => strtoupper($colonia),
            "calle" => strtoupper($calle),
            "numero_exterior" => $num_exterior,
            "numero_interior" => $num_interior,
            "nombre_conyuge" => strtoupper($conyuge),
            "edad_conyuge" => $edad_conyuge,
            "ocupacion_conyuge" => strtoupper($ocupacion_conyuge),
            "tel_conyuge" => $tel_conyuge,
            "escolaridad" => strtoupper($escolaridad),
            'fecha_ingreso' => $fecha_ingreso,
            "lic_ing" => $lic_ing,
            "created_at" => $date,
            "codigo_postal" => $codigo_postal
        ];

        $insertData = $this->personalDataModel->insert($data);
        $id_datos = $this->db->insertID();

        if ($insertData) {
            $builder =  $this->db->table('tbl_users_emergency_contact');
            for ($j = 0; $j < count($tel_contacto); $j++) {
                $dataItems = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    "parentesco_emergencia" => strtoupper($parentesco[$j]),
                    "contacto_emergencia" => strtoupper($contacto_emergencia[$j]),
                    "tel_emergencia" => $tel_contacto[$j],
                    'created_at' => $date
                ];
                $builder->insert($dataItems);
            }

            if (!empty($hijo[0])) {
                $builder =  $this->db->table('tbl_users_children');
                for ($i = 0; $i < count($hijo); $i++) {
                    $dataItem = [
                        'id_datos' => $id_datos,
                        'num_nomina' => $num_nomina,
                        'nombre_hijo' =>  $hijo[$i],
                        'fecha_nacimiento' => $hijo_fecha[$i],
                        'edad_hijo' => $hijo_edad[$i],
                        'genero' => $hijo_genero[$i],
                        'created_at' => $date
                    ];
                    $builder->insert($dataItem);
                }
            }
            $builder =  $this->db->table('tbl_users_parents');

            for ($k = 0; $k < count($padres); $k++) {
                $dataItems = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    "nombre_padres" => strtoupper($padres[$k]),
                    "fecha_nacimiento_padres" => strtoupper($padres_fecha[$k]),
                    "genero_padres" => strtoupper($padres_genero[$k]),
                    "finado" => strtoupper($estatus_padres[$k]),
                    "edad" =>  $padres_edad[$k],
                    'created_at' => $date
                ];
                $builder->insert($dataItems);
            }
        }
        return ($insertData) ? json_encode(true) : json_encode(false);
    }

    public function personalDataPT2()
    {
        /******************DOCUMENTOS*******************/
        try {
            $sessionUser = session()->id_user;
            $num_nomina = session()->payroll_number;
            $info = $this->db->query("SELECT curp, rfc, id_datos, escolaridad FROM tbl_users_personal_data 
         WHERE num_nomina = $num_nomina AND active_status = 1")->getRow();
            $id_datos = $info->id_datos;
            $dateFileUp = strval(date("Y-m-d_H_i_s"));
            $date = date("Y-m-d_H:i:s");
            $binder =  '../public/doc/comprobantes/' . session()->id_user;
            if (!file_exists($binder)) {
                mkdir($binder, 0777, true);
            }
            $domicilio = $this->request->getFile('doc_domicilio'); //tipo 1
            $newName = "domicilio_" . $sessionUser . "_" . $dateFileUp;
            $nameDo = $domicilio->getClientName();
            $domicilio = $domicilio->move($binder,  $newName);
            $e_documen = $binder . "/" . $newName;
            $upDomicilio = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 1,
                'nombre_original' => $nameDo,
                'descripcion' => "Comprobante de Domicilio",
                'ubicacion' => $e_documen,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $this->documentModel->insert($upDomicilio);

            $estudios = $this->request->getFile('doc_estudios'); //tipo 2
            $newNameEs = "estudios_" . $sessionUser . "_" . $dateFileUp;
            $nameEs = $estudios->getClientName();
            $estudios = $estudios->move($binder,  $newNameEs);
            $e_estudio = $binder . "/" . $newNameEs;
            $upEstudios = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 2,
                'descripcion' => "Comprobante de " . $info->escolaridad,
                'nombre_original' => $nameEs,
                'ubicacion' => $e_estudio,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $this->documentModel->insert($upEstudios);

            $acta = $this->request->getFile('doc_acta'); //tipo 3
            $newNameAc = "acta_" . $sessionUser . "_" . $dateFileUp;
            $nameAc = $acta->getClientName();
            $acta = $acta->move($binder,  $newNameAc);
            $e_acta = $binder . "/" . $newNameAc;
            $upActa = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 3,
                'descripcion' => "Acta de Nacimiento",
                'nombre_original' => $nameAc,
                'ubicacion' => $e_acta,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $this->documentModel->insert($upActa);

            $diplomas = $this->request->getPost('id_diploma_'); // tipo 4
            if ($diplomas != null) {
                $contD = count($diplomas);
                for ($iD = 0; $iD < $contD; $iD++) {
                    $docDiploma = "doc_diploma_" . $diplomas[$iD];
                    $tittleDiploma = "diploma_" . $diplomas[$iD];
                    $doc_diploma = $this->request->getFile($docDiploma);
                    $tt_diploma = $this->request->getPost($tittleDiploma);
                    $nameDiploma = $doc_diploma->getClientName();
                    $newNameDiploma = "diploma_" . $sessionUser . "_" . $dateFileUp;
                    $doc_diploma = $doc_diploma->move($binder,  $newNameDiploma);
                    $e_diploma = $binder . "/" . $newNameDiploma;
                    $upDiploma = [
                        'id_datos' => $id_datos,
                        'num_nomina' => $num_nomina,
                        'tipo_document' => 4,
                        'descripcion' => "Comprobante de " . $tt_diploma,
                        'nombre_original' => $nameDiploma,
                        'ubicacion' => $e_diploma,
                        'created_at' => $date,
                        'active_status' => 1,
                    ];
                    $this->documentModel->insert($upDiploma);
                }
            }

            $cursos = $this->request->getPost('id_cusos_'); //tipo 5
            if ($cursos != null) {
                $contC = count($cursos);
                for ($iC = 0; $iC < $contC; $iC++) {
                    $docCurso = "doc_curso_" . strval($cursos[$iC]);
                    $tittleCurso = "curso_" . strval($cursos[$iC]);
                    $doc_curso = $this->request->getFile($docCurso);
                    $curso = $this->request->getPost($tittleCurso);
                    $newNameCurso = "curso_" . $sessionUser . "_" . $dateFileUp;
                    $nameCurso = $doc_curso->getClientName();
                    $doc_curso = $doc_curso->move($binder,  $newNameCurso);
                    $e_curso = $binder . "/" . $newNameCurso;
                    $upCurso = [
                        'id_datos' => $id_datos,
                        'num_nomina' => $num_nomina,
                        'tipo_document' => 5,
                        'descripcion' => "Comprobante de " . $curso,
                        'nombre_original' => $nameCurso,
                        'ubicacion' => $e_curso,
                        'created_at' => $date,
                        'active_status' => 1,
                    ];
                    $this->documentModel->insert($upCurso);
                }
            }

            $ingles = $this->request->getPost('ingles'); // tipo 6
            if ($ingles == 1) {
                $doc_ingles = $this->request->getFile('doc_ingles');
                $newNameIng = "ing_" . $sessionUser . "_" . $dateFileUp;
                $nameIng = $doc_ingles->getClientName();
                $doc_ingles = $doc_ingles->move($binder,  $newNameIng);
                $e_ing = $binder . "/" . $newNameIng;
                $upIng = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 6,
                    'descripcion' => "Comprobante de Ingles",
                    'nombre_original' => $nameIng,
                    'ubicacion' => $e_ing,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upIng);
            }

            $docCURP = $this->request->getFile('doc_curp'); //tipo 7
            $newName = "CURP_" . $sessionUser . "_" . $dateFileUp;
            $nameCURP = $docCURP->getClientName();
            $docCURP = $docCURP->move($binder,  $newName);
            $e_CRUP = $binder . "/" . $newName;
            $upCURP = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 7,
                'nombre_original' => $nameCURP,
                'descripcion' => $info->curp,
                'ubicacion' => $e_CRUP,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $this->documentModel->insert($upCURP);

            $docRFC = $this->request->getFile('doc_rfc'); //tipo 8
            $newName = "RFC_" . $sessionUser . "_" . $dateFileUp;
            $nameRFC = $docRFC->getClientName();
            $docRFC = $docRFC->move($binder,  $newName);
            $e_RFC = $binder . "/" . $newName;
            $upRFC = [
                'id_datos' => $id_datos,
                'num_nomina' => $num_nomina,
                'tipo_document' => 8,
                'nombre_original' => $nameRFC,
                'descripcion' => $info->rfc,
                'ubicacion' => $e_RFC,
                'created_at' => $date,
                'active_status' => 1,
            ];
            $this->documentModel->insert($upRFC);

            $cv = $this->request->getPost('cv'); // tipo 9
            if ($cv == 1) {
                $doc_cv = $this->request->getFile('doc_cv');
                $newNameCV = "Curriculum_" . $sessionUser . "_" . $dateFileUp;
                $nameCV = $doc_cv->getClientName();
                $doc_cv = $doc_cv->move($binder,  $newNameCV);
                $e_cv = $binder . "/" . $newNameCV;
                $upCV = [
                    'id_datos' => $id_datos,
                    'num_nomina' => $num_nomina,
                    'tipo_document' => 9,
                    'descripcion' => "Curiculum",
                    'nombre_original' => $nameCV,
                    'ubicacion' => $e_cv,
                    'created_at' => $date,
                    'active_status' => 1,
                ];
                $this->documentModel->insert($upCV);
            }
            return json_encode(true);
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e;
        }
    }

    public function validateAmountPermissions()
    {
        $data = $this->db->query("SELECT amount_permissions FROM tbl_assign_departments_to_managers_new WHERE id_user = " . session()->id_user)->getRow();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function validateAmountPermissionsUsers()
    {
        $id_director = session()->id_user;
        $data = $this->db->query("SELECT a.id ,a.payroll_number, CONCAT(b.`name`,' ',b.surname,' ',b.second_surname) AS user_name, c.job, d.departament, amount_permissions
        FROM tbl_assign_departments_to_managers_new AS a
        LEFT JOIN tbl_users AS b ON a.id_user = b.id_user
        LEFT JOIN cat_job_position AS c ON b.id_job_position = c.id
        JOIN cat_departament AS d ON b.id_departament = d.id_depto
         WHERE a.id_director = $id_director ORDER BY payroll_number ASC")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function notificarEmail($dir_email, $title, $data)
    {
        $idUser = $data['id_user'];
        $query = $this->db->query("SELECT amount_permissions 
            FROM tbl_assign_departments_to_managers_new 
            WHERE id_user = $idUser")->getRow();
        $query1 = $this->db->query("SELECT color FROM cat_color_type_permiss WHERE type_permiss = " . $data["id_tipo_permiso"])->getRow();
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
            //Server settings
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            //  ACTUALIZACION
            if ($data['num_permiso_mes'] == 4) {
                $mail->setFrom('notificacion@walworth.com', 'Sistema de Permisos | Directores');
            } elseif ($data['num_permiso_mes'] == 5) {
                $mail->setFrom('notificacion@walworth.com', 'Sistema de Permisos | Director General');
            } else {
                $mail->setFrom('notificacion@walworth.com', 'Sistema de Permisos');
            }
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            $mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $datas = ['notify' => $data, 'permisos' => $query, 'color' => $query1];
            $email_template = view('notificaciones/permisos', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Permisos';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function notificarAcuentaEmail($dir_email, $title, $data, $folio_vacation)
    {

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $dir_email = changeEmail($dir_email);
        //$dir_email = "rcruz@walworth.com.mx";
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

            //Server settings
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('notificacion@walworth.com', 'Sistema de Permisos | A Cuenta de Vacaciones ');
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            $mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('hrivas@walworth.com.mx');
            /*  $mail->addCC('copiado@hotmail.com');
            $mail->addCC('copiado@hotmail.com');
            $mail->addCC('copiado@hotmail.com'); */
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $datas = ['notify' => $data, "folio" => $folio_vacation];
            $email_template = view('notificaciones/permisos_cuenta_vacaciones', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Permiso a Cuenta de Vacaciones';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function notificarVacationsEmail($dir_email, $title, $idVcns)
    {

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $dir_email = changeEmail($dir_email);

        $query = $this->db->query("SELECT a.nombre_solicitante, a.departamento, a.tipo_empleado, a.puesto, a.num_nomina,
                                          a.num_dias_a_disfrutar, a.a_cargo,DATE_FORMAT(a.fecha_registro,'%d/%m/%Y') AS fecha_registro,
                                            GROUP_CONCAT(DATE_FORMAT(b.date_vacation,'%d/%m/%Y')
                                    ORDER BY b.date_vacation ASC SEPARATOR ', ') AS dias_vacaciones, DATE_FORMAT(a.regreso,'%d/%m/%Y')regreso
                                   FROM tbl_vacations AS a
                                   JOIN tbl_vacations_items AS b on a.id_vcns = b.id_vcns
                                   WHERE a.id_vcns = $idVcns")->getRow();

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

            //Server settings
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('notificacion@walworth.com', 'Sistema de Vacaciones');
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            $mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $data = ['info' => $query];
            $email_template = view('notificaciones/permisos_vacaciones', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Permisos y Vacaciones';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function generateReportsForDirection()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $FechaFin = date("Y-m-d", strtotime($data->fecha_fin . " +1 day"));
        try {
            $cont = 2;
            $spreadsheet = new Spreadsheet();
            if ($data->tipo_reportes == 1) {
                $columnTitle = 'A1:V1';
                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
                $sheet->getStyle("$columnTitle")->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(11)
                    ->getColor()
                    ->setRGB('FFFFFF');
                $sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
                $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
                $sheet->getStyle("$columnTitle")->getBorders()->getTop()->setColor($color);
                $sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                $NombreArchivo = "entradas_salidas.xlsx";

                $sql = ($data->categoria == 1) ? "AND t1.num_nomina IN (SELECT DISTINCT wt1.payroll_number FROM tbl_assign_departments_to_managers_new AS wt1 WHERE wt1.id_director = $data->parametro)" : 'AND t1.num_nomina=' . $data->parametro;
                $typePermissions = ($data->permissions == "all") ? "" : "AND t1.id_tipo_permiso = $data->permissions ";

                $reporte = $this->db->query("SELECT t1.tipo_permiso, t1.id_es, t1.`user`, t1.fecha_creacion, t1.tipo_empleado, t1.nombre_solicitante,
                    t1.departamento, t1.num_nomina, t1.hora_salida, t1.fecha_salida, t1.hora_entrada, t1.fecha_entrada, t1.inasistencia_del, t1.inasistencia_al,
                    t1.goce_sueldo, t1.observaciones, t1.estatus,  CONCAT(b.`name`,' ',b.surname) AS authoriza,
                    CASE
                        WHEN (t1.confirm_hora_entrada IS NOT NULL OR confirm_hora_salida IS NOT NULL) AND t1.id_tipo_permiso IN (1,2) THEN
                            'SI'
                        ELSE ''
                    END AS vigilancia_confir,  
                    CASE
                        WHEN t1.id_tipo_permiso = 3 THEN
                            'A CUENTA DE VACACIONES'
                        WHEN t1.id_tipo_permiso IN (4,5) THEN
                            t1.tipo_permiso
                        WHEN (t1.confirm_hora_entrada IS NOT NULL OR confirm_hora_salida IS NOT NULL) AND t1.inasistencia_del = '0000-00-00' THEN
                            TIME_FORMAT(
                                SEC_TO_TIME(
                                    (t1.hora_vigilancia * 60 + t1.minutos_vigilancia) * 60
                                ),'%H:%i'
                            ) 
                        WHEN t1.confirm_hora_entrada IS NULL AND confirm_hora_salida IS NULL AND t1.inasistencia_del = '0000-00-00' THEN
                            TIME_FORMAT(
                                SEC_TO_TIME(
                                    (t1.hora_permiso * 60 + t1.minuto_permiso) * 60
                                ),'%H:%i'
                            ) 
                        ELSE 'INASISTENCIA'
                    END AS tiempo_solicitado,          
		            CASE 
	                    WHEN t1.id_pago_tiempo IS NULL THEN 
		                    ''
		                WHEN t1.id_pago_tiempo = 0 THEN 
		                    'Deuda'
                        WHEN t2.status_autorize = 1 THEN
                            'Pendiente'
                        WHEN t2.status_autorize = 3 THEN
                            'Rechazado'
	                    ELSE
                            TIME_FORMAT(
                                SEC_TO_TIME(
                                    SUM(t2.hour_pay * 60 + t2.min_pay) * 60
                                ), '%H:%i'
                            )END AS total_tiempo,
                 	CASE 
	                    WHEN t1.id_pago_tiempo IS NULL THEN 
                    		''
		                WHEN t1.id_pago_tiempo = 0 THEN 
		                    'Deuda'
                        WHEN t2.status_autorize = 1 THEN
                            'Pendiente'
                        WHEN t2.status_autorize = 3 THEN
                            'Rechazado'
	                    ELSE
                            GROUP_CONCAT(
                                DISTINCT CONCAT(
                                    DATE_FORMAT(t2.day_to_pay, '%d/%m/%Y')
                                )
                            SEPARATOR ', ')
                        END AS days_pay    
                    FROM tbl_entry_and_exit_permits AS t1
                        LEFT JOIN (
                            SELECT st1.id_item, st1.hour_pay , st1.min_pay, st1.day_to_pay, st1.status_autorize
                            FROM tbl_entry_and_exit_permits_time_pay_items AS st1
                            WHERE st1.active_status = 1 ) 
                        AS t2 ON FIND_IN_SET( t2.id_item, t1.id_pago_tiempo )
                    LEFT JOIN tbl_users AS b ON t1.id_usuario_autoriza = b.id_user  
                    WHERE t1.active_status = 1 
                        AND t1.fecha_creacion BETWEEN '$data->fecha_inicio' AND '$FechaFin'
                        $typePermissions $sql
                    GROUP BY t1.id_es
                ORDER BY t1.id_es DESC")->getResult();

                $sheet->setTitle("Entradas_Salidas");
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(35);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(45);
                $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);

                $sheet->setCellValue('A1', 'FOLIO');
                $sheet->setCellValue('B1', 'USUARIO');
                $sheet->setCellValue('C1', 'FECHA CREACION');
                $sheet->setCellValue('D1', 'TIPO PERMISO');
                $sheet->setCellValue('E1', 'TIPO EMPLEADO');
                $sheet->setCellValue('F1', 'NOMBRE SOLICITANTE');
                $sheet->setCellValue('G1', 'DEPARTAMENTO');
                $sheet->setCellValue('H1', 'NUMERO NOMINA');
                $sheet->setCellValue('I1', 'HORA SALIDA');
                $sheet->setCellValue('J1', 'FECHA SALIDA');
                $sheet->setCellValue('K1', 'HORA ENTRADA');
                $sheet->setCellValue('L1', 'FECHA ENTRADA');
                $sheet->setCellValue('M1', 'INASISTENCIA DEL');
                $sheet->setCellValue('N1', 'INASISTENCIA AL');
                $sheet->setCellValue('O1', 'GOCE SUELDO');
                $sheet->setCellValue('P1', 'OBSERVACIONES');
                $sheet->setCellValue('Q1', 'ESTATUS');
                $sheet->setCellValue('R1', 'USUARIO AUTORIZADOR');
                $sheet->setCellValue('S1', 'CONFIRMACION');
                $sheet->setCellValue('T1', 'TIEMPO DE PERMISO');
                $sheet->setCellValue('U1', 'TIEMPO DE PAGO');
                $sheet->setCellValue('V1', 'DIAS DE PAGO');

                foreach ($reporte as $value) {
                    $tipo_permiso = ($value->tipo_permiso == null) ? "NO DEFINIDO" : $value->tipo_permiso;
                    $confirVigilancia = ($value->id_es > 16163) ? $value->vigilancia_confir : 'REGISTRO NO EXISTENTE';
                    $fechasPago = ($value->id_es > 16163) ? ($value->days_pay ?? 'NO DEFINIDO') : 'REGISTRO NO EXISTENTE';
                    $tiempoPago = ($value->id_es > 16163) ? ($value->total_tiempo ?? 'NO DEFINIDO') : 'REGISTRO NO EXISTENTE';
                    $tiempoVigilacia = ($value->id_es > 16163) ? ($value->tiempo_solicitado ?? 'NO DEFINIDO') : 'REGISTRO NO EXISTENTE';

                    $sheet->setCellValue('A' . $cont, $value->id_es);
                    $sheet->setCellValue('B' . $cont, $value->user);
                    $sheet->setCellValue('C' . $cont, $value->fecha_creacion);
                    $sheet->setCellValue('D' . $cont, $tipo_permiso);
                    $sheet->setCellValue('E' . $cont, strtoupper($value->tipo_empleado));
                    $sheet->setCellValue('F' . $cont, $value->nombre_solicitante);
                    $sheet->setCellValue('G' . $cont, $value->departamento);
                    $sheet->setCellValue('H' . $cont, $value->num_nomina);
                    $sheet->setCellValue('I' . $cont, $value->hora_salida);
                    $sheet->setCellValue('J' . $cont, $value->fecha_salida);
                    $sheet->setCellValue('K' . $cont, $value->hora_entrada);
                    $sheet->setCellValue('L' . $cont, $value->fecha_entrada);
                    $sheet->setCellValue('M' . $cont, $value->inasistencia_del);
                    $sheet->setCellValue('N' . $cont, $value->inasistencia_al);
                    $sheet->setCellValue('O' . $cont, $value->goce_sueldo);
                    $sheet->setCellValue('P' . $cont, $value->observaciones);
                    $sheet->setCellValue('Q' . $cont, $value->estatus);
                    $sheet->setCellValue('R' . $cont, $value->authoriza);
                    $sheet->setCellValue('S' . $cont, $confirVigilancia);
                    $sheet->setCellValue('T' . $cont, $tiempoVigilacia);
                    $sheet->setCellValue('U' . $cont, $tiempoPago);
                    $sheet->setCellValue('V' . $cont, $fechasPago);
                    $cont++;
                }
            } else if ($data->tipo_reportes == 2) {
                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:R1');
                $sheet->getStyle("A1:R1")->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(11)
                    ->getColor()
                    ->setRGB('FFFFFF');
                $sheet->getStyle("A1:R1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('A1:R1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
                $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
                $sheet->getStyle('A1:R1')->getBorders()->getTop()->setColor($color);
                $sheet->getStyle('A1:R1')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
                $NombreArchivo = "vacaciones_semana.xlsx";
                $sql = ($data->categoria == 1) ? "AND a.id_user IN(SELECT DISTINCT wt1.id_user FROM tbl_assign_departments_to_managers_new AS wt1 WHERE wt1.id_director = $data->parametro )" : "AND a.id_user IN (SELECT wt1.id_user FROM tbl_users AS wt1 WHERE wt1.payroll_number = $data->parametro )";

                $reporte = $this->db->query("SELECT a.id_vcns, a.id_user, a.nombre_solicitante, a.tipo_empleado, a.departamento, a.num_nomina, 
                    a.puesto, a.num_dias_a_disfrutar, a.regreso, a.dias_restantes, a.prima_vacacional, a.estatus,
                    DATE_FORMAT(a.fecha_registro,'%d/%m/%Y') AS fecha_registro,
                    DATE_FORMAT(a.fecha_ingreso,'%d/%m/%Y') AS fecha_ingreso,
                    CONCAT(c.`name`, ' ', c.surname) AS authoriza,
                    CASE
                        WHEN a.id_vcns > 8695 THEN
                            DATE_FORMAT(MAX(b.date_vacation),'%d/%m/%Y')
                        ELSE
                            DATE_FORMAT(a.dias_a_disfrutar_al,'%d/%m/%Y')
                    END AS dias_a_disfrutar_al,
                    CASE
                        WHEN a.id_vcns > 8695 THEN
                            DATE_FORMAT(MIN(b.date_vacation),'%d/%m/%Y')
                        ELSE
                            DATE_FORMAT(a.dias_a_disfrutar_del,'%d/%m/%Y')
                    END AS dias_a_disfrutar_del,
                    CASE
                        WHEN a.id_vcns > 8695 THEN
                            GROUP_CONCAT(DISTINCT CONCAT(DATE_FORMAT(b.date_vacation,'%d/%m/%Y')) SEPARATOR ',  ')
                        ELSE 
                            'REGISTRO NO EXISTENTE'
                    END AS concatenado
                    FROM tbl_vacations AS a
                        LEFT JOIN (
                            SELECT jt1.id_vcns, jt1.date_vacation 
                            FROM tbl_vacations_items AS jt1 
                            WHERE jt1.active_status = 1
                        ) AS b ON a.id_vcns = b.id_vcns
                        LEFT JOIN tbl_users AS c ON a.user_authorizes = c.id_user
                    WHERE a.active_status = 1
                        AND a.fecha_registro  BETWEEN '$data->fecha_inicio' and '$FechaFin' 
                        $sql
                    GROUP BY a.id_vcns
                ORDER BY a.fecha_registro DESC")->getResult();

                $sheet->setTitle("Vacaciones");

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

                $sheet->setCellValue('A1', 'ID_VCNS');
                $sheet->setCellValue('B1', 'USUARIO');
                $sheet->setCellValue('C1', 'FECHA_CREACION');
                $sheet->setCellValue('D1', 'NOMBRE_SOLICITANTE');
                $sheet->setCellValue('E1', 'TIPO_EMPLEADO');
                $sheet->setCellValue('F1', 'DEPARTAMENTO');
                $sheet->setCellValue('G1', 'NUMERO_NOMINA');
                $sheet->setCellValue('H1', 'PUESTO');
                $sheet->setCellValue('I1', 'FECHA_CREACION');
                $sheet->setCellValue('J1', 'NUM_DIAS_A_DISFRUTAR');
                $sheet->setCellValue('K1', 'DIAS_A_DISFRUTAR_DEL');
                $sheet->setCellValue('L1', 'DIAS_A_ADISFRUTAR_AL');
                $sheet->setCellValue('M1', 'DIA(S)_EXACTOS');
                $sheet->setCellValue('N1', 'REGRESO');
                $sheet->setCellValue('O1', 'DIAS_RESTANTES');
                $sheet->setCellValue('P1', 'PRIMA_VACACIONAL');
                $sheet->setCellValue('Q1', 'ESTATUS');
                $sheet->setCellValue('R1', 'USUARIO AUTORIZADOR');

                foreach ($reporte as $key => $value) {
                    $sheet->setCellValue('A' . $cont, $value->id_vcns);
                    $sheet->setCellValue('B' . $cont, $value->id_user);
                    $sheet->setCellValue('C' . $cont, $value->fecha_registro);
                    $sheet->setCellValue('D' . $cont, $value->nombre_solicitante);
                    $sheet->setCellValue('E' . $cont, $value->tipo_empleado);
                    $sheet->setCellValue('F' . $cont, $value->departamento);
                    $sheet->setCellValue('G' . $cont, $value->num_nomina);
                    $sheet->setCellValue('H' . $cont, $value->puesto);
                    $sheet->setCellValue('I' . $cont, $value->fecha_ingreso);
                    $sheet->setCellValue('J' . $cont, $value->num_dias_a_disfrutar);
                    $sheet->setCellValue('K' . $cont, $value->dias_a_disfrutar_del);
                    $sheet->setCellValue('L' . $cont, $value->dias_a_disfrutar_al);
                    $sheet->setCellValue('M' . $cont, $value->concatenado);
                    $sheet->setCellValue('N' . $cont, $value->regreso);
                    $sheet->setCellValue('O' . $cont, $value->dias_restantes);
                    $sheet->setCellValue('P' . $cont, $value->prima_vacacional);
                    $sheet->setCellValue('Q' . $cont, $value->estatus);
                    $sheet->setCellValue('R' . $cont, $value->authoriza);
                    $cont++;
                }
            } else {
                $columnTitle = 'A1:J1';
                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
                $sheet->getStyle("$columnTitle")->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(11)
                    ->getColor()
                    ->setRGB('FFFFFF');
                $sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
                $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
                $sheet->getStyle("$columnTitle")->getBorders()->getTop()->setColor($color);
                $sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

                $NombreArchivo = "pago_tiempo_semana.xlsx";
                $reporte = $this->db->query("SELECT t1.id_item, t1.payroll_number, 
                    CONCAT(t2.`name`, ' ', t2.surname) AS usuario,
                    CONCAT(t3.`name`, ' ', t3.surname) AS manager,
                    DATE_FORMAT(t1.day_to_pay, '%d/%m/%Y') AS day_pay,
                    TIME_FORMAT(SEC_TO_TIME((t1.hour_pay * 60 + t1.min_pay) * 60),'%H:%i') AS time_pay,
                    CASE
                        WHEN t2.type_of_employee = 1 THEN
                            'ADMINISTRATIVO'
                        WHEN t2.type_of_employee = 2 THEN
                            'SINDICALIZADO'
                    END AS type_employe,
                    CASE
                        WHEN t1.type_pay = 1 THEN
                            TIME_FORMAT(hour_in, '%H:%m')
                        WHEN t1.type_pay = 2 THEN
                            TIME_FORMAT(hour_out, '%H:%m')
                        ELSE
                            CONCAT(TIME_FORMAT(hour_in, '%H:%m'), '  ---  ', TIME_FORMAT(hour_out, '%H:%m'))
                    END AS check_clock,
                    CASE
                        WHEN status_autorize = 1 THEN 'PENDIENTE'
                        WHEN status_autorize = 2 THEN 'AUTORIZADO'
                        WHEN status_autorize = 3 THEN 'RACHAZADA'
                        END AS txt_autorize,
                    CASE
                        WHEN available_used_debit = 1 THEN 'DISPONIBLE'
                        WHEN available_used_debit = 2 THEN 'USADO'
                        WHEN available_used_debit = 3 THEN 'DEUDA'
                    END AS txt_estatus,
                    CASE
                        WHEN status_autorize = 3 AND available_used_debit = 1 THEN
                            'RACHAZADA'
                        WHEN status_autorize = 1 AND available_used_debit = 1 THEN
                            'PENDIENTE'
                        WHEN status_autorize = 2 AND available_used_debit = 1 THEN
                            'AUTORIZADO SIN USO'
                        WHEN status_autorize = 2 AND available_used_debit = 2 THEN
                            'AUTORIZADO Y USADO'
                        WHEN status_autorize = 1 AND available_used_debit = 3 THEN
                            'DEUDA'
                        WHEN status_autorize = 2 AND available_used_debit = 3 THEN
                            'DEUDA PAGADA'
                        WHEN status_autorize = 3 AND available_used_debit = 3 THEN
                            'DEUDA NO PAGADA'
                    END AS estado
                FROM tbl_entry_and_exit_permits_time_pay_items AS t1
                    LEFT JOIN tbl_users AS t2 ON t1.id_user = t2.id_user
                    LEFT JOIN tbl_users AS t3 ON t3.id_user = t1.id_manager_authorize
                WHERE t1.active_status = 1
                AND t1.created_at BETWEEN '$data->fecha_inicio' and '$FechaFin' 
                ORDER BY t1.id_item DESC")->getResult();

                $sheet->setTitle("Pago de Tiempo");
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

                $sheet->setCellValue('A1', 'FOLIO');
                $sheet->setCellValue('B1', 'USUARIO');
                $sheet->setCellValue('C1', 'NOMINA');
                $sheet->setCellValue('D1', 'TIPO EMPLEADO');
                $sheet->setCellValue('E1', 'DIA DE PAGO');
                $sheet->setCellValue('F1', 'TIEMPO');
                $sheet->setCellValue('G1', 'HORA CHECADA');
                $sheet->setCellValue('H1', 'ESTADO PERMISO');
                $sheet->setCellValue('I1', 'AUTORIZACION');
                $sheet->setCellValue('J1', 'RESPONSABLE');

                foreach ($reporte as $key => $value) {
                    $sheet->setCellValue('A' . $cont, $value->id_item);
                    $sheet->setCellValue('B' . $cont, $value->usuario);
                    $sheet->setCellValue('C' . $cont, $value->payroll_number);
                    $sheet->setCellValue('D' . $cont, $value->type_employe);
                    $sheet->setCellValue('E' . $cont, $value->day_pay);
                    $sheet->setCellValue('F' . $cont, $value->time_pay);
                    $sheet->setCellValue('G' . $cont, $value->check_clock);
                    $sheet->setCellValue('H' . $cont, $value->txt_estatus);
                    $sheet->setCellValue('I' . $cont, $value->txt_autorize);
                    $sheet->setCellValue('J' . $cont, $value->manager);
                    $cont++;
                }
            }

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
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function myPermits()
    {
        return ($this->is_logged) ? view('permissions/permissions_per_user') : redirect()->to(site_url());
    }

    public function viewSpecialPermission()
    {
        return ($this->is_logged) ? view('permissions/permissions_special_permiss') : redirect()->to(site_url());
    }

    public function insertPermissSpecial()
    {
        try {
            $typePermiss = $this->request->getPost("tipo_permiso");
            $dayPermiss = $this->request->getPost("dia_permiso");
            $maxTimea = $this->request->getPost("hora_entrada");
            $timePermis_h = $this->request->getPost("horas");
            $timePermis_i = $this->request->getPost("min");
            $inPermis = $this->request->getPost("in_permis");
            $outPermis = $this->request->getPost("out_permis");
            $absencePermis = $this->request->getPost("absence_permis");
            $motive = trim($this->request->getPost("motivo"));
            $obs = trim($this->request->getPost("obs"));
            $this->db->transStart();
            if ($typePermiss == 1) {
                $arrayDayPermiss = explode(",", $dayPermiss);
                for ($i = 0; $i < count($arrayDayPermiss); $i++) {
                    $insertData = [
                        'type_permiss' => $typePermiss,
                        'day_permiss' => $arrayDayPermiss[$i],
                        'motive' => strtoupper($motive),
                        'obs' => $obs,
                        'active_in' => $inPermis ?? 0,
                        'active_out' => $outPermis ?? 0,
                        'active_absence' => $absencePermis ?? 0,
                        'time_permis_h' => $timePermis_h,
                        'time_permis_i' => $timePermis_i,
                        'id_created' => session()->id_user,
                        'created_at' => date("Y-m-d H:i:s")
                    ];
                    $this->specialModel->insert($insertData);
                }
            } else if ($typePermiss == 2) {
                $insertData = [
                    'type_permiss' => $typePermiss,
                    'day_permiss' => $dayPermiss,
                    'motive' => $motive,
                    'obs' => $obs,
                    'max_time' => $maxTimea,
                    'id_created' => session()->id_user,
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->specialModel->insert($insertData);
            }


            $result = $this->db->transComplete();
            return json_encode($result);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function listTablePermissSpecial()
    {
        /*
        $this->db->query("UPDATE tbl_days_special_permiss 
            SET enabled_status = 0
            WHERE active_status = 1 
            AND enabled_status = 1 
        AND day_permiss < CURDATE()");
        */
        $data = $this->db->query("SELECT id_day_festive, enabled_status, UPPER(motive) AS tipo,
            DATE_FORMAT(day_permiss,'%d/%m/%Y') AS fecha_permiso, 
            CASE 
	            WHEN type_permiss = 1 AND active_absence = 1 THEN
		            'Día libre'
		        WHEN type_permiss = 1 AND (active_in = 1 OR active_out = 1) THEN
		            TIME_FORMAT(SEC_TO_TIME((time_permis_h * 60 + time_permis_i) * 60),'%H Horas, %i minutos')
	            ELSE
		            ''
            END AS tiempo,
            IF(type_permiss = 2,TIME_FORMAT(max_time ,'%H:%i'),'') AS hora_in
            FROM tbl_days_special_permiss 
        WHERE active_status = 1")->getResult();
        $result = ($data) ? $data : false;
        return json_encode($result);
    }

    public function onOffPermissSpecial()
    {
        $idFolio = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $this->db->transStart();
        $this->db->query("UPDATE tbl_days_special_permiss 
            SET enabled_status = $status
        WHERE id_day_festive = $idFolio");
        $result = $this->db->transComplete();
        return json_encode($result);
    }

    public function deletePermissSpecial()
    {
        $idFolio = $this->request->getPost('id');
        $this->db->transStart();
        $this->db->query("UPDATE tbl_days_special_permiss 
            SET active_status = 2
        WHERE id_day_festive = $idFolio");
        $result = $this->db->transComplete();
        return json_encode($result);
    }

    public function my_Permits()
    {
        //$data  = $this->permissionsModel->where('id_user', session()->id_user)->findAll();
        $idUser = session()->id_user;
        $data = $this->db->query("SELECT a.id_es, a.fecha_creacion, a.nombre_solicitante, a.departamento, a.tipo_permiso, a.estatus,
            a.fecha_salida, a.hora_salida, 
            a.fecha_entrada, a.hora_entrada, 
            a.inasistencia_del, a.inasistencia_al,
            IF ( a.id_tipo_permiso IS NULL, '#BDBDBD', ( SELECT st1.color FROM cat_color_type_permiss AS st1 WHERE st1.type_permiss = a.id_tipo_permiso ) ) AS colorPermiss 
            FROM tbl_entry_and_exit_permits AS a 
            WHERE a.active_status = 1 
        AND a.id_user = $idUser")->getResult();
        return json_encode($data);
    }

    public function permissionsAll()
    {
        return ($this->is_logged) ? view('permissions/permissions_all') : redirect()->to(site_url());
    }
    public function reports()
    {
        return ($this->is_logged) ? view('permissions/permissions_reports') : redirect()->to(site_url());
    }

    public function editPermissionVacations($typo = null)
    {
        $id_folio = $this->request->getPost('id_folio');
        if ($typo == 1) {
            $builder = $this->db->table('tbl_vacations');
            $builder->select('*');
            $builder->where('id_vcns', $id_folio);
            $builder->limit(1);
            $data = $builder->get()->getResult();
        } else if ($typo == 2) {
            $data = $this->db->query("SELECT a.id_user,a.num_nomina, a.id_depto, a.id_vcns, a.nombre_solicitante, a.num_dias_a_disfrutar, DATE_FORMAT(regreso,'%d/%m/%Y') AS regreso,
            GROUP_CONCAT(DISTINCT CONCAT(b.date_vacation) SEPARATOR ',') AS concatenado,
            GROUP_CONCAT(DISTINCT CONCAT(b.id_item) SEPARATOR ',') AS items
            FROM
                tbl_vacations AS a
            LEFT JOIN tbl_vacations_items AS b ON a.id_vcns = b.id_vcns
            WHERE a.id_vcns = $id_folio
                AND b.active_status = 1
            GROUP BY a.id_vcns")->getRow();
        } else if ($typo == 3) {
            $data = $this->db->query("SELECT a.id_vcns,a.num_nomina, a.nombre_solicitante, a.num_dias_a_disfrutar, a.regreso, a.a_cargo, a.id_a_cargo,
                CASE 
                    WHEN a.id_vcns > 8694 THEN 
                        MAX(c.date_vacation)
                    ELSE 
                        a.dias_a_disfrutar_al
                    END AS dias_a_disfrutar_al,
                CASE 
                    WHEN a.id_vcns > 8694 THEN 
                        MIN(c.date_vacation)
                    ELSE 
                        a.dias_a_disfrutar_del
                    END AS dias_a_disfrutar_del
                FROM tbl_vacations AS a
                    LEFT JOIN (SELECT st1.date_vacation, st1.id_vcns  FROM tbl_vacations_items AS st1 WHERE st1.active_status = 1 ) AS c ON a.id_vcns = c.id_vcns
                WHERE a.id_vcns = $id_folio
            GROUP BY a.id_vcns")->getRow();
        }
        return json_encode($data);
    }

    public function updateDaysVacationsNew()
    {
        $idItems = $this->request->getPost("id_items_new");
        $idUser = $this->request->getPost("id_user_new");
        $idDepto = $this->request->getPost("id_depto_new");
        $idFolio = $this->request->getPost("editar_vcns_new");
        $arrayDaysToVacations = $this->request->getPost("vacaciones_dias_disfrutar");
        $vacacionesRegresar = DateTime::createFromFormat('d/m/Y', $this->request->getPost("vacaciones_regresar_actividades"))->format('Y-m-d');
        $diasAnteriores = $this->db->query("SELECT num_dias_a_disfrutar AS cantidad, estatus FROM tbl_vacations WHERE id_vcns = $idFolio")->getRow();

        $arraysEstatusItem = ["Pendiente" => 1, "Autorizada" => 2, "Rechazada" => 3, "Cancelada" => 4];
        $estatusItems = $arraysEstatusItem[$diasAnteriores->estatus];

        $this->db->transStart();

        $this->db->query("UPDATE tbl_vacations_items SET active_status = 2 WHERE id_item IN ($idItems)");

        $arraItems = explode(",", $arrayDaysToVacations);
        $countArray = count($arraItems);
        for ($i = 0; $i < $countArray; $i++) {
            $dataItems = [
                'id_vcns' => $idFolio,
                'id_user' => $idUser,
                'id_depto' => $idDepto,
                'active' => $estatusItems,
                'date_vacation' => $arraItems[$i],
            ];
            $this->vacationItemsModel->insert($dataItems);
        }

        $data = [
            "regreso" => $vacacionesRegresar,
            "num_dias_a_disfrutar" => $countArray,
            "id_update" => session()->id_user,
            "update_at" => date("Y-d-m H:i:s"),
        ];
        $this->vacationModel->update($idFolio, $data);

        if ($diasAnteriores->cantidad != $countArray) {
            $diferenciaDias = $diasAnteriores->cantidad - $countArray;
            $accion = ($diferenciaDias < 0) ? $diferenciaDias : '+' .  $diferenciaDias;
            $this->db->query("UPDATE tbl_users SET vacation_days_total = vacation_days_total $accion WHERE id_user = $idUser");
            $this->db->query("UPDATE tbl_vacations SET dias_restantes = dias_restantes $accion WHERE id_vcns = $idFolio");
        }

        $result = $this->db->transComplete();
        return json_encode($result);
    }

    public function deleteVacations()
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $query1 = $this->db->query("SELECT a.estatus, a.num_dias_a_disfrutar, a.id_user, b.id_es
            FROM tbl_vacations AS a LEFT JOIN tbl_entry_and_exit_permits AS b ON a.id_vcns = b.acuenta_vacaciones
            WHERE a.id_vcns = $id_folio")->getRow();
            $result2 = $this->vacationsDelete($id_folio, $query1->estatus, $query1->num_dias_a_disfrutar, $query1->id_user);
            if ($query1->id_es != null) {
                $permis = $this->db->query("SELECT estatus, fecha_creacion, tipo_permiso, id_pago_tiempo FROM tbl_entry_and_exit_permits WHERE id_es = $query1->id_es")->getRow();
                $result1 = $this->permissDelete($query1->id_es, $permis->estatus, $permis->fecha_creacion, $permis->id_pago_tiempo);
            } else {
                $result1 = true;
            }
            return ($result1 && $result2) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function deletePermissions()
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $permis = $this->db->query("SELECT estatus, fecha_creacion, acuenta_vacaciones,tipo_permiso, id_pago_tiempo FROM tbl_entry_and_exit_permits WHERE id_es = $id_folio")->getRow();
            $result1 = $this->permissDelete($id_folio, $permis->estatus, $permis->fecha_creacion, $permis->id_pago_tiempo);
            if ($permis->acuenta_vacaciones != null) {
                $query1 = $this->db->query("SELECT estatus, num_dias_a_disfrutar, id_user FROM tbl_vacations WHERE id_vcns = $permis->acuenta_vacaciones")->getRow();
                $result2 = $this->vacationsDelete($permis->acuenta_vacaciones, $query1->estatus, $query1->num_dias_a_disfrutar, $query1->id_user);
            } else {
                $result2 = true;
            }
            return ($result1 && $result2) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function permissDelete($idPermis, $status, $dateCreated, $idPayTime)
    {
        try {
            if ($status == "Autorizada" || $status == "Pendiente") {
                $diff = (new DateTime($dateCreated))->diff(new DateTime(date('Y-m-d')));
                if ($diff->m == 0 && $diff->y == 0) {
                    $query = $this->db->query("SELECT  id, amount_permissions FROM tbl_assign_departments_to_managers_new 
                    WHERE id_user IN (SELECT id_user FROM tbl_entry_and_exit_permits WHERE id_tipo_permiso = 2 AND id_es = $idPermis)")->getRow();
                    if ($query != null) {
                        $amount = ($query->amount_permissions > 0) ? intval($query->amount_permissions) - 1 : 0;
                        $this->db->query("UPDATE tbl_assign_departments_to_managers_new SET amount_permissions = $amount WHERE id = $query->id");
                    }
                }
            }
            $data = ['active_status' => 2];
            $builder = $this->db->table('tbl_entry_and_exit_permits');
            $builder->where('id_es', $idPermis);
            $result = $builder->update($data);
            $this->db->query("UPDATE tbl_entry_and_exit_permits_items SET active_status = 2 WHERE id_es = $idPermis");

            if ($idPayTime != null && $idPayTime != 0) {
                $idItems = $idPayTime;
                $this->db->query("UPDATE tbl_entry_and_exit_permits_time_pay_items SET available_used_debit = 1 WHERE id_item IN ($idItems)");
            }

            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function vacationsDelete($idVacation, $status, $days, $user)
    {
        try {
            if ($status == "Autorizada" || $status == "Pendiente") {
                $query = $this->db->query("SELECT vacation_days_total FROM tbl_users WHERE id_user = $user")->getRow();
                $newDays = intval($query->vacation_days_total) + intval($days);
                $this->db->query("UPDATE tbl_users SET vacation_days_total = $newDays WHERE id_user = $user");
            }
            $dataV = ['active_status' => 2];
            $result = $this->vacationModel->update($idVacation, $dataV);
            $this->db->query("UPDATE tbl_vacations_items SET active_status = 2 WHERE id_vcns = $idVacation");

            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function authorizePermission() // autorizar permisos
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $status = $this->request->getPost('estatus');
            $estatus = ($status == 1) ? 'Autorizada' : 'Rechazada';
            $id_user = session()->id_user;
            $notificacionEnviada = false;
            $cont = 0;
            $data = [
                'id_usuario_autoriza' => $id_user,
                'estatus' => $estatus,
                'autorized_at' => date("Y-m-d H:i:s"),
            ];

            $this->db->transStart();

            $permis = $this->db->query("SELECT fecha_creacion, acuenta_vacaciones, id_pago_tiempo FROM tbl_entry_and_exit_permits WHERE id_es = $id_folio")->getRow();
            if ($status == 2) {
                $diff = (new DateTime($permis->fecha_creacion))->diff(new DateTime(date('Y-m-d')));
                if ($diff->m == 0 && $diff->y == 0) {
                    $query = $this->db->query("SELECT id, amount_permissions FROM tbl_assign_departments_to_managers_new
                        WHERE id_user IN (SELECT id_user FROM tbl_entry_and_exit_permits WHERE tipo_permiso = 'PERSONAL' AND id_es = $id_folio)")->getRow();
                    if ($query != null) {
                        $amount = ($query->amount_permissions > 0) ? intval($query->amount_permissions) - 1 : 0;
                        $this->db->query("UPDATE tbl_assign_departments_to_managers_new SET amount_permissions = $amount WHERE id = $query->id");
                    }
                }
            }

            $arraysEstatusItem = ["Pendiente" => 1, "Autorizada" => 2, "Rechazada" => 3, "Cancelada" => 4];
            $estatusItems = $arraysEstatusItem[$estatus];

            if ($permis->acuenta_vacaciones != null) {
                $id_vacation = intval($permis->acuenta_vacaciones);
                if ($estatus == "Rechazada") {

                    $queryVcns = $this->db->query("SELECT num_nomina, num_dias_a_disfrutar
                    FROM tbl_vacations WHERE id_vcns = $id_vacation")->getRow();
                    $payroll_number = $queryVcns->num_nomina;
                    $days = $queryVcns->num_dias_a_disfrutar;

                    $builder = $this->db->table('tbl_users');
                    $builder->set('vacation_days_total', 'vacation_days_total +' . $days, false);
                    $builder->where('payroll_number', $payroll_number);
                    $builder->update();
                }


                $this->db->query("UPDATE tbl_vacations_items SET `status` = $estatusItems WHERE id_vcns = $id_vacation");
                $dataV = [
                    'estatus' => $estatus,
                    "user_authorizes" => session()->id_user,
                    'autorized_at' => date("Y-m-d H:i:s"),
                ];
                $this->vacationModel->update($id_vacation, $dataV);
            }

            if ($permis->id_pago_tiempo != null && $permis->id_pago_tiempo != 0) {
                $idItems = $permis->id_pago_tiempo;
                $this->db->query("UPDATE tbl_entry_and_exit_permits_time_pay_items SET available_used_debit = 1 WHERE id_item IN ($idItems)");
            }
            $this->permissionsModel->update($id_folio, $data);
            $this->db->query("UPDATE tbl_entry_and_exit_permits_items SET estatus = $estatusItems WHERE id_es = $id_folio");

            $result = $this->db->transComplete();

            $Directores = [
                3,
                20,
                26,
                33,
                37,
                56,
                66,
                81,
                86,
                92,
                117,
                120,
                217,
                254,
                259,
                261,
                265,
                268,
                269,
                272,
                303,
                304,
                314,
                315,
                322,
                328,
                338,
                339,
                340,
                346,
                347,
                377,
                378,
                639,
                695,
                833,
                1119,
                2,
                27,
                75,
                151,
                159,
                250,
                251,
                252,
                253,
                258,
                262,
                375,
                592,
                852,
                1390,
                905,
                1188
            ];
            $permis = $this->db->query("SELECT id_user FROM tbl_entry_and_exit_permits WHERE id_es = $id_folio LIMIT 1")->getRow();
            $valorABuscar = $permis->id_user; // Este es el ID que deseas buscar

            if (in_array($valorABuscar, $Directores) && !$notificacionEnviada) {
                // El valor (ID) fue encontrado en el array
                $nData = $this->db->query("SELECT * FROM tbl_entry_and_exit_permits WHERE id_es = $id_folio LIMIT 1")->getRow();

                $email = 'msanchez@walworth.com.mx';
                $title = "Autorizacion de Permisos";

                if ($cont == 0) {
                    $this->autorizaPermisosEmail($email, $title, $nData);
                    $cont++;
                }


                $notificacionEnviada = true; // Bandera que indica que la notificación fue enviada

            }





            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    /* ***** ACTUALIZACION  VIGILANCIA *****  */
    public function authorizedPermissions()
    {
        try {
            $data = $this->db->query("SELECT id_es, nombre_solicitante, estatus, confirm_hora_entrada, confirm_hora_salida,
                CASE
                    WHEN hora_salida = '00:00:00' THEN
                        '-----'
                    ELSE 
                        CONCAT(DATE_FORMAT(fecha_salida, '%d-%m-%Y'),'    ',TIME_FORMAT(hora_salida, '%H:%i'))
                END AS out_data,
                CASE
                    WHEN hora_entrada = '00:00:00' THEN 
                        '-----'
                    ELSE 
                        CONCAT(DATE_FORMAT(fecha_entrada, '%d-%m-%Y'),'    ',TIME_FORMAT(hora_entrada, '%H:%i'))
                END AS in_data,                
                CASE 
                    WHEN hora_salida <> '00:00:00' AND hora_entrada <> '00:00:00' AND confirm_hora_salida IS NULL THEN
		                CONCAT( fecha_salida, ' ', hora_salida )
		            WHEN hora_salida <> '00:00:00' AND hora_entrada <> '00:00:00' AND confirm_hora_salida IS NOT NULL THEN
		                CONCAT( fecha_entrada, ' ', hora_entrada ) 
	                WHEN hora_salida <> '00:00:00' THEN
		                CONCAT(fecha_salida,' ',hora_salida)
	                WHEN hora_entrada <> '00:00:00' THEN
		                CONCAT(fecha_entrada,' ',hora_entrada)
                END AS orden
                FROM tbl_entry_and_exit_permits
                WHERE active_status = 1 
                    AND (estatus = 'Autorizada' 
                        OR estatus = 'Pendiente')
                    AND id_tipo_permiso <> 5
                ORDER BY orden DESC
            LIMIT 1000")->getResult();
            return (count($data) > 0) ? json_encode($data) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizedPermissionsVilla()
    {
        try {
            $builder = $this->db->table('tbl_entry_and_exit_permits');
            $builder->select('*');
            $where = "estatus='Autorizada' OR estatus='Pendiente'";
            $builder->where($where);
            $builder->where('area_operativa', 71);
            $builder->limit(1000);
            $builder->orderBy('id_es', 'DESC');
            $data = $builder->get()->getResult();
            return (count($data) > 0) ? json_encode($data) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }


    public function editVacations()
    {
        $id_folio = $this->request->getPost('id_folio');
        $data = $this->db->query("SELECT a.id_vcns, a.nombre_solicitante, a.regreso, a.num_dias_a_disfrutar, a.num_nomina, a.a_cargo,
            CASE 
                WHEN a.id_vcns > 8694 THEN 
                    MAX(c.date_vacation)
                ELSE 
                    a.dias_a_disfrutar_al
                END AS dias_a_disfrutar_al,
            CASE 
                WHEN a.id_vcns > 8694 THEN 
                    MIN(c.date_vacation)
                ELSE 
                    a.dias_a_disfrutar_del
                END AS dias_a_disfrutar_del
            FROM tbl_vacations AS a
                LEFT JOIN (SELECT st1.date_vacation, st1.id_vcns  FROM tbl_vacations_items AS st1 WHERE st1.active_status = 1 ) AS c ON a.id_vcns = c.id_vcns
         WHERE a.id_vcns = $id_folio")->getRow();
        return json_encode($data);
    }

    public function authorizeVacation() // autorizar vacaciones
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $status = $this->request->getPost('estatus');
            $days = $this->request->getPost('dias');
            $payroll_number = $this->request->getPost('num_nomina');
            $estatus = ($status == 1) ? 'Autorizada' : 'Rechazada';
            $id_user = session()->id_user;
            $notificacionEnviada = false;

            if ($status == 2) {

                /* $builder = $this->db->table('tbl_users');
                $builder->set('vacation_days_total', 'vacation_days_total +' . $days, false);
                $builder->where('payroll_number', $payroll_number);
                $builder->update(); */
                $this->db->query("UPDATE tbl_users SET vacation_days_total = vacation_days_total + $days WHERE payroll_number = $payroll_number");
            }
            $arraysEstatusItem = ["Pendiente" => 1, "Autorizada" => 2, "Rechazada" => 3, "Cancelada" => 4];
            $estatusItems = $arraysEstatusItem[$estatus];

            $this->db->query("UPDATE tbl_vacations_items SET `status` = $estatusItems WHERE id_vcns = $id_folio");

            $dataP = [
                'estatus' => $estatus,
                'id_usuario_autoriza' => $id_user,
                'autorized_at' => date("Y-m-d H:i:s"),
            ];

            $builder = $this->db->table('tbl_entry_and_exit_permits');
            $builder->where('acuenta_vacaciones', $id_folio);
            $builder->update($dataP);



            $dataV = [
                'estatus' => $estatus,
                'user_authorizes' =>  $id_user,
                'autorized_at' => date("Y-m-d H:i:s"),
            ];

            $builder = $this->db->table('tbl_vacations');
            $builder->where('id_vcns', $id_folio);
            $resultV = $builder->update($dataV);

            $dataId = $this->db->query("SELECT id_user 
            FROM tbl_users 
            WHERE  payroll_number = $payroll_number")->getRow();

            $id_usuario = $dataId->id_user;

            $Directores = [
                3,
                20,
                26,
                33,
                37,
                56,
                66,
                81,
                86,
                92,
                117,
                120,
                217,
                254,
                259,
                261,
                265,
                268,
                269,
                272,
                303,
                304,
                314,
                315,
                322,
                328,
                338,
                339,
                340,
                346,
                347,
                356,
                377,
                378,
                639,
                695,
                833,
                1119,
                2,
                27,
                75,
                151,
                159,
                250,
                251,
                252,
                253,
                258,
                262,
                375,
                592,
                852,
                1390,
                905,
                1188,
                1
            ];
            $valorABuscar = $id_usuario; // Este es el ID que deseas buscar

            if (in_array($valorABuscar, $Directores) && !$notificacionEnviada) {
                // El valor (ID) fue encontrado en el array
                $dataEmail = $this->db->query("SELECT `name`, surname 
                                                FROM tbl_users 
                                                WHERE  payroll_number = $payroll_number")->getRow();

                $email = 'mosanchez@walworth.com.mx';
                $title = $dataEmail->name . " " . $dataEmail->surname;

                $dataVacation = $this->db->query("SELECT num_dias_a_disfrutar,estatus,regreso
                                                 FROM tbl_vacations
                                                 WHERE  id_vcns = $id_folio")->getRow();

                $days = $dataVacation->num_dias_a_disfrutar;
                $status = $dataVacation->estatus;

                $nData = [
                    'folio' => $id_folio,
                    'status' => $status,
                    'usuario' => $title,
                    'dias' => $days
                ];
                $this->autorizarVacationsEmail($email, $title, $nData);
                $notificacionEnviada = true; // Bandera que indica que la notificación fue enviada
            }

            return ($resultV) ? json_encode($resultV) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function autorizarVacationsEmail($dir_email, $title, $data)
    {

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

            //Server settings
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('notificacion@walworth.com', 'Sistema de Vacaciones');
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            $mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/permisos_autorizados', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Autorización de Vacaciones';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function autorizaPermisosEmail($dir_email, $title, $data)
    {

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $dir_email = changeEmail($dir_email);
        $title;
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

            //Server settings
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('notificacion@walworth.com', 'Sistema de Permisos');
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            //$mail->addAddress($dir_email, "Monserrat Sanchez");
            $mail->addAddress($dir_email, "Monserrat Sanchez");
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Informacion del Sistema');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name
            $datas = ["data" => $data];
            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/permisos_autorizados_entradas', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Autorización de Permisos';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function vacationPermission()
    {
        try {
            if ($this->is_logged) {
                $idUser = session()->id_user;
                // $this->vacationItemsModel
                $user = trim($this->request->getPost('usuario'));
                $departament = trim($this->request->getPost('depto'));
                $job_position = trim($this->request->getPost('puesto_trabajo'));
                $payroll_number = trim($this->request->getPost('num_nomina'));
                $date_admission = trim($this->request->getPost('fecha_ingreso'));
                $type_of_employee = trim($this->request->getPost('tipo_empleado'));
                $days_available = trim($this->request->getPost('dias_disponibles'));
                $arrayDaysToEnjoy = str_replace(' ', '', $this->request->getPost('dias_disfrutar'));
                $days_to_enjoy = trim($this->request->getPost('cant_dias_disfrutar'));
                $return_activities = DateTime::createFromFormat('d/m/Y', ($this->request->getPost('regresar_actividades')))->format('Y-m-d');
                $date = date("Y-m-d H:i:s");
                $insert_permissions = false;
                $vacation_type = trim($this->request->getPost('tipo_vacaciones'));
                $aCargo = '';
                $aCargoId = 0;

                if ($idUser == 1063) {
                    echo "SELECT IF (a.vacation_days_total - $days_to_enjoy < 0 , 
                                IF( -1 * (a.vacation_days_total - $days_to_enjoy) <= b.days_new, 1, 2 )
                                ,1
                            ) AS dias
                            FROM tbl_users AS a
                            JOIN cat_vacation_days AS b ON (a.years_worked + 1) = b.years
                        WHERE a.negative_special_vacation = 0 
	                AND a.id_user = $idUser";
                    return json_encode(false);
                }
                if ($queryDays = $this->db->query("SELECT 
                    IF (a.vacation_days_total - $days_to_enjoy < 0 , 
                        IF( -1 * (a.vacation_days_total - $days_to_enjoy) <= b.days_new, 1, 2 )
                        ,1
                    ) AS dias
                    FROM tbl_users AS a
	                    JOIN cat_vacation_days AS b ON (a.years_worked + 1) = b.years
                    WHERE a.negative_special_vacation = 0 
	                AND a.id_user = $idUser")->getRow()) {
                    if ($queryDays->dias != 1) {
                        return json_encode("faltaDias");
                    }
                }

                if ($vacation_type == 1 && session()->type_of_employee == 1) {
                    $aCargoId = trim($this->request->getPost('a_cargo'));

                    if ($aCargoId == 111111) {
                    } else {
                        $valitacionVacations = $this->db->query("SELECT id_item
                            FROM tbl_vacations_items
                            WHERE active_status = 1                    
                                AND id_vcns IN (SELECT id_vcns 
                                    FROM tbl_vacations 
                                    WHERE id_user = $aCargoId AND (estatus = 'Autorizada' OR estatus = 'Pendiente')
                                )
                            AND FIND_IN_SET(date_vacation, '$arrayDaysToEnjoy')")
                            ->getRow();

                        if ($valitacionVacations != null) {
                            return json_encode("compaVacacionando");
                        }
                    }

                    $array = explode(',', $arrayDaysToEnjoy);
                    $start_days = min($array);
                    $end_of_days = max($array);
                    if ($aCargoId != 111111) {
                        $valitacionVacations2 = $this->db->query("SELECT dias_a_disfrutar_del AS inicio, dias_a_disfrutar_al AS final
                            FROM tbl_vacations  
                            WHERE id_user = $aCargoId AND active_status = 1
                            AND (estatus = 'Autorizada' OR estatus = 'Pendiente')
                            AND (
                                dias_a_disfrutar_al BETWEEN '$start_days' AND '$end_of_days'
                                OR
                                dias_a_disfrutar_del BETWEEN '$start_days' AND '$end_of_days'
                                OR 
                                (
                                    dias_a_disfrutar_del > '$start_days'
                                    AND
                                    dias_a_disfrutar_al < '$end_of_days'
                                ) 
                        )")->getRow();
                        if ($valitacionVacations2 != null) {
                            return json_encode("compaVacacionando");
                        }
                    }

                    if ($aCargoId == 111111) {
                        $aCargo = "Gabriela Garcia";
                    } else {
                        $query = $this->db->query("SELECT CONCAT(`name`,' ',surname,' ',second_surname) AS nombreUser FROM tbl_users WHERE id_user = $aCargoId ")->getRow();
                        $aCargo = $query->nombreUser;
                    }
                }

                if ($vacation_type == 2 || $vacation_type == 3) {
                    $acuenta = 2;
                } else {
                    $acuenta = 1;
                }

                $this->db->transStart();
                $data  = $this->db->query("SELECT vacation_days_total FROM tbl_users WHERE id_user = " . session()->id_user)->getRow();
                $dias_restantes = intval($data->vacation_days_total) - intval($days_to_enjoy);
                $dataUser = ['vacation_days_total' => $dias_restantes];
                $update = $this->model->update(session()->id_user, $dataUser);
                /* if (session()->id_user == 1248) {
                echo 'DIAS DE QUERI INICAL: '. $data->vacation_days_total .'<br>';
                echo 'DIAS SOLICITADOS '.$days_to_enjoy.'<br>';
                echo 'DIAS RESTANTES '.$dias_restantes. '<br>';
                echo 'UPDATE: '. $update. '<br>';
                return;
            } */

                $data = [
                    "id_user" => session()->id_user,
                    "nombre_solicitante" => $user,
                    "id_a_cargo" => $aCargoId,
                    "a_cargo" => $aCargo,
                    "id_depto" => session()->id_depto,
                    "departamento" => $departament,
                    "puesto" => $job_position,
                    "num_nomina" => $payroll_number,
                    "fecha_ingreso" => $date_admission,
                    "tipo_empleado" => $type_of_employee,
                    'num_dias_a_disfrutar' => $days_to_enjoy,
                    "regreso" => $return_activities,
                    "dias_restantes" => $dias_restantes,
                    "fecha_registro" => $date,
                    "acuenta_vacaciones" => $acuenta
                ];
                // var_dump($data);
                $insert_vacations = $this->vacationModel->insert($data);
                $folio_vacation = $this->db->insertID();

                $arraItems = explode(",", $arrayDaysToEnjoy);
                for ($i = 0; $i < count($arraItems); $i++) {
                    $dataItems = [
                        'id_vcns' => $folio_vacation,
                        'id_user' => session()->id_user,
                        'id_depto' => session()->id_depto,
                        'date_vacation' => $arraItems[$i],
                    ];
                    // echo $i . '<br>';
                    // var_dump($dataItems);
                    $this->vacationItemsModel->insert($dataItems);
                }

                /*----------------------------------------------------------------CODIGO A CUENTA DE VACACIONES ---------------------------------------------------------- */
                if ($vacation_type == 2 || $vacation_type == 3) {

                    $type_of_leave = trim($this->request->getPost('tipo_permiso'));
                    $hourly_leave = trim($this->request->getPost('permiso_hora'));
                    $remarks = trim($this->request->getPost('observaciones'));
                    $remarks = "Permiso con Folio: " . $folio_vacation . " a cuenta de Vacaciones, " . $remarks;
                    $hourly_exit = trim($this->request->getPost('hora_salida'));

                    $hora_salida = "00:00:00";
                    $dia_salida = "0000-00-00";
                    $hora_entrada = "00:00:00";
                    $dia_entrada = "0000-00-00";
                    $inasistencia_inicio = "0000-00-00";
                    $inasistencia_final = "0000-00-00";
                    $start_days = $arrayDaysToEnjoy;

                    if ($type_of_leave == 2) {
                        $hora_salida = $hourly_leave;
                        $dia_salida = $start_days;
                    } else {
                        $hora_entrada = $hourly_leave;
                        $dia_entrada = $start_days;
                    }

                    if ($type_of_leave == 3) {
                        $hora_salida = $hourly_exit;
                        $dia_salida = $start_days;
                        $hora_entrada = $hourly_leave;
                        $dia_entrada = $start_days;
                    }
                    $data_permissions = [
                        "id_user" => session()->id_user,
                        "user" => $user,
                        "fecha_creacion" => $date,
                        "tipo_empleado" => $type_of_employee,
                        "nombre_solicitante" => $user,
                        'centro_costo' => session()->cost_center,
                        "area_operativa" => session()->area_operativa,
                        "id_depto" => session()->id_depto,
                        "departamento" => session()->departament,
                        "num_nomina" => session()->payroll_number,
                        "hora_salida" => $hora_salida,
                        "fecha_salida" => $dia_salida,
                        "hora_entrada" => $hora_entrada,
                        "fecha_entrada" => $dia_entrada,
                        "inasistencia_del" => $inasistencia_inicio,
                        "inasistencia_al" => $inasistencia_final,
                        // "tipo_permiso" => "PERSONAL",
                        "id_tipo_permiso" => 3,
                        "tipo_permiso" => "A CUENTA",
                        "goce_sueldo" => "NO",
                        "observaciones" => $remarks,
                        "acuenta_vacaciones" => $folio_vacation
                    ];
                    $insert_permissions = $this->permissionsModel->insert($data_permissions);
                }
                /*----------------------------------------------------------------FIN CODIGO A CUENTA DE VACACIONES ---------------------------------------------------------- */
                /*  $dataDirector = $this->db->query("SELECT id_director FROM tbl_assign_departments_to_managers_new WHERE payroll_number = $payroll_number")->getRow();
            $idDirector = $dataDirector->id_director;
            $dataEmail = $this->db->query("SELECT email, `name`, surname FROM tbl_users WHERE id_user = $idDirector")->getRow();
            
            $email = $dataEmail->email;
            $title = $dataEmail->name . " " . $dataEmail->surname; */
                // MANAGER
                if ($insert_permissions && $insert_vacations) {
                    $dataEmail = $this->db->query("SELECT email, `name`, surname 
                FROM tbl_users 
                WHERE id_user IN (SELECT id_manager 
                    FROM tbl_assign_departments_to_managers_new 
                    WHERE id_user = " . session()->id_user . ")")->getRow();
                    $email = $dataEmail->email;
                    $title = $dataEmail->name . " " . $dataEmail->surname;
                    $this->notificarAcuentaEmail($email, $title, $data_permissions, $folio_vacation);
                } else {

                    $dataEmail = $this->db->query("SELECT email, `name`, surname 
                FROM tbl_users 
                WHERE id_user IN (SELECT id_manager 
                    FROM tbl_assign_departments_to_managers_new 
                    WHERE id_user = " . session()->id_user . ")")->getRow();
                    $email = $dataEmail->email;
                    $title = $dataEmail->name . " " . $dataEmail->surname;
                    $this->notificarVacationsEmail($email, $title, $folio_vacation);
                }

                $this->db->query("UPDATE tbl_users SET negative_special_vacation = 0 AND id_user = $idUser");
                $result = $this->db->transComplete();
                return ($result) ? json_encode($dias_restantes) : json_encode(false);
            } else {
                redirect()->to(site_url());
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '<br> Exception:    ' . $e;
        }
    }

    public function reportsGenerate()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));
        $mes = date("m", strtotime($data->fechaInicio));
        $dia = date("d", strtotime($data->fechaInicio));
        $Anio = date("Y", strtotime($data->fechaInicio));
        $FechaFin = date("Y-m-d", strtotime($data->fechaFin . " +1 day"));

        $cont = 2;
        $spreadsheet = new Spreadsheet();
        if ($data->tipoReporte == 1) {
            $columnTitle = 'A1:V1';
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
            $sheet->getStyle("$columnTitle")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle("$columnTitle")->getBorders()->getTop()->setColor($color);
            $sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
            $NombreArchivo = "entradas_salidas_semana" . $dia . "-" . $mes . "-" . $Anio . ".xlsx";
            // $reporte = $conectar->query("SELECT * FROM entrada_salida WHERE fecha_creacion BETWEEN '" . $fechaInicio . "' and '" . $fechaFin . "' ORDER BY fecha_creacion,id_es");
            $typePermissions = ($data->permissions == "all") ? "" : "AND t1.id_tipo_permiso = '$data->permissions' ";

            $query = $this->db->query("SELECT t1.tipo_permiso, t1.id_es, t1.`user`, t1.fecha_creacion, t1.tipo_empleado, t1.nombre_solicitante,
                t1.departamento, t1.num_nomina, t1.hora_salida, t1.fecha_salida, t1.hora_entrada, t1.fecha_entrada, t1.inasistencia_del, t1.inasistencia_al,
                t1.goce_sueldo, t1.observaciones, t1.estatus,  CONCAT(b.`name`,' ',b.surname) AS authoriza, 
                (SELECT ct1.area FROM cat_operational_area AS ct1 WHERE ct1.id_area = t1.area_operativa) AS area_operativa,
                CASE
                    WHEN (t1.confirm_hora_entrada IS NOT NULL OR confirm_hora_salida IS NOT NULL) AND t1.id_tipo_permiso IN (1,2) THEN
                        'SI'
                    ELSE ''
                END AS vigilancia_confir,  
                CASE
                    WHEN t1.id_tipo_permiso = 3 THEN
                        'A CUENTA DE VACACIONES'
                    WHEN t1.id_tipo_permiso IN (4,5) THEN
                        t1.tipo_permiso
                    WHEN (t1.confirm_hora_entrada IS NOT NULL OR confirm_hora_salida IS NOT NULL) AND t1.inasistencia_del = '0000-00-00' THEN
                        TIME_FORMAT(
                            SEC_TO_TIME(
                                (t1.hora_vigilancia * 60 + t1.minutos_vigilancia) * 60
                            ),'%H:%i'
                        ) 
                    WHEN t1.confirm_hora_entrada IS NULL AND confirm_hora_salida IS NULL AND t1.inasistencia_del = '0000-00-00' THEN
                        TIME_FORMAT(
                            SEC_TO_TIME(
                                (t1.hora_permiso * 60 + t1.minuto_permiso) * 60
                            ),'%H:%i'
                        ) 
                    ELSE 'INASISTENCIA'
                END AS tiempo_solicitado,
                CASE 
	                WHEN t1.id_pago_tiempo IS NULL THEN 
		                ''
		            WHEN t1.id_pago_tiempo = 0 THEN 
		                'Deuda'
                    WHEN t2.status_autorize = 1 THEN
                        'Pendiente'
                    WHEN t2.status_autorize = 3 THEN
                        'Rechazado'
	                ELSE
                        TIME_FORMAT(
                            SEC_TO_TIME(
                                SUM(t2.hour_pay * 60 + t2.min_pay) * 60
                            ), '%H:%i'
                        )
                END AS total_tiempo,
                CASE 
	                WHEN t1.id_pago_tiempo IS NULL THEN 
                		''
		            WHEN t1.id_pago_tiempo = 0 THEN 
		                'Deuda'
                    WHEN t2.status_autorize = 1 THEN
                        'Pendiente'
                    WHEN t2.status_autorize = 3 THEN
                        'Rechazado'
	                ELSE
                        GROUP_CONCAT(
                            DISTINCT CONCAT(
                                DATE_FORMAT(t2.day_to_pay, '%d/%m/%Y')
                            ) SEPARATOR ', '
                        )
                END AS days_pay    
                FROM tbl_entry_and_exit_permits AS t1
                    LEFT JOIN (
                        SELECT st1.id_item, st1.hour_pay , st1.min_pay, st1.day_to_pay, st1.status_autorize
                        FROM tbl_entry_and_exit_permits_time_pay_items AS st1
                        WHERE st1.active_status = 1 ) 
                    AS t2 ON FIND_IN_SET( t2.id_item, t1.id_pago_tiempo )
                    LEFT JOIN tbl_users AS b ON t1.id_usuario_autoriza = b.id_user  
                WHERE t1.active_status = 1 
                    AND t1.fecha_creacion BETWEEN '$data->fechaInicio' AND '$FechaFin'
                    $typePermissions 
                GROUP BY t1.id_es
            ORDER BY t1.id_es DESC");

            $reporte = $query->getResult();
            $sheet->setTitle("Entradas_Salidas");
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(35);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);

            $sheet->setCellValue('A1', 'FOLIO');
            $sheet->setCellValue('B1', 'USUARIO');
            $sheet->setCellValue('C1', 'FECHA CREACION');
            $sheet->setCellValue('D1', 'TIPO PERMISO');
            $sheet->setCellValue('E1', 'TIPO EMPLEADO');
            $sheet->setCellValue('F1', 'NOMBRE SOLICITANTE');
            $sheet->setCellValue('G1', 'AREA OPERATIVA');
            $sheet->setCellValue('H1', 'DEPARTAMENTO');
            $sheet->setCellValue('I1', 'NUMERO NOMINA');
            $sheet->setCellValue('J1', 'HORA SALIDA');
            $sheet->setCellValue('K1', 'FECHA SALIDA');
            $sheet->setCellValue('L1', 'HORA ENTRADA');
            $sheet->setCellValue('M1', 'FECHA ENTRADA');
            $sheet->setCellValue('N1', 'INASISTENCIA DEL');
            $sheet->setCellValue('O1', 'INASISTENCIA AL');
            $sheet->setCellValue('P1', 'GOCE SUELDO');
            $sheet->setCellValue('Q1', 'OBSERVACIONES');
            $sheet->setCellValue('R1', 'ESTATUS');
            $sheet->setCellValue('S1', 'USUARIO AUTORIZADOR');
            $sheet->setCellValue('T1', 'CONFIRMACION');
            $sheet->setCellValue('U1', 'TIEMPO DE PERMISO');
            $sheet->setCellValue('V1', 'TIEMPO DE PAGO');
            $sheet->setCellValue('W1', 'DIAS DE PAGO');

            foreach ($reporte as $value) {
                $tipo_permiso = ($value->tipo_permiso == null) ? "---" : $value->tipo_permiso;
                $confirVigilancia = ($value->id_es > 16163) ? $value->vigilancia_confir : '';
                $tiempoVigilacia = ($value->id_es > 16163) ? ($value->tiempo_solicitado) : '';
                $fechasPago = ($value->id_es > 16163) ? ($value->days_pay ?? 'Pago No Autorizado') : '';
                $tiempoPago = ($value->id_es > 16163) ? ($value->total_tiempo ?? 'Pago No Autorizado') : '';

                $sheet->setCellValue('A' . $cont, $value->id_es);
                $sheet->setCellValue('B' . $cont, $value->user);
                $sheet->setCellValue('C' . $cont, $value->fecha_creacion);
                $sheet->setCellValue('D' . $cont, $tipo_permiso);
                $sheet->setCellValue('E' . $cont, strtoupper($value->tipo_empleado));
                $sheet->setCellValue('F' . $cont, $value->nombre_solicitante);
                $sheet->setCellValue('G' . $cont, $value->area_operativa ?? 'SIN REGISTRO');
                $sheet->setCellValue('H' . $cont, $value->departamento);
                $sheet->setCellValue('I' . $cont, $value->num_nomina);
                $sheet->setCellValue('J' . $cont, $value->hora_salida);
                $sheet->setCellValue('K' . $cont, $value->fecha_salida);
                $sheet->setCellValue('L' . $cont, $value->hora_entrada);
                $sheet->setCellValue('M' . $cont, $value->fecha_entrada);
                $sheet->setCellValue('N' . $cont, $value->inasistencia_del);
                $sheet->setCellValue('O' . $cont, $value->inasistencia_al);
                $sheet->setCellValue('P' . $cont, $value->goce_sueldo);
                $sheet->setCellValue('Q' . $cont, $value->observaciones);
                $sheet->setCellValue('R' . $cont, $value->estatus);
                $sheet->setCellValue('S' . $cont, $value->authoriza);
                $sheet->setCellValue('T' . $cont, $confirVigilancia);
                $sheet->setCellValue('U' . $cont, $tiempoVigilacia);
                $sheet->setCellValue('V' . $cont, $tiempoPago);
                $sheet->setCellValue('W' . $cont, $fechasPago);
                $cont++;
            }
        } else if ($data->tipoReporte == 2) {
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:R1');
            $sheet->getStyle("A1:R1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("A1:R1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A1:R1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle('A1:R1')->getBorders()->getTop()->setColor($color);
            $sheet->getStyle('A1:R1')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
            $NombreArchivo = "vacaciones_semana" . $dia . "-" . $mes . "-" . $Anio . ".xlsx";
            // $reporte = $conectar->query("SELECT * FROM vacaciones WHERE fecha_registro BETWEEN '" . $fechaInicio . "' and '" . $fechaFin . "' ORDER BY fecha_registro");
            /* $query = $this->db->query("SELECT a.*, CONCAT(b.`name`,' ',b.surname) AS authoriza  
            FROM tbl_vacations AS a 
            LEFT JOIN tbl_users AS b ON a.user_authorizes = b.id_user WHERE a.active_status = 1
                AND a.fecha_registro  BETWEEN '$data->fechaInicio' and '$FechaFin' ORDER BY a.fecha_registro"); */
            $reporte = $this->db->query("SELECT a.id_vcns, a.id_user, a.nombre_solicitante, a.tipo_empleado, a.departamento, a.num_nomina, 
            a.puesto, a.num_dias_a_disfrutar, a.regreso, a.dias_restantes, a.prima_vacacional, a.estatus,
            DATE_FORMAT(a.fecha_registro,'%d/%m/%Y') AS fecha_registro,
            DATE_FORMAT(a.fecha_ingreso,'%d/%m/%Y') AS fecha_ingreso,
            CONCAT(c.`name`, ' ', c.surname) AS authoriza,
            CASE
                WHEN a.id_vcns > 8695 THEN
                    DATE_FORMAT(MAX(b.date_vacation),'%d/%m/%Y')
                ELSE
                    DATE_FORMAT(a.dias_a_disfrutar_al,'%d/%m/%Y')
            END AS dias_a_disfrutar_al,
            CASE
                WHEN a.id_vcns > 8695 THEN
                    DATE_FORMAT(MIN(b.date_vacation),'%d/%m/%Y')
                ELSE
                    DATE_FORMAT(a.dias_a_disfrutar_del,'%d/%m/%Y')
            END AS dias_a_disfrutar_del,
            CASE
                WHEN a.id_vcns > 8695 THEN
                    GROUP_CONCAT(DISTINCT CONCAT(DATE_FORMAT(b.date_vacation,'%d/%m/%Y')) SEPARATOR ',  ')
                ELSE 
                    'REGISTRO NO EXISTENTE'
            END AS concatenado
            FROM tbl_vacations AS a
                LEFT JOIN (
                    SELECT jt1.id_vcns, jt1.date_vacation 
                    FROM tbl_vacations_items AS jt1 
                    WHERE jt1.active_status = 1
                ) AS b ON a.id_vcns = b.id_vcns
                LEFT JOIN tbl_users AS c ON a.user_authorizes = c.id_user
            WHERE a.active_status = 1
                AND a.fecha_registro  BETWEEN '$data->fechaInicio' and '$FechaFin' 
            GROUP BY a.id_vcns
            ORDER BY a.fecha_registro DESC")->getResult();

            $sheet->setTitle("Vacaciones");

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

            $sheet->setCellValue('A1', 'FOLIO VACACIONES');
            $sheet->setCellValue('B1', 'USUARIO');
            $sheet->setCellValue('C1', 'FECHA_CREACION');
            $sheet->setCellValue('D1', 'NOMBRE_SOLICITANTE');
            $sheet->setCellValue('E1', 'TIPO_EMPLEADO');
            $sheet->setCellValue('F1', 'DEPARTAMENTO');
            $sheet->setCellValue('G1', 'NUMERO_NOMINA');
            $sheet->setCellValue('H1', 'PUESTO');
            $sheet->setCellValue('I1', 'FECHA_CREACION');
            $sheet->setCellValue('J1', 'NUM_DIAS_A_DISFRUTAR');
            $sheet->setCellValue('K1', 'DIAS_A_DISFRUTAR_DEL');
            $sheet->setCellValue('L1', 'DIAS_A_ADISFRUTAR_AL');
            $sheet->setCellValue('M1', 'DIA(S)_EXACTOS');
            $sheet->setCellValue('N1', 'REGRESO');
            $sheet->setCellValue('O1', 'DIAS_RESTANTES');
            $sheet->setCellValue('P1', 'PRIMA_VACACIONAL');
            $sheet->setCellValue('Q1', 'ESTATUS');
            $sheet->setCellValue('R1', 'USUARIO AUTORIZADOR');

            foreach ($reporte as $key => $value) {
                $sheet->setCellValue('A' . $cont, $value->id_vcns);
                $sheet->setCellValue('B' . $cont, $value->id_user);
                $sheet->setCellValue('C' . $cont, $value->fecha_registro);
                $sheet->setCellValue('D' . $cont, $value->nombre_solicitante);
                $sheet->setCellValue('E' . $cont, $value->tipo_empleado);
                $sheet->setCellValue('F' . $cont, $value->departamento);
                $sheet->setCellValue('G' . $cont, $value->num_nomina);
                $sheet->setCellValue('H' . $cont, $value->puesto);
                $sheet->setCellValue('I' . $cont, $value->fecha_ingreso);
                $sheet->setCellValue('J' . $cont, $value->num_dias_a_disfrutar);
                $sheet->setCellValue('K' . $cont, $value->dias_a_disfrutar_del);
                $sheet->setCellValue('L' . $cont, $value->dias_a_disfrutar_al);
                $sheet->setCellValue('M' . $cont, $value->concatenado);
                $sheet->setCellValue('N' . $cont, $value->regreso);
                $sheet->setCellValue('O' . $cont, $value->dias_restantes);
                $sheet->setCellValue('P' . $cont, $value->prima_vacacional);
                $sheet->setCellValue('Q' . $cont, $value->estatus);
                $sheet->setCellValue('R' . $cont, $value->authoriza);
                $cont++;
            }
        } else {
            $columnTitle = 'A1:J1';
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter("$columnTitle");
            $sheet->getStyle("$columnTitle")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("$columnTitle")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle("$columnTitle")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle("$columnTitle")->getBorders()->getTop()->setColor($color);
            $sheet->getStyle("$columnTitle")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

            $NombreArchivo = "entradas_salidas_semana" . $dia . "-" . $mes . "-" . $Anio . ".xlsx";
            $reporte = $this->db->query("SELECT t1.id_item, t1.payroll_number, 
                CONCAT(t2.`name`, ' ', t2.surname) AS usuario,
                CONCAT(t3.`name`, ' ', t3.surname) AS manager,
                DATE_FORMAT(t1.day_to_pay, '%d/%m/%Y') AS day_pay,
                TIME_FORMAT(SEC_TO_TIME((t1.hour_pay * 60 + t1.min_pay) * 60),'%H:%i') AS time_pay,
                CASE
                    WHEN t2.type_of_employee = 1 THEN
                        'ADMINISTRATIVO'
                    WHEN t2.type_of_employee = 2 THEN
                        'SINDICALIZADO'
                END AS type_employe,
                CASE
                    WHEN t1.type_pay = 1 THEN
                        TIME_FORMAT(hour_in, '%H:%m')
                    WHEN t1.type_pay = 2 THEN
                        TIME_FORMAT(hour_out, '%H:%m')
                    ELSE
                        CONCAT(TIME_FORMAT(hour_in, '%H:%m'), '  ---  ', TIME_FORMAT(hour_out, '%H:%m'))
                END AS check_clock,
                CASE
                    WHEN status_autorize = 1 THEN 'PENDIENTE'
                    WHEN status_autorize = 2 THEN 'AUTORIZADO'
                    WHEN status_autorize = 3 THEN 'RACHAZADA'
                    END AS txt_autorize,
                CASE
                    WHEN available_used_debit = 1 THEN 'DISPONIBLE'
                    WHEN available_used_debit = 2 THEN 'USADO'
                    WHEN available_used_debit = 3 THEN 'DEUDA'
                END AS txt_estatus,
                CASE
                    WHEN status_autorize = 3 AND available_used_debit = 1 THEN
	                    'RACHAZADA'
                    WHEN status_autorize = 1 AND available_used_debit = 1 THEN
	                    'PENDIENTE'
                    WHEN status_autorize = 2 AND available_used_debit = 1 THEN
	                    'AUTORIZADO SIN USO'
                    WHEN status_autorize = 2 AND available_used_debit = 2 THEN
	                    'AUTORIZADO Y USADO'
                    WHEN status_autorize = 1 AND available_used_debit = 3 THEN
	                    'DEUDA'
                    WHEN status_autorize = 2 AND available_used_debit = 3 THEN
	                    'DEUDA PAGADA'
                    WHEN status_autorize = 3 AND available_used_debit = 3 THEN
	                    'DEUDA NO PAGADA'
                END AS estado
            FROM tbl_entry_and_exit_permits_time_pay_items AS t1
                LEFT JOIN tbl_users AS t2 ON t1.id_user = t2.id_user
                LEFT JOIN tbl_users AS t3 ON t3.id_user = t1.id_manager_authorize
            WHERE t1.active_status = 1
            AND t1.created_at BETWEEN '$data->fechaInicio' and '$FechaFin' 
            ORDER BY t1.id_item DESC")->getResult();

            $sheet->setTitle("Pago de Tiempo");
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(11);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

            $sheet->setCellValue('A1', 'FOLIO');
            $sheet->setCellValue('B1', 'USUARIO');
            $sheet->setCellValue('C1', 'NOMINA');
            $sheet->setCellValue('D1', 'TIPO EMPLEADO');
            $sheet->setCellValue('E1', 'DIA DE PAGO');
            $sheet->setCellValue('F1', 'TIEMPO');
            $sheet->setCellValue('G1', 'HORA CHECADA');
            $sheet->setCellValue('H1', 'ESTADO PERMISO');
            $sheet->setCellValue('I1', 'AUTORIZACION');
            $sheet->setCellValue('J1', 'RESPONSABLE');

            foreach ($reporte as $key => $value) {
                $sheet->setCellValue('A' . $cont, $value->id_item);
                $sheet->setCellValue('B' . $cont, $value->usuario);
                $sheet->setCellValue('C' . $cont, $value->payroll_number);
                $sheet->setCellValue('D' . $cont, $value->type_employe);
                $sheet->setCellValue('E' . $cont, $value->day_pay);
                $sheet->setCellValue('F' . $cont, $value->time_pay);
                $sheet->setCellValue('G' . $cont, $value->check_clock);
                $sheet->setCellValue('H' . $cont, $value->txt_estatus);
                $sheet->setCellValue('I' . $cont, $value->txt_autorize);
                $sheet->setCellValue('J' . $cont, $value->manager);
                $cont++;
            }
        }

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

    public function listdayFestive()
    {
        $result = $this->db->query("SELECT obs, motive, active_in,active_out,active_absence, requires_list,
                DATE_FORMAT(max_time,'%H:%i') AS max_time,
                TIME_FORMAT( SEC_TO_TIME(( time_permis_h * 60 + time_permis_i ) * 60 ), '%H:%i' ) AS time_permis
            FROM tbl_days_special_permiss  
            WHERE active_status = 1 
            AND enabled_status = 1 
            AND type_permiss = 1 
        AND day_permiss >= CURDATE()")->getRow();
        if ($result != null) {
            if ($result->requires_list == 1) {
                $validation = $this->db->query("SELECT true AS active 
                FROM cat_list_special_permiss WHERE active_status = 1 
                AND payroll_number = " . session()->payroll_number)->getRow()->active;
                $result = ($validation == 1) ? $result : null;
            }
        }
        return json_encode($result);
    }

    public function listdayTraffic()
    {
        $result = $this->db->query("SELECT id_day_festive AS id_retraso, DATE_FORMAT(max_time,'%H:%i') AS max_time
            FROM tbl_days_special_permiss  
            WHERE active_status = 1
            AND enabled_status = 1 
            AND type_permiss = 2
        AND day_permiss = CURDATE()")->getRow();
        return json_encode($result);
    }

    public function listdayFestiveArray()
    {
        $result = $this->db->query("SELECT DATE_FORMAT( day_permiss, '%Y-%m-%d' ) AS diasYmd,
            DATE_FORMAT( day_permiss, '%d/%m/%Y' ) AS diasdmY, obs
            FROM tbl_days_special_permiss 
            WHERE active_status = 1 
            AND enabled_status = 1 
            AND type_permiss = 1 
            AND enabled_status = 1 
        AND day_permiss >= CURDATE()")->getResult();
        return json_encode($result);
    }

    public function listdayTrafficArray()
    {
        $result = $this->db->query("SELECT DATE_FORMAT( day_permiss, '%d/%m/%Y' ) AS diasdmY, obs
            FROM tbl_days_special_permiss 
            WHERE active_status = 1 
            AND enabled_status = 1 
            AND type_permiss = 2
        AND day_permiss = CURDATE()")->getResult();
        return json_encode($result);
    }

    public function permissions_all()
    {
        $data = $this->db->query("SELECT a.id_es, a.nombre_solicitante, a.tipo_permiso, a.estatus,
                a.fecha_salida, a.hora_salida,
                a.fecha_entrada, a.hora_entrada,
                a.inasistencia_del, a.inasistencia_al,
                CONCAT( b.`name`, ' ', b.surname ) AS authoriza,
                IF(a.id_tipo_permiso IS NULL,'#BDBDBD', (SELECT st1.color FROM cat_color_type_permiss AS st1 WHERE st1.type_permiss = a.id_tipo_permiso)) AS colorPermiss
            FROM tbl_entry_and_exit_permits AS a
                LEFT JOIN tbl_users AS b ON a.id_usuario_autoriza = b.id_user 
            WHERE a.active_status = 1 
            ORDER BY a.id_es DESC 
        LIMIT 1000")->getResult();
        return json_encode($data);
    }

    public function permission_edit()
    {
        $id_folio = trim($this->request->getPost('id_folio'));
        $data  = $this->permissionsModel->where('id_es', $id_folio)->find();
        return (count($data) > 0) ? json_encode($data) : "error";
    }


    public function vacations_all()
    {
        $data = $this->db->query("SELECT a.id_vcns, DATE_FORMAT(a.fecha_registro,'%d/%m/%Y') AS fecha_registro, a.nombre_solicitante, a.num_dias_a_disfrutar, a.regreso, a.estatus, a.active_status,
                CONCAT(b.`name`,' ',b.surname) AS authoriza,
                CASE 
                    WHEN a.id_vcns > 8694 THEN 
                        MAX(c.date_vacation)
                    ELSE 
                        a.dias_a_disfrutar_al
                    END AS dias_a_disfrutar_al,
                CASE 
                    WHEN a.id_vcns > 8694 THEN 
                        MIN(c.date_vacation)
                    ELSE 
                        a.dias_a_disfrutar_del
                END AS dias_a_disfrutar_del
            FROM tbl_vacations AS a
                LEFT JOIN (SELECT st1.date_vacation, st1.id_vcns  FROM tbl_vacations_items AS st1 WHERE st1.active_status = 1 ) AS c ON a.id_vcns = c.id_vcns
                LEFT JOIN tbl_users AS b ON a.user_authorizes = b.id_user
            WHERE a.active_status = 1
            GROUP BY a.id_vcns
            ORDER BY a.id_vcns DESC
            LIMIT 1000")->getResult();
        return json_encode($data);
    }

    public function permissionsDate()
    {
        $star_date = $this->request->getPost("star_date");
        $end_date = $this->request->getPost("end_date");
        $serch = $this->request->getPost("serch");
        $option = $this->request->getPost("option");
        if ($serch == 2) {
            $where = "AND a.num_nomina = $option";
        } else if ($serch == 3) {
            $where = "AND a.area_operativa = $option";
        } else {
            $where = "";
        }
        $data = $this->db->query("SELECT CONCAT(b.`name`,' ',b.surname) AS authoriza, a.id_es , a.nombre_solicitante,
            a.tipo_permiso, a.estatus, id_tipo_permiso,
            CASE 
                WHEN a.fecha_salida = '0000-00-00' THEN
                    '---'
                ELSE
                    CONCAT(DATE_FORMAT(a.fecha_salida,'%d/%m/%Y'),'  -  ',DATE_FORMAT(a.hora_salida,'%H:%i' ))
            END AS salida,
            CASE 
                WHEN a.fecha_entrada = '0000-00-00' THEN
                    '---'
                ELSE
                    CONCAT(DATE_FORMAT(a.fecha_entrada,'%d/%m/%Y'),'  -  ',DATE_FORMAT(a.hora_entrada,'%H:%i' ))
            END AS entrada,
            CASE 
                WHEN a.inasistencia_del = '0000-00-00' THEN
                    '---'
                ELSE
                    CONCAT(DATE_FORMAT(a.inasistencia_del,'%d/%m/%Y'),'  -  ',DATE_FORMAT(a.inasistencia_al,'%d/%m/%Y'))
            END AS inasistencia,
            IF(a.id_tipo_permiso IS NULL,
                '#BDBDBD',
                ( SELECT st1.color FROM cat_color_type_permiss AS st1 WHERE st1.type_permiss = a.id_tipo_permiso )
            ) AS colorPermiss
            FROM tbl_entry_and_exit_permits AS a 
            LEFT JOIN tbl_users AS b ON a.id_usuario_autoriza = b.id_user 
            WHERE a.active_status = 1 $where 
            AND a.fecha_creacion  BETWEEN '$star_date' AND '$end_date'")->getResult();
        return json_encode($data);
    }

    public function vacationsDate()
    {
        $star_date = $this->request->getPost("star_date");
        $end_date = $this->request->getPost("end_date");
        $serch = $this->request->getPost("serch");
        $option = $this->request->getPost("option");
        if ($serch == 2) {
            $where = "AND a.num_nomina = $option";
        } else if ($serch == 3) {
            $where = "AND a.id_depto = $option";
        } else {
            $where = "";
        }

        $data = $this->db->query("SELECT a.id_vcns, a.fecha_registro AS fecha_creacion, a.nombre_solicitante, a.num_dias_a_disfrutar, a.regreso, a.estatus, a.active_status,
                CONCAT(b.`name`,' ',b.surname) AS authoriza,
                CASE 
                    WHEN a.id_vcns > 8694 THEN 
                        MAX(c.date_vacation)
                    ELSE 
                        a.dias_a_disfrutar_al
                    END AS dias_a_disfrutar_al,
                CASE 
                    WHEN a.id_vcns > 8694 THEN 
                        MIN(c.date_vacation)
                    ELSE 
                        a.dias_a_disfrutar_del
                END AS dias_a_disfrutar_del
            FROM tbl_vacations AS a
                LEFT JOIN (SELECT st1.date_vacation, st1.id_vcns  FROM tbl_vacations_items AS st1 WHERE st1.active_status = 1 ) AS c ON a.id_vcns = c.id_vcns
                LEFT JOIN tbl_users AS b ON a.user_authorizes = b.id_user
            WHERE a.active_status = 1 
                $where 
                AND fecha_registro BETWEEN '$star_date' AND '$end_date'
            GROUP BY a.id_vcns
            ORDER BY a.id_vcns DESC")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function editSave()
    {
        try {
            $id_folio = trim($this->request->getPost('editar_folio'));
            $fecha_salida = trim($this->request->getPost('editar_permiso_salida'));
            $hora_salida = trim($this->request->getPost('editar_permiso_salida_h'));
            $fecha_entrada = trim($this->request->getPost('editar_permiso_entrada'));
            $hora_entrada = trim($this->request->getPost('editar_permiso_entrada_h'));
            $editar_inasistencia_del = trim($this->request->getPost('editar_inasistencia_del'));
            $editar_inasistencia_al = trim($this->request->getPost('editar_inasistencia_al'));

            if (empty($fecha_salida) || empty($hora_salida)) {
                $fecha_salida = "0000-00-00";
                $hora_salida = "00:00:00";
            }

            if (empty($fecha_entrada) || empty($hora_entrada)) {
                $fecha_entrada = "0000-00-00";
                $hora_entrada = "00:00:00";
            }

            if (empty($editar_inasistencia_del) || empty($editar_inasistencia_al)) {
                $editar_inasistencia_del = "0000-00-00";
                $editar_inasistencia_al = "0000-00-00";
            }

            $data = [
                "fecha_salida" => $fecha_salida,
                "fecha_entrada" => $fecha_entrada,
                "hora_salida" => $hora_salida,
                "hora_entrada" => $hora_entrada,
                "inasistencia_del" => $editar_inasistencia_del,
                "inasistencia_al" => $editar_inasistencia_al
            ];

            $result  = $this->permissionsModel->update($id_folio, $data);
            return ($result) ? json_encode(true) : "error";
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function editSaveVacation()
    {
        $id_folio = trim($this->request->getPost('id_folio'));

        $regresando = trim($this->request->getPost('editar_regresando'));
        $cantidad = trim($this->request->getPost('editar_cantidad'));
        $editar_vacaciones_del = trim($this->request->getPost('editar_vacaciones_del'));
        $editar_vacaciones_al = trim($this->request->getPost('editar_vacaciones_al'));

        if (empty($regresando)) {
            $regresando = "0000-00-00";
        }

        if (empty($editar_vacaciones_del) && empty($editar_vacaciones_al)) {
            $editar_vacaciones_del = "0000-00-00";
            $editar_vacaciones_al = "0000-00-00";
        }

        $data = [
            "regreso" => $regresando,
            "dias_a_disfrutar_del" => $editar_vacaciones_del,
            "dias_a_disfrutar_al" => $editar_vacaciones_al,
            "num_dias_a_disfrutar" => intval($cantidad)
        ];

        $result  = $this->vacationModel->update($id_folio, $data);
        return ($result) ? json_encode(true) : "error";
    }

    public function myVacations()
    {
        try {
            $idUser =  session()->id_user;
            $data = $this->db->query("SELECT a.id_vcns, a.fecha_registro, a.nombre_solicitante, a.num_dias_a_disfrutar, a.regreso, a.estatus, a.active_status,
                CONCAT(b.`name`,' ',b.surname) AS authoriza,
                CASE 
                    WHEN a.id_vcns > 8694 THEN 
                        MAX(c.date_vacation)
                    ELSE 
                        a.dias_a_disfrutar_al
                END AS dias_a_disfrutar_al,
                CASE 
                    WHEN a.id_vcns > 8694 THEN 
                        MIN(c.date_vacation)
                    ELSE 
                        a.dias_a_disfrutar_del
                END AS dias_a_disfrutar_del
                FROM tbl_vacations AS a
                    LEFT JOIN (
                        SELECT st1.date_vacation, st1.id_vcns 
                        FROM tbl_vacations_items AS st1 
                        WHERE st1.active_status = 1 
                    ) AS c ON a.id_vcns = c.id_vcns
                    LEFT JOIN tbl_users AS b ON a.user_authorizes = b.id_user
                WHERE a.active_status = 1
                    AND a.id_user = $idUser
                GROUP BY a.id_vcns
                ORDER BY a.id_vcns DESC
            LIMIT 1000")->getResult();
            // $data  = $this->vacationModel->where('id_user', session()->id_user)->where('active_status', 1)->findAll();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizePermissions()
    {
        return ($this->is_logged) ? view('permissions/permissions_authorize') : redirect()->to(site_url());
    }

    public function authorizePermissionsNew()
    {
        return ($this->is_logged) ? view('permissions/permissions_authorize_new') : redirect()->to(site_url());
    }

    public function authorizePermissionsDirector()
    {
        return ($this->is_logged) ? view('permissions/permissions_authorize_director') : redirect()->to(site_url());
    }

    public function authorize_permissions()
    {
        try {
            $id_user = session()->id_user;
            $data = $this->db->query("	SELECT a.id_es, a.nombre_solicitante, a.tipo_permiso, a.estatus, a.observaciones,
                a.fecha_salida, a.hora_salida,
                a.fecha_entrada, a.hora_entrada,
                a.inasistencia_del, a.inasistencia_al,
                    IF(a.id_tipo_permiso IS NULL,
                            '#BDBDBD',
                            ( SELECT st1.color FROM cat_color_type_permiss AS st1 WHERE st1.type_permiss = a.id_tipo_permiso )
                    ) AS colorPermiss
                FROM tbl_entry_and_exit_permits AS a
                WHERE a.id_user IN (SELECT DISTINCT id_user 
                    FROM tbl_assign_departments_to_managers_new 
                    WHERE id_manager = $id_user) 
                AND a.active_status = 1 
                AND a.num_permiso_mes <> 5
                AND a.num_permiso_mes <> 4
                ORDER BY id_es DESC 
            LIMIT 1500;")->getResult();

            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorize_permissionsNew()
    {
        try {
            $id_user = session()->id_user;
            $query = $this->db->query("SELECT * FROM tbl_entry_and_exit_permits 
                WHERE id_user IN ( SELECT DISTINCT id_user 
            FROM tbl_assign_departments_to_managers_new
            WHERE id_director = $id_user AND active_status = 1) 
                AND active_status = 1 AND num_permiso_mes = 4 
            ORDER BY id_es DESC 
            LIMIT 1500");
            $data = $query->getResult();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorize_permissionsDirector()
    {
        try {
            $id_user = session()->id_user;
            $query = $this->db->query("SELECT * FROM tbl_entry_and_exit_permits WHERE active_status = 1 AND num_permiso_mes = 5 ORDER BY id_es DESC LIMIT 1500");
            $data = $query->getResult();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function shiftHours()
    {
        $typeEmploye = session()->type_of_employee;
        $idTurn = $this->request->getPost('id_turn');
        $data = $this->db->query("SELECT hour_in, hour_out, hour_in_saturday, hour_out_saturday 
            FROM cat_turns 
            WHERE id = $idTurn 
            -- AND type_of_employee = $typeEmploye 
                AND active_status = 1")->getRow();
        return json_encode($data);
    }

    public function authorization() // todos los permisos
    {
        try {
            $id = trim($this->request->getPost('id_folio'));
            $autorizacion = trim($this->request->getPost('autorizacion'));
            (int)$id_user = session()->id_user;
            $dataUser = [
                'id_usuario_autoriza' => $id_user,
                'estatus' => $autorizacion,
                'autorized_at' => date("Y-m-d H:i:s"),
            ];

            $this->db->transStart();

            $permis = $this->db->query("SELECT fecha_creacion, acuenta_vacaciones, tipo_permiso, id_pago_tiempo 
                FROM tbl_entry_and_exit_permits WHERE id_es = $id")->getRow();

            if (($autorizacion == 'Rechazada'  || $autorizacion == 'Cancelada') && $permis->tipo_permiso == 'PERSONAL') {
                $diff = (new DateTime($permis->fecha_creacion))->diff(new DateTime(date('Y-m-d')));
                if ($diff->m == 0 && $diff->y == 0) {
                    $query = $this->db->query("SELECT  id, amount_permissions FROM tbl_assign_departments_to_managers_new 
                        WHERE id_user IN (SELECT id_user FROM tbl_entry_and_exit_permits WHERE tipo_permiso = 'PERSONAL' AND id_es = $id)")->getRow();
                    if ($query != null) {
                        $amount = ($query->amount_permissions > 0) ? intval($query->amount_permissions) - 1 : 0;
                        $this->db->query("UPDATE tbl_assign_departments_to_managers_new SET amount_permissions = $amount WHERE id = $query->id");
                    }
                }
            }

            $arraysEstatusItem = ["Pendiente" => 1, "Autorizada" => 2, "Rechazada" => 3, "Cancelada" => 4];
            $estatusItems = $arraysEstatusItem[$autorizacion];

            if ($permis->acuenta_vacaciones != null) {
                $id_vacation = intval($permis->acuenta_vacaciones);
                if ($autorizacion == "Rechazada" || $autorizacion == "Cancelada") {

                    $queryVcns = $this->db->query("SELECT num_nomina, num_dias_a_disfrutar
                    FROM tbl_vacations WHERE id_vcns = $id_vacation")->getRow();

                    $payroll_number = $queryVcns->num_nomina;
                    $days = $queryVcns->num_dias_a_disfrutar;

                    $builder = $this->db->table('tbl_users');
                    $builder->set('vacation_days_total', 'vacation_days_total +' . $days, false);
                    $builder->where('payroll_number', $payroll_number);
                    $builder->update();
                }

                $this->db->query("UPDATE tbl_vacations_items SET `status` = $estatusItems WHERE id_vcns = $id_vacation");
                $dataV = [
                    'estatus' => $autorizacion,
                    "user_authorizes" => session()->id_user,
                    'autorized_at' => date("Y-m-d H:i:s"),
                ];
                $this->vacationModel->update($id_vacation, $dataV);
            }

            if ($permis->id_pago_tiempo != null && $permis->id_pago_tiempo != 0) {
                $idItems = $permis->id_pago_tiempo;
                $this->db->query("UPDATE tbl_entry_and_exit_permits_time_pay_items SET available_used_debit = 1 WHERE id_item IN ($idItems)");
            }

            $this->permissionsModel->update($id, $dataUser);
            $this->db->query("UPDATE tbl_entry_and_exit_permits_items SET estatus = $estatusItems WHERE id_es = $id");

            $result = $this->db->transComplete();

            return ($result) ? json_encode(true) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizationVacations() // todas las Vacaciones
    {
        try {
            $id = trim($this->request->getPost('id_folio'));
            $autorizacion = trim($this->request->getPost('autorizacion'));
            $days = $this->request->getPost('dias');
            $payroll_number = $this->request->getPost('num_nomina');

            $this->db->transStart();

            if ($autorizacion == "Rechazada" || $autorizacion == "Cancelada") {
                // $builder = $this->db->table('tbl_users');
                // $builder->set('vacation_days_total', 'vacation_days_total +' . $days, false);
                // $builder->where('payroll_number', $payroll_number);
                // $builder->update();

                $this->db->query("UPDATE tbl_users SET vacation_days_total = vacation_days_total + $days WHERE payroll_number = $payroll_number");
            }

            $arraysEstatusItem = ["Pendiente" => 1, "Autorizada" => 2, "Rechazada" => 3, "Cancelada" => 4];
            $estatusItems = $arraysEstatusItem[$autorizacion];

            $this->db->query("UPDATE tbl_vacations_items SET `status` = $estatusItems WHERE id_vcns = $id");

            $dataUser = [
                'estatus' => $autorizacion,
                "user_authorizes" => session()->id_user,
                'autorized_at' => date("Y-m-d H:i:s"),
            ];
            $this->vacationModel->update($id, $dataUser);

            $query = $this->db->query("SELECT id_es FROM tbl_entry_and_exit_permits WHERE acuenta_vacaciones = $id")->getRow();
            if ($query != null) {
                $dataUserPermis = [
                    'estatus' => $autorizacion,
                    'id_usuario_autoriza' => session()->id_user,
                    'autorized_at' => date("Y-m-d H:i:s"),
                ];
                $this->permissionsModel->update($query->id_es, $dataUserPermis);
            }
            //$this->db->query("UPDATE tbl_entry_and_exit_permits SET estatus = $autorizacion WHERE acuenta_vacaciones = $id;");
            $result = $this->db->transComplete();

            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {

            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorize_vacations()
    {
        try {
            $idUser = session()->id_user;
            $query = $this->db->query("SELECT a.id_vcns, DATE_FORMAT(a.fecha_registro,'%d/%m/%Y') AS fecha_creacion, a.nombre_solicitante, a.estatus,
            CASE
                WHEN a.id_vcns > 8694 THEN
                    MAX(c.date_vacation)
                ELSE
                    a.dias_a_disfrutar_al
            END AS dias_a_disfrutar_al,
            CASE
                WHEN a.id_vcns > 8694 THEN
                    MIN(c.date_vacation)
                ELSE
                    a.dias_a_disfrutar_del
            END AS dias_a_disfrutar_del
            FROM tbl_vacations AS a
                LEFT JOIN (SELECT st1.date_vacation, st1.id_vcns FROM tbl_vacations_items AS st1 WHERE st1.active_status = 1 ) AS c ON a.id_vcns = c.id_vcns
            WHERE a.active_status = 1 
            AND a.id_user IN (SELECT DISTINCT t1.id_user
                FROM tbl_assign_departments_to_managers_new AS t1
                WHERE (t1.id_manager = $idUser) AND t1.active_status = 1 )
            GROUP BY a.id_vcns
            ORDER BY a.id_vcns DESC
            LIMIT 1000
            ");
            $data = $query->getResult();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorize_vacationsNew()
    {
        try {
            $id_user = session()->id_user;
            $query = $this->db->query("SELECT * FROM tbl_vacations
            WHERE id_user IN ( SELECT DISTINCT id_user FROM tbl_assign_departments_to_managers_new
            WHERE id_director = $id_user) AND active_status = 1");
            $data = $query->getResult();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function listDaysVacation()
    {
        $idVcsn = $this->request->getPost('id_folio');
        $data = $this->db->query("SELECT date_vacation FROM tbl_vacations_items WHERE active_status = 1 AND id_vcns = $idVcsn")->getResult();
        return json_encode($data);
    }

    public function pdfSeePermissions($id_permission = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query(" SELECT a.*, CONCAT(b.`name`,' ',b.surname) AS authoriza 
        FROM tbl_entry_and_exit_permits AS a 
            LEFT JOIN tbl_users AS b ON a.id_usuario_autoriza = b.id_user
        WHERE MD5(concat('" . $key . "',id_es))='" . $id_permission . "'");
        $dataPermission =  $query->getRow();

        $dataPayTime = null;
        if ($dataPermission->id_pago_tiempo != null && $dataPermission->id_pago_tiempo != 0) {
            $dataPayTime = $this->db->query("SELECT
            CASE
                WHEN a.type_pay = 1 THEN 'Llegar Antes'
                WHEN a.type_pay = 2 THEN 'Quedarse Despues'
                WHEN a.type_pay = 3 THEN 'Turno Completo'
            END AS type_pay,
            DATE_FORMAT( a.day_to_pay, '%d/%m/%y') AS day_to_pay,
            CASE
                WHEN a.min_pay = 0 AND a.hour_pay > 0 THEN
                    CONCAT(a.hour_pay, ' Hora(s).')
                WHEN a.min_pay > 0 AND a.hour_pay = 0 THEN
                    CONCAT(a.min_pay, ' Minuto(s).')
                ELSE 
                    CONCAT(a.hour_pay, ' Horas y ', a.min_pay, ' Minuto(s).')
            END AS time_pay,
            CASE
                WHEN a.status_autorize = 3 AND a.available_used_debit = 1 THEN
                    'RACHAZADA'
                WHEN a.status_autorize = 1 AND a.available_used_debit = 1 THEN
                    'PENDIENTE'
                WHEN a.status_autorize = 2 AND a.available_used_debit = 1 THEN
                    'AUTORIZADO SIN USO'
                WHEN a.status_autorize = 2 AND a.available_used_debit = 2 THEN
                    'AUTORIZADO Y USADO'
                WHEN a.status_autorize = 1 AND a.available_used_debit = 3 THEN
                    'DEUDA'
                WHEN a.status_autorize = 2 AND a.available_used_debit = 3 THEN
                    'DEUDA PAGADA'
                WHEN a.status_autorize = 3 AND a.available_used_debit = 3 THEN
                    'DEUDA NO PAGADA'
            END AS estado,
            CONCAT(d.`name`,' ',d.surname) AS authorize,
            TIME_FORMAT(CONCAT(a.hour_pay,':',a.min_pay),'%H:%i') AS tiempo,
            e.name_turn
            FROM tbl_entry_and_exit_permits_time_pay_items AS a
                LEFT JOIN tbl_users AS d ON a.id_manager_authorize = d.id_user
                JOIN cat_turns AS e ON a.id_turn = e.id
            WHERE a.active_status = 1
            AND a.id_item IN ($dataPermission->id_pago_tiempo)")->getResult();
        }
        $data = [
            "request" => $dataPermission,
            "paytime" => $dataPayTime,
        ];
        $html2 = view('pdf/permission', $data);
        $html = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
        $html2pdf->pdf->SetTitle('Permisos');
        $html2pdf->writeHTML($html2);
        ob_end_clean();
        $html2pdf->output('permiso_' . $id_permission . '.pdf', 'I');
    }

    public function pdfVacationPermissions($id_vacation = null)
    {
        try {
            //CIFRADO
            $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
            $dataVacations = $this->db->query("SELECT a.*, CONCAT(b.`name`,' ',b.surname) AS authoriza  
        FROM tbl_vacations AS a 
            LEFT JOIN tbl_users AS b ON a.user_authorizes = b.id_user
        WHERE MD5(concat('" . $key . "',id_vcns))='" . $id_vacation . "'")->getRow();

            $query1 = $this->db->query("SELECT DATE_FORMAT(date_vacation,'%d/%m/%Y') AS date_vacation 
        FROM tbl_vacations_items WHERE active_status = 1 AND id_vcns = " . $dataVacations->id_vcns)->getResult();

            $data = ["request" => $dataVacations, 'days' => $query1];

            $html2 = view('pdf/vacations', $data);
            $html = ob_get_clean();
            $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');
            $html2pdf->pdf->SetTitle('Permiso Vacaciones');
            $html2pdf->writeHTML($html2);
            ob_end_clean();
            $html2pdf->output('permiso_' . $id_vacation . '.pdf', 'I');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }

    public function globalReport()
    {
        $NombreArchivo = "reporte_global.xlsx";
        $query = $this->db->query("SELECT  a.payroll_number, a.name, a.surname,a.second_surname, a.date_admission, a.years_worked,a.vacation_days_total,b.departament FROM
                                    tbl_users AS a
                                    INNER JOIN cat_departament AS b
                                    ON a.id_departament = b.id_depto
                                    ORDER BY payroll_number ASC");
        $reporte = $query->getResult();

        $cont = 8;
        $date = date("d/m/Y");
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A7:F7');
        $sheet->getStyle("A7:F7")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor()
            ->setRGB('FFFFFF');
        $sheet->getStyle("A7:F7")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A7:F7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
        $sheet->getStyle('A7:F7')->getBorders()->getTop()->setColor($color);
        // $sheet->getStyle('A7:F7')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle("A3:A4")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor();

        $sheet->getStyle("F3")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor();

        $sheet->setTitle("Reporte Global");

        $sheet->setCellValue('A3', 'INDUSTRIAL DE VALVULAS S.A. DE C.V.');
        $sheet->setCellValue('A4', 'HISTORICO VACACIONES');
        $sheet->setCellValue('F3', $date);
        $sheet->setCellValue('A7', 'NUMERO DE NOMINA');
        $sheet->setCellValue('B7', 'NOMBRE');
        $sheet->setCellValue('C7', 'FECHA IGRESO');
        $sheet->setCellValue('D7', 'ANTIGÜEDAD');
        $sheet->setCellValue('E7', 'DEPARTAMENTO');
        $sheet->setCellValue('F7', 'SALDO FINAL DE VACACIONES');

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);

        foreach ($reporte as $key => $value) {

            $sheet->getStyle("A" . $cont . ":F" . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $celdaA = 'A' . $cont;
            $celdaB = 'B' . $cont;
            $celdaC = 'C' . $cont;
            $celdaD = 'D' . $cont;
            $celdaE = 'E' . $cont;
            $celdaF = 'F' . $cont;

            $name = $value->name . " " . $value->surname . " " . $value->second_surname;
            $sheet->setCellValue($celdaA, $value->payroll_number);
            $sheet->setCellValue($celdaB, $name);
            $sheet->setCellValue($celdaC, $value->date_admission);
            $sheet->setCellValue($celdaD, $value->years_worked);
            $sheet->setCellValue($celdaE, $value->departament);
            $sheet->setCellValue($celdaF, $value->vacation_days_total);
            $sheet->getStyle('C' . $cont)
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $cont++;
        }

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

    public function reportVacationGlobal()
    {
        $NombreArchivo = "reporte_global_vacation.xlsx";

        $query = $this->db->query("SELECT  a.payroll_number, a.name, a.surname,a.second_surname, a.vacation_days_total,b.departament FROM
                                    tbl_users AS a
                                    INNER JOIN cat_departament AS b
                                    ON a.id_departament = b.id_depto
                                    ORDER BY payroll_number ASC");
        $reporte = $query->getResult();
        //var_dump($reporte);


        $cont = 5;
        $date = date("d/m/Y");
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A4:D4');
        $sheet->getStyle("A4:D4")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor()
            ->setRGB('FFFFFF');
        $sheet->getStyle("A4:D4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A4:D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
        $sheet->getStyle('A4:D4')->getBorders()->getTop()->setColor($color);

        $sheet->getStyle("A2:A4")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor();

        $sheet->getStyle("D2")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor();

        $sheet->setTitle("Reporte Global");


        $sheet->setCellValue('A2', 'HISTORICO VACACIONES');
        $sheet->setCellValue('D2', $date);


        $sheet->setCellValue('A4', 'NUMERO DE NOMINA');
        $sheet->setCellValue('B4', 'NOMBRE');
        $sheet->setCellValue('C4', 'DEPARTAMENTO');
        $sheet->setCellValue('D4', 'SALDO DE VACACIONES');

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(35);


        foreach ($reporte as $key => $value) {



            $sheet->getStyle("A" . $cont . ":D" . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $celdaA = 'A' . $cont;
            $celdaB = 'B' . $cont;
            $celdaC = 'C' . $cont;
            $celdaD = 'D' . $cont;


            $name = $value->name . " " . $value->surname . " " . $value->second_surname;
            $sheet->setCellValue($celdaA, $value->payroll_number);
            $sheet->setCellValue($celdaB, $name);
            $sheet->setCellValue($celdaC, $value->departament);
            $sheet->setCellValue($celdaD, $value->vacation_days_total);

            $cont++;
        }



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

    public function individualReport()
    {

        $data = json_decode(stripslashes($this->request->getPost('data')));

        $NombreArchivo = "reporte_individual.xlsx";

        $query = $this->db->query("SELECT  a.payroll_number, a.name, a.surname,a.second_surname, a.date_admission, a.years_worked,a.vacation_days_total,b.departament FROM
                                    tbl_users AS a
                                    INNER JOIN cat_departament AS b
                                    ON a.id_departament = b.id_depto
                                    WHERE
                                    id_user=$data->num_nomina");
        $reporte = $query->getResult();
        //var_dump($reporte);


        $cont = 13;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A12:E12');
        $sheet->getStyle("A12:E12")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor()
            ->setRGB('FFFFFF');
        $sheet->getStyle("A12:E12")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A12:E12')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
        $sheet->getStyle('A12:E12')->getBorders()->getTop()->setColor($color);
        // $sheet->getStyle('A7:F7')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle("A2:A3")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor();


        $sheet->setTitle("Reporte Individual");

        $sheet->setCellValue('A2', 'INDUSTRIAL DE VALVULAS S.A. DE C.V.');
        $sheet->setCellValue('A3', 'HISTORICO VACACIONES');

        $sheet->setCellValue('A5', 'Numero Colaborador');
        $sheet->setCellValue('A6', 'Nombre');
        $sheet->setCellValue('A7', 'Departamento');
        $sheet->setCellValue('A8', 'Fecha Ingreso');
        $sheet->setCellValue('A9', 'Antigüedad');

        foreach ($reporte as $key => $value) {
            $name = $value->name . " " . $value->surname . " " . $value->second_surname;
            $sheet->setCellValue('B5', $value->payroll_number);
            $sheet->setCellValue('B6', $name);
            $sheet->setCellValue('B7', $value->departament);
            $sheet->setCellValue('B8', $value->date_admission);
            $sheet->setCellValue('B9', $value->years_worked);
        }

        $sheet->setCellValue('A12', 'Ejercicio');
        $sheet->setCellValue('B12', 'Fecha días Disfrutados');
        $sheet->setCellValue('C12', 'Numero de Días');
        $sheet->setCellValue('D12', 'Dias de Aniversario');
        $sheet->setCellValue('E12', 'Saldo Final');


        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);


        foreach ($reporte as $key => $value) {



            $sheet->getStyle("A" . $cont . ":F" . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $celdaA = 'A' . $cont;
            $celdaB = 'B' . $cont;
            $celdaC = 'C' . $cont;
            $celdaD = 'D' . $cont;
            $celdaE = 'E' . $cont;
            $celdaF = 'F' . $cont;


            $sheet->setCellValue($celdaA, $value->payroll_number);
            $sheet->setCellValue($celdaB, $name);
            $sheet->setCellValue($celdaC, $value->date_admission);
            $sheet->setCellValue($celdaD, $value->years_worked);
            $sheet->setCellValue($celdaE, $value->departament);
            $sheet->setCellValue($celdaF, $value->vacation_days_total);
            $sheet->getStyle('C' . $cont)
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $cont++;
        }



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

    public function reportGeneralData()
    {
        try {
            $NombreArchivo = "reporte_datos_generales.xlsx";
            $query = $this->db->query("SELECT * FROM tbl_users_personal_data WHERE active_status = 1 ORDER BY num_nomina")->getResult();
            $cont = 3;
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A2:U2');;
            $sheet->setTitle("Informacion General");

            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(28);
            $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(15);

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(22);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(22);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(21);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(22);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(28);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(28);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(22);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(16);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(11);
            $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(15);

            //UBICACION DEL TEXTO
            $sheet->getStyle('A1:U2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet->getStyle('A1:U2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A6A6A6');
            $spreadsheet->getActiveSheet()->getStyle('I1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0');
            $spreadsheet->getActiveSheet()->getStyle('R1:U1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('7030A0');
            $spreadsheet->getActiveSheet()->getStyle('A2:U2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet->getStyle("A2:U2")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            $sheet->getStyle("A1:U1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(16)
                ->getColor()
                ->setRGB('FFFFFF');


            // TITULO DE CELDA
            $sheet->setCellValue('A1', 'INFORMACION PERSONAL')->mergeCells('A1:L1');
            $sheet->setCellValue('A2', 'NOMINA');
            $sheet->setCellValue('B2', 'NOMBRE(S)');
            $sheet->setCellValue('C2', 'APELLIDO PATERNO');
            $sheet->setCellValue('D2', 'APELLIDO MATERNO');
            $sheet->setCellValue('E2', 'CURP');
            $sheet->setCellValue('F2', 'EDAD');
            $sheet->setCellValue('G2', 'FECHA DE NACIMIENTO');
            $sheet->setCellValue('H2', 'ESCOLARIDAD');
            $sheet->setCellValue('I2', 'TITULO');
            $sheet->setCellValue('J2', 'FECHA INGRESO');
            $sheet->setCellValue('K2', 'RFC');
            $sheet->setCellValue('L2', 'ESTADO CIVIL');
            $sheet->setCellValue('M1', 'DOMICILIO')->mergeCells('M1:S1');
            $sheet->setCellValue('M2', 'CALLE');
            $sheet->setCellValue('N2', 'NO. INTERIOR');
            $sheet->setCellValue('O2', 'No. EXTERIOR');
            $sheet->setCellValue('P2', 'COLONIA');
            $sheet->setCellValue('Q2', 'MUNICIPIO');
            $sheet->setCellValue('R2', 'ESTADO');
            $sheet->setCellValue('S2', 'CP');
            $sheet->setCellValue('T1', 'CONTUGE')->mergeCells('T1:W1');
            $sheet->setCellValue('T2', 'NOMBRE');
            $sheet->setCellValue('U2', 'EDAD');
            $sheet->setCellValue('V2', 'OCUPACION');
            $sheet->setCellValue('W2', 'TELEFONO');


            foreach ($query as $key => $value) {
                $edad = ($value->edad_conyuge == 0) ? "" : $value->edad_conyuge;
                $sheet->setCellValue('A' . $cont, $value->num_nomina);
                $sheet->setCellValue('B' . $cont, $value->nombre);
                $sheet->setCellValue('C' . $cont, $value->ape_paterno);
                $sheet->setCellValue('D' . $cont, $value->ape_materno);
                $sheet->setCellValue('E' . $cont, $value->curp);
                $sheet->setCellValue('F' . $cont, $value->edad_usuario);
                $sheet->setCellValue('G' . $cont, date("d/m/Y", strtotime($value->fecha_nacimiento)));
                $sheet->setCellValue('H' . $cont, $value->escolaridad);
                $sheet->setCellValue('I' . $cont, $value->lic_ing);
                $sheet->setCellValue('J' . $cont, date("d/m/Y", strtotime($value->fecha_ingreso)));
                $sheet->setCellValue('K' . $cont, $value->rfc);
                $sheet->setCellValue('L' . $cont, $value->estado_civil);
                $sheet->setCellValue('M' . $cont, $value->calle);
                $sheet->setCellValue('N' . $cont, $value->numero_interior);
                $sheet->setCellValue('O' . $cont, $value->numero_exterior);
                $sheet->setCellValue('P' . $cont, $value->colonia);
                $sheet->setCellValue('Q' . $cont, $value->municipio);
                $sheet->setCellValue('R' . $cont, $value->estado);
                $sheet->setCellValue('S' . $cont, $value->codigo_postal);
                $sheet->setCellValue('T' . $cont, $value->nombre_conyuge);
                $sheet->setCellValue('U' . $cont, $edad);
                $sheet->setCellValue('V' . $cont, $value->ocupacion_conyuge);
                $sheet->setCellValue('W' . $cont, $value->tel_conyuge);
                $cont++;
            }
            /* --------------------------------------HOJA 2 ---------------------------- */
            $query1 = $this->db->query("SELECT * FROM tbl_users_parents WHERE active_status = 1 ORDER BY num_nomina")->getResult();
            $cont1 = 2;
            $sheet1 = $spreadsheet->createSheet(1)->setAutoFilter('A1:F1');
            $sheet1->setTitle("Padres");
            $sheet1->getRowDimension('1')->setRowHeight(15);

            $sheet1->getColumnDimension('A')->setAutoSize(true);
            $sheet1->getColumnDimension('B')->setAutoSize(true);
            $sheet1->getColumnDimension('C')->setAutoSize(true);
            $sheet1->getColumnDimension('D')->setAutoSize(true);
            $sheet1->getColumnDimension('E')->setAutoSize(true);
            $sheet1->getColumnDimension('F')->setAutoSize(true);

            $sheet1->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet1->getStyle('A1:F1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $sheet1->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet1->getStyle("A1:F1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // $sheet1->setCellValue('A1', 'INFORMACION padres')->mergeCells('A1:J1');
            $sheet1->setCellValue('A1', 'No. NOMINA');
            $sheet1->setCellValue('B1', 'NOMBRE DE FAMILIAR');
            $sheet1->setCellValue('C1', 'GENERO');
            $sheet1->setCellValue('D1', 'FECHA DE NACIMIENTO');
            $sheet1->setCellValue('E1', 'ESTADO');
            $sheet1->setCellValue('F1', 'EDAD');

            foreach ($query1 as $value) {
                $sheet1->setCellValue('A' . $cont1, $value->num_nomina);
                $sheet1->setCellValue('B' . $cont1, $value->nombre_padres);
                $sheet1->setCellValue('C' . $cont1, $value->genero_padres);
                $sheet1->setCellValue('D' . $cont1, date("d/m/Y", strtotime($value->fecha_nacimiento_padres)));
                $sheet1->setCellValue('E' . $cont1, $value->finado);
                $sheet1->setCellValue('F' . $cont1, $value->edad);
                $cont1++;
            }
            /* --------------------------------HOJA 3-------------------------------- */
            $query2 = $this->db->query("SELECT * FROM tbl_users_children WHERE active_status = 1 ORDER BY num_nomina")->getResult();
            $cont2 = 2;
            $sheet2 = $spreadsheet->createSheet(2)->setAutoFilter('A1:E1');
            $sheet2->setTitle("Hijos");
            $sheet2->getRowDimension('1')->setRowHeight(15);

            $sheet2->getColumnDimension('A')->setAutoSize(true);
            $sheet2->getColumnDimension('B')->setAutoSize(true);
            $sheet2->getColumnDimension('C')->setAutoSize(true);
            $sheet2->getColumnDimension('D')->setAutoSize(true);
            $sheet2->getColumnDimension('E')->setAutoSize(true);

            $sheet2->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet2->getStyle('A1:E1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $sheet2->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet2->getStyle("A1:E1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // $sheet2->setCellValue('A1', 'INFORMACION padres')->mergeCells('A1:J1');
            $sheet2->setCellValue('A1', 'No. NOMINA');
            $sheet2->setCellValue('B1', 'NOMBRE DE HIJO');
            $sheet2->setCellValue('C1', 'GENERO');
            $sheet2->setCellValue('D1', 'FECHA DE NACIMIENTO');
            $sheet2->setCellValue('E1', 'EDAD');

            foreach ($query2 as $key => $value) {
                $sheet2->setCellValue('A' . $cont2, $value->num_nomina);
                $sheet2->setCellValue('B' . $cont2, $value->nombre_hijo);
                $sheet2->setCellValue('C' . $cont2, $value->genero);
                $sheet2->setCellValue('D' . $cont2, date("d/m/Y", strtotime($value->fecha_nacimiento)));
                $sheet2->setCellValue('E' . $cont2, $value->edad_hijo);
                $cont2++;
            }
            /* --------------------------------HOJA 4-------------------------- */
            $query3 = $this->db->query("SELECT * FROM tbl_users_emergency_contact WHERE active_status = 1 ORDER BY num_nomina")->getResult();
            $cont3 = 2;
            $sheet3 = $spreadsheet->createSheet(3)->setAutoFilter('A1:D1');
            $sheet3->setTitle("Contactos de Emergencia");
            // $sheet3->setCellValue('A1', 'emergencia')->mergeCells('A1:D1');
            $sheet3->getRowDimension('1')->setRowHeight(15);

            $sheet3->getColumnDimension('A')->setAutoSize(true);
            $sheet3->getColumnDimension('B')->setAutoSize(true);
            $sheet3->getColumnDimension('C')->setAutoSize(true);
            $sheet3->getColumnDimension('D')->setAutoSize(true);

            $sheet3->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet3->getStyle('A1:D1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $sheet3->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet3->getStyle("A1:D1")->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            $sheet3->setCellValue('A1', 'No. NOMINA');
            $sheet3->setCellValue('B1', 'NOMBRE DEL CONTACTO');
            $sheet3->setCellValue('C1', 'TELEFONO');
            $sheet3->setCellValue('D1', 'PARENTESCO');

            foreach ($query3 as $key => $value) {
                $sheet3->setCellValue('A' . $cont3, mb_strtoupper($value->num_nomina));
                $sheet3->setCellValue('B' . $cont3, mb_strtoupper($value->contacto_emergencia));
                $sheet3->setCellValue('C' . $cont3, $value->tel_emergencia);
                $sheet3->setCellValue('D' . $cont3, mb_strtoupper($value->parentesco_emergencia));
                $cont3++;
            }

            /* $cont4 = 2;
            $sheet4 = $spreadsheet->createSheet(4)->setAutoFilter('A1:G1');
            $sheet4->setTitle("Cursos y Diplomas");
            $sheet4->getColumnDimension('A')->setWidth(100);
            $sheet4->setCellValue('A1', 'cursos')->mergeCells('A1:J1'); */


            $writer = new Xlsx($spreadsheet);
            $writer->save($NombreArchivo);
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . basename($NombreArchivo));
            header("Expires:0");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: public");
            header("Content-Length:" . filesize($NombreArchivo));
            flush();
            readfile($NombreArchivo);
            exit;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e;
        }
    }

    public function listPermissions()
    {
        try {
            $mes = trim($this->request->getPost('mes'));
            $ano = date('Y');
            $depto = trim($this->request->getPost('depto'));
            $tipo_permiso = trim($this->request->getPost('tipo_permiso'));

            switch ($tipo_permiso) {
                case 'Medicos':
                    $tipo = 'SERVICIO MEDICO';
                    break;
                case 'Personales':
                    $tipo = 'PERSONAL';
                    break;
                case 'Laborales':
                    $tipo = 'LABORAL';
                    break;

                default:
                    $tipo = "Error";
                    break;
            }
            switch ($mes) {
                case 'Ene':
                    $mes = 1;
                    break;
                case 'Feb':
                    $mes = 2;
                    break;
                case 'Mar':
                    $mes = 3;
                    break;
                case 'Abr':
                    $mes = 4;
                    break;
                case 'Mayo':
                    $mes = 5;
                    break;
                case 'Jun':
                    $mes = 6;
                    break;
                case 'Jul':
                    $mes = 7;
                    break;
                case 'Ago':
                    $mes = 8;
                    break;
                case 'Sep':
                    $mes = 9;
                    break;
                case 'Oct':
                    $mes = 10;
                    break;
                case 'Nov':
                    $mes = 11;
                    break;
                case 'Dic':
                    $mes = 12;
                    break;

                default:
                    $mes = "Error";
                    break;
            }
            $query = $this->db->query("SELECT
                                    p.fecha_creacion,
                                    p.nombre_solicitante,
                                    p.tipo_empleado,
                                    a.profile_img,
                                    j.job,
                                    COUNT(nombre_solicitante) as total
                                    FROM
                                        tbl_entry_and_exit_permits AS p
                                    INNER JOIN tbl_users as a
                                    ON p.id_user = a.id_user
                                    INNER JOIN cat_job_position as j
                                    ON a.id_job_position = j.id       
                                    WHERE p.id_depto = $depto AND  MONTH(p.fecha_creacion) = $mes AND YEAR(p.fecha_creacion) = $ano AND p.tipo_permiso = '$tipo' AND p.estatus = 'Autorizada' 
                                    AND p.acuenta_vacaciones IS NULL AND p.active_status = 1  GROUP BY nombre_solicitante");

            return json_encode($query->getResultArray());
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e;
        }
    }

    public function plotLeave()
    {

        $id_user = session()->id_user;
        $fecha_inicio = "'" . date('Y') . "-01-01'";
        $fecha_fin = "'" . date('Y') . "-12-31'";
        $area_operativa = 'id_depto ';
        switch ($id_user) {
            case 592:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'id_depto = 43';
                break;
            case 852:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'id_depto = 43';
                break;
            case 1390:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'id_depto = 43';
                break;
            case 1063:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'id_depto = 43';
                break;
            case 265:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'id_depto = 42';
                break;
            case 50:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 267:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 627:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 252:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'id_depto = 35';
                break;
            case 1:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'id_depto = 43';
                break;
            case 854:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                //< $sql = 35;
                break;

            case 339:
                $sql = 15;
                $area_operativa = 'id_depto = 15';
                break;

            case 37:
                $sql = 57;
                $area_operativa = 'id_depto = 57';
                break;
            case 151:
                $sql = 'SELECT id_depto FROM cat_departament ORDER BY id_depto = 46 DESC';
                $area_operativa = 'id_depto = 46';
                break;
            case 125:
                $sql = 'SELECT id_depto FROM cat_departament ORDER BY id_depto = 46 DESC';
                $area_operativa = 'id_depto = 46';
                break;
            case 107:
                $sql = 'SELECT id_depto FROM cat_departament ORDER BY id_depto = 46 DESC';
                $area_operativa = 'id_depto = 46';
                break;

            case 250:
                $sql = 'SELECT id_depto FROM cat_departament ORDER BY id_depto = 66 DESC';
                $area_operativa = 'id_depto = 66';
                break;


            default:
                $sql = 'SELECT id_depto FROM cat_departament WHERE id_director = ' . $id_user . ' AND active_status= 1';
                break;
        }

        $query = $this->db->query("SELECT
        departamento,
       id_depto,
        COUNT(*) AS total,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 1 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Ene_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 1 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Ene_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 1 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Ene_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 2 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Feb_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 2 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Feb_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 2 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Feb_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 3 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Mar_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 3 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Mar_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 3 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Mar_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 4 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Abr_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 4 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Abr_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 4 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Abr_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 5 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS May_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 5 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS May_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 5 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS May_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 6 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Jun_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 6 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Jun_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 6 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Jun_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 7 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Jul_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 7 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Jul_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 7 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Jul_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 8 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Ago_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 8 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Ago_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 8 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Ago_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 9 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Sep_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 9 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Sep_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 9 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Sep_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 10 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Oct_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 10 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Oct_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 10 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Oct_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 11 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Nov_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 11 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Nov_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 11 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Nov_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 12 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Dic_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 12 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Dic_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 12 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Dic_medico,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 1 THEN
                1
            ELSE
                0
            END
        ) AS Ene,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 2 THEN
                1
            ELSE
                0
            END
        ) AS Feb,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 3 THEN
                1
            ELSE
                0
            END
        ) AS Mar,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 4 THEN
                1
            ELSE
                0
            END
        ) AS Abr,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 5 THEN
                1
            ELSE
                0
            END
        ) AS May,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 6 THEN
                1
            ELSE
                0
            END
        ) AS Jun,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 7 THEN
                1
            ELSE
                0
            END
        ) AS Jul,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 8 THEN
                1
            ELSE
                0
            END
        ) AS Ago,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 9 THEN
                1
            ELSE
                0
            END
        ) AS Sep,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 10 THEN
                1
            ELSE
                0
            END
        ) AS Oct,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 11 THEN
                1
            ELSE
                0
            END
        ) AS Nov,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 12 THEN
                1
            ELSE
                0
            END
        ) AS Dic
        FROM tbl_entry_and_exit_permits
        WHERE id_depto IN ($sql)
            AND estatus = 'Autorizada'
            AND acuenta_vacaciones  IS NULL
            AND active_status = 1
            AND fecha_creacion BETWEEN $fecha_inicio
            AND $fecha_fin
        GROUP BY id_depto ORDER BY $area_operativa  DESC;");

        $permisos =  $query->getResultArray();

        // $deptos = $this->graphDeptos($id_user);



        return json_encode($permisos);
    }

    public function graphDeptos($id_user)
    {

        $query = $this->db->query("SELECT id_depto FROM cat_departament WHERE id_director = $id_user ");
        return $query->getResultArray();
    }

    /* ***** CONFIRMACION DE VIGILANCIA***** */
    public function timeOfEntry()
    {
        try {
            $date = date("Y-m-d H:i:s");
            $time = date("H:i:s");
            $folio = trim($this->request->getPost('folio'));
            $type = trim($this->request->getPost('type'));
            $query = $this->db->query("	SELECT a.id_turno, a.hora_entrada, a.hora_salida, confirm_hora_salida, id_tipo_permiso,
                CASE
                    WHEN a.hora_entrada <> '00:00:00'AND a.hora_salida <> '00:00:00' THEN
                        3
                    WHEN a.hora_entrada <> '00:00:00' THEN
                        1
                    WHEN a.hora_salida <> '00:00:00' THEN
                        2
                    ELSE
                        NULL
                    END AS tipo
                FROM tbl_entry_and_exit_permits AS a
            WHERE a.id_es = $folio")->getRow();

            $field = ($type == 1) ? 'confirm_hora_entrada' : 'confirm_hora_salida';

            if ($query->tipo != 3) {
                if ($query->id_tipo_permiso == 3) {
                    $data = [
                        $field => $date
                    ];
                } else {
                    $hour = ($query->tipo == 2) ? 'hour_out' : 'hour_in';
                    $day = (date('N') == 6) ? 'saturday' : '';
                    $fieldSQL = $hour . $day;
                    $query1 = $this->db->query("SELECT $fieldSQL AS h FROM cat_turns WHERE id = $query->id_turno")->getRow();

                    $diffTime = (new DateTime($time))->diff(new DateTime($query1->h));
                    $data = [
                        'hora_vigilancia' => $diffTime->h,
                        'minutos_vigilancia' => $diffTime->i,
                        $field => $date
                    ];
                }
            } else {
                $h = 0;
                $m = 0;
                if ($type == 1) {
                    $diffTime = (new DateTime($time))->diff(new DateTime($query->confirm_hora_salida));
                    $h = $diffTime->h;
                    $m = $diffTime->i;
                }
                $data = [
                    'hora_vigilancia' => $h,
                    'minutos_vigilancia' => $m,
                    $field => $date
                ];
            }
            $result = $this->permissionsModel->update($folio, $data);

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e;
        }
    }

    public function timeOfEntry2()
    {
        try {
            $date = $this->request->getPost('dia');
            $time = $this->request->getPost('hora');
            $folio = trim($this->request->getPost('folio'));
            $type = trim($this->request->getPost('type'));
            $query = $this->db->query("	SELECT a.id_turno, a.hora_entrada, a.hora_salida, confirm_hora_salida, id_tipo_permiso,
                CASE
                    WHEN a.hora_entrada <> '00:00:00'AND a.hora_salida <> '00:00:00' THEN
                        3
                    WHEN a.hora_entrada <> '00:00:00' THEN
                        1
                    WHEN a.hora_salida <> '00:00:00' THEN
                        2
                    ELSE
                        NULL
                    END AS tipo
                FROM tbl_entry_and_exit_permits AS a
            WHERE a.id_es = $folio")->getRow();

            $field = ($type == 1) ? 'confirm_hora_entrada' : 'confirm_hora_salida';

            if ($query->tipo != 3) {
                if ($query->id_tipo_permiso == 3) {
                    $data = [
                        $field => $date
                    ];
                } else {
                    $hour = ($query->tipo == 2) ? 'hour_out' : 'hour_in';
                    $day = (date('N') == 6) ? 'saturday' : '';
                    $fieldSQL = $hour . $day;
                    $query1 = $this->db->query("SELECT $fieldSQL AS h FROM cat_turns WHERE id = $query->id_turno")->getRow();

                    $diffTime = (new DateTime($time))->diff(new DateTime($query1->h));
                    $data = [
                        'hora_vigilancia' => $diffTime->h,
                        'minutos_vigilancia' => $diffTime->i,
                        $field => $date
                    ];
                }
            } else {
                $h = 0;
                $m = 0;
                if ($type == 1) {
                    $diffTime = (new DateTime($time))->diff(new DateTime($query->confirm_hora_salida));
                    $h = $diffTime->h;
                    $m = $diffTime->i;
                }
                $data = [
                    'hora_vigilancia' => $h,
                    'minutos_vigilancia' => $m,
                    $field => $date
                ];
            }
            $result = $this->permissionsModel->update($folio, $data);

            return ($result) ? json_encode(true) : json_encode(false);
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e;
        }
    }

    public function tablePermissions()
    {

        $id_user = session()->id_user;
        $fecha_inicio = "'" . date('Y') . "-01-01'";
        $fecha_fin = "'" . date('Y') . "-12-31'";
        $area_operativa = 'area_operativa ';
        switch ($id_user) {
            case 592:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'area_operativa = 43';
                break;
            case 852:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'area_operativa = 43';
                break;
            case 1390:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'area_operativa = 43';
                break;
            case 1063:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'area_operativa = 43';
                break;
            case 265:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'area_operativa = 42';
                break;
            case 50:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 267:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 627:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 252:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'area_operativa = 35';
                break;
            case 1:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'area_operativa = 43';
                break;
            case 854:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;

            case 339:
                $sql = 15;
                $area_operativa = 'area_operativa = 15';
                break;

            case 37:
                $sql = 57;
                $area_operativa = 'area_operativa = 57';
                break;
            case 151:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1 ORDER BY id_depto = 46 DESC';
                $area_operativa = 'area_operativa = 46';
                break;
            case 125:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1 ORDER BY id_depto = 46 DESC';
                $area_operativa = 'area_operativa = 46';
                break;
            case 107:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1 ORDER BY id_depto = 46 DESC';
                $area_operativa = 'area_operativa = 46';
                break;

            case 92:
                $sql = 'SELECT id_depto FROM cat_departament WHERE id_director = ' . $id_user . ' AND active_status= 1';
                break;
            case 2501:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1 ';
                break;


            default:
                $sql = 'SELECT id_depto FROM cat_departament WHERE id_director = ' . $id_user . ' AND active_status= 1';
                break;
        }

        $query = $this->db->query(" SELECT departamento, area_operativa, COUNT(*) AS total,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 1 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Ene_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 1 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Ene_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 1 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Ene_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 2 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Feb_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 2 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Feb_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 2 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Feb_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 3 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Mar_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 3 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Mar_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 3 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Mar_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 4 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Abr_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 4 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Abr_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 4 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Abr_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 5 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS May_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 5 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS May_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 5 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS May_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 6 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Jun_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 6 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Jun_laboral,  
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 6 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Jun_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 7 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Jul_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 7 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Jul_laboral,
    
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 7 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Jul_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 8 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Ago_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 8 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Ago_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 8 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Ago_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 9 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Sep_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 9 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Sep_laboral,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 9 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Sep_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 10 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Oct_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 10 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Oct_laboral,
    
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 10 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Oct_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 11 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Nov_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 11 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Nov_laboral,
    
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 11 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Nov_medico,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 12 AND tipo_permiso = 'PERSONAL' ) THEN
                1
            ELSE
                0
            END
        ) AS Dic_personal,
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 12 AND tipo_permiso = 'LABORAL')  THEN
                1
            ELSE
                0
            END
        ) AS Dic_laboral,
    
        SUM(
            CASE
            WHEN (MONTH (fecha_creacion) = 12 AND tipo_permiso = 'SERVICIO MEDICO' ) THEN
                1
            ELSE
                0
            END
        ) AS Dic_medico,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 1 THEN
                1
            ELSE
                0
            END
        ) AS Ene,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 2 THEN
                1
            ELSE
                0
            END
        ) AS Feb,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 3 THEN
                1
            ELSE
                0
            END
        ) AS Mar,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 4 THEN
                1
            ELSE
                0
            END
        ) AS Abr,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 5 THEN
                1
            ELSE
                0
            END
        ) AS May,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 6 THEN
                1
            ELSE
                0
            END
        ) AS Jun,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 7 THEN
                1
            ELSE
                0
            END
        ) AS Jul,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 8 THEN
                1
            ELSE
                0
            END
        ) AS Ago,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 9 THEN
                1
            ELSE
                0
            END
        ) AS Sep,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 10 THEN
                1
            ELSE
                0
            END
        ) AS Oct,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 11 THEN
                1
            ELSE
                0
            END
        ) AS Nov,
        SUM(
            CASE
            WHEN MONTH (fecha_creacion) = 12 THEN
                1
            ELSE
                0
            END
        ) AS Dic
        FROM
            tbl_entry_and_exit_permits
        WHERE
        area_operativa IN ($sql)
        AND estatus = 'Autorizada'
        AND active_status = 1
        AND fecha_creacion BETWEEN $fecha_inicio
        AND $fecha_fin
        GROUP BY area_operativa 
        ORDER BY $area_operativa  DESC;");

        $permisos =  $query->getResultArray();

        return json_encode($permisos);
    }

    public function tableVacations()
    {

        $id_user = session()->id_user;
        $fecha_inicio = date('Y') . "-01-01";
        $fecha_fin = date('Y') . "-12-31";
        $area_operativa = 'fnl.departamento ';
        switch ($id_user) {
            case 592:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'fnl.departamento = 43';
                break;
            case 852:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'fnl.departamento = 43';
                break;
            case 1390:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'fnl.departamento = 43';
                break;
            case 1063:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'fnl.departamento = 43';
                break;
            case 265:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'fnl.departamento = 42';
                break;
            case 50:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 267:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 627:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 252:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;
            case 1:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                $area_operativa = 'fnl.departamento = 43';
                break;
            case 854:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1';
                break;

            case 37:
                $sql = 57;
                $area_operativa = 'fnl.departamento = 57';
                break;
            case 151:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1 ORDER BY id_depto = 46 DESC';
                $area_operativa = 'fnl.departamento = 46';
                break;
            case 125:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1 ORDER BY id_depto = 46 DESC';
                $area_operativa = 'fnl.departamento = 46';
                break;
            case 107:
                $sql = 'SELECT id_depto FROM cat_departament WHERE active_status= 1 ORDER BY id_depto = 46 DESC';
                $area_operativa = 'fnl.departamento = 46';
                break;

            default:
                $sql = 'SELECT id_depto FROM cat_departament WHERE id_director = ' . $id_user . ' AND active_status= 1';
                break;
        }

        $query = $this->db->query("SELECT *, Ene + Feb + Mar + + Abr + May + Jun + Jul + Ago + Sep + Oct + Nov + Dic AS total FROM (
            SELECT (SELECT ct1.departament FROM cat_departament AS ct1 WHERE ct1.id_depto = a.id_depto)AS departamento, a.id_depto AS area_operativa,
                SUM( CASE
                    WHEN a.id_vcns <8695 AND MONTH (a.fecha_registro)= 1 THEN a.num_dias_a_disfrutar 
                            WHEN a.id_vcns > 8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 1
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE 
                        0
                END ) AS Ene,
                SUM( CASE
                    WHEN a.id_vcns <8695 AND MONTH (a.fecha_registro)=2 THEN a.num_dias_a_disfrutar 
                            WHEN a.id_vcns > 8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 2
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns)
                    ELSE 
                        0
                END ) AS Feb,
                SUM( CASE
                    WHEN a.id_vcns <8695 AND MONTH (a.fecha_registro)= 3 THEN a.num_dias_a_disfrutar 
                            WHEN a.id_vcns > 8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 3
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE 
                        0
                END ) AS Mar,
                SUM( CASE
                    WHEN a.id_vcns <8695 AND MONTH (a.fecha_registro)= 4 THEN a.num_dias_a_disfrutar 
                            WHEN a.id_vcns > 8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 4
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE 
                        0
                END ) AS Abr,
                SUM( CASE
                    WHEN a.id_vcns <8695 AND MONTH (a.fecha_registro)= 5 THEN a.num_dias_a_disfrutar 
                            WHEN a.id_vcns > 8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 5
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE 
                        0
                END ) AS May,
                SUM( CASE
                    WHEN a.id_vcns <8695 AND MONTH (a.fecha_registro)= 6 THEN a.num_dias_a_disfrutar 
                            WHEN a.id_vcns > 8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 6
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE 
                        0
                END ) AS Jun,
                SUM( CASE
                    WHEN a.id_vcns <8695 AND MONTH (a.fecha_registro)=7 THEN a.num_dias_a_disfrutar 
                            WHEN a.id_vcns > 8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 7
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE 
                        0
                END ) AS Jul,
                SUM( CASE
                    WHEN a.id_vcns 
                <8695 AND MONTH (a.fecha_registro)=8 THEN a.num_dias_a_disfrutar WHEN a.id_vcns>
                    8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 8
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE
                        0
                END ) AS Ago,
                SUM( CASE
                    WHEN a.id_vcns 
                    <8695 AND MONTH (a.fecha_registro)=9 THEN a.num_dias_a_disfrutar 
                            WHEN a.id_vcns > 
                        8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 9
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE
                        0
                END ) AS Sep,
                SUM( CASE
                    WHEN a.id_vcns 
                        <8695 AND MONTH (a.fecha_registro)=10 THEN a.num_dias_a_disfrutar WHEN a.id_vcns>
                            8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 10
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE
                        0
                END ) AS Oct,
                SUM( CASE
                    WHEN a.id_vcns 
                            <8695 AND MONTH (a.fecha_registro)=11 THEN a.num_dias_a_disfrutar WHEN a.id_vcns>
                                8694  THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 11
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE
                        0
                END ) AS Nov,
                SUM( CASE
                    WHEN a.id_vcns <8695 AND MONTH (a.fecha_registro)=12 THEN a.num_dias_a_disfrutar WHEN a.id_vcns>8694 THEN
                        ( SELECT COUNT(ct1.id_item)
                        FROM tbl_vacations_items AS ct1
                        WHERE MONTH (ct1.date_vacation) = 12
                        AND ct1.`status` IN (2)
                        AND ct1.id_vcns = a.id_vcns )
                    ELSE
                        0
                END ) AS Dic
                FROM tbl_vacations AS a
                WHERE a.id_depto IN ($sql)
                AND a.estatus = 'Autorizada'
                AND a.active_status = 1
                AND a.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin'
                GROUP BY a.id_depto 
            ) AS fnl
            ORDER BY $area_operativa DESC
        ;");

        $permisos =  $query->getResultArray();

        return json_encode($permisos);
    }

    public function tableDirectors()
    {

        $id_user = session()->id_user;
        $fecha_inicio = date('Y') . "-01-01";
        $fecha_fin = date('Y') . "-12-31";

        $query = $this->db->query("SELECT a.nombre_solicitante, a.departamento, a.id_depto,
            SUM(a.num_dias_a_disfrutar) AS total,
            SUM( CASE
                WHEN MONTH (a.fecha_registro) = 1 THEN
                    a.num_dias_a_disfrutar
                ELSE
                    0
            END ) AS Ene,
            SUM( CASE
                WHEN MONTH (a.fecha_registro) = 2 THEN
                    a.num_dias_a_disfrutar
                ELSE
                    0
            END ) AS Feb,
            SUM( CASE
                WHEN MONTH (a.fecha_registro) = 3 THEN
                    a.num_dias_a_disfrutar
                ELSE
                    0
            END ) AS Mar,
            SUM( CASE
                WHEN MONTH (a.fecha_registro) = 4 THEN
                a.num_dias_a_disfrutar
            ELSE
                0
            END ) AS Abr,
            SUM( CASE
                WHEN MONTH (a.fecha_registro) = 5 THEN
                    a.num_dias_a_disfrutar
                ELSE
                    0
            END ) AS May,
            SUM( CASE
                WHEN MONTH (a.fecha_registro) = 6 THEN
                    a.num_dias_a_disfrutar
                ELSE
                    0
            END ) AS Jun,
            SUM( CASE
                WHEN a.id_vcns < 8695 AND MONTH (a.fecha_registro) = 7 THEN
                    a.num_dias_a_disfrutar
                WHEN a.id_vcns > 8694 THEN
                    (SELECT COUNT(ct1.id_item)
                    FROM tbl_vacations_items AS ct1
                    WHERE MONTH (ct1.date_vacation) = 7
                    AND ct1.active_status = 1
                    AND ct1.`status` = 2
                    AND ct1.id_vcns = a.id_vcns)
                ELSE
                    0
            END ) AS Jul,
            SUM( CASE
                WHEN a.id_vcns < 8695 AND MONTH (a.fecha_registro) = 8 THEN
                    a.num_dias_a_disfrutar
                WHEN a.id_vcns > 8694 THEN
                    (SELECT COUNT(ct1.id_item)
                    FROM tbl_vacations_items AS ct1
                    WHERE MONTH (ct1.date_vacation) = 8
                    AND ct1.active_status = 1
                    AND ct1.`status` = 2
                    AND ct1.id_vcns = a.id_vcns)
                ELSE
                    0
            END ) AS Ago,
            SUM( CASE
                WHEN a.id_vcns < 8695 AND MONTH (a.fecha_registro) = 9 THEN
                    a.num_dias_a_disfrutar
                WHEN a.id_vcns > 8694 THEN
                    (SELECT COUNT(ct1.id_item)
                    FROM tbl_vacations_items AS ct1
                    WHERE MONTH (ct1.date_vacation) = 9
                    AND ct1.active_status = 1
                    AND ct1.`status` = 2
                    AND ct1.id_vcns = a.id_vcns)
                ELSE
                    0
            END ) AS Sep,
            SUM( CASE
                WHEN a.id_vcns < 8695 AND MONTH (a.fecha_registro) = 10 THEN
                    a.num_dias_a_disfrutar
                WHEN a.id_vcns > 8694 THEN
                    (SELECT COUNT(ct1.id_item)
                    FROM tbl_vacations_items AS ct1
                    WHERE MONTH (ct1.date_vacation) = 10
                    AND ct1.active_status = 1
                    AND ct1.`status` = 2
                    AND ct1.id_vcns = a.id_vcns)
                ELSE
                    0
            END ) AS Oct,
            SUM( CASE
                WHEN a.id_vcns < 8695 AND MONTH (a.fecha_registro) = 11 THEN
                    a.num_dias_a_disfrutar
                WHEN a.id_vcns > 8694 THEN
                    (SELECT COUNT(ct1.id_item)
                    FROM tbl_vacations_items AS ct1
                    WHERE MONTH (ct1.date_vacation) = 11
                    AND ct1.active_status = 1
                    AND ct1.`status` = 2
                    AND ct1.id_vcns = a.id_vcns)
                ELSE
                    0
            END ) AS Nov,
            SUM( CASE
                WHEN a.id_vcns < 8695 AND MONTH (a.fecha_registro) = 12 THEN
                    a.num_dias_a_disfrutar
                WHEN a.id_vcns > 8694 THEN
                    (SELECT COUNT(ct1.id_item)
                    FROM tbl_vacations_items AS ct1
                    WHERE MONTH (ct1.date_vacation) = 12
                    AND ct1.active_status = 1
                    AND ct1.`status` = 2
                    AND ct1.id_vcns = a.id_vcns)
                ELSE
                    0
            END ) AS Dic
            FROM tbl_vacations AS a
            WHERE a.id_user IN (
            SELECT DISTINCT wt1.id_director
            FROM cat_departament AS wt1
            WHERE wt1.id_director <> 1
            AND wt1.id_director <> 906
            AND wt1.id_director <> 265
            )
            AND a.estatus = 'Autorizada'
            AND a.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin'
            GROUP BY a.id_user
        ORDER BY a.id_user ASC");

        $permisos =  $query->getResultArray();

        return json_encode($permisos);
    }

    /* **************************************** */
    // $this->timePayModel
    public function viewTimePay()
    {
        return ($this->is_logged) ? view('permissions/generate_time_payment') : redirect()->to(site_url());
    }

    public function viewAuthorizeTimePay()
    {
        return ($this->is_logged) ? view('permissions/authorize_time_payment') : redirect()->to(site_url());
    }

    public function insertTimePayment()
    {
        try {
            $toDay = date("Y-m-d H:i:s");
            $payrollNumber = session()->payroll_number;
            $expectedDate = $this->request->getPost("fecha_prevista");
            $totalSolid = $this->request->getPost("total_solicitado");

            $this->db->transStart();

            $insertData = [
                'id_user' => session()->id_user,
                'payroll_number' => $payrollNumber,
                'id_depto' => session()->id_depto,
                'depto' => session()->departament,
                'expected_date' => $expectedDate,
                'total_required' => $totalSolid,
                'created_at' => $toDay,
            ];
            $this->timePayModel->insert($insertData);
            $idRequest = $this->db->insertID();

            $items = $this->request->getPost("items");
            $arrayIdTurno = $this->request->getPost("turno_");
            $arrayTypePermiss = $this->request->getPost("tipo_permiso_");
            $arraydayPay = $this->request->getPost("dia_salida_");
            $arrayHour = $this->request->getPost("input_horas_");
            $arrayMinutes = $this->request->getPost("input_minutos_");
            $arrayHoursInLV = $this->request->getPost("L-V_entrada_");
            $arrayHoursOutLV = $this->request->getPost("L-V_salida_");
            $arrayHoursInS = $this->request->getPost("S_entrada_");
            $arrayHoursOutS = $this->request->getPost("S_salida_");

            $idPermis = $this->request->getPost("id_permis");
            $arrayIdPayTime = [];
            $availUsedDebit = ($idPermis) ? 3 : 1;
            for ($i = 0; $i < $items; $i++) {
                $numberDay = date('N', strtotime($arraydayPay[$i]));
                $hourIn = null;
                $hourOut = null;
                if ($arrayTypePermiss[$i] == 3) {
                    $hourIn = ($numberDay == 6) ? $arrayHoursInS[$i] :  $arrayHoursInLV[$i];
                    $hourOut = ($numberDay == 6) ? $arrayHoursOutS[$i] : $arrayHoursOutLV[$i];
                } else {
                    $hoursMinus  = ($arrayHour[$i] > 0) ? '-' . $arrayHour[$i] . ' hours' : '';
                    $minutesMinus =  ($arrayMinutes[$i] > 0) ? '-' . $arrayMinutes[$i] . ' minutes' : '';
                    if ($arrayTypePermiss[$i] == 1) {
                        $horario = ($numberDay == 6) ? $arrayHoursInS[$i] :  $arrayHoursInLV[$i];
                        $hourIn = date('H:i:s', strtotime("$hoursMinus $minutesMinus", strtotime($horario)));
                    } else if ($arrayTypePermiss[$i] == 2) {
                        $horario = ($numberDay == 6) ? $arrayHoursOutS[$i] : $arrayHoursOutLV[$i];
                        $hourOut = date('H:i:s', strtotime("$hoursMinus $minutesMinus", strtotime($horario)));
                    }
                }

                $dataItems = [
                    'id_request' => $idRequest,
                    'id_user' => session()->id_user,
                    'payroll_number' => $payrollNumber,
                    'id_turn' => $arrayIdTurno[$i],
                    'type_pay' => $arrayTypePermiss[$i],
                    'day_to_pay' => $arraydayPay[$i],
                    'hour_pay' => $arrayHour[$i],
                    'min_pay' => $arrayMinutes[$i],
                    'hour_in' => $hourIn,
                    'hour_out' => $hourOut,
                    'available_used_debit' => $availUsedDebit,
                    'created_at' => $toDay,
                ];
                $this->timePayItemsModel->insert($dataItems);
                array_push($arrayIdPayTime, $this->db->insertID());
            }
            $idPayTime = implode(",", $arrayIdPayTime);
            if ($idPermis) {
                $data_permissions = ['id_pago_tiempo' => $idPayTime,];
                $this->permissionsModel->update($idPermis, $data_permissions);
            }
            //  email a manager

            $dataEmail = $this->db->query("SELECT email, name, surname 
            FROM tbl_users 
            WHERE id_user IN 
                (SELECT id_manager 
                FROM tbl_assign_departments_to_managers_new 
                WHERE id_user = " . session()->id_user . ")")->getRow();
            $email = $dataEmail->email;
            $title = $dataEmail->name . " " . $dataEmail->surname;
            $result = $this->db->transComplete();
            $this->notificarPayTimeEmail($email, $title, $idPayTime);
            return json_encode($result);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function editTimePayment()
    {
        try {
            $toDay = date("Y-m-d H:i:s");
            $this->db->transStart();

            $idItem = $this->request->getPost("id_item");
            $IdTurno = $this->request->getPost("turno");
            $TypePermiss = $this->request->getPost("tipo_permiso");
            $dayPay = $this->request->getPost("dia_salida");
            $Hour = $this->request->getPost("input_horas");
            $Minutes = $this->request->getPost("input_minutos");
            $HoursInLV = $this->request->getPost("L-V_entrada");
            $HoursOutLV = $this->request->getPost("L-V_salida");
            $HoursInS = $this->request->getPost("S_entrada");
            $HoursOutS = $this->request->getPost("S_salida");

            $numberDay = date('N', strtotime($dayPay));
            $hourIn = null;
            $hourOut = null;
            if ($TypePermiss == 3) {
                $hourIn = ($numberDay == 6) ? $HoursInS :  $HoursInLV;
                $hourOut = ($numberDay == 6) ? $HoursOutS : $HoursOutLV;
            } else {
                $hoursMinus  = ($Hour > 0) ? '-' . $Hour . ' hours' : '';
                $minutesMinus =  ($Minutes > 0) ? '-' . $Minutes . ' minutes' : '';
                if ($TypePermiss == 1) {
                    $horario = ($numberDay == 6) ? $HoursInS :  $HoursInLV;
                    $hourIn = date('H:i:s', strtotime("$hoursMinus $minutesMinus", strtotime($horario)));
                } else if ($TypePermiss == 2) {
                    $horario = ($numberDay == 6) ? $HoursOutS : $HoursOutLV;
                    $hourOut = date('H:i:s', strtotime("$hoursMinus $minutesMinus", strtotime($horario)));
                }
            }

            $dataItems = [
                'id_turn' => $IdTurno,
                'type_pay' => $TypePermiss,
                'day_to_pay' => $dayPay,
                'hour_pay' => $Hour,
                'min_pay' => $Minutes,
                'hour_in' => $hourIn,
                'hour_out' => $hourOut,
                'id_update' => session()->id_user,
                'update_at' => $toDay,

            ];
            $this->timePayItemsModel->update($idItem, $dataItems);
            $result = $this->db->transComplete();
            return json_encode($result);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function dataTimePayment()
    {
        $idUser = session()->id_user;
        $data = $this->db->query("SELECT a.id_item, a.id_request, a.status_autorize, c.departament AS depto,
            CONCAT(b.`name`, ' ', b.surname, ' ', b.second_surname ) AS nombre,
            DATE_FORMAT(a.day_to_pay, '%d/%m/%Y') AS day_to_pay,
            CASE
                WHEN a.min_pay = 0 AND a.hour_pay > 0 THEN
                    CONCAT(a.hour_pay, ' Hora(s).')
                WHEN a.min_pay > 0 AND a.hour_pay = 0 THEN
                    CONCAT(a.min_pay, ' Minuto(s).')
                ELSE 
                    CONCAT(a.hour_pay, ' Horas y ', a.min_pay, ' Minuto(s).')
            END AS time_pay,
            CASE
                WHEN a.status_autorize <> 2 AND a.available_used_debit <> 3 THEN 
                    4
                ELSE 
                    a.available_used_debit
            END AS estado
        FROM tbl_entry_and_exit_permits_time_pay_items AS a
            LEFT JOIN tbl_users AS b ON a.id_user = b.id_user
            JOIN cat_departament AS c ON b.id_departament = c.id_depto
        WHERE a.active_status = 1
            AND a.id_user IN (
                SELECT id_user
                FROM tbl_assign_departments_to_managers_new
                WHERE active_status = 1
            AND id_manager = $idUser )")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function myTimePayment()
    {
        $payrollNumber = session()->payroll_number;
        $data = $this->db->query("SELECT a.id_item, a.id_request, a.status_autorize, c.departament AS depto,
            CONCAT(b.`name`, ' ', b.surname, ' ', b.second_surname ) AS nombre,
            DATE_FORMAT(a.day_to_pay, '%d/%m/%Y') AS day_to_pay,
            CASE
                WHEN a.min_pay = 0 AND a.hour_pay > 0 THEN
                    CONCAT(a.hour_pay, ' Hora(s).')
                WHEN a.min_pay > 0 AND a.hour_pay = 0 THEN
                    CONCAT(a.min_pay, ' Minuto(s).')
                ELSE 
                    CONCAT(a.hour_pay, ' Horas y ', a.min_pay, ' Minuto(s).')
            END AS time_pay,
            CASE
                WHEN a.status_autorize <> 2 AND a.available_used_debit <> 3 THEN 
                    4
                ELSE 
                    a.available_used_debit
            END AS estado
        FROM tbl_entry_and_exit_permits_time_pay_items AS a
            LEFT JOIN tbl_users AS b ON a.id_user = b.id_user
            JOIN cat_departament AS c ON b.id_departament = c.id_depto
        WHERE a.active_status = 1
            AND a.payroll_number = $payrollNumber")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function dataTimePaymentALL()
    {
        $data = $this->db->query("SELECT a.id_item, a.id_request, a.status_autorize, c.departament AS depto, a.type_pay,
                CONCAT(b.`name`, ' ', b.surname, ' ', b.second_surname ) AS nombre,
                DATE_FORMAT( a.day_to_pay, '%d/%m/%y') AS day_to_pay,
                TIME_FORMAT(a.hour_out, '%H:%i') AS hour_out,
                CASE
                    WHEN a.min_pay = 0 AND a.hour_pay > 0 THEN
                        CONCAT(a.hour_pay, ' Hora(s).')
                    WHEN a.min_pay > 0 AND a.hour_pay = 0 THEN
                        CONCAT(a.min_pay, ' Minuto(s).')
                    ELSE 
                        CONCAT(a.hour_pay, ' Horas y ', a.min_pay, ' Minuto(s).')
                END AS time_pay,
                CASE
                    WHEN a.status_autorize <> 2 AND a.available_used_debit <> 3 THEN 
                        4
                    ELSE 
                        a.available_used_debit
                END AS estado,
                CASE
                    WHEN a.type_pay = 1 THEN 
                        TIME_FORMAT(a.hour_in, '%H:%i')
                    WHEN a.type_pay = 2 THEN 
                        TIME_FORMAT(a.hour_out, '%H:%i')
                    ELSE 
                        CONCAT(TIME_FORMAT(a.hour_in, '%H:%i'),' --- ',TIME_FORMAT(a.hour_out, '%H:%i'))
                END AS check_clock,
                CONCAT(d.`name`,' ',d.surname) AS authorize
            FROM tbl_entry_and_exit_permits_time_pay_items AS a
                LEFT JOIN tbl_users AS b ON a.id_user = b.id_user
                JOIN cat_departament AS c ON b.id_departament = c.id_depto
                LEFT JOIN tbl_users AS d ON a.id_manager_authorize = d.id_user
            WHERE a.active_status = 1")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
    }

    public function updateStatusTimePayment()
    {
        try {
            $toDay = date("Y-m-d H:i:s");
            $statusAutorize = $this->request->getPost('status_autorize');
            $idItem = $this->request->getPost('id_item');
            $idRequest = $this->request->getPost('id_contract');

            $this->db->transStart();
            $updateItemData = [
                'status_autorize' => $statusAutorize,
                'id_manager_authorize' => session()->id_user,
                'manager_authorize_date' => $toDay,
            ];
            $this->timePayItemsModel->update($idItem, $updateItemData);

            $query = $this->db->query("SELECT hour_pay, min_pay 
            FROM tbl_entry_and_exit_permits_time_pay_items
            WHERE active_status = 1 AND id_request = $idRequest 
            AND status_autorize = 2 ORDER BY id_item ASC")->getResult();

            $hoursArray = [];
            $minutesArray = [];

            foreach ($query as $key) {
                array_push($hoursArray, $key->hour_pay);
                array_push($minutesArray, $key->min_pay);
            }

            $totalMinutes = array_sum($minutesArray);
            $totalHours = array_sum($hoursArray) + floor($totalMinutes / 60);
            $totalMinutes %= 60;

            $totalTime = sprintf("%02d:%02d", $totalHours, $totalMinutes);

            $updateData = ['total_pay' => $totalTime,];
            $this->timePayModel->update($idRequest, $updateData);

            $result = $this->db->transComplete();
            return json_encode($result);
        } catch (\Exception $th) {
            return json_encode($th);
        }
    }

    public function listOfTurns()
    {
        $data = $this->db->query("SELECT id, name_turn 
        FROM cat_turns 
        WHERE active_status = 1 
        AND type_of_employee = 2")->getResult();
        return json_encode($data);
    }

    public function listOfTimePay()
    {
        $dateLimit = date('Y-m-d', strtotime("-16 Days", strtotime(date('Y-m-d'))));
        $idUser = session()->id_user;

        $data = $this->db->query("SELECT id_item, 
            CASE
                WHEN min_pay > 9 THEN
                    CONCAT(hour_pay, ':', min_pay)
                ELSE
                    CONCAT(hour_pay, ':0', min_pay)
            END AS time_pay,
            DATE_FORMAT(day_to_pay, '%d/%m/%Y') AS day_to_pay
            FROM tbl_entry_and_exit_permits_time_pay_items
            WHERE active_status = 1
                AND available_used_debit = 1
                AND status_autorize = 2
                AND day_to_pay > '$dateLimit'
                AND id_user = $idUser        
        ORDER BY created_at DESC")->getResult();
        return json_encode($data);
    }

    public function validatePaymentTime()
    {
        $payrollNumber = session()->payroll_number;
        $data = $this->db->query("SELECT CASE 
                WHEN id_pago_tiempo = 0 THEN 'noCreatePayTime'
                ELSE id_pago_tiempo
            END AS resp
            FROM tbl_entry_and_exit_permits
            WHERE active_status = 1
                AND pago_deuda = 2 
                AND (estatus = 'Autorizada' OR estatus = 'Pendiente')
                AND num_nomina = $payrollNumber
        ORDER BY fecha_creacion DESC")->getRow();
        if ($data == null) {
            return json_encode(false);
        } else if ($data->resp == 'noCreatePayTime') {
            return json_encode('noCreatePayTime');
        }

        $time = $this->db->query("SELECT hour_pay, min_pay 
        FROM tbl_entry_and_exit_permits_time_pay_items
        WHERE active_status = 1 
            AND status_autorize = 1
            AND id_item IN ($data->resp)")->getResult();

        if ($time != null) {
            $hoursArray = [];
            $minutesArray = [];

            foreach ($time as $key) {
                array_push($hoursArray, $key->hour_pay);
                array_push($minutesArray, $key->min_pay);
            }

            $totalMinutes = array_sum($minutesArray);
            $totalHours = array_sum($hoursArray) + floor($totalMinutes / 60);
            $totalMinutes %= 60;

            $totalTime = sprintf("%02d:%02d", $totalHours, $totalMinutes);
            return json_encode($totalTime);
        }
        return json_encode(false);
    }

    public function validateDebtTime()
    {
        $query = $this->db->query("SELECT id_es, fecha_creacion, 
            CASE 
                WHEN confirm_hora_entrada IS NOT NULL THEN 
                    TIME_FORMAT(CONCAT(hora_vigilancia,':',minutos_vigilancia), '%H:%i') 
                ELSE
                    TIME_FORMAT(CONCAT(hora_permiso,':',minuto_permiso), '%H:%i')
            END AS tiempo_dueda
        FROM tbl_entry_and_exit_permits 
        WHERE active_status = 1
            AND id_pago_tiempo = '0'
            AND (estatus = 'Autorizada' OR estatus = 'Pendiente')
            AND num_nomina = " . session()->payroll_number)->getRow();
        return json_encode($query);
    }

    public function notificarPayTimeEmail($email, $title, $arrayId)
    {
        $longitud = count(explode(",", $arrayId));
        $query = $this->db->query("SELECT a.id_item, type_pay,
            DATE_FORMAT(day_to_pay, '%d/%m/%Y') AS day_to_pay,
            CONCAT(b.`name`,' ',b.surname) AS usuario,    
            CASE
                WHEN a.min_pay = 0 AND a.hour_pay > 0 THEN
                    CONCAT(a.hour_pay, ' Hora(s).')
                WHEN a.min_pay > 0 AND a.hour_pay = 0 THEN
                    CONCAT(a.min_pay, ' Minuto(s).')
                ELSE 
                    CONCAT(a.hour_pay, ' Horas y ', a.min_pay, ' Minuto(s).')
            END AS time_pay
            FROM tbl_entry_and_exit_permits_time_pay_items AS a
                JOIN tbl_users AS b ON a.id_user = b.id_user
            WHERE a.id_item IN ($arrayId)")->getResult();

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
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
            //Server settings
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            // $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('notificacion@walworth.com', 'Sistema de Pago de Tiempo');
            // Add a recipient
            //$mail->addAddress($dir_email, $title);
            $mail->addAddress($dir_email, $title);
            // Name is optional
            //$mail->addAddress('another_email@example.com');
            $mail->addReplyTo('hrivas@walworth.com.mx', 'Informacion del Sistema');
            //$mail->addCC('cc@example.com');

            $mail->addBCC('rcruz@walworth.com.mx');
            // $mail->addBCC('hrivas@walworth.com.mx');

            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $datas = ['notify' => $query, 'count' => $longitud];
            $email_template = view('notificaciones/notify_pay_time', $datas);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Pago de Tiempo';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function deletePaymentTime()
    {
        $idItem = $this->request->getPost('id_item');
        $deletData = [
            'id_delete' => session()->id_user,
            'delete_at' => date("Y-m-d H:i:s"),
            'active_status' => 2,
        ];
        $result = $this->timePayItemsModel->update($idItem, $deletData);
        return json_encode($result);
    }

    public function dataItemPaymentTime()
    {
        $idItem = $this->request->getPost('id_item');
        $data = $this->db->query("SELECT id_item, id_turn, type_pay, day_to_pay, hour_pay, min_pay
        FROM tbl_entry_and_exit_permits_time_pay_items 
        WHERE id_item = $idItem")->getRow();
        return json_encode($data);
    }

    public function permisssionTotal()
    {

        $date = date('Y');

        $query = $this->db->query("SELECT
    COUNT(*) AS total,
    
    -- Enero
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 1 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Ene,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 1 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Ene,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 1 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 1 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Ene,
    
    -- Febrero
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 2 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Feb,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 2 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Feb,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 2 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 2 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Feb,
    
    
    -- Marzo
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 3 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Mar,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 3 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Mar,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 3 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 3 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Mar,
    
    
    -- Abril
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 4 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Abr,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 4 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Abr,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 4 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 4 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Abr,
    
    
    -- Mayo
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 5 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_May,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 5 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_May,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 5 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 5 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_May,
    
    
    -- Junio
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 6 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Jun,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 6 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Jun,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 6 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 6 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Jun,
    
    -- Julio
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 7 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Jul,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 7 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Jul,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 7 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 7 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Jul,
    
    -- Agosto
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 8 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Ago,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 8 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Ago,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 8 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 8 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Ago,
    
    -- Septiembre
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 9 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Sep,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 9 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Sep,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 9 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 9 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Sep,
    
    -- Octubre
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 10 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Oct,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 10 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Oct,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 10 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 10 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Oct,
    
    -- Noviembre
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 11 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Nov,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 11 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Nov,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 11 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 11 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Nov,
    
    -- Diciembre
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 12 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END) AS Por_atender_Dic,
    SUM(CASE WHEN MONTH(Ticket_FechaCreacion) = 12 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END) AS Atendidos_Dic,
		SUM(
        CASE WHEN MONTH(Ticket_FechaCreacion) = 12 AND (Ticket_EstatusId = 1 OR Ticket_EstatusId = 2) THEN 1 ELSE 0 END +
        CASE WHEN MONTH(Ticket_FechaCreacion) = 12 AND (Ticket_EstatusId = 3 OR Ticket_EstatusId = 5) THEN 1 ELSE 0 END
    ) AS Total_Dic
    
FROM
    tbl_tickets_request
WHERE
    YEAR(Ticket_FechaCreacion) = $date")->getResult();

        return ($query != "") ? json_encode($query) : json_encode(false);
    }
}
