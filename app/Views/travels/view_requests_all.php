<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Todas las Solicitudes
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/font-awesome.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Solicitudes de Viáticos & Gastos.</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Viajes</li>
          </ol>
        </div>
      </div>
    </div>
  </div>  
  
  <section class="content">
    <div class="container-fluid">      
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Viáticos & Gastos.</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_todos_viajes" class="table table-bordered table-striped " role="grid" aria-describedby="todos_viajes" style="width:100%" ref="">
            </table>
          </div>
        </div>

      </div>
  </section>
  <div class="modal fade" id="aprovarViajeModal" tabindex="-1" aria-labelledby="aprovarViajeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Aprovar Viaje<label id="articulo"></label></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="aprovar_viaje" method="post">
          <div class="modal-body">
            <input type="hidden" id="id_viaje" name="id_viaje" value="">
            <input type="hidden" id="id_user" name="id_user" value="">
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="usuario">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="" readonly>
              </div>
              <div class="form-group col-md-3">
                <label for="motivo">Motivo</label>
                <input type="text" class="form-control" id="motivo" name="motivo" readonly>
              </div>

              <div class="form-group col-md-3">
                <label for="origen">Origen</label>
                <input type="text" class="form-control" id="origen" name="origen" readonly>
              </div>

              <div class="form-group col-md-3">
                <label for="destino">Destino</label>
                <input type="text" class="form-control" id="destino" name="destino" readonly>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="presupuesto">Presupuesto</label>
                <input type="text" class="form-control" id="presupuesto" name="presupuesto" onchange="validar()">
                <div id="error_presupuesto" class="text-danger"></div>
                <div id="montoDiv"></div>
              </div>
              <div class="form-group col-md-8">
                <label for="observacion">Observacion del Viaje</label>
                <textarea style="height:7rem!important;" class="form-control" id="observacion" name="observacion" cols="4" rows="3" readonly></textarea>
              </div>
            </div>
            <div id="anticipoDiv" class="row"></div>
            <hr>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="aprovacion">Aprovación</label>
                <select name="estado" id="aprovacion" class="form-control" onchange="opciones()">
                  <option value="">Seleccionar...</option>
                  <option value="3">Aprovar</option>
                  <option value="6">Rechazar</option>
                </select>
                <div id="error_aprovacion" class="text-danger"></div>
              </div>
              <div id="comentarioDiv" class="form-group col-md-8"></div>
            </div>
          </div>
          <input type="hidden" name="firma_user" id="firma_user">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="btn_aprovar_viaje" name="btn_aprovar_viaje" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/travels/travels_all_v1.js"></script>
<?= $this->endSection() ?>