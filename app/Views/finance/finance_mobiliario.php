<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Inventario - Mobiliario
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url() ?>/public/css/fixedColumns.dataTables.min.css" rel="stylesheet" />
<style>
  .badge-cancel {
    color: #fff;
    background-color: #f76a77;
  }

  .btn-outline-black {
    color: #000;
    border-color: #000;
  }

  .dataTable th,
  .dataTable td {
    padding: 8px;
    /* Ajusta el padding según sea necesario */
  }

  .btn-guardar {
    color: #fff;
    background-color: #1f2d3d;
    border-color: #1f2d3d;
    box-shadow: none;
  }

  .btn-circle {
    background-color: rgb(107, 106, 106);
    border-color: #444444;
    border-radius: 30px;
    box-shadow: none;
    font-weight: 400 !important;
    padding: 0.4rem 2rem;
    color: #fff;
  }

  .btn:hover {
    color: rgb(199, 199, 199) !important;
    text-decoration: none;
  }

  /* Cambiar la fuente de los encabezados de la tabla */
  table.dataTable thead th {
    font-family: 'system-ui', sans-serif;
    /* Cambia la fuente */
    font-size: 14px;
    /* Cambia el tamaño de la fuente */
    font-weight: bold;
    /* Hace el texto más grueso */
    color: #333;
    /* Cambia el color del texto */
    background-color: #f5f5f5;
    /* Cambia el color de fondo */
    text-transform: uppercase;
    /* Convierte el texto a mayúsculas */
  }

  /* Ensure that the demo table scrolls */
  th,
  td {
    white-space: nowrap;
  }

  div.dataTables_wrapper {
    width: 99%;
    margin: 0 auto;
  }

  table.dataTable tbody tr>.dtfc-fixed-left,
  table.dataTable tbody tr>.dtfc-fixed-right {
    z-index: 1;
    background-color: #eee;

  }

  .remarcar {
    font-weight: bold;
  }

  .dtfc-right-top-blocker {
    display: none !important;
  }

  .dataTables_scrollBody::-webkit-scrollbar:vertical {
    width: 9px;
  }

  .dataTables_scrollBody::-webkit-scrollbar {
    /* width: 8px; */
    height: 8px;
  }

  .dataTables_scrollBody::-webkit-scrollbar-track {
    background: rgba(241, 241, 241, .12);
  }

  .dataTables_scrollBody::-webkit-scrollbar-thumb {
    background-color: rgba(0.75, 0.75, 0.75, 0.4);
    border-radius: 5px;
    /* border: 3px solid #474D54; */
  }

  .my-col-rq {
    flex: 0 0 20%;
    max-width: 20%;
    position: relative;
    width: 100%;
    padding-right: 7.5px;
    padding-left: 7.5px;
  }

  .my-col-it {
    flex: 0 0 13.3333333333%;
    max-width: 13.3333333333%;
    position: relative;
    width: 100%;
    padding-right: 7.5px;
    padding-left: 7.5px;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Mobiliario</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Finanzas</li>
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
          <h3 class="card-title">Inventario - Mobiliario</h3>
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

          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Mobiliario Activo</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Mobiliario Inactivo</button>
            </li>

          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <div class="col-md-12">
                <br>
                <!-- Contenedor para el botón de "Nuevo Activo" alineado a la derecha -->
                <div class="text-right">
                  <a class="btn btn-circle" onclick="abrirMobiliarioModal()">
                    Nuevo Mobiliario
                  </a>
                  <!--  <button class="btn btn-guardar " onclick="abrirActivoModal()">Nuevo Activo</button> -->
                </div>
              </div>
              <br>
              <div class="table-responsive" style="">

                <table id="tbl_finanzas_inventario_mobiliario" class="display compact dataTable table-bordered table-striped  nowrap" style="width:100%" role="grid" aria-describedby="finanzas_inventario_mobiliario">

                  <thead style="font-size:15px;">
                    <tr>
                      <th>
                        <center>Id<center>
                      </th>
                      <th>
                        <center>Código<center>
                      </th>
                      <th>
                        <center>Descripción<center>
                      </th>
                      <th>
                        <center>Marca<center>
                      </th>
                      <th>
                        <center>Capacidad<center>
                      </th>
                      <th>
                        <center>Modelo<center>
                      </th>
                      <th>
                        <center>Serie<center>
                      </th>
                      <th>
                        <center>Ubicación<center>
                      </th>
                      <th>
                        <center>Area<center>
                      </th>
                      <th>
                        <center>Fecha<center>
                      </th>
                      <th>
                        <center>Proveedor<center>
                      </th>
                      <th>
                        <center>Factura<center>
                      </th>
                      <th>
                        <center>Revisado<center>
                      </th>
                      <th>
                        <center>ACCIONES<center>
                      </th>

                    </tr>
                  </thead>

                </table>

              </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <br>
              <div style="">
                <table id="tbl_inventario_inactivo_mobiliario" class="table table-striped table-bordered nowrap" style="width:100%" role="grid" aria-describedby="inventario_inactivo_mobiliario">
                </table>
              </div>
            </div>

          </div>


        </div>

        <div class="card-footer">
          <a href="#">Finanzas</a>
        </div>
      </div>
    </div>
  </section>












  <section>
    <!-- Modal -->
    <div class="modal fade" id="mobiliarioModal" tabindex="-1" role="dialog" aria-labelledby="mobiliarioModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Alta de Activo</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="alta_mobiliario" action="" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">

                  <div class="form-group col-md-4">
                    <label for="codigo">Codigo</label>
                    <input type="text" class="form-control" id="codigo" placeholder="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="descripcion">Descripción</label>
                    <input type="text" class="form-control" id="descripcion" placeholder="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="marca">Marca</label>
                    <input type="text" class="form-control" id="marca" placeholder="">
                  </div>
                  <hr>
                  <div class="form-group col-md-4">
                    <label for="capacidad">Capacidad</label>
                    <input type="text" class="form-control" id="capacidad" placeholder="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="modelo">Modelo</label>
                    <input type="text" class="form-control" id="modelo" placeholder="">
                  </div>

                  <div class="form-group col-md-4">
                    <label for="serie">Serie</label>
                    <input type="text" class="form-control" id="serie" placeholder="">
                  </div>
                  <hr>
                  <div class="form-group col-md-4">
                    <label for="ubicacion">Ubicación</label>
                    <input type="text" class="form-control" id="ubicacion" placeholder="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="area">Area</label>
                    <input type="text" class="form-control" id="area" placeholder="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="fecha">Fecha</label>
                    <input type="date" class="form-control" id="fecha" />
                  </div>
                  <hr>
                  <div class="form-group col-md-4">
                    <label for="proveedor">Proveedor</label>
                    <input type="text" class="form-control" id="proveedor" />
                  </div>
                  <div class="form-group col-md-3">
                    <label for="revisado">Revisado</label>
                    <!--  <input type="text" class="form-control" id="revisado" /> -->
                    <select name="revisado" id="revisado" class="form-control">
                      <option value="">Seleccionar...</option>
                      <option value="no">no</option>
                      <option value="ok">ok</option>
                    </select>
                  </div>
                  <hr>
                  <div class="form-group col-md-4">
                    <label for="datos">Datos</label>
                    <input type="text" class="form-control" id="datos" />
                  </div>
                  <div class="form-group col-md-4">
                    <label for="factura">Factura</label>
                    <input type="file" class="form-control" id="factura" placeholder="">
                  </div>

                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button id="btn_mobiliario" type="submit" class="btn btn-guardar">Guardar mobiliario</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <section>
    <!-- Modal para editar -->
    <div class="modal fade" id="editarMobiliarioModal" tabindex="-1" aria-labelledby="editarMobiliarioModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarMobiliarioModalLabel">Editar Mobiliario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="form_editar_mobiliario" method="post">
            <div class="modal-body">
              <input type="hidden" id="edit_id_activo" name="id_activo">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_codigo">Código</label>
                    <input type="text" class="form-control" id="edit_codigo" name="codigo">
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="edit_descripcion">Descripción</label>
                    <input type="text" class="form-control" id="edit_descripcion" name="descripcion">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_marca">Marca</label>
                    <input type="text" class="form-control" id="edit_marca" name="marca">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_capacidad">Capacidad</label>
                    <input type="text" class="form-control" id="edit_capacidad" name="capacidad">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_modelo">Modelo</label>
                    <input type="text" class="form-control" id="edit_modelo" name="modelo">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_serie">Serie</label>
                    <input type="text" class="form-control" id="edit_serie" name="serie">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_ubicacion">Ubicación</label>
                    <input type="text" class="form-control" id="edit_ubicacion" name="ubicacion">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_area">Area</label>
                    <input type="text" class="form-control" id="edit_area" name="area">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_fecha">Fecha</label>
                    <input type="date" class="form-control" id="edit_fecha" name="fecha">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_proveedor">Proveedor</label>
                    <input type="text" class="form-control" id="edit_proveedor" name="proveedor">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="edit_revisado">Revisado</label>
                    <select class="form-control" id="edit_revisado" name="revisado">
                      <option value="no">No</option>
                      <option value="ok">OK</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="edit_datos">Datos</label>
                    <input type="text" class="form-control" id="edit_datos" name="datos">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-guardar" id="btnGuardarCambios">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>



</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/js/lib/dataTables.fixedColumns.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/finance/inventario_mobiliario.js"></script>
<?= $this->endSection() ?>