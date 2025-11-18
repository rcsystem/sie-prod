<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Permisos Especiales
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<!-- <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> -->
<!-- <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css"> -->
<style>
    .my-label-span {
        font-size: 13px;
        margin-left: 1rem;
        margin-top: 12px;
    }


    .bg-acceptable {
        color: #fff;
        background-color: #F65E0A;
        border-color: #F65E0A;
    }

    .toggle {
        position: relative;
        box-sizing: border-box;
        padding: inherit;
    }

    .toggle input[type="checkbox"] {
        position: absolute;
        left: 0;
        top: 0;
        z-index: 10;
        width: 56%;
        height: 100%;
        cursor: pointer;
        opacity: 0;
    }

    .toggle label {
        position: relative;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    .toggle label:before {
        content: '';
        width: 40px;
        height: 22px;
        background: #ccc;
        position: relative;
        display: inline-block;
        border-radius: 46px;
        box-sizing: border-box;
        transition: 0.2s ease-in;
    }

    .toggle label:after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        left: 2px;
        top: 2px;
        z-index: 2;
        background: #fff;
        box-sizing: border-box;
        transition: 0.2s ease-in;
    }

    .toggle input[type="checkbox"]:checked+label:before {
        background: #4BD865;
    }

    .toggle input[type="checkbox"]:checked+label:after {
        left: 19px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administrar Permisos Especiales</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Permisos & Vacaciones</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Alta de Permisos Especiales</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_alta_permiss" method="post" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-2" style="text-align: center;">
                                <label>Tipo de Permiso</label>
                                <input type="hidden" name="tipo_permiso" id="tipo_permiso">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-primary btn-opcion">
                                        <input type="radio" onclick="tipoPermiso(1)"> FESTIVO
                                    </label>
                                    <label class="btn btn-outline-primary btn-opcion">
                                        <input type="radio" onclick="tipoPermiso(2)"> TRAFICO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_tipo_permiso"></div>
                            </div>
                            <div class="form-group col-md-3" id="div_dia_permiso" style="display: none;">
                                <label for="dia_permiso">Selecciona Dia(s):</label>
                                <input type="date" class="form-control" id="dia_permiso" name="dia_permiso" style="background-color: white;" onchange="limpiarError(this)">
                                <div id="error_dia_permiso" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3" id="div_hora_entrada" style="display: none;">
                                <label for="hora_entrada">Hora de Entrada:</label>
                                <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" onchange="limpiarError(this)">
                                <div id="error_hora_entrada" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3" id="div_tiempo_permiso" style="display: none;">
                                <label for="tiempo_permiso">Tiempo de Permiso:</label>
                                <div class="input-group mb-3">
                                    <input type="number" id="horas" name="horas" min="0" class="form-control" onchange="limpiarTiempo(this)">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Hrs</span>
                                    </div>
                                    <input type="number" id="min" name="min" min="0" max="59" class="form-control" onchange="limpiarTiempo(this)">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Min</span>
                                    </div>
                                </div>
                                <div id="error_tiempo_permiso" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3 row" id="div_cbx_tipo_permiso" style="display: none;">
                                <label for="horas_permiso" style="width:100%;margin-bottom: -2px;">Tipo de Permisos:</label>
                                <div class="form-group col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="in_permis" id="in_permis" class="form-check-input chbx-opcion" style="width: 30px;;height: calc(2.25rem + 2px)" onclick="limpiarCbx(this)">
                                        <label class="my-label-span form-check-label" for="in_permis">
                                            <span class="float-right">ENTRADA</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="out_permis" id="out_permis" class="form-check-input chbx-opcion" style="width: 30px;;height: calc(2.25rem + 2px)" onclick="limpiarCbx(this)">
                                        <label class="my-label-span form-check-label" for="out_permis">
                                            <span class="float-right">SALIDA</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="absence_permis" id="absence_permis" class="form-check-input chbx-opcion" style="width: 30px;;height: calc(2.25rem + 2px)" onclick="limpiarCbx(this)">
                                        <label class="my-label-span form-check-label" for="absence_permis">
                                            <span class="float-right">INASISTENCIA</span>
                                        </label>
                                    </div>
                                </div>
                                <div id="error_cbx_tipo_permiso" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-4" id="div_motivo" style="display: none;">
                                <label for="motivo">Motivo:</label>
                                <input type="text" class="form-control" id="motivo" name="motivo" onchange="limpiarError(this)">
                                <div id="error_motivo" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-8" id="div_obs" style="display: none;">
                                <label for="obs">Predefinir las Observaciones:</label>
                                <textarea class="form-control" id="obs" name="obs" cols="30" rows="3" onchange="limpiarError(this)"></textarea>
                                <div id="error_obs" class="text-danger"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <button id="btn_alta_permiss" type="submit" class="btn btn-guardar btn-lg">Generar Motivo de Permiso Especial</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="#">Formulario Alta de Permisos Especiales</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Inventario de Vehiculos
                    </h3>
                </div>
                <div class="card-body">
                    <table id="tabla_permisos_especiales" class="table table-bordered table-striped " role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Inventario </a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/flatpickr/flatpickr.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/idioma/es.js"></script>
<script src="<?= base_url() ?>/public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>/public/js/permissions/special_permiss_v1.js"></script>
<?= $this->endSection() ?>