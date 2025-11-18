<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <?php if ($datas->request_status != 6) {
        if ($datas->request_status == 1) { ?> <p><span style="color:rgb(37,37,37)">
                    <p>Se ha generado una nueva Solicitud de Viaje. <br></p>
                </span></p>
        <?php } else if ($datas->request_status == 2) { ?>
            <p><span style="color:rgb(37,37,37)">
                    <p> La solicitud de Viaje con Folio: <?= $datas->id_travel; ?>, ha sido Autorizada por <?= $manager->name; ?> <?= $manager->surname; ?> <?= $manager->second_surname; ?>
                        <br> Datos de Solicitud
                    </p>
                </span></p>
        <?php } else if ($datas->request_status == 3) { ?>
            <?php if ($datas->request_advance == 1) { ?>
                <p><span style="color:rgb(37,37,37)">
                        <p> Tu Solicitud de Viaje con Folio: <?= $datas->id_travel; ?>, ha sido Autorizada y se te proporcionara <b> $<?= $datas->estimated_budget_approve; ?> </b> de tu presupuesto estimado del Viaje y
                        <br><b> $<?= $datas->amount_approve; ?></b> del Anticipo solicitado.
                        <br> Comentario Adicional: <?= $datas->cancel; ?>.
                        <br>No olvides subir tu comprobación de gastos. (Formato específico).
                        </p>
                    </span></p>
            <?php } else { ?>
                <p><span style="color:rgb(37,37,37)">
                        <p> Tu Solicitud de Viaje con Folio: <?= $datas->id_travel; ?>, ha sido Autorizada y se te proporcionara <b> $<?= $datas->estimated_budget_approve; ?> </b>de tu presupuesto estimado del Viaje,
                        <br> Comentario Adicional: <?= $datas->cancel; ?>.
                        <br>No olvides subir tu comprobación de gastos. (Formato específico).
                        </p>
                    </span></p>
            <?php } ?>
        <?php } ?>
        <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
            <tbody>
                <tr style="background:#eee">
                    <td colspan="2" style="font-weight:bold">Información del Solicitante</td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Nombre</td>
                    <td><?= $datas->user_name; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Departamento</td>
                    <td><?= $datas->depto; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Area Operativa</td>
                    <td><?= $datas->cost_center; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Puesto</td>
                    <td><?= $datas->job_position; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Número de Nómina</td>
                    <td><?= $datas->payroll_number; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Presupuesto (Estimado):</td>
                    <td><?= "$" . $datas->estimated_budget; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Motivo del Viaje:</td>
                    <td><?= $datas->reason_for_travel; ?></td>
                </tr>

                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Origen:</td>
                    <td><?= $datas->origin_of_trip; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Destino:</td>
                    <td><?= $datas->trip_destination; ?></td>
                </tr>

                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Fecha y Hora Viaje Ida:</td>
                    <td><?= $datas->trip_start . " | " . date('H:i a', strtotime($datas->departure_time)); ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Fecha y Hora Viaje Regreso:</td>
                    <td><?= $datas->return_trip . " | " . date('H:i a', strtotime($datas->return_time)); ?></td>
                </tr>

                <?php if ($datas->lodging_required == 1) { ?>
                    <tr style="background:#fbfbfb">
                        <td style="font-weight:bold;width:180px">Hotel Preferente:</td>
                        <td><?= $datas->preferred_hotel; ?></td>
                    </tr>

                <?php } ?>

                <?php if ($datas->car_rental == 1) { ?>
                    <tr style="background:#fbfbfb">
                        <td style="font-weight:bold;width:180px">Persona que renta Auto:</td>
                        <td><?= $datas->car_rental_name; ?></td>
                    </tr>

                <?php } ?>
                <?php if ($datas->request_advance == 1) { ?>
                    <tr style="background:#fbfbfb">
                        <td style="font-weight:bold;width:180px">Monto Solicitado</td>
                        <td>$ <?= $datas->amount; ?></td>
                    </tr>
                    <tr style="background:#eee">
                        <td colspan="2" style="font-weight:bold;width:180px">Detalles de Anticipo</td>
                    </tr>
                    <tr style="background:#fbfbfb">
                        <td style="font-weight:bold;width:180px">Tipo</td>
                        <td><?= $datas->advance_type; ?></td>
                    </tr>
                    <?php if ($datas->amount_approve != null) {
                         foreach ($items as $key) { ?>
                        <tr>
                            <td style="font-weight:bold;width:180px"><?= $key->description; ?> Aprobado</td>
                            <td>$ <?= $key->monto_approve; ?></td>
                        </tr>
                    <?php }
                    }else{ 
                        foreach ($items as $key) { ?>
                        <tr>
                            <td style="font-weight:bold;width:180px"><?= $key->description; ?></td>
                            <td>$ <?= $key->monto; ?></td>
                        </tr>
                <?php } 
                }?>
                <?php } ?>
                <tr style="background:#eee">
                    <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
                </tr>
            </tbody>
        </table><?php } else { ?>
        <p><span style="color:rgb(37,37,37)">
                <p> Tu Solicitud de Viaje con Folio: <?= $datas->id_travel; ?>, ha sido Rechazada.
                    Por el sisguiente Motivo:<br>
                    <?= $datas->cancel; ?>.
                </p>
            </span></p>
    <?php } ?>
</body>

</html>