<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administrar Usuarios
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administrar Usuarios</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url('dashboard')?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
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
          <h3 class="card-title">Usuarios</h3>
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
              <table id="tabla_usuarios" class="table table-bordered table-striped " role="grid" aria-describedby="usuarios_info" style="width:100%" ref="">
          </table>
        </div>

        <div class="card-footer">
          <a href="#">Usuarios</a>
        </div>
      </div>
    </div>
  </section>
  <section>
        <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="editUser" method="post">
                            <input type="hidden" id="id_usuario" name="id_usuario" value="">
                            <div class="form-row">
                            <div class="form-group col-md-4">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="apellido_p">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_p" name="apellido_p" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="apellido_m">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_m" name="apellido_m" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fecha_admision">Fecha de Admisión</label>
                                    <input type="date" class="form-control" id="fecha_admision" name="fecha_admision" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_empleado">Tipo de Empleado</label>
                                    <select name="tipo_empleado" id="tipo_empleado" class="form-control" required>
                                        <option value="1">Administrativo</option>
                                        <option value="2">Sindicalizado</option>
                                    </select>
                                   
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="dias_vacaciones">Dias de Vacaciones</label>
                                    <input type="number" class="form-control" id="dias_vacaciones" name="dias_vacaciones" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="anios_laborados">Años Laborados</label>
                                    <input type="number" class="form-control" id="anios_laborados" name="anios_laborados" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" value="" required>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="actualiza_usuario" name="actualiza_requisicion" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url()?>/public/js/users/index.js"></script>
<?= $this->endSection() ?>