<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('dashboard') ?>" class="brand-link">
    <img src="<?= base_url() ?>/public/images/icon-180.png" alt="Walworth Logo" class="brand-image" style="opacity: .8">
    <span class="brand-text font-weight-light sie-font-bold">Walworth </span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= session()->profile_img; ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="<?= base_url("usuarios/info") ?>" class="d-block"><?= ucwords(session()->name . " " . session()->surname); ?></a> <!-- AQUI -->
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search">
        <!-- <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div> -->
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <!--       <li class="nav-item">
            <a href="<?= base_url('usuarios/encuesta') ?>" class="nav-link">
              <i class="fas fa-users mr-2"></i>
              <p>
                Encuesta
              </p>
            </a>
          </li> | session()->id_user == 1-->
        <?php if (session()->id_user == 1063) { ?>
          <!-- <li class="nav-item">
            <?php //CIFRADO  
            $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677'; ?>
            <a href="http://bkstrategy.mx/Servicios/Servicios/<?= md5($key . session()->id_user) ?>" target="_blank" class="nav-link">
              <i class="fas fa-ticket-alt nav-icon"></i>
              <p>Tickets</p>
            </a>
          </li> -->
        <?php } ?>
        <li class="nav-item">
          <a href="https://grupowalworth.com/walnet/" target="_blank" class="nav-link">
            <i class="fab fa-uikit"></i>
            <p>
              Walnet
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="https://universidadwalworth.com/" target="_blank" class="nav-link">
            <i class="fas fa-university nav-icon"></i>
            <p>
              Universidad Walworth
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="https://login.workbeat.com/" target="_blank" class="nav-link">
            <i class="fab fa-weebly nav-icon"></i>
            <p>
              Workbeat
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="http://13.65.16.81:86/Buzon?idcompany=2" target="_blank" class="nav-link">
            <i class="fas fa-hands-helping nav-icon"></i>
            <p>
              Línea de Ética
            </p>
          </a>
        </li>
        <?php if (session()->id_user == 710 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 44) {  ?>
          <li class="nav-item">
            <?php //CIFRADO  
            $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677'; ?>
            <a href="https://grupowalworth.com/walnet/main.php?e=<?= md5($key . 'vigilancia@walworth.com.mx'); ?>" target="_blank" class="nav-link">
              <i class="fas fa-object-group nav-icon"></i>
              <p>Salidas Walnet</p>
            </a>
          </li>
        <?php }  ?>


        <?php if (session()->id_user == 27 || session()->id_user == 1178 || session()->id_user == 1390 || session()->id_user == 852 || session()->id_user == 1 || session()->id_user == 151 || session()->id_user == 107 || session()->id_user == 863 || session()->id_user == 1302 || session()->id_user == 854) {  ?>

          <li class="nav-item">
            <a href="<?= base_url('corporativo/reportes-permisos') ?>" class="nav-link">
              <i class="fas fa-chart-line nav-icon"></i>
              <p>
                Corporativo Reportes
              </p>
            </a>
          </li>
        <?php }  ?>
        <!-- Menu Liberación -->
        <?php if (
          session()->id_user == 710 || session()->id_user == 44 ||
          session()->id_user == 42 || session()->id_user == 455 || session()->id_user == 903 ||
          session()->id_user == 267 || session()->id_user == 328
          || session()->id_user == 294 || session()->id_user == 272 || session()->id_user == 259 || session()->id_user == 356 ||
          session()->id_user == 1 ||
          session()->id_user == 2 ||
          session()->id_user == 20 ||
          session()->id_user == 25 ||
          session()->id_user == 26 ||
          session()->id_user == 27 ||
          session()->id_user == 30 ||
          session()->id_user == 37 ||
          session()->id_user == 46 ||
          session()->id_user == 50 ||
          session()->id_user == 56 ||
          session()->id_user == 66 ||
          session()->id_user == 72 ||
          session()->id_user == 75 ||
          session()->id_user == 76 ||
          session()->id_user == 82 ||
          session()->id_user == 86 ||
          session()->id_user == 92 ||
          session()->id_user == 1075 ||
          session()->id_user == 1119 ||
          session()->id_user == 1283 ||
          session()->id_user == 1292 ||
          session()->id_user == 1361 ||
          session()->id_user == 1390 ||
          session()->id_user == 151 ||
          session()->id_user == 152 ||
          session()->id_user == 159 ||
          session()->id_user == 171 ||
          session()->id_user == 217 ||
          session()->id_user == 253 ||
          session()->id_user == 254 ||
          session()->id_user == 258 ||
          session()->id_user == 259 ||
          session()->id_user == 261 ||
          session()->id_user == 262 ||
          session()->id_user == 263 ||
          //session()->id_user == 265 ||
          session()->id_user == 268 ||
          session()->id_user == 269 ||
          session()->id_user == 272 ||
          session()->id_user == 282 ||
          session()->id_user == 293 ||
          session()->id_user == 299 ||
          session()->id_user == 303 ||
          session()->id_user == 304 ||
          session()->id_user == 315 ||
          session()->id_user == 322 ||
          session()->id_user == 328 ||
          session()->id_user == 339 ||
          session()->id_user == 340 ||
          session()->id_user == 346 ||
          session()->id_user == 347 ||
          session()->id_user == 353 ||
          session()->id_user == 356 ||
          session()->id_user == 362 ||
          session()->id_user == 375 ||
          session()->id_user == 377 ||
          session()->id_user == 378 ||
          session()->id_user == 592 ||
          session()->id_user == 639 ||
          session()->id_user == 650 ||
          session()->id_user == 685 ||
          session()->id_user == 695 ||
          session()->id_user == 833 ||
          session()->id_user == 852 ||
          session()->id_user == 905


        ) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-file-signature nav-icon"></i>
              <p>
                Liberación
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (session()->id_user == 1 || session()->id_user == 1390  || session()->id_user == 50 || session()->id_user == 267) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('liberacion/solicitudes_liberacion') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Todas las solicitudes</p>
                  </a>
                </li>
              <?php } ?>
              <?php if (
                 session()->id_user == 710 || session()->id_user == 44 ||
          session()->id_user == 42 || session()->id_user == 455 || session()->id_user == 903 ||
          session()->id_user == 267 || session()->id_user == 328
          || session()->id_user == 294 || session()->id_user == 272 || session()->id_user == 259 || session()->id_user == 356 ||
          session()->id_user == 1 ||
          session()->id_user == 2 ||
          session()->id_user == 20 ||
          session()->id_user == 25 ||
          session()->id_user == 26 ||
          session()->id_user == 27 ||
          session()->id_user == 30 ||
          session()->id_user == 37 ||
          session()->id_user == 46 ||
          session()->id_user == 50 ||
          session()->id_user == 56 ||
          session()->id_user == 66 ||
          session()->id_user == 72 ||
          session()->id_user == 75 ||
          session()->id_user == 76 ||
          session()->id_user == 82 ||
          session()->id_user == 86 ||
          session()->id_user == 92 ||
          session()->id_user == 1075 ||
          session()->id_user == 1119 ||
          session()->id_user == 1283 ||
          session()->id_user == 1292 ||
          session()->id_user == 1361 ||
          session()->id_user == 1390 ||
          session()->id_user == 151 ||
          session()->id_user == 152 ||
          session()->id_user == 159 ||
          session()->id_user == 171 ||
          session()->id_user == 217 ||
          session()->id_user == 253 ||
          session()->id_user == 254 ||
          session()->id_user == 258 ||
          session()->id_user == 259 ||
          session()->id_user == 261 ||
          session()->id_user == 262 ||
          session()->id_user == 263 ||
          //session()->id_user == 265 ||
          session()->id_user == 268 ||
          session()->id_user == 269 ||
          session()->id_user == 272 ||
          session()->id_user == 282 ||
          session()->id_user == 293 ||
          session()->id_user == 299 ||
          session()->id_user == 303 ||
          session()->id_user == 304 ||
          session()->id_user == 315 ||
          session()->id_user == 322 ||
          session()->id_user == 328 ||
          session()->id_user == 339 ||
          session()->id_user == 340 ||
          session()->id_user == 346 ||
          session()->id_user == 347 ||
          session()->id_user == 353 ||
          session()->id_user == 356 ||
          session()->id_user == 362 ||
          session()->id_user == 375 ||
          session()->id_user == 377 ||
          session()->id_user == 378 ||
          session()->id_user == 592 ||
          session()->id_user == 639 ||
          session()->id_user == 650 ||
          session()->id_user == 685 ||
          session()->id_user == 695 ||
          session()->id_user == 833 ||
          session()->id_user == 852 ||
          session()->id_user == 905
                    
                 
              ) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('liberacion/solicitudes_departamento') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Validar Solicitudes</p>
                  </a>
                </li>
              <?php } ?>
              <?php if (session()->id_user == 1 || session()->id_user == 1390) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('liberacion/items') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Items del inventario</p>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php }  ?>
        <!-- End Menu Liberación -->
        <!-- Menu Logisitica -->
        <?php if (session()->id_user == 1 || session()->id_user == 1283 || session()->id_user == 710) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-car nav-icon"></i>
              <p>
                Estacionamiento
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (session()->id_user == 1 || session()->id_user == 1283 || session()->id_user == 710) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('vigilancia/scanner') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Escanner</p>
                  </a>
                </li>
              <?php } ?>
              <?php if (session()->id_user == 1 || session()->id_user == 1283 || session()->id_user == 710) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('vigilancia/usuarios_estacionamiento') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Alta usuarios</p>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php }  ?>
        <!-- End Menu Logisitica -->
        <!-- Menu Logisitica -->
        <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 345 || session()->id_user == 272 || session()->id_user == 328) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-pallet nav-icon"></i>
              <p>
                Logisitica
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (
                session()->id_user == 1 ||  session()->id_user == 328 || session()->id_user == 1390 || session()->id_user == 50 ||
                session()->id_user == 267 || session()->id_user == 272 || session()->id_user == 345
              ) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('logistica/solicitudes') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Solicitudes MSI</p>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php }  ?>
        <!-- End Menu Logisitica -->
        <!-- End Menu servicios generales -->
        <!-- Menu Logisitica -->
        <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 345 || session()->id_user == 272 || session()->id_user == 328) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">

              <i class="fas fa-file-invoice-dollar nav-icon"></i>
              <p>
                Facturas Almacen
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (session()->id_user == 1 || session()->id_user == 328 || session()->id_user == 1390  || session()->id_user == 50 || session()->id_user == 267 || session()->id_user == 272) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('almacen/solicitudes') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Solicitudes</p>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php }  ?>
        <!-- End Menu Servicios generales -->
        <?php if (
          session()->id_user == 903 || session()->id_user == 27 || session()->id_user == 50 || session()->id_user == 267 ||
          session()->id_user == 1 || session()->id_user == 26 || session()->id_user == 92 || session()->id_user == 294 ||
          session()->id_user == 315 || session()->id_user == 31 || session()->id_user == 1390 ||
          session()->id_user == 75 || session()->id_user == 259 || session()->id_user == 1392 || session()->id_user == 1178 ||
          session()->id_user == 35 || session()->id_user == 1297 || session()->id_user == 1385 || session()->id_user == 353 ||
          session()->id_user == 268 || session()->id_user == 872 || session()->id_user == 303 || session()->id_user == 277 ||
          session()->id_user == 343 || session()->id_user == 258 || session()->id_user == 272 || session()->id_user == 1283 ||
          session()->id_user == 346 || session()->id_user == 152 || session()->id_user == 872 || session()->id_user == 44 ||
          session()->id_user == 1292 || session()->id_user == 1334 || session()->id_user == 151 || session()->id_user == 863 ||
          session()->id_user == 1152 || session()->id_user == 1361 || session()->id_user == 303 ||
          session()->id_user == 329 || session()->id_user == 1299 || session()->id_user == 1302 || session()->id_user == 868 ||
          session()->id_user == 1396 || session()->id_user == 265
        ) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-coffee-pot"></i>
              <i class="fas fa-balance-scale nav-icon"></i>
              <p>
                Finanzas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (
                session()->id_user == 1
              ) {  ?>
                <li class="nav-item">
                  <a href="" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Solicitudes</p>
                    <i class="right fas fa-angle-left"></i>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url('finanzas/percepciones_deducciones') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Percepciones y Deducciones</p>
                      </a>
                    </li>
                  </ul>
                </li>
              <?php } ?>
              <?php if (
                session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 26 || session()->id_user == 294 ||
                session()->id_user == 92
              ) {  ?>
                <li class="nav-item">
                  <a href="" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Inventarios</p>
                    <i class="right fas fa-angle-left"></i>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url('finanzas/maquinaria') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Maquinaria</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url('finanzas/mobiliario') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Mobiliario</p>
                      </a>
                    </li>
                  </ul>
                </li>
              <?php } ?>
              <?php if (
                session()->id_user == 50 || session()->id_user == 267 || session()->id_user == 1 || session()->id_user == 1390 ||
                session()->id_user == 92 || session()->id_user == 315 || session()->id_user == 26 || session()->id_user == 294
              ) {  ?>

                <li class="nav-item">
                  <a href="" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Adm.Personal</p>
                    <i class="right fas fa-angle-left"></i>
                  </a>
                  <ul class="nav nav-treeview">
                    <?php if (
                      session()->id_user == 50 || session()->id_user == 267 || session()->id_user == 1 || session()->id_user == 1390
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/solicitud_pago') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Solicitud de pago</p>
                        </a>
                      </li>
                    <?php  }  ?>
                    <?php if (
                      session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 50
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/aprobar_solicitud') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Aprobar Solicitud</p>
                        </a>
                      </li>
                    <?php  }  ?>
                    <?php if (
                      session()->id_user == 1 || session()->id_user == 1390 ||
                      session()->id_user == 92
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/autorizar_solicitud') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Autorizar Solicitud</p>
                        </a>
                      </li>
                    <?php } ?>

                    <?php if (
                      session()->id_user == 1 || session()->id_user == 1390 ||
                      session()->id_user == 315
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/pagar_solicitud') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Pago de Solicitudes</p>
                        </a>
                      </li>
                    <?php } ?>

                    <?php if (
                      session()->id_user == 1 || session()->id_user == 1390 ||
                      session()->id_user == 26 || session()->id_user == 294 || session()->id_user == 1334
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/solicitudes_pagadas') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Solicitudes de Pago</p>
                        </a>
                      </li>
                    <?php } ?>

                  </ul>
                </li>
              <?php } ?>
              <?php if (
                session()->id_user == 903 || session()->id_user == 27 || session()->id_user == 1 || session()->id_user == 1390 ||
                session()->id_user == 92 || session()->id_user == 315 || session()->id_user == 26 || session()->id_user == 294 ||
                session()->id_user == 31 || session()->id_user == 1390 || session()->id_user == 75 || session()->id_user == 259 ||
                session()->id_user == 1392 || session()->id_user == 1178 || session()->id_user == 35 || session()->id_user == 1297 ||
                session()->id_user == 1385 || session()->id_user == 353 || session()->id_user == 268 || session()->id_user == 872 ||
                session()->id_user == 303 || session()->id_user == 277 || session()->id_user == 343 || session()->id_user == 258 ||
                session()->id_user == 272 || session()->id_user == 1283 || session()->id_user == 346 || session()->id_user == 152 ||
                session()->id_user == 872 || session()->id_user == 44 || session()->id_user == 1292 || session()->id_user == 1334  ||
                session()->id_user == 151 || session()->id_user == 863 || session()->id_user == 1152 || session()->id_user == 1361 ||
                session()->id_user == 303 || session()->id_user == 329 || session()->id_user == 1299 || session()->id_user == 277 ||
                session()->id_user == 1302 || session()->id_user == 868 ||  session()->id_user == 1396 || session()->id_user == 346 ||
                session()->id_user == 50 || session()->id_user == 267 || session()->id_user == 265
              ) {  ?>

                <li class="nav-item">
                  <a href="" class="nav-link">
                    <i class="fas fa-file-signature nav-icon"></i>
                    <p>
                      Solicitudes de Pago</p>
                    <i class="right fas fa-angle-left"></i>
                  </a>
                  <ul class="nav nav-treeview">
                    <?php if (
                      session()->id_user == 903 || session()->id_user == 27 ||
                      session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 92 ||
                      session()->id_user == 315 || session()->id_user == 31 || session()->id_user == 1390 ||
                      session()->id_user == 75 || session()->id_user == 259 || session()->id_user == 1392 ||
                      session()->id_user == 35 || session()->id_user == 1297 || session()->id_user == 1385 ||
                      session()->id_user == 353 || session()->id_user == 268 || session()->id_user == 272 ||
                      session()->id_user == 1283 || session()->id_user == 1334 || session()->id_user == 151 ||
                      session()->id_user == 863 || session()->id_user == 343 || session()->id_user == 1152 ||
                      session()->id_user == 1361 || session()->id_user == 303 || session()->id_user == 1292 ||
                      session()->id_user == 329 || session()->id_user == 1299 || session()->id_user == 294 ||
                      session()->id_user == 44 || session()->id_user == 258 || session()->id_user == 277 || session()->id_user == 1302 ||
                      session()->id_user == 868 || session()->id_user == 1396 || session()->id_user == 346 || session()->id_user == 50 ||
                      session()->id_user == 267
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/solicitudes_pagos') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Solicitudes</p>
                        </a>
                      </li>
                    <?php } ?>
                    <?php if (session()->id_user == 92 || session()->id_user == 1) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/autorizar_pagos') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Autorizar pago</p>
                        </a>
                      </li>
                    <?php } ?>

                    <?php if (
                      session()->id_user == 1 || session()->id_user == 1390 ||
                      session()->id_user == 315
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/pagar_solicitudes') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Pagar solicitudes</p>
                        </a>
                      </li>

                    <?php } ?>

                    <?php if (
                      session()->id_user == 1 || session()->id_user == 1390 ||
                      session()->id_user == 26 || session()->id_user == 294 || session()->id_user == 1178 || session()->id_user == 1334 || session()->id_user == 265
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('finanzas/pago_solicitudes') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Solicitudes de Pago</p>
                        </a>
                      </li>
                    <?php } ?>

                  </ul>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-ticket-alt nav-icon"></i>
            <p>
              Tickets
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php if (session()->id_depto == 43) {
            ?>
              <li class="nav-item">
                <a href="<?= base_url('tickets/tecnologias-informacion') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Departamento de TI</p>
                </a>
              </li>
            <?php } ?>
            <?php //if (session()->id_user == 1063 || session()->id_user == 1 || session()->id_user == 1283 || session()->id_user == 1178 || session()->manager_tickets != false ||session()->access_tickets != false) { 
            ?>
            <li class="nav-item">
              <a href="<?= base_url('tickets/servicios-generales') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Servicios Generales</p>
              </a>
            </li>
            <?php //} 
            ?>

            <?php if (/* ession()->manager_tickets != false || session()->access_tickets != false || session()->id_user == 1226 || session()->id_user == 833 */session()->id_user == 1 || session()->id_user == 1390) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('tickets/mantenimiento') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mantenimiento</p>
                </a>
              </li>
            <?php } ?>
            <?php if (session()->id_user == 1063) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('tickets/reporte-tickets') ?>" class="nav-link">
                  <!-- $routes->post('reporte-tickets', 'Tickets::viewReportes'); -->
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reportes</p>
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>


        <?php if (session()->id_user == 1 || session()->id_user == 1079 || session()->id_user == 1385  || session()->id_user == 1407 || session()->id_user == 1390 || session()->menu_services || session()->access_travel_expens == true) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-concierge-bell nav-icon"></i>
              <p>
                Solicitar Servicios
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1178 || session()->id_user == 1348 || session()->id_user == 1283 || session()->id_user == 92) {  ?>

                <li class="nav-item">
                  <a href="<?= base_url('corporativo/reportes-servicios') ?>" class="nav-link">
                    <i class="fas fa-chart-line nav-icon"></i>
                    <p>
                      Reportes Servicios
                    </p>
                  </a>
                </li>
              <?php }  ?>

              <?php if (session()->id_user == 265 || session()->id_user == 870  || session()->id_user == 1079 || session()->id_user == 1385 || session()->id_user == 1407 ||  session()->id_user == 1154 || session()->id_user == 1 || session()->id_user == 294 || session()->id_user == 340 || session()->id_user == 1390 || session()->id_user == 252 || session()->access_travel_expens == true || session()->authorize_travel_expens == true) {  // if (session()->id_rol != 2) {  
              ?>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="fas fa-money-check-alt nav-icon"></i>
                    <p>
                      Viaticos y Gastos
                      <i class="fas fa-angle-left right"></i>
                      <!--  <span class="badge badge-info right">6</span> -->
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <?php if (session()->id_user == 265 || session()->id_user == 870 || session()->id_user == 1 || session()->id_user == 294 || session()->id_user == 26 || session()->id_user == 1178 || session()->id_user == 1348 || session()->id_user == 1283 || session()->id_user == 92) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('viajes/solicitudes') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Todas la Solicitudes</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="<?= base_url('viajes/reportes') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Reportes</p>
                        </a>
                      </li>
                    <?php } ?>
                    <?php if (session()->id_user == 1 || session()->id_user == 1390) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('viajes/autorizacion-comprobacion-fuera-tiempo') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Autorizar Comprobaciones</p>
                        </a>
                      </li>
                    <?php } ?>
                    <?php if (session()->id_user == 1 || session()->id_user == 30 ||  session()->id_user == 294 || session()->id_user == 26 || session()->id_user == 1390 || session()->id_user == 1063 || session()->id_user == 252 || session()->authorize_travel_expens == true) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('viajes/autorizar') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Autorizar</p>
                        </a>
                      </li>
                    <?php } ?>

                    <li class="nav-item">
                      <a href="<?= base_url('viajes/solicitud') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Generar Solicitud</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url('viajes/comprobacion') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Comprobación de Gastos</p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="<?= base_url('viajes/mis-solicitudes') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Mis Solicitudes</p>
                      </a>
                    </li>
                  </ul>
                </li>
              <?php } ?>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-pencil-ruler nav-icon"></i>
                  <p>
                    Papelería
                    <i class="fas fa-angle-left right"></i>
                    <!--  <span class="badge badge-info right">6</span> -->
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <?php if (
                    session()->id_user == 903 || session()->id_user == 1390  || session()->id_user == 27 || session()->id_user == 1 || session()->id_user == 10630 ||
                    session()->id_user == 1075 || session()->id_user == 1254  || session()->id_user == 73 ||
                    session()->id_user == 1348
                  ) {  ?>
                    <li class="nav-item">
                      <a href="<?= base_url('papeleria/todas-solicitudes') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Todas las solicitudes</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url('papeleria/inventario') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Inventario</p>
                      </a>
                    </li>
                    <?php if (
                      session()->id_user == 903  || session()->id_user == 27 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 ||
                      session()->id_user == 1075  || session()->id_user == 1254  || session()->id_user == 73 ||
                      session()->id_user == 1348
                    ) {  ?>
                      <li class="nav-item">
                        <a href="<?= base_url('papeleria/entradas') ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Entradas & Salidas</p>
                        </a>
                      </li>
                    <?php  }  ?>
                    <li class="nav-item">
                      <a href="<?= base_url('papeleria/reportes') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Reportes</p>
                      </a>
                    </li>

                  <?php }  ?>
                  <?php if (
                    session()->id_user == 27  || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 903 || session()->authorize_services == true ||
                    session()->id_user == 1178 || session()->id_user == 1348
                  ) {  ?>
                    <li class="nav-item">
                      <a href="<?= base_url('papeleria/autorizar') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Autorizar Papelería</p>
                      </a>
                    </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a href="<?= base_url('papeleria/crear') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Solicitud de Papelería</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?= base_url('papeleria/mis-solicitudes') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Mis Solicitudes</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-luggage-cart nav-icon"></i>
                  <p>
                    Valija</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                  <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1178 || session()->id_user == 1348 || session()->id_user == 1063 || session()->id_user == 1254 || session()->id_user == 1283 || session()->id_user == 1075 || session()->id_user == 1109) {  ?>
                    <li class="nav-item">
                      <a href="<?= base_url('valija/todas-solicitudes') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                          Todas las Solicitudes
                        </p>
                      </a>
                    </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a href="<?= base_url('valija/crear') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Solicitud Valija</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="<?= base_url('valija/mis-solicitudes') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                        Mis Solicitudes
                      </p>
                    </a>
                  </li>
                  <!-- <li class="nav-item">
                        <a href="<?= base_url('paquetes/crear-solicitud') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Generar Solicitud</p>
                      </a>
                    </li>
                     -->
                </ul>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-box-open nav-icon"></i>
                  <p>
                    Paquetería</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                  <?php if (
                    session()->id_user == 1  || session()->id_user == 1390 ||  session()->id_user == 1063 || session()->id_user == 1254 || session()->id_user == 1283 ||
                    session()->id_user == 1075 || session()->id_user == 1178 || session()->id_user == 1348
                  ) {  ?>
                    <li class="nav-item">
                      <a href="<?= base_url('paqueteria/autorizar') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Autorizar Solicitud</p>
                      </a>
                    </li>
                  <?php } ?>

                  <li class="nav-item">
                    <a href="<?= base_url('paqueteria/crear-solicitud') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Generar Solicitud</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url('paqueteria/mis-solicitudes') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                        Mis Solicitudes
                      </p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-car nav-icon"></i>
                  <p>
                    Solicitud de Vehículo</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                  <?php if (
                    session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 || session()->id_user == 1254 ||
                    session()->id_user == 1283 || session()->id_user == 1109 || session()->id_user == 1178 || session()->id_user == 1348
                  ) {  ?>
                    <li class="nav-item">
                      <a href="<?= base_url('autos/todas-solicitudes') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Todas las Solicitudes</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url('autos/vehiculos') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Alta de Vehiculos</p>
                      </a>
                    </li>
                  <?php } ?>

                  <?php if (session()->id_user == 1  || session()->id_user == 1390 || session()->id_user == 1063 || session()->authorize_services == true) {  ?>
                    <li class="nav-item">
                      <a href="<?= base_url('autos/autorizar') ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Autorizar Solicitud</p>
                      </a>
                    </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a href="<?= base_url('autos/crear-solicitud') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Generar Solicitud</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url('autos/mis-solicitudes') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                        Mis Solicitudes
                      </p>
                    </a>
                  </li>
                </ul>
              </li>


            </ul>
          </li>
        <?php } ?>


        <?php if (session()->type_of_employee == 1) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-coffee-pot"></i>
              <i class="fas fa-coffee nav-icon"> </i>
              <p>
                Cafetería
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1178 || session()->id_user == 1348 || session()->id_user == 1283 || session()->id_user == 1063) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('cafeteria/autorizar') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Autorizar Solicitudes</p>
                  </a>
                </li>
              <?php } ?>
              <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 27  || session()->id_user == 1063) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('cafeteria/autorizar-james') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Autorizar Sala James</p>
                  </a>
                </li>
              <?php } ?>
              <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1178 || session()->id_user == 1348 || session()->id_user == 27 || session()->id_user == 1283 || session()->id_user == 1063) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('cafeteria/menus-admin') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Administrar Menus</p>
                  </a>
                </li>
              <?php } ?>
              <li class="nav-item">
                <a href="<?= base_url('cafeteria/crear') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Solicitud de Cafetería</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('cafeteria/solicitudes') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mis Solicitudes</p>
                </a>
              </li>
            </ul>
          </li>
        <?php } ?>
        <?php if (session()->access_requisition == true || session()->id_user == 20  || session()->id_user == 346 || session()->id_user == 375 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 || session()->id_user == 854) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Requisición de Personal
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <?php if (session()->type_of_employee == 1) {  ?>
                <?php if (session()->id_user == 852 || session()->id_user == 1390 ||  session()->id_user == 1 || session()->id_user == 1390 ||  session()->id_user == 1064 ||  session()->id_user == 262 || session()->id_user == 27) {  ?>
                  <li class="nav-item">
                    <a href="<?= base_url('requisiciones/autorizar') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Autorizar Requisiciones</p>
                    </a>
                  </li>
                <?php } ?>
              <?php } ?>
              <li class="nav-item">
                <a href="<?= base_url('requisiciones/generar') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generar Requisición</p>
                </a>
              </li>
              <?php if (session()->type_of_employee == 1) {  ?>
                <?php if (session()->id_user == 27 || session()->id_user == 837  || session()->id_user == 1054 || session()->id_user == 16 || session()->id_rol == 1) {  ?>
                  <li class="nav-item">
                    <a href="<?= base_url('requisiciones/todas') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Todas las Requisiciones</p>
                    </a>
                  </li>
                <?php } ?>
              <?php } ?>
              <li class="nav-item">
                <a href="<?= base_url('requisiciones/mis-requisiciones') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mis Requisiciones</p>
                </a>
              </li>
              <?php if (session()->id_user == 27 || session()->id_rol == 1) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('requisiciones/asignar') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Autorizaciones</p>
                    <!-- <p>Asignar Area Operativa</p> -->
                  </a>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Permisos & Vacaciones
              <i class="fas fa-angle-left right"></i>
              <!--  <span class="badge badge-info right">6</span> -->
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php if (session()->type_of_employee == 1) {  ?>
              <?php if (session()->authorizeNew == true || session()->id_user == 252 || session()->id_user == 1  || session()->id_user == 1390) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('permisos/autorizar-direcion-general') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Quinto Permiso</p> <!-- NEW AUTHORISE - DIRECTOR -->
                  </a>
                </li>
              <?php } ?>
              <?php if (session()->authorizeNew == true || session()->id_user == 1  || session()->id_user == 1390 || session()->id_user == 911) {  ?>
                <!-- <li class="nav-item">
                    <a href="<?= base_url('permisos/administrar-permisos') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Mis Usuarios</p>
                    </a>
                  </li> -->
                <li class="nav-item">
                  <a href="<?= base_url('permisos/autorizar_new') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Permisos Extra</p> <!-- NEW AUTHORISE - DIRECTOR -->
                  </a>
                </li>
              <?php } ?>
              <?php if (session()->authorize == true /* || session()->id_user == 254 || session()->id_user == 159 */ || session()->id_user == 1  || session()->id_user == 1390 || session()->id_user == 1063) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('permisos/autorizar') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Autorizar Permisos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('permisos/autorizar-pago-tiempo') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Autorizar Pago de Tiempo</p>
                  </a>
                </li>
              <?php } ?>
            <?php } ?>
            <li class="nav-item">
              <a href="<?= base_url('permisos/crear') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Generar Permisos</p>
              </a>
            </li>
            <?php if (session()->type_of_employee == 2 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('permisos/pago-horas') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generar Pago de Horas</p>
                </a>
              </li>
            <?php } ?>
            <?php if (session()->id_user == 1 || session()->id_user == 50 || session()->id_user == 267  || session()->id_user == 1390) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('permisos/todos') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Todos los Permisos</p>
                </a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a href="<?= base_url('permisos/mis-permisos') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Mis Permisos</p>
              </a>
            </li>
            <?php if (session()->id_user == 1 || session()->id_user == 50 || session()->id_user == 267 || session()->id_user == 1390) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('permisos/reportes') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reportes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('permisos/administrar-permisos-especiales') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permisos Especiales</p>
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
        <?php // if (session()->access_contracts == true || session()->id_depto == 42 || session()->id_user == 1 || session()->id_user == 1063 || session()->id_user == 252 || session()->id_user == 854) {  
        ?>
        <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-user-clock nav-icon"></i>
              <p>Personal Eventual <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php
              //|| session()->id_user == 252 || session()->id_user == 854
              if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('usuarios/autorizar-planta') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Autorizar Planta</p>
                  </a>
                </li>
              <?php } ?>
              <?php
              // session()->id_depto == 42 || session()->id_user == 854
              if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('usuarios/todos-contratos') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Todos los Contratos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('usuarios/reportes-contratos') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Reportes</p>
                  </a>
                </li>
              <?php } ?>

              <li class="nav-item">
                <a href="<?= base_url('usuarios/contratos') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contratos</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="<?= base_url('wps/uniones') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Uniones</p>
                </a>
              </li>
              <li class="nav-item"> 206
                <a href="pages/charts/inline.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Recubrir</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/uplot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Solicitud</p>
                </a>
              </li> -->
            </ul>
          </li>
        <?php } ?>

        <?php if (session()->id_user == 1) {  ?>
          <!--      <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                WPS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= base_url('wps/material-base') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Metal Base</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('wps/uniones') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Uniones</p>
                </a>
              </li>
              <li class="nav-item"> 206
                <a href="pages/charts/inline.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Recubrir</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/uplot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Solicitud</p>
                </a>
              </li>
            </ul>
          </li> -->
        <?php } ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>
              Visitas & Horario Obscuro
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php if (session()->id_user == 1063 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1178 || session()->id_user == 1283) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('qhse/autorizar') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Autorizar Permisos</p>
                </a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a href="<?= base_url('qhse/proveedores') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Permiso Visitas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('qhse/horario-obscuro') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Permiso Horario Obscuro</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('qhse/mis-permisos') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Mis Permisos</p>
              </a>
            </li>
          </ul>
        </li>
        <?php if (
          session()->id_user == 259 || session()->id_user == 112 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1390 || session()->id_user == 834 || session()->id_user == 1063 || session()->id_user == 854 || session()->id_user == 844
          || session()->id_user == 1330 || session()->id_user == 75 || session()->id_user == 328 || session()->id_user == 457  || session()->id_user == 345
        ) {  ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-hard-hat"></i>
              <p>
                Equipos Personales
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (session()->id_user == 259 || session()->id_user == 834 || session()->id_user == 1  || session()->id_user == 1390 || session()->id_user == 1063  || session()->id_user == 854  || session()->id_user == 1330) {  ?>
                <li class="nav-item">
                  <a href="<?= base_url('qhse/todos-vales-epp') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Todos Vales EPP</p>
                  </a>
                </li>
              <?php } ?>
              <?php if (session()->id_user == 844 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 328 || session()->id_user == 457 || session()->id_user == 345) { ?>
                <li class="nav-item">
                  <a href="<?= base_url('qhse/todos-vales-almacen') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Vales Almacen</p>
                  </a>
                </li>
              <?php }  // base_url('qhse/articulos-epp')  
              ?>
              <li class="nav-item">
                <a href="<?= base_url('almacen/listado') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lista articulos EPP</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('qhse/entrega-equipos') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generar Vale EPP</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?= base_url('qhse/entrega-epp-almacen') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Entrega de EPP</p>
                </a>
              </li>
            </ul>
          </li>
        <?php } ?>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-medal"></i>
            <p>
              Responsabilidad Social
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <?php if (
              session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 ||
              session()->id_user == 854 || session()->id_user == 1330 || session()->id_user == 75
            ) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('qhse/eventos-menus') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Menus</p>
                </a>
              </li>
            <?php } ?>
            <?php if (
              session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 ||
              session()->id_user == 854 || session()->id_user == 1330 || session()->id_user == 75
            ) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('qhse/voluntariado-solicitudes') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Listado Solicitudes</p>
                </a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a href="<?= base_url('qhse/mis-insignias') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Mis Insignias</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Voluntariado
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>

              <ul class="nav nav-treeview">

                <li class="nav-item">
                  <a href="<?= base_url('qhse/voluntariado-evento') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Por Evento</p>
                  </a>
                <li class="nav-item">
                  <a href="<?= base_url('qhse/permanente-evento') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Permanente</p>
                  </a>
                </li>
            </li>
          </ul>
        </li>


        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Acciones Verdes
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('qhse/limpieza') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Campañas de limpieza</p>
              </a>
            <li class="nav-item">
              <a href="<?= base_url('qhse/reforestacion') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Reforestación</p>
              </a>
            </li>
        </li>
      </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="far fa-circle nav-icon"></i>
          <p>Actividades deportivas
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?= base_url('qhse/carreras') ?>" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Carrera con causa</p>
            </a>
          </li>
        </ul>
      </li>




      </ul>
      </li>


      <?php if (session()->id_user == 710 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 || session()->id_depto == 73 || session()->id_user == 854 || session()->id_user == 1330 || session()->id_user == 75 || session()->id_user == 1283) {  ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-flag"></i>
            <!-- <i class="nav-icon fas fa-exclamation-triangle"></i> -->
            <p>
              Recorridos de HSE
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php if (session()->id_user == 1063 || session()->id_user == 1330 || session()->id_user == 75 || session()->id_user == 854 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1283) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('recorridos-HSE/reportes-graficas') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reportes</p>
                </a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a href="<?= base_url('recorridos-HSE/resgistrar-incidencia') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Recorridos | Incidencias</p>
              </a>
            </li>
            <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 || session()->id_depto == 73 || session()->id_user == 854 || session()->id_user == 1330 || session()->id_user == 75) {  ?>

              <li class="nav-item">
                <a href="<?= base_url('recorridos-HSE/seguimiento-incidencia-condiciones-inseguras') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Seguimiento Condiciones</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('recorridos-HSE/seguimiento-incidencia-actividades-inseguras') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Seguimiento Actividades</p>
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (session()->id_user == 1063 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1178 || session()->id_user == 1283) {  ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-warehouse"></i>
            <p>
              &nbsp; Estacionamiento
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php if (session()->id_user == 1063 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1178 || session()->id_user == 1283) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('estacionamiento/registro-de-usuarios') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registro de Vehiculos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('estacionamiento/movimientos-de-vehiculos') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Movimiento de Vehiculos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('estacionamiento/reportes') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reportes</p>
                </a>
              </li>
            <?php  } ?>
            <?php if (session()->id_user == 1063 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1178 || session()->id_user == 1283) {   ?>
              <li class="nav-item">
                <a href="<?= base_url('estacionamiento/entradas-salidas') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Entradas & Salidas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('estacionamiento/asignar-cajon') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Asignacion de Cajon</p>
                </a>
              </li>
            <?php  } ?>
            <?php if (/* session()->id_user != 710 */session()->id_user == 1063) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('estacionamiento/registar-mi-vehiculo') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registrar mis Vehiculos</p>
                </a>
              </li>
            <?php  } ?>
          </ul>
        </li>
      <?php } ?>
      <?php if (
        session()->id_user == 345 || session()->id_user == 844  || session()->id_user == 328 || session()->id_user ==  846 || session()->payroll_number == 82191
        || session()->id_user == 710 || session()->id_user == 75 || session()->id_user == 272 || session()->id_user == 351 || session()->id_user == 473
        || session()->id_user == 371 || session()->id_user == 716 || session()->id_user == 370 || session()->id_user == 369 || session()->id_user == 1
        || session()->id_user == 1063 || session()->id_depto == 25 || session()->id_user == 852 || session()->id_user == 592 || session()->id_user == 1263
        || session()->id_user == 561 || session()->id_user == 562 || session()->id_user == 436 || session()->id_user == 1269 || session()->id_user == 402
        || session()->id_user == 457 || session()->id_user == 317 || session()->id_user == 801 || session()->id_user == 783  || session()->id_user == 854 || session()->id_user == 1390
      ) {  ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-dolly nav-icon"></i>
            <p>
              Almacen Materia Prima
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php if (session()->id_user == 1 || session()->id_user == 1063  || session()->id_user == 592 || session()->id_user == 650 || session()->id_user == 317 || session()->id_user == 369 || session()->id_user == 329 || session()->id_user == 457 || session()->id_user == 854) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('almacen/autorizar') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Autorizar Transferencias</p>
                </a>
              </li>
            <?php } ?>
            <?php if (session()->id_user == 844 || session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063  || session()->id_user == 650 || session()->id_user == 328 || session()->id_user == 317 || session()->id_user == 457 || session()->id_user == 854) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('almacen/reportes') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reportes</p>
                </a>
              </li>
            <?php } ?>
            <?php if (session()->id_user == 710 || session()->id_user == 272 || session()->id_user == 75 || session()->id_user == 854) {
            } else { ?>
              <li class="nav-item">
                <a href="<?= base_url('almacen/listado') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Listado Codigos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('almacen/salidas') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generar Transferencias</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('almacen/materiales') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Entrega de Materiales</p>
                </a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a href="<?= base_url('almacen/transferencias') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado Transferencias</p>
              </a>
            </li>
          </ul>
        </li>
      <?php } ?>
      <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 || session()->id_user == 1334 || session()->id_user == 852 || session()->id_user == 1390 || session()->id_user == 356 || session()->id_user == 1370 || session()->id_user == 1371 || session()->id_user == 1372) {  ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-network-wired nav-icon"></i>
            <p>
              Sistemas
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php if (session()->id_user == 1 || session()->id_user == 1390 || session()->id_user == 1063 || session()->id_user == 852 || session()->id_user == 1390 || session()->id_user == 1334 || session()->id_user == 1370 || session()->id_user == 1371 || session()->id_user == 1372) { ?>
              <li class="nav-item">
                <a href="<?= base_url('sistemas/reportes') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reportes Sistemas</p>
                </a>
              </li><?php } ?>
            <!-- <li class="nav-item">
                <a href="<?= base_url('sistemas/registrar-equipos-v1') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registrar Equipos</p>
                </a>
              </li> -->
            <!-- <li class="nav-item">
                  <a href="<?= base_url('sistemas/suministros') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Suministros</p>
                  </a>
                </li> -->
            <?php if (session()->id_user == 1 || session()->id_user == 852 || session()->id_user == 1390 || session()->id_user == 1063 || session()->id_user == 1334 || session()->id_user == 1370 || session()->id_user == 1371 || session()->id_user == 1372) {  ?>
              <li class="nav-item">
                <a href="<?= base_url('sistemas/prestamos-equipos') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Prestamos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('sistemas/inventario') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventario</p>
                </a>
              </li>
            <?php }  ?>

            <!-- <li class="nav-item">
                  <a href="<?= base_url('sistemas/historial') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Historial</p>
                  </a>
                </li> -->
            <!-- 
                <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ticket´s</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= base_url('sistemas/tickets') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Generar Ticket</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url('sistemas/mis-tickets') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                        Mis Ticket's
                      </p>
                    </a>
                    <?php if (session()->id_user == 1 || session()->id_user == 852 || session()->id_user == 1390 || session()->id_user == 592 || session()->id_user == 1069 || session()->id_user == 1063) {  ?>
                  <li class="nav-item">
                    <a href="<?= base_url('sistemas/tickets-todos') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Todos los Ticket's</p>
                    </a>
                  </li>
                <?php } ?>
                </ul>
              </li>
              --->

            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Equipos</p>
                <i class="right fas fa-angle-left"></i>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('sistemas/equipos') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Equipos Fijos y Moviles</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('sistemas/equipos-asignar') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Asignar Equipos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('sistemas/calendario') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Calendario Mantto</p>
                  </a>
              </ul>
            </li>

          </ul>
        </li>
      <?php } ?>
      <?php if (session()->id_user == 3 || session()->id_user == 1157 || session()->id_user == 1 || session()->id_user == 852 || session()->id_user == 1390 || session()->id_user == 592 || session()->id_user == 1063 || session()->id_user == 94 || session()->id_user == 2 || session()->id_user == 3 || session()->id_user == 1321 || session()->id_user == 854) {  ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-truck-loading nav-icon"></i>
            <p>
              Suministros
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('suministros/todas-solicitudes') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Todas las Solicitudes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('suministros/solicitud') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Generar Solicitud</p>
              </a>
            </li>

          </ul>
        </li>
      <?php } ?>
      <?php if (session()->id_user == 1 || session()->id_user == 1063 || session()->id_user == 852 || session()->id_user == 1390 || session()->id_user == 1292 || session()->id_user == 112 || session()->id_user == 1314 || session()->id_user == 854) { ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-hand-holding-medical nav-icon"></i>
            <p>
              Servicio Medico
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <?php /* if (session()->id_user == 1063) { */ ?>
            <li class="nav-item">
              <a href="<?= base_url('medico/reportes-medicos') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Reportes</p>
              </a>
            </li>
            <?php  /* } */ ?>
            <li class="nav-item">
              <a href="<?= base_url('medico/todos-los-permisos') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Todos los Registros</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('medico/generar-permiso') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Generar Incapacidades</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('medico/examen-medico') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Crear Examenes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('medico/generar-consulta') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Crear Consulta Medica</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('medico/inventario-medicamentos') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inventario Medicinas</p>
              </a>
            </li>
          </ul>
        </li>
      <?php } ?>
      <?php if (session()->id_user == 1 || session()->id_depto == 42 || session()->id_user == 852 || session()->id_user == 1390) { ?>
        <!--<li class="nav-header">EXAMPLES</li>
          <li class="nav-item">
            <a href="<?= base_url('usuarios/') ?>" class="nav-link">
              <i class="fas fa-users mr-2"></i>
              <p>
                Otro solo
              </p>
            </a>
          </li> -->

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-cogs mr-2"></i>
            <p>
              Configuración
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('usuarios/') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Usuarios</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('usuarios/alta-usuario') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Alta Usuarios</p>
              </a>
            </li>
            <?php if (session()->id_user == 267 || session()->id_user == 1 || session()->id_user == 1390) { ?>
              <li class="nav-item">
                <a href="<?= base_url('qhse/tiempos-obscuros') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tiempos Obscuros</p>
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>