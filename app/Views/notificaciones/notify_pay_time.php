<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php $typePay = [1 => 'Llegar Antes', 2 => 'Quedarse Despues', 3 => 'Turno Completo']; ?>

<body>
    <style>
        .btn {
            background-color: #11344b;
            border: solid 1px #11344b;
            border-radius: 5px;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }
    </style>
    <p><span style="color:rgb(37,37,37)">El Usuario(a):<b> <?= $notify[0]->usuario ?> </b> ha generado un nuevo pagos de tiempo.</span></p>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10">
        <tbody>
            <tr style="background:#5C636A">
                <td colspan="6" style="font-weight:bold;color:white;">DETALLES DE PAGO DE TIEMPO</td>
            </tr>
            <?php for ($i = 0; $i < $count; $i++) { ?>
                <?php if ($i > 0) { ?>
                    <tr style="background:#5C636A">
                        <td colspan="6" style="font-weight:bold;color:black;height:0px; margin-top:0;padding: 2px 0 0 0;"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="width: 10%;" style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;"><b>FECHA DE PAGO:</b></td>
                    <td style="width: 20%;" style="color: black;background-color:#FBFBFB;"><?= $notify[$i]->day_to_pay ?></td>
                    <td style="width: 10%;" style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;"><b>TIEMPO A PAGAR:</b></td>
                    <td style="width: 15%;" style="color: black;background-color:#FBFBFB;"><?= $notify[$i]->time_pay ?></td>
                    <td style="width: 10%;" style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;"><b>TIPO DE PAGO:</b></td>
                    <td style="width: 15%;" style="color: black;background-color:#FBFBFB;"><?= $typePay[$notify[$i]->type_pay] ?></td>
                </tr>
            <?php } ?>
            <tr style="background:#5C636A">
                <td colspan="6" style="font-weight:bold;color:white"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>
    <table style="border:0;">
        <tbody>
            <tr>
                <td>
                    <a class="btn" href="https://sie.grupowalworth.com/permisos/autorizar-pago-tiempo" target="_blank">Autorizar Pagos de Tiempo</a>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>