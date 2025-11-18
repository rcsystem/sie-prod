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
    .p-calif {
        font-size: 20px;
        margin-bottom: 0;
        margin-top: 4px;
    }
    .btn-calif{
        font-size: 75px;
    }

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
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h1 style="margin-bottom:1rem;">Tickets Mantenimiento</h1>
                        </div>
                    </div>
                </div>
            </section>
            <?php if (session()->access_tickets == 3) { ?>
                <section class="content-header bg-white" style="margin-top: -1rem;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="sel-filtro-prioridad">Prioridad:</label>
                                <select class="form-control" id="sel-filtro-prioridad" onchange="BuscarTickets();">
                                    <option value="">TODAS</option>
                                    <option value="1">BAJA</option>
                                    <option value="2">MEDIA</option>
                                    <option value="3">ALTA</option>
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
            <?php } ?>
        </div>
    </div>
    <div class="content">
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
                    <?php if (session()->access_tickets != 3) { ?>
                        <div class="form-group row">
                            <button type="button" id="btn-agregar" onclick="Agregar();" class="btn btn-block btn-outline-danger"><i class="fas fa-plus" style="margin-right:5px;"></i>Generar Ticket</button>
                        </div>
                        <div class="form-group row">
                            <button type="button" onclick="solicitarCalificacion();" class="btn btn-block btn-outline-success"><i class="fas fa-plus" style="margin-right:5px;"></i>CALIFICAR</button>
                        </div>
                    <?php } ?>
                    <div id="todo"></div>
                </div>
            </div>
            <div class="card-html card-muted">
                <div class="card-header">
                    <h3 class="card-title">
                        Autorizados(s)
                    </h3>
                    <div class="card-tools">
                        <span class="badge bg-secondary float-right" id="div-auto"></span>
                    </div>
                </div>
                <div class="card-body scrold">
                    <div id="autorizado"></div>
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
</div>

<section>
    <div class="modal fade" id="nuevoTicketModal" role="dialog" aria-labelledby="nuevoTicketModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" style="max-width: 800px !important;">
            <div class="modal-content modal-lg" style="margin-top: 20%;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-ticket-alt nav-icon"></i>&nbsp;&nbsp;&nbsp;Nuevo Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_nuevo_ticket" method="post">
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Area:</label>
                                <select class="form-control" id="sel-area-equipo" name="sel-area-equipo" onchange="machineData(1)" required>
                                    <option value="1">PLANTA INDUSTRIAL DE VALVULAS</option>
                                    <option value="2">VILLAHERMOSA</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Tipo de Servicio:</label>
                                <select class="form-control" id="sel-tipo-serv-equipo" name="sel-tipo-serv-equipo" required>
                                    <option value="1">MANTENIMIENTO</option>
                                    <!-- <option value="">Opciones..</option> -->
                                    <!-- <option value="2">SERVICIOS GENERALES</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-sm-6" id="div_clave_1">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Clave de Maquina:</label>
                                <select class="form-control" id="sel-tipo-equipo" name="sel-tipo-equipo" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" onchange="machineData(2)" required></select>
                            </div>
                            <div class="col-sm-6" id="div_opcion1">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Maquina:</label>
                                <select class="form-control" id="sel-clave" name="sel-clave" data-toggle="validation" data-required="true" data-message="Area." style="width: 100%;" required></select>
                            </div>
                            <div class="col-sm-6" id="div_opcion2">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Especifica:</label>
                                <input type="text" name="sel-otro" id="sel-otro" class="form-control">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-sm-6">
                                <label for="sel-mantenimiento">Tipo de Mantenimiento:</label>
                                <select id="sel-mantenimiento" name="sel-mantenimiento" class="form-control" required>
                                    <option value="">Opciones..</option>
                                    <?php foreach ($actvidad as $key) { ?>
                                        <option value="<?= $key->ActividadId; ?>"><?= $key->Actividad_Actividad; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="sel-codigo">Código de Falla:</label>
                                <select id="sel-codigo" name="sel-codigo" class="form-control" required>
                                    <option value="">Opciones..</option>
                                    <?php foreach ($codigos as $key) { ?>
                                        <option value="<?= $key->id_fail; ?>"><?= $key->name_fail; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-sm-12">
                                <label>Descripcion de Trabajo:</label>
                                <textarea class="form-control" id="txt-descripcion" name="txt-descripcion" rows="4" maxlength="450" required></textarea>
                            </div>
                        </div>
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
                                <div class="row" style="margin-left: -14px;margin-right: -14px;">
                                    <div class="col-md-12">
                                        <label style="font-family: 'Roboto Condensed';font-size:15px;">Clave de Maquina:</label>
                                        <input type="text" class="form-control" id="txt_detalle_equipo" readonly>
                                    </div>
                                </div>
                                <div class="row" style="margin-left: -14px;margin-right: -14px;margin-top: 1rem;">
                                    <div class="col-md-6">
                                        <label style="font-family: 'Roboto Condensed';font-size:15px;">Tipo de Mantenimiento:</label>
                                        <input type="text" class="form-control" id="txt_detalle_mante" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-family: 'Roboto Condensed';font-size:15px;">Codigo de Falla:</label>
                                        <input type="text" class="form-control" id="txt_detalle_falla" readonly>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 1rem;">
                                    <i class="fas fa-align-left fa-2x" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Descripción</label>
                                    <textarea class="form-control" style="height:6rem;" id="txt-ddescripcion" rows="4" maxlength="450" readonly></textarea>
                                </div>
                                <div class="form-group row" style="margin-left: -14px;margin-right: -14px;margin-top: 1rem;">
                                    <div class="col-md-6" id="div_date_star_procces">
                                        <label>Inicio de Proceso: </label>
                                        <input type="datetime" id="date_star_procces" value="" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6" id="div_date_end_procces">
                                        <label>Final de Proceso: </label>
                                        <input type="datetime" id="date_end_procces" value="" class="form-control" readonly>
                                    </div>
                                </div>
                                <div id="icon_refacciones"><i class="fas fa-toolbox fa-2x" style="color:#999999;padding-right: 10px;margin-top:1rem"></i><label style="font-size: 17px;">Solicitud de Refacción</label></div>
                                <div id="div_add_refacciones"></div>
                                <div id="div_refacciones"></div>
                                <div id="div-txtsolucion" class="form-group row" style="display:none; margin-top: 1rem;">
                                    <i class="fas fa-tasks fa-2x" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Trabajos Realizados</label>
                                    <textarea class="form-control" style="height:6rem;" id="txt-solucion" rows="4" maxlength="450" readonly></textarea>
                                    <label style="font-family: 'Roboto Condensed';font-size:15px;margin-top: 1rem;">Codigo de Causa:</label>
                                    <input type="text" class="form-control" id="txt_detalle_causa" readonly>
                                </div>
                                <div id="div-txtcancel" class="form-group row" style="display:none; margin-top: 1rem;">
                                    <div class="col-md-6">
                                        <i class="fas fa-exclamation-circle fa-2x" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Motivo de Cancelacion</label>
                                    </div>
                                    <div class="col-md-6" style="text-align: end;padding-top: 12px;">
                                        <i class="far fa-clock nav-icon"></i><label id="fecha_cancelado" style="font-size: 12px;"></label>
                                    </div>
                                    <textarea class="form-control" style="height:6rem;" id="txt-cancel" rows="4" maxlength="450" readonly></textarea>
                                </div>
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
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#2980B9;"></i> Fecha: <b style="margin-left: 34px;" id="lbl-fecha"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#2980B9;"></i> Hora: <b style="margin-left: 40px;" id="lbl-hora"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#DA1F1C;"></i> Prioridad: <b style="margin-left:18px;" id="lbl-prioridad"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#999999;"></i> Usuario: <b style="margin-left:27px;" id="lbl-usuario"></b></a></li>
                                        <li><a href="#" class="nav-link" style="font-size: 13px;"><i class="fa fa-circle" style="color:#DE37A1;"></i> Tecnico: <b style="margin-left:20px;" id="lbl-tecnico"></b></a></li>
                                        <?php /* if (session()->access_tickets == 3 && session()->manager_tickets != false) { */ ?>
                                        <li id="li_pdf" style="text-align: center;"></li>
                                        <?php /* } */ ?>
                                    </ul>
                                </div>
                                <div class="form-group row" id="div_titulo_acciones"> <i class="fas fa-list fa-lg" style="color:#999999;padding-right: 10px;"></i><label style="font-size: 17px;">Acciones</label> </div>
                                <div class="form-group row">
                                    <ul class="nav nav-pills flex-column">
                                        <?php if (session()->authorize_tickets_mante == true) { ?>
                                            <li class="nav-item active"> <button style="border:none;" class="btn btn-outline-success" onclick="changeStatusForUser(2)" id="btn-authorize-users"> <i class="fas fa-exclamation-circle nav-icon"></i>Autorizar </button> </li>
                                        <?php } ?>
                                        <?php if (session()->access_tickets == 3) {
                                            if (session()->manager_tickets != false) { ?>
                                                <li class="nav-item active"> <button style="border:none;" class="btn btn-outline-dark" onclick="requestSparePart()" id="btn-request-spare-part-admin"> <i class="fas fa-toolbox nav-icon"></i>Solicitud de Refacción </button> </li>
                                                <li class="nav-item active"> <button style="border:none;" class="btn btn-outline-warning" onclick="changeStatusForAdmin(3)" id="btn-proces-admin"> <i class="fas fa-play-circle nav-icon"></i>Iniciar Proceso </button> </li>
                                            <?php } ?>
                                            <li class="nav-item active"> <button style="border:none;" class="btn btn-outline-info" onclick="changeStatusForAdmin(4)" id="btn-conclud-admin"> <i class="fas fa-stop-circle nav-icon"></i>Concluir Proceso</button> </li>
                                        <?php } else { ?>
                                            <li class="nav-item active"> <button style="border:none;" class="btn btn-outline-success" onclick="changeStatusForUser(5)" id="btn-clossed-users"> <i class="fas fa-user-check nav-icon"></i>Aceptar </button> </li>
                                        <?php } ?>
                                        <li class="nav-item active"> <button style="border:none;" class="btn btn-outline-danger" onclick="changeStatusForUser(0)" id="btn-cancel-users"> <i class="fas fa-check-circle nav-icon"></i>Cancelar </button> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- <input type="hidden" id="tecnico_id"> -->
                        <input type="hidden" id="id_Request">
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
    <div class="modal fade sl-modal" id="asignarTicketModal" role="dialog" aria-labelledby="detalleTicketModalLabel" aria-hidden="true" data-backdrop='static' data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-undo-alt"></i> Asignar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_asignar" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Tecnico:</label>
                                <select class="form-control select2bs4 select2-hidden-accessible" style="width: 100%; height: calc(2.25rem + 2px);" id="sel-asignar-tecnico" name="sel-asignar-tecnico" required>
                                    <option value="">Opciones...</option>
                                    <?php foreach ($inge as $key) { ?>
                                        <option value="<?= $key->TecnicoId; ?>"><?= $key->nombre; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="sel-filtro-prioridad">Prioridad:</label>
                                <select class="form-control" id="sel-asignar-prioridad" name="sel-asignar-prioridad" required>
                                    <option value="">Opciones...</option>
                                    <option value="1">BAJA</option>
                                    <option value="2">MEDIA</option>
                                    <option value="3">ALTA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_asignar" name="editar_permiso" class="btn btn-guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="modal fade sl-modal" id="concluirTicketModal" tabindex="-1" role="dialog" aria-labelledby="detalleTicketModalLabel" aria-hidden="true" data-backdrop='static' data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="far fa-star-half"></i> Concluir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_concluir" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="sel-filtro-prioridad">Codigo de Causa:</label>
                                <select class="form-control" id="sel-concluir-causa" name="sel-concluir-causa" required>
                                    <option value="">Opciones...</option>
                                    <option value="Mala Reparacion">Mala Reparacion</option>
                                    <option value="Calidad de Refaccion">Calidad de Refaccion</option>
                                    <option value="Mala Operacion">Mala Operacion</option>
                                    <option value="Desgaste Natural">Desgaste Natural</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-lg-12" id="div_concluir_otra_causa" style="margin-top: 1rem;">
                                <label for="sel-filtro-prioridad">Otra Causa:</label>
                                <input type="text" name="sel-concluir-otro-causa" id="sel-concluir-otro-causa" class="form-control">
                            </div>
                            <div class="col-lg-12" style="margin-top: 1rem;">
                                <label for="sel-filtro-prioridad">Trabajos Realizados:</label>
                                <textarea name="txt-concluir-realizado" id="txt-concluir-realizado" cols="30" rows="4" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_concluir" name="editar_permiso" class="btn btn-guardar">Guardar</button>
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
                        <div class="row col-md-12">
                            <label style="font-family: 'Roboto Condensed';font-size:15px;">Motivo:</label>
                            <textarea class="form-control" id="txt-cancelar-motivo" name="txt-cancelar-motivo" rows="2" maxlength="450" data-toggle="validation" data-required="true" data-message="Motivo de cancelacion." required></textarea>
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

<section>
    <div class="modal fade sl-modal" id="solicitarTicketModal" role="dialog" aria-labelledby="detalleTicketModalLabel" aria-hidden="true" data-backdrop='static' data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-toolbox nav-icon"></i> Solicitar Refaccion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_solicitar" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Número de Requisición:</label>
                                <input type="text" class="form-control" id="orden_compra" name="orden_compra" required>
                            </div>
                            <div class="col-lg-6">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Código de Pieza:</label>
                                <input type="text" class="form-control" id="orden_codigo" name="orden_codigo" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Nombre del Comprador:</label>
                                <input type="text" class="form-control" id="orden_nombre" name="orden_nombre" required>
                            </div>
                            <div class="col-lg-6">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Fecha Contemplada:</label>
                                <input type="date" class="form-control" id="orden_fecha" name="orden_fecha" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Cantidad de Piezas:</label>
                                <input type="number" min="1" class="form-control" id="cant_pz" name="cant_pz" required>
                            </div>
                            <div class="col-lg-4">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Consto Unitario:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" min="0.00" step="0.01" inputmode="decimal" class="form-control" id="costo_unitario" name="costo_unitario" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label style="font-family: 'Roboto Condensed';font-size:15px;">Monto:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" min="0.00" step="0.01" inputmode="decimal" class="form-control" id="monto" name="monto" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="btn_solicitar" class="btn btn-guardar">Guardar</button>
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
<script src="<?php echo base_url(); ?>/public/js/tickets/administrar_mantenimiento_tablero.js"></script>
<!-- <script src="<?php echo base_url(); ?>/public/js/tickets/administrar-tablero_v1.js"></script> -->
<?= $this->endSection() ?>