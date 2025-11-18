<style>
    body {
        font-family: 'Roboto','Arial', 'Helvetica', sans-serif;
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
        width: 210px;
        height: 38px;
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
        background-color: #b71c1c;
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

    .title_tab {
        width: 45%;
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
        background: #b71c1c;
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

    .img2 img {
        width: 200px;
        height: 80px;
        float: left;
        margin-right: auto;
        margin-left: 350px;
        margin-top: -117px;
    }

    /* ======== OPCIONAL - COLORES CORPORATIVOS ======== */
    .title_tab,
    .title_tab3 {
        background-color: #b71c1c;
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 7px;
        border: none;
        font-size: 14px;
    }

    /* ======== AJUSTES VISUALES ======== */
    tr:first-child td {
        border-top: 0.7px solid #555 !important;
    }

    tr:last-child td {
        border-bottom: 0.7px solid #555 !important;
    }

    tr td:first-child {
        border-left: 0.7px solid #555 !important;
    }

    tr td:last-child {
        border-right: 0.7px solid #555 !important;
    }

    /* Evita fugas visuales en PDF */
    .border_off {
        border-color: transparent !important;
    }
</style>


<!-- <page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'> -->
<!-- <page_header> -->
<div class="header">
    <div class="norma">
        <label for=""><i>FAT-01-4.3.2</i></label>
    </div>
    <div class="img">
        <img src="<?= base_url(); ?>/public/images/logo_Walworth.png" alt="Grupo Walworth">
        <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->fecha_creacion)); ?></label></span><br>
        <span>Hora de creación: <label for="" style="margin-left:40px"><?= date('H:i', strtotime($request->fecha_creacion)); ?></label></span>
    </div>
</div>
<!--  </page_header>
    <page_footer>
    </page_footer> -->

<div class="title">
    <h3>REQUISICIÓN DE PERSONAL</h3>
</div>
<table class="tab1">
    <tbody>
        <tr>
            <td style="width:70%"><span>Solicitante:
                </span><?= mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8") . ' ' . mb_convert_case($request->surname, MB_CASE_TITLE, "UTF-8"); ?>
            </td>
            <td style="width:30%; text-align:right;"><span>Folio:
                </span><?= mb_convert_case($request->id_folio, MB_CASE_TITLE, "UTF-8"); ?></td>
        </tr>
        <tr>
            <td style="width:70%;"><span>Departamento: </span><?= ucwords(strtolower($request->depto)); ?></td>
            <td style="width:30%; text-align:right;"><span>Estatus:
                </span><?= mb_convert_case($request->estatus, MB_CASE_TITLE, "UTF-8"); ?></td>
        </tr>
    </tbody>
</table>


<table class="tab2" style="margin-bottom:50px;">
    <thead>
        <tr>
            <th colspan="2" class="title_tab">PRINCIPAL</th>
            <th colspan="2" class="middle"></th>
            <th colspan="2" class="title_tab">DATOS GENERALES</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="left_text" style="margin-bottom:12px">Empresa solicitante</td>
            <td class="right_text"><?= $request->empresa_solicitante; ?></td>
            <td class="border_right" colspan="2" rowspan="11"></td>
            <td class="left_text">Cotización</td>
            <td class="right_text"><?= $request->cotizacion; ?></td>
        </tr>
        <tr>
            <td class="left_text">Centro de costos</td>
            <td class="right_text"><?= $request->centro_costos; ?></td>
            <td class="left_text">Periodo</td>
            <td class="right_text"><?= $request->periodo; ?></td>
        </tr>
        <?php if ($request->id_folio > 1372) { ?>
            <tr>
                <td class="left_text">Área operativa</td>
                <td class="right_text"><?= ucwords(mb_strtolower($request->area_operativas)); ?></td>
                <!--  <td class="left_text">Estado civil</td>
                <td class="right_text"><?php //$request->estado_civil; 
                                        ?></td> -->
                <td class="left_text">Rolar turno</td>
                <td class="right_text"><?= $request->rolar_turno; ?></td>
            </tr>
            <tr>
                <td class="left_text">Tipo de personal</td>
                <td class="right_text"><?= $request->tipo_de_personal; ?></td>
                <td class="left_text">Licencia de conducir</td>
                <td class="right_text"><?= $request->licencia_conducir; ?></td>
            </tr>
            <tr>
                <td class="left_text">Puesto solicitado</td>
                <td class="right_text"><?= $request->puesto_solicitado; ?></td>
                <td class="left_text">Años de experiencia</td>
                <td class="right_text"><?= $request->anios_experiencia; ?></td>
            </tr>
            <tr>
                <td class="left_text">Personas requeridas</td>
                <td class="right_text"><?= $request->personas_requeridas; ?></td>
                <td class="left_text">Trato con clientes/proveedores</td>
                <td class="right_text"><?= $request->trato_cli_prov; ?></td>

            </tr>
            <tr>
                <td class="left_text">Grado de estudios</td>
                <?php if ($request->tipo_estudios != "") { ?>
                    <td class="right_text"><?= $request->tipo_estudios; ?></td>
                <?php } else { ?>
                    <td class="right_text"><?= $request->grado_estudios; ?></td>
                <?php } ?>
                <td class="left_text">Manejo de personal</td>
                <td class="right_text"><?= $request->manejo_personal; ?></td>
            </tr>
            <tr>
                <td class="left_text">Motivo de la requisición</td>
                <td class="right_text"><?= $request->motivo_requisicion; ?></td>

            </tr>
            <tr>
                <td class="left_text">Jefe inmediato:</td>
                <td class="right_text"><?= $request->jefe_inmediato; ?></td>

            </tr>
            <tr>
                <td class="left_text">Colaborador a reemplazar:</td>
                <td class="right_text"><?= $request->colaborador_reemplazo; ?></td>

            </tr>

        <?php } else { ?>
            <tr>
                <td class="left_text">Área operativa</td>
                <td class="right_text"><?= ucwords(mb_strtolower($request->area_operativas)); ?></td>
                <td class="left_text">Género requerido</td>
                <td class="right_text"><?= $request->genero_requerido; ?></td>
            </tr>
            <tr>
                <td class="left_text">Tipo de personal</td>
                <td class="right_text"><?= $request->tipo_de_personal; ?></td>
                <td class="left_text">Estado civil</td>
                <td class="right_text"><?= $request->estado_civil; ?></td>
            </tr>
            <tr>
                <td class="left_text">Puesto solicitado</td>
                <td class="right_text"><?= $request->puesto_solicitado; ?></td>
                <td class="left_text">Edad mínima</td>
                <td class="right_text"><?= $request->edad_minima; ?></td>
            </tr>
            <tr>
                <td class="left_text">Personas requeridas</td>
                <td class="right_text"><?= $request->personas_requeridas; ?></td>
                <td class="left_text">Edad máxima</td>
                <td class="right_text"><?= $request->edad_maxima; ?></td>
            </tr>
            <tr>
                <td class="left_text">Grado de estudios</td>
                <?php if ($request->tipo_estudios != "") { ?>
                    <td class="right_text"><?= $request->tipo_estudios; ?></td>
                <?php } else { ?>
                    <td class="right_text"><?= $request->grado_estudios; ?></td>
                <?php } ?>
                <td class="left_text">Rolar turno</td>
                <td class="right_text"><?= $request->rolar_turno; ?></td>
            </tr>
            <tr>
                <td class="left_text">Motivo de la requisición</td>
                <td class="right_text"><?= $request->motivo_requisicion; ?></td>
                <td class="left_text">Licencia de conducir</td>
                <td class="right_text"><?= $request->licencia_conducir; ?></td>
            </tr>
            <tr>
                <td class="left_text">Jefe inmediato:</td>
                <td class="right_text"><?= $request->jefe_inmediato; ?></td>
                <td class="left_text">Años de experiencia</td>
                <td class="right_text"><?= $request->anios_experiencia; ?></td>
            </tr>
            <tr>
                <td class="left_text">Colaborador a reemplazar:</td>
                <td class="right_text"><?= $request->colaborador_reemplazo; ?></td>
                <td class="left_text">Trato con clientes/proveedores</td>
                <td class="right_text"><?= $request->trato_cli_prov; ?></td>
            </tr>
            <tr>
                <td class="border_off">&nbsp;</td>
                <td class="border_off">&nbsp;</td>
                <td class="left_text">Manejo de personal</td>
                <td class="right_text"><?= $request->manejo_personal; ?></td>
            </tr>
        <?php } ?>

    </tbody>
</table>
<?php if ($request->id_folio > 1372) { ?>
    <div class="img2">
        <img src="<?= base_url(); ?>/public/images/blanco.png">
    </div>
<?php } ?>
<table class="tab2">
    <thead>
        <tr>
            <th colspan="2" class="title_tab">SALARIO</th>
            <th colspan="2" class="middle"></th>
            <th colspan="2" class="title_tab">JORNADA LABORAL</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="left_text">Salario inicial</td>
            <td class="right_text"><?= $request->salario_inicial; ?></td>
            <td class="border_right" colspan="2" rowspan="3"></td>
            <td class="left_text">Jornada</td>
            <td class="right_text"><?= $request->jornada; ?></td>
        </tr>
        <tr>
            <td class="left_text">Salario final</td>
            <td class="right_text"><?= $request->salario_final; ?></td>
            <td class="left_text">Horario inicial</td>
            <td class="right_text"><?= date('h:i a', strtotime($request->horario_inicial)); ?></td>
        </tr>
        <tr>
            <td class="border_off">&nbsp;</td>
            <td class="border_off">&nbsp;</td>
            <td class="left_text">Horario final</td>
            <td class="right_text"><?= date('h:i a', strtotime($request->horario_final)); ?></td>
        </tr>
    </tbody>
</table>

<div style="page-break-after: always"></div>
<table class="tab3">
    <thead>
        <tr>
            <th colspan="2" class="title_tab3">PRINCIPALES CONOCIMIENTOS</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="sub">Primer conocimiento</td>
            <td class="descripcion"><?= $request->conocimiento_1; ?></td>
        </tr>
        <tr>
            <td class="sub">Segundo conocimiento</td>
            <td class="descripcion"><?= $request->conocimiento_2; ?></td>
        </tr>
        <tr>
            <td class="sub">Tercer conocimiento</td>
            <td class="descripcion"><?= $request->conocimiento_3; ?></td>
        </tr>
        <tr>
            <td class="sub">Cuarto conocimiento</td>
            <td class="descripcion"><?= $request->conocimiento_4; ?></td>
        </tr>
        <tr>
            <td class="sub">Quinto conocimiento</td>
            <td class="descripcion"><?= $request->conocimiento_5; ?></td>
        </tr>
    </tbody>
</table>

<table class="tab3">
    <thead>
        <tr>
            <th colspan="2" class="title_tab3">COMPETENCIAS</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="sub">Primer competencia</td>
            <td class="descripcion"><?= $request->competencia_1; ?></td>
        </tr>
        <tr>
            <td class="sub">Segunda competencia</td>
            <td class="descripcion"><?= $request->competencia_2; ?></td>
        </tr>
        <tr>
            <td class="sub">Tercer competencia</td>
            <td class="descripcion"><?= $request->competencia_3; ?></td>
        </tr>
        <tr>
            <td class="sub">Cuarta competencia</td>
            <td class="descripcion"><?= $request->competencia_4; ?></td>
        </tr>
        <tr>
            <td class="sub">Quinta competencia</td>
            <td class="descripcion"><?= $request->competencia_5; ?></td>
        </tr>
    </tbody>
</table>


<table class="tab3">
    <thead>
        <tr>
            <th colspan="2" class="title_tab3">PRINCIPALES ACTIVIDADES</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="sub">Primer actividad</td>
            <td class="descripcion"><?= $request->actividad_1; ?></td>
        </tr>
        <tr>
            <td class="sub">Segunda actividad</td>
            <td class="descripcion"><?= $request->actividad_2; ?></td>
        </tr>
        <tr>
            <td class="sub">Tercer actividad</td>
            <td class="descripcion"><?= $request->actividad_3; ?></td>
        </tr>
        <tr>
            <td class="sub">Cuarta actividad</td>
            <td class="descripcion"><?= $request->actividad_4; ?></td>
        </tr>
        <tr>
            <td class="sub">Quinta actividad</td>
            <td class="descripcion"><?= $request->actividad_5; ?></td>
        </tr>
    </tbody>
</table>

<!-- </page> -->