<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
<p><span style="color:rgb(37,37,37)">El Usuario(a):<b> <?= $username ?> </b> ha realizado una nueva
  requisición con el <b>Folio: <?= $id_folio ?>.</b></span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" >
    <tbody>
      
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;">Solicitud de personal</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Tipo de Personal:</td>
        <td  style="color:black;"><?= $tipo_personal ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Puesto Solicitado:</td>
        <td style="color:black;"><?= $puesto ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Personas Requeridas:</td>
        <td style="color:black;"><?= $personas_requeridas ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Motivo de la Requisición:</td>
        <td style="color:black;"><?= $motivo ?></td>
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
            href="https://sie.grupowalworth.com/requisiciones/autorizar"
            target="_blank"
            >Autorizar Requisición</a
          >
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>