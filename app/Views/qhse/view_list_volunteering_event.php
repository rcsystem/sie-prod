<?php ?>
<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitudes de Voluntariado.
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>

.btn-group>.btn-group:not(:first-child), .btn-group>.btn:not(:first-child) {
    margin-left: 20px;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Solicitudes de Voluntariado.</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Solicitudes</li>
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
          <h3 class="card-title">Solicitudes Voluntarias</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
        <button id="enviarSeleccionados" class="btn btn-success">Enviar Seleccionados</button>


          <table id="tabla_solicitudes_voluntarias" class="table table-bordered table-striped dataTable display" cellspacing="0" role="grid" aria-describedby="voluntarias_info" style="width:100%" ref="">
          </table>
        </div>

        <div class="card-footer">
          <a href="#">Solicitudes</a>
        </div>
      </div>
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Solicitudes Permanentes</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="tabla_solicitudes_permanentes" class="table table-bordered table-striped dataTable display" cellspacing="0" role="grid" aria-describedby="permanentes_info" style="width:100%" ref="">
          </table>
        </div>

        <div class="card-footer">
          <a href="#">Solicitudes</a>
        </div>
      </div>
    </div>
  </section>
 
  <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verPermisosModal" aria-modal="false">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verPermisosModal">Documento PDF Permisos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe id="carga_pdf" src="" width="100%" height="700px"></iframe>
      </div>
    </div>
  </div>
</div>

 


</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/qhse/hse_voluntariado_v1.js"></script>
<?= $this->endSection() ?>