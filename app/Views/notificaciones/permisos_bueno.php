<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php

$colorStatus = ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'color:white;background:#28a745;' : 'color:black;background:#efef2f;';
$txtStatus = ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'Autorizado' : 'Pendiente';

if ($notify["num_permiso_mes"] == 4 && $notify["tipo_permiso"] == "PERSONAL") {
  $tipo_permiso = "PERSONAL | PERMISO EXTRA";
  $link = "https://sie.grupowalworth.com/permisos/autorizar_new";
  $color = "color:white;background:#F65E0A;";
} elseif ($notify["num_permiso_mes"] == 5 && $notify["tipo_permiso"] == "PERSONAL") {
  $tipo_permiso = "PERSONAL | QUINTO PERMISO";
  $link = "https://sie.grupowalworth.com/permisos/autorizar-direcion-general";
  $color = "color:white;background:#F7304F;";
} else {
  $tipo_permiso = ($notify["id_tipo_permiso"] == 4) ? 'DÍA POR: ' . $notify["tipo_permiso"] : $notify["tipo_permiso"];
  $link = "https://sie.grupowalworth.com/permisos/autorizar";
  // $color = "color:white;background:" . $color->color . ";";

  $color = ($notify["tipo_permiso"] == "ATENCIÓN PSICOLÓGICA") ? "color:white;background:#16C9BA;" :"color:white;background:" . $color->color . ";" ;
  
}
?>

<body>
  <p><span style="color:rgb(37,37,37)">El Usuario(a):<b> <?= $notify["user"] ?> </b> ha generado una nueva Solicitud de Permiso.</b></span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10">
    <tbody>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;">Solicitud de Permiso</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha Creación:</td>
        <td style="color:black;"><?= $notify["fecha_creacion"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tipo de Permiso:</td>
        <td style="<?php echo $color ?>"><?= $tipo_permiso ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tipo Empleado:</td>
        <td style="color:black;"><?= $notify["tipo_empleado"] ?></td>
      </tr>
      <?php if ($notify["tipo_empleado"] == 'Sindicalizado') { ?>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Goce de Sueldo:</td>
          <td style="color:black;"><?= $notify["goce_sueldo"] ?></td>
        </tr>
        <?php if ($notify["goce_sueldo"] == 'SI') { ?>
          <tr style="background:#fbfbfb">
            <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tiempo:</td>
            <td style="color:black;"><?= ($notify["pago_deuda"] = 1) ? 'PAGADO' : 'POR PAGAR'; ?></td>
          </tr>
        <?php } ?>
      <?php } ?>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Numero de nomina:</td>
        <td style="color:black;"><?= $notify["num_nomina"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Estatus:</td>
        <td style="<?php echo $colorStatus ?>"><?= $txtStatus ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Departamento:</td>
        <td style="color:black;"><?= $notify["departamento"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Puesto:</td>
        <td style="color:black;"><?= session()->job_position ?></td>
      </tr>
      <?php if ($notify["hora_salida"] != "") { ?>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha de Salida:</td>
          <td style="color:black;"><?= $notify["fecha_salida"] ?></td>
        </tr>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Hora de Salida:</td>
          <td style="color:black;"><?= $notify["hora_salida"] ?></td>
        </tr>
      <?php } ?>
      <?php if ($notify["hora_entrada"] != "") { ?>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha de Entrada:</td>
          <td style="color:black;"><?= $notify["fecha_entrada"] ?></td>
        </tr>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Hora de Entrada:</td>
          <td style="color:black;"><?= $notify["hora_entrada"] ?></td>
        </tr>
      <?php } ?>
      <?php if ($notify["inasistencia_del"] != "") { ?>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Inasistencia Del:</td>
          <td style="color:black;"><?= $notify["inasistencia_del"] ?></td>
        </tr>
        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px;text-align: right;color:black;">Inasistencia Al:</td>
          <td style="color:black;"><?= $notify["inasistencia_al"] ?></td>
        </tr>
      <?php } ?>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Observaciones:</td>
        <td style="color:black;"><?= $notify["observaciones"] ?></td>
      </tr>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0">
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
              padding: 12px 25px;
              text-decoration: none;
              text-transform: capitalize;
            " href="<?php echo $link; ?>" target="_blank">Autorizar Permiso</a>
          <!-- href="https://sie.grupowalworth.com/permisos/autorizar" -->
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>