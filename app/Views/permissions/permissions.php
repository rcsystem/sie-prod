<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Generar Permisos
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<style>
  .btn-opcion {
    margin-top: 4px;
  }

  .btn-primary:not(:disabled):not(.disabled).active,
  .btn-primary:not(:disabled):not(.disabled):active,
  .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #1f2d3d;
    border-color: #1f2d3d;
  }

  .btn-outline-festive {
    color: #CB6BD3;
    border-color: #CB6BD3;
  }

  .btn-outline-festive:not(:disabled):not(.disabled).active,
  .btn-outline-festive:not(:disabled):not(.disabled):active,
  .show>.btn-outline-festive.dropdown-toggle {
    color: #fff;
    background-color: #CB6BD3;
    border-color: #CB6BD3;
  }

  .btn-outline-festive:hover {
    color: #fff;
    background-color: #CB6BD3;
    text-decoration: none;
  }

  .btn-outline-traffic {
    color: #16C9BA;
    border-color: #16C9BA;
  }

  .btn-outline-traffic:not(:disabled):not(.disabled).active,
  .btn-outline-traffic:not(:disabled):not(.disabled):active,
  .show>.btn-outline-traffic.dropdown-toggle {
    color: #fff;
    background-color: #16C9BA;
    border-color: #16C9BA;
  }

  .btn-outline-traffic:hover {
    color: #fff;
    background-color: #16C9BA;
    text-decoration: none;
  }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 sie-font-bold">Permisos & Vacaciones</h1>
          <!-- <h5 class="m-0 sie-font-bold">Desarrollo</h5> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Permisos & Vacaciones</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div id="card_permisos" class="card card-default ">
        <div class="card-header">
          <h3 class="card-title sie-font-bold">Salidas y Entradas</h3>
          <div class="card-tools">
            <button id="colllapse_permisos" type="button" class="btn btn-tool" data-card-widget="collapse">
              <i id="icon_card_permisos" class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div id="body_permisos" class="card-body col-md-12">
          <div class="container-fluid">
            <form id="permisos" method="post">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="permiso_usuario">Solicitante</label>
                  <input type="text" class="form-control " id="permiso_usuario" name="permiso_usuario" value="<?= strtoupper(session()->name . " " . session()->surname); ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_departamento">Departamento</label>
                  <input type="text" class="form-control " id="permiso_departamento" name="permiso_departamento" value="<?= (session()->departament == "ALMACEN VILLAHERMOSA") ? "DOS BOCAS" : session()->departament; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="area_operativa">Area Operativa</label>
                  <input type="text" class="form-control " id="area_operativa" name="area_operativa" value="<?= session()->cost_center; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_puesto_trabajo">Puesto</label>
                  <input type="text" class="form-control " id="permiso_puesto_trabajo" name="permiso_puesto_trabajo" value="<?= session()->job_position; ?>" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="permiso_num_nomina">Número de Nómina</label>
                  <input type="text" class="form-control " id="permiso_num_nomina" name="permiso_num_nomina" value="<?= session()->payroll_number; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_tipo_empleado">Tipo de Empleado</label>
                  <input type="text" class="form-control " id="permiso_tipo_empleado" name="permiso_tipo_empleado" value="<?= (session()->type_of_employee > 1) ? "Sindicalizado" : "Administrativo"; ?>" readonly>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <p><label>Seleccione su Turno:</label></p>
                  <Select id="turno" name="turno" class="form-control" onchange="pintarHorarios(),dateLibrary()">
                    <option value="">Selecciona....</option>
                    <?php foreach ($turnos as $key) { ?>
                      <option value="<?php echo $key->turn; ?>"><?php echo $key->name_turn; ?></option>
                    <?php } ?>
                  </Select>
                  <div id="div_horario"></div>
                  <div id="error_turno" class="text-danger"></div>
                </div>
                <div class="col-md-4" style="text-align: center;">
                  <label style="margin-bottom: 24px;">Tipo de Permiso</label>
                  <input type="hidden" name="tipo_permiso" id="tipo_permiso">
                  <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-primary btn-opcion">
                      <input type="radio" onclick="tipoPermiso(1)"> LABORAL
                    </label>
                    <label class="btn btn-outline-primary btn-opcion">
                      <input type="radio" onclick="tipoPermiso(2)"> PERSONAL
                    </label>
                   
                    <label class="btn btn-outline-traffic btn-opcion" id="btn_opcion_4" style="display: none;">
                      <input type="radio" onclick="tipoPermiso(4)">
                      <p id="p_opcion_4" style="margin-bottom: 0 !important;">POR TRAFICO</p>
                    </label>
                    <label class="btn btn-outline-traffic btn-opcion" id="btn_opcion_6" > 
                      <input type="radio" onclick="tipoPermiso(6)"> REFORESTACIÓN 2025
                    </label>

                    <label class="btn btn-outline-traffic btn-opcion" id="btn_opcion_4">
                      <input type="radio" onclick="tipoPermiso(8)">ATENCIÓN PSICOLÓGICA
                    </label>
                 
                    <!-- <label class="btn btn-outline-festive btn-opcion" id="btn_opcion_4">
                      <input type="radio" onclick="tipoPermiso(7)">DIA DE LA MUJER
                    </label> -->
                  </div>
                  <div class="text-danger" id="error_tipo_permiso"></div>
                </div>
                <input type="hidden" name="goce_sueldo" id="goce_sueldo">
                <?php if (session()->type_of_employee == 2) { ?>
                  <div class="form-group col-md-4">
                    <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                      <p><label>Goce de Sueldo</label></p>
                      <label class="btn btn-outline-primary btn-opcion">
                        <input type="radio" onclick="goce(1)"> SI
                      </label>
                      <label class="btn btn-outline-primary btn-opcion">
                        <input type="radio" onclick="goce(2)"> No
                      </label>
                      <div id="error_sueldo" class="text-danger"></div>
                    </div>
                  </div>
                <?php } else { ?>
                  <!-- <div class="form-group col-md-4 text-center">
                    <p><label>Goce de Sueldo</label></p>
                    <label id="lbl_goce_empleado" class="btn btn-outline-primary btn-opcion">
                      <input type="checkbox" style="display: contents;" onclick="goceEmpleado(this)"> NO
                      </label>
                  </div> -->
                <?php } ?>
              </div>
              <hr>
              <div id="div_cards_all" style="display: none;">
                <div id="card_salida" class="card card-default collapsed-card">
                  <div class="card-header">
                    <h3 class="card-title sie-font-bold">Permiso de SALIDA</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i id="card_salida_icon" class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                  <div id="card_salida_body" class="card-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="permiso_autoriza_salida">A las</label>
                        <input type="time" class="form-control " id="permiso_autoriza_salida" name="permiso_autoriza_salida" value="" onchange="validarNew(this)">
                        <div id="error_permiso_autoriza_salida" class="text-danger"></div>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="permiso_dia_salida">Del día</label>
                        <input type="date" class="form-control " id="permiso_dia_salida" name="permiso_dia_salida" style="background-color: #fff;" onchange="validarNew(this)">
                        <div id="error_permiso_dia_salida" class="text-danger"></div>
                      </div>
                      <div class="form-group col-md-6">
                      </div>
                    </div>
                  </div>
                </div>
                <hr id="hr_salida">
                <div id="card_entrada" class="card card-default collapsed-card">
                  <div class="card-header">
                    <h3 class="card-title sie-font-bold">Permiso de ENTRADA</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i id="card_entrada_icon" class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                  <div id="card_entrada_body" class="card-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="permiso_autoriza_entrada">A las </label>
                        <input type="time" class="form-control " id="permiso_autoriza_entrada" name="permiso_autoriza_entrada" onchange="validarNew(this)">
                        <div id="error_permiso_autoriza_entrada" class="text-danger"></div>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="permiso_dia_entrada">Del día</label>
                        <input type="date" class="form-control" style="background-color: #fff;" id="permiso_dia_entrada" name="permiso_dia_entrada" onchange="validarNew(this)"> <!-- campo a validar L-V- O S -->
                        <div id="error_permiso_dia_entrada" class="text-danger"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr id="hr_entrada">
                <div id="card_inasistencia" class="card card-default collapsed-card" style="display: none;">
                  <div class="card-header">
                    <h3 class="card-title sie-font-bold">Permiso de INASISTENCIA</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i id="card_inasistencia_icon" class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="permiso_inasistencia">Selecciona el Día o los Días de Inasistencia</label>
                        <input type="date" class="form-control" id="permiso_inasistencia" name="permiso_inasistencia" style="background-color: #fff;" onchange="validarNew(this)">
                        <div id="error_permiso_inasistencia" class="text-danger"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr id="hr_inasistencia" style="display: none;">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="permiso_observaciones">Observación:</label>
                    <textarea class="form-control " cols="30" min="3" id="permiso_observaciones" name="permiso_observaciones" onchange="validarNew(this)"></textarea>
                    <div id="error_permiso_observaciones" class="text-danger"></div>
                  </div>
                  <div id="div_evidencia" class="col-md-6" style="display: none;">
                    <label for="permiso_observaciones">Evidencias Fotograficas:</label>
                    <input type="file" accept="image/*" name="evidencias" id="evidencias" class="form-control">
                    <!-- onchange="validarNew(this)"></textarea> -->
                    <div id="error_permiso_observaciones" class="text-danger"></div>
                  </div>
                </div>
                <hr>
                <?php if (session()->type_of_employee == 2 || session()->id_user == 1063 || session()->id_user == 1) { ?>
                  <div class="row" id="div_tiempo_pagado" style="display: none;margin-bottom: 10px;">
                  </div>
                <?php } ?>
                <div class="row">
                  <div style="width: auto !important;">
                    <button id="guardar_permiso" type="submit" class="btn btn-guardar btn-lg sie-font-bold">Generar</button>
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
<?php  if(session()->id_user == 1){   ?>
  <section class="content">
    <div class="container-fluid">
      <div id="card_vacaciones" class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title sie-font-bold">Vacaciones</h3>
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
                  <input type="text" class="form-control " id="vacaciones_usuario" name="vacaciones_usuario" value="<?php foreach ($dias_vacaciones as $key => $value) {
                                                                                                                      echo strtoupper(session()->name . " " . session()->surname . " " . strtolower($value->second_surname));
                                                                                                                    }  ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="departamento">Departamento</label>
                  <input type="text" class="form-control " id="vacaciones_departamento" name="vacaciones_departamento" value="<?= (session()->departament == "ALMACEN VILLAHERMOSA") ? "DOS BOCAS" : session()->departament; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="puesto_trabajo">Puesto</label>
                  <input type="text" class="form-control " id="vacaciones_puesto_trabajo" name="vacaciones_puesto_trabajo" value="<?= session()->job_position; ?>" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="num_nomina">Número de Nómina</label>
                  <input type="text" class="form-control " id="vacaciones_num_nomina" name="vacaciones_num_nomina" value="<?= session()->payroll_number; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="fecha_ingreso">Fecha de ingreso</label>
                  <input type="text" class="form-control " id="vacaciones_fecha_ingreso" name="vacaciones_fecha_ingreso" value="<?= session()->date_admission; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="tipo_empleado">Tipo de Empleado</label>
                  <input type="text" class="form-control " id="vacaciones_tipo_empleado" name="vacaciones_tipo_empleado" value="<?= (session()->type_of_employee > 1) ? "Sindicalizado" : "Administrativo"; ?>" readonly>
                </div>
              </div>
              <hr>
              <div class="form-group col-md-12">
                <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                  <p><label>Tipo de Permiso Vacacional</label></p>
                  <label id="vacacional_1" class="btn btn-outline-info btn-flat">
                    <input type="radio" id="vacacional" name="vacaciones" value="1"> VACACIONAL
                  </label>
                  <label id="acuenta_1" class="btn btn-outline-info btn-flat">
                    <input type="radio" id="acuenta" name="vacaciones" value="2">PERMISO A CUENTA DE VACACIONES
                  </label>
                  <div id="error_permiso" name="error_permiso" class="text-danger"></div>
                </div>
              </div>
              <hr>
              <div id="agregados"></div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="dias_disponibles">Dias disponibles</label>
                  <input type="text" class="form-control " id="vacaciones_dias_disponibles" name="vacaciones_dias_disponibles" value="<?php foreach ($dias_vacaciones as $key => $value) {
                                                                                                                                        echo $value->vacation_days_total;
                                                                                                                                      } ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="vacaciones_dias_disfrutar">Dias a disfrutar</label>
                  <input type="date" id="vacaciones_dias_disfrutar" class="form-control" style="background-color: #fff;" onchange="validarNew(this)">
                  <div id="error_vacaciones_dias_disfrutar" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="regresar_activiades">Debiendo regresar a sus actividades:</label>
                  <input type="date" class="form-control " id="vacaciones_regresar_actividades" name="vacaciones_regresar_actividades" style="background-color: #fff;" onchange="validarNew(this)">
                  <div id="error_vacaciones_regresar_actividades" name="error_vacaciones_regresar_actividades" class="text-danger"></div>
                </div>
                <?php if (session()->type_of_employee == 1) { ?>
                  <div class="form-group col-md-4" style="display:none;" id="div_a_cargo">
                    <label for="a_cargo">Dejando mis responsabilidades a cargo de:</label>
                    <select class="form-control" id="a_cargo" name="a_cargo" onchange="validarNew(this)">
                      <option value="">Seleccionar una Opción</option>
                      <?php if (session()->id_user == 1335) { ?>
                        <option value="111111">Gabriela Garcia</option>
                      <?php } ?>
                      <?php if (session()->id_user == 1299) { ?>
                        <option value="303">GILBERTO VEGA CHAVEZ</option>
                      <?php } ?>
                      <?php foreach ($compañeros as $key => $value) { ?>
                        <option value="<?= $value->id_user ?>"><?= $value->nombre ?></option>
                      <?php } ?>
                    </select>
                    <div id="error_a_cargo" name="error_a_cargo" class="text-danger"></div>
                  </div>
                <?php } ?>
              </div>
              <button type="submit" id="permiso_vacaciones" class="btn btn-guardar btn-lg sie-font-bold">Generar</button>
            </form>
          </div>
        </div>

        <div id="footer_vacaciones" class="card-footer">
          <a href="#">Vacaciones</a>
        </div>
      </div>
    </div>
  </section>

  <?php } ?>

  <input type="hidden" name="tipo" id="tipo" value="<?= session()->type_of_employee ?>">
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script> -->
<script src="<?= base_url() ?>/public/plugins/flatpickr/flatpickr.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/idioma/es.js"></script>
<script src="<?= base_url() ?>/public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

<script src="<?= base_url() ?>/public/js/permissions/permissions_generate_v7-8.js"></script>

<?= $this->endSection() ?>