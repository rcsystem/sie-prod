<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Alta de Usuarios
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
          <h1 class="m-0">Alta Usuarios</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Alta Usuarios</li>
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
          <h3 class="card-title">Registrar Usuario</h3>
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
          <form id="registrar_usuarios_excel" method="post" enctype="multipart/form-data">
            <div class="form-group col-md-6">
              <label for="motivo_visita">Usuarios Excel</label>
              <div class="custom-file">
                <input type="file" class="file-up" id="usuarios_excel" name="usuarios_excel" lang="es" required>
              </div>
            </div>
            <button id="btn_registro_excel" type="submit" class="btn btn-guardar btn-lg">Importar Usuarios</button>
          </form>
        </div>

        <div class="card-footer">
          <a href="#">Usuarios</a>
        </div>
      </div>
      <!-- SELECT2 EXAMPLE II-->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Registrar Usuario</h3>
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
          <form id="registrar_usuario" method="post" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control rounded-0" id="nombre" name="nombre" required />
              </div>
              <div class="form-group col-md-4">
                <label for="ape_paterno">Apellido Paterno</label>
                <input type="text" class="form-control rounded-0" id="ape_paterno" name="ape_paterno" required />
              </div>
              <div class="form-group col-md-4">
                <label for="ape_materno">Apellido Materno</label>
                <input type="text" class="form-control rounded-0" id="ape_materno" name="ape_materno" required />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="correo">Email</label>
                <input type="email" class="form-control rounded-0" id="correo" name="correo" required />
              </div>
              <div class="form-group col-md-4">
                <label for="password">Password</label>
                <input type="text" class="form-control rounded-0" id="password" name="password" required>
              </div>
              <div class="form-group col-md-4">
                <label for="num_empleado">Numero de Empleado</label>
                <input type="number" class="form-control rounded-0" id="num_empleado" name="num_empleado" onkeypress="return validaNumericos(event)" min="1" required>
              </div>
              <div class="form-group col-md-4">
                <label for="fecha_ingreso">Fecha Ingreso</label>
                <input type="date" class="form-control rounded-0" id="fecha_ingreso" name="fecha_ingreso" required>
              </div>
              <div class="form-group col-md-4">
                <label for="depto">Departamento</label>
                <select id="depto" name="depto" class="form-control rounded-0 select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" required>
                  <option value="">Seleccionar...</option>
                  <?php foreach ($departament as $label => $opt) { ?>
                    <optgroup label="<?php echo $label; ?>">
                      <?php foreach ($opt as $id => $name) { ?>
                        <option value="<?= $id ?>"><?= $name ?></option>
                      <?php } ?>
                    </optgroup>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group col-md-4">
                <label for="tipo_usuario">Tipo de Usuario</label>
                <select id="tipo_usuario" name="tipo_usuario" class="form-control rounded-0" required>
                  <option value="">Seleccionar una Opción</option>
                  <option value="1">Administrativo</option>
                  <option value="2">Sindicalizado</option>
                </select>
              </div>

              <div class="form-group col-md-4">
                <label for="rol_usuario">Rol de Usuario</label>
                <select id="rol_usuario" name="rol_usuario" class="form-control rounded-0" required>
                  <option value="">Seleccionar una Opción</option>
                  <?php foreach ($roles as $key => $value) { ?>
                    <option value="<?= $value["id_rol"] ?>"><?= $value["rol"] ?></option>
                  <?php } ?>

                </select>
              </div>


              <div class="form-group col-md-4">
                <label for="puesto">Puesto</label>
                <select id="puesto" name="puesto" class="form-control rounded-0" required>
                  <option value="">Seleccionar una Opción</option>
                </select>
              </div>


              <div class="form-group col-md-4">
                <label for="puesto">Autoriza Permisos</label>
                <select id="autoriza" class="form-control" name="autoriza" required>
                  <option value="">Seleccionar</option>
                  <?php foreach ($autorizar as $key => $value) { ?>
                    <option value="<?= $value["id_user"] ?>"><?= $value["name"] . " " . $value["surname"] ?></option>
                  <?php } ?>
                </select>
              </div>


            </div>
            <!-- checkbox -->
            <div id="asignacion" class="form-row">
              <div class="form-group col-md-12">
                <label for="puesto">Asignar Departamentos</label>
                <select id="asigna_depto_gerente" class="js-example-basic-multiple" name="asignar_depto_gerente[]" multiple="multiple" style="width: 100%; height: calc(2.25rem + 2px);">
                  <?php foreach ($departament as $label => $opt) { ?>
                    <optgroup label="<?php echo $label; ?>">
                      <?php foreach ($opt as $id => $name) { ?>
                        <option value="<?= $id ?>"><?= $name ?></option>
                      <?php } ?>
                    </optgroup>
                  <?php } ?>
                </select>
              </div>
            </div>


            <button id="btn_registro" type="submit" class="btn btn-guardar btn-lg">Guardar usuario</button>
          </form>
        </div>

        <div class="card-footer">
          <a href="#">Usuarios</a>
        </div>
      </div>
    </div>
  </section>
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
<script src="<?= base_url() ?>/public/js/users/register_v2.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>

<?= $this->endSection() ?>