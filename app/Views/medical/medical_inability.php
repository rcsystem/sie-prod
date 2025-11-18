<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Servicio Médico
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<style>
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Incapacidades Médicas</h1>
                    <!-- <h5>Desarrollo AQUI</h5> -->
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
                    <h3 class="card-title">Permiso de Incapacidad Médica</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="permiso_medico" method="post">
                            <div class="row">
                                <input type="hidden" name="id_user" id="id_user">
                                <div class="form-group col-md-4">
                                    <label for="nomina">Numero de Empleado:</label>
                                    <input type="number" min="1" name="nomina" id="nomina" class="form-control">
                                    <div class="text-danger" id="error_nomina"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nombre">Nombre de Empleado:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="puesto">Puesto:</label>
                                    <input type="text" name="puesto" id="puesto" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="depto">Departamento:</label>
                                    <input type="text" name="depto" id="depto" class="form-control" readonly>
                                    <input type="hidden" name="id_depto" id="id_depto">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="c_costos">Centro de Costos:</label>
                                    <input type="text" name="c_costos" id="c_costos" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="tipo_empleado">Tipo de Empleado:</label>
                                    <input type="text" name="tipo_empleado" id="tipo_empleado" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="turno">Turno:</label>
                                    <select name="turno" id="turno" class="form-control" onchange="limpiarError(this)">
                                    </select>
                                    <div class="text-danger" id="error_turno"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="tipo_permiso">Tipo de Permiso:</label>
                                    <select name="tipo_permiso" id="tipo_permiso" class="form-control">
                                        <option value="">Opciones...</option>
                                        <option value="1">Salida a Cuenta de Vacaciones</option>
                                        <option value="2">Permiso Otorgado por la Empresa</option>
                                        <!-- <option value="3">Pago de Tiempo</option> -->
                                        <option value="4">Falta Justificada</option>
                                        <!-- <option value="5">Home Office</option> -->
                                        <!-- <option value="6">A Cuenta de Vacaciones</option> -->
                                    </select>
                                    <div class="text-danger" id="error_tipo_permiso"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="motivo">Motivo:</label>
                                    <select name="motivo" id="motivo" class="form-control" onchange="limpiarError(this)">
                                        <option value="">Opciones...</option>
                                        <option value="Enfermedad general">Enfermedad general</option>
                                        <option value="Accidente de trabajo">Accidente de trabajo</option>
                                        <option value="Accidente de trayecto ">Accidente de trayecto </option>
                                        <option value="Enfermedad respiratoria">Enfermedad respiratoria</option>
                                        <option value="Covid">Covid</option>
                                        <option value="Influenza confirmado">Influenza confirmado</option>
                                        
                                    </select>
                                    <div class="text-danger" id="error_motivo"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                                        <p style="margin-bottom: -3px;"><label>Goce de Sueldo</label></p>
                                        <label id="goce_sueldo_si" class="btn btn-outline-primary">
                                            <input type="radio" id="sueldo_si" class="" value=""> SI
                                        </label>
                                        <label id="goce_sueldo_no" class="btn btn-outline-primary">
                                            <input type="radio" id="sueldo_no" class="" value=""> No
                                        </label>
                                        <div id="error_sueldo" name="error_sueldo" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="div_entrada" class="row"></div>
                            <div id="div_salida" class="row"></div>
                            <div id="div_inasistencia" class="row"></div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="sistemas">Sistema:</label>
                                    <input list="browsers" id="sistemas" name="sistemas" class="form-control">
                                    <div class="text-danger" id="error_sistemas"></div>
                                    <datalist id="browsers">
                                        <option value="CARDIOVASCULAR"></option>
                                        <option value="DERMATOLÓGICO"></option>
                                        <option value="ENDOCRINOLÓGICO"></option>
                                        <option value="GASTROINTESTINAL"></option>
                                        <option value="GENITOURINARIO"></option>
                                        <option value="GINECO-OBSTÉTRICO"></option>
                                        <option value="INMUNOLÓGICO"></option>
                                        <option value="MUSCULOESQUELÉTICO"></option>
                                        <option value="NEUROLÓGICO"></option>
                                        <option value="ODONTOLÓGICO"></option>
                                        <option value="OFTALMOLÓGICO"></option>
                                        <option value="PSICOLÓGICO"></option>
                                        <option value="RESPIRATORIO"></option>
                                        <option value="OTRO"></option>
                                        <option value="ÓRGANO DE LOS SENTIDOS"></option>
                                        <option value="NA"></option>
                                        <option value="PSIQUIÁTRICO"></option>
                                    </datalist>
                                </div>
                                <div class="form-group col-md-4" id="div_otro_sistema"></div>
                                <div class="form-group col-md-12">
                                    <label for="diagnostico">Diagnostico:</label>
                                    <textarea name="diagnostico" id="diagnostico" class="form-control" style="height:10rem!important;" onchange="limpiarError(this)"></textarea>
                                    <div class="text-danger" id="error_diagnostico"></div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="observaciones">Observaciones para Permiso:</label>
                                    <textarea name="observaciones" id="observaciones" class="form-control" onchange="limpiarError(this)"></textarea>
                                    <div class="text-danger" id="error_observaciones"></div>
                                </div>
                            </div>
                            <div class="row">
                                <button type="submit" id="btn_permiso_medico" class="btn btn-guardar btn-lg">Generar</button>
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
<script src="<?= base_url() ?>/public/plugins/flatpickr/flatpickr.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/idioma/es.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>/public/js/medical/medical_inability_v1.js"></script>
<?= $this->endSection() ?>