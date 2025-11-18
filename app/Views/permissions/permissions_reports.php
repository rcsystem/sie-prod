<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>

Permisos

<?= $this->endSection() ?>



<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Entradas | Salidas | Vacaciones</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item">Reportes</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Reporte Global & Reporte Individual </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <form id="formReportesIndividual" method="post">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <input type="number" class="form-control rounded-0" id="numero_nomina" name="numero_nomina" value="" required>
                </div>
                <div class="form-group col-md-4">
                  <button id="reporte_individual" name="reporte_individual" type="submit" class="btn btn-guardar">Reporte Individual</button>
                </div>
              </div>
              <hr>
            </form>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <div class="form-row">
              <div class="form-group col-md-4">
                <form id="formReportesGlobal" method="post">
                  <button id="reporte_global" name="reporte_global" type="submit" class="btn btn-guardar btn-lg">Reporte Global</button>
                </form>
              </div>
              <div class="form-group col-md-4">
                <form id="formDatosGeneral" method="post">
                  <button id="reporte_datos_general" name="reporte_datos_general" type="submit" class="btn btn-guardar btn-lg">Reporte de Usuarios Datos Generales</button>
                </form>
              </div>
            </div>
            <hr>
            <form id="formReportesVacacionesTotal" method="post">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <button id="reporte_vacaciones" name="reporte_vacaciones" type="submit" class="btn btn-guardar btn-lg">Saldo de Vacaciones </button>
                </div>
              </div>
              <hr>
            </form>
          </div>
        </div>
        <div class="card-footer">
          <a href="#">Reportes</a>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Reporte por Dirección o Usuario</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <form id="form_reportes_por_direccion" method="post">             
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="categoria">Categoria</label>
                  <select name="categoria" id="categoria" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <option value="1">Dirección</option>
                    <option value="2">Usuario</option>
                  </select>
                </div>
                <div id="parametro" class=""></div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="cantidad">Fecha Inicio</label>
                  <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="minimo">Fecha Final</label>
                  <input type="date" class="form-control" id="fecha_final" name="fecha_final" onchange="limpiarError(this)">
                <div class="text-danger" id="error_fecha_final"></div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="tipo_reportes">Tipo de Reporte</label>
                  <select name="tipo_reportes" id="tipo_reportes" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <option value="1">Permisos</option>
                    <option value="2">Vacaciones</option>
                    <option value="3">Pago de Tiempo</option>
                  </select>
                </div>
                <div id="permisos_div" class=""></div>
              </div>
              <hr>
              <button id="btn_reportes_por_direccion" type="submit" class="btn btn-guardar btn-lg">Generar</button>
            </form>
          </div>
        </div>
        <div class="card-footer">
          <a href="#">Reportes</a>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title"> Ver Permisos y Vacaciones Anteriores </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <form id="form_data_table" method="post">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Ver:</label>
                  <select id="tipo_anterior" class="form-control" required>
                    <option value="">Opcion...</option>
                    <option value="1">Permisos</option>
                    <option value="2">Vacaciones</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label>Fecha Inicio:</label>
                  <input type="date" id="fecha_inicio_b" class="form-control" onchange="limpiarError(this)">
                  <div class="text-fdanger" id="error_fecha_inicio_b" ></div>
                </div>
                <div class="form-group col-md-4">
                  <label>Fecha Final:</label>
                  <input type="date" id="fecha_fin_b" class="form-control" onchange="limpiarError(this)">
                  <div class="text-fdanger" id="error_fecha_fin_b" ></div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label>Buscar por:</label>
                  <select id="tipo_busqueda" class="form-control" required>
                    <option value="1">Todos</option>
                    <option value="2">Nomina</option>
                    <option value="3">Departamento</option>
                  </select>
                </div>
                <div id="opcion_div" class="form-group col-md-4"></div>
                <div class="form-group col-md-4" style="text-align:right;margin-top: 2rem;">
                  <button id="btm_data_table" type="submit" class="btn btn-guardar">BUSCAR DATOS</button>
                </div>
              </div>
            </form>
            <hr>
            <div id="table_div"></div>
          </div>
        </div>
        <div class="card-footer">
          <a href="#">Reportes</a>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="fechasVacacionesModal" tabindex="-1" aria-labelledby="fechasVacacionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Fechas de Vacaciones</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="div_dias">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Generar Reporte</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="container">
            <div id="reporte_fechas" class=" col-md-12 ">
              <form id="formReporte" action="POST">
                <div class="d-flex align-items-center flex-column justify-content-center h-100">
                  <div class="form-group col-md-6">
                    <label for="tipo_reporte">Seleccionar Reporte</label>
                    <select class="form-control" id="tipo_reporte" >
                      <option value="">Seleccionar</option>
                      <option value="1">Salidas y Entradas</option>
                      <option value="3">Pago de Tiempo</option>
                      <option value="2">Vacaciones</option>
                    </select>
                    <div id="error_tipo_reporte" class="text-danger"></div>
                  </div>
                  <div id="cat_permiso_div"></div>
                  <div class="form-group col-md-6">
                    <label for="fecha_ini">Fecha Inicial</label>
                    <input type="date" id="fecha_ini" class="form-control" onchange="limpiarError(this)">
                    <div id="error_fecha_ini" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="fecha_fin">Fecha Final</label>
                    <input type="date" id="fecha_fin" class="form-control" onchange="limpiarError(this)">
                    <div id="error_fecha_fin" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-6 my-4">
                    <button type="submit" id="generarReporte" class="btn btn-guardar btn-block"> <b style="font-size:22px"> Generar </b> </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <a href="#">Solicitudes</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="vacacionesModal" tabindex="-1" aria-labelledby="vacacionesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Vacaciones<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_vacaciones" method="post">
              <input type="hidden" id="dias" name="dias">
              <input type="hidden" id="num_nomina" name="num_nomina">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="folio_vacaciones">Folio</label>
                  <input type="text" class="form-control" id="folio_vacaciones" name="folio_vacaciones" value="" readonly>
                </div>
                <div class="form-group col-md-8">
                  <div class="form-group col-md-6">
                    <label for="usuario_vacaciones">Usuario</label>
                    <input type="text" class="form-control" id="usuario_vacaciones" name="usuario_vacaciones" readonly>
                  </div>
                </div>
                <div class="form-group col-md-4">
                  <label for="del">Del</label>
                  <input type="date" class="form-control" id="del_vacaciones" name="del_vacaciones" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="al">Al</label>
                  <input type="date" class="form-control" id="al_vacaciones" name="al_vacaciones" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="salario_inicial">Regresa</label>
                  <input type="date" class="form-control" id="regresa" name="regresa" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="salario_final">Autorizar</label>
                  <select name="autorizacion_vacaciones" id="autorizacion_vacaciones" class="form-control" required>
                    <option value="">Seleccionar Opción..</option>
                    <option value="Autorizada">Autorizada</option>
                    <option value="Rechazada">Rechazada</option>
                  </select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="autoriza_vacaciones" name="actualiza_requisicion" class="btn btn-guardar">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="modal fade" id="editarVacacionesNewModal" tabindex="-1" role="dialog" aria-labelledby="editarVacacionesNewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Vacaciones <label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editar_vacaciones_new" method="post">
            <div class="modal-body">
              <div class="form-row">
                <input type="hidden" name="id_items_new" id="id_items_new">
                <input type="hidden" name="id_user_new" id="id_user_new">
                <input type="hidden" name="id_depto_new" id="id_depto_new">
                <input type="hidden" name="count_array" id="count_array">
                <div class="form-group col-md-1">
                  <label for="editar_vcns_new">Folio</label>
                  <input type="number" class="form-control" id="editar_vcns_new" name="editar_vcns_new" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="editar_usuario_vcns_new">Usuario</label>
                  <input type="text" class="form-control" id="editar_usuario_vcns_new" readonly>
                </div>
                <div class="form-group col-md-5">
                  <label for="editar_catidad_new">Cantidad de Días a Disfrutar:</label>
                  <input type="number" min="1" id="editar_catidad_new" class="form-control" value="" required />
                </div>
                <div class="form-group col-md-6">
                  <label for="vacaciones_dias_disfrutar">Dias a disfrutar</label>
                  <input type="date" id="vacaciones_dias_disfrutar" name="vacaciones_dias_disfrutar" class="form-control" style="background-color: #fff;" require>
                  <div id="error_vacaciones_dias_disfrutar" class="text-danger"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="regresar_activiades">Debiendo regresar a sus actividades:</label>
                  <input type="date" class="form-control " id="vacaciones_regresar_actividades" name="vacaciones_regresar_actividades" style="background-color: #fff;" require>
                  <div id="error_vacaciones_regresar_actividades" class="text-danger"></div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
              <button id="btn_editar_vacaciones_new" type="submit" class="btn btn-guardar">Actualizar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/flatpickr/flatpickr.js"></script>
<script src="<?= base_url() ?>/public/plugins/flatpickr/idioma/es.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/permissions/permissions_reports_v1-1.js"></script>

<?= $this->endSection() ?>