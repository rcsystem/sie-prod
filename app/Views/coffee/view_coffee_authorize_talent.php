<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Autorizar Sala James Walworth
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
          <h1 class="m-0">Solitudes de Sala James</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Cafetería</li>
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
          <h3 class="card-title">Solicitudes Cafetería Sala James</h3>
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
          <table id="tabla_autorizar_james" class="table table-bordered table-striped " role="grid" aria-describedby="authorizar" style="width:100%" ref=""></table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Coffee Break</a>
        </div>
      </div>
    </div>
  </section>
  <section>
        <div class="modal fade" id="jamesModal" tabindex="-1" aria-labelledby="jamesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Autorización Sala James<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="respuesta_coffee" method="post">
                          
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
                                    <label for="fecha">Fecha & Hora</label>
                                    <input type="text" class="form-control" id="fecha" name="fecha" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="depto">Sala</label>
                                    <input type="text" class="form-control" id="sala" name="sala" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado</label>
                                   <select name="estado" id="estado" class="form-control">
                                      <option value="">Seleccionar</option>
                                      <option value="6">Autorizada</option>
                                      <option value="7">Rechazada</option>
                                   </select>
                                   <div id="error_estado" class=" text-danger"></div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="res_coffee" name="res_coffee" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/coffee/authorize_james_v1.js"></script>

<?= $this->endSection() ?>