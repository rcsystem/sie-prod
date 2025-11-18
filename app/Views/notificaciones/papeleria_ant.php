<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
  <p><span style="color:rgb(37,37,37)">
      <p>Se ha generado una nueva solicitud. Aquí están todas las respuestas: </p>
    </span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
    <tbody>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Email</td>
        <td><?= $request->email; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Empresa</td>
        <td>Walworth</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Nombre</td>
        <td><?= mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8"); ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Numero de Empleado</td>
        <td><?= $request->payroll_number; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Departamento</td>
        <td><?= $request->departament; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Centro de Costo</td>
        <td><?= $request->cost_center; ?></td>
      </tr>

      <?php if (!empty($personal)) { ?>
        <?php foreach ($personal as $label => $opt) { ?>
          <tr style="background:#eee">
            <td colspan="2" style="font-weight:bold"><?= $label; ?></td>
          </tr>
          <?php foreach ($opt as $id => $names) { ?>

            <tr style="background:#fbfbfb">
              <td style="font-weight:bold;width:180px"><?= $id; ?></td>
              <td><?= $names; ?></td>
            </tr>
          <?php } ?>
        <?php } ?>
      <?php } ?>
      <?php if ($request->obs_request != "") { ?>
        <tr style="background:#eee">
          <td colspan="2" style="font-weight:bold">Observaciones</td>
        </tr>
        <tr style="background:#fbfbfb">
          <td colspan="2" style="text-align: justify;font-weight:bold;width:375px"><?= $request->obs_request; ?></td>

        </tr>
      <?php } ?>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
    </tbody>
  </table>
<?php  if($option != 2 ){ ?>
  <table border="0" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td>
          <a
            style="
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
              padding: 12px 25px;
              text-decoration: none;
              text-transform: capitalize;
            "
            href="https://sie.grupowalworth.com/papeleria/autorizar"
            target="_blank"
            >Autorizar Papelería</a
          >
        </td>
      </tr>
    </tbody>
  </table>
  <?php } ?>
</body>

</html>