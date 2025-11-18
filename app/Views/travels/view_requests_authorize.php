<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Autorización de Viáticos y Gastos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/font-awesome.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Viáticos y Gastos. </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Viajes</li>
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
          <h3 class="card-title">Autorización de Viáticos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_autorizar_viaticos" class="table table-bordered table-striped " role="grid" aria-describedby="mis_viaticos" style="width:100%" ref="">

            </table>
          </div>
        </div>

      </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Autorización de Gastos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_autorizar_gastos" class="table table-bordered table-striped " role="grid" aria-describedby="mis_gastos" style="width:100%" ref="">

            </table>
          </div>
        </div>

      </div>
  </section>

  <section>
    <div class="modal fade" id="autorizarGastoModal" tabindex="-1" aria-labelledby="autorizarGastoModalLabel" style="overflow-y: scroll;" aria-hidden="true">
      <div class="modal-dialog modal-xl gastos">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Gasto<label id="folio_gasto"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_gasto" method="post">
              <input type="hidden" id="id_folio" name="id_folio" value="">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="usuario">Usuario</label>
                  <input type="text" class="form-control" id="usuario" name="usuario" value="" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="motivo">Motivo</label>
                  <input type="text" class="form-control" id="motivo" name="motivo" disabled>
                </div>

                <div class="form-group col-md-4">
                  <label for="inicio">Inicio</label>
                  <input type="text" class="form-control" id="inicio" name="inicio" disabled>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="termino">Termino</label>
                  <input type="text" class="form-control" id="termino" name="termino" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="presupuesto">Presupuesto</label>
                  <input type="text" class="form-control" id="presupuesto" name="presupuesto" value="" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="autorizacion">Autorización</label>
                  <select name="autorizacion" id="autorizacion" class="form-control">
                    <option value="">Seleccionar...</option>
                    <option value="2">Autorizar</option>
                    <option value="3">Cancelar</option>
                  </select>
                  <div id="error_autorizacion" class="text-danger"></div>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-7">
                  <table class="tab2" style="margin-top:30px;width:90%;">
                    <tr style="background:#8b9eaf;">
                      <td colspan="2" style="font-weight:bold;font-size:16px;text-align:center;">Tipos de Gastos</td>
                    </tr>
                    <tbody id="listado_gastos"></tbody>

                  </table>
                </div>
                <div id="cancelDiv" class="form-group col-md-5"></div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="btn_autorizar_gasto" name="btn_autorizar_gasto" class="btn btn-guardar">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="autorizarViaticoModal" tabindex="-1" aria-labelledby="autorizarVaiticoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl viaticos">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Viáticos<label id="folio_viaticos"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_viaticos" method="post">
              <input type="hidden" id="id_folio_v" name="id_folio_v" value="">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="usuario">Usuario</label>
                  <input type="text" class="form-control" id="v_usuario" name="v_usuario" value="" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="motivo">Motivo</label>
                  <input type="text" class="form-control" id="v_motivo" name="v_motivo" disabled>
                </div>

                <div class="form-group col-md-4">
                  <label for="inicio">Inicio</label>
                  <input type="text" class="form-control" id="v_inicio" name="v_inicio" disabled>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="termino">Termino</label>
                  <input type="text" class="form-control" id="v_termino" name="v_termino" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="presupuesto">Presupuesto</label>
                  <input type="text" class="form-control" id="v_presupuesto" name="v_presupuesto" value="" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="autorizacion">Autorización</label>
                  <select name="v_autorizacion" id="v_autorizacion" class="form-control">
                    <option value="">Seleccionar...</option>
                    <option value="2">Autorizar</option>
                    <option value="3">Cancelar</option>
                  </select>
                  <div id="error_autorizacion_v" class="text-danger"></div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="btn_autorizar_viaticos" name="btn_autorizar_gasto" class="btn btn-guardar">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <!-- Modal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verViaticosModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verViaticosModal">Documento PDF Viáticos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <iframe id="carga_pdf" src="" width="100%" height="700px"></iframe>
          </div>
        </div>
      </div>
    </div>
  </section>



</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/dist/js/pages/jquery.velocity.js"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/travels/travels_authorized_v1-0.js"></script>
<?= $this->endSection() ?>