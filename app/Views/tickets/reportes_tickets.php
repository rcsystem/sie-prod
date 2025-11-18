<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Ticket´s
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .switch {
        position: absolute;
        top: 50%;
        width: 100px;
        height: 30px;
        text-align: center;
        margin: -20px 0 0 0;
        background: #00bc9c;
        transition: all 0.2s ease;
        border-radius: 25px;
    }

    .switch span {
        position: absolute;
        width: 20px;
        height: 4px;
        top: 50%;
        left: 45%;
        margin: -2px 0px 0px -4px;
        background: #fff;
        display: block;
        transform: rotate(-45deg);
        transition: all 0.2s ease;
    }

    .switch span:after {
        content: "";
        display: block;
        position: absolute;
        width: 4px;
        height: 12px;
        /* left: 50%; */
        margin-top: -8px;
        background: #fff;
        transition: all 0.2s ease;
    }

    input[type=radio] {
        display: none;
    }

    .switch label {
        cursor: pointer;
        color: rgba(0, 0, 0, 0.4);
        width: 60px;
        line-height: 50px;
        transition: all 0.2s ease;
    }

    .lbl-yes {
        position: absolute;
        left: -8px;
        top: -10px;
        height: 20px;
    }

    .lbl-no {
        position: absolute;
        top: -10px;
        right: -6px;

    }

    .no:checked~.switch {
        background: #eb4f37;
    }

    .no:checked~.switch span:after {
        background: #fff;
        height: 20px;
        margin-top: -8px;
        margin-left: 8px;
    }

    .sl-modal {
        width: 30%;
        top: 35%;
        right: 35%;
        left: 35%;
    }

    .scrold {
        width: 25rem;
        height: 535px;
        overflow: hidden;
        overflow-y: scroll;
        border: 1px solid rgba(168, 168, 168, 0.4);
        border-top: none;
        background-color: white;
    }

    @media screen and (min-width: 1900px) {
        .scrold {
            width: 25rem;
            height: 52rem;
            overflow: hidden;
            overflow-y: scroll;
            border: 1px solid rgba(168, 168, 168, 0.4);
            border-top: none;
            background-color: white;
        }
    }

    .scrold::-webkit-scrollbar {
        width: 8px;
        /* height: 8px; */
    }

    .scrold::-webkit-scrollbar-track {
        background: rgba(241, 241, 241, .12);
    }

    .scrold::-webkit-scrollbar-thumb {
        background-color: rgba(168, 168, 168, 0.3);
        border-radius: 20px;
        /* border: 3px solid #474D54; */
    }

    .card-style-personal {
        border-radius: .25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24);
        padding: inherit;
        margin-bottom: 15px;

    }

    .nav-icon {
        margin-left: 0.05rem;
        margin-right: 0.2rem;
        text-align: center;
    }

    .card-html {
        /* flex: 1 0 0%; */
        margin-right: 7.5px;
        margin-bottom: 0;
        margin-left: 7.5px;
    }

    .box-cards {
        height: 100%;
        overflow: hidden;
        overflow-x: scroll;
        display: flex;
        padding-bottom: 10px;
    }

    .box-cards::-webkit-scrollbar {
        /* width: 8px; */
        height: 8px;
    }

    .box-cards::-webkit-scrollbar-track {
        background: rgba(241, 241, 241, .12);
    }

    .box-cards::-webkit-scrollbar-thumb {
        background-color: rgba(0.75, 0.75, 0.75, 0.4);
        border-radius: 20px;
        /* border: 3px solid #474D54; */
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h1 style="margin-bottom:1rem;">Reportes Tickets </h1>
                        </div>
                    </div>
                </div>
            </section>
            <?php if (session()->id_user == 1188 || session()->id_user == 1226 || session()->manager_tickets == 1){?>
                <section class="content-header bg-white" id="div-filtros-reports" style="margin-top: -1rem;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="direct_area">Area:</label>
                                <select class="form-control" id="direct_area" onchange="ObtenerInfomacion();">
                                    <option value="1">EPICOR</option>
                                    <option value="2">INFRAESTRUCTURA</option>
                                    <!-- <option value="3">MANTENIMIENTO</option> -->
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="date_range_reports">Fecha:</label>
                                <input type="hidden" id="fch-inicio">
                                <input type="hidden" id="fch-fin">
                                <input type="text" id="date_range_reports" class="form-control">
                            </div>
                            <div class="col-lg-3" style="text-align: start;padding-top: 2rem;">
                                <button id="btn_reset_range" class="btn btn-outline-dark btn-opcion">Restablecer fecha</button>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>
        </div>
    </div>
    <div class="content">
        <div>
            <section class="content pt-2" style="font-family: 'Roboto Condensed'; display: contents;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="far fa-star-half"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Nuevo (s)</span>
                                    <span class="info-box-number" id="div-nuevo"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon" style="background-color: #F39C12;border-color: #F39C12;color:white;"><i class="far fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">En proceso</span>
                                    <span class="info-box-number" id="div-proceso"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-star-half-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Concluido (s)</span>
                                    <span class="info-box-number" id="div-concluido"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cerrado (s)</span>
                                    <span class="info-box-number" id="div-cerrado"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="info-box" id="info_box_cumplimiento">
                            <div class="col-md-6" style="padding-top: 1rem;padding-left: 45%;">
                                <span class="info-box-icon bg-muted" style="font-size:40px;"><i class="far fa-calendar-check"></i></span>
                            </div>
                            <div class="info-box-content">
                                <span class="info-box-text">Cumplimiento</span>
                                <span class="info-box-number" id="div-cumplimiento" style="font-size:40px;margin-top:-20px;margin-bottom:-12px;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content pt-3">
                <div class="container-fluid" style="width:95%;">
                    <div class="row" style="width: 100%;">
                        <div class="col-md-6">
                            <div id="contenedor_uno">
                                <canvas id="grafico_uno" height="" width=""></canvas>
                            </div>
                        </div>
                        <div class="col-md-6 pt-4">
                            <div class="table-responsive" id="div-actividades">
                                <table class="table table-hover" id="tabla-actividades">
                                    <thead>
                                        <tr style="background-color:#999999;border-color:#999999;color:white;">
                                            <th style="text-align:center;">
                                                No.
                                            </th>
                                            <th style="text-align:center;">
                                                Actividad
                                            </th>
                                            <th style="text-align:center;">
                                                Total
                                            </th>
                                            <th style="text-align:center;">
                                                Tiempo
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color:#F73633;border-color:#F73633;color:white;">
                                            <td></td>
                                            <td></td>
                                            <td>Total:</td>
                                            <td>Promedio:</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php if (session()->manager_tickets != false /* || session()->id_user == 1063 */ || session()->id_user == 1188 || session()->id_user == 1226) { ?>
                <section class="content pt-4">
                    <div class="container-fluid" style="width:80%;">
                        <div class="row">
                            <div class=" col-12">
                                <div class="table-responsive" id="div-tecnicos">
                                    <table class="table table-hover" id="tabla-tecnicos">
                                        <thead>
                                            <tr style="background-color:#999999;border-color:#999999;color:white;">
                                                <th style="text-align:center;">
                                                    Ingeniero
                                                </th>
                                                <th style="text-align:center;">
                                                    Cumplimiento
                                                </th>
                                                <th style="text-align:center;">
                                                    Tiempo
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background-color:#F73633;border-color:#F73633;color:white;">
                                                <td></td>
                                                <td>Total:</td>
                                                <td>Promedio:</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/tickets/reportes.js"></script>
<?= $this->endSection() ?>