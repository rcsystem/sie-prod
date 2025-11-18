<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php $category = [1 => 'Transporte', 2 => 'Gasolina', 3 => 'Extras']; ?>

<body>
    <p><span style="color:rgb(37,37,37)">
            <p> </p>
        </span></p>

    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #5C636A;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#5C636A">
                <?php if ($status == 1) { ?>
                    <td colspan="2" style="color:#fff;font-weight:bold">Solicitud de Gastos con Folio: <?= $data->folio ?></td>
                <?php } ?>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Estado:</td>
                <td style="color:#fff;background-color: <?= $data->color ?>;"><?= $data->txt; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Nombre</td>
                <td><?= $data->user_name; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Departamento</td>
                <td><?= $data->departamento ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Area Operativa</td>
                <td><?= $data->area_ope ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Puesto</td>
                <td><?= $data->puesto ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Número de Nómina</td>
                <td><?= $data->nomina ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Total de Gastos:</td>
                <td><?= $data->total ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Inicio:</td>
                <td><?= $data->inicio ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Termino:</td>
                <td><?= $data->final ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Motivo:</td>
                <td><?= $data->obs ?></td>
            </tr>
            <tr style="background:#5C636A">
                <td colspan="2" style="font-weight:bold;color:#fff;"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>

    <table border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td>
                    <a style="
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
            " href="https://sie.grupowalworth.com/viajes/autorizar" target="_blank">Autorizar Gasto</a>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>