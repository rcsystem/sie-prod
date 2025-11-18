<?php

/**
 * GENERADOR DE REPORTE ENTRADAS Y SALIDAS & VACACIONES
 * @version 1.1 pre-prod
 * @author Rafael Cruz Aguilar <rafael.cruz.aguilar1@gmail.com>
 * @telefono 55-65-42-96-49
 * Archivo Generador de Repore
 */

namespace App\Controllers\Api\Permisos;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


use App\Models\userModel;
use CodeIgniter\I18n\Time;
use Spipu\Html2Pdf\Html2Pdf;
use App\Models\vacationsModel;
use App\Models\deptoModel;
use App\Models\personalDataModel;
use App\Models\usersChildrenModel;
use App\Models\permissionsModel;
//use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Permisos extends ResourceController
{
    use ResponseTrait;
    // get all product
  

    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->model = new userModel();
        $this->vacationModel = new vacationsModel();
        $this->deptoModel = new deptoModel();
        $this->personalDataModel = new personalDataModel();
        $this->childrenModel = new usersChildrenModel();
        $this->permissionsModel = new permissionsModel();
        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function index()
    {
        $deptoData = $this->deptoModel->where('active_status', 1)->findAll();
        return $this->respond($deptoData, 200);
    }


     // create a product
     public function create()
     {
        
         $data = [
             'departament' => $this->request->getPost('departament'),
             'area' => $this->request->getPost('area'),
             'cost_center' => $this->request->getPost('cost_center')
         ];
         $this->deptoModel->insert($data);
         $response = [
             'status'   => 201,
             'error'    => null,
             'messages' => [
                 'success' => 'Data Saved'
             ]
         ];
          
         return $this->respondCreated($response, 201);
     }

   
    public function generate()
    {
        $hora_salida = trim($this->request->getPost('permiso_autoriza_salida'));
        $dia_salida = trim($this->request->getPost('permiso_dia_salida'));
        $tipo_empleado = trim($this->request->getPost('permiso_tipo_empleado'));
        $hora_entrada = trim($this->request->getPost('permiso_autoriza_entrada'));
        $dia_entradas = trim($this->request->getPost('permiso_dia_entrada'));
        $fecha_actual = new Time("now");
        $dia_entrada = date("Y-m-d", strtotime($dia_entradas));

        /*  if($dia_entrada == $fecha_actual){
            return  json_encode('error');
        } */

        if ($dia_entrada == "1969-12-31") {
            $dia_entrada = "0000-00-00";
        }


        $inasistencia_inicio = trim($this->request->getPost('permiso_inasistencia'));
        $inasistencia_final = trim($this->request->getPost('permiso_dia_inasistencia'));

        $observaciones = trim($this->request->getPost('permiso_observaciones'));
        $goce_sueldo = trim($this->request->getPost('goce_sueldo'));
        $user = session()->name . " " . session()->surname;

        $data_permissions = [
            "id_user" => session()->id_user,
            "user" => $user,
            "fecha_creacion" => $fecha_actual,
            "tipo_empleado" => $tipo_empleado,
            "nombre_solicitante" => $user,
            'centro_costo' => session()->cost_center,
            "area_operativa" => session()->id_depto,
            "departamento" => session()->departament,
            "num_nomina" => session()->payroll_number,
            "hora_salida" => $hora_salida,
            "fecha_salida" => $dia_salida,
            "hora_entrada" => $hora_entrada,
            "fecha_entrada" => $dia_entrada,
            "inasistencia_del" => $inasistencia_inicio,
            "inasistencia_al" => $inasistencia_final,
            "goce_sueldo" => $goce_sueldo,
            "observaciones" => $observaciones
        ];
        $number = session()->payroll_number;
        $query = $this->db->query("SELECT DISTINCT id_manager
                                    FROM
                                        tbl_assign_departments_to_managers_copy
                                    WHERE
                                        payroll_number = $number");
        $idUser = $query->getResultArray();
        foreach ($idUser as $key => $value) {
            $builder = $this->db->table('tbl_users');
            $builder->select('email,name,surname');
            $builder->where('id_user', $value["id_manager"]);
            $builder->limit(1);
            $email = $builder->get()->getResultArray();

            foreach ($email as $key => $value1) {

                $user_name = $value1["name"] . " " . $value1["surname"];

                $this->notificarEmail($value1["email"], $user_name, $data_permissions);
            }
        }

        return ($this->permissionsModel->insert($data_permissions)) ? json_encode(true) : json_encode('error');
    }

    public function departamentsAll()
    {
        $deptoData = $this->deptoModel->where('active_status', 1)->findAll();

        if (count($deptoData) > 0) :
            echo  json_encode($deptoData);
        else :
            echo json_encode("error");
        endif;
    }

    public function personalData()
    {
        /*********************PRIMERA PARTE DEL FOMULARIO DATOS DEL USUARIO ***************************/
        $num_nomina = trim($this->request->getPost('num_nomina'));
        $nombre_usuario = trim($this->request->getPost('nombre_usuario'));
        $ape_paterno = trim($this->request->getPost('ape_paterno'));
        $ape_materno = trim($this->request->getPost('ape_materno'));
        $genero = trim($this->request->getPost('genero'));
        $edad_usuario = trim($this->request->getPost('edad_usuario'));
        $fecha_nacimiento = trim($this->request->getPost('fecha_nacimiento'));
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



        /*********************SEXTA PARTE DEL FOMULARIO DATOS HIJOS ***************************/
        $escolaridad = trim($this->request->getPost('escolaridad'));
        $diplomados = trim($this->request->getPost('diplomados'));
        $cursos_externos = trim($this->request->getPost('cursos_externos'));
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
            "diplomados" => $diplomados,
            "lic_ing" => $lic_ing,
            "cursos_externos" => $cursos_externos,
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



                if (!empty($padres_edad[$k])) {
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
                } else {
                    $dataItems = [
                        'id_datos' => $id_datos,
                        'num_nomina' => $num_nomina,
                        "nombre_padres" => strtoupper($padres[$k]),
                        "fecha_nacimiento_padres" => strtoupper($padres_fecha[$k]),
                        "genero_padres" => strtoupper($padres_genero[$k]),
                        "finado" => strtoupper($estatus_padres[$k]),
                        "edad" =>  0,
                        'created_at' => $date
                    ];
                }


                $builder->insert($dataItems);
            }
        }



        return ($insertData) ? json_encode(true) : json_encode(false);
    }

    public function notificarEmail($dir_email, $title, $data)
    {

        if ($dir_email == "krubio@grupowalworth.com") {
            $dir_email = "krubio@walworth.com.mx";
        } elseif ($dir_email == "arodriguez@grupowalworth.com") {
            $dir_email = "arodriguez@walworth.com.mx";
        } elseif ($dir_email == "rgalvez@grupowalworth.com") {
            $dir_email = "rgalvez@walworth.com.mx";
        } elseif ($dir_email == "mflores@grupowalworth.com") {
            $dir_email = "mflores@walworth.com.mx";
        } elseif ($dir_email == "ibarreto@grupowalworth.com") {
            $dir_email = "ibarreto@walworth.com.mx";
        } elseif ($dir_email == "jwaisburd@grupowalworth.com") {
            $dir_email = "jwaisburd@walworth.com.mx";
        }

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
            $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('notificacion@grupowalworth.com', 'Sistema de Permisos');
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
            $email_template = view('notificaciones/permisos', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Permisos y Vacaciones';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function notificarVacationsEmail($dir_email, $title, $data)
    {

        if ($dir_email == "krubio@grupowalworth.com") {
            $dir_email = "krubio@walworth.com.mx";
        } elseif ($dir_email == "arodriguez@grupowalworth.com") {
            $dir_email = "arodriguez@walworth.com.mx";
        } elseif ($dir_email == "rgalvez@grupowalworth.com") {
            $dir_email = "rgalvez@walworth.com.mx";
        } elseif ($dir_email == "mflores@grupowalworth.com") {
            $dir_email = "mflores@walworth.com.mx";
        } elseif ($dir_email == "ibarreto@grupowalworth.com") {
            $dir_email = "ibarreto@walworth.com.mx";
        } elseif ($dir_email == "jwaisburd@grupowalworth.com") {
            $dir_email = "jwaisburd@walworth.com.mx";
        }

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
            $mail->Username = 'requisiciones@walworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            $mail->Password = 'Walworth321$';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;
            //Recipients
            $mail->setFrom('notificacion@grupowalworth.com', 'Sistema de Vacaciones');
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
            $email_template = view('notificaciones/permisos_vacaciones', $data);
            $mail->MsgHTML($email_template);
            $mail->Subject =  'Notificación de Permisos y Vacaciones';
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function generateReports()
    {


        $data = json_decode(stripslashes($this->request->getPost('data')));


        /*  $mes = date("m", strtotime($fechaInicio));

        $dia = date("d", strtotime($fechaInicio));

        $Anio = date("Y", strtotime($fechaInicio)); */

        //echo $FechaObj   = DateTime::createFromFormat('!m', $mes);

        //echo $NombreMes = $FechaObj->format('F'); // March

        if ($data->tipo_reportes == 1) {
            $sql = ($data->categoria == 1) ? 'area_operativa=' . $data->parametro : 'num_nomina=' . $data->parametro;
            $NombreArchivo = "permisos.xlsx";
            // $reporte = $conectar->query("SELECT * FROM entrada_salida WHERE fecha_creacion BETWEEN '" . $fechaInicio . "' and '" . $fechaFin . "' ORDER BY fecha_creacion,id_es");

            $query = $this->db->query("SELECT
                                            *
                                        FROM
                                            tbl_entry_and_exit_permits
                                        WHERE
                                            fecha_salida BETWEEN '" . $data->fecha_inicio . "'
                                        AND '" . $data->fecha_fin .  "' AND " . $sql . "
                                        UNION
                                            SELECT
                                                *
                                            FROM
                                                tbl_entry_and_exit_permits
                                            WHERE
                                                fecha_entrada BETWEEN '" . $data->fecha_inicio . "'
                                            AND '" . $data->fecha_fin .  "' AND " . $sql . "
                                            ORDER BY
                                                fecha_creacion,
                                                id_es");
            $reporte = $query->getResult();
        } else {
            $NombreArchivo = "vacaciones.xlsx";
            $sql = ($data->categoria == 1) ? "id_depto=" . $data->parametro : "num_nomina= " . $data->parametro;
            // $reporte = $conectar->query("SELECT * FROM vacaciones WHERE fecha_registro BETWEEN '" . $fechaInicio . "' and '" . $fechaFin . "' ORDER BY fecha_registro");
            $query = $this->db->query("SELECT * FROM
                                        tbl_vacations
                                        WHERE
                                        dias_a_disfrutar_del 
                                        BETWEEN '" . $data->fecha_inicio . "' and '" . $data->fecha_fin . "' AND " . $sql . " ORDER BY id_vcns ");
            $reporte = $query->getResult();
            //var_dump($reporte);
        }
        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:P1');
        $sheet->getStyle("A1:P1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor()
            ->setRGB('FFFFFF');
        $sheet->getStyle("A1:P1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
        $sheet->getStyle('A1:P1')->getBorders()->getTop()->setColor($color);
        $sheet->getStyle('A1:P1')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        if ($data->tipo_reportes == 1) {
            $sheet->setTitle("Entradas_Salidas");

            $sheet->setCellValue('A1', 'ID_ES');
            $sheet->setCellValue('B1', 'USUARIO');
            $sheet->setCellValue('C1', 'FECHA_CREACION');
            $sheet->setCellValue('D1', 'TIPO_EMPLEADO');
            $sheet->setCellValue('E1', 'NOMBRE_SOLICITANTE');
            $sheet->setCellValue('F1', 'DEPARTAMENTO');
            $sheet->setCellValue('G1', 'NUMERO_NOMINA');
            $sheet->setCellValue('H1', 'HORA_SALIDA');
            $sheet->setCellValue('I1', 'FECHA_SALIDA');
            $sheet->setCellValue('J1', 'HORA_ENTRADA');
            $sheet->setCellValue('K1', 'FECHA_ENTRADA');
            $sheet->setCellValue('L1', 'INASISTENCIA_DEL');
            $sheet->setCellValue('M1', 'INASISTENCIA_AL');
            $sheet->setCellValue('N1', 'GOCE_SUELDO');
            $sheet->setCellValue('O1', 'OBSERVACIONES');
            $sheet->setCellValue('P1', 'ESTATUS');

            foreach ($reporte as $key => $value) {

                ($cont % 2 == 0)
                    ? $spreadsheet->getActiveSheet()->getStyle('A' . $cont . ':P' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('B8CCE4')
                    : $spreadsheet->getActiveSheet()->getStyle('A' . $cont . ':P' . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D9E1F2');

                $celdaA = 'A' . $cont;
                $celdaB = 'B' . $cont;
                $celdaC = 'C' . $cont;
                $celdaD = 'D' . $cont;
                $celdaE = 'E' . $cont;
                $celdaF = 'F' . $cont;
                $celdaG = 'G' . $cont;
                $celdaH = 'H' . $cont;
                $celdaI = 'I' . $cont;
                $celdaJ = 'J' . $cont;
                $celdaK = 'K' . $cont;
                $celdaL = 'L' . $cont;
                $celdaM = 'M' . $cont;
                $celdaN = 'N' . $cont;
                $celdaO = 'O' . $cont;
                $celdaP = 'P' . $cont;

                $sheet->setCellValue($celdaA, $value->id_es);
                $sheet->setCellValue($celdaB, $value->user);
                $sheet->setCellValue($celdaC, $value->fecha_creacion);
                $sheet->setCellValue($celdaD, $value->tipo_empleado);
                $sheet->setCellValue($celdaE, $value->nombre_solicitante);
                $sheet->setCellValue($celdaF, $value->departamento);
                $sheet->setCellValue($celdaG, $value->num_nomina);
                $sheet->setCellValue($celdaH, $value->hora_salida);
                $sheet->setCellValue($celdaI, $value->fecha_salida);
                $sheet->setCellValue($celdaJ, $value->hora_entrada);
                $sheet->setCellValue($celdaK, $value->fecha_entrada);
                $sheet->setCellValue($celdaL, $value->inasistencia_del);
                $sheet->setCellValue($celdaM, $value->inasistencia_al);
                $sheet->setCellValue($celdaN, $value->goce_sueldo);
                $sheet->setCellValue($celdaO, $value->observaciones);
                $sheet->setCellValue($celdaP, $value->estatus);
                $cont++;
            }
        } else {
            $sheet->setTitle("Vacaciones");

            $sheet->setCellValue('A1', 'ID_VCNS');
            $sheet->setCellValue('B1', 'USUARIO');
            $sheet->setCellValue('C1', 'FECHA_CREACION');
            $sheet->setCellValue('D1', 'NOMBRE_SOLICITANTE');
            $sheet->setCellValue('E1', 'TIPO_EMPLEADO');
            $sheet->setCellValue('F1', 'DEPARTAMENTO');
            $sheet->setCellValue('G1', 'NUMERO_NOMINA');
            $sheet->setCellValue('H1', 'PUESTO');
            $sheet->setCellValue('I1', 'FECHA_INGRESO');
            $sheet->setCellValue('J1', 'NUM_DIAS_A_DISFRUTAR');
            $sheet->setCellValue('K1', 'DIAS_A_DISFRUTAR_DEL');
            $sheet->setCellValue('L1', 'DIAS_A_ADISFRUTAR_AL');
            $sheet->setCellValue('M1', 'REGRESO');
            $sheet->setCellValue('N1', 'DIAS_RESTANTES');
            $sheet->setCellValue('O1', 'PRIMA_VACACIONAL');
            $sheet->setCellValue('P1', 'ESTATUS');


            foreach ($reporte as $key => $value) {
                $celdaA = 'A' . $cont;
                $celdaB = 'B' . $cont;
                $celdaC = 'C' . $cont;
                $celdaD = 'D' . $cont;
                $celdaE = 'E' . $cont;
                $celdaF = 'F' . $cont;
                $celdaG = 'G' . $cont;
                $celdaH = 'H' . $cont;
                $celdaI = 'I' . $cont;
                $celdaJ = 'J' . $cont;
                $celdaK = 'K' . $cont;
                $celdaL = 'L' . $cont;
                $celdaM = 'M' . $cont;
                $celdaN = 'N' . $cont;
                $celdaO = 'O' . $cont;
                $celdaP = 'P' . $cont;

                $sheet->setCellValue($celdaA, $value->id_vcns);
                $sheet->setCellValue($celdaB, $value->id_user);
                $sheet->setCellValue($celdaC, $value->fecha_registro);
                $sheet->setCellValue($celdaD, $value->nombre_solicitante);
                $sheet->setCellValue($celdaE, $value->tipo_empleado);
                $sheet->setCellValue($celdaF, $value->departamento);
                $sheet->setCellValue($celdaG, $value->num_nomina);
                $sheet->setCellValue($celdaH, $value->puesto);
                $sheet->setCellValue($celdaI, $value->fecha_ingreso);
                $sheet->setCellValue($celdaJ, $value->num_dias_a_disfrutar);
                $sheet->setCellValue($celdaK, $value->dias_a_disfrutar_del);
                $sheet->setCellValue($celdaL, $value->dias_a_disfrutar_al);
                $sheet->setCellValue($celdaM, $value->regreso);
                $sheet->setCellValue($celdaN, $value->dias_restantes);
                $sheet->setCellValue($celdaO, $value->prima_vacacional);
                $sheet->setCellValue($celdaP, $value->estatus);
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





    public function myPermits()
    {
        return ($this->is_logged) ? view('user/permissions_per_user') : redirect()->to(site_url());
    }
    public function my_Permits()
    {
        $data  = $this->permissionsModel->where('id_user', session()->id_user)->findAll();
        return json_encode($data);
    }

    public function permissionsAll()
    {
        return ($this->is_logged) ? view('user/permissions_all') : redirect()->to(site_url());
    }
    public function reports()
    {
        return ($this->is_logged) ? view('user/permissions_reports') : redirect()->to(site_url());
    }

    public function editPermission()
    {
        $id_folio = trim($this->request->getPost('id_folio'));
        $builder = $this->db->table('tbl_entry_and_exit_permits');
        $builder->select('*');
        $builder->where('id_es', $id_folio);
        $builder->limit(1);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }

    public function editPermissionVacations()
    {
        $id_folio = $this->request->getPost('id_folio');
        $builder = $this->db->table('tbl_vacations');
        $builder->select('*');
        $builder->where('id_vcns', $id_folio);
        $builder->limit(1);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }

    public function deleteVacations()
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $data = [
                'active_status' => 2
            ];
            $builder = $this->db->table('tbl_vacations');
            $builder->where('id_vcns', $id_folio);
            $result = $builder->update($data);
            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function deletePermissions()
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $data = [
                'active_status' => 2
            ];
            $builder = $this->db->table('tbl_entry_and_exit_permits');
            $builder->where('id_es', $id_folio);
            $result = $builder->update($data);
            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizePermission()
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $status = $this->request->getPost('estatus');
            $estatus = ($status == 1) ? 'Autorizada' : 'Rechazada';
            $data = [
                'estatus' => $estatus
            ];
            $builder = $this->db->table('tbl_entry_and_exit_permits');
            $builder->where('id_es', $id_folio);
            $result = $builder->update($data);
            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizedPermissions()
    {
        try {
            $builder = $this->db->table('tbl_entry_and_exit_permits');
            $builder->select('*');
            $where = "estatus='Autorizada' OR estatus='Pendiente'";
            $builder->where($where);
            //$builder->where('estatus', 'Autorizada');
            //$builder->where('estatus', 'Pendiente');
            $builder->limit(1500);
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
        $builder = $this->db->table('tbl_vacations');
        $builder->select('*');
        $builder->where('id_vcns', $id_folio);
        $builder->limit(1);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }

    public function authorizeVacation()
    {
        try {
            $id_folio = $this->request->getPost('id_folio');
            $status = $this->request->getPost('estatus');
            $days = $this->request->getPost('dias');
            $payroll_number = $this->request->getPost('num_nomina');
            $estatus = ($status == 1) ? 'Autorizada' : 'Rechazada';
            if ($status == 2) {
                $builder = $this->db->table('tbl_users');
                $builder->set('vacation_days_total', 'vacation_days_total +' . $days, false);
                $builder->where('payroll_number', $payroll_number);
                $builder->update();
            }
            $data = [
                'estatus' => $estatus
            ];
            $builder = $this->db->table('tbl_vacations');
            $builder->where('id_vcns', $id_folio);
            $result = $builder->update($data);
            return ($result) ? json_encode($result) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function vacationPermission()
    {
        $user = trim($this->request->getPost('usuario'));
        $departament = trim($this->request->getPost('depto'));
        $job_position = trim($this->request->getPost('puesto_trabajo'));
        $payroll_number = trim($this->request->getPost('num_nomina'));
        $date_admission = trim($this->request->getPost('fecha_ingreso'));
        $type_of_employee = trim($this->request->getPost('tipo_empleado'));
        $days_available = trim($this->request->getPost('dias_disponibles'));
        $days_to_enjoy = trim($this->request->getPost('dias_disfrutar'));
        $start_days = trim($this->request->getPost('inicio_dias'));
        $end_of_days = trim($this->request->getPost('fin_dias'));
        $return_activities = trim($this->request->getPost('regresar_actividades'));
        $date = date("Y-m-d H:i:s");

        $data  = $this->model->where('id_user', session()->id_user)->find();

        foreach ($data as $key => $value) {
            $dias_disponibles = $value["vacation_days_total"];


            $dias_restantes = $dias_disponibles - $days_to_enjoy;
            $dataUser = ['vacation_days_total' => $dias_restantes];
            $this->model->update(session()->id_user, $dataUser);
            $data = [
                "id_user" => session()->id_user,
                "nombre_solicitante" => $user,
                "id_depto" => session()->id_depto,
                "departamento" => $departament,
                "puesto" => $job_position,
                "num_nomina" => $payroll_number,
                "fecha_ingreso" => $date_admission,
                "tipo_empleado" => $type_of_employee,
                "num_dias_a_disfrutar" => $days_to_enjoy,
                "dias_a_disfrutar_del" => $start_days,
                "dias_a_disfrutar_al" => $end_of_days,
                "regreso" => $return_activities,
                "dias_restantes" => $dias_restantes,
                "fecha_registro" => $date
            ];


            $number = session()->payroll_number;
            $query = $this->db->query("SELECT DISTINCT id_manager
                                    FROM
                                        tbl_assign_departments_to_managers_copy
                                    WHERE
                                        payroll_number = $number");
            $idUser = $query->getResultArray();
            foreach ($idUser as $key => $value) {
                $builder = $this->db->table('tbl_users');
                $builder->select('email,name,surname');
                $builder->where('id_user', $value["id_manager"]);
                $builder->limit(1);
                $email = $builder->get()->getResultArray();
                $user1 = $email[0]["name"] . " " . $email[0]["surname"];

                $this->notificarVacationsEmail($email[0]["email"], $user1, $data);
            }

            return ($this->vacationModel->insert($data)) ? json_encode($dias_restantes) : json_encode('error');
        }
    }



    public function reportsGenerate($tipoReporte = null, $fechaInicio = null, $fechaFin = null)
    {
        $mes = date("m", strtotime($fechaInicio));

        $dia = date("d", strtotime($fechaInicio));

        $Anio = date("Y", strtotime($fechaInicio));

        //echo $FechaObj   = DateTime::createFromFormat('!m', $mes);

        //echo $NombreMes = $FechaObj->format('F'); // March

        if ($tipoReporte == 1) {
            $NombreArchivo = "entradas_salidas_semana" . $dia . "-" . $mes . "-" . $Anio . ".xlsx";
            // $reporte = $conectar->query("SELECT * FROM entrada_salida WHERE fecha_creacion BETWEEN '" . $fechaInicio . "' and '" . $fechaFin . "' ORDER BY fecha_creacion,id_es");

            $query = $this->db->query("SELECT * FROM
                                        tbl_entry_and_exit_permits
                                        WHERE
                                        fecha_creacion 
                                        BETWEEN '" . $fechaInicio . "' and '" . $fechaFin . "' ORDER BY fecha_creacion,id_es");
            $reporte = $query->getResult();
        } else {
            $NombreArchivo = "vacaciones_semana" . $dia . "-" . $mes . "-" . $Anio . ".xlsx";
            // $reporte = $conectar->query("SELECT * FROM vacaciones WHERE fecha_registro BETWEEN '" . $fechaInicio . "' and '" . $fechaFin . "' ORDER BY fecha_registro");
            $query = $this->db->query("SELECT * FROM
                                        tbl_vacations
                                        WHERE
                                        fecha_registro 
                                        BETWEEN '" . $fechaInicio . "' and '" . $fechaFin . "' ORDER BY fecha_registro");
            $reporte = $query->getResult();
            //var_dump($reporte);
        }

        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:P1');
        $sheet->getStyle("A1:P1")->getFont()->setBold(true)
            ->setName('Calibri')
            ->setSize(11)
            ->getColor()
            ->setRGB('FFFFFF');
        $sheet->getStyle("A1:P1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
        $sheet->getStyle('A1:P1')->getBorders()->getTop()->setColor($color);
        $sheet->getStyle('A1:P1')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        if ($tipoReporte == 1) {
            $sheet->setTitle("Entradas_Salidas");

            $sheet->setCellValue('A1', 'ID_ES');
            $sheet->setCellValue('B1', 'USUARIO');
            $sheet->setCellValue('C1', 'FECHA_CREACION');
            $sheet->setCellValue('D1', 'TIPO_EMPLEADO');
            $sheet->setCellValue('E1', 'NOMBRE_SOLICITANTE');
            $sheet->setCellValue('F1', 'DEPARTAMENTO');
            $sheet->setCellValue('G1', 'NUMERO_NOMINA');
            $sheet->setCellValue('H1', 'HORA_SALIDA');
            $sheet->setCellValue('I1', 'FECHA_SALIDA');
            $sheet->setCellValue('J1', 'HORA_ENTRADA');
            $sheet->setCellValue('K1', 'FECHA_ENTRADA');
            $sheet->setCellValue('L1', 'INASISTENCIA_DEL');
            $sheet->setCellValue('M1', 'INASISTENCIA_AL');
            $sheet->setCellValue('N1', 'GOCE_SUELDO');
            $sheet->setCellValue('O1', 'OBSERVACIONES');
            $sheet->setCellValue('P1', 'ESTATUS');

            foreach ($reporte as $key => $value) {
                $celdaA = 'A' . $cont;
                $celdaB = 'B' . $cont;
                $celdaC = 'C' . $cont;
                $celdaD = 'D' . $cont;
                $celdaE = 'E' . $cont;
                $celdaF = 'F' . $cont;
                $celdaG = 'G' . $cont;
                $celdaH = 'H' . $cont;
                $celdaI = 'I' . $cont;
                $celdaJ = 'J' . $cont;
                $celdaK = 'K' . $cont;
                $celdaL = 'L' . $cont;
                $celdaM = 'M' . $cont;
                $celdaN = 'N' . $cont;
                $celdaO = 'O' . $cont;
                $celdaP = 'P' . $cont;

                $sheet->setCellValue($celdaA, $value->id_es);
                $sheet->setCellValue($celdaB, $value->user);
                $sheet->setCellValue($celdaC, $value->fecha_creacion);
                $sheet->setCellValue($celdaD, $value->tipo_empleado);
                $sheet->setCellValue($celdaE, $value->nombre_solicitante);
                $sheet->setCellValue($celdaF, $value->departamento);
                $sheet->setCellValue($celdaG, $value->num_nomina);
                $sheet->setCellValue($celdaH, $value->hora_salida);
                $sheet->setCellValue($celdaI, $value->fecha_salida);
                $sheet->setCellValue($celdaJ, $value->hora_entrada);
                $sheet->setCellValue($celdaK, $value->fecha_entrada);
                $sheet->setCellValue($celdaL, $value->inasistencia_del);
                $sheet->setCellValue($celdaM, $value->inasistencia_al);
                $sheet->setCellValue($celdaN, $value->goce_sueldo);
                $sheet->setCellValue($celdaO, $value->observaciones);
                $sheet->setCellValue($celdaP, $value->estatus);
                $cont++;
            }
        } else {
            $sheet->setTitle("Vacaciones");

            $sheet->setCellValue('A1', 'ID_VCNS');
            $sheet->setCellValue('B1', 'USUARIO');
            $sheet->setCellValue('C1', 'FECHA_CREACION');
            $sheet->setCellValue('D1', 'NOMBRE_SOLICITANTE');
            $sheet->setCellValue('E1', 'TIPO_EMPLEADO');
            $sheet->setCellValue('F1', 'DEPARTAMENTO');
            $sheet->setCellValue('G1', 'NUMERO_NOMINA');
            $sheet->setCellValue('H1', 'PUESTO');
            $sheet->setCellValue('I1', 'FECHA_INGRESO');
            $sheet->setCellValue('J1', 'NUM_DIAS_A_DISFRUTAR');
            $sheet->setCellValue('K1', 'DIAS_A_DISFRUTAR_DEL');
            $sheet->setCellValue('L1', 'DIAS_A_ADISFRUTAR_AL');
            $sheet->setCellValue('M1', 'REGRESO');
            $sheet->setCellValue('N1', 'DIAS_RESTANTES');
            $sheet->setCellValue('O1', 'PRIMA_VACACIONAL');
            $sheet->setCellValue('P1', 'ESTATUS');


            foreach ($reporte as $key => $value) {
                $celdaA = 'A' . $cont;
                $celdaB = 'B' . $cont;
                $celdaC = 'C' . $cont;
                $celdaD = 'D' . $cont;
                $celdaE = 'E' . $cont;
                $celdaF = 'F' . $cont;
                $celdaG = 'G' . $cont;
                $celdaH = 'H' . $cont;
                $celdaI = 'I' . $cont;
                $celdaJ = 'J' . $cont;
                $celdaK = 'K' . $cont;
                $celdaL = 'L' . $cont;
                $celdaM = 'M' . $cont;
                $celdaN = 'N' . $cont;
                $celdaO = 'O' . $cont;
                $celdaP = 'P' . $cont;

                $sheet->setCellValue($celdaA, $value->id_vcns);
                $sheet->setCellValue($celdaB, $value->id_user);
                $sheet->setCellValue($celdaC, $value->fecha_registro);
                $sheet->setCellValue($celdaD, $value->nombre_solicitante);
                $sheet->setCellValue($celdaE, $value->tipo_empleado);
                $sheet->setCellValue($celdaF, $value->departamento);
                $sheet->setCellValue($celdaG, $value->num_nomina);
                $sheet->setCellValue($celdaH, $value->puesto);
                $sheet->setCellValue($celdaI, $value->fecha_ingreso);
                $sheet->setCellValue($celdaJ, $value->num_dias_a_disfrutar);
                $sheet->setCellValue($celdaK, $value->dias_a_disfrutar_del);
                $sheet->setCellValue($celdaL, $value->dias_a_disfrutar_al);
                $sheet->setCellValue($celdaM, $value->regreso);
                $sheet->setCellValue($celdaN, $value->dias_restantes);
                $sheet->setCellValue($celdaO, $value->prima_vacacional);
                $sheet->setCellValue($celdaP, $value->estatus);
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

    public function permissions_all()
    {
        $builder = $this->db->table('tbl_entry_and_exit_permits');
        $builder->select('*');
        $builder->where('active_status', 1);
        $builder->orderBy('id_es', 'DESC');
        $builder->limit(3500);
        $data = $builder->get()->getResult();
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
        $builder = $this->db->table('tbl_vacations');
        $builder->select('*');
        $builder->where('active_status', 1);
        $builder->orderBy('id_vcns', 'DESC');
        $builder->limit(1500);
        $data = $builder->get()->getResult();
        return json_encode($data);
    }





    public function myVacations()
    {
        try {

            $data  = $this->vacationModel->where('id_user', session()->id_user)->findAll();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizePermissions()
    {
        return ($this->is_logged) ? view('user/permissions_authorize') : redirect()->to(site_url());
    }

    public function authorize_permissions()
    {
        try {
            $id_user = session()->id_user;
            $query = $this->db->query("SELECT * FROM
                                        tbl_entry_and_exit_permits
                                        WHERE
                                            num_nomina IN (
                                                SELECT DISTINCT
                                                    payroll_number
                                                FROM
                                                    tbl_assign_departments_to_managers_copy
                                                WHERE
                                                id_manager = $id_user OR id_director = $id_user) AND active_status = 1");
            $data = $query->getResult();

            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorization()
    {
        $id = trim($this->request->getPost('id_folio'));
        $autorizacion = trim($this->request->getPost('autorizacion'));
        $dataUser = ['estatus' => $autorizacion];
        $this->permissionsModel->update($id, $dataUser);
    }

    public function authorizationVacations()
    {
        $id = trim($this->request->getPost('id_folio'));
        $autorizacion = trim($this->request->getPost('autorizacion'));
        $days = $this->request->getPost('dias');
        $payroll_number = $this->request->getPost('num_nomina');

        if ($autorizacion == "Rechazada") {
            $builder = $this->db->table('tbl_users');
            $builder->set('vacation_days_total', 'vacation_days_total +' . $days, false);
            $builder->where('payroll_number', $payroll_number);
            $builder->update();
        }
        $dataUser = ['estatus' => $autorizacion];
        $result = $this->vacationModel->update($id, $dataUser);

        return ($result) ? json_encode($result) : json_encode(false);
    }

    public function authorize_vacations()
    {
        try {
            $id_user = session()->id_user;
            $query = $this->db->query("SELECT * FROM
                                        tbl_vacations
                                        WHERE
                                            num_nomina IN (
                                                SELECT DISTINCT
                                                payroll_number
                                                FROM
                                                    tbl_assign_departments_to_managers_copy
                                                WHERE
                                                    id_manager = $id_user OR id_director = $id_user) AND active_status = 1");
            $data = $query->getResult();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function pdfSeePermissions($id_permission = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_entry_and_exit_permits
                                        WHERE
                                        MD5(concat('" . $key . "',id_es))='" . $id_permission . "'");
        $dataPermission =  $query->getRow();
        $data = [
            "request" => $dataPermission
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
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
        $query = $this->db->query("SELECT * FROM
                                        tbl_vacations
                                        WHERE
                                        MD5(concat('" . $key . "',id_vcns))='" . $id_vacation . "'");
        $dataVacations =  $query->getRow();
        //d($dataVacations);
        $data = [
            "request" => $dataVacations
        ];

        $html2 = view('pdf/vacations', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Permiso Vacaciones');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('permiso_' . $id_vacation . '.pdf', 'I');
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
        //var_dump($reporte);


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
        $NombreArchivo = "reporte_datos_generales.xlsx";
        $query = $this->db->query("SELECT id_datos FROM tbl_users_personal_data");
        $datos_info = count($query->getResult());
        //$datos_info = 203;
        //var_dump($datos);
        $items = 1;
        $cont = 1;
        $cont2 = 2;

        $cont_emer = 4;
        $cont_emer2 = 5;

        $cont_hijos = 8;
        $cont_hijos2 = 9;

        $cont_padres = 15;
        $cont_padres2 = 16;

        $contador_usuario = 1;
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(38);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);

        while ($items <= $datos_info) {
            $query = $this->db->query("SELECT num_nomina,ape_paterno,
                                                ape_materno,
                                                nombre,
                                                edad_usuario,
                                                fecha_nacimiento,
                                                fecha_ingreso,
                                                estado_civil,
                                                estado,
                                                municipio,
                                                colonia,
                                                codigo_postal,
                                                calle,
                                                numero_exterior,
                                                numero_interior,
                                                nombre_conyuge,
                                                edad_conyuge,
                                                ocupacion_conyuge,
                                                tel_conyuge,
                                                escolaridad,
                                                lic_ing,
                                                diplomados,
                                                cursos_externos
                                                FROM tbl_users_personal_data
                                                WHERE id_datos=" . $contador_usuario);
            $reporte = $query->getResult();

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("A" . $cont . ":W" . $cont)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("A" . $cont . ":W" . $cont)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle("A" . $cont . ":W" . $cont)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle("A" . $cont . ":W" . $cont)->getBorders()->getTop()->setColor($color);

            $sheet->setTitle("Reporte Datos Generales");

            $sheet->setCellValue('A' . $cont, 'Numero Nomina');
            $sheet->setCellValue('B' . $cont, 'Apellido paterno');
            $sheet->setCellValue('C' . $cont, 'Apellido materno');
            $sheet->setCellValue('D' . $cont, 'Nombres(s)');
            $sheet->setCellValue('E' . $cont, 'Edad');
            $sheet->setCellValue('F' . $cont, 'Fecha de Nacimiento');
            $sheet->setCellValue('G' . $cont, 'Fecha Ingreso');
            $sheet->setCellValue('H' . $cont, 'Estado civil');
            $sheet->setCellValue('I' . $cont, 'Estado');
            $sheet->setCellValue('J' . $cont, 'Municipio');
            $sheet->setCellValue('K' . $cont, 'Colonia');
            $sheet->setCellValue('L' . $cont, 'Codigo Postal');
            $sheet->setCellValue('M' . $cont, 'Calle');
            $sheet->setCellValue('N' . $cont, 'Numero Exterior');
            $sheet->setCellValue('O' . $cont, 'Numero Interior');
            $sheet->setCellValue('P' . $cont, 'Conyuge');
            $sheet->setCellValue('Q' . $cont, 'Edad Conyuge');
            $sheet->setCellValue('R' . $cont, 'Ocupacion Conyuge');
            $sheet->setCellValue('S' . $cont, 'Telefono Conyuge');
            $sheet->setCellValue('T' . $cont, 'Escolaridad');
            $sheet->setCellValue('U' . $cont, 'Carrera');
            $sheet->setCellValue('V' . $cont, 'Diplomados');
            $sheet->setCellValue('W' . $cont, 'Cursos Extra');


            foreach ($reporte as $key => $value) {

                $sheet->setCellValue('A' . $cont2, $value->num_nomina);
                $sheet->setCellValue('B' . $cont2, $value->ape_paterno);
                $sheet->setCellValue('C' . $cont2, $value->ape_materno);
                $sheet->setCellValue('D' . $cont2, $value->nombre);
                $sheet->setCellValue('E' . $cont2, $value->edad_usuario);
                $sheet->setCellValue('F' . $cont2, $value->fecha_nacimiento);
                $sheet->setCellValue('G' . $cont2, $value->fecha_ingreso);
                $sheet->setCellValue('H' . $cont2, $value->estado_civil);
                $sheet->setCellValue('I' . $cont2, $value->estado);
                $sheet->setCellValue('J' . $cont2, $value->municipio);
                $sheet->setCellValue('K' . $cont2, $value->colonia);
                $sheet->setCellValue('L' . $cont2, $value->codigo_postal);
                $sheet->setCellValue('M' . $cont2, $value->calle);
                $sheet->setCellValue('N' . $cont2, $value->numero_exterior);
                $sheet->setCellValue('O' . $cont2, $value->numero_interior);
                $sheet->setCellValue('P' . $cont2, $value->nombre_conyuge);
                $sheet->setCellValue('Q' . $cont2, $value->edad_conyuge);
                $sheet->setCellValue('R' . $cont2, $value->ocupacion_conyuge);
                $sheet->setCellValue('S' . $cont2, $value->tel_conyuge);
                $sheet->setCellValue('T' . $cont2, $value->escolaridad);
                $sheet->setCellValue('U' . $cont2, $value->lic_ing);
                $sheet->setCellValue('V' . $cont2, $value->diplomados);
                $sheet->setCellValue('W' . $cont2, $value->cursos_externos);

                $cont2 = $cont2 + 20;
            }

            $sheet->getStyle("B" . $cont_emer . ":D" . $cont_emer)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("B" . $cont_emer . ":D" . $cont_emer)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle("B" . $cont_emer . ":D" . $cont_emer)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle("B" . $cont_emer . ":D" . $cont_emer)->getBorders()->getTop()->setColor($color);

            $sheet->setCellValue('B' . $cont_emer, 'Contacto de Emergencia');
            $sheet->setCellValue('C' . $cont_emer, 'Numero Teléfonico');
            $sheet->setCellValue('D' . $cont_emer, 'Parentesco');

            $query_emer = $this->db->query("SELECT  a.contacto_emergencia,a.tel_emergencia,a.parentesco_emergencia 
                                            FROM tbl_users_emergency_contact AS a
                                            WHERE
                                            a.id_datos=" . $contador_usuario);

            $emergencia = $query_emer->getResult();

            foreach ($emergencia as $key => $value) {

                $sheet->setCellValue('B' . $cont_emer2, $value->contacto_emergencia);
                $sheet->setCellValue('C' . $cont_emer2, $value->tel_emergencia);
                $sheet->setCellValue('D' . $cont_emer2, $value->parentesco_emergencia);
                $cont_emer2++;
            }

            $cont_emer2 =  $cont_emer2 + 18;
            $cont_emer = $cont_emer + 20;

            $sheet->getStyle("B" . $cont_hijos . ":E" . $cont_hijos)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("B" . $cont_hijos . ":E" . $cont_hijos)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle("B" . $cont_hijos . ":E" . $cont_hijos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle("B" . $cont_hijos . ":E" . $cont_hijos)->getBorders()->getTop()->setColor($color);


            $sheet->setCellValue('B' . $cont_hijos, 'Nombre(s) Hijo(s)');
            $sheet->setCellValue('C' . $cont_hijos, 'Fecha de Nacimiento');
            $sheet->setCellValue('D' . $cont_hijos, 'Edad');
            $sheet->setCellValue('E' . $cont_hijos, 'Genero');

            $query_hijos = $this->db->query("SELECT  nombre_hijo,fecha_nacimiento,edad_hijo,genero 
                                                FROM tbl_users_children 
                                                WHERE
                                                id_datos=" . $contador_usuario);

            $hijos = $query_hijos->getResult();

            $cn_hijos = count($hijos);
            $contador_hijos = 0;

            if ($cn_hijos > 0) {
                foreach ($hijos as $key => $value) {

                    $sheet->setCellValue('B' . $cont_hijos2, $value->nombre_hijo);
                    $sheet->setCellValue('C' . $cont_hijos2, $value->fecha_nacimiento);
                    $sheet->setCellValue('D' . $cont_hijos2, $value->edad_hijo);
                    $sheet->setCellValue('E' . $cont_hijos2, $value->genero);
                    $cont_hijos2++;
                    $contador_hijos++;
                }
                $numbers = 20 - $contador_hijos;
                $cont_hijos2 = $cont_hijos2 + $numbers;
                $cont_hijos = $cont_hijos + 20;
            } else {
                for ($i = 1; $i <= 5; $i++) {
                    $sheet->setCellValue('B' . $cont_hijos2, '-');
                    $sheet->setCellValue('C' . $cont_hijos2, '-');
                    $sheet->setCellValue('D' . $cont_hijos2, '-');
                    $sheet->setCellValue('E' . $cont_hijos2, '-');
                    $cont_hijos2++;
                    $contador_hijos++;
                }
                $numbers = 20 - $contador_hijos;
                $cont_hijos2 = $cont_hijos2 + $numbers;
                $cont_hijos = $cont_hijos + 20;
            }

            $sheet->getStyle("B" . $cont_padres . ":F" . $cont_padres)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle("B" . $cont_padres . ":F" . $cont_padres)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle("B" . $cont_padres . ":F" . $cont_padres)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle("B" . $cont_padres . ":F" . $cont_padres)->getBorders()->getTop()->setColor($color);


            $sheet->setCellValue('B' . $cont_padres, 'Nombre de los Padres');
            $sheet->setCellValue('C' . $cont_padres, 'Fecha de Nacimiento');
            $sheet->setCellValue('D' . $cont_padres, 'Genero');
            $sheet->setCellValue('E' . $cont_padres, 'Estado');
            $sheet->setCellValue('F' . $cont_padres, 'Edad');

            $query_padres = $this->db->query("SELECT nombre_padres,fecha_nacimiento_padres,genero_padres,finado,edad 
                                                FROM tbl_users_parents
                                                WHERE
                                                id_datos=" . $contador_usuario);

            $padres = $query_padres->getResult();

            $contador_padres = 0;
            foreach ($padres as $key => $value) {

                $sheet->setCellValue('B' . $cont_padres2, $value->nombre_padres);
                $sheet->setCellValue('C' . $cont_padres2, $value->fecha_nacimiento_padres);
                $sheet->setCellValue('D' . $cont_padres2, $value->genero_padres);
                $sheet->setCellValue('E' . $cont_padres2, $value->finado);
                $sheet->setCellValue('F' . $cont_padres2, $value->edad);
                $cont_padres2++;
                $contador_padres++;
            }
            $numbers1 = 20 - $contador_padres;
            $cont_padres2 = $cont_padres2 + $numbers1;
            $cont_padres = $cont_padres + 20;


            $cont = $cont + 20;
            $contador_usuario++;
            $items++;
        }



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
    }
}
