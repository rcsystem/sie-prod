<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Servicio Médico
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<style>
    .switch {
        position: absolute;
        top: 50%;
        width: 100px;
        height: 30px;
        text-align: center;
        margin: -20px 0 0 0;
        background: #00bc9c;
        transition: all 0.2s ease;
        border-radius: 25px;
    }

    .switch span {
        position: absolute;
        width: 20px;
        height: 4px;
        top: 50%;
        left: 45%;
        margin: -2px 0px 0px -4px;
        background: #fff;
        display: block;
        transform: rotate(-45deg);
        transition: all 0.2s ease;
    }

    .switch span:after {
        content: "";
        display: block;
        position: absolute;
        width: 4px;
        height: 12px;
        /* left: 50%; */
        margin-top: -8px;
        background: #fff;
        transition: all 0.2s ease;
    }

    input[type=radio] {
        display: none;
    }

    .switch label {
        cursor: pointer;
        color: rgba(0, 0, 0, 0.4);
        width: 60px;
        line-height: 50px;
        transition: all 0.2s ease;
    }

    .lbl-yes {
        position: absolute;
        left: -8px;
        top: -10px;
        height: 20px;
    }

    .lbl-no {
        position: absolute;
        top: -10px;
        right: -6px;

    }

    .no:checked~.switch {
        background: #eb4f37;
    }

    .no:checked~.switch span:after {
        background: #fff;
        height: 20px;
        margin-top: -8px;
        margin-left: 8px;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Exámenes Periódicos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Servicio Médico</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Formulario de Exámen Periódico</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="form_examen_medico" method="post">
                            <div class="row">
                                <input type="hidden" id="id_user">
                                <div class="form-group col-md-2">
                                    <label for="nomina">Numero de Empleado:</label>
                                    <input type="number" min="1" name="nomina" id="nomina" class="form-control">
                                    <div class="text-danger" id="error_nomina"></div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="depto">Departamento:</label>
                                    <input type="text" name="depto" id="depto" class="form-control" readonly>
                                    <input type="hidden" name="id_depto" id="id_depto">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="puesto">Puesto:</label>
                                    <input type="text" name="puesto" id="puesto" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="genero">Genero:</label>
                                    <select name="genero" id="genero" class="form-control" readonly onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="FEMENINO">FEMENINO</option>
                                        <option value="MASCULINO">MASCULINO</option>
                                        <option value="INDEFINIDO">INDEFINIDO</option>
                                    </select>
                                    <div class="text-danger" id="error_genero"></div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="edad">Edad:</label>
                                    <input type="number" name="edad" id="edad" min="1" class="form-control" readonly onchange="validar()">
                                    <div class="text-danger" id="error_edad"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="escolaridad">Escolaridad:</label>
                                    <select class="form-control" id="escolaridad" name="escolaridad" readonly onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="PRIMARIA">PRIMARIA</option>
                                        <option value="SECUNDARIA">SECUNDARIA</option>
                                        <option value="BACHILLERATO GENERAL">BACHILLERATO GENERAL</option>
                                        <option value="BACHILLERATO TÉCNICO">BACHILLERATO TÉCNICO</option>
                                        <option value="LICENCIATURA">LICENCIATURA</option>
                                        <option value="INGENIERIA">INGENIERIA</option>
                                        <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                        <option value="MAESTRÍA">MAESTRÍA</option>
                                        <option value="DOCTORADO">DOCTORADO</option>
                                    </select>
                                    <div class="text-danger" id="error_escolaridad"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="estado_civil">Estado Civil:</label>
                                    <select name="estado_civil" id="estado_civil" class="form-control" readonly onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="SOLTERO">SOLTER@</option>
                                        <option value="CASADO">CASAD@</option>
                                        <option value="UNION LIBRE">UNION LIBRE</option>
                                        <option value="VIUDO">VIUDO@</option>
                                        <option value="OTRO">OTRO</option>
                                    </select>
                                    <div id="error_estado_civil" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="antiguedad_general">Antigüedad General:</label>
                                    <input type="text" name="antiguedad_general" id="antiguedad_general" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="tipo_empleado">Tipo de Empleado:</label>
                                    <input type="text" name="tipo_empleado" id="tipo_empleado" class="form-control" readonly>
                                    <div id="error_tipo_empleado" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="examen_ant">Último Examen Periódico :</label>
                                    <input type="date" name="examen_ant" class="form-control">
                                    </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3 row">
                                    <div style="width: auto;margin-right: 10px;">
                                        <label>Ejercicio: </label>
                                        <input type="hidden" name="ejercicio" class="radio" id="dato_1" value="0">
                                    </div>
                                    <div style="width:auto; padding-top: 35px;">
                                        <input type="radio" onclick="radioButton(1,1)" class="yes" id="yes_1" />
                                        <input type="radio" onclick="radioButton(1,0)" class="no" id="no_1" checked />
                                        <div class="switch">
                                            <label class="lbl-yes" id="lbl_yes_1" for="yes_1">SI</label>
                                            <label class="lbl-no" id="lbl_no_1" for="no_1" style="color: #fff;">NO</label>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 row">
                                    <div style="width: auto;margin-right: 10px;">
                                        <label>Tabaquismo: </label>
                                        <input type="hidden" name="tabaquismo" class="radio" id="dato_2" value="0">
                                    </div>
                                    <div style="width:auto; padding-top: 35px;">
                                        <input type="radio" onclick="radioButton(2,1)" class="yes" id="yes_2" />
                                        <input type="radio" onclick="radioButton(2,0)" class="no" id="no_2" checked />
                                        <div class="switch">
                                            <label class="lbl-yes" id="lbl_yes_2" for="yes_2">SI</label>
                                            <label class="lbl-no" id="lbl_no_2" for="no_2" style="color: #fff;">NO</label>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 row">
                                    <div style="width: auto;margin-right: 10px;">
                                        <label>Alcoholismo: </label>
                                        <input type="hidden" name="alcoholismo" class="radio" id="dato_3" value="0">
                                    </div>
                                    <div style="width:auto; padding-top: 35px;">
                                        <input type="radio" onclick="radioButton(3,1)" class="yes" id="yes_3" />
                                        <input type="radio" onclick="radioButton(3,0)" class="no" id="no_3" checked />
                                        <div class="switch">
                                            <label class="lbl-yes" id="lbl_yes_3" for="yes_3">SI</label>
                                            <label class="lbl-no" id="lbl_no_3" for="no_3" style="color: #fff;">NO</label>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 row">
                                    <div style="width: auto;margin-right: 10px;">
                                        <label>Toxicomanías: </label>
                                        <input type="hidden" name="toxicomanias" class="radio" id="dato_4" value="0">
                                    </div>
                                    <div style="width:auto; padding-top: 35px;">
                                        <input type="radio" onclick="radioButton(4,1)" class="yes" id="yes_4" />
                                        <input type="radio" onclick="radioButton(4,0)" class="no" id="no_4" checked />
                                        <div class="switch">
                                            <label class="lbl-yes" id="lbl_yes_4" for="yes_4">SI</label>
                                            <label class="lbl-no" id="lbl_no_4" for="no_4" style="color: #fff;">NO</label>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="grado_salud">Grado de Salud:</label>
                                    <select name="grado_salud" id="grado_salud" class="form-control" onchange="validar()">
                                        <option value="">opcion</option>
                                        <option value="0">GRADO 0</option>
                                        <option value="1">GRADO I</option>
                                        <option value="2">GRADO II</option>
                                        <option value="3">GRADO III</option>
                                        <option value="4">GRADO IV</option>
                                    </select>
                                    <div class="text-danger" id="error_grado_salud"></div>
                                </div>
                                <div class="col-md-9" style="text-align: center;">
                                    <label>Motivo Relacionado:</label>
                                    <input type="hidden" name="motivo_comun" id="motivo_comun">
                                    <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" onclick="motivoComun(1)"> ESTRÉS LABORAL
                                        </label>
                                        <label class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" onclick="motivoComun(2)"> ESTRÉS PERSONAL
                                        </label>
                                        <label class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" onclick="motivoComun(3)"> EGRONOMÍA
                                        </label>
                                        <label class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" onclick="motivoComun(4)"> ENFERMEDAD DE TRABAJO
                                        </label>
                                    </div>
                                    <div class="text-danger" id="error_motivo_comun"></div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 1rem;">
                                <div class="form-group col-md-4">
                                    <label for="imc">IMC:</label>
                                    <select name="imc" id="imc" class="form-control" onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="BAJO PESO">BAJO PESO</option>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="SOBREPESO">SOBREPESO</option>
                                        <option value="OBESIDAD GI">OBESIDAD GI</option>
                                        <option value="OBESIDAD GII">OBESIDAD GII</option>
                                        <option value="OBESIDAD GIII">OBESIDAD GIII</option>
                                    </select>
                                    <div id="error_imc" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="has">HAS:</label>
                                    <select name="has" id="has" class="form-control" onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="OPTIMA">OPTIMA</option>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="NORMAL ALTA">NORMAL ALTA</option>
                                        <option value="HIPERTENSION GI">HIPERTENSION GI</option>
                                        <option value="HIPERTENSION GII">HIPERTENSION GII</option>
                                        <option value="HIPERTENSION GIII">HIPERTENSION GIII</option>
                                        <option value="HIPERTENSION SISTOLICA AISLADA">HIPERTENSION SISTOLICA AISLADA</option>
                                    </select>
                                    <div id="error_has" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="dm">DM:</label>
                                    <select name="dm" id="dm" class="form-control" onchange="validar()">
                                        <option value="">Opciones...</option>
                                        <option value="NO PORTADOR">NO PORTADOR</option>
                                        <option value="DM CONTROLADA">DM CONTROLADA</option>
                                        <option value="DM NO CONTROLADA">DM NO CONTROLADA</option>
                                        <option value="GLUCOSA ELEVADA EN AYUNO">GLUCOSA ELEVADA EN AYUNO</option>
                                    </select>
                                    <div id="error_dm" class="text-danger"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-10" id="div_error_visual"></div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-success" id="btn_add_visual">
                                        <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Agregar
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-secondary" id="btn_remove_visual" disabled>
                                        <i class="fas fa-minus-circle"></i>&nbsp;&nbsp;Retirar
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="visual_1">Aguideza Visual:</label>
                                    <select name="visual_[]" id="visual_1" class="form-control" onchange="validarVisual(1)">
                                        <option value="">Opciones...</option>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="DISM AGUDEZA VISUAL LEJANA">DISM AGUDEZA VISUAL LEJANA</option>
                                        <option value="DISM AGUDEZA VISUAL CERCANA">DISM AGUDEZA VISUAL CERCANA</option>
                                        <option value="DALTONISMO">DALTONISMO</option>
                                        <option value="CAMPIMETRIA ANORMAL">CAMPIMETRIA ANORMAL</option>
                                    </select>
                                    <div id="error_visual_1" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3 extra" id="div_visual_2" style="display: none;">
                                    <label for="visual_2">Aguideza Visual:</label>
                                    <select name="visual_[]" id="visual_2" class="form-control" onchange="validarVisual(2)">
                                        <option value="">Opciones...</option>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="DISM AGUDEZA VISUAL LEJANA">DISM AGUDEZA VISUAL LEJANA</option>
                                        <option value="DISM AGUDEZA VISUAL CERCANA">DISM AGUDEZA VISUAL CERCANA</option>
                                        <option value="DALTONISMO">DALTONISMO</option>
                                        <option value="CAMPIMETRIA ANORMAL">CAMPIMETRIA ANORMAL</option>
                                    </select>
                                    <div id="error_visual_2" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3 extra" id="div_visual_3" style="display: none;">
                                    <label for="visual_3">Aguideza Visual:</label>
                                    <select name="visual_[]" id="visual_3" class="form-control" onchange="validarVisual(3)">
                                        <option value="">Opciones...</option>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="DISM AGUDEZA VISUAL LEJANA">DISM AGUDEZA VISUAL LEJANA</option>
                                        <option value="DISM AGUDEZA VISUAL CERCANA">DISM AGUDEZA VISUAL CERCANA</option>
                                        <option value="DALTONISMO">DALTONISMO</option>
                                        <option value="CAMPIMETRIA ANORMAL">CAMPIMETRIA ANORMAL</option>
                                    </select>
                                    <div id="error_visual_3" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3 extra" id="div_visual_4" style="display: none;">
                                    <label for="visual_4">Aguideza Visual:</label>
                                    <select name="visual_[]" id="visual_4" class="form-control" onchange="validarVisual(4)">
                                        <option value="">Opciones...</option>
                                        <option value="NORMAL">NORMAL</option>
                                        <option value="DISM AGUDEZA VISUAL LEJANA">DISM AGUDEZA VISUAL LEJANA</option>
                                        <option value="DISM AGUDEZA VISUAL CERCANA">DISM AGUDEZA VISUAL CERCANA</option>
                                        <option value="DALTONISMO">DALTONISMO</option>
                                        <option value="CAMPIMETRIA ANORMAL">CAMPIMETRIA ANORMAL</option>
                                    </select>
                                    <div id="error_visual_4" class="text-danger"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-10" id="div_error_dx"></div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-success" id="btn_add_dx">
                                        <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Agregar
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-secondary" id="btn_remove_dx" disabled>
                                        <i class="fas fa-minus-circle"></i>&nbsp;&nbsp;Retirar
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-7">
                                    <label for="dx_1">DX:</label>
                                    <input type="text" name="dx_[]" id="dx_1" class="form-control" onchange="validarDX(1)">
                                    <div class="text-danger" id="error_dx_1"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sistema">Aparatos y Sistemas:</label>
                                    <select name="sistema_[]" id="sistema_1" class="form-control" onchange="validarDX(1)">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($system as $key) { ?>
                                            <option value="<?php echo $key->id_system; ?>"><?php echo $key->system; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_sistema_1"></div>
                                </div>
                            </div>
                            <div class="row extra" id="div_dx_2" style="display:none;">
                                <div class="form-group col-md-7">
                                    <label for="dx_2">DX 2:</label>
                                    <input type="text" name="dx_[]" id="dx_2" class="form-control" onchange="validarDX(2)">
                                    <div class="text-danger" id="error_dx_2"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sistema">Aparatos y Sistemas:</label>
                                    <select name="sistema_[]" id="sistema_2" class="form-control" onchange="validarDX(2)">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($system as $key) { ?>
                                            <option value="<?php echo $key->id_system; ?>"><?php echo $key->system; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_sistema_2"></div>
                                </div>
                            </div>
                            <div class="row extra" id="div_dx_3" style="display:none;">
                                <div class="form-group col-md-7">
                                    <label for="dx_3">DX 3:</label>
                                    <input type="text" name="dx_[]" id="dx_3" class="form-control" onchange="validarDX(3)">
                                    <div class="text-danger" id="error_dx_3"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sistema">Aparatos y Sistemas:</label>
                                    <select name="sistema_[]" id="sistema_3" class="form-control" onchange="validarDX(3)">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($system as $key) { ?>
                                            <option value="<?php echo $key->id_system; ?>"><?php echo $key->system; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_sistema_3"></div>
                                </div>
                            </div>
                            <div class="row extra" id="div_dx_4" style="display:none;">
                                <div class="form-group col-md-7">
                                    <label for="dx_4">DX 4:</label>
                                    <input type="text" name="dx_[]" id="dx_4" class="form-control" onchange="validarDX(4)">
                                    <div class="text-danger" id="error_dx_4"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sistema">Aparatos y Sistemas:</label>
                                    <select name="sistema_[]" id="sistema_4" class="form-control" onchange="validarDX(4)">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($system as $key) { ?>
                                            <option value="<?php echo $key->id_system; ?>"><?php echo $key->system; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="text-danger" id="error_sistema_4"></div>
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit" id="btn_examen_medico" style="margin-top: 1rem;" class="btn btn-guardar btn-lg btn-block">Generar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Servicio Médico</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/medical/medical_exam.js"></script>
<?= $this->endSection() ?>