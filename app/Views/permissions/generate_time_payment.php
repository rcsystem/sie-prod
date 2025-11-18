<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Pago de Tiempo
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<style>
    .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .show>.btn-primary.dropdown-toggle {
        color: #fff;
        background-color: #1f2d3d;
        border-color: #1f2d3d;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 sie-font-bold">Pago de Tiempo</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Permisos & Vacaciones</li>
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
            <div id="card_permisos" class="card card-default "> <!-- collapsed-card -->
                <div class="card-header">
                    <h3 class="card-title sie-font-bold">Generar Registro de Pago de Tiempo</h3>
                    <div class="card-tools">
                        <button id="colllapse_permisos" type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i id="icon_card_permisos" class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div id="body_permisos" class="card-body col-md-12">
                    <div class="container-fluid">
                        <input type="hidden" id="hoy" value="<?= date('Y-m-d', strtotime('-15 days')); ?>">
                        <input type="hidden" id="15dias" value="<?= date('Y-m-d', strtotime('+15 days')); ?>">
                        <form id="form_tiempo" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Solicitante</label>
                                    <input type="text" class="form-control" value="<?= strtoupper(session()->name . " " . session()->surname); ?>" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Nómina</label>
                                    <input type="text" class="form-control" value="<?= session()->payroll_number; ?>" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Departamento</label>
                                    <input type="text" class="form-control" value="<?= (session()->departament == "ALMACEN VILLAHERMOSA") ? "DOS BOCAS" : session()->departament; ?>" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Area Operativa</label>
                                    <input type="text" class="form-control" value="<?= session()->cost_center; ?>" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Puesto</label>
                                    <input type="text" class="form-control" value="<?= session()->job_position; ?>" readonly>
                                </div>
                                <div class="form-group col-md-2" id="div_1"></div>
                                <div class="form-group col-md-2" id="div_2"></div>
                                <div class="form-group col-md-3" id="div_3"></div>
                                <div class="form-group col-md-2" style="text-align: end;padding-top: 2rem;">
                                    <button type="button" id="btn_agregar_item" class="btn btn-outline-info"><i class="far fa-calendar-plus" style="margin-right: 5px;"></i>Añadir Fecha</button>
                                </div>
                            </div>
                            <div id="error_item"></div>
                            <div class="form-row" id="div_dia_1">
                                <div class="form-group col-md-3">
                                    <input type="hidden" name="L-V_entrada_[]" id="L-V_entrada_1"><input type="hidden" name="L-V_salida_[]" id="L-V_salida_1">
                                    <input type="hidden" name="S_entrada_[]" id="S_entrada_1"><input type="hidden" name="S_salida_[]" id="S_salida_1">
                                    <label>En el Turno:</label>
                                    <Select id="turno_1" name="turno_[]" class="form-control" onchange="turnos(this,1),turnoCompleto(1)">
                                        <option value="">Selecciona....</option>
                                    </Select>
                                    <div id="div_horario_1"></div>
                                    <div id="error_turno_1" class="text-danger"></div>
                                </div>
                                <div class="col-md-2">
                                    <label for="tipo_permiso_1">Tipo de pago:</label>
                                    <select name="tipo_permiso_[]" id="tipo_permiso_1" class="form-control" onchange="limpiarError(this),turnoCompleto(1)">
                                        <option value="">Opciones....</option>
                                        <option value="1" id="tipo_permiso_1_opc" style="display: none;">Llegar Antes</option>
                                        <option value="2">Quedarse Despues</option>
                                        <option value="3">Turno Completo</option>
                                    </select>
                                    <div id="error_tipo_permiso_1" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="dia_salida_1">Día Pago de tiempo:</label>
                                    <input type="date" class="form-control" id="dia_salida_1" name="dia_salida_[]" onchange="limpiarError(this),turnoCompleto(1)">
                                    <div id="error_dia_salida_1" class="text-danger"></div>
                                </div>
                                <div class="col-md-3">
                                    <label>Cantidad de Horas:</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="input_horas_1" name="input_horas_[]" value="0" min="0" max="9" onchange="limpiarError(this),tiempoTotal()">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Hrs</span>
                                                </div>
                                            </div>
                                            <div id="error_input_horas_1" class="text-danger"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="input_minutos_1" name="input_minutos_[]" value="0" min="0" max="59" onchange="limpiarError(this),tiempoTotal()">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Min</span>
                                                </div>
                                            </div>
                                            <div id="error_input_minutos_1" class="text-danger"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="clones_dias"></div>
                            <div class="row">
                                <button id="btn_tiempo" type="submit" class="btn btn-guardar btn-lg sie-font-bold">Generar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="footer_permisos" class="card-footer">
                    <a href="#">Permisos Entrada ó Salida</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- <script src="<?= base_url() ?>/public/js/permissions/permissions_generate_v4-4.js"></script> -->
<script src="<?= base_url() ?>/public/js/permissions/generate_time_payment.js"></script>
<?= $this->endSection() ?>