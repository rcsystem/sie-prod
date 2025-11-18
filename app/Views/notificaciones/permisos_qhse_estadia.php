<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
<p><span style="color:rgb(37,37,37)"><p>Se ha generado un nuevo permiso de Visita con los siguientes datos: </p></span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
    <tbody>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Usuario:</td>
        <td><?= $datos["name"]; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Puesto:</td>
        <td><?= $datos["job"]; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Departamento:</td>
        <td><?= $datos["departament"]; ?></td>
      </tr>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Proveedor</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Nombre de Proveedor:</td>
        <td><?= $datos["suppliers"]; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Número de personas:</td>
        <td><?= $datos["num_persons"]; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Departamento a visitar:</td>
        <td><?= $datos["departament_you_visit"]; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Razón de la visita:</td>
        <td><?= $datos["reason_for_visit"]; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Inicio de la Estadia:</td>
        <td><?= $datos["start_date_of_stay"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Final de la Estadia:</td>
        <td><?= $datos["end_date_of_stay"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Hora de Entrada:</td>
        <td><?= $datos["time_of_entry"] ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Requiere EPP:</td>
        <td><?= ($datos["epp"] == 1) ? "SI" : "NO" ; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Trabajos dentro de las instalaciones:</td>
        <td><?= ($datos["trabajos"] == 1) ? "SI" : "NO" ; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Acceso con vehículo:</td>
        <td><?= ($datos["auto"] == 1) ? "SI" : "NO" ; ?></td>
      </tr>
      <?php if($datos["auto"] == 1){ ?>
        <?php   foreach ($cars as $key => $val) {  ?>
        <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Modelo:</td>
        <td><?= $val->modelo; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Color:</td>
        <td><?= $val->color; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Matricula:</td>
        <td><?= $val->placas; ?></td>
      </tr>

      <?php  
        }    
      }
      ?>
      
      
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Visitantes</td>
      </tr>
      <?php   foreach ($visitors as $key => $value) {  ?>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Nombre del Visitante:</td>
        <td><?= $value->visitor ?></td>
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
            href="https://sie.grupowalworth.com/qhse/autorizar"
            target="_blank"
            >Autorizar Visita</a
          >
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>
