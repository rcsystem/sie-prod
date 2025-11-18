<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Talento | Solicitud de pago
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<style>
  .font-solicitud {
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 700;
  }


  .form-control {
    border: none;
    border-bottom: 1px solid #ced4da;
    background: no-repeat center bottom, center calc(100% - 1px);
    background-size: 0 100%, 100% 100%;
    transition: background 0s ease-out;
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


  .btn-success {
    background-color: #009D11;
  }

  .btn-success:not(:disabled):not(.disabled).active,
  .btn-success:not(:disabled):not(.disabled):active,
  .show>.btn-success.dropdown-toggle {
    color: #fff;
    background-color: #0e5d20;
    border-color: #1c7430;
  }

  .btn-circle {
    background-color: #18ad2f;
    border-color: #18ad2f;
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

  .fuente-chica {
    font-size: 14px;
    /* Ajusta el tamaño según necesites */
  }
</style>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-solicitud">Solicitud de pago</h1>
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
          <h3 class="card-title">SOLICITUD DE CHEQUE / TRANSFERENCIA </h3>
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
            <a class="btn btn-circle" onclick="solicitudTalentoModal()">
              Nueva Solicitud
            </a>
            <!--  <button class="btn btn-guardar " onclick="abrirActivoModal()">Nuevo Activo</button> -->
          </div>
          <table id="tbl_pago_talento" class=" font-table display dataTable table-bordered table-striped nowrap" style="width:100%">

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
    <div class="modal fade" id="solicitudTalentoModal" tabindex="-1" role="dialog" aria-labelledby="solicitudTalentoModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Solicitud de Pago</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="uploadForm" class="font-solicitud" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-4 mb-5">
                  <select name="empresas" id="empresas" class="form-control">
                    <option value="">Seleccionar</option>
                    <option value="INDUSTRIAL DE VALVULAS">INDUSTRIAL DE VALVULAS</option>
                    <option value="WALWORTH">WALWORTH</option>
                    <option value="GRUPO WALWORTH">GRUPO WALWORTH</option>
                  </select>
                  <div id="error_empresas" class="text-danger"></div>
                </div>
                <div class="col-md-4 mb-5">
                  <div class="btn-group-toggle text-left " data-toggle="buttons">
                    <label>Tipo de pago </label>
                    <label class="btn btn-success">
                      <input type="radio" name="tipo_pago" id="cheque" class="" value="cheque"> Cheque
                    </label>
                    <label class="btn btn-success">
                      <input type="radio" name="tipo_pago" id="transferencia" class="" value="transferencia"> Transferencia
                    </label>
                  </div>
                  <div id="error_tipo" class="text-danger"></div>
                </div>
              </div>
              <div class="row">

                <div class="col-md-3">
                  <div class="form-group mb-5">
                    <label class="floating-label" for="nombre_empresa">Expedir a nombre de:</label>
                    <input type="text" class="form-control escribe" id="nombre_empresa" name="nombre_empresa">
                    <div id="error_empresa" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group mb-5">
                    <label class="floating-label" for="banco">Banco:</label>
                    <select name="banco" id="banco" class="form-control">
                      <option value="" disabled selected></option>
                      <option value="BBVA">BBVA</option>
                      <option value="BANAMEX">BANAMEX</option>
                      <option value="CITYBANAMEX">CITYBANAMEX</option>
                      <option value="CITIBANK">CITIBANK</option>
                      <option value="SANTANDER">SANTANDER</option>
                      <option value="HSBC">HSBC</option>
                      <option value="BAJÍO">BAJÍO</option>
                      <option value="IXE">IXE</option>
                      <option value="INBURSA">INBURSA</option>
                      <option value="AFIRME">AFIRME</option>
                      <option value="AZTECA">AZTECA</option>
                      <option value="AUTOFIN">AUTOFIN</option>
                      <option value="BANCO MULTIVA">BANCO MULTIVA</option>
                      <option value="BANCO FAMSA">BANCO FAMSA</option>
                      <option value="BANCOPPEL">BANCOPPEL</option>
                      <option value="AMERICAN EXPRESS">AMERICAN EXPRESS</option>
                      <option value="BANORTE">BANORTE</option>
                      <option value="STP">STP</option>
                      <option value="BANK UNICREDIT BANKA SLOVENIJA D.D.">BANK UNICREDIT BANKA SLOVENIJA D.D.</option>
                      <option value="BANK ALHABIB LIMITED">BANK ALHABIB LIMITED</option>
                      <option value="BANCO J.P. MORGAN S.A.">BANCO J.P. MORGAN S.A.</option>
                      <option value="WELLS FARGO">WELLS FARGO</option>
                      <option value="TD BANK">TD BANK</option>
                      <option value="SCOTIABANK">SCOTIABANK</option>
                      <option value="BANCO BANCO MONEX S.A. INSTITUCION DE BANCA MULTIPLE, MONEX GRUPO FINANCIERO">
                        BANCO BANCO MONEX S.A. INSTITUCION DE BANCA MULTIPLE, MONEX GRUPO FINANCIERO
                      </option>
                      <option value="KUSPIT">KUSPIT</option>
                       <option value="CITI MÉXICO">CITI MÉXICO</option>
                        <option value="PACIFIC PREMIER BANK">PACIFIC PREMIER BANK</option>


                    </select>
                    <div id="error_banco" class="text-danger"></div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group mb-5">
                    <label class="floating-label" for="concepto">Concepto:</label>
                    <input type="text" class="form-control escribe" id="concepto" name="concepto">
                    <div id="error_concepto" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group mb-5">
                    <label class="floating-label" for="cuenta">Cuenta:</label>
                    <input type="text" id="cuenta" name="cuenta" class="form-control escribe">
                    <div id="error_cuenta" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group mb-5">
                    <label class="floating-label" for="clabe">Clabe:</label>
                    <input type="text" id="clabe" name="clabe" class="form-control escribe">
                    <div id="error_clabe" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group mb-5">
                    <label class="floating-label" for="cantidad">Por la cantidad de:</label>
                    <input type="text" class="form-control escribe" id="cantidad" name="cantidad">
                    <div id="error_cantidad" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-5">
                    <label class="floating-label" for="cantidad_letra">Cantidad en letra:</label>
                    <input type="text" class="form-control" id="cantidad_letra" name="cantidad_letra">
                    <div id="error_letra" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group mb-5">
                    <label class="floating-label2" for="orden_compra">Orden de Compra:</label>
                    <input type="file" class="form-control" id="orden_compra" name="orden_compra" accept=".pdf" />
                    <div id="error_oc" class="text-danger"></div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-5">
                    <label class="floating-label2" for="factura">Factura:</label>
                    <input type="file" class="form-control" id="factura" name="factura" accept=".pdf" />
                    <div id="error_factura" class="text-danger"></div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-5">
                    <label class="floating-label2" for="caratula">Caratula Bancaria:</label>
                    <input type="file" class="form-control" id="caratula" name="caratula" accept=".pdf" />
                    <div id="error_caratula" class="text-danger"></div>
                  </div>
                </div>



              </div>
              <div class="col-md-12">
                <button id="btn-submit" class="btn btn-block btn-guardar font-solicitud mt-3">Generar Solicitud de Pago</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <!-- Modal -->
    <div class="modal fade" id="aprobarSolicitudModal" tabindex="-1" role="dialog" aria-labelledby="aprobarsolicitudModal">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Aprobar Solicitud</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" id="closeModalBtn">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="pdfModalBody" class="modal-body">
            <input type="hidden" name="id_solicitud" id="id_solicitud" value="" />
            <div id="btnAcciones" class="col-md-12 row">
              <div class="col-md-4 text-left">
                <button id="signBtn" class="btn btn-guardar">Aprobar Solicitud</button>
              </div>
              <div class="col-md-4">
                <div id="progressContainer" style="display: none;">
                  <div class="progress">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%;" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <p id="progressText" class="text-center">Estamos procesando tu solicitud, por favor espera...</p>
                </div>
                <div id="successMessage" class="text-center" style="display: none;">
                  <h4 id="successText">¡Proceso completado!</h4>
                </div>
                <div id="errorMessage" class="text-center" style="display: none;">
                  <h4 id="errorText">Hubo un error al procesar la solicitud.</h4>
                </div>
              </div>
              <div class="col-md-4 text-right">
                <button id="btn-signed" class="btn btn-danger"><b>Envio para autorizar</b></button>
              </div>
            </div>
          </div>
          <br>
          <div id="pdfContainer" class="text-center">
            <object id="pdfObject" type="application/pdf" width="95%" height="700">
              <p>Tu navegador no soporta archivos PDF.</p>
            </object>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let modal = document.getElementById("aprobarSolicitudModal");
      let closeModalBtn = document.getElementById("closeModalBtn");

      modal.addEventListener("shown.bs.modal", function() {
        closeModalBtn.focus(); // Asegura que el foco esté en el botón de cierre
      });

      modal.addEventListener("hidden.bs.modal", function() {
        document.activeElement.blur(); // Remueve el foco al cerrar el modal
      });
    });
  </script>




</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/js/finance/pagos_talento_v3.js"></script>
<!-- AdminLTE for demo purposes -->


<?= $this->endSection() ?>