<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Autorizar Solicitudes
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
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
          <h1 class="m-0">Solitudes de Paquetería</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Paquetería</li>
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
          <h3 class="card-title">Solicitudes Paquetería</h3>
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
            <table id="tbl_autorizar" class="table table-bordered table-striped " role="grid" aria-describedby="authorizar" style="width:100%" ref=""></table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Solicitud de Paquetería</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="paqueteria_Modal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorización con Guía de Envío<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="form_paqueteria" method="post">

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
                  <label for="tipo_">Tipo de Envio</label>
                  <input type="text" class="form-control" id="tipo_" name="tipo_" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="empresa_R">Remitente</label>
                  <input type="text" class="form-control" id="empresa_R" name="empresa_R" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="empresa_D">Destino</label>
                  <input type="text" class="form-control" id="empresa_D" name="empresa_D" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="estado">Estado</label>
                  <select name="estado" id="estado" class="form-control" onchange="validar()">
                    <option value="">Seleccionar</option>
                    <option value="2">Autorizada</option>
                    <option value="3">Rechazada</option>
                  </select>
                  <div id="error_estado" class=" text-danger"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="coment">Comentario:</label>
                  <textarea class="form-control" cols="30" rows="3" id="coment" name="coment" value="" onchange="validar()"></textarea>
                  <div id="error_coment" class="text-danger"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="guia">Guía de Envío</label>
                  <div class="custom-file">
                    <label for="guia" id="lbl_guia" class="custom-file-label" style="color:#BDBDBD;">documento.pdf</label>
                    <input type="file" class="custom-file-input" accept="application/pdf" id="guia" name="guia" size="1024" onchange="validar()">
                    <div id="error_guia" name="error_guia" class="text-danger"></div>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="btn_form_paquetiria" name="btn_form_paquetiria" class="btn btn-guardar">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!--   <input type="hidden" name="tipo" id="tipo" value="<?= session()->type_of_employee ?>"> -->
</div>
<style>
  .file-up {
    border: 1px solid;
    border-color: #CED4Da;
    padding: 5px;
    width: 100%;
  }
</style>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/packer/authorize_v1.js"></script>

<?= $this->endSection() ?>