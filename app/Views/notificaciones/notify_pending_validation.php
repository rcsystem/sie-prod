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

        .info {
            background-color: #17a2b8;
            width: auto;
            color: #fff;
        }

        .primary {
            width: auto;
            color: #fff;
            background-color: #007bff;
        }
    </style>
    <?php

    ?>

</head>

<body>
    <table style="border-radius: 2px;
                  margin: 2em 0 3em;
                  min-width: 400px;
                  border: 1px solid #eee;
                  border-collapse: separate;
                  border-spacing: 1px;" cellspacing="0" cellpadding="10">
        <tbody>
            <tr style="background-color: #5C636A;
                       color: #fff;
                       width: min-content;
                       text-align: center;">
                <td colspan="4" style="font-weight:bold">
                    <p>COMPROBACIONES PENDIENTES</p>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td style=" background-color: #EEEEEE; font-weight: bold; width: auto;">FECHA DE MOVIMIENTO</td>
                <td style=" background-color: #EEEEEE; font-weight: bold; width: auto;">LUGAR</td>
                <td style=" background-color: #EEEEEE; font-weight: bold; width: auto;">MONTO</td>
                <td style=" background-color: #EEEEEE; font-weight: bold; width: auto;">ESTADO</td>
            </tr>
            <?php for ($i = 0; $i < count($activos); $i++) { ?>
                <?php if ($i > 0) { ?>
                    <tr style="background:#BDBDBD;width: min-content;">
                        <td colspan="4" style="padding: 1px;"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="width: auto; background-color: #FBFBFB;"><?= $activos[$i]->fecha; ?></td>
                    <td style="width: auto; background-color: #FBFBFB;"><?= $activos[$i]->lugar; ?></td>
                    <td style="width: auto; background-color: #FBFBFB;"><?= $activos[$i]->monto; ?></td>
                    <td class="<?= $activos[$i]->estado_color; ?>"><?= $activos[$i]->estado_txt; ?></td>
                </tr>
            <?php } ?>
            <?php if ($deuda) { ?>
                <tr style="background-color: #BE2423;color: #fff;width: min-content;text-align: center;">
                    <td colspan="4" style="font-weight:bold">
                        <p>DESCUENTOS</p>
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" background-color: #EEEEEE; font-weight: bold; width: auto;">FECHA DE MOVIMIENTO</td>
                    <td style=" background-color: #EEEEEE; font-weight: bold; width: auto;" colspan="2">LUGAR</td>
                    <td style=" background-color: #EEEEEE; font-weight: bold; width: auto;">MONTO</td>
                </tr>
                <?php for ($i = 0; $i < count($deuda); $i++) { ?>
                    <?php if ($i > 0) { ?>
                        <tr style="background:#BDBDBD;width: min-content;">
                            <td colspan="4" style="padding: 1px;"></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td style="width: auto; background-color: #FBFBFB;"><?= $deuda[$i]->fecha; ?></td>
                        <td style="width: auto; background-color: #FBFBFB;" colspan="2"><?= $deuda[$i]->lugar; ?></td>
                        <td style="width: auto; background-color: #FBFBFB;"><?= $deuda[$i]->monto; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>

            <tr></tr>
            <tr style="background: #5C636A; color: #fff;">
                <td colspan="2" style="font-weight:bold;color:#FFF;">Create by Walworth IT</td>
                <td colspan="2" style="text-align: end;">

                    <a style="background-color: #1F2D3D;
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
                                text-transform: capitalize;" href="https://sie.grupowalworth.com/viajes/comprobacion" target="_blank">
                        Ver Mis Comprobaciones
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>