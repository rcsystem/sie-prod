<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Horario Obscuro
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .show>.btn-primary.dropdown-toggle {
        color: #fff;
        background-color: #1f2d3d;
        border-color: #1f2d3d;
    }

    .custom-file-label::after {
        content: "Subir";
    }

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h5 class="m-0">Personal Interno que trabaja en horario obscuro (Festivos, horario diferente al asignado y fines de semana.)</h5>
                </div><!-- /.col -->
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">HSE</li>
                        
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
                    <h3 class="card-title">Horario Obscuro</h3>
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
                    <div id="content-form" class="container-fluid">
                        <form id="horas_extras" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="id_usuario" name="id_usuario" value="<?= session()->id_user ?>">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="permiso_num_nomina">Número de Nómina</label>
                                    <input type="text" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="<?= session()->payroll_number; ?>" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="permiso_usuario">Solicitante</label>
                                    <input type="text" class="form-control rounded-0" id="usuario" name="usuario" value="<?= ucwords(session()->name . " " . session()->surname); ?>" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="permiso_departamento">Departamento</label>
                                    <input type="text" class="form-control rounded-0" id="departamento" name="departamento" value="<?= session()->departament; ?>" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="permiso_puesto_trabajo">Puesto</label>
                                    <input type="text" class="form-control rounded-0" id="puesto" name="puesto" value="<?= session()->job_position; ?>" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fecha_extra">Fecha del Horario Obscuro</label>
                                    <input type="date" class="form-control rounded-0" id="fecha_extra" name="fecha_extra" value="" onchange="validar()" >
                                    <div id="error_fecha_extra" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fecha_extra">Hora de Entrada</label>
                                    <input type="time" class="form-control rounded-0" id="hora_entrada" name="hora_entrada" value="" onchange="validar()" >
                                    <div id="error_hora_entrada" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fecha_extra">Hora de Salida</label>
                                    <input type="time" class="form-control rounded-0" id="hora_salida" name="hora_salida" value="" onchange="validar()" >
                                    <div id="error_hora_salida" class="text-danger"></div>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <button id="btn-agregar-item" class="btn btn-success" type="button"><i class="fas fa-user-plus"></i> Agregar Usuario</button>
                                </div>
                                <div id="resultado" class="error col-md-8"></div>
                            </div>
                            <div id="alumnos">
                                <div id="duplica" class="agrega-item">
                                    <div id="item-duplica"></div>
                                </div>
                                <div id="tiempo_extra">
                                    <div id="extra_1" class="form-row ">
                                        <div class="form-group col-md-2">
                                            <label for="extra_num_nomina">Número de Nómina</label>
                                            <input type="text" class="form-control rounded-0" id="num_nomina_extra_1" name="num_nomina_extra[]"  onkeyup="javascript:this.value=this.value.toUpperCase();" title="Ingresa un Codigo Valido" onchange="escuchar(1);validarClon(1)">
                                        <div id="error_num_nomina_extra_1" class="text-danger"></div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label id="extra_usuario" for="usuario_extra">Usuario</label>
                                            <input type="text" class="form-control rounded-0" id="usuario_extra_1" name="usuario_extra[]" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="depto_1">Departamento</label>
                                            <input type="text" class="form-control rounded-0" id="depto_1" name="depto[]" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="permiso_puesto_trabajo">Puesto</label>
                                            <input type="text" class="form-control rounded-0" id="puesto_1" name="puesto_extra[]" value="" readonly>
                                        </div>
                                        <div id="btn_eliminar_1" class="form-group col-md-1"></div>
                                    </div>
                                </div>
                            </div>
                    </div>


                    <button id="guardar_permiso" type="submit" class="btn btn-guardar btn-lg btn-block">Generar</button>
                    </form>
                </div>

                <div class="card-footer">
                    <a href="#">Horario Obscuro</a>
                </div>
            </div>
        </div>

    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/js/qhse/tiempo_extra_v1.js"></script>
<?= $this->endSection() ?>