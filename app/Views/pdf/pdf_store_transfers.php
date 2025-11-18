<style>
    body {
        font-family: 'Arial', sans-serif;
        color: #333;
        font-size: 12px;
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
        margin: 20px 0;
        background-color: #2c3e50;
        color: white;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
    }

    .title h3 {
        padding: 0;
        margin: auto;
    }

    .tab1 {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tab1 tr td {
        border: 1px solid #ccc;
        padding: 8px;
        font-size: 12px;
    }

    .tab1 span {
        font-size: 14px;
        /*  font-weight: bold; */
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
        width: 11%;
        background: rgb(52, 58, 64);
        color: white;
        font-weight: bold;
        font-size: 12px;
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
        border-left: 0.5px solid #BDBDBD;
        border-right: 0.5px solid #BDBDBD;
        border-bottom: 0.5px solid #BDBDBD;
        border-top: 0.5px solid #BDBDBD;
        padding: 5px 4px 5px 3px;
    }

    .tab2 tr th {
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
        background: #343A40;
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
                <label for="">

                    <?php

                    switch ($data->type_transfer) {
                        case 'Herramientas':
                            $codigo = "FAL-01 Rev.A";
                            break;
                        case 'Suministro':
                            $codigo = "FAL-02 Rev.A";
                            break;
                        case 'Traslado':
                            $codigo = "FAL-08 REV.O";
                            break;
                        case 'Indirectos':
                            $codigo = " FAL-04";
                            break;

                        default:
                            $codigo = "FAL";
                            break;
                    }

                    ?>

                    <i><?= $codigo ?></i>

                </label>
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->created_at)); ?></label></span><br>
                <span>Hora de creación: <label for="" style="margin-left:40px"><?= date('H:i', strtotime($request->created_at)); ?></label></span>
            </div>
        </div>
    </page_header>
    <page_footer>
    </page_footer>
    <div class="title">
        <h4><?php
           // strtoupper($data->type_transfer);

            // Convertir la variable $transfer a minúsculas y eliminar espacios extra
             $tituloNormalizado = strtolower(trim($data->type_transfer));

            // Mapa de nombres de empresas a sus respectivos códigos
            $tituloMap = [
                'herramientas' => 'SALIDA POR BAJA DE MATERIALES EN ALMACÉN DE HERRAMIENTAS',
                'suministro' => 'VALE DE SALIDA DE MATERIAS PRIMAS',
                'indirectos' => 'SALIDA POR BAJA DE MATERIALES EN ALMACÉN DE INDIRECTOS',
                'traslado' => 'VALE DE SALIDA DE ALMACEN DE PRODUCTO TERMINADO',
                
            ];

            // Asignar el código de empresa según el mapa o usar 1 como valor predeterminado
            $titulo = $tituloMap[$tituloNormalizado] ?? "NO HAY TITULO";

echo $titulo;
            ?></h4>
    </div>
    <table class="tab1">
        <tbody>
            <tr>
                <td style="width:60%">
                    <span>Usuario:
                    </span><?= mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8") . " " . mb_convert_case($request->surname, MB_CASE_TITLE, "UTF-8") ?>
                </td>
                <td style="width:40%;text-align:right;">
                    <span>Folio: </span><?= ucwords(strtolower($request->id_vouchers)); ?>
                </td>
            </tr>
            <tr>
                <td style="width:60%">
                    <span>Número de Nómina:</span>

                    <?= mb_convert_case($data->payroll_number, MB_CASE_TITLE, "UTF-8"); ?>

                </td>
                <?php if ($data->payroll_number != '') { ?>
                    <td style="width:40%; text-align:right;">

                        <span>Estatus: </span><?php switch ($request->estatus) {
                                                    case '2':
                                                        echo "Autorizada";
                                                        break;
                                                    case '3':
                                                        echo "Rechadaza";
                                                        break;
                                                    default:
                                                        echo "Pendiente";
                                                        break;
                                                }  ?>


                    </td>

                <?php  }  ?>

            </tr>
            <tr>
                <td style="width:60%">
                    <?php if (isset($imagen->payrollnumber_image) && !empty($imagen->payrollnumber_image)) { ?>
                        <img src="<?= $imagen->payrollnumber_image; ?>" width="180" height="20" alt="">

                    <?php } ?>
                </td>
                <td style="text-align:right;">

                    <span>Transferir a:
                    </span><?php switch ($request->departures) {
                                case 1:
                                    echo "NAVE 1";
                                    break;
                                case 2:
                                    echo  "NAVE 4";
                                    break;
                                case 3:
                                    echo  "NAVE 3";
                                    break;
                                case 4:
                                    echo  "Villahermosa";
                                    break;
                                case 5:
                                    echo  "Century";
                                    break;

                                default:
                                    echo  "Error";
                                    break;
                            } ?>

                </td>
            </tr>
            <tr>
                <td style="width:60%">
                    <span>Centro de Costo: </span><?= ucwords(strtolower($data->cost_center)); ?>
                </td>
                <td style="width:40%; text-align:right;">

                    <span>Autoriza:
                    </span><?php if (isset($request->nombre)) {
                                echo mb_convert_case($request->nombre, MB_CASE_TITLE, "UTF-8") . " " . mb_convert_case($request->apellido, MB_CASE_TITLE, "UTF-8");
                            } ?>
                </td>

            </tr>
            <tr>
                <td style="width:60%">
                    <?php if (isset($imagen->costcenter_image) && !empty($imagen->costcenter_image)) { ?>
                        <img src="<?= $imagen->costcenter_image  ?>" width="180" height="20" alt="">
                    <?php } ?>
                </td>
                <td style="text-align:right;width:40%">

                </td>

            </tr>

        </tbody>
    </table>


    <table class="tab2" style="margin-top:30px;margin-bottom:5px;">
        <tbody>

            <?php
            $cont = 1;
            foreach ($item as $key => $value) { ?>

                <tr>
                    <?php if ($value["barcode_image"] != "") { ?>

                        <td class="title_tab">CODIGO:</td>
                        <td class="" style="text-align: left;width:30%;font-size: 12px;padding-left: 5px;">
                            <img src="<?= $value["barcode_image"] ?>" alt="" width="165" height="25">
                        </td>

                    <?php } ?>
                    <td class="title_tab">UBICACIÓN:</td>
                    <td class="" style="text-align: center;width:30%;font-size: 12px;padding-left: 5px;">
                        <img src="<?= $value["weight_image"] ?>" alt="" width="165" height="25">&nbsp;&nbsp;
                        <span style="padding-left: 5px;"><?= mb_convert_case($value["weight"], MB_CASE_TITLE, "UTF-8") ?></span>
                    </td>
                </tr>

                <tr>

                    <td class="title_tab">ARTICULO:</td>
                    <td class="" style="text-align: left;width:40%;font-size: 12px;padding-left: 5px;"><?= mb_convert_case($value["code"], MB_CASE_TITLE, "UTF-8") ?> - <?= mb_convert_case($value["article"], MB_CASE_TITLE, "UTF-8"); ?></td>
                    <td class="title_tab" style="width: 10%;">CANTIDAD: </td>
                    <td class="" style="text-align: center;width: 9%;font-size: 12px;"><?= mb_convert_case($value["amount"], MB_CASE_TITLE, "UTF-8") . " " . $value["unit_of_measure"]; ?></td>


                </tr>
                <tr>
                    <td class="title_tab">OBVS: </td>
                    <td colspan="3" class="" style="width: 30%;font-size: 12px; padding-left: 5px;">
                        <?= ucfirst($value["observation"]); ?></td>

                </tr>

            <?php $cont++;
            } ?>
        </tbody>
    </table>

</page>