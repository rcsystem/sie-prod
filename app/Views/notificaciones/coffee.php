<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php
switch ($datas->meeting_room) {
    case '1':
        $datas->sala = "Sala de Consejo";
        break;
    case '2':
        $datas->sala = "Sala de Operaciones";
        break;
    case '3':
        $datas->sala = "Sala de Ingenieria";
        break;
    case '4':
        $datas->sala = "Sala James Walworth";
        break;
    case '5':
        $datas->sala = "Sala de Logistica";
        break;
    case '6':
        $datas->sala = "Sala de Ventas";
        break;
    case '7':
        $datas->sala = "Sala de Calidad";
        break;
    case '8':
        $datas->sala = "Mezzanine (Nave 3)";
        break;

    default:
        $datas->sala = "Error no se Selecciono Sala";
        break;
}
?>

<body>
    <?php  if($datas->status != 4 ){
         if ($datas->status == 2) { ?>
        <p><span style="color:rgb(37,37,37)">
            <p>Tu solicitud a sido AUTORIZADA. <br> DATOS DE SOLICITUD</p>
        </span></p>
    <?php } else if ($datas->status == 3) { ?>
        <p><span style="color:rgb(37,37,37)">
            <p>Tu solicitud a sido RECHAZADA. <br> DATOS DE SOLICITUD</p>
        </span></p>
    <?php }else if($datas->status == 7){ ?>
        <p><span style="color:rgb(37,37,37)">
            <p>Tu Solicitud de Sala James ha sido Rechada. <br> DATOS DE SOLICITUD</p>
        </span></p>

    <?php } else { ?>
        <p><span style="color:rgb(37,37,37)">
            <p>Se ha generado una nueva solicitud. <br> DATOS DE SOLICITUD</p>
        </span></p>
    <?php } ?>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Numero de Empleado</td>
                <td><?= $datas->payroll_number; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Nombre</td>
                <td><?= mb_convert_case($datas->name, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Puesto</td>
                <td><?= $datas->position_job; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Departamento</td>
                <td><?= $datas->depto; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Centro de Costo</td>
                <td><?= $datas->area_operativa; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Sala de Juntas</td>
                <td><?= $datas->sala; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Motivo de la Reunion</td>
                <td><?= $datas->reason_meeting; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Fecha y Hora</td>
                <td><?= $datas->date; ?> | <?= $datas->horario; ?> </td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Numero de personas</td>
                <td><?= $datas->num_person; ?> </td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Observaciones</td>
                <td><?= $datas->observations; ?> </td>
            </tr>
            <?php if ($datas->menu_especial != 0) {  ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Menu Especial</td>
                    <td style="text-align:left; float:left;">
                        <?= mb_strtoupper($menu->tittle_menu, 'UTF-8'); ?>
                    </td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td colspan="2">
                        <ol style="font-size: 14px; float: left;">
                            <?php
                            foreach ($comida as $description => $value) {
                            ?>
                                <li><?= mb_strtoupper($value->description, 'UTF-8'); ?></li>
                            <?php
                            }
                            ?>
                        </ol>
                    </td>
                </tr>
            <?php } 
        } elseif($datas->status == 4){?>
        <p><span style="color:rgb(37,37,37)">
            <p>Solicitud con folio: <?= $datas->id_coffee; ?> a sido cancelada por el solicitante <?= mb_convert_case($datas->name, MB_CASE_TITLE, "UTF-8"); ?> por la siguiente razon: <br>
            <?= $datas->reason_cancel;?>.</p>
        </span></p>
           <?php } 
           elseif($datas->status == 7){?>
        <p><span style="color:rgb(37,37,37)">
            <p>Solicitud con folio: <?= $datas->id_coffee; ?> a sido cancelada por el departamento de Talento: <br>
            <?= $datas->reason_cancel;?>.</p>
        </span></p>
           <?php } ?>
           <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
        </tbody>
    </table>
</body>

</html>