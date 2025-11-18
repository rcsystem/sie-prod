<!DOCTYPE html>

<html lang="en">

<head> <meta charset="UTF-8"> </head>

<body>
    <?php
    $clasificacion = ['Error', 'Soporte Técnico', 'Otros Servicios', 'Consulta/Capacitación'];
    $prioridad = ['No Definido', 'No Definido', 'BAJA', 'MEDIA', 'ALTA'];
    ?>

    <div style="border: solid 3px #3C3C48;border-radius: 15px;width: 50%;height: auto;margin-right: auto;margin-left: auto;padding: 10px;">
        <div style="display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -7.5px;margin-left: -7.5px;margin-left: auto;margin-right: auto;">
            <div style="width: 30%;padding: 2px;">
                <img src="http://bkstrategy.mx/bks/assets/img/logo_sg.png" style="max-width: 100%;height: auto;margin-top:5%">
            </div>
            <div style="width: 65%;">
                <form action="<?= base_url() ?>/tickets/servicios-generales">
                    <?php if ($tipo == 1) { ?>
                        <p style="margin-left:5%;font-family: Century Gothic;">Se ha generado un nuevo ticket con los siguientes datos:</p>
                    <?php } else if ($tipo == 2) {
                        $opcion = ['error', 'comentario', 'archivo']; ?>
                        <p style="margin-left:5%;font-family: Century Gothic;">Se ha agregado un <b><?= $opcion[$opc] ?></b> al ticket con los siguientes datos:</p>
                    <?php } else if ($tipo == 3) {
                        $opcion = ['error', 'Nuevo', 'En proceso', 'Concluido', 'Cancelado', 'Cerrado']; ?>
                        <p style="margin-left:5%;font-family: Century Gothic;">Su ticket con los siguientes datos paso a "<b><?= $opcion[$ticket->estatus]; ?></b>"</p>
                    <?php } else if ($tipo == 4) { ?>
                        <p style="margin-left:5%;font-family: Century Gothic;"> El usario <?= $ticket->Ticket_UsuarioCreacion; ?> a "<b>Cancelado</b>" el Ticket por el siguiente motivo: <br> <?= $ticket->motive_cancel; ?></p>
                    <?php } ?>
                    <ul class="cuadrado" style="list-style-type: circle;font-family: Century Gothic;">
                        <?php if ($tipo == 1) { ?>
                            <li style="padding: 2px;">Folio: <b><?= $id; ?></b></li>
                            <li style="padding: 2px;">Actividad: <b><?= $ticket->act; ?></b></li>
                            <li style="padding: 2px;">Usuario: <b><?= $ticket->Ticket_UsuarioCreacion; ?></b></li>
                        <?php } else { ?>
                            <li style="padding: 2px;">Folio: <b><?= $id; ?></b></li>
                            <li style="padding: 2px;">Actividad: <b><?= $ticket->act; ?></b></li>
                            <li style="padding: 2px;">Prioridad: <b><?= $prioridad[$ticket->Ticket_PrioridadId]; ?></b></li>
                            <li style="padding: 2px;">Usuario: <b><?= $ticket->Ticket_UsuarioCreacion; ?></b></li>
                        <?php } ?>
                    </ul>
                    <input type="submit" value="Ir" style="float:right;display: inline-block;font-weight: 400;color: #212529;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-color: transparent;border: 1px solid transparent;padding: 0.375rem 0.75rem;font-size: 1rem;line-height: 1.5;border-radius: 0.25rem;transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;color: #fff;background-color: #dc3545;border-color: #dc3545;box-shadow: none;">
                </form>
            </div>
        </div>
    </div>
</body>

</html>