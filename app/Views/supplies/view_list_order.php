<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Ordenes Suministros
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/dataTables-check/dataTables.checkboxes.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/dataTables-check/awesome-bootstrap-checkbox.css">

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
          <h1 class="m-0">Ordenes de Suministros</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Suministros</li>
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
          <h3 class="card-title">Ordenes</h3>
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
            <form id="frm-sum" method="post">
              <button id="btn-reporte" class="btn btn-success btn-flat">Excel</button>
              <table id="tabla_ordenes_sunimistros" class="table table-bordered table-striped " role="grid" aria-describedby="ordenes_info" style="width:100%" ref="">
              </table>
            </form>
          </div>

        </div>

        <div class="card-footer">
          <a href="#">Mis Permisos</a>
        </div>
      </div>

    </div>
  </section>

  <section>
    <div class="modal fade" id="listarItemsModal" tabindex="-1" data-backdrop='static' data-keyboard="false" aria-labelledby="listarItemsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">ORDEN DE SUMINISTRO: <label id="ordenes_compras"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado" class="form-group"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <!--  <button type="submit" id="res_papeleria" name="res_papeleria" class="btn btn-guardar">Guardar</button> -->
          </div>
        </div>

      </div>
    </div>
  </section>


  <section>
    <div class="modal fade" id="listarModal" tabindex="-1" aria-labelledby="listarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">CERRAR PARTIDA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="form_cierre" method="post">
            <div class="modal-body">
              <div class="form-group col-md-12">
                <input type="hidden" id="items" name="items" />
                <label for="fecha_cierre">Fecha de Cierre</label>
                <input type="date" id="fecha_cierre" name="fecha_cierre" class="form-control">
                <div id="error_fecha_cierre" class="text-danger"></div>
              </div>
              <div class="form-group col-md-12">
                <label for="fecha_cierre">Observaciones</label>
                <textarea name="obs_partida" id="obs_partida" cols="3" rows="3" class="form-control"></textarea>
                <div id="error_obs" class="text-danger"></div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" id="cerrar_partida" name="cerrar_partida" class="btn btn-guardar">Guardar</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </section>


  <section>
    <div class="modal fade" id="editRequestModal" tabindex="-1" data-backdrop='static' data-keyboard="false" aria-labelledby="editRequestModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">EDITAR ORDEN <label id="ordenes_compras"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="editar_orden" method="post">
              <input type="hidden" id="id_requisicion" name="id_requisicion" value="">
              <div class="col-md-12">
                <div class="form-group col-md-12">
                <label for="orden_compra">Orden de Compra</label>
                  <input type="text" id="orden_compra" name="orden_compra" class="form-control" value=""/>
                  <div id="error_orden_compra" class="text-danger"></div>
                </div>
                <div class="row">
                <div class="form-group col-md-6">
                  <label for="fecha_formalizacion">Fecha Formalización de Orden</label>
                  <input type="date" class="form-control rounded-0" id="fecha_formalizacion" name="fecha_formalizacion" value=""/>
                </div>
                <div class="form-group col-md-6">
                  <label for="fecha_estatus">Fecha Estatus de Trabajo</label>
                  <input type="date" class="form-control rounded-0" id="fecha_estatus" name="fecha_estatus" value=""/>
                </div>
              </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="guardar_orden" name="guardar_orden" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/plugins/dataTables-check/dataTables.checkboxes.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/supplies/listado_v2.js
"></script>
<?= $this->endSection() ?>