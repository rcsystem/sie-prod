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
        max-width: max-content;
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

    .title_dia {
        width: 20%;
        background: rgb(145, 150, 154);
        color: rgb(246, 246, 246);
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: center;
        border: none;
        border-left: 1px solid #BDBDBD;
        border-right: 1px solid #BDBDBD;
        border-bottom: 1px solid #BDBDBD;
        border-top: 1px solid #BDBDBD;
        padding: 5px 4px 5px 3px;
    }

    .tab2 tbody tr td {
        border-left: 1px solid #BDBDBD;
        border-right: 1px solid #BDBDBD;
        border-bottom: 1px solid #BDBDBD;
        border-top: 1px solid #BDBDBD;
        padding: 5px 4px 5px 3px;
    }

    .tab3 {
        width: 100%;
        font-size: 14px;
        border-collapse: collapse;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .tab3 .title-tab3 {
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

    .tab3 {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .title-tab-header {
        width: 58%;
        background: rgb(92, 99, 106);
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

    .hop-tbl {
        /* background: rgb(205, 205, 205); */
        background: rgb(145, 150, 154);
        width: auto !important;
        border-bottom: none;
        border-top: none;
        padding: none;
        margin: none;
        height: 3px;
    }

    .title-tab {
        width: 39%;
        background: rgb(145, 150, 154);
        color: rgb(246, 246, 246);
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: right;
        border: none;
    }

    .celd-tab {
        font-size: 12px !important;
        padding: 2px 5px 2px 5px !important;
        /* border: none ; */
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
                <!-- <span>Hora de creación: <label for=""style="margin-left:40px"><?= date('H:i', strtotime($request->created_at)); ?></label></span> -->
            </div>
        </div>
    </page_header>
    <page_footer>
    </page_footer>
    <div class="title">
       
            <h3>SOLICITUD POR EVENTO</h3>
        
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Solicitante:
                    </span><?= mb_convert_case($request->user_name, MB_CASE_TITLE, "UTF-8") ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Folio:
                    </span><?= mb_convert_case($request->id_volunteering, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Departamento: </span><?= ucwords(strtolower($request->departament)); ?></td>
                <td style="width:30%;text-align:right;"><span>Goce de Sueldo: </span><?= ucwords(strtolower($request->goce_sueldo)); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Estatus:
                    </span><?= mb_convert_case($request->estatus, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td style="width:30%; text-align:right;"><span>Número de Nómina:
                    </span><?= mb_convert_case($request->num_nomina, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <?php if ($request->turno_permiso != null) { ?>
                <tr>
                    <td style="width:70%;"><span>Turno:
                        </span><?= mb_convert_case($request->turno_permiso, MB_CASE_TITLE, "UTF-8"); ?></td>
                    <td></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>


    <table class="tab2" style="margin-bottom:25px;">
        <tbody>
            <tr>
                <td class="title-tab">Se autoriza la SALIDA a las:</td>
                <td class="" style="text-align: center;width: 20%;"><?= ($request->hora_salida != "00:00:00") ? date('H:i a', strtotime($request->hora_salida)) . "." : "---"; ?></td>
                <td class="title_dia">del día: </td>
                <td class="" style="text-align: center;"><?= ($request->fecha_salida != "0000-00-00") ? date("d-m-Y", strtotime($request->fecha_salida)) : "---"; ?></td>
            </tr>
            <tr>
                <td class="title-tab">Se autoriza la ENTRADA a las:</td>
                <td class="" style="text-align: center;"><?= ($request->hora_entrada != "00:00:00") ? date('H:i a', strtotime($request->hora_entrada)) . "." : "---"; ?></td>
                <td class="title_dia">del día: </td>
                <td class="" style="text-align: center;width: 20%;"><?= ($request->fecha_entrada != "0000-00-00") ? date("d-m-Y", strtotime($request->fecha_entrada)) : "---"; ?></td>
            </tr>
            <tr>
                <td class="title-tab">Se autoriza INASISTENCIA del:</td>
                <td class="" style="text-align: center;"><?= ($request->inasistencia_del != "0000-00-00") ? date("d-m-Y", strtotime($request->inasistencia_del)) : "---"; ?></td>
                <td class="title_dia">al día: </td>
                <td class="" style="text-align: center;"><?= ($request->inasistencia_al != "0000-00-00") ?  date("d-m-Y", strtotime($request->inasistencia_al)) : "---"; ?></td>
            </tr>
            <?php if ($request->estatus != 'Pendiente') { ?>
                <tr>
                    <td class="title-tab"><?= strtoupper($request->estatus); ?> por:</td>
                    <td colspan="3" style="text-align: center;"><?= $request->authoriza ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <table class="tab3" style="width:max-content;margin-bottom:40px;">
        <thead>
            <tr>
                <th colspan="" class="title-tab-header">OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:left;word-wrap: break-word;font-size: 14px;">
                    <p style="margin-left:12px; margin-right:12px;"><?= ($request->observaciones != "") ? $request->observaciones : " "; ?></p><br>
                </td>
            </tr>
        </tbody>
    </table>


</page>