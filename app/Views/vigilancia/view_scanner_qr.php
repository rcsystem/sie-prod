<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Scanner<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Scanner QR</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Scanner QR</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Scanner QR</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="text-center mb-3">
                        <div id="scanner" style="max-width:400px; margin:auto;"></div>
                        <button id="btnToggleScanner" class="btn btn-primary mt-2">▶️ Activar Scanner</button>
                    </div>
                    <div id="resultado" class="text-center font-weight-bold">Esperando escaneo...</div>

                    <div id="resultados" class="mt-4">
                        <table id="tbl_estacionamientos" class="table table-bordered table-striped"></table>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <small>Vigilancia</small>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/js/lib/html5-qrcode.min.js"></script>
<script>
    let tbl_estacionamientos;

    $(document).ready(function() {
        // ================== DataTable ==================
        tbl_estacionamientos = $("#tbl_estacionamientos").DataTable({
            processing: true,
            ajax: {
                method: "post",
                url: `${urls}vigilancia/tbl_estacionamientos`,
                dataSrc: "data",
            },
            responsive: true,
            ordering: true,
            autoWidth: true,
            bInfo: false,
            rowId: "id_estacionamiento",
            dom: "lBfrtip",
            language: {
                url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            columns: [{
                    data: "id_estacionamiento",
                    title: "Folio",
                    className: "text-center"
                },
                {
                    data: "nombre_usuario",
                    title: "Usuario",
                    className: "text-center"
                },
                {
                    data: "marbete",
                    title: "Marbete",
                    className: "text-center"
                },
                {
                    data: "modelo",
                    title: "Modelo",
                    className: "text-center"
                },
                {
                    data: "color",
                    title: "Color",
                    className: "text-center"
                },
                {
                    data: "tipo",
                    title: "Tipo",
                    className: "text-center"
                },
                {
                    data: "placa",
                    title: "Placa",
                    className: "text-center"
                },
                {
                    data: null,
                    title: "Acceso",
                    className: "text-center",
                    render: data =>
                        data.estado === "ENTRADA" ?
                        `<span class="badge badge-success">Entrada</span>` :
                        `<span class="badge badge-info">Salida</span>`
                },
                {
                    data: null,
                    title: "Tiempo",
                    className: "text-center",
                    render: data => {
                        const fecha = new Date(data.scanner_at);
                        return fecha.toLocaleDateString() + ' ' +
                            fecha.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                    }

                },
                {
                    data: null,
                    className: "text-center",
                    render: data => `
                    <button class="btn btn-outline-danger btn-sm" 
                        title="Desactivar Activo" 
                        onclick="deleteChange(${data.id_estacionamiento})">
                        <i class="fas fa-power-off"></i>
                    </button>`
                }
            ],
            order: [
                [0, "desc"]
            ],
            buttons: [{
                extend: "excelHtml5",
                text: '<i class="far fa-file-excel"></i> Exportar a Excel',
                title: "Inventario",
                className: "btn btn-success",
            }]
        });
    });

    //=================== Delete Change ==================
    function deleteChange(id) {
        Swal.fire({
            title: "¿Desactivar este registro?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, desactivar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                //solo vamos a cambiar el status no a eliminar

                fetch(`${urls}vigilancia/delete_estacionamiento/${id}`, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        Swal.fire({
                            icon: data.ok ? 'success' : 'error',
                            title: data.mensaje,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tbl_estacionamientos.ajax.reload(null, false);
                    })
                    .catch(() => {
                        Swal.fire("Error", "No se pudo eliminar el registro", "error");
                    });
            }
        });
    }


    // ================== QR Scanner ==================
    let ultimoScan = 0;
    const tiempoBloqueo = 8000;
    const html5QrCode = new Html5Qrcode("scanner");
    let scannerActivo = false;

    function onScanSuccess(decodedText) {
        let ahora = Date.now();
        if (ahora - ultimoScan < tiempoBloqueo) return;
        ultimoScan = ahora;

        try {
            let datos = JSON.parse(decodedText);

            // Mostrar info clave
            $("#resultado").html(`
            <p><b>Marbete:</b> ${datos.marbete}</p>
            <p><b>Empleado:</b> ${datos.nombre}</p>
            <p><b>Tipo:</b> ${datos.tipo}</p>
            <p><b>Placa:</b> ${datos.placa}</p>
            <p><b>Modelo:</b> ${datos.modelo}</p>
            <p><b>Color:</b> ${datos.color}</p>
        `);

            // Enviar al backend
            fetch("<?= base_url('/vigilancia/registrar') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(datos)
                })
                .then(r => r.json())
                .then(data => {
                    Swal.fire({
                        icon: data.ok ? 'success' : 'error',
                        title: data.mensaje,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    tbl_estacionamientos.ajax.reload(null, false);
                    setTimeout(() => {
                        $("#resultado").html("Esperando escaneo...");
                    }, 8000);
                })
                .catch(() => {
                    Swal.fire("Error", "No se pudo enviar la información", "error");
                });
        } catch (e) {
            Swal.fire("Error", "El QR leído no tiene un formato válido", "error");
        }
    }

    // ================== Botón Toggle ==================
    $("#btnToggleScanner").on("click", function() {
        const $btn = $(this);
        $btn.prop("disabled", true);

        if (!scannerActivo) {
            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: 250
                }, onScanSuccess)
                .then(() => {
                    scannerActivo = true;
                    $btn.text("⏹️ Detener Scanner").removeClass("btn-primary").addClass("btn-success");
                })
                .catch(err => Swal.fire("Error", "No se pudo iniciar el scanner: " + err, "error"))
                .finally(() => $btn.prop("disabled", false));
        } else {
            html5QrCode.stop()
                .then(() => {
                    scannerActivo = false;
                    $btn.text("▶️ Activar Scanner").removeClass("btn-success").addClass("btn-primary");
                })
                .catch(() => Swal.fire("Error", "No se pudo detener el scanner", "error"))
                .finally(() => $btn.prop("disabled", false));
        }
    });
</script>
<?= $this->endSection() ?>