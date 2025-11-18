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
        background-color: #0056B3;
        color: white;
        display: flex;
        justify-content: center;
        align-content: center;
    }


    .tab1 {
        width: 100%;
        border: 1px solid red;
        border-collapse: collapse;
        margin-bottom: 10px;
        padding-top: -15px;
        padding-bottom: 20px;
    }

    .tab1 tr td {
        padding: 4px;
        font-size: 16px;
    }

    .title_tab {
        width: 100%;
        background: rgb(117, 117, 117);
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: center;
        border: none;
    }

    /* tabla para datos */
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
        border-collapse: collapse;
        font-size: 12px;
    }

    .tab3 tbody tr td {
        width: 100%;
        height: 40px;
        border: none;
        border-left: 1px solid #BDBDBD;
        border-right: 1px solid #BDBDBD;
        border-bottom: 1px solid #BDBDBD;
        border-top: 1px solid #BDBDBD;
        padding: 5px 4px 5px 3px;
    }

    .border_none {
        border-left: none;
        border-right: none;
        border-bottom: none;
        border-top: none;
    }

    .border_left_right {
        border-bottom: none;
        border-top: none;
    }

    .border_top_bottom {
        border-left: none;
        border-right: none;
    }

    .field_tbl {
        font-weight: bold;
        text-align: center;
        font-size: 12px;
        background: #eee;
        /* padding: top der abj izq; */
        padding: 5px 10px 5px 10px;

    }

    .data_tbl {
        font-weight: bold;
        text-align: center;
        font-size: 12px;
        padding-top: 3px;
        padding-bottom: 3px;
    }

    .data_tbl_lg {
        /* font-weight: bold; */
        justify-content: flex-start;
        align-content: flex-start;
        text-align: left;
        font-size: 12px;
        padding: 5px 15px 5px 15px;

    }
</style>
<?php
$sistema = ($request->system == "OTRO") ? $request->other_system : $request->system;
?>
<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!--  <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->created_at)); ?></label></span><br>
                <span>Hora de creación: <label for="" style="margin-left:10px"><?= date('H:i a', strtotime($request->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>

    <div class="title">
        <h3>INCAPACIDAD MEDICA</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Empleado:
                    </span><?= mb_convert_case($request->user_name, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span style="font-weight:bold;">Folio:
                    </span><?= mb_convert_case($request->id_request, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Departamento:
                    </span><?= ucwords(strtolower($request->departament)); ?></td>
                <td style="width:30%;text-align:right;"><span style="font-weight:bold;">Puesto:
                    </span><?= ucwords(strtolower($request->position_job)); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Atendido Por:
                    </span><?= ucwords(strtolower($request->user_generate)); ?></td>
                <td style="width:30%;text-align:right;"><span style="font-weight:bold;">Goce de Sueldo:
                    </span><?= ucwords(strtolower($request->salary)); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab2">
        <thead>
            <tr>
                <th colspan="4" class="title_tab2">DATOS DEL PERMISO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:25%;" class="field_tbl">TIPO DE PERMISO: </td>
                <td style="width:30%;" class="data_tbl"><?= ucwords(strtolower($request->type_permission)); ?></td>
                <td style="width:15%;" class="field_tbl">MOTIVO: </td>
                <td style="width:30%;" class="data_tbl"><?= ucwords(strtolower($request->motive)); ?></td>
            </tr>
            <?php if ($request->date_out != '0000-00-00') { ?>
                <tr>
                    <td colspan="4" class="subtitle_tab2">SALIDA</td>
                </tr>
                <tr>
                    <td style="width:25%;" class="field_tbl">FECHA: </td>
                    <td style="width:30%;" class="data_tbl"><?= date("d/m/Y", strtotime($request->date_out)); ?></td>
                    <td style="width:15%;" class="field_tbl">HORA: </td>
                    <td style="width:30%;" class="data_tbl"><?= date("H:i", strtotime($request->time_out)); ?></td>
                </tr>
            <?php }
            if ($request->date_star != '0000-00-00') { ?>
                <tr>
                    <td colspan="4" class="subtitle_tab2">INASISTENCIA</td>
                </tr>
                <tr>
                    <td style="width:25%;" class="field_tbl">DEL DÍA: </td>
                    <td style="width:30%;" class="data_tbl"><?= date("d/m/Y", strtotime($request->date_star)); ?></td>
                    <td style="width:15%;" class="field_tbl">HASTA: </td>
                    <td style="width:30%;" class="data_tbl"><?= date("d/m/Y", strtotime($request->date_end)); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <br><br>

    <table class="tab3">
        <thead>
            <tr>
                <th colspan="2" class="title_tab2">INFORMACION DE CONSULTA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:25%;" class="field_tbl">
                    <span style="font-weight:bold;">SISTEMA:</span>
                </td>
                <td style="width:75%;" class="data_tbl_lg">
                    <?= mb_convert_case($sistema, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
            </tr>
            <tr>
                <td style="width:25%;" class="field_tbl">
                    <span style="font-weight:bold;">DIAGNOSTICO:</span>
                </td>
                <td style="width:75%;" class="data_tbl_lg">
                    <?= $request->diagnostic; ?>
                </td>
            </tr>
            <tr>
                <td style="width:25%;" class="field_tbl">
                    <span style="font-weight:bold;">OBSERVACIONES:</span>
                </td>
                <td style="width:75%;" class="data_tbl_lg">
                    <?= $request->obs; ?>
                </td>
            </tr>
        </tbody>
    </table>


    <page_footer>
    </page_footer>
</page>