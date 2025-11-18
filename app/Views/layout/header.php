<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <!-- <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Contactos</a>
    </li> -->
    <li class="nav-item d-none d-sm-inline-block">
    <a class="nav-link" href="<?= base_url('directorio/') ?>">
    <i class="fas fa-address-card" style="margin-right: 5px;"></i>Directorio
        <span class="badge badge-warning navbar-badge"></span> 
      </a>
      </li>
  </ul>
  <input type="hidden" id="urls" value="<?= site_url() ?>">
  <script>
    const urls = document.getElementById('urls').value;
    const key = "5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677";
  </script>
  <!-- Right navbar links -->
  <!-- Navbar Search -->
  <ul class="navbar-nav ml-auto">

    <?PHP if (session()->id_user == 1063 || session()->id_user == 1) { ?>
      <!-- Barra Buscador -->
      <!-- <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
      </a>
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>  -->
      <!-- Notifications Dropdown Menu -->
      <!-- <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge"></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">Notificaciones</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a>
      </div>
    </li>  -->
      <a class="nav-link" href="<?= base_url('usuarios/') ?>">
        <i class="fas fa-users" style="margin-right: 5px;"></i>Usuarios
        <span class="badge badge-warning navbar-badge"></span>
      </a>
      <a class="nav-link" href="<?= base_url('sistemas/inventario') ?>">
        <i class="fas fa-boxes" style="margin-right: 5px;"></i>Inventario
        <span class="badge badge-warning navbar-badge"></span>
      </a>
      <a class="nav-link" href="<?= base_url('permisos/todos') ?>">
        <i class="fas fa-copy" style="margin-right: 5px;"></i>Permisos
        <span class="badge badge-warning navbar-badge"></span>
      </a>
    <?PHP } ?>
    <a class="nav-link" href="<?= base_url(route_to('signout')) ?>">
      <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
    </a>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <?= session()->username; ?>
        <i class="fas fa-user-cog"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">Menu</span>
        <div class="dropdown-divider"></div>
        <a href="<?= base_url("usuarios/info") ?>" class="dropdown-item">
          <i class="fas fa-info-circle mr-2"></i> Mi Información
        </a>
        <div class="dropdown-divider"></div>
        <?php if (session()->id_user == 1 || session()->id_user == 1063 || session()->id_user == 27 || session()->id_user == 50 || session()->id_user == 252 || session()->id_user == 265 || session()->id_user == 267 || session()->id_user == 627) { ?>
          <a href="<?= base_url("usuarios/info-todos") ?>" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> Informacion de Usuarios
          </a>
          <div class="dropdown-divider"></div>
        <?php } ?>
        <button id="btn_password_change" class="button dropdown-item">
          <i class="fas fa-key mr-2"></i> Cambiar mi Contraseña
        </button>
        <!--
          <div class="dropdown-divider"></div>
          
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a> -->
        <!-- <div class="dropdown-divider"></div>
        <a href="<?= base_url(route_to('signout')) ?>" class="dropdown-item dropdown-footer"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a> -->
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <?php if (session()->id_user == 1 || session()->id_user == 1063) { ?>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    <?php } ?>
  </ul>
</nav>
<!-- /.navbar -->

<!-- small modal -->
<div class="modal" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" style="margin-top: 15%;">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-key mr-2" style="margin-right: 15px;"></i>Cambiar Contraseña</h4>
      </div>
      <form id="form_nueva_contra" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label for="nueva_pw">Nueva Contraseña:</label>
              <input type="password" class="form-control" id="nueva_pw" name="nueva_pw" onchange="validar()">
              <div id="error_nueva_pw" class="text-danger"></div>
            </div>
            <div class="col-md-12" style="margin-top: 1rem;">
              <label for="confirma_pw">Confirma Contraseña:</label>
              <input type="password" class="form-control" id="confirma_pw" name="confirma_pw" onchange="validar()">
              <div id="error_confirma_pw" class="text-danger"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
          <button type="submit" id="btn_nueva_contra" class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
</div>
</div>
<!-- style_modal_animation.css -->
<?= $this->section('js') ?>
<script>
  $('#btn_password_change').click(function() {
    $("#nueva_pw").val('');
    $("#error_nueva_pw").text('');
    $("#nueva_pw").removeClass('has-error');
    $("#confirma_pw").val('');
    $("#error_confirma_pw").text('');
    $("#confirma_pw").removeClass('has-error');

    $("#smallModal").modal("show");
  });

  function validar() {
    if ($.trim($("#nueva_pw").val()).length > 0) {
      $("#nueva_pw").removeClass('has-error');
      $("#error_nueva_pw").text('');
    }
    if ($.trim($("#confirma_pw").val()).length > 0) {
      $("#confirma_pw").removeClass('has-error');
      $("#error_confirma_pw").text('');
      if ($.trim($("#nueva_pw").val()).length > 0 && ($("#nueva_pw").val() != $("#confirma_pw ").val())) {
        $("#confirma_pw").addClass('has-error');
        $("#error_confirma_pw").text('Las contraseñas no coinciden');
      }
    }
  }

  $("#form_nueva_contra").submit(function(e) {
    e.preventDefault();
    var count = 0
    if ($.trim($("#nueva_pw").val()).length == 0) {
      $("#nueva_pw").addClass('has-error');
      $("#error_nueva_pw").text('Campo requerido');
      count += 1;
    } else {
      $("#nueva_pw").removeClass('has-error');
      $("#error_nueva_pw").text('');
    }

    if ($.trim($("#confirma_pw").val()).length == 0) {
      $("#confirma_pw").addClass('has-error');
      $("#error_confirma_pw").text('Campo requerido');
      count += 1;
    } else {
      $("#confirma_pw").removeClass('has-error');
      $("#error_confirma_pw").text('');
    }

    if ($("#nueva_pw").val() != $("#confirma_pw ").val()) {
      $("#confirma_pw").addClass('has-error');
      $("#error_confirma_pw").text('Las contraseñas no coinciden');
      count += 1;
    }

    if (count != 0) {
      return false;
    }
    let timerInterval = Swal.fire({
      title: 'Generando Ticket!',
      html: 'Espere unos Segundos.',
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading()
      },
    });
    $("#btn_nueva_contra").prop('disabled', true);
    const password = new FormData($("#form_nueva_contra")[0]);
    $.ajax({
      type: "post",
      url: `${urls}sistemas/nuevo-password`,
      data: password,
      cache: false,
      dataType: "json",
      contentType: false,
      processData: false,
      success: function(save) {
        Swal.close(timerInterval);
        $("#btn_nueva_contra").prop('disabled', false);
        if (save === true) {
          $("#form_nueva_contra")[0].reset();
          $("#smallModal").modal("hide");
          Swal.fire({
            icon: 'success',
            title: "¡Cambio Exitoss!",
            html: `Se cambio tu contraseña`,
          });
          CargarTickets();
        } else if (save === "repeated_pw") {
          Swal.fire({
            icon: 'info',
            title: "¡Contraseña Repetida!",
            html: `La contraseña no puede ser igual a la existente`,
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Algo salió Mal! Contactar con el Administrador",
          });
        }
      },
    }).fail(function(jqXHR, textStatus, errorThrown) {
      $("#btn_nueva_contra").prop('disabled', false);
      if (jqXHR.status === 0) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Fallo de conexión: ​​Verifique la red.",
        });
      } else if (jqXHR.status == 404) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No se encontró la página solicitada [404]",
        });
      } else if (jqXHR.status == 500) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Internal Server Error [500]",
        });
      } else if (textStatus === "parsererror") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Error de análisis JSON solicitado.",
        });
      } else if (textStatus === "timeout") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Time out error.",
        });
      } else if (textStatus === "abort") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Ajax request aborted.",
        });
      } else {
        alert("Uncaught Error: " + jqXHR.responseText);
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: `Uncaught Error: ${jqXHR.responseText}`,
        });
      }
    });
  });
</script>

<?= $this->endSection() ?>