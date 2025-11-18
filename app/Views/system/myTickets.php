<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Mis Ticket's
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Mis Tickets</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Sistemas</li>
            <li class="breadcrumb-item active">Tickets</li>
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
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Tickets de Actividades</h3>
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
            <table id="tabla_tickets_actividades" class="table table-bordered table-striped " role="grid" aria-describedby="actividades_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Actividades</a>
        </div>
      </div>
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Tickets de Usuarios</h3>
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
            <table id="tabla_tickets_usuarios" class="table table-bordered table-striped " role="grid" aria-describedby="actividades_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Actividades</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="actividad_Modal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">TICKET'S<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="actualizar_ticket" method="post">

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="folio">Folio</label>
                  <input type="text" class="form-control" id="folio" name="folio" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="usuario">Usuario</label>
                  <input type="text" class="form-control" id="usuario" name="usuario" readonly>
                </div>
              </div>
              <div class="form-group col-md-12">
                <label for="actividad">Actividad</label>
                <textarea class="form-control" id="actividad" name="actividad" onchange="valida()"></textarea>
                <div id="error_actividad" class=" text-danger"></div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="btn_actualizar_ticket" name="actualizar_ticket" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/system/mytickets_v1.js"></script>
<?= $this->endSection() ?>