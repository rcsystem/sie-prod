<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitud de Cheque / Transferencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 95%;
            margin: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .header .logo {
            width: 230px;
            height: 38px;
        }

        .header .logo2 {
            width: 68%;
            height: 48px;
            float: right;
            margin-top: -40px;
            margin-right: -5px;
        }

        .header-titulo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 97%;
            /* border: 1px solid #000; */
            padding: 0px;

        }

        .titulo,
        .fecha {
            flex: 1;
        }

        .titulo {
            text-align: left;
        }

        .fecha {
            text-align: right;
            margin-top: -30px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group-1 {
            background-color: #ccc;
            border: 1px solid #ccc;
            padding-bottom: 12px;
            margin-bottom: 8px;
        }

        .label {
            font-weight: bold;
            margin-top: 10px;
            margin-left: 10px;

        }

        .input {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            box-sizing: border-box;
            /* Asegura que el ancho incluya los bordes */
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .contenedor {
            width: 97%;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 8px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .contenedor2 {
            width: 97%;
            margin: 0 auto;
            border: 1px solid#c0c0c0;
            padding: 8px;
            border-radius: 10px;

        }

        .contendor-item {
            border: 1px solid #ccc;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        p {
            margin-left: 10px;
        }

        .fila-grande td {
            height: 110px;
            /* Altura personalizada para filas específicas */
        }

        .table td {
            width: 33.33%;

            padding: 0;
            box-sizing: border-box;
            margin: 0;
            /* Elimina margenes */
            line-height: normal;
            /* Asegura que la altura de línea no afecte el ancho */
            text-align: center;
            font-size: 11px;

        }
    </style>
</head>



<body>
    <div class="container">
        <div class="contenedor">
            <!-- Encabezado con logos -->
            <div class="header">
                <img class="logo" src="https://sie.grupowalworth.com/public/images/logo_Walworth.png" alt="Logo Walworth" />
                <img class="logo2" src="https://sie.grupowalworth.com/public/images/imagen-pago.png" alt="Logo 2 Walworth" />
            </div>

            <!-- Título y fecha -->
            <div class="header-titulo">
                <div class="titulo">
                    <h5>SOLICITUD DE CHEQUE / TRANSFERENCIA</h5>
                </div>
                <div class="fecha">
                    <span>Fecha: <?php
                                    // Definir los meses en español
                                    $meses = [
                                        1 => 'enero',
                                        2 => 'febrero',
                                        3 => 'marzo',
                                        4 => 'abril',
                                        5 => 'mayo',
                                        6 => 'junio',
                                        7 => 'julio',
                                        8 => 'agosto',
                                        9 => 'septiembre',
                                        10 => 'octubre',
                                        11 => 'noviembre',
                                        12 => 'diciembre'
                                    ];

                                    // Convertir la fecha en objeto DateTime
                                    $fechaObj = DateTime::createFromFormat('Y-m-d H:i:s', $created_at);

                                    // Extraer día, mes y año
                                    $dia = $fechaObj->format('d');
                                    $mes = (int) $fechaObj->format('m'); // Convertir a número entero
                                    $año = $fechaObj->format('Y');

                                    // Construir la fecha en español
                                    $fecha = "{$dia} de {$meses[$mes]} de {$año}";
                                    echo $fecha;
                                    ?></span>
                </div>
            </div>
        </div>

        <div class="contenedor2">
            <!-- Campos del formulario -->
            <div class="contendor-item">
                <div class="form-group-1">
                    <label class="label">Tipo de pago:</label>
                </div>
                <div>

                    <p class="input"><?= $tipo_pago ?></p>
                </div>
            </div>
            <div class="contendor-item">
                <div class="form-group-1">
                    <label class="label">Favor de expedir a nombre de:</label>
                </div>
                <p class="input"><?= $nombre_empresa ?></p>
            </div>
            <div class="contendor-item">
                <div class="form-group-1">
                    <label class="label">Cuenta bancaria y/o CLABE interbancaria:</label>
                </div>
                <div style="text-align: center;">
                    <span class="input"><?= $banco ?></span><br>
                    <span class="input">CUENTA: <?= $cuenta ?></span><br>
                    <span class="input">CLABE: <?= $clabe ?></span>
                </div>
            </div>
            <div class="contendor-item">
                <div class="form-group-1">
                    <label class="label">Por la cantidad de:</label>
                </div>
                <p class="input">$<?php

                                    // Eliminar el símbolo de dólar para poder convertir a número
                                    $numero = floatval(str_replace("$", "", $cantidad));

                                    // Formatear con separadores de miles y dos decimales
                                    $cantidad =  number_format($numero, 2, '.', ',');
                                    echo $cantidad;
                                    ?></p>
            </div>
            <div class="contendor-item">
                <div class="form-group-1">
                    <label class="label">Con letra:</label>
                </div>
                <p class="input" style="font-size: 11px;"><?= $cantidad_letra ?></p>
            </div>
            <div class="contendor-item">
                <div class="form-group-1">
                    <label class="label">Por concepto de:</label>
                </div>
                <p class="input"><?= $concepto ?></p>
            </div>
            <!-- Tabla de autorizaciones -->
            <table class="table" style="width:100%;">
                <tr style="text-align: center;">
                    <th>SOLICITANTE</th>
                    <th>AUTORIZACIÓN</th>
                    <th>AUTORIZACIÓN</th>
                </tr>
                <tr class="fila-grande" style="width: 100%;">
                    <td style="vertical-align: bottom;"><span><?= ucwords(strtolower($user_name)); ?></span></td>
                    <td style="vertical-align: bottom;">
                        <span>
                            <?php
                            // ✅ Insertar firma solo si es la primera página
                            // Puedes mejorar el switch usando un array asociativo para mayor claridad y escalabilidad
                            $autorizadores = [
                                50  => 'Lic. Guadalupe Martínez Rivera',
                                265 => 'Lic. Guadalupe Martínez Rivera',
                                267 => 'Lic. Guadalupe Martínez Rivera',
                            ];
                            $autorizador = $autorizadores[$id_user] ?? '';
                            echo $autorizador; // Muestra el nombre del autorizador
                            ?>
                            
                        </span>
                    </td>
                    <td style="vertical-align: bottom;"><span>Enrico Pérez - Gerente de Administración y Finanzas</span></td>
                </tr>
            </table>
        </div>
        <div style="width: 100%;text-align:right;font-size:10px;">
            <span>FCU-01 REV. 1</span>
        </div>
    </div>
</body>

</html>