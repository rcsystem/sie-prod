<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitudes de Vehiculos
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Todas Solitudes de Vehiculos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Solicitud de Vehiculos</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- REPORTES -->
  <section class="content">
    <div class="container-fluid">
      <div class="card card-default  collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Reportes Vehiculos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <form id="form_reportes" method="post">
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="tipo_reportes">Tipo de Reporte</label>
                  <select id="tipo_reportes" class="form-control rounded-0" required>
                    <option value="1">Todos</option>
                    <option value="2">Por Vehiculo</option>
                  </select>
                </div>
                <div id="parametro" class=""></div>
                <div class="form-group col-md-3">
                  <label for="cantidad">Fecha Inicio</label>
                  <input type="date" class="form-control rounded-0" id="fecha_inicial" required>
                </div>
                <div class="form-group col-md-3">
                  <label for="minimo">Fecha Final</label>
                  <input type="date" class="form-control rounded-0" id="fecha_final" required>
                </div>
              </div>
              <hr>
              <button id="btn_reportes" type="submit" class="btn btn-guardar btn-lg">Generar</button>
            </form>
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
          <h3 class="card-title">Solicitudes Vehiculos</h3>
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
            <table id="tabla_solicitudes" class="table table-bordered table-striped " role="grid" aria-describedby="authorizar" style="width:100%" ref=""></table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Solicitud de Vehiculos</a>
        </div>
      </div>
    </div>
  </section>
  <!-- MODAL -->
  <section>
    <div class="modal fade" id="vehiculo_Modal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Asignación de Vehiculo<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="solicitud_de_vehiculo" method="post">

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="folio">Folio</label>
                  <input type="text" class="form-control" id="folio" name="folio" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="usuario">Usuario</label>
                  <input type="text" class="form-control" id="usuario" name="usuario" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="viaje">Viaje</label>
                  <input type="text" class="form-control" id="viaje" name="viaje" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="observacion">Observaciones</label>
                  <textarea name="observacion" id="observacion" style="height: 106px !important;" cols="3" rows="2" class="form-control" onchange="validar()"></textarea>
                  <div id="error_observacion" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="auto">Asignar Vehiculo</label>
                  <select name="auto" id="auto" class="form-control" onchange="placas()">
                    <option value="">Seleccionar...</option>
                    <?php foreach ($carros as $key => $value) { ?>

                      <option value="<?= $value->id_car ?>"><?= $value->model ?></option>

                    <?php  }   ?>
                  </select>
                  <div id="error_auto" class="text-danger"></div>
                  <div id="placasDiv"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="estado">Estado</label>
                  <select name="estado" id="estado" class="form-control" onchange="validar()">
                    <option value="">Seleccionar</option>
                    <option value="4">Asignado</option>
                    <option value="3">Rechazada</option>
                  </select>
                  <div id="error_estado" class=" text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <input type="hidden" class="form-control" id="tipo" name="tipo" readonly>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="autorisar_cars" name="autorizar_cars" class="btn btn-guardar">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <input type="hidden" name="tipo" id="tipo" value="<?= session()->type_of_employee ?>">
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/cars/request_all_v2.js"></script>

<?= $this->endSection() ?>