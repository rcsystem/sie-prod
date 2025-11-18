<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
HSE | Autorizar
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

  .custom-file-label::after {
    content: "Subir";
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"> HSE | Autorizar</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Proveedores</li>
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
          <h3 class="card-title">Estadias</h3>
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
            <table id="tabla_permiso_estadias" class="table table-bordered table-striped " role="grid" aria-describedby="estadias" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Estadias</a>
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
          <h3 class="card-title">Permiso Entrada</h3>
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
            <table id="tabla_permiso_proveedores" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Proveedores</a>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Horario Obscuro</h3>
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
            <table id="tabla_tiempos_extras" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Proveedores</a>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="modal fade" id="autorizarModal" tabindex="-1" aria-labelledby="autorizarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Permisos<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_permiso" method="post">
              <input type="hidden" id="id_folio" name="id_folio" value="">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="tipo_personal">Proveedor</label>
                  <input type="text" class="form-control" id="proveedor" name="proveedor" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="puesto_solicitado">Dia de visita</label>
                  <input type="date" class="form-control" id="dia_visita" name="dia_visita" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="salario_inicial">Hora de llegada(tentativa)</label>
                  <input type="time" class="form-control" id="hora_llegada" name="hora_llegada" value="" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Persona a quien visita</label>
                  <input type="text" class="form-control" id="persona_visita" name="persona_visita" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Departamento</label>
                  <input type="text" class="form-control" id="departamento" name="departamento" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Autorizar</label>
                  <select class="form-control" name="autorizacion" id="autorizacion" required>
                    <option value="">Seleccionar Opción</option>
                    <option value="2">Autorizar</option>
                    <option value="3">Rechazar</option>

                  </select>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="autorizar_permisos" name="sautorizar_permisos" class="btn btn-guardar">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
  </section>

  <section>
    <div class="modal fade" id="autorizarTiemposModal" tabindex="-1" aria-labelledby="autorizarTiemposModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Horario Obscuro<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_tiempo_extra" method="post">
              <input type="hidden" id="id_folio_extra" name="id_folio_extra" value="">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="tipo_personal">Usuario</label>
                  <input type="text" class="form-control" id="usuario_extra" name="usuario_extra" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="puesto_solicitado">Departamento</label>
                  <input type="text" class="form-control" id="depto_extra" name="depto_extra" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Día de Horario Obscuro</label>
                  <input type="date" class="form-control" id="dia_extra" name="dia_extra" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="salario_inicial">Hora de llegada</label>
                  <input type="time" class="form-control" id="hora_llegada_extra" name="hora_llegada_extra" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Hora de Salida</label>
                  <input type="time" class="form-control" id="hora_salida_extra" name="hora_salida_extra" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Autorizar</label>
                  <select class="form-control" name="autorizacion" id="autorizacion_extra" required>
                    <option value="">Seleccionar Opción</option>
                    <option value="2">Autorizar</option>
                    <option value="3">Rechazar</option>

                  </select>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="autorizar_extra" name="autorizar_extra" class="btn btn-guardar">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
  </section>

  <section>
    <div class="modal fade" id="autorizarEstadiasModal" tabindex="-1" aria-labelledby="autorizarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Estadias<label id="articulos"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_estadias" method="post">
              <input type="hidden" id="id_folio_estadia" name="id_folio_estadia" value="">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="estadia_proveedor">Proveedor</label>
                  <input type="text" class="form-control" id="estadia_proveedor" name="estadia_proveedor" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="inicio_estadia">Inicio Estadia</label>
                  <input type="date" class="form-control" id="inicio_estadia" name="inicio_estadia" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="fin_estadia">Final de Estadia</label>
                  <input type="date" class="form-control" id="fin_estadia" name="fin_estadia" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="salario_inicial">Hora de llegada(tentativa)</label>
                  <input type="time" class="form-control" id="estadia_hora_llegada" name="estadia_hora_llegada" value="" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Persona a quien visita</label>
                  <input type="text" class="form-control" id="estadia_persona_visita" name="estadia_persona_visita" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Departamento</label>
                  <input type="text" class="form-control" id="estadia_departamento" name="estadia_departamento" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="personas_requeridas">Autorizar</label>
                  <select class="form-control" name="estadia_autorizacion" id="estadia_autorizacion" required>
                    <option value="">Seleccionar Opción</option>
                    <option value="2">Autorizar</option>
                    <option value="3">Rechazar</option>

                  </select>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="autorizar_estadia" name="autorizar_estadia" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.js"></script>
<script src="<?= base_url() ?>/public/js/qhse/authorize_v1.js"></script>
<?= $this->endSection() ?>