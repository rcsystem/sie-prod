<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
DashBoard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url() ?>/public/css/animate.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet" />

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
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <img class="img-fluid" src="<?= base_url() ?>/public/images/WW-180Logo.png" alt="" style="display:block;width:20% !important;">
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active sie-font-bold">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
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
            <div class="col-lg-4">
                <a <?= $ruteTag ?>>
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"> <i class="fas fa-id-card-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Marbete:</span>
                            <H2 style="margin-top: 10px;"><?= $info->tag; ?></H2>
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
                            <H2 style="margin-top: 10px;"><?= $info->permiss; ?></H2>
                        </div>
                    </div>
                </a>
            </div>
        <!--     <div class="col-lg-4">
                <div class="info-box bg-gradient-<? //echo $color; ?>">
                    <span class="info-box-icon"> <i class="far fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mis Días de Vacaciones Disponibles:</span>
                        <H2 style="margin-top: 10px;"><? //$info->vacation; ?></H2>
                    </div>
                </div>
            </div> -->
        </div>
        <?php if (session()->id_user == 1 || session()->id_user == 1063 || session()->id_user == 854 || session()->id_user == 75 || session()->id_user == 1202) { ?>
            <div class="row">
                <div class="my-col-rq">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <B>Marbetes</B>
                            <H2 style="margin-top: 10px;"><?= $allTags; ?></H2>
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
                            <H2 style="margin-top: 10px;"><?= $tags[1]; ?></H2>
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
                            <H2 style="margin-top: 10px;"><?= $tags[2]; ?></H2>
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
                            <H2 style="margin-top: 10px;"><?= $tags[3]; ?></H2>
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
                            <H2 style="margin-top: 10px;"><?= $tags[4]; ?></H2>
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
                            <H2 style="margin-top: 10px;"><?= $tags[5]; ?></H2>
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
                            <H2 style="margin-top: 10px;"><?= $tags[6]; ?></H2>
                        </div>
                        <div class="icon">
                            <i class="fas">N1</i>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <?php if (
            session()->id_user == 92 || session()->id_user == 151 || session()->id_user == 37 || session()->id_user == 50 || session()->id_user == 627 || session()->id_user == 267 ||
                session()->id_user == 905 || session()->id_user == 251 || session()->id_user == 906 || session()->id_user == 374 || session()->id_user == 159 ||
                session()->id_user == 252 || session()->id_user == 1188 || session()->id_user == 1 || session()->id_user == 1063 || session()->id_user == 265 ||
                session()->id_user == 250 || session()->id_user == 592 || session()->id_user == 262  || session()->id_user == 107 || session()->id_user == 852 || session()->id_user == 854
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
            <?php if (session()->id_user == 252 || session()->id_user == 1 || session()->id_user == 151  || session()->id_user == 1063 || session()->id_user == 107 || session()->id_user == 852 || session()->id_user == 854) { ?>
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card">
                        <div class="card-header">
                            <h3 id="permiso-card" class="card-title sie-font-bold">
                                <i class="fas fa-table mr-1"></i>
                                Vacaciones de Directores - <?= date('Y'); ?>
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

                                    <div class="table-responsive" id="div-tabla-directores">

                                        <table class="table table-striped nowrap" id="tabla_vacaciones_directores" style="width: 100%;">

                                            <thead style="font-size:15px;">
                                                <tr style="background-color:#154360;boder-color:#154360;color:white;">
                                                    <th style="background-color:#154360;boder-color:#154360;color:white;">
                                                        <center>Director<center>
                                                    </th>

                                                    <th>
                                                        <center>Total Dias<center>
                                                    </th>
                                                    <th>
                                                        <center>Enero<center>
                                                    </th>
                                                    <th>
                                                        <center>Febrero<center>
                                                    </th>
                                                    <th>
                                                        <center>Marzo<center>
                                                    </th>
                                                    <th>
                                                        <center>Abril<center>
                                                    </th>
                                                    <th>
                                                        <center>Mayo<center>
                                                    </th>
                                                    <th>
                                                        <center>Junio<center>
                                                    </th>
                                                    <th>
                                                        <center>Julio<center>
                                                    </th>
                                                    <th>
                                                        <center>Agosto<center>
                                                    </th>
                                                    <th>
                                                        <center>Septiembre<center>
                                                    </th>
                                                    <th>
                                                        <center>Octubre<center>
                                                    </th>
                                                    <th>
                                                        <center>Noviembre<center>
                                                    </th>
                                                    <th>
                                                        <center>Diciembre<center>
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

            <?php if (
                session()->id_user == 1 || session()->id_user == 592 || session()->id_user == 151 ||
                session()->id_user == 250 || session()->id_user == 1063 || session()->id_user == 252 ||
                session()->id_user == 262 || session()->id_user == 1188 || session()->id_user == 265 ||
                session()->id_user == 374  || session()->id_user == 107 || session()->id_user == 852 
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

            <?php if (
                session()->id_user == 1 || session()->id_user == 592 || session()->id_user == 151 ||
                session()->id_user == 250 || session()->id_user == 1063 || session()->id_user == 252 ||
                session()->id_user == 262 || session()->id_user == 1188 || session()->id_user == 265 ||
                session()->id_user == 374  || session()->id_user == 107 || session()->id_user == 852
            ) { ?>
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card">
                        <div class="card-header">
                            <h3 id="permiso-card" class="card-title sie-font-bold">
                                <i class="fas fa-table mr-1"></i>
                                Vacaciones - <?= date('Y'); ?>
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

                                    <div class="table-responsive" id="div-tabla-vacaciones">

                                        <table class="table table-striped nowrap" id="tabla_permisos_vacaciones" style="width: 100%;">

                                            <thead style="font-size:15px;">
                                                <tr style="background-color:#154360;boder-color:#154360;color:white;">
                                                    <th style="background-color:#154360;boder-color:#154360;color:white;">
                                                        <center>Departamento<center>
                                                    </th>
                                                    <th>
                                                        <center>Area Operativa<center>
                                                    </th>
                                                    <th>
                                                        <center>Total Vacaciones<center>
                                                    </th>
                                                    <th>
                                                        <center>Enero<center>
                                                    </th>
                                                    <th>
                                                        <center>Febrero<center>
                                                    </th>
                                                    <th>
                                                        <center>Marzo<center>
                                                    </th>
                                                    <th>
                                                        <center>Abril<center>
                                                    </th>
                                                    <th>
                                                        <center>Mayo<center>
                                                    </th>
                                                    <th>
                                                        <center>Junio<center>
                                                    </th>
                                                    <th>
                                                        <center>Julio<center>
                                                    </th>
                                                    <th>
                                                        <center>Agosto<center>
                                                    </th>
                                                    <th>
                                                        <center>Septiembre<center>
                                                    </th>
                                                    <th>
                                                        <center>Octubre<center>
                                                    </th>
                                                    <th>
                                                        <center>Noviembre<center>
                                                    </th>
                                                    <th>
                                                        <center>Diciembre<center>
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
<script src="<?= base_url() ?>/public/dist/js/pages/dashboard_v3.js"></script>

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
<?= $this->endSection() ?>