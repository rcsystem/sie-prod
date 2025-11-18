<style>

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
        width: 150px;
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

    .tab1, .tab2, .tab3 {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tab1 td, .tab2 td, .tab3 td {
        padding: 10px;
        font-size: 12px;
        border: 1px solid #ccc;
    }

    .tab1 span, .tab2 span {
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



<?php
$titulo = [
    1 => $request->tipo_permiso,
    2 => $request->tipo_permiso,
    3 => 'A CUENTA DE VACACIONES',
    4 => 'MOTIVO: ' .  $request->tipo_permiso,
    5 => $request->tipo_permiso,
    8 => $request->tipo_permiso,
];

?>

<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!-- <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span style="margin-left:400px">Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->fecha_creacion)); ?></label></span><br>
                <!-- <span>Hora de creación: <label for=""style="margin-left:40px"><?= date('H:i', strtotime($request->fecha_creacion)); ?></label></span> -->
            </div>
        </div>
    </page_header>
    <page_footer>
    </page_footer>
    <div class="title">
        <?php if ($request->id_tipo_permiso != null) { ?>
            <H3>PERMISO <?= $titulo[$request->id_tipo_permiso]; ?> </H3>
        <?php } else { ?>
            <h3>AUTORIZACIÓN DE INASISTENCIA, ENTRADAS O SALIDAS</h3>
        <?php } ?>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Solicitante:
                    </span><?= mb_convert_case($request->nombre_solicitante, MB_CASE_TITLE, "UTF-8") ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Folio:
                    </span><?= mb_convert_case($request->id_es, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Departamento: </span><?= ucwords(strtolower($request->departamento)); ?></td>
                <td style="width:30%;text-align:right;"><span>Goce de Sueldo: </span><?= ucwords(strtolower($request->goce_sueldo)); ?></td>
            </tr>
            <?php if ($request->turno_permiso != null) { ?>
                <tr>
                    <td style="width:70%;"><span>Turno:
                        </span><?= mb_convert_case($request->turno_permiso, MB_CASE_TITLE, "UTF-8"); ?></td>
                        <td style="width:30%; text-align:right;"><span>Número de Nómina:
                        </span><?= mb_convert_case($request->num_nomina, MB_CASE_TITLE, "UTF-8"); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td style="width:70%;"><span>Estatus:
                    </span><?= mb_convert_case($request->estatus, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td></td>
            </tr>
      

        </tbody>
    </table>


    <table class="tab2" style="margin-bottom:25px;">
        <tbody>
            <tr>
                <td class="title-tab1" style="text-align: center;width: 40%;">Se autoriza la SALIDA a las:</td>
                <td class="" style="text-align: center;width: 20%;"><?= ($request->hora_salida != "00:00:00") ? date('H:i a', strtotime($request->hora_salida)) . "." : "---"; ?></td>
                <td class="title_dia">del día: </td>
                <td class="" style="text-align: center;"><?= ($request->fecha_salida != "0000-00-00") ? date("d-m-Y", strtotime($request->fecha_salida)) : "---"; ?></td>
            </tr>
            <tr>
                <td class="title-tab1"  style="text-align: center;width: 40%;">Se autoriza la ENTRADA a las:</td>
                <td class="" style="text-align: center;"><?= ($request->hora_entrada != "00:00:00") ? date('H:i a', strtotime($request->hora_entrada)) . "." : "---"; ?></td>
                <td class="title_dia">del día: </td>
                <td class="" style="text-align: center;width: 20%;"><?= ($request->fecha_entrada != "0000-00-00") ? date("d-m-Y", strtotime($request->fecha_entrada)) : "---"; ?></td>
            </tr>
            <tr>
                <td class="title-tab1"   style="text-align: center;width: 40%;">Se autoriza INASISTENCIA del:</td>
                <td class="" style="text-align: center;"><?= ($request->inasistencia_del != "0000-00-00") ? date("d-m-Y", strtotime($request->inasistencia_del)) : "---"; ?></td>
                <td class="title_dia">al día: </td>
                <td class="" style="text-align: center;"><?= ($request->inasistencia_al != "0000-00-00") ?  date("d-m-Y", strtotime($request->inasistencia_al)) : "---"; ?></td>
            </tr>
            <?php if ($request->estatus != 'Pendiente') { ?>
                <tr>
                    <td class="title-tab1" style="text-align: center;"><?= strtoupper($request->estatus); ?> por:</td>
                    <td colspan="3" style="text-align: center;"><?= $request->authoriza ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <table class="tab3" style="width:100%;margin-bottom:40px;">
        <thead>
            <tr style="width:100%;">
                <th colspan="" class="title-tab-header">OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:100%;text-align:left;word-wrap: break-word;font-size: 14px;">
                    <p style="margin-left:12px; margin-right:12px;"><?= ($request->observaciones != "") ? $request->observaciones : " "; ?></p><br>
                </td>
            </tr>
        </tbody>
    </table>


    <?php if ($request->id_pago_tiempo) { ?>
        <!-- <table class="tab2" style="margin-bottom:25px;"> -->
        <table class="tab3" style="width:max-content;margin-bottom:40px;">
            <thead>
                <tr>
                    <th colspan="6" class="title-tab-header">PAGOS DE TIEMPO</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0;
                foreach ($paytime as $key) { ?>
                    <?php if ($i > 0) { ?>
                        <tr>
                            <td colspan="6" class="hop-tbl"></td>
                        </tr>
                    <?php } ?>
                    <?php $i++; ?>
                    <tr>
                        <td class="celd-tab title-tab" style="width: 10% !important;">Turno:</td>
                        <td class="celd-tab" style="width:25%"><?= $key->name_turn ?></td>
                        <td class="celd-tab title-tab" style="width: 15% !important;">Fecha:</td>
                        <td class="celd-tab" style="width:15%"><?= $key->day_to_pay ?></td>
                        <td class="celd-tab title-tab" style="width: 10% !important;">Tiempo:</td>
                        <td class="celd-tab" style="width:25%"><?= $key->tiempo ?></td>
                    </tr>
                    <tr>
                        <td class="celd-tab title-tab" style="width: auto !important;">Tipo:</td>
                        <td class="celd-tab" colspan="2"><?= strtoupper($key->type_pay) ?></td>
                        <td colspan="2" class="celd-tab title-tab" style="width: auto !important;">Estado</td>
                        <td class="celd-tab"><?= $key->estado ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else if ($request->pago_deuda == 2 && $request->id_pago_tiempo == 0) { ?>
        <table class="tab3" style="width:max-content;margin-bottom:40px;">
            <thead>
                <tr>
                    <th colspan="" class="title-tab-header">PAGOS DE TIEMPO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:left;word-wrap: break-word;font-size: 14px;">
                        <p style="margin-left:12px; margin-right:12px;">Se tiene Deuda de Pago de tiempo y no se ha generado pago de tiempo.</p><br>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php } ?>
</page>