<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Usuarios | Tiempos Obcuros
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

    .custom-file-label::after {
        content: "Subir";
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Usuarios | Tiempos Obscuros</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Proveedores</li>
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
                    <h3 class="card-title">Horario Obscuro</h3>
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
                        <table id="tabla_tiempos_extras" class="table table-bordered table-striped " role="grid" aria-describedby="suministros_info" style="width:100%" ref="">

                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="#">Usuarios</a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="modal fade" id="user_Modal" tabindex="-1" aria-labelledby="emergencyContactModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header" style="margin-bottom: -1px;">
                        <h5 class="modal-title" id="modat_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal_body">
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
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.js"></script>
<script src="<?= base_url() ?>/public/js/users/tabla_tiempos_extra_v1.js"></script>
<?= $this->endSection() ?>