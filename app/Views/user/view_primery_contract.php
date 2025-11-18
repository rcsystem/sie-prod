<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Personal Eventual
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .card-header {
    background-color: #910c01; /* #980d01; #7a0b02*/
    color: #f8f9fa;
}
.card-primary.card-outline {
     border-top: 0px solid #007bff; 
}
</style>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2 class="m-0">Primer Contrato</h2>
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
  
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
  
        <!-- /.col -->
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Generar Nuevo Contrato</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body col-auto p-5 ">
            <form id="contrato_temp" method="post">
              <div class="row">
                <div class="form-group col-md-4">
                  <img style="width: 400px;height:100px;" src="<?= base_url("public/images/inval.png"); ?>" alt="">
                </div>
                <div class="form-group col-md-8 text-right">
                  <div style="margin-top: 1rem;">
                    <h4 style="text-decoration: underline;">PRIMER CONTRATO</h4>
                  </div>
                </div>
                <div class="form-group col-md-12 text-left">
                  <div style="margin-top: 1rem;">
                    <h5><b>INDUSTRIAL DE VALVULAS, S.A DE C.V.</b></h5>
                  </div>
                </div>
                <input type="hidden" id="gerente" name="gerente" value="<?= session()->id_user ?>">
                <input type="hidden" id="id_user" name="id_user" value="<?= $temporal[0]["id_user"] ;?>">
                <input type="hidden" id="tipo" name="tipo" value="<?= $temporal[0]["type_of_employee"] ;?>">
                 
                <div class="form-group col-md-4">
                  <input type="text" class="form-control rounded-0" id="puesto" name="puesto" value="<?= $temporal[0]["name"] . " " . $temporal[0]["surname"]; ?>"readonly>
                </div>
                <div class="form-group col-md-4">
                  <input type="text" class="form-control rounded-0" id="usuario" name="usuario" value="<?= $temporal[0]["job"]; ?>"readonly>
                </div>
                <div class="form-group col-md-4">
                  <input type="date" class="form-control rounded-0" id="fecha" name="fecha" value="<?= $fcha = date("Y-m-d"); ?>"readonly>
                </div>
                <div class="form-group col-md-6">
                  <input type="text" class="form-control rounded-0" id="manager" name="manager" value="<?= session()->name." ".session()->surname; ?>" readonly>
                </div>
                <div class="form-group col-md-6">
                  <input type="text" class="form-control rounded-0" id="depto" name="depto" value="<?= $temporal[0]["departament"]; ?>" readonly>
                </div>
                               
              </div>
              <div class="form-group col-md-12" style="margin-top: 1rem;">
              <div id="error_opcion"></div>
              <div class="form-group col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="opcion" id="opcion_1" value="1" >
                    <label class="form-check-label" for="opcion_1" style="cursor: pointer;">
                    Formular contrato por Tiempo Indeterminado (Planta)
                    </label>
                  </div>
                </div>
                <div class="form-group col-md-12">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="opcion" id="opcion_2" value="2" >
                    <label class="form-check-label" for="opcion_2" style="cursor: pointer;">
                    Formular contrato por tiempo Determinado u Obra Determinada, de   
                    <select name="contrato" id="contrato" >
                      <option value="">Seleccionar..</option>
                      <option value="2">30 días</option>
                      <option value="3">60 días</option>
                      <option value="4">90 días</option>
                    </select>               
                    </label>
                    <div id="error_opcion2" class="text-danger"></div>
                  </div>
                </div>
                <!-- <div class="form-group col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="opcion" id="opcion_3" value="3" >
                    <label class="form-check-label" for="opcion_3" style="cursor: pointer;">
                    Proceder a dar de baja a la persona mencionada.
                    </label>
                  </div>
                </div> -->
              </div>
              
            
            
            <div class="form-group col-md-12" style="margin-top: 1rem;">
            <h5> <p>Observaciones: </p></h5>
            <textarea name="observaciones" id="observaciones" cols="2" rows="2" class="form-control" ></textarea>
            </div>
            
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" id="guardar_contrato"  name="guardar_contrato" class="btn btn-guardar btn-block btn-lg" ><i class="fas fa-file-signature"></i> AUTORIZACIÓN</button>
              
            </div>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
      </form>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <?= $this->endSection() ?>
  <?= $this->section('js') ?>

  <!-- AdminLTE for demo purposes -->
  <script src="<?= base_url() ?>/public/plugins/md5/jquery.md5.min.js"></script>
  <script src="<?= base_url() ?>/public/js/users/primery_contract_v1.js"></script>
  <?= $this->endSection() ?>