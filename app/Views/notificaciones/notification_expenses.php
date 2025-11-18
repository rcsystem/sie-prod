<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <p><span style="color:rgb(37,37,37)">
            <p> Recordatorio de comprobación de Gastos </p>
        </span></p>

    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Solicitud de Gastos con Folio: <?= $folio ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Nombre</td>
                <td><?= $usuario; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Motivo</td>
                <td><?= $razon; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Fecha Inicio</td>
                <td><?= $inicio; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Fecha Termino</td>
                <td><?= $regreso; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Total a Comprobar</td>
                <td><?= $total; ?></td>
            </tr>
         
            
           

            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>