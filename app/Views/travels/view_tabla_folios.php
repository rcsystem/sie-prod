<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Comprobaciones
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .my-a-card {
        color: black;
    }

    .card-style-personal {
        border-radius: .25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24);
        padding: inherit;
        margin-bottom: 15px;

    }

    .scrold {
        width: 100%;
        height: 535px;
        overflow: hidden;
        overflow-y: scroll;
        border: 1px solid rgba(168, 168, 168, 0.4);
        border-top: none;
        background-color: white;
    }

    .scrold::-webkit-scrollbar {
        width: 8px;
    }

    .scrold::-webkit-scrollbar-track {
        background: rgba(241, 241, 241, .12);
    }

    .scrold::-webkit-scrollbar-thumb {
        background-color: rgba(168, 168, 168, 0.3);
        border-radius: 20px;
    }

    .nav-icon {
        /* margin-left: 0.05rem; */
        margin-right: 5px;
        text-align: center;
    }

    .card-html {
        width: 45%;
        margin-right: 7.5px;
        margin-bottom: 0;
        margin-left: 7.5px;
    }

    .box-cards {
        padding-left: 7%;
        height: 100%;
        overflow: hidden;
        display: flex;
        padding-bottom: 10px;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Comprobaciónes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Viajes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="box-cards">
            <div class="card-html card-secondary">
                <div class="card-header">
                    <h3 class="card-title" style="text-align: center;">
                        VÍATICOS
                    </h3>
                </div>
                <div class="card-body scrold" id="viaticos"></div>
            </div>
            <div class="card-html">
                <div class="card-header" style="background-color: #F39C12;border-color: #F39C12;color:white;">
                    <h3 class="card-title" style="text-align: center;">
                        GASTOS
                    </h3>
                </div>
                <div class="card-body scrold" id="gastos"></div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/travels/tabla_folios.js"></script>
<?= $this->endSection() ?>