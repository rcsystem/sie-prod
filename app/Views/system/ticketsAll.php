<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Ticket's
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Ticket's</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Sistemas</li>
            <li class="breadcrumb-item active">Tickets</li>
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
          <h3 class="card-title">Ticket's de Actividades IT</h3>
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
            <table id="tabla_actividades_it" class="table table-bordered table-striped " role="grid" aria-describedby="actividades_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Actividades</a>
        </div>
      </div>
            <!-- SELECT2 EXAMPLE -->
           <!--  <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Ticket's de Actividades Usuarios</h3>
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
            <table id="tabla_todas_actividades" class="table table-bordered table-striped " role="grid" aria-describedby="actividades_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Actividades</a>
        </div>
      </div>
    </div>
  </section> -->
  
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.js"></script>
<!-- <script src="<?= base_url() ?>/public/js/system/ticketsAllit.js"></script> -->
<script src="<?= base_url() ?>/public/js/system/ticketsAll_v1.js"></script>
<?= $this->endSection() ?>