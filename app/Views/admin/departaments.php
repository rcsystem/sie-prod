<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Departmentos y Centros de Costo
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Deptos & Centro de Costo</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Deptos</li>
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
          <h3 class="card-title">Alta de Departamentos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="container">
            <div id="resultado" class="error"></div>
            <form id="form_depto" action="" method="post">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="departamento">Departamento</label>
                  <input type="text" class="form-control" id="departamento" name="departamento" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="centro_costo">Centro de Costo</label>
                  <input type="number" class="form-control" id="centro_costo" name="centro_costo" value=""  onkeypress="return validaNumericos(event)" min="1" required>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label for="area">Area</label>
                <input type="text" class="form-control" id="area" required>
              </div>
              <button class="btn btn-guardar btn-lg">Guardar</button>
            </form>
          </div>
        </div>
        <!--  /.card-body -->
        <div class="card-footer">
          <a href="#">Alta de Departamentos</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Departamentos</h3>
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
          <table id="tabla_deptos" class="table table-bordered table-striped " role="grid" aria-describedby="usuarios_info" style="width:100%" ref="">
          </table>
        </div>


        <div class="card-footer">
          <a href="#">Centros de costo</a>
        </div>
      </div>
    </div>
  </section>
  <section>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Actualizar Departamento</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <section class="content">
              <div class="container-fluid">
                <!-- SELECT2 EXAMPLE -->

                <!-- /.card-header -->
                <div class="card-body">
                  <div class="container">
                    <div id="edit_resultado" class="error"></div>
                    <form id="editar_depto" action="post">
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="departamento">Departamento</label>
                          <input type="text" class="form-control" id="editar_departamento" name="departamento" value="" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="centro_costo">Centro de Costo</label>
                          <input type="number" class="form-control" id="editar_centro_costo" name="centro_costo" value="" onkeypress="return validaNumericos(event)" min="1" required>
                        </div>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="area">Area</label>
                        <input type="text" class="form-control" id="editar_area" value="" required>
                      </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary">Actualizar</button>
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
<script src="<?= base_url() ?>/public/js/departaments/index.js"></script>
<?= $this->endSection() ?>