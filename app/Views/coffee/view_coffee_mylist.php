<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitudes de Cafetería
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
.btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #1f2d3d;
    border-color:  #1f2d3d;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Solicitudes de Cafetería</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Cafetería</li>
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
          <h3 class="card-title">Mis Solicitudes</h3>
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
          <table id="mis_solicitudes_cafeteria" class="table table-bordered table-striped " role="grid" aria-describedby="permisos_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Mis Solicitudes</a>
        </div>
      </div>
            
    </div>
  </section>

  <section>
    <div class="modal fade" id="cancelarModal" tabindex="-1" aria-labelledby="Cancelar SolicitudModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cancelar Solicitud<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="cancelar" method="post">
              <div class="form-row">
                <input type="hidden" class="form-control" id="folio" name="folio" value="" readonly>
                <div class="form-group col-md-4">
                  <label for="fecha">Fecha & Hora</label>
                  <input type="text" class="form-control" id="fecha" name="fecha" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="depto">Sala</label>
                  <input type="text" class="form-control" id="sala" name="sala" readonly>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-10">
                  <label for="razon">Razon:</label>
                  <textarea class="form-control" name="razon" id="razon" cols="30" rows="3"></textarea>
                  <div id="error_razon" class=" text-danger"></div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="btn_cancelar" name="btn_cancelar" class="btn btn-guardar">Cancelar Solicitud</button>
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
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/coffee/my_request_v1.js"></script>
<?= $this->endSection() ?>