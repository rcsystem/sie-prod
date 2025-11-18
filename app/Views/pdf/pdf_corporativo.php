<style>
    body {
        font-family: 'Arial'
    }

    table {
        width: 40%;
        /* Ajusta el ancho total de la tabla según tus necesidades */
        border-collapse: collapse;

    }

    th,
    td {
        border: 0.3px solid #dddddd;
        /* padding: 0px; */
        text-align: center;

        /* Ajusta el ancho de cada columna según tus necesidades */
    }

    /* Eliminar bordes horizontales entre las celdas de datos (<td>) */
    td {
        border-top: none;
    }

    table th {
        text-align: center;
    }


    .content-img {
        width: 120px;
        height: 80px;
    }

    .titulo {
        width: 100%;
        text-align: center;
        margin: -65px 0px 10px 0px;
        color: #da1f1c;
        display: flex;
        justify-content: center;
        align-content: center;


    }

    .titulo-cafe {
        width: 100%;
        text-align: center;
        background-color: rgb(102, 102, 102);
        color: #fff;

    }

    .container {
        width: 50%;
        height: 80px;
        margin-top: 16px;
        text-align: center;
        font-size: 13px;
        margin-bottom: 2rem;
    }

    .title_table {
        border: none;
        font-weight: bold;
        color: #fff;
        background-color: #da1f1c;
        text-align: center;
        font-size: 13px;
        vertical-align: middle;
        padding: 2px;
    }

    .servicios_totales {
        border: none;
        text-align: center;
        color: #000;
        background-color: #fff;
        font-size: 13px;
        border: 0.3px solid #dddddd;
        /* Establecer un borde en todas las celdas */
        padding: 1px;
        /* Añadir relleno para mejorar la apariencia */
    }

    .area {

        /* border: 0.3px solid #154360; */

        border: none;
        background-color: #da1f1c;
        text-align: center;
        color: #fff;
        font-size: 13px;

        /*  background-color: #3C3C48; */

    }

    .table_dos tr td:not(:first-child):not(:last-child) {
        border-right: 0px;
    }

    .table_dos tr td:first-child {
        border-left: 0px;
    }

    .table_dos tr td:last-child {
        border-right: 0px;

    }
</style>

<?php

$meses = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];

?>


<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div>
            <div class="content-img">
                <img src="<?= base_url(); ?>/public/images/logo_Walworth.png" style="width:260px;" alt="Grupo Walworth">
            </div>
        </div>

    </page_header>

    <div class="titulo">
        <h5>FINANCE DIRECTION (QUALITY GENERAL SERVICES )</h5>
    </div>


    <div class="titulos">
        <!--    <span>January a October 2023</span> -->
        <table style="width:100%;">
            <tr>
                <td style="border:none;vertical-align: middle;padding:2px;height:2px;width:33%;color:#B3B2B2;font-size:16px;"><span><b>FINANCE DIRECTION (COFFEE SHOP)</b></span></td>
                <td style="border:none;vertical-align: middle;padding:2px;height:2px;width:33%;"></td>
                <td style="border:none;vertical-align: middle;padding:2px;height:2px;width:33%;"></td>
            </tr>
        </table>
    </div>


    <div class="container">


        <table class="table_uno">
            <thead class="unir">
                <tr>

                    <th colspan="" class="servicios_totales">Total services</th>
                    <?php

                    for ($i = $dataCoffe["total_mes"]; $i <= $dataCoffe["total_mes"]; $i++) {
                        // Obtener el nombre del mes correspondiente
                        $ultimoMes = $meses[$i];
                        echo "<th colspan='' class='title_table'>" . $meses[$i] . "</th>";
                    }

                    ?>

                    <th colspan="" class="title_table">Total</th>
                    <th colspan="" class="title_table">Monthly Average</th>
                    <th colspan="" class="title_table"><?= $ultimoMes ?> vs average</th>
                    <th colspan="" class="title_table">% Dist. by area</th>

                </tr>

            </thead>

            <tbody>

                <tr>
                    <td class='area' style='vertical-align: middle;'>Areas</td>
                    <?php  // Iterar sobre los meses
                    for ($i = $dataCoffe["total_mes"]; $i <= $dataCoffe["total_mes"]; $i++) { ?>
                        <td class='' style='vertical-align: middle;'>
                            <table class="table_dos" style="font-size: 13px;text-align:center;width:100%;">
                                <tr>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Plan</td>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Real</td>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">&nbsp;%&nbsp;&nbsp;</td>

                                </tr>
                            </table>
                        </td>
                    <?php } ?>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>

                </tr>



                <?php
                $sumpromedio = 0;
                $total_solicitudes = 0;
                $cont = 0;
                foreach ($dataCoffe["resultados"] as $departamento => $meses) {
                    $mesesConInformacion = 0; // Inicializar la variable que contará los meses con información
                    $elementoDic = 0;

                    $mesesConInfo = 0; // Inicializar la variable que contará los meses con información

                    // Iterar sobre los meses
                    foreach ($meses as $mes => $informacion) {
                        // Verificar si hay información en el mes
                        if ($informacion != '0, 0, 0%') {
                            $mesesConInfo++;
                        }
                    }
                ?>
                    <tr>
                        <td class='area' style='vertical-align: middle;'><?= $departamento ?></td>

                        <?php  // Iterar sobre los meses
                        for ($i = $dataCoffe["total_mes"]; $i <= $dataCoffe["total_mes"]; $i++) {
                            // echo "hola ".$meses[$i]."<br>";
                            $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";

                            if ($valor == '0') {
                                echo " <td style='font-size: 13px;vertical-align: middle;'>{$valor}</td>";
                            } else {
                        ?>
                                <td>

                                    <table class="table_dos" style="font-size: 13px;text-align:center;width:100%;">


                                        <?php
                                        $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";
                                        $valoresSeparados = explode(', ', $valor);
                                        if ($i == $mesesConInfo) {
                                            $elementoDic = $valoresSeparados[0];
                                        }
                                        ?>
                                        <tr>

                                            <?php echo '<td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">' . implode('</td><td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">', $valoresSeparados) . '</td>'; ?>
                                        </tr>
                                    </table>

                                </td>
                        <?php

                                // Incrementar la variable si el valor no es cero
                                $mesesConInformacion++;
                            }
                        }
                        ?>
                        <?php
                        if ($cont <= 6) {


                            foreach ($dataCoffe["total_areas"] as $departamentos => $totalSolicitudes) {

                                if ($departamento == $departamentos) {
                                    // Calcular el promedio
                                    /*     echo "total_solicitudes:".$totalSolicitudes." <br>";
                                    echo "meses_info:".$mesesConInformacion." <br>"; */
                                    $promedio =  round($totalSolicitudes / $mesesConInformacion);
                                    $total_solicitudes += $totalSolicitudes;
                                    $sumpromedio += $promedio;

                                    $promediDic = ($elementoDic / $totalSolicitudes) - 1;
                                    // Convertir a porcentaje
                                    $valorPorcentaje = round($promediDic * 100);

                                    $acumuladoSolicitud = $dataCoffe["solicitudes_total"];

                                    $acumuladoTotal = round(($totalSolicitudes / $acumuladoSolicitud) * 100);
                                    //color anterior: #D9D9D9
                                    echo "<td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'>{$totalSolicitudes}</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'>{$promedio}</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'>{$valorPorcentaje} %</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'>{$acumuladoTotal} %</td>";
                                }
                            }
                        }
                        ?>
                    </tr>

                <?php

                    $cont++;
                } ?>
                <tr>
                    <td style="text-align:center; font-size: 13px;">Grand Total </td>

                    <?php

                    $contfinal = 1;
                    foreach ($dataCoffe["total_solicitudes"] as $departamento => $meses) {

                        if ($contfinal == $dataCoffe["total_mes"]) {
                            $totalDic = $meses;
                        }

                        $valor = ($meses != '0, 0, 0%') ? $meses : "0";

                        if ($valor == '0') { ?>
                            <!-- <td style='background-color:#D9D9D9;text-align: center;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;'><?php //"ASD".$valor 
                                                                                                                                                                                                                            ?></td> -->
                        <?php  } else { ?>


                            <td style="background-color:#D9D9D9;">
                                <table>
                                    <tr>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $meses; ?></td>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $dataCoffe["total_atendidas"][$departamento]; ?></td>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">
                                            <?php
                                            $totalPorcentaje = ($dataCoffe["total_atendidas"][$departamento] != '0, 0, 0%') ? round(($dataCoffe["total_atendidas"][$departamento] / $meses) * 100, 0) : 0;
                                            echo $totalPorcentaje . '%';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                    <?php }

                        $contfinal++;
                    }

                    $promedioDic = ($totalDic / $total_solicitudes) - 1;
                    // Convertir a porcentaje
                    $totalPorcentaje = round($promedioDic * 100);

                    $total2 = ($total_solicitudes / $total_solicitudes) * 100;
                    ?>
                    <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'><?php echo $total_solicitudes; ?> </td>
                    <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'><?php echo $sumpromedio; ?> </td>
                    <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'><?php echo $totalPorcentaje . '%'; ?> </td>
                    <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'><?php echo $total2 . '%'; ?> </td>
                </tr>


            </tbody>


        </table>
        <!-- <td style="width:33%;"></td> -->

        <table style="margin: left;margin-top:8px">
            <tr>
                <td class="title_table" style="padding: 2px;">Areas</td>
                <td class="title_table">Pending</td>
                <td class="title_table">Approved</td>
                <td class="title_table">Declined</td>
                <td class="title_table">Total</td>
            </tr>
            <?php
            $fila_total = count($dataCoffe["totales_areas"]);
            foreach ($dataCoffe["totales_areas"] as $key => $value) :

                $color = ($key === $fila_total - 1) ? '#d9d9d9' : '#fff';
                $departamentos = (empty($value->Departamento)) ? "Grand Total" : $value->Departamento;

                echo "<tr style='font-size: 13px;'>
                                <td class='area' style='vertical-align: middle;padding: 1.8px;'>{$departamentos}</td>
                                <td style='vertical-align: middle; background-color:{$color}'> {$value->Pendiente}</td>
                                 <td style='vertical-align: middle; background-color:{$color}'> {$value->Autorizada}</td>
                                 <td style='vertical-align: middle; background-color:{$color}'> {$value->Rechazada}</td>
                                <td style='vertical-align: middle; background-color:#D9D9D9'> {$value->suma_total}</td>
                            </tr>";
            endforeach;
            ?>



        </table>



    </div>
    <div class="titulos">

        <!--    <span>January a October 2023</span> -->
        <table style="width:100%;">
            <tr>
                <td style="border:none;vertical-align: middle;padding:2px;height:2px;width:33%;color:#B3B2B2;font-size:16px;"><span><b>FINANCE DIRECTION (VALIJA)</b></span></td>
                <td style="border:none;vertical-align: middle;padding:2px;height:2px;width:33%;"></td>
                <td style="border:none;vertical-align: middle;padding:2px;height:2px;width:33%;"></td>
            </tr>
        </table>
    </div>

    <div class="container">




        <table class="table_uno">
            <thead class="unir">
                <tr>

                    <th colspan="" class="servicios_totales">Total services</th>
                    <?php

                    $meses = [
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December'
                    ];

                    for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) {
                        // Obtener el nombre del mes correspondiente
                        $ultimoMes = $meses[$i];
                        echo "<th colspan='' class='title_table'>" . $meses[$i] . "</th>";
                    }

                    ?>
                    <th colspan="" class="title_table">Total</th>
                    <th colspan="" class="title_table">Monthly Average</th>
                    <th colspan="" class="title_table"><?= $ultimoMes ?> vs average</th>
                    <th colspan="" class="title_table">% Dist. by area</th>

                </tr>

            </thead>

            <tbody>

                <tr>
                    <td class='area' style='vertical-align: middle;'>Areas</td>
                    <?php  // Iterar sobre los meses
                    for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) { ?>
                        <td class='' style='vertical-align: middle;'>
                            <table class="table_dos" style="font-size: 13px;text-align:center;width:100%;">
                                <tr>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Plan</td>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Real</td>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">&nbsp;%&nbsp;&nbsp;</td>

                                </tr>
                            </table>
                        </td>
                    <?php } ?>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>

                </tr>



                <?php
                $sumpromedio = 0;
                $total_solicitudes = 0;
                $cont = 0;
                foreach ($dataValija["resultados"] as $departamento => $meses) {
                    $mesesConInformacion = 0; // Inicializar la variable que contará los meses con información
                    $elementoDic = 0;

                    $mesesConInfo = 0; // Inicializar la variable que contará los meses con información

                    // Iterar sobre los meses
                    foreach ($meses as $mes => $informacion) {
                        // Verificar si hay información en el mes
                        if ($informacion != '0, 0, 0%') {
                            $mesesConInfo++;
                        }
                    }
                ?>
                    <tr>
                        <td class='area' style='vertical-align: middle;'><?= $departamento ?></td>

                        <?php  // Iterar sobre los meses
                        for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) {
                            // echo "hola ".$meses[$i]."<br>";
                            $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";

                            if ($valor == '0') { ?>
                                <td style='font-size: 13px; vertical-align: middle;'><?= $valor ?></td>
                            <?php  } else {
                            ?>
                                <td>

                                    <table class="table_dos" style="font-size: 13px;text-align:center;width:100%;">


                                        <?php
                                        $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";
                                        $valoresSeparados = explode(', ', $valor);
                                        if ($i == $mesesConInfo) {
                                            $elementoDic = $valoresSeparados[0];
                                        }
                                        ?>
                                        <tr>

                                            <?php echo '<td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">' . implode('</td><td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">', $valoresSeparados) . '</td>'; ?>
                                        </tr>
                                    </table>

                                </td>
                        <?php

                                // Incrementar la variable si el valor no es cero
                                $mesesConInformacion++;
                            }
                        }
                        ?>
                        <?php
                        if ($cont <= 6) {

                            foreach ($dataValija["total_areas"] as $departamentos => $totalSolicitudes) {
                                if ($departamento == $departamentos) {
                                    // Calcular el promedio
                                    $promedio =  round($totalSolicitudes / $mesesConInformacion);
                                    $total_solicitudes += $totalSolicitudes;
                                    $sumpromedio += $promedio;

                                    $promediDic = ($elementoDic / $totalSolicitudes) - 1;
                                    // Convertir a porcentaje
                                    $valorPorcentaje = round($promediDic * 100);

                                    $acumuladoSolicitud = $dataValija["solicitudes_total"];

                                    $acumuladoTotal = round(($totalSolicitudes / $acumuladoSolicitud) * 100);



                        ?>
                                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $totalSolicitudes; ?></td>
                                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $promedio; ?></td>
                                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $valorPorcentaje . '%'; ?></td>
                                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $acumuladoTotal . '%'; ?></td>



                        <?php }
                            }
                        }
                        ?>
                    </tr>

                <?php

                    $cont++;
                } ?>
                <tr>
                    <td style="text-align:center; font-size: 13px;">Grand Total </td>

                    <?php

                    $contfinal = 1;
                    foreach ($dataValija["total_solicitudes"] as $departamento => $meses) {

                        if ($contfinal == $dataValija["total_mes"]) {
                            $totalDic = $meses;
                        }

                        $valor = ($meses != '0, 0, 0%') ? $meses : "0";

                        if ($valor == '0') { ?>
                            <!--  <td style='background-color:#D9D9D9;text-align: center;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;'><?php //$valor; 
                                                                                                                                                                                                                            ?></td> -->
                        <?php  } else { ?>


                            <td style="background-color:#D9D9D9;">
                                <table>
                                    <tr>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $meses; ?></td>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $dataCoffe["total_atendidas"][$departamento]; ?></td>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">
                                            <?php
                                            $totalPorcentaje = ($dataValija["total_atendidas"][$departamento] != '0, 0, 0%') ? round(($dataValija["total_atendidas"][$departamento] / $meses) * 100, 0) : 0;
                                            echo $totalPorcentaje . '%';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                    <?php }

                        $contfinal++;
                    }

                    $promedioDic = ($totalDic / $total_solicitudes) - 1;
                    // Convertir a porcentaje
                    $totalPorcentaje = round($promedioDic * 100);

                    $total2 = ($total_solicitudes / $total_solicitudes) * 100;
                    ?>
                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $total_solicitudes ?> </td>
                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $sumpromedio ?> </td>
                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $totalPorcentaje . '%' ?> </td>
                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $total2 . '%' ?> </td>
                </tr>


            </tbody>


        </table>



        <table style="margin: left;margin-top:16px;">
            <tr>
                <td class="title_table" style="padding: 2px;">Areas</td>
                <td class="title_table">Pending</td>
                <td class="title_table">Approved</td>
                <td class="title_table">Declined</td>
                <td class="title_table">Total</td>
            </tr>
            <?php
            $fila_total = count($dataValija["totales_areas"]);
            foreach ($dataValija["totales_areas"] as $key => $value) {

                $color = ($key === $fila_total - 1) ? '#d9d9d9' : '#fff';

                $departamentos = (empty($value->Departamento)) ? "Grand Total" : $value->Departamento;
                echo "<tr style='font-size: 13px;'>
                                <td class='area' style='vertical-align: middle;padding: 1.8px;'>{$departamentos}</td>
                                <td style='vertical-align: middle; background-color:{$color}'>{$value->Pendiente}</td>
                                <td style='vertical-align: middle; background-color:{$color}'>{$value->Autorizada}</td>
                                <td style='vertical-align: middle; background-color:{$color}'>{$value->Rechazada}</td>
                                <td style='vertical-align: middle; background-color:#D9D9D9'>{$value->suma_total}</td>
                             </tr>";
            }



            ?>
        </table>




    </div>

    <div class="titulos" style="page-break-before: always;">
        <!--    <span>January a October 2023</span> -->
        <table style="width:100%;">
            <tr>
                <td style="border:none;vertical-align: middle;width:33%;color:#B3B2B2;font-size:16px;"><span><b>FINANCE DIRECTION (PAPERWORK)</b></span></td>
                <td style="border:none;vertical-align: middle;width:33%;"></td>
                <td style="border:none;vertical-align: middle;width:33%;"></td>
            </tr>
        </table>
    </div>


    <div class="container">


        <table class="table_uno">
            <thead class="unir">
                <tr>

                    <th colspan="" class="servicios_totales">Total services</th>

                    <?php
                    $meses = [
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December'
                    ];

                    for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) {
                        // Obtener el nombre del mes correspondiente
                        $ultimoMes = $meses[$i];
                        echo "<th colspan='' class='title_table'>" . $meses[$i] . "</th>";
                    }

                    ?>

                    <th colspan="" class="title_table">Total</th>
                    <th colspan="" class="title_table">Monthly Average</th>
                    <th colspan="" class="title_table"><?= $ultimoMes ?> vs average</th>
                    <th colspan="" class="title_table">% Dist. by area</th>

                </tr>

            </thead>

            <tbody>

                <tr>
                    <td class='area' style='vertical-align: middle;'>Areas</td>
                    <?php  // Iterar sobre los meses
                    for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) { ?>
                        <td class='' style='vertical-align: middle;'>
                            <table class="table_dos" style="font-size: 13px;text-align:center;width:100%;">
                                <tr>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Plan</td>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Real</td>
                                    <td style="vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">&nbsp;%&nbsp;&nbsp;</td>

                                </tr>
                            </table>
                        </td>
                    <?php } ?>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>

                </tr>



                <?php
                $sumpromedio = 0;
                $total_solicitudes = 0;
                $cont = 0;
                foreach ($dataStationery["resultados"] as $departamento => $meses) {
                    $mesesConInformacion = 0; // Inicializar la variable que contará los meses con información
                    $elementoDic = 0;

                    $mesesConInfo = 0; // Inicializar la variable que contará los meses con información

                    // Iterar sobre los meses
                    foreach ($meses as $mes => $informacion) {
                        // Verificar si hay información en el mes
                        if ($informacion != '0, 0, 0%') {
                            $mesesConInfo++;
                        }
                    }
                ?>
                    <tr>
                        <td class='area' style='vertical-align: middle;'><?= $departamento ?></td>

                        <?php  // Iterar sobre los meses
                        for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) {
                            // echo "hola ".$meses[$i]."<br>";
                            $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";

                            if ($valor == '0') { ?>
                                <td style='font-size: 13px; vertical-align: middle;'><?= $valor ?></td>
                            <?php  } else {
                            ?>
                                <td>

                                    <table class="table_dos" style="font-size: 13px;text-align:center;width:100%;">


                                        <?php
                                        $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";
                                        $valoresSeparados = explode(', ', $valor);
                                        if ($i == $mesesConInfo) {
                                            $elementoDic = $valoresSeparados[0];
                                        }
                                        ?>
                                        <tr>

                                            <?php echo '<td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">' . implode('</td><td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">', $valoresSeparados) . '</td>'; ?>
                                        </tr>
                                    </table>

                                </td>
                        <?php

                                // Incrementar la variable si el valor no es cero
                                $mesesConInformacion++;
                            }
                        }
                        ?>
                        <?php
                        if ($cont <= 7) {

                            foreach ($dataStationery["total_areas"] as $departamentos => $totalSolicitudes) {
                                if ($departamento == $departamentos) {
                                    // Calcular el promedio
                                    $promedio =  round($totalSolicitudes / $mesesConInformacion);
                                    $total_solicitudes += $totalSolicitudes;
                                    $sumpromedio += $promedio;

                                    $promediDic = ($elementoDic / $totalSolicitudes) - 1;
                                    // Convertir a porcentaje
                                    $valorPorcentaje = round($promediDic * 100);

                                    $acumuladoSolicitud = $dataStationery["solicitudes_total"];

                                    $acumuladoTotal = round(($totalSolicitudes / $acumuladoSolicitud) * 100);




                                    echo "<td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'>{$totalSolicitudes}</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'>{$promedio}</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'>{$valorPorcentaje} % </td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'>{$acumuladoTotal} % </td>";
                                }
                            }
                        }
                        ?>
                    </tr>

                <?php

                    $cont++;
                } ?>
                <tr>
                    <td style="text-align:center; font-size: 13px;">Grand Total </td>

                    <?php

                    $contfinal = 1;
                    foreach ($dataStationery["total_solicitudes"] as $departamento => $meses) {

                        if ($contfinal == $dataValija["total_mes"]) {
                            $totalDic = $meses;
                        }

                        $valor = ($meses != '0, 0, 0%') ? $meses : "0";

                        if ($valor == '0') { ?>
                            <!--  <td style='background-color:#D9D9D9;text-align: center;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;'><?php //$valor 
                                                                                                                                                                                                                            ?></td> -->
                        <?php  } else { ?>


                            <td style="background-color:#D9D9D9;">
                                <table>
                                    <tr>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $meses; ?></td>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $dataCoffe["total_atendidas"][$departamento]; ?></td>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">
                                            <?php
                                            $totalPorcentaje = ($dataStationery["total_atendidas"][$departamento] != '0, 0, 0%') ? round(($dataStationery["total_atendidas"][$departamento] / $meses) * 100, 0) : 0;
                                            echo $totalPorcentaje . '%';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                    <?php }

                        $contfinal++;
                    }

                    $promedioDic = ($totalDic / $total_solicitudes) - 1;
                    // Convertir a porcentaje
                    $totalPorcentaje = round($promedioDic * 100);

                    $total2 = ($total_solicitudes / $total_solicitudes) * 100;
                    ?>
                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $total_solicitudes ?> </td>
                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $sumpromedio ?> </td>
                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $totalPorcentaje . '%' ?> </td>
                    <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'><?= $total2 . '%' ?> </td>
                </tr>


            </tbody>


        </table>

        <table style="margin:left;margin-top:10px;">
            <tr>
                <td class="title_table" style="padding: 2px;">Areas</td>
                <td class="title_table">Pending</td>
                <td class="title_table">Approved</td>
                <td class="title_table">Declined</td>
                <td class="title_table">Total</td>
            </tr>
            <?php
            $fila_total = count($dataStationery["totales_areas"]);
            foreach ($dataStationery["totales_areas"] as $key => $value) {

                $color = ($key === $fila_total - 1) ? '#d9d9d9' : '#fff';

                $departamentos = (empty($value->Departamento)) ? "Grand Total" : $value->Departamento;
                echo "<tr style='font-size: 13px;'>
                                <td class='area' style='vertical-align: middle;padding: 1.8px;'>{$departamentos}</td>
                                <td style='vertical-align: middle; background-color:{$color}'>{$value->Pendiente}</td>
                                <td style='vertical-align: middle; background-color:{$color}'>{$value->Autorizada}</td>
                                <td style='vertical-align: middle; background-color:{$color}'>{$value->Rechazada}</td>
                                <td style='vertical-align: middle; background-color:#D9D9D9'>{$value->suma_total}</td>
                             </tr>";
            }



            ?>
        </table>



    </div>



    <div class="container">


        <!--    <span>January a October 2023</span> -->
        <table style="width:100%;">
            <tr>
                <td style="border:none;vertical-align: middle;padding:4px;height:1px;width:60%;color:#B3B2B2;font-size:16px;"><span><b>FINANCE DIRECTION (TICKET´S IT)</b></span></td>
                <td style="border:none;vertical-align: middle;padding:4px;height:1px;width:20%;"></td>
                <td style="border:none;vertical-align: middle;padding:4px;height:1px;width:20%;"></td>
            </tr>
        </table>



        <table class="table_uno">
            <thead class="unir">
                <tr>

                    <th colspan="" class="servicios_totales">Total services</th>
                    <?php
                    $meses = [
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December'
                    ];

                    for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) {
                        // Obtener el nombre del mes correspondiente
                        $ultimoMes = $meses[$i];
                        echo "<th colspan='' class='title_table'>" . $meses[$i] . "</th>";
                    }

                    ?>
                    <th colspan="" class="title_table">Total</th>
                    <th colspan="" class="title_table">Monthly Average</th>
                    <th colspan="" class="title_table"><?= $ultimoMes ?> vs average</th>
                    <th colspan="" class="title_table">% Dist. by area</th>

                </tr>

            </thead>

            <tbody>

                <tr>
                    <td class='area' style='vertical-align: middle;'>Areas</td>
                    <?php  // Iterar sobre los meses
                    for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) { ?>
                        <td class='' style='vertical-align: middle;'>
                            <table class="table_dos" style="vertical-align: middle;font-size: 13px;width:100%;">
                                <tr style="vertical-align: middle;margin:auto;">
                                    <td style="margin:auto;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Plan</td>
                                    <td style="margin:auto;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Real</td>
                                    <td style="margin:auto;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">&nbsp;%&nbsp;&nbsp;</td>

                                </tr>
                            </table>
                        </td>
                    <?php } ?>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                    <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>

                </tr>



                <?php
                $sumpromedio = 0;
                $total_solicitudes = 0;
                $cont = 0;
                foreach ($dataTickets["resultados"] as $departamento => $meses) {
                    $mesesConInformacion = 0; // Inicializar la variable que contará los meses con información
                    $elementoDic = 0;

                    $mesesConInfo = 0; // Inicializar la variable que contará los meses con información

                    // Iterar sobre los meses
                    foreach ($meses as $mes => $informacion) {
                        // Verificar si hay información en el mes
                        if ($informacion != '0, 0, 0%') {
                            $mesesConInfo++;
                        }
                    }


                ?>
                    <tr>
                        <td class='area' style='vertical-align: middle;'><?= $departamento ?></td>

                        <?php  // Iterar sobre los meses
                        for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) {
                            // echo "hola ".$meses[$i]."<br>";
                            $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";

                            if ($valor == '0') { ?>
                                <td style='font-size: 13px;vertical-align: middle;'><?= $valor ?></td>
                            <?php  } else {
                            ?>
                                <td>

                                    <table class="table_dos" style="vertical-align: middle;font-size: 13px;text-align:center;width:100%;">


                                        <?php
                                        $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";
                                        $valoresSeparados = explode(', ', $valor);
                                        if ($i == $mesesConInfo) {
                                            $elementoDic = $valoresSeparados[0];
                                        }
                                        ?>
                                        <tr>

                                            <?php echo '<td style="margin:auto;text-align:center;border:none;width:auto;padding: 2.2px;padding-bottom:0px;color:#D9D9D9;padding-top: 0px;font-size: 13px;color: #000;">' . implode('</td><td style="margin:auto;text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">', $valoresSeparados) . '</td>'; ?>
                                        </tr>
                                    </table>

                                </td>
                        <?php

                                // Incrementar la variable si el valor no es cero
                                $mesesConInformacion++;
                            }
                        }
                        ?>
                        <?php
                        if ($cont <= 7) {

                            foreach ($dataTickets["total_areas"] as $departamentos => $totalSolicitudes) {
                                if ($departamento == $departamentos) {
                                    // Calcular el promedio
                                    $promedio =  round($totalSolicitudes / $mesesConInformacion);
                                    $total_solicitudes += $totalSolicitudes;
                                    $sumpromedio += $promedio;

                                    $promediDic = ($elementoDic / $totalSolicitudes) - 1;
                                    // Convertir a porcentaje
                                    $valorPorcentaje = round($promediDic * 100);

                                    $acumuladoSolicitud = $dataTickets["solicitudes_total"];

                                    $acumuladoTotal = round(($totalSolicitudes / $acumuladoSolicitud) * 100);



                                    echo " <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'> {$totalSolicitudes}</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'> {$promedio}</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'> {$valorPorcentaje} %</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'> {$acumuladoTotal} %</td>";
                                }
                            }
                        }
                        ?>
                    </tr>

                <?php

                    $cont++;
                } ?>
                <tr>
                    <td style="text-align:center; font-size: 13px;">Grand Total </td>

                    <?php

                    $contfinal = 1;
                    foreach ($dataTickets["total_solicitudes"] as $departamento => $meses) {

                        if ($contfinal == $dataValija["total_mes"]) {
                            $totalDic = $meses;
                        }

                        $valor = ($meses != '0, 0, 0%') ? $meses : "0";

                        if ($valor == '0') { ?>
                            <!--  <td style='background-color:#D9D9D9;text-align: center;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;'><?php //$valor 
                                                                                                                                                                                                                            ?></td> -->
                        <?php  } else { ?>


                            <td style="background-color:#D9D9D9;">
                                <table>
                                    <tr>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $meses; ?></td>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $dataTickets["total_atendidas"][$departamento]; ?></td>
                                        <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">
                                            <?php
                                            $totalPorcentaje = ($dataTickets["total_atendidas"][$departamento] != '0, 0, 0%') ? round(($dataTickets["total_atendidas"][$departamento] / $meses) * 100, 0) : 0;
                                            echo $totalPorcentaje . '%';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                    <?php }

                        $contfinal++;
                    }

                    /*             $promedioDic = ($totalDic / $total_solicitudes) - 1;
                    // Convertir a porcentaje
                    $totalPorcentaje = round($promedioDic * 100);

                    $total2 = ($total_solicitudes / $total_solicitudes) * 100; */

                    if ($total_solicitudes > 0) {
                        $promedioDic = ($totalDic / $total_solicitudes) - 1;
                        $totalPorcentaje = round($promedioDic * 100);

                        $total2 = ($total_solicitudes / $total_solicitudes) * 100; // siempre será 100
                    } else {
                        $promedioDic = 0;
                        $totalPorcentaje = 0;
                        $total2 = 0; // o 100, depende de tu lógica de negocio
                    }


                    echo "<td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'>{$total_solicitudes} </td>
                                <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'>{$sumpromedio} </td>
                                <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'>{$totalPorcentaje} %  </td>
                                <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'>{$total2} %  </td>";
                    ?>
                </tr>


            </tbody>


        </table>

        <table style="margin:left;margin-top:16px;">
            <tr>
                <td class="title_table" style="padding: 2px;">Areas</td>
                <td class="title_table">Pending</td>
                <td class="title_table">Approved</td>
                <td class="title_table">Declined</td>
                <td class="title_table">Total</td>
            </tr>
            <?php
            $fila_total = count($dataTickets["totales_areas"]);
            foreach ($dataTickets["totales_areas"] as $key => $value) :

                $color = ($key === $fila_total - 1) ? '#d9d9d9' : '#fff';

                $departamentos = (empty($value->Departamento)) ? "Grand Total" : $value->Departamento;

                echo " <tr style='font-size: 13px;'>
                                <td class='area' style='vertical-align: middle;padding: 1.8px;'> {$departamentos}</td>
                                <td style='vertical-align: middle; background-color:{$color}'> {$value->Pendiente}</td>
                                 <td style='vertical-align: middle; background-color:{$color}'> {$value->Autorizada}</td>
                                 <td style='vertical-align: middle; background-color:{$color}'> {$value->Rechazada}</td>
                                <td style='vertical-align: middle; background-color:#D9D9D9'>{$value->suma_total}</td>
                            </tr>";

            endforeach;  ?>
        </table>



    </div>





    <?php


    if ($dataTicketsSg["solicitudes_total"] != 0) { ?>

        <div class="titulos" style="page-break-before: always;">
            <!--    <span>January a October 2023</span> -->
            <table style="width:100%;">
                <tr>
                    <td style="border:none;vertical-align: middle;width:50%;color:#B3B2B2;font-size:16px;"><span><b>FINANCE DIRECTION (TICKET'S GENERAL SERVICES)</b></span></td>
                    <td style="border:none;vertical-align: middle;width:50%;"></td>

                </tr>
            </table>
        </div>

        <div class="container">

            <table class="table_uno">
                <thead class="unir">
                    <tr>

                        <th colspan="" class="servicios_totales">Total services</th>
                        <?php
                        $meses = [
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December'
                        ];

                        for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) {
                            // Obtener el nombre del mes correspondiente
                            $ultimoMes = $meses[$i];
                            echo "<th colspan='' class='title_table'>" . $meses[$i] . "</th>";
                        }

                        ?>
                        <th colspan="" class="title_table">Total</th>
                        <th colspan="" class="title_table">Monthly Average</th>
                        <th colspan="" class="title_table"><?= $ultimoMes ?> vs average</th>
                        <th colspan="" class="title_table">% Dist. by area</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>
                        <td class='area' style='vertical-align: middle;'>Areas</td>
                        <?php  // Iterar sobre los meses
                        for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) { ?>
                            <td class='' style='vertical-align: middle;'>
                                <table class="table_dos" style="vertical-align: middle;font-size: 13px;width:100%;">
                                    <tr style="vertical-align: middle;">
                                        <td style="margin:auto;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Plan</td>
                                        <td style="margin:auto;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">Real</td>
                                        <td style="margin:auto;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">&nbsp;%&nbsp;&nbsp;</td>

                                    </tr>
                                </table>
                            </td>
                        <?php } ?>
                        <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                        <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                        <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>
                        <td style="font-size: 13px;vertical-align: middle;width:auto;padding:1.2px;padding-bottom:0px;border: 0.3px solid #dddddd;color:#000;">---</td>

                    </tr>



                    <?php
                    $sumpromedio = 0;
                    $total_solicitudes = 0;
                    $cont = 0;
                    foreach ($dataTicketsSg["resultados"] as $departamento => $meses) {
                        $mesesConInformacion = 0; // Inicializar la variable que contará los meses con información
                        $elementoDic = 0;

                        $mesesConInfo = 0; // Inicializar la variable que contará los meses con información

                        // Iterar sobre los meses
                        foreach ($meses as $mes => $informacion) {
                            // Verificar si hay información en el mes
                            if ($informacion != '0, 0, 0%') {
                                $mesesConInfo++;
                            }
                        }


                    ?>
                        <tr>
                            <td class='area' style='vertical-align: middle;'><?= $departamento ?></td>

                            <?php  // Iterar sobre los meses
                            for ($i = $dataValija["total_mes"]; $i <= $dataValija["total_mes"]; $i++) {
                                // echo "hola ".$meses[$i]."<br>";
                                $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";

                                if ($valor == '0') { ?>
                                    <td style='font-size: 13px;vertical-align: middle;'><?= $valor ?></td>
                                <?php  } else {
                                ?>
                                    <td>

                                        <table class="table_dos" style="vertical-align: middle;font-size: 13px;text-align:center;width:100%;">


                                            <?php
                                            $valor = ($meses[$i] != '0, 0, 0%') ? $meses[$i] : "0";
                                            $valoresSeparados = explode(', ', $valor);
                                            if ($i == $mesesConInfo) {
                                                $elementoDic = $valoresSeparados[0];
                                            }
                                            ?>
                                            <tr style="">
                                                <td style="margin:auto;text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;padding-top: 1px;font-size: 13px;color: #000;"><?= $valoresSeparados[0] ?></td>
                                                <td style="margin:auto;text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;padding-top: 1px;font-size: 13px;color: #000;"><?= $valoresSeparados[1] ?></td>
                                                <td style="margin:auto;text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;padding-top: 1px;font-size: 13px;color: #000;"><?= $valoresSeparados[2] ?></td>
                                                <?php //echo '<td style="text-align:center;margin:auto;text-align:center;border:none;vertical-align:middle;padding: 2.8px;font-size: 13px;color: #000;">' . implode('</td><td style="margin:auto;text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;padding-top: 1px;font-size: 13px;color: #000;">', $valoresSeparados) . '</td>'; 
                                                ?>
                                            </tr>
                                        </table>

                                    </td>
                            <?php

                                    // Incrementar la variable si el valor no es cero
                                    $mesesConInformacion++;
                                }
                            }
                            ?>
                            <?php
                            if ($cont <= 7) {

                                foreach ($dataTicketsSg["total_areas"] as $departamentos => $totalSolicitudes) {
                                    if ($departamento == $departamentos) {
                                        // Calcular el promedio
                                        $promedio =  round($totalSolicitudes / $mesesConInformacion);
                                        $total_solicitudes += $totalSolicitudes;
                                        $sumpromedio += $promedio;

                                        $promediDic = ($elementoDic / $totalSolicitudes) - 1;
                                        // Convertir a porcentaje
                                        $valorPorcentaje = round($promediDic * 100);

                                        $acumuladoSolicitud = $dataTicketsSg["solicitudes_total"];

                                        $acumuladoTotal = round(($totalSolicitudes / $acumuladoSolicitud) * 100);



                                        echo " <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'> {$totalSolicitudes}</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'> {$promedio}</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'> {$valorPorcentaje} %</td>
                                                <td style='font-size: 13px; vertical-align: middle; background-color:#D9D9D9;'> {$acumuladoTotal} %</td>";
                                    }
                                }
                            }
                            ?>
                        </tr>

                    <?php

                        $cont++;
                    } ?>
                    <tr>
                        <td style="text-align:center; font-size: 13px;">Grand Total </td>

                        <?php

                        $contfinal = 1;
                        foreach ($dataTicketsSg["total_solicitudes"] as $departamento => $meses) {

                            if ($contfinal == $dataValija["total_mes"]) {
                                $totalDic = $meses;
                            }

                            $valor = ($meses != '0, 0, 0%') ? $meses : "0";

                            if ($valor == '0') { ?>
                                <!--  <td style='background-color:#D9D9D9;text-align: center;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;'><?php //$valor 
                                                                                                                                                                                                                                ?></td> -->
                            <?php  } else { ?>


                                <td style="background-color:#D9D9D9;">
                                    <table>
                                        <tr>
                                            <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $meses; ?></td>
                                            <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;"><?= $dataTicketsSg["total_atendidas"][$departamento]; ?></td>
                                            <td style="text-align: center;border:none;vertical-align: middle;width:auto;padding: 2.8px;padding-bottom:0px;color:#D9D9D9;padding-top: 1px;font-size: 13px;color: #000;">
                                                <?php
                                                $totalPorcentaje = ($dataTicketsSg["total_atendidas"][$departamento] != '0, 0, 0%') ? round(($dataTicketsSg["total_atendidas"][$departamento] / $meses) * 100, 0) : 0;
                                                echo $totalPorcentaje . '%';
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                        <?php }

                            $contfinal++;
                        }

                        $promedioDic = ($totalDic / $total_solicitudes) - 1;
                        // Convertir a porcentaje
                        $totalPorcentaje = round($promedioDic * 100);

                        $acumuladoSolicitud = $dataTicketsSg["solicitudes_total"];

                        if ($total_solicitudes != 0) {
                            $total2 = ($total_solicitudes / $total_solicitudes) * 100;
                        } else {
                            $total2 = $total_solicitudes  * 100;
                        }


                        echo "<td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'>{$total_solicitudes} </td>
                                <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'>{$sumpromedio} </td>
                                <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'>{$totalPorcentaje} %  </td>
                                <td style='font-size: 13px;vertical-align: middle; background-color:#D9D9D9;'>{$total2} %  </td>";
                        ?>
                    </tr>


                </tbody>


            </table>

            <table style="margin: left;margin-top:16px;">
                <tr>
                    <td class="title_table" style="padding:2px;">Areas</td>
                    <td class="title_table">Pending</td>
                    <td class="title_table">Approved</td>
                    <td class="title_table">Declined</td>
                    <td class="title_table">Total</td>
                </tr>
                <?php
                $fila_total = count($dataTicketsSg["totales_areas"]);
                foreach ($dataTicketsSg["totales_areas"] as $key => $value) :

                    $color = ($key === $fila_total - 1) ? '#d9d9d9' : '#fff';

                    $departamentos = (empty($value->Departamento)) ? "Grand Total" : $value->Departamento;

                    echo " <tr style='font-size: 13px;'>
                                <td class='area' style='vertical-align: middle;padding: 1.8px;'> {$departamentos}</td>
                                <td style='vertical-align: middle; background-color:{$color}'> {$value->Pendiente}</td>
                                 <td style='vertical-align: middle; background-color:{$color}'> {$value->Autorizada}</td>
                                 <td style='vertical-align: middle; background-color:{$color}'> {$value->Rechazada}</td>
                                <td style='vertical-align: middle; background-color:#D9D9D9'>{$value->suma_total}</td>
                            </tr>";

                endforeach;  ?>
            </table>


        </div>

    <?php }  ?>

    <div class="titulos">

        <!--    <span>January a October 2023</span> -->
        <table style="width:100%;">
            <tr>
                <td style="border:none;vertical-align: middle;width:33%;color:#B3B2B2;font-size:16px;"><span><b>FINANCE DIRECTION (WORK ACCIDENTS)</b></span></td>
                <td style="border:none;vertical-align: middle;width:33%;"></td>
                <td style="border:none;vertical-align: middle;width:33%;"></td>
            </tr>
        </table>
    </div>

    <div class="container">

        <?php
        // var_dump($request);
        ?>

        <table class="table_uno">
            <thead class="unir">
                <tr>

                    <th colspan="" class="servicios_totales" style="font-size: 13px;">Total services</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">January</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">February</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">March</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">April</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">May</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">June</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">July</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">August</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">September</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">October</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">November</th>
                    <th colspan="" class="title_table" style="font-size: 13px;">December</th>


                </tr>

            </thead>

            <tbody>


                <tr>
                    <td style="font-size: 13px;vertical-align: middle;">No. Accidents</td>
                    <?php
                    $mesesConDatos = []; // Almacena los meses para los que tienes datos

                    // Inicializa un array para cada mes con valor 0
                    $mesesData = array_fill(1, 12, 0);

                    foreach ($dataAccidents as $value) {
                        $mesActual = $value->mes;
                        $totalRegistros = $value->total_registros;

                        // Almacena el valor para el mes actual
                        $mesesData[$mesActual] = $totalRegistros;

                        // Agrega el mes actual a la lista de meses con datos
                        $mesesConDatos[] = $mesActual;
                    }

                    // Imprime los valores en la tabla
                    foreach ($mesesData as $valorMes) {
                        echo "<td style='font-size: 13px;vertical-align: middle;'>$valorMes</td>";
                    }
                    ?>
                </tr>


            </tbody>


        </table>



    </div>

    <div class="titulos" >

        <!--    <span>January a October 2023</span> -->
        <table style="width:100%;">
            <tr>
                <td style="border:none;vertical-align: middle;width:50%;color:#B3B2B2;font-size:16px;"><span><b>FINANCE DIRECTION (VIATICS)</b></span></td>
                <td style="border:none;vertical-align: middle;width:50%;"></td>

            </tr>
        </table>
    </div>

    <div class="container" >

        <?php
        // var_dump($request);
        ?>

        <table style="margin-right: 30px;">
            <tr>
                <td style="width:25%;vertical-align: middle;border:0px;"></td>
                <td style="width:25%;vertical-align: middle;border:0px;">
                    <table style="margin: auto;">
                        <tr>
                            <td class="title_table" style="padding: 2px;font-size: 13px;">Areas</td>
                            <td class="title_table" style="font-size: 13px;">Assigned</td>
                            <td class="title_table" style="font-size: 13px;">Checked</td>
                            <td class="title_table" style="font-size: 13px;">To be verified</td>

                        </tr>
                        <?php
                        $fila_total = count($dataExpenses);
                        foreach ($dataExpenses as $key => $value) {

                            $color = ($key === $fila_total - 1) ? '#d9d9d9' : '#fff';
                            $color2 = (empty($value->Departamento)) ? "background-color:#fff;color:#000;border: 0.3px solid #dddddd;" : "";
                            $departamentos = (empty($value->Departamento)) ? "Total Viaticos" : $value->Departamento;
                            echo "<tr style='font-size: 13px'>
                        <td class='area' style='vertical-align: middle;padding: 2px;{$color2}'>{$departamentos}</td>
                        <td style='vertical-align: middle;padding: 2px; background-color:{$color}'>$" . number_format($value->suma_total, 2) . "</td>
                        <td style='vertical-align: middle;padding: 2px; background-color:{$color}'>$" . number_format($value->suma_comprobado, 2) . "</td>
                        <td style='vertical-align: middle;padding: 2px; background-color:{$color}'>$" . number_format($value->diferencia, 2) . "</td>
                        
                     </tr>";
                        }



                        ?>
                    </table>
                </td>
                <td style="width:25%;vertical-align: middle;border:0px;"></td>
                <td style="width:25%;vertical-align: middle;border:0px;">
                    <table style="margin: auto;">
                        <tr>
                            <td class="title_table" style="padding: 2px;font-size: 13px;">Areas</td>
                            <td class="title_table" style="font-size: 13px;">Assigned</td>
                            <td class="title_table" style="font-size: 13px;">Checked</td>
                            <td class="title_table" style="font-size: 13px;">To be verified</td>
                        </tr>
                        <?php
                        $fila_total = count($dataTravels);
                        foreach ($dataTravels as $key => $value) {

                            $color = ($key === $fila_total - 1) ? '#d9d9d9' : '#fff';
                            $color2 = (empty($value->Departamento)) ? "background-color:#fff;color:#000;border: 0.3px solid #dddddd;" : "";

                            $departamentos = (empty($value->Departamento)) ? "Total Gastos" : $value->Departamento;
                            echo "<tr style='font-size: 13px;'>
                    <td class='area' style='vertical-align:middle;padding:2px;{$color2}'>{$departamentos}</td>
                    <td style='vertical-align: middle;padding: 2px; background-color:{$color}'>$" . number_format($value->suma_total, 2) . "</td>
                    <td style='vertical-align: middle;padding: 2px; background-color:{$color}'>$" . number_format($value->suma_comprobado, 2) . "</td>
                    <td style='vertical-align: middle;padding: 2px; background-color:{$color}'>$" . number_format($value->diferencia, 2) . "</td>
                     </tr>";
                        }



                        ?>
                    </table>
                </td>
            </tr>
        </table>



    </div>



    <page_footer>
        <div style="width:100%;">
            <table style="width:100%;">
                <tr>
                    <td style="width:33%;border:0px;"></td>
                    <td style="width:33%;border:0px;">

                    </td>
                    <td style="width:33%;border:0px;text-align:right;">

                        <!--  <img src="<?php //base_url(); 
                                        ?>/public/images/GrupoW-Registrada.png" style="width:210px;" alt="Grupo Walworth"> -->

                    </td>
                </tr>
            </table>
        </div>

        <div class="titulos">

            <!--    <span>January a October 2023</span> -->
            <table style="width:100%;">
                <tr>
                    <td style="border:none;vertical-align: middle;padding:1.5px;height:6px;width:33%;background-color:#da1f1c;"></td>
                    <td style="border:none;vertical-align: middle;padding:1.5px;height:6px;width:33%;background-color:#da1f1c;"></td>
                    <td style="border:none;vertical-align: middle;padding:1.5px;height:6px;width:33%;background-color:#da1f1c;"></td>
                </tr>
            </table>
        </div>

    </page_footer>






</page>