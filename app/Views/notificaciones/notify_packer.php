<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php

if($datas["sure"] == 1){
$seguro = "SI";
$costo = "$ ".$datas["cost"];
}else{
    $seguro = "NO";  
}
if($datas["shipping_type"] == 1){
    $tipo ="Día Siguiente";
}
if($datas["shipping_type"] == 2){
    $tipo ="Terrestre";
}
if ($datas["gather"] == 1) {
    $recoleccion = "Se Requiere Recolección";
}
if ($datas["gather"] == 2) {
    $recoleccion = "No Necesaria";
}
?>
<body>
    <p><span style="color:rgb(37,37,37)">
            <p>Se ha generado una nueva solicitud de Paquetería.</p>
        </span></p>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Area Operativa</td>
                <td><?= $datas["area_operative"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Numero Telefonico</td>
                <td><?= $datas["sender_phone"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Nombre</td>
                <td><?= mb_convert_case($datas["sender_name"], MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr style="background:#eee">
                <td style="font-weight:bold;width:180px">Empresa</td>
                <td><?= $datas["sending_company"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Calle</td>
                <td><?= $datas["sender_street"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Numero</td>
                <td><?= $datas["sender_num"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Colonia</td>
                <td><?= $datas["sender_col"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Localidad</td>
                <td><?= $datas["sender_locality"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Estado</td>
                <td><?= $datas["sender_state"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">País</td>
                <td><?= $datas["sender_country"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Codigo Postal</td>
                <td><?= $datas["sender_cp"]; ?></td>
            </tr>
            <tr style="background:#eee">
                <td style="font-weight:bold;width:180px">Empresa Destino</td>
                <td><?= $datas["recipient_company"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Calle</td>
                <td><?= $datas["recipient_street"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Numero</td>
                <td><?= $datas["recipient_num"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Colonia</td>
                <td><?= $datas["recipient_col"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Localidad</td>
                <td><?= $datas["recipient_locality"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Estado</td>
                <td><?= $datas["recipient_state"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">País</td>
                <td><?= $datas["recipient_country"]; ?></td>
            </tr>
            <tr style="background:#eee">
                <td style="font-weight:bold;width:180px">Cantidad de Paquetes</td>
                <td><?= $cont; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Tipo de Envio</td>
                <td><?= $tipo; ?></td>
            </tr>
            <?php if ($datas["sure"] == 1) {  ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Seguro</td>
                    <td><?= $seguro; ?></td>
                </tr><tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Costo</td>
                    <td><?= $costo; ?></td>
                </tr>
            <?php } else {  ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Seguro</td>
                    <td><?= $seguro; ?></td>
                </tr>
            <?php } ?>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Recoleccion</td>
                <td><?= $recoleccion ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Observacion</td>
                <td><?= $datas["observation"]; ?></td>
            </tr>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>
</body>

</html>