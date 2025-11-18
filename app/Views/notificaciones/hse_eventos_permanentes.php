<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>


<body>
  
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10">
    <tbody>
      <tr style="background:#1d72c1">
        <td colspan="2" style="font-weight:bold;color:#fff;">Eventos Permanente</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Fecha Creación:</td>
        <td style="color:black;"><?= $data["created_at"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Usuario:</td>
        <td style=""> <?= $data["user_name"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Número de Contacto:</td>
        <td style="color:black;"><?= $data["tel_user"] ?></td>
      </tr>

      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Número de nomina:</td>
        <td style="color:black;"><?= $data["payroll_number"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Departamento:</td>
        <td style="color:black;"><?= $data["departament"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Puesto:</td>
        <td style="color:black;"><?= $data["job_position"] ?></td>
      </tr>
    
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Evento:</td>
        <td style='color:black;'><?= $data["activity"] ?></td>
      </tr>

      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Observaciones:</td>
        <td style="color:black;"><?= $data["obs_volunteering"] ?></td>
      </tr>


      <tr style="background:#1d72c1">
        <td colspan="2" style="font-weight:bold;color:#fff;"><b>Create by Walworth IT</b></td>
      </tr>
    </tbody>
  </table>
  <!-- <table border="0" cellpadding="0" cellspacing="0">
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
            " href="<?php //echo $link; ?>" target="_blank">Autorizar Permiso</a>
          
        </td>
      </tr>
    </tbody>
  </table> -->
</body>

</html>