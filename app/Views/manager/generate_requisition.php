<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Generar Requisición
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Generar Requisición.</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Generar</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Requisiciones</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="w-100 text-center p-0 mt-3 mb-2">
                                <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                                    <form id="msform" method="post">
                                        <!-- progressbar -->
                                        <ul id="progressbar">
                                            <li class="active" id="account"><strong>Datos Internos</strong></li>
                                            <li id="personal"><strong>Salarios</strong></li>
                                            <li id="payment"><strong>Generales</strong></li>
                                            <li id="laboral"><strong>Jornada Laboral</strong></li>
                                            <li id="conocimiento"><strong>Conocimientos</strong></li>
                                            <li id="competencia"><strong>Competencias</strong></li>
                                            <li id="confirm"><strong>Actividades</strong></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div> <br> <!-- fieldsets -->
                                        <fieldset id="fieldset_inicial">
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Datos Internos:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 1 - 7</h2>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="empresa_solicitante">Empresa Solicitante:</label>
                                                            <select name="empresa_solicitante" id="empresa_solicitante" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <?php foreach ($company as $key => $value) { ?>
                                                                    <option value="<?= $value['name_company'] ?>"><?= $value["name_company"] ?></otion>
                                                                    <?php    } ?>
                                                            </select>
                                                            <div id="error_empresa_solicitante" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tipo_personal">Tipo de personal</label>
                                                            <select name="tipo_personal" id="tipo_personal" class="form-control">
                                                                <option value="">Selecciona una opción...</option>
                                                                <?php foreach ($personnel_type as $key => $value) { ?>
                                                                    <option value="<?= $value['personnel_type'] ?>"><?= $value["personnel_type"] ?></otion>
                                                                    <?php    } ?>
                                                            </select>
                                                            <div id="error_tipo_personal" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="puestos" class="form-group">
                                                            <label for="puesto_solicitado">Puesto Solicitado:</label>
                                                            <select name="puesto_solicitado" id="puesto_solicitado" class="form-control">
                                                                <option value="">Seleccionar...</option>
                                                            </select>
                                                        </div>
                                                        <div id="error_puesto_solicitado" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="personas_requeridas">Numero de personas requeridas:</label>
                                                            <input type="number" class="form-control" id="personas_requeridas" name="personas_requeridas" onkeypress="return validaNumericos(event)" min="1">
                                                            <div id="error_personas_requeridas" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="area_operativa">Área operativa:</label>
                                                            <select name="area_operativa" id="area_operativa" class="form-control rounded-0 select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" required>
                                                                    <option value=""></option>
                                                                <?php foreach($departament as $label => $opt){ ?>
                                                                    <optgroup label="<?php echo $label; ?>">
                                                                        <?php foreach ($opt as $id => $name){ ?>
                                                                        <option value="<?= $id ?>"><?= $name ?></option>
                                                                        <?php } ?>
                                                                    </optgroup>
                                                                    <?php } ?>

                                                            </select>
                                                        </div>
                                                        <div id="error_area_operativa" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="centro_costo">Centro de Costos:</label>
                                                            <input type="number" class="form-control" id="centro_costo" name="centro_costo" value="" onkeypress="return validaNumericos(event)" min="1">
                                                            <div id="error_centro_costo" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="grado_estudios">Último grado de estudios</label>
                                                            <select name="grado_estudios" id="grado_estudios" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <?php foreach ($level_of_study as $key => $value) { ?>
                                                                    <option value="<?= $value['level_of_study'] ?>"><?= $value["level_of_study"] ?></otion>
                                                                    <?php    } ?>
                                                            </select>
                                                            <div id="error_grado_estudios" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                   <div id="tipo_grado_estudios" class=""></div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="motivo">Motivo de la Requisición</label>
                                                            <select name="motivo" id="motivo" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <?php foreach ($reason_for_the_requisition as $key => $value) { ?>
                                                                    <option value="<?= $value['reason_for_the_requisition'] ?>"><?= $value["reason_for_the_requisition"] ?></otion>
                                                                    <?php    } ?>
                                                            </select>
                                                            <div id="error_motivo" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="jefe_inmediato">Jefe inmediato:</label>
                                                            <input type="text" class="form-control" id="jefe_inmediato" name="jefe_inmediato" required>
                                                            <div id="error_jefe_inmediato" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="remplazo">Nombre del Colaborador a reemplazar</label>
                                                            <input type="text" class="form-control" id="remplazo" name="remplazo">
                                                            <div id="error_remplazo" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="button" name="next" class="next action-button" value="Siguiente" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Salarios:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 2 - 7</h2>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="fieldlabels">De:</label>
                                                        <input type="text" id="salario_inicial" name="salario_inicial" class="salario" onclick="ValidateDecimalInputs(this)" onchange="MASK(this,this.value,'-$##,###,##0.00',1)" placeholder="Ejemplo: 10000.99" />
                                                        <div id="error_salario_inicial" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="fieldlabels">Hasta:</label>
                                                        <input type="text" id="salario_final" name="salario_final" class="salario" onclick="ValidateDecimalInputs(this)" onchange="MASK(this,this.value,'-$##,###,##0.00',1)" placeholder="Ejemplo: 10000.99" />
                                                        <div id="error_salario_final" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="fieldlabels">Cotización:</label>
                                                        <select name="cotizacion" id="cotizacion" class="form-control" required>
                                                            <option value="">Seleccionar una opción...</option>
                                                            <option value="Neto">Neto</option>
                                                            <option value="Bruto">Bruto</option>
                                                        </select>
                                                        <div id="error_cotizacion" class="text-danger"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="fieldlabels">Periodo: </label>
                                                        <select name="periodo" id="periodo" class="form-control" required>
                                                            <option value="">Seleccionar una opción...</option>
                                                            <option value="Mensual">Mensual</option>
                                                            <option value="Semanal">Semanal</option>
                                                        </select>
                                                        <div id="error_periodo" class="text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <input type="button" name="next" class="next2 action-button" value="Siguiente" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Regresar" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Datos Generales:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 3 - 7</h2>
                                                    </div>
                                                   <!--  <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="genero_requerido">Género requerido:</label>
                                                            <select name="genero_requerido" id="genero_requerido" class="form-control">
                                                                <option value="">Seleccionar una opción...</option>
                                                                <?php foreach ($gender as $key => $value) { ?>
                                                                    <option value="<?= $value['gender'] ?>"><?= $value["gender"] ?></otion>
                                                                    <?php    } ?>
                                                            </select>
                                                            <div id="error_genero_requerido" class="text-danger"></div>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="anios_experiencia">Años de Experiencia:</label>
                                                            <select name="anios_experiencia" id="anios_experiencia" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <option value="1">1 año</option>
                                                                <option value="2">2 años</option>
                                                                <option value="3">3 años</option>
                                                                <option value="4">4 años</option>
                                                                <option value="5">5 años</option>
                                                                <option value="Más de 5">Más de 5 años</option>
                                                            </select>
                                                            <div id="error_anios_experiencia" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                   <!--  <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="estado_civil">Estado Civil:</label>
                                                            <select name="estado_civil" id="estado_civil" class="form-control">
                                                                <option value="">Seleccionar una opción...</option>
                                                                <?php foreach ($civil_status as $key => $value) { ?>
                                                                    <option value="<?= $value['civil_status'] ?>"><?= $value["civil_status"] ?></otion>
                                                                    <?php    } ?>
                                                            </select>
                                                            <div id="error_estado_civil" class="text-danger"></div>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="rolar_turnos">Rolar Turnos:</label>
                                                            <select name="rolar_turnos" id="rolar_turnos" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <option value="Si">Si</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                            <div id="error_rolar_turnos" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="edad_minima">Edad Mánima:</label>
                                                            <input type="number" class="form-control" id="edad_minima" name="edad_minima" onkeypress="return validaNumericos(event)" min="1">
                                                            <div id="error_edad_minima" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="edad_maxima">Edad Máxima:</label>
                                                            <input type="number" class="form-control" id="edad_maxima" name="edad_maxima" onkeypress="return validaNumericos(event)" min="1">
                                                            <div id="error_edad_maxima" class="request-error text-danger"></div>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="trato_clientes">Trato con Clientes o Proveedores:</label>
                                                            <select name="trato_clientes" id="trato_clientes" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <option value="Si">Si</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                            <div id="error_trato_clientes" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="manejo_personal">Manejo de Personal</label>
                                                            <select name="manejo_personal" id="manejo_personal" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <option value="Si">Si</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                            <div id="error_manejo_personal" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="licencia">Licencia de Conducir</label>
                                                            <select name="licencia" id="licencia" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <option value="Estatal">Estatal</option>
                                                                <option value="Federal">Federal</option>
                                                                <option value="Particular">Particular</option>
                                                                <option value="Otra">Otra</option>
                                                            </select>
                                                            <div id="error_licencia" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <br>
                                            <input type="button" name="next" class="next3 action-button" value="Siguiente" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Jornada Laboral:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 4 - 7</h2>
                                                    </div>                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="horario_inicial">Horario Inicial:</label>
                                                            <input type="time" class="form-control" id="horario_inicial" name="horario_inicial">
                                                            <div id="error_horario_inicial" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="horario_final">Horario Final:</label>
                                                            <input type="time" class="form-control" id="horario_final" name="horario_final">
                                                            <div id="error_horario_final" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="jornada">Jornada de Trabajo</label>
                                                            <select name="jornada" id="jornada" class="form-control" required>
                                                                <option value="">Selecciona una opción...</option>
                                                                <option value="Lunes a Viernes">Lunes a Viernes</option>
                                                                <option value="Lunes a Sabado">Lunes a Sabado</option>
                                                            </select>
                                                            <div id="error_jornada" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="button" name="next" class="next4 action-button" value="Siguiente" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Conocimientos:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 5 - 7</h2>
                                                    </div>
                                                
                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="primer_conocimiento">Primer Conocimiento:</label>
                                                            <input type="text" class="form-control" id="primer_conocimiento" name="primer_conocimiento" placeholder="Máximo 100 caracteres">
                                                            <div id="error_primer_conocimiento" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="segundo_conocimiento">Segundo Conocimiento:</label>
                                                            <input type="text" class="form-control" id="segundo_conocimiento" name="segundo_conocimiento" placeholder="Máximo 100 caracteres">
                                                            <div id="error_segundo_conocimiento" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tercer_conocimiento">Tercero Conocimiento:</label>
                                                            <input type="text" class="form-control" id="tercer_conocimiento" name="tercer_conocimiento" placeholder="Máximo 100 caracteres">
                                                            <div id="error_tercer_conocimiento" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="cuarto_conocimiento">Cuarto Conocimiento:</label>
                                                            <input type="text" class="form-control" id="cuarto_conocimiento" name="cuarto_conocimiento" placeholder="Máximo 100 caracteres">
                                                            <div id="error_cuarto_conocimiento" class="request-error text-danger"></div>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="quinto_conocimiento">Quinto Conocimiento:</label>
                                                            <input type="text" class="form-control" id="quinto_conocimiento" name="quinto_conocimiento" placeholder="Máximo 100 caracteres">
                                                            <div id="error_quinto_conocimiento" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                </div>    
                                            </div> 
                                            <input type="button" name="next" class="next5 action-button" value="Siguiente" /> 
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Competencias:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 6 - 7</h2>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="primer_competencia">Primer Competencia:</label>
                                                            <input type="text" class="form-control" id="primer_competencia" name="primer_competencia" placeholder="Máximo 100 caracteres">
                                                            <div id="error_primer_competencia" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="segunda_competencia">Segundo Competencia:</label>
                                                            <input type="text" class="form-control" id="segunda_competencia" name="segunda_competencia" placeholder="Máximo 100 caracteres">
                                                            <div id="error_segunda_competencia" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tercer_competencia">Tercero Competencia:</label>
                                                            <input type="text" class="form-control" id="tercer_competencia" name="tercer_competencia" placeholder="Máximo 100 caracteres">
                                                            <div id="error_tercer_competencia" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="cuarta_competencia">Cuarto Competencia:</label>
                                                            <input type="text" class="form-control" id="cuarta_competencia" name="cuarta_competencia" placeholder="Máximo 100 caracteres">
                                                            <div id="error_cuarta_competencia" class="request-error text-danger"></div>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="quinta_competencia">Quinto Competencia:</label>
                                                            <input type="text" class="form-control" id="quinta_competencia" name="quinta_competencia" placeholder="Máximo 100 caracteres">
                                                            <div id="error_quinta_competencia" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                                 <input type="button" name="next" class="next6 action-button" value="Siguiente" /> 
                                                 <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>
                                        <fieldset id="fieldset_final">
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Actividades:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 7 - 7</h2>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="primer_actividad">Primer Actividad</label>
                                                            <input type="text" class="form-control" id="primer_actividad" name="primer_actividad" placeholder="Máximo 100 caracteres">
                                                            <div id="error_primer_actividad" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="segunda_actividad">Segundo Actividad</label>
                                                            <input type="text" class="form-control" id="segunda_actividad" name="segunda_actividad" placeholder="Máximo 100 caracteres">
                                                            <div id="error_segunda_actividad" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tercer_actividad">Tercer Actividad</label>
                                                            <input type="text" class="form-control" id="tercer_actividad" name="tercer_actividad" placeholder="Máximo 100 caracteres">
                                                            <div id="error_tercer_actividad" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="cuarta_actividad">Cuarta Actividad</label>
                                                            <input type="text" class="form-control" id="cuarta_actividad" name="cuarta_actividad" placeholder="Máximo 100 caracteres">
                                                            <div id="error_cuarta_actividad" class="request-error text-danger"></div>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="quinta_actividad">Quinta Actividad</label>
                                                            <input type="text" class="form-control" id="quinta_actividad" name="quinta_actividad" placeholder="Máximo 100 caracteres">
                                                            <div id="error_quinta_actividad" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <input type="submit" id="genera_request" name="next" class="nex7 action-button" value="Generar" /> 
                                                 <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="card-footer">
                        <a href="#">Generar Requisición</a>
                    </div>
                </div>
            </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/requisitions/generates_v5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>

<?= $this->endSection() ?>