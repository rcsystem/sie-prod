<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Comprobaciones
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .my-col-it {
        flex: 0 0 20%;
        max-width: 20%;
        position: relative;
        width: 100%;
        padding-right: 7.5px;
        padding-left: 7.5px;
    }

    #to_upload {
        background-color: #FAFAFA;
        border: 1px dashed #BBB;
        padding: 10px;
        text-align: center;
    }

    #to_upload a {
        display: inline-block;
        position: relative;
        overflow: hidden;
        vertical-align: top;
        text-decoration: none;
    }

    #to_upload a input {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        z-index: 10;
        width: 100%;
    }

    #to_upload>div {
        margin-top: 10px;
        overflow: hidden;
    }

    /* 5 imagenes por fila */
    #to_upload>div>div {
        width: 220px;
        height: 140px;
        padding-right: 1%;
        padding-left: 1%;
        float: left;
    }

    .dd-thumbnail {
        min-height: 110px;
        /* todo:imagenes perfectas en el div */
        background-size: cover;
    }

    .dd-thumbnail i {
        /*icon size*/
        font-size: 50px;
        margin-top: 40px;
    }

    .dd-file-info {
        width: 100%
    }

    .dd-file-info span {
        float: left;
        font-size: 13px;
        padding-top: 3px;
    }

    .dd-file-info button {
        float: right;
        background: none;
        border: none;
        outline: none;
        color: #444444;
    }

    .fa-file-pdf {
        color: firebrick;
    }

    .fa-file-code {
        color: gainsboro;
    }

    /*******/

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

    .files {
        /* background: #eee; */
        padding: 0.1rem 0.1rem 0.1rem 0.1rem;
        margin: 0 0.5rem 0;
        border-radius: 3px;
    }

    .files ul {
        list-style: none;
        padding: 0;
        max-height: 150px;
        overflow: auto;
    }

    .files li {
        padding: 0.5rem 0;
        padding-right: 2rem;
        position: relative;
    }

    .files i {
        cursor: pointer;
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
    }




    span.file-size {
        color: #999;
        padding-left: 0.5rem;
    }

    i.fa-trash-alt {
        color: firebrick;
        cursor: pointer;
        padding-left: 0.5rem;
    }

    /* Estilo para el ícono cuando el cursor está sobre él (hover) */
    i.fas.fa-trash-alt:hover {
        color: #000;
        /* Cambia el color del ícono en hover */
        /* Otros estilos que desees aplicar al ícono en hover */
    }

    .table.tabla-pequena th {
        font-size: 15px;
        padding: 8px;
        height: 25px;
    }

    .horizontal-list {
        list-style-type: none;
        /* Quita las viñetas/bullets de la lista */
        padding: 0;
        /* Elimina el relleno predeterminado de la lista */
        margin-bottom: auto;
    }

    .horizontal-list li {
        display: inline-block;
        /* Muestra los elementos en línea */
        margin-right: 10px;
        /* Añade un margen derecho entre los elementos (ajusta según tus necesidades) */
    }

    h5.card-title {
        margin-right: 1rem;
    }

    /*****css checkbox ******/
    label {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;

        --slide-distance: 1.2rem;
        --slider-size: 1.25rem;
        --slider-padding: 0.2rem;
        --transition-duration: 200ms;
    }

    .slider {
        flex-shrink: 0;
        width: calc(var(--slider-size) + var(--slide-distance) + var(--slider-padding) * 2);
        padding: var(--slider-padding);
        border-radius: 9999px;
        background-color: #d1d5db;
        transition: background-color var(--transition-duration);

        &::after {
            content: "";
            display: block;
            width: var(--slider-size);
            height: var(--slider-size);
            background-color: #fff;
            border-radius: 9999px;
            transition: transform var(--transition-duration);
        }
    }

    input:checked+.slider {
        background-color: hsl(130deg, 100%, 30%);

        &::after {
            transform: translateX(var(--slide-distance));
        }
    }

    input:focus-visible+.slider {
        outline-offset: 2px;
        outline: 2px solid hsl(210deg, 100%, 40%);
    }

    .label {
        line-height: 1.5;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }

    .bg-green {
        background: #527332 !important;
    }
    
    #inputOculto {
      display: none;
    }
  </style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Comprobación de <?= $a = ($type == 1) ? 'Viaticos' : 'Gastos'; ?> con <b id="h_folio"> </b>.</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Viajes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content pt-2" style="font-family: 'Roboto Condensed'; display: contents;">
        <div class="container-fluid">
            <div class="row">
                <div class="my-col-it">
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"> <i class="fas fa-money-bill-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">MONTO SOLICITADO</span>
                            <H2 style="margin-top: 10px;" id="h2_solicitado">$5000</H2>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"> <i class="fas fa-money-check-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">ESTADO DE CUENTA</span>
                            <H2 style="margin-top: 10px;" id="h2_estado_cuenta">$1200</H2>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"> <i class="fas fa-user-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">MONTO COMPROBADO:</span>
                            <H2 style="margin-top: 10px;" id="h2_comprobado">$500</H2>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="info-box bg-gradient-danger">
                        <span class="info-box-icon"> <i class="fas fa-user-tag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">DESCUENTO:</span>
                            <H2 style="margin-top: 10px;" id="h2_descuento">$0</H2>
                        </div>
                    </div>
                </div>
                <div id="div_monto_grado" class="my-col-it">
                    <div class="info-box bg-gradient-secondary">
                        <span class="info-box-icon"> <i class="far" id="icon_grade" style="font-size: 3rem;">XI</i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">DIARIO POR GRADO:</span>
                            <H2 style="margin-top: 10px;" id="h2_grado">$0</H2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Comprobaciones</h3> &nbsp;&nbsp;  | &nbsp;&nbsp;  <span style="font-size: small;color:red"><b>COLOCAR EL MONTO AL 16 % ó al  8 % DE FACTURA, ANTES DE COMPROBAR LOS SIGUIENTES GASTOS (CAFETERIA/OXXO)</b></span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12" style="margin-bottom: 1rem;">
                    
                        <div class="row">
                            <div class="col-md-6">
                                <?php if ($type == 1) { ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="estado_visitar">Estado a visitar</label>
                                            <input type="text" class="form-control" id="estado_visitar" name="estado_visitar">
                                            <div id="error_estado" class="text-danger"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="dias_visita">Dias de Visita</label>
                                            <input type="number" class="form-control" id="dias_visita" name="dias_visita">
                                            <div id="error_dias_visita" class="text-danger"></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div id="datos_totales" class="col-md-6">
                                <h2 id="totales"></h2>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" id="subtotal_acumulado" name="subtotal_acumulado" value="0">
                    <input type="hidden" id="iva_acumulado" name="iva_acumulado" value="0">
                    <input type="hidden" id="total_acumulado" name="total_acumulado" value="0">
                    <input type="hidden" id="folio" name="folio" value="<?= $folio; ?>">
                    <input type="hidden" id="type" name="type" value="<?= $type; ?>">
                    <div id="formato_nuevo"></div>

                </div>
            </div>

        </div>
    </section>
    <section>
        <div class="modal fade" id="DocumentModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" style="width:50%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Comprobantes de Anticipo<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="subir_documentos" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id_travel" id="id_travel">
                            <div id="elementosDiv" class="row"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button id="btn_subir_documentos" class="btn btn-guardar">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
<input type="hidden" name="iduser" id="iduser" value="<?= session()->id_user ;?>"/>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/travels/verification_v18.js"></script>
<?= $this->endSection() ?>

adriana berenice 