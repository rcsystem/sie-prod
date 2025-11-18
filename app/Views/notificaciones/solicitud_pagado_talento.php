<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Notificación de Solicitud de Pago</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f2ec;
      padding: 20px;
    }

    .container {
      max-width: 600px;
      background: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
      text-align: center;
    }

    h2 {
      color: #d35400;
    }

    .details {
      background: #fbe8d4;
      padding: 15px;
      border-radius: 5px;
      margin-top: 15px;
      text-align: left;
    }

    .details p {
      margin: 5px 0;
      color: #333;
      font-size: 14px;
    }

    .button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #d35400;
      color: #ffffff;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 15px;
      font-weight: bold;
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
    <h2>📄 Solicitud de Cheque / Transferencia</h2>
    <p>Estimado(a),</p>
    <p>Se ha registrado una nueva solicitud de pago con Folio:  <?= htmlspecialchars($request["id_request"]); ?>  y está en proceso de revisión.</p>

    <div class="details">
      <p>📅 <strong>Fecha:</strong> <?php $meses = [
                                        1 => 'enero',
                                        2 => 'febrero',
                                        3 => 'marzo',
                                        4 => 'abril',
                                        5 => 'mayo',
                                        6 => 'junio',
                                        7 => 'julio',
                                        8 => 'agosto',
                                        9 => 'septiembre',
                                        10 => 'octubre',
                                        11 => 'noviembre',
                                        12 => 'diciembre'
                                    ];

                                    $created_at = $request["created_at"];

                                    // Convertir la fecha en objeto DateTime
                                    $fechaObj = DateTime::createFromFormat('Y-m-d H:i:s', $created_at);

                                    // Extraer día, mes y año
                                    $dia = $fechaObj->format('d');
                                    $mes = (int) $fechaObj->format('m'); // Convertir a número entero
                                    $año = $fechaObj->format('Y');

                                    // Construir la fecha en español
                                    $fecha = "{$dia} de {$meses[$mes]} de {$año}";
                                    echo $fecha; ?></p>
      <p>💳 <strong>Tipo de pago:</strong> <?= htmlspecialchars($request["tipo_pago"]); ?></p>
      <p>🏦 <strong>Expedir a nombre de:</strong><?= htmlspecialchars($request["nombre_empresa"]); ?></p>
      <p>🏛️ <strong>Banco:</strong> <?= htmlspecialchars($request["banco"]); ?></p>
      <p>🔢 <strong>Cuenta:</strong> <?= $request["cuenta"] != "" ? htmlspecialchars($request["cuenta"]): ''; ?></p>
      <p>🔗 <strong>CLABE:</strong> <?=  $request["clabe"] != "" ? htmlspecialchars($request["clabe"]): ''; ?></p>
      <p>💰 <strong>Cantidad:</strong> <?= htmlspecialchars($request["cantidad"]); ?></p>
      <p>📝 <strong>Con letra:</strong> <?= htmlspecialchars($request["cantidad_letra"]); ?></p>
      <p>📌 <strong>Por concepto de:</strong> <?= htmlspecialchars($request["concepto"]); ?> </p>
    </div>

    <a href="https://sie.grupowalworth.com/finanzas/solicitudes_pagos" class="button">🔎 Revisar Solicitud</a>

    <p class="footer">Este es un correo automático, por favor no responda.</p>
  </div>

  <p style="font-weight:bold;color:black;font-size: 11px;"><b>⚙️ Created by Walworth IT</b></p>
</body>

</html>
