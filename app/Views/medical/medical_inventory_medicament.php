<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Servicio Médico
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .bg-acceptable {
        color: #fff;
        background-color: #F65E0A;
        border-color: #F65E0A;
    }

    .toggle {
        position: relative;
        box-sizing: border-box;
        padding: inherit;
    }

    .toggle input[type="checkbox"] {
        position: absolute;
        left: 0;
        top: 0;
        z-index: 10;
        width: 56%;
        height: 100%;
        cursor: pointer;
        opacity: 0;
    }

    .toggle label {
        position: relative;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    .toggle label:before {
        content: '';
        width: 40px;
        height: 22px;
        background: #ccc;
        position: relative;
        display: inline-block;
        border-radius: 46px;
        box-sizing: border-box;
        transition: 0.2s ease-in;
    }

    .toggle label:after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        left: 2px;
        top: 2px;
        z-index: 2;
        background: #fff;
        box-sizing: border-box;
        transition: 0.2s ease-in;
    }

    .toggle input[type="checkbox"]:checked+label:before {
        background: #4BD865;
    }

    .toggle input[type="checkbox"]:checked+label:after {
        left: 19px;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inventario de Medicamentos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Servicio Médico</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- NUEVA ENTRADA / INVENTARIO -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default collapsed-card ">
                <div class="card-header">
                    <h3 class="card-title">Alta de Medicamentos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form id="form_medicament" method="post">
                        <div class="col-md-12 form-row">
                            <div class="form-group col-md-4">
                                <label>Sustancia Activa:</label>
                                <input type="text" class="form-control" name="sustancia_activa" id="sustancia_activa" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Nombre Comercial:</label>
                                <input type="text" class="form-control" name="nombre_comercial" id="nombre_comercial" required>
                            </div>
                            <div class="col-md-2">
                                <label>Presentacion</label>
                                <select class="form-control" name="presentacion" id="presentacion" required>
                                    <option value="">Opciones...</option>
                                    <?php foreach ($medicament as $key) { ?>
                                        <option value="<?php echo $key->id ?>"><?php echo $key->presentation ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Piezas por Caja:</label>
                                <input type="number" class="form-control" name="pz_caja" id="pz_caja" min="1" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Fecha de Caducidad:</label>
                                <input type="date" class="form-control" name="fecha_caducidad" id="fecha_caducidad" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Cantidad de Cajas:</label>
                                <input type="number" class="form-control" name="catidad" id="catidad" min="1" required>
                            </div>
                            <!-- <div class="form-group col-md-3">
                                <label>Identificador:</label>
                                <input type="text" class="form-control" name="identificador" id="identificador" required>
                            </div> -->
                            <div class="form-group col-md-6" style="padding-top: 26px;text-align: right;">
                                <button type="submit" id="alta_medicament" class="btn btn-guardar btn-lg">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="#">Servicio Médico</a>
                </div>
            </div>
        </div>
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Inventario de Medicamentos</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body col-md-12">
                <div class="container-fluid">
                    <table id="tabla_suministros" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="#">Servicio Médico</a>
            </div>
        </div>
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Medicamentos en Pastilleo</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body col-md-12">
                <div class="container-fluid">
                    <table id="tabla_suministros_pastilleo" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

                    </table>
                </div>
            </div>

            <div class="card-footer">
                <a href="#">Servicio Médico</a>
            </div>
        </div>
    </section>

    <!-- ACTUALIZACION -->
    <section>
        <div class="modal fade" id="actualizaModal" tabindex="-1" aria-labelledby="actualizaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-prescription-bottle"></i>&nbsp;&nbsp; Informacion de Medicamento:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit_article" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="id_medicament" name="id_medicament" value="">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Sustancia Activa:</label>
                                    <input type="text" class="form-control" name="modal_sustancia_activa" id="modal_sustancia_activa" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Nombre Comercial:</label>
                                    <input type="text" class="form-control" name="modal_nombre_comercial" id="modal_nombre_comercial" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label>Presentacion</label>
                                    <select class="form-control" name="modal_presentacion" id="modal_presentacion" disabled>
                                        <option value="">Opciones...</option>
                                        <?php foreach ($medicament as $key) { ?>
                                            <option value="<?php echo $key->id ?>"><?php echo $key->presentation ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Piezas por Caja:</label>
                                    <input type="number" class="form-control" name="modal_pz_caja" id="modal_pz_caja" min="1" disabled>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Piezas Existentes:</label>
                                    <input type="number" class="form-control" name="modal_pz_exist" id="modal_pz_exist" min="1" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Fecha de Caducidad:</label>
                                    <input type="date" class="form-control" name="modal_fecha_caducidad" id="modal_fecha_caducidad" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Semaforización de Medicamento:</label>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <input class="form-control" name="color_semaforo" id="color_semaforo" disabled>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="semaforo" id="semaforo" disabled>
                                        </div>

                                    </div>
                                </div>
                                <!-- <div class="form-group col-md-3">
                                <label>Identificador:</label>
                                <input type="text" class="form-control" name="identificador" id="identificador" disabled>
                            </div> -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <!-- <button type="submit" id="actualizar_suministro" class="btn btn-primary">Actualizar</button> -->
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
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/medical/inventario_v0.js"></script>
<?= $this->endSection() ?>