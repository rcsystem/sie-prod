<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Todas las Solicitudes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Solicitudes Papelería</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item"><a href="#">Papelería</a></li>
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
          <h3 class="card-title">Solicitudes de papelería</h3>
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
            <table id="tabla_solicitudes" class="table table-bordered table-striped " role="grid" aria-describedby="solicitudes" style="width:100%" ref="" >

            </table>
        </div>

        <div class="card-footer">
          <a href="#">Solicitudes</a>
        </div>
      </div>
    </div>
  </section>
  <section>
        <div class="modal fade" id="papeleriaModal" tabindex="-1" aria-labelledby="papeleriaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Papeleria<label id="articulo"></label></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="resultado"></div>
                        <form id="respuesta_papeleria" method="post">
                          
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="folio">Folio</label>
                                    <input type="text" class="form-control" id="folio" name="folio" value="" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="usuario">Usuario</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="usuario">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="fecha">Fecha</label>
                                    <input type="text" class="form-control" id="fecha" name="fecha" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="depto">Departamento</label>
                                    <input type="text" class="form-control" id="depto" name="depto" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="centro_costo">Centro de Costo</label>
                                    <input type="text" class="form-control" id="centro_costo" name="centro_costo" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="centro_costo">Fecha Entrega</label>
                                    <input type="date" class="form-control" id="entrega" name="entrega">
                                    <div id="error_entrega" class=" text-danger"></div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="estado">Estado</label>
                                   <select name="estado" id="estado" class="form-control">
                                      <option value="">Seleccionar</option>
                                      <option value="3">Completado</option>
                                      <option value="4">Rechazado</option>
                                   </select>
                                   <div id="error_estado" class=" text-danger"></div>
                                </div>
                                <div class="form-group col-md-6">
                                <table class="tab2" style="margin-top:30px;width:90%;">
                                    <tbody id="table">


                                    </tbody>
                                </table>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="centro_costo">Observación</label>
                                    <textarea class="form-control" name="obs_entrega" id="obs_entrega" cols="20" rows="2"></textarea>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" id="res_papeleria" name="res_papeleria" class="btn btn-guardar">Guardar</button>
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
<script src="<?= base_url()?>/public/js/stationery/all_requests_v1.js"></script>
<?= $this->endSection() ?>
