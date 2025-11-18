<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notificación de Permiso</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9fafb;
      padding: 30px;
      margin: 0;
      color: #374151;
      line-height: 1.6;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
      overflow: hidden;
      border: 1px solid #e5e7eb;
    }

    .header {
      background-color: #13c047;
      color: #ffffff;
      padding: 20px;
      text-align: center;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }

    .header h1 {
      font-size: 24px;
      margin: 0;
      font-weight: 600;
    }

    .header p {
      font-size: 14px;
      margin: 5px 0 0;
      line-height: 1.4;
    }

    .content {
      padding: 25px 30px;
      color: #374151;
      font-size: 14px;
    }

    .details {
      background-color: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 15px 20px;
      margin-top: 20px;
    }

    .details table {
      width: 100%;
      border-collapse: collapse;
    }

    .details table tr {
      border-bottom: 1px solid #e5e7eb;
    }

    .details table td {
      padding: 10px 0;
      vertical-align: top;
    }

    .details table td:first-child {
      font-weight: 600;
      color: #111827;
      width: 40%;
      text-align: right;
      padding-right: 15px;
    }

    .details table td:last-child {
      color: #4b5563;
    }

    .status {
      font-weight: bold;
      padding: 8px 12px;
      border-radius: 4px;
      text-transform: uppercase;
      font-size: 12px;
      display: inline-block;
      margin-top: 10px;
    }

    .status-success {
      background-color: #28a745;
      color: #ffffff;
    }

    .status-pending {
      background-color: #efef2f;
      color: #000000;
    }

    .button {
      display: inline-block;
      background-color: #13c047;
      color: #ffffff;
      text-decoration: none;
      padding: 12px 24px;
      margin-top: 25px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      text-align: center;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .button:hover {
      background-color: #10a63c;
      transform: translateY(-2px);
    }

    .footer {
      background-color: #f3f4f6;
      padding: 15px;
      text-align: center;
      font-size: 12px;
      color: #6b7280;
      border-bottom-left-radius: 12px;
      border-bottom-right-radius: 12px;
    }

    .brand {
      font-size: 11px;
      color: #9ca3af;
      margin-top: 10px;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="container">
    <!-- Encabezado -->
    <div class="header">
      <h1>Nueva Solicitud de Permiso</h1>
      <p>El usuario <?= htmlspecialchars($notify["user"]) ?> ha generado una nueva solicitud.</p>
    </div>

    <!-- Contenido -->
    <div class="content">
      <div class="details">
        <table>
          <tr>
            <td>Fecha Creación:</td>
            <td><?= htmlspecialchars($notify["fecha_creacion"]) ?></td>
          </tr>
          <tr>
            <td>Tipo de Permiso:</td>
            <td>
              <span class="status <?php echo ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'status-success' : 'status-pending'; ?>">
                <?= htmlspecialchars($tipo_permiso) ?>
              </span>
            </td>
          </tr>
          <tr>
            <td>Tipo Empleado:</td>
            <td><?= htmlspecialchars($notify["tipo_empleado"]) ?></td>
          </tr>
          <?php if ($notify["tipo_empleado"] == 'Sindicalizado') { ?>
            <tr>
              <td>Goce de Sueldo:</td>
              <td><?= htmlspecialchars($notify["goce_sueldo"]) ?></td>
            </tr>
            <?php if ($notify["goce_sueldo"] == 'SI') { ?>
              <tr>
                <td>Tiempo:</td>
                <td><?= ($notify["pago_deuda"] == 1) ? 'PAGADO' : 'POR PAGAR'; ?></td>
              </tr>
            <?php } ?>
          <?php } ?>
          <tr>
            <td>Número de Nómina:</td>
            <td><?= htmlspecialchars($notify["num_nomina"]) ?></td>
          </tr>
          <tr>
            <td>Estatus:</td>
            <td>
              <span class="status <?php echo ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'status-success' : 'status-pending'; ?>">
                <?= ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'Autorizado' : 'Pendiente'; ?>
              </span>
            </td>
          </tr>
          <tr>
            <td>Departamento:</td>
            <td><?= htmlspecialchars($notify["departamento"]) ?></td>
          </tr>
          <tr>
            <td>Puesto:</td>
            <td><?= htmlspecialchars(session()->job_position) ?></td>
          </tr>
          <?php if ($notify["hora_salida"] != "") { ?>
            <tr>
              <td>Fecha de Salida:</td>
              <td><?= htmlspecialchars($notify["fecha_salida"]) ?></td>
            </tr>
            <tr>
              <td>Hora de Salida:</td>
              <td><?= htmlspecialchars($notify["hora_salida"]) ?></td>
            </tr>
          <?php } ?>
          <?php if ($notify["hora_entrada"] != "") { ?>
            <tr>
              <td>Fecha de Entrada:</td>
              <td><?= htmlspecialchars($notify["fecha_entrada"]) ?></td>
            </tr>
            <tr>
              <td>Hora de Entrada:</td>
              <td><?= htmlspecialchars($notify["hora_entrada"]) ?></td>
            </tr>
          <?php } ?>
          <?php if ($notify["inasistencia_del"] != "") { ?>
            <tr>
              <td>Inasistencia Del:</td>
              <td><?= htmlspecialchars($notify["inasistencia_del"]) ?></td>
            </tr>
            <tr>
              <td>Inasistencia Al:</td>
              <td><?= htmlspecialchars($notify["inasistencia_al"]) ?></td>
            </tr>
          <?php } ?>
          <tr>
            <td>Observaciones:</td>
            <td><?= htmlspecialchars($notify["observaciones"]) ?></td>
          </tr>
        </table>
      </div>

      <!-- Botón de Acción -->
      <a href="<?= htmlspecialchars($link) ?>" class="button" target="_blank">Autorizar Permiso</a>
    </div>

    <!-- Pie de Página -->
    <div class="footer">
      Este es un correo automático. Por favor, no responda a este mensaje.
    </div>
    <div class="brand"><b>Desarrollado por Walworth IT</b></div>
  </div>
</body>

</html>