<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php

$color = "color:white;background:#f2a100;" ;
$link = "https://sie.grupowalworth.com/permisos/autorizar";

?>
<body>
<p><span style="color:rgb(37,37,37)">El Usuario(a):<b> <?= $notify["user"] ?> </b> ha generado una nueva Solicitud de Permiso.</b></span></p>
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
        <td style="<?php echo $color ?>">A Cuenta de Vacaciones</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Folio Vacaciones:</td>
        <td  style="color:black;"><?= $folio ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Estatus:</td>
        <td  style="color:black;background:#efef2f;">Pendiente</td>
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
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Departamento:</td>
        <td style="color:black;"><?= $notify["departamento"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Puesto:</td>
        <td style="color:black;"><?= session()->job_position ?></td>
      </tr>
      <?php if($notify["hora_salida"] != ""){ ?>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha de Salida:</td>
        <td style="color:black;"><?= $notify["fecha_salida"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Hora de Salida:</td>
        <td style="color:black;"><?= $notify["hora_salida"] ?></td>
      </tr>
      <?php } ?>
      <?php if($notify["hora_entrada"] != ""){ ?>
        <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha de Entrada:</td>
        <td style="color:black;"><?= $notify["fecha_entrada"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Hora de Entrada:</td>
        <td style="color:black;"><?= $notify["hora_entrada"] ?></td>
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
            href="<?php echo $link; ?>"
            target="_blank"
            >Autorizar Permiso</a
          >
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>