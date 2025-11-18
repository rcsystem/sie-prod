<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Datos Generales
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/datos_generales/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .custom-file-label::after {
        content: "Subir";
    }

    .file-error {
        background-color: red;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Datos Generales.</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Datos Generales</li>
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
                    <h3 class="card-title">Información General</h3>
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
                                    <form id="msform" method="post" enctype="multipart/form-data">
                                        <!-- progressbar -->
                                        <ul id="progressbar">
                                            <li class="active" id="account"><strong>Datos del Usuario</strong></li>
                                            <li id="personal"><strong>En caso de Emergencia</strong></li>
                                            <li id="payment"><strong>Domicilio</strong></li>
                                            <li id="laboral"><strong>Datos Cónyuge</strong></li>
                                            <li id="conocimiento"><strong>Datos Familiares</strong></li>
                                            <li id="competencia"><strong>Formación Académica</strong></li>
                                            <li id="documentos"><strong>Documentacion</strong></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div> <br> <!-- fieldsets -->
                                        <fieldset id="fieldset_inicial">
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Datos Internos:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 1 - 7</h2>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="empresa_solicitante">Numero de Nomina:</label>
                                                            <input type="number" min="1" id="num_nomina" name="num_nomina" class="form-control" onchange="validar()">
                                                            <div id="error_num_nomina" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="nombre_usuario">Nombre:</label>
                                                            <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" onchange="validar()">
                                                            <div id="error_nombre_usuario" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="puestos" class="form-group">
                                                            <label for="ape_paterno">Apellido Paterno:</label>
                                                            <input type="text" id="ape_paterno" name="ape_paterno" class="form-control" onchange="validar()">
                                                        </div>
                                                        <div id="error_ape_paterno" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="puestos" class="form-group">
                                                            <label for="ape_materno">Apellido Materno:</label>
                                                            <input type="text" id="ape_materno" name="ape_materno" class="form-control" onchange="validar()">
                                                        </div>
                                                        <div id="error_ape_materno" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="puestos" class="form-group">
                                                            <label for="fecha_ingreso">Fecha de Ingreso:</label>
                                                            <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control" onchange="validar()">
                                                        </div>
                                                        <div id="error_fecha_ingreso" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                                                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" onchange="validar()">
                                                            <div id="error_fecha" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="genero">Genero:</label>
                                                            <select name="genero" id="genero" class="form-control" onchange="validar()">
                                                                <option value="">Seleccionar...</option>
                                                                <option value="Femenino">Femenino</option>
                                                                <option value="Masculino">Masculino</option>
                                                            </select>
                                                        </div>
                                                        <div id="error_genero" class="request-error text-danger"></div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div id="" class="form-group">
                                                            <label for="edad_usuario">Edad:</label>
                                                            <input type="number" min="1" id="edad_usuario" name="edad_usuario" class="form-control" onchange="validar()">
                                                        </div>
                                                        <div id="error_edad_usuario" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="" class="form-group">
                                                            <label for="curp">CURP:</label>
                                                            <input type="text" style="text-transform:uppercase;" id="curp" name="curp" class="form-control" onchange="validar()">
                                                        </div>
                                                        <div id="error_curp" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div id="" class="form-group">
                                                            <label for="rfc">RFC:</label>
                                                            <input type="text" style="text-transform:uppercase;" id="rfc" name="rfc" class="form-control" onchange="validar()">
                                                        </div>
                                                        <div id="error_rfc" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="estado_civil">Estado Civil:</label>
                                                            <select name="estado_civil" id="estado_civil" class="form-control" onchange="validar()">
                                                                <option value="">Seleccionar...</option>
                                                                <option value="Soltero">Solter@</option>
                                                                <option value="Casado">Casad@</option>
                                                                <option value="Union Libre">Unión Libre</option>
                                                                <option value="Viudo">Viud@</option>

                                                            </select>
                                                            <div id="error_estado_civil" class="text-danger"></div>
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
                                                        <h2 class="fs-title">En caso de Emergencia:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 2 - 7</h2>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="fieldlabels">Parentesco:</label>
                                                        <select name="parentesco[]" id="parentesco_1" class="form-control" onchange="validar()">
                                                            <option value="">Seleccionar...</option>
                                                            <option value="Madre">Madre</option>
                                                            <option value="Padre">Padre</option>
                                                            <option value="Hermano">Hermano</option>
                                                            <option value="Hermana">Hermana</option>
                                                            <option value="Esposo">Esposo</option>
                                                            <option value="Esposa">Esposa</option>
                                                            <option value="Hija">Hija</option>
                                                            <option value="Hijo">Hijo</option>
                                                            <option value="Tia">Tía</option>
                                                            <option value="Tio">Tío</option>
                                                            <option value="Otro">Otro</option>
                                                        </select>
                                                        <div id="error_parentesco_1" class="text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="fieldlabels">Contactar a:</label>
                                                        <input type="text" id="contacto_emergencia_1" name="contacto_emergencia[]" class="form-control" placeholder="Ejemplo: Juanito Perez Garcia" onchange="validar()" />
                                                        <div id="error_contacto_emergencia_1" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="fieldlabels">Teléfono Celular de Contacto:</label>
                                                        <input type="tel" id="tel_contacto_1" name="tel_contacto[]" class="form-control" placeholder="Ejemplo: 55-01-02-03-04" onchange="validar()" />
                                                        <div id="error_tel_contacto_1" class="request-error text-danger"></div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="fieldlabels">2.Parentesco:</label>
                                                        <select name="parentesco[]" id="parentesco_2" class="form-control" onchange="validar()">
                                                            <option value="">Seleccionar...</option>
                                                            <option value="Madre">Madre</option>
                                                            <option value="Padre">Padre</option>
                                                            <option value="Hermano">Hermano</option>
                                                            <option value="Hermana">Hermana</option>
                                                            <option value="Esposo">Esposo</option>
                                                            <option value="Esposa">Esposa</option>
                                                            <option value="Hija">Hija</option>
                                                            <option value="Hijo">Hijo</option>
                                                            <option value="Tia">Tía</option>
                                                            <option value="Tio">Tío</option>
                                                            <option value="Otro">Otro</option>
                                                        </select>
                                                        <div id="error_parentesco_2" class="text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="fieldlabels">2.Contactar a:</label>
                                                        <input type="text" id="contacto_emergencia_2" name="contacto_emergencia[]" class="form-control" placeholder="Ejemplo: Juanito Perez Garcia" onchange="validar()" />
                                                        <div id="error_contacto_emergencia_2" class="request-error text-danger"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="fieldlabels">2.Teléfono Celular de Contacto:</label>
                                                        <input type="tel" id="tel_contacto_2" name="tel_contacto[]" class="form-control" placeholder="Ejemplo: 55-01-02-03-04" onchange="validar()" />
                                                        <div id="error_tel_contacto_2" class="request-error text-danger"></div>
                                                    </div>


                                                </div>
                                            </div>
                                            <br>
                                            <input type="button" name="next" class="next2 action-button" value="Siguiente" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Regresar" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Domicilio:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 3 - 7</h2>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="calle">Calle:</label>
                                                            <input type="text" id="calle" name="calle" class="form-control" onchange="validar()">
                                                            <div id="error_calle" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="num_exterior">Numero Exterior:</label>
                                                            <input type="text" id="num_exterior" name="num_exterior" class="form-control" onchange="validar()">
                                                            <div id="error_num_exterior" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="num_interior">Numero Interior:</label>
                                                            <input type="text" id="num_interior" name="num_interior" class="form-control" onchange="validar()">
                                                            <div id="error_num_interior" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="colonia">Colonia:</label>
                                                            <input type="text" id="colonia" name="colonia" class="form-control" onchange="validar()">
                                                            <div id="error_colonia" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="municipio">Municipio:</label>
                                                            <input type="text" id="municipio" name="municipio" class="form-control" onchange="validar()">
                                                            <div id="error_municipio" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="estado">Estado:</label>
                                                            <input type="text" id="estado" name="estado" class="form-control" onchange="validar()">
                                                            <div id="error_estado" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="colonia">Codigo Postal:</label>
                                                            <input type="number" min="1" id="codigo_postal" name="codigo_postal" class="form-control" onchange="validar()">
                                                            <div id="error_cp" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>


                                                </div>

                                            </div>
                                            <br>
                                            <input type="button" name="next" class="next3 action-button" value="Siguiente" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Datos del Cónyuge:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 4 - 7</h2>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="conyuge">Nombre Completo:</label>
                                                            <input type="text" class="form-control" id="conyuge" name="conyuge" onchange="validar()">
                                                            <div id="error_conyuge" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="edad_conyuge">Edad:</label>
                                                            <input type="number" min="1" class="form-control" id="edad_conyuge" name="edad_conyuge" onchange="validar()">
                                                            <div id="error_edad_conyuge" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="ocupacion_conyuge">Ocupación:</label>
                                                            <input type="text" class="form-control" id="ocupacion_conyuge" name="ocupacion_conyuge" onchange="validar()">
                                                            <div id="error_ocupacion_conyuge" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tel_conyuge">Numero Teléfonico Celular:</label>
                                                            <input type="tel" class="form-control" id="tel_conyuge" name="tel_conyuge" placeholder="Ejemplo: 55-01-02-03-04" onchange="validar()">
                                                            <div id="error_tel_conyuge" class="request-error text-danger"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <input type="button" name="next" class="next4 action-button" value="Siguiente" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Datos Padres del Usuario <?php echo session()->name; ?> <?php echo session()->surname; ?></h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 5 - 7</h2>
                                                    </div>
                                                    <div id="extra_1" class="col-md-12 row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="hijo_1">Nombre del Padre:</label>
                                                                <input type="text" class="form-control" id="padres_1" name="padres[]" onchange="validar()">
                                                                <div id="error_padres_1" style="margin-top:-26px;" class="text-danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="hijo_fecha_1">Fecha de Nacimiento:</label>
                                                                <input type="date" class="form-control" id="padres_fecha_1" name="padres_fecha[]" onchange="validar()">
                                                                <div id="error_padres_fecha_1" style="margin-top:-26px;" class="text-danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="hijo_genero_1">Genero:</label>
                                                                <select name="padres_genero[]" id="padres_genero_1" class="form-control" onchange="validar()">
                                                                    <option value="">Seleccionar</option>
                                                                    <option value="Femenino">Femenino</option>
                                                                    <option value="Masculino">Masculino</option>
                                                                </select>
                                                                <div id="error_padres_genero_1" class=" text-danger"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="estatus_padres_1">Estado:</label>
                                                                <select name="estatus_padres[]" id="estatus_padres_1" class="form-control" onchange="validar()">
                                                                    <option value="">Seleccionar</option>
                                                                    <option value="Vive">Vive</option>
                                                                    <option value="Finado">Finado</option>
                                                                </select>
                                                                <div id="error_estatus_padres_1" class="text-danger"></div>
                                                            </div>
                                                        </div>
                                                        <div id="status_padres_1">
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="hijo_2">Nombre de la Madre:</label>
                                                            <input type="text" class="form-control" id="padres_2" name="padres[]" onchange="validar()">
                                                            <div id="error_padres_2" style="margin-top:-26px;" class="text-danger"></div>
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <label for="hijo_fecha_2">Fecha de Nacimiento:</label>
                                                            <input type="date" class="form-control" id="padres_fecha_2" name="padres_fecha[]" onchange="validar()">
                                                            <div id="error_padres_fecha_2" style="margin-top:-26px;" class="text-danger"></div>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <label for="hijo_genero_1">Genero:</label>
                                                            <select name="padres_genero[]" id="padres_genero_2" class="form-control" onchange="validar()">
                                                                <option value="">Seleccionar</option>
                                                                <option value="Femenino">Femenino</option>
                                                                <option value="Masculino">Masculino</option>
                                                            </select>
                                                            <div id="error_padres_genero_2" class="text-danger"></div>
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <label for="estatus_padres_2">Estado:</label>
                                                            <select name="estatus_padres[]" id="estatus_padres_2" class="form-control" onchange="validar()">
                                                                <option value="">Seleccionar</option>
                                                                <option value="Vive">Vive</option>
                                                                <option value="Finado">Finado</option>
                                                            </select>
                                                            <div id="error_estatus_padres_2" class="text-danger"></div>
                                                        </div>
                                                        <div id="status_padres_2">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h2 class="fs-title">Datos Hijo(s) del Usuario <?php echo session()->name; ?> <?php echo session()->surname; ?>:</h2>
                                                        <p style="color:red;">*NOTA: Sí, NO tienes hijos, puedes dejar vacío los campos de abajo 🢃 </p>
                                                    </div>
                                                    <div class="col-md-12 row">
                                                        <div class="col-md-2">
                                                            <button id="btn-agregar-item" class="btn btn-hijo" type="button"><i class="fas fa-user-plus"></i> Agregar</button>
                                                        </div>
                                                        <div id="resultado" class="col-md-10">

                                                        </div>
                                                    </div>
                                                    <div id="form_duplica" class="row col-md-12">
                                                        <div id="duplica" class="agrega-item col-md-12">
                                                            <div id="item-duplica" class=""></div>
                                                        </div>
                                                        <div id="extra_1" class="col-md-12 row">

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="hijo_1">Nombre Completo:</label>
                                                                    <input type="text" class="form-control" id="hijo_1" name="hijo[]">
                                                                    <div id="error_hijo_1" class="request-error text-danger"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="hijo_fecha_1">Fecha de Nacimiento:</label>
                                                                    <input type="date" class="form-control" id="hijo_fecha_1" name="hijo_fecha[]">
                                                                    <div id="error_hijo_fecha_1" class="request-error text-danger"></div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="hijo_genero_1">Genero:</label>
                                                                    <select name="hijo_genero[]" id="hijo_genero_1" class="form-control">
                                                                        <option value="">Seleccionar</option>
                                                                        <option value="Femenino">Femenino</option>
                                                                        <option value="Masculino">Masculino</option>
                                                                    </select>
                                                                    <div id="error_hijo_genero_1" class="request-error text-danger"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="hijo_fecha_1">Edad:</label>
                                                                    <input type="number" class="form-control" id="hijo_edad_1" name="hijo_edad[]">
                                                                    <div id="error_hijo_fecha_1" class="request-error text-danger"></div>
                                                                </div>
                                                            </div>


                                                            <div id="btn_eliminar_1" class="form-group col-md-1"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="button" name="next" class="next5 action-button" value="Siguiente" />
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Competencias:</h2>
                                                        <!-- <p style="color:red;">*NOTA: Subir los Comprobantes en formato PDF</p> -->
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 6 - 7</h2>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="escolaridad">Escolaridad:</label>
                                                            <select class="form-control" id="escolaridad" name="escolaridad">
                                                                <option value="">Seleccionar...</option>
                                                                <option value="Primaria">Primaria</option>
                                                                <option value="Secundaria">Secundaria</option>
                                                                <option value="Bachillerato General">Bachillerato General</option>
                                                                <option value="Bachillerato Técnico">Bachillerato Técnico</option>
                                                                <option value="Licenciatura">Licenciatura</option>
                                                                <option value="Ingenieria">Ingenieria</option>
                                                                <option value="Especialidad">Especialidad</option>
                                                                <option value="Maestria">Maestría</option>
                                                                <option value="Doctorado">Doctorado</option>
                                                            </select>
                                                            <div id="error_esc" class="text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <div id="tipo_escolaridad"></div>
                                                </div>
                                            </div>
                                            <button class="action-button" type="submit" id="btn_msform">Guardar</button>
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset>

                                        <!-- <fieldset id="fieldset_final">
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Documentacion:</h2>
                                                        <p style="color:red;">*NOTA: Subir los Comprobantes en formato PDF</p>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Paso 7 - 7</h2>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="input-group col-md-4">
                                                        <label for="doc_acta">Acta de Nacimiento:</label>
                                                    </div>
                                                    <div class="input-group col-md-7">
                                                        <div class="custom-file">
                                                            <input type="file" accept="application/pdf" class="custom-file-input" id="doc_acta" name="doc_acta" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                                            <label id="lbl_acta" class="custom-file-label" for="doc_acta">Acta de Nacimiento</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="input-group col-md-4">
                                                        <label for="doc_curp">CURP:</label>
                                                    </div>
                                                    <div class="input-group col-md-7">
                                                        <div class="custom-file">
                                                            <input type="file" accept="application/pdf" class="custom-file-input" id="doc_curp" name="doc_curp" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                                            <label id="lbl_curp" class="custom-file-label" for="doc_curp">CURP</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="input-group col-md-4">
                                                        <label for="doc_rfc">RFC:</label>
                                                    </div>
                                                    <div class="input-group col-md-7">
                                                        <div class="custom-file">
                                                            <input type="file" accept="application/pdf" class="custom-file-input" id="doc_rfc" name="doc_rfc" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                                            <label id="lbl_rfc" class="custom-file-label" for="doc_rfc">RFC</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="input-group col-md-4">
                                                        <label for="doc_domicilio">Comprobante de Domicilio:</label>
                                                    </div>
                                                    <div class="input-group col-md-7">
                                                        <div class="custom-file">
                                                            <input type="file" accept="application/pdf" class="custom-file-input" id="doc_domicilio" name="doc_domicilio" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                                            <label id="lbl_domicilio" class="custom-file-label">Domicilio</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="input-group col-md-4">
                                                        <label for="doc_estudios">Comprobante de Ultimo Grado de Estudios:</label>
                                                    </div>
                                                    <div class="input-group col-md-7">
                                                        <div class="custom-file">
                                                            <input type="file" accept="application/pdf" class="custom-file-input" id="doc_estudios" name="doc_estudios" aria-describedby="inputGroupFileAddon01" onchange="validar()">
                                                            <label id="lbl_estudios" class="custom-file-label" for="doc_estudios">Ultimo Grado de Estudios</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px;" id="ingles_div"></div>
                                                <div class="row" style="margin-top:10px;" id="cv_div"></div>
                                            </div>
                                            <button class="action-button" type="submit" id="btn_msform">Guardar</button>
                                            <input type="button" name="previous" class="previous action-button-previous" value="Anterior" />
                                        </fieldset> -->

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Datos Generales</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/survey/generate_v5.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<?= $this->endSection() ?>