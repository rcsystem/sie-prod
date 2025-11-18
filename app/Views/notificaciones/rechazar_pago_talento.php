<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Notificación de Rechazo</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
    }

    .container {
      max-width: 600px;
      background: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    h2 {
      color: #d9534f;
    }

    .details {
      background: #f8d7da;
      padding: 15px;
      border-radius: 5px;
      margin-top: 15px;
    }

    .details p {
      margin: 5px 0;
      color: #555;
    }

    .footer {
      margin-top: 20px;
      font-size: 12px;
      color: #777;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Solicitud de Pago con Folio: <?= htmlspecialchars($request[0]["id_request"]); ?></h2>
    <p>Estimado(a),</p>
    <p>Su solicitud de pago ha sido rechazada.</p>

    <div class="details">
      <p><strong>Empresa:</strong> <?= htmlspecialchars($request[0]["nombre_empresa"]); ?></p>
      <p><strong>Tipo de pago:</strong> <?= htmlspecialchars($request[0]["tipo_pago"]); ?></p>
      <p><strong>Banco:</strong> <?= htmlspecialchars($request[0]["banco"]); ?></p>
      <p><strong>Cuenta:</strong> <?= htmlspecialchars($request[0]["cuenta"]); ?></p>
      <p><strong>CLABE:</strong> <?= htmlspecialchars($request[0]["clabe"]); ?></p>
      <p><strong>Cantidad:</strong> <?= htmlspecialchars($request[0]["cantidad"]); ?></p>
      <p><strong>Cantidad letra:</strong> <?= htmlspecialchars($request[0]["cantidad_letra"]); ?></p>
      <p><strong>Concepto:</strong> <?= htmlspecialchars($request[0]["concepto"]); ?></p>
    </div>

    <p class="footer">Este es un correo automático, por favor no responda.</p>
  </div>

  <p style="font-weight:bold;color:black;font-size: 11px;"><b>Create by Walworth IT</b></p>

</body>

</html>
