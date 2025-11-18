<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administracion de Equipos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<style>
    .has-error-bg {
        background-color: #F10000;
    }

    .div-error-select2 {
        padding: 1px;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Administracion de Equipos</h1>
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
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Asignación | Recolección | Renovación de Equipos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="procesos" method="post">
                        <div class="form-row" style="margin-bottom: 1rem;">
                            <div class="col-md-9">
                                <input type="hidden" name="tipo_proceso" id="tipo_proceso">
                                <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-success btn-opcion">
                                        <input type="radio" onclick="proceso(1)"> ASIGNACIÓN
                                    </label>
                                    <label class="btn btn-outline-primary btn-opcion">
                                        <input type="radio" onclick="proceso(2)"> RENOVACIÓN
                                    </label>
                                    <label class="btn btn-outline-info btn-opcion">
                                        <input type="radio" onclick="proceso(3)"> RECOLECCIÓN POR USUSARIO
                                    </label>
                                    <?PHP if (session()->id_user == 1063) { ?>
                                        <label class="btn btn-outline-info btn-opcion">
                                            <input type="radio" onclick="proceso(4)"> RECOLECCIÓN POR EQUIPO
                                        </label>
                                    <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="div_formulario" class="col-md-12"></div>
                            <div class="col-md-12" id="div_obs" style="display: none;">
                                <hr>
                                <label for="coment_asig">Observaciones</label>
                                <textarea name="coment_asig" id="coment_asig" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="footer" style="margin-top: 10px;">
                            <button type="submit" id="btn_procesos" class="btn btn-guardar btn-lg">Guardar</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="#">Generar</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Todas las Asignaciones</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <table id="tbl_equipos_asignados" class="table table-bordered table-striped " role="grid" aria-describedby="equipos_info" style="width:100%" ref="">

                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="#">Historial</a>
                </div>

            </div>
    </section>

    <section>
        <div class="modal fade" id="ResponsibaModal" tabindex="-1" aria-labelledby="ResponsibaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Responsiba de Equipo: <label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="responsiba" method="post">
                            <div id="campos" class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="equipo">IMEI / SERIE</label>
                                    <input type="text" class="form-control" id="equipo" name="equipo" readonly>
                                    <!-- <input type="hidden" class="form-control" id="id_user_" name="id_user_" readonly> -->
                                    <!-- <input type="hidden" class="form-control" id="folio_" name="folio_" readonly> -->
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="recibe_">RECIBE</label>
                                    <input type="text" class="form-control" id="recibe_" name="recibe_" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="entrega_">ENTREGA</label>
                                    <input type="text" class="form-control" id="entrega_" name="entrega_" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado_">Estado</label>
                                    <select id="estado_" name="estado_" class="form-control">
                                        <option value="0">Pendiente</option>
                                        <option value="1">Confirmado</option>
                                        <option value="2">Rechazado</option>
                                    </select>
                                    <div id="error_estado_" class="text-danger"></div>
                                </div>
                                <div id="firma_opc" class="form-group col-md-4"></div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button id="btn_responsiba" name="btn_responsiba" class="btn btn-primary">Guardar</button>
                    </div>
                    </form>
                </div>
            </div>
    </section>
    <section>
        <!-- Modal -->
<div class="modal fade" id="manttoModal" tabindex="-1" aria-labelledby="manttoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manttoModalLabel">Programar Mantenimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
      </div>
      <div class="modal-body">
        <form id="manttoForm">
            <input type="hidden" id="id_equipo_mantto" name="id_equipo_mantto">
          <div class="mb-3">
            <label for="fecha_mantto" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="fecha_mantto" name="fecha_mantto"/>
          </div>
          <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
          </div>
          <button id="btn_mantto" type="submit" class="btn btn-success">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/system/equipment_asignation_v2.js"></script>
<?= $this->endSection() ?>