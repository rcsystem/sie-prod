<style>
    body {
        font-family: 'Helvetica'
    }

    .norma {
        width: 98.5%;
        text-align: right;
    }

    .header {
        width: 100%;
        display: flex;
        height: 30px;
    }

    .header .img {
        width: 100%;
    }

    .header .img img {
        width: 200px;
        height: 39px;
        float: left;
        margin-right: auto;
        margin-left: 7px;
    }

    .header span {
        width: 300px;
        font-size: 13px;
        font-weight: bold;
        text-align: right;
        float: right;
        margin: 5px 0 0 350px;
    }

    .title {
        width: 100%;
        text-align: center;
        margin: 0 0 30px 0;
        background-color: rgb(218, 26, 34);
        color: white;
        display: flex;
        justify-content: center;
        align-content: center;
    }

    .title h3 {
        padding: 0;
        margin: auto;
    }

    .tab1 {
        width: 100%;
        border: 1px solid red;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tab1 tr td {
        padding: 4px;
        font-size: 16px;
    }

    .tab1 span {
        font-size: 16px;
        font-weight: bold;
    }

    .left_text {
        text-align: left;
        font-weight: bold;
        width: 25%;
        word-wrap: break-word;
        background-color: rgb(238, 238, 238);
    }

    .right_text {
        text-align: right;
        width: 20%;
        word-wrap: break-word;
    }

    .middle {
        width: 10%;
        background-color: transparent;
        border: none;
    }

    .title_tab {
        width: 45%;
        background: rgb(117, 117, 117);
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: center;
        border: none;
    }


    .border_off {
        border: none;
    }

    .border_right {
        border-top: none;
        border-bottom: none;
        border-left: none;
    }

    .tab2 {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        margin-left: 100rem;
    }

    .tab2 tbody tr td {
        border-left: 1px solid #BDBDBD;
        border-right: 1px solid #BDBDBD;
        border-bottom: 1px solid #BDBDBD;
    }

    .title_tab2 {
        width: 100%;
        background-color: #474D54;
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 8px;
        text-align: center;
        border: none;
    }

    .subtitle_tab2 {
        font-weight: bold;
        text-align: center;
        font-size: 12px;
        background: #BDBDBD;
        padding-top: 5px;
        padding-bottom: 5px;
    }


    .tab3 {
        width: 100%;
        font-size: 14px;
        border-collapse: collapse;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .tab3 .title_tab3 {
        width: 100%;
        background: rgb(117, 117, 117);
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: center;
        border: none;
    }

    .sub {
        width: 25%;
        background-color: red;
        font-size: 14px;
        font-weight: bold;
        padding: 8px 4px 8px 3px;
        background-color: rgb(238, 238, 238);
        border: 1px solid #BDBDBD;
    }

    .descripcion {
        width: 75%;
        background-color: transparent;
        padding: 8px 4px 8px 5px;
        border: 1px solid #BDBDBD;
        word-wrap: break-word;
    }

    .img2 img {
        width: 200px;
        height: 80px;
        float: left;
        margin-right: auto;
        margin-left: 350px;
        margin-top: -117px;
    }

    .field-tbl {
        font-weight: bold;
        text-align: center;
        font-size: 12px;
        background: #eee;
        /* padding: top der abj izq; */
        padding: 5px 10px 5px 10px;

    }

    .data-tbl {
        font-weight: bold;
        text-align: center;
        font-size: 12px;
        padding-top: 3px;
        padding-bottom: 3px;
    }

    .data-tbl-lg {
        /* font-weight: bold; */
        justify-content: flex-start;
        align-content: flex-start;
        text-align: left;
        font-size: 12px;
        padding: 5px 15px 5px 15px;

    }
</style>

<?php
$estado = [1 => 'Pendiente', 2 => 'Autorizado', 3 => 'Cancelado'];
$nivel = [0=>'Error',1 => 'Director General y Directores de Área', 2 => 'Gerentes de Jefes de Área', 3 => 'Vendedor Sr y Vendedor Jr', 4 => 'Resto del Personal',5 => 'Resto del Personal',6 => 'Resto del Personal',7 => 'Resto del Personal',8 => 'Resto del Personal'];
?>
<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <label for=""><i><!-- FAT-01-4.3.2 --></i></label>
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->created_at)); ?></label></span><br>
                <span>Hora de creación: <label for="" style="margin-left:10px"><?= date('H:i A', strtotime($request->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>
    <page_footer>
    </page_footer>

    <div class="title">
        <h3>SOLICITUD DE VIATICOS</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Solicitante:
                    </span><?= mb_convert_case($request->user_name, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Folio:
                    </span><?= mb_convert_case($request->id_request_travel, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%"><span>Departament:
                    </span><?= mb_convert_case($request->departament, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Centro de Costos:
                    </span><?= mb_convert_case($request->cost_center, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td>
                    <span>Estatus:</span>
                    <?= $estado[$request->request_status]; ?>
                </td>
                <td style="text-align:right;">
                    <span>Viaje en Avión:</span>
                    <?= $avion = ($request->airplane == 1) ? 'Si' : 'No'; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="tab2">
        <thead>
            <tr>
                <th colspan="4" class="title_tab2">DATOS DEL VIAJE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:23%;" class="field-tbl">NIVEL JERÁRQUICO: </td>
                <td style="width:27%;" class="data-tbl"><?= $nivel[$request->level]; ?></td>
                <td style="width:23%;" class="field-tbl">VÍATICOS: </td>
                <td style="width:27%;" class="data-tbl">$<?= $request->total_travel; ?></td>
            </tr>
            <tr>
                <td class="field-tbl">TIPO DE VIAJE: </td>
                <td class="data-tbl"><?= $avion = ($request->type_of_travel == 1) ? 'NACIONAL' : 'INTERNACIONAL'; ?></td>
                <td class="field-tbl">VIAJE CON ALGUIEN: </td>
                <td class="data-tbl"><?= $extra = ($request->hierarchy == 1) ? 'NO' : 'SI'; ?></td>
            </tr>
            <tr>
                <td class="field-tbl">ORIGEN: </td>
                <td class="data-tbl"><?= mb_strtoupper($request->trip_origin); ?></td>
                <td class="field-tbl">DESTINO: </td>
                <td class="data-tbl"><?= mb_strtoupper($request->trip_destination); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="subtitle_tab2">SALIDA</td>
            </tr>
            <tr>
                <td class="field-tbl">FECHA: </td>
                <td class="data-tbl"><?= date("d/m/Y", strtotime($request->start_of_trip)); ?></td>
                <td class="field-tbl">HORA: </td>
                <td class="data-tbl"><?= date("H:i A", strtotime($request->start_time)); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="subtitle_tab2">REGRESO:</td>
            </tr>
            <tr>
                <td class="field-tbl">FECHA: </td>
                <td class="data-tbl"><?= date("d/m/Y", strtotime($request->return_trip)); ?></td>
                <td class="field-tbl">HORA: </td>
                <td class="data-tbl"><?= date("H:i A", strtotime($request->return_time)); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="subtitle_tab2">MOTIVO DEL VIAJE:</td>
            </tr>
            <tr>
                <td colspan="4" class="data-tbl-lg"><?= $request->trip_details; ?></td>
            </tr>
        </tbody>
    </table>


    <!-- codigo para poner una segunda hoja -->
    <!--  <div style="page-break-after: always"></div> -->




</page>