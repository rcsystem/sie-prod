<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Todas las Solicitudes Valija
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
          <h1 class="m-0">Valijas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Valijas</li>
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
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Reportes de Valija</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!--<button type="button" class="btn btn-tool" data-card-widget="remove"> <i class="fas fa-times"></i> </button> -->
          </div>
        </div>
        <div class="card-body">
          <form id="formReportes" method="post">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="cantidad">Fecha Inicio</label>
                <input type="date" class="form-control rounded-0" id="fecha_inicial" name="fecha_inicial" value="" onchange="validar()">
                <div id="error_fecha_inicial" name="error_fecha_inicial" class="text-danger"></div>
              </div>
              <div class="form-group col-md-4">
                <label for="minimo">Fecha Final</label>
                <input type="date" class="form-control rounded-0" id="fecha_final" name="fecha_final" value="" onchange="validar()">
                <div id="error_fecha_final" name="error_fecha_final" class="text-danger"></div>
              </div>
              <div class="form-group col-md-4" style="text-align: right;">
                <button id="generar_reporte" style="margin-top:26px;" type="submit" class="btn btn-guardar btn-lg">Generar</button>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer">
          <a href="#">Reportes</a>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Todas las Solicitudes </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_valija_todas_solicitudes" class="table table-bordered table-striped " role="grid" aria-describedby="permisos_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Todas las Solicitudes</a>
        </div>
      </div>

    </div>
  </section>
  <section>

    <div class="modal fade" id="autorizarValijaModal" tabindex="-1" aria-labelledby="autorizarValijaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Valija <label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="autorizar_valija" method="post">

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="id_folio_valija">Folio</label>
                  <input type="number" class="form-control" id="id_valija" name="id_valija" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="usuario_valija">Usuario</label>
                  <input type="text" class="form-control" id="usuario_valija" name="usuario_valija" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="origen_valija">Origen:</label>
                  <input name="origen_valija" id="origen_valija" type="text" class="form-control" readonly />
                </div>
                <div class="form-group col-md-6">
                  <label for="destino_valija">Destino:</label>
                  <input name="destino_valija" id="destino_valija" type="text" class="form-control" readonly />
                </div>
                <div class="form-group col-md-6">
                  <label for="regresando">Observación:</label>
                  <textarea name="observacion" id="observacion" class="form-control" cols="10" rows="2" readonly></textarea>
                </div>

                <div class="form-group col-md-6">
                  <label for="estatus_valija">Estatus</label>
                  <select name="estatus_valija" id="estatus_valija" class="form-control" required>
                    <option value="">Seleccionar una Opción</option>
                    <option value="2">Concluida</option>
                    <option value="3">Rechazada</option>
                  </select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="actualizar_vacaciones" class="btn btn-guardar">Actualizar</button>
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
<script src="<?= base_url() ?>/public/js/valija/valija_requests_all_v1.js"></script>
<?= $this->endSection() ?>