<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php
$empleado = $notify["nombre_solicitante"];
$dia = date("d/m/Y", strtotime($notify["fecha_salida"]));
$vacaciones = "";
if (intval($permiso) == 1) {
  // $vacaciones = "<br> Sé ha autorizado la salida por motivo médico, se le solicita recordarle al usuario <b>$empleado</b>, se debe de generar el permiso 'A Cuenta de Vacaciones' para el día <b>$dia</b> desde el apartado de 'Permisos & Vacaciones'.";
  $vacaciones = "<br> Sé ha autorizado la salida por motivo médico, se le solicita recordarle al usuario <b>$empleado</b>, traer sus incapacidades para recuperar su dia de vacaciones.";
}
?>

<body>
<p><span style="color:rgb(37,37,37)">El Usuario(a):<b> <?= $notify["user"] ?> </b> ha generado una nueva Solicitud de Permiso.</b><?php echo $vacaciones ?></span></p>
<table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" >
    <tbody>
      
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;">Solicitud de Permiso</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha Creación:</td>
        <td  style="color:black;"><?= $notify["fecha_creacion"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tipo de Permiso:</td>
        <td style="color:white;background:#4980f5;;"><?= $notify["tipo_permiso"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tipo Empleado:</td>
        <td  style="color:black;"><?= $notify["tipo_empleado"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Numero de nomina:</td>
        <td style="color:black;"><?= $notify["num_nomina"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Estatus:</td>
        <td  style="color:white;background:#47dc66;;">Autorizada</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Departamento:</td>
        <td style="color:black;"><?= $notify["departamento"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Puesto:</td>
        <td style="color:black;"><?= $puesto ?></td>
      </tr>
      <?php if($notify["hora_salida"] != "00:00:00"){ ?>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha de Salida:</td>
        <td style="color:black;"><?= $notify["fecha_salida"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Hora de Salida:</td>
        <td style="color:black;"><?= $notify["hora_salida"] ?></td>
      </tr>
      <?php } ?>
      <?php if($notify["inasistencia_del"] != "0000-00-00"){ ?>
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
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Motivo:</td>
        <td style="color:black;"><?= $notify["observaciones"] ?></td>
      </tr>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
    </tbody>
  </table>
</body>

</html>