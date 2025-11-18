<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Estados de Cuenta
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .my-col-it {
        flex: 0 0 20%;
        max-width: 20%;
        position: relative;
        width: 100%;
        padding-right: 7.5px;
        padding-left: 7.5px;
    }

    .custom-file-label::after {
        content: "Seleccionar";
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Estado de cuenta de <?= $a = ($type == 1) ? 'Viaticos' : 'Gastos'; ?> <b id="h_folio"> </b>.</h1>
                    <input type="hidden" id="folio" value="<?= $folio; ?>">
                    <input type="hidden" id="type" value="<?= $type; ?>">
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Viajes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content pt-2" style="font-family: 'Roboto Condensed'; display: contents;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" style="text-align: center;">
                    <h3 id="h3_usuario">HORUS SAMAEL RIVAS PEDRAZA</h3>
                </div>
            </div>
            <div class="row">
                <div class="my-col-it">
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"> <i class="fas fa-money-bill-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">MONTO SOLICITADO</span>
                            <H2 style="margin-top: 10px;" id="h2_solicitado"></H2>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"> <i class="fas fa-money-check-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">ESTADO DE CUENTA</span>
                            <H2 style="margin-top: 10px;" id="h2_estado_cuenta"></H2>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"> <i class="fas fa-user-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">MONTO COMPROBADO:</span>
                            <H2 style="margin-top: 10px;" id="h2_comprobado"></H2>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="info-box bg-gradient-danger">
                        <span class="info-box-icon"> <i class="fas fa-user-tag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">DESCUENTO:</span>
                            <H2 style="margin-top: 10px;" id="h2_descuento"></H2>
                        </div>
                    </div>
                </div>
                <div id="div_monto_grado" class="my-col-it">
                    <div class="info-box bg-gradient-secondary">
                        <span class="info-box-icon"> <i class="far" id="icon_grade" style="font-size: 3rem;"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">DIARIO POR GRADO:</span>
                            <H2 style="margin-top: 10px;" id="h2_grado"></H2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Subir Estados de Cuentas</h3>
                    <div class="card-tools">
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button> -->
                        <button type="button" id="btn_dowload_format" class="btn btn-tool btn-outline-dark" onclick="download()">Descargar Formato<i class="fas fa-file-download" style="margin-left: 10px;"></i></button>

                    </div>
                </div>
                <div class="card-body">
                    <form id="form_estado_cuenta" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="motivo_visita">Cargar Estado de Cuenta Excel</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="archivo" name="archivo" lang="es" onchange="validarFile(this)">
                                    <label class="custom-file-label" for="customFileLang" id="lbl_archivo">Seleccionar Excel</label>
                                </div>
                                <div id="error_archivo" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3" style="padding-top: 26px;">
                                <button id="btn_estado_cuenta" type="submit" class="btn btn-outline-guardar btn-lg"><i class="fas fa-file-upload" style="margin-right: 10px;"></i> Subir Datos</button>
                            </div>

                        </div>
                    </form>
                    <hr>
                    <table id="tabla_estado_cuenta" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="faltaComprobacionModal" tabindex="-1" role="dialog" aria-labelledby="modalMedianoLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Falta de Comprobacion de Monto Efectivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_estado_cuenta_individual" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-1">
                                <label for="regla_codigo">Regla</label>
                                <input type="text" id="regla_codigo" name="regla_codigo" class="form-control" list="opciones_regla_codigo" maxlength="2" oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '').toUpperCase();">
                                <datalist id="opciones_regla_codigo">
                                    <option value="EX">
                                    <option value="EF">
                                </datalist>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="lugar">Lugar/Motivo</label>
                                <input type="text" id="lugar" name="lugar" class="form-control" onchange="validarInput(this)">
                                <div id="error_lugar" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="fecha">Fecha</label>
                                <input type="date" id="fecha" name="fecha" class="form-control" onchange="validarInput(this)">
                                <div id="error_fecha" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="monoto_original">Monto Original</label>
                                <div class="input-group">
                                    <input type="number" id="monoto_original" name="monoto_original" class="form-control" step="0.01" min="1.00" class="form-control" placeholder="1.00" onchange="validarInput(this)">
                                    <div class="input-group-prepend">
                                        <input type="text" name="divisa" id="divisa" class="form-control" readonly>
                                    </div>
                                </div>
                                <div id="error_monoto_original" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="monto_mxn">Monto MXN</label>
                                <input type="text" id="monto_mxn" name="monto_mxn" class="form-control" onchange="validarInput(this)">
                                <div id="error_monto_mxn" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-2" style="padding-top: 32px;">
                                <button id="btn_estado_cuenta_individual" type="submit" class="btn btn-outline-guardar"><i class="fas fa-arrow-up" style="margin-right: 10px;"></i> Subir Dato</button>
                            </div>
                        </div>
                    </form>
                    <p>Este es un modal de tamaño mediano.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/travels/account_status_v.js"></script>
<?= $this->endSection() ?>