<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php
if($info["origin"] == "OTRO" ){
   $origen = $info["another_origin"];
}else {
   $origen = $info["origin"]; 
}

if($info["destination"] == "OTRO" ){
   $destino = $info["another_destination"];
}else {
   $destino = $info["destination"]; 
}  ?>
<body>
    <p>Se ha generado una nueva Solicitud de Valija. <br></p>
    </span></p>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Nombre</td>
                <td><?= $info["user_name"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Departamento</td>
                <td><?= $info["departament"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Puesto</td>
                <td><?= $info["job_position"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Número de Nómina</td>
                <td><?= $info["payroll_number"]; ?></td>
            </tr>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold;width:180px">Detalles</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Fecha Creación</td>
                <td><?= $info["created_at"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Origen</td>
                <td> <?= $origen; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Destino</td>
                <td> <?= $destino; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Prioridad</td>
                <td><?= $info["priority"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Fecha Diligencia</td>
                <td><?= $info["date"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Hora</td>
                <td><?= $info["time"]; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td colspan="2" style="font-weight:bold;width:180px">Descripcion del envio</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td colspan="2" ><?= $info["observation"]; ?></td>
            </tr>
        </tbody>
    </table>
</body>

</html>