<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php
$dia = date("d/m/Y", strtotime($notify[0]->date_expiration)) ?? 'Indefinido';
$empresa = [1 => 'Walworth', 2 => 'Grupo Walworth', 3 => ' Ax One', 4 => 'Inval'];
$bgColor = [1 => '#28A745', 2 => '#3FC3EE', 3 => '#D36B7B'];
$texto = ($notify[0]->option != 3) ? 'Observación' : 'Motivo de baja';
$obs = ($notify[0]->option != 3) ? $notify[0]->observations : $notify[0]->cause_of_termination;
?>

<body>
  <p><span style="color:rgb(37,37,37)">El Usuario(a):<b> <?= $notify[0]->manager ?> </b> ha generado un nuevo grupo de Contratos de tipo </b><?= $notify[0]->contrato ?>.</span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10">
    <tbody>

      <tr style="background:#5C636A">
        <td colspan="6" style="font-weight:bold;color:white;">CONTRATOS TEMPORALES</td>
      </tr>
      <tr>
        <td style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;"><b>Tipo de Contrato:</b></td>
        <td colspan="2" style="color: black;background-color:<?php echo $bgColor[$notify[0]->option]; ?>;"><?= $notify[0]->contrato ?></td>
        <td style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;"><b>Fecha de Termino:</b></td>
        <td colspan="2" style="color: black;background-color:#FBFBFB;"><?= $dia ?></td>
      </tr>
      <tr>
        <td colspan="6" style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;text-align: center !important;"><b><?= $texto ?>:</b></td>
      </tr>
      <tr>
        <td colspan="6" style="color: black;background-color:#FBFBFB;"><?= $obs ?></td>
      </tr>
      <tr style="background:#5C636A">
        <td colspan="6" style="font-weight:bold;color:white;">USUARIOS</td>
      </tr>
      <?php for ($i = 0; $i < $count; $i++) { ?>
        <?php if ($i > 0) { ?>
          <tr style="background:#5C636A">
            <td colspan="6" style="font-weight:bold;color:black;height:0px; margin-top:0;padding: 2px 0 0 0;"></td>
          </tr>
        <?php } ?>
        <tr>
          <td style="width: 10%;" style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;"><b>Empleado:</b></td>
          <td style="width: 20%;" style="color: black;background-color:#FBFBFB;"><?= $notify[$i]->nombre ?></td>
          <td style="width: 10%;" style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;"><b>Nómina:</b></td>
          <td style="width: 15%;" style="color: black;background-color:#FBFBFB;"><?= $notify[$i]->payroll_number ?></td>
          <td style="width: 10%;" style="font-weight: bold;width: auto;text-align: right;color: black;background-color:#D4D4D4;"><b>Empresa:</b></td>
          <td style="width: 15%;" style="color: black;background-color:#FBFBFB;"><?= $empresa[$notify[$i]->company] ?></td>
        </tr>
      <?php } ?>
      <tr style="background:#5C636A">
        <td colspan="6" style="font-weight:bold;color:white"><b>Create by Walworth IT</b></td>
      </tr>
      <?php if ($type == 3) { ?>
        <tr>
          <td style="text-align: star;">
            <a style="background-color: #11344b;border: solid 1px #11344b;
            border-radius: 5px; box-sizing: border-box;
            color: #fff; cursor: pointer; display: inline-block;
            font-size: 14px; font-weight: bold; margin: 0;
            padding: 10px 19px; margin-top:-15px; text-decoration: none;
            text-transform: capitalize;" href="https://sie.grupowalworth.com/usuarios/autorizar-planta" target="_blank">Ir</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</body>

</html>