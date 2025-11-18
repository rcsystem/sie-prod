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

    .tab2 {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
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

    .border_off {
        border: none;
    }

    .border_right {
        border-top: none;
        border-bottom: none;
        border-left: none;
    }

    .title_tab {
        width: 10%;
        background: rgb(52, 58, 64);
        color: white;
        font-weight: bold;
        font-size: 12px;
        padding: 10px;
        text-align: center;
        border: none;
    }

    .title_dia {
        width: 12%;
        background: rgb(52, 58, 64);
        color: white;
        font-weight: bold;
        font-size: 12px;
        padding: 10px;
        text-align: right;
        border: none;
        border-left: 0.5px solid #BDBDBD;
        border-right: 0.5px solid #BDBDBD;
        border-bottom: 0.5px solid #BDBDBD;
        border-top: 0.5px solid #BDBDBD;
        padding: 5px 4px 5px 3px;
    }

    .tab2 tbody tr td {
        border-left: 0.5px solid #BDBDBD;
        border-right: 0.5px solid #BDBDBD;
        border-bottom: 0.5px solid #BDBDBD;
        border-top: 0.5px solid #BDBDBD;
        padding: 5px 4px 5px 3px;
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
        border: 0.5px solid #BDBDBD;
    }

    .descripcion {
        width: 75%;
        background-color: transparent;
        padding: 8px 4px 8px 5px;
        border: 1px solid #BDBDBD;
        word-wrap: break-word;
    }

    .tab3 {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .title_tab2 {
        width: 58%;
        background: rgb(117, 117, 117);
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: center;
        border: none;
    }

    .tab3 tbody tr td {
        width: 100%;
        height: 30px;
        border: none;
        border-left: 1px solid #BDBDBD;
        border-right: 1px solid #BDBDBD;
        border-bottom: 1px solid #BDBDBD;
        border-top: 1px solid #BDBDBD;
        padding: 5px 4px 5px 3px;
    }

    .title-tab-header {
        width: 55%;
        background: rgb(52, 58, 64);
        color: white;
        font-weight: bold;
        font-size: 14px;
        padding: 6px;
        text-align: center;
        border: none;
    }
</style>


<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!-- <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->created_at)); ?></label></span><br>
                <span>Hora de creación: <label for="" style="margin-left:40px"><?= date('H:i ', strtotime($request->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>

    <div class="title">
        <h3>ENTREGA DE EQUIPO DE PROTECCION PERSONAL</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Solicitante:
                    </span><?= mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8") ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Número de Nómina:</span>
                    <?= mb_convert_case($request->payroll_number, MB_CASE_TITLE, "UTF-8"); ?>
                </td>

            </tr>

            <tr>
                <td style="width:70%;"><span>Departamento: </span><?= ucwords(strtolower($request->departament)); ?></td>
                <?php  if ($request->payrollnumber_image != "") { ?>
                    <td style="text-align:right;"><img src="<?= $request->payrollnumber_image ?>" width="80" height="15" alt=""></td>
               <?php } ?>
                

            </tr>

            <tr>
                <td style="width:70%"><span>Estatus:
                    </span><?php
                            $estatus =  $request->request_status;
                            switch ($estatus) {
                                case 1:
                                    $status = '<span style="color:#000000;">Pendiente</span>';
                                    break;
                                case 2:
                                    $status = '<span style="color:#009f00;">Entregado</span>';
                                    break;
                                case 3:
                                    $status = '<span style="color:#b30000;">Cancelado</span>';
                                    break;

                                default:
                                    $status = '<span style="color:#000000;">Error</span>';
                                    break;
                            }

                            echo  mb_convert_case($status, MB_CASE_TITLE, "UTF-8");

                            ?>
                </td>

                <td style="width:30%; text-align:right;"><span>Centro de Costo: </span><?= ucwords(strtolower($request->cost_center)); ?> </td>
            </tr>
            <tr>
                <td style="width:70%">
                    <span>Número de Vale:
                    </span><span style="color:#b30000;"><?= mb_convert_case($request->id_request, MB_CASE_TITLE, "UTF-8"); ?></span>
                </td>
                <?php  if ($request->costcenter_image != "") { ?>
                    <td style="text-align:right;"><img src="<?= $request->costcenter_image ?>" width="80" height="15" alt=""></td>
               <?php } ?>
                

            </tr>
        </tbody>
    </table>


    <table class="tab2" style="margin-bottom:5px;">
        <tbody>
            <?php foreach ($articles as $key => $item) {

                if ($item->barcode_image != "") { ?>

                    <tr>
                        <td class="title_tab">CODIGO</td>
                        <td class="" style="text-align: left;width:40%;font-size: 11px;">
                            <img src="<?= $item->barcode_image ?>" alt="" width="100" height="15">
                        </td>
                    </tr>
                <?php } ?>

                <tr>
                    <td class="title_tab">EQUIPO:</td>
                    <td class="" style="text-align: left;width:40%;font-size: 11px;"><?= mb_convert_case($item->code_store, MB_CASE_TITLE, "UTF-8") ?> - <?= mb_convert_case($item->product, MB_CASE_TITLE, "UTF-8"); ?></td>
                    <td class="title_dia" style="">SOLICITADO: </td>
                    <td class="" style="text-align: center;width: 10%;font-size: 12px;"><?= mb_convert_case($item->quantity, MB_CASE_TITLE, "UTF-8"); ?></td>
                    <td class="title_dia" style="">ENTREGADO: </td>
                    <td class="" style="text-align: center;width: 10%;font-size: 12px;">
                        <?php
                        $cantidad = ($item->cant_confirm != "") ? $item->cant_confirm : "0";
                        echo mb_convert_case($cantidad, MB_CASE_TITLE, "UTF-8");
                        ?></td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

    <table class="tab3" style="width:max-content;margin-bottom:40px;">
        <thead>
            <tr>
                <th colspan="" class="title-tab-header">ESPECIFICACIONES</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:left;word-wrap: break-word;font-size: 14px;">
                    <p style="margin-left:12px; margin-right:12px;"><?= ($request->id_user_deliver != 0) ? $request->specify : $request->obs_request; ?></p><br>
                </td>
            </tr>
        </tbody>
    </table>


    <page_footer>
    </page_footer>

</page>