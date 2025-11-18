<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
  <p><span style="color:rgb(37,37,37)">
      <p>Información de Partidas por Vencer: </p>
    </span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
    <tbody>      
      <?php if (!empty($partidas)) { ?>
        <?php foreach ($partidas as $label => $opt) { ?>
          <tr style="background:#eee">
            <td colspan="2" style="font-weight:bold"><?= $label; ?></td>
          </tr>
          <?php foreach ($opt as $id => $names) { ?>

            <tr style="background:#fbfbfb">
              <td style="font-weight:bold;width:180px"><?= $id; ?></td>
              <td ><?= $names; ?></td>
            </tr>
          <?php } ?>
        <?php } ?>
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
            href="https://sie.grupowalworth.com/suministros/todas-solicitudes"
            target="_blank"
            >Ver Ordenes</a
          >
        </td>
      </tr>
    </tbody>
  </table>

</body>

</html>