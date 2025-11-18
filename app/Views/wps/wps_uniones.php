<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Uniones
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
                    <h1 class="m-0">Uniones Permanentes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
                        <li class="breadcrumb-item active">Uniones</li>
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
                    <h3 class="card-title">Uniones Permanentes</h3>
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
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group col-md-6"><label for="grado_material">Grado del Material</label><input type="search" class="form-control" id="grado_material" name="grado_material" value=""></div>
                            <div class="form-group col-md-6"><label for="np">No. P</label><input type="search" class="form-control" id="num_p" name="num_p" value=""></div>
                            <div class="form-group col-md-12">
                                <table id="tabla" class="table table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">No P</th>
                                            <th scope="col">Grupo</th>
                                            <th scope="col">Espec. ASTM</th>
                                            <th scope="col">Grado</th>
                                            <th scope="col">No P</th>
                                            <th scope="col">Grupo</th>
                                            <th scope="col">Espec. ASTM</th>
                                            <th scope="col">Grado</th>
                                            <th scope="col">SMAW</th>
                                            <th scope="col">GMAW</th>
                                            <th scope="col">FCAW</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($uniones as $key => $value) { ?>
                                        
                                       
                                        <tr>
                                            <th scope="row"><?=$value["base_num_parte"]?></th>
                                            <td><?=$value["base_grupo"]?></td>
                                            <td><?=$value["base_grado"]?></td>
                                            <td><?=$value["extremo_num_parte"]?></td>
                                            <td><?=$value["extremo_grupo"]?></td>
                                            <td><?=$value["extremo_espec_astm"]?></td>
                                            <td><?=$value["extremo_grado"]?></td>
                                            <td><?=$value["smaw"]?></td>
                                            <td><?=$value["gmaw"]?></td>
                                            <td><?=$value["gtaw"]?></td>
                                            <td><?=$value["fcaw"]?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="#">Material Base</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/wps/uniones.js"></script>
<?= $this->endSection() ?>