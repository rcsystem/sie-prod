<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administrar Menus
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administrar Vehiculos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Solicitud de Vehiculos</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Alta de Vehiculos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="guardar_auto" method="post" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="modelo">Modelo del Vehiculo</label>
                                    <input type="text" class="form-control rounded-0" id="modelo" name="modelo" value="" onchange="validar()">
                                    <div id="error_modelo" name="error_modelo" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="placas">Placas</label>
                                    <input type="text" class="form-control rounded-0" id="placas" name="placas" value="" onchange="validar()">
                                    <div id="error_placas" name="error_placas" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="imagen">Imagen del Vehiculo</label>
                                    <input type="file" class="form-control rounded-0" id="imagen" name="imagen" size="1024" onchange="validar()">
                                    <div id="error_imagen" name="error_imagen" class="text-danger"></div>
                                </div>
                            </div>
                            <hr>
                            <button id="btn_guardar_auto" type="submit" class="btn btn-guardar btn-lg">Guardar</button>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Formulario alta de Vehiculos </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Inventario de Vehiculos
                    </h3>
                </div>
                <div class="card-body">
                    <table id="tabla_inventario_vehiculos" class="table table-bordered table-striped " role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Inventario </a>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/js/cars/administra_vehicules_v2.js"></script>
<?= $this->endSection() ?>