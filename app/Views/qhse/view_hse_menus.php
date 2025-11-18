<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
HSE | Solicitudes Epp
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .bg-acceptable {
    color: #fff;
    background-color: #F65E0A;
    border-color: #F65E0A;
  }

  .toggle {
    position: relative;
    box-sizing: border-box;
    padding: inherit;
  }

  .toggle input[type="checkbox"] {
    position: absolute;
    left: 0;
    top: 0;
    z-index: 10;
    width: 56%;
    height: 100%;
    cursor: pointer;
    opacity: 0;
  }

  .toggle label {
    position: relative;
    display: flex;
    align-items: center;
    box-sizing: border-box;
  }

  .toggle label:before {
    content: '';
    width: 40px;
    height: 22px;
    background: #ccc;
    position: relative;
    display: inline-block;
    border-radius: 46px;
    box-sizing: border-box;
    transition: 0.2s ease-in;
  }

  .toggle label:after {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    left: 2px;
    top: 2px;
    z-index: 2;
    background: #fff;
    box-sizing: border-box;
    transition: 0.2s ease-in;
  }

  .toggle input[type="checkbox"]:checked+label:before {
    background: #4BD865;
  }

  .toggle input[type="checkbox"]:checked+label:after {
    left: 19px;
  }

  .checkbox-center {
    display: flex;
    justify-content: center;
    /* Centrado horizontal */
    align-items: center;
    /* Centrado vertical */
    height: 100%;
    /* Asegura que ocupe toda la celda */
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"> HSE | Listado de Menús</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Responsabilidad Social</li>
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
          <h3 class="card-title">Agregar Nuevos Menus</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="container">
            <form id="agregar_menus" action="POST">
              <div class="row">
                <div id="cat_permiso_div"></div>
                <div class="col-md-3">
                  <label for="nombre_menu">Nombre del Menu</label>
                  <input type="text" id="nombre_menu" name="nombre_menu" class="form-control" />
                  <div class="text-danger" id="error_menu"></div>
                </div>
                <div class="col-md-3">
                  <label for="fecha_evento">Fecha Evento</label>
                  <input type="date" id="fecha_evento" name="fecha_evento" class="form-control" />
                  <div class="text-danger" id="error_fecha"></div>
                </div>
                <div class="col-md-3">
                  <label for="tipo_menu">Tipo de Menu</label>
                  <select name="tipo_menu" id="tipo_menu" class="form-control">
                    <option value="">Seleccionar</option>
                    <option value="1">Voluntario</option>
                    <option value="2">Acciones Verdes</option>
                    <option value="3">Actividades Deportivas</option>
                  </select>
                  <div class="text-danger" id="error_tipo_menu"></div>
                </div>
                <div class="col-md-2 my-4">
                  <button type="submit" id="btn_social" class="btn btn-guardar btn-block"> <b style="font-size:18px"> Guardar </b> </button>
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
          <h3 class="card-title">Lista de Menus</h3>
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
            <table id="tabla_lista_menus" class="table table-bordered table-striped " role="grid" aria-describedby="lista_menus" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Listado</a>
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
<script src="<?= base_url() ?>/public/js/qhse/list_menus_v1.js"></script>
<?= $this->endSection() ?>