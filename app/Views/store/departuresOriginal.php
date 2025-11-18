<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Transferencias entre Naves
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Transferencia entre Naves</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item"><a href="#">Almacen</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Transferencias</h3>
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
          <form id="materia_prima" method="post" enctype="multipart/form-data">
            <div class="form-row">

              <div id="resultado" class="form-group error col-md-8"></div>

            </div>

            <div class="row">
              <div class="form-group col-md-6">
                <label for="destinatario">Origen</label>
                <select name="destinatario" id="destinatario" class="form-control" required>
                  <option value="">Seleccionar...</option>
                  <option value="1">NAVE 1</option>
                  <option value="3">NAVE 3</option>
                  <option value="2">NAVE 4</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="destinatario">Salida a </label>
                <select name="tranferir" id="transferir" class="form-control" required>
                  <option value="">Seleccionar...</option>
                  <option value="1">NAVE 1</option>
                  <option value="3">NAVE 3</option>
                  <option value="2">NAVE 4</option>
                  <option value="4">VillaHermosa</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="btn-papeleria col-md-6">

                <button type="submit" id="create-account-button" class="btn btn-guardar btn-lg"> Generar Solicitud</button>
                <button id="btn-agregar-item" class="btn btn-secondary btn-lg" type="button"> Agregar Item</button>
              </div>

            </div>
            <hr>
            <br>
            <div id="form_duplica" class="row">
              <div id="duplica" class="agrega-item col-md-12">
                <div id="item-duplica" class=""></div>
              </div>
              <div id="product" class="col-md-12">
                <div id="item-card_1" class="card">
                  <div id="header-car" class="card-header"> <span id="title-item"> Agregar Item</span>

                  </div>

                  <div class="card-body row">
                    <div class="form-group  col-md-4">
                      <label>Código</label>
                      <input type="text" id="codigo_1" name="codigo[]" class="form-control rounded-0" onchange="escuchar(1)" required>
                      <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group  col-md-4">
                      <label>Artículo</label>
                      <input type="text" id="articulo_1" name="articulo[]" class="form-control rounded-0" required>
                    </div>

                    <div class="form-group col-md-4">
                      <label>Cantidad</label>
                      <input type="number" step="any"  id="cantidad_1" name="cantidad[]" class="form-control rounded-0"   required>
                    </div>
                    <div class="form-group col-md-4">
                      <label for="peso">Ubicación</label>
                      <input type="text" name="peso[]" id="peso_1" minlength="3" class="form-control rounded-0" required>
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group  col-md-8">
                      <label>Observaciones</label>
                      <textarea name="observacion[]" id="observacion_1" cols="4" rows="2" class="form-control rounded-0"></textarea>
                    </div>
                  </div>
                  <div class="card-footer text-muted"><a href="#">Item</a></div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="card-footer">
          <a href="#">Solicitudes</a>
        </div>
      </div>
    </div>
  </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/store/departures_v1.js"></script>
<?= $this->endSection() ?>