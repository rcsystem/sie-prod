<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Todas las solicitudes del departamento
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/font-awesome.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/quill.snow.css">


<style>
/* Modal más amplio y con scroll cómodo */
#editarLiberation .modal-dialog { max-width: 820px; }
#editarLiberation .modal-body { max-height: 70vh; overflow-y: auto; }

/* Encabezado de info */
#info_solicitud .label { color:#6c757d; font-size:.85rem; }
#info_solicitud .value { font-weight:600; }

/* Tabla de ítems */
.table-liberation th, .table-liberation td { vertical-align: middle; }
.table-liberation .item-name { font-weight:500; }

/* Badges estandarizados */
.badge-pendiente { background:#ffc107; color:#212529; }   /* amarillo */
.badge-progreso  { background:#17a2b8; color:#212529; }                  /* azul */
.badge-firmado   { background:#28a745; color:#212529; }                  /* verde */

/* Switch más grande */
.form-switch .form-check-input {
    width: 2rem;
    height: 1.5rem;
    margin-top: -16px;
}
.form-switch .form-check-input:focus { box-shadow: none; }

/* Botones del header de la lista */
.items-header-actions { gap:.5rem; }
 
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Solicitudes de Liberación para el departamento</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Liberación del departamento</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Solicitudes de Liberación del departamento</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <!-- <div class="col-md-12 text-right">
                <a class="btn btn-outline-primary" onclick="abrirSolicitudModal()">
                    Agregar una Solicitud
                </a>
            </div> -->
            <table id="tabla_liberation" class="table table-bordered table-striped " role="grid" aria-describedby="todos_viajes" style="width:100%" ref="">
            </table>
          </div>
        </div>
      </div>
  </section>

  <!-- Main content -->
  <section>
    <div class="modal fade" id="editarLiberation" tabindex="-1" aria-labelledby="editarLiberationLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarLiberationLabel">Firmar</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div id="items_container" class="modal-body">
                <!-- Aquí se listarán los items con status -->
            </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Main content -->

    <!-- PDF MODAL -->
  <section>
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verPermisosModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Documento PDF</h5>
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
  <!-- END PDF MODAL -->


</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/dist/js/pages/jquery.velocity.js"></script>
<script src="<?= base_url() ?>/public/js/quill.min.js"></script>
<script src="<?= base_url() ?>/public/js/liberation/request_liberation_department_all.js"></script>
<?= $this->endSection() ?>