<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Qr Inventario Activo
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/flatpickr.min.css">

<style>
  #pdf-viewer {
    border: 1px solid #ccc;
    width: 100%;
    max-width: 600px;
    height: 500px;
    overflow: auto;
  }

  canvas {
    display: block;
    margin: auto;
  }

  .badge-cancel {
    color: #fff;
    background-color: #f76a77;
  }

  .btn-outline-black {
    color: #000;
    border-color: #000;
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

          <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf" required>
            <button type="submit" class="btn btn-guardar">Subir PDF</button>
          </form>

          <div id="pdfContainer">
            <object id="pdfObject" type="application/pdf" width="800" height="600">
              <p>Tu navegador no soporta archivos PDF.</p>
            </object>
          </div>

          <button id="signBtn" style="display:none;" class="btn btn-guardar">Firmar PDF</button>






        </div>

        <div class="card-footer">
          <a href="#">Finanzas</a>
        </div>
      </div>
    </div>
  </section>












  <section>
    <!-- Modal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="verPermisosModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="verPermisosModal">Documento PDF Permisos</h5>
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



</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/finance/finance_solicitud_v1.js"></script>
<script>

$('#uploadForm').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: `${urls}finanzas/subir_pdf`, // Ruta del controlador en CodeIgniter 4
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // Asumiendo que la respuesta es la URL del PDF subido
            var pdfUrl = response.pdfUrl;
            $('#pdfObject').attr('data', pdfUrl);
            $('#signBtn').show(); // Mostrar el botón de firma
        },
        error: function() {
            alert('Hubo un error al subir el archivo');
        }
    });
});

$('#signBtn').click(function() {
    var pdfUrl = $('#pdfObject').attr('data');
    
    $.ajax({
        url:`${urls}finanzas/firmar_pdf`, // Ruta del controlador en CodeIgniter 4
        type: 'POST',
        data: { pdfPath: pdfUrl },
        success: function(response) {
            var signedPdfUrl = response.signedPdfUrl;
            $('#pdfObject').attr('data', signedPdfUrl); // Mostrar el PDF firmado
        },
        error: function() {
            alert('Hubo un error al firmar el archivo');
        }
    });
});



</script>

<?= $this->endSection() ?>