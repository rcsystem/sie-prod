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
</style>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-solicitud">Alta solicitud de pago</h1>
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
          <h3 class="card-title">Detalle de Activo </h3>
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
          <table id="tbl_pago_adm" class=" font-tables display compact dataTable table-bordered table-striped nowrap" style="width:100%">

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
    <div class="modal fade" id="solicitudModal" tabindex="-1" role="dialog" aria-labelledby="solicitudModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Solicitud de Pago</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="uploadForm" enctype="multipart/form-data">
              <div class="row font-solicitud">

                <div class="col-md-4">
                  <div class="form-group">
                    <label class="floating-label" for="empresa">Empresa:</label>
                    <select name="empresa" id="empresa" class="form-control select-container">
                      <option value="" disabled selected></option>
                      <option value="Industrial de Valvulas S.A de C.V.">Industrial de Valvulas S.A de C.V.</option>
                      <option value="Grupo Walworth S.A. de C.V.">Grupo Walworth S.A. de C.V.</option>
                      <option value="Walworth Valvulas S.A. de C.V.">Walworth Valvulas S.A. de C.V.</option>
                    </select>
                    <div id="error_empresa" class="text-danger"></div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="floating-label" for="concepto">Concepto de solicitud:</label>
                    <select name="concepto" id="concepto" class="form-control">
                      <option value="" disabled selected></option>
                      <option value="NOMINA SEMANAL"> NOMINA SEMANAL</option>
                      <option value="NOMINA QUINCENAL">NOMINA QUINCENAL</option>
                      <option value="PENSION ALIMENTICIA">PENSION ALIMENTICIA</option>
                      <option value="CUOTA SINDICAL SEMANAL">CUOTA SINDICAL SEMANAL</option>
                      <option value="CUOTA SINDICAL EVENTOS SOCIALES">CUOTA SINDICAL EVENTOS SOCIALES</option>
                      <option value="AYUDA SINDICAL EVENTOS ESPECIALES">AYUDA SINDICAL EVENTOS ESPECIALES</option>
                      <option value="FONACOT">FONACOT</option>
                      <option value="FONDO DE AHORRO SEMANAL">FONDO DE AHORRO SEMANAL</option>
                      <option value="FONDO DE AHORRO FINIQUITOS">FONDO DE AHORRO FINIQUITOS</option>
                      <option value="CAJA DE AHORROS SINDICALIZADOS">CAJA DE AHORROS SINDICALIZADOS</option>
                      <option value="VALES DESPENSA">VALES DESPENSA</option>
                      <option value="PAGO CUOTAS IMSS">PAGO CUOTAS IMSS</option>
                      <option value="CAJA DE AHORROS EMPLEADOS">CAJA DE AHORROS EMPLEADOS</option>
                      <option value="DEVOLUCIÓN CAJA DE AHORROS FINIQUITOS">DEVOLUCIÓN CAJA DE AHORROS FINIQUITOS</option>
                      <option value="PRESTAMOS NOMINOM">PRESTAMOS NOMINOM</option>
                      <option value="OPTICA OCULAR">OPTICA OCULAR </option>
                      <option value="PAGO DE FINIQUITOS">PAGO DE FINIQUITOS</option>
                      <option value="PAGARÉS">PAGARÉS</option>
                      <option value="AYUDA POR DEFUNCIÓN">AYUDA POR DEFUNCIÓN</option>
                      <option value="APOYO POR DEFUNCIÓN">APOYO POR DEFUNCIÓN</option>
                      <option value="AYUDA SINDICAL VARIOS">AYUDA SINDICAL VARIOS</option>
                      <option value="TARJETAS VALES DE DESPENSA">TARJETAS VALES DE DESPENSA</option>
                      <option value="CAJA DE AHORRO EMPLEADOS">CAJA DE AHORRO EMPLEADOS</option>
                      <option value="DEVOLUCION FONDO DE AHORRO (BAJA)">DEVOLUCION FONDO DE AHORRO (BAJA)</option>
                      <option value="REPARTO DE UTILIDADES">REPARTO DE UTILIDADES</option>

                    </select>
                    <div id="error_concepto" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label class="floating-label" for="mes_solicitud">Mes:</label>
                    <select name="mes_solicitud" id="mes_solicitud" class="form-control">
                      <option value="" disabled selected></option>
                      <option value="Enero">Enero</option>
                      <option value="Febrero">Febrero</option>
                      <option value="Marzo">Marzo</option>
                      <option value="Abril">Abril</option>
                      <option value="Mayo">Mayo</option>
                      <option value="Junio">Junio</option>
                      <option value="Julio">Julio</option>
                      <option value="Agosto">Agosto</option>
                      <option value="Septiembre">Septiembre</option>
                      <option value="Octubre">Octubre</option>
                      <option value="Noviembre">Noviembre</option>
                      <option value="Diciembre">Diciembre</option>
                    </select>
                    <div id="error_mes" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group ">
                    <label class="floating-label" for="tipo_nomina">Tipo de nómina:</label>
                    <select name="tipo_nomina" id="tipo_nomina" class="form-control">
                      <option value="" disabled selected></option>
                      <option value="Semanal">Semanal</option>
                      <option value="Quincenal">Quincenal</option>
                    </select>
                    <div id="error_tipo" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label class="floating-label" for="periodo">Período:</label>
                    <select name="periodo" id="periodo" class="form-control"></select>
                    <div id="error_periodo" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label class="floating-label" for="fecha">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" onfocus="this.showPicker();">
                    <div id="error_fecha" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label class="floating-label" for="monto">Monto de la Solicitud:</label>
                    <input type="text" class="form-control" id="monto" name="monto">
                    <div id="error_monto" class="text-danger"></div>
                  </div>
                </div>

                <div class="col-md-8">
                  <div class="form-group">
                    <label class="floating-label" for="observaciones"></label>
                    <textarea name="observaciones" id="observaciones" cols="40" rows="2" class="form-control" placeholder="Observaciones"></textarea>
                    <div id="error_observaciones" class="text-danger"></div>
                  </div>
                </div>

              </div>

              <div class="font-solicitud col-md-12 row">

                <div class="form-group col-md-4">
                  <label for="pdfFile">Archivo PDF:</label>
                  <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf" class="form-control-file" required>
                </div>
                <!-- <div class="form-group col-md-4">
                  <label for="pdfFile">Archivo EXCEL:</label>
                  <input type="file" id="xlsFile" name="xlsFile" accept=".xlsx, .xls" class="form-control-file">
                </div> -->

                <div id="fileInputContainer" class="form-group col-md-4"></div>
                <div id="fileInputContainerSua" class="form-group"></div>
                <div id="fileInputContainerTxt" class="form-group col-md-4"></div>

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
    <div class="modal fade" id="aprobarSolicitudModal" tabindex="-1" role="dialog" aria-labelledby="aprobarsolicitudModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Archivo de la Solicitud</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="pdfModalBody" class="modal-body">


          <div id="pdfContainer">
            <object id="pdfObject" type="application/pdf" width="800" height="600">
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
<script src="<?= base_url() ?>/public/js/finance/finanzas_solicitud_v15.js"></script>
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