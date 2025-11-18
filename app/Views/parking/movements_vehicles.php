<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Estacionamiento
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<!-- <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css"> -->
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Movimientos de Usuarios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">HSE</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Todos los Movimientos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl_todos_registros" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref=""></table>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section>

        <div class="modal fade" id="ver_modal" tabindex="-1" aria-labelledby="verModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-qrcode"></i>  Datos del usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label>Nomina:</label>
                                <input type="number" class="form-control" min="1" id="nomina_modal" disabled>
                            </div>
                            <div class="col-md-4">
                                <label>Nombre:</label>
                                <input type="text" class="form-control" id="nombre_modal" disabled>
                            </div>
                            <div class="col-md-4">
                                <label>Departamento:</label>
                                <input type="text" class="form-control" id="depto_modal" disabled>
                            </div>
                            <div class="col-md-2">
                                <label>Ext. o Telefono:</label>
                                <input type="text" class="form-control" id="ext_modal" disabled>
                            </div>
                        </div>
                        <hr>
                        <!-- <form id="form_editar_datos" method="post"> -->
                        <div id="div_modal"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <!-- <button id="btn_editar_datos" class="btn btn-guardar">Actualizar</button> -->
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </section>


</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://cdn.rawgit.com/janantala/angular-qr/master/lib/qrcode.js" type="text/javascript"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/parking/movements_vehicles.js"></script>
<!-- <script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script> -->
<?= $this->endSection() ?>