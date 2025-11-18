<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
DashBoard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/animate.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/fixedColumns.dataTables.min.css" rel="stylesheet" />

<style>
    .nav-pills .nav-link {
        color: #cdcdcd;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: #fff;
        background-color: #b90202;
    }

    .nav-pills .nav-link:not(.active):hover {
        color: #b90202;
    }

    .card-title {
        margin-top: 5px;
    }

    /* .content-select select{
	appearance: none;
	-webkit-appearance: none;
	-moz-appearance: none;
    } */
    /* .content-select select::-ms-expand {
    display: none;
    } */
    .content-select select {
        margin-top: 5px;
        margin-bottom: -1rem;
        cursor: pointer;
        width: 100%;
        cursor: pointer;
        height: 38px;
        outline: none;
        border-radius: 2px;
        background: #b90202;
        font-size: 1rem;
        border: 1px solid #b90202;
        border-radius: 0.25em;
        color: #FFF;
        padding: 7px 7px 7px 10px;
        position: relative;
        transition: all 0.25s ease;
        text-align: center;
    }

    .content-select select:hover {
        background: #b90202;

    }

    .content-select select option:focus {
        background: #b90202;
        box-shadow: #b90202;

    }

    .content-select select option {
        border: 1px solid #FFFFFF !important;
        background: #343A40;
    }


    /* Estilos para motores Webkit y blink (Chrome, Safari, Opera... )*/


    .contenedor::-webkit-scrollbar {
        -webkit-appearance: none;
        width: 8px;
    }

    .contenedor::-webkit-scrollbar:vertical {
        width: 2px;
    }

    .contenedor::-webkit-scrollbar-button:increment,
    .contenedor::-webkit-scrollbar-button {
        display: none;
    }

    .contenedor::-webkit-scrollbar:horizontal {
        height: 1px;
    }

    .contenedor::-webkit-scrollbar-thumb {
        background-color: rgb(211, 211, 211, 0.9);
        ;
        border-radius: 2px;
        border: 1px solid rgb(211, 211, 211, 0.9);
    }

    .contenedor::-webkit-scrollbar-track {
        border-radius: 0;
        background: #343A40;
    }

    .image-user {
        margin-top: 0.5rem;
        margin-left: 1rem;
        margin-right: 1rem;
        width: 50px;
        height: 50px;
    }

    .total_permisos {
        margin-left: auto;
        margin-top: 0.5rem;
        margin-right: 0.8rem;
    }

    .modal.fade {
        opacity: 1;
    }

    .modal.fade .modal-dialog {
        -webkit-transform: translate(0);
        -moz-transform: translate(0);
        transform: translate(0);
    }

    /* Ensure that the demo table scrolls */

    .card-body {
        overflow-x: auto;
        /* Permite el desplazamiento horizontal si la tabla es muy ancha */
        max-width: 100%;
        /* Evita que el contenido sobresalga */
    }



    th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        width: 99%;
        margin: 0 auto;
    }

    table.dataTable tbody tr>.dtfc-fixed-left,
    table.dataTable tbody tr>.dtfc-fixed-right {
        z-index: 1;
        background-color: #eee;

    }

    .remarcar {
        font-weight: bold;
    }

    .dtfc-right-top-blocker {
        display: none !important;
    }

    .dataTables_scrollBody::-webkit-scrollbar:vertical {
        width: 9px;
    }

    .dataTables_scrollBody::-webkit-scrollbar {
        /* width: 8px; */
        height: 8px;
    }

    .dataTables_scrollBody::-webkit-scrollbar-track {
        background: rgba(241, 241, 241, .12);
    }

    .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background-color: rgba(0.75, 0.75, 0.75, 0.4);
        border-radius: 5px;
        /* border: 3px solid #474D54; */
    }

    .my-col-rq {
        flex: 0 0 20%;
        max-width: 20%;
        position: relative;
        width: 100%;
        padding-right: 7.5px;
        padding-left: 7.5px;
    }

    .my-col-it {
        flex: 0 0 13.3333333333%;
        max-width: 13.3333333333%;
        position: relative;
        width: 100%;
        padding-right: 7.5px;
        padding-left: 7.5px;
    }

    /*reloj de accidentes */

    .body-reloj {
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
    }

    .containers {
        display: flex;
        justify-content: space-around;
        /* Distribuye los marcadores con espacio entre ellos */
        align-items: center;
        background-color: #303030;
        padding: 11px;
        border: 5px solid #303030;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.5);
        width: 700px;
        /* Aumentamos el ancho del contenedor para los dos marcadores */
        margin-bottom: 1.5rem;
        border-radius: 8px;
    }

    .panel {
        text-align: center;
        margin: 0 20px;
        /* Separación horizontal entre los paneles */
    }

    .text {
        color: white;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .number {
        color: red;
        font-size: 30px;
        font-weight: bold;
        text-shadow: 0 0 10px rgba(255, 0, 0, 0.8);
        /* Simula el brillo del led */
        display: inline-block;
        background-color: black;
        padding: 10px;
        border-radius: 10px;
        width: 70px;
        /* Ancho fijo para mantener el formato */
    }

    .buttons {
        margin-top: 8px;
    }

    .buttons button {

        margin: 3px;
        font-size: 14px;
        background-color: #008CBA;
        /* Botón azul */
        color: white;
        border: none;
        border-radius: 2px;
        cursor: pointer;
    }

    .buttons button:hover {
        background-color: #005f73;
        /* Cambia el color cuando pasa el mouse */
    }


    .animated-card {
        z-index: 20;
        opacity: 0;
        transform: translateY(20px) scale(1);
        transition:
            transform 0.2s ease-out,
            opacity 0.6s ease-out;
    }

    .animated-card.visible {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .animated-card:hover {
        transform: translateY(-5px) scale(1.05);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Estado inicial: invisible y desplazado arriba */
    .slide-header {
        opacity: 0;
        transform: translateY(-40px);
    }

    /* Al añadir .visible ejecuta la animación */
    .slide-header.visible {
        animation: slideDown 0.6s ease-out forwards;
    }
</style>

<style>
    .greeting-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .greeting-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    }

    .greeting-text {
        display: flex;
        flex-direction: column;
    }

    #dynamic-greeting {
        font-size: 1.8rem;
        font-weight: 700;
        color:#ef3043
    }


</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center"
                style="display:flex; justify-content:space-between; align-items:center; width:100%;">

                <!-- Logo -->
                <div class="d-flex align-items-center">
                   <!--  <div class="text-center">
                        <img src="<?= base_url() ?>/public/images/logo_Walworth.png" alt="Walworth Logo" style="height:40px; margin-right:1px;">
                    </div> -->

                    <!-- Saludo + Nombre + Mensaje -->
                    <div style="margin-left: 1rem;margin-right: 3rem;" class="align-items-center">
                        <h3 id="dynamic-greeting" style="margin:0; font-weight:700;">¡Buenos días!</h3>
                        <p id="greeting-message" style="margin:0; font-size:19px;font-weight:400;">
                            <b><?= session()->name ?? 'Usuario' ?>, que tengas un gran día.</b>
                        </p>
                    </div>

                </div>

                <!-- Fecha y hora -->
                <div class="text-right" style="min-width:200px;">
                    <small id="date-display"><?= date('l, j F Y') ?></small><br>
                    <span id="current-time" style="font-size:1.5rem; font-weight:bold;color:#1e5593">00:00</span>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <?php
    if ($info->vacation > 8) {
        $color = 'primary';
    } else if ($info->vacation > 5) {
        $color = 'warning';
    } else if ($info->vacation >= 0) {
        $color = 'danger';
    } else {
        $color = 'secondary';
    }
    $ruteTag = ($info->tag == 'VER MIS VEHICULOS') ? "href='https://sie.grupowalworth.com/estacionamiento/registar-mi-vehiculo'" : '';
    // var_dump($ruteTag);
    // echo $ruteTag;
    /*  
                            echo $vars= session()->id_user ;
                            echo "<br>";
                            echo session()->name ;
                            echo "<br>";
                            echo session()->surname ;
                            echo "<br>";
                            echo session()->profile_google ;
                            echo "<br>rol:";
                            echo session()->id_rol ;
                            echo "<br>depto_id";
                            echo session()->id_depto ;
                            echo "<br>puestos";
                            echo session()->job_positions ;
                            echo "<br>departamento";
                            echo session()->departament ;
                            echo "<br> paynumber";
                            echo session()->payroll_number ;
                            echo "<br>";
                            echo session()->date_admission ;
                            echo "<br> tipo de empleado:";
                            echo session()->type_of_employee ;
                            echo "<br> centro costo:";
                            echo session()-> cost_center ;
                            echo "<br>";
                            echo session()->  is_logged ;
                            echo "<br>"; 
                        */

    ?>
    <!-- Main content -->
    <section class="content">
        <input type="hidden" id="id_users" value="<?= session()->id_user; ?>">
        <!-- <div class="container-fluid">
            <img class="img-fluid" src="<?= base_url() ?>/public/images/WW-180Logo.png" alt="" style="display:block;margin:auto;width:42% !important;">
        </div> -->

        <div class="row">
            <!--    <div class="col-lg-4">
                <a <?php //$ruteTag 
                    ?>>
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"> <i class="fas fa-id-card-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Marbete:</span>
                            <H2 style="margin-top: 10px;"><?php //$info->tag; 
                                                            ?></H2>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a href="https://sie.grupowalworth.com/permisos/mis-permisos">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"> <i class="far fa-calendar-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Mis Permisos Personales de este mes:</span>
                            <H2 style="margin-top: 10px;"><?php //$info->permiss; 
                                                            ?></H2>
                        </div>
                    </div>
                </a>
            </div> -->


            <div class=" col-md-8">
                <div style="text-align: center; margin-bottom: 30px;" class="col-lg-12 slide-header">

                    <h3 style="font-size: 30px; font-weight: bold; margin-bottom: 5px;color:#f73a3a;">
                        🏅 Top 3 Colaboradores
                    </h3>
                    <p style="font-size: 13px; color: #6c757d; margin-bottom: 20px;">
                        Con más insignias este año
                    </p>

                    <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">

                        <?php
                        $delay = 0;
                        foreach ($insignias as $insignia) {

                        ?>


                            <!-- Tarjeta 1 -->
                            <div class="animated-card" style="cursor:pointer;background: #f8f9fa; padding: 15px 20px; border-radius: 15px; width: 180px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                <div style="font-size: 14px; font-weight: bold; color:darkcyan;"><?= ucwords(strtolower($insignia->user_name)) ?></div>
                                <div style="font-size: 10px; color: #666;"><?= $insignia->departament ?></div>
                                <div style="font-size: 38px; font-weight: bold; margin-top: 0px; color: #2c3e50;"><?= $insignia->total_medallas ?></div>
                                <div style="font-size: 12px; color: #888;">Insignias</div>
                            </div>
                        <?php

                            $delay += 0.15; // aumenta 150ms por tarjeta
                        } ?>


                    </div>
                </div>
            </div>
            <div class="col-lg-4 body-reloj">
                <div class="containers">
                    <div class="panel">
                        <div class="text">DÍAS SIN ACCIDENTES</div>
                        <div class="number" id="dias-counter">0</div>
                        <?php if (session()->id_user == 1 || session()->id_user == 1292 || session()->id_user == 75  || session()->id_user == 112 || session()->id_user == 1330) { ?>
                            <div class="buttons">
                                <button onclick="changeCounter('dias-counter', 'increment')"><i class="fas fa-plus"></i></button>
                                <button onclick="changeCounter('dias-counter', 'decrement')"><i class="fas fa-minus"></i></button>
                                <button onclick="resetCounter()"><i class="fas fa-undo-alt"></i></i></button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="panel">
                        <div class="text">RECORD SIN ACCIDENTES</div>
                        <div class="number" id="record-counter">0</div>
                        <?php if (session()->id_user == 1 || session()->id_user == 1292 || session()->id_user == 75 || session()->id_user == 112 || session()->id_user == 1330) { ?>
                            <div class="buttons">
                                <button onclick="changeCounter('record-counter', 'increment')"><i class="fas fa-plus"></i></button>
                                <button onclick="changeCounter('record-counter', 'decrement')"><i class="fas fa-minus"></i></button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (session()->id_user == 1 || session()->id_user == 1063 || session()->id_user == 854 || session()->id_user == 75 || session()->id_user == 1202) { ?>
            <!--   
            <div class="row">
                <div class="my-col-rq">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <B>Marbetes</B>
                            <H2 style="margin-top: 10px;"><?php //$allTags; 
                                                            ?></H2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="small-box" style="background-color: #7DCD67;">
                        <div class="inner">
                            <B>Automóviles</B>
                            <H2 style="margin-top: 10px;"><?php //$tags[1]; 
                                                            ?></H2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-car-side"></i>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="small-box" style="background-color: #F99403;">
                        <div class="inner">
                            <B>Motocicletas</B>
                            <H2 style="margin-top: 10px;"><?php //$tags[2]; 
                                                            ?></H2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="small-box" style="background-color: #D36B7B;">
                        <div class="inner">
                            <B>Bicicletas</B>
                            <H2 style="margin-top: 10px;"><?php //$tags[3]; 
                                                            ?></H2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bicycle"></i>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="small-box" style="background-color: #6BA7D3;">
                        <div class="inner">
                            <B>Nave 3</B>
                            <H2 style="margin-top: 10px;"><?php //$tags[4]; 
                                                            ?></H2>
                        </div>
                        <div class="icon">
                            <i class="fas">N3</i>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="small-box" style="background-color: #2EC903;">
                        <div class="inner">
                            <B>Jardines</B>
                            <H2 style="margin-top: 10px;"><?php //$tags[5]; 
                                                            ?></H2>
                        </div>
                        <div class="icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                    </div>
                </div>
                <div class="my-col-it">
                    <div class="small-box" style="background-color: #CB6BD3;">
                        <div class="inner">
                            <B>Nave 1</B>
                            <H2 style="margin-top: 10px;"><?php //$tags[6]; 
                                                            ?></H2>
                        </div>
                        <div class="icon">
                            <i class="fas">N1</i>
                        </div>
                    </div>
                </div>
            </div> 
            -->
            <div class="row">


                <!--    <div style="text-align: center; margin-bottom: 30px;" class="col-lg-12">

                    <h3 style="font-size: 30px; font-weight: bold; margin-bottom: 5px;">
                        🏅 Top 3 Colaboradores
                    </h3>
                    <p style="font-size: 13px; color: #6c757d; margin-bottom: 20px;">
                        Con más insignias este año
                    </p>

                    <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                        
                        <div style="background: #f8f9fa; padding: 15px 20px; border-radius: 15px; width: 180px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <div style="font-size: 18px; font-weight: bold;">Juan Pérez</div>
                            <div style="font-size: 12px; color: #666;">Talento Humano</div>
                            <div style="font-size: 34px; font-weight: bold; margin-top: 8px; color: #2c3e50;">15</div>
                            <div style="font-size: 11px; color: #888;">Insignias</div>
                        </div>

                        
                        <div style="background: #f8f9fa; padding: 15px 20px; border-radius: 15px; width: 180px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <div style="font-size: 18px; font-weight: bold;">Laura Méndez</div>
                            <div style="font-size: 12px; color: #666;">Calidad</div>
                            <div style="font-size: 34px; font-weight: bold; margin-top: 8px; color: #2c3e50;">12</div>
                            <div style="font-size: 11px; color: #888;">Insignias</div>
                        </div>

                        
                        <div style="background: #f8f9fa; padding: 15px 20px; border-radius: 15px; width: 180px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <div style="font-size: 18px; font-weight: bold;">Carlos López</div>
                            <div style="font-size: 12px; color: #666;">Compras</div>
                            <div style="font-size: 34px; font-weight: bold; margin-top: 8px; color: #2c3e50;">10</div>
                            <div style="font-size: 11px; color: #888;">Insignias</div>
                        </div>
                    </div>
                </div> -->

            </div>

        <?php } ?>

        <div class="row">
            <?php // if ( session()->type_of_employee == 1 ) { 
            ?>
            <section class="col-lg-8 connectedSortable">
                <div class="card">
                    <div class="card-header ui-sortable-handle d-flex align-items-center" style="cursor: move;">

                        <input type="text" id="searchInput" class="form-control form-control-sm mr-3" placeholder="Buscar..." style="max-width: 200px;">
                        <h3 class="card-title mr-3">
                            <i class="fas fa-address-card mr-1"></i>
                            Directorio
                        </h3>
                        <div class="card-tools ml-auto">
                            <ul class="pagination pagination-sm m-0">
                                <!-- Paginación va aquí -->
                            </ul>
                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered" id="directoryTable">
                            <thead>
                                <tr style="font-size:13px;font-family: 'Roboto Condensed';">

                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Extensión</th>
                                    <th>Email</th>
                                    <th>Departamento</th>
                                    <th>Número Directo (DID)</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                <!-- Se llenará dinámicamente con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <!--  <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add item</button> -->
                    </div>
                </div>
            </section>

            <?php //} 
            ?>
            <?php if (session()->id_user == 1 || session()->id_user == 26 || session()->id_user == 294 || session()->id_user == 92) { ?>
                <!-- Left col -->
                <section class="col-lg-4 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card">
                        <div class="card-header">
                            <h3 id="activo-card" class="card-title sie-font-bold">
                                <i class="fas fa-paste mr-1"></i>
                                Activo Fijo
                            </h3>
                            <div id="tools-card" class="card-tools content-select">
                                <div id="lista-activos"></div>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div id="content-activos" class="tab-content p-0">

                                <div class="chart tab-pane active" id="revenue-chart-0" style="display: inline-block; width: 100%;position: relative; height: 300px;">
                                    <!-- Asegúrate de que el ID coincida entre el HTML y el JS -->
                                    <canvas id="activo-chart-canvas-0" height="300" style="height: 300px;"></canvas>
                                </div>

                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </section>

                <!-- /.Left col -->
            <?php } ?>
            <?php if (
                session()->id_user == 92 || session()->id_user == 151 || session()->id_user == 37 || session()->id_user == 50 || session()->id_user == 627 || session()->id_user == 267 ||
                session()->id_user == 905 || session()->id_user == 251 || session()->id_user == 906 || session()->id_user == 374 || session()->id_user == 159 ||
                session()->id_user == 252 || session()->id_user == 1188 || session()->id_user == 1 || session()->id_user == 1063 || session()->id_user == 265 ||
                session()->id_user == 250 || session()->id_user == 592 || session()->id_user == 262  || session()->id_user == 107 || session()->id_user == 852 || session()->id_user == 1390
                || session()->id_user == 854
            ) { ?>
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card">
                        <div class="card-header">
                            <h3 id="permiso-card" class="card-title sie-font-bold">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Permisos por Departamentos - <?= date('Y'); ?>
                            </h3>

                            <div id="tools-card" class="card-tools content-select">
                                <!-- <select name="" id="" class="form-control">
                        <option value="">Seleccionar</option>
                    </select> -->
                                <ul id="list_deptos" class="nav nav-pills ml-auto">
                                    <!-- <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Deptos</a>
                    </li> -->
                                    <!--  <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li> -->
                                </ul>
                                <div id="lista-deptos"></div>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div id="content-deptos" class="tab-content p-0">
                                <!-- Morris chart - Sales -->
                                <!--  <div class="chart tab-pane " id="revenue-chart"
                       style="position: relative; height: 300px;">
                      <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
                   </div>
                  <div id="sales-chart" class="chart tab-pane" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                  </div> -->
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </section>
                <!-- /.Left col -->
            <?php } ?>




            <?php if (
                session()->id_user == 1 || session()->id_user == 592 || session()->id_user == 151 ||
                session()->id_user == 250 || session()->id_user == 1063 || session()->id_user == 252 ||
                session()->id_user == 262 || session()->id_user == 1188 || session()->id_user == 265 ||
                session()->id_user == 374  || session()->id_user == 107
            ) { ?>
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card">
                        <div class="card-header">
                            <h3 id="permiso-card" class="card-title sie-font-bold">
                                <i class="fas fa-table mr-1"></i>
                                Todos los Permisos - <?= date('Y'); ?>
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body" style="font-family:'Roboto Condensed'">

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive" id="div-tabla-requerimientos">

                                        <table class="table table-striped nowrap" id="tabla_general_permisos" style="width: 100%;">

                                            <thead style="font-size:15px;">
                                                <tr style="background-color:#154360;boder-color:#154360;color:white;">
                                                    <th style="background-color:#154360;boder-color:#154360;color:white;">
                                                        <center>Departamento<center>
                                                    </th>
                                                    <th>
                                                        <center>Area Operativa<center>
                                                    </th>
                                                    <th>
                                                        <center>Total Permisos<center>
                                                    </th>
                                                    <th>
                                                        <center>Ene Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Ene Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Ene Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Feb Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Feb Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Feb Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Mar Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Mar Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Mar Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Abr Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Abr Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Abr Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>May Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>May Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>May Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Jun Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Jun Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Jun Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Jul Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Jul Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Jul Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Ago Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Ago Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Ago Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Sep Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Sep Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Sep Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Oct Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Oct Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Oct Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Nov Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Nov Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Nov Medico<center>
                                                    </th>
                                                    <th>
                                                        <center>Dic Personal<center>
                                                    </th>
                                                    <th>
                                                        <center>Dic Laboral<center>
                                                    </th>
                                                    <th>
                                                        <center>Dic Medico<center>
                                                    </th>
                                                </tr>
                                            </thead>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- /.card -->
                </section>
                <!-- /.Left col -->
            <?php } ?>


        </div>
        <!-- /.card -->
    </section>

    <!-- Main row -->


    <!-- right col -->
</div>
<!-- /.row (main row) -->
</div><!-- /.container-fluid -->
</section>

<!-- <section>
    <div class="modal hide fade in" id="newPasswordModal" tabindex="-1" aria-labelledby="emergencyContactModalLabel" aria-hidden="true" data-backdrop='static' data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar Contraseña: <label id="articulo"></label></h5>
                </div>
                <div class="modal-body">
                    <form id="actualiza_password" method="post">
                        <div class="justify-content-center">
                            <div class="col-md-12">
                                <div class="form-group col-md-6 offset-md-3">
                                    <label for="password">Nuevo Contraseña</label>
                                    <input type="text" class="form-control" id="password" name="password">
                                    <br>
                                    <div id="error_password" class="request-error text-danger"></div>
                                </div>
                                <div class="form-group col-md-6 offset-md-3">
                                    <label for="nuevo_password">Repite Contraseña</label>
                                    <input type="text" class="form-control" id="nuevo_password" name="nuevo_password">
                                    <br>
                                    <div id="error_nuevo_password" class="request-error text-danger"></div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button id="actualizar_password" class="btn btn-guardar">Actualizar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</section> -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Listado de Usuarios</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
                <div class="">
                    <div id="lista_usuarios" class="body">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalTicket" tabindex="-1" role="dialog" aria-labelledby="ModalTicketLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalTicketLabel">Reporte de Tickets</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div id="report-tickets" class="body">
                        <div class="container">
                            <form id="form_servicio_reporte" action="POST">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="servicio_tipo_reporte">Seleccionar Reporte</label>
                                        <select class="form-control" id="servicio_tipo_reporte">

                                            <option value="2">Tickets IT</option>

                                        </select>
                                        <div class="text-danger" id="error_servicio_tipo_reporte"></div>
                                    </div>
                                    <div id="cat_permiso_div"></div>
                                    <div class="col-md-3">
                                        <label for="servicio_fecha_ini">Fecha Inicial</label>
                                        <input type="date" id="servicio_fecha_ini" class="form-control">
                                        <div class="text-danger" id="error_servicio_fecha_ini"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="servicio_fecha_fin">Fecha Final</label>
                                        <input type="date" id="servicio_fecha_fin" class="form-control">
                                        <div class="text-danger" id="error_servicio_fecha_fin"></div>
                                    </div>
                                    <div class="col-md-2 my-4">
                                        <button type="submit" id="btn_servicio_reporte" class="btn btn-guardar" style="margin-top: 0.4rem;"> <b style="font-size:18px;"> Generar </b> </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- /.content -->
</div>


<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script src="<?= base_url() ?>/public/js/lib/dataTables.fixedColumns.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url() ?>/public/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<!-- <script src="<?= base_url() ?>/public/plugins/sparklines/sparkline.js"></script> -->
<!-- JQVMap -->
<!-- <script src="<?= base_url() ?>/public/plugins/jqvmap/jquery.vmap.min.js"></script> -->
<!-- <script src="<?= base_url() ?>/public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script> -->
<!-- jQuery Knob Chart -->
<!-- <script src="<?= base_url() ?>/public/plugins/jquery-knob/jquery.knob.min.js"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url() ?>/public/dist/js/pages/jquery.velocity.js"></script>
<script src="<?= base_url() ?>/public/dist/js/pages/dashboard_v5.js"></script>
<script src="<?= base_url() ?>/public/plugins/confetti.browser.min.js"></script>
<canvas id="confetti-canvas" style="position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:10;"></canvas>
<canvas id="fireworksCanvas" style="position:fixed;top:0;left:0;width:100%;height:100%; pointer-events:none;z-index:9999;display:none;"></canvas>

<script>
$(document).ready(function () {
  const meta = 400; // 🔹 valor de referencia
  const $dias = $("#dias-counter");
  const canvas = document.getElementById("fireworksCanvas");
  const ctx = canvas.getContext("2d");
  let fireworks = [];
  let launched = false;

  // Crear mensaje de celebración (solo una vez)
  const msg = document.createElement("div");
  msg.id = "fireworksMessage";
  msg.style.cssText = `
    position: fixed;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 3rem;
    font-weight: bold;
    color: #fff;
    text-shadow: 0 0 10px #000, 0 0 20px #ff0000;
    background: rgba(0,0,0,0.6);
    padding: 25px 40px;
    border-radius: 15px;
    z-index: 10000;
    display: none;
    animation: popIn 0.8s ease-out forwards;
  `;
  msg.innerHTML = "🎉 ¡Felicidades! 400 días sin accidentes 🎉";
  document.body.appendChild(msg);

  // 🔹 Animación de entrada del mensaje
  const style = document.createElement("style");
  style.innerHTML = `
    @keyframes popIn {
      0% { transform: translate(-50%, -50%) scale(0); opacity: 0; }
      70% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
      100% { transform: translate(-50%, -50%) scale(1); }
    }
  `;
  document.head.appendChild(style);

  // Ajustar tamaño del canvas
  function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  $(window).on("resize", resizeCanvas);
  resizeCanvas();

  // 🔹 Revisión periódica
  setTimeout(checkFireworks, 1000);
  setInterval(checkFireworks, 1500);

  function checkFireworks() {
    const dias = parseInt($dias.text());
    if (!launched && dias == meta) {
      launched = true;
      lanzarFuegos();
    }
  }

  // 🔥 Fuegos artificiales + mensaje
  function lanzarFuegos() {
    canvas.style.display = "block";
    $("#fireworksMessage").fadeIn(500);
    fireworks = [];

    function crearFuego() {
      const x = Math.random() * canvas.width;
      const y = Math.random() * (canvas.height / 2);
      const colores = ["#ff0000", "#00ff00", "#ffff00", "#00ffff", "#ff00ff", "#ffa500", "#ffffff"];
      for (let i = 0; i < 40; i++) {
        fireworks.push({
          x,
          y,
          velX: (Math.random() - 0.5) * 6,
          velY: (Math.random() - 0.5) * 6,
          tamaño: Math.random() * 3 + 1,
          color: colores[Math.floor(Math.random() * colores.length)],
          vida: 100
        });
      }
    }

    function animar() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      fireworks.forEach((p, i) => {
        p.x += p.velX;
        p.y += p.velY;
        p.velY += 0.05;
        p.vida -= 2;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.tamaño, 0, Math.PI * 2);
        ctx.fillStyle = p.color;
        ctx.fill();

        if (p.vida <= 0) fireworks.splice(i, 1);
      });

      if (fireworks.length < 150 && Math.random() < 0.05) crearFuego();
      requestAnimationFrame(animar);
    }

    animar();

    // Apagar luego de unos segundos
    setTimeout(() => {
      $(canvas).fadeOut(1500);
      $("#fireworksMessage").fadeOut(1500);
    }, 8000);
  }

  // 🔹 Botones +/-
  window.changeCounter = function (id, action) {
    const $el = $("#" + id);
    let valor = parseInt($el.text());
    if (action === "increment") valor++;
    else if (action === "decrement" && valor > 0) valor--;
    $el.text(valor);
    if (id === "dias-counter") checkFireworks();
  };

  window.resetCounter = function () {
    $("#dias-counter").text("0");
    launched = false;
  };
});
</script>



<script>

    
    $(document).ready(function() {
        let currentPage = 1;
        const itemsPerPage = 5;
        let allData = [];
        let filteredData = [];

        function loadDirectories() {
            $.get(`${urls}directorio/listar`, function(data) {
                allData = data;
                filteredData = allData; // Inicialmente, todos los datos
                renderPage(currentPage);
                renderPagination();
            });
        }

        function renderPage(page) {
            let start = (page - 1) * itemsPerPage;
            let end = start + itemsPerPage;
            let paginatedData = filteredData.slice(start, end);

            let tableContent = "";
            paginatedData.forEach((dir, index) => {
                tableContent += `<tr>
                          
                          <td style="font-size:13px;">${dir.nombre || "N/A"}</td>
                          <td style="font-size:13px;">${dir.apellido || "N/A"}</td>
                          <td style="text-align:center;">${dir.extension || "N/A"}</td>
                          <td style="font-size:12px;">${dir.email || "N/A"}</td>
                          <td style="font-size:11px;">${dir.departamento || "N/A"}</td>
                          <td style="font-size:13px; text-align:center;">${dir.numero_directo || "N/A"}</td>
                        </tr>`;
            });

            $("#directoryTable tbody").html(tableContent);
        }

        function renderPagination() {
            let totalPages = Math.ceil(filteredData.length / itemsPerPage);
            let paginationContent = `<li class="page-item"><a href="#" class="page-link prev">«</a></li>`;

            for (let i = 1; i <= totalPages; i++) {
                paginationContent += `<li class="page-item ${i === currentPage ? "active" : ""}">
                              <a href="#" class="page-link page-num" data-page="${i}">${i}</a>
                            </li>`;
            }

            paginationContent += `<li class="page-item"><a href="#" class="page-link next">»</a></li>`;
            $(".pagination").html(paginationContent);
        }

        $(".pagination").on("click", ".page-num", function(e) {
            e.preventDefault();
            currentPage = parseInt($(this).data("page"));
            renderPage(currentPage);
            renderPagination();
        });

        $(".pagination").on("click", ".prev", function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
                renderPagination();
            }
        });

        $(".pagination").on("click", ".next", function(e) {
            e.preventDefault();
            let totalPages = Math.ceil(filteredData.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
                renderPagination();
            }
        });

        $("#searchInput").on("keyup", function() {
            let searchText = $(this).val().toLowerCase();
            filteredData = allData.filter((dir) => {
                return (
                    (dir.nombre || "").toLowerCase().includes(searchText) ||
                    (dir.apellido || "").toLowerCase().includes(searchText) ||
                    (dir.extension || "").toLowerCase().includes(searchText) ||
                    (dir.email || "").toLowerCase().includes(searchText) ||
                    (dir.departamento || "").toLowerCase().includes(searchText) ||
                    (dir.numero_directo || "").toLowerCase().includes(searchText)
                );
            });
            currentPage = 1; // Reiniciar a la primera página
            renderPage(currentPage);
            renderPagination();
        });

        loadDirectories();
    });
</script>

<script>
    // Saludo dinámico según la hora del día
    function updateGreeting() {
        const hour = new Date().getHours();
        const greetingElement = document.getElementById('dynamic-greeting');
        const messageElement = document.getElementById('greeting-message');

        let greeting = "";
        let message = "";

        if (hour >= 5 && hour < 12) {
            greeting = "¡Buenos días!";
            message = "Que tengas un día productivo.";
        } else if (hour >= 12 && hour < 19) {
            greeting = "¡Buenas tardes!";
            message = "Esperamos que tu día vaya excelente.";
        } else {
            greeting = "¡Buenas noches!";
            message = "Descansa y recarga energías.";
        }

        greetingElement.textContent = greeting;
        messageElement.textContent = "<?= session()->name ?? 'Usuario' ?>," + " " + message;
    }

    // Actualizar reloj en tiempo real
    function updateClock() {
        const now = new Date();
        const timeElement = document.getElementById('current-time');
        const dateElement = document.getElementById('date-display');

        const timeString = now.toLocaleTimeString('es-ES');
        const dateString = now.toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        timeElement.textContent = timeString;
        dateElement.textContent = dateString.charAt(0).toUpperCase() + dateString.slice(1);
    }

    // Inicializar y configurar intervalos
    document.addEventListener('DOMContentLoaded', function() {
        updateGreeting();
        updateClock();

        // Actualizar cada segundo
        setInterval(updateClock, 1000);

        // Actualizar saludo cada minuto (por si cambia de día/noche)
        setInterval(updateGreeting, 60000);

        // Animación de entrada para la tarjeta de saludo
        const greetingCard = document.querySelector('.greeting-card');
        greetingCard.style.opacity = '0';
        greetingCard.style.transform = 'translateY(-20px)';

        setTimeout(() => {
            greetingCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            greetingCard.style.opacity = '1';
            greetingCard.style.transform = 'translateY(0)';
        }, 300);
    });
</script>



<script>
    $(document).ready(function() {
        let data = new FormData();
        data.append("iduser", <?= session()->id_user ?>);
        $.ajax({
            data: data, //datos que se envian a traves de ajax
            url: urls + "sistemas/active-password", //archivo que recibe la peticion
            type: "post", //método de envio
            processData: false, // dile a jQuery que no procese los datos
            contentType: false, // dile a jQuery que no establezca contentType
            success: function(response) {
                //console.log(response);
                var datos = JSON.parse(response);
                console.log("res: " + datos[0].active_password);
                if (datos[0].type_of_employee == 1) {
                    return;
                }
                if (datos[0].active_password == 1) {
                    $("#newPasswordModal").modal("show");
                } else {

                    console.log("cambio su password");
                }
            },
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fallo de conexión: ​​Verifique la red.",
                });
                $("#guardar_ticket").prop("disabled", false);
            } else if (jqXHR.status == 404) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No se encontró la página solicitada [404]",
                });
                $("#guardar_ticket").prop("disabled", false);
            } else if (jqXHR.status == 500) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]",
                });
                $("#guardar_ticket").prop("disabled", false);
            } else if (textStatus === "parsererror") {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error de análisis JSON solicitado.",
                });
                $("#guardar_ticket").prop("disabled", false);
            } else if (textStatus === "timeout") {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error.",
                });
                $("#guardar_ticket").prop("disabled", false);
            } else if (textStatus === "abort") {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted.",
                });

                $("#guardar_ticket").prop("disabled", false);
            } else {
                alert("Uncaught Error: " + jqXHR.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: `Uncaught Error: ${jqXHR.responseText}`,
                });
                $("#guardar_ticket").prop("disabled", false);
            }
        });

    });

    $("#actualiza_password").submit(function(event) {
        event.preventDefault();
        let password = $.trim($("#password").val());
        let nuevo_password = $.trim($("#nuevo_password").val());

        if ($.trim(password).length == 0) {
            var error_pass = "El campo es requerido";
            $("#error_password").text(error_pass);
            $("#password").addClass("has-error");
            return;
        } else {
            error_pass = "";
            $("#error_password").text(error_pass);
            $("#password").removeClass("has-error");
        }

        if ($.trim(nuevo_password).length == 0) {
            var error_passn = "El campo es requerido";
            $("#error_nuevo_password").text(error_passn);
            $("#nuevo_password").addClass("has-error");
            return;
        } else {
            error_passn = "";
            $("#error_nuevo_password").text(error_passn);
            $("#nuevo_password").removeClass("has-error");

        }

        if (password != nuevo_password) {
            $("#error_nuevo_password").text("Las contraseñas no son iguales");
            return;
        } else {
            $("#error_nuevo_password").text("");
        }

        let data = new FormData();
        data.append("password1", password);
        data.append("password2", nuevo_password);
        $.ajax({
            data: data, //datos que se envian a traves de ajax
            url: urls + "sistemas/nuevo-password", //archivo que recibe la peticion
            type: "post", //método de envio
            processData: false, // dile a jQuery que no procese los datos
            contentType: false, // dile a jQuery que no establezca contentType
            success: function(response) {
                //una vez que el archivo recibe el request lo procesa y lo devuelve
                console.log(response);
                /*codigo que borra todos los campos del form newProvider*/
                if (response != "error") {
                    $("#password").val("");
                    $("#nuevo_password").val("");

                    $('#newPasswordModal').modal('toggle');
                    Swal.fire("!Se ha Actualizado tu Contraseña!", "", "success");
                } else {
                    $('#newPasswordModal').modal('toggle');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Algo salió Mal! Contactar con el Administrador",
                    });
                }
            },
        }).fail(function(jqXHR, textStatus, errorThrown) {

            if (jqXHR.status === 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Fallo de conexión: ​​Verifique la red.",
                });
                $("#guardar_permiso").prop("disabled", false);

            } else if (jqXHR.status == 404) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No se encontró la página solicitada [404]",
                });
                $("#guardar_permiso").prop("disabled", false);
            } else if (jqXHR.status == 500) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]",
                });
                $("#guardar_permiso").prop("disabled", false);
            } else if (textStatus === 'parsererror') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error de análisis JSON solicitado.",
                });
                $("#guardar_permiso").prop("disabled", false);
            } else if (textStatus === 'timeout') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error.",
                });
                $("#guardar_permiso").prop("disabled", false);
            } else if (textStatus === 'abort') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted.",
                });

                $("#guardar_permiso").prop("disabled", false);
            } else {

                alert('Uncaught Error: ' + jqXHR.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: `Uncaught Error: ${jqXHR.responseText}`,
                });
                $("#guardar_permiso").prop("disabled", false);
            }
        });
    });

    $("#more_info").on('click', function(e) {
        e.preventDefault();
        $("#more_info").empty();
        $("#p_more_info").empty();
        if ($("#more_info").val() == 1) {
            $("#more_info").val(2);
            $("#more_info").append(`Menos Información  <i class="far fa-arrow-alt-circle-left"></i>`);
            $("#p_more_info").append(`Se les informa que a partir del día de hoy, la plataforma de permisos de entrada y salida, así como vacaciones tendrá la siguiente actualización:<br><br>
            *Los Jefes de área serán los que autorizarán los permisos del personal a su cargo.<br>
            *Solo se tendrá 3 permisos personales en  un periodo de 30 días.<br>
            *Si llegase a requerir un 4to permiso, éste será autorizado por la Dirección respectiva.<br>
            *Si se necesita un quinto permiso será autorizado por la Dirección General.<br>
            *Si el permiso personal rebasa las 3 horas después de la entrada o antes de la salida, en automático el sistema les recomendará realizar un permiso a cuenta de vacaciones.<br>
            *Es importante que cuando el permiso sea laboral, en el apartado de observaciones detalla que actividad  que se realizara, de lo contrario no se considera como permiso laboral.<br>`);
        } else {
            $("#more_info").val(1);
            $("#more_info").append(`Más Información  <i class="fas fa-arrow-circle-right"></i>`);
            $("#p_more_info").append(`Se les informa que a partir del día de hoy, la plataforma de permisos de entrada y salida, así como vacaciones tendrá la siguiente actualización:`);
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selecciona todas las tarjetas
        const cards = document.querySelectorAll('.animated-card');
        // Agrega la clase 'visible' para disparar la animación
        cards.forEach(card => {
            card.classList.add('visible');
        });
    });
</script>
<script>
    // Inicializar confetti apuntando al canvas
    const canvas = document.getElementById('confetti-canvas');
    const myConfetti = confetti.create(canvas, {
        resize: true,
        useWorker: true
    });

    document.querySelectorAll('.animated-card').forEach(card => {
        // Inicializa contador en dataset
        card.dataset.confettiCount = 0;

        const handler = () => {
            let count = parseInt(card.dataset.confettiCount, 10);

            if (count >= 3) {
                // Ya llegó al máximo: removemos el listener
                card.removeEventListener('mouseenter', handler);
                return;
            }

            // Disparamos confeti
            const {
                left,
                top,
                width,
                height
            } = card.getBoundingClientRect();
            const originX = (left + width / 2) / window.innerWidth;
            const originY = (top + height / 2) / window.innerHeight;

            myConfetti({
                particleCount: 50,
                spread: 60,
                origin: {
                    x: originX,
                    y: originY
                }
            });
            myConfetti({
                particleCount: 30,
                spread: 100,
                origin: {
                    x: originX,
                    y: originY
                }
            });

            // Incrementa el contador
            card.dataset.confettiCount = count + 1;
        };

        card.addEventListener('mouseenter', handler);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.slide-header');
        // Small timeout para que reconozca el estado inicial
        setTimeout(() => {
            header.classList.add('visible');
        }, 100);
    });
</script>







<?= $this->endSection() ?>