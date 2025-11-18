<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Mis Solicitudes
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/font-awesome.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .custom-file-label::after {
        content: "Subir";
    }

    .file-error {
        border-color: red;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Mis Solicitudes.</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Viaticos & Gastos</li>
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
          <h3 class="card-title">Mis Viáticos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_usuario_viaticos" class="table table-bordered table-striped " role="grid" aria-describedby="mis_viajes" style="width:100%" ref="">

            </table>
          </div>
        </div>

      </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Mis Gastos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_usuario_gastos" class="table table-bordered table-striped " role="grid" aria-describedby="mis_viajes" style="width:100%" ref="">

            </table>
          </div>
        </div>

      </div>
  </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/travels/my_travels_v1.js"></script>
<?= $this->endSection() ?>