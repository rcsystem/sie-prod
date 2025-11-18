<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administrar Usuarios
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administrar Usuarios</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuarios</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabla_usuarios" class="table table-bordered table-striped " role="grid" aria-describedby="usuarios_info" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Usuarios</a>
                </div>
            </div>
        </div>
    </section>
    <section>
        <!-- <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true"> -->
        <div class="modal fade" id="editarModal" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editUser" method="post" autocomplete="off">
                        <div class="modal-body">
                            <div id="resultado"></div>
                            <input type="hidden" id="id_usuario" name="id_usuario" value="">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="apellido_p">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="apellido_m">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="curp">CRUP</label>
                                    <input type="text" class="form-control" id="curp" name="curp" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nss">NSS</label>
                                    <input type="text" class="form-control" id="nss" name="nss" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nomina_modal">Nomina</label>
                                    <input type="text" class="form-control" id="nomina_modal" name="nomina_modal" required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fecha_admision">Fecha de Admisión</label>
                                    <input type="date" class="form-control" id="fecha_admision" name="fecha_admision" autocomplete="off" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_empleado">Tipo de Empleado</label>
                                    <select name="tipo_empleado" id="tipo_empleado" class="form-control" required>
                                        <option value="1">Administrativo</option>
                                        <option value="2">Sindicalizado</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="area_operative">Area Operativa</label>
                                    <select id="area_operative" name="area_operative" class="form-control rounded-0 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($areas as $key) { ?>
                                            <option value="<?= $key->id_area ?>"><?= $key->area ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="depto">Departamento</label>
                                    <select id="depto" name="depto" class="form-control select2-hidden-accessible" style="width: 100%; height: 100%;" required>
                                        <?php foreach ($departament as $label => $opt) { ?>
                                            <optgroup label="<?php echo $label; ?>">
                                                <?php foreach ($opt as $id => $name) { ?>
                                                    <option value="<?= $id ?>"><?= $name ?></option>
                                                <?php } ?>
                                            </optgroup>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="clace_cost">Clave de Centro de Costos</label>
                                    <select id="clace_cost" name="clace_cost" class="form-control rounded-0 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($centros as $key) { ?>
                                            <option value="<?= $key->id_cost_center ?>"><b><?= $key->clave_cost_center ?></b> <?= $key->cost_center ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="puesto">Puesto</label>
                                    <select id="puesto" name="puesto" class="form-control select2-hidden-accessible" style="width: 100%; height: 100%;" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($puestos as $key) { ?>
                                            <option value="<?= $key->id?>"><b><?= $key->job ?></b></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="dias_vacaciones">Dias de Vacaciones</label>
                                    <input type="number" class="form-control" id="dias_vacaciones" name="dias_vacaciones" autocomplete="off" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="anios_laborados">Años Laborados</label>
                                    <input type="number" class="form-control" id="anios_laborados" name="anios_laborados" autocomplete="off" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" value="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="puesto">Grado Gerarquia</label>
                                    <select class="form-control" id="grado" name="grado" data-toggle="validation" data-required="true" data-message="Grado." style="width: 100%;" required>
                                        <option value="">Seleccionar una Opción</option>
                                        <option value="1">I</option>
                                        <option value="2">II</option>
                                        <option value="3">III</option>
                                        <option value="4">IV</option>
                                        <option value="5">V</option>
                                        <option value="6">VI</option>
                                        <option value="7">VII</option>
                                        <option value="8">VIII</option>
                                        <option value="9">IX</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="id_manager_PyV">Persona que Autoriza Permisos & Vacaciones:</label>
                                    <select class="form-control" id="id_manager_PyV" name="id_manager_PyV" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required>
                                        <option value="">CONTACTAR A SISTEMAS</option>
                                        <?php foreach ($gerente as $key => $value) { ?>
                                            <option value="<?= $value->id_user ?>"><?= $value->nombre ?></otion>
                                            <?php } ?>
                                    </select>
                                    <div id="error_id_manager_P&V" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="id_manager_papeleria">Persona que Autoriza Papeleria:</label>
                                    <select class="form-control" id="id_manager_papeleria" name="id_manager_papeleria" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required>
                                        <option value="">CONTACTAR A SISTEMAS</option>
                                        <?php foreach ($gerente as $key => $value) { ?>
                                            <option value="<?= $value->id_user ?>"><?= $value->nombre ?></otion>
                                            <?php } ?>
                                    </select>
                                    <div id="error_id_manager_papeleria" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="id_manager_contrato">Persona que Autoriza Contratos Temporales:</label>
                                    <select class="form-control" id="id_manager_contrato" name="id_manager_contrato" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required readonly>
                                        <option value="">CONTACTAR A SISTEMAS</option>
                                        <?php foreach ($gerente as $key => $value) { ?>
                                            <option value="<?= $value->id_user ?>"><?= $value->nombre ?></otion>
                                            <?php } ?>
                                    </select>
                                    <div id="error_id_manager_contrato" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="id_manager_requicicion">Persona que Autoriza Requisiciones:</label>
                                    <select class="form-control" id="id_manager_requicicion" name="id_manager_requicicion" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;">
                                        <option value="">CONTACTAR A SISTEMAS</option>
                                        <?php foreach ($gerente as $key => $value) { ?>
                                            <option value="<?= $value->id_user ?>"><?= $value->nombre ?></otion>
                                            <?php } ?>
                                    </select>
                                    <div id="error_id_manager_requicicion" class="text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="actualiza_usuario" class="btn btn-guardar">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="modal fade" id="cardDataModal" role="dialog" aria-labelledby="cardDataModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="far fa-address-card"></i> Datos de Credencial</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-6">
                                <label>Nombre:</label>
                                <input type="text" class="form-control" id="nombre_card" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Nomina:</label>
                                <input type="text" class="form-control" id="nomina_card" readonly>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-6">
                                <label>Apellidos:</label>
                                <input type="text" class="form-control" id="apellidos_card" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>CURP:</label>
                                <input type="text" class="form-control" id="curp_card" readonly>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-6">
                                <label>Departamento:</label>
                                <input type="text" class="form-control" id="depto_card" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>NSS:</label>
                                <input type="text" class="form-control" id="nss_card" readonly>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-6">
                                <label>Puesto:</label>
                                <input type="text" class="form-control" id="job_card" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/users/admin_users_v5.js"></script>
<?= $this->endSection() ?>