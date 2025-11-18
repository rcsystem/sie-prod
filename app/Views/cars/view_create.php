<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitud de Vehiculo
<?= $this->endSection() ?>

<?= $this->section('content') ?>

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
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Solitud de Automovil</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Solicitud de Vehiculos</li>
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
          <h3 class="card-title">Solitud de Automovil</h3>

        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <form id="solicitud_vehiculo" method="post">

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
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="tipo_viaje">Tipo de Viaje</label>
                  <select name="tipo_viaje" id="tipo_viaje" class="form-control rounded-0" onchange="viaje()">
                    <option value="">Seleccionar</option>
                    <option value="1">VIAJE CORTO</option>
                    <option value="2">VIAJE PROLONGADO</option>
                  </select>
                  <div id="error_tipo_viaje" class="text-danger"></div>
                </div>
                <div class="form-group col-md-12">
                  <div class="row">
                <div id="opciones" class="form-group col-md-3"></div>
                <div id="opciones2" class="form-group col-md-3"></div>
                <div id="opciones3" class="form-group col-md-3"></div>
                <div id="opciones4" class="form-group col-md-3"></div>
                </div>
                </div>
            <!--     <div class="col-12">
                  <label>Vehiculos Disponibles</label>
                  <div id="error_vehiculo" class="text-danger"></div>
                  <div class="card-deck">
                    <div id="vehiculos" class="form-group row">
                    </div>
                  </div>
                </div> -->
                <div class="form-group col-md-12">
                  <label for="motivo">Motivo de Solicitud:</label>
                  <textarea class="form-control rounded-0" cols="30" rows="3" id="motivo" name="motivo" onchange="validar()"></textarea>
                  <div id="error_motivo" class="text-danger"></div>
                </div>
              </div>
              <button id="btn_solicitud_vehiculo" type="submit" class="btn btn-guardar btn-lg btn-block">Generar</button>
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
<!-- AdminLTE for demo purposes  -->
<script src="<?= base_url() ?>/public/js/cars/create_request_v1.js"></script>


<?= $this->endSection() ?>