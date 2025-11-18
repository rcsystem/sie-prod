<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Personal Eventual
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contratos Temporales por Usuario</h1>
                    <h5 class="m-0"><?= $nombre ?></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Contratos Temporales</h3>
                    <div class="card-tools">
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="id_md5" value="<?= $id_md5 ?>">
                    <table id="tbl_contratos_de_usuario" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="info_usuarios" style="width:80%;margin-left:10%;" ref=""></table>
                </div>
                <div class="card-footer">
                    <a href="#">Usuarios</a>
                </div>
            </div>
        </div>
    </section>
    
    <section>
        <div class="modal fade" id="contrato_temporal_Modal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Contrato Temporal<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form_contrato_temp" method="post">
                        <div class="modal-body">
                            <div class="form-row">
                                <input type="hidden" class="form-control" id="folio" name="folio" value="" readonly>
                                <div class="form-group col-md-6">
                                    <label for="tipo">Tipo de Contrato:</label>
                                    <input type="text" class="form-control" id="tipo" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fecha_creacion">Fecha Inicio Contrato:</label>
                                    <input type="date" class="form-control" id="fecha_creacion" name="fecha_creacion">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="fecha_expiracion">Fecha de Vencimiento: </label>
                                    <input type="date" class="form-control" id="fecha_expiracion" name="fecha_expiracion">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fecha_recontrato">Fecha de Recontratacion</label>
                                    <input type="date" class="form-control" id="fecha_recontrato" name="fecha_recontrato">
                                    <div id="error_fecha_recontrato" class=" text-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="btn_contrato_temp" class="btn btn-guardar">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/users/all_temp_contract_by_user_v6.js"></script>
<?= $this->endSection() ?>