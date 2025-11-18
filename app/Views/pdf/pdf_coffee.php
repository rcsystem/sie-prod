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
        margin-bottom: 10px;
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
        text-align: left;
        width: 20%;
        word-wrap: break-word;
    }

    .middle {
        width: 10%;
        background-color: transparent;
        border: none;
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

    .tab2 tbody tr td {
        border-left: 1px solid #BDBDBD;
        border-right: 1px solid #BDBDBD;
        border-bottom: 1px solid #BDBDBD;
        padding: 5px 4px 5px 3px;
    }

    .border_off {
        border: none;
    }

    .border_right {
        border-top: none;
        border-bottom: none;
        border-left: none;
    }

    .tab3 {
        width: 100%;
        font-size: 14px;
        border-collapse: collapse;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    /*rgb(117, 117, 117)*/
    .tab3 .title_tab3 {
        width: 100%;
        background: #eee;
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
        font-size: 12px;
    }

    .title_tab2 {
        width: 58%;
        background: #EEE;
        color: black;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: center;
        border: none;
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
</style>


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
        <h3>SOLICITUDES DE COFFEE BREAK</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Solicitante:
                    </span><?= mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Folio:
                    </span><?= mb_convert_case($request->id_coffee, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Departamento: </span><?= ucwords(strtolower($request->depto)); ?></td>
                <td style="width:30%;text-align:right;"><span>Area Operativa:</span><?= ucwords(strtolower($request->area_operativa)); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Puesto: </span><?= ucwords(strtolower($request->position_job)); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab2" style="width:100%;margin-bottom:5px;">

        <tbody>

            <tr>
                <td style="width:25%; font-weight:bold;text-align:center;font-size:15px;background:#eee;">Lugar:</td>
                <td style="width:25%; font-weight:bold;font-size:15px;">
                    <?php
                    switch ($request->meeting_room) {
                        case '1':
                            $sala = "Sala de Consejo";
                            break;
                        case '2':
                            $sala = "Sala de Operaciones";
                            break;
                        case '3':
                            $sala = "Sala de Ingenieria";
                            break;
                        case '4':
                            $sala = "Sala James Walworth";
                            break;
                        case '5':
                            $sala = "Sala de Logistica";
                            break;
                        case '6':
                            $sala = "Sala de Ventas";
                            break;
                        case '7':
                            $sala = "Sala de Calidad";
                            break;
                        case '8':
                            $sala = "Mezzanine (Nave 3)";
                            break;

                        default:
                            $sala = "Error no se Selecciono Sala";
                            break;
                    }
                    ?>
                    <?= $sala;  ?>
                </td>
                <td style="width:25%;font-weight:bold;text-align:center;font-size:15px;background:#eee;">Motivo de la Reunión:</td>
                <td style="width:25%;font-weight:bold;text-align:center;font-size:15px;"><?= ucwords(strtolower($request->reason_meeting)); ?></td>
            </tr>
            <tr>
                <td style="font-weight:bold;text-align:center;font-size:15px;background:#eee;">Fecha de la Reunión:</td>
                <td style="font-weight:bold;text-align:center;font-size:15px;"><?= ucwords(strtolower($request->date)); ?></td>
                <td style="font-weight:bold;text-align:center;font-size:15px;background:#eee;">Hora de la Reunión:</td>
                <td style="font-weight:bold;text-align:center;font-size:15px;"><?= date('H:i a', strtotime($request->horario)); ?></td>
            </tr>

            <tr>
                <td style="font-weight:bold;text-align:center;font-size:15px;background:#eee;">Numero de Personas:</td>
                <td style="font-weight:bold;text-align:center;font-size:15px;"><?= ucwords(strtolower($request->num_person)); ?></td>
                <td style="font-weight:bold;text-align:center;font-size:15px;background:#eee;">Menu Especial:</td>
                <td style="font-weight:bold;text-align:center;font-size:15px;"><?= ($request->menu_especial == 0) ? "NO" : "SI"; ?></td>
            </tr>

        </tbody>
    </table>

<?php if( $request->observations != ""){
     ?>

    <table class="tab3" style="width:max-content;margin-bottom:20px;">
        <thead>
            <tr>

                <th colspan="" class="title_tab2">OBSERVACIONES</th>

            </tr>

        </thead>
        <tbody>
            <tr>
                <td class="" style="text-align:left;word-wrap: break-word;font-size: 14px;">
                    <p><?= mb_strtoupper($request->observations, 'UTF-8'); ?></p><br>
                </td>
            </tr>
        </tbody>
    </table>
    <?php } ?>

    <table class="tab3" style="width:max-content;margin-bottom:20px;">
       <thead>
            <tr>

                <th colspan="" class="title_tab2">SERVICIO DE COFFEE BREAK</th>

            </tr>

        </thead>
        <tbody>
            <tr>
                <td class="" style="text-align:left;word-wrap: break-word;font-size: 14px;">
                    <?php
                foreach ($items as $key => $value) { 
                    ?>
                        <p> <?=  mb_strtoupper($value->product, 'UTF-8');  ?></p>
                    <?php
                    } 
                    ?><br>
                </td>
            </tr>
        </tbody>
    </table>
    <?php if($menu != null){ ?>
    <table class="tab2" style="width:80%!important;">
        <thead>
            <tr>

                <th colspan="" class="title_tab2">MENU ESPECIAL SELECCIONADO</th>

            </tr>

        </thead>
        <tbody>
            <tr>
                <td class="">

                    <div class="card-body" role="button">
                        <div style="float: left;margin-left:20px;">
                            <h5 class="card-title">
                                <label for="menu1"><?= mb_strtoupper($menu->tittle_menu, 'UTF-8'); ?></label>
                            </h5>
                        </div>
                        <div class="text-center">
                            <img style="width:225px; height:160px; float: left; margin-left:20px; margin-bottom:20px;" src=<?= $menu->imagen_menu; ?> alt="Menu 1" />
                            <ol style="width:50%; font-size: 14px; float: right;" class="card-text1">
                            <?php
                                foreach ($comida as $description => $value) {
                                ?>
                                    <li><?= mb_strtoupper($value->description, 'UTF-8'); ?></li>
                                <?php
                                }
                                ?>
                            </ol>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
<?php } ?>
</page>