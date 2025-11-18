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
        width: 22%;
        background: rgb(117, 117, 117);
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: right;
        border: none;
    }

    .title_dia {
        width: 20%;
        background: rgb(117, 117, 117);
        color: white;
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
</style>


<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!-- <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span>Fecha de creación: <label for=""><?= date("d/m/Y", strtotime($request->fecha_registro)); ?></label></span><br>
                <span>Hora de creación: <label for="" style="margin-left:40px"><?= ($request->fecha_registro != "00:00:00") ? date('H:i', strtotime($request->fecha_registro)) : "---"; ?></label></span>
            </div>
        </div>
    </page_header>
    <div class="title">
        <h3>AUTORIZACIÓN DE VACACIONES</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Solicitante:
                    </span><?= mb_convert_case($request->nombre_solicitante, MB_CASE_TITLE, "UTF-8") ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Número de Nómina:
                    </span><?= mb_convert_case($request->num_nomina, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Departamento: </span><?= mb_convert_case(ucwords(strtolower($request->departamento)), MB_CASE_TITLE, "UTF-8"); ?></td>
                <td style="width:30%; text-align:right;"><span>Fecha Ingreso:
                    </span><?= date("d/m/Y", strtotime($request->fecha_ingreso)); ?></td>
            </tr>
            <tr>
                <td style="width:70%; text-align:left;"><span>Puesto:
                    </span><?= mb_convert_case($request->puesto, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td style="width:30%; text-align:right;"><span>Estado:
                    </span><?= mb_convert_case($request->estatus, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
        </tbody>
    </table>


    <table class="tab2" style="margin-bottom:25px;">
        <tbody>
            <tr>
                <td class="title_tab">Disfrutara:</td>
                <td class="" style="font-size:16px;text-align:center;width:20%;"><?= ($request->num_dias_a_disfrutar != 0) ? $request->num_dias_a_disfrutar : "---"; ?></td>
                <td class="title_tab" style="font-size:16px;text-align:center;width:12%;"><?= ($request->num_dias_a_disfrutar > 1) ? "días del:" : "día del:"; ?></td>
                <td class="" style="font-size:16px;text-align:center;width:16%;"><?= ($request->dias_a_disfrutar_del != 0) ? date("d/m/Y", strtotime($request->dias_a_disfrutar_del)) : "---"; ?></td>
                <td class="title_tab" style="font-size:16px;text-align:center;width:12%;"><?= "al día:"; ?></td>
                <td class="" style="font-size:16px;text-align:center;width:16%;"><?= ($request->dias_a_disfrutar_al != 0) ? date("d/m/Y", strtotime($request->dias_a_disfrutar_al)) : "---"; ?></td>
            </tr>
            <tr>
                <td class="title_tab">Regresando:</td>
                <td class="" style="text-align:center;font-size:16px;"><?= ($request->regreso != "0000-00-00") ? date("d/m/Y", strtotime($request->regreso)) : "---"; ?></td>
                <td class="title_tab" style="font-size:16px;text-align:center;width:12%;"><?= "Restan:" ?></td>
                <td class="" style="font-size:16px;text-align:center;width:16%;"><?= $request->dias_restantes; ?></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-left:25%;">
        <?php if (strlen($request->a_cargo) > 12) { ?>
            <table class="tab3" style="text-align: center;width:65%;margin-bottom:40px;">
                <thead>
                    <tr>
                        <th class="title_tab2"> DEJANDO A CARGO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-size:17px; text-align:center;">
                            <p class="tet-center"> <?= $request->a_cargo; ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php } ?>
        <?php if ($request->estatus != 'Pendiente') { ?>
            <table class="tab3" style="text-align:center;width:65%;margin-bottom:40px;">
                <thead>
                    <tr>
                        <th class="title_tab2"><?= strtoupper($request->estatus); ?> POR</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-size:17px; text-align:center;">
                            <p class="tet-center"><?= $request->authoriza ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <page_footer>
    </page_footer>
</page>