<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <p style="color:#000; font-size:18px;"><b>Se ha Autorizado la Solicitud de Permiso de Horario Obscuro con Folio: <?= $folio; ?></b> </p>
        </span></p>

        <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Usuario:</td>
                <td><?= $request->name; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Puesto:</td>
                <td><?= $request->job; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Departamento:</td>
                <td><?= $request->departament; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Día del Horario Obscuro:</td>
                <td><?= date("d/m/Y", strtotime($request->day_you_visit)); ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Hora de Entrada:</td>
                <td><?= date('H:i a', strtotime($request->time_of_entry)); ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Hora de Salida:</td>
                <td><?= date('H:i a', strtotime($request->departure_time)); ?></td>
            </tr>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Información de Usuarios</td>
            </tr>
            <?php foreach ($personal as $key => $value) { ?>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px"><?= mb_convert_case($value->user, MB_CASE_TITLE, "UTF-8") ?></td>
                    <td><?= "<b>Depto:</b> <br/>".mb_convert_case($value->depto, MB_CASE_TITLE, "UTF-8")."<br/> <b>Puesto:</b> <br/>".mb_convert_case($value->job, MB_CASE_TITLE, "UTF-8") ?></td>
                </tr>
            <?php } ?>
            <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
        </tbody>
    </table>

</body>

</html>
