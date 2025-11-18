<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Todos los Permisos
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">
<style>
  .badge-cancel {
    color: #fff;
    background-color: #f76a77;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Todos los Permisos</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Autorizar Permisos</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <!-- PERMISOS collapsed-card-->
      <div class="card card-default ">
        <div class="card-header">
          <h3 class="card-title">Permisos</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!--  <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
          <table id="tabla_autorizar_todos_permisos" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="permisos_info" style="width:100%" ref="">
          </table>
        </div>

        <div class="card-footer">
          <a href="#">Permisos</a>
        </div>
      </div>
      <!-- VACACIONES collapsed-card-->
      <div class="card card-default ">
        <div class="card-header">
          <h3 class="card-title">Vacaciones</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
          <table id="tabla_autorizar_todos_vacaciones" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
          </table>
        </div>
        <div class="card-footer">
          <a href="#">Vacaciones</a>
        </div>
      </div>

      <!-- PAGO TIEMPO -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Pagos de Tiempo</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="tbl_pago_tiempo" class="table table-bordered table-striped dataTable display" cellspacing="0" role="grid" aria-describedby="usuarios_info" style="width:100%" ref="">
          </table>
        </div>
        <div class="card-footer">
          <a href="#">Pago de Tiempo</a>
        </div>
      </div>

    </div>
  </section>

  <section>
    <div class="modal fade" id="permisosModal" tabindex="-1" role="dialog" aria-labelledby="permisosModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl autoriza-permisos">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Autorizar Permisos<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="autorizar_permisos" method="post">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="tipo_personal">Folio</label>
                  <input type="text" class="form-control" id="folio" name="folio" value="" readonly>
                </div>
                <div class="form-group col-md-8">
                  <div class="form-group col-md-6">
                    <label for="puesto_solicitado">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" readonly>
                  </div>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_del">Salida</label>
                  <input type="text" class="form-control" id="permiso_salida" name="permiso_salida" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="permiso_al">Entrada</label>
                  <input type="text" class="form-control" id="permiso_entrada" name="permiso_entrada" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="permiso_inasistencia">Inasistencia</label>
                  <input type="text" class="form-control" id="permiso_inasistencia" name="permiso_inacistencia" value="" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="observaciones">Observaciones</label>
                  <textarea name="observaciones" id="observaciones" cols="10" rows="2" class="form-control" readonly></textarea>
                </div>
                <div class="form-group col-md-4">
                  <label for="autorizacion">Autorizar</label>
                  <select name="autorizacion" id="autorizacion" class="form-control" required>
                    <option value="">Seleccionar Opción..</option>
                    <option value="Autorizada">Autorizada</option>
                    <option value="Rechazada">Rechazada</option>
                    <option value="Cancelada">Cancelada</option>
                  </select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="autoriza_permiso" name="actualiza_requisicion" class="btn btn-guardar">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="permisosEditarModal" tabindex="-1" role="dialog" aria-labelledby="permisosEditarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Permisos<label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editar_permisos" method="post">
            <div class="modal-body">
              <div id="resultado"></div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="tipo_personal">Folio</label>
                  <input type="text" class="form-control" id="editar_folio" name="editar_folio" value="" readonly>
                </div>
                <div class="form-group col-md-8">
                  <div class="form-group col-md-6">
                    <label for="editar_usuario">Usuario</label>
                    <input type="text" class="form-control" id="editar_usuario" readonly>
                  </div>
                </div>
                <div class="form-group col-md-3">
                  <label for="editar_permiso_salida">Fecha de Salida</label>
                  <input type="date" class="form-control" id="editar_permiso_salida" name="editar_permiso_salida" value="">
                </div>
                <div class="form-group col-md-3">
                  <label for="editar_permiso_salida">Hora de Salida</label>
                  <input type="time" class="form-control" id="editar_permiso_salida_h" name="editar_permiso_salida_h" value="">
                </div>
                <div class="form-group col-md-3">
                  <label for="editar_permiso_entrada">Fecha de Entrada</label>
                  <input type="date" class="form-control" id="editar_permiso_entrada" name="editar_permiso_entrada" value="">
                </div>
                <div class="form-group col-md-3">
                  <label for="editar_permiso_entrada">Hora de Entrada</label>
                  <input type="time" class="form-control" id="editar_permiso_entrada_h" name="editar_permiso_entrada_h" value="">
                </div>
                <div class="form-group col-md-3">
                  <label for="editar_inasistencia_del">Inasistencia del</label>
                  <input type="date" class="form-control" id="editar_inasistencia_del" name="editar_inasistencia_del" value="">
                </div>
                <div class="form-group col-md-3">
                  <label for="editar_inasistencia_al">Inasistencia al</label>
                  <input type="date" class="form-control" id="editar_inasistencia_al" name="editar_inasistencia_al" value="">
                </div>
                <div class="form-group col-md-12">
                  <label for="observaciones">Observaciones</label>
                  <textarea id="editar_observaciones" cols="10" rows="2" class="form-control" readonly></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
              <button type="submit" id="editar_permiso" name="editar_permiso" class="btn btn-guardar">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="editarVacacionesModal" tabindex="-1" role="dialog" aria-labelledby="editarVacacionesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl ">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Vacaciones <label id="articulo"></label></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editar_vacaciones" method="post">
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="editar_folio_vcns">Folio</label>
                  <input type="number" class="form-control" id="editar_folio_vcns" name="id_folio" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="editar_usuario_vcns">Usuario</label>
                  <input type="text" class="form-control" id="editar_usuario_vcns" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="editar_vacaciones_del">Vacaciones del:</label>
                  <input type="date" id="editar_vacaciones_del" name="editar_vacaciones_del" class="form-control" value="" required />
                </div>
                <div class="form-group col-md-6">
                  <label for="editar_vacaciones_al">Vacaciones al:</label>
                  <input type="date" id="editar_vacaciones_al" name="editar_vacaciones_al" class="form-control" value="" required />
                </div>
                <div class="form-group col-md-6">
                  <label for="editar_regresando">Regresando:</label>
                  <input type="date" id="editar_regresando" name="editar_regresando" class="form-control" value="" required />
                </div>
                <div class="form-group col-md-6">
                  <label for="editar_regresando">Cantidad de Días a Disfrutar:</label>
                  <input type="number" min="1" id="editar_catidad" name="editar_cantidad" class="form-control" value="" required />
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
              <button id="actualiza_vacaciones" type="submit" class="btn btn-guardar">Actualizar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="vacacionesModal" tabindex="-1" role="dialog" aria-labelledby="vacacionesModalLabel" style="overflow-y: scroll;" aria-hidden="true">
    <div class="modal-dialog modal-xl vacaciones">
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
            <input type="text" id="num_nomina" name="num_nomina">
            <div class="form-row">
              <div class="form-group col-md-3">
                <label for="folio_vacaciones">Folio</label>
                <input type="text" class="form-control" id="folio_vacaciones" name="folio_vacaciones" value="" readonly>
              </div>
              <div class="form-group col-md-6">
                <label for="usuario_vacaciones">Usuario</label>
                <input type="text" class="form-control" id="usuario_vacaciones" name="usuario_vacaciones" readonly>
              </div>
              <div class="col-md-3" id="div_btn" style="text-align: center;"></div>
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
              <div class="form-group col-md-4" id="div_modal_a_cargo">
                <label for="modal_a_cargo">Dejando responsabilidades a cargo de:</label>
                <input type="text" class="form-control" id="modal_a_cargo" readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="salario_final">Autorizar</label>
                <select name="autorizacion_vacaciones" id="autorizacion_vacaciones" class="form-control" required>
                  <option value="">Seleccionar Opción..</option>
                  <option value="Autorizada">Autorizada</option>
                  <option value="Rechazada">Rechazada</option>
                  <option value="Cancelada">Cancelada</option>
                </select>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" id="autoriza_vacaciones" name="actualiza_requisicion" class="btn btn-guardar">Guardar</button>
        </div>
        </form>
      </div>
    </div>
  </div>

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

  <div class="modal fade" id="datosPagoTiempoModal" tabindex="-1" aria-labelledby="datosPagoTiempoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-edit" style="margin-right: 10px;"></i>Editar Pago de Tiempo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_editar_pago_tiempo">
          <div class="modal-body" id="div_pago_tiempo">
            <div class="form-row">
              <div class="form-group col-md-3">
                <input type="hidden" name="id_item" id="id_item">
                <input type="hidden" name="L-V_entrada" id="L-V_entrada"><input type="hidden" name="L-V_salida" id="L-V_salida">
                <input type="hidden" name="S_entrada" id="S_entrada"><input type="hidden" name="S_salida" id="S_salida">
                <label>En el Turno:</label>
                <Select id="turno" name="turno" class="form-control" onchange="turnos(this),turnoCompleto()">
                  <!-- <option value="">Selecciona....</option> -->
                </Select>
                <div id="div_horario"></div>
                <div id="error_turno" class="text-danger"></div>
              </div>
              <div class="col-md-2">
                <label for="tipo_permiso">Tipo de pago:</label>
                <select name="tipo_permiso" id="tipo_permiso" class="form-control" onchange="limpiarError(this),turnoCompleto()">
                  <option value="">Opciones....</option>
                  <option value="1" id="tipo_permiso_opc" style="display: none;">Llegar Antes</option>
                  <option value="2">Quedarse Despues</option>
                  <option value="3">Turno Completo</option>
                </select>
                <div id="error_tipo_permiso" class="text-danger"></div>
              </div>
              <div class="form-group col-md-3">
                <label for="dia_salida">Día Pago de tiempo:</label>
                <input type="date" class="form-control" id="dia_salida" name="dia_salida" onchange="limpiarError(this),turnoCompleto()">
                <div id="error_dia_salida" class="text-danger"></div>
              </div>
              <div class="col-md-3">
                <label>Cantidad de Horas:</label>
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="number" class="form-control" id="input_horas" name="input_horas" value="0" min="0" max="9" onchange="limpiarError(this)">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Hrs</span>
                      </div>
                    </div>
                    <div id="error_input_horas" class="text-danger"></div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="number" class="form-control" id="input_minutos" name="input_minutos" value="0" min="0" max="59" onchange="limpiarError(this)">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Min</span>
                      </div>
                    </div>
                    <div id="error_input_minutos" class="text-danger"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-guardar" id="btn_editar_pago_tiempo">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <section>
    <!-- Modal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verPermisosModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Documento PDF Permisos</h5>
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
    <div class="modal fade" id="pdfVacacionesModal" tabindex="-1" role="dialog" aria-labelledby="verVacacionesModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verVacacionesModal">Documento PDF Vacaciones</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <iframe id="carga_pdf_vacaciones" src="" width="100%" height="700px"></iframe>
          </div>
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
<script src="<?= base_url() ?>/public/dist/js/pages/jquery.velocity.js"></script>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/permissions/permissions_authorize_all_v9-6.js"></script>
<?= $this->endSection() ?>