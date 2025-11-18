<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Solicitud de Liberación
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<style>
  .btn-outline-tipo {
    color: #444444;
    border-color: #444444;
    border-radius: 30px;
    box-shadow: none;
    font-weight: 400 !important;
    padding: 0.4rem 2rem;
  }

  .btn-outline-tipo:not(:disabled):not(.disabled).active,
  .btn-outline-tipo:not(:disabled):not(.disabled):active,
  .show>.btn-outline-tipo.dropdown-toggle {
    color: #fff;
    background-color: #444444;
    border-color: #444444;
  }

  .btn-outline-tipo:hover {
    color: #fff;
    background-color: #444444;
    text-decoration: none;
  }

  div.btn-group1.btn {
    padding: 0.375rem rem 2rem !important;
  }

  .btn-tipo {
    background-color: #444;
    border-color: #444;
    border-radius: 30px;
    box-shadow: none;
    color: #FFFFFF;
    font-weight: 400 !important;
    padding: 0.4rem 2rem;
  }

  .btn-tipo:hover {
    color: #d0d8df;
    text-decoration: none;
  }

  .btn-tipo:not(:disabled):not(.disabled).active,
  .btn-tipo:not(:disabled):not(.disabled):active,
  .show>.btn-tipo.dropdown-toggle {
    color: #fff;
    background-color: #1f2d3d;
    border-color: #262626;
    border-radius: 30px;

  }

  .btn-primary:not(:disabled):not(.disabled).active,
  .btn-primary:not(:disabled):not(.disabled):active,
  .show>.btn-primary.dropdown-toggle {
    color: #fff;
    background-color: #1f2d3d;
    border-color: #1f2d3d;
  }

  .no-check {
    width: 90%;
    height: 36px;
    margin-top: 3px;
    margin-bottom: 5px !important;
    background-color: #3e4744;
    padding-top: 5px;
    color: #fff;
  }

  .check {
    width: 90%;
    height: 36px;
    margin-top: 3px;
    margin-bottom: 5px !important;
    padding-top: 5px;
    background-color: #1f2d3d;
    color: #E9ECEF;
  }

  .custom-file-label::after {
    content: "Subir";
  }



  div.forms button,
  input {
    font-family: "Montserrat", "Helvetica Neue", Arial, sans-serif;
  }

  div.forms a {
    color: #f96332;
  }

  div.forms a:hover,
  a:focus {
    color: #f96332;
  }

  div.forms p {
    line-height: 1.61em;
    font-weight: 300;
    font-size: 1.2em;
  }

  div.forms.category {
    text-transform: capitalize;
    font-weight: 400;
    color: #9A9A9A;
  }

  div.forms .body {
    color: #2c2c2c;
    font-size: 14px;
    /* font-family: "Montserrat", "Helvetica Neue", Arial, sans-serif; */
    overflow-x: hidden;
    -moz-osx-font-smoothing: grayscale;
    -webkit-font-smoothing: antialiased;
  }

  div.forms .nav-item .nav-link,
  .nav-tabs .nav-link {
    -webkit-transition: all 300ms ease 0s;
    -moz-transition: all 300ms ease 0s;
    -o-transition: all 300ms ease 0s;
    -ms-transition: all 300ms ease 0s;
    transition: all 300ms ease 0s;
  }

  div.forms .card a {
    -webkit-transition: all 150ms ease 0s;
    -moz-transition: all 150ms ease 0s;
    -o-transition: all 150ms ease 0s;
    -ms-transition: all 150ms ease 0s;
    transition: all 150ms ease 0s;
  }

  [data-toggle="collapse"][data-parent="#accordion"] i {
    -webkit-transition: transform 150ms ease 0s;
    -moz-transition: transform 150ms ease 0s;
    -o-transition: transform 150ms ease 0s;
    -ms-transition: all 150ms ease 0s;
    transition: transform 150ms ease 0s;
  }

  [data-toggle="collapse"][data-parent="#accordion"][aria-expanded="true"] i {
    filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg);
  }


  div.forms .now-ui-icons {
    display: inline-block;
    font: normal normal normal 14px/1 'Nucleo Outline';
    font-size: inherit;
    speak: none;
    text-transform: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  @-webkit-keyframes nc-icon-spin {
    0% {
      -webkit-transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
    }
  }

  @-moz-keyframes nc-icon-spin {
    0% {
      -moz-transform: rotate(0deg);
    }

    100% {
      -moz-transform: rotate(360deg);
    }
  }

  @keyframes nc-icon-spin {
    0% {
      -webkit-transform: rotate(0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
      transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
      -moz-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
      -o-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }

  .now-ui-icons.objects_umbrella-13:before {
    content: "\ea5f";
  }

  .now-ui-icons.shopping_cart-simple:before {
    content: "\ea1d";
  }

  .now-ui-icons.shopping_shop:before {
    content: "\ea50";
  }

  .now-ui-icons.ui-2_settings-90:before {
    content: "\ea4b";
  }

  div.forms .nav-tabs {
    border: 0;
    padding: 15px 0.7rem;
  }

  div.forms .nav-tabs:not(.nav-tabs-neutral)>.nav-item>.nav-link.active {
    box-shadow: 0px 5px 35px 0px rgba(0, 0, 0, 0.3);
  }

  div.forms .card .nav-tabs {
    border-top-right-radius: 0.1875rem;
    border-top-left-radius: 0.1875rem;
  }

  div.forms .nav-tabs>.nav-item>.nav-link {
    color: #888888;
    margin: 0;
    margin-right: 5px;
    background-color: transparent;
    border: 1px solid transparent;
    border-radius: 30px;
    font-size: 14px;
    padding: 11px 23px;
    line-height: 1.5;
  }

  div.forms .nav-tabs>.nav-item>.nav-link:hover {
    background-color: transparent;
  }

  div.forms .nav-tabs>.nav-item>.nav-link.active {
    background-color: #444;
    border-radius: 30px;
    color: #FFFFFF;
  }

  div.forms .nav-tabs>.nav-item>.nav-link i.now-ui-icons {
    font-size: 14px;
    position: relative;
    top: 1px;
    margin-right: 3px;
  }

  div.forms .nav-tabs.nav-tabs-neutral>.nav-item>.nav-link {
    color: #FFFFFF;
  }

  div.forms .nav-tabs.nav-tabs-neutral>.nav-item>.nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    color: #FFFFFF;
  }

  div.forms .card {
    border: 0;
    border-radius: 0.1875rem;
    display: inline-block;
    position: relative;
    width: 100%;
    margin-bottom: 30px;
    box-shadow: 0px 0px 1px 0px rgba(0, 0, 0, 0.2);
  }

  div.forms .card .card-header {
    background-color: transparent;
    border-bottom: 0;
    background-color: transparent;
    border-radius: 0;
    padding: 0;
  }

  div.forms .card[data-background-color="orange"] {
    background-color: #f96332;
  }

  div.forms .card[data-background-color="red"] {
    background-color: #FF3636;
  }

  div.forms .card[data-background-color="yellow"] {
    background-color: #FFB236;
  }

  div.forms .card[data-background-color="blue"] {
    background-color: #2CA8FF;
  }

  div.forms .card[data-background-color="green"] {
    background-color: #15b60d;
  }

  [data-background-color="orange"] {
    background-color: #e95e38;
  }

  [data-background-color="black"] {
    background-color: #2c2c2c;
  }

  [data-background-color]:not([data-background-color="gray"]) {
    color: #FFFFFF;
  }

  [data-background-color]:not([data-background-color="gray"]) p {
    color: #FFFFFF;
  }

  [data-background-color]:not([data-background-color="gray"]) a:not(.btn):not(.dropdown-item) {
    color: #FFFFFF;
  }

  [data-background-color]:not([data-background-color="gray"]) .nav-tabs>.nav-item>.nav-link i.now-ui-icons {
    color: #FFFFFF;
  }


  @font-face {
    font-family: 'Nucleo Outline';
    src: url("https://github.com/creativetimofficial/now-ui-kit/blob/master/assets/fonts/nucleo-outline.eot");
    src: url("https://github.com/creativetimofficial/now-ui-kit/blob/master/assets/fonts/nucleo-outline.eot") format("embedded-opentype");
    src: url("https://raw.githack.com/creativetimofficial/now-ui-kit/master/assets/fonts/nucleo-outline.woff2");
    font-weight: normal;
    font-style: normal;

  }

  .now-ui-icons {
    display: inline-block;
    font: normal normal normal 14px/1 'Nucleo Outline';
    font-size: inherit;
    speak: none;
    text-transform: none;
    /* Better Font Rendering */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  footer {
    margin-top: 50px;
    color: #555;
    background: #fff;
    padding: 25px;
    font-weight: 300;
    background: #f7f7f7;

  }

  div.forms .footer p {
    margin-bottom: 0;
  }

  footer p a {
    color: #555;
    font-weight: 400;
  }

  footer p a:hover {
    color: #e86c42;
  }


  @media screen and (max-width: 768px) {

    div.forms .nav-tabs {
      display: inline-block;
      width: 100%;
      padding-left: 100px;
      padding-right: 100px;
      text-align: center;
    }

    div.forms .nav-tabs .nav-item>.nav-link {
      margin-bottom: 5px;
    }
  }

  label:not(.form-check-label):not(.custom-file-label) {
    font-weight: 400;
  }

  .btn-circle {
    width: 45px;
    height: 45px;
    padding: 6px 0px;
    border-radius: 24px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
  }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Solicitud de Liberación</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">DashBoard</a></li>
            <li class="breadcrumb-item active">Solicitud de Liberación</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Solicitud</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body col-md-12">
          <div class="container-fluid">
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="viaje_num_nomina">Num de Nómina</label>
                <input type="text" class="form-control rounded-0" id="viaje_num_nomina" name="viaje_num_nomina" value="<?= session()->payroll_number; ?>" readonly>
              </div>
              <div class="form-group col-md-3">
                <label for="viaje_usuario">Solicitante</label>
                <input type="text" class="form-control rounded-0" id="viaje_usuario" name="viaje_usuario" value="<?= strtoupper(session()->name . " " . session()->surname); ?>" readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="viaje_departamento">Departamento</label>
                <input type="text" class="form-control rounded-0" id="viaje_departamento" name="viaje_departamento" value="<?= session()->departament; ?>" readonly>
              </div>
              <!-- <div class="form-group col-md-2">
                <label for="centro_costo">Centro de Costo</label>
                <input type="text" class="form-control rounded-0" id="centro_costo" name="centro_costo" value="<?= session()->cost_center; ?>" readonly>
              </div> -->
              <div class="form-group col-md-3">
                <label for="viaje_puesto_trabajo">Puesto</label>
                <input type="text" class="form-control rounded-0" id="viaje_puesto_trabajo" name="viaje_puesto_trabajo" value="<?= session()->job_position; ?>" readonly>
              </div>
            </div>
            <hr>
            <h3>Información del departamento de TECNOLOGÍA DE LA INFORMACIÓN</h3>
            <!-- Estos pueden ser varios -->
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="viaje_num_nomina">Equipo</label>
                <input type="text" class="form-control rounded-0" id="viaje_num_nomina" name="viaje_num_nomina" value="<?= session()->payroll_number; ?>" readonly>
              </div>
              <div class="form-group col-md-3">
                <label for="viaje_usuario">Numero de serie</label>
                <input type="text" class="form-control rounded-0" id="viaje_usuario" name="viaje_usuario" value="<?= strtoupper(session()->name . " " . session()->surname); ?>" readonly>
              </div>
              <div class="form-group col-md-4">
                <label for="viaje_departamento">Status del equipo</label>
                <input type="text" class="form-control rounded-0" id="viaje_departamento" name="viaje_departamento" value="<?= session()->departament; ?>" readonly>
              </div>
              <div class="form-group col-md-3">
                <label for="viaje_puesto_trabajo">Comentarios</label>
                <input type="text" class="form-control rounded-0" id="viaje_puesto_trabajo" name="viaje_puesto_trabajo" value="<?= session()->job_position; ?>" readonly>
              </div>
            </div>
          </div>
          <div id="forms" class="forms mt-1">
            <div class="row">
              <div class="col-md-12 ml-auto col-xl-12 mr-auto">
                <div class="card">
                  <!-- <div class="card-header">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#profile" role="tab" onclick="changeForm(1)">
                          Viáticos
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#messages" role="tab" onclick="changeForm(2)">
                          Gastos
                        </a>
                      </li>
                    </ul>
                  </div> -->
                  <div class="card-body">
                    <div class="tab-content text-center">
                      <div class="tab-pane active" id="profile" role="tabpanel">
                        <form id="form_solicitud_viatico" method="post">
                          <!-- <div class="form-row">
                            <div class="col-md-3">
                              <input type="hidden" name="tipo_viaje" id="tipo_viaje">
                              <label>Tipo de Viaje</label>
                              <div class="btn-group-toggle" data-toggle="buttons">
                                <label for="radio_1" class="btn btn-outline-tipo btn-option">
                                  <input type="radio" id="radio_1" onclick="tipoViaje(1)"> Nacional
                                </label>
                                <label for="radio_2" class="btn btn-outline-tipo btn-option">
                                  <input type="radio" id="radio_2" onclick="tipoViaje(2)"> Internacional
                                </label>
                              </div>
                              <div id="error_tipo_viaje" class="text-center text-danger"></div>
                            </div>
                            <div id="resultado_internacional" class="div-option"></div>
                            <div class="col-md-3">
                              <input type="hidden" name="jerarquia" id="jerarquia">
                              <label>Viaja con Diferente Nivel Jerárquico</label>
                              <div class="btn-group-toggle" data-toggle="buttons">
                                <label for="radio_3" class="btn btn-outline-tipo btn-option">
                                  <input type="radio" id="radio_3" onclick="tipoNivel(2)"> SI
                                </label>
                                <label for="radio_4" class="btn btn-outline-tipo btn-option">
                                  <input type="radio" id="radio_4" onclick="tipoNivel(1)"> NO
                                </label>
                              </div>
                              <div id="error_jerarquia" class="text-center text-danger"></div>
                            </div>
                            <div id="div_otra_jerarquia" class="div-option"></div>
                          </div> -->
                          <hr>
                          <!-- <div class="form-row">
                            <div class="form-group col-md-3">
                              <label for="inicio_viaje">Inicio del Viaje</label>
                              <input type="date" class="form-control" id="inicio_viaje" name="inicio_viaje" onchange="limpiarError(this),calcularViaticos()">
                              <div id="error_inicio_viaje" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                              <label for="regreso_viaje">Regreso del Viaje</label>
                              <input type="date" class="form-control" id="regreso_viaje" name="regreso_viaje" onchange="limpiarError(this),calcularViaticos()">
                              <div id="error_regreso_viaje" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                              <label for="origen_viaje">Origen del Viaje</label>
                              <input type="text" class="form-control" id="origen_viaje" name="origen_viaje" value="" onchange="limpiarError(this)">
                              <div id="error_origen_viaje" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                              <label for="destino_viaje">Destino del Viaje</label>
                              <input type="text" class="form-control" id="destino_viaje" name="destino_viaje" value="" onchange="limpiarError(this)">
                              <div id="error_destino_viaje" class="text-danger"></div>
                            </div>
                          </div> -->
                          <!-- <div class="form-row">
                            <div class="form-group col-md-3">
                              <label>Viaje en Avión</label>
                              <input type="hidden" name="avion" id="avion">
                              <div class=" btn-group-toggle" data-toggle="buttons">
                                <label for="avion_si" class="btn btn-outline-tipo btn-option">
                                  <input type="radio" id="avion_si" onclick="vueloOpc(1)"> SI
                                </label>
                                <label for="avion_no" class="btn btn-outline-tipo btn-option">
                                  <input type="radio" id="avion_no" onclick="vueloOpc(2)"> NO
                                </label>
                              </div>
                              <div id="error_avion" class="text-center text-danger"></div>
                            </div>
                            <div id="resultado_avion_1" class="col-md-3 div-option"></div>
                            <div id="resultado_avion_2" class="col-md-3 div-option"></div>
                          </div> -->
                          <!-- <div id="calculo_viaticos" class="div-option" style="font-size:1.5rem;"></div>
                          <hr>
                          <input type="hidden" id="total_viaticos" name="total_viaticos">
                          <input type="hidden" id="divisa_viaticos" name="divisa_viaticos">
                          <div class="form-group col-md-12 mb-5">
                            <label for="detalle_viaje">Detallar Motivo del Viaje:</label>
                            <textarea class="form-control" cols="30" rows="3" id="detalle_viaje" name="detalle_viaje" onchange="limpiarError(this)"></textarea>
                            <div id="error_detalle_viaje" class="text-danger"></div>
                          </div> -->
                          <button id="btn_solicitud_viatico" type="submit" class="btn btn-guardar btn-lg btn-block">Generar</button>
                        </form>
                      </div>

                      <!-- <div class="tab-pane" id="messages" role="tabpanel">
                        <form id="form_gastos" method="post">
                          <div class="row justify-content-center">
                            <div class="form-group col-md-3">
                              <label for="monto_gasto">Monto</label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" class="form-control" id="monto_gasto" name="monto_gasto" onchange="limpiarError(this)">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">MXN</span>
                                </div>
                              </div>
                              <div id="error_monto_gasto" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                              <label for="inicio_gastos">Inicio</label>
                              <input type="date" class="form-control" id="inicio_gastos" name="inicio_gastos" onchange="limpiarError(this)">
                              <div id="error_inicio_gastos" class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-3">
                              <label for="regreso_gastos">Termino</label>
                              <input type="date" class="form-control" id="regreso_gastos" name="regreso_gastos" onchange="limpiarError(this)">
                              <div id="error_regreso_gastos" class="text-danger"></div>
                            </div>
                          </div>
                          <div class="row justify-content-center">
                            <div class="form-group col-md-9">
                              <label for="motivo_gasto">Motivo</label>
                              <textarea cols="30" rows="5" class="form-control" id="motivo_gasto" name="motivo_gasto" onchange="limpiarError(this)"></textarea>
                              <div id="error_motivo_gasto" class="text-danger"></div>
                            </div>
                          </div>
                          <button id="btn_gastos" type="submit" class="btn btn-guardar btn-lg btn-block">Generar</button>
                        </form>
                      </div> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </form>
      <div class="card-footer">
        <a href="#">Solicitud de Liberación</a>
      </div>
    </div>
</div>
</section>

</div>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<!-- AdminLTE for demo purposes -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/public/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- <script src="<?= base_url() ?>/public/js/travels/travel_index_v2.js"></script> -->

<?= $this->endSection() ?>