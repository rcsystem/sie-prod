<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
<p><span style="color:rgb(37,37,37)">
      <p>Se ha registrado un Nuevo Usuario con Contrato Temporal. </p>
    </span></p>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
    <tbody>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Usuario</td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Empresa</td>
        <td><?php  switch ($company) {
                
            case '1':
                $empresa = "Walworth";
                break;

            case '2':
                $empresa = "Grupo Walworth";
                break;
            case '3':
                $empresa = "Ax One";
                break;
                case '4':
                  $empresa = "Inval";
                  break;

            default:
                $empresa = "Error";
                break;
        }
  echo $empresa;
        ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Nombre</td>
        <td><?php $nombre = $name." ".$surname;  echo mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8"); ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Numero de Empleado</td>
        <td><?= $payroll_number; ?></td>
      </tr>
      <tr style="background:#fbfbfb">
        <td style="font-weight:bold;width:180px">Puesto</td>
        <td><?= $job_position; ?></td>
      </tr>
      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold">Información del Contrato</td>
      </tr>
      
      <tr style="background:#fbfbfb">
      <td style="font-weight:bold;width:180px">Tipo de Contrato</td>
        <td><?php switch ($type_of_contract) {
                
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
      echo $days;
      ?></td>
      </tr>
      <tr style="background:#fbfbfb">
      <td style="font-weight:bold;width:180px">Termino de Contrato</td>
        <td><?= $date_expiration; ?></td>
      </tr>

      <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
    </tbody>
  </table>
</body>

</html>
