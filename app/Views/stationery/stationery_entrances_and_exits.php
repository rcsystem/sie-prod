<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Entradas & Salidas
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Entradas & Salidas</h1>
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

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Entradas de papelería</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabla_entradas_inventario" class="table table-bordered table-striped " role="grid" aria-describedby="vacaciones_entradas" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Entradas Inventario </a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Salidas de papelería</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabla_salidas_inventario" class="table table-bordered table-striped " role="grid" aria-describedby="vacaciones_salidas" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Salidas Inventario </a>
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
                                <div class="form-group col-md-4">
                                    <label for="folio">Folio</label>
                                    <input type="text" class="form-control" id="folio" name="folio" value="" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="producto">Producto</label>
                                    <input type="text" class="form-control" id="producto" name="producto" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="unidad">Unidad de Medida</label>
                                    <input type="text" class="form-control" id="unidad_medida" name="unidad_medida" value="">
                                    <div id="error_unidad_medida" name="error_unidad_medida" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="minimo1">Stock Minimo</label>
                                    <input type="number" class="form-control" id="minimo1" name="minimo1" onkeypress="return validaNumericos(event)" min="1" onchange="validaModal()">
                                    <div id="error_minimo1" name="error_minimo1" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="maximo1">Stock Maximo</label>
                                    <input type="number" class="form-control" id="maximo1" name="maximo1" onkeypress="return validaNumericos(event)" min="1" onchange="validaModal()">
                                    <div id="error_maximo1" name="error_maximo1" class="text-danger"></div>
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
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.js"></script>
<script src="<?= base_url() ?>/public/js/stationery/entradas_v1.js"></script>
<?= $this->endSection() ?>