<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Servicio Médico
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
          <h1 class="m-0">Todos los Registros</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Servicio Médico</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Permisos Incapacidades Médicas</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_solicitudes" class="table table-bordered table-striped " role="grid" aria-describedby="authorizar" style="width:100%" ref=""></table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Servicio Médico</a>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Consultas Médicas</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_consultas" class="table table-bordered table-striped " role="grid" aria-describedby="authorizar" style="width:100%" ref=""></table>
          </div>
        </div>
        <div class="card-footer">
          <a href="#">Servicio Médico</a>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Examenes Médicos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_examenes" class="table table-bordered table-striped " role="grid" aria-describedby="authorizar" style="width:100%" ref=""></table>
          </div>
        </div>
        <div class="card-footer">
          <a href="#">Servicio Médico</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="cerrar_consulta_Modal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-notes-medical"></i> Cerrar Consulta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="form_cerrar_consulta" method="post">
              <input type="hidden" name="id_request" id="id_request">
              <div class="form-row">
                <div class="col-md-4">
                  <label>Nombre Pasiente:</label>
                  <input type="text" id="modal_nombre" class="form-control" disabled>
                </div>
                <div class="col-md-4">
                  <label>Tipo de Atencion:</label>
                  <input type="text" id="modal_tipo_atencion" class="form-control" disabled>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="calificacion_accidente">Calificación:</label>
                  <select name="calificacion_accidente" id="calificacion_accidente" class="form-control" required>
                    <option value="">Opciones...</option>
                    <option value="SI DE TRABAJO">SI DE TRABAJO</option>
                    <option value="SI DE TRAYECTO">SI DE TRAYECTO</option>
                    <option value="ENF. GENERAL">ENF. GENERAL</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="tipo_incapacidad">Tipo de Incapacidad:</label>
                  <select name="tipo_incapacidad" id="tipo_incapacidad" class="form-control" required>
                    <option value="">Opciones...</option>
                    <option value="Inicial">Inicial</option>
                    <option value="Subsiguente">Subsiguente</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="estado">Estado:</label>
                  <select name="estado" id="estado" class="form-control">
                    <option value="1">Proceso</option>
                    <option value="2">Finalizado</option>
                  </select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="btn_cerrar_consulta" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/medical/request_all_v1.js"></script>

<?= $this->endSection() ?>