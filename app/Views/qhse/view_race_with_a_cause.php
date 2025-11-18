<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Actividades Deportivas
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

  .btn-retirar-item {
    margin-top: -3.2rem;
  }

  .form-control {
    border: none;
    border-bottom: 1px solid #ced4da;
    background: no-repeat center bottom, center calc(100% - 1px);
    background-size: 0 100%, 100% 100%;
    transition: background 0s ease-out;
  }

  .custom-file-label::after {
    content: "Subir";
  }

  .form-group .floating-label {
    position: absolute;
    top: 11px;
    left: 6px;
    font-size: 1rem;
    z-index: 1;
    cursor: text;
    transition: all 0.3s ease;
    color: #73808b;
  }

  .form-group .floating-label+.form-control {
    /*  padding-left: 0; */
    padding-right: 0;
    border-radius: 0;
  }

  .form-control:focus {
    border-bottom-color: transparent;
    background-size: 100% 100%, 100% 100%;
    transition-duration: 0.3s;
    box-shadow: none;
    background-image: linear-gradient(to top, #00c163 2px, rgba(70, 128, 255, 0) 2px), linear-gradient(to top, #ced4da 1px, rgba(206, 212, 218, 0) 1px);
  }

  .form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #c6d8ff;
    outline: 0;
    box-shadow: 0 0 0 0rem rgba(70, 128, 255, 0.25);
  }

  .form-group.fill .floating-label {
    top: -17px;
    font-size: 0.9rem;
    color: #4f4a4a;
  }


  .animate-show {
    animation: showAnimation 0.8s ease-in-out;
  }

  @keyframes showAnimation {
    0% {
      transform: translateX(-100%);
    }

    100% {
      transform: translateX(0);
    }
  }

  input[type=radio] {
    width: 100%;
    height: 26px;
    opacity: 0;
    cursor: pointer;
  }

  .radio-group div {
    width: 85px;
    display: inline-block;
    border: 2px solid #AEABAE;
    border-radius: 5px;
    text-align: center;
    position: relative;
    /* padding-bottom: 10px; */
  }

  .radio-group label {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    margin-bottom: 10px;
    line-height: 2em;
    pointer-events: none;
  }

  .radio-group input[type=radio]:checked+label {
    background: #1C7298;
    color: #fff;
  }

  .form-check-input {
    width: 20px;
    height: 30px;
    top: -10px;
  }

  .form-check-label {
    margin-left: 0.5rem;
  }

  .section-option {

    margin-left: 2rem;

  }

  .opt-check {
    width: 18px;
    height: 18px;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-9">
          <h5 class="m-0">Actividades Deportivas</h5>
        </div><!-- /.col -->
        <div class="col-sm-3">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">HSE</li>

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
          <h3 class="card-title">Información Personal</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card-body mt-3">

            <div id="content-form" class="container-fluid mb-4">
              <form id="solicitud_eventos" method="post" enctype="multipart/form-data">
                <input type="hidden" id="tipo_evento" name="tipo_evento" value="Actividades Deportivas">
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label class="floating-label" for="num_nomina">Número de Nómina</label>
                    <input type="text" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="<?= session()->payroll_number; ?>" readonly>

                  </div>
                  <div class="form-group col-md-3">
                    <label class="floating-label" for="usuario">Solicitante</label>
                    <input type="text" class="form-control rounded-0" id="usuario" name="usuario" value="<?= ucwords(session()->name . " " . session()->surname); ?>" readonly>
                  </div>
                  <div class="form-group col-md-4">
                    <label class="floating-label" for="departamento">Departamento</label>
                    <input type="text" class="form-control rounded-0" id="departamento" name="departamento" value="<?= session()->departament; ?>" readonly>
                  </div>
                  <div class="form-group col-md-3">
                    <label class="floating-label" for="puesto">Puesto</label>
                    <input type="text" class="form-control rounded-0" id="puesto" name="puesto" value="<?= session()->job_position; ?>" readonly>
                  </div>
                </div>
                <hr>

                <div id="extra_1" class="form-row ">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="floating-label" for="tel_contacto">Teléfono de contacto</label>
                      <input type="tel" id="tel_contacto" name="tel_contacto" class="form-control" value="" placeholder="Ejemplo: 55 12 34 56 78" />
                      <div id="error_contacto" class="text-danger"></div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="floating-label" for="motivo">Motivo por el que te interesaría participar</label>
                      <input type="text" id="motivo" name="motivo" class="form-control" value="" />
                      <div id="error_motivo" class="text-danger"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-10 pt-4">
                  <h4>ACTIVIDAD EN LA QUE TE INTERESA PARTICIPAR</h4>

                  <fieldset>
                    <div id="actividades-container"></div>

                  </fieldset>

                  <hr>
                  <div class="col-md-10 pt-4">
                    <h4>Personas a Registrar</h4>

                    <div id="personas-container">
                      <!-- Campos de nombre y talla se agregarán aquí -->
                    </div>

                    <button type="button" id="add-persona" class="btn btn-outline-primary mt-3">
                      <i class="fas fa-plus-circle"></i> Agregar Persona
                    </button>
                  </div>

                </div>


            </div>

            <button id="guardar_solicitud" type="submit" class="btn btn-guardar btn-lg btn-block"><i class="fas fa-file-alt"></i> Enviar Solicitud</button>
            </form>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Permanente</a>
        </div>
      </div>
    </div>

  </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/js/qhse/hse_actividades_deportivas_v2.js"></script>

<?= $this->endSection() ?>