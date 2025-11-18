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
                <span>Hora de creación: <label for="" style="margin-left:10px"><?= date('H:i A', strtotime($request->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>

    <div class="title">
        <h3>SOLICITUD DE GASTOS</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%">
                    <span>Solicitante:</span>
                    <?= mb_convert_case($request->user, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;">
                    <span>Folio: </span>
                    <?= $request->id_expenses; ?>
                </td>
            </tr>
            <tr>
                <td style="width:70%">
                    <span>Departament:</span>
                    <?= mb_convert_case($request->departament, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;">
                    <span>Centro de Costos:</span>
                    <?= mb_convert_case($request->cost_center, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span>Estatus:</span>
                    <?= $estado[$request->expenses_status]; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="tab2">
        <thead>
            <tr>
                <th colspan="4" class="title_tab2">DATOS GENERALES DE GASTOS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:26%;" class="field-tbl">CANTIDAD SOLICITADA: </td>
                <td style="width:24%;" class="data-tbl">$<?= number_format($request->total_amount, 2, '.', ',') ; ?></td>
                <td style="width:26%;" class="field-tbl">DIFERENCIA: </td>
                <td style="width:24%;" class="data-tbl"><?= $diff = ($request->difference != null) ? '$' . $request->difference : '---'; ?></td>
            </tr>
            <tr>
                <td class="field-tbl">FECHA INICIO: </td>
                <td class="data-tbl"><?= date("d/m/Y", strtotime($request->start_date)); ?></td>
                <td class="field-tbl">FECHA FIN: </td>
                <td class="data-tbl"><?= date("d/m/Y", strtotime($request->end_date)); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="subtitle_tab2">MOTIVO DEL VIAJE:</td>
            </tr>
            <tr>
                <td colspan="4" class="data-tbl-lg"><?= $request->reasons; ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab2">
        <thead>
            <tr>
                <th colspan="4" class="title_tab2">DATOS ESPECÍFICOS DE GASTOS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:10%;" class="field-tbl">No.</td>
                <td style="width:20%;" class="field-tbl">RUBRO</td>
                <td style="width:40%;" class="field-tbl">ESPECIFICACION</td>
                <td style="width:20%;" class="field-tbl">CANTIDAD</td>
            </tr>
            <?php $i = 1; ?>
            <?php foreach ($item as $key) { ?>
                <tr>
                    <td class="data-tbl">#<?= $i; ?></td>
                    <td class="data-tbl"><?= $rubro[$key->id_category]; ?></td>
                    <td class="data-tbl"><?= $key->definition; ?></td>
                    <td class="data-tbl">$<?= number_format($key->amount, 2, '.', ','); ?></td>
                </tr>
                <?php $i++; ?>
            <?php } ?>
        </tbody>
    </table>
    <page_footer>
    </page_footer>
</page>