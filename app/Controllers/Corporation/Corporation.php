<?php

namespace App\Controllers\Corporation;

use DateTime;
use App\Models\DeptoModel;
use App\Models\OverTimeModel;
use App\Models\VisitQhseModel;
use PHPMailer\PHPMailer\Exception;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spipu\Html2Pdf\Html2Pdf;
use CodeIgniter\Files\File;

class Corporation extends BaseController
{
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->deptoModel = new DeptoModel();
        $this->visitModel = new VisitQhseModel();
        $this->overTimeModel = new OverTimeModel();


        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }


    public function viewReports()
    {
        return ($this->is_logged) ?  view('corporation/corporativos_reportes') : redirect()->to(site_url());
    }
    /**
     * vista para reportes de servicios Generales
     */
    public function viewReportsServices()
    {
        return ($this->is_logged) ?  view('corporation/servicios_generales_reportes') : redirect()->to(site_url());
    }


    public function pdfReportCoffe($start_date, $end_date)
    {

        $query = $this->db->query("SELECT
                                        area_operativa,
                                        COUNT(id_coffee) AS solicitudes,
                                        SUM(CASE WHEN `status` = 2 THEN 1 ELSE 0 END) AS atendida,
                                        MONTH(created_at) AS Mes 
                                    FROM
                                        tbl_coffee_break 
                                    WHERE
                                        STATUS IN (1, 2, 3)    
                                      AND created_at BETWEEN '$start_date' AND '$end_date' -- Ajuste en el rango de fechas
                                    --    AND created_at BETWEEN '2023-02-01' AND '2023-02-29' -- Ajuste en el rango de fechas
                                    GROUP BY
                                        depto, Mes ORDER BY depto ASC;
                                    ");
        $data =  $query->getResult();

        // Inicializar un array para almacenar los meses únicos
         $mesesUnicos = 1;

        // Recorrer los resultados y almacenar los meses únicos
        foreach ($data as $result) {
            //$mesesUnicos[] = $result->Mes;
            $mesesUnicos = $result->Mes;
        }

        // Obtener los meses únicos
        //$mesesUnicos = array_unique($mesesUnicos);

        // Contar la cantidad de meses únicos
        //$cantidadMeses = count($mesesUnicos);

        $cantidadMeses = $mesesUnicos;

        // Suponiendo que $result es el conjunto de resultados de tu consulta
        $resultados = array();

        $queryStatus = $this->db->query(" SELECT
                                            Departamento,
                                            SUM(CASE WHEN Estado = 'Autorizada' THEN cantidad ELSE 0 END) AS Autorizada,
                                            SUM(CASE WHEN Estado = 'Pendiente' THEN cantidad ELSE 0 END) AS Pendiente,
                                            SUM(CASE WHEN Estado = 'Rechazada' THEN cantidad ELSE 0 END) AS Rechazada,
                                            SUM(cantidad) AS 'suma_total'
                                        FROM (
                                            SELECT
                                                CASE
                                                
                                                    WHEN area_operativa IN (6401,6403,6404,6406,6407,6409,6411,6420,6421,6516,6408,6402,6405,6333) THEN 'Comercial'
                                                    WHEN area_operativa IN (6502,6504,6506,6507,6508,6510,6505,6514,6503,6501,6513) THEN 'Finanzas'
                                                    WHEN area_operativa IN (6320,6321,6324,6322,6323) THEN 'Calidad / HSE'
                                                    WHEN area_operativa IN (6509,6520) THEN 'TI'
                                                    WHEN area_operativa IN (6413) THEN 'Villahermosa'
                                                    WHEN area_operativa IN (6521,6524) THEN 'Corporativo'                                                 
                                                    WHEN area_operativa IN (6306,6332,6302,6330,6304,6307,6303,6430,6341,6331,6102,6312,6102,6106,6301,6311,6113,6101,6341,6103,6104,6105,6108,6109,6112,6115,6110,6111) THEN 'Operaciones'
                                                    WHEN area_operativa IN (6431,6432,6308,6309,6305) THEN 'Logistica / Almacenes'
                                                    -- Agrega más casos según tu lista de departamentos
                                                    ELSE 'Desconocido'
                                                END AS Departamento,
                                                CASE
                                                        WHEN status = 1 THEN 'Pendiente'
                                                    WHEN status = 2 THEN 'Autorizada'
                                                    WHEN status = 3 THEN 'Rechazada'
                                                    ELSE 'Estado Desconocido'
                                                END AS Estado,
                                                COUNT(*) AS cantidad
                                            FROM
                                                tbl_coffee_break
                                            WHERE
                                                status IN (1, 2, 3)
                                                AND created_at BETWEEN '$start_date' AND '$end_date'
                                            GROUP BY
                                                area_operativa,
                                                status
                                        ) AS Subconsulta
                                        GROUP BY
                                            Departamento
                                        WITH ROLLUP;");
        $dataTotal =  $queryStatus->getResult();

        // var_dump($dataTotal);

        // Arrays para almacenar totales por mes
        $totalesSolicitadas = array_fill(1, 12, 0);
        $totalesAtendidas = array_fill(1, 12, 0);

        // Arrays para almacenar totales por área operativa
        $totalesSolicitudesPorArea = array();
        $totalesAtendidasPorArea = array();

        // Arrays para almacenar totales por área operativa
        $totalSolicitudesPorArea = array();
        // $totalAtendidasPorArea = array();

        // Definir un array asociativo que mapea áreas operativas a departamentos
        $departamentos = [
            6413 => 'Almacen Villahermosa',
            6401 => 'Comercial',
            6403 => 'Comercial',
            6404 => 'Comercial',
            6406 => 'Comercial',
            6407 => 'Comercial',
            6409 => 'Comercial',
            6411 => 'Comercial',
            6420 => 'Comercial',
            6502 => 'Finanzas',
            6504 => 'Finanzas',
            6506 => 'Finanzas',
            6507 => 'Finanzas',
            6508 => 'Finanzas',
            6510 => 'Finanzas',
            6505 => 'Finanzas',
            6320 => 'Calidad / HSE',
            6321 => 'Calidad / HSE',
            6324 => 'Calidad / HSE',
            6306 => 'Operaciones',
            6405 => 'Comercial',
            6332 => 'Operaciones',
            6509 => 'TI',
            6520 => 'TI',
            6521 => 'Corporativo',
            6431 => 'Logistica / Almacenes',
            6432 => 'Logistica / Almacenes',
            6302 => 'Operaciones',
            6330 => 'Operaciones',
            6304 => 'Operaciones',
            6307 => 'Operaciones',
            6421 => 'Comercial',
            6514 => 'Finanzas',
            6308 => 'Logistica / Almacenes',
            6516 => 'Comercial',
            6503 => 'Finanzas',
            6303 => 'Operaciones',
            6430 => 'Operaciones',
            6341 => 'Operaciones',
            6309 => 'Logistica / Almacenes',
            6322 => 'Calidad / HSE',
            6331 => 'Operaciones',
            6102 => 'Operaciones',
            6323 => 'Calidad / HSE',
            6312 => 'Operaciones',
            6102 => 'Operaciones',
            6106 => 'Operaciones',
            6301 => 'Operaciones',
            6311 => 'Operaciones',
            6113 => 'Operaciones',
            6501 => 'Finanzas',
            6408 => 'Comercial',
            6513 => 'Finanzas',
            6101 => 'Operaciones',
            6341 => 'Operaciones',
            6305 => 'Logistica / Almacenes',
            6402 => 'Comercial',
            6103 => 'Operaciones',
            6104 => 'Operaciones',
            6105 => 'Operaciones',
            6108 => 'Operaciones',
            6109 => 'Operaciones',
            6112 => 'Operaciones',
            6115 => 'Operaciones',
            6110 => 'Operaciones',
            6111 => 'Operaciones',
            6333 => 'Comercial',
            6524 => 'Corporativo'
        ];


        // Inicializar totales por área operativa
        foreach ($departamentos as $area_operativa => $departamento) {
            $totalesSolicitudesPorArea[$departamento] = array_fill(1, 12, 0);
            $totalesAtendidasPorArea[$departamento] = array_fill(1, 12, 0);

            $totalSolicitudesPorArea[$departamento] = 0;
            // $totalAtendidasPorArea[$departamento] = 0;
        }


        foreach ($data as $row) {

            $area_operativa = $row->area_operativa;
            $mes = $row->Mes;
            $solicitadas = $row->solicitudes;
            $atendidas = $row->atendida;
            /**
             ** se agruparann por Area para una visualizacion mas facil.
             */

            // Acumular totales por mes
            $totalesSolicitadas[$mes] += $solicitadas;
            $totalesAtendidas[$mes] += $atendidas;

            // Acumular totales por área operativa y mes
            $departamento = $departamentos[$area_operativa] ?? 'Depto.Desconocido';

            $totalesSolicitudesPorArea[$departamento][$mes] += $solicitadas;
            $totalesAtendidasPorArea[$departamento][$mes] += $atendidas;

            $totalSolicitudesPorArea[$departamento] += $solicitadas;
            //$totalAtendidasPorArea[$departamento] += $atendidas;
        }

        // Filtrar departamentos que tengan cero solicitudes y cero atendidas
        $totalSolicitudesPorArea = array_filter($totalSolicitudesPorArea, function ($value) {
            return $value > 0;
        });



        /*  $totalAtendidasPorArea = array_filter($totalAtendidasPorArea, function ($value) {
            return $value > 0;
        }); */

        // Construir la tabla de resultados
        $resultados = array();
        foreach ($departamentos as $area_operativa => $departamento) {
            // Verificar si hay información para este departamento
            $hayInformacion = array_sum($totalesSolicitudesPorArea[$departamento]) > 0;

            if ($hayInformacion) {
                $resultados[$departamento] = array();
                foreach ($totalesSolicitudesPorArea[$departamento] as $mes => $totalSolicitudes) {
                    $totalAtendidas = $totalesAtendidasPorArea[$departamento][$mes];
                    $totalPorcentaje = ($totalSolicitudes != 0) ? round(($totalAtendidas / $totalSolicitudes) * 100, 0) : 0;
                    $resultados[$departamento][$mes] = "$totalSolicitudes, $totalAtendidas, $totalPorcentaje%";
                }
            }
        }

        // var_dump($totalSolicitudesPorArea);

        // Sumar los datos
        $sumaSolicitudes = array_sum($totalSolicitudesPorArea);

        $dataCoffe = [
            "resultados" => $resultados,
            "total_solicitudes" => $totalesSolicitadas,
            "total_atendidas" => $totalesAtendidas,
            "total_areas" => $totalSolicitudesPorArea,
            "solicitudes_total" => $sumaSolicitudes,
            "totales_areas" => $dataTotal,
            "total_mes" => $cantidadMeses
        ];

        return $dataCoffe;
    }


    public function pdfReportValija($start_date, $end_date)
    {
        $queryStatus = $this->db->query("SELECT
                                            Departamento,
                                            SUM(CASE WHEN Estado = 'Autorizada' THEN cantidad ELSE 0 END) AS Autorizada,
                                            SUM(CASE WHEN Estado = 'Pendiente' THEN cantidad ELSE 0 END) AS Pendiente,
                                            SUM(CASE WHEN Estado = 'Rechazada' THEN cantidad ELSE 0 END) AS Rechazada,
                                            SUM(cantidad) AS 'suma_total'
                                        FROM (
                                            SELECT
                                                CASE
                                                    WHEN area_operativa IN (6401,6403,6404,6406,6407,6409,6411,6420,6421,6516,6408,6402,6405,6333) THEN 'Comercial'
                                                    WHEN area_operativa IN (6502,6504,6506,6507,6508,6510,6505,6514,6503,6501,6513) THEN 'Finanzas'
                                                    WHEN area_operativa IN (6320,6321,6324,6322,6323) THEN 'Calidad / HSE'
                                                    WHEN area_operativa IN (6509,6520) THEN 'TI'
                                                    WHEN area_operativa IN (6413) THEN 'Villahermosa'
                                                    WHEN area_operativa IN (6521,6524) THEN 'Corporativo'                                                 
                                                    WHEN area_operativa IN (6306,6332,6302,6330,6304,6307,6303,6430,6341,6331,6102,6312,6102,6106,6301,6311,6113,6101,6341,6103,6104,6105,6108,6109,6112,6115,6110,6111) THEN 'Operaciones'
                                                    WHEN area_operativa IN (6431,6432,6308,6309,6305) THEN 'Logistica / Almacenes'
                                                    -- Agrega más casos según tu lista de departamentos
                                                    ELSE 'Desconocido'
                                                END AS Departamento,
                                                CASE
                                                        WHEN status = 1 THEN 'Pendiente'
                                                    WHEN status = 2 THEN 'Autorizada'
                                                    WHEN status = 3 THEN 'Rechazada'
                                                    ELSE 'Estado Desconocido'
                                                END AS Estado,
                                                COUNT(*) AS cantidad
                                            FROM
                                            tbl_valija_request
                                            WHERE
                                                status IN (1, 2, 3)
                                                AND created_at BETWEEN '$start_date' AND '$end_date'
                                            GROUP BY
                                                area_operativa,
                                                status
                                        ) AS Subconsulta
                                        GROUP BY
                                            Departamento
                                        WITH ROLLUP;");
        $dataTotal =  $queryStatus->getResult();

        $query = $this->db->query("SELECT
                                        area_operativa,
                                        COUNT(id_valija) AS solicitudes,
                                        SUM(CASE WHEN `status` = 2 THEN 1 ELSE 0 END) AS atendida,
                                        MONTH(created_at) AS Mes 
                                    FROM
                                        tbl_valija_request 
                                    WHERE
                                        STATUS IN (1, 2, 3) 
                                        AND created_at BETWEEN '$start_date' AND '$end_date' -- Ajuste en el rango de fechas
                                    GROUP BY
                                        departament, Mes ORDER BY departament ASC;
                                    ");
        $data =  $query->getResult();

        // Inicializar un array para almacenar los meses únicos
         $mesesUnicos = 1;

        // Recorrer los resultados y almacenar los meses únicos
        foreach ($data as $result) {
            $mesesUnicos = $result->Mes;
        }

        // Obtener los meses únicos
        //$mesesUnicos = array_unique($mesesUnicos);

        // Contar la cantidad de meses únicos
        $cantidadMeses = $mesesUnicos;

        // Suponiendo que $result es el conjunto de resultados de tu consulta
        $resultados = array();

        // Arrays para almacenar totales por mes
        $totalesSolicitadas = array_fill(1, 12, 0);
        $totalesAtendidas = array_fill(1, 12, 0);

        // Arrays para almacenar totales por área operativa
        $totalesSolicitudesPorArea = array();
        $totalesAtendidasPorArea = array();

        // Arrays para almacenar totales por área operativa
        $totalSolicitudesPorArea = array();
        // $totalAtendidasPorArea = array();

        // Definir un array asociativo que mapea áreas operativas a departamentos
        $departamentos = [
            6413 => 'Almacen Villahermosa',
            6401 => 'Comercial',
            6403 => 'Comercial',
            6404 => 'Comercial',
            6406 => 'Comercial',
            6407 => 'Comercial',
            6409 => 'Comercial',
            6411 => 'Comercial',
            6420 => 'Comercial',
            6502 => 'Finanzas',
            6504 => 'Finanzas',
            6506 => 'Finanzas',
            6507 => 'Finanzas',
            6508 => 'Finanzas',
            6510 => 'Finanzas',
            6505 => 'Finanzas',
            6320 => 'Calidad / HSE',
            6321 => 'Calidad / HSE',
            6324 => 'Calidad / HSE',
            6306 => 'Operaciones',
            6405 => 'Comercial',
            6332 => 'Operaciones',
            6509 => 'TI',
            6520 => 'TI',
            6521 => 'Corporativo',
            6431 => 'Logistica / Almacenes',
            6432 => 'Logistica / Almacenes',
            6302 => 'Operaciones',
            6330 => 'Operaciones',
            6304 => 'Operaciones',
            6307 => 'Operaciones',
            6421 => 'Comercial',
            6514 => 'Finanzas',
            6308 => 'Logistica / Almacenes',
            6516 => 'Comercial',
            6503 => 'Finanzas',
            6303 => 'Operaciones',
            6430 => 'Operaciones',
            6341 => 'Operaciones',
            6309 => 'Logistica / Almacenes',
            6322 => 'Calidad / HSE',
            6331 => 'Operaciones',
            6102 => 'Operaciones',
            6323 => 'Calidad / HSE',
            6312 => 'Operaciones',
            6102 => 'Operaciones',
            6106 => 'Operaciones',
            6301 => 'Operaciones',
            6311 => 'Operaciones',
            6113 => 'Operaciones',
            6501 => 'Finanzas',
            6408 => 'Comercial',
            6513 => 'Finanzas',
            6101 => 'Operaciones',
            6341 => 'Operaciones',
            6305 => 'Logistica / Almacenes',
            6402 => 'Comercial',
            6103 => 'Operaciones',
            6104 => 'Operaciones',
            6105 => 'Operaciones',
            6108 => 'Operaciones',
            6109 => 'Operaciones',
            6112 => 'Operaciones',
            6115 => 'Operaciones',
            6110 => 'Operaciones',
            6111 => 'Operaciones',
            6333 => 'Comercial',
            6524 => 'Corporativo'
        ];


        // Inicializar totales por área operativa
        foreach ($departamentos as $area_operativa => $departamento) {
            $totalesSolicitudesPorArea[$departamento] = array_fill(1, 12, 0);
            $totalesAtendidasPorArea[$departamento] = array_fill(1, 12, 0);

            $totalSolicitudesPorArea[$departamento] = 0;
            // $totalAtendidasPorArea[$departamento] = 0;
        }


        foreach ($data as $row) {

            $area_operativa = $row->area_operativa;
            // echo $area_operativa2 = $row->area_operativa;
            $mes = $row->Mes;
            $solicitadas = $row->solicitudes;
            $atendidas = $row->atendida;
            /**
             ** se agruparann por Area para una visualizacion mas facil.
             */

            // Acumular totales por mes
            $totalesSolicitadas[$mes] += $solicitadas;
            $totalesAtendidas[$mes] += $atendidas;

            // Acumular totales por área operativa y mes
            // $departamento2 = $departamentos[$area_operativa];
            $departamento = $departamentos[$area_operativa];

            $totalesSolicitudesPorArea[$departamento][$mes] += $solicitadas;
            $totalesAtendidasPorArea[$departamento][$mes] += $atendidas;

            $totalSolicitudesPorArea[$departamento] += $solicitadas;
            //$totalAtendidasPorArea[$departamento] += $atendidas;
        }

        // Filtrar departamentos que tengan cero solicitudes y cero atendidas
        $totalSolicitudesPorArea = array_filter($totalSolicitudesPorArea, function ($value) {
            return $value > 0;
        });



        /*  $totalAtendidasPorArea = array_filter($totalAtendidasPorArea, function ($value) {
           return $value > 0;
       }); */

        // Construir la tabla de resultados
        $resultados = array();
        foreach ($departamentos as $area_operativa => $departamento) {
            // Verificar si hay información para este departamento
            $hayInformacion = array_sum($totalesSolicitudesPorArea[$departamento]) > 0;

            if ($hayInformacion) {
                $resultados[$departamento] = array();
                foreach ($totalesSolicitudesPorArea[$departamento] as $mes => $totalSolicitudes) {
                    $totalAtendidas = $totalesAtendidasPorArea[$departamento][$mes];
                    $totalPorcentaje = ($totalSolicitudes != 0) ? round(($totalAtendidas / $totalSolicitudes) * 100, 0) : 0;
                    $resultados[$departamento][$mes] = "$totalSolicitudes, $totalAtendidas, $totalPorcentaje%";
                }
            }
        }

        //var_dump($resultados);
        // Sumar los datos
        $sumaSolicitudes = array_sum($totalSolicitudesPorArea);

        $dataValija = [
            "resultados" => $resultados,
            "total_solicitudes" => $totalesSolicitadas,
            "total_atendidas" => $totalesAtendidas,
            "total_areas" => $totalSolicitudesPorArea,
            "solicitudes_total" => $sumaSolicitudes,
            "totales_areas" => $dataTotal,
            "total_mes" => $cantidadMeses
        ];

        return $dataValija;
    }

    public function pdfReportStationery($start_date, $end_date)
    {
        $queryStatus = $this->db->query(" SELECT
                                            Departamento,
                                            SUM(CASE WHEN Estado = 'Autorizada' THEN cantidad ELSE 0 END) AS Autorizada,
                                            SUM(CASE WHEN Estado = 'Pendiente' THEN cantidad ELSE 0 END) AS Pendiente,
                                            SUM(CASE WHEN Estado = 'Rechazada' THEN cantidad ELSE 0 END) AS Rechazada,
                                            SUM(cantidad) AS 'suma_total'
                                        FROM (
                                            SELECT
                                                CASE
                                                    WHEN cost_center IN (6401,6403,6404,6406,6407,6409,6411,6420,6421,6516,6408,6402,6405,6333) THEN 'Comercial'
                                                    WHEN cost_center IN (6502,6504,6506,6507,6508,6510,6505,6514,6503,6501,6513) THEN 'Finanzas'
                                                    WHEN cost_center IN (6320,6321,6324,6322,6323) THEN 'Calidad / HSE'
                                                    WHEN cost_center IN (6509,6520) THEN 'TI'
                                                    WHEN cost_center IN (6413) THEN 'Villahermosa'
                                                    WHEN cost_center IN (6521,6524) THEN 'Corporativo'                                                 
                                                    WHEN cost_center IN (6306,6332,6302,6330,6304,6307,6303,6430,6341,6331,6102,6312,6102,6106,6301,6311,6113,6101,6341,6103,6104,6105,6108,6109,6112,6115,6110,6111) THEN 'Operaciones'
                                                    WHEN cost_center IN (6431,6432,6308,6309,6305) THEN 'Logistica / Almacenes'
                                                    -- Agrega más casos según tu lista de departamentos
                                                    ELSE 'Desconocido'
                                                END AS Departamento,
                                                CASE
                                                        WHEN request_status = 1 THEN 'Pendiente'
                                                    WHEN request_status = 2 OR request_status = 3 THEN 'Autorizada'
                                                    WHEN request_status = 4 THEN 'Rechazada'
                                                    ELSE 'Estado Desconocido'
                                                END AS Estado,
                                                COUNT(*) AS cantidad
                                            FROM
                                            tbl_stationery_requests
                                            WHERE
                                                request_status IN (1, 2, 3, 4)
                                                AND created_at BETWEEN '$start_date' AND '$end_date'
                                            GROUP BY
                                                cost_center,
                                                request_status
                                        ) AS Subconsulta
                                        GROUP BY
                                            Departamento
                                        WITH ROLLUP;");
        $dataTotal =  $queryStatus->getResult();

        $query = $this->db->query("SELECT
                                        cost_center as area_operativa,
                                        COUNT(id_request) AS solicitudes,
                                        SUM(CASE WHEN `request_status` = 2 OR `request_status` = 3 THEN 1 ELSE 0 END) AS atendida,
                                        MONTH(created_at) AS Mes 
                                    FROM
                                        tbl_stationery_requests 
                                    WHERE
                                        request_status IN (1, 2, 3, 4) 
                                        AND created_at BETWEEN '$start_date' AND '$end_date' -- Ajuste en el rango de fechas
                                    GROUP BY
                                        departament, Mes ORDER BY departament ASC;
                                    ");
        $data =  $query->getResult();
        // Suponiendo que $result es el conjunto de resultados de tu consulta
        $resultados = array();

        // Arrays para almacenar totales por mes
        $totalesSolicitadas = array_fill(1, 12, 0);
        $totalesAtendidas = array_fill(1, 12, 0);

        // Arrays para almacenar totales por área operativa
        $totalesSolicitudesPorArea = array();
        $totalesAtendidasPorArea = array();

        // Arrays para almacenar totales por área operativa
        $totalSolicitudesPorArea = array();
        // $totalAtendidasPorArea = array();

        // Definir un array asociativo que mapea áreas operativas a departamentos
        $departamentos = [
            6413 => 'Almacen Villahermosa',
            6401 => 'Comercial',
            6403 => 'Comercial',
            6404 => 'Comercial',
            6406 => 'Comercial',
            6407 => 'Comercial',
            6409 => 'Comercial',
            6411 => 'Comercial',
            6420 => 'Comercial',
            6502 => 'Finanzas',
            6504 => 'Finanzas',
            6506 => 'Finanzas',
            6507 => 'Finanzas',
            6508 => 'Finanzas',
            6510 => 'Finanzas',
            6505 => 'Finanzas',
            6320 => 'Calidad / HSE',
            6321 => 'Calidad / HSE',
            6324 => 'Calidad / HSE',
            6306 => 'Operaciones',
            6405 => 'Comercial',
            6332 => 'Operaciones',
            6509 => 'TI',
            6520 => 'TI',
            6521 => 'Corporativo',
            6431 => 'Logistica / Almacenes',
            6432 => 'Logistica / Almacenes',
            6302 => 'Operaciones',
            6330 => 'Operaciones',
            6304 => 'Operaciones',
            6307 => 'Operaciones',
            6421 => 'Comercial',
            6514 => 'Finanzas',
            6308 => 'Logistica / Almacenes',
            6516 => 'Comercial',
            6503 => 'Finanzas',
            6303 => 'Operaciones',
            6430 => 'Operaciones',
            6341 => 'Operaciones',
            6309 => 'Logistica / Almacenes',
            6322 => 'Calidad / HSE',
            6331 => 'Operaciones',
            6102 => 'Operaciones',
            6323 => 'Calidad / HSE',
            6312 => 'Operaciones',
            6102 => 'Operaciones',
            6106 => 'Operaciones',
            6301 => 'Operaciones',
            6311 => 'Operaciones',
            6113 => 'Operaciones',
            6501 => 'Finanzas',
            6408 => 'Comercial',
            6513 => 'Finanzas',
            6101 => 'Operaciones',
            6341 => 'Operaciones',
            6305 => 'Logistica / Almacenes',
            6402 => 'Comercial',
            6103 => 'Operaciones',
            6104 => 'Operaciones',
            6105 => 'Operaciones',
            6108 => 'Operaciones',
            6109 => 'Operaciones',
            6112 => 'Operaciones',
            6115 => 'Operaciones',
            6110 => 'Operaciones',
            6111 => 'Operaciones',
            6333 => 'Comercial',
            6524 => 'Corporativo'
        ];


        // Inicializar totales por área operativa
        foreach ($departamentos as $area_operativa => $departamento) {
            $totalesSolicitudesPorArea[$departamento] = array_fill(1, 12, 0);
            $totalesAtendidasPorArea[$departamento] = array_fill(1, 12, 0);

            $totalSolicitudesPorArea[$departamento] = 0;
            // $totalAtendidasPorArea[$departamento] = 0;
        }


        foreach ($data as $row) {

            $area_operativa = $row->area_operativa;
            $mes = $row->Mes;
            $solicitadas = $row->solicitudes;
            $atendidas = $row->atendida;
            /**
             ** se agruparann por Area para una visualizacion mas facil.
             */

            // Acumular totales por mes
            $totalesSolicitadas[$mes] += $solicitadas;
            $totalesAtendidas[$mes] += $atendidas;

            // Acumular totales por área operativa y mes
            $departamento = $departamentos[$area_operativa] ?? 'Depto. Desconocido';

            $totalesSolicitudesPorArea[$departamento][$mes] += $solicitadas;
            $totalesAtendidasPorArea[$departamento][$mes] += $atendidas;

            $totalSolicitudesPorArea[$departamento] += $solicitadas;
            //$totalAtendidasPorArea[$departamento] += $atendidas;
        }

        // Filtrar departamentos que tengan cero solicitudes y cero atendidas
        $totalSolicitudesPorArea = array_filter($totalSolicitudesPorArea, function ($value) {
            return $value > 0;
        });



        /*  $totalAtendidasPorArea = array_filter($totalAtendidasPorArea, function ($value) {
            return $value > 0;
        }); */

        // Construir la tabla de resultados
        $resultados = array();
        foreach ($departamentos as $area_operativa => $departamento) {
            // Verificar si hay información para este departamento
            $hayInformacion = array_sum($totalesSolicitudesPorArea[$departamento]) > 0;

            if ($hayInformacion) {
                $resultados[$departamento] = array();
                foreach ($totalesSolicitudesPorArea[$departamento] as $mes => $totalSolicitudes) {
                    $totalAtendidas = $totalesAtendidasPorArea[$departamento][$mes];
                    $totalPorcentaje = ($totalSolicitudes != 0) ? round(($totalAtendidas / $totalSolicitudes) * 100, 0) : 0;
                    $resultados[$departamento][$mes] = "$totalSolicitudes, $totalAtendidas, $totalPorcentaje%";
                }
            }
        }

        //var_dump($resultados);
        // Sumar los datos
        $sumaSolicitudes = array_sum($totalSolicitudesPorArea);

        $dataStationery = [
            "resultados" => $resultados,
            "total_solicitudes" => $totalesSolicitadas,
            "total_atendidas" => $totalesAtendidas,
            "total_areas" => $totalSolicitudesPorArea,
            "solicitudes_total" => $sumaSolicitudes,
            "totales_areas" => $dataTotal
        ];

        return $dataStationery;
    }


    public function pdfReportTickets($start_date, $end_date)
    {
        $queryStatus = $this->db->query(" SELECT
                                            Departamento,
                                            SUM(CASE WHEN Estado = 'Atendida' THEN cantidad ELSE 0 END) AS Autorizada,
                                            SUM(CASE WHEN Estado = 'Pendiente' THEN cantidad ELSE 0 END) AS Pendiente,
                                            SUM(CASE WHEN Estado = 'Rechazada' THEN cantidad ELSE 0 END) AS Rechazada,
                                            SUM(cantidad) AS 'suma_total'
                                        FROM (
                                            SELECT
                                                CASE
                                                    WHEN cost_center IN (6401,6403,6404,6406,6407,6409,6411,6420,6421,6516,6408,6402,6405,6333) THEN 'Comercial'
                                                    WHEN cost_center IN (6502,6504,6506,6507,6508,6510,6505,6514,6503,6501,6513) THEN 'Finanzas'
                                                    WHEN cost_center IN (6320,6321,6324,6322,6323) THEN 'Calidad / HSE'
                                                    WHEN cost_center IN (6509,6520) THEN 'TI'
                                                    WHEN cost_center IN (6413) THEN 'Villahermosa'
                                                    WHEN cost_center IN (6521,6524) THEN 'Corporativo'                                                 
                                                    WHEN cost_center IN (6306,6332,6302,6330,6304,6307,6303,6430,6341,6331,6102,6312,6102,6106,6301,6311,6113,6101,6341,6103,6104,6105,6108,6109,6112,6115,6110,6111) THEN 'Operaciones'
                                                    WHEN cost_center IN (6431,6432,6308,6309,6305) THEN 'Logistica / Almacenes'
                                                    -- Agrega más casos según tu lista de departamentos
                                                    ELSE 'Desconocido'
                                                END AS Departamento,
                                                CASE
                                                        WHEN Ticket_EstatusId = 1 OR  Ticket_EstatusId = 2 THEN 'Pendiente'
                                                        WHEN Ticket_EstatusId = 3 OR  Ticket_EstatusId = 5 THEN 'Atendida'
                                                    WHEN Ticket_EstatusId = 4 THEN 'Rechazada'
                                                    ELSE 'Estado Desconocido'
                                                END AS Estado,
                                                COUNT(*) AS cantidad
                                            FROM
                                            tbl_tickets_request
                                            WHERE
                                            Ticket_EstatusId IN (1, 2, 3, 4, 5)
                                                AND Ticket_FechaCreacion BETWEEN '$start_date' AND '$end_date'
                                            GROUP BY
                                                cost_center,
                                                Ticket_EstatusId
                                        ) AS Subconsulta
                                        GROUP BY
                                            Departamento
                                        WITH ROLLUP;");
        $dataTotal =  $queryStatus->getResult();

        $query = $this->db->query("SELECT
                                    cost_center AS area_operativa,
                                    COUNT(TicketId) AS solicitudes,
                                    SUM(CASE WHEN Ticket_EstatusId = 3 OR  Ticket_EstatusId = 5 THEN 1 ELSE 0 END) AS atendida,
                                    MONTH(Ticket_FechaCreacion) AS Mes 
                                FROM
                                    tbl_tickets_request 
                                WHERE
                                Ticket_EstatusId IN (1, 2, 3, 4, 5) 
                                AND Ticket_FechaCreacion BETWEEN '$start_date' AND '$end_date' -- Ajuste en el rango de fechas
								AND id_depto IS NOT NULL  -- Agregando condición para excluir registros sin id_depto
                                GROUP BY
                                    Mes,id_depto  ORDER BY Mes ASC;
                                    ");
        $data =  $query->getResult();
        // Suponiendo que $result es el conjunto de resultados de tu consulta
        $resultados = array();

        // Arrays para almacenar totales por mes
        $totalesSolicitadas = array_fill(1, 12, 0);
        $totalesAtendidas = array_fill(1, 12, 0);

        // Arrays para almacenar totales por área operativa
        $totalesSolicitudesPorArea = array();
        $totalesAtendidasPorArea = array();

        // Arrays para almacenar totales por área operativa
        $totalSolicitudesPorArea = array();
        // $totalAtendidasPorArea = array();

        // Definir un array asociativo que mapea áreas operativas a departamentos
        $departamentos = [
            6413 => 'Almacen Villahermosa',
            6401 => 'Comercial',
            6403 => 'Comercial',
            6404 => 'Comercial',
            6406 => 'Comercial',
            6407 => 'Comercial',
            6409 => 'Comercial',
            6411 => 'Comercial',
            6420 => 'Comercial',
            6502 => 'Finanzas',
            6504 => 'Finanzas',
            6506 => 'Finanzas',
            6507 => 'Finanzas',
            6508 => 'Finanzas',
            6510 => 'Finanzas',
            6505 => 'Finanzas',
            6320 => 'Calidad / HSE',
            6321 => 'Calidad / HSE',
            6324 => 'Calidad / HSE',
            6306 => 'Operaciones',
            6405 => 'Comercial',
            6332 => 'Operaciones',
            6509 => 'TI',
            6520 => 'TI',
            6521 => 'Corporativo',
            6431 => 'Logistica / Almacenes',
            6432 => 'Logistica / Almacenes',
            6302 => 'Operaciones',
            6330 => 'Operaciones',
            6304 => 'Operaciones',
            6307 => 'Operaciones',
            6421 => 'Comercial',
            6514 => 'Finanzas',
            6308 => 'Logistica / Almacenes',
            6516 => 'Comercial',
            6503 => 'Finanzas',
            6303 => 'Operaciones',
            6430 => 'Operaciones',
            6341 => 'Operaciones',
            6309 => 'Logistica / Almacenes',
            6322 => 'Calidad / HSE',
            6331 => 'Operaciones',
            6102 => 'Operaciones',
            6323 => 'Calidad / HSE',
            6312 => 'Operaciones',
            6102 => 'Operaciones',
            6106 => 'Operaciones',
            6301 => 'Operaciones',
            6311 => 'Operaciones',
            6113 => 'Operaciones',
            6501 => 'Finanzas',
            6408 => 'Comercial',
            6513 => 'Finanzas',
            6101 => 'Operaciones',
            6341 => 'Operaciones',
            6305 => 'Logistica / Almacenes',
            6402 => 'Comercial',
            6103 => 'Operaciones',
            6104 => 'Operaciones',
            6105 => 'Operaciones',
            6108 => 'Operaciones',
            6109 => 'Operaciones',
            6112 => 'Operaciones',
            6115 => 'Operaciones',
            6110 => 'Operaciones',
            6111 => 'Operaciones',
            6333 => 'Comercial',
            6524 => 'Corporativo'
        ];


        // Inicializar totales por área operativa
        foreach ($departamentos as $area_operativa => $departamento) {
            $totalesSolicitudesPorArea[$departamento] = array_fill(1, 12, 0);
            $totalesAtendidasPorArea[$departamento] = array_fill(1, 12, 0);

            $totalSolicitudesPorArea[$departamento] = 0;
            // $totalAtendidasPorArea[$departamento] = 0;
        }


        foreach ($data as $row) {

            $area_operativa = $row->area_operativa;
            $mes = $row->Mes;
            $solicitadas = $row->solicitudes;
            $atendidas = $row->atendida;
            /**
             ** se agruparann por Area para una visualizacion mas facil.
             */

            // Acumular totales por mes
            $totalesSolicitadas[$mes] += $solicitadas;
            $totalesAtendidas[$mes] += $atendidas;

            // Acumular totales por área operativa y mes
            $departamento = $departamentos[$area_operativa] ?? 'Depto. Desconocido';

            $totalesSolicitudesPorArea[$departamento][$mes] += $solicitadas;
            $totalesAtendidasPorArea[$departamento][$mes] += $atendidas;

            $totalSolicitudesPorArea[$departamento] += $solicitadas;
            //$totalAtendidasPorArea[$departamento] += $atendidas;
        }

        // Filtrar departamentos que tengan cero solicitudes y cero atendidas
        $totalSolicitudesPorArea = array_filter($totalSolicitudesPorArea, function ($value) {
            return $value > 0;
        });



        /*  $totalAtendidasPorArea = array_filter($totalAtendidasPorArea, function ($value) {
            return $value > 0;
        }); */

        // Construir la tabla de resultados
        $resultados = array();
        foreach ($departamentos as $area_operativa => $departamento) {
            // Verificar si hay información para este departamento
            $hayInformacion = array_sum($totalesSolicitudesPorArea[$departamento]) > 0;

            if ($hayInformacion) {
                $resultados[$departamento] = array();
                foreach ($totalesSolicitudesPorArea[$departamento] as $mes => $totalSolicitudes) {
                    $totalAtendidas = $totalesAtendidasPorArea[$departamento][$mes];
                    $totalPorcentaje = ($totalSolicitudes != 0) ? round(($totalAtendidas / $totalSolicitudes) * 100, 0) : 0;
                    $resultados[$departamento][$mes] = "$totalSolicitudes, $totalAtendidas, $totalPorcentaje%";
                }
            }
        }

        //var_dump($resultados);
        // Sumar los datos
        $sumaSolicitudes = array_sum($totalSolicitudesPorArea);

        $dataTickets = [
            "resultados" => $resultados,
            "total_solicitudes" => $totalesSolicitadas,
            "total_atendidas" => $totalesAtendidas,
            "total_areas" => $totalSolicitudesPorArea,
            "solicitudes_total" => $sumaSolicitudes,
            "totales_areas" => $dataTotal
        ];
        return $dataTickets;
    }

    public function pdfReportExpenses($start_date, $end_date)
    {

        $query = $this->db->query("SELECT
                                    Departamento,
                                    clave_cost_center,
                                    SUM(card_confirm_money) AS suma_total,
                                    SUM(verification_money) AS suma_comprobado,
                                    (SUM(card_confirm_money) - SUM(verification_money)) AS diferencia
                                FROM
                                    (
                                    SELECT
                                        clave_cost_center,
                                        CASE
                                            WHEN clave_cost_center IN (6401, 6403, 6404, 6406, 6407, 6409, 6411, 6420, 6421, 6516, 6408, 6402, 6333) THEN 'Comercial'
                                            WHEN clave_cost_center IN (6502, 6504, 6506, 6507, 6508, 6510, 6505, 6514, 6503, 6501, 6513) THEN 'Finanzas'
                                            WHEN clave_cost_center IN (6320, 6321, 6324, 6322, 6323) THEN 'Calidad / HSE'
                                            WHEN clave_cost_center IN (6509, 6520) THEN 'TI'
                                            WHEN clave_cost_center IN (6521, 6524) THEN 'Corporativo'
                                            WHEN clave_cost_center IN (6413) THEN 'Almacen Villahermosa'
                                            WHEN clave_cost_center IN (
                                                6306, 6405, 6332, 6302, 6330, 6304, 6307, 6303, 6430, 6341, 6331, 6102, 6312, 6102, 6106, 6301, 6311,
                                                6113, 6101, 6341, 6103, 6104, 6105, 6108, 6109, 6112, 6115, 6110, 6111
                                            ) THEN 'Operaciones'
                                            WHEN clave_cost_center IN (6431, 6432, 6308, 6309, 6305) THEN 'Logistica / Almacenes'
                                            ELSE 'Desconocido'
                                        END AS Departamento,
                                        card_confirm_money,
                                        verification_money
                                       -- COUNT(*) AS algo
                                    FROM
                                        tbl_services_request_expenses
                                    WHERE
                                    day_star_expenses BETWEEN '$start_date'
                                        AND '$end_date'
                                        AND active_status = 1
                                        AND card_confirm_money <> 0.00 
                                    GROUP BY
                                        clave_cost_center
                                    ) AS Subconsulta
                                GROUP BY
                                    Departamento WITH ROLLUP;
                                ");

        return   $query->getResult();
    }
    public function pdfReportTravelMes($start_date, $end_date)
    {

        $query = $this->db->query("SELECT
                                    MONTHNAME( day_star_travel ) AS mes,
                                CASE
                                        
                                        WHEN clave_cost_center IN ( 6401, 6403, 6404, 6406, 6407, 6409, 6411, 6420, 6421, 6516, 6408, 6402, 6333 ) THEN
                                        'Comercial' 
                                        WHEN clave_cost_center IN ( 6502, 6504, 6506, 6507, 6508, 6510, 6505, 6514, 6503, 6501, 6513 ) THEN
                                        'Finanzas' 
                                        WHEN clave_cost_center IN ( 6320, 6321, 6324, 6322, 6323 ) THEN
                                        'Calidad / HSE' 
                                        WHEN clave_cost_center IN ( 6509, 6520 ) THEN
                                        'TI' 
                                        WHEN clave_cost_center IN ( 6521, 6524 ) THEN
                                        'Corporativo' 
                                        WHEN clave_cost_center IN ( 6413 ) THEN
                                        'Almacen Villahermosa' 
                                        WHEN clave_cost_center IN (
                                        6306,6405,6332,6302,6330,6304,6307,6303,6430,6341,6331,6102,6312,6102,6106,6301,6311,
                                        6113,6101,6341,6103,6104,6105,6108,6109,6112,6115,6110,6111 
                                            ) THEN
                                            'Operaciones' 
                                            WHEN clave_cost_center IN ( 6431, 6432, 6308, 6309, 6305 ) THEN
                                            'Logistica / Almacenes' ELSE 'Desconocido' 
                                        END AS Departamento,
                                        SUM( card_confirm_money ) AS suma_card_confirm_money,
                                        SUM( verification_money ) AS suma_verification_money,
                                        clave_cost_center
                                    FROM
                                        tbl_services_request_travel 
                                    WHERE
                                        day_star_travel BETWEEN '$start_date' 
                                        AND '$end_date' 
                                        AND active_status = 1 
                                        AND card_confirm_money <> 0.00 
                                    GROUP BY
                                        mes,
                                        Departamento 
                                    ORDER BY
                                    mes DESC,
                                    Departamento;");
        $data = $query->getResult();
    }
    public function pdfAccidents($start_date, $end_date)
    {
        $query = $this->db->query(" SELECT
                                    YEAR(created_at) AS anio,
                                    MONTH(created_at) AS mes,
                                    COUNT(id_request) AS total_registros
                                FROM
                                    tbl_medical_request
                                        WHERE
                                        created_at BETWEEN '$start_date' 
                                        AND '$end_date' 
                                        AND motive = 'Accidente de trabajo'
                                        AND active_status = 1
                                GROUP BY
                                    YEAR(created_at), MONTH(created_at)
                                ORDER BY
                                    mes,anio;");
        return   $query->getResult();
    }
    public function pdfReportTravels($start_date, $end_date)
    {

        $query = $this->db->query("SELECT
                                    Departamento,
                                    clave_cost_center,
                                    SUM(card_confirm_money) AS suma_total,
                                    SUM(verification_money) AS suma_comprobado,
                                    (SUM(card_confirm_money) - SUM(verification_money)) AS diferencia
                                FROM
                                    (
                                    SELECT
                                        clave_cost_center,
                                        CASE
                                            WHEN clave_cost_center IN (6401, 6403, 6404, 6406, 6407, 6409, 6411, 6420, 6421, 6516, 6408, 6402, 6333) THEN 'Comercial'
                                            WHEN clave_cost_center IN (6502, 6504, 6506, 6507, 6508, 6510, 6505, 6514, 6503, 6501, 6513) THEN 'Finanzas'
                                            WHEN clave_cost_center IN (6320, 6321, 6324, 6322, 6323) THEN 'Calidad / HSE'
                                            WHEN clave_cost_center IN (6509, 6520) THEN 'TI'
                                            WHEN clave_cost_center IN (6521, 6524) THEN 'Corporativo'
                                            WHEN clave_cost_center IN (6413) THEN 'Almacen Villahermosa'
                                            WHEN clave_cost_center IN (
                                                6306, 6405, 6332, 6302, 6330, 6304, 6307, 6303, 6430, 6341, 6331, 6102, 6312, 6102, 6106, 6301, 6311,
                                                6113, 6101, 6341, 6103, 6104, 6105, 6108, 6109, 6112, 6115, 6110, 6111
                                            ) THEN 'Operaciones'
                                            WHEN clave_cost_center IN (6431, 6432, 6308, 6309, 6305) THEN 'Logistica / Almacenes'
                                            ELSE 'Desconocido'
                                        END AS Departamento,
                                        card_confirm_money,
                                        verification_money
                                      -- COUNT(*) AS algo
                                    FROM
                                        tbl_services_request_travel
                                    WHERE
                                        day_star_travel BETWEEN '$start_date'
                                        AND '$end_date'
                                        AND active_status = 1
                                        AND card_confirm_money <> 0.00 
                                    GROUP BY
                                        clave_cost_center
                                    ) AS Subconsulta
                                GROUP BY
                                    Departamento WITH ROLLUP;
    
                                ");

        return   $query->getResult();
    }

    public function pdfAdmitionUser($start_date, $end_date)
    {
        $query = $this->db->query("SELECT
                                        YEAR(date_admission) AS anio,
                                        MONTH(date_admission) AS mes,
                                        COUNT(*) AS cantidad_usuarios,
                                            gender
                                    FROM
                                        tbl_users
                                    WHERE
                                        -- YEAR(date_admission) = 2024
                                            date_admission
                                            BETWEEN '$start_date' AND '$end_date' AND active_status = 1
                                    GROUP BY
                                    anio, mes,gender;");

        return  $query->getResult();
    }

    public function pdfReportTicketsSg($start_date, $end_date)
    {
        $queryStatus = $this->db->query(" SELECT
                                            Departamento,
                                            SUM(CASE WHEN Estado = 'Atendida' THEN cantidad ELSE 0 END) AS Autorizada,
                                            SUM(CASE WHEN Estado = 'Pendiente' THEN cantidad ELSE 0 END) AS Pendiente,
                                            SUM(CASE WHEN Estado = 'Rechazada' THEN cantidad ELSE 0 END) AS Rechazada,
                                            SUM(cantidad) AS 'suma_total'
                                        FROM (
                                            SELECT
                                                CASE
                                                    WHEN cost_center IN (6401,6403,6404,6406,6407,6409,6411,6420,6421,6516,6408,6402,6405,6333) THEN 'Comercial'
                                                    WHEN cost_center IN (6502,6504,6506,6507,6508,6510,6505,6514,6503,6501,6513) THEN 'Finanzas'
                                                    WHEN cost_center IN (6320,6321,6324,6322,6323) THEN 'Calidad / HSE'
                                                    WHEN cost_center IN (6509,6520) THEN 'TI'
                                                    WHEN cost_center IN (6413) THEN 'Villahermosa'
                                                    WHEN cost_center IN (6521,6524) THEN 'Corporativo'                                                 
                                                    WHEN cost_center IN (6306,6332,6302,6330,6304,6307,6303,6430,6341,6331,6102,6312,6102,6106,6301,6311,6113,6101,6341,6103,6104,6105,6108,6109,6112,6115,6110,6111) THEN 'Operaciones'
                                                    WHEN cost_center IN (6431,6432,6308,6309,6305) THEN 'Logistica / Almacenes'
                                                    -- Agrega más casos según tu lista de departamentos
                                                    ELSE 'Desconocido'
                                                END AS Departamento,
                                                CASE
                                                        WHEN Ticket_EstatusId = 1 OR  Ticket_EstatusId = 2 THEN 'Pendiente'
                                                        WHEN Ticket_EstatusId = 3 OR  Ticket_EstatusId = 5 THEN 'Atendida'
                                                    WHEN Ticket_EstatusId = 4 THEN 'Rechazada'
                                                    ELSE 'Estado Desconocido'
                                                END AS Estado,
                                                COUNT(*) AS cantidad
                                            FROM
                                            tbl_tickets_service_request
                                            WHERE
                                            Ticket_EstatusId IN (1, 2, 3, 4, 5)
                                                AND Ticket_FechaCreacion BETWEEN '$start_date' AND '$end_date'
                                            GROUP BY
                                                cost_center,
                                                Ticket_EstatusId
                                        ) AS Subconsulta
                                        GROUP BY
                                            Departamento
                                        WITH ROLLUP;");
        $dataTotal =  $queryStatus->getResult();

        $query = $this->db->query("SELECT
                                    cost_center AS area_operativa,
                                    COUNT(TicketId) AS solicitudes,
                                    SUM(CASE WHEN Ticket_EstatusId = 3 OR  Ticket_EstatusId = 5 THEN 1 ELSE 0 END) AS atendida,
                                    MONTH(Ticket_FechaCreacion) AS Mes 
                                FROM
                                    tbl_tickets_service_request 
                                WHERE
                                Ticket_EstatusId IN (1, 2, 3, 4, 5) 
                                AND Ticket_FechaCreacion BETWEEN '$start_date' AND '$end_date' -- Ajuste en el rango de fechas
								AND id_depto IS NOT NULL  -- Agregando condición para excluir registros sin id_depto
                                GROUP BY
                                    Mes,id_depto  ORDER BY Mes ASC;
                                    ");
        $data =  $query->getResult();
        // Suponiendo que $result es el conjunto de resultados de tu consulta
        $resultados = array();

        // Arrays para almacenar totales por mes
        $totalesSolicitadas = array_fill(1, 12, 0);
        $totalesAtendidas = array_fill(1, 12, 0);

        // Arrays para almacenar totales por área operativa
        $totalesSolicitudesPorArea = array();
        $totalesAtendidasPorArea = array();

        // Arrays para almacenar totales por área operativa
        $totalSolicitudesPorArea = array();
        // $totalAtendidasPorArea = array();

        // Definir un array asociativo que mapea áreas operativas a departamentos
        $departamentos = [
            6413 => 'Almacen Villahermosa',
            6401 => 'Comercial',
            6403 => 'Comercial',
            6404 => 'Comercial',
            6406 => 'Comercial',
            6407 => 'Comercial',
            6409 => 'Comercial',
            6411 => 'Comercial',
            6420 => 'Comercial',
            6502 => 'Finanzas',
            6504 => 'Finanzas',
            6506 => 'Finanzas',
            6507 => 'Finanzas',
            6508 => 'Finanzas',
            6510 => 'Finanzas',
            6505 => 'Finanzas',
            6320 => 'Calidad / HSE',
            6321 => 'Calidad / HSE',
            6324 => 'Calidad / HSE',
            6306 => 'Operaciones',
            6405 => 'Comercial',
            6332 => 'Operaciones',
            6509 => 'TI',
            6520 => 'TI',
            6521 => 'Corporativo',
            6431 => 'Logistica / Almacenes',
            6432 => 'Logistica / Almacenes',
            6302 => 'Operaciones',
            6330 => 'Operaciones',
            6304 => 'Operaciones',
            6307 => 'Operaciones',
            6421 => 'Comercial',
            6514 => 'Finanzas',
            6308 => 'Logistica / Almacenes',
            6516 => 'Comercial',
            6503 => 'Finanzas',
            6303 => 'Operaciones',
            6430 => 'Operaciones',
            6341 => 'Operaciones',
            6309 => 'Logistica / Almacenes',
            6322 => 'Calidad / HSE',
            6331 => 'Operaciones',
            6102 => 'Operaciones',
            6323 => 'Calidad / HSE',
            6312 => 'Operaciones',
            6102 => 'Operaciones',
            6106 => 'Operaciones',
            6301 => 'Operaciones',
            6311 => 'Operaciones',
            6113 => 'Operaciones',
            6501 => 'Finanzas',
            6408 => 'Comercial',
            6513 => 'Finanzas',
            6101 => 'Operaciones',
            6341 => 'Operaciones',
            6305 => 'Logistica / Almacenes',
            6402 => 'Comercial',
            6103 => 'Operaciones',
            6104 => 'Operaciones',
            6105 => 'Operaciones',
            6108 => 'Operaciones',
            6109 => 'Operaciones',
            6112 => 'Operaciones',
            6115 => 'Operaciones',
            6110 => 'Operaciones',
            6111 => 'Operaciones',
            6333 => 'Comercial',
            6524 => 'Corporativo'
        ];


        // Inicializar totales por área operativa
        foreach ($departamentos as $area_operativa => $departamento) {
            $totalesSolicitudesPorArea[$departamento] = array_fill(1, 12, 0);
            $totalesAtendidasPorArea[$departamento] = array_fill(1, 12, 0);

            $totalSolicitudesPorArea[$departamento] = 0;
            // $totalAtendidasPorArea[$departamento] = 0;
        }


        foreach ($data as $row) {

            $area_operativa = $row->area_operativa;
            $mes = $row->Mes;
            $solicitadas = $row->solicitudes;
            $atendidas = $row->atendida;
            /**
             ** se agruparann por Area para una visualizacion mas facil.
             */

            // Acumular totales por mes
            $totalesSolicitadas[$mes] += $solicitadas;
            $totalesAtendidas[$mes] += $atendidas;

            // Acumular totales por área operativa y mes
            $departamento = $departamentos[$area_operativa] ?? 'Depto. Desconocido';

            $totalesSolicitudesPorArea[$departamento][$mes] += $solicitadas;
            $totalesAtendidasPorArea[$departamento][$mes] += $atendidas;

            $totalSolicitudesPorArea[$departamento] += $solicitadas;
            //$totalAtendidasPorArea[$departamento] += $atendidas;
        }

        // Filtrar departamentos que tengan cero solicitudes y cero atendidas
        $totalSolicitudesPorArea = array_filter($totalSolicitudesPorArea, function ($value) {
            return $value > 0;
        });



        /*  $totalAtendidasPorArea = array_filter($totalAtendidasPorArea, function ($value) {
            return $value > 0;
        }); */

        // Construir la tabla de resultados
        $resultados = array();
        foreach ($departamentos as $area_operativa => $departamento) {
            // Verificar si hay información para este departamento
            $hayInformacion = array_sum($totalesSolicitudesPorArea[$departamento]) > 0;

            if ($hayInformacion) {
                $resultados[$departamento] = array();
                foreach ($totalesSolicitudesPorArea[$departamento] as $mes => $totalSolicitudes) {
                    $totalAtendidas = $totalesAtendidasPorArea[$departamento][$mes];
                    $totalPorcentaje = ($totalSolicitudes != 0) ? round(($totalAtendidas / $totalSolicitudes) * 100, 0) : 0;
                    $resultados[$departamento][$mes] = "$totalSolicitudes, $totalAtendidas, $totalPorcentaje%";
                }
            }
        }

        //var_dump($resultados);
        // Sumar los datos
        $sumaSolicitudes = array_sum($totalSolicitudesPorArea);

        $dataTicketsSg = [
            "resultados" => $resultados,
            "total_solicitudes" => $totalesSolicitadas,
            "total_atendidas" => $totalesAtendidas,
            "total_areas" => $totalSolicitudesPorArea,
            "solicitudes_total" => $sumaSolicitudes,
            "totales_areas" => $dataTotal
        ];

        return $dataTicketsSg;
    }



    public function pdfReportsServices()
    {


        //$data = json_decode(stripslashes($this->request->getPost('data')));
        $start_date = date("Y-m-d", strtotime($this->request->getPost('start_date')));
        $end_date = date("Y-m-d", strtotime($this->request->getPost('end_date') ));

/* 
          $start_date = "2025-08-01";
        $end_date = "2025-08-31";  
 */
        $dataCoffe = $this->pdfReportCoffe($start_date, $end_date);
        $dataValija = $this->pdfReportValija($start_date, $end_date);
        $dataStationery = $this->pdfReportStationery($start_date, $end_date);
        $dataTickets = $this->pdfReportTickets($start_date, $end_date);
        $dataTicketsSg = $this->pdfReportTicketsSg($start_date, $end_date);
        $dataExpenses = $this->pdfReportExpenses($start_date, $end_date);
        $dataTravels = $this->pdfReportTravels($start_date, $end_date);
        $dataAccidents = $this->pdfAccidents($start_date, $end_date);


        //  var_dump($dataAccidents);

        $dataRequest = [
            "dataCoffe" => $dataCoffe,
            "dataValija" => $dataValija,
            "dataStationery" => $dataStationery,
            "dataTickets" => $dataTickets,
            "dataTicketsSg" => $dataTicketsSg,
            "dataExpenses" => $dataExpenses,
            "dataTravels" => $dataTravels,
            "dataAccidents" => $dataAccidents
        ];


        ob_start();
        // return view('pdf/pdf_corporativo', $dataRequest);
        $html2 = view('pdf/pdf_corporativo', $dataRequest);

        ob_get_clean();

        $html2pdf = new Html2Pdf('L', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Reportes');

        $html2pdf->writeHTML($html2);
        ob_end_clean();

        // $html2pdf->output('reportes_corporativo.pdf', 'I');

        $numeroAleatorio = uniqid(mt_rand(), true);
        $numeroAleatorio = str_pad($numeroAleatorio, 4, '0', STR_PAD_LEFT);
        $nombre = 'reportes_corporativo_' . $numeroAleatorio . '.pdf';
        $ruta = 'PDF/' . $nombre;
        $html2pdf->output(FCPATH . $ruta, 'F'); // Guarda el PDF en un archivo

        // Devuelve la ruta del archivo PDF
        // $pdfPath = "https://sie.grupowalworth.com/public/PDF/" . $nombre;
        //return json_encode(['success' => true, 'pdfPath' => $pdfPath]);

        // Devuelve la ruta del archivo PDF
        $pdfPath = base_url("public/PDF/{$nombre}");
        $response = ['success' => true, 'pdfPath' => $pdfPath];

        // Eliminar el archivo después de enviar la respuesta
        //unlink($pdfPath);

        return json_encode($response);
    }




    public function Suppliers()
    {
        $builder = $this->db->table('cat_departament');
        $builder->select('id_depto, departament, area');
        $query = $builder->get()->getResultArray();
        foreach ($query as $key => $value) {
            $groups[$value['area']][$value['id_depto']] = $value['departament'];
        }
        $data = [
            "departament" => $groups
        ];
        return ($this->is_logged) ?  view('qhse/suppliers', $data) : redirect()->to(site_url());
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
            $columnTitle = 'A1:R1';
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

            $reporte = $this->db->query("SELECT t1.tipo_permiso, t1.id_es, t1.`user`, t1.fecha_creacion, t1.tipo_empleado, t1.nombre_solicitante,
                t1.departamento, t1.num_nomina, t1.hora_salida, t1.fecha_salida, t1.hora_entrada, t1.fecha_entrada, t1.inasistencia_del, t1.inasistencia_al,
                t1.goce_sueldo, t1.observaciones, t1.estatus,  CONCAT(b.`name`,' ',b.surname) AS authoriza,
                CASE
                    WHEN t1.hora_vigilancia IS NOT NULL AND t1.inasistencia_del = '0000-00-00' THEN
                        TIME_FORMAT(
                            SEC_TO_TIME(
                                (t1.hora_vigilancia * 60 + t1.minutos_vigilancia) * 60
                            ),'%H:%i'
                        ) 
                    WHEN t1.hora_vigilancia IS NULL AND t1.inasistencia_del = '0000-00-00' THEN
                        TIME_FORMAT(
                            SEC_TO_TIME(
                                (t1.hora_permiso * 60 + t1.minuto_permiso) * 60
                            ),'%H:%i'
                        ) 
                    ELSE 'INASISTENCIA'
                END AS tiempo_solicitado,

                TIME_FORMAT(
                    SEC_TO_TIME(
                        SUM(t2.hour_pay * 60 + t2.min_pay) * 60
                    ), '%H:%i'
                ) AS total_tiempo,
         
                 GROUP_CONCAT(
                     DISTINCT CONCAT(
                        DATE_FORMAT(t2.day_to_pay, '%d/%m/%Y')
                    ) SEPARATOR ', '
                ) AS days_pay

                FROM tbl_entry_and_exit_permits AS t1
                    LEFT JOIN (
                        SELECT st1.id_item, st1.hour_pay , st1.min_pay, st1.day_to_pay 
                        FROM tbl_entry_and_exit_permits_time_pay_items AS st1
                        WHERE st1.status_autorize = 2
                            AND st1.active_status = 1 ) 
                    AS t2 ON FIND_IN_SET( t2.id_item, t1.id_pago_tiempo )
                LEFT JOIN tbl_users AS b ON t1.id_usuario_autoriza = b.id_user  
                WHERE t1.active_status = 1 
                    AND t1.fecha_creacion BETWEEN '$data->fechaInicio' AND '$FechaFin'                   
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
            // $sheet->setCellValue('S1', 'TIEMPO DE PERMISO');
            // $sheet->setCellValue('T1', 'TIEMPO DE PAGO');
            // $sheet->setCellValue('U1', 'DIAS DE PAGO');

            foreach ($reporte as $key => $value) {
                $tipo_permiso = ($value->tipo_permiso == null) ? "NO DEFINIDO" : $value->tipo_permiso;
                $tiempoVigilacia = ($value->id_es > 16163) ? ($value->tiempo_solicitado ?? 'NO DEFINIDO') : 'REGISTRO NO EXISTENTE';
                $tiempoPago = ($value->id_es > 16163) ? ($value->total_tiempo ?? 'NO DEFINIDO') : 'REGISTRO NO EXISTENTE';
                $fechasPago = ($value->id_es > 16163) ? ($value->days_pay ?? 'NO DEFINIDO') : 'REGISTRO NO EXISTENTE';

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
                // $sheet->setCellValue('S' . $cont, $tiempoVigilacia);
                // $sheet->setCellValue('T' . $cont, $tiempoPago);
                // $sheet->setCellValue('U' . $cont, $fechasPago);
                $cont++;
            }
        } else if ($data->tipoReporte == 2) {
            $coloums = 'A1:R1';
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter($coloums);
            $sheet->getStyle($coloums)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(11)
                ->getColor()
                ->setRGB('FFFFFF');
            $sheet->getStyle($coloums)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle($coloums)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
            $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
            $sheet->getStyle($coloums)->getBorders()->getTop()->setColor($color);
            $sheet->getStyle($coloums)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
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
            WHERE t1.active_status = 1")->getResult();

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

    public function permissionsDate()
    {
        $star_date = $this->request->getPost("star_date");
        $end_date = $this->request->getPost("end_date");
        $serch = $this->request->getPost("serch");
        $option = $this->request->getPost("option");
        if ($serch == 2) {
            $where = "AND num_nomina = $option";
        } else if ($serch == 3) {
            $where = "AND area_operativa = $option";
        } else {
            $where = "";
        }
        $data = $this->db->query("SELECT * FROM tbl_entry_and_exit_permits WHERE active_status = 1 $where AND fecha_creacion  BETWEEN '$star_date' AND '$end_date'")->getResult();
        return ($data) ? json_encode($data) : json_encode(false);
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

    public function reportServicio()
    {
        try {
            $data = json_decode(stripslashes($this->request->getPost('data')));
            $FechaFin = date("Y-m-d", strtotime($data->fechaFin . " +1 day"));
            $NombreArchivo = "reporte_servicios.xlsx";

            $spreadsheet = new Spreadsheet();
            if ($data->tipoReporte == 2) {
                $cont =  2;
                $columnTitle = 'A1:O1';
                $query = $this->db->query("SELECT a.TicketId, b.Actividad_Actividad, a.Ticket_UsuarioCreacion, a.motive_cancel,
                    d.departament, e.payroll_number, a.Ticket_EstatusId, a.Ticket_FechaCreacion, a.Ticket_Descripcion,
                    CONCAT(c.Tecnico_Nombre,' ',c.Tecnico_ApellidoPaterno) AS ing,
                    DATE_FORMAT(a.date_cancel, '%d/%m/%Y - %H:%i') AS cancel_at,  
                    DATE_FORMAT(a.Ticket_FechaCreacion, '%d/%m/%Y - %H:%i') AS created_at, 
                    DATE_FORMAT(a.Ticket_FechaConcluido,'%d/%m/%Y - %H:%i') AS conclud_at,
                    CASE
                        WHEN b.Actividad_AreaId = 1 THEN 
                            'AX ONE' 
                        ELSE 
                            'INFRAESTRUCTURA IT' 
                    END AS area,
                    CASE
                        WHEN a.Ticket_EstatusId = 1 THEN 
                            'NUEVO'
                        WHEN a.Ticket_EstatusId = 2 THEN 
                            'EN PROCESO'
                        WHEN a.Ticket_EstatusId = 3 THEN 
                            'CONCLUIDA'
                        WHEN a.Ticket_EstatusId = 4 THEN 
                            'CANCELADA'
                        WHEN a.Ticket_EstatusId = 5 THEN 
                            'CONCLUIDA'
                    END AS estado,
                    CASE
                        WHEN a.Ticket_PrioridadId = 2 THEN 
                            'BAJA'
                        WHEN a.Ticket_PrioridadId = 3 THEN 
                            'MEDIA'
                        WHEN a.Ticket_PrioridadId = 4 THEN 
                            'ALTA'
                        ELSE 'NO DEFINIDO'
                    END AS prioridad
                    FROM tbl_tickets_request AS a
                        JOIN cat_ticket_actividad AS b ON a.id_activity = b.ActividadId
                        JOIN cat_ticket_tecnico AS c ON a.Ticket_TecnicoId = c.TecnicoId
                        JOIN cat_departament AS d ON a.id_depto = d.id_depto
                        JOIN tbl_users AS e ON a.Ticket_UsuarioCreacionId = e.id_user
                    WHERE a.active_status = 1
                        AND a.Ticket_FechaCreacion BETWEEN '$data->fechaInicio' AND '$FechaFin'
                ORDER BY a.TicketId DESC")->getResult();
                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter($columnTitle);
                $sheet->setTitle("Reporte de TICKETS IT");

                $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(28);

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

                //UBICACION DEL TEXTO
                $sheet->getStyle($columnTitle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
                $sheet->getStyle($columnTitle)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

                //COLOR DE CELDAS
                $spreadsheet->getActiveSheet()->getStyle($columnTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

                // FONT-TEXT
                $sheet->getStyle($columnTitle)->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(10)
                    ->getColor()
                    ->setRGB('FFFFFF');

                // TITULO DE CELDA
                $sheet->setCellValue('A1', 'FOLIO');
                $sheet->setCellValue('B1', 'NÓMBRE');
                $sheet->setCellValue('C1', 'NOMINA');
                $sheet->setCellValue('D1', 'DEPARTAMENTO');
                $sheet->setCellValue('E1', 'FECHA SOLICITUD');
                $sheet->setCellValue('F1', 'ÁREA');
                $sheet->setCellValue('G1', 'INGENIERO');
                $sheet->setCellValue('H1', 'ACTIVIDAD');
                $sheet->setCellValue('I1', 'PRIORIDAD');
                $sheet->setCellValue('J1', 'DESCRIPCIÓN');
                $sheet->setCellValue('K1', 'ESTADO');
                $sheet->setCellValue('L1', 'FECHA CONCLUSIÓN');
                $sheet->setCellValue('M1', 'FECHA CANCELACIÓN');
                $sheet->setCellValue('N1', 'MOTIVO CANCELACIÓN');
                $sheet->setCellValue('O1', 'TIEMPO DEL TICKET DESDE SU CREACIÓN');

                foreach ($query as $value) {
                    $tiempoNuevo = '';
                    if ($value->Ticket_EstatusId == 1 || $value->Ticket_EstatusId == 2) {
                        $diffTime = (new DateTime($value->Ticket_FechaCreacion))->diff(new DateTime());
                        $anio = ($diffTime->y > 0) ? $diffTime->y . ' año(s)' : '';
                        $mes = ($diffTime->m > 0) ? ' ' . $diffTime->m . ' mes(es)' : '';
                        $dias = ($diffTime->d > 0) ? ' ' . $diffTime->d . ' día(s)' : '';
                        $horas = ($diffTime->h > 0) ? ' ' . $diffTime->h . ' hora(s)' : '';
                        $minutos = ($diffTime->i > 0) ? ' ' . $diffTime->i . ' minuto(s)' : '';
                        $tiempoNuevo = $anio . $mes . $dias . $horas . $minutos;
                    }
                    $sheet->setCellValue('A' . $cont, $value->TicketId);
                    $sheet->setCellValue('B' . $cont, $value->Ticket_UsuarioCreacion);
                    $sheet->setCellValue('C' . $cont, $value->payroll_number);
                    $sheet->setCellValue('D' . $cont, $value->departament);
                    $sheet->setCellValue('E' . $cont, $value->created_at);
                    $sheet->setCellValue('F' . $cont, $value->area);
                    $sheet->setCellValue('G' . $cont, $value->ing);
                    $sheet->setCellValue('H' . $cont, strtoupper($value->Actividad_Actividad));
                    $sheet->setCellValue('I' . $cont, $value->prioridad);
                    $sheet->setCellValue('J' . $cont, $value->Ticket_Descripcion);
                    $sheet->setCellValue('K' . $cont, $value->estado);
                    $sheet->setCellValue('L' . $cont, $value->conclud_at);
                    $sheet->setCellValue('M' . $cont, $value->cancel_at);
                    $sheet->setCellValue('N' . $cont, $value->motive_cancel);
                    $sheet->setCellValue('O' . $cont, $tiempoNuevo);
                    $cont++;
                }
            } else if ($data->tipoReporte == 3) {
                $cont = 2;
                $columnTitle = 'A1:J1';
                $query = $this->db->query("SELECT a.id_request, a.cost_center, a.departament, a.payroll_number,
                    CASE
                        WHEN a.request_status = 1 THEN 'Pendiente'
                        WHEN a.request_status = 2 THEN 'Autorizada'
                        WHEN a.request_status = 3 THEN 'Completado'
                        WHEN a.request_status = 4 THEN 'Rechazada'
                        ELSE 'Error'
                    END AS estatus,
                    DATE_FORMAT(a.created_at,'%d/%m/%Y %H:%i') AS created_at,
                    CONCAT(d.`name`,' ',d.surname) AS usuario,
                    c.description_product, b.quantity, b.unit
                    -- GROUP_CONCAT(DISTINCT CONCAT('- ',c.description_product,' ',b.quantity,' ',b.unit) SEPARATOR ',     ') AS concatenado
                    FROM tbl_stationery_requests AS a
                        INNER JOIN tbl_stationery_items as b ON a.id_request = b.id_request
                        INNER JOIN tbl_stationery_inventory as c ON b.id_product = c.id_product
                        JOIN tbl_users AS d ON a.id_user = d.id_user
                        WHERE a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                        AND a.active_status = 1
                    -- GROUP BY a.id_request
                ORDER BY a.id_request DESC")->getResult();
                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter($columnTitle);
                $sheet->setTitle("Reporte de Papeleria");

                $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(28);

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(18);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);

                //UBICACION DEL TEXTO
                $sheet->getStyle($columnTitle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
                $sheet->getStyle($columnTitle)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

                //COLOR DE CELDAS
                $spreadsheet->getActiveSheet()->getStyle($columnTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

                // FONT-TEXT
                $sheet->getStyle($columnTitle)->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(10)
                    ->getColor()
                    ->setRGB('FFFFFF');

                // TITULO DE CELDA
                $sheet->setCellValue('A1', 'FOLIO');
                $sheet->setCellValue('B1', 'NOMBRE');
                $sheet->setCellValue('C1', 'NÓMINA');
                $sheet->setCellValue('D1', 'CENTRO DE COSTOS');
                $sheet->setCellValue('E1', 'DEPARTAMENTO');
                $sheet->setCellValue('F1', 'FECHA SOLICITUD');
                $sheet->setCellValue('G1', 'ESTADO');
                // $sheet->setCellValue('H1', 'LISTADO DE SOLICITUDES');
                $sheet->setCellValue('H1', 'PRODUCTO');
                $sheet->setCellValue('I1', 'CANTIDAD');
                $sheet->setCellValue('J1', 'UNIDAD');

                foreach ($query as $value) {
                    $sheet->setCellValue('A' . $cont, $value->id_request);
                    $sheet->setCellValue('B' . $cont, $value->usuario);
                    $sheet->setCellValue('C' . $cont, $value->payroll_number);
                    $sheet->setCellValue('D' . $cont, $value->cost_center);
                    $sheet->setCellValue('E' . $cont, $value->departament);
                    $sheet->setCellValue('F' . $cont, $value->created_at);
                    $sheet->setCellValue('G' . $cont, $value->estatus);
                    // $sheet->setCellValue('H' . $cont, $value->concatenado);
                    $sheet->setCellValue('H' . $cont, $value->description_product);
                    $sheet->setCellValue('I' . $cont, $value->quantity);
                    $sheet->setCellValue('J' . $cont, $value->unit);
                    $cont++;
                }
            } else if ($data->tipoReporte == 4) {
                $cont = 2;
                $columnTitle = 'A1:N1';
                $query = $this->db->query("SELECT a.id_valija, a.user_name, a.payroll_number, a.departament, a.priority,
                     a.observation, a.job_position,
                    DATE_FORMAT(a.created_at,'%d/%m/%Y - %H:%i') AS created_at_format,
                    DATE_FORMAT(a.date, '%d/%m/%Y') AS date_format,
                    DATE_FORMAT(a.time, '%H:%i') AS time_format,
                    CASE
                        WHEN a.type_of_employee = 1 THEN
                            'ADMINISTRATIVOS'
                        WHEN a.type_of_employee = 1 THEN
                            'SINDICALIZADOS'
                        ELSE
                            'ERROR'
                    END AS type_employe,
                    CASE
                        WHEN a.origin = 'OTRO' THEN
                            another_origin
                        ELSE
                            a.origin
                    END AS origin_txt,
                    CASE
                        WHEN a.destination = 'OTRO' THEN
                            another_origin
                        ELSE
                            a.destination
                    END AS destination_txt,
                    CASE
                        WHEN a.`status` = 1 THEN
                            'PENDIENTE'
                        WHEN a.`status` = 2 THEN
                            'CONCLUIDO'
                        ELSE
                            'ERROR'
                    END AS status_txt
                    FROM tbl_valija_request AS a
                    WHERE a.active_status = 1
                        AND a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                ORDER BY a.id_valija DESC")->getResult();
                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter($columnTitle);
                $sheet->setTitle("Reporte de Valija");

                $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(28);

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(30);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

                //UBICACION DEL TEXTO
                $sheet->getStyle($columnTitle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
                $sheet->getStyle($columnTitle)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

                //COLOR DE CELDAS
                $spreadsheet->getActiveSheet()->getStyle($columnTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

                // FONT-TEXT
                $sheet->getStyle($columnTitle)->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(10)
                    ->getColor()
                    ->setRGB('FFFFFF');

                // TITULO DE CELDA
                $sheet->setCellValue('A1', 'FOLIO');
                $sheet->setCellValue('B1', 'NOMBRE');
                $sheet->setCellValue('C1', 'NÓMINA');
                $sheet->setCellValue('D1', 'TIPO EMPLEADO');
                $sheet->setCellValue('E1', 'DEPARTAMENTO');
                $sheet->setCellValue('F1', 'PUESTO');
                $sheet->setCellValue('G1', 'FECHA CREACIÓN');
                $sheet->setCellValue('H1', 'ORIGEN VALIJA');
                $sheet->setCellValue('I1', 'DESTINO VALIJA');
                $sheet->setCellValue('J1', 'FECHA');
                $sheet->setCellValue('K1', 'HORA');
                $sheet->setCellValue('L1', 'PRIORIDAD');
                $sheet->setCellValue('M1', 'OBSERVACIÓN');
                $sheet->setCellValue('N1', 'ESTADO');

                foreach ($query as $value) {
                    $sheet->setCellValue('A' . $cont, $value->id_valija);
                    $sheet->setCellValue('B' . $cont, $value->user_name);
                    $sheet->setCellValue('C' . $cont, $value->payroll_number);
                    $sheet->setCellValue('D' . $cont, $value->type_employe);
                    $sheet->setCellValue('E' . $cont, $value->job_position);
                    $sheet->setCellValue('F' . $cont, $value->departament);
                    $sheet->setCellValue('G' . $cont, $value->created_at_format);
                    $sheet->setCellValue('H' . $cont, strtoupper($value->origin_txt));
                    $sheet->setCellValue('I' . $cont, strtoupper($value->destination_txt));
                    $sheet->setCellValue('J' . $cont, $value->date_format);
                    $sheet->setCellValue('K' . $cont, $value->time_format);
                    $sheet->setCellValue('L' . $cont, $value->priority);
                    $sheet->setCellValue('M' . $cont, $value->observation);
                    $sheet->setCellValue('N' . $cont, $value->status_txt);
                    $cont++;
                }
            } else if ($data->tipoReporte == 5) {
                $cont =  2;
                $columnTitle = 'A1:R1';
                $query = $this->db->query("SELECT a.id_request,a. observation,
                    a.sending_company, a.sender_name, b.payroll_number,  a.area_operative, b.departament,
                    CONCAT(a.sender_street,', ',
                        a.sender_num,', ',
                        a.sender_col,', ',
                        a.sender_locality,', ',
                        a.sender_state,', ',
                        a.sender_country,', CP: ',
                        a.sender_cp
                    ) AS sender_direction,
                    a.recipient_company,
                    a.recipient_name,
                    CAST(a.recipient_phone AS CHAR(15)) AS recipient_phone_txt,
                    CONCAT(a.recipient_street,', ',
                        a.recipient_num,', ',
                        a.recipient_locality,', ',
                        a.recipient_state,', ',
                        a.recipient_country
                    ) AS recipient_direction,
                    DATE_FORMAT(a.created_at,'%d/%m/%Y - %H:%i') AS created_at_format,
                    DATE_FORMAT(a.autorize_at,'%d/%m/%Y') AS autorize_at_format,
                    COUNT(c.id_item) AS num_packet,
                    CASE
                        WHEN a.shipping_type = 1 THEN 'DÍA SIGUIENTE'
                        WHEN a.shipping_type = 2 THEN 'TERRESTRE'
                        ELSE 'SIN DATO'
                    END AS shipping_type_txt,
                    CASE
                        WHEN a.sure = 1 THEN 'SI'
                        WHEN a.sure = 2 THEN 'NO'
                        ELSE 'SIN DATO'
                    END AS sure_txt,
                    CASE
                        WHEN a.sure = 1 THEN CONCAT('$' ,sure)
                        WHEN a.sure = 2 THEN ''
                        ELSE 'SIN DATO'
                    END AS cost_txt,
                    CASE
                        WHEN a.`status` = 1 THEN 'PENDIENTE'
                        WHEN a.`status` = 2 THEN 'AUTORIZADO'
                        WHEN a.`status` = 3 THEN 'CANCELADO'
                        ELSE 'SIN DATO'
                    END AS status_txt                
                    FROM tbl_packer_request AS a
                        LEFT JOIN (
                            SELECT st1.id_user, st2.departament, st1.payroll_number
                            FROM tbl_users AS st1
                            JOIN cat_departament AS st2 ON st1.id_departament = st2.id_depto
                        ) AS b ON b.id_user = a.id_user
                        LEFT JOIN (
                            SELECT st3.id_request, st3.id_item 
                            FROM tbl_packer_item AS st3 
                            WHERE st3.`status` = 1
                        ) AS c ON a.id_request = c.id_request
                    WHERE a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                        AND a.active_status = 1
                    GROUP BY a.id_request
                ORDER BY a.id_request DESC")->getResult();
                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter($columnTitle);
                $sheet->setTitle("Reporte de Paqueteria");

                $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(28);

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

                //UBICACION DEL TEXTO
                $sheet->getStyle($columnTitle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
                $sheet->getStyle($columnTitle)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

                //COLOR DE CELDAS
                $spreadsheet->getActiveSheet()->getStyle($columnTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

                // FONT-TEXT
                $sheet->getStyle($columnTitle)->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(10)
                    ->getColor()
                    ->setRGB('FFFFFF');

                // TITULO DE CELDA
                $sheet->setCellValue('A1', 'FOLIO');
                $sheet->setCellValue('B1', 'NOMBRE');
                $sheet->setCellValue('C1', 'NÓMINA');
                $sheet->setCellValue('D1', 'CENTRO DE COSTOS');
                $sheet->setCellValue('E1', 'DEPARTAMENTO');
                $sheet->setCellValue('F1', 'FECHA SOLICITUD');
                $sheet->setCellValue('G1', 'EMPRESA EMISORA');
                $sheet->setCellValue('H1', 'DIRECCIÓN EMISORA');
                $sheet->setCellValue('I1', 'EMPRESA RECEPTORA');
                $sheet->setCellValue('J1', 'NOMBRE DE RECEPTOR');
                $sheet->setCellValue('K1', 'TELÉFONO RECEPTORA');
                $sheet->setCellValue('L1', 'DIRECCIÓN RECEPTORA');
                $sheet->setCellValue('M1', 'TIPO DE ENVIÓ');
                $sheet->setCellValue('N1', 'SEGURO');
                $sheet->setCellValue('O1', 'MONTO DE SEGURO');
                $sheet->setCellValue('P1', 'PAQUETES');
                $sheet->setCellValue('Q1', 'ESTADO');
                $sheet->setCellValue('R1', 'FECHA AUTORIZACIÓN');

                foreach ($query as $value) {
                    $sheet->setCellValue('A' . $cont, $value->id_request);
                    $sheet->setCellValue('B' . $cont, $value->sender_name);
                    $sheet->setCellValue('C' . $cont, $value->payroll_number);
                    $sheet->setCellValue('D' . $cont, $value->area_operative);
                    $sheet->setCellValue('E' . $cont, $value->departament);
                    $sheet->setCellValue('F' . $cont, $value->created_at_format);
                    $sheet->setCellValue('G' . $cont, $value->sending_company);
                    $sheet->setCellValue('H' . $cont, strtoupper($value->sender_direction));
                    $sheet->setCellValue('I' . $cont, $value->recipient_company);
                    $sheet->setCellValue('J' . $cont, $value->recipient_name);
                    $sheet->setCellValue('K' . $cont, $value->recipient_phone_txt);
                    $sheet->setCellValue('L' . $cont, strtoupper($value->recipient_direction));
                    $sheet->setCellValue('M' . $cont, $value->shipping_type_txt);
                    $sheet->setCellValue('N' . $cont, $value->sure_txt);
                    $sheet->setCellValue('O' . $cont, $value->cost_txt);
                    $sheet->setCellValue('P' . $cont, $value->num_packet);
                    $sheet->setCellValue('Q' . $cont, $value->status_txt);
                    $sheet->setCellValue('R' . $cont, $value->autorize_at_format);
                    $cont++;
                }
            } else if ($data->tipoReporte == 6) {
                $cont = 3;
                $columnTitle = 'A1:O2';
                $query = $this->db->query("SELECT a.id_request, a.depto, a.motive, a.observation, d.model, a.`name`, a.payroll_number,
                    DATE_FORMAT(c.date,'%d/%m/%Y') AS date_sh,
                    DATE_FORMAT(a.created_at,'%d/%m/%Y - %H:%i') AS created_at,
                    DATE_FORMAT(a.date_autorize,'%d/%m/%Y - %H:%i') AS date_autorize,
                    CONCAT(DATE_FORMAT(c.star_time,' %H:%i'),' - ',DATE_FORMAT(c.end_time,' %H:%i')) AS horas_sh,
                    CONCAT(DATE_FORMAT(b.end_date,'%d/%m/%Y'),' - ',DATE_FORMAT(b.end_datetime,' %H:%i')) AS end_lg,
                    CONCAT(DATE_FORMAT(b.star_date,'%d/%m/%Y'),' - ',DATE_FORMAT(b.star_datetime,' %H:%i')) AS star_lg,
                    CASE
                        WHEN a.type_trip = 1 THEN 'Viaje Corto'
                        WHEN a.type_trip = 2 THEN 'Vieja Prolongado'
                        ELSE 'Error'
                    END AS tipo_viaje,
                    CASE
                        WHEN a.`status` = 1 THEN 'Pendiente'
                        WHEN a.`status` = 2 THEN 'Autorizada'
                        WHEN a.`status` = 3 THEN 'Rechazada'
                        WHEN a.`status` = 4 THEN 'Asignada'
                        ELSE 'Error'
                    END AS estatus             
                    FROM tbl_cars_request AS a
                        LEFT JOIN tbl_cars_extended_trip AS b ON a.id_request = b.id_request
                        LEFT JOIN tbl_cars_short_trip AS c ON a.id_request = c.id_request
                        LEFT JOIN tbl_cars_vehicles AS d ON a.id_cars = d.id_car
                    WHERE a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                        AND a.active_status = 1
                ORDER BY a.id_request ASC")->getResult();
                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A2:O2');
                $sheet->setTitle("Reporte de Vehiculos");

                // $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(28);

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(18);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(50);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

                //UBICACION DEL TEXTO
                $sheet->getStyle($columnTitle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
                $sheet->getStyle($columnTitle)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

                //COLOR DE CELDAS
                $spreadsheet->getActiveSheet()->getStyle($columnTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
                $spreadsheet->getActiveSheet()->getStyle('G1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5C636A');
                $spreadsheet->getActiveSheet()->getStyle('I1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3D4246');

                // FONT-TEXT
                $sheet->getStyle($columnTitle)->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(10)
                    ->getColor()
                    ->setRGB('FFFFFF');

                // TITULO DE CELDA
                $sheet->setCellValue('A1', 'FOLIO')->mergeCells('A1:A2');
                $sheet->setCellValue('B1', 'FECHA SOLICITUD')->mergeCells('B1:B2');
                $sheet->setCellValue('C1', 'NOMBRE')->mergeCells('C1:C2');
                $sheet->setCellValue('D1', 'NÓMINA')->mergeCells('D1:D2');
                $sheet->setCellValue('E1', 'DEPARTAMENTO')->mergeCells('E1:E2');
                $sheet->setCellValue('F1', 'TIPO DE VIAJE')->mergeCells('F1:F2');
                $sheet->setCellValue('G1', 'VIAJE CORTO')->mergeCells('G1:H1');
                $sheet->setCellValue('G2', 'FECHA');
                $sheet->setCellValue('H2', 'HORA INICO - FIN');
                $sheet->setCellValue('I1', 'VIAJE LARGO')->mergeCells('I1:J1');
                $sheet->setCellValue('I2', 'FECHA INICIAL');
                $sheet->setCellValue('J2', 'FECHA FINAL');
                $sheet->setCellValue('K1', 'VEHÍCULO')->mergeCells('K1:K2');
                $sheet->setCellValue('L1', 'MOTIVO')->mergeCells('L1:L2');
                $sheet->setCellValue('M1', 'OBSERVACIÓN')->mergeCells('M1:M2');
                $sheet->setCellValue('N1', 'ESTADO')->mergeCells('N1:N2');
                $sheet->setCellValue('O1', 'FECHA AUTORIZACIÓN')->mergeCells('O1:O2');

                foreach ($query as $value) {
                    $sheet->setCellValue('A' . $cont, $value->id_request);
                    $sheet->setCellValue('B' . $cont, $value->created_at);
                    $sheet->setCellValue('C' . $cont, $value->name);
                    $sheet->setCellValue('D' . $cont, $value->payroll_number);
                    $sheet->setCellValue('E' . $cont, $value->depto);
                    $sheet->setCellValue('F' . $cont, strtoupper($value->tipo_viaje));
                    $sheet->setCellValue('G' . $cont, $value->date_sh);
                    $sheet->setCellValue('H' . $cont, $value->horas_sh);
                    $sheet->setCellValue('I' . $cont, $value->star_lg);
                    $sheet->setCellValue('J' . $cont, $value->end_lg);
                    $sheet->setCellValue('K' . $cont, $value->model ?? 'NO ASIGNADO');
                    $sheet->setCellValue('L' . $cont, $value->motive);
                    $sheet->setCellValue('M' . $cont, $value->observation);
                    $sheet->setCellValue('N' . $cont, strtoupper($value->estatus));
                    $sheet->setCellValue('O' . $cont, $value->date_autorize);
                    $cont++;
                }
            } else if ($data->tipoReporte == 7) {
                $columnTitle = 'A1:M1';
                $cont = 2;
                $query = $this->db->query("SELECT a.id_coffee, a.depto, a.reason_meeting, a.num_person, a.`name`, a.payroll_number,
                    CONCAT(DATE_FORMAT(a.date,'%m/%d/%Y'),' - ',DATE_FORMAT(a.horario,'%H:%i')) AS horario_solicitud,
                    DATE_FORMAT(a.date_authorize,'%d/%m/%Y') AS date_authorize,
                    DATE_FORMAT(a.created_at,'%d/%m/%Y') AS created_at,
                    CASE
                        WHEN a.meeting_room = 1 THEN 'Sala de Consejo'
                        WHEN a.meeting_room = 2 THEN 'Sala de Operaciones'
                        WHEN a.meeting_room = 3 THEN 'Sala de Ingenieria'
                        WHEN a.meeting_room = 4 THEN 'Sala James Walworth'
                        WHEN a.meeting_room = 5 THEN 'Sala de Logistica'
                        WHEN a.meeting_room = 6 THEN 'Sala de Ventas'
                        WHEN a.meeting_room = 7 THEN 'Sala de Calidad'
                        WHEN a.meeting_room = 8 THEN 'Mezzanine (Nave 3)'
                        ELSE 'Error'
                    END AS sala,
                    CASE
                        WHEN a.menu_especial > 0 THEN 'SI'
                        ELSE 'NO'
                    END AS menu_Especial,
                    CASE
                        WHEN a.status = 1 THEN 'Pendiente'
                        WHEN a.status = 2 THEN 'Autorizada'
                        WHEN a.status = 3 THEN 'Rechazada'
                        WHEN a.status = 4 THEN 'Eliminada'
                        WHEN a.status = 6 THEN 'Autoriza Talento'
                        WHEN a.status = 7 THEN 'Rechaza Talento'
                        ELSE 'Error'
                    END AS estatus,
                    GROUP_CONCAT(DISTINCT CONCAT(b.product) SEPARATOR ', ') AS concatenado
                    /*GROUP_CONCAT(DISTINCT CONCAT(c.description) SEPARATOR ', ') AS concatenado1*/
                    FROM tbl_coffee_break AS a
                        LEFT JOIN tbl_coffee_items AS b ON a.id_coffee = b.id_coffee
                        LEFT JOIN tbl_coffee_menu_requisiton AS c ON a.id_coffee = c.id_coffee
                    WHERE a.active_status = 1
                        AND a.status <> 5 AND a.status <> 4 AND a.status <> 7 
                        AND a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                    GROUP BY a.id_coffee
                ORDER BY a.id_coffee DESC")->getResult();

                $sheet = $spreadsheet->getActiveSheet()->setAutoFilter($columnTitle);
                $sheet->setTitle("Reporte de Cafeteria");

                $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(28);

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40);
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);


                //UBICACION DEL TEXTO
                $sheet->getStyle($columnTitle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
                $sheet->getStyle($columnTitle)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

                //COLOR DE CELDAS
                $spreadsheet->getActiveSheet()->getStyle($columnTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

                // FONT-TEXT
                $sheet->getStyle($columnTitle)->getFont()->setBold(true)
                    ->setName('Calibri')
                    ->setSize(10)
                    ->getColor()
                    ->setRGB('FFFFFF');

                // TITULO DE CELDA
                $sheet->setCellValue('A1', 'FOLIO');
                $sheet->setCellValue('B1', 'NOMBRE');
                $sheet->setCellValue('C1', 'NÓMINA');
                $sheet->setCellValue('D1', 'DEPARTAMENTO');
                $sheet->setCellValue('E1', 'FECHA SOLICITUD');
                $sheet->setCellValue('F1', 'FECHA DE JUNTA');
                $sheet->setCellValue('G1', 'SALA');
                $sheet->setCellValue('H1', 'NÚMERO PERSONAS');
                $sheet->setCellValue('I1', 'RAZÓN');
                $sheet->setCellValue('J1', 'MENÚ ESPECIAL');
                $sheet->setCellValue('K1', 'SUMINISTROS SOLICITADOS');
                $sheet->setCellValue('L1', 'ESTADO');
                $sheet->setCellValue('M1', 'FECHA AUTORIZACIÓN');

                foreach ($query as $value) {
                    $sheet->setCellValue('A' . $cont, $value->id_coffee);
                    $sheet->setCellValue('B' . $cont, $value->name);
                    $sheet->setCellValue('C' . $cont, $value->payroll_number);
                    $sheet->setCellValue('D' . $cont, $value->depto);
                    $sheet->setCellValue('E' . $cont, $value->created_at);
                    $sheet->setCellValue('F' . $cont, $value->horario_solicitud);
                    $sheet->setCellValue('G' . $cont, $value->sala);
                    $sheet->setCellValue('H' . $cont, $value->num_person);
                    $sheet->setCellValue('I' . $cont, $value->reason_meeting);
                    $sheet->setCellValue('J' . $cont, $value->menu_Especial);
                    $sheet->setCellValue('K' . $cont, $value->concatenado);
                    $sheet->setCellValue('L' . $cont, $value->estatus);
                    $sheet->setCellValue('M' . $cont, $value->date_authorize);
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
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e;
        }
    }

    public function reportServiciosALL()
    {
        try {
            $data = json_decode(stripslashes($this->request->getPost('data')));
            $FechaFin = date("Y-m-d", strtotime($data->fechaFin . " +1 day"));

            $spreadsheet = new Spreadsheet();
            $NombreArchivo = "reporte_servicios.xlsx";
            $cont =  2;
            $columnTitle = 'A1:Q1';
            $query = $this->db->query("SELECT a.TicketId, b.Actividad_Actividad, a.Ticket_UsuarioCreacion, a.motive_cancel,
                d.departament, e.payroll_number, a.Ticket_EstatusId, a.Ticket_FechaCreacion, a.Ticket_Descripcion,
                CONCAT(c.Tecnico_Nombre,' ',c.Tecnico_ApellidoPaterno) AS ing,
                DATE_FORMAT(a.date_cancel, '%d/%m/%Y - %H:%i') AS cancel_at,  
                DATE_FORMAT(a.Ticket_FechaCreacion, '%d/%m/%Y - %H:%i') AS created_at, 
                DATE_FORMAT(a.Ticket_FechaConcluido,'%d/%m/%Y - %H:%i') AS conclud_at,
                CASE
                    WHEN b.Actividad_AreaId = 1 THEN 
                        'AX ONE' 
                    ELSE 
                        'INFRAESTRUCTURA IT' 
                END AS area,
                CASE
                    WHEN a.Ticket_EstatusId = 1 THEN 
                        'NUEVO'
                    WHEN a.Ticket_EstatusId = 2 THEN 
                        'EN PROCESO'
                    WHEN a.Ticket_EstatusId = 3 THEN 
                        'CONCLUIDA'
                    WHEN a.Ticket_EstatusId = 4 THEN 
                        'CANCELADA'
                    WHEN a.Ticket_EstatusId = 5 THEN 
                        'CONCLUIDA'
                END AS estado,
                CASE
                    WHEN a.Ticket_PrioridadId = 2 THEN 
                        'BAJA'
                    WHEN a.Ticket_PrioridadId = 3 THEN 
                        'MEDIA'
                    WHEN a.Ticket_PrioridadId = 4 THEN 
                        'ALTA'
                    ELSE 'NO DEFINIDO'
                END AS prioridad
                FROM tbl_tickets_request AS a
                    JOIN cat_ticket_actividad AS b ON a.id_activity = b.ActividadId
                    JOIN cat_ticket_tecnico AS c ON a.Ticket_TecnicoId = c.TecnicoId
                    JOIN cat_departament AS d ON a.id_depto = d.id_depto
                    JOIN tbl_users AS e ON a.Ticket_UsuarioCreacionId = e.id_user
                WHERE a.active_status = 1
                    AND a.Ticket_FechaCreacion BETWEEN '$data->fechaInicio' AND '$FechaFin'
            ORDER BY a.TicketId DESC")->getResult();
            $sheet = $spreadsheet->getActiveSheet()->setAutoFilter($columnTitle);
            $sheet->setTitle("Tikets IT");

            $sheet->getRowDimension('1')->setRowHeight(28);

            $sheet->getColumnDimension('A')->setWidth(13);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setWidth(30);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setWidth(40);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('O')->setAutoSize(true);
            $sheet->getColumnDimension('P')->setAutoSize(true);

            //UBICACION DEL TEXTO
            $sheet->getStyle($columnTitle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet->getStyle($columnTitle)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $spreadsheet->getActiveSheet()->getStyle($columnTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet->getStyle($columnTitle)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet->setCellValue('A1', 'FOLIO');
            $sheet->setCellValue('B1', 'NOMBRE');
            $sheet->setCellValue('C1', 'NOMINA');
            $sheet->setCellValue('D1', 'DEPARTAMENTO');
            $sheet->setCellValue('E1', 'FECHA SOLICITUD');
            $sheet->setCellValue('F1', 'AREA');
            $sheet->setCellValue('G1', 'INGENIERO');
            $sheet->setCellValue('H1', 'ACTIVIDAD');
            $sheet->setCellValue('I1', 'PRIORIDAD');
            $sheet->setCellValue('J1', 'DESCRIPCION');
            $sheet->setCellValue('K1', 'ESTADO');
            $sheet->setCellValue('L1', 'FECHA CONCLUCION');
            $sheet->setCellValue('M1', 'FECHA CANCELACION');
            $sheet->setCellValue('N1', 'MOTIVO CANCELACION');
            $sheet->setCellValue('O1', 'TIEMPO DEL TICKET DESDE SU CREACION');

            foreach ($query as $value) {
                $tiempoNuevo = '';
                if ($value->Ticket_EstatusId == 1 || $value->Ticket_EstatusId == 2) {
                    $diffTime = (new DateTime($value->Ticket_FechaCreacion))->diff(new DateTime());
                    $anio = ($diffTime->y > 0) ? $diffTime->y . ' año(s)' : '';
                    $mes = ($diffTime->m > 0) ? ' ' . $diffTime->m . ' mes(es)' : '';
                    $dias = ($diffTime->d > 0) ? ' ' . $diffTime->d . ' día(s)' : '';
                    $horas = ($diffTime->h > 0) ? ' ' . $diffTime->h . ' hora(s)' : '';
                    $minutos = ($diffTime->i > 0) ? ' ' . $diffTime->i . ' minuto(s)' : '';
                    $tiempoNuevo = $anio . $mes . $dias . $horas . $minutos;
                }
                $sheet->setCellValue('A' . $cont, $value->TicketId);
                $sheet->setCellValue('B' . $cont, $value->Ticket_UsuarioCreacion);
                $sheet->setCellValue('C' . $cont, $value->payroll_number);
                $sheet->setCellValue('D' . $cont, $value->departament);
                $sheet->setCellValue('E' . $cont, $value->created_at);
                $sheet->setCellValue('F' . $cont, $value->area);
                $sheet->setCellValue('G' . $cont, $value->ing);
                $sheet->setCellValue('H' . $cont, strtoupper($value->Actividad_Actividad));
                $sheet->setCellValue('I' . $cont, $value->prioridad);
                $sheet->setCellValue('J' . $cont, $value->Ticket_Descripcion);
                $sheet->setCellValue('K' . $cont, $value->estado);
                $sheet->setCellValue('L' . $cont, $value->conclud_at);
                $sheet->setCellValue('M' . $cont, $value->cancel_at);
                $sheet->setCellValue('N' . $cont, $value->motive_cancel);
                $sheet->setCellValue('O' . $cont, $tiempoNuevo);
                $cont++;
            }

            /* --------------------------------------HOJA 2 ---------------------------- */

            $cont1 = 2;
            $columnTitle1 = 'A1:H1';
            $sheet1 = $spreadsheet->createSheet(1)->setAutoFilter($columnTitle1);
            $query1 = $this->db->query("SELECT a.id_request, a.cost_center, a.departament, a.payroll_number,
                CASE
                    WHEN a.request_status = 1 THEN 'Pendiente'
                    WHEN a.request_status = 2 THEN 'Autorizada'
                    WHEN a.request_status = 3 THEN 'Completado'
                    WHEN a.request_status = 4 THEN 'Rechazada'
                    ELSE 'Error'
                END AS estatus,
                DATE_FORMAT(a.created_at,'%d/%m/%Y %H:%i') AS created_at,
                CONCAT(d.`name`,' ',d.surname) AS usuario,
                -- CONCAT(c.description_product,' ',b.quantity,' ',b.unit) AS concatenado
                GROUP_CONCAT(DISTINCT CONCAT('- ',c.description_product,' ',b.quantity,' ',b.unit) SEPARATOR ',     ') AS concatenado
                FROM tbl_stationery_requests AS a
                    INNER JOIN tbl_stationery_items as b ON a.id_request = b.id_request
                    INNER JOIN tbl_stationery_inventory as c ON b.id_product = c.id_product
                    JOIN tbl_users AS d ON a.id_user = d.id_user
                    WHERE a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                    AND a.active_status = 1
                GROUP BY a.id_request
            ORDER BY a.id_request DESC")->getResult();
            $sheet1->setTitle("Papeleria");

            $sheet1->getRowDimension('1')->setRowHeight(28);

            $sheet1->getColumnDimension('A')->setWidth(13);
            $sheet1->getColumnDimension('B')->setAutoSize(true);
            $sheet1->getColumnDimension('C')->setWidth(15);
            $sheet1->getColumnDimension('D')->setWidth(12);
            $sheet1->getColumnDimension('E')->setWidth(25);
            $sheet1->getColumnDimension('F')->setWidth(25);
            $sheet1->getColumnDimension('G')->setWidth(18);
            $sheet1->getColumnDimension('H')->setAutoSize(true);

            //UBICACION DEL TEXTO
            $sheet1->getStyle($columnTitle1)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet1->getStyle($columnTitle1)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $sheet1->getStyle($columnTitle1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet1->getStyle($columnTitle1)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet1->setCellValue('A1', 'FOLIO');
            $sheet1->setCellValue('B1', 'NOMBRE');
            $sheet1->setCellValue('C1', 'NOMINA');
            $sheet1->setCellValue('D1', 'CENTRO DE COSTOS');
            $sheet1->setCellValue('E1', 'DEPARTAMENTO');
            $sheet1->setCellValue('F1', 'FECHA SOLICITUD');
            $sheet1->setCellValue('G1', 'ESTADO');
            $sheet1->setCellValue('H1', 'LISTADO DE SOLICITUDES');

            foreach ($query1 as $value) {
                $sheet1->setCellValue('A' . $cont1, $value->id_request);
                $sheet1->setCellValue('B' . $cont1, $value->usuario);
                $sheet1->setCellValue('C' . $cont1, $value->payroll_number);
                $sheet1->setCellValue('D' . $cont1, $value->cost_center);
                $sheet1->setCellValue('E' . $cont1, $value->departament);
                $sheet1->setCellValue('F' . $cont1, $value->created_at);
                $sheet1->setCellValue('G' . $cont1, $value->estatus);
                $sheet1->setCellValue('H' . $cont1, $value->concatenado);
                $cont1++;
            }

            /* --------------------------------HOJA 3-------------------------------- */

            $cont2 = 2;
            $columnTitle2 = 'A1:N1';
            $query2 = $this->db->query("SELECT a.id_valija, a.user_name, a.payroll_number, a.departament, a.priority,
                 a.observation, a.job_position,
                DATE_FORMAT(a.created_at,'%d/%m/%Y - %H:%i') AS created_at_format,
                DATE_FORMAT(a.date, '%d/%m/%Y') AS date_format,
                DATE_FORMAT(a.time, '%H:%i') AS time_format,
                CASE
                    WHEN a.type_of_employee = 1 THEN
                        'ADMINISTRATIVOS'
                    WHEN a.type_of_employee = 1 THEN
                        'SINDICALIZADOS'
                    ELSE
                        'ERROR'
                END AS type_employe,
                CASE
                    WHEN a.origin = 'OTRO' THEN
                        another_origin
                    ELSE
                        a.origin
                END AS origin_txt,
                CASE
                    WHEN a.destination = 'OTRO' THEN
                        another_origin
                    ELSE
                        a.destination
                END AS destination_txt,
                CASE
                    WHEN a.`status` = 1 THEN
                        'PENDIENTE'
                    WHEN a.`status` = 2 THEN
                        'CONCLUIDO'
                    ELSE
                        'ERROR'
                END AS status_txt
                FROM tbl_valija_request AS a
                WHERE a.active_status = 1
                    AND a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
            ORDER BY a.id_valija DESC")->getResult();
            $sheet2 = $spreadsheet->createSheet(2)->setAutoFilter($columnTitle2);
            $sheet2->setTitle("Valija");

            $sheet2->getRowDimension('1')->setRowHeight(28);

            $sheet2->getColumnDimension('A')->setWidth(13);
            $sheet2->getColumnDimension('B')->setAutoSize(true);
            $sheet2->getColumnDimension('C')->setAutoSize(true);
            $sheet2->getColumnDimension('D')->setAutoSize(true);
            $sheet2->getColumnDimension('E')->setWidth(25);
            $sheet2->getColumnDimension('F')->setAutoSize(true);
            $sheet2->getColumnDimension('G')->setAutoSize(true);
            $sheet2->getColumnDimension('H')->setWidth(20);
            $sheet2->getColumnDimension('I')->setWidth(20);
            $sheet2->getColumnDimension('J')->setAutoSize(true);
            $sheet2->getColumnDimension('K')->setAutoSize(true);
            $sheet2->getColumnDimension('L')->setAutoSize(true);
            $sheet2->getColumnDimension('M')->setWidth(30);
            $sheet2->getColumnDimension('N')->setAutoSize(true);

            //UBICACION DEL TEXTO
            $sheet2->getStyle($columnTitle2)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet2->getStyle($columnTitle2)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $sheet2->getStyle($columnTitle2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet2->getStyle($columnTitle2)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet2->setCellValue('A1', 'FOLIO');
            $sheet2->setCellValue('B1', 'NOMBRE');
            $sheet2->setCellValue('C1', 'NOMINA');
            $sheet2->setCellValue('D1', 'TIPO EMPLEADO');
            $sheet2->setCellValue('E1', 'DEPARTAMENTO');
            $sheet2->setCellValue('F1', 'PUESTO');
            $sheet2->setCellValue('G1', 'FECHA CREACION');
            $sheet2->setCellValue('H1', 'ORIGEN VALIJA');
            $sheet2->setCellValue('I1', 'DESTINO VALIJA');
            $sheet2->setCellValue('J1', 'FECHA');
            $sheet2->setCellValue('K1', 'HORA');
            $sheet2->setCellValue('L1', 'PRIORIDAD');
            $sheet2->setCellValue('M1', 'OBSERVACION');
            $sheet2->setCellValue('N1', 'ESTADO');

            foreach ($query2 as $value) {
                $sheet2->setCellValue('A' . $cont2, $value->id_valija);
                $sheet2->setCellValue('B' . $cont2, $value->user_name);
                $sheet2->setCellValue('C' . $cont2, $value->payroll_number);
                $sheet2->setCellValue('D' . $cont2, $value->type_employe);
                $sheet2->setCellValue('E' . $cont2, $value->job_position);
                $sheet2->setCellValue('F' . $cont2, $value->departament);
                $sheet2->setCellValue('G' . $cont2, $value->created_at_format);
                $sheet2->setCellValue('H' . $cont2, strtoupper($value->origin_txt));
                $sheet2->setCellValue('I' . $cont2, strtoupper($value->destination_txt));
                $sheet2->setCellValue('J' . $cont2, $value->date_format);
                $sheet2->setCellValue('K' . $cont2, $value->time_format);
                $sheet2->setCellValue('L' . $cont2, $value->priority);
                $sheet2->setCellValue('M' . $cont2, $value->observation);
                $sheet2->setCellValue('N' . $cont2, $value->status_txt);
                $cont2++;
            }

            /* --------------------------------HOJA 4-------------------------- */

            $cont3 =  2;
            $columnTitle3 = 'A1:R1';
            $query3 = $this->db->query("SELECT a.id_request,a. observation,
                a.sending_company, a.sender_name, b.payroll_number,  a.area_operative, b.departament,
                CONCAT(a.sender_street,', ',
                    a.sender_num,', ',
                    a.sender_col,', ',
                    a.sender_locality,', ',
                    a.sender_state,', ',
                    a.sender_country,', CP: ',
                    a.sender_cp
                ) AS sender_direction,
                a.recipient_company,
                a.recipient_name,
                CAST(a.recipient_phone AS CHAR(15)) AS recipient_phone_txt,
                CONCAT(a.recipient_street,', ',
                    a.recipient_num,', ',
                    a.recipient_locality,', ',
                    a.recipient_state,', ',
                    a.recipient_country
                ) AS recipient_direction,
                DATE_FORMAT(a.created_at,'%d/%m/%Y - %H:%i') AS created_at_format,
                DATE_FORMAT(a.autorize_at,'%d/%m/%Y') AS autorize_at_format,
                COUNT(c.id_item) AS num_packet,
                CASE
                    WHEN a.shipping_type = 1 THEN 'DÍA SIGUIENTE'
                    WHEN a.shipping_type = 2 THEN 'TERRESTRE'
                    ELSE 'SIN DATO'
                END AS shipping_type_txt,
                CASE
                    WHEN a.sure = 1 THEN 'SI'
                    WHEN a.sure = 2 THEN 'NO'
                    ELSE 'SIN DATO'
                END AS sure_txt,
                CASE
                    WHEN a.sure = 1 THEN CONCAT('$' ,sure)
                    WHEN a.sure = 2 THEN ''
                    ELSE 'SIN DATO'
                END AS cost_txt,
                CASE
                    WHEN a.`status` = 1 THEN 'PENDIENTE'
                    WHEN a.`status` = 2 THEN 'AUTORIZADO'
                    WHEN a.`status` = 3 THEN 'CANCELADO'
                    ELSE 'SIN DATO'
                END AS status_txt                
                FROM tbl_packer_request AS a
                    LEFT JOIN (
                        SELECT st1.id_user, st2.departament, st1.payroll_number
                        FROM tbl_users AS st1
                        JOIN cat_departament AS st2 ON st1.id_departament = st2.id_depto
                    ) AS b ON b.id_user = a.id_user
                    LEFT JOIN (
                        SELECT st3.id_request, st3.id_item 
                        FROM tbl_packer_item AS st3 
                        WHERE st3.`status` = 1
                    ) AS c ON a.id_request = c.id_request
                WHERE a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                    AND a.active_status = 1
                GROUP BY a.id_request
            ORDER BY a.id_request DESC")->getResult();
            $sheet3 = $spreadsheet->createSheet(3)->setAutoFilter($columnTitle3);
            $sheet3->setTitle("Paqueteria");
            $sheet3->getRowDimension('1')->setRowHeight(28);

            $sheet3->getColumnDimension('A')->setWidth(13);
            $sheet3->getColumnDimension('B')->setAutoSize(true);
            $sheet3->getColumnDimension('C')->setAutoSize(true);
            $sheet3->getColumnDimension('D')->setAutoSize(true);
            $sheet3->getColumnDimension('E')->setAutoSize(true);
            $sheet3->getColumnDimension('F')->setAutoSize(true);
            $sheet3->getColumnDimension('G')->setAutoSize(true);
            $sheet3->getColumnDimension('H')->setWidth(40);
            $sheet3->getColumnDimension('I')->setAutoSize(true);
            $sheet3->getColumnDimension('J')->setAutoSize(true);
            $sheet3->getColumnDimension('K')->setAutoSize(true);
            $sheet3->getColumnDimension('L')->setWidth(40);
            $sheet3->getColumnDimension('M')->setAutoSize(true);
            $sheet3->getColumnDimension('N')->setAutoSize(true);
            $sheet3->getColumnDimension('O')->setAutoSize(true);
            $sheet3->getColumnDimension('P')->setAutoSize(true);
            $sheet3->getColumnDimension('Q')->setAutoSize(true);
            $sheet3->getColumnDimension('R')->setAutoSize(true);

            //UBICACION DEL TEXTO
            $sheet3->getStyle($columnTitle3)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet3->getStyle($columnTitle3)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $sheet3->getStyle($columnTitle3)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet3->getStyle($columnTitle3)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet3->setCellValue('A1', 'FOLIO');
            $sheet3->setCellValue('B1', 'NOMBRE');
            $sheet3->setCellValue('C1', 'NOMINA');
            $sheet3->setCellValue('D1', 'CENTRO DE COSTOS');
            $sheet3->setCellValue('E1', 'DEPARTAMENTO');
            $sheet3->setCellValue('F1', 'FECHA SOLICITUD');
            $sheet3->setCellValue('G1', 'EMPRESA EMISORA');
            $sheet3->setCellValue('H1', 'DIRECCION EMISORA');
            $sheet3->setCellValue('I1', 'EMPRESA RECEPTORA');
            $sheet3->setCellValue('J1', 'NOMBRE DE RECEPTOR');
            $sheet3->setCellValue('K1', 'TELEFONO RECEPTORA');
            $sheet3->setCellValue('L1', 'DIRECCION RECEPTORA');
            $sheet3->setCellValue('M1', 'TIPO DE ENVIO');
            $sheet3->setCellValue('N1', 'SEGURO');
            $sheet3->setCellValue('O1', 'MONTO DE SEGURO');
            $sheet3->setCellValue('P1', 'PAQUETES');
            $sheet3->setCellValue('Q1', 'ESTADO');
            $sheet3->setCellValue('R1', 'FECHA AUTORIZACION');

            foreach ($query3 as $value) {
                $sheet3->setCellValue('A' . $cont3, $value->id_request);
                $sheet3->setCellValue('B' . $cont3, $value->sender_name);
                $sheet3->setCellValue('C' . $cont3, $value->payroll_number);
                $sheet3->setCellValue('D' . $cont3, $value->area_operative);
                $sheet3->setCellValue('E' . $cont3, $value->departament);
                $sheet3->setCellValue('F' . $cont3, $value->created_at_format);
                $sheet3->setCellValue('G' . $cont3, $value->sending_company);
                $sheet3->setCellValue('H' . $cont3, strtoupper($value->sender_direction));
                $sheet3->setCellValue('I' . $cont3, $value->recipient_company);
                $sheet3->setCellValue('J' . $cont3, $value->recipient_name);
                $sheet3->setCellValue('K' . $cont3, $value->recipient_phone_txt);
                $sheet3->setCellValue('L' . $cont3, strtoupper($value->recipient_direction));
                $sheet3->setCellValue('M' . $cont3, $value->shipping_type_txt);
                $sheet3->setCellValue('N' . $cont3, $value->sure_txt);
                $sheet3->setCellValue('O' . $cont3, $value->cost_txt);
                $sheet3->setCellValue('P' . $cont3, $value->num_packet);
                $sheet3->setCellValue('Q' . $cont3, $value->status_txt);
                $sheet3->setCellValue('R' . $cont3, $value->autorize_at_format);
                $cont3++;
            }

            /* --------------------------------HOJA 5-------------------------- */

            $cont4 = 3;
            $columnTitle4 = 'A1:O2';
            $query4 = $this->db->query("SELECT a.id_request, a.depto, a.motive, a.observation, d.model, a.`name`, a.payroll_number,
                DATE_FORMAT(c.date,'%d/%m/%Y') AS date_sh,
                DATE_FORMAT(a.created_at,'%d/%m/%Y - %H:%i') AS created_at,
                DATE_FORMAT(a.date_autorize,'%d/%m/%Y - %H:%i') AS date_autorize,
                CONCAT(DATE_FORMAT(c.star_time,' %H:%i'),' - ',DATE_FORMAT(c.end_time,' %H:%i')) AS horas_sh,
                CONCAT(DATE_FORMAT(b.end_date,'%d/%m/%Y'),' - ',DATE_FORMAT(b.end_datetime,' %H:%i')) AS end_lg,
                CONCAT(DATE_FORMAT(b.star_date,'%d/%m/%Y'),' - ',DATE_FORMAT(b.star_datetime,' %H:%i')) AS star_lg,
                CASE
                    WHEN a.type_trip = 1 THEN 'Viaje Corto'
                    WHEN a.type_trip = 2 THEN 'Vieja Prolongado'
                    ELSE 'Error'
                END AS tipo_viaje,
                CASE
                    WHEN a.`status` = 1 THEN 'Pendiente'
                    WHEN a.`status` = 2 THEN 'Autorizada'
                    WHEN a.`status` = 3 THEN 'Rechazada'
                    WHEN a.`status` = 4 THEN 'Asignada'
                    ELSE 'Error'
                END AS estatus             
                FROM tbl_cars_request AS a
                    LEFT JOIN tbl_cars_extended_trip AS b ON a.id_request = b.id_request
                    LEFT JOIN tbl_cars_short_trip AS c ON a.id_request = c.id_request
                    LEFT JOIN tbl_cars_vehicles AS d ON a.id_cars = d.id_car
                WHERE a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                    AND a.active_status = 1
            ORDER BY a.id_request ASC")->getResult();
            $sheet4 = $spreadsheet->createSheet(4)->setAutoFilter('A2:O2');
            $sheet4->setTitle("Vehiculos");

            // $sheet4->getRowDimension('1')->setRowHeight(28);

            $sheet4->getColumnDimension('A')->setWidth(13);
            $sheet4->getColumnDimension('B')->setAutoSize(true);
            $sheet4->getColumnDimension('C')->setWidth(15);
            $sheet4->getColumnDimension('D')->setWidth(12);
            $sheet4->getColumnDimension('E')->setWidth(25);
            $sheet4->getColumnDimension('F')->setWidth(25);
            $sheet4->getColumnDimension('G')->setWidth(18);
            $sheet4->getColumnDimension('H')->setAutoSize(true);
            $sheet4->getColumnDimension('I')->setAutoSize(true);
            $sheet4->getColumnDimension('J')->setAutoSize(true);
            $sheet4->getColumnDimension('K')->setAutoSize(true);
            $sheet4->getColumnDimension('L')->setWidth(50);
            $sheet4->getColumnDimension('M')->setWidth(40);
            $sheet4->getColumnDimension('N')->setAutoSize(true);
            $sheet4->getColumnDimension('O')->setAutoSize(true);

            //UBICACION DEL TEXTO
            $sheet4->getStyle($columnTitle4)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet4->getStyle($columnTitle4)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $sheet4->getStyle($columnTitle4)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
            $sheet4->getStyle('G1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5C636A');
            $sheet4->getStyle('I1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3D4246');

            // FONT-TEXT
            $sheet4->getStyle($columnTitle4)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet4->setCellValue('A1', 'FOLIO')->mergeCells('A1:A2');
            $sheet4->setCellValue('B1', 'FECHA SOLICITUD')->mergeCells('B1:B2');
            $sheet4->setCellValue('C1', 'NOMBRE')->mergeCells('C1:C2');
            $sheet4->setCellValue('D1', 'NOMINA')->mergeCells('D1:D2');
            $sheet4->setCellValue('E1', 'DEPARTAMENTO')->mergeCells('E1:E2');
            $sheet4->setCellValue('F1', 'TIPO DE VIAJE')->mergeCells('F1:F2');
            $sheet4->setCellValue('G1', 'VIAJE CORTO')->mergeCells('G1:H1');
            $sheet4->setCellValue('G2', 'FECHA');
            $sheet4->setCellValue('H2', 'HORA INICO - FIN');
            $sheet4->setCellValue('I1', 'VIAJE LARGO')->mergeCells('I1:J1');
            $sheet4->setCellValue('I2', 'FECHA INICIAL');
            $sheet4->setCellValue('J2', 'FECHA FINAL');
            $sheet4->setCellValue('K1', 'VEHICULO')->mergeCells('K1:K2');
            $sheet4->setCellValue('L1', 'MOTIVO')->mergeCells('L1:L2');
            $sheet4->setCellValue('M1', 'OBSERVACION')->mergeCells('M1:M2');
            $sheet4->setCellValue('N1', 'ESTADO')->mergeCells('N1:N2');
            $sheet4->setCellValue('O1', 'FECHA AUTORIZACION')->mergeCells('O1:O2');

            foreach ($query4 as $value) {
                $sheet4->setCellValue('A' . $cont4, $value->id_request);
                $sheet4->setCellValue('B' . $cont4, $value->created_at);
                $sheet4->setCellValue('C' . $cont4, $value->name);
                $sheet4->setCellValue('D' . $cont4, $value->payroll_number);
                $sheet4->setCellValue('E' . $cont4, $value->depto);
                $sheet4->setCellValue('F' . $cont4, strtoupper($value->tipo_viaje));
                $sheet4->setCellValue('G' . $cont4, $value->date_sh);
                $sheet4->setCellValue('H' . $cont4, $value->horas_sh);
                $sheet4->setCellValue('I' . $cont4, $value->star_lg);
                $sheet4->setCellValue('J' . $cont4, $value->end_lg);
                $sheet4->setCellValue('K' . $cont4, $value->model ?? 'NO ASIGNADO');
                $sheet4->setCellValue('L' . $cont4, $value->motive);
                $sheet4->setCellValue('M' . $cont4, $value->observation);
                $sheet4->setCellValue('N' . $cont4, strtoupper($value->estatus));
                $sheet4->setCellValue('O' . $cont4, $value->date_autorize);
                $cont4++;
            }

            /* --------------------------------HOJA 6-------------------------- */

            $columnTitle5 = 'A1:M1';
            $cont5 = 2;
            $query5 = $this->db->query("SELECT a.id_coffee, a.depto, a.reason_meeting, a.num_person, a.`name`, a.payroll_number,
                CONCAT(DATE_FORMAT(a.date,'%m/%d/%Y'),' - ',DATE_FORMAT(a.horario,'%H:%i')) AS horario_solicitud,
                DATE_FORMAT(a.date_authorize,'%d/%m/%Y') AS date_authorize,
                DATE_FORMAT(a.created_at,'%d/%m/%Y') AS created_at,
                CASE
                    WHEN a.meeting_room = 1 THEN 'Sala de Consejo'
                    WHEN a.meeting_room = 2 THEN 'Sala de Operaciones'
                    WHEN a.meeting_room = 3 THEN 'Sala de Ingenieria'
                    WHEN a.meeting_room = 4 THEN 'Sala James Walworth'
                    WHEN a.meeting_room = 5 THEN 'Sala de Logistica'
                    WHEN a.meeting_room = 6 THEN 'Sala de Ventas'
                    WHEN a.meeting_room = 7 THEN 'Sala de Calidad'
                    WHEN a.meeting_room = 8 THEN 'Mezzanine (Nave 3)'
                    ELSE 'Error'
                END AS sala,
                CASE
                    WHEN a.menu_especial > 0 THEN 'SI'
                    ELSE 'NO'
                END AS menu_Especial,
                CASE
                    WHEN a.status = 1 THEN 'Pendiente'
                    WHEN a.status = 2 THEN 'Autorizada'
                    WHEN a.status = 3 THEN 'Rechazada'
                    WHEN a.status = 4 THEN 'Eliminada'
                    WHEN a.status = 6 THEN 'Autoriza Talento'
                    WHEN a.status = 7 THEN 'Rechaza Talento'
                    ELSE 'Error'
                END AS estatus,
                GROUP_CONCAT(DISTINCT CONCAT(b.product) SEPARATOR ', ') AS concatenado
                /*GROUP_CONCAT(DISTINCT CONCAT(c.description) SEPARATOR ', ') AS concatenado1*/
                FROM tbl_coffee_break AS a
                    LEFT JOIN tbl_coffee_items AS b ON a.id_coffee = b.id_coffee
                    LEFT JOIN tbl_coffee_menu_requisiton AS c ON a.id_coffee = c.id_coffee
                WHERE a.active_status = 1
                    AND a.status <> 5 AND a.status <> 4 AND a.status <> 7 
                    AND a.created_at BETWEEN '$data->fechaInicio' AND '$FechaFin'
                GROUP BY a.id_coffee
            ORDER BY a.id_coffee DESC")->getResult();

            $sheet5 = $spreadsheet->createSheet(5)->setAutoFilter($columnTitle5);
            $sheet5->setTitle("Cafeteria");

            $sheet5->getRowDimension('1')->setRowHeight(28);

            $sheet5->getColumnDimension('A')->setWidth(13);
            $sheet5->getColumnDimension('B')->setAutoSize(true);
            $sheet5->getColumnDimension('C')->setAutoSize(true);
            $sheet5->getColumnDimension('D')->setWidth(20);
            $sheet5->getColumnDimension('E')->setAutoSize(true);
            $sheet5->getColumnDimension('F')->setAutoSize(true);
            $sheet5->getColumnDimension('G')->setAutoSize(true);
            $sheet5->getColumnDimension('H')->setAutoSize(true);
            $sheet5->getColumnDimension('I')->setWidth(25);
            $sheet5->getColumnDimension('J')->setAutoSize(true);
            $sheet5->getColumnDimension('K')->setWidth(40);
            $sheet5->getColumnDimension('L')->setAutoSize(true);
            $sheet5->getColumnDimension('M')->setAutoSize(true);


            //UBICACION DEL TEXTO
            $sheet5->getStyle($columnTitle5)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // definir alineacion de texto HORUZONTAL    
            $sheet5->getStyle($columnTitle5)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // definir alineacion de texto VERTICAL

            //COLOR DE CELDAS
            $sheet5->getStyle($columnTitle5)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            // FONT-TEXT
            $sheet5->getStyle($columnTitle5)->getFont()->setBold(true)
                ->setName('Calibri')
                ->setSize(10)
                ->getColor()
                ->setRGB('FFFFFF');

            // TITULO DE CELDA
            $sheet5->setCellValue('A1', 'FOLIO');
            $sheet5->setCellValue('B1', 'NOMBRE');
            $sheet5->setCellValue('C1', 'NOMINA');
            $sheet5->setCellValue('D1', 'DEPARTAMENTO');
            $sheet5->setCellValue('E1', 'FECHA SOLICITUD');
            $sheet5->setCellValue('F1', 'FECHA DE JUNTA');
            $sheet5->setCellValue('G1', 'SALA');
            $sheet5->setCellValue('H1', 'NUMERO PERSONAS');
            $sheet5->setCellValue('I1', 'RAZON');
            $sheet5->setCellValue('J1', 'MENU ESPECIAL');
            $sheet5->setCellValue('K1', 'SUMINISTROS SOLICITADOS');
            $sheet5->setCellValue('L1', 'ESTADO');
            $sheet5->setCellValue('M1', 'FECHA AUTORIZACION');

            foreach ($query5 as $value) {
                $sheet5->setCellValue('A' . $cont5, $value->id_coffee);
                $sheet5->setCellValue('B' . $cont5, $value->name);
                $sheet5->setCellValue('C' . $cont5, $value->payroll_number);
                $sheet5->setCellValue('D' . $cont5, $value->depto);
                $sheet5->setCellValue('E' . $cont5, $value->created_at);
                $sheet5->setCellValue('F' . $cont5, $value->horario_solicitud);
                $sheet5->setCellValue('G' . $cont5, $value->sala);
                $sheet5->setCellValue('H' . $cont5, $value->num_person);
                $sheet5->setCellValue('I' . $cont5, $value->reason_meeting);
                $sheet5->setCellValue('J' . $cont5, $value->menu_Especial);
                $sheet5->setCellValue('K' . $cont5, $value->concatenado);
                $sheet5->setCellValue('L' . $cont5, $value->estatus);
                $sheet5->setCellValue('M' . $cont5, $value->date_authorize);
                $cont5++;
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
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $e;
        }
    }
}
