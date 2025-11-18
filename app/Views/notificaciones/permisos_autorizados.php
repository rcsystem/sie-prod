<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php $color = ($status == 1) ? '#24c924' : '#C82333'; ?>
<body>
      <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Solicitud de Vacaciones Folio: <?= $folio ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="text-align:right;font-weight:bold;width:120px">Nombre</td>
                <td><?= $usuario; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="text-align:right;font-weight:bold;width:120px">Dias a Disfrutar</td>
                <td><?= $days; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="text-align:right;font-weight:bold;width:120px">Estatus</td>
                <td style="color:#fff;background: <?= $color ?>"><b> <?= ($status == 1) ? 'Autorizada' : 'Rechazada'; ?> </b> </td>
            </tr>      
            
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>