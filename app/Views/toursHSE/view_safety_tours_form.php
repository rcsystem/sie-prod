<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Recorridos de HSE
<?= $this->endSection() ?>
<?= $this->section('css') ?>
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
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Recorridos de HSE</h1>
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
                    <h3 class="card-title">Alta de Incidencia</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" id="card_reporte_infraccion">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_reporte_infraccion">
                        <div class="row">
                            <div class="col-md-3" style="text-align: center;">
                                <label>Tipo de Motivo Inseguro:</label>
                                <input type="hidden" name="tipo_reporte" id="tipo_reporte">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-primary btn-opcion">
                                        <input type="radio" onclick="tipoReporte(1)"> ACTOS
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-primary btn-opcion">
                                        <input type="radio" onclick="tipoReporte(2)"> CONDICIONES
                                    </label>
                                </div>
                                <div class="text-danger" id="error_tipo_reporte"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <input type="hidden" name="opc_seguimiento" id="opc_seguimiento">
                                <label>Requiere Seguimiento:</label>
                                <div class="form-check">
                                    <input class="form-check-input" style="width: 30px;height: 30px;" type="checkbox" id="seguimiento" class="form-control" onclick="actualizarValor(this)">
                                    <label class="form-check-label" for="miCheckbox" id="lbl_seguimiento" style="margin-left: 20px;font-size: 24px;">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="div_usuario" style="display: none;">
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <input type="hidden" name="opc_retro_jefe" id="opc_retro_jefe">
                                <label>Requiere concientización:</label>
                                <div class="form-check">
                                    <input class="form-check-input" style="width: 30px;height: 30px;" type="checkbox" id="retro_jefe" class="form-control" onclick="actualizarValor(this)">
                                    <label class="form-check-label" for="miCheckbox" id="lbl_retro_jefe" style="margin-left: 20px;font-size: 24px;">No</label>
                                </div>
                            </div>
                            <div class="col-md-3 space-up">
                                <label for="nomina">Nomina</label>
                                <input type="number" min="1" name="nomina" id="nomina" class="form-control" onchange="pintarNombre(this)">
                                <div id="error_nomina" class="text-danger"></div>
                            </div>
                            <div class="col-md-3 space-up">
                                <label>Nombre Usuario</label>
                                <input type="hidden" id="id_usuario" name="id_usuario">
                                <input type="text" id="nombre_usuario" class="form-control" readonly>
                            </div>
                            <div class="col-md-3 space-up">
                                <label>Gravedad de Incidencia:</label>
                                <input type="hidden" name="valor_gravedad" id="valor_gravedad">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons" style="text-align: center;">
                                    <label style="font-size: 16px;" class="btn btn-outline-success btn-option-form-3 btn-opcion-sub">
                                        <input id="gravedad" type="radio" onclick="gravedadNivel(this,1)">LEVE
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-warning btn-option-form-3 btn-opcion-sub">
                                        <input id="gravedad" type="radio" onclick="gravedadNivel(this,2)">MEDIA
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-danger btn-option-form-3 btn-opcion-sub">
                                        <input id="gravedad" type="radio" onclick="gravedadNivel(this,3)">GRAVE
                                </div>
                                <div class="text-danger" id="error_gravedad"></div>
                            </div>
                        </div>
                        <div class="row" id="div_departamento" style="display: none;">
                            <div class="col-md-3 space-up">
                                <label>Departamento:</label>
                                <select id="id_departamento" name="id_departamento" class="select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="limpiarError(this)">
                                    <option value="">Seleccionar Opción...</option>
                                    <?php foreach ($departamentos as $key) {  ?>
                                        <option value="<?= $key->id_depto; ?>"><?= $key->departament; ?></option>
                                    <?php } ?>
                                    <option value="0">OTRO</option>
                                </select>
                                <div id="error_id_departamento" class="text-danger"></div>
                            </div>
                            <div class="col-md-3 space-up" id="div_otro" style="display: none;">
                                <label>OTRO:</label>
                                <input type="text" name="otro_depto" id="otro_depto" class="form-control">
                                <div id="error_otro_depto" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 space-up">
                                <label>¿Qué identifica? :</label>
                                <select id="tipo_incidencia" name="tipo_incidencia" class="form-control" onchange="limpiarError(this)"></select>
                                <div id="error_tipo_incidencia" class="text-danger"></div>
                            </div>
                            <div class="col-md-3 space-up">
                                <label>Descripción breve :</label>
                                <textarea name="descripcion" id="descripcion" cols="10" rows="3" class="form-control" onchange="limpiarError(this)"></textarea>
                                <div id="error_descripcion" class="text-danger"></div>
                            </div>
                            <!--  <div class="col-md-3 space-up" style="text-align: center;display: flex;align-items: center;">
                                <button type="button" id="btn_foto_incidencia" class="btn_tomar_foto" onclick="document.getElementById('foto_incidencia').click();"><i class="fas fa-camera" style="margin-right: 10px;"></i>TOMAR FOTO1
                                </button>
                            </div>
                            <div class="col-md-3 space-up">
                                <img id="previa_foto_incidencia" class="imagePreview" alt="Imagen previa" style="display:none; max-width: 91%; margin-top: 10px;margin-left: 5%;">
                                <input type="file" accept="image/*" class="foto" capture="camera" id="foto_incidencia" name="foto_incidencia" onchange="mostrarImagenPrevia(this);">
                            </div> -->
<!-- 
                            <button type="button" id="btnAbrirGaleria">Abrir Galería</button>
<input type="file" id="galeriaInput" accept="image/*" style="display:none;"> -->


                            <!-- Botón para tomar foto -->
                            <div class="col-md-3 space-up" style="text-align: center; display: flex; align-items: center;">
                                <button type="button" id="btn_foto_incidencia" class="btn_tomar_foto" onclick="tomarFoto();">
                                    <i class="fas fa-camera" style="margin-right: 10px;"></i>TOMAR FOTO
                                </button>
                            </div>
                            <!-- Previsualización de la imagen -->
                            <div class="col-md-3 space-up">
                                <img id="previa_foto_incidencia" class="imagePreview" alt="Imagen previa" style="display:none; max-width: 91%; margin-top: 10px; margin-left: 5%;">
                                <input type="file" accept="image/*" class="foto" capture="camera" id="foto_incidencia" name="foto_incidencia" onchange="mostrarImagenPrevia(this);" style="display:none;">
                            </div>
                        </div>
                        <div class="row" id="div_reincidencias" style="display:none;"></div>
                        <div class="row" id="div_msj_rh" style="display:none;">
                            <div class="col-md-3 space-up">
                                <label>Mensaje extra de la sancion:</label>
                                <textarea name="correo_rh" id="correo_rh" cols="10" rows="3" class="form-control" onchange="limpiarError(this)"></textarea>
                                <div id="error_correo_rh" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 space-up" style="text-align: center;">
                                <button id="btn_reporte_infraccion" style="display: none;" type="submit" class=" btn btn-outline-guardar btn-block"><i class="fas fa-save" style="margin-right: 10px;"></i>REGISTRAR INCIDENCIA</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <p style="color: #0078D7;">Recorridos de HSE</p>
                </div>
            </div>
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Registro de Recorrido</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" id="card_registro_recorrido" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form_registro_recorrido">
                        <div class="row">
                            <div class="col-md-3 space-up">
                                <label>Departamento:</label>
                                <select id="id_departamento_recorrido" name="id_departamento_recorrido" class="select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="limpiarError(this)">
                                    <option value="">Seleccionar Opción...</option>
                                    <?php foreach ($departamentos as $key) {  ?>
                                        <option value="<?= $key->id_depto; ?>"><?= $key->departament; ?></option>
                                    <?php } ?>
                                </select>
                                <div id="error_id_departamento_recorrido" class="text-danger"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Uso EPP:</label>
                                <input type="hidden" name="uso_epp" id="valor_campo_1">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_1" type="radio" value="1" onclick="valorRadioBtn(this,1)"><i class="fas fa-check"></i>
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_1" type="radio" value="0" onclick="valorRadioBtn(this,0)"><i class="fas fa-times"></i>
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_1"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Uso uniforme:</label>
                                <input type="hidden" name="uso_uniforme" id="valor_campo_2">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_2" type="radio" value="0.5" onclick="valorRadioBtn(this,1)"><i class="fas fa-check"></i>
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_2" type="radio" value="0" onclick="valorRadioBtn(this,0)"><i class="fas fa-times"></i>
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_2"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Uso de Celular:</label>
                                <input type="hidden" name="uso_celular" id="valor_campo_3">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_3" type="radio" value="0" onclick="valorRadioBtn(this,1)">SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_3" type="radio" value="0.5" onclick="valorRadioBtn(this,0)">NO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_3"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Uso de Bisutería:</label>
                                <input type="hidden" name="uso_bisuteria" id="valor_campo_4">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_4" type="radio" value="0" onclick="valorRadioBtn(this,1)">SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_4" type="radio" value="0.5" onclick="valorRadioBtn(this,0)">NO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_4"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Cabello Recogido:</label>
                                <input type="hidden" name="cabello_recogido" id="valor_campo_5">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_5" type="radio" value="0.5" onclick="valorRadioBtn(this,1)">SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_5" type="radio" value="0" onclick="valorRadioBtn(this,0)">NO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_5"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Orden y Limpieza:</label>
                                <input type="hidden" name="orden_limpieza" id="valor_campo_6">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_6" type="radio" value="1" onclick="valorRadioBtn(this,1)"><i class="fas fa-check"></i>
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_6" type="radio" value="0" onclick="valorRadioBtn(this,0)"><i class="fas fa-times"></i>
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_6"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Actos Inseguros:</label>
                                <input type="hidden" name="actos_inseguros" id="valor_campo_7">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_7" type="radio" value="0" onclick="valorRadioBtn(this,1)"> SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_7" type="radio" value="2" onclick="valorRadioBtn(this,0)"> NO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_7"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Condiciones Inseguras:</label>
                                <input type="hidden" name="condiciones_inseguras" id="valor_campo_8">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_8" type="radio" value="0" onclick="valorRadioBtn(this,1)"> SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_8" type="radio" value="2" onclick="valorRadioBtn(this,0)"> NO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_8"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Trabajos de Mantenimiento:</label>
                                <input type="hidden" name="trabajos_mantenimiento" id="valor_campo_9">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_9" type="radio" value="0" onclick="valorRadioBtn(this,1)"> SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_9" type="radio" value="0" onclick="valorRadioBtn(this,0)"> NO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_9"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Manejo de Residuos:</label>
                                <input type="hidden" name="manejo_residuos" id="valor_campo_10">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_10" type="radio" value="0.5" onclick="valorRadioBtn(this,1)"><i class="fas fa-check"></i>
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_10" type="radio" value="0" onclick="valorRadioBtn(this,0)"><i class="fas fa-times"></i>
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_10"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Trabajos Peligrosos:</label>
                                <input type="hidden" name="trabajos_peligrosos" id="valor_campo_11">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_11" type="radio" value="0" onclick="valorRadioBtn(this,1)"> SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_11" type="radio" value="0" onclick="valorRadioBtn(this,0)"> NO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_11"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Permiso de Trabajo:</label>
                                <input type="hidden" name="permiso_trabajo" id="valor_campo_12">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form-3 btn-opcion">
                                        <input id="campo_12" type="radio" value="1" onclick="valorRadioBtn(this,1)"> SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form-3 btn-opcion">
                                        <input id="campo_12" type="radio" value="0" onclick="valorRadioBtn(this,0)"> NO
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form-3 btn-opcion">
                                        <input id="campo_12" type="radio" value="1" onclick="valorRadioBtn(this,2)"> N/A
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_12"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>Personal de Ajeno a INVAL:</label>
                                <input type="hidden" name="personal_ajeno_inval" id="valor_campo_13">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_13" type="radio" value="0" onclick="valorRadioBtn(this,1)"> SI
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form btn-opcion">
                                        <input id="campo_13" type="radio" value="0" onclick="valorRadioBtn(this,0)"> NO
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_13"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <label>EPP:</label>
                                <input type="hidden" name="epp_ajeno_inval" id="valor_campo_14">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form-3 btn-opcion">
                                        <input id="campo_14" type="radio" value="0.5" onclick="valorRadioBtn(this,0)"><i class="fas fa-check"></i>
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form-3 btn-opcion">
                                        <input id="campo_14" type="radio" value="0" onclick="valorRadioBtn(this,1)"><i class="fas fa-times"></i>
                                    </label>
                                    <label style="font-size: 16px;" class="btn btn-outline-secondary btn-option-form-3 btn-opcion">
                                        <input id="campo_14" type="radio" value="0.5" onclick="valorRadioBtn(this,2)"><i class="fas fa-minus"></i>
                                    </label>
                                </div>
                                <div class="text-danger" id="error_campo_14"></div>
                            </div>

                            <div class="col-md-3 space-up">
                                <label>Observaciones:</label>
                                <textarea name="observacion" id="observacion" cols="10" rows="3" class="form-control" onchange="limpiarError(this)"></textarea>
                                <div id="error_observacion" class="text-danger"></div>
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <button type="button" id="btn_foto_recorrido_1" class="btn_tomar_foto" onclick="document.getElementById('foto_recorrido_1').click();"><i class="fas fa-camera" style="margin-right: 10px;"></i>TOMAR FOTO
                                </button>
                            </div>
                            <div class="col-md-3 space-up">
                                <img id="previa_foto_recorrido_1" onclick="retirarFoto(this)" class="imagePreview" alt="Imagen previa" style="display:none; max-width: 91%; margin-top: 10px;margin-left: 5%;">
                                <input type="file" accept="image/*" class="foto" capture="camera" id="foto_recorrido_1" name="foto_recorrido_1" onchange="mostrarImagenPrevia1(this);">
                            </div>
                            <div class="col-md-3 space-up" style="text-align: center;">
                                <button type="button" id="btn_foto_recorrido_2" class="btn_tomar_foto" onclick="document.getElementById('foto_recorrido_2').click();"><i class="fas fa-camera" style="margin-right: 10px;"></i>TOMAR FOTO
                                </button>
                            </div>
                            <div class="col-md-3 space-up">
                                <img id="previa_foto_recorrido_2" onclick="retirarFoto(this)" class="imagePreview" alt="Imagen previa" style="display:none; max-width: 91%; margin-top: 10px;margin-left: 5%;">
                                <input type="file" accept="image/*" class="foto" capture="camera" id="foto_recorrido_2" name="foto_recorrido_2" onchange="mostrarImagenPrevia1(this);">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 space-up" style="text-align: center;">
                                <button id="btn_registro_recorrido" type="submit" class=" btn btn-outline-guardar btn-block"><i class="fas fa-save" style="margin-right: 10px;"></i>REGISTRAR RECORRIDO</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <p style="color: #0078D7;">Recorridos de HSE</p>
                </div>
            </div>
        </div>
    </section>
</div>
<style>
</style>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/toursHSE/safety_tours_form_v4.js"></script>
<?= $this->endSection() ?>