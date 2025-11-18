<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitud de Paquetería
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/packer/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Solicitud de Paquetería.</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Paquetería </li>
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
                    <h3 class="card-title">Paquetería</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="w-100 text-center p-0 mt-3 mb-2">
                                <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                                    <form id="msform" method="post">
                                        <!-- progressbar -->
                                        <ul id="progressbar">
                                            <li class="active" id="account"><strong>Datos Remitente</strong></li>
                                            <li id="personal"><strong>Datos Destinatario</strong></li>
                                            <li id="confirm"><strong>Descripción del Paquete</strong></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div> <br> <!-- fieldsets -->
                                        <fieldset id="fieldset_inicial">
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">DATOS REMITENTE:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 1 - 3</h2>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="empresa_solicitante">Empresa Solicitante:</label>
                                                            <select name="empresa_solicitante" id="empresa_solicitante" class="form-control" onchange="valida()">
                                                                <option value="">Selecciona una opción...</option>
                                                                <?php foreach ($company as $key => $value) { ?>
                                                                    <option value="<?= $value['name_company'] ?>"><?= $value["name_company"] ?></otion>
                                                                    <?php    } ?>
                                                                    <option value="OTRO">OTRO</otion>
                                                            </select>
                                                            <div id="error_empresa_solicitante" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div id="agrega_otro"></div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="nombre">Solicitante:</label>
                                                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= strtoupper(session()->name . " " . session()->surname); ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="puesto_trabajo">Area Operativa:</label>
                                                            <input type="text" class="form-control" id="puesto_trabajo" name="puesto_trabajo" value="<?= session()->cost_center; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="telefono_R">Número Telefonico:</label>
                                                            <input type="text" maxlength="20" class="form-control" id="telefono_R" name="telefono_R" value="" onchange="valida()">
                                                            <div id="error_telefono_R" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <!--- --->
                                                    <div class="col-md-12" style="text-align:center; border-top:5px; border-bottom:2px;"><label style="font-size:20px;">DIRECCION REMITENTE</label></div>
                                                    <!-- -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="calle_R">Solicitante:</label>
                                                            <input type="text" class="form-control" id="solicitante_R" name="solicitante_R" value="" onchange="valida()">
                                                            <div id="error_solicitante_R" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="calle_R">Calle:</label>
                                                            <input type="text" class="form-control" id="calle_R" name="calle_R" value="" onchange="valida()">
                                                            <div id="error_calle_R" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="colonia_R">Colonia:</label>
                                                            <input type="text" class="form-control" id="colonia_R" name="colonia_R" value="" onchange="valida()">
                                                            <div id="error_colonia_R" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="numero_R">Número:</label>
                                                            <input type="text" maxlength="6" class="form-control" id="numero_R" name="numero_R" value="" onchange="valida()">
                                                            <div id="error_numero_R" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="localidad_R">Localidad:</label>
                                                            <input type="text" class="form-control" id="localidad_R" name="localidad_R" value="" onchange="valida()">
                                                            <div id="error_localidad_R" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <!-- -->
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="estado_R">Estado:</label>
                                                            <input type="text" class="form-control" id="estado_R" name="estado_R" value="" onchange="valida()">
                                                            <div id="error_estado_R" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="pais_R">País:</label>
                                                            <input name="pais_R" id="pais_R" class="form-control" onchange="valida()">
                                                            <!-- <select >
                                                                <option value="">Selecciona una opción...</option>
                                                                <option value="mexico">MÉXICO</otion>
                                                                <option value="eua">ESTADOS UNIDOS</otion>
                                                                <option value="canada">CANADA</otion>
                                                            </select> -->
                                                            <div id="error_pais_R" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="cp_R">Código Postal:</label>
                                                            <input type="text" maxlength="8" class="form-control" id="cp_R" name="cp_R" value="" onchange="valida()">
                                                            <div id="error_cp_R" class="text-danger"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <input type="button" name="next" class="next action-button" value="Siguiente" />
                                        </fieldset>

                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">DATOS DESTINATARIO:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 2 - 3</h2>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="empresa_destino">Empresa Destino:</label>
                                                            <input type="text" class="form-control" id="empresa_destino" name="empresa_destino" value="" onchange="valida()">
                                                            <div id="error_empresa_destino" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="nombre_D">Nombre de Destinatario:</label>
                                                            <input type="text" class="form-control" id="nombre_D" name="nombre_D" value="" onchange="valida()">
                                                            <div id="error_nombre_D" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="telefono_D">Número Telefonico:</label>
                                                            <input type="text" maxlength="20" class="form-control" id="telefono_D" name="telefono_D" value="" onchange="valida()">
                                                            <div id="error_telefono_D" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"> </div>
                                                    <!--- --->
                                                    <div class="col-md-12" style="text-align:center; border-top:5px; border-bottom:2px;"><label style="font-size:20px;">DIRECCION DESTINATARIO</label></div>
                                                    <!-- -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="calle_D">Calle:</label>
                                                            <input type="text" class="form-control" id="calle_D" name="calle_D" value="" onchange="valida()">
                                                            <div id="error_calle_D" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="numero_D">Número:</label>
                                                            <input type="text" maxlength="6" class="form-control" id="numero_D" name="numero_D" value="" onchange="valida()">
                                                            <div id="error_numero_D" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="colonia_D">Colonia:</label>
                                                            <input type="text" class="form-control" id="colonia_D" name="colonia_D" value="" onchange="valida()">
                                                            <div id="error_colonia_D" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="localidad_D">Localidad:</label>
                                                            <input type="text" class="form-control" id="localidad_D" name="localidad_D" value="" onchange="valida()">
                                                            <div id="error_localidad_D" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <!-- -->
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="estado_D">Estado:</label>
                                                            <input type="text" class="form-control" id="estado_D" name="estado_D" value="" onchange="valida()">
                                                            <div id="error_estado_D" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="pais_D">País:</label>
                                                            <input type="text" class="form-control" id="pais_D" name="pais_D" value="" onchange="valida()">
                                                            <div id="error_pais_D" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="cp_D">Codigo Postal:</label>
                                                            <input type="text" class="form-control" id="cp_D" name="cp_D" value="" onchange="valida()">
                                                            <div id="error_cp_D" class="text-danger"></div>
                                                        </div>
                                                    </div> -->

                                                </div>
                                            </div>
                                            <input type="button" name="next" class="next2 action-button" value="Siguiente" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>

                                        <fieldset id="fieldset_final">
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">PAQUETE:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 3 - 3</h2>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-5">
                                                        <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                                                            <label>Tipo de Envío: </label>
                                                            <label id="dia_sig_" class="btn btn-primary">
                                                                <input type="radio" name="tipo_envio" id="dia_sig" class="" value="" onchange="valida()"> Dia Siguiente
                                                            </label>
                                                            <label id="terrestre_" class="btn btn-primary">
                                                                <input type="radio" name="tipo_envio" id="terrestre" class="" value="" onchange="valida()"> Terrestre
                                                            </label>

                                                        </div>
                                                        <div id="error_tipo_envio" class="text-center text-danger"></div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                                                            <label>Seguro: </label>
                                                            <label id="seguro_si_" class="btn btn-primary">
                                                                <input type="radio" name="seguro" id="seguro_si" class="" value="" onchange="valida()"> SI
                                                            </label>
                                                            <label id="seguro_no_" class="btn btn-primary">
                                                                <input type="radio" name="seguro" id="seguro_no" class="" value="" onchange="valida()"> No
                                                            </label>

                                                        </div>
                                                        <div id="error_seguro" class="text-center text-danger"></div>
                                                    </div>
                                                    <div id="monto" class="col-md-3">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                    <div class="btn-group1 btn-group-toggle text-center" data-toggle="buttons">
                                                            <label>Recoleccion:  </label>
                                                            <label id="recoleccion_si_" class="btn btn-primary">
                                                                <input type="radio" name="recoleccion" id="recoleccion_si" class="" value="" onchange="valida()"> SI
                                                            </label>
                                                            <label id="recoleccion_no_" class="btn btn-primary">
                                                                <input type="radio" name="recoleccion" id="recoleccion_no" class="" value="" onchange="valida()"> No
                                                            </label>

                                                        </div>
                                                        <div id="error_recoleccion" class="text-center text-danger"></div>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <label for="obs">Observaciones:</label>
                                                            <textarea class="form-control" cols="30" rows="3" id="obs" name="obs" value="" onchange="valida()"></textarea>
                                                            <div id="error_obs" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <button id="btn_agregar" style="color:white; background:#52492b; width:200px; height:40px;">Agregar paquete</button>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button id="btn_documentos" style="color:white; background:#52492b; width:200px; height:40px;">Agregar Documento</button>
                                                    </div>
                                                    <div class="col-md-7" id="error_paquete">
                                                    </div>
                                                </div>
                                                <hr>
                                                <div id="paquetes">
                                                </div>
                                                <br>
                                            </div>
                                            <input type="submit" name="btn_generar" class="next3 action-button" value="Generar" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Generar Solicitud Paquetería</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/packer/generates_request_v2.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>

<?= $this->endSection() ?>