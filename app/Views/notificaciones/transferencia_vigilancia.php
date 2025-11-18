<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
<p><span style="color:rgb(37,37,37)">El Usuario(a):<b> <?= $user ?> </b> ha Autorizado la
  Transferencia con el <b>Folio: <?= $id_vouchers ?>.</b></span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" >
    <tbody>
      
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;">Transferencia</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Origen:</td>
        <td  style="color:black;"><?php
         switch ($addressee) {
          case '1':
            $origen = "Nave 1";
            break;
            case '2':
              $origen = "Nave 4";
            break;
            case '3':
              $origen = "Nave 3";
            break;

            case '4':
              $origen = "Villahermosa";
            break;
        
          default:
            $origen = "Error";
            break;
        }
        echo $origen;
         ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px;text-align: right;color:black;">Destino:</td>
        <td style="color:black;"><?php
        switch ($departures) {
          case '1':
            $destino = "Nave 1";
            break;
            case '2':
              $destino = "Nave 4";
            break;
            case '3':
              $destino = "Nave 3";
            break;
            case '4':
              $destino = "Villahermosa";
            break;
        
          default:
            $destino = "Error";
            break;
        }
        echo $destino;
        ?></td>
      </tr>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
    </tbody>
  </table>
  
</body>

</html>