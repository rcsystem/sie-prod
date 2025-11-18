<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
HSE | Almacen Epp
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
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

  .type-h4 {
    font-size: 1.5rem;
    font-family: 'Roboto Condensed', sans-serif !important;
    font-weight: 700 !important;
    font-style: normal;
  }

  .item {
    padding-top: 10px;
    padding-bottom: 10px;
  }

  .item-color {
    background-color: #D5D5D5;
  }

  .form-controls {
    width: 100%;
    border: none;
    border-bottom: 1px solid #ced4da;
    background: no-repeat center bottom, center calc(100% - 1px);
    background-size: 0 100%, 100% 100%;
    transition: background 0s ease-out;
    color: #495057;
    background-color: #fff;
    border-color: #00bc8c;
    outline: 0;
    box-shadow: 0 0 0 0rem rgba(70, 128, 255, 0.25);
  }

  .custom-file-label::after {
    content: "Subir";
  }

  .form-controls:focus {
    border-bottom-color: transparent;
    background-size: 100% 100%, 100% 100%;
    transition-duration: 0.3s;
    box-shadow: none;
    background-image: linear-gradient(to top, #00c163 2px, rgba(70, 128, 255, 0) 2px), linear-gradient(to top, #ced4da 1px, rgba(206, 212, 218, 0) 1px);
  }

  .form-controls:focus {
    color: #495057;
    background-color: #fff;
    border-color: #c6d8ff;
    outline: 0;
    box-shadow: 0 0 0 0rem rgba(70, 128, 255, 0.25);
  }


  .animate-show {
    animation: showAnimation 0.8s ease-in-out;
  }
  .swal2-container {
    z-index: 1080 !important; /* Asegúrate de que esté por encima del modal Bootstrap */
}
@media (min-width: 1200px) {
    .modal-xl {
        max-width: 1340px;
    }
}
</style>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"> HSE | Entrega Epp Prueba</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Inventario</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Entrega de Equipo de Protección Personal.</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <label for="num_nomina">Número de Nómina</label>
              <input type="number" name="num_nomina" id="num_nomina" class="form-control" min="1" onchange="datosUsusario(this)">
              <div class="text-danger" id="error_num_nomina"></div>

            </div>
            <div class="col-md-3">
              <label>Nombre Usuario</label>
              <input type="hidden" id="id_user" name="id_user">
              <input type="text" id="nombre" class="form-control" style="border: none;" disabled>
            </div>
            <div class="col-md-2" style="padding-top: 1.5rem;">
              <button type="button" id="btn_buscar_vale" class="btn btn-lg btn-outline-guardar">BUSCAR <i class="fas fa-search" style="margin-left: 10px;"></i></button>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="especificacion">Especificación:</label>
              <textarea id="especificacion" name="especificacion" class="form-controls" rows="3" readonly></textarea>
            </div>
          </div>
          <hr>

          <div id="vouchers_list">
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Modal -->
  <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="itemModalLabel">Items List</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="code_nomina" style="margin-top:1rem; margin-bottom:1rem;"></div>
        <div class="modal-body" id="modalBody">
          <form id="form_items"></form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="exampleModalLabel">CALVE DE CONFIRMACION</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="miFormulario">
            <div class="form-group">
              <label for="clave_conf">Solicita la clave al Usuario</label>
              <input type="text" class="form-control" id="clave_conf" name="clave_conf">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <!-- <button type="button" class="btn btn-primary" onclick="guardarDatos()">Guardar</button> -->
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url() ?>/public/js/qhse/equiment_raw_materialB_v2.js"></script>
<?= $this->endSection() ?>