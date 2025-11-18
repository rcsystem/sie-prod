<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Facturas Almacen
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

    .preview-item {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }

    .preview-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: 4px;
    }

    .preview-document {
        padding: 15px;
        background: white;
        border-radius: 4px;
        text-align: center;
    }

    .file-icon {
        font-size: 48px;
        color: #6c757d;
    }

    .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        font-size: 14px;
    }

    .color-th {
        background-color: darkcyan;
        color: white;
    }

    /* ===== Estilos modernos para DataTables ===== */
.table-modern {
  border-collapse: separate !important;
  border-spacing: 0 8px !important;
}

.table-modern tbody tr {
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  transition: all 0.2s ease-in-out;
}

.table-modern tbody tr:hover {
  transform: scale(1.01);
  box-shadow: 0 4px 14px rgba(0,0,0,0.1);
}

.table-modern td, 
.table-modern th {
  vertical-align: middle !important;
  border: none !important;
}

.table-modern thead th {
  background: darkcyan;
  color: white;
  border: none !important;
  font-weight: 600;
  text-transform: uppercase;
}

.dataTables_wrapper .dataTables_filter input {
  border-radius: 20px;
  border: 1px solid #ccc;
  padding: 5px 12px;
  transition: all 0.2s ease-in-out;
}

.dataTables_wrapper .dataTables_filter input:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

/* Botones de acción */
.btn-icon {
  border-radius: 50%;
  padding: 6px 9px;
  font-size: 14px;
  margin-right: 4px;
  transition: all 0.2s ease;
}

.btn-icon:hover {
  transform: scale(1.1);
}

/* Badges de estatus */
.badge-pendiente {
  background: #ffcc00;
  color: #000;
  font-weight: 600;
  border-radius: 20px;
  padding: 6px 12px;
}

.badge-aprobado {
  background: #28a745;
  color: #fff;
  border-radius: 20px;
  padding: 6px 12px;
}

.badge-rechazado {
  background: #dc3545;
  color: #fff;
  border-radius: 20px;
  padding: 6px 12px;
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

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Facturas Almacen</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item"><a href="#">Almacen</a></li>
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
                <div class="card-header" style="background-color: darkcyan;">
                    <h3 class="card-title">Facturas</h3>
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
                    <div class="col-md-12 text-right">
                        <a class="btn btn-circle" onclick="abrirSolicitudModal()">
                            Nueva Solicitud
                        </a>
                        <!--  <button class="btn btn-guardar " onclick="abrirActivoModal()">Nuevo Activo</button> -->
                    </div>
                    <table class="table table-modern table-hover" id="table_facturas_almacen" ></table>
                </div>

                <div class="card-footer">
                    <a href="#">Facturas</a>
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
                        <h5 class="modal-title" id="verPermisosModal"> SOLICITUDES ALMACÉN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formSolicitud" enctype="multipart/form-data">
                            <div class="row font-solicitud">


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="floating-label" for="concepto">Concepto de solicitud:</label>
                                        <input type="text" id="concepto" name="concepto" class="form-control" />
                                        <div id="error_concepto" class="text-danger"></div>
                                    </div>
                                </div>


                                <div class="font-solicitud col-md-12 row">

                                    <div class="form-group col-md-4">
                                        <label for="pdfFiles">Archivo PDF:</label>
                                        <input type="file" id="pdfFiles" name="pdfFiles[]" accept="application/pdf" class="form-control-file" multiple />
                                    </div>
                                    <div id="previewContainer" class="row mt-3">
                                        <!-- Las previsualizaciones aparecerán aquí -->
                                    </div>
                                </div>

                                <!-- Contenedor para previsualizaciones -->


                                <div class="col-md-12">

                                    <button id="btnGuardarSolicitud" class="btn btn-block btn-guardar font-solicitud mt-3">Enviar Solicitud</button>
                                </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="modal fade" id="ModalFacturas" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Firmar Documentos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body d-flex" style="height:80vh;">
                        <!-- Lista lateral -->
                        <div id="listaArchivos" class="border-end pe-2" style="width:20%; overflow-y:auto;">
                            <ul class="list-group" id="listaPDFs"></ul>
                        </div>

                        <!-- Visor -->
                        <div class="flex-grow-1 ps-3 d-flex flex-column">
                            <iframe id="visorPDF" class="flex-grow-1" width="100%"></iframe>

                            <div class="mt-3 text-right">
                                <button class="btn btn-primary" id="btnFirmar">Firmar documento</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>




    <input type="hidden" id="user_" name="user_" value="<?= session()->id_user; ?>">
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/store/solicitudes_almacen_v1.js"></script>
<?= $this->endSection() ?>