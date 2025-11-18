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

    .border_none {
        border-left: none;
        border-right: none;
        border-bottom: none;
        border-top: none;
    }

    .border_right {
        border-left: none;
        border-bottom: none;
        border-top: none;
    }

    .border_left {
        border-right: none;
        border-bottom: none;
        border-top: none;
    }

    .border_left_right {
        border-bottom: none;
        border-top: none;
    }

    .border_bottom_right {
        border-top: none;
        border-left: none;
    }

    .border_bottom_left {
        border-top: none;
        border-right: none;
    }

    .border_left_bottom_right {
        border-top: none;
    }

    .border_top_bottom {
        border-left: none;
        border-right: none;
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
        font-weight: bold;
        text-align: center;
        font-size: 15px;
        background: #eee;
        /* padding: top der abj izq; */
        padding: 5px 10px 5px 10px;

    }

    .data_tbl {
        font-weight: bold;
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
if ($request->sure == 1) {
    $seguro = "SI";
    $costo = "$" . $request->cost;
} else {
    $seguro = "NO";
}
if ($request->shipping_type == 1) {
    $tipo = "Dia Siguiente";
}
if ($request->shipping_type == 2) {
    $tipo = "Terrestre";
}
if ($request->gather == 1) {
    $recoleccion = "Se Requiere Recoleccion";
}
if ($request->gather == 2) {
    $recoleccion = "No Necesaria";
}
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
    <page_footer>
    </page_footer>

    <div class="title">
        <h3>SOLICITUDES DE PAQUETERIA </h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Solicitante:
                    </span><?= mb_convert_case($request->sender_name, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span style="font-weight:bold;">Folio:
                    </span><?= mb_convert_case($request->id_request, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Telefono:
                    </span><?= ucwords(strtolower($request->sender_phone)); ?></td>
                <td style="width:30%;text-align:right;"><span style="font-weight:bold;">Area Operativa:
                    </span><?= ucwords(strtolower($request->area_operative)); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab3" style="width:max-content;margin-bottom:20px;">
        <thead>
            <tr>
                <th colspan="3" class="title_tab2">DATOS DE REMITENTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="data_tbl3 border_left_right" style="width:100%;" colspan="3"><span style="font-weight:bold;">Empresa:
                    </span><?= mb_strtoupper($request->sending_company, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td class="data_tbl3 border_left" style="width:40%;"><span style="font-weight:bold;">País:
                    </span><?= mb_convert_case($request->sender_country, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="data_tbl3 border_none" style="width:40%; "><span style="font-weight:bold;">Estado:
                    </span><?= mb_convert_case($request->sender_state, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="data_tbl3 border_right" style="width:20%;"><span style="font-weight:bold;">C.P.:
                    </span><?= mb_convert_case($request->sender_cp, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td class="data_tbl3 border_left" style="width:40%;"><span style="font-weight:bold;">Localidad:
                    </span><?= mb_convert_case($request->sender_locality, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="data_tbl3 border_none" style="width:40%;"><span style="font-weight:bold;">Colonia:
                    </span><?= mb_convert_case($request->sender_col, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="data_tbl3 border_right" style="width:20%;"><span style="font-weight:bold;">No.:
                    </span><?= mb_strtoupper($request->sender_num, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td class="data_tbl3 border_left_bottom_right" style="width:100%;" colspan="3"><span style="font-weight:bold;">Calle:
                    </span><?= mb_convert_case($request->sender_street, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab3" style="width:max-content;margin-bottom:20px;">
        <thead>
            <tr>
                <th colspan="3" class="title_tab2">DATOS DE DESTINATARIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="data_tbl3 border_left_right" style="width:100%;" colspan="3"><span style="font-weight:bold;">Empresa:
                    </span><?= mb_strtoupper($request->recipient_company, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td class="data_tbl3 border_left" style="width:40%;"><span style="font-weight:bold;">Telefono:
                    </span><?= mb_convert_case($request->recipient_phone, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="data_tbl3 border_right" style="width:60%;" colspan="2"><span style="font-weight:bold;">Nombre:
                    </span><?= mb_convert_case($request->recipient_name, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td class="data_tbl3 border_left" style="width:40%;"><span style="font-weight:bold;">País:
                    </span><?= mb_convert_case($request->recipient_country, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="data_tbl3 border_none" style="width:40%;"><span style="font-weight:bold;">Estado:
                    </span><?= mb_convert_case($request->recipient_state, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="data_tbl3 border_right" style="width:20%;"><span style="font-weight:bold;">No.:
                    </span><?= mb_strtoupper($request->recipient_num, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td class="data_tbl3 border_left" style="width:40%;"><span style="font-weight:bold;">Colonia:
                    </span><?= mb_convert_case($request->recipient_col, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="data_tbl3 border_right" style="width:60%;" colspan="2"><span style="font-weight:bold;">Localidad:
                    </span><?= mb_convert_case($request->recipient_locality, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td class="data_tbl3 border_left_bottom_right" style="width:100%;" colspan="3"><span style="font-weight:bold;">Calle:
                    </span><?= mb_convert_case($request->recipient_street, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab3" style="width:max-content;margin-bottom:20px;">
        <thead>
            <tr>
                <th colspan="3" class="title_tab2">CARACTERISTICAS DE ENVIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="data_tbl_carac border_left" style="width:30%; text-align:left;"><span style="font-weight:bold;">Seguro:
                    </span><?= mb_convert_case($seguro, MB_CASE_TITLE, "UTF-8"); ?></td>
                <?php if ($request->sure == 1) { ?>
                    <td class="data_tbl_carac border_none" style="width:30%; text-align:left;"><span style="font-weight:bold;">Monto:
                        </span><?= mb_convert_case($costo, MB_CASE_TITLE, "UTF-8"); ?></td>
                    <td class="data_tbl_carac border_right" style="width:40%; text-align:left;"><span style="font-weight:bold;">Tipo de Envio:
                        </span><?= mb_convert_case($tipo, MB_CASE_TITLE, "UTF-8"); ?></td>
                <?php } else { ?>
                    <td class="data_tbl_carac border_right" style="width:70%; text-align:left;" colspan="2"><span style="font-weight:bold;">Tipo de Envio:
                        </span><?= mb_convert_case($tipo, MB_CASE_TITLE, "UTF-8"); ?></td>
                <?php } ?>

            </tr>
            <tr>
                <td class="data_tbl_carac border_left_right" colspan="3" style="width:80%; text-align:left"><span style="font-weight:bold;">Recoleccion: </span><?= $recoleccion; ?></td>
            </tr>
            <tr>
                <td class="data_tbl_carac border_left_bottom_right" colspan="3" style="width:80%; text-align:left"><span style="font-weight:bold;">Observaciones:
                    </span><br><br><?= $request->observation; ?><br><br></td>
            </tr>
        </tbody>
    </table>

    <table class="tab2">
        <thead>
            <tr>
                <th colspan="6" class="title_tab2">DATOS DE ENVIO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:15%;" class="field_tbl"></td>
                <td style="width:17%;" class="field_tbl">CANTIDAD</td>
                <td style="width:17%;" class="field_tbl">PESO</td>
                <td style="width:17%;" class="field_tbl">BASE</td>
                <td style="width:17%;" class="field_tbl">ALTURA</td>
                <td style="width:17%;" class="field_tbl">PROFUNDIDAD</td>
            </tr>
            <?php foreach ($item as $key => $value) { ?>
                <tr>
                    <?php if ($value->weight > 0 ) { ?>
                        <td style="width:15%;" class="field_tbl">Paquete</td>
                        <td style="width:17%;" class="data_tbl"><?= ucwords(strtolower($value->amount)); ?></td>
                        <td style="width:17%;" class="data_tbl"><?= floatval($value->weight) . " Kg"; ?></td>
                        <td style="width:17%;" class="data_tbl"><?= floatval($value->base) . " cm"; ?></td>
                        <td style="width:17%;" class="data_tbl"><?= floatval($value->height) . " cm"; ?></td>
                        <td style="width:17%;" class="data_tbl"><?= floatval($value->depth) . " cm"; ?></td>
                        <?php } else { ?>
                        <td style="width:15%;" class="field_tbl">Documentos</td>
                        <td style="width:17%;" class="data_tbl"><?= ucwords(strtolower($value->amount)); ?></td>
                        <td style="width:68%;" class="data_tbl" colspan="4" ></td>
                    <?php } ?>
                </tr>
            <?php } ?>

        </tbody>
    </table>




</page>