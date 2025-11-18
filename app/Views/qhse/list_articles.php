<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Administrar Menus
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listado de EPP</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item"><a href="#">EPP</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Alta de Articulos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <form id="nuevo_articulo" method="post">
                            <button id="btn_guardar_articulo" type="submit" class="btn btn-guardar btn-lg">Guardar</button>

                            <div id="resultado" class="form-group error col-md-6"></div>
                            <hr>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="titulo">Nombre del Articulo</label>
                                    <input type="text" class="form-control rounded-0" id="articulo" name="articulo" value="" onchange="validar()">
                                    <div id="error_articulo" name="error_articulo" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="titulo">Unidad</label>
                                    <input type="text" class="form-control rounded-0" id="unidad" name="unidad" value="" onchange="validar()">
                                    <div id="error_unidad" name="error_unidad" class="text-danger"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Formulario Menu Nuevo</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Listado de Articulos</h3>
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
                    <table id="tabla_lista_articulos" class="table table-bordered table-striped " role="grid" aria-describedby="articulos_info" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Listado de Epp </a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="modal fade" id="actualizar_menu_modal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">MENU<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="editar_menu" method="post" enctype="multipart/form-data">
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <div id="img-menus" class="img-menu">
                                            <img id="imagen1" name="imagen1" class="img-fluid" src="" alt="" style="height: 250px;">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-8">

                                        <input type="hidden" class="form-control" id="folio_edit" name="folio_edit" value="" readonly>

                                        <div id="titulo_edit" name="titulo_edit" class="text-left titulo_edit"></div>
                                        <div class="form-row">

                                            <h5 aling="center" class="">PLATILLOS DEL MENU<label id="articulo"></label></h5>
                                            <div class="btn-platillos">

                                                <button id="btn_agregar_platillo_edit" class="btn btn-guardar"> <i class="fas fa-utensils"></i> Agregar</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="resultado_edit" class="form-group"></div>
                                        <div id="input_duplica_edit">
                                            <div id="item-duplica-edit"></div>
                                            <div id="extra_edit_1" class="row">
                                                <div class="col-md-1">

                                                    <input type="hidden" class="form-control" id="food_edit_1" name="food_edit[]" value="" readonly>
                                                </div>
                                                <div class="form-group col-md-9">

                                                    <input type="text" class="form-control rounded-0" id="comida_edit_1" name="comida_edit[]" value="" onchange="validarPlatilloModal(1)">
                                                    <div id="error_comida_edit_1" name="error_comida_edit_[]" class="text-danger"></div>

                                                </div>
                                                <div class="col-md-1">
                                                    <div id="btn_eliminar_edit_1" class="form-group"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" id="btn_editar_menu" name="btn_editar_menu" class="btn btn-guardar">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="modal fade" id="eliminar_comida_modal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ELIMINAR MENU<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="borrar_comida" method="post" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <img id="imagen2" name="imagen2" src="" alt="" width="" height="" class="">
                                </div>
                                <div class="form-group col-md-8">

                                    <input type="hidden" class="form-control" id="folio_borrar" name="folio_borrar" value="" readonly>

                                    <div id="titulo_borrar" name="titulo_borrar" style="margin-bottom:3rem;"></div>
                                    <div class="form-row">

                                        <h5 aling="center" class="">ELIMINAR PLATILLOS DEL MENU<label id="articulo"></label></h5>

                                    </div>
                                    <hr>
                                    <div id="resultado_borrar" class="form-group error col-md-9"></div>
                                    <div id="input_duplica_borrar">

                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" id="btn_borrar_comida" name="btn_borrar_comida" class="btn btn-guardar">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
<?= $this->section('js') ?>


<script src="<?= base_url() ?>/public/js/qhse/list_articles_v1.js"></script>

<?= $this->endSection() ?>