<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Personal Eventual
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <?php if (session()->id_user == '267') {?>
                    <h1 class="m-0">Personal Administrativo Eventual</h1>
                    <?php } else if (session()->id_user == '627') {?>
                    <h1 class="m-0">Personal Sindicalizado Eventual</h1>
                    <?php } else {?>
                    <h1 class="m-0">Personal Eventual</h1>
                    <?php }?>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">            
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Usuarios</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <!-- 
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabla_usuarios_temp" class="table table-bordered table-striped " role="grid" aria-describedby="usuarios_temp" style="width:100%" ref="">
                    </table>
                </div>

                <div class="card-footer">
                    <a href="#">Personal Eventual</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/users/contracts_all_v3.js"></script>
<?= $this->endSection() ?>