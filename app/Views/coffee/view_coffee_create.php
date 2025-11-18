<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitud de Cafetería
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<style>

  .btn-primary:not(:disabled):not(.disabled).active,
  .btn-primary:not(:disabled):not(.disabled):active,
  .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #022d5c;
    border-color: #022d5c;
  }

  .active {
    border-color: #28a745 !important;
    background-color: #dee2e6;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.18), 0 3px 6px rgba(0, 0, 0, 0.2);
  }

  .card {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15), 0 2px 5px rgba(0, 0, 0, 0.2);
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
  }

  .btn-check {
    position: absolute;
    clip: rect(0, 0, 0, 0);
    pointer-events: none;
  }

  .btn-primary:not(:disabled):not(.disabled).active,
  .btn-primary:not(:disabled):not(.disabled):active,
  .show>.btn-primary {
    color: #fff;
    background-color: #062a50;
    border-color: #062a50 !important;
  }
  .ocultar{
    display:none !important;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Solitud de Coffee Break</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Cafetería</li>
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
          <h3 class="card-title">Cafetería</h3>
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
            <form id="coffee_break" method="post">

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="permiso_usuario">Solicitante</label>
                  <input type="text" class="form-control rounded-0" id="usuario_coffee" name="usuario_coffee" value="<?= strtoupper(session()->name . " " . session()->surname); ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_departamento">Departamento</label>
                  <input type="text" class="form-control rounded-0" id="departamento_coffee" name="departamento_coffee" value="<?= session()->departament; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="area_operativa">Area Operativa</label>
                  <input type="text" class="form-control rounded-0" id="area_operativa" name="area_operativa" value="<?= session()->cost_center; ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_puesto_trabajo">Puesto</label>
                  <input type="text" class="form-control rounded-0" id="puesto_trabajo" name="puesto_trabajo" value="<?= session()->job_position; ?>" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="permiso_num_nomina">Número de Nómina</label>
                  <input type="text" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="<?= session()->payroll_number; ?>" readonly>
                </div>


              </div>
              <hr>
              <div class="col-md-12">
                <p style="color:#d30202"><b>*Favor de realizar la solicitud al menos con 24 hrs. de anticipación.</b></p>
                <p><b>Seleccionar que incluira servicio de coffee break, según el motivo de la reunión:</b> </p>
                <div class="row btn-group btn-group-toggle" data-toggle="buttons">
                  <div id="item-coffe" class="col-md-12">
                    <label id="agua_lb" class="btn btn-primary ">
                      <input type="checkbox" id="agua" name="menu-coffe" value="Agua embotellada" autocomplete="off" class="btn-check" onchange="validar()"> Agua embotellada
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label id="jarra_lb" class="btn btn-primary">
                      <input type="checkbox" id="refresco" name="menu-coffe" value="Refresco" autocomplete="off" class="btn-check" onchange="validar()"> Refresco
                      <!-- <input type="checkbox" id="jarra" name="menu-coffe" value="Agua en jarra de cristal ( se colocan vasos de cristal)" autocomplete="off" class="btn-check" onchange="validar()"> Agua en jarra de cristal ( se colocan vasos de cristal) -->
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label id="cafe_lb" class="btn btn-primary">
                      <input type="checkbox" id="cafe" name="menu-coffe" value="Café (se colocan tazas de cerámica)" autocomplete="off" class="btn-check" onchange="validar()"> Café (se colocan tazas de cerámica)
                    </label>
                  </div>
                  <div class="col-md-12">
                    <label id="galletas_lb" class="btn btn-primary">
                      <input type="checkbox" id="galletas" name="menu-coffe" value="Galletas" autocomplete="off" class="btn-check" onchange="validar()"> Galletas
                    </label>
                  </div>
                  <div id="error_checkbox" class="text-danger"></div>
                </div>
              </div>
              <hr>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="sala_coffee">Sala de Juntas</label>
                  <select name="sala_coffee" id="sala_coffee" class="form-control rounded-0" onchange="validar()">
                    <option value="" >Seleccionar</option>
                    <?php foreach ($meeting as $key => $value) {  ?>
                      <option value="<?= $value->id_room; ?>"><?= $value->meeting_room; ?></option>
                    <?php } ?>
                  </select>
                  <div id="error_sala" class="text-danger"></div>
                </div>
                <div class="form-group col-md-3">
                  <label for="motivo_coffee">Motivo de la Reunión</label>
                  <select name="motivo_coffee" id="motivo_coffee" class="form-control rounded-0" onchange="validar()">
                    <option value="">Seleccionar</option>
                    <?php foreach ($reason as $key => $value) {  ?>
                      <option value="<?= $value->reason_for_meeting; ?>"><?= $value->reason_for_meeting; ?></option>
                    <?php } ?>
                  </select>
                  <div id="error_motivo" class="text-danger"></div>
                </div>
                <div class="form-group col-md-3">
                  <label for="permiso_autoriza_salida">Fecha</label>
                  <input type="text" class="form-control rounded-0 fondo" id="fecha_coffee" name="fecha_coffee" style="background-color:#fff;" value="" onchange="validar()">
                  <div id="error_fecha" class="text-danger"></div>
                </div>
                <div class="form-group col-md-3">
                  <label for="horario_coffeee">Horario</label>
                  <input type="time" class="form-control rounded-0" id="horario_coffee" name="horario_coffee" onchange="validar()">
                  <div id="error_horario" class="text-danger"></div>
                </div>
                <div class="form-group col-md-3">
                  <label for="no_personas">Numero de Personas </label>
                  <input type="number" class="form-control rounded-0" id="no_personas" name="no_personas" min="1" onchange="validar()">
                  <div id="error_personas" class="text-danger"></div>
                </div>

                <div class="form-group col-md-4">
                  <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                    <p><label>Menus Servicios Especiales </label></p>
                    <label id="menu_si_" class="btn btn-primary">
                      <input type="radio" name="menu_especial" id="menu_si" class="" value="" onchange="validar()"> SI
                    </label>
                    <label id="menu_no_" class="btn btn-primary">
                      <input type="radio" name="menu_especial" id="menu_no" class="" value="" onchange="validar()"> No
                    </label>

                  </div>
                  
                  <div id="error_menus" class="text-center text-danger"></div>
                </div>
                <div id="leyenda" class="ocultar col-md-12"><p style="color:#d30202"><b>*Favor de realizar la solicitud al menos con 1 semana de anticipación.</b></p></div>
                <div id="error_select_menus" class="text-center text-danger"></div>
                <div class="col-12">
                  <div class="card-deck">
                    <div id="menus" class="form-group row">

                    </div>
                  </div>
                </div>
                <div class="form-group col-md-12">
                  <label for="regresar_activiades">Observaciones:</label>
                  <textarea class="form-control rounded-0" cols="30" rows="3" id="coffee_observaciones" name="permiso_observaciones"></textarea>
                </div>
              </div>


              <button id="guardar_coffee" type="submit" class="btn btn-guardar btn-lg btn-block">Generar</button>
            </form>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Coffee Break</a>
        </div>
      </div>
    </div>
  </section>

  <input type="hidden" name="tipo" id="tipo" value="<?= session()->type_of_employee ?>">
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/flatpickr/flatpickr.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/idioma/es.js"></script>
<script src="<?= base_url() ?>/public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>/public/js/coffee/index_v4.js"></script>

<?= $this->endSection() ?>