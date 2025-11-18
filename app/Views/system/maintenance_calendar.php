<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administracion de Equipos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />

<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .has-error-bg {
        background-color: #F10000;
    }

    .div-error-select2 {
        padding: 1px;
    }

    .fc-event {
        cursor: pointer;
    }

    .sie-size {
        font-size: 13px;
    }

    .table .thead-dark th {
        color: #fff;
        background-color: #20c997;
        border-color: #20c997;
    }
    /* Puedes poner esto en tu archivo CSS */
.btn-group-space > * {
  margin-right: 6px;
}
.btn-group-space > *:last-child {
  margin-right: 0;
}
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Calendario de Mantenimiento de Equipos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Sistemas</li>
                    </ol>
                </div>
            </div>
        </div>








    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="container">
                <button class="btn btn-primary my-3" onclick="Mantto()">Agregar Mantenimiento</button>
                <button class="btn btn-success my-3" onclick="listadoMantto()">Listado de Mantenimientos</button>
                <div id='calendar'></div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="manttoModal" tabindex="-1" aria-labelledby="manttoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="manttoModalLabel">Programar Mantenimiento</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="manttoForm" method="POST">
                                <input type="hidden" id="hidden_id_user" name="hidden_id_user">
                                <input type="hidden" id="hidden_equipo" name="hidden_equipo">
                                <input type="hidden" id="hidden_id_equip" name="hidden_id_equip">
                                <input type="hidden" id="hidden_usuario" name="hidden_usuario">
                                <input type="hidden" id="hidden_departamento" name="hidden_departamento" value="">
                                <div class="mb-3">
                                    <label for="fecha_mantto" class="form-label">Asignar Usuario</label>
                                    <!--  <select id="usuario_mantto" class="form-control" style="width: 100%" required></select> -->
                                    <input type="text" id="input_usuario_equipo" placeholder="Buscar por usuario o equipo" class="form-control">
                                    <div id="resultados_busqueda" class="list-group mt-2" style="position: absolute; z-index: 1000;"></div>

                                </div>

                                <div class="mb-3">
                                    <label for="nombre_tecnico" class="form-label">Nombre del Técnico</label>
                                    <select id="nombre_tecnico" class="form-control" name="nombre_tecnico" required>
                                        <option value="">Seleccione</option>
                                        <option value="Edna Carolina Noriega">Edna Carolina Noriega</option>
                                        <option value="Guillermo Garcia">Guillermo Garcia</option>
                                        <option value="Joaquín Lucio">Joaquín Lucio</option>
                                        <option value="Hugo Sanchez">Hugo Sanchez</option>
                                        <option value="Sergio Pacheco">Sergio Pacheco</option>

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tipo_mantto" class="form-label">Tipo de Mantto</label>
                                    <select id="tipo_mantto" class="form-control" name="tipo_mantto" required>
                                        <option value="">Seleccione</option>
                                        <option value="Preventivo">Preventivo</option>
                                        <option value="Correctivo">Correctivo</option>
                                        <option value="Correctivo que sustituye preventivo">Correctivo que sustituye preventivo</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fecha_mantto" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" id="fecha_mantto" name="fecha_mantto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Actividades a Realizar</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <!-- Modal -->
        <div id="modalDetalleMantto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detalleManttoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content shadow rounded">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="detalleManttoLabel">
                            <i class="fas fa-tools mr-2"></i> Detalle del Mantenimiento
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="container">
                            <div class="list-group">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-calendar-alt mr-2 text-danger"></i>Fecha:</strong>
                                    <span id="detalle_fecha" class="text-right"></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-tools mr-2 text-secondary"></i>Tipo Mantto:</strong>
                                    <span id="detalle_tipo" class="text-right"></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-user mr-2 text-success"></i>Tecnico:</strong>
                                    <span id="nombre_tecnicos" class="text-right"></span>
                                </div>

                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-desktop mr-2 text-primary"></i>Equipo:</strong>
                                    <span id="detalle_equipo" class="text-right"></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-laptop mr-2 text-secondary"></i>Modelo:</strong>
                                    <span id="detalle_modelo" class="text-right"></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-tag mr-2 text-info"></i>Marca:</strong>
                                    <span id="detalle_marca" class="text-right"></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-user mr-2 text-success"></i>Usuario:</strong>
                                    <span id="detalle_usuario" class="text-right"></span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-comment mr-2 text-warning"></i>Observaciones:</strong>
                                    <span id="detalle_observaciones" class="text-right"></span>
                                </div>
                            </div>
                            <hr>
                            <div id="infoCambio">
                                <div id="tituloCambio" class="form-group text-center">
                                    <h3>Cambiar Tipo de Mantenimiento</h3>
                                </div>
                                <hr>
                                <form id="cambiarManttoForm" method="POST">
                                    <input type="hidden" id="hidden_id_mantto" name="hidden_id_mantto" value="">

                                    <div class="form-group mt-3">
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label for="nueva_fecha">
                                                    <i class="fas fa-calendar-day mr-1"></i> Nueva fecha de mantenimiento:
                                                </label>
                                                <input type="date" class="form-control" id="nueva_fecha" name="nueva_fecha">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="nueva_fecha">
                                                    <i class="fas fa-calendar-day mr-1"></i> Tipo de Mantenimiento:
                                                </label>
                                                <select id="tipo_mantenimiento" class="form-control" name="tipo_mantenimiento" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="Preventivo">Preventivo</option>
                                                    <option value="Correctivo">Correctivo</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="nombres_tecnico" class="form-label">Nombre del Técnico</label>
                                                <select id="nombres_tecnico" class="form-control" name="nombres_tecnico" required>
                                                    <option value="">Seleccione</option>
                                                    <option value="Edna Carolina Noriega">Edna Carolina Noriega</option>
                                                    <option value="Guillermo Garcia">Guillermo Garcia</option>
                                                    <option value="Joaquín Lucio">Joaquín Lucio</option>
                                                    <option value="Hugo Sanchez">Hugo Sanchez</option>
                                                    <option value="Sergio Pacheco">Sergio Pacheco</option>

                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group mt-3">

                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <button id="btnGuardarFecha" class="btn btn-success btn-block">
                                                    <i class="fas fa-save mr-1"></i> Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div id="tituloCancelacion" class="col-md-12 form-group text-center">
                                    <h3>Cancelar Mantenimiento</h3>

                                </div>
                                <hr>
                                <div id="formCancelacion" class="form-group mt-3">
                                    <form id="cancelarManttoForm" method="POST">
                                        <div class="form-row">
                                            <div class="col-md-12 mb-3">
                                                <textarea name="observaciones_cancelar" id="observaciones_cancelar" class="form-control" rows="3" placeholder="Ingrese observaciones..."></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <button id="btnCancelarMantto" class="btn btn-danger btn-block">
                                                    <i class="fas fa-trash mr-1"></i> Cancelar Mantenimiento
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="infoCancelacion">

                                <div class="col-md-12 mb-3">
                                    <textarea name="observacion_cancelar" id="observacion_cancelar" class="form-control" rows="3" readonly></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section>
        <!-- Modal -->
        <div class="modal fade" id="listadoManttoModal" tabindex="-1" aria-labelledby="listadoManttoModal" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="listadoManttoModal">Programar Mantenimiento</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="tbl_mantto" class="table table-bordered table-striped"></table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <!-- Modal -->
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verManttoModal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verManttoModal">Documento PDF Mantto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="carga_pdf" src="" width="100%" height="700px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <section>
        <!-- Modal -->
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verManttoModal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verManttoModal">Documento PDF Mantto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="carga_pdf" src="" width="100%" height="700px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>
<script src="<?= base_url() ?>/public/js/system/calendario_mantto.js"></script>

<?= $this->endSection() ?>