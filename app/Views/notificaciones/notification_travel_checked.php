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
    <table class="tbl-1" cellspacing="0" cellpadding="10">
        <tbody>
            <tr class="tittle-dark">
                <td colspan="4" style="font-weight:bold">
                    <p>COMPROBACION REALIZADA</p>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td class="td-tittle-data">FECHA DE MOVIMIENTO</td>
                <td class="td-tittle-data">LUGAR</td>
                <td class="td-tittle-data">MONTO</td>
                <td class="td-tittle-data">ESTADO</td>
            </tr>
          
                <tr>
                    <td class="td-data"><?= $fecha; ?></td>
                    <td class="td-data"><?= $lugar; ?></td>
                    <td class="td-data"><?= $cantidad; ?></td>
                    <td class="text-center" style="background-color:#0aa100;">COMPROBADO</td>
                </tr>
            
        

            <tr></tr>
            <tr class="tbl-1-pie">
                <td colspan="4" style="font-weight:bold;color:#FFF;">Create by Walworth IT</td>
                
            </tr>
        </tbody>
    </table>

</body>

</html>