<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pago Exitoso</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f4;
      padding: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 50vh;
    }

    .container {
      max-width: 1100px;
      background: #ffffff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    h2 {
      color: #28a745;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .request-id {
      background:rgb(60, 211, 0);
      color: #212529;
      padding: 10px;
      border-radius: 5px;
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 15px;
      display: inline-block;
    }

    p {
      color: #333;
      font-size: 14px;
      margin: 5px 0;
    }

    .details {
      background: #d4edda;
      padding: 15px;
      border-radius: 5px;
      margin-top: 15px;
      text-align: left;
    }

    .details p {
      color: #155724;
      font-weight: 500;
    }

    .comment {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-top: 15px;
      text-align: left;
      font-style: italic;
      color: #555;
      font-size: 13px;
    }

    .footer {
      margin-top: 20px;
      font-size: 12px;
      color: #777;
    }

    .button {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #28a745;
      color: #fff;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      border-radius: 5px;
      transition: 0.3s ease;
    }

    .button:hover {
      background-color: #218838;
    }

    .brand {
      font-weight: bold;
      color: #444;
      font-size: 11px;
      margin-top: 15px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>✅ Pago Exitoso</h2>
    
    <p>Estimado(a),</p>
    <p>Le informamos que su pago con <b>N° de Solicitud: <?= htmlspecialchars($request[0]["id_request"]); ?> </b> ha sido </p>
    <p>procesado exitosamente.</p>

    <div class="details">
      <p><strong>🏢 Empresa:</strong> <?= htmlspecialchars($request[0]["company"]); ?></p>
      <p><strong>📅 Mes:</strong> <?= htmlspecialchars($request[0]["month"]); ?></p>
      <p><strong>⏳ Frecuencia:</strong> <?= htmlspecialchars($request[0]["type_of_payroll"]); ?></p>
      <p><strong>📖 Concepto:</strong> <?= htmlspecialchars($request[0]["application_concept"]); ?></p>
      <p><strong>📆 Periodo:</strong> <?= htmlspecialchars($request[0]["period"]); ?></p>
      <p><strong>🕒 Fecha de Pago:</strong> <?= htmlspecialchars($request[0]["date_request"]); ?></p>
      <p><strong>💲 Monto:</strong> $<?= number_format($request[0]["amount"], 2); ?></p>
    </div>

    <div class="comment">
      <p><strong>📝 Comentario:</strong> <?= !empty($request[0]["comentario_pago"]) ? htmlspecialchars($request[0]["comentario_pago"]) : "Sin comentarios."; ?></p>
    </div>

    <a href="<?= base_url('/') ?>" class="button">Volver al Inicio</a>

    <p class="footer">Este es un mensaje automático, por favor no responda.</p>
    <p class="brand">🔹 Creado por <b>Walworth IT</b></p>
  </div>
</body>

</html>
