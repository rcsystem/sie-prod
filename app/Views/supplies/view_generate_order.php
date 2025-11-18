<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Generar Orden
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .show>.btn-primary.dropdown-toggle {
        color: #fff;
        background-color: #1f2d3d;
        border-color: #1f2d3d;
    }

    .custom-file-label::after {
        content: "Subir";
    }

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Generar Orden</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">TBH</li>
                        
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
                    <h3 class="card-title">Generar Orden</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div id="content-form" class="container-fluid">
                        <form id="horas_extras" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="id_usuario" name="id_usuario" value="<?= session()->id_user ?>">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="permiso_num_nomina">Num. Nómina</label>
                                    <input type="text" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="<?= session()->payroll_number; ?>" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="permiso_usuario">Solicitante</label>
                                    <input type="text" class="form-control rounded-0" id="usuario" name="usuario" value="<?= ucwords(session()->name . " " . session()->surname); ?>" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="permiso_departamento">Departamento</label>
                                    <input type="text" class="form-control rounded-0" id="departamento" name="departamento" value="<?= session()->departament; ?>" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="permiso_puesto_trabajo">Puesto</label>
                                    <input type="text" class="form-control rounded-0" id="puesto" name="puesto" value="<?= session()->job_position; ?>" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="orden_compra">Orden de Compra</label>
                                    <input type="text" class="form-control rounded-0" id="orden_compra" name="orden_compra" value=""/>
                                    <div id="error_orden_compra" class="text-danger"></div>
                                    
                                </div>
                                
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <button id="btn-agregar-item" class="btn btn-info" type="button"><i class="fas fa-file-alt"></i> Nuevo Item</button>
                                </div>
                                <div id="resultado" class="error col-md-8"></div>
                            </div>
                            <div id="estado" class="col-md-12"></div>
                            <div id="alumnos">
                                <div id="duplica" class="agrega-item">
                                    <div id="item-duplica"></div>
                                </div>
                                <div id="tiempo_extra">
                                    <div id="extra_1" class="form-row ">
                                        <div class="form-group col-md-2">
                                            <label id="partida" for="num_partida_1">Código</label>
                                            <input type="text" class="form-control rounded-0" id="num_partida_1" name="num_partida[]" onchange="escuchar(1)" onkeyup="javascript:this.value=this.value.toUpperCase();" title="Ingresa un Codigo Valido" >
                                        <div id="error_num_partida_1" class="text-danger"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label  for="tipo_1">Tipo</label>
                                            <input type="text" class="form-control rounded-0" id="tipo_1" name="tipo[]" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label  for="diametro">Diametro</label>
                                            <input type="text" class="form-control rounded-0" id="diametro_1" name="diametro[]" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="clase_1">Clase</label>
                                            <input type="text" class="form-control rounded-0" id="clase_1" name="clase[]" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="tiempo_1">Tiempo</label>
                                            <input type="text" class="form-control rounded-0" id="tiempo_1" name="tiempo[]" value="" readonly>
                                        </div>                                       
                                        <div class="form-group col-md-6">
                                            <label  for="desc_1">Descripción</label>
                                            <textarea id="desc_1" name="desc[]"  class="form-control rounded-0" cols="4" rows="5" readonly></textarea>
                                        </div>
                                        <input type="hidden" id="desc_breve_1" name="desc_breve[]"  class="form-control rounded-0" value="" />
                                        <div class="form-group col-md-3">
                                            <label for="figura_1">Figura</label>
                                            <input type="text" class="form-control rounded-0" id="figura_1" name="figura[]" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label  for="cantidad_1">Num. de Piezas</label>
                                            <input type="text" class="form-control rounded-0" id="cantidad_1" name="cantidad[]" value="" />
                                            <div id="error_cantidad_1" class="text-danger"></div>
                                        </div>
                                        <div id="btn_eliminar_1" class="form-group col-md-1"></div>
                                        <hr>
                                    </div>
                                    
                                </div>
                            </div>
                    </div>


                    <button id="guardar_permiso" type="submit" class="btn btn-guardar btn-lg btn-block">Generar</button>
                    </form>
                </div>

                <div class="card-footer">
                    <a href="#">Solicitud</a>
                </div>
            </div>
        </div>

    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/supplies/solicitud_v2.js"></script>
<?= $this->endSection() ?>