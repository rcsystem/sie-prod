<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
      color: #333;
    }

    .container {
      max-width: 700px;
      margin: 20px auto;
      background: #fff;
      border-radius: 6px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .header {
      background-color: #5C636A;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    .header h2 {
      margin: 0;
      font-size: 20px;
    }

    .section {
      padding: 20px;
    }

    h3 {
      margin-top: 0;
      font-size: 18px;
      color: #444;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
      font-size: 14px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #e5e5e5;
      text-align: center;
    }

    th {
      background-color: #f1f1f1;
      font-weight: 600;
    }

    tr:nth-child(even) td {
      background-color: #fafafa;
    }

    .status-badge {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 13px;
      color: #fff;
    }
    .status-firmado   { background-color: #28a745; }
    .status-pendiente { background-color: #ffc107; color:#000; }
    .status-adeudo    { background-color: #dc3545; }
    .status-progreso  { background-color: #007bff; }

    .footer {
      background-color: #f1f1f1;
      padding: 15px;
      text-align: center;
      font-size: 12px;
      color: #666;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Cabecera -->
    <div class="header">
      <h2>Solicitud de Liberación Completada</h2>
    </div>

    <!-- Datos usuario -->
    <div class="section">
      <h3>Usuario</h3>
      <table>
        <tr>
          <th>Nombre</th>
          <th>Email</th>
          <th>Nómina</th>
          <th>Estado</th>
        </tr>
        <tr>
          <td><?= $solicitud->name ?> <?= $solicitud->surname ?> <?= $solicitud->second_surname ?></td>
          <td><?= $solicitud->user_email ?></td>
          <td><?= $solicitud->payroll_number ?></td>
          <td>
            <?php
              $status = strtoupper($solicitud->request_status);
              $class = '';
              switch ($status) {
                case 'FIRMADO':     $class = 'status-firmado'; break;
                case 'PENDIENTE':   $class = 'status-pendiente'; break;
                case 'ADEUDO':      $class = 'status-adeudo'; break;
                case 'EN PROGRESO': $class = 'status-progreso'; break;
                default:            $class = ''; break;
              }
            ?>
            <span class="status-badge <?= $class ?>"><?= $status ?></span>
          </td>
        </tr>
      </table>

      <!-- Jefe directo -->
      <h3>Jefe Directo</h3>
      <table>
        <tr>
          <th>Nombre</th>
          <th>Email</th>
        </tr>
        <tr>
          <td><?= $jefeDirecto ? $jefeDirecto->name . ' ' . $jefeDirecto->surname : '-' ?></td>
          <td><?= $jefeDirecto ? $jefeDirecto->email : '-' ?></td>
        </tr>
      </table>

      <p style="margin-top:20px;">
        ✅ Todos los departamentos han completado las firmas de liberación para este usuario.
      </p>
    </div>

    <!-- Pie -->
    <div class="footer">
      <p>Sistema de Liberaciones – Walworth IT<br>
      Este correo es generado automáticamente, por favor no responder.</p>
    </div>
  </div>
</body>
</html>
