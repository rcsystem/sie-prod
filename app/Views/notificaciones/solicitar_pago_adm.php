<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Notificación de Pago</title>
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
      color: #333;
    }

    .details {
      background: #eef5ff;
      padding: 15px;
      border-radius: 5px;
      margin-top: 15px;
    }

    .details p {
      margin: 5px 0;
      color: #555;
    }

    .button {
      
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
    <h2>Solicitud de Pago</h2>
    <p>Estimado(a),</p>
    <p>Se ha registrado una nueva solicitud de pago y ahora se requiere realizar el pago correspondiente.</p>
    <?php

    // var_dump($request);
    ?>
    <div class="details">

      <p><strong>Empresa:</strong> <?= htmlspecialchars($request["company"]); ?></p>
      <p><strong>Mes:</strong> <?= htmlspecialchars($request["month"]); ?></p>
      <p><strong>Frecuencia:</strong> <?= htmlspecialchars($request["type_of_payroll"]); ?></p>
      <p><strong>Concepto:</strong> <?= htmlspecialchars($request["application_concept"]); ?></p>
      <p><strong>Periodo:</strong> <?= htmlspecialchars($request["period"]); ?></p>
      <p><strong>Fecha de Solicitud:</strong> <?= htmlspecialchars($request["date_request"]); ?></p>
      <p><strong>Monto:</strong> $<?= number_format($request["amount"], 2); ?></p>
      <p><strong>Referencia:</strong> <?= htmlspecialchars($request["comment"]); ?></p>

    </div>

    <a href="https://sie.grupowalworth.com/finanzas/pagar_solicitud"  style="display: inline-block;
      padding: 10px 20px;
      background-color:rgb(19, 192, 71);
      color: #ffffff;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 15px;">Realizar Pago</a>

    <p class="footer">Este es un correo automático, por favor no responda.</p>
  </div>

  <p style="font-weight:bold;color:black;font-size: 11px;"><b>Create by Walworth IT</b></p>

</body>

</html>
