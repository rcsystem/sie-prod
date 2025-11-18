<?php ?>
<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Mis Usuarios
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .toggle {
    position: relative;
    box-sizing: border-box;
    padding: inherit;
  }

  .toggle input[type="checkbox"] {
    position: absolute;
    left: 0;
    top: 0;
    z-index: 10;
    width: 56%;
    height: 100%;
    cursor: pointer;
    opacity: 0;
  }

  .toggle label {
    position: relative;
    display: flex;
    align-items: center;
    box-sizing: border-box;
  }

  .toggle label:before {
    content: '';
    width: 40px;
    height: 22px;
    background: #ccc;
    position: relative;
    display: inline-block;
    border-radius: 46px;
    box-sizing: border-box;
    transition: 0.2s ease-in;
  }

  .toggle label:after {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    left: 2px;
    top: 2px;
    z-index: 2;
    background: #fff;
    box-sizing: border-box;
    transition: 0.2s ease-in;
  }

  .toggle input[type="checkbox"]:checked+label:before {
    background: #4BD865;
  }

  .toggle input[type="checkbox"]:checked+label:after {
    left: 19px;
  }
</style>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Mis Usuarios | Direccion</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Mis Usuarios</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Usuarios</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12" style="text-align: end;">
              <button type="button" id="off_all" class="btn btn-guardar">Desactivar Todos</button>
            </div>
          </div>
          <table id="tabla_usuarios_permisos" class="table table-bordered table-striped dataTable display" cellspacing="0" role="grid" aria-describedby="usuarios_info" style="width:100%" ref="">
          </table>
        </div>
        <div class="card-footer">
          <a href="#">Permisos</a>
        </div>
      </div>
    </div>
  </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
<script src="<?= base_url() ?>/public/js/permissions/permissions_administration_v1.js"></script>
<?= $this->endSection() ?>