<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php $color = ($data->estatus == 1) ? '#24c924' : '#C82333'; 
$colorStatus = ($data->id_tipo_permiso == 4 || $data->id_tipo_permiso == 6) ? 'color:white;background:#28a745;' : 'color:white;background:#16f433;';

?>
<body>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Solicitud de Permisos Folio: <?= $data->id_es ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="text-align:right;font-weight:bold;width:120px">Nombre</td>
                <td><?= $data->user; ?></td>
            </tr>
          
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px;text-align: right;color:black;">Estatus:</td>
                <td style="<?php echo $colorStatus ?>"><?= $data->estatus ?></td>
            </tr>
           
          
            <?php if ($data->hora_salida != "00:00:00") { ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha de Salida:</td>
                    <td style="color:black;"><?= $data->fecha_salida ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Hora de Salida:</td>
                    <td style="color:black;"><?= $data->hora_salida ?></td>
                </tr>
            <?php } ?>
            <?php if ($data->hora_entrada != "00:00:00") { ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha de Entrada:</td>
                    <td style="color:black;"><?= $data->fecha_entrada ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Hora de Entrada:</td>
                    <td style="color:black;"><?= $data->hora_entrada ?></td>
                </tr>
            <?php } ?>

            <?php if ($data->inasistencia_del != "0000-00-00") { ?>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Inasistencia Del:</td>
          <td style="color:black;"><?= $data->inasistencia_del ?></td>
        </tr>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Inasistencia Al:</td>
          <td style="color:black;"><?= $data->inasistencia_al ?></td>
        </tr>
      <?php } ?>
       
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px;text-align: right;color:black;">Observaciones:</td>
                <td style="color:black;"><?= $data->observaciones ?></td>
            </tr>

            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>