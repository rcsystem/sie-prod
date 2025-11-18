<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Autorizar Solicitud de Pago
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
  #pdf-viewer {
    border: 1px solid #ccc;
    width: 100%;
    max-width: 600px;
    height: 500px;
    overflow: auto;
  }

  canvas {
    display: block;
    margin: auto;
  }

  .badge-cancel {
    color: #fff;
    background-color: #f76a77;
  }

  .btn-outline-black {
    color: #000;
    border-color: #000;
  }

  .font-solicitud {
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 700;
  }

  .font-table {
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 400;
  }

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

  .autocomplete-suggestions {
    border: 1px solid #ccc;
    background: #fff;
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    z-index: 9999;
  }

  .autocomplete-suggestions div {
    padding: 8px;
    cursor: pointer;
  }

  .autocomplete-suggestions div:hover {
    background-color: #e9e9e9;
  }

  .btn-success {
    background-color: #009D11;
  }

  .form-group {
    margin-bottom: 3rem;
  }


  .select-container {
    position: relative;
  }

  .select-container::after {
    content: '▼';
    font-size: 12px;
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    pointer-events: none;
  }

  .btn-circle {
    background-color: #c72220;
    border-color: #c72220;
    border-radius: 30px;
    box-shadow: none;
    font-weight: 400 !important;
    padding: 0.4rem 2rem;
    color: #fff;
  }

  table.dataTable>thead .sorting:before,
  table.dataTable>thead .sorting:after,
  table.dataTable>thead .sorting_asc:before,
  table.dataTable>thead .sorting_asc:after,
  table.dataTable>thead .sorting_desc:before,
  table.dataTable>thead .sorting_desc:after,
  table.dataTable>thead .sorting_asc_disabled:before,
  table.dataTable>thead .sorting_asc_disabled:after,
  table.dataTable>thead .sorting_desc_disabled:before,
  table.dataTable>thead .sorting_desc_disabled:after {
    position: absolute;
    bottom: .2em;
    display: block;
    opacity: .3;
  }
</style>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-solicitud">Autorizar Solicitudes de Pago Adm. Personal</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Finanzas</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <!-- PERMISOS collapsed-card-->
      <div class="card card-default ">
        <div class="card-header">
          <h3 class="card-title">Solicitudes </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!--  <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
          <!--    <div class="col-md-12 text-right">
                  <a class="btn btn-circle" onclick="abrirSolicitudModal()">
                    Nueva Solicitud
                  </a>
                 
                </div> -->
          <table id="tbl_autorizar_adm" class=" font-tables display compact dataTable table-bordered table-striped nowrap" style="width:100%">

          </table>

        </div>


        <div class="card-footer">
          <a href="#">Finanzas</a>
        </div>
      </div>
    </div>
  </section>












  <section>
  <!-- Modal -->
  <div class="modal fade" id="autorizarSolicitudModal" tabindex="-1" role="dialog" aria-labelledby="autorizarsolicitudModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="verPermisosModal">Autorizar Solicitud</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="pdfModalBody" class="modal-body">
          <input type="hidden" name="id_solicitud" id="id_solicitud" value="" />

          <!-- Área de comentarios -->
        <!--   <div class="form-group">
            <label for="comentarioSolicitud"><b>Comentario:</b></label>
            <textarea class="form-control" id="comentarioSolicitud" name="comentario" rows="1" placeholder="Escribe un comentario sobre esta solicitud..."></textarea>
          </div> -->

          <div id="btnAcciones" class="col-md-12 row">
            <div class="col-md-4 text-left">
              <button id="signBtn" class="btn btn-guardar">Firmar PDF</button>
            </div>
            <div class="col-md-4">
              <!-- Div que contiene la barra de progreso -->
              <div id="progressContainer" style="display: none;">
                <div class="progress">
                  <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%;" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p id="progressText" class="text-center">Estamos procesando tu solicitud, por favor espera...</p>
              </div>
              <!-- Mensajes de éxito o error -->
              <div id="successMessage" class="text-center" style="display: none;">
                <h4 id="successText">¡Proceso completado!</h4>
              </div>
              <div id="errorMessage" class="text-center" style="display: none;">
                <h4 id="errorText">Hubo un error al procesar la solicitud.</h4>
              </div>
            </div>
            <div class="col-md-4 text-right">
              <button id="btn-signed" class="btn btn-danger"><b>Autorizar Pago</b></button>
            </div>
          </div>

          <br>

          <!-- Contenedor del PDF -->
          <div id="pdfContainer" class="text-center">
            <object id="pdfObject" type="application/pdf" width="100%" height="700">
              <p>Tu navegador no soporta archivos PDF.</p>
            </object>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>




</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/finance/finanzas_autorizar_v6.js"></script>


<?= $this->endSection() ?>