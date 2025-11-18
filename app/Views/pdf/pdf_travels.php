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
        background-color: #DA1A22;
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

    .left_text {
        text-align: left;
        font-weight: bold;
        width: 25%;
        word-wrap: break-word;
        background-color: #BDBDBD;
    }

    .border_off {
        border-top: none;
        border-right: none;
        border-bottom: none;
        border-left: none;
    }

    .border_top {
        border-top: 5px solid black !important;
        border-right: none;
        border-bottom: none;
        border-left: none;
    }

    .border_rigth {
        border-left: none;
        border-bottom: none;
        border-left: none;
    }
    
    /* .title_tab_h {
        width: 20%;
        background: #BDBDBD;
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
        text-align: right;
        border: none;
    }

    .title_dia {
        width: 25%;
        background: #BDBDBD;
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
    } */

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
        background: #BDBDBD;
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
        background-color: #BDBDBD;
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
        background: #BDBDBD;
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

    .field_tbl {
        border-top: 1px solid #BDBDBD;
        font-weight: bold;
        text-align: center;
        font-size: 15px;
        background: #eee;
        /* padding: top der abj izq; */
        padding: 5px 10px 5px 10px;

    }

    .data_tbl {
        /* font-weight: bold; */
        text-align: center;
        font-size: 15px;
        padding-top: 3px;
        padding-bottom: 3px;
    }

    .no-border-bottom {
        border-bottom: none;
    }

    .no-border-top {
        border-top: none;
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
                <span>Hora de creación: <label for="" style="margin-left:15px"><?= date('H:i a', strtotime($request->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>
    <page_footer>
    </page_footer>
    <div class="title">
        <h3>SOLICITUD DE VIAJE</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Solicitante:
                    </span><?= mb_convert_case($request->user_name, MB_CASE_TITLE, "UTF-8") ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Número de Nómina:
                    </span><?= mb_convert_case($request->payroll_number, MB_CASE_TITLE, "UTF-8"); ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Puesto: </span><?= ucwords(strtolower($request->job_position)); ?></td>
                <td style="width:30%;text-align:right;"><span>Centro de Costo:
                    </span><?= $request->cost_center; ?></td>
            </tr>
            <tr>
                <td style="width:70%;"><span>Departamento: </span><?= ucwords(strtolower($request->depto)); ?></td>
                <td style="width:30%;text-align:right;"><span>Estatus: </span>
                    <?php switch ($request->request_status) {
                        case '1':
                            $estatus = '<span class="badge badge-warning">Pendiente</span>';
                            break;
                        case '2':
                            $estatus = '<span class="badge badge-info">Autorizado</span>';
                            break;
                        case '3':
                            $estatus = '<span class="badge badge-success">Aprobado</span>';
                            break;
                        case '6':
                            $estatus = '<span class="badge badge-danger">Rechazada</span>';
                            break;
                        default:
                            $estatus = '<span class="badge badge-warning">Error</span>';
                            break;
                    }
                    echo $estatus;
                    ?></td>
            </tr>

        </tbody>
    </table>


    <table class="tab2" style="width:max-content;margin-bottom:5px;">

        <tbody>
            <tr>
                <td class="field_tbl" style="width:25%;">Motivo del Viaje:</td>
                <td class="data_tbl" style="width:25%;"><?= mb_convert_case($request->reason_for_travel, MB_CASE_TITLE, "UTF-8"); ?></td>
                <td class="field_tbl" style="width:25%;">Presupuesto: </td>
                <td class="data_tbl" style="width:25%;">$ 
                <?php if ($request->request_status == 3) {
                  echo  $request->estimated_budget_approve; 
                } else { echo $request->estimated_budget;}?></td>
            </tr>
            <tr>
                <td class="field_tbl" style="width:25%;">Origen del Viaje:</td>
                <td class="data_tbl" style="width:25%;"><?= $request->origin_of_trip; ?></td>
                <td class="field_tbl" style="width:25%;">Destino del Viaje: </td>
                <td class="data_tbl" style="width:25%;"><?= $request->trip_destination; ?></td>
            </tr>
            <tr>
                <td class="field_tbl" style="width:25%;">Inicio del Viaje:</td>
                <td class="data_tbl" style="width:25%;"><?= $request->trip_start; ?></td>
                <td class="field_tbl" style="width:25%;">Regreso del Viaje: </td>
                <td class="data_tbl" style="width:25%;"><?= $request->return_trip; ?></td>
            </tr>
            <tr>
                <td class="field_tbl" style="width:25%;">Horario Ida:</td>
                <td class="data_tbl" style="width:25%;"><?= date('H:ia', strtotime($request->departure_time)); ?></td>
                <td class="field_tbl" style="width:25%;">Horario Regreso: </td>
                <td class="data_tbl" style="width:25%;"><?= date('H:ia', strtotime($request->return_time)); ?></td>
            </tr>



        </tbody>
    </table>

    <table class="tab2" style="width:max-content;margin-bottom:40px;">
        <tbody>
            <?php if ($request->lodging_required == 1) {  ?>
                <tr>
                    <td class="field_tbl" style="width:25%;">Hospedaje:</td>
                    <td class="" style="text-align: center;width:20%;">SI</td>
                    <td class="field_tbl" style="width:25%;">Hotel Preferente: </td>
                    <td class="" style="text-align: center;width: 30%;"><?= mb_convert_case($request->preferred_hotel, MB_CASE_TITLE, "UTF-8"); ?></td>
                </tr>

            <?php  } ?>
            <?php if ($request->car_rental == 1) {  ?>
                <tr>
                    <td class="field_tbl" style="width:25%;">Renta de Auto:</td>
                    <td class="" style="text-align: center;width:20%;">SI</td>
                    <td class="field_tbl" style="width:25%;">Renta del Auto: </td>
                    <td class="" style="text-align: center;width: 30%;"><?= mb_convert_case($request->car_rental_name, MB_CASE_TITLE, "UTF-8"); ?></td>
                </tr>
            <?php  } ?>
        </tbody>
    </table>

    <?php if ($request->request_advance == 1) {  ?>
        <table class="tab2" style="width:max-content;margin-bottom:15px;">
            <thead>
                <tr>
                    <th colspan="4" class="title_tab2">DATOS DE ANTICIPO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:10%;" class="field_tbl"> # </td>
                    <td style="width:30%;" class="field_tbl">TIPO DE GASTO</td>
                    <td style="width:30%;" class="field_tbl">MONTO SOLICITADO</td>
                    <td style="width:30%;" class="field_tbl">MONTO AUTORIZADO</td>
                </tr>
                <?php $i = 1;
                foreach ($item as $key => $value) { ?>
                    <tr>
                        <td style="width:10%;" class="data_tbl"><?= ucwords(strtolower($i)); ?></td>
                        <td style="width:30%;" class="data_tbl"><?= mb_convert_case($value->description, MB_CASE_TITLE, "UTF-8"); ?></td>
                        <td style="width:30%;" class="data_tbl"><?= "$ " . floatval($value->monto); ?></td>
                        <td style="width:30%;" class="data_tbl"><?= "$ " . floatval($value->monto_approve); ?></td>
                    </tr>
                <?php $i++;
                } ?>
                <tr>
                    <td class="border_rigth"></td>
                    <td class="field_tbl" style="width:30%;"><?= mb_convert_case($request->advance_type, MB_CASE_TITLE, "UTF-8"); ?></td>
                    <td class="data_tbl" style="width:25%;"><?= "$" . $request->amount; ?></td>
                    <td class="data_tbl" style="width:25%;"><?= "$" . $request->amount_approve; ?></td>
                </tr>
            </tbody>
        </table>

    <?php  } ?>
    <table class="tab3" style="width:max-content;margin-bottom:40px;">
        <thead>
            <tr>

                <th colspan="" class="title_tab2">OBSERVACIONES</th>

            </tr>

        </thead>
        <tbody>
            <tr>
                <td style="padding-left:20px;padding-rigth: 20px;"><?= ($request->observation != "") ? $request->observation : " "; ?></td>
            </tr>
        </tbody>
    </table>
    <page_footer>
        <table class="tab2">
            <tbody>
                <tr>
                    <td style="width:27%; height:100px;" class="border_off data_tbl "><?php if ($request->firma_user != null) { ?>
                            <img src="<?= $request->firma_user; ?>" style="width:80%;height:95px;">
                        <?php } ?>
                    </td>
                    <td style="width:5%; height:100px;" class="border_off data_tbl "></td>
                    <td style="width:36%; height:100px;" class="border_off data_tbl "><?php if ($request->firma_manager != null) { ?>
                            <img src="<?= $request->firma_manager; ?>" style="width:80%;height:95px;">
                        <?php } ?>
                    </td>
                    <td style="width:5%; height:100px;" class="border_off data_tbl"></td>
                    <td style="width:27%; height:100px;" class="border_off data_tbl "><?php if ($request->firma_admin != null) { ?>
                            <img src="<?= $request->firma_admin; ?>" style="width:80%;height:95px;">
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:27%;" class="field_tbl border_top">FIRMA SOLICITANTE</td>
                    <td style="width:5%;" class="border_off"></td>
                    <td style="width:36%;" class="field_tbl border_top">AUTORIZACION JEFE DIRECTO </td>
                    <td style="width:5%;" class="border_off"></td>
                    <td style="width:27%;" class="field_tbl border_top">AUTORIZACION ADMINISTRATIVA</td>
                </tr>
            </tbody>
        </table>
    </page_footer>

</page>