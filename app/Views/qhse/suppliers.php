<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Visitantes
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">

<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
  .btn-primary:not(:disabled):not(.disabled).active,
  .btn-primary:not(:disabled):not(.disabled):active,
  .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #1f2d3d;
    border-color: #1f2d3d;
  }

  .custom-file-label::after {
    content: "Subir";
  }

  .calendar-visita {
    background: #ffffff !important;
  }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Visitantes</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Visitantes</li>
          </ol>
        </div>
      </div>
    </div>
  </div>  
  <section class="content">
    <div class="container-fluid">      
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Permiso Entrada</h3>
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
            <form id="permisos_visitas" method="post" enctype="multipart/form-data">
              <input type="hidden" id="id_usuario" name="id_usuario" value="<?= session()->id_user ?>">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="permiso_usuario">Solicitante</label>
                  <input type="text" class="form-control rounded-0" id="usuario" name="usuario" value="<?= ucwords(session()->name . " " . session()->surname); ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_departamento">Departamento</label>
                  <input type="text" class="form-control rounded-0" id="departamento" name="departamento" value="<?= session()->departament; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_puesto_trabajo">Puesto</label>
                  <input type="text" class="form-control rounded-0" id="puesto" name="puesto" value="<?= session()->job_position; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_num_nomina">Número de Nómina</label>
                  <input type="text" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="<?= session()->payroll_number; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_tipo_empleado">*Nombre proveedor </label>
                  <input type="text" class="form-control rounded-0" id="proveedor" name="proveedor" value="" onchange="limpiarError(this)">
                  <div id="error_proveedor" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_tipo_empleado">*Numero de personas que ingresan</label>
                  <input type="number" class="form-control rounded-0" id="num_personas" name="num_personas" min="1" onchange="limpiarError(this)">
                  <div id="error_num_personas" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_tipo_empleado">*Persona a quien visita</label>
                  <input type="text" class="form-control rounded-0" id="visita" name="visita" onchange="limpiarError(this)">
                  <div id="error_visita" class="text-danger"></div>
                </div>
                <div class="form-group col-md-8">
                  <div class="col-md-6">
                    <label for="depto">Departamento</label>
                    <select id="depto" name="depto" class="form-control rounded-0 select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="limpiarError(this)">
                      <option value="">Seleccionar...</option>
                      <?php foreach ($departament as $label => $opt) { ?>
                        <optgroup label="<?php echo $label; ?>">
                          <?php foreach ($opt as $id => $name) { ?>
                            <option value="<?= $name ?>"><?= $name ?></option>
                          <?php } ?>
                        </optgroup>
                      <?php } ?>
                    </select>
                    <div id="error_depto" class="text-danger"></div>
                  </div>
                </div>
                <div class="form-group col-md-3">
                  <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                    <p><label>Estadia Prolongada?</label></p>
                    <label id="estadia_si" class="btn btn-primary">
                      <input type="radio" name="estadia" id="estadia_si" class="" value="1"> SI
                    </label>
                    <label id="estadia_no" class="btn btn-primary">
                      <input type="radio" name="estadia" id="estadia_no" class="" value="0"> No
                    </label>
                  </div>
                  <div id="error_estadia" class="text-center text-danger"></div>
                </div>
                <div id="cont_estadia" class=""></div>
                <div class="form-group col-md-3">
                  <label for="motivo_visita">*Hora de llegada a instalaciones</label>
                  <input type="time" class="form-control rounded-0" id="hora_entrada" name="hora_entrada" value="" onchange="limpiarError(this)">
                  <div id="error_hora" class="text-danger"></div>
                </div>
                <div class="form-group col-md-6">
                  <p><label>Requiere que el personal coloque (Casco, lentes y Tapones de seguridad) EPP para recorrido de planta?</label></p>
                  <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                    <label id="epp_si_" class="btn btn-primary">
                      <input type="radio" name="epp" id="epp_si" class="" value="1"> SI
                    </label>
                    <label id="epp_no_" class="btn btn-primary">
                      <input type="radio" name="epp" id="epp_no" class="" value="0"> No
                    </label>
                  </div>
                  <div id="error_epp" class="text-center text-danger"></div>
                </div>
                <div class="col-md-12">
                  <hr>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <p><label>El Personal visitante realizara trabajos dentro de las instalaciones de Walworth?</label></p>
                      <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                        <label id="goce_sueldo_si" class="btn btn-primary">
                          <input type="radio" name="trabajos" id="trabajos_si" class="" value="1"> SI
                        </label>
                        <label id="goce_sueldo_no" class="btn btn-primary">
                          <input type="radio" name="trabajos" id="trabajos_no" class="" value="0"> No
                        </label>
                      </div>
                      <div id="error_trabajos" class="text-center text-danger"></div>
                    </div>
                    <div id="seguro_trabajo"></div>
                  </div>
                </div>
                <div class="col-md-12">
                  <hr>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <p><label>El Personal visitante requiere acceso con vehículo ?</label></p>
                      <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                        <label id="auto_si" class="btn btn-primary">
                          <input type="radio" name="auto" id="auto_si" class="" value="1"> SI
                        </label>
                        <label id="goce_sueldo_no" class="btn btn-primary">
                          <input type="radio" name="auto" id="auto_no" class="" value="0"> No
                        </label>
                      </div>
                      <div id="error_auto" class="text-center text-danger"></div>
                    </div>
                    <div id="seguro_auto"></div>
                  </div>
                </div>
                <div id="datos_auto"></div>
                <div class="form-group col-md-12">
                  <hr>
                  <div class="row">
                    <div class="form-group col-md-4">
                      <button id="btn-agregar-item" class="btn btn-success" type="button"><i class="fas fa-user-plus"></i> Agregar Visitante</button>
                    </div>
                    <div id="resultado" class="error col-md-10"></div>
                  </div>
                </div>
                <div id="alumnos" class="col-md-12">
                  <div id="duplica" class="agrega-item">
                    <div id="item-duplica"></div>
                  </div>
                  <div id="tiempo_extra">
                    <div id="extra_1" class="form-row ">
                      <div class="form-group col-md-6">
                        <label for="visitante">Visitante</label>
                        <input type="text" class="form-control rounded-0" id="visitante_1" name="visitante[]" placeholder="Ingresar Nombre del Visitante" onchange="validarClon(1)">
                        <div id="error_visitante_1" class="text-danger"></div>
                      </div>
                      <div class="form-group col-md-5">
                        <label id="nacionalidad" for="nacionalidad">Nacionalidad</label>
                        <input type="text" class="form-control rounded-0" id="nacionalidad_1" name="nacionalidad[]" onchange="validarClon(1)">
                        <div id="error_nacio_1" class="text-danger"></div>
                      </div>

                      <div id="btn_eliminar_1" class="form-group col-md-1"></div>
                    </div>
                  </div>
                </div>
                <div class="form-group col-md-12">
                  <label for="motivo_visita">*Motivo de visita</label>
                  <textarea class="form-control" name="motivo_visita" id="motivo_visita" cols="6" rows="2" onchange="limpiarError(this)"></textarea>
                  <div id="error_motivo_visita" class="text-danger"></div>
                </div>
              </div>
              <hr>
          </div>
          <button id="guardar_permiso" type="submit" class="btn btn-guardar btn-lg btn-block">Generar</button>
          </form>
        </div>
        <div class="card-footer">
          <a href="#">Proveedores</a>
        </div>
      </div>
    </div>
  </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/flatpickr.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/idioma/es.js"></script>
<script src="<?= base_url() ?>/public/js/qhse/proveedores_v3.js"></script>
<?= $this->endSection() ?>