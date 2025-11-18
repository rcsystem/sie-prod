<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Ticket´s
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/plugins/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .switch {
        position: absolute;
        top: 50%;
        width: 100px;
        height: 30px;
        text-align: center;
        margin: -20px 0 0 0;
        background: #00bc9c;
        transition: all 0.2s ease;
        border-radius: 25px;
    }

    .switch span {
        position: absolute;
        width: 20px;
        height: 4px;
        top: 50%;
        left: 45%;
        margin: -2px 0px 0px -4px;
        background: #fff;
        display: block;
        transform: rotate(-45deg);
        transition: all 0.2s ease;
    }

    .switch span:after {
        content: "";
        display: block;
        position: absolute;
        width: 4px;
        height: 12px;
        /* left: 50%; */
        margin-top: -8px;
        background: #fff;
        transition: all 0.2s ease;
    }

    input[type=radio] {
        display: none;
    }

    .switch label {
        cursor: pointer;
        color: rgba(0, 0, 0, 0.4);
        width: 60px;
        line-height: 50px;
        transition: all 0.2s ease;
    }

    .lbl-yes {
        position: absolute;
        left: -8px;
        top: -10px;
        height: 20px;
    }

    .lbl-no {
        position: absolute;
        top: -10px;
        right: -6px;

    }

    .no:checked~.switch {
        background: #eb4f37;
    }

    .no:checked~.switch span:after {
        background: #fff;
        height: 20px;
        margin-top: -8px;
        margin-left: 8px;
    }

    .sl-modal {
        width: 30%;
        top: 35%;
        right: 35%;
        left: 35%;
    }

    .scrold {
        width: 25rem;
        height: 535px;
        overflow: hidden;
        overflow-y: scroll;
        border: 1px solid rgba(168, 168, 168, 0.4);
        border-top: none;
        background-color: white;
    }

    @media screen and (min-width: 1900px) {
        .scrold {
            width: 25rem;
            height: 52rem;
            overflow: hidden;
            overflow-y: scroll;
            border: 1px solid rgba(168, 168, 168, 0.4);
            border-top: none;
            background-color: white;
        }
    }

    .scrold::-webkit-scrollbar {
        width: 8px;
        /* height: 8px; */
    }

    .scrold::-webkit-scrollbar-track {
        background: rgba(241, 241, 241, .12);
    }

    .scrold::-webkit-scrollbar-thumb {
        background-color: rgba(168, 168, 168, 0.3);
        border-radius: 20px;
        /* border: 3px solid #474D54; */
    }

    .card-style-personal {
        border-radius: .25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24);
        padding: inherit;
        margin-bottom: 15px;

    }

    .nav-icon {
        margin-left: 0.05rem;
        margin-right: 0.2rem;
        text-align: center;
    }

    .card-html {
        /* flex: 1 0 0%; */
        margin-right: 7.5px;
        margin-bottom: 0;
        margin-left: 7.5px;
    }

    .box-cards {
        height: 100%;
        overflow: hidden;
        overflow-x: scroll;
        display: flex;
        padding-bottom: 10px;
    }

    .box-cards::-webkit-scrollbar {
        /* width: 8px; */
        height: 8px;
    }

    .box-cards::-webkit-scrollbar-track {
        background: rgba(241, 241, 241, .12);
    }

    .box-cards::-webkit-scrollbar-thumb {
        background-color: rgba(0.75, 0.75, 0.75, 0.4);
        border-radius: 20px;
        /* border: 3px solid #474D54; */
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <?php
            switch (session()->access_tickets) {
                case 1:
                    $titulo = 'EPICOR';
                    break;
                case 2:
                    $titulo = 'Infraestructura';
                    break;

                default:
                    $titulo = 'Departamento de TI';
                    break;
            }
            ?>
            <section class="content-header ">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h1 style="margin-bottom:1rem;">Tickets <?= $titulo ?></h1>
                        </div>
                        <div class="col-md-2">
                            <?php if (session()->access_tickets == 1 || session()->access_tickets == 2 || session()->id_user == 1188) {
                                $empresa = (session()->access_tickets == 1) ? 'EPICOR' : 'INFRAESTRUCTURA';
                                $titulo = (session()->id_user == 1188) ? 'DEPARTAMENTO DE TI' : $empresa;
                            ?>
                                <div class="row" style="text-align: end;">
                                    <div class="btn-group1 btn-group-toggle" data-toggle="buttons">
                                        <label style="border:none;" class="btn btn-outline-primary btn-opcion active">
                                            <input type="radio" onclick="vista(1)" checked> <i class="far fa-newspaper"></i> Tablero
                                        </label>
                                        <label style="border:none;" class="btn btn-outline-primary btn-opcion">
                                            <input type="radio" onclick="vista(2)"> <i class="fas fa-chart-line"></i> Reporte
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
            <?php if (session()->access_tickets == 1 || session()->access_tickets == 2) { ?>
                <section class="content-header bg-white" id="div-filtros" style="margin-top: -1rem;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="sel-filtro-prioridad">Prioridad:</label>
                                <select class="form-control" id="sel-filtro-prioridad" onchange="BuscarTickets();">
                                    <option value="">TODAS</option>
                                    <option value="2">BAJA</option>
                                    <option value="3">MEDIA</option>
                                    <option value="4">ALTA</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="sel-filtro-actividad">Actividad:</label>
                                <select id="sel-filtro-actividad" class="form-control rounded-0 select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" onchange="BuscarTickets();">
                                    <option value="">TODAS</option>
                                    <?php foreach ($actvidad as $key) { ?>
                                        <option value="<?= $key->ActividadId; ?>"><?= $key->Actividad_Actividad; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="sel-filtro-usuario">Creado por:</label>
                                <select class="form-control " style="width: 100%; height: calc(2.25rem + 2px);" id="sel-filtro-usuario" onchange="BuscarTickets();">
                                    <option value="">TODOS</option>
                                    <?php foreach ($usuarios as $key) { ?>
                                        <option value="<?= $key->id_user; ?>"><?= $key->nombre; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="sel-filtro-tecnico">Atendido por:</label>
                                <select class="form-control select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" id="sel-filtro-tecnico" onchange="BuscarTickets();">
                                    <option value="">TODOS</option>
                                    <?php foreach ($inge as $key) { ?>
                                        <option value="<?= $key->TecnicoId; ?>"><?= $key->nombre; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <input type="hidden" id="fecha_inicio">
                                <input type="hidden" id="Fecha_fin">
                                <label for="date_range">Fecha:</label>
                                <input type="text" id="date_range" class="form-control">
                            </div>
                            <div class="col-lg-2">
                                <label for="txt-buscar">Folio:</label>
                                <input type="search" id="txt-buscar" class="form-control" placeholder="Buscar ...">
                                <div class="text-danger" id="error_txt-buscar"></div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php }
            if (session()->id_user == 1188) { ?>
                <section class="content-header bg-white" id="div-filtros-reports" style="margin-top: -1rem;display:none;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="direct_area">Area:</label>
                                <select class="form-control" id="direct_area" onchange="ObtenerInfomacion();">
                                    <option value="1">EPICOR</option>
                                    <option value="2">INFRAESTRUCTURA</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="date_range_reports">Fecha:</label>
                                <input type="hidden" id="fch-inicio">
                                <input type="hidden" id="fch-fin">
                                <input type="text" id="date_range_reports" class="form-control">
                            </div>
                            <div class="col-lg-3" style="text-align: start;padding-top: 2rem;">
                                <button id="btn_reset_range" class="btn btn-outline-dark btn-opcion" style="display:none;">Restablecer fecha</button>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>
        </div>
    </div>
    <section class="content">
        <div id="div_tablero">
            <div class="box-cards">
                <div class="card-html card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Nuevo(s)
                        </h3>
                        <div class="card-tools">
                            <span class="badge bg-secondary float-right" id="div-ntbl"></span>
                        </div>
                    </div>
                    <div class="card-body scrold">
                            <div class="form-group row">
                                <button type="button" id="btn-agregar" onclick="Agregar();" class="btn btn-block btn-outline-danger"><i class="fas fa-plus"></i> Agregar Ticket</button>
                            </div>
                        <div id="todo"></div>
                    </div>
                </div>
                <div class="card-html">
                    <div class="card-header" style="background-color: #F39C12;border-color: #F39C12;color:white;">
                        <h3 class="card-title">
                            En proceso
                        </h3>
                        <div class="card-tools">
                            <span class="badge float-right" style="background-color: #F39C12;border-color: #F39C12;color:white;" id="div-eptbl"></span>
                        </div>
                    </div>
                    <div class="card-body scrold" id="inprogress">
                    </div>
                </div>
                <div class="card-html card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            Concluido (s)
                        </h3>
                        <div class="card-tools">
                            <span class="badge bg-info float-right" id="div-ctbl"></span>
                        </div>
                    </div>
                    <div class="card-body scrold" id="completed">
                    </div>
                </div>
                <div class="card-html card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            Cerrado (s)
                        </h3>
                        <div class="card-tools">
                            <span class="badge bg-success float-right" id="div-cltbl"></span>
                        </div>
                    </div>
                    <div class="card-body scrold" id="closed">
                    </div>
                </div>
                <div class="card-html card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            Cancelado (s)
                        </h3>
                        <div class="card-tools">
                            <span class="badge bg-danger float-right" id="div-catbl"></span>
                        </div>
                    </div>
                    <div class="card-body scrold" id="cancelled">
                    </div>
                </div>
            </div>
        </div>

        <div id="div_reporte" style="display:none;">
            <section class="content pt-2" style="font-family: 'Roboto Condensed'; display: contents;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="far fa-star-half"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Nuevo (s)</span>
                                    <span class="info-box-number" id="div-nuevo"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon" style="background-color: #F39C12;border-color: #F39C12;color:white;"><i class="far fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">En proceso</span>
                                    <span class="info-box-number" id="div-proceso"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-star-half-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Concluido (s)</span>
                                    <span class="info-box-number" id="div-concluido"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cerrado (s)</span>
                                    <span class="info-box-number" id="div-cerrado"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="info-box" id="info_box_cumplimiento">
                            <div class="col-md-6" style="padding-top: 1rem;padding-left: 45%;">
                                <span class="info-box-icon bg-muted" style="font-size:40px;"><i class="far fa-calendar-check"></i></span>
                            </div>
                            <div class="info-box-content">
                                <span class="info-box-text">Cumplimiento</span>
                                <span class="info-box-number" id="div-cumplimiento" style="font-size:40px;margin-top:-20px;margin-bottom:-12px;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content pt-3">
                <div class="container-fluid" style="width:95%;">
                    <div class="row" style="width: 100%;">
                        <div class="col-md-6">
                            <div id="contenedor_uno">
                                <canvas id="grafico_uno" height="" width=""></canvas>
                            </div>
                        </div>
                        <div class="col-md-6 pt-4">
                            <div class="table-responsive" id="div-actividades">
                                <table class="table table-hover" id="tabla-actividades">
                                    <thead>
                                        <tr style="background-color:#999999;boder-color:#999999;color:white;">
                                            <th style="text-align:center;">
                                                No.
                                            </th>
                                            <th style="text-align:center;">
                                                Actividad
                                            </th>
                                            <th style="text-align:center;">
                                                Total
                                            </th>
                                            <th style="text-align:center;">
                                                Tiempo
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color:#F73633;boder-color:#F73633;color:white;">
                                            <td></td>
                                            <td></td>
                                            <td>Total:</td>
                                            <td>Promedio:</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php if (session()->manager_tickets != false /* || session()->id_user == 1063 */ || session()->id_user == 1188) { ?>
                <section class="content pt-4">
                    <div class="container-fluid" style="width:80%;">
                        <div class="row">
                            <div class=" col-12">
                                <div class="table-responsive" id="div-tecnicos">
                                    <table class="table table-hover" id="tabla-tecnicos">
                                        <thead>
                                            <tr style="background-color:#999999;boder-color:#999999;color:white;">
                                                <th style="text-align:center;">
                                                    Ingeniero
                                                </th>
                                                <th style="text-align:center;">
                                                    Cumplimiento
                                                </th>
                                                <th style="text-align:center;">
                                                    Tiempo
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background-color:#F73633;boder-color:#F73633;color:white;">
                                                <td></td>
                                                <td>Total:</td>
                                                <td>Promedio:</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>
        </div>
    </section>
</div>

<section>
    <div class="modal fade" id="nuevoTicketModal" role="dialog" aria-labelledby="nuevoTicketModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-ticket-alt nav-icon"></i>&nbsp;&nbsp;&nbsp;Nuevo Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_nuevo_ticket" method="post">
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <div class="form-row">
                            <div class="col-sm-5">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Area:</label>
                                <select class="form-control" id="sel-area" name="sel-area" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required>
                                    <option value="">Opciones..</option>
                                    <option value="1">EPICOR</option>
                                    <option value="2">INFRAESTRUCTURA</option>
                                    <!-- <option value="3">MANTENIMIENTO</option> -->
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Actividad:</label>
                                <select class="form-control" id="sel-actividad" name="sel-actividad" data-toggle="validation" data-required="true" data-message="Actividad." style="width: 100%;" required>
                                </select>
                            </div>
                            <div class="col-sm-12" style="margin-top: 10px;">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Descripción:</label>
                                <textarea class="form-control" id="txt-descripcion" name="txt-descripcion" rows="4" maxlength="500" required></textarea>
                            </div>
                        </div>
                        <?php if (session()->access_tickets == 1 || session()->access_tickets == 2) { ?>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label style="font-family: 'Roboto Condensed';font-size:15px;">Usuario:</label>
                                    <select class="form-control" id="sel-usuario" name="sel-usuario" style="width: 100%;">
                                        <option value="">Opciones...</option>
                                        <?php foreach ($usuarios as $key) { ?>
                                            <option value="<?= $key->id_user; ?>"><?= $key->nombre; ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="hidden" name="name_user" id="name_user">
                                </div>
                                <div class="col-sm-4">
                                    <label style="font-family: 'Roboto Condensed';font-size:15px;">Departamento:</label>
                                    <input type="hidden" name="id_depto" id="id_depto">
                                    <input type="text" class="form-control" name="sel-departamento" id="sel-departamento" style="width: 100%;" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <label style="font-family: 'Roboto Condensed';font-size:15px;">Ingeniero:</label>
                                    <select class="form-control" id="sel-tecnico" name="sel-tecnico" style="width: 100%;"></select>
                                </div>
                                <div class="col-sm-4" style="margin-top: 10px;">
                                    <label style="font-family: 'Roboto Condensed';font-size:15px;">Prioridad:</label>
                                    <select class="form-control" id="sel-agprioridad" name="sel-agprioridad" style="width: 100%;">
                                        <option value="">Opciones...</option>
                                        <option value="2">BAJA</option>
                                        <option value="3">MEDIA</option>
                                        <option value="4">ALTA</option>
                                    </select>
                                </div>
                                <div class="col-sm-4" style="margin-top: 10px;">
                                    <label style="font-family: 'Roboto Condensed';font-size:15px;">Estatus:</label>
                                    <select class="form-control" id="sel-agestatus" name="sel-agestatus" style="width: 100%;" onchange="solucion()">
                                        <option value="">Opciones...</option>
                                        <option value="1">Nuevo</option>
                                        <option value="2">En proceso</option>
                                        <option value="3">Concluido</option>
                                    </select>
                                </div>
                                <div class="col-sm-12" style="margin-top: 10px; display:none" id="div_solucion">
                                    <label style="font-family: 'Roboto Condensed';font-size:15px;">Solucion:</label>
                                    <textarea name="txt-solucion" id="txt-solucion" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                        <?php  } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_nuevo_ticket" name="editar_permiso" class="btn btn-guardar">Generar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="modal fade" id="detalleTicketModal" tabindex="-1" role="dialog" aria-labelledby="detalleTicketModalLabel" aria-hidden="true" data-backdrop='static' data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-ticket-alt nav-icon"></i>&nbsp;&nbsp;&nbsp;Detalles de Ticket</h5>
                    <button type="button" class="close" id="btn_cerrar_header" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="form-group row"> <i class="fas fa-align-left fa-2x" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Descripción</label> </div>
                                <div class="form-group row"> <textarea class="form-control"  id="txt-ddescripcion" rows="6" maxlength="450" readonly></textarea> </div>
                                <div id="div-txtsolucion" style="display:none;">
                                    <div class="form-group row"> <i class="fas fa-tasks fa-2x" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Solución</label> </div>
                                    <div class="form-group row"> <textarea class="form-control" style="height:7rem;" id="txt-solucion" rows="5" maxlength="450" readonly></textarea> </div>
                                </div>
                                <div class="form-group row"> <i class="fas fa-list fa-2x" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Chat</label> </div>
                                <form id="form_nuevo_comentario" method="post">
                                    <input type="hidden" id="id_Request" name="id_Request">
                                    <div id="div_nueva_accion"></div>
                                </form>
                                <div class="form-group row">
                                    <div class="timeline w-100" id="div-acciones"></div>
                                </div>
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-4">
                                <div class="form-group row"> <i class="fas fa-tag fa-lg" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Etiquetas</label> </div>
                                <div class="form-group row">
                                    <ul class="nav nav-pills flex-column" style="padding: 0">
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:black;"></i> Folio: <b style="margin-left:39px;" id="lbl-folio"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#2980B9;"></i> Fecha: <b style="margin-left: 34px;" id="lbl-clasificacion"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#2980B9;"></i> Hora: <b style="margin-left: 40px;" id="lbl-hora"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#DA1F1C;"></i> Prioridad: <b style="margin-left:18px;" id="lbl-prioridad"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#999999;"></i> Usuario: <b style="margin-left:27px;" id="lbl-usuario"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#DE37A1;"></i> Ingeniero: <b style="margin-left:20px;" id="lbl-tecnico"></b></a></li>
                                    </ul>
                                </div>
                                <div class="form-group row"> <i class="fas fa-list fa-lg" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Acciones</label> </div>
                                <div class="form-group row">
                                    <ul class="nav nav-pills flex-column">
                                        <?php if (session()->access_tickets == 1 || session()->access_tickets == 2) { ?>
                                            <li class="nav-item active"> <a href="#" class="nav-link" onclick="Reassign()" style="font-size: 13px;" id="hrf-reasign"> <i class="fas fa-undo-alt nav-icon"></i> Reasignar </a> </li>
                                            <li class="nav-item active"> <a href="#" class="nav-link" onclick="Priority()" style="font-size: 13px;" id="hrf-priority"> <i class="fas fa-project-diagram nav-icon"></i> Prioridad </a> </li>
                                            <li class="nav-item active"> <a href="#" class="nav-link" onclick="Status()" style="font-size: 13px;" id="hrf-status"> <i class="far fa-star-half nav-icon"></i> Estatus </a> </li>
                                        <?php } else { ?>
                                            <li class="nav-item active"> <button style="border:none;" class="btn btn-outline-danger" onclick="Cancel()" id="btn-cancel-users"> <i class="fas fa-exclamation-circle nav-icon"></i>Cancelar </button> </li>
                                        <?php } ?>
                                        <li class="nav-item active"> <a href="#" class="nav-link" onclick="Status()" style="font-size: 13px;" id="hrf-status-user"> <i class="far fa-star-half nav-icon"></i> Estatus </a> </li>
                                        <li class="nav-item active"> <a href="#" class="nav-link" onclick="Comment(2)" style="font-size: 13px;" id="hrf-file"> <i class="far fa-file-alt nav-icon"></i>Archivo <span class="badge bg-danger float-right p-2" id="div-tblfile" style="font-size: 10px;margin-left:45px;"></span> </a> </li>
                                        <li class="nav-item active"> <a href="#" class="nav-link" onclick="Comment(1)" style="font-size: 13px;" id="hrf-comment"> <i class="far fa-comment-dots nav-icon"></i> Comentario <span class="badge bg-secondary float-right p-2" id="div-tblcomment" style="font-size: 10px;margin-left:23px;"></span> </a> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="tecnico_id">
                        <input type="hidden" id="estatus_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn_cerrar" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="modal fade sl-modal" id="reasignarTicketModal" tabindex="-1" role="dialog" aria-labelledby="reasignarTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-undo-alt"></i> Reasignar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_reasignar" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="tecnico_id_ant" id="tecnico_id_ant">
                            <input type="hidden" name="id_request_reasignar" id="id_request_reasignar">
                            <label style="font-family: 'Roboto Condensed';font-size:15px;">Ingeniero:</label>
                            <select class="form-control" id="reasig-tecnico" name="reasig-tecnico" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_reasignar" name="editar_permiso" class="btn btn-guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="modal fade sl-modal" id="prioridadTicketModal" tabindex="-1" role="dialog" aria-labelledby="prioridadTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-project-diagram"></i>Prioridad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_prioridad" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id_request_prioridad" id="id_request_prioridad">
                            <label style="font-family: 'Roboto Condensed';font-size:15px;">Prioridad:</label>
                            <select class="form-control" id="update-agprioridad" name="update-agprioridad" style="width: 100%;" required>
                                <option value="">Opciones...</option>
                                <option value="2">BAJA</option>
                                <option value="3">MEDIA</option>
                                <option value="4">ALTA</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_prioridad" name="editar_permiso" class="btn btn-guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="modal fade sl-modal" id="estatusTicketModal" tabindex="-1" role="dialog" aria-labelledby="estatusTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="far fa-star-half" style="margin-right: 1rem;"></i> Estatus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_estatus" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id_request_estatus" id="id_request_estatus">
                            <label style="font-family: 'Roboto Condensed';font-size:15px;">Estatus:</label>
                            <select class="form-control" id="update-agestatus" name="update-agestatus" style="width: 100%;"></select>
                        </div>
                        <div class="row" id="div_solucion_modal" style="margin-top: 8px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_estatus" class="btn btn-guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="modal fade" id="permisosEditarModal" tabindex="-1" role="dialog" aria-labelledby="permisosEditarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Permisos<label id="articulo"></label></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editar_permisos" method="post">
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="tipo_personal">Folio</label>
                                <input type="text" class="form-control" id="editar_folio" name="editar_folio" value="" readonly>
                            </div>
                            <div class="form-group col-md-8">
                                <div class="form-group col-md-6">
                                    <label for="editar_usuario">Usuario</label>
                                    <input type="text" class="form-control" id="editar_usuario" readonly>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="editar_permiso_salida">Fecha de Salida</label>
                                <input type="date" class="form-control" id="editar_permiso_salida" name="editar_permiso_salida" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="editar_permiso_salida">Hora de Salida</label>
                                <input type="time" class="form-control" id="editar_permiso_salida_h" name="editar_permiso_salida_h" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="editar_permiso_entrada">Fecha de Entrada</label>
                                <input type="date" class="form-control" id="editar_permiso_entrada" name="editar_permiso_entrada" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="editar_permiso_entrada">Hora de Entrada</label>
                                <input type="time" class="form-control" id="editar_permiso_entrada_h" name="editar_permiso_entrada_h" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="editar_inasistencia_del">Inasistencia del</label>
                                <input type="date" class="form-control" id="editar_inasistencia_del" name="editar_inasistencia_del" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="editar_inasistencia_al">Inasistencia al</label>
                                <input type="date" class="form-control" id="editar_inasistencia_al" name="editar_inasistencia_al" value="">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="observaciones">Observaciones</label>
                                <textarea id="editar_observaciones" cols="10" rows="2" class="form-control" readonly></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="editar_permiso" name="editar_permiso" class="btn btn-guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="modal fade sl-modal" id="cancelarTicketModal" tabindex="-1" role="dialog" aria-labelledby="cancelarTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-circle" style="margin-right: 1rem;"></i>Cancelar Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_cancelar" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id_request_cancelar" id="id_request_cancelar">
                            <input type="hidden" name="update-agestatus" value="4">
                            <label style="font-family: 'Roboto Condensed';font-size:15px;">Motivo:</label>
                            <textarea class="form-control" name="txt-solucion-agestatus" rows="2" maxlength="450" data-toggle="validation" data-required="true" data-message="Solucion." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_cancelar" class="btn btn-guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/plugins/select2/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/tickets/administrar-tablero_v4.js"></script>
<?= $this->endSection() ?>