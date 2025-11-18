<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Todas las Requisiciones
<?= $this->endSection() ?>
<?= $this->section('css') ?>

<link rel="stylesheet" href="<?= base_url() ?>/public/css/requisiciones/generar/style.css">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Todas las Requisiciones.</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Requisiciones</li>
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
                    <h3 class="card-title">Todas las Requisiciones</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body col-md-12">
                    <div class="container-fluid">
                        <table id="tabla_todas_requisiciones" class="table table-bordered table-striped display" role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

                        </table>
                    </div>
                </div>

            </div>
    </section>
    <section>
        <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Requisición<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="actualizar_requisicion" method="post">
                            <input type="hidden" id="id_folio" name="id_folio" value="">
                            <div class="form-row">
                            <div class="form-group col-md-4">
                                    <label for="tipo_personal">Empresa Solicitante</label>
                                    <input type="text" class="form-control" id="empresa" name="empresa" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Centro de Costo</label>
                                    <input type="text" class="form-control" id="centro_costo" name="centro_costo" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Area Operativa</label>
                                    <input type="text" class="form-control" id="area_operativa" name="area_operativa" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Tipo de personal</label>
                                    <input type="text" class="form-control" id="tipo_personal" name="tipo_personal" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="puesto_solicitado">Puesto solicitado</label>
                                    <input type="text" class="form-control" id="puesto_solicitado" name="puesto_solicitado" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Motivo de la Requisición</label>
                                    <input type="text" class="form-control" id="motivo" name="motivo" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="jefe_inmediato">Jefe Inmediato</label>
                                    <input type="text" class="form-control" id="jefe_inmediato" name="jefe_inmediato" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Colaborador a Remplazar</label>
                                    <input type="text" class="form-control" id="remplazo" name="remplazo" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="personas_requeridas">Personas requeridas</label>
                                    <input type="number" class="form-control" id="personas_requeridas" name="personas_requeridas" onkeypress="return validaNumericos(event)" min="1" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Cotización</label>
                                    <input type="text" class="form-control" id="cotizacion" name="cotizacion" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Periodo</label>
                                    <input type="text" class="form-control" id="periodo" name="periodo" value="" required>
                                </div>
                               
                                <div class="form-group col-md-4">
                                    <label for="estudios">Grado Estudios</label>
                                    <input type="text" class="form-control" id="estudios" name="estudios" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Estado Civil</label>
                                    <input type="text" class="form-control" id="estado_civil" name="estado_civil" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Rolar Turno</label>
                                    <input type="text" class="form-control" id="rolar" name="rolar" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Licencia de Conducir</label>
                                    <input type="text" class="form-control" id="licencia" name="licencia" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Años de Experiencia</label>
                                    <input type="number" class="form-control" id="experiencia" name="experiencia" value=""  required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Trato con Clientes/Proveedores</label>
                                    <input type="text" class="form-control" id="trato" name="trato" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Manejo de Personal</label>
                                    <input type="text" class="form-control" id="manejo" name="manejo" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_personal">Jornada</label>
                                    <input type="text" class="form-control" id="jornada" name="jornada" value="" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="salario_inicial">Salario inicial</label>
                                    <input type="text" class="form-control" id="salario_inicial" name="salario_inicial" value="" onclick="ValidateDecimalInputs(this)" onchange="MASK(this,this.value,'-$##,###,##0.00',1)" placeholder="Ejemplo: 10000.99" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="salario_final">Salario final</label>
                                    <input type="text" class="form-control" id="salario_final" name="salario_final" onclick="ValidateDecimalInputs(this)" onchange="MASK(this,this.value,'-$##,###,##0.00',1)" placeholder="Ejemplo: 10000.99" required>
                                </div>
                         
                                <div class="form-group col-md-4">
                                    <label for="horario_inicial">Horario inicial</label>
                                    <input type="time" class="form-control" id="horario_inicial" name="horario_inicial" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="horario_final">Horario final</label>
                                    <input type="time" class="form-control" id="horario_final" name="horario_final" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="horario_final">Estatus</label>
                                    <select name="status_req" id="status_req" class="form-control" required>
                                        <option value="">Seleccionar</option>
                                        <option value="Rechazada">Rechazar</option>
                                        <option value="Autorizada">Autorizar</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="actualiza_requisicion" name="actualiza_requisicion" class="btn btn-guardar">Guardar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/requisitions/requisitions_all_v1.min.js"></script>
<?= $this->endSection() ?>