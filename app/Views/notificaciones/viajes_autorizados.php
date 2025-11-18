<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
    
    <p><span style="color:rgb(37,37,37)">
            <p> La Solicitud de Viaje con Folio: <?= $request->id_travel; ?>, ha sido Autorizada </p>
        </span></p>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
        <tbody>
            <tr style="background:#eee">
                <td colspan="2" style="font-weight:bold">Información de la solicitud</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Usuario:</td>
                <td><?= $request->user_name; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Puesto:</td>
                <td><?= $request->job_position; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Departamento:</td>
                <td><?= $request->depto; ?></td>
            </tr>
              <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Presupuesto (Estimado):</td>
                <td><?= "$".$request->estimated_budget; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Motivo del Viaje:</td>
                    <td><?= $request->reason_for_travel; ?></td>
                </tr>
          
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Origen:</td>
                <td><?= $request->origin_of_trip; ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Destino:</td>
                <td><?= $request->trip_destination; ?></td>
            </tr>

            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Fecha y Hora Viaje Ida:</td>
                <td><?= $request->trip_start." | ".date('H:i a', strtotime($request->departure_time)); ?></td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Fecha y Hora Viaje Regreso:</td>
                <td><?= $request->return_trip." | ".date('H:i a', strtotime($request->return_time)); ?></td>
            </tr>

            <?php if($request->lodging_required == 1){ ?>
                <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Hotel Preferente:</td>
                <td><?= $request->preferred_hotel; ?></td>
            </tr>

            <?php } ?>

            <?php if($request->car_rental == 1){ ?>
                <tr style="background:#fbfbfb">
                <td style="font-weight:bold;width:180px">Persona que renta Auto:</td>
                <td><?= $request->car_rental_name; ?></td>
                </tr>

            <?php } ?>
            
            <?php if($request->request_advance == 1){ ?>
                <tr style="background:#fbfbfb">
                 <td style="font-weight:bold;width:180px">Forma de Anticipo:</td>
                 <td><?= $request->advance_type; ?></td>
                 </tr>

                 <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Cantidad:</td>
                    <td><?= "$".$request->amount; ?></td>
                 </tr>
            <?php } ?>
         
            <tr style="background:#eee">
        <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
      </tr>
        </tbody>
    </table>

    <table border="0" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td>
          <a
            style="
              background-color: #11344b;
              border: solid 1px #11344b;
              border-radius: 5px;
              box-sizing: border-box;
              color: #fff;
              cursor: pointer;
              display: inline-block;
              font-size: 14px;
              font-weight: bold;
              margin: 0;
              padding: 12px 25px;
              text-decoration: none;
              text-transform: capitalize;
            "
            href="https://sie.grupowalworth.com/viajes/solicitudes"
            target="_blank"
            >Aprovar Viaje</a
          >
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>