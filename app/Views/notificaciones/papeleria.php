<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');
    
    body {
      font-family: 'Open Sans', Arial, sans-serif;
      line-height: 1.6;
      color: #333333;
      background-color: #f7f7f7;
      margin: 0;
      padding: 20px;
    }
    
    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .email-header {
      background-color: #11344b;
      color: white;
      padding: 20px;
      text-align: center;
    }
    
    .email-title {
      margin: 0;
      font-size: 20px;
      font-weight: 700;
    }
    
    .email-body {
      padding: 25px;
    }
    
    .intro-text {
      margin-bottom: 25px;
      color: #444444;
    }
    
    .info-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      border-radius: 6px;
      overflow: hidden;
    }
    
    .info-table thead tr {
      background-color: #11344b;
      color: #ffffff;
    }
    
    .info-table th {
      padding: 12px 15px;
      text-align: left;
      font-weight: 600;
    }
    
    .info-table tbody tr {
      border-bottom: 1px solid #e0e0e0;
    }
    
    .info-table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    
    .info-table tbody tr:last-child {
      border-bottom: 2px solid #11344b;
    }
    
    .info-table td {
      padding: 12px 15px;
      vertical-align: top;
    }
    
    .info-table .label {
      font-weight: 600;
      color: #11344b;
      width: 35%;
    }
    
    .section-header {
      background-color: #e9f0f5 !important;
      font-weight: 700 !important;
      color: #11344b !important;
    }
    
    .button-container {
      text-align: center;
      margin: 30px 0 20px;
    }
    
    .action-button {
      background-color: #11344b;
      border: none;
      border-radius: 5px;
      color: white;
      padding: 12px 30px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    
    .action-button:hover {
      background-color: #0a2538;
    }
    
    .footer {
      text-align: center;
      padding: 15px;
      font-size: 12px;
      color: #777777;
      background-color: #f5f5f5;
    }
    
    @media screen and (max-width: 600px) {
      .email-container {
        width: 100%;
        border-radius: 0;
      }
      
      .info-table {
        display: block;
        overflow-x: auto;
      }
      
      .label {
        width: 120px !important;
      }
    }
  </style>
</head>

<body>
  <div class="email-container">
    <div class="email-header">
      <h1 class="email-title">Nueva Solicitud de Papelería</h1>
    </div>
    
    <div class="email-body">
      <p class="intro-text">Se ha generado una nueva solicitud. Aquí están los detalles:</p>
      
      <table class="info-table">
        <thead>
          <tr>
            <th colspan="2">Información del Solicitante</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="label">Email</td>
            <td><?= $request->email; ?></td>
          </tr>
          <tr>
            <td class="label">Empresa</td>
            <td>Walworth</td>
          </tr>
          <tr>
            <td class="label">Nombre</td>
            <td><?= mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8"); ?></td>
          </tr>
          <tr>
            <td class="label">Número de Empleado</td>
            <td><?= $request->payroll_number; ?></td>
          </tr>
          <tr>
            <td class="label">Departamento</td>
            <td><?= $request->departament; ?></td>
          </tr>
          <tr>
            <td class="label">Centro de Costo</td>
            <td><?= $request->cost_center; ?></td>
          </tr>

          <?php if (!empty($personal)) { ?>
            <?php foreach ($personal as $label => $opt) { ?>
              <tr class="section-header">
                <td colspan="2"><?= $label; ?></td>
              </tr>
              <?php foreach ($opt as $id => $names) { ?>
                <tr>
                  <td class="label"><?= $id; ?></td>
                  <td><?= $names; ?></td>
                </tr>
              <?php } ?>
            <?php } ?>
          <?php } ?>
          
          <?php if ($request->obs_request != "") { ?>
            <tr class="section-header">
              <td colspan="2">Observaciones</td>
            </tr>
            <tr>
              <td colspan="2"><?= $request->obs_request; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      
      <?php if($option != 2) { ?>
        <div class="button-container">
          <a href="https://sie.grupowalworth.com/papeleria/autorizar" class="action-button" target="_blank">
            Autorizar Papelería
          </a>
        </div>
      <?php } ?>
    </div>
    
    <div class="footer">
      <p>Create by Walworth IT</p>
    </div>
  </div>
</body>

</html>