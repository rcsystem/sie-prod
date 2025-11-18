<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Mis Requisiciones
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
                    <h1 class="m-0">Mis Requisiciones.</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Mis Requisiciones</li>
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
                <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_usuario_requisicion" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

            </table>
          </div>
        </div>
             
            </div>
    </section>
    <section>
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Requisición<label id="articulo"></label></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="resultado"></div>
        <form id="actualizar_requisicion" method="post">
          <input type="hidden" id="id_folio" name="id_folio" value="" >
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="tipo_personal">Tipo de personal</label>
              <input type="text" class="form-control" id="tipo_personal" name="tipo_personal" value="" required>
            </div>
            <div class="form-group col-md-4">
              <label for="puesto_solicitado">Puesto solicitado</label>
              <input type="text" class="form-control" id="puesto_solicitado" name="puesto_solicitado" required>
            </div>
          
          <div class="form-group col-md-4">
            <label for="personas_requeridas">Personas requeridas</label>
            <input type="number" class="form-control" id="personas_requeridas" name="personas_requeridas" onkeypress="return validaNumericos(event)" min="1" required>
          </div>
          <div class="form-group col-md-4">
              <label for="salario_inicial">Salario inicial</label>
              <input type="text" class="form-control" id="salario_inicial" name="salario_inicial" value="" onclick="ValidateDecimalInputs(this)" onchange="MASK(this,this.value,'-$##,###,##0.00',1)" placeholder="Ejemplo: 10000.99" required>
            </div>
            <div class="form-group col-md-4">
              <label for="salario_final">Salario final</label>
              <input type="text" class="form-control" id="salario_final" name="salario_final" onclick="ValidateDecimalInputs(this)" onchange="MASK(this,this.value,'-$##,###,##0.00',1)" placeholder="Ejemplo: 10000.99" required>
            </div>
          
          <div class="form-group col-md-4">
            <label for="horario_inicial">Horario inicial</label>
            <input type="time" class="form-control" id="horario_inicial" name="horario_inicial"  required>
          </div>
          <div class="form-group col-md-4">
            <label for="horario_final">Horario final</label>
            <input type="time" class="form-control" id="horario_final" name="horario_final" required>
          </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button id="actualiza_requisicion" name="salida_suministros" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/requisitions/per_user_v1.min.js"></script>
<?= $this->endSection() ?>