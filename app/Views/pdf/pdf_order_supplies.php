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
    .title_tab3 {
        width: 100%;
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
if ($solicitud->orden_status == 1) {
    $estado = "ABIERTA";
} else if ($solicitud->orden_status == 2) {
    $estado = "CERRADA";
} else {
    $estado = "ERROR";
}
$cont = 0;
$saltos = count($valvulas);
?>
<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!--  <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($solicitud->created_at)); ?></label></span><br>
                <span>Hora de creación: <label for="" style="margin-left:10px"><?= date('H:i', strtotime($solicitud->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>

    <div class="title">
        <h3>ORDEN DE SUMINISTROS</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Generado por:
                    </span><?= mb_convert_case($solicitud->usuario, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span style="font-weight:bold;">Folio:
                    </span><?= mb_convert_case($solicitud->id_request, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span style="font-weight:bold;">Núrmero de Orden:
                    </span><?= ucwords(strtolower($solicitud->orden_compra)); ?></td>
                <td style="width:30%;text-align:right;"><span style="font-weight:bold;">Estado de Orden:
                    </span><?= ucwords(strtolower($estado)); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab2">
        <thead>
            <tr>
                <th colspan="4" class="title_tab3">DATOS DE ORDEN</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($valvulas as $key => $value) { 
                $fecha_real = ($value->fecha_real_entrega != null) ? date("d/m/Y", strtotime($value->fecha_real_entrega)) : "NO DEFINIDA";
                $cont++;?>
                <tr>
                    <td class="field_tbl">CODIGO</td>
                    <td colspan="2" class="field_tbl">DESCRIPCION</td>
                    <td class="field_tbl">PIEZAS</td>
                </tr>
                <tr>
                    <td rowspan="5" class="data_tbl"><?= $value->codigo; ?></td>
                    <td colspan="2" class="data_tbl"><?= $value->desc_breve; ?></td>
                    <td class="data_tbl"><?= $value->num_piezas." PZ"; ?></td>
                </tr>
                <tr>
                    <td class="field_tbl">TIPO</td>
                    <td class="field_tbl">DIAMETRO</td>
                    <td class="field_tbl">CLASE</td>
                </tr>
                <tr>
                    <td class="data_tbl"><?= $value->tipo; ?></td>
                    <td class="data_tbl"><?= $value->diametro; ?></td>
                    <td class="data_tbl"><?= $value->clase; ?></td>
                </tr>
                <tr>
                    <td class="field_tbl">TIEMPO</td>
                    <td class="field_tbl">ENTREGA</td>
                    <td class="field_tbl">FECHA REAL</td>
                </tr>
                <tr>
                    <td class="data_tbl"><?= $value->tiempo; ?></td>
                    <td class="data_tbl"><?= date("d/m/Y", strtotime($value->fecha_entrega)); ?></td>
                    <td class="data_tbl"><?= $fecha_real; ?></td>
                </tr>
                <?php if ($cont == 4) { ?>
                    <tr><td colspan="4" style="border-left: none;border-right: none;border-bottom: none;color:#FFFFFF;">-</td></tr>
                    <tr><td colspan="4" style="border: none;color:#FFFFFF;">-</td></tr>
                    <tr><td colspan="4" style="border: none;color:#FFFFFF;">-</td></tr>
                    <tr><td colspan="4" style="border: none;color:#FFFFFF;">-</td></tr>
                <?php } else if ($cont == 9 || $cont == 14 || $cont == 19 || $cont == 24) { ?>
                    <tr><td colspan="4" style="border-left: none;border-right: none;border-bottom: none;color:#FFFFFF;">-</td></tr>
                    <tr><td colspan="4" style="border: none;color:#FFFFFF;">-</td></tr>
                    <tr><td colspan="4" style="border: none;color:#FFFFFF;">-</td></tr>
                <?php } else if ($cont < $saltos) { ?>
                    <tr><td colspan="4" class="border_top_bottom" style="color:#FFFFFF;">-</td></tr>
            <?php } } ?>
        </tbody>
    </table>

    <page_footer>
    </page_footer>
</page>