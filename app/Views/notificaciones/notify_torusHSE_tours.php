<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Helvetica'
        }

        .tbl-1 {
            border-radius: 2px;
            margin: 2em 0 3em;
            min-width: 400px;
            width: 80%;
            border: 1px solid #eee;
            border-collapse: separate;
            border-spacing: 1px
        }

        .tbl-1-pie {
            background: #5C636A;
            color: #fff;
        }

        .tittle-dark {
            background-color: #5C636A;
            color: #fff;
            width: min-content;
            text-align: center;
        }

        .tittle-danger {
            background-color: #BE2423;
            color: #fff;
            width: min-content;
            text-align: center;
        }

        .td-tittle-data {
            background-color: #EEEEEE;
            font-weight: bold;
            width: auto;
        }

        .td-data {
            width: auto;
            background-color: #FBFBFB;
        }

        .td-data-s {
            width: 10%;
            background-color: #FBFBFB;
        }

        .btn-sie {
            background-color: #1F2D3D;
            border: solid 1px #1F2D3D;
            border-radius: 5px;
            box-sizing: border-box;
            color: #fff !important;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 10px 19px;
            margin-top: -15px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .tittle-info {
            background-color: #17a2b8;
            width: auto;
            color: #fff;
            text-align: center;
        }

        .primary {
            width: auto;
            color: #fff;
            background-color: #007bff;
        }
    </style>
    <?php
    $opcIcon = [1 => '✓', 0 => '✘', 2 => '--'];
    $opcTXT = [1 => 'SI', 0 => 'NO', 2 => 'N/A'];
    ?>

</head>

<body>
    <table style="border-radius: 2px;
            margin: 2em 0 3em;
            min-width: 400px;
            width: 80%;
            border: 1px solid #eee;
            border-collapse: separate;
            border-spacing: 1px" cellspacing="0" cellpadding="10">
        <tbody>
            <tr style="background-color: #5C636A;
                        color: #fff;
                        width: min-content;
                        text-align: center;">
                <td colspan="6" style="font-weight:bold">
                    <p>REPORTE DE RECORRIDOS HSE</p>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #EEEEEE;
                            font-weight: bold;
                            width: auto;">DEPARTAMENTO</td>
                <td style="width: auto;
            background-color: #FBFBFB;" colspan="3"><?= $recorrido["departament"] ?></td>
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;">CALIFICACION</td>
                <td style="width: auto;
            background-color: #FBFBFB;" style="font-size: 20px;"><?= $recorrido["qualification"] ?></td>
            </tr>
            <tr style="background-color: #17a2b8;
            width: auto;
            color: #fff;
            text-align: center;">
                <td colspan="6" style="font-weight:bold;padding-top: 13px;padding-bottom: 13px;">
                    PERSONAL EN LÍNEA / ÁREA
                </td>
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">EPP</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["use_epp"]) ? '✓' : '✘' ?></td>
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">UNIFORME</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["use_uniform"]) ? '✓' : '✘' ?></td>
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">USO DE CELULAR</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["use_cel"]) ? 'SI' : 'N0' ?></td>
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">USO DE BISUTERIA</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["use_jewelry"]) ? 'SI' : 'N0' ?></td>
            </tr>
            <tr style="text-align: center;">
                <td colspan="2">CABELLO RECOGIDO</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["tied_hair"]) ? 'SI' : 'N0' ?></td>
                <td colspan="3"></td>
            </tr>
            <tr style="background-color: #17a2b8;
            width: auto;
            color: #fff;
            text-align: center;">
                <td colspan="6" style="font-weight:bold;padding-top: 13px;padding-bottom: 13px;">
                    LÍNEA / ÁREA EN GENERAL
                </td>
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">ORDEN Y LIMPIEZA</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["order_clean"]) ? '✓' : '✘' ?></td>
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">ACTOS INSEGUROS</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["unsafe_acts"]) ? 'SI' : 'N0' ?></td>
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">CONDICIONES INSEGURAS</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["unsafe_conditions"]) ? 'SI' : 'N0' ?></td>
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">TRABAJOS DE MANTENIMIENTO</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["maintenance_work"]) ? 'SI' : 'N0' ?></td>
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">MANEJO DE RESIDUOS</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["waste_management"]) ? '✓' : '✘' ?></td>
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">TRABAJOS PELIGROSOS</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["dangerous_works"]) ? 'SI' : 'N0' ?></td>
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">PERMISO DE TRABAJO</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= $opcTXT[$recorrido["permiss_works"]] ?></td>
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="3"></td>
            </tr>
            <tr style="background-color: #17a2b8;
            width: auto;
            color: #fff;
            text-align: center;">
                <td colspan="6" style="font-weight:bold;padding-top: 13px;padding-bottom: 13px;">
                    PERSONAL EXTERNO
                </td>
            </tr>
            <tr style="text-align: center;">
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">PERSONAL AJENO A INVAL</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= ($recorrido["personal_no_inval"]) ? 'SI' : 'N0' ?></td>
                <td style="background-color: #EEEEEE;
            font-weight: bold;
            width: auto;" colspan="2">EPP</td>
                <td style="width: 10%;
            background-color: #FBFBFB;"><?= $opcIcon[$recorrido["epp_no_inval"]] ?></td>
            </tr>

            <?php if ($recorrido["observation"]) { ?>
                <tr style="background-color: #17a2b8;
            width: auto;
            color: #fff;
            text-align: center;">
                    <td colspan="6" style="font-weight:bold">
                        OBSERVACIONES
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="width: auto;
            background-color: #FBFBFB;">
                        <p><?= $recorrido["observation"] ?></p>
                    </td>
                </tr>
            <?php } ?>
            <tr></tr>
            <tr style="background: #5C636A;color: #fff;">
                <td colspan="6" style="font-weight:bold;color:#FFF;">Create by Walworth IT</td>
                <!-- <td colspan="2" style="text-align: end;">
                    <a class="btn-sie" href="https://sie.grupowalworth.com/viajes/comprobacion" target="_blank">Ver Mis Comprobaciones</a>
                </td> -->
            </tr>
        </tbody>
    </table>
</body>

</html>