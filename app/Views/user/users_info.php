<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Información de Usuarios
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Información de Usuarios</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Usuarios</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE collapsed-card-->
      <div class="card card-default ">
        <div class="card-header">
          <h3 class="card-title">Información de Usuarios</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
        <div class="form-group col-md-4">
                <form id="formDatosGeneral" method="post">
                  <button id="reporte_datos_general" name="reporte_datos_general" type="submit" class="btn btn-guardar">Descargar Info Usuarios </button>
                </form>
                </div>
                <hr>
          <table id="tabla_info_usuarios" class="table table-striped table-bordered nowrap" role="grid" aria-describedby="info_usuarios" style="width:100%" ref="">
          </table>
        </div>

        <div class="card-footer">
          <a href="#">Información de Usuarios</a>
        </div>
      </div>

    </div>
  </section>
  <section>
    <div class="modal fade" id="emergencyContactModal" tabindex="-1" aria-labelledby="emergencyContactModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header" style="margin-bottom: -1px;">
            <h5 class="modal-title"><i class="fas fa-ambulance" style="margin-right: 0.5rem;"></i> CONTACTOS DE EMERGENCIA: <label id="usuario_info" style="font-weight: 400;"></label> </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="resultado"></div>
            <form id="contactos_emergencia" method="post">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="contacto_1">Contacto 1</label>
                  <input type="text" class="form-control" id="contacto_1" name="contacto_1" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                    <label for="parentesco_1">Parentesco</label>
                    <input type="text" class="form-control" id="parentesco_1" name="parentesco_1" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="tel_1">Número Telefónico</label>
                  <input type="text" class="form-control" id="tel_1" name="tel_1" readonly>
                </div>

                <div class="form-group col-md-4">
                  <label for="contacto_2">Contacto 2</label>
                  <input type="text" class="form-control" id="contacto_2" name="contacto_2" value="" readonly>
                </div>
                <div class="form-group col-md-4">
                    <label for="parentesco_2">Parentesco</label>
                    <input type="text" class="form-control" id="parentesco_2" name="parentesco_2" readonly>
                </div>
                <div class="form-group col-md-4">
                  <label for="tel_2">Número Telefónico</label>
                  <input type="text" class="form-control" id="tel_2" name="tel_2" readonly>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
           <!--  <button type="submit" id="guarda_contacto" name="actualiza_requisicion" class="btn btn-guardar">Guardar</button> -->
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="informacionUserModal" tabindex="-1" aria-labelledby="permisosEditarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tittle_modal"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="modal_body"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <!-- <button type="submit" id="editar_permiso" name="editar_permiso" class="btn btn-guardar">Guardar</button> -->
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="informacionFamilyModal" tabindex="-1" aria-labelledby="permisosEditarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tittle_modal_family"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="modal_family_body"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <!-- <button type="submit" id="editar_permiso" name="editar_permiso" class="btn btn-guardar">Guardar</button> -->
          </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="emergencyContactModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header" style="margin-bottom: -1px;">
            <h5 class="modal-title"><i class="fas fa-file-pdf"></i>&nbsp;&nbsp;&nbsp;DOCUMENTOS DEL USUARIO: <label id="usuario_doc" style="font-weight: 400;"></label> </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="tbl_doc"></div>
          <input type="hidden" id="nombre_user">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
            <!-- <button id="btn_todos" name="actualiza_requisicion" value="" class="btn btn-info"><i class="fas fa-cloud-download-alt"></i>&nbsp;&nbsp;Todos</button> -->
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/users/info_users_v2.js"></script>
<?= $this->endSection() ?>