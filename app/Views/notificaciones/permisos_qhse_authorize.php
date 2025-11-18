<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
  <p style="color:#000; font-size:18px;"><b>Se ha Autorizado el permiso de Visita con Folio: <?= $datos->id ?></b> </p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
    <tbody>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Usuario:</td>
        <td><?= $datos->name; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Puesto:</td>
        <td><?= $datos->job; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Departamento:</td>
        <td><?= $datos->departament; ?></td>
      </tr>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Proveedor</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Nombre de Proveedor:</td>
        <td><?= $datos->suppliers; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Número de personas:</td>
        <td><?= $datos->num_persons; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Departamento a visitar:</td>
        <td><?= $datos->departament_you_visit; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Razón de la visita:</td>
        <td><?= $datos->reason_for_visit; ?></td>
      </tr>
      <?php if ($datos->day_you_visit === '0000-00-00') { ?>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px">Inicio de la Estadia:</td>
          <td><?= $datos->start_date_of_stay; ?></td>
        </tr>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px">Fin de la Estadia:</td>
          <td><?= $datos->end_date_of_stay; ?></td>
        </tr>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px">Hora de llegada (tentativa):</td>
          <td><?= $datos->time_of_entry; ?></td>
        </tr>
      <?php } else { ?>

        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px">Día y hora de la visita:</td>
          <td><?= $datos->day_you_visit . " " . $datos->time_of_entry; ?></td>
        </tr>
      <?php } ?>

      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Visitantes</td>
      </tr>
      <?php foreach ($visitors as $key => $value) {  ?>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px">Nombre del Visitante:</td>
          <td><?= $value->visitor ?></td>
        </tr>

      <?php } ?>
    </tbody>
  </table>
</body>

</html>