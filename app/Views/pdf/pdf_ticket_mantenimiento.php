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

    /* tabla para datos */
    .tab2 {
        min-width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        margin-left: none;
        margin-right: none;
        margin-bottom: 20px;
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

    .salto_tab2 {
        background: #C9C9C9;
        height: 2px;
        padding: none;
        margin: none;
    }

    .field-tbl {
        font-weight: bold;
        text-align: center;
        font-size: 12px;
        background: #eee;
        /* padding: top der abj izq; */
        padding: 5px 10px 5px 10px;

    }

    .field-tbl-lg {
        font-weight: bold;
        text-align: center;
        font-size: 12px;
        background: #eee;
        /* padding: top der abj izq; */
        padding: 5px 10px 5px 10px;
        height: auto;
        /* permite que la celda crezca en altura */
        word-wrap: break-word;
        /* permite que el texto se ajuste sin romper la tabla */
        white-space: normal;
    }


    .data-tbl {
        /* font-weight: bold; */
        text-align: center;
        font-size: 11px;
        padding-top: 3px;
        padding-bottom: 3px;
    }

    .data-tbl-lg {
        overflow: hidden;
        justify-content: flex-start;
        align-content: flex-start;
        text-align: start;
        font-size: 25px;
        white-space: nowrap;
        min-height: 300px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding-bottom: 10px;
    }
</style>
<?php
$prioridad = [1 => 'BAJA', 2 => 'MEDIA', 3 => 'ALTA'];
$rubro = [1 => 'TRANSPORTE', 2 => 'GASOLINA', 3 => 'EXTRAS'];
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
                <span>Hora de creación: <label for="" style="margin-left:10px"><?= date('H:i ', strtotime($request->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>

    <div class="title">
        <h3>ORDEN DE TRABAJO PARA MANTENIMIENTO</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%">
                    <span>Solicitante:</span>
                    <?= mb_convert_case($request->name_user, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;">
                    <span>No. de Orden: </span>
                    <?= $request->id_order; ?>
                </td>
            </tr>
            <tr>
                <td style="width:70%">
                    <span>Departament:</span>
                    <?= mb_convert_case($request->name_depto, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;">
                    <span>Número de Nomina:</span>
                    <?= mb_convert_case($request->payroll_number, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span>Estatus:</span>
                    CERRADO
                </td>
            </tr>
        </tbody>
    </table>

    <table class="tab2">
        <thead>
            <tr>
                <th colspan="4" class="title_tab2">DATOS DEL TICKET</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="field-tbl">CLAVE DE MAQUINA / EQUIPO</td>
                <td colspan="3" class="data-tbl"><?= strtoupper($request->equipo); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="field-tbl">DESCRIPCION DE TRABAJO</td>
            </tr>
            <tr>
                <td colspan="4" class="data-tbl-lg">
                    <p style="padding: 0 10px 0 10px;"><?= mb_strtolower($request->description, "UTF-8"); ?> </p>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="salto_tab2"></td>
            </tr>
            <tr>
                <td class="field-tbl">TIPO DE MANTENIMIENTO</td>
                <td class="field-tbl">PRIORIDAD</td>
                <td class="field-tbl">CÓDIGO DE FALLA</td>
                <td class="field-tbl">CÓDIGO DE CAUSA</td>
            </tr>
            <tr>
                <td class="data-tbl"> <?= strtoupper($request->Actividad_Actividad); ?> </td>
                <td class="data-tbl"> <?= $prioridad[$request->id_priority]; ?> </td>
                <td class="data-tbl"> <?= strtoupper($request->name_fail); ?> </td>
                <td class="data-tbl"> <?= strtoupper($request->cause_code); ?> </td>
            </tr>
            <tr>
                <td colspan="4" class="salto_tab2"></td>
            </tr>
            <tr>
                <td colspan="2" class="field-tbl">AUTORIZADO POR</td>
                <td colspan="2" class="field-tbl">CON FECHA</td>
            </tr>
            <tr>
                <td colspan="2" class="data-tbl"> <?= mb_convert_case($request->manager_authorize, MB_CASE_TITLE, "UTF-8"); ?> </td>
                <td colspan="2" class="data-tbl"> <?= date("d/m/Y H:i", strtotime($request->authotize_at)); ?> </td>
            </tr>
            <tr>
                <td colspan="4" class="salto_tab2"></td>
            </tr>
            <tr>
                <td colspan="2" class="field-tbl">TÉCNICO ASIGNADO</td>
                <td colspan="2" class="field-tbl">PUESTO</td>
            </tr>
            <tr>
                <td colspan="2" class="data-tbl"> <?= mb_convert_case($request->tecnico, MB_CASE_TITLE, "UTF-8"); ?> </td>
                <td colspan="2" class="data-tbl"> <?= strtoupper($request->jop_tecnico) ?? ''; ?> </td>
            </tr>
            <tr>
                <td colspan="2" class="field-tbl">FECHA Y HORA DE INICIO</td>
                <td colspan="2" class="field-tbl">FECHA Y HORA DE TERMINO</td>
            </tr>
            <tr>
                <td colspan="2" class="data-tbl"> <?= date("d/m/Y H:i", strtotime($request->star)); ?> </td>
                <td colspan="2" class="data-tbl"> <?= date("d/m/Y H:i", strtotime($request->end)); ?> </td>
            </tr>
            <tr>
                <td colspan="4" class="field-tbl">TRABAJOS REALIZADOS</td>
            </tr>
            <tr>
                <td colspan="4" class="data-tbl-lg">
                    <p style="padding: 0 10px 0 10px;"><?= mb_strtolower($request->work_done, "UTF-8"); ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="salto_tab2"></td>
            </tr>
            <tr>
                <td colspan="2" class="field-tbl">ACEPTADO POR</td>
                <td colspan="2" class="field-tbl">CON FECHA</td>
            </tr>
            <tr>
                <td colspan="2" class="data-tbl"> <?= mb_convert_case($request->manager_aceept, MB_CASE_TITLE, "UTF-8"); ?> </td>
                <td colspan="2" class="data-tbl"> <?= date("d/m/Y H:i", strtotime($request->accept_at)); ?> </td>
            </tr>
        </tbody>
    </table>

    <?php if (!empty($items) && !is_null($items)) { ?>
        <table class="tab2">
            <thead>
                <tr>
                    <th colspan="5" class="title_tab2">COMPRA DE REFACCIONES Y/O PARTES</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($items as $key) {
                    if ($i > 1) { ?>
                        <tr>
                            <td colspan="5" class="salto_tab2"></td>
                        </tr>                        
                    <?php } ?>
                    <tr>
                        <td style="padding: none; margin:none;background:#D3D3D3;" rowspan="3" class="data-tbl"></td>
                        <!-- <td style="padding: none; margin:none;background:#D3D3D3;" rowspan="5" class="data-tbl"></td> -->
                        <td class="field-tbl" style="overflow-wrap: break-word;">CÓDIGO DE PIEZA</td>
                        <td class="data-tbl"><?= mb_strtolower($key->code_spare_part, "UTF-8"); ?></td>
                        <td class="field-tbl">NÚMERO DE ORDEN</td>
                        <td class="data-tbl"><?= $key->num_order; ?></td>
                        
                    </tr>
                    <tr>
                        <td class="field-tbl">COMPRADOR</td>
                        <td class="data-tbl"> <?= mb_convert_case($key->assigned_buyer_name, MB_CASE_TITLE, "UTF-8"); ?> </td>
                        <td class="field-tbl">FECHA ESTIMADA</td>
                        <td class="data-tbl"> <?= date("d/m/Y", strtotime($key->estimated_delivery_date)); ?> </td>
                    </tr>
                    <tr>
                        <td class="field-tbl">FECHA SOLICITUD</td>
                        <td class="data-tbl"> <?= date("d/m/Y H:i", strtotime($key->date_star)); ?> </td>
                        <td class="field-tbl">FECHA RECEPCIÓN</td>
                        <td class="data-tbl"> <?= date("d/m/Y H:i", strtotime($key->date_end)); ?> </td>
                    </tr>
                    <!-- <tr>
                        <td class="field-tbl">CANTIDAD</td>
                        <td class="data-tbl"> <?= /* intdiv($key->date_star) */ 1; ?> </td>
                        <td class="field-tbl">COSTO UNITARIO</td>
                        <td class="data-tbl">$ <?= /* number_format($key->amount, 2, '.', ','); */ '10' ?> </td>
                    </tr>
                    <tr>
                        <td style="font-size: 5px !important; border-right:none;" class="field-tbl" colspan="2"></td>
                        <td class="field-tbl">MONTO</td>
                        <td class="data-tbl">$ <?= /* number_format($key->amount, 2, '.', ','); */ '10' ?> </td>
                    </tr> -->
                    <?php $i++; ?>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <page_footer>
    </page_footer>
</page>