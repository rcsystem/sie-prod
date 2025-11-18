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
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seguimiento de Incidencias | Actividades Inseguras</h1>
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
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Registro de Actividades Inseguras</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <table id="tbl_incidencias" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <p style="color: #0078D7;">Recorridos de HSE</p>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="modal fade" id="actualizaModal" tabindex="-1" aria-labelledby="actualizaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Seguimiento de Incidencia:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form_confirm_solucion" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="id_incidencia" name="id_incidencia">
                            <div class="form-row">
                                <div class="col-md-3" style="text-align: center;">
                                    <label>¿Se soluciono?</label>
                                    <input type="hidden" name="respuesta_opc" id="respuesta_opc">
                                    <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                        <label style="font-size: 16px;" class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" onclick="cambioValorRadioBtn(1)"> SI
                                        </label>
                                        <label style="font-size: 16px;" class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" onclick="cambioValorRadioBtn(2)"> NO
                                        </label>
                                    </div>
                                    <div class="text-danger" id="error_respuesta_opc"></div>
                                </div>
                                <div class="col-md-3 space-up">
                                    <label>Descripción breve :</label>
                                    <textarea name="descripcion" id="descripcion" cols="10" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="col-md-3 space-up" style="text-align: center;display: flex;align-items: center;">
                                    <button type="button" id="btn_foto_incidencia" class="btn_tomar_foto" onclick="document.getElementById('foto_incidencia').click();"><i class="fas fa-camera" style="margin-right: 10px;"></i>TOMAR FOTO
                                    </button>
                                </div>
                                <div class="col-md-3 space-up">
                                    <img id="previa_foto_incidencia" class="imagePreview" alt="Imagen previa" style="display:none; max-width: 91%; margin-top: 10px;margin-left: 5%;">
                                    <input type="file" accept="image/*" class="foto" capture="camera" id="foto_incidencia" name="foto_incidencia_respuesta" onchange="mostrarImagenPrevia(this);">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="btn_confirm_solucion" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/js/toursHSE/view_follow_activitys_tbl_v.js"></script>
<?= $this->endSection() ?>