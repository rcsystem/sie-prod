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

    .subtitle {
        width: 58%;
        background-color: #EEE;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        padding-top: 0px;
        padding-bottom: 7px;
    }

    .border-none {
        border-left: none;
        border-right: none;
        border-bottom: none;
        border-top: none;
    }

    .no-border-right {
        border-right: none;
    }

    .no-border-left {
        border-left: none;
    }

    .no-border-bottom {
        border-bottom: none;
    }

    .no-border-top {
        border-top: none;
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
        border-top: 1px solid #BDBDBD;
        font-weight: bold;
        text-align: center;
        font-size: 15px;
        background: #eee;
        /* padding: top der abj izq; */
        padding: 5px 10px 5px 10px;

    }

    .field_firma {
        border-top: 1px solid #BDBDBD;
        font-weight: bold;
        text-align: center;
        font-size: 10px;
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

    .firma {
        width: 85%;
        height: 80%;
        padding: 5px 4px 5px 3px;
    }
</style>
<?php
switch ($request->id_user_delivery) {
    case 29:
        $src = './images/firmas_users/img_firma.png';
        break;
    case 839:
        $src = './images/firmas_users/img_firma.png';
        break;
    case 1069:
        $src = './images/firmas_users/img_firma.png';
        break;

    default:
        $src = './images/firmas_users/img_firma.png';
        break;
}

/* ($request->id_user_delivery = 1) {
    $src= "Public/images/firmas_Rafael/2022-03-17/firma-3";
} if ($request->id_user_delivery = 1063) {
    $src= "Public/images/firmas_yo/2022-03-17/firma-3";
} */
?>

<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!--  <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->date_delivery)); ?></label></span><br>
                <span>Hora de creación: <label for="" style="margin-left:10px"><?= date('H:i a', strtotime($request->date_delivery)); ?></label></span>
            </div>
        </div>
    </page_header>

    <div class="title">
        <h3>RESPOSIBA DE EQUIPO DE COMPUTO</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:50%;"><span style="font-weight:bold;">Responsable:
                    </span><?= mb_convert_case($users->name, MB_CASE_TITLE, "UTF-8") . " " .
                                mb_convert_case($users->surname, MB_CASE_TITLE, "UTF-8") . " " .
                                mb_convert_case($users->second_surname, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:50%; text-align:right;"><span style="font-weight:bold;">Folio:
                    </span><?= mb_convert_case($request->id_request, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left;"><span style="font-weight:bold;">Area Operativa:
                    </span><?= ucwords(strtolower($users->departament)); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="tab3" style="width:84%;margin-left:8%;margin-right:8%;margin-bottom:20px;">
        <thead>
            <tr>
                <th colspan="2" class="title_tab2">ESPECIFICACIONES</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height:13px; width:30%; text-align:right;"><span style="font-weight:bold;">Marca y Modelo:
                    </span>
                </td>
                <td style="height:13px; width:70%; text-align:center;">
                    <?= mb_convert_case($equip->marca, MB_CASE_TITLE, "UTF-8") . " " .
                        mb_convert_case($equip->model, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
            </tr>
            <tr>
                <td style="height:13px; width:30%; text-align:right;"><span style="font-weight:bold;">Tipo de Equipo:
                    </span>
                </td>
                <td style="height:13px; width:70%; text-align:center;">
                    <?= mb_convert_case($equip->type_product, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
            </tr>
            <tr>
                <td style="height:13px; width:30%; text-align:right;"><span style="font-weight:bold;">IMEI / SERIE:
                    </span>
                </td>
                <td style="height:13px; width:70%; text-align:center;">
                    <?= mb_convert_case($equip->no_serial, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
            </tr>
            <tr>
                <td style="height:13px; width:30%; text-align:right;"><span style="font-weight:bold;">Condiciones del Equipo y Obsercaciones:
                    </span>
                </td>
                <td style="height:25px; width:70%; text-align:center;">
                    <?= mb_convert_case($equip->features, MB_CASE_TITLE, "UTF-8"); ?>. <br>
                    <?= mb_convert_case($request->coment, MB_CASE_TITLE, "UTF-8"); ?>.
                </td>
            </tr>
        </tbody>
    </table>
    <table class="border-none" style="font-size:18px;width:80%;margin-left:10%;margin-right:10%;margin-bottom:20px;">
        <tbody>
            <tr>
                <td>
                    <span style="font-weight:bold;">
                        <p style="text-align:center; font-size:11px;">POLITICAS DE USO, NOTAS DE CUIDADOS Y CASOS</p>
                        <p style="width:84%;font-size:9px;">
                            Me comprometo a cuidar y hacer buen uso del equipo asi como conservarlo en buen estado, devolverlo cuando cause baja en la empresa,
                            <br>o me sea solicitado. En caso contrario PAGARE y ACEPTARE el descuento via NOMINA del IMPORTE que corresponda a su reposición,
                            <br>(el costo del equipo se expresa en la lista actual de la marca en su sitio web con base en la devaluación o costo actual de modelos y
                            <br>marcas "pagarlo el costo a la fecha").<br>
                            <br>En caso de daño o ruptura de alguna pieza imputable al uso por trabajo u operación el usuario debera realizar el reporte correspondiente
                            <br>"por escrito" via email o impreso y no tratar de hacer ninguna reparación, de lo contrario el usuario sera responsable de la falla y debera
                            <br>cubrir el costo total de la reparación o sustitución del equipo.<br>
                            <br>Cuando el daño, ruptura o falla de alguna pieza sea por accidente o negligencia; el usuario se compromete a pagar un monto que va del
                            <br>30% al 70 % del precio por reparación o sustitución del equipo: (Este monto sera designado tomando en cuenta la causa del imprevisto).<br>
                            <br>El usuario se compromete a hacer uso exclusivo del equipo celular o de computo para la acciones de la Empresa, pueden durante su
                            <br>poder descargar aplicaciones que no impidan la correcta operacion sin embargo; "El incumplimiento de esta clausula derivara en las
                            <br>sanciones que la Dirección General considere pertinentes".<br>
                            <br>Lasrenovacion realizaran de 36 a 48 meses según el equipo asignado al usuario basado en su perfil de puesto. Al finalizar el periodo el
                            <br>usuario debera devolver el equipo anterior en óptimas condiciones que el tiempo de uso lo amerite, si el equipo se entrega con daño
                            <br>severo o sin alguna pieza o parte; debera pagar una penalización que va del 30 al 70% del costo de la reparación del equipo.
                            <br>(Dicho monto sera designado tomando en cuenta la causa del daño "Accidente o negligencia)<br>
                            <br>LEVANTAR EL ACTA NTE MINISTERIO PÚBLICO, PRESENCIAL O DIGITAL:
                        </p>
                        <p style="width:84%;font-size:8px;">
                            http://www.edomex.gob.mx/sis/pgjem/sistema/msai0.asp
                            <br>https://www.pgj.cdmx.gob.mx/nuestros-servicios/en-linea
                        </p>
                        <p style="width:84%;font-size:9px;">
                            SIN LA DENUNCIA DE PAGOS DE DEDUCIBLE SE ANALIZARAN SEGUN EL TIPO DE EVENTO.
                        </p>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <page_footer>
        <table class="tab2" style="width:90%;margin-left:5%;margin-right:5%;margin-bottom:20px;">
            <tbody>
                <tr>
                    <td style="width:40%; padding: 5px 4px 5px 3px;" class="field_firma">ACEPTO CON FIRMA ELECTRONICA</td>
                    <td style="width:20%;" class="no-border-bottom no-border-top "></td>
                    <td style="width:40%; padding: 5px 4px 5px 3px;" class="field_firma">FIRMA</td>
                </tr>
                <tr>
                    <td style="width:40%; padding: 5px 4px 5px 3px; height:80px;" class="data_tbl ">
                    <?php if ($request->confir == 1) { ?>
                    <img src="<?= $firma->e_signature ?>" class="firma">
                    <?php } ?>
                    </td>
                    <td style="width:20%; height:80px;" class="data_tbl no-border-bottom no-border-top "></td>
                    <td style="width:40%; padding: 5px 4px 5px 3px; height:80px;" class="data_tbl ">
                        <img src="<?= $src ?>" class="firma">
                    </td>
                </tr>
                <tr>
                    <td style="width:40%; padding: 5px 4px 5px 3px;" class="field_firma">RECIBE:
                        <?= mb_convert_case($users->name, MB_CASE_TITLE, "UTF-8") . " " .
                            mb_convert_case($users->surname, MB_CASE_TITLE, "UTF-8") . " " .
                            mb_convert_case($users->second_surname, MB_CASE_TITLE, "UTF-8"); ?> </td>
                    <td style="width:20%;" class="no-border-bottom no-border-top "></td>
                    <td style="width:40%; padding: 5px 4px 5px 3px;" class="field_firma">ENTREGA:
                        <?= mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8") . " " .
                            mb_convert_case($request->surname, MB_CASE_TITLE, "UTF-8") . " " .
                            mb_convert_case($request->second_surname, MB_CASE_TITLE, "UTF-8"); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </page_footer>
</page>