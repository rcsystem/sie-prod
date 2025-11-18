<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
  <p><span style="color:rgb(37,37,37)">
      <p>Estos Productos estan al minimo o apunto de terminarse: </p>
    </span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
    <tbody>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Producto</td>
      </tr>

      <?php foreach ($request as $key => $value) { ?>

        <tr style="background:#fbfbfb">
          <td style="font-weight:bold;width:180px"><?= $value["producto"]; ?></td>
          <td><b>Quedan: <?= $value["cantidad"]." ".$value["unidad"]; ?></b></td>
        </tr>
      <?php } ?>

      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
    </tbody>
  </table>

</body>

</html>