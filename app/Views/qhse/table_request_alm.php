<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
HSE | Solicitudes Epp
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .btn-primary:not(:disabled):not(.disabled).active,
  .btn-primary:not(:disabled):not(.disabled):active,
  .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #1f2d3d;
    border-color: #1f2d3d;
  }

  .custom-file-label::after {
    content: "Subir";
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"> HSE | Solicitudes Epp</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Solicitudes</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <section class="content">
      <div class="container-fluid">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Reporte de entrega de EPP</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="container">
              <form id="epp_reporte" action="POST">
                <div class="row">
               
                  <div id="cat_permiso_div"></div>
                  <div class="col-md-3">
                    <label for="servicio_fecha_ini">Fecha Inicial</label>
                    <input type="date" id="servicio_fecha_ini" class="form-control" onchange="limpiarError(this)">
                    <div class="text-danger" id="error_servicio_fecha_ini"></div>
                  </div>
                  <div class="col-md-3">
                    <label for="servicio_fecha_fin">Fecha Final</label>
                    <input type="date" id="servicio_fecha_fin" class="form-control" onchange="limpiarError(this)">
                    <div class="text-danger" id="error_servicio_fecha_fin"></div>
                  </div>
                  <div class="col-md-2 my-4">
                    <button type="submit" id="btn_servicio_reporte" class="btn btn-guardar btn-block"> <b style="font-size:22px"> Generar </b> </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="card-footer">
            <a href="#">Servicios</a>
          </div>
        </div>
      </div>
    </section>


  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Epp</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>

        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_solicitudes_epp_alm" class="table table-bordered table-striped " role="grid" aria-describedby="invetario_epp" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Solicitudes</a>
        </div>
      </div>
    </div>
  </section>

  <section>
        <div class="modal fade" id="inventarioModal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inventario<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="parametros_epp" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="folio">Folio</label>
                                    <input type="text" class="form-control" id="folio" name="folio" value="" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="producto">Producto</label>
                                    <input type="text" class="form-control" id="producto" name="producto" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="unidad_medida">Unidad de Medida</label>
                                    <input type="text" class="form-control" id="unidad_medida" name="unidad_medida" value="">
                                    <div id="error_unidad_medida" name="error_unidad_medida" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="minimo1">Stock Minimo</label>
                                    <input type="number" class="form-control" id="minimo1" name="minimo1" onkeypress="return validaNumericos(event)" min="1" onchange="validaModal()">
                                    <div id="error_minimo1" name="error_minimo1" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="maximo1">Stock Maximo</label>
                                    <input type="number" class="form-control" id="maximo1" name="maximo1" onkeypress="return validaNumericos(event)" min="1" onchange="validaModal()">
                                    <div id="error_maximo1" name="error_maximo1" class="text-danger"></div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="parametros" name="parametros" class="btn btn-guardar">Guardar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section>

        <div class="modal fade" id="salidaModal" tabindex="-1" aria-labelledby="salidaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Salida de Artículo<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="salida_articulos" method="post">
                            
                            <input type="hidden" id="id_producto" name="id_producto" value="">
                           
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nombre_articulo">Artículo</label>
                                    <input type="text" class="form-control" id="nombre_articulo" name="nombre_articulo" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cantidad_salida">Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad_salida" name="cantidad_salida" onkeypress="return validaNumericos(event)" min="1" onchange="validaModal()">
                                    <div id="error_cantidad_salida" name="error_cantidad_salida" class="text-danger"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="observacion_salida">Observación</label>
                                <textarea name="observacion_salida" id="observacion_salida" cols="12" rows="4" class="form-control" placeholder="algun detalle en la entrega?" onchange="validaModal()"></textarea>
                                <div id="error_observacion_salida" name="error_observacion_salida" class="text-danger"></div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="salida_suministros" name="salida_suministros" class="btn btn-primary">Guardar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section>

        <div class="modal fade" id="entradaModal" tabindex="-1" aria-labelledby="entradaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Entrada de Artículo<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="registrar_articulo" method="post">
                            <input type="hidden" id="id_articulos" name="id_articulos" value="">
                           <!--  <input type="hidden" id="code_epicor" name="code_epicor" value=""> -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nombre_entrada">Artículo</label>
                                    <input type="text" class="form-control" id="nombre_entrada" name="nombre_entrada" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cantidad_entrada">Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad_entrada" name="cantidad_entrada" onkeypress="return validaNumericos(event)" min="1" onchange="validaModal()">
                                    <div id="error_cantidad_entrada" name="error_cantidad_entrada" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacion_entrada">Observación</label>
                                <textarea name="observacion_entrada" id="observacion_entrada" cols="12" rows="4" class="form-control" placeholder="algun detalle en la entrega?" onchange="validaModal()"></textarea>
                                <div id="error_observacion_entrada" name="error_observacion_entrada" class="text-danger"></div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="guardar_entrada" name="guardar_entrada" class="btn btn-primary">Guardar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.js"></script>
<script src="<?= base_url() ?>/public/js/qhse/request_epp_alm_v1.js"></script>
<?= $this->endSection() ?>