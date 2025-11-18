<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<p><span style="color:rgb(37,37,37)"><p>Se ha generado una nueva Requisición con los siguientes datos: </p></span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
    <tbody>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Genera la Requisición</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:110px">Usuario:</td>
        <td><?= $usuario["usuario"]; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:110px">Orden de Compra:</td>
        <td><?= $usuario["orden_compra"]; ?></td>
      </tr>
   
      
     
        <?php   foreach ($items as $key => $val) {  ?>
    
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:110px"><b>Codigo:</b><br/><b><?= $val->codigo; ?></b></td>
        <td><b>Descripción:</b><br/><?= $val->desc_breve; ?><br/><b>  Numero Piezas: </b> <br/><?= $val->num_piezas; ?><br/> <b>  Entrega: </b><br/> <?= $val->tiempo; ?></td>
      </tr>

      <?php } ?>
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
            >Ver Requisición</a
          >
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>
