<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Inventario de Equipos
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Inventario de Equipos</h1>
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
            <h3 class="card-title">Alta de Equipos</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <form id="alta_articulo" method="post">
              <div class="col-md-12 form-row">
                <div class="form-group col-md-5">
                  <label for="tipo">Tipo</label>
                  <select name="tipo" id="tipo" class="form-control" onchange="tipoEquipo()">
                    <option value="">Seleccionar Opción...</option>
                    <?php foreach ($tipos as $value) { ?>
                      <option value="<?= $value->id; ?>"><?= $value->type_product; ?></option>
                    <?php } ?>
                  </select>
                  <div id="error_tipo" class="text-danger"></div>
                </div>
              </div>


              <div id="campos" class="form-row"></div>
              <div class="footer">
                <button type="submit" id="btn_alta_articulo" name="btn_alta_articulo" class="btn btn-guardar btn-lg">Guardar</button>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <a href="#">Registro</a>
          </div>
        </div>

        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Inventario de Equipos</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body col-md-12">
            <div class="container-fluid">
              <table id="tbl_equipos" class="table table-bordered table-striped " role="grid" aria-describedby="equipos_info" style="width:100%" ref="">
              </table>
            </div>
          </div>
          <div class="card-footer">
            <a href="#">Inventario</a>
          </div>
        </div>
    </section>

    <section>
      <div class="modal fade" id="actualizaModal" tabindex="-1" aria-labelledby="actualizaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Actualizar Articulo: <label id="articulo"></label></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="form_edit_article" method="post">
                <div id="campos" class="form-row">
                  <div class="form-group col-md-2">
                    <label for="folio_">Etiqueta</label>
                    <input type="hidden" id="folio_" name="folio_">
                    <input type="text" class="form-control" id="etiqueta_" readonly>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="fecha_">Fecha de Alta</label>
                    <input type="text" class="form-control" id="fecha_" readonly>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="marca_">Marca</label>
                    <input type="text" class="form-control" onchange="validarEdit(this)" id="marca_" name="marca_">
                    <div id="error_marca_" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="modelo_">Modelo</label>
                    <input type="text" class="form-control" onchange="validarEdit(this)" id="modelo_" name="modelo_">
                    <div id="error_modelo_" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="costo_equipo_">Costo Aproximado</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input type="text" class="form-control" onchange="validarEdit(this)" id="costo_equipo_" name="costo_equipo_">
                    </div>
                    <div id="error_costo_equipo_" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="no_serie_">No. Serie / IMEI</label>
                    <input type="text" class="form-control" onchange="validarEdit(this)" id="no_serie_" name="no_serie_">
                    <div id="error_no_serie_" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="tipo_">Tipo de Equipo</label>
                    <select name="tipo_" id="tipo_" class="form-control" onchange="validarEdit(this)">
                      <?php foreach ($tipos as $value) { ?>
                        <option value="<?= $value->id; ?>"><?= $value->type_product; ?></option>
                      <?php } ?>
                    </select>
                    <div id="error_tipo_" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="procesador_">Procesador</label>
                    <input type="text" class="form-control" onchange="validarEdit(this)" id="procesador_" name="procesador_">
                    <div id="error_procesador_" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-1">
                    <label for="memoria_">Memoria</label>
                    <select id="memoria_" name="memoria_" class="form-control " onchange="validarEdit(this)">
                      <option value="">...</option>
                      <option value="2GB">2GB</option>
                      <option value="4GB">4GB</option>
                      <option value="8GB">8GB</option>
                      <option value="12GB">12GB</option>
                      <option value="16GB">16GB</option>
                      <option value="32GB">32GB</option>
                    </select>
                    <div id="error_memoria_" class="text-danger"></div>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="disco_duro_">Disco Duro</label>
                    <input type="hidden" name="disco_duro_" id="disco_duro_">
                    <div class="input-group">
                      <input type="number" min="1" id="disco_duro_txt_" class="form-control" onchange="hardDisc(2)">
                      <div class="input-group-prepend">
                        <select id="disco_duro_extent_" class="form-control" onchange="hardDisc(2)">
                          <option value="GB">GB</option>
                          <option value="TB">TB</option>
                        </select>
                      </div>
                      <div class="input-group-prepend">
                        <select id="disco_duro_type_" class="form-control" onchange="hardDisc(2)">
                          <option value="SSD">SSD</option>
                          <option value="HDD">HDD</option>
                        </select>
                        <div id="error_disco_duro_" class="text-danger"></div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="dmf_">Fecha Manofactura(año)</label>
                    <input type="text" class="form-control" id="dmf_" name="dmf_" onchange="validarEdit(this)" placeholder="2017">
                    <div id="error_dmf_" class="text-danger"></div>
                  </div>

                  <!-- <div class="form-group col-md-2">
                    <label for="estado_">Estado</label>
                    <select id="estado_" name="estado_" class="form-control" onchange="validarEdit(this)">
                      <option value="1">ALMACENADO</option>
                      <option value="2">ASIGNADO</option>
                      <option value="3">REFACCION</option>
                      <option value="4">OBSOLETO</option>
                    </select>
                    <div id="error_estado_" class="text-danger"></div>
                  </div> -->
                  <div class="form-group col-md-12">
                    <label for="caracteristicas_">Observación Extra</label>
                    <textarea type="text" class="form-control" onchange="validarEdit(this)" id="caracteristicas_" name="caracteristicas_"></textarea>
                    <div id="error_caracteristicas_" class="text-danger"></div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button id="btn_edit_article" class="btn btn-primary">Actualizar</button>
            </div>
            </form>
          </div>
        </div>
    </section>

    <section>
      <div class="modal fade" id="historialModal" tabindex="-1" aria-labelledby="historialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Historial Articulo: <label id="articulo"></label></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div id="campos" class="form-row">
                <div class="form-group col-md-4">
                  <label for="etiqueta_h">Etiqueta</label>
                  <input type="text" class="form-control" id="etiqueta_h" name="etiqueta_h" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="no_serie_h">NO. SERIE / IMEI</label>
                  <input type="text" class="form-control" id="no_serie_h" name="no_serie_h" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="estado_h">Estado</label>
                  <input type="text" id="estado_h" name="estado_h" class="form-control" disabled>
                </div>
              </div>
              <hr>
              <div class="form-group col-md-12">
                <div id="tbl_h" class="container-fluid"></div>
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
  <script src="<?= base_url() ?>/public/js/system/equipment_v1.js"></script>
  <?= $this->endSection() ?>