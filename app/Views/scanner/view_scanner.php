<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Listado de Material
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .result {
        background-color: green;
        color: #fff;
        padding: 20px;
    }

    .row {
        display: flex;
    }

    a#reader__dashboard_section_swaplink {
        margin-top: 2rem !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listado de Material</h1>
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
                    <h3 class="card-title">Material</h3>
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

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div id="reader"></div>
                        </div>
                        <div class="col-md-6 mt-4">
                            <h4>RESULTADO DEL SCANEO</h4>
                            <div class="mt-5">
                                <ul id="result">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="#">Inventario </a>
                </div>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/js/scanner/html5-qrcode.min.js"></script>

<script type="text/javascript">
    var flag = true;

    function onScanSuccess(qrCodeMessage) {
        console.log("datos: " + qrCodeMessage);
        codeMessageQR = filterCharacter(qrCodeMessage);
        console.log("datos2: " + codeMessageQR);
        console.log("flag: "+flag);
        
        if (flag) {
            $("#result").append(`<li><input type="text" id="producto_" name="" class="form-control" value="${codeMessageQR}"   /></li>`);
            flag = false;
            setTimeout(() => flag = true, 3000);
        }

    }

    function filterCharacter(qrMessage) {
        // Primero establece un modo
        let resultStr = qrMessage.trim();
        // Cuando finaliza el bucle, devuelve el resultado final resultStr
        return resultStr;
    }

    function onScanError(errorMessage) {
        //document.getElementById('result').innerHTML = '<span class="result">' + errorMessage + '</span>';
        //handle scan error
    }

    var html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 1,
            qrbox: 250
        });
    html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>
<?= $this->endSection() ?>