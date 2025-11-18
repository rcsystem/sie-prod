<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Permisos
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
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Mis Permisos & Vacaciones</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Permisos & Vacaciones</li>
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
          <h3 class="card-title">Entradas & Salidas</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_usuario_permisos" class="table table-bordered table-striped " role="grid" aria-describedby="permisos_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Mis Permisos</a>
        </div>
      </div>
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Vacaciones</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_usuario_vacaciones" class="table table-bordered table-striped " role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Mis Vacaciones</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="fechasVacacionesModal" tabindex="-1" aria-labelledby="fechasVacacionesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Fechas de Vacaciones</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="div_dias">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <?php if (session()->type_of_employee == 2) { ?>
    <section class="content">
      <div class="container-fluid">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Pago de Tiempo</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <table id="tbl_pago_tiempo" class="table table-bordered table-striped dataTable display" cellspacing="0" role="grid" aria-describedby="usuarios_info" style="width:100%" ref="">
            </table>
          </div>
          <div class="card-footer">
            <a href="#">Pago de Tiempo</a>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/permissions/permissions_users_v3-3.js"></script>
<?= $this->endSection() ?>