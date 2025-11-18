<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Equipos de la solicitud
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/font-awesome.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Lista de equipos / Departamento</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Equipo para Liberación</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Subir Solicitudes de Liberación</h3>
          <div class="card-tools">
            <button type="button" id="btn_dowload_format" class="btn btn-tool btn-outline-dark" onclick="download()">Descargar Formato<i class="fas fa-file-download" style="margin-left: 10px;"></i></button>
          </div>
        </div>
        <div class="card-body">
          <form id="form_estado_cuenta" method="post" enctype="multipart/form-data">
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
                <button id="btn_estado_cuenta" type="submit" class="btn btn-outline-guardar btn-lg"><i class="fas fa-file-upload" style="margin-right: 10px;"></i> Subir Datos</button>
              </div>
            </div>
          </form>
          <div class="col-md-12 row">
          <div class="form-group col-md-3" style="padding-top: 1.5rem;">
                <button id="btn_actualizar_cuentas" type="submit" class="btn btn-outline-guardar btn-lg"><i class="fas fa-redo-alt" style="margin-right: 10px;"></i>Actualizar Datos</button>
              </div>
              <div id="result_cuentas" class="col-md-9" style="padding-top: 1.5rem;"></div>
              </div>
          <hr>
          <table id="tabla_estado_cuenta" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
          </table>
        </div>
      </div>
    </div>
  </section> -->

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Listado de items.</h3>
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
                    Agregar Item
                </a>
            </div>
            <!-- <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#modalCreateItem">
                <i class="fas fa-plus"></i> Agregar Item
            </button> -->
            <table id="tabla_items_liberation" class="table table-bordered table-striped " role="grid" aria-describedby="todos_los_items" style="width:100%" ref="">
            </table>
          </div>
        </div>
      </div>
  </section>

  <!-- Main content -->
  
  <!-- </section> -->
    <section>
        <div class="modal fade" id="modalCreateItem" tabindex="-1" aria-labelledby="modalCreateItemLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCreateItem">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                        <label for="name">Equipo</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="form-group col-md-4">
                        <label for="description">Descripción</label>
                        <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                        <div class="form-group col-md-4">
                        <label for="department_id">Departamento</label>
                        <select class="form-control" name="department_id" id="department_id" required>
                            <option value="">Seleccione una opción</option>
                            <option value="HSE">HSE</option>
                            <option value="Soporte técnico e infraestructura">Soporte técnico e infraestructura</option>
                            <option value="Contabilidad y Servicios generales">Contabilidad y Servicios generales</option>
                            <option value="Mercadotecnia">Mercadotecnia</option>
                            <option value="Metrología">Metrología</option>
                            <option value="Caja de Ahorros No Sindicalizados">Caja de Ahorros No Sindicalizados</option>
                            <option value="Caja de Ahorros Sindicalizados">Caja de Ahorros Sindicalizados</option>
                            <option value="Jefe de área">Jefe de área</option>
                            <option value="Gestión de talento">Gestión de talento</option>
                            <option value="Vigilancia">Vigilancia</option>
                            <option value="Servicio Médico">Servicio Médico</option>
                        </select>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" form="formCreateItem" class="btn btn-guardar">Guardar</button>
                </div>
                </div>
            </div>
        </div>
    </section>

  <!-- <section>
    <div class="modal fade" id="autorizarGastoModal" tabindex="-1" aria-labelledby="autorizarGastoModalLabel" style="overflow-y: scroll;" aria-hidden="true">
      <div class="modal-dialog modal-xl gastos">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Gasto<label id="folio_gasto"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_gasto" method="post">
              <input type="hidden" id="id_folio" name="id_folio" value="">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="usuario">Usuario</label>
                  <input type="text" class="form-control" id="usuario" name="usuario" value="" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="motivo">Motivo</label>
                  <input type="text" class="form-control" id="motivo" name="motivo" disabled>
                </div>

                <div class="form-group col-md-4">
                  <label for="inicio">Inicio</label>
                  <input type="text" class="form-control" id="inicio" name="inicio" disabled>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="termino">Termino</label>
                  <input type="text" class="form-control" id="termino" name="termino" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="presupuesto">Presupuesto</label>
                  <input type="text" class="form-control" id="presupuesto" name="presupuesto" value="" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="autorizacion">Autorización</label>
                  <select name="autorizacion" id="autorizacion" class="form-control">
                    <option value="">Seleccionar...</option>
                    <option value="2">Autorizar</option>
                    <option value="3">Cancelar</option>
                  </select>
                  <div id="error_autorizacion" class="text-danger"></div>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-7">
                  <table class="tab2" style="margin-top:30px;width:90%;">
                    <tr style="background:#8b9eaf;">
                      <td colspan="2" style="font-weight:bold;font-size:16px;text-align:center;">Tipos de Gastos</td>
                    </tr>
                    <tbody id="listado_gastos"></tbody>

                  </table>
                </div>
                <div id="cancelDiv" class="form-group col-md-5"></div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="btn_autorizar_gasto" name="btn_autorizar_gasto" class="btn btn-guardar">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section> -->

  <!-- <section>
    <div class="modal fade" id="autorizarViaticoModal" tabindex="-1" aria-labelledby="autorizarVaiticoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl viaticos">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Viáticos<label id="folio_viaticos"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_viaticos" method="post">
              <input type="hidden" id="id_folio_v" name="id_folio_v" value="">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="usuario">Usuario</label>
                  <input type="text" class="form-control" id="v_usuario" name="v_usuario" value="" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="motivo">Motivo</label>
                  <input type="text" class="form-control" id="v_motivo" name="v_motivo" disabled>
                </div>

                <div class="form-group col-md-4">
                  <label for="inicio">Inicio</label>
                  <input type="text" class="form-control" id="v_inicio" name="v_inicio" disabled>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="termino">Termino</label>
                  <input type="text" class="form-control" id="v_termino" name="v_termino" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="presupuesto">Presupuesto</label>
                  <input type="text" class="form-control" id="v_presupuesto" name="v_presupuesto" value="" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="autorizacion">Autorización</label>
                  <select name="v_autorizacion" id="v_autorizacion" class="form-control">
                    <option value="">Seleccionar...</option>
                    <option value="2">Autorizar</option>
                    <option value="3">Cancelar</option>
                  </select>
                  <div id="error_autorizacion_v" class="text-danger"></div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="btn_autorizar_viaticos" name="btn_autorizar_gasto" class="btn btn-guardar">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section> -->
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/dist/js/pages/jquery.velocity.js"></script>
<script src="<?= base_url() ?>/public/js/liberation/request_liberation_items_all.js"></script>
<?= $this->endSection() ?>