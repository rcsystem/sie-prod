<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php
$viaje = [ 1 => "Viaje Corto", 2 => "Viaje Largo"];
?>
<body>
    <?php if ($datas[0]->status == 2) { ?>
        <p><span style="color:rgb(37,37,37)">
                <p>Se ha Autorizado la solicitud con folio <?= $datas[0]->id_request ?>.</p>
            </span></p>
    <?php } else if ($datas[0]->status == 3) { ?>
        <p><span style="color:rgb(37,37,37)">
                <p>Se ha Rechazado tu solicitud con folio <?= $datas[0]->id_request ?>.
                Motivo: <?= mb_convert_case($datas[0]->observation, MB_CASE_TITLE, "UTF-8")?>.</p></p>
            </span></p>
    <?php } else if ($datas[0]->status == 4) { ?>
        <p><span style="color:rgb(37,37,37)">
                <p>Se ha Autorizado tu solicitud con folio <?= $datas[0]->id_request ?>. <br>
                Comentario Extra: <?= mb_convert_case($datas[0]->observation, MB_CASE_TITLE, "UTF-8")?>.</p>
            </span></p>
    <?php } else { ?>
        <p><span style="color:rgb(37,37,37)">
                <p>Se ha generado una nueva solicitud.</p>
            </span></p>
    <?php } ?>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <?php if ($datas[0]->status == 4) { ?>
                <tr style="background:#eee">
                    <td colspan="2" style="font-weight:bold">Vehiculo Asignado</td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Modelo</td>
                    <td><?= mb_convert_case($cars->model, MB_CASE_TITLE, "UTF-8"); ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Placas</td>
                    <td><?= $cars->placa ?></td>
                </tr>
            <?php } ?>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Numero de Empleado</td>
                <td><?= $datas[0]->payroll_number; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Nombre</td>
                <td><?= mb_convert_case($datas[0]->name, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Puesto</td>
                <td><?= $datas[0]->position_job; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Departamento</td>
                <td><?= $datas[0]->depto; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Centro de Costo</td>
                <td><?= $datas[0]->area_operativa; ?></td>
            </tr>
            <tr style="background:#eee">
                <td style="font-weight:bold;width:180px">Viaje</td>
                <td><?= $viaje[$datas[0]->type_trip]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Motivo del viaje</td>
                <td><?= $datas[0]->motive; ?></td>
            </tr>
            <?php if ($datas[0]->type_trip == 1) {  ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Fecha Solicitada</td>
                    <td><?= $trip[0]->date; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Hora Inicial</td>
                    <td><?= $trip[0]->star_time; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Hora Final</td>
                    <td><?= $trip[0]->end_time; ?></td>
                </tr>
            <?php } ?>
            <?php if ($datas[0]->type_trip == 2) {  ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Fecha Inicial Solicitada</td>
                    <td><?= $trip[0]->star_date; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Hora Inicial</td>
                    <td><?= $trip[0]->star_datetime; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Fecha Final Solicitada</td>
                    <td><?= $trip[0]->end_date; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Hora Final</td>
                    <td><?= $trip[0]->end_datetime; ?></td>
                </tr>
            <?php } ?>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>
</body>



</html>