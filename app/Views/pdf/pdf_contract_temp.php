<style>
    body {
        font-family: 'Helvetica'
    }

    .norma {
        width: 98.5%;
        text-align: right;
    }

    .header {
        width: 98%;
        display: flex;
        height: 30px;
    }

    .header .img img {
        width: 310px;
        height: 65px;

        margin-right: auto;
        margin-left: 7px;
        margin-top: 1px;
    }

    .tab1 {
        width: 100%;
        border-collapse: separate;
        border-spacing: 4px;
        margin-bottom: 20px;
    }

    .tab1 tr td {
        padding: 4px;
        font-size: 16px;
        border: 1px solid transparent;
        border-bottom: 0.5px solid #000;
    }

    .tab1 span {
        font-size: 14px;
        font-weight: bold;
    }

    .text-contract {
        font-size: 13px;

    }

    .data-tbl-lg {
        overflow: hidden;
        justify-content: flex-start;
        align-content: flex-start;
        text-align: justify;
        font-size: 12px;
        white-space: nowrap;
        height: 43px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding-bottom: 10px;
        text-decoration: underline black;
    }

    .data {
        font-size: 13px;
        width: 100%;
        border-bottom: none;
    }

    .data-center-center {
        vertical-align: middle;
        text-align: center;
        font-size: 13px;
    }
</style>

<?php
$option1 = ($contract->option == 1) ? "X" : "&nbsp;";
$option2 = ($contract->option == 2) ? "X" : "&nbsp;";
$option3 = ($contract->option == 3) ? "X" : "&nbsp;";

setlocale(LC_TIME, "spanish");
$mi_fecha = $contract->date_expiration;
$mi_fecha = str_replace("/", "-", $mi_fecha);
$Nueva_Fecha = date("d-m-Y", strtotime($mi_fecha));
$fecha_vencimiento = strftime("%A, %d de %B de %Y", strtotime($Nueva_Fecha));
//ejemplo devuelve: lunes, 16 de abril de 2022

$mi_fecha2 = $contract->date_admission;
$mi_fecha2 = str_replace("/", "-", $mi_fecha2);
$Nueva_Fecha2 = date("d-m-Y", strtotime($mi_fecha2));
$fecha_ingreso = strftime(" %d de %B de %Y", strtotime($Nueva_Fecha2));

$to_day = date('Y-m-d');
$date1 = new DateTime($contract->date_admission);
$date2 = new DateTime($contract->create_contract);
$diff = $date1->diff($date2);
$aaaa = ($diff->y > 0) ? $diff->y . " años " : "";
$mm = ($diff->m > 0) ? $diff->m . " meses " : "";
$dd = ($diff->d > 0) ? $diff->d . " dias" : "";
$a = ($diff->y > 0 && $diff->m > 0) ? ", " : "";
$b = ($diff->m > 0 && $diff->d > 0) ? " y " : "";
$tiempo = $aaaa . $a . $mm . $b . $dd;
?>
<!-- 754 -- 1
753 -- 2 -->
<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="img">
                <img src="./images/inval.png" alt="Walworth">
            </div>
        </div>
    </page_header>
    <div style="width: 100%;border: 1px solid #000;">
        <div style="text-decoration: underline;margin-right:12px;text-align:right;">
            <h4>AVISO DE TERMINACIÓN DE CONTRATO </h4>
        </div>
        <div class="emp" style="margin-left: 25px;margin-top: 5px;padding-bottom:10px;">
            <h5>INDUSTRIAL DE VALVULAS, S.A. DE C.V. </h5>
        </div>
        <div style="width: 96%;margin-left:15px;padding-bottom:80px;">
            <table class="tab1" cellpadding="0" cellspacing="0" <?= (strlen($contract->deptoManager) <= 25) ? "style='margin-bottom:30px;'" : ''; ?>>
                <tbody>
                    <tr>
                        <td class="data-center-center" style="width:5%; border-bottom:1px solid transparent;">DE:</td>
                        <td class="data-center-center" style="width:45%;">ADMINISTRACION DE PERSONAL</td>
                        <td class="data-center-center" style="width:8%; border-bottom:1px solid transparent;">FECHA:</td>
                        <td class="data-center-center" style="width:42%;"><?= date('d-m-Y', strtotime($contract->create_contract)); ?></td>
                    </tr>
                    <tr>
                        <td class="data-center-center" style="width:5%;border-bottom:1px solid transparent;">A: </td>
                        <td class="data-center-center" style="width:45%;"><?= $contract->manager ?></td>
                        <td class="data-center-center" style="width:8%;border-bottom:1px solid transparent;">DEPTO:</td>
                        <td class="data-center-center" style="width:42%;"><?= $contract->deptoManager ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-contract">
                <p <?= ($contract->option != 2 && strlen($contract->usuario) <= 34) ? "style='margin-bottom:27px;'" : ""; ?>>
                    <?php if ($contract->option == 2) { ?>
                        El próximo día <span style="text-decoration: underline;color:red;"><?= utf8_encode($fecha_vencimiento); ?></span>
                        termina el contrato de trabajo que por <span style="text-decoration: underline"><?= $contract->type_contract; ?></span>
                    <?php } else { ?>
                        Se genero un contrato de tipo <span style="text-decoration: underline"><?= $contract->type_contract; ?></span>
                    <?php } ?>
                    , que se celebró con el Señor (a) <span style="text-decoration: underline"><?= $contract->usuario; ?></span>
                </p>
                <p>Quien ingresó a la empresa en fecha &nbsp;<span style="text-decoration: underline;"><?= $fecha_ingreso ?>.</span>
                    <?php if ($contract->date_reing != '0000-00-00' && $contract->date_reing != null) { ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        REING. <span style="text-decoration: underline;"><?= date('d-m-Y', strtotime($contract->date_reing)); ?></span>
                    <?php } ?>
                </p>
                <div style="margin-bottom: 20px;"></div>
                <table class="tab1" style="margin-top: -20px;">
                    <tbody>
                        <tr>
                            <td class="" style="padding: 1px;font-size:13px;border-bottom:1px solid transparent;text-align:left;">Le agradeceremos informarnos a la brevedad posible.</td>
                        </tr>
                        <tr>
                            <td class="" style="padding: 1px;font-size:13px;border-bottom:1px solid transparent;text-align:left;">...si como resultado de la evaluación del desempeño deberemos:</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table class="tab4">
                <tbody>
                    <tr>
                        <td style="width:20%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                        <td class="" style="width:50%;padding: 4px;font-size:13px;">( <b><?= $option1 ?> </b> ) Formular contrato por Tiempo Indeterminado (Planta)</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                        <td class="" style="width:50%;padding: 4px;font-size:13px;">( <b><?= $option2 ?></b> ) Formular contrato por tiempo Determinado u Obra Determinada<span style="text-decoration: underline;"><?= $contract->typeContractOption; ?>.</span></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                        <td class="" style="width:50%;padding: 4px;font-size:13px;">( <b><?= $option3 ?></b> ) Proceder a dar de baja a la persona mencionada.</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-contract">
                <p>Datos extraídos del expediente:</p>
                <table class="tab1">
                    <tbody>
                        <tr>
                            <td class="" style="width:100px;padding: 1px;font-size:13px;border-bottom:1px solid transparent;text-align:right;">Puesto actual:</td>
                            <td class="" style="width:82%;padding: 1px;font-size:13px;"><b><?= $contract->deptoUser ?></b></td>
                        </tr>
                        <tr>
                            <td class="" style="width:100px;padding: 1px;font-size:13px;border-bottom:1px solid transparent;text-align:right;">Antiguedad:</td>
                            <td class="" style="width:75%;padding: 1px;font-size:13px;"><?= $tiempo; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-contract">
                <table class="tab1">
                    <tbody>
                        <tr>
                            <td class="data">En caso de que la decisión sea baja, es indispensable señalar a continuación las causas:</td>
                        </tr>
                        <tr>
                            <td class="data data-tbl-lg"><?= $contract->cause_of_termination ?></td>
                        </tr>
                        <tr>
                            <td class="data">Observaciones:</td>
                        </tr>
                        <tr>
                            <td class="data data-tbl-lg"><?= $contract->observations; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table style="width: 100%;border: none">
                <tbody>
                    <?php if ($contract->type_of_employee == 1 && $contract->option == 1 && $contract->direct_authorization != 0) { ?>
                            <tr>
                                <td style="width: 50%;text-align:center;">
                                    <h5 style=" font-weight: normal;">AUTORIZADO POR</h5>
                                </td>
                                <td style="width: 50%;text-align:center;">
                                    <h5 style=" font-weight: normal;"><?php echo $direc = ($contract->direct_authorization == 1) ? "ACEPTADO" : "RECHAZADO"?> POR DIRECCION</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5 style='margin-bottom: -15px;text-align:center;'> <?= $contract->manager; ?> </h5>
                                </td>
                                <td>
                                    <h5 style='margin-bottom: -15px;text-align:center;'> <?= $contract->name_director; ?> </h5>
                                </td>
                            </tr>
                    <?php } else { ?>
                        <tr>
                            <td style="width: 100%;text-align:center;">
                                <h5 style=" font-weight: normal;">AUTORIZADO POR</h5>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 100%;text-align:center;">
                                <h5 style='margin-bottom: -15px;'><?= $contract->manager ?></h5>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <page_footer>
        <div style="text-align: right;">
            <img src="./images/inval-ico.png" alt="">
            <span>FAP-18 REV.ORIGINAL</span>
        </div>
    </page_footer>
</page>