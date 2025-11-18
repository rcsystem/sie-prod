<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Alta de Usuarios
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
  .custom-file-label::after {
    content: "Subir";
  }
</style>

<div class="content-wrapper">
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
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Registrar Usuario</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
          <form id="registrar_usuarios_excel" method="post" enctype="multipart/form-data">

            <div class="form-group col-md-8">
              <label for="motivo_visita">Usuarios Excel</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="usuarios_excel" name="usuarios_excel" lang="es" required>
                <label class="custom-file-label" for="customFileLang"> Importar Excel</label>
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
      <!-- <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Asignar Modulos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="nomina">Numero de Empleado</label>
              <input type="number" class="form-control rounded-0" id="nomina" name="nomina" onkeypress="return validaNumericos(event)" min="1" onchange="modulos()" required>
              <div id="error_nomina" class="text-danger"></div>
            </div>
          </div>
          <form id="acceso_modulo" method="post" enctype="multipart/form-data">
            <h4 id="h4"></h4>
            <input type="hidden" id="id_user" name="id_user">
            <div id="accesos" class="form-row"></div>
            <button id="btn_acceso_modulo" type="submit" class="btn btn-guardar btn-lg">Guardar Accesos</button>
          </form>
        </div>

        <div class="card-footer">
          <a href="#">Usuarios</a>
        </div>
      </div> -->

      <!-- SELECT2 EXAMPLE II-->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Registrar Usuario</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
          <form id="registrar_usuario" method="post" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="empresa">Empresa</label>
                <select name="empresa" id="empresa" class="form-control rounded-0" required>
                  <option value="">Seleccionar...</option>
                  <option value="4">INVAL</option>
                  <option value="1">WALWORTH</option>
                  <option value="2">GRUPO WALWORTH</option>
                  <!-- <option value="3">Ax One</option> -->
                </select>
              </div>
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
              <div class="form-group col-md-4">
                <label for="ape_materno">Número de Seguridad Social</label>
                <input type="text" class="form-control rounded-0" id="nss" name="nss" required />
              </div>
              <div class="form-group col-md-4">
                <label for="ape_materno">CURP</label>
                <input type="text" class="form-control rounded-0" id="curp" name="curp" required />
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
                <select id="depto" name="depto" class="form-control rounded-0 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" required>
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
                <label for="area_operative">Area Operativa</label>
                <select id="area_operative" name="area_operative" class="form-control rounded-0 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" required>
                  <option value="">Seleccionar...</option>
                  <?php foreach ($areas as $key) { ?>
                    <option value="<?= $key->id_area ?>"><?= $key->area ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="clace_cost">Clave de Centro de Costos</label>
                <select id="clace_cost" name="clace_cost" class="form-control rounded-0 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" required>
                  <option value="">Seleccionar...</option>
                  <?php foreach ($centros as $key) { ?>
                    <option value="<?= $key->id_cost_center ?>"><b><?= $key->clave_cost_center ?></b> <?= $key->cost_center ?></option>
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
                <select class="form-control" id="rol_usuario" name="rol_usuario" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required>
                  <option value="">Seleccionar una Opción</option>
                  <?php foreach ($roles as $key => $value) { ?>
                    <option value="<?= $value["id_rol"] ?>"><?= $value["rol"] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="puesto">Puesto</label>
                <select class="form-control" id="puesto" name="puesto" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required>
                  <option value="">Seleccionar una Opción</option>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="puesto">Grado Gerarquia</label>
                <select class="form-control" id="grado" name="grado" data-toggle="validation" data-required="true" data-message="Grado." style="width: 100%;" required>
                  <option value="">Seleccionar una Opción</option>
                  <option value="1">I</option>
                  <option value="2">II</option>
                  <option value="3">III</option>
                  <option value="4">IV</option>
                  <option value="5">V</option>
                  <option value="6">VI</option>
                  <option value="7">VII</option>
                  <option value="8">VIII</option>
                  <option value="9">IX</option>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="puesto">Tipo de Contrato</label>
                <select id="contrato" class="form-control rounded-0" name="contrato" required>
                  <option value="">Seleccionar</option>
                  <option value="1">Planta</option>
                  <option value="2">30 días</option>
                  <option value="3">60 días</option>
                  <option value="4">90 días</option>
                </select>
              </div>
              <div id="fecha_contrato"></div>
              <div class="form-group col-md-4">
                <label for="puesto">Jefe Directo</label>
                <select class="form-control" id="autoriza" name="autoriza" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required>
                  <option value="">Seleccionar</option>
                  <?php foreach ($autorizar as $key => $value) { ?>
                    <option value="<?= $value["id_user"] ?>"><?= $value["name"] . " " . $value["surname"] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label for="puesto">Director</label>
                <select class="form-control" id="director" name="director" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required>
                  <option value="">Seleccionar</option>
                  <?php foreach ($director as $key => $value) { ?>
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
            <hr>
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
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/users/register_user_v2.js"></script>

<?= $this->endSection() ?>