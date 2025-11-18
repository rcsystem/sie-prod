<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Reportes Papelería
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reportes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item"><a href="#">Papelería</a></li>
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
                    <h3 class="card-title">Reporte de papelería</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formReportes" method="post">

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="categoria">Categoria</label>
                                <select name="categoria" id="categoria" class="form-control rounded-0"  onchange="validar()">
                                    <option value="">Seleccionar</option>
                                    <option value="1">Centro de Costo</option>
                                    <option value="2">Usuario</option>
                                </select>
                                <div id="error_categoria" name="error_categoria" class="text-danger"></div>
                            </div>
                            <div id="parametro" class="">

                            </div>
                            <div class="form-group col-md-3">
                                <label for="cantidad">Fecha Inicio</label>
                                <input type="date" class="form-control rounded-0" id="fecha_inicial" name="fecha_inicial" value=""  onchange="validar()">
                                <div id="error_fecha_inicial" name="error_fecha_inicial" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="minimo">Fecha Final</label>
                                <input type="date" class="form-control rounded-0" id="fecha_final" name="fecha_final" value=""  onchange="validar()">
                                <div id="error_fecha_final" name="error_fecha_final" class="text-danger"></div>
                            </div>

                        </div>
                        <hr>
                        <button id="generar_reporte" type="submit" class="btn btn-guardar btn-lg">Guardar</button>
                    </form>
                </div>

                <div class="card-footer">
                    <a href="#">Reporte de papelería </a>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="modal fade" id="inventarioModal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inventario<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="parametros_papeleria" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="folio">Folio</label>
                                    <input type="text" class="form-control" id="folio" name="folio" value="" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="producto">Producto</label>
                                    <input type="text" class="form-control" id="producto" name="producto" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="minimo">Stock Minimo</label>
                                    <input type="number" class="form-control" id="minimo" name="minimo" onkeypress="return validaNumericos(event)" min="1" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="maximo">Stock Maximo</label>
                                    <input type="number" class="form-control" id="maximo" name="maximo" onkeypress="return validaNumericos(event)" min="1" required>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="parametros" name="parametros" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/stationery/vh_reports_v1.js"></script>

<?= $this->endSection() ?>