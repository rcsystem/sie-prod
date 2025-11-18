<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administrar Usuarios
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administrar Autorizacion Requisiciones</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Requisiciones</li>
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
                    <h3 class="card-title">Administrar Usuarios</h3>
                    <div class="card-tools">
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="fs-title">Número de Nomina</h5>
                            <input type="number" min="1" maxlength="11" class="form-control" id="nomina" value="">
                            <div id="error_nomina" class="text-danger"></div>
                        </div>
                        <div class="col-6">
                            <button id="btn_buscar" class="btn btn-guardar" style="margin-top: 2rem;"> BUSCAR </button>
                        </div>
                    </div>
                    <div id="modal">
                        <hr>
                        <h5>Datos de Usuario</h5>
                        <form id="editManager" method="post">
                            <div id="resultado"></div>
                            <div class="form-row">
                                <input type="hidden" id="registro_modal" name="registro_modal" value="">
                                <div class="form-group col-md-3">
                                    <label for="nombre_modal">Nombre</label>
                                    <input type="text" class="form-control" id="nombre_modal" value="" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="apellido_p_modal">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_p_modal" value="" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="apellido_m_modal">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_m_modal" value="" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="id_manager">Autorizador de Requisiciones:</label>
                                    <select name="id_manager" id="id_manager" class="form-control">
                                        <option value="">Selecciona una opción...</option>
                                        <?php foreach ($manager as $key => $value) { ?>
                                            <option value="<?= $value->id_user ?>"><?= $value->name . " " . $value->surname . " " . $value->second_surname ?></otion>
                                            <?php } ?>
                                    </select>
                                    <div id="error_id_manager" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-1" style="text-align:right;">
                                    <button type="submit" style="margin-top: 2rem;" id="actualiza_gerente" class="btn btn-guardar">GUARDAR</button>
                                </div>
                            </div><!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> -->
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Requisiciones</a>
                </div>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/requisitions/asignation_manager_v1.min.js"></script>
<?= $this->endSection() ?>