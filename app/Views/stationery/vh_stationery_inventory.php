<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Papelería
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inventario</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item"><a href="#">Papelería</a></li>
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
          <h3 class="card-title">Alta de Articulos</h3>
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
            <form id="nuevo_articulo" method="post">

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="categoria">Categoria</label>
                  <select name="categoria" id="categoria" class="form-control rounded-0" onchange="validar()">
                      <option value="">Seleccionar</option>
                      <?php
                      foreach ($data as $key => $value) { ?>
                          <option value="<?= $value->id_cat; ?>"><?= $value->category;?></option>
                      
                     <?php  } ?>
                  </select>
                  <div id="error_categoria" name="error_categoria" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="descripcion">Descripción</label>
                  <input type="text" class="form-control rounded-0" id="descripcion" name="descripcion" value="" onchange="validar()">
                  <div id="error_descripcion" name="error_descripcion" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="cantidad">Cantidad Inicial</label>
                  <input type="number" class="form-control rounded-0" id="cantidad" name="cantidad" value="" onchange="validar()">
                  <div id="error_cantidad" name="error_cantidad" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="minimo">Minimo</label>
                  <input type="number" class="form-control rounded-0" id="minimo" name="minimo" value="" onchange="validar()">
                  <div id="error_minimo" name="error_minimo" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="maximo">Maximo</label>
                  <input type="number" class="form-control rounded-0" id="maximo" name="maximo" value="" onchange="validar()">
                  <div id="error_maximo" name="error_maximo" class="text-danger"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="imagen">Imagen del Articulo</label>
                  <input type="file" class="form-control rounded-0" id="imagen" name="imagen" size="1024" onchange="validar()">
                  <div id="error_imagen" name="error_imagen" class="text-danger"></div>
                </div>
              </div>
              <hr>
              <button id="guardar_articulo" type="submit" class="btn btn-guardar btn-lg">Guardar</button>
            </form>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Listado de Material</a>
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
                    <h3 class="card-title">Inventario de papelería</h3>
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
                    <table id="tabla_inventario_papeleria" class="table table-bordered table-striped " role="grid" aria-describedby="vacaciones_info" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Inventario </a>
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
                        <form id="parametros_papeleria" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="folio">Folio</label>
                                    <input type="text" class="form-control" id="folio" name="folio" value="" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="producto">Producto</label>
                                    <input type="text" class="form-control" id="producto" name="producto" readonly>
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
                            <input type="hidden" id="id_articulo" name="id_articulo" value="">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="stock_min">Artículo</label>
                                    <input type="text" class="form-control" id="nombre_articulo" name="nombre_articulo" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="stock_max">Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad_salida" name="cantidad_salida" onkeypress="return validaNumericos(event)" min="1" onchange="validaModal()">
                                    <div id="error_cantidad_salida" name="error_cantidad_salida" class="text-danger"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description_supplies">Observación</label>
                                <textarea name="observacion_salida" id="observacion_salida" cols="12" rows="4" class="form-control" placeholder="algun detalle en la entrega?" onchange="validaModal()"></textarea>
                                <div id="error_observacion_salida" name="error_observacion_salida" class="text-danger"></div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button id="salida_suministros" name="salida_suministros" class="btn btn-primary">Guardar</button>
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
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="stock_min">Artículo</label>
                                    <input type="text" class="form-control" id="nombre_entrada" name="nombre_entrada" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="stock_max">Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad_entrada" name="cantidad_entrada" onkeypress="return validaNumericos(event)" min="1" onchange="validaModal()">
                                    <div id="error_cantidad_entrada" name="error_cantidad_entrada" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description_supplies">Observación</label>
                                <textarea name="observacion_entrada" id="observacion_entrada" cols="12" rows="4" class="form-control" placeholder="algun detalle en la entrega?" onchange="validaModal()"></textarea>
                                <div id="error_observacion_entrada" name="error_observacion_entrada" class="text-danger"></div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button id="guardar_entrada" name="guardar_entrada" class="btn btn-primary">Guardar</button>
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
<script src="<?= base_url() ?>/public/js/stationery/vh_inventario_v1.js"></script>
<!-- <script src="<?= base_url() ?>/public/js/permissions/permissions_authorize.js"></script> -->
<?= $this->endSection() ?>