<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitud de Commedor
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/dining_room/main.min.css">
<link href='https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.css' rel='stylesheet'>
<link href='https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css' rel='stylesheet'>
<style>
    .fc .fc-daygrid-day-number {
 
    font-size: 1.3rem;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Solitud de Comedor</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Comedor</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <!-- Contenedor de contenido -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div  style="height:80%;
    margin: 0 auto;">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div id="calendar"></div> <!-- Calendario aquí -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</section>


</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/dining_room/main.min.js"></script>

<script src='<?= base_url() ?>/public/js/dining_room/locales/es.js'></script>
<script src="<?= base_url() ?>/public/js/dining_room/sg_dining_room_v1.js"></script>

<?= $this->endSection() ?>