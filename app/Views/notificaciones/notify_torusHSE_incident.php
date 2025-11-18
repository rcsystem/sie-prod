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

        .evidence {
            width: 100%;
            height: 300px;
        }
    </style>
</head>

<body>
    <?php if ($RH) { ?>
        <p><?= $incidencia->sancion ?></p>
    <?php } ?>
    <table class="tbl-1" cellspacing="0" cellpadding="10">
        <tbody>
            <tr class="tittle-dark">
                <td colspan="4" style="font-weight:bold">
                    <p>NOTIFICACION DE <?= $incidencia->tipo ?> DETECTADA POR HSE</p>
                </td>
            </tr>
            <?php if ($incidencia->type == 1) { ?>
                <tr style="text-align: center;">
                    <td class="td-tittle-data" colspan="2">EVIDENCIA</td>
                    <td class="td-data" style="font-size: 15px;" colspan="2"><?= $incidencia->name_user ?></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="td-tittle-data" style="width: 40%;" rowspan="3" colspan="2"><img class="evidence" src="<?= $imagen ?>" alt="EVIDENCIA"></td>
                    <td class="td-tittle-data">Incidencia:</td>
                    <td class="td-data"><?= $incidencia->categoria ?></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="td-tittle-data">Requiere concientización:</td>
                    <td class="td-data"><?= $incidencia->retro ?></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="td-tittle-data">Gravedad de Incidencia:</td>
                    <td class="td-data"><?= $incidencia->nivel ?></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="td-tittle-data"><?= $incidencia->dia ?></td>
                    <td class="td-tittle-data"><?= $incidencia->hora ?></td>
                    <td class="td-data" style="font-size: 15px;" colspan="2"><?= $incidencia->departament ?></td>
                </tr>
            <?php } else { ?>
                <tr style="text-align: center;">
                    <td class="td-tittle-data" colspan="2">EVIDENCIA</td>
                    <td class="td-data" style="font-size: 15px;" colspan="2"><?= $incidencia->departament ?></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="td-tittle-data" style="width: 40%;" rowspan="2" colspan="2"><img class="evidence" src="<?= $imagen ?>" alt="EVIDENCIA"></td>
                    <td class="td-tittle-data">Incidencia:</td>
                    <td class="td-data"><?= $incidencia->categoria ?></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="td-tittle-data">Requiere concientización:</td>
                    <td class="td-data"><?= $incidencia->retro ?></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="td-tittle-data"><?= $incidencia->dia ?></td>
                    <td class="td-tittle-data"><?= $incidencia->hora ?></td>
                    <td class="td-tittle-data">Gravedad de Incidencia:</td>
                    <td class="td-data"><?= $incidencia->nivel ?></td>
                </tr>
            <?php } ?>
            <tr class="tittle-info">
                <td colspan="4" style="font-weight:bold">
                    DETALLES DE INCIDENCIA
                </td>
            </tr>
            <tr>
                <td colspan="4" class="td-data">
                    <p><?= $incidencia->description ?></p>
                </td>
            </tr>
            <tr></tr>
            <tr class="tbl-1-pie">
                <td colspan="4" style="font-weight:bold;color:#FFF;">Create by Walworth IT</td>
                <!-- <td colspan="2" style="text-align: end;">
                    <a class="btn-sie" href="https://sie.grupowalworth.com/viajes/comprobacion" target="_blank">Ver Mis Comprobaciones</a>
                </td> -->
            </tr>
        </tbody>
    </table>
</body>

</html>