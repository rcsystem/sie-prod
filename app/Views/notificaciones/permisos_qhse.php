<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notificación de Visita</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f8;
      color: #333;
      margin: 0;
      padding: 2rem;
    }

    .container {
      max-width: 800px;
      margin: auto;
      background: #ffffff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    h2 {
      color: #11344b;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 1.5rem 0;
      font-size: 14px;
    }

    th, td {
      text-align: left;
      padding: 10px;
      border: 1px solid #e0e0e0;
    }

    th {
      background-color: #11344b;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .section-header {
      background-color: #e8f0f6;
      font-weight: bold;
      padding: 10px;
      color: #11344b;
    }

    .btn {
      display: inline-block;
      background-color: #11344b;
      color: white;
      padding: 12px 24px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      margin-top: 2rem;
    }

    .btn:hover {
      background-color: #0d2b3f;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Nuevo permiso de visita generado</h2>
    <p>Se ha generado un nuevo permiso de visita con los siguientes datos:</p>

    <table>
      <tr><th colspan="2">Información del Solicitante</th></tr>
      <tr><td><strong>Usuario:</strong></td><td><?= $datos["name"]; ?></td></tr>
      <tr><td><strong>Puesto:</strong></td><td><?= $datos["job"]; ?></td></tr>
      <tr><td><strong>Departamento:</strong></td><td><?= $datos["departament"]; ?></td></tr>
    </table>

    <table>
      <tr><th colspan="2">Información del Proveedor</th></tr>
      <tr><td><strong>Nombre de Proveedor:</strong></td><td><?= $datos["suppliers"]; ?></td></tr>
      <tr><td><strong>Número de personas:</strong></td><td><?= $datos["num_persons"]; ?></td></tr>
      <tr><td><strong>Departamento a visitar:</strong></td><td><?= $datos["departament_you_visit"]; ?></td></tr>
      <tr><td><strong>Razón de la visita:</strong></td><td><?= $datos["reason_for_visit"]; ?></td></tr>
      <tr><td><strong>Día y hora de la visita:</strong></td><td><?= $datos["day_you_visit"] . " " . $datos["time_of_entry"]; ?></td></tr>
      <tr><td><strong>Requiere EPP:</strong></td><td><?= ($datos["epp"] == 1) ? "SI" : "NO"; ?></td></tr>
      <tr><td><strong>Trabajos dentro de las instalaciones:</strong></td><td><?= ($datos["trabajos"] == 1) ? "SI" : "NO"; ?></td></tr>
      <tr><td><strong>Acceso con vehículo:</strong></td><td><?= ($datos["auto"] == 1) ? "SI" : "NO"; ?></td></tr>

      <?php if($datos["auto"] == 1): ?>
        <?php foreach ($cars as $val): ?>
          <tr><td><strong>Modelo:</strong></td><td><?= $val->modelo; ?></td></tr>
          <tr><td><strong>Color:</strong></td><td><?= $val->color; ?></td></tr>
          <tr><td><strong>Matrícula:</strong></td><td><?= $val->placas; ?></td></tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </table>

    <table>
      <tr><th colspan="2">Visitantes</th></tr>
      <?php foreach ($visitors as $value): ?>
        <tr><td><strong>Nombre del Visitante:</strong></td><td><?= $value->visitor; ?></td></tr>
      <?php endforeach; ?>
    </table>

    <a class="btn" href="https://sie.grupowalworth.com/qhse/autorizar" target="_blank">Autorizar Visita</a>
  </div>
</body>
</html>
