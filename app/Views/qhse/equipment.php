<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Entrega de Equipos
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
    .autocomplete-suggestions {
  border: 1px solid #ccc;
  background: #fff;
  max-height: 200px;
  overflow-y: auto;
  position: absolute;
  z-index: 9999;
}

.autocomplete-suggestions div {
  padding: 8px;
  cursor: pointer;
}

.autocomplete-suggestions div:hover {
  background-color: #e9e9e9;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h5 class="m-0">Vale de Equipos de Proteccion Personal</h5>
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
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Equipo Personal</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-body mt-3">

                        <div id="content-form" class="container-fluid mb-4">
                            <form id="equipos" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="id_usuario" name="id_usuario" value="<?= session()->id_user ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label class="floating-label" for="num_nomina">Número de Nómina</label>
                                        <input type="text" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="<?= session()->payroll_number; ?>" readonly>

                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="floating-label" for="usuario">Solicitante</label>
                                        <input type="text" class="form-control rounded-0" id="usuario" name="usuario" value="<?= ucwords(session()->name . " " . session()->surname); ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="floating-label" for="departamento">Departamento</label>
                                        <input type="text" class="form-control rounded-0" id="departamento" name="departamento" value="<?= session()->departament; ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="floating-label" for="puesto">Puesto</label>
                                        <input type="text" class="form-control rounded-0" id="puesto" name="puesto" value="<?= session()->job_position; ?>" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div id="extra" class="form-row pt-3">
                                    <div class="form-group col-md-2">
                                        <label class="floating-label" for="epp_num_nomina">Número de Nómina</label>
                                        <input type="text" class="form-control rounded-0" id="epp_num_nomina" name="epp_num_nomina" onkeyup="javascript:this.value=this.value.toUpperCase();" title="Ingresar Numero de nomina" onchange="escuchar(1);validarUsuario()">
                                        <div id="error_num_nomina" class="text-danger"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="floating-label" for="epp_usuario">Usuario</label>
                                            <input type="text" class="form-control rounded-0" id="epp_usuario" name="epp_usuario" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="floating-label" for="epp_depto">Departamento</label>
                                            <input type="text" class="form-control rounded-0" id="epp_depto" name="epp_depto" value="" />
                                            <div id="error_epp_depto" class="text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="floating-label" for="epp_puesto">Puesto</label>
                                            <input type="text" class="form-control rounded-0" id="epp_puesto" name="epp_puesto" value="" />
                                            <div id="error_epp_puesto" class="text-danger"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="epp_centro_costo" name="epp_centro_costo" value="" />
                                    <input type="hidden" id="id_user" name="id_user" value="" />
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-4 pb-3">
                                        <button id="btn-agregar-item" class="btn btn-guardar" type="button"><i class="fas fa-shopping-cart"></i> Agregar Articulo </button>
                                    </div>
                                    <div class="radio-group">
                                        <div>
                                            <input type="radio" name="opt1" value="Desgaste" />
                                            <label for="opt1">Desgaste</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="opt1" value="Perdida" />
                                            <label>Perdida</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="opt1" value="Otro" />
                                            <label>Otro</label>
                                        </div>
                                    </div>
                                    <div id="resultado" class="error col-md-8"></div>



                                    <div class="form-group col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="0" name="entrega_equipo" id="entrega_equipo" />
                                            <label class="form-check-label" for="flexCheckDefault"><b>Entrega por HSE</b></label>
                                        </div>
                                    </div>
                                    <!-- <div id="especificar" class="form-group col-md-6"></div> -->

                                </div>


                                <div id="alumnos">
                                    <div id="duplica" class="agrega-item">
                                        <div id="item-duplica"></div>
                                    </div>
                                    <div id="tiempo_extra">
                                        <div id="extra_1" class="form-row ">
                                               <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="floating-label" for="codigo_1">Codigo</label>
                                                    <input type="text" id="codigo_1" name="codigo[]" class="form-control code-input" value="" onchange="inventary_code(this)" />
                                                    <div id="error_codigo_1" class="text-danger"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"> <!-- onchange="inventary(this) -->
                                                    <label class="floating-label" for="epp_1">Articulo</label>
                                                    <input list="browsers_1" id="epp_1" name="epp[]" class="form-control autocomplete-input" autocomplete="off" value="" />
                                                   <!--  <datalist id="browsers_1"></datalist> -->
                                                   <div id="suggestions" class="autocomplete-suggestions"></div>
                                                    <div id="error_epp_1" class="text-danger"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="floating-label" for="cantidad_1">Cantidad</label>
                                                    <input type="number" id="cantidad_1" name="cantidad[]" class="form-control" value="" placeholder />
                                                    <div id="error_cantidad_1" class="text-danger"></div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="id_product_1" name="id_product[]" value="" />
                                            <input type="hidden" id="medida_1" name="medida[]" value="" />
                                            <div id="btn_eliminar_1" class="form-group col-md-1"></div>

                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="form-group">
                            <label for="especificar">Especificar</label>
                            <textarea id="especificar" name="especificar" class="form-control" rows="3"></textarea>

                        </div>


                        <button id="guardar_permiso" type="submit" class="btn btn-guardar btn-lg btn-block"><i class="fas fa-file-alt"></i> Generar Vale</button>
                        </form>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="#">Entrega de EPP</a>
                </div>
            </div>
        </div>

    </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->

<script src="<?= base_url() ?>/public/js/qhse/entrega_equipos_v2.js"></script>

<?= $this->endSection() ?>