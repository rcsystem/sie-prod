<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitud Valija
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
          <h1 class="m-0">Solicitar valija</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Solicitar valija</li>
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
          <h3 class="card-title">Solicitar valija</h3>
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
            <form id="valija" method="post">

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="permiso_usuario">Solicitante</label>
                  <input type="text" class="form-control rounded-0" id="permiso_usuario" name="permiso_usuario" value="<?= strtoupper(session()->name . " " . session()->surname); ?>" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_departamento">Departamento</label>
                  <input type="text" class="form-control rounded-0" id="permiso_departamento" name="permiso_departamento" value="<?=  (session()->departament == "ALMACEN VILLAHERMOSA")?"DOS BOCAS":session()->departament; ?>" readonly>
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

                <div class="form-group col-md-3">
                  <label for="valija_origen">Origen</label>
                  <select name="valija_origen" id="valija_origen" class="form-control rounded-0" onchange="validar()">
                    <option value="">Seleccionar...</option>
                    <option value="Walworth">WALWORTH</option>
                    <option value="AX ONE">AX ONE</option>
                    <option value="GRUPO WALWORTH">GRUPO WALWORTH</option>
                    <option value="OTRO">OTRO</option>
                  </select>
                  <div id="error_valija_origen" class="text-danger"></div>
                </div>
                <div id="inserta_otro_origen" ></div>

                <div class="form-group col-md-3">
                  <label for="valija_destino">Destino</label>
                  <select name="valija_destino" id="valija_destino" class="form-control rounded-0" onchange="validar()">
                    <option value="">Seleccionar...</option>
                    <option value="Walworth">WALWORTH</option>
                    <option value="AX ONE">AX ONE</option>
                    <option value="GRUPO WALWORTH">GRUPO WALWORTH</option>
                    <option value="OTRO">OTRO</option>
                  </select>
                  <div id="error_valija_destino" class="text-danger"></div>  
                </div>
                <div id="inserta_otro_destino"></div>

                <div class="form-group col-md-3">
                  <label for="valija_prioridad">Prioridad</label>
                  <select name="valija_prioridad" id="valija_prioridad" class="form-control rounded-0" onchange="validar()">
                    <option value="">Seleccionar...</option>
                    <option value="INMEDIATA">INMEDIATA</option>
                    <option value="NORMAL">NORMAL</option>
                    <option value="BAJA">BAJA</option>
                    
                  </select>
                  <div id="error_valija_prioridad" class="text-danger"></div>              
                </div>

                <div class="form-group col-md-3">
                  <label for="valija_fecha">Fecha</label>
                  <input type="date" id="valija_fecha" name="valija_fecha" class="form-control rounded-0" onchange="validar()">
                  <div id="error_valija_fecha" class="text-danger"></div>               
                </div>
                <div class="form-group col-md-3">
                  <label for="valija_hora">Hora</label>
                  <input type="time" id="valija_hora" name="valija_hora" class="form-control rounded-0" onchange="validar()">
                  <div id="error_valija_hora" class="text-danger"></div>                
                </div>
       
              </div>

                    <hr>
                    <div class="form-group col-md-12">
                      <label class="text-danger" for="permiso_observaciones"><b>Descripción del envío (Colocar Dirección Completa, Nombre y Contacto) y especificaciones:</b></label>
                      <textarea class="form-control rounded-0" cols="30" min="3" id="valija_observacion" name="permiso_observaciones" onchange="validar()"></textarea>
                      <div id="error_valija_observacion" name="permiso_observaciones" class="text-danger"></div>
                    </div>
              <hr>

              <button id="guardar_valija" type="submit" class="btn btn-guardar btn-lg btn-block">Generar</button>
            </form>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Valija</a>
        </div>
      </div>
    </div>
  </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/js/valija/valija_v1.js"></script>

<?= $this->endSection() ?>