<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Estacionamiento
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<!-- <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css"> -->
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Asinacion de Cajon</h1>
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
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Marbetes Registrados hoy</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tbl_todos_entradas" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref=""></table>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://cdn.rawgit.com/janantala/angular-qr/master/lib/qrcode.js" type="text/javascript"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/parking/assignment_drawer.js"></script>
<!-- <script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script> -->
<?= $this->endSection() ?>