<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>
Personal Eventual
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .card-header {
    background-color: #910c01;
    /* #980d01; #7a0b02*/
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
          <h2 class="m-0">Personal Eventual: <?php echo $temporal[0]["name"] . " " . $temporal[0]["surname"]; ?></h2>
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
  <input type="hidden" id="gerente" name="gerente" value="<?php //$gerente 
                                                          ?>">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <!--  <a href="mailbox.html" class="btn btn-primary btn-block mb-3">Regresar</a>
 -->
          <?php
          $cont30 = 0;
          $cont60 = 0;
          $cont90 = 0;
          $contFinal = 0;
          $cont = 0;
          $contratos_30 = array();
          $contratos_60 = [];
          $contratos_90 = [];
          $contratoFinal = [];
          $keys = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';
          $active = "";
          foreach ($temporal as $key => $value) {
            switch ($value["type_of_contract"]) {
              
              case '1':
                $active = "";
                break;
              case '2':
                $cont30++;
                $tipo_contrato = "30 días";
                $datosContrato30 = [
                  "id_contrato" => $value["id_contract"],
                  "fecha_entrada" => $value["date_of_new_entry"],
                  "fecha_termino" => $value["date_expiration"]
                ];
                array_push($contratos_30, $datosContrato30);
                break;

              case '3':
                $cont60++;
                $tipo_contrato = "60 días";
                $datosContrato60 = [
                  "id_contrato" => $value["id_contract"],
                  "fecha_entrada" => $value["date_of_new_entry"],
                  "fecha_termino" => $value["date_expiration"]
                ];
                array_push($contratos_60, $datosContrato60);
                break;

              case '4':
                $cont90++;
                $tipo_contrato = "90 días";
                $datosContrato90 = [
                  "id_contrato" => $value["id_contract"],
                  "fecha_entrada" => $value["date_of_new_entry"],
                  "fecha_termino" => $value["date_expiration"]
                ];
                array_push($contratos_90, $datosContrato90);
                break;

              default:
                $contFinal++;
                $tipo_contrato = "Error Contactar al Administrador";
                if ($value["option"] == 1) {
                  $tipo_contrato = "Planta";
                } elseif ($value["option"] == 3) {
                  $tipo_contrato = "Baja";
                }
                $datosFinales = [
                  "id_contrato" => $value["id_contract"],
                  "fecha_entrada" => $value["date_of_new_entry"],
                  'estado' =>  $value["direct_authorization"]
                ];
                array_push($contratoFinal, $datosFinales);
                $active = "disabled";
                break;
            }
            setlocale(LC_TIME, "spanish");

            if ($value["date_expiration"] == "0000-00-00") {
              $fecha_vencimiento = 'INDEFINIDAMENTE';
            } else {
              $mi_fecha = $value["date_expiration"];
              $mi_fecha = str_replace("/", "-", $mi_fecha);
              $Nueva_Fecha = date("d-m-Y", strtotime($mi_fecha));
              $fecha_vencimiento = strftime("%A, %d de %B de %Y", strtotime($Nueva_Fecha));
              //ejemplo devuelve: lunes, 16 de abril de 2022
            }
            $cont++;
          }
          $fecha_reingreso = $cont - 1;
          $date = date("Y-m-d");
          $fecha1 = new DateTime($value["date_admission"]);
          $fecha2 = new DateTime($date);
          $fecha = $fecha1->diff($fecha2);
          $tiempo = "";
          //años
          if ($fecha->y > 0) {
            $tiempo .= $fecha->y;

            if ($fecha->y == 1)
              $tiempo .= " año, ";
            else
              $tiempo .= " años, ";
          }
          //meses
          if ($fecha->m > 0) {
            $tiempo .= $fecha->m;

            if ($fecha->m == 1)
              $tiempo .= " mes, ";
            else
              $tiempo .= " meses, ";
          }
          //dias
          if ($fecha->d > 0) {
            $tiempo .= $fecha->d;
            if ($fecha->d == 1)
              $tiempo .= " día ";
            else
              $tiempo .= " días ";
          }
          ?>
          <?php if ($cont30 > 0) { ?>

            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Contratos de 30 días</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">

                  <?php $num_contrato_30 = 1;
                  foreach ($contratos_30 as $key => $value) {  ?>
                    <li class="nav-item">
                      <a class="nav-link" href="<?= base_url('usuarios/ver-contrato/' . md5($keys . $value["id_contrato"])) ?>" target="_blank"><i class="far fa-file-alt text-danger">
                        </i> Contrato: <?= $num_contrato_30 ?></a>
                      <span class="dropdown-header"> Inicio: <?= $value["fecha_entrada"] ?> | Fin: <?= $value["fecha_termino"] ?></span>

                    </li>
                  <?php $num_contrato_30++;
                  } ?>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          <?php  } ?>
          <?php if ($cont60 > 0) { ?>
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Contratos de 60 días</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <?php $num_contrato_60 = 1;
                  foreach ($contratos_60 as $key => $value) {  ?>
                    <li class="nav-item">
                      <a class="nav-link" href="<?= base_url('usuarios/ver-contrato/' . MD5($keys . $value["id_contrato"])) ?>" target="_blank"><i class="far fa-file-alt text-danger">
                        </i> Contrato: <?= $num_contrato_60 ?><span class="dropdown-header"> Inicio: <?= $value["fecha_entrada"] ?> | Fin: <?= $value["fecha_termino"] ?></span></a>

                    </li>
                  <?php $num_contrato_60++;
                  } ?>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          <?php  } ?>
          <?php if ($cont90 > 0) { ?>
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Contratos de 90 días</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <?php $num_contrato_90 = 1;
                  foreach ($contratos_90 as $key => $value) {  ?>
                    <li class="nav-item">
                      <a class="nav-link" href="<?= base_url('usuarios/ver-contrato/' . md5($keys . $value["id_contrato"])) ?>" target="_blank"><i class="far fa-file-alt text-danger">
                        </i> Contrato: <?= $num_contrato_90 ?><span class="dropdown-header"> Inicio: <?= $value["fecha_entrada"] ?> | Fin: <?= $value["fecha_termino"] ?></span></a>

                    </li>
                  <?php $num_contrato_90++;
                  } ?>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          <?php  } ?>
          <?php if ($contFinal > 0) { ?>
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Contrato de <?= $tipo_contrato ?></h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <?php foreach ($contratoFinal as $key => $value) {  ?>
                    <li class="nav-item">
                      <a class="nav-link" href="<?= base_url('usuarios/ver-contrato/' . md5($keys . $value["id_contrato"])) ?>" target="_blank">
                        <i class="far fa-file-alt text-danger"></i> Contrato: <?= $value["estado"]; ?>
                        <span class="dropdown-header"> Creado: <?= $value["fecha_entrada"] ?></span>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          <?php  } ?>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Generar Nuevo Contrato</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form id="contrato_temp" method="post">
                <div class="row">
                  <div class="form-group col-md-4">
                    <img style="width: 400px;height:100px;" src="<?= base_url("public/images/inval.png"); ?>" alt="">
                  </div>
                  <div class="form-group col-md-8 text-right">
                    <div style="margin-top: 1rem;">
                      <h4 style="text-decoration: underline;">AVISO DE TERMINACIÓN DE CONTRATO</h4>
                    </div>
                  </div>
                  <div class="form-group col-md-8 text-left">
                    <div style="margin-top: 1rem;">
                      <h5><b>INDUSTRIAL DE VALVULAS, S.A DE C.V.</b></h5>
                    </div>
                  </div>

                  <input type="hidden" id="firma_user" name="firma_user">
                  <input type="hidden" id="tipo" name="tipo" value="<?= $temporal[0]["type_of_employee"]; ?>">
                  <input type="hidden" id="id_user" name="id_user" value="<?= $temporal[0]["id_user"]; ?>">
                  <input type="hidden" id="reingreso" name="reingreso" value="<?= $temporal[$fecha_reingreso]["date_of_new_entry"]; ?>">
                  <div class="form-group col-md-6">
                    <input type="text" class="form-control rounded-0" id="departamento" name="departamento" value="ADMINISTRACION DE PERSONAL" readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <input type="date" class="form-control rounded-0" id="fecha" name="fecha" value="<?= $fcha = date("Y-m-d"); ?>" readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <input type="text" class="form-control rounded-0" id="manager" name="manager" value="<?= session()->name . " " . session()->surname; ?>" readonly>
                  </div>
                  <div class="form-group col-md-6">
                    <input type="text" class="form-control rounded-0" id="depto" name="depto" value="<?= $temporal[0]["departament"]; ?>" readonly>
                  </div>

                  <div class="form-group col-md-11" style="margin-top: 1rem;">
                    <h5>
                      <p>El próximo dia &nbsp;<b><span style="text-decoration: underline; color:red;"><?= utf8_encode($fecha_vencimiento); ?></span></b>&nbsp;
                        termina el contrato de trabajo que por <b> <span style="text-decoration: underline;"> <?= $tipo_contrato ?></span></b>
                        que se celebró con el Señor (a)&nbsp;&nbsp; <b><span style="text-decoration: underline;"><?= $temporal[0]["name"] . " " . $temporal[0]["surname"] . " " . $temporal[0]["second_surname"]; ?></span></b></p>
                      <p>Quien ingresó a la empresa en fecha &nbsp;&nbsp;<b><span style="text-decoration: underline;"><?= $temporal[0]["date_of_new_entry"]; ?></span>.</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo ($temporal[0]["type_of_employee"] == 2) ? "REING.<b><span style='text-decoration: underline;'>" . $temporal[$fecha_reingreso]['date_of_new_entry'] . "</span></b>" : ""; ?> </p>
                    </h5>
                    <br>
                    <h5>
                      <p>Le agradeceremos informarnos a la brevedad posible.</p>
                      <p>...si como resultado de la evaluación del desempeño deberemos:</p>
                    </h5>
                  </div>

                </div>
                <div class="form-group col-md-12" style="margin-top: 1rem;">
                  <div id="error_opcion"></div>
                  <div class="form-group col-md-6">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="opcion" id="opcion_1" value="1" <?= $active ?>>
                      <label class="form-check-label" for="opcion_1" style="cursor: pointer;">
                        Formular contrato por Tiempo Indeterminado (Planta)
                      </label>
                    </div>
                  </div>
                  <div class="form-group col-md-12">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="opcion" id="opcion_2" value="2" <?= $active ?>>
                      <label class="form-check-label" for="opcion_2" style="cursor: pointer;">
                        Formular contrato por tiempo Determinado u Obra Determinada, de
                        <select name="contrato" id="contrato" <?= $active ?>>
                          <option value="">Seleccionar..</option>
                          <option value="2">30 días</option>
                          <?php if ($temporal[0]["type_of_employee"] == 1) { ?>
                            <option value="3">60 días</option>
                            <option value="4">90 días</option>
                          <?php } ?>
                        </select>
                      </label>
                      <div id="error_opcion2" class="text-danger"></div>
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="opcion" id="opcion_3" value="3" <?= $active ?>>
                      <label class="form-check-label" for="opcion_3" style="cursor: pointer;">
                        Proceder a dar de baja a la persona mencionada.
                      </label>
                    </div>
                  </div>
                </div>
                <br>
                <div class="form-group col-md-12" style="margin-top: 1rem;">
                  <h5>
                    <p> Datos extraídos del expediente:</p>
                  </h5>
                </div>

                <div class="form-group col-md-12" style="margin-top: 1rem;">
                  <h5>
                    <p>Puesto actual: <b><span style="text-decoration: underline;"><?= $temporal[0]["job"] ?></span></b> </p>
                  </h5>
                  <!-- <h5> <p>Números de contratos: <b> <?php ($cont30 > 0) ? $cont30 . "x30  " : ""; ?> <?= ($cont60 > 0) ? "&nbsp; " . $cont60 . "x60" : ""; ?> <?= ($cont90 > 0) ? "&nbsp;" . $cont90 . "x90" : ""; ?></b> </p> </h5> -->
                  <h5>
                    <p>Tiempo de Contratación: <b style="text-decoration: underline;"> <?= $tiempo; ?></b> </p>
                  </h5>
                </div>
                <div class="form-group col-md-12" style="margin-top: 1rem;">
                  <h5>
                    <p>En caso de que la decisión sea baja, es indispensable señalar a continuación las causas: </p>
                  </h5>
                  <textarea name="causa_baja" id="causa_baja" cols="2" rows="2" class="form-control" <?= $active ?>></textarea>
                  <div id="error_baja" class="text-danger"></div>
                </div>
                <div class="form-group col-md-12" style="margin-top: 1rem;">
                  <h5>
                    <p>Observaciones: </p>
                  </h5>
                  <textarea name="observaciones" id="observaciones" cols="2" rows="2" class="form-control" <?= $active ?>></textarea>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" id="guardar_contrato" name="guardar_contrato" class="btn btn-guardar btn-block btn-lg" <?= $active ?>><i class="fas fa-file-signature"></i> AUTORIZACIÓN</button>

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
  <script src="<?= base_url() ?>/public/js/users/contracts_users_v2.js"></script>
  <?= $this->endSection() ?>