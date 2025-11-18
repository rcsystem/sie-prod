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
                    <h1 class="m-0">Personal Eventual</h1>
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
                    <h3 class="card-title">Contratos a Planta</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <!-- 
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabla_usuarios_temp" class="table table-bordered table-striped " role="grid" aria-describedby="usuarios_temp" style="width:100%" ref="">
                    </table>
                </div>
                <div class="card-footer">
                    <a href="#">Personal Eventual</a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-file-signature" style="margin-right: 10px;"></i>Confirmar Contrato<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form_edit_contract" method="post">
                        <div class="modal-body">
                            <div id="resultado"></div>
                            <input type="hidden" id="id_contract" name="id_contract" value="">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nombre">Nombre Empleado:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="apellido_p">Nombre Jefe:</label>
                                    <input type="text" class="form-control" id="apellido_p" name="apellido_p" readonly>
                                </div>
                                <div class="col-md-12" style="text-align: center; padding-top: 2rem;">
                                    <input type="hidden" name="estado_contrato" id="estado_contrato">
                                    <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-success btn-opcion">
                                            <input type="radio" onclick="estadoContrato(1)"> ACEPTAR
                                        </label>
                                        <label class="btn btn-outline-danger btn-opcion" style="margin-left: 5px;">
                                            <input type="radio" onclick="estadoContrato(2)"> RECHAZAR
                                        </label>
                                    </div>
                                    <div class="text-danger" id="error_estado_contrato"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="btn__edit_contract" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/users/contracts_direct_v2.js"></script>
<?= $this->endSection() ?>