<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Inventario de Suministros
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Inventario de Suministros</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Sistemas</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
    <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">Alta de suministros</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <form id="alta_articulo" method="post">
              <div class="col-md-12 form-row">
                <div class="form-group col-md-12">
                  <label for="description_supplies">Descripción</label>
                  <input type="text" class="form-control" id="nombre_suministro" placeholder="Toner kyocera" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="stock_min">Stock Minimo</label>
                  <input type="number" class="form-control" id="alta_stock_min" name="alta_stock_min" onkeypress="return validaNumericos(event)" min="1" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="stock_max">Stock Maximo</label>
                  <input type="number" class="form-control" id="alta_stock_max" name="alta_stock_max" onkeypress="return validaNumericos(event)" min="1" required>
                </div>
              </div>
          
          <div class="footer">
              <button type="submit" id="alta_suministro" name="alta_suministro" class="btn btn-guardar btn-lg">Guardar</button>
          </div>
          </form>
        </div>
        <!--  /.card-body -->
        <div class="card-footer">
          <a href="#">suministros</a>
        </div>
      </div>
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Suministros</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <table id="tabla_suministros" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

            </table>
          </div>
        </div>

        <div class="card-footer">
          <a href="#">Inventario</a>
        </div>
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
            <form id="edit_article" method="post">
              <input type="hidden" id="id_article" name="id_article" value="">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="stock_min">Stock Minimo</label>
                  <input type="number" class="form-control" id="stock_min" name="stock_min" onkeypress="return validaNumericos(event)" min="1" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="stock_max">Stock Maximo</label>
                  <input type="number" class="form-control" id="stock_max" name="stock_max" onkeypress="return validaNumericos(event)" min="1" required>
                </div>
              </div>
              <div class="form-group">
                <label for="description_supplies">Descripción</label>
                <input type="text" class="form-control" id="description_supplies" placeholder="Toner kyocera" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button id="actualizar_suministro" name="actualizar_suministro" class="btn btn-primary">Actualizar</button>
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
            <form id="registrar_articulos" method="post">
              <input type="hidden" id="id_articulo" name="id_articulo" value="" >
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="stock_min">Artículo</label>
                  <input type="text" class="form-control" id="nombre_articulo" name="nombre_articulo" readonly>
                </div>
                <div class="form-group col-md-6">
                  <label for="stock_max">Cantidad</label>
                  <input type="number" class="form-control" id="cantidad_salida" name="cantidad_salida" onkeypress="return validaNumericos(event)" min="1" required>
                </div>
              </div>
              <div class="form-group">
                <label for="description_supplies">¿A quién se entregó?</label>
                <input type="text" class="form-control" id="entrega" name="entrega" placeholder="persona quien recibio" required>
              </div>
              <div class="form-group">
                <label for="description_supplies">Observación</label>
               <textarea name="observacion_salida" id="observacion_salida" cols="12" rows="4" class="form-control" placeholder="algun detalle en la entrega?"></textarea>
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
          <input type="hidden" id="id_articulos" name="id_articulos" value="" >
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="stock_min">Artículo</label>
              <input type="text" class="form-control" id="nombre_entrada" name="nombre_entrada" readonly>
            </div>
            <div class="form-group col-md-6">
              <label for="stock_max">Cantidad</label>
              <input type="number" class="form-control" id="cantidad_entrada" name="cantidad_entrada" onkeypress="return validaNumericos(event)" min="1" required>
            </div>
          </div>
          <div class="form-group">
            <label for="description_supplies">Observación</label>
           <textarea name="observacion_entrada" id="observacion_entrada" cols="12" rows="4" class="form-control" placeholder="algun detalle en la entrega?"></textarea>
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
<script src="<?= base_url() ?>/public/js/system/index_v1.js"></script>
<?= $this->endSection() ?>