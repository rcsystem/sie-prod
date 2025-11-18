<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
    .header { text-align: center; margin-bottom: 20px; }
    .header h1 { font-size: 18px; margin: 0; }
    .content { padding: 0 15px; }
    .content p { margin: 8px 0; }
    .details { background: #f0f4ff; border-radius: 5px; padding: 10px; margin: 15px 0; }
    .details strong { color: #2c3e50; }
    .footer { font-size: 10px; color: #777; text-align: center; margin-top: 30px; }
  </style>
</head>
<body>
  <div class="header">
    <h1>Notificación de Código de Seguridad</h1>
    <hr>
  </div>
  <div class="content">
    <p>Fecha y hora: <?= $date ?></p>
    <p>Estimado(a),</p>
    <p>Para continuar con la firma de la solicitud <strong>Folio <?= htmlspecialchars($folio) ?></strong>,</p>
    <p>por favor ingresa el siguiente <strong>código de validación</strong> en la pantalla correspondiente:</p>

    <div class="details">
      <p><strong>Código:</strong> <h1> <?= htmlspecialchars($code) ?></h1></p>
      <p><strong>Folio:</strong><h1> <?= htmlspecialchars($folio) ?></h1></p>
    </div>

    <p>Este código es válido solo por 15 minutos. Si no recibes el código o expira, solicita uno nuevo desde la aplicación.</p>
  </div>

  <div class="footer">
    <p>Este es un correo automático generado por el sistema. Por favor, no responda a este mensaje.</p>
  </div>
</body>
</html>
