<style>
    body {
        font-family: 'Arial', sans-serif;
        color: #333;
        font-size: 12px;
        margin: 0;
        padding: 0;
    }

    .norma {
        width: 98.5%;
        text-align: right;
    }

    .header {
        width: 100%;
        display: flex;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 2px solid #ccc;

    }

    .header .img {
        display: flex;
        align-items: center;
    }

    .header img {
        width: 160px;
        height: auto;
        margin-right: auto;
        ;
    }


    .header span {
        font-size: 12px;
        margin-left: 20px;
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



    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th,
    .table td {
        padding: 8px 10px;
        border: 1px solid #BDBDBD;
        font-size: 12px;
    }

    .table th {
        background: #2c3e50;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .left_text {
        text-align: left;
        /*  font-weight: bold;
   background-color: rgb(238, 238, 238); */
    }

    .right_text {
        text-align: right;
    }

    .title_tab {
        width: 16.66%;
        border: 1px solid #2c3e50;
        background: #2c3e50;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .title_tab2 {
        background: #2c3e50;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .tab3 {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .tab3 th {
        background: #2c3e50;
        color: white;
        font-weight: bold;
    }

    .center-text {
        text-align: center;
    }

    .sub {
        width: 25%;
        background-color: rgb(238, 238, 238);
        font-size: 14px;
        font-weight: bold;
        padding: 8px;
        border: 1px solid #BDBDBD;
    }

    .descripcion {
        width: 75%;
        padding: 8px 5px;
        border: 1px solid #BDBDBD;
    }

    .tab2 {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        border: 1px solid #2c3e50;
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
                <span style="margin-left: 420px;">Fecha de creación: <label><?= date("d/m/Y", strtotime($request->fecha_registro)); ?></label></span><br>
                <span style="margin-left: 620px;">Hora de creación: <label><?= ($request->fecha_registro != "00:00:00") ? date('H:i', strtotime($request->fecha_registro)) : "---"; ?></label></span>
            </div>
        </div>
    </page_header>

    <div class="title">
        <h3>AUTORIZACIÓN DE VACACIONES</h3>
    </div>

    <table class="table">
        <tbody>
            <tr>
                <td class="left_text" style="width:60%"><span>Solicitante:</span> <?= mb_convert_case($request->nombre_solicitante, MB_CASE_TITLE, "UTF-8") ?></td>
                <td class="right_text" style="width:40%"><span>Folio:</span> <?= $request->id_vcns; ?></td>
            </tr>
            <tr>
                <td class="left_text"><span>Departamento:</span> <?= mb_convert_case(ucwords(strtolower($request->departamento)), MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="right_text"><span>Número de Nómina:</span> <?= mb_convert_case($request->num_nomina, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td class="left_text"><span>Puesto:</span> <?= mb_convert_case($request->puesto, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="right_text"><span>Estado:</span> <?= mb_convert_case($request->estatus, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
        </tbody>
    </table>

    <?php if ($request->id_vcns < 8695) { ?>
        <table class="table tab2" style="margin-bottom:25px;">
            <tbody>
                <tr>
                    <td class="title_tab">Disfrutará:</td>
                    <td class="center-text" style="width:16.66%;"><?= ($request->num_dias_a_disfrutar != 0) ? $request->num_dias_a_disfrutar : "---"; ?></td>
                    <td class="title_tab"><?= ($request->num_dias_a_disfrutar > 1) ? "días del:" : "día del:"; ?></td>
                    <td class="center-text"  style="width:16.66%;"><?= ($request->dias_a_disfrutar_del != 0) ? date("d/m/Y", strtotime($request->dias_a_disfrutar_del)) : "---"; ?></td>
                    <td class="title_tab">al día:</td>
                    <td class="center-text"  style="width:16.66%;"><?= ($request->dias_a_disfrutar_al != 0) ? date("d/m/Y", strtotime($request->dias_a_disfrutar_al)) : "---"; ?></td>
                </tr>
                <tr>
                    <td class="title_tab">Regresando:</td>
                    <td class="center-text"><?= ($request->regreso != "0000-00-00") ? date("d/m/Y", strtotime($request->regreso)) : "---"; ?></td>
                    <td class="title_tab">Restan:</td>
                    <td class="center-text"><?= $request->dias_restantes; ?></td>
                </tr>
            </tbody>
        </table>
    <?php } else { ?>
        <div style="">
            <table class="table tab2" style="margin-bottom:25px;">
                <tbody>
                    <tr>
                        <td class="title_tab">Disfrutará:</td>
                        <td class="center-text"><?= ($request->num_dias_a_disfrutar != 0) ? $request->num_dias_a_disfrutar : "---"; ?></td>
                        <td class="title_tab">Regresando:</td>
                        <td class="center-text"><?= ($request->regreso != "0000-00-00") ? date("d/m/Y", strtotime($request->regreso)) : "---"; ?></td>
                        <td class="title_tab">Restan:</td>
                        <td class="center-text"><?= $request->dias_restantes; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="">
            <table class="table tab2" >
                <thead>
                    <tr>
                        <th class="title_tab2" style="border: 1px solid #2c3e50;">DIAS SOLICITADOS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($days as $key) { ?>
                        <tr>
                            <td class="center-text"><?= $key->date_vacation; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <div style="">
        <?php if (strlen($request->a_cargo) > 12) { ?>
            <table class="table tab3" style="width:65%; margin-bottom:40px;">
                <thead>
                    <tr>
                        <th class="title_tab2" style="border: 1px solid #2c3e50;">DEJANDO A CARGO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center-text" style="font-size:17px;"><?= $request->a_cargo; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } ?>
        <?php if ($request->estatus != 'Pendiente') { ?>
            <table class="table tab3" style="width:65%; margin-bottom:40px;">
                <thead>
                    <tr>
                        <th class="title_tab2" style="border: 1px solid #2c3e50;"><?= strtoupper($request->estatus); ?> POR</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center-text" style="font-size:17px;"><?= $request->authoriza; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <page_footer>
        <div style="text-align:center;">

        </div>
    </page_footer>
</page>