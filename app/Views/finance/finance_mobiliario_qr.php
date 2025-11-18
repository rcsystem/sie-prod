<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Qr Inventario Activo
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">
<style>
  .badge-cancel {
    color: #fff;
    background-color: #f76a77;
  }

  .btn-outline-black {
    color: #000;
    border-color: #000;
  }

  .font-qr {
    font-family: 'Source Sans Pro', sans-serif;
    font-weight: 400;
    /* Puedes cambiarlo a 300, 400i, 700 según lo necesites */
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Qr Inventario Activo</h1>
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
          <h3 class="card-title">Detalle de Activo </h3>
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


          <table id="tabla-activos" class="table table-striped table-bordered nowrap font-qr" style="width:100%" role="grid" aria-describedby="finanzas_inventario">
            <thead>
              <tr>
                <th>Id</th>
                <th>Codigo</th>
                <th>Descripción</th>
                <th>Marca</th>
                <th>Capacidad</th>
                <th>Modelo</th>
                <th>Serie</th>
                <th>Ubicación</th>
                <th>Area</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Revisado</th>
                <th>Datos</th>
                <th>Factura</th>
                <th>Archivos</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($activos as $activo):
                $nombreArchivo = $activo['factura'];
                $id_activo = $activo['id_activo'];
              ?>
                <tr>
                  <td><?= $id_activo ?></td>
                  <td><?= $activo['codigo'] ?></td>
                  <td><?= $activo['descripcion'] ?></td>
                  <td><?= $activo['marca'] ?></td>
                  <td><?= $activo['capacidad'] ?></td>
                  <td><?= $activo['modelo'] ?></td>
                  <td><?= $activo['serie'] ?></td>
                  <td><?= $activo['ubicacion'] ?></td>
                  <td><?= $activo['area'] ?></td>
                  <td><?= $activo['fecha'] ?></td>
                  <td><?= $activo['proveedor'] ?></td>
                  <td><?= $activo['revisado'] ?></td>
                  <td><?= $activo['datos'] ?></td>
                  <td><a href="<?= base_url($activo["ruta_factura"]) ?>" download="<?= $nombreArchivo ?>" title="Descargar factura">
                      <?= $activo['factura'] ?>
                    </a> </td>
                  <td>
                    <button type="button" class="btn btn-outline-warning btn-sm" title="Descargar Archivos" onclick="downloadData(<?= $id_activo ?>)">
                      <i class="fas fa-file-archive"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

        </div>

        <div class="card-footer">
          <a href="#">Finanzas</a>
        </div>
      </div>
    </div>
  </section>


</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/finance/finance_inventory_v1.js"></script>

<script>
  $(document).ready(function() {
    $('#tabla-activos').DataTable({
      responsive: true,
      language: {
        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json"
      }
    });
  });
</script>


<?= $this->endSection() ?>