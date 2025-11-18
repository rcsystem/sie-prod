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
        padding: none;
    }

    .title_tab2 {
        width: 58%;
        background-color: #BDBDBD;
        color: black;
        font-weight: bold;
        font-size: 16px;
        padding: 8px;
        text-align: center;
        border: none;
    }

    .subtitle {
        width: 58%;
        background-color: #EEE;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        padding-top: 0px;
        padding-bottom: 7px;
    }

    .border-none {
        border-left: none;
        border-right: none;
        border-bottom: none;
        border-top: none;
    }

    .-no-border-right {
        border-right: none;
    }

    .no-border-left {
        border-left: none;
    }

    .no-border-bottom {
        border-bottom: none;
    }

    .no-border-top {
        border-top: none;
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

    .field_tbl {
        border-top: 1px solid #BDBDBD;
        font-weight: bold;
        text-align: center;
        font-size: 15px;
        background: #eee;
        /* padding: top der abj izq; */
        padding: 5px 10px 5px 10px;

    }

    .data_tbl {
        /* font-weight: bold; */
        text-align: center;
        font-size: 15px;
        padding-top: 3px;
        padding-bottom: 3px;
    }


    .data_tbl3 {
        padding-left: 9px;
        padding-right: 5px;
        padding-top: -2px;
        padding-bottom: -2x;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        text-align: left;
    }

    .data_tbl_carac {
        padding-left: 12px;
        padding-right: 5px;
        padding-top: -2px;
        padding-bottom: -2px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        text-align: left;
    }
</style>
<?php

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
        <h3>SOLICITUDES DE ANTICIPO </h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span style="font-weight:bold;">Solicitante:
                    </span><?= mb_convert_case($request->user, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span style="font-weight:bold;">Folio:
                    </span><?= mb_convert_case($request->id_request, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Departamento: </span><?= ucwords(strtolower($request->departament)); ?></td>
                <td style="width:30%;text-align:right;"><span style="font-weight:bold;">Area Operativa:</span><?= ucwords(strtolower($request->cost_center)); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Puesto: </span><?= ucwords(strtolower($request->job_position)); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab2">
        <thead>
            <tr>
                <th colspan="3" class="title_tab2">DATOS DE ENVIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:10%;" class="field_tbl"> # </td>
                <td style="width:50%;" class="field_tbl">TIPO DE GASTO</td>
                <td style="width:40%;" class="field_tbl">MONTO SOLICITADO</td>
            </tr>
            <?php $i = 1;
            foreach ($item as $key => $value) { ?>
                <tr>
                    <td style="width:10%;" class="data_tbl"><?= ucwords(strtolower($i)); ?></td>
                    <td style="width:50%;" class="data_tbl"><?= mb_convert_case($value->description, MB_CASE_TITLE, "UTF-8"); ?></td>
                    <td style="width:40%;" class="data_tbl"><?= "$ " . floatval($value->monto); ?></td>
                </tr>
            <?php $i++;
            } ?>
            <tr>
                <td style="width:60%;" class="field_tbl" colspan="2">TOTAL</td>
                <td style="width:40%;" class="data_tbl"><?= "$ " . floatval($request->total); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab3" style="width:max-content;margin-bottom:20px; margin-top: 20px">
<thead>
    <tr>
        <th class="title_tab2">MOTIVO DE LA SOLICITUD</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="data_tbl3" style="width:100%;">
        <?= $request->motive; ?></td>
    </tr>
</tbody>
</table>
    
    <page_footer>
    <table class="tab2">
        <tbody>
            <tr>
                <td style="width:27%;" class="field_tbl">FIRMA SOLICITANTE</td>
                <td style="width:5%;" class="no-border-bottom no-border-top "></td>
                <td style="width:36%;" class="field_tbl">AUTORIZACION JEFE DIRECTO </td>
                <td style="width:5%;" class="no-border-bottom no-border-top "></td>
                <td style="width:27%;" class="field_tbl">AUTORIZACION ADMINISTRATIVA</td>
            </tr>
            <tr>
                <td style="width:27%; height:100px;" class="data_tbl "></td>
                <td style="width:5%; height:100px;" class="data_tbl no-border-bottom no-border-top "></td>
                <td style="width:36%; height:100px;" class="data_tbl "> </td>
                <td style="width:5%; height:100px;" class="data_tbl no-border-bottom no-border-top "></td>
                <td style="width:27%; height:100px;" class="data_tbl "></td>
            </tr>
        </tbody>
    </table>
    </page_footer>
</page>