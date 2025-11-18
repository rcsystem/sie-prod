<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Autorizar Requisiciones
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Autorizar Requisiciones</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Requisiciones</a></li>
                        <li class="breadcrumb-item active">Autorizar Requisiciones</li>
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
          <h3 class="card-title">Requisiciones</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
              <table id="tabla_autorizar_requisiciones" class="table table-bordered table-striped " role="grid" aria-describedby="usuarios_info" style="width:100%" ref="">
          </table>
        </div>

        <div class="card-footer">
          <a href="#">Requisiciones</a>
        </div>
      </div>
    </div>
  </section>
  <section>

    <div class="modal fade" id="autorizarModal" tabindex="-1" aria-labelledby="autorizarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Requisición: <label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="autorizar_requisicion" method="post">
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
                  <label for="puesto_solicitado">Puesto Solicitado</label>
                  <input type="text" class="form-control" id="puesto_solicitado" name="puesto_solicitado" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="personas_requeridas">Personas Requeridas</label>
                  <input type="text" class="form-control" id="personas_requeridas" name="personas_requeridas" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="motivo">Motivo de Requisición</label>
                  <input type="text" class="form-control" id="motivo" name="motivo" readonly>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="actualizar_requisicion" class="btn btn-guardar">Actualizar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <!-- Modal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verPermisosModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Autorizar Requisición</h5>
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
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url()?>/public/js/requisitions/authorization_v1.js"></script>
<?= $this->endSection() ?>
