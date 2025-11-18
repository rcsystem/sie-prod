<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Estacionamiento
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
        padding: 10px 0 10px 5px;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Registro de Usuarios / Vehículos</h1>
                    <!-- <h4 class="m-0">Desarrollo</h4> -->
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
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"> Registro de Vehículos</h3>
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
                                <div class="col-md-2">
                                    <label>Nomina:</label>
                                    <input type="number" class="form-control" min="1" name="nomina" id="nomina">
                                    <div id="error_nomina" class="text-danger"></div>
                                </div>
                                <div class="col-md-3">
                                    <input type="hidden" name="id_user" id="id_user">
                                    <label>Nombre:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" readonly>
                                    <div id="error_nombre" class="text-danger"></div>
                                </div>
                                <div class="col-md-3">
                                    <input type="hidden" name="id_depto" id="id_depto">
                                    <label>Departamento:</label>
                                    <input type="text" class="form-control" id="depto" name="depto" readonly>
                                    <div id="error_depto" class="text-danger"></div>
                                </div>
                                <div class="col-md-2">
                                    <label>Ext. o Telefono:</label>
                                    <input type="text" class="form-control" name="ext" id="ext" onchange="validarItem(this)">
                                    <div id="error_ext" class="text-danger"></div>
                                </div>
                                <div class="col-md-2">
                                    <label>Tipo de Vehículo:</label>
                                    <select class="form-control" name="tipo_vehiculo" id="tipo_vehiculo">
                                        <option value="">Opciones...</option>
                                        <option value="1">Automóvil</option>
                                        <option value="4">Automóvil Nave 3</option>
                                        <option value="5">Automóvil Jardines</option>
                                        <option value="6">Automóvil Nave 1</option>
                                        <option value="2">Motocicleta</option>
                                        <option value="3">Bicicleta</option>
                                    </select>
                                    <div id="error_tipo_vehiculo" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="row" id="div_tags" style="display: none;">
                                <div class="col-md-4 text-center" style="padding-top: 1rem;">
                                    <input type="hidden" name="tipo_marbete" id="tipo_marbete">
                                    <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-success btn-opcion" style="margin-right: 10px;">
                                            <input type="radio" id="opc_1" onclick="tipoMarbete(1)"> MARBETE NUEVO
                                        </label>
                                        <label class="btn btn-outline-success btn-opcion">
                                            <input type="radio" id="opc_2" onclick="tipoMarbete(2)"> MARBETE REASIGNAR
                                        </label>
                                    </div>
                                    <div id="error_tipo_marbete" class="text-danger"></div>
                                </div>
                                <div class="col-md-3" id="div_no_marbete" style="display: none;">
                                    <label>No. Marbete:</label>
                                    <select name="no_marbete" id="no_marbete" class="form-control" onchange="validarItem(this)"></select>
                                    <div id="error_no_marbete" class="text-danger"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row" id="div_titulo">
                                <div class="col-md-4">
                                    <h4>Datos de Vehículo</h4>
                                </div>
                                <div class="col-md-5" id="error_item"></div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button id="btn_agregar_item" class="btn btn-guardar btn-style" style="background-color:#0056B3!important;" disabled>
                                        <i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp;Agregar Vehículo
                                    </button>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 1rem;" id="div_datos_1">
                            </div>
                            <div id="items_clon" style="margin-bottom: 1rem;"></div>
                            <button type="submit" id="btn_registro" class="btn btn-guardar btn-lg">Guardar</button>
                            <!-- <button id="#btn_dowload_5" class="btn btn-guardar btn-lg" onclick="DownloadQR(5, '/public/images/qr/qrcode_5.png' ,'HORUS', 1)"><i class="fas fa-qrcode"></i> Descargar</button> -->
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuarios de Automoviles</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl_todos_automoviles" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref=""></table>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuarios de Motocicletas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl_todos_motos" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref=""></table>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuarios de Bicicletas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl_todos_bicis" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref=""></table>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuarios de Estacionamiento Nave 3</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl_todos_n3" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref=""></table>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuarios de Estacionamiento Jardin</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl_todos_jardin" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref=""></table>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuarios de Estacionamiento Nave 1</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl_todos_n1" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref=""></table>
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
                        <h5 class="modal-title"><i class="fas fa-qrcode"></i> Datos del usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form_editar_vehiculos">
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="id_record" id="id_record">
                                <input type="hidden" name="id_user_modal" id="id_user_modal">
                                <div class="col-md-2">
                                    <label>Nomina:</label>
                                    <input type="number" class="form-control" min="1" id="nomina_modal" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label>Nombre:</label>
                                    <input type="text" class="form-control" id="nombre_modal" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label>Departamento:</label>
                                    <input type="text" class="form-control" id="depto_modal" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label>Ext. o Telefono:</label>
                                    <input type="text" class="form-control" id="ext_modal" disabled>
                                </div>
                                <div class="col-md-2">
                                    <label>Tipo de Vehículo:</label>
                                    <input type="text" class="form-control" id="tipo_vehiculo_modal" disabled>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Datos de los Vehículos</h4>
                                </div>
                                <div class="col-md-5" id="error_item_modal"></div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button id="btn_agregar_item_modal" class="btn btn-guardar btn-style" style="background-color:#0056B3!important;">
                                        <i class="fas fa-plus"></i>&nbsp;&nbsp;&nbsp;Agregar Vehículo
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="tipo_tbl" id="tipo_tbl">
                            <div id="items_existentes"></div>
                            <div id="items_clon_modal"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            <button id="btn_editar_vehiculos" class="btn btn-guardar">Actualizar</button>
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
<script src="<?= base_url() ?>/public/js/parking/create_record_v3.js"></script>
<?= $this->endSection() ?>