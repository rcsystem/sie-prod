<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Permanente
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

  .btn-retirar-item {
    margin-top: -3.2rem;
  }

  .form-control {
    border: none;
    border-bottom: 1px solid #ced4da;
    background: no-repeat center bottom, center calc(100% - 1px);
    background-size: 0 100%, 100% 100%;
    transition: background 0s ease-out;
  }

  .custom-file-label::after {
    content: "Subir";
  }

  .form-group .floating-label {
    position: absolute;
    top: 11px;
    left: 6px;
    font-size: 1rem;
    z-index: 1;
    cursor: text;
    transition: all 0.3s ease;
    color: #73808b;
  }

  .form-group .floating-label+.form-control {
    /*  padding-left: 0; */
    padding-right: 0;
    border-radius: 0;
  }

  .form-control:focus {
    border-bottom-color: transparent;
    background-size: 100% 100%, 100% 100%;
    transition-duration: 0.3s;
    box-shadow: none;
    background-image: linear-gradient(to top, #00c163 2px, rgba(70, 128, 255, 0) 2px), linear-gradient(to top, #ced4da 1px, rgba(206, 212, 218, 0) 1px);
  }

  .form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #c6d8ff;
    outline: 0;
    box-shadow: 0 0 0 0rem rgba(70, 128, 255, 0.25);
  }

  .form-group.fill .floating-label {
    top: -17px;
    font-size: 0.9rem;
    color: #4f4a4a;
  }


  .animate-show {
    animation: showAnimation 0.8s ease-in-out;
  }

  @keyframes showAnimation {
    0% {
      transform: translateX(-100%);
    }

    100% {
      transform: translateX(0);
    }
  }

  input[type=radio] {
    width: 100%;
    height: 26px;
    opacity: 0;
    cursor: pointer;
  }

  .radio-group div {
    width: 85px;
    display: inline-block;
    border: 2px solid #AEABAE;
    border-radius: 5px;
    text-align: center;
    position: relative;
    /* padding-bottom: 10px; */
  }

  .radio-group label {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    margin-bottom: 10px;
    line-height: 2em;
    pointer-events: none;
  }

  .radio-group input[type=radio]:checked+label {
    background: #1C7298;
    color: #fff;
  }

  .form-check-input {
    width: 20px;
    height: 30px;
    top: -10px;
  }

  .form-check-label {
    margin-left: 0.5rem;
  }

  .section-option {

    margin-left: 2rem;

  }

  .opt-check {
    width: 18px;
    height: 18px;
  }
  .form-container {
            margin: 50px auto;
            padding: 30px;
            background-color: #f7f7f7;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #42bd4f;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
            color: #124a85;
        }
        input[type="file"] {
        position: absolute;
        right: -9999px;
        visibility: hidden;
        opacity: 0;

    }

        label.up_file {
        position: relative;
        padding: 0.3rem 1rem;
        background: #212529;
        color: #fff;
        display: inline-block;
        text-align: center;
        overflow: hidden;
        border-radius: 3px;
        outline: none;

        &:hover {
            background: #646769;
            color: #fff;
            cursor: pointer;
            transition: 0.2s all;
            outline: none;
        }
    }
    .input-info{

      text-align: center;
    }

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-9">
          <h5 class="m-0">Validar Facturas</h5>
        </div><!-- /.col -->
        <div class="col-sm-3">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">HSE</li>

          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h2 class="form-title">Consulta de Estatus CFDI</h2>
                <div id="estatus_cfdi"></div>
                <form id="consulta-cfdi-form" enctype="multipart/form-data">
                <div class="form-group">
                <div id="tipo_archivo" class="input-info">
                  <label id="up_file" class="up_file" for="upload">
                    <input type="file" 
                            id="upload" 
                            name="upload[]" 
                            
                            onchange="fileFacturas(this)"
                            accept=".xml" 
                            multiple 
                            class="btn"/>
                    Elige PDF & XML
                  </label>
                  <div id="error_fac"></div>
                </div>
                    </div>

                    

                    <!-- <div class="form-group">
                        <label for="rfc_emisor">RFC Emisor</label>
                        <input type="text" class="form-control" id="rfc_emisor" name="rfc_emisor" placeholder="Ingrese el RFC del Emisor" required>
                    </div>
                    <div class="form-group">
                        <label for="rfc_receptor">RFC Receptor</label>
                        <input type="text" class="form-control" id="rfc_receptor" name="rfc_receptor" placeholder="Ingrese el RFC del Receptor" required>
                    </div>
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="number" step="0.01" class="form-control" id="total" name="total" placeholder="Ingrese el Total" required>
                    </div>
                    <div class="form-group">
                        <label for="uuid">UUID (Folio Fiscal)</label>
                        <input type="text" class="form-control" id="uuid" name="uuid" placeholder="Ingrese el UUID (Folio Fiscal)" required>
                    </div> -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-custom">Consultar</button>
                    </div>
                </form>
                <div id="tabla_result"></div>
            </div>
        </div>
    </div>
</div>
  </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/js/admin/sg_fac_v1.js"></script>

<?= $this->endSection() ?>