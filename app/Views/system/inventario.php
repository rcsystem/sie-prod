<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administracion de Equipos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">

<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administracion de Inventario</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Sistemas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->id_user == 1 ||session()->id_user == 1386 || session()->id_user == 1390 || session()->id_user == 1334 || session()->id_user == 1370) { ?>
        <!-- SALIDA -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Salida de Productos</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="asignar_equipo" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="ID_">Nomina</label>
                                    <input type="text" name="ID_" id="ID_" class="form-control">
                                    <div id="error_ID_" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="depto">Departamento</label>
                                    <input list="browsers" id="depto" name="depto" class="form-control" required>
                                    <datalist id="browsers">
                                        <?php foreach ($departament as $label => $opt) { ?>
                                            <option value="<?= $opt["departament"] ?>"></option>
                                            </optgroup>
                                        <?php } ?>
                                    </datalist>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="hidden" class="form-control" id="user" name="user" value="">
                                    <div class="form-group col-md-12">
                                        <label>Nombre Usuario</label>
                                        <select id="id_user" name="id_user" class="form-control rounded-0 select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);">
                                            <option value="">Seleccionar Opción...</option>
                                            <?php foreach ($usuarios as $key => $usuario) {  ?>
                                                <option value="<?php echo $usuario->id_user; ?>"><?php echo $usuario->user; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <h5>DATOS DE PRODUCTOS</h5>
                                </div>
                                <div id="error_hijos" class="form-group col-md-6"></div>
                                <div class="form-group col-md-2" style="text-align: right;">
                                    <button id="btn_agregar" class="btn btn-guardar btn-style" style="background-color:#0056B3!important;"><i class="fas fa-plus-square"></i> &nbsp;&nbsp;&nbsp; Agregar</button>
                                </div>
                            </div>
                            <div id="inputs_duplica">
                            </div>
                            <div class="footer">
                                <button type="submit" id="btn_asignar_equipo" name="btn_asignar_equipo" class="btn btn-guardar btn-lg">Asignar</button>
                            </div>
                        </form>
                    </div>
                    <div id="usuarios_select" class="form-row"></div>
                    <div class="card-footer">
                        <a href="#">Salida</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- NUEVA ENTRADA -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Alta de Productos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove"> <i class="fas fa-times"></i> </button> -->
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="alta_articulo" method="post">
                            <div class="col-md-12 form-row">
                                <div class="form-group col-md-6">
                                    <label>Descripción</label>
                                    <input type="text" class="form-control" name="nombre_suministro" placeholder="" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Costo Unitario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="1.00" name="costo_equipo" id="costo_equipo" class="form-control" placeholder="500.00" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Codigo:</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>No. Requisicion</label>
                                    <input type="text" name="requicicion" id="requicicion" class="form-control" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Cantidad</label>
                                    <input type="number" class="form-control" name="stock" min="1" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Stock Minimo</label>
                                    <input type="number" class="form-control" name="stock_min" min="0" required>
                                </div>
                                <div class="form-group col-md-3" style="padding-top: 26px;text-align: right;">
                                    <button type="submit" id="alta_suministro" class="btn btn-guardar btn-lg">Guardar</button>
                                </div>
                            </div>
                            <div class="footer">
                            </div>
                        </form>
                    </div>
                    <!--  /.card-body -->
                    <div class="card-footer">
                        <a href="#">Productos</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- REPORTE -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default collapsed-card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Reportes</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>

                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div style="padding: 1rem 1rem;">
                            <h4>Reporte</h4>
                            <form id="form_reportes" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Tipo de Reporte</label>
                                        <select class="form-control rounded-0" name="tipo" id="tipo" onchange="validarReport()">
                                            <option value="">Selecciona una Opcion...</option>
                                            <option value="1">Entradas de Productos</option>
                                            <option value="2">Salidas de Productos</option>
                                        </select>
                                        <div id="error_tipo" class="text-danger"></div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Fecha Inicio</label>
                                        <input type="date" class="form-control rounded-0" id="fecha_inicial" name="fecha_inicial" value="" onchange="validarReport()">
                                        <div id="error_fecha_inicial" class="text-danger"></div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Fecha Final</label>
                                        <input type="date" class="form-control rounded-0" id="fecha_final" name="fecha_final" value="" onchange="validarReport()">
                                        <div id="error_fecha_final" class="text-danger"></div>
                                    </div>
                                    <div class="form-group col-md-3" style="text-align: right;">
                                        <button id="btn_reportes" style="margin-top:26px;" type="submit" class="btn btn-guardar btn-lg">Generar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--INVENTARIO -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Productos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
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
                        <a href="#">Inventario</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Asignaciones Responsivas -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Asignaciones</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body col-md-12">
                        <div class="container-fluid">
                            <table id="tabla_asignaciones" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref=""> </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="#">Inventario</a>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>


    <!--MODAL ACTUALIZACION -->
    <section>
        <div class="modal fade" id="actualizaModal" tabindex="-1" aria-labelledby="actualizaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Actualizar Articulo: <label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit_article" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="id_article" name="id_article" value="">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Descripción</label>
                                    <input type="text" class="form-control" id="description" name="description" placeholder="" required>
                                </div>
                                <!-- <div class="form-group col-md-3">
                                    <label>Cantidad</label>
                                    <input type="number" class="form-control" id="stock" name="stock" min="1" required>
                                </div> -->
                                <div class="form-group col-md-3">
                                    <label>Stock Minimo</label>
                                    <input type="number" class="form-control" id="stock_min" name="stock_min" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="actualizar_suministro" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!--MODAL ENTRADA -->
    <section>
        <div class="modal fade" id="entradaModal" tabindex="-1" aria-labelledby="entradaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="articulo_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="entrada_articulo" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="id_articulos" name="id_articulos" value="">
                            <input type="hidden" name="cantidad" id="cantidad">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad_entrada" name="cantidad_entrada" min="1" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>No. Requisición</label>
                                    <input type="number" class="form-control" id="requisicion_entrada" name="requisicion_entrada" min="1" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Código EPICOR</label>
                                    <input type="number" class="form-control" id="codigo_entrada" name="codigo_entrada" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button id="btn_entrada_articulo" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
    .custom-file-label::after {
        content: "Subir";
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/system/inventario_v2.js"></script>
<?= $this->endSection() ?>