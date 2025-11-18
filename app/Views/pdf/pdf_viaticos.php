<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        color: #333;
        margin: 0;
        padding: 2rem;
    }

    .header {
        width: 100%;
        display: flex;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 2px solid #ccc;
    }

    .header .img img {
        width: 220px;
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
        margin: 40px 0;
        background-color: #D61F2A;
        color: white;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
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

    .tab1,
    .tab2,
    .tab3,
    .tab4 {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tab1 td,
    .tab2 td,
    .tab3 td,
    .tab4 td {
        padding: 10px;
        font-size: 12px;
        border: 1px solid #ccc;
    }

    .tab1 span,
    .tab2 span {
        font-weight: bold;
    }

    .label {
        width: 30%;
    }


    .value {
        width: 70%;
    }

    .label2 {
        width: 30%;
    }

    .value2 {
        width: 70%;
    }



    th {
        text-align: left;
        padding: 10px;
        border: 0px solid #e0e0e0;
        background-color: #D61F2A;
        color: white;
    }
</style>



<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!-- <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/logo_Walworth.png" alt="Walworth">
                <span style="margin-left:320px"><b>Fecha de creación:</b> <label for="" style=""><?= $request->creacion; ?></label></span><br>
                <span style="margin-left:670px"><b>Folio:</b> <label for="" style="margin-left:40px font-size:12px"><?= $request->folio; ?></label></span>
            </div>
        </div>
    </page_header>
    <page_footer>
    </page_footer>
    <div class="title">
        <h3>SOLICITUD DE VIATICOS</h3>
    </div>

    <table class="tab4">
        <tr>
            <th colspan="2">Información del Solicitante</th>
        </tr>
        <tr>
            <td class="label2"><strong>Nómina:</strong></td>
            <td class="value2"><?= $request->nomina; ?></td>
        </tr>
        <tr>
            <td class="label2"><strong>Usuario:</strong></td>
            <td class="value2"><?= $request->user_name; ?></td>
        </tr>
        <tr>
            <td class="label2"><strong>Grado:</strong></td>
            <td class="value2"><?= $request->grado; ?></td>
        </tr>
        <tr>
            <td class="label2"><strong>Departamento:</strong></td>
            <td class="value2"><?= $request->departament; ?></td>
        </tr>
    </table>


    <table border="1" class="tab1">



        <tr>
            <td class="label">Periodo</td>
            <td class="value"><?= $request->fechas; ?></td>
        </tr>
        <tr>
            <td class="label">Tipo de viaje</td>
            <td class="value"><?= $request->tipo_viaje; ?></td>
        </tr>
        <tr>
            <td class="label">¿Viaje con avión?</td>
            <td class="value"><?= $request->avion; ?></td>
        </tr>
        <tr>
            <td class="label">Destino</td>
            <td class="value"><?= $request->destino; ?></td>
        </tr>
        <tr>
            <td class="label">Viáticos Asignados</td>
            <td class="value"><?= $request->total; ?></td>
        </tr>
        <tr>
            <td class="label">Estado</td>
            <td class="value"><span class="badge" style="background-color: <?= $request->color; ?>; color:#FFF;"><?= $request->txt; ?></span></td>
        </tr>
    </table>

    <div style="text-align:center; font-size:10px; color:#888;">
        Generado automáticamente por el sistema
    </div>

</page>