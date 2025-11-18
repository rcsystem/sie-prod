<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Personal Eventual
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Estilos para el checkbox */
    input[type="checkbox"] {
        /* Cambiar el tamaño del checkbox */
        transform: scale(1.5);
        -webkit-transform: scale(1.5);
        /* Para navegadores basados en Webkit */
        -moz-transform: scale(2.5);
        /* Para navegadores basados en Gecko */
    }

    .container-ChBox {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 5px;
    }
</style>

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
                    <h3 class="card-title">Usuarios</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12" style="text-align:end;margin-bottom: 11px;padding-right: 0px;">
                        <button id="masivo" class="btn btn-secondary" disabled><i class="fas fa-file-upload" style="margin-right: 15px;"></i>CONTRATOS MASIVOS</button>
                    </div>
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
        <div class="modal fade" id="configurarContrato" tabindex="-1" aria-labelledby="configurarContratoLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-file-upload" style="margin-right: 15px;"></i>CONTRATOS MASIVOS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="form_contratos_masivos" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="tipo_contrato">Contrato por:</label>
                                    <select name="tipo_contrato" id="tipo_contrato" class="form-control" required>
                                        <option value="">Opciones...</option>
                                        <option value="1">Tiempo Indeterminado (Planta)</option>
                                        <option value="2">Tiempo Determinado</option>
                                        <option value="3">Baja</option>
                                    </select>
                                </div>
                                <div id="div_temporal" class="form-group col-md-6">
                                    <label for="tipo_temporal">Cantidad de Días:</label>
                                    <select name="tipo_temporal" id="tipo_temporal" class="form-control">
                                        <option value="2">30 días</option>
                                        <option value="3">60 días</option>
                                        <option value="4">90 días</option>
                                    </select>
                                </div>
                                <div id="div_obs" class="form-group col-md-12">
                                    <label for="dias_vacaciones">Observaciones:</label>
                                    <textarea class="form-control" name="obs" id="obs" cols="20" rows="4"></textarea>
                                </div>
                                <div id="div_baja" class="form-group col-md-12">
                                    <label for="dias_vacaciones">Causa de Bajas:</label>
                                    <textarea class="form-control" name="baja" id="baja" cols="20" rows="4"></textarea>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_contratos_masivos" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/users/contracts_v5.js"></script>
<?= $this->endSection() ?>