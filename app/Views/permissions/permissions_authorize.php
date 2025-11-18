<?php ?>
<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Autorizar Permisos
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Autorización de Permisos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Autorizar Permisos</li>
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
          <h3 class="card-title">Permisos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="tabla_autorizar_permisos" class="table table-bordered table-striped dataTable display" cellspacing="0" role="grid" aria-describedby="usuarios_info" style="width:100%" ref="">
          </table>
        </div>

        <div class="card-footer">
          <a href="#">Permisos</a>
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
        <div class="card-body">
          <table id="tabla_autorizar_vacaciones" class="table table-bordered table-striped dataTable display" cellspacing="0" role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
          </table>
        </div>

        <div class="card-footer">
          <a href="#">Vacaciones</a>
        </div>
      </div>
    </div>
  </section>
  <section>

    <div class="modal fade" id="autorizarModal" tabindex="-1" aria-labelledby="autorizarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Permiso: <label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="autorizar_permiso" method="post">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="id_folio">Folio</label>
                  <input type="number" class="form-control" id="id_folio" name="id_folio" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="usuario">Usuario</label>
                  <input type="text" class="form-control" id="usuario" name="usuario" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="motivo">Motivo</label>
                  <textarea name="motivo" id="motivo" cols="4" rows="4" class="form-control" readonly></textarea>
                </div>

                <div class="form-group col-md-6">
                  <label for="description_supplies">Estatus</label>
                  <select name="estatus" id="estatus" class="form-control" required>
                    <option value="">Seleccionar una Opción</option>
                    <option value="1">Autorizada</option>
                    <option value="2">Rechazada</option>
                  </select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <button id="actualizar_permiso" class="btn btn-guardar">Actualizar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="autorizarVacacionesModal" tabindex="-1" aria-labelledby="autorizarVacacionesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Vacaciones</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="autorizar_vacaciones" method="post">
              <input type="hidden" id="dias" name="dias">
              <input type="text" id="num_nomina" name="num_nomina">
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="id_folio_vacaciones">Folio</label>
                  <input type="number" class="form-control" id="id_folio_vacaciones" name="id_folio_vacaciones" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="usuario_vacaciones">Usuario</label>
                  <input type="text" class="form-control" id="usuario_vacaciones" name="usuario_vacaciones" readonly>
                </div>
                <div class="col-md-3" id="div_btn" style="text-align: center;"></div>
                <div class="form-group col-md-4">
                  <label for="vacaciones_del">Vacaciones del:</label>
                  <input name="vacaciones_del" id="vacaciones_del" cols="4" rows="4" class="form-control" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="vacaciones_al">Vacaciones al:</label>
                  <input name="vacaciones_al" id="vacaciones_al" cols="4" rows="4" class="form-control" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="regresando">Regresando:</label>
                  <input name="regresando" id="regresando" cols="4" rows="4" class="form-control" readonly />
                </div>
                <div class="form-group col-md-6" id="div_modal_a_cargo">
                  <label for="modal_a_cargo">Dejando responsabilidades a cargo de:</label>
                  <input type="text" class="form-control" id="modal_a_cargo" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="estatus_vacaciones">Estatus</label>
                  <select name="estatus_vacaciones" id="estatus_vacaciones" class="form-control" required>
                    <option value="">Seleccionar una Opción</option>
                    <option value="1">Autorizada</option>
                    <option value="2">Rechazada</option>
                  </select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <button id="actualizar_vacaciones" class="btn btn-guardar">Actualizar</button>
          </div>
          </form>
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
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/permissions/permissions_authorize_v5-3.js"></script>
<?= $this->endSection() ?>