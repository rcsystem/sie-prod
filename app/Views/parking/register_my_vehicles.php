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
<style>
    input[type="text"] {
        text-transform: uppercase;
    }

    .custom-file-label::after {
        content: "Seleccionar";
    }

    .row {
        padding: 10px 0 10px 0;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Registro de Vehículos</h1>
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
                    <h3 class="card-title">Registro de Vehículos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="form_registro" method="post">
                            <div class="row">
                                <div style="width: auto;margin-right:1rem;padding-top: 5px;">
                                    <label>Número de contacto o Extensión en caso de emergencia:</label>
                                </div>
                                <div class="col-md-3" style="text-align: start;">
                                    <input type="text" minlength="12" maxlength="14" class="form-control" name="ext" id="ext" value="<?= $ext ?? '' ?>" placeholder="55 1234 5678" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row" id="div_titulo">
                                <div class="col-md-4">
                                    <h4>Datos de Vehículo</h4>
                                </div>
                                <div class="col-md-5" id="error_item"></div>
                                <div class="col-md-3" style="text-align: right;">
                                    <!-- <button id="btn_agregar_item" class="btn btn-guardar btn-style" style="background-color:#0056B3!important;">
                                        <i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp;Agregar Vehículo
                                    </button> -->
                                </div>
                            </div>
                            <div id="items_existentes"></div>
                            <div id="items_clon" style="margin-bottom: 1rem;"></div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <button type="submit" id="btn_registro" class="btn btn-guardar btn-lg">Registrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="modal fade" id="actualizarPolizaModal" tabindex="-1" role="dialog" aria-labelledby="actualizarPolizaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="actualizarPolizaModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form_actualiza_poliza">
                        <div class="modal-body">
                            <input type="hidden" name="id_item" id="id_item">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Vencimiento:</label>
                                    <input type="date" class="form-control" name="vencimiento_modal" id="vencimiento_modal" onchange="validarItem(this)">
                                    <div id="error_vencimiento_modal" class="text-danger"></div>
                                </div>
                                <div class="col-md-6">
                                    <label>Póliza:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" accept=".pdf" name="archivo_modal" id="archivo_modal" onchange="validarFile(this)">
                                        <label class="custom-file-label" id="lbl_archivo_modal" for="archivo_modal">Selecionar</label>
                                    </div>
                                    <div id="error_archivo_modal" class="text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-guardar" id="btn_actualiza_poliza">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://cdn.rawgit.com/janantala/angular-qr/master/lib/qrcode.js" type="text/javascript"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/parking/register_my_vehicles.js"></script>
<!-- <script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script> -->
<?= $this->endSection() ?>