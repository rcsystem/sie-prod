<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
    
    body {
      font-family: 'Roboto', Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      background-color: #f5f7fa;
      padding: 20px;
      margin: 0;
    }
    
    .notification-container {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    
    .notification-header {
      background: #2c3e50;
      color: white;
      padding: 15px 20px;
      font-size: 18px;
      font-weight: 500;
    }
    
    .notification-content {
      padding: 20px;
    }
    
    .user-info {
      font-size: 16px;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }
    
    .user-info b {
      color: #2c3e50;
    }
    
    .medical-note {
      background: #fff8e1;
      padding: 12px;
      border-left: 4px solid #ffc107;
      margin: 15px 0;
      font-size: 14px;
      border-radius: 0 4px 4px 0;
    }
    
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 14px;
    }
    
    .details-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    
    .details-table td {
      padding: 12px 15px;
      border-bottom: 1px solid #eee;
    }
    
    .details-table td:first-child {
      font-weight: 500;
      color: #2c3e50;
      width: 40%;
      text-align: right;
    }
    
    .badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-weight: 500;
      font-size: 13px;
    }
    
    .permission-type {
      background-color: #4980f5;
      color: white;
    }
    
    .status-approved {
      background-color: #47dc66;
      color: white;
    }
    
    .footer {
      text-align: center;
      padding: 15px;
      background: #f5f7fa;
      color: #7f8c8d;
      font-size: 12px;
      border-top: 1px solid #eee;
    }
    
    .footer b {
      color: #2c3e50;
    }
  </style>
</head>
<?php
$empleado = $notify["nombre_solicitante"];
$dia = date("d/m/Y", strtotime($notify["fecha_salida"]));
$vacaciones = "";
if (intval($permiso) == 1) {
  $vacaciones = "<div class='medical-note'>Sé ha autorizado la salida por motivo médico, se le solicita recordarle al usuario <b>$empleado</b>, traer sus incapacidades para recuperar su día de vacaciones.</div>";
}
?>

<body>
  <div class="notification-container">
    <div class="notification-header">
      Nueva Solicitud de Permiso
    </div>
    
    <div class="notification-content">
      <div class="user-info">
        El usuario(a): <b><?= $notify["user"] ?></b> ha generado una nueva Solicitud de Permiso.
        <?php echo $vacaciones ?>
      </div>
      
      <table class="details-table">
        <tbody>
          <tr>
            <td>Fecha Creación:</td>
            <td><?= $notify["fecha_creacion"] ?></td>
          </tr>
          <tr>
            <td>Tipo de Permiso:</td>
            <td><span class="badge permission-type"><?= $notify["tipo_permiso"] ?></span></td>
          </tr>
          <tr>
            <td>Tipo Empleado:</td>
            <td><?= $notify["tipo_empleado"] ?></td>
          </tr>
          <tr>
            <td>Número de nómina:</td>
            <td><?= $notify["num_nomina"] ?></td>
          </tr>
          <tr>
            <td>Estatus:</td>
            <td><span class="badge status-approved">Autorizada</span></td>
          </tr>
          <tr>
            <td>Departamento:</td>
            <td><?= $notify["departamento"] ?></td>
          </tr>
          <tr>
            <td>Puesto:</td>
            <td><?= $puesto ?></td>
          </tr>
          <?php if($notify["hora_salida"] != "00:00:00"){ ?>
          <tr>
            <td>Fecha de Salida:</td>
            <td><?= $notify["fecha_salida"] ?></td>
          </tr>
          <tr>
            <td>Hora de Salida:</td>
            <td><?= $notify["hora_salida"] ?></td>
          </tr>
          <?php } ?>
          <?php if($notify["inasistencia_del"] != "0000-00-00"){ ?>
          <tr>
            <td>Inasistencia Del:</td>
            <td><?= $notify["inasistencia_del"] ?></td>
          </tr>
          <tr>
            <td>Inasistencia Al:</td>
            <td><?= $notify["inasistencia_al"] ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td>Motivo:</td>
            <td><?= $notify["observaciones"] ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div class="footer">
      <b>Create by Walworth TI</b>
    </div>
  </div>
</body>

</html>