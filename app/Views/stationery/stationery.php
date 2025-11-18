<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitud de Papelería
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .btn-item {
    color: #fff;
    background-color: #640e0e;
    border-color: #640e0e;
    box-shadow: none;
  }

  .botoncito {
    position: relative;
    display: inline-block;
    width: 3.6rem;
    height: 2rem;
  }

  .botoncito input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .deslizadora {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #DA1A22;
    transition: .4s;
  }

  .deslizadora:before {
    position: absolute;
    content: "";
    height: 1.6rem;
    width: 1.6rem;
    left: 0.2rem;
    bottom: 0.2rem;
    background-color: white;
    transition: .4s;
  }

  input:checked+.deslizadora {
    background-color: #34a853;
  }

  input:checked+.deslizadora:before {
    transform: translateX(1.6rem);
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <?php if (session()->id_user == 27 || session()->id_user == 903 || session()->id_user == 1283 || session()->id_user == 1156 || session()->id_user == 1) { ?>
        <div class="row">
          <div class="col-sm-6">
            <label class="m-0" style="font-size:2rem;Font-family:inherit;font-weight:500;line-height:1.2;color: inherit;">Papelería</label>
            <label class="botoncito" style="padding-top:11px;margin-left:1rem;">
              <input type="checkbox" id="status_form">
              <span class="deslizadora"></span>
            </label>
            <label id="lbl_form" style="margin-left:1rem;"></label>
          </div>
          <div class="col-sm-6" style="padding-top:1rem;">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
              <li class="breadcrumb-item"><a href="#">Papelería</a></li>
            </ol>
          </div>
        </div>
      <?php } else { ?>
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0">Papelería</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
              <li class="breadcrumb-item"><a href="#">Papelería</a></li>
            </ol>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Solicitud de papelería</h3>
          <!-- <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div> -->
        </div>
        <div class="card-body">
          <form id="solicitud_papeleria" method="post" enctype="multipart/form-data">

            <div class="form-row">
              <?php foreach ($datos as $key => $value) { ?>

                <div class="form-group col-md-3">
                  <label for="num_nomina">Numero de Nomina</label>
                  <input type="text" class="form-control rounded-0" id="num_nomina" name="num_nomina" value="<?= $value->payroll_number ?>" required readonly />
                </div>
                <div class="form-group col-md-3">
                  <label for="nombre_pape">Nombre</label>
                  <input type="text" class="form-control rounded-0" id="nombre_pape" name="nombre_pape" value="<?= $value->name . " " . $value->surname . " " . $value->second_surname ?>" required readonly />
                </div>
                <div class="form-group col-md-3">
                  <label for="email_pape">Correo</label>
                  <input type="email" class="form-control rounded-0" id="email_pape" name="email_pape" value="<?= $value->email ?>" required readonly />
                </div>
                <div class="form-group col-md-3">
                  <label for="centro_costo">Centro de Costo</label>
                  <input type="text" class="form-control rounded-0" id="centro_costo" name="centro_costo" value="<?= $value->clave_cost_center ?>" required readonly />
                </div>
                <div class="form-group col-md-3">
                  <label for="depto">Departamento</label>
                  <input type="text" class="form-control rounded-0" id="depto_pape" name="depto_pape" value="<?= $value->departament ?>" required readonly />
                </div>
              <?php } ?>

            </div>
            <div class="row">
              <div class="btn-papeleria col-md-5">

                <button type="submit" id="create-account-button" class="btn btn-guardar btn-lg"> Generar Solicitud</button>
                <button id="btn-agregar-item" class="btn btn-item btn-lg" type="button"> Agregar Item</button>

              </div>
              <div class="col-md-7">
                <label for="observaciones">Observaciones</label>
                <textarea class="form-control" name="observaciones" id="observaciones" cols="4" rows="3" placeholder="Aqui se podra agregar Articulos que no cuenten con existencia o articulos Nuevos pero sean prioridad pedirlos"></textarea>
              </div>
            </div>
            <hr>
            <div class="text-center form-group col-md-12">
              <label style="color:#A31F18;"> *Las Entregas son los dias Viernes de 11:00am a 12:30pm en recepción. </label>
              <br>
              <label style="color:#A31F18;"> *No olvidar al recoger sus pedidos llevar sus repuestos de plumas o marcadores </label>

            </div>
            <div id="resultado" class="form-group error col-md-12"></div>


            <br>
            <div id="form_duplica" class="row">
              <div id="duplica" class="agrega-item col-md-12">
                <div id="item-duplica" class=""></div>
              </div>
              <div id="product" class="col-md-12">
                <div id="item-card_1" class="card">
                  <div id="header-car" class="card-header"><span id="title-item">Agregar Item</span></div>
                  <div class="card-body row">
                    <div class="form-group  col-md-4">
                      <label>Categoría</label>
                      <select name="categoria[]" id="categoria_1" class="form-control" onchange="escuchar(1)">
                        <option value="">Seleccionar Opción</option>
                        <?php foreach ($catalogo as $key => $value) { ?>
                          <option value="<?= $value->id_cat ?>"><?= $value->category ?></option>
                        <?php } ?>
                      </select>
                      <div id="error_categoria_1" name="error_categoria[]" class="text-danger"></div>
                      <div class="help-block with-errors"></div>
                    </div>
                    <div id="one" class="form-group  col-md-4">
                      <label>Descripción</label>
                      <select name="descripcion[]" id="descripcion_1" class="form-control" onchange="cambioImagen(1)">
                        <option value="">Seleccionar Opción</option>
                      </select>
                      <div id="error_descripcion_1" name="error_descripcion[]" class="text-danger"></div>
                    </div>
                    <div class="form-group col-md-2">
                      <label>Cantidad</label>
                      <input type="number" pattern="[1-9]" id="cantidad_1" name="cantidad[]" class="form-control" onkeypress="return validaNumericos(event)" min="1" onchange="consultaInventario(1)">
                      <div id="resultado_1" class=" text-danger"></div>
                      <div id="error_cantidad_1" name="error_cantidad[]" class="text-danger"></div>
                    </div>
                    <div class="form-group col-md-2">
                      <label>Unidad de Medida</label>
                      <p id="unidad_1"></p>
                      <input type="hidden" id="medida_1" name="medida[]" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="imagen">Imagen</label>
                      <img id="imagen_1" name="imagen[]" src="" alt="" width="" height="" class="">
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="hidden" id="estado"></div>
                    <div id="observacion_1">
                    </div>
                  </div>
                  <div class="card-footer text-muted"><a href="#">Item Papelería</a></div>
                </div>
              </div>
            </div>
            
          </form>
        </div>

        <div class="card-footer">
          <a href="#">Solicitudes</a>
        </div>
      </div>
    </div>
  </section>
</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<!-- AdminLTE for demo purposes -->
<script src="<?= base_url() ?>/public/js/stationery/index_generate_v2.js"></script>
<?= $this->endSection() ?>