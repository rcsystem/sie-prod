<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Estacionamiento
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css"> -->
<style>
    .btn-outline-moto {
        color: #F99403;
        border-color: #F99403;
    }
    .btn-outline-moto:not(:disabled):not(.disabled).active,
    .btn-outline-moto:not(:disabled):not(.disabled):active,
    .show>.btn-outline-moto.dropdown-toggle {
        color: #fff;
        background-color: #F99403;
        border-color: #F99403;
    }
    .btn-outline-moto:hover {
        color: #fff;
        background-color: #F99403;
        text-decoration: none;
    }

    .btn-outline-bici {
        color: #D36B7B;
        border-color: #D36B7B;
    }
    .btn-outline-bici:not(:disabled):not(.disabled).active,
    .btn-outline-bici:not(:disabled):not(.disabled):active,
    .show>.btn-outline-bici.dropdown-toggle {
        color: #fff;
        background-color: #D36B7B;
        border-color: #D36B7B;
    }
    .btn-outline-bici:hover {
        color: #fff;
        background-color: #D36B7B;
        text-decoration: none;
    }

    .btn-outline-n3 {
        color: #6BA7D3;
        border-color: #6BA7D3;
    }
    .btn-outline-n3:not(:disabled):not(.disabled).active,
    .btn-outline-n3:not(:disabled):not(.disabled):active,
    .show>.btn-outline-n3.dropdown-toggle {
        color: #fff;
        background-color: #6BA7D3;
        border-color: #6BA7D3;
    }
    .btn-outline-n3:hover {
        color: #fff;
        background-color: #6BA7D3;
        text-decoration: none;
    }

    .btn-outline-n1 {
        color: #CB6BD3;
        border-color: #CB6BD3;
    }
    .btn-outline-n1:not(:disabled):not(.disabled).active,
    .btn-outline-n1:not(:disabled):not(.disabled):active,
    .show>.btn-outline-n1.dropdown-toggle {
        color: #fff;
        background-color: #CB6BD3;
        border-color: #CB6BD3;
    }
    .btn-outline-n1:hover {
        color: #fff;
        background-color: #CB6BD3;
        text-decoration: none;
    }

    .btn-outline-jardin {
        color: #2EC903;
        border-color: #2EC903;
    }
    .btn-outline-jardin:not(:disabled):not(.disabled).active,
    .btn-outline-jardin:not(:disabled):not(.disabled):active,
    .show>.btn-outline-jardin.dropdown-toggle {
        color: #fff;
        background-color: #2EC903;
        border-color: #2EC903;
    }
    .btn-outline-jardin:hover {
        color: #fff;
        background-color: #2EC903;
        text-decoration: none;
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
                    <h1 class="m-0">Control Estacionamiento</h1>
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

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Registro de Entradas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" onclick="resetForm(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="form_entradas" method="post">
                            <div class="row">
                                <div class="col-md-8">
                                    <label>Tipo de Vehículo:</label>
                                    <input type="hidden" name="tipo" id="tipo">
                                    <div class="btn-group1 btn-group-toggle" data-toggle="buttons" style="text-align: center;">
                                        <label class="btn btn-outline-primary btn-opcion" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculo(1)"> AUTO
                                        </label>
                                        <label class="btn btn-outline-moto btn-opcion" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculo(2)"> MOTO
                                        </label>
                                        <label class="btn btn-outline-bici btn-opcion" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculo(3)"> BICI
                                        </label>
                                        <br>
                                        <label class="btn btn-outline-n3 btn-opcion" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculo(4)"> NAVE 3
                                        </label>
                                        <label class="btn btn-outline-jardin btn-opcion" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculo(5)"> JARDIN
                                        </label>
                                        <label class="btn btn-outline-n1 btn-opcion" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculo(6)"> NAVE 1
                                        </label>
                                    </div>
                                    <div class="text-danger" id="error_tipo"></div>
                                </div>
                                <div class="col-md-4">
                                    <label for="num_marbete">Número de Marbete:</label>
                                    <input class="form-control" type="number" min="1" name="num_marbete" id="num_marbete" onchange="datosMarbete()">
                                    <div class="text-danger" id="error_num_marbete"></div>
                                </div>
                                <!-- <div class="col-md-4">
                                    <label for="num_espacio" id="lbl_num_espacio">Número de Cajón:</label>
                                    <input class="form-control" type="number" min="1" name="num_espacio" id="num_espacio">
                                    <div class="text-danger" id="error_num_espacio"></div>
                                </div> -->
                            </div>
                            <input type="hidden" name="item_vehiculo" id="item_vehiculo">
                            <div id="div_usuario" style="text-align: center;"></div>
                            <div id="div_cards" class="btn-group1 btn-group-toggle" data-toggle="buttons" style="text-align: center;">
                            </div>
                            <hr>

                            <!-- <div class="p-3 pb-5 container is-max-desktop">
                                <h2 class="is-size-5 has-text-weight-bold has-text-centered mb-5">
                                    Leer código QR
                                </h2>
                                <div>
                                    <div id="qr-data" class="box has-text-centered is-size-5 has-text-white"></div>
                                    <canvas id="cam-canvas" class="d-none"></canvas>
                                    <div class="has-text-centered p-1 mb-4">
                                        <button class="button is-primary" id="btn-cam">Iniciar cámara</button>
                                    </div>
                                    <p class="help">
                                        Haz click en el botón para iniciar la cámara, seguidamente escanea un
                                        código QR y comprueba como al detectarlo aparece su contenido en la
                                        caja superior del vídeo.
                                    </p>
                                </div>
                            </div> -->

                            <button type="submit" id="btn_entradas" class="btn btn-guardar btn-lg btn-block">Registrar Entrada</button>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Registro de Salidas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" onclick="resetForm(2)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="form_salidas" method="post">
                            <div class="row">
                                <div class="col-md-8">
                                    <label>Tipo de Vehículo:</label>
                                    <input type="hidden" name="tipo_salida" id="tipo_salida">
                                    <div class="btn-group1 btn-group-toggle row" data-toggle="buttons" style="text-align: center;">
                                        <label class="btn btn-outline-primary btn-opcion-salida" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculoSalida(1)"> AUTO
                                        </label>
                                        <label class="btn btn-outline-moto btn-opcion-salida" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculoSalida(2)"> MOTO
                                        </label>
                                        <label class="btn btn-outline-bici btn-opcion-salida" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculoSalida(3)"> BICI
                                        </label>
                                        <br>
                                        <label class="btn btn-outline-n3 btn-opcion-salida" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculoSalida(4)"> NAVE 3
                                        </label>
                                        <label class="btn btn-outline-jardin btn-opcion-salida" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculoSalida(5)"> JARDIN
                                        </label>
                                        <label class="btn btn-outline-n1 btn-opcion-salida" style="width: 30%;margin: 2px;">
                                            <input type="radio" onclick="tipoVehiculoSalida(6)"> NAVE 1
                                        </label>
                                    </div>
                                    <div class="text-danger" id="error_tipo_salida"></div>
                                </div>
                                <div class="col-md-4">
                                    <label for="num_marbete_salida">Numero de Marbete:</label>
                                    <input class="form-control" type="number" min="1" name="num_marbete_salida" id="num_marbete_salida" onchange="datosMarbeteSalida()">
                                    <div class="text-danger" id="error_num_marbete_salida"></div>
                                </div>
                            </div>
                            <input type="hidden" name="item_vehiculo_salida" id="item_vehiculo_salida">
                            <div id="div_usuario_salidas" style="text-align: center;"></div>
                            <div id="div_cards_salidas" class="btn-group1 btn-group-toggle" data-toggle="buttons" style="text-align: center;">
                            </div>
                            <hr>
                            <div class="row col-md-12">
                                <label for="obs_salida">Observaciones:</label>
                                <textarea name="obs_salida" id="obs_salida" cols="15" rows="3" class="form-control"></textarea>
                            </div>
                            <hr>
                            <button type="submit" id="btn_salidas" class="btn btn-guardar btn-lg btn-block">Registrar Salida</button>
                            <!-- <div class="p-3 pb-5 container is-max-desktop">
                                <h2 class="is-size-5 has-text-weight-bold has-text-centered mb-5">
                                    Leer código QR
                                </h2>
                                <div>
                                    <div id="qr-data" class="box has-text-centered is-size-5 has-text-white"></div>
                                    <canvas id="cam-canvas" class="d-none"></canvas>
                                    <div class="has-text-centered p-1 mb-4">
                                        <button class="button is-primary" id="btn-cam">Iniciar cámara</button>
                                    </div>
                                    <p class="help">
                                        Haz click en el botón para iniciar la cámara, seguidamente escanea un
                                        código QR y comprueba como al detectarlo aparece su contenido en la
                                        caja superior del vídeo.
                                    </p>
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Estacionamiento</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<!-- <script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script> -->
<script src="<?= base_url() ?>/public/js/parking/control_in_out.js"></script>
<?= $this->endSection() ?>