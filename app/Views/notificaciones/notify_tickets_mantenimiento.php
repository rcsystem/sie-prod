<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php
$link = site_url() . "tickets/mantenimiento";
$user = $ticket["name_user"] ?? '';
$textObject = [
    1 => "El Usuario(a):<b> " . $user . " </b> ha generado una nuevo Ticket de Mantenimiento.</b>",
    2 => "El Tickets a sido Autorizado.",
    4 => "EL proceso de reparacion ah sido Finalizado"
];
$textBottomObject = [ 1 => "Autorizar Ticket", 2 => "Ver Tickets.", 4 => "Cerrar Ticket" ];
?>

<body>
    <p><span style="color:rgb(37,37,37)"><?= $textObject[$tipo]; ?></span></p>
    <table style="border-radius:2px;margin:2em 0 1em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold;color:black;text-align:center;">Ticket de Mantenimiento <b style="margin-left: 1rem;">Folio <?= $folio ?></b></td>
            </tr>
            <?php if ($tipo == 1) { ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha Creación:</td>
                    <td style="color:black;"><?= $ticket["created_at"] ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tipo de Mantenimiento:</td>
                    <td style="color:black;"><?= $actividad->Actividad_Actividad ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Codigo de Falla:</td>
                    <td style="color:black;"><?= $falla->name_fail ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Equipo:</td>
                    <td style="color:black;"><?= $ticket["equip"] ?> / <?= $ticket["id_machine"] ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td colspan="2" style="font-weight:bold;width:180px;text-align: center;color:black;">Descripcion de Trabajo:</td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td colspan="2" style="color:black;" style="padding-left:5px;padding-right:5px;"><?= $ticket["description"] ?></td>
                </tr>
            <?php } else { ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha Creación:</td>
                    <td style="color:black;"><?= $datos->created_at ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tipo de Mantenimiento:</td>
                    <td style="color:black;"><?= $datos->Actividad_Actividad ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Codigo de Falla:</td>
                    <td style="color:black;"><?= $datos->name_fail ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">Equipo:</td>
                    <td style="color:black;"><?= $datos->equipo ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td colspan="2" style="font-weight:bold;width:180px;text-align: center;color:black;">Descripcion de Trabajo:</td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td colspan="2" style="color:black;" style="padding-left:5px;padding-right:5px;"><?= $datos->description ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <table style="border:0" cellpadding="0" cellspacing="0">
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
                        padding: 10px 19px;
                        margin-top:-15px;
                        text-decoration: none;
                        text-transform: capitalize;" 
                    href="<?= $link; ?>" 
                    target="_blank"><?= $textBottomObject[$tipo]; ?></a>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>