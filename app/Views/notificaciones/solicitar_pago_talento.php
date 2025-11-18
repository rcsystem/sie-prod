<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notificación de Pago</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9fafb;
      padding: 30px;
      margin: 0;
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
      line-height: 1.6;
    }

    .content p {
      margin: 0 0 15px;
    }

    .details {
      background-color: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 15px 20px;
      margin-top: 20px;
    }

    .details p {
      margin: 8px 0;
      font-size: 14px;
      color: #4b5563;
    }

    .details strong {
      color: #111827;
      font-weight: 600;
    }

    .button {
      display: inline-block;
      background-color: #13c047;
      color: #ffffff;
      text-decoration: none;
      padding: 12px 24px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      text-align: center;
      margin-top: 25px;
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
      <h1>Notificación de Solicitud de Pago <?= htmlspecialchars($request["id_request"]); ?></h1>
      <p>Se ha registrado una nueva solicitud de pago y requiere su revisión.</p>
    </div>

    <!-- Contenido -->
    <div class="content">
      <p>Estimado(a),</p>
      <p>Por favor, revise los detalles de la solicitud de pago a continuación:</p>

      <!-- Detalles de la solicitud -->
      <div class="details">
        <p><strong>Tipo de pago:</strong> <?= htmlspecialchars($request["tipo_pago"]); ?></p>
        <p><strong>Expedir a nombre de:</strong> <?= htmlspecialchars($request["nombre_empresa"]); ?></p>
        <p><strong>Banco:</strong> <?= htmlspecialchars($request["banco"]); ?></p>
        <p><strong>Cuenta:</strong> <?= $request["cuenta"] != "" ? htmlspecialchars($request["cuenta"]) : '-'; ?></p>
        <p><strong>CLABE:</strong> <?= $request["clabe"] != "" ? htmlspecialchars($request["clabe"]) : '-'; ?></p>
        <p><strong>Cantidad:</strong> $<?= htmlspecialchars($request["cantidad"]); ?></p>
        <p><strong>Con letra:</strong> <?= htmlspecialchars($request["cantidad_letra"]); ?></p>
        <p><strong>Por concepto de:</strong> <?= htmlspecialchars($request["concepto"]); ?></p>
      </div>

      <!-- Botón de acción -->
      <a href="https://sie.grupowalworth.com/finanzas/pagar_solicitudes" class="button">Realizar Pago</a>
    </div>

    <!-- Pie de página -->
    <div class="footer">
      Este es un correo automático. Por favor, no responda a este mensaje.
    </div>
    <div class="brand"><b>Desarrollado por Walworth IT</b></div>
  </div>
</body>
</html>