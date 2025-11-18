
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
<p><span style="color:rgb(37,37,37)">El Usuario(a):<b> <?= $nombre_solicitante ?> </b> ha generado una nueva Solicitud de Vacaciones.</b></span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" >
    <tbody>
      
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;">Solicitud de Vacaciones</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha Creación:</td>
        <td  style="color:black;"><?= $fecha_registro ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Departamento:</td>
        <td  style="color:black;"><?= $departamento ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tipo Empleado:</td>
        <td  style="color:black;"><?= $tipo_empleado ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Puesto:</td>
        <td  style="color:black;"><?= session()->job_position ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Numero de nomina:</td>
        <td style="color:black;"><?= $num_nomina ?></td>
      </tr>
     
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Dias a Disfrutar:</td>
        <td style="color:black;"><?= $num_dias_a_disfrutar ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Disfrutar Del:</td>
        <td style="color:black;"><?= $dias_a_disfrutar_del ?></td>
      </tr>
      
        <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Disfrutar Al:</td>
        <td style="color:black;"><?= $dias_a_disfrutar_al ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Regresando:</td>
        <td style="color:black;"><?= $regreso ?></td>
      </tr>
      <?php if (strlen($a_cargo) > 12) {?>
        <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Dejando a cargo:</td>
        <td style="color:black;"><?= $a_cargo ?></td>
      </tr>
      <?php } ?>
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
            href="https://sie.grupowalworth.com/permisos/autorizar"
            target="_blank"
            >Autorizar Vacaciones</a
          >
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>