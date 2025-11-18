<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Permisos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
  .nav-icon {
    margin-right: 10px;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Entradas | Salidas | Vacaciones</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item">Reportes</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Reporte de Servicios</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="container">
              <form id="form_servicio_reporte" action="POST">
                <div class="row">
                  <div class="col-md-4">
                    <label for="servicio_tipo_reporte">Seleccionar Reporte</label>
                    <select class="form-control" id="servicio_tipo_reporte" onchange="limpiarError(this)">
                      <?php if (session()->id_user == 1063 || session()->id_user == 1) { ?>
                        <option value="1">Todos de 1</option>
                        <option value="2">Tickets IT</option>
                        <?php } ?>
                        <option value="">Seleccionar...</option>
                        <option value="3">Papeleria</option>
                        <option value="4">Valija</option>
                        <option value="5">Paqueteria</option>
                        <option value="6">Vehículos</option>
                        <option value="7">Cafeteria</option>
                    </select>
                    <div class="text-danger" id="error_servicio_tipo_reporte"></div>
                  </div>
                  <div id="cat_permiso_div"></div>
                  <div class="col-md-3">
                    <label for="servicio_fecha_ini">Fecha Inicial</label>
                    <input type="date" id="servicio_fecha_ini" class="form-control" onchange="limpiarError(this)">
                    <div class="text-danger" id="error_servicio_fecha_ini"></div>
                  </div>
                  <div class="col-md-3">
                    <label for="servicio_fecha_fin">Fecha Final</label>
                    <input type="date" id="servicio_fecha_fin" class="form-control" onchange="limpiarError(this)">
                    <div class="text-danger" id="error_servicio_fecha_fin"></div>
                  </div>
                  <div class="col-md-2 my-4">
                    <button type="submit" id="btn_servicio_reporte" class="btn btn-guardar btn-block"> <b style="font-size:22px"> Generar </b> </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="card-footer">
            <a href="#">Servicios</a>
          </div>
        </div>
      </div>
    </section>



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
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/corporation/corporativos_reports_v1.js"></script>

<?= $this->endSection() ?>