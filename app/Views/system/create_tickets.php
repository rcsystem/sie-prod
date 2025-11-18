<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Ticket´s
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    #draw-canvas {
        border: 2px dotted #CCCCCC;
        border-radius: 5px;
        cursor: crosshair;
    }

    .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .show>.btn-primary.dropdown-toggle {
        color: #fff;
        background-color: #1f2d3d;
        border-color: #1f2d3d;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ticket's de Servicio.</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item">Sistemas</li>
                        <li class="breadcrumb-item active">Ticket´s</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Tickets IT</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <form id="generar_ticket_it" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="id_usuario" name="id_usuario" value="" />
                        <div class="col-md-12 form-row">

                            <div class="form-group col-md-3">
                                <label for="usuario">Usuario</label>

                                <select name="usuario_it" id="usuario_it" class="form-control rounded-0">
                                    <option value="">Seleccionar...</option>
                                    <option value="Adolfo Gonzalez">Adolfo Gonzalez</option>
                                    <option value="Rafael Cruz">Rafael Cruz</option>
                                    <option value="Omar Canales">Omar Canales</option>
                                    <option value="Alexis Diego">Alexis Diego</option>
                                    <option value="Horus Rivas">Horus Rivas</option>
                                    <option value="Carolina Mesas">Carolina Mesas</option>
                                    <option value="Sergio del Carmen Amézquita Benítez">Sergio del Carmen Amézquita</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="fecha_actividad">Fecha de Actividad</label>
                                <input type="date" class="form-control rounded-0" id="fecha_actividad" name="fecha_actividad" value="" required />
                            </div>
                            <div id="complejo_it" class="form-group col-md-3">
                                <div class="text-center btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <p><label>Complejidad</label></p>
                                    <label id="complejo_standard" class="btn btn-primary" title="menos de 2 hrs.">
                                        <input type="radio" name="complejidad_it" id="complejidad_it" class="radio_it" value="1" required> Standard
                                    </label>
                                    <label id="complejo_medio" class="btn btn-primary" title="de 2  a 4 hrs.">
                                        <input type="radio" name="complejidad_it" id="complejidad_it" class="radio_it" value="2" required> Medio
                                    </label>
                                    <label id="complejo_advance" class="btn btn-primary" title="mas de 4 hrs.">
                                        <input type="radio" name="complejidad_it" id="complejidad_it" class="radio_it" value="3" required> Advance
                                    </label>
                                </div>
                            </div>
                            <div id="home_office_it" class="text-center form-group col-md-3">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <p><label>¿Se podría hacer en Home Office? </label></p>
                                    <label id="home_si" class="btn btn-primary">
                                        <input type="radio" name="home_it" id="home_it" class="radio_it" value="1" required> Sí
                                    </label>
                                    <label id="home_no" class="btn btn-primary">
                                        <input type="radio" name="home_it" id="home_it" class="radio_it" value="2" required> No
                                    </label>
                                </div>
                            </div>


                            <div class="form-group col-md-12">
                                <label for="actividad_it">Actividad a realizar</label>
                                <textarea name="actividad_it" id="actividad_it" cols="4" rows="5" class="form-control" required></textarea>
                            </div>
                        </div>

                        <div class="footer">
                            <button type="submit" id="guardar_ticket_it" name="guardar_ticket_it" class="btn btn-guardar btn-lg btn-block">Generar Actividad</button>
                        </div>
                    </form>
                </div>
                <!--  /.card-body -->
                <div class="card-footer">
                    <a href="#">Ticket´s IT</a>
                </div>
            </div>

        </div>
    </section>



</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/system/create_tickets_v1.js"></script>
<?= $this->endSection() ?>