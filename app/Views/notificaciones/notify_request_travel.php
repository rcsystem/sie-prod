<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Viáticos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .header {
            background: #5C636A;
            color: #fff;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border-radius: 6px 6px 0 0;
        }
        .section {
            background: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }
        .row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Solicitud de Viáticos - Folio: <?= $data->folio ?></div>
        
        <div class="section">
            <div class="row"><span class="label">Estado:</span> <span style="background: <?= $data->color ?>; padding: 3px 6px; color: white; border-radius: 4px;"> <?= $data->txt ?> </span></div>
            <div class="row"><span class="label">Nombre:</span> <span><?= $data->user_name; ?></span></div>
            <div class="row"><span class="label">Departamento:</span> <span><?= $data->departamento; ?></span></div>
            <div class="row"><span class="label">Área Operativa:</span> <span><?= $data->area_ope; ?></span></div>
            <div class="row"><span class="label">Puesto:</span> <span><?= $data->puesto; ?></span></div>
            <div class="row"><span class="label">Número de Nómina:</span> <span><?= $data->nomina; ?></span></div>
            <div class="row"><span class="label">Viáticos Asignado:</span> <span><?= $data->total; ?></span></div>
        </div>

        <div class="header">Datos de Viaje</div>
        
        <div class="section">
            <div class="row"><span class="label">Fechas de Viaje:</span> <span><?= $data->fechas; ?></span></div>
            <div class="row"><span class="label">Viaje con Diferente Nivel Jerárquico:</span> <span><?= $data->dif_nivel; ?></span></div>
            <div class="row"><span class="label">Nivel Jerárquico:</span> <span><?= $data->grado; ?></span></div>
            <div class="row"><span class="label">Tipo de Viaje:</span> <span><?= $data->tipo_viaje; ?></span></div>
            <div class="row"><span class="label">Origen:</span> <span><?= $data->inicio_lugar; ?></span></div>
            <div class="row"><span class="label">Destino:</span> <span><?= $data->final_lugar; ?></span></div>
            <div class="row"><span class="label">Viaje en Avión:</span> <span><?= $data->avion; ?></span></div>
            <div class="row"><span class="label">Fecha y Hora Viaje Ida:</span> <span><?= $data->inicio_avion; ?></span></div>
            <div class="row"><span class="label">Fecha y Hora Viaje Regreso:</span> <span><?= $data->final_avion; ?></span></div>
            <div class="row"><span class="label">Motivo del Viaje:</span> <span><?= $data->obs; ?></span></div>
        </div>

        <div class="footer">Creado por Walworth IT</div>
    </div>
</body>
</html>
