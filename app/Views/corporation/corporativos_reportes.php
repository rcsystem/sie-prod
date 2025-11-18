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
  .swal2-popup {
    font-size: 2rem !important;
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
          <a href="<?= base_url();?>/corporativo/reportes" target="_blank">Reporte de Corporativo</a>
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
                        <?php } ?>
                        <option value="">Seleccionar...</option>
                        <option value="2">Tickets IT</option>
                        <option value="8">Servicios Generales</option>
                        <option value="3">Papeleria</option>
                        <option value="4">Valija</option>
                        <option value="5">Paqueteria</option>
                        <option value="6">Vehículos</option>
                        <option value="7">Cafeteria</option>
                        <option value="8">Reporte Indicadores</option>
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

  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title"> Ver Permisos y Vacaciones Anteriores </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <form id="form_data_table" method="post">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Ver:</label>
                  <select id="tipo_anterior" class="form-control" required>
                    <option value="">Opcion...</option>
                    <option value="1">Permisos</option>
                    <option value="2">Vacaciones</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label>Fecha Inicio:</label>
                  <input type="date" id="fecha_inicio_b" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                  <label>Fecha Final:</label>
                  <input type="date" id="fecha_fin_b" class="form-control" onchange="limpiarError(this)">
                  <div class="text-danger" id="error_fecha_fin_b"></div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label>Buscar por:</label>
                  <select id="tipo_busqueda" class="form-control" required>
                    <option value="1">Todos</option>
                    <option value="2">Nomina</option>
                    <option value="3">Departamento</option>
                  </select>
                </div>
                <div id="opcion_div" class="form-group col-md-4"></div>
                <div class="form-group col-md-4" style="text-align:right;margin-top: 2rem;">
                  <button id="btm_data_table" type="submit" class="btn btn-guardar">BUSCAR DATOS</button>
                </div>
              </div>
            </form>
            <hr>
            <div id="table_div"></div>
          </div>
        </div>
        <div class="card-footer">
          <a href="#">Reportes</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Generar Reporte</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="container">
            <div id="reporte_fechas" class=" col-md-12 ">
              <form id="formReporte" action="POST">
                <div class="d-flex align-items-center flex-column justify-content-center h-100">
                  <div class="form-group col-md-6">
                    <label for="tipo_reporte">Seleccionar Reporte</label>
                    <select class="form-control" id="tipo_reporte" required>
                      <option value="">Seleccionar</option>
                      <option value="1">Salidas y Entradas</option>
                      <option value="2">Vacaciones</option>
                    </select>
                  </div>
                  <div id="cat_permiso_div"></div>
                  <div class="form-group col-md-6">
                    <label for="fecha_ini">Fecha Inicial</label>
                    <input type="date" id="fecha_ini" class="form-control" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="fecha_fin">Fecha Final</label>
                    <input type="date" id="fecha_fin" class="form-control" onchange="limpiarError(this)">
                    <div class="text-danger" id="error_fecha_fin"></div>
                  </div>
                  <div class="form-group col-md-6 my-4">
                    <button type="submit" id="generarReporte" class="btn btn-guardar btn-block"> <b style="font-size:22px"> Generar </b> </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <a href="#">Solicitudes</a>
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