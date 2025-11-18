<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitud de pago
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

  /**CHECK BOX **/
  .bg-acceptable {
    color: #fff;
    background-color: #F65E0A;
    border-color: #F65E0A;
  }

  .toggle {
    position: relative;
    box-sizing: border-box;
    padding: inherit;
  }

  .toggle input[type="checkbox"] {
    position: absolute;
    left: 0;
    top: 0;
    z-index: 10;
    width: 56%;
    height: 100%;
    cursor: pointer;
    opacity: 0;
  }

  .toggle label {
    position: relative;
    display: flex;
    align-items: center;
    box-sizing: border-box;
  }

  .toggle label:before {
    content: '';
    width: 40px;
    height: 22px;
    background: #ccc;
    position: relative;
    display: inline-block;
    border-radius: 46px;
    box-sizing: border-box;
    transition: 0.2s ease-in;
  }

  .toggle label:after {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    left: 2px;
    top: 2px;
    z-index: 2;
    background: #fff;
    box-sizing: border-box;
    transition: 0.2s ease-in;
  }

  .toggle input[type="checkbox"]:checked+label:before {
    background: #4BD865;
  }

  .toggle input[type="checkbox"]:checked+label:after {
    left: 19px;
  }

  .checkbox-center {
    display: flex;
    justify-content: center;
    /* Centrado horizontal */
    align-items: center;
    /* Centrado vertical */
    height: 100%;
    /* Asegura que ocupe toda la celda */
  }
</style>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-solicitud">Solicitudes MSI</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Logística</li>
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
          <h3 class="card-title">Detalle de Archivo </h3>
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
          <div class="col-md-12 text-right">
            <a class="btn btn-circle" onclick="abrirSolicitudModal()">
              Nueva Solicitud
            </a>
            <!--  <button class="btn btn-guardar " onclick="abrirActivoModal()">Nuevo Activo</button> -->
          </div>
          <table id="tbl_solicitudes_msi" class=" font-tables display compact dataTable table-bordered table-striped nowrap" style="width:100%">

          </table>

        </div>


        <div class="card-footer">
          <a href="#">Logística</a>
        </div>
      </div>
    </div>
  </section>



  <section>
    <!-- Modal -->
    <div class="modal fade" id="solicitudModal" tabindex="-1" role="dialog" aria-labelledby="solicitudModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal"> SOLICITUD DE MOVIMIENTO AL INVENTARIO (SMI)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formSolicitud" enctype="multipart/form-data">
              <div class="row font-solicitud">


                <div class="col-md-4">
                  <div class="form-group">
                    <label class="floating-label" for="conceptos">Concepto de solicitud:</label>
                    <select name="conceptos" id="conceptos" class="form-control" style="cursor: pointer;">

                    </select>
                    <div id="error_conceptos" class="text-danger"></div>
                  </div>
                </div>


                <div class="font-solicitud col-md-12 row">

                  <div class="form-group col-md-4">
                    <label for="pdfFile">Archivo PDF:</label>
                    <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf" class="form-control-file" required>
                  </div>

                  <div id="fileInputContainer" class="form-group"></div>
                  <div id="fileInputContainerSua" class="form-group"></div>
                  <div id="fileInputContainerTxt" class="form-group"></div>
                  <div id="fileInputContainerPdf" class="form-group"></div>
                  <div id="fileInputContainerXml" class="form-group"></div>

                </div>

                <div class="col-md-12">

                  <button id="btnGuardarSolicitud" class="btn btn-block btn-guardar font-solicitud mt-3">Solicitud de Movimiento al Inventario</button>
                </div>


            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
   

    <div class="modal fade" id="modalPdfSolicitud" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h5 class="modal-title">
          Solicitud: <span id="folioPdf"></span> · Aprobador actual: <span id="lblAprobadorActual"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
      </div>
      <div class="modal-body p-0">
       
        <iframe id="visorPdfSolicitud" src="" style="width:100%; height:78vh;" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <input type="text" id="smi_id_actual">
        <input type="text" id="smi_aprobador_actual">
        <button type="button" id="btnFirmar" class="btn btn-success">
          <i class="fas fa-pen-nib"></i> Firmar documento
        </button>
        <button type="button" id="btnEnviarSiguiente" class="btn btn-primary" disabled>
          <i class="fas fa-share-square"></i> Enviar al siguiente
        </button>
      </div>
    </div>
  </div>
</div>


  </section>



</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
  var usuario_actual = "<?= session()->get('name')." ". session()->get('surname'); ?>";
</script>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/logistica/logistica_solicitud_v1.js"></script>
<script>
  $(document).ready(function() {
    // Función para manejar el cambio en select, input y textarea
    function handleLabel() {
      if ($(this).val()) {
        $(this).addClass('filled');
      } else {
        $(this).removeClass('filled');
      }
    }

    // Aplicar el evento a todos los select, input y textarea
    $('select, input, textarea').on('change input', handleLabel);

    // Verificar el estado inicial de los campos al cargar la página
    $('select, input, textarea').each(function() {
      if ($(this).val()) {
        $(this).addClass('filled');
      }
    });
  });
</script>

<?= $this->endSection() ?>