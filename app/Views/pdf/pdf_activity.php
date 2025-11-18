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
        font-size: 12px;
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
                <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->activity_date)); ?></label></span><br>
                <span>Hora de creación: <label for="" style="margin-left:40px"><?= date('H:i', strtotime($request->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>
    <page_footer>
    </page_footer>

    <div class="title">
        <h3>TICKET´S DE ACTIVIDADES</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Usuario IT:
                    </span><?= mb_convert_case($request->user, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Folio:
                    </span><?= mb_convert_case($request->id_activity, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Departamento: </span><?= ucwords(strtolower($request->departament)); ?></td>
                <td style="width:30%;text-align:right;"><span>Home Office:
                    </span><?= ($request->homeoffice == 1)?"Sí":"No"; ?>
                </td>
            </tr>
            <tr>
            <td style="width:70%;"><span>Complejidad: </span><?php switch ($request->complexity) {
                                                                                        case 1:
                                                                                            echo "Standard";
                                                                                            break;
                                                                                        case 2:
                                                                                            echo "Medio";
                                                                                            break;
                                                                                        case 3:
                                                                                            echo "Advance";
                                                                                            break;

                                                                                        default:
                                                                                            echo "***";
                                                                                            break;
                                                                                    } ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab3" style="width:max-content;margin-bottom:20px;">
        <thead>
            <tr>

                <th colspan="" class="title_tab2">ACTIVIDAD REALIZADA</th>

            </tr>

        </thead>
        <tbody>
            <tr>
                <td class="" style="text-align:left;word-wrap: break-word;font-size: 14px;">
                    <p><?= $request->activity; ?></p>
                </td>
            </tr>
        </tbody>
    </table>
</page>