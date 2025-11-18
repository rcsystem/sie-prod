<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Generar Permisos
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<style>
  .btn-primary:not(:disabled):not(.disabled).active,
  .btn-primary:not(:disabled):not(.disabled):active,
  .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #1f2d3d;
    border-color: #1f2d3d;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Permisos & Vacaciones</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Permisos & Vacaciones</li>
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
      <div id="card_permisos" class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Salidas y Entradas</h3>
          <div class="card-tools">
            <button id="colllapse_permisos" type="button" class="btn btn-tool" data-card-widget="collapse">
              <i id="icon_card_permisos" class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div id="body_permisos" class="card-body col-md-12">
          <div class="container-fluid">
            <form id="permisos" method="post">

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="permiso_usuario">Solicitante</label>
                  <input type="text" class="form-control rounded-0" id="permiso_usuario" name="permiso_usuario" value="<?= strtoupper(session()->name . " " . session()->surname); ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_departamento">Departamento</label>
                  <input type="text" class="form-control rounded-0" id="permiso_departamento" name="permiso_departamento" value="<?= (session()->departament == "ALMACEN VILLAHERMOSA") ? "DOS BOCAS" : session()->departament; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="area_operativa">Area Operativa</label>
                  <input type="text" class="form-control rounded-0" id="area_operativa" name="area_operativa" value="<?= session()->cost_center; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_puesto_trabajo">Puesto</label>
                  <input type="text" class="form-control rounded-0" id="permiso_puesto_trabajo" name="permiso_puesto_trabajo" value="<?= session()->job_position; ?>" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="permiso_num_nomina">Número de Nómina</label>
                  <input type="text" class="form-control rounded-0" id="permiso_num_nomina" name="permiso_num_nomina" value="<?= session()->payroll_number; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_tipo_empleado">Tipo de Empleado</label>
                  <input type="text" class="form-control rounded-0" id="permiso_tipo_empleado" name="permiso_tipo_empleado" value="<?= (session()->type_of_employee > 1) ? "Sindicalizado" : "Administrativo"; ?>" readonly>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                    <p><label>Tipo de Permiso</label></p>
                    <label id="tipo_laboral" class="btn btn-primary">
                      <input type="radio" id="laboral" class="" value=""> LABORAL
                    </label>
                    <label id="tipo_personal" class="btn btn-primary">
                      <input type="radio" id="personal" class="" value=""> PERSONAL
                    </label>
                    <div id="error_tipo" name="error_tipo" class="text-danger"></div>
                  </div>
                </div>
                <?php if (session()->type_of_employee > 1 || session()->id_user == 1063) { ?>
                  <div class="form-group col-md-4">
                    <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                      <p><label>Goce de Sueldo</label></p>
                      <label id="goce_sueldo_si" class="btn btn-primary">
                        <input type="radio" name="sueldo_si" id="sueldo_si" class="" value=""> SI
                      </label>
                      <label id="goce_sueldo_no" class="btn btn-primary">
                        <input type="radio" name="sueldo_no" id="sueldo_no" class="" value=""> No
                      </label>
                      <div id="error_sueldo" name="error_sueldo" class="text-danger"></div>
                    </div>
                  </div>
                <?php } ?>
              </div>
              <hr>
              <div class="card card-default collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Permiso de SALIDA</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>

                <div class="card-body">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="permiso_autoriza_salida">A las</label>
                      <input type="time" class="form-control rounded-0" id="permiso_autoriza_salida" name="permiso_salida" value="" onchange="validar()">
                      <div id="error_permiso_autoriza_salida" name="error_permiso_autoriza_salida" class="text-danger"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="permiso_dia_salida">Del día</label>
                      <input type="date" class="form-control rounded-0" id="permiso_dia_salida" name="permiso_dia_salida" onchange="validar()">
                      <div id="error_permiso_dia_salida" name="error_permiso_dia_salida" class="text-danger"></div>
                    </div>
                    <div class="form-group col-md-6">
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="card card-default collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Permiso de ENTRADA</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>

                <div class="card-body">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="permiso_autoriza_entrada">A las </label>
                      <input type="time" class="form-control rounded-0" id="permiso_autoriza_entrada" name="permiso_entrada">
                      <div id="error_permiso_autoriza_entrada" name="error_permiso_autoriza_entrada" class="text-danger"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="permiso_dia_entrada">Del día</label>
                      <?php

                      if (session()->type_of_employee > 1) { ?>
                        <input type="text" class="form-control rounded-0" id="permiso_dia_entradas" name="permiso_dia_entradas" data-provide="datepicker" onchange="validar()">
                      <?php } else { ?>
                        <input type="date" class="form-control rounded-0" id="permiso_dia_entrada" name="permiso_dia_entrada" onchange="validar()">
                      <?php } ?>
                      <div id="error_permiso_dia_entrada" name="error_permiso_dia_entrada" class="text-danger"></div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="card card-default collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Permiso de INASISTENCIA</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>

                <div class="card-body">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="permiso_inasistencia">Del día</label>
                      <?php if (session()->type_of_employee > 1) { ?>
                        <input type="text" class="form-control rounded-0" id="permiso_inasistencias" name="permiso_inasistencias" data-provide="datepicker" onchange="validar()">
                      <?php } else { ?>
                        <input type="date" class="form-control rounded-0" id="permiso_inasistencia" onchange="validar()">
                      <?php }

                      ?>
                      <div id="error_permiso_inasistencia" name="error_permiso_inasistencia" class="text-danger"></div>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="permiso_dia_inasistencia">Al día</label>
                      <input type="date" class="form-control rounded-0" id="permiso_dia_inasistencia">
                      <div id="error_permiso_dia_inasistencia" name="error_permiso_dia_inasistencia" class="text-danger"></div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="form-group col-md-12">
                <label for="permiso_observaciones">Observación:</label>
                <textarea class="form-control rounded-0" cols="30" min="3" id="permiso_observaciones" name="permiso_observaciones" onchange="validar()"></textarea>
                <div id="error_permiso_observaciones" name="permiso_observaciones" class="text-danger"></div>
              </div>
              <hr>
              <div class="row">
                <div style="width: auto !important;">
                  <button id="guardar_permiso" type="submit" class="btn btn-guardar btn-lg">Generar</button>
                </div>
                <div style="text-align:initial;padding-left: 10px;padding-top: 11px;">
                  <h5 id="cantidad_permisos" style="color:#B70923;"></h5>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div id="footer_permisos" class="card-footer">
          <a href="#">Permisos Entrada ó Salida</a>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div id="card_vacaciones" class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Vacaciones</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i id="icon_card_vacaciones" class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div id="body_vacaciones" class="card-body col-md-12">
          <div class="container-fluid">
            <form id="vacaciones" method="post">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="usuario">Solicitante</label>
                  <input type="email" class="form-control rounded-0" id="vacaciones_usuario" name="vacaciones_usuario" value="<?php foreach ($dias_vacaciones as $key => $value) {
                                                                                                                                echo strtoupper(session()->name . " " . session()->surname . " " . strtolower($value->second_surname));
                                                                                                                              }  ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="departamento">Departamento</label>
                  <input type="text" class="form-control rounded-0" id="vacaciones_departamento" name="vacaciones_departamento" value="<?= (session()->departament == "ALMACEN VILLAHERMOSA") ? "DOS BOCAS" : session()->departament; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="puesto_trabajo">Puesto</label>
                  <input type="text" class="form-control rounded-0" id="vacaciones_puesto_trabajo" name="vacaciones_puesto_trabajo" value="<?= session()->job_position; ?>" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="num_nomina">Número de Nómina</label>
                  <input type="text" class="form-control rounded-0" id="vacaciones_num_nomina" name="vacaciones_num_nomina" value="<?= session()->payroll_number; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="fecha_ingreso">Fecha de ingreso</label>
                  <input type="text" class="form-control rounded-0" id="vacaciones_fecha_ingreso" name="vacaciones_fecha_ingreso" value="<?= session()->date_admission; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="tipo_empleado">Tipo de Empleado</label>
                  <input type="text" class="form-control rounded-0" id="vacaciones_tipo_empleado" name="vacaciones_tipo_empleado" value="<?= (session()->type_of_employee > 1) ? "Sindicalizado" : "Administrativo"; ?>" readonly>
                </div>
              </div>
              <hr>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="dias_disponibles">Dias disponibles</label>
                  <input type="text" class="form-control rounded-0" id="vacaciones_dias_disponibles" name="vacaciones_dias_disponibles" value="<?php foreach ($dias_vacaciones as $key => $value) {
                                                                                                                                                  echo $value->vacation_days_total;
                                                                                                                                                } ?>" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="vacaciones_dias_disfrutar">Dias a disfrutar</label>
                  <input type="number" class="form-control rounded-0" id="vacaciones_dias_disfrutar" name="vacaciones_dias_disfrutar" onkeypress="return validaNumericos(event)" min="1">
                  <div id="error_vacaciones_dias_disfrutar" name="error_vacaciones_dias_disfrutar" class="text-danger"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="vacaciones_inicio_dias">Del día</label>
                  <?php if (session()->type_of_employee > 1) { ?>
                    <input type="text" class="form-control rounded-0" id="vacaciones_inicio_dia" name="inicio_dias" data-provide="datepicker">
                  <?php } else { ?>
                    <input type="date" class="form-control rounded-0" id="vacaciones_inicio_dias" name="inicio_dias">
                  <?php } ?>
                  <div id="error_vacaciones_inicio_dias" name="error_vacaciones_inicio_dias" class="text-danger"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="vacaiones_fin_dias">Al día</label>
                  <input type="date" class="form-control rounded-0" id="vacaciones_fin_dias" name="fin_dias">
                  <div id="error_vacaciones_fin_dias" name="error_vacaciones_fin_dias" class="text-danger"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="regresar_activiades">Debiendo regresar a sus actividades:</label>
                  <input type="date" class="form-control rounded-0" id="vacaciones_regresar_actividades" name="vacaciones_regresar_actividades">
                  <div id="error_vacaciones_regresar_actividades" name="error_vacaciones_regresar_actividades" class="text-danger"></div>
                </div>
              </div>
              <button type="submit" id="permiso_vacaciones" class="btn btn-guardar btn-lg">Generar</button>
            </form>
          </div>
        </div>

        <div id="footer_vacaciones" class="card-footer">
          <a href="#">Vacaciones</a>
        </div>
      </div>
    </div>
  </section>
  <input type="hidden" name="tipo" id="tipo" value="<?= session()->type_of_employee ?>">
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>/public/js/permissions/permissions_generate_v2.js"></script>
<script>
  var date = new Date();
  date.setDate(date.getDate() + 1);
  $('#permiso_dia_entradas').datepicker({
    startDate: date
  });

  $('#permiso_inasistencias').datepicker({
    startDate: date
  });

  $('#vacaciones_inicio_dia').datepicker({

    startDate: date

  });
</script>
<?= $this->endSection() ?>