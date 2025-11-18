<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
  <p><span style="color:rgb(37,37,37)">
      <h2> <b> Se ha generado un Nuevo Contrato. </b></h2>
    </span></p>
  <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
    <tbody>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Usuario</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Empresa</td>
        <td>Walworth</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Nombre</td>
        <td><?= $usuario->nombre; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Numero de Empleado</td>
        <td><?= $usuario->payroll_number; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Puesto</td>
        <td><?= $usuario->job; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Departamento</td>
        <td><?= $usuario->departament; ?></td>
      </tr>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Contrato</td>
      </tr>
      <?php switch ($contrato["option"]) {
        case '1':
          $days = "Planta";
          break;
        case '2':
          switch ($contrato["type_of_contract"]) {

            case '2':
              $days = " 30 dias";
              break;

            case '3':
              $days = " 60 dias";
              break;
            case '4':
              $days = " 90 dias";
              break;

            default:
              $days = "Error";
              break;
          }
          break;
        case '3':
          $days = "Baja";
          break;

        default:
          # code...
          break;
      }

      ?>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Tipo de Contrato</td>
        <td><?= $days; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Fecha de Termino de Contrato</td>
        <td><?= $contrato["date_expiration"]; ?></td>
      </tr>
      <?php if ($contrato["cause_of_termination"] != "") { ?>
        <tr style="background:#fbfbfb">
          <td colspan="2" style="font-weight:bold;width:180px">Causas de la Baja</td>
        </tr>
        <tr style="background:#fbfbfb">
          <td colspan="2"><?= $contrato["cause_of_termination"]; ?></td>
        </tr>
      <?php } ?>

      <?php if ($contrato["observations"] != "") { ?>
        <tr style="background:#fbfbfb">
          <td colspan="2" style="font-weight:bold;width:180px">Observaciones</td>
        </tr>
        <tr style="background:#fbfbfb">
          <td colspan="2"><?= $contrato["observations"]; ?></td>
        </tr>
      <?php } ?>

      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
    </tbody>
  </table>
</body>

</html>