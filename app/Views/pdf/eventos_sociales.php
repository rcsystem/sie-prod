<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    .titulo {
        background-color: #2c3e50;
        color: white;
        padding: 18px;
        text-align: center;
        font-weight: bold;
        font-size: 18px;
    }

    .tabla-datos,
    .tabla-permisos {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        
    }

    .tabla-datos td {
        padding: 5px;
    }

    

    .tabla-datos td,
    .tabla-datos th {
        padding: 5px;
        text-align: left;
        border: 1px solid #eee;
    }

    .tabla-permisos td,
    .tabla-permisos th {
        border: 1px solid #eee;
        padding: 5px;
        text-align: left;
    }

    .subtitulo {
        background-color: #2c3e50;
        color: white;
        padding: 5px;
        font-weight: bold;
        font-size: large;
        text-align: center;
    }


    body {
        font-family: 'Arial', sans-serif;
        color: #333;
        font-size: 12px;
    }

    .header {
        width: 100%;
        display: flex;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 2px solid #ccc;
    }

    .header .img img {
        width: 180px;
        height: auto;
        margin-right: auto;
    }

    .header .info {
        font-size: 12px;
        text-align: right;
        margin-right: 10px;
    }

    .title {
        width: 100%;
        text-align: center;
        margin: 20px 0;
        background-color: #2c3e50;
        color: white;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
    }

    .tab1,
    .tab2,
    .tab3 {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tab1 td,
    .tab2 td,
    .tab3 td {
        padding: 10px;
        font-size: 12px;
        border: 1px solid #ccc;
    }

    .tab1 span,
    .tab2 span {
        font-weight: bold;
    }

    .tab3 thead th {
        background-color: #34495e;
        color: white;
        padding: 10px;
        font-size: 14px;
        text-align: center;
    }

    .title-tab {
        /* background-color: #bdc3c7; */
        font-weight: bold;
        padding: 10px;
        text-align: right;
    }

    .celd-tab {
        font-size: 12px;
        padding: 8px;
    }

    .observaciones {
        font-size: 12px;
        margin: 0 10px;
    }

    .footer {
        text-align: right;
        font-size: 10px;
        color: #777;
    }
</style>

<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!-- <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/logo_Walworth.png" alt="Grupo Walworth">
                <span style="margin-left:380px">Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->created_at)); ?></label></span><br>
                <!-- <span>Hora de creación: <label for=""style="margin-left:40px"><?= date('H:i', strtotime($request->created_at)); ?></label></span> -->
            </div>
        </div>
    </page_header>
    <page_footer>
    </page_footer>
    <br>
    <br>

    <div class="titulo">EVENTO <?= $request->activity ?></div>

    <table class="tabla-datos">
        <tr>
            <td style="width:70%"><strong>Solicitante:</strong> <?= $request->user_name ?></td>
            <td style="width:30%;text-align:right;"><strong>Folio:</strong> <?= $request->id_volunteering ?></td>
        </tr>
        <tr>
            <td style="width:70%"><strong>Departamento:</strong> <?= $request->departament ?></td>
            <td style="width:30%;text-align:right;"><strong>Teléfono:</strong> <?= $request->tel_user ?></td>
        </tr>
        <tr>
            <td style="width:70%"><strong>Puesto:</strong> <?= $request->job_position ?></td>
            <td style="width:30%;text-align:right;"><strong>Nómina:</strong> <?= $request->payroll_number ?></td>
        </tr>
        <tr>
            <td style="width:70%"><strong>Tipo de evento:</strong> <?= $request->tipo_evento ?></td>
            <td style="width:30%;text-align:right;"><strong>Estatus:</strong> <?= ucfirst($request->assistance) ?></td>
        </tr>
       
        <tr>
            <td style="width:70%"><strong>Actividad:</strong> <?= $request->activity ?></td>
            <td style="width:30%;text-align:right;"><strong>Fecha del evento:</strong> <?= date('d/m/Y', strtotime($request->event_date)) ?></td>
        </tr>
      
        <tr>
            <td colspan="2"><strong>Motivo:</strong> <?= $request->obs_volunteering ?></td>
        </tr>
    </table>

    <br>

    <div class="subtitulo">INVITADOS</div>
    <table class="tabla-permisos">
        <thead>
            <tr>
                <th>Nombre del Invitado</th>
                <th>Talla</th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($extra)) : ?>
                <?php foreach ($extra as $invitado) : ?>
                    <tr>
                        <td style="width:70%"><?= $invitado->nombre_invitado ?></td>
                        <td style="width:30%;"><?= $invitado->talla_invitado ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="2">No hay invitados registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</page>