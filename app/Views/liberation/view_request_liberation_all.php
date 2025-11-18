<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Todas las Solicitudes
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/font-awesome.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<style>
  .badge-adeudo { background:#ffc107; color:#000; } /* amarillo */
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Solicitudes de Liberación </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Liberación</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Subir Solicitudes de Liberación</h3>
          <div class="card-tools">
            <button type="button" id="btn_dowload_format" class="btn btn-tool btn-outline-dark" onclick="download()">Descargar Formato<i class="fas fa-file-download" style="margin-left: 10px;"></i></button>
          </div>
        </div>
        <div class="card-body">
          <form id="form_solicitudes_liberation" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="motivo_visita">Cargar Solicitudes de Liberación Masivo Excel</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="archivo" name="archivo" lang="es" onchange="validarFile(this)">
                  <label class="custom-file-label" for="customFileLang" id="lbl_archivo">Seleccionar Excel</label>
                </div>
                <div id="error_archivo" class="text-danger"></div>
              </div>
              <div class="form-group col-md-3" style="padding-top: 1.5rem;">
                <button id="btn_solicitudes_liberation" type="submit" class="btn btn-outline-guardar btn-lg"><i class="fas fa-file-upload" style="margin-right: 10px;"></i> Subir Datos</button>
              </div>
            </div>
          </form>
          <!-- <div class="col-md-12 row"> -->
              <!-- <div class="form-group col-md-3" style="padding-top: 1.5rem;">
                <button id="btn_actualizar_cuentas" type="submit" class="btn btn-outline-guardar btn-lg"><i class="fas fa-redo-alt" style="margin-right: 10px;"></i>Actualizar Datos</button>
              </div> -->
              <!-- <div id="result_cuentas" class="col-md-9" style="padding-top: 1.5rem;"></div>
          </div> -->
          <!-- <hr>
          <table id="tabla_estado_cuenta" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
          </table> -->
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Solicitudes de Liberación.</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <div class="col-md-12 text-right">
                <a class="btn btn-outline-primary" onclick="abrirSolicitudModal()">
                    Agregar una Solicitud
                </a>
            </div>
            <table id="tabla_liberation" class="table table-bordered table-striped " role="grid" aria-describedby="todos_viajes" style="width:100%" ref="">
            </table>
          </div>
        </div>
      </div>
  </section>

  <!-- Main content -->
  <section>
    <div class="modal fade" id="modalCreateRequestLiberation" tabindex="-1" aria-labelledby="modalCreateRequestLiberationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nueva solicitud</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCreateRequestLiberation">
                  <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="empresa_id">Empresa</label>
                        <select class="form-control" id="empresa_id" name="empresa_id" required>
                          <option value="">Seleccione una empresa</option>
                          <!-- Opciones cargadas dinámicamente -->
                        </select>
                      </div>
                      <br>
                      <input type="hidden" id="id_user_name" name="id_user_name" >
                       <div class="form-group col-md-4">
                        <label for="payroll_number">No. Nomina</label>
                         <input type="text" class="form-control" name="modal_payroll_number" id="modal_payroll_number" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="user_name">Nombre</label>
                        <input type="text" class="form-control" name="modal_user_name" id="modal_user_name" list="lista-usuarios" required>
                      <datalist id="lista-usuarios"></datalist>
                      </div>

                       <div class="form-group col-md-6">
                        <label for="department">Departamento</label>
                        <input type="text" class="form-control" name="modal_department" id="modal_department" readonly>
                      </div>

                      <div class="form-group col-md-6">
                        <label class="floating-label" for="tipo_nomina">Tipo de nómina:</label>
                        <select name="tipo_nomina" id="tipo_nomina" class="form-control" required>
                          <option value="" disabled selected>Seleccione tipo de nómina</option>
                          <option value="Semanal">Semanal</option>
                          <option value="Quincenal">Quincenal</option>
                        </select>
                      </div>


                      <div class="form-group col-md-6">
                          <label class="floating-label" for="periodo">Período:</label>
                          <select name="periodo" id="periodo" class="form-control" required>
                            <option value="" disabled selected>Seleccione periodo</option>
                          </select>
                      </div>

                                           
                      <div class="form-group col-md-6" style="display:none;">
                        <label for="department_id">Departamento ID</label>
                        <input type="text" class="form-control" name="department_id" id="department_id" readonly>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="direct_manager">Jefe Inmediato</label>
                        <input type="text" class="form-control" name="modal_direct_manager" id="modal_direct_manager" readonly>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="tel">Num Tel.</label>
                        <input type="text" class="form-control" name="tel" id="tel">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="equip_asigned">Equipo asignado</label>
                        <input type="text" class="form-control" name="modal_equip_asigned" id="modal_equip_asigned" readonly>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="date">Fecha de solicitud</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="notification">Mandar notificaciones</label>
                        <input type="checkbox" name="notification" id="notification">
                      </div>
                     <!--  <div class="form-group col-md-12">
                          <label for="equip_info">Equipos asignados</label>   
                          <div id="equip_info" class="border p-2" style="max-height:150px; overflow-y:auto;">
                              <small>No hay equipos asignados</small> 
                          </div>
                      </div> -->
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" form="formCreateRequestLiberation" class="btn btn-guardar">Guardar</button>
            </div>
            </div>
        </div>
    </div>
  </section>
  <!-- End Main content -->

  <!-- PDF MODAL -->
  <section>
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verPermisosModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Documento PDF</h5>
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
  <!-- END PDF MODAL -->
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/dist/js/pages/jquery.velocity.js"></script>
<script src="<?= base_url() ?>/public/js/liberation/request_liberation_all.js"></script>
<?= $this->endSection() ?>