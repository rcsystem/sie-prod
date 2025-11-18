<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Recorridos de HSE
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .imagePreview {
        max-width: 100%;
        max-height: 300px;
        margin-top: 20px;
    }

    .btn_tomar_foto {
        display: inline-block;
        width: 100%;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        color: #fff;
        background-color: #4CAF50;
        border: none;
        border-radius: 4px;
    }

    .foto {
        display: none;
    }

    .space-up {
        margin-top: 5px;
    }

    .btn-option-form {
        font-size: 20px !important;
        width: 45%;
    }

    .btn-option-form-3 {
        font-size: 20px !important;
        width: 30%;
    }

    .card-style-personal {
        border-radius: .25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24);
        cursor: pointer;
        padding: 5px 15px 15px 15px !important;
        margin-bottom: 15px;
        margin-left: 5px;
        margin-right: 5px;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Reportes HSE</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                            <li class="breadcrumb-item active">HSE</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="content-header">
            <form id="form_campos_incidencias">
                <div class="row">
                    <?php if (session()->id_user == 1063 || session()->id_user == 1  || session()->id_user == 75) { ?>
                        <div class="col-md-5">
                            <h1>Gráfico de Incidencias</h1>
                        </div>
                        <div class="col-md-2">
                            <select id="departamento" name="departamento" class="js-example-basic-single form-control" style="width: 100%;" onchange="traerDatos()">
                                <?php foreach ($departamentos as $depto) { ?>
                                    <option value="<?= $depto->id_depto; ?>"><?= $depto->departament; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="fechas_reporte" id="fechas_reporte" class="form-control flatpickr-input" style="background-color: #fff;" onchange="traerDatos()">
                        </div>
                        <div class="col-md-2">
                            <select id="mes_reporte" class=" form-control">
                                <?php foreach ($fechas as $depto) { ?>
                                    <option value="<?= $depto->orden; ?>"><?= $depto->mes; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-success" id="btn_descargar_excel"><i class="fas fa-file-excel"></i></button>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-6">
                            <h1>Gráfico de Incidencias</h1>
                        </div>
                        <div class="col-md-3">
                            <select id="departamento" name="departamento" class="js-example-basic-single form-control" style="width: 100%;" onchange="traerDatos()">
                                <?php foreach ($departamentos as $depto) { ?>
                                    <option value="<?= $depto->id_depto; ?>"><?= $depto->departament; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="fechas_reporte" id="fechas_reporte" class="form-control flatpickr-input" style="background-color: #fff;" onchange="traerDatos()">
                        </div>
                    <?php } ?>
                </div>
            </form>
        </div>
    </section>
    <div class="container">
        <div style="width: 100%;">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <section>
        <div class="modal fade" id="miModal" role="dialog" aria-labelledby="nuevoTicketModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="tittle_modal"></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row" id="modal_body">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- Agrega los enlaces a los archivos JS de jQuery y Chart.js -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/flatpickr.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/idioma/es.js"></script>
<script src="<?= base_url() ?>/public/plugins/chart.js/Chart.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/toursHSE/view_reports_ToursHSE_v.js"></script>
<?= $this->endSection() ?>