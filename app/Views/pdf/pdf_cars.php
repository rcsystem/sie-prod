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

    .tab2 {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
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
        padding: 10px;
        text-align: center;
        border: none;
    }
    .subtitle{
        width: 58%;
        background-color:#EEE;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        padding-top: 0px;
        padding-bottom: 7px;
    }

    .border_right_left {
        border-bottom: none;
    }

    .border_bottom_right {
        padding-bottom: 20px;
        border-left: none;
    }

    .border_bottom_left {
        padding-bottom: 20px;
        border-right: none;
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
        <h3>SOLICITUD DE VEHICULO</h3>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:70%"><span>Solicitante:
                    </span><?= mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8"); ?>
                </td>
                <td style="width:30%; text-align:right;"><span>Folio:
                    </span><?= mb_convert_case($request->id_request, MB_CASE_TITLE, "UTF-8"); ?></td>
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

    <table class="tab3" style="width:max-content;margin-bottom:20px;">
        <thead>
            <tr>

                <th colspan="" class="title_tab2">MOTIVO</th>

            </tr>

        </thead>
        <tbody>
            <tr>
                <td class="borde" style="text-align:left;word-wrap: break-word;font-size: 14px;">
                    <p style="margin-left:12px; margin-rigth:12px;"><?= mb_strtoupper($request->motive, 'UTF-8'); ?></p><br>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="tab2" style="width:max-content;margin-bottom:20px;">
        <thead>
            <tr>
                <th colspan="2" class="title_tab2">DATOS DEL VIAJE</th>
            </tr>
        </thead>
        <tbody class="text-center" style="text-align:left;word-wrap: break-word;font-size: 14px;">
            <tr>
                <?php
               
                 if($request->status == 4){ 
                 
                 
                 ?>
                <td colspan="2" class="border_right_left">
                    <div style="width:100%; text-align:center; margin-bottom:20px;">
                      <img style="width:250px; height:150px; float:left; margin-top:20px;" src=<?= ($cars->imagen != "")? $cars->imagen:""; ?> >
                    </div>
                    <div style="width:100%; text-align:center; margin-bottom:20px;">
                        <p>
                            <label style="font-size:15px; font-weight:bold; ">MODELO:</label>&nbsp;&nbsp;<?= mb_strtoupper($cars->model, 'UTF-8'); ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label style="font-size:15px; font-weight:bold; ">PLACAS:</label>&nbsp;&nbsp;<?= mb_strtoupper($cars->placa, 'UTF-8'); ?>
                        </p>
                    </div>
                </td>
                <?php
                
            
            } else{ ?>
                <td colspan="2" class="border_right_left">
                    <h1  style="width:100%; text-align:center; margin-bottom:20px;">VEHICULO NO ASIGNADO</h1>
                </td>
                <?php }?>
            </tr>
            <tr>
                <td colspan="2"  class="subtitle border_right_left">
                    <?php if ($request->type_trip == 1) { ?>
                        <p style="padding-top: -10px;">VIAJE CORTO</p>
                    <?php } ?>
                    <?php if ($request->type_trip == 2) { ?>
                        <p style="padding-top: -10px;">VIAJE PROLONGADO</p>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style="width:50%; text-align:center;" class="border_bottom_left">
                    <?php if ($request->type_trip == 1) { ?>
                    <h5>FECHA SOLISITADA</h5>
                    <label><?= mb_strtoupper($trip->date, 'UTF-8'); ?></label>
                <?php } ?>
                <?php if ($request->type_trip == 2) { ?>
                    <h5>FECHA DE INICIO SOLISITADA</h5>
                    <label><?= mb_strtoupper($trip->star_date, 'UTF-8'); ?></label>
                    <h5>HORA DE INICIO</h5>
                    <label><?= mb_strtoupper($trip->star_datetime, 'UTF-8'); ?></label>
                <?php } ?>
                </td>
                <td style="width:50%; text-align:center;" class="border_bottom_right">
                <?php if ($request->type_trip == 1) { ?>
                    <h5>HORA DE INICIO</h5>
                    <label><?= mb_strtoupper($trip->star_time, 'UTF-8'); ?></label>
                    <h5>HORA DE FINALIZACION</h5>
                    <label><?= mb_strtoupper($trip->end_time, 'UTF-8'); ?></label>
                <?php } ?>
                <?php if ($request->type_trip == 2) { ?>
                    <h5>FECHA DE FINALIZACION</h5>
                    <label><?= mb_strtoupper($trip->end_date, 'UTF-8'); ?></label>
                    <h5>HORA DE FINALIZACION</h5>
                    <label><?= mb_strtoupper($trip->end_datetime, 'UTF-8'); ?></label>
                <?php } ?>
                </td>
            </tr>
        </tbody>
    </table>
</page>