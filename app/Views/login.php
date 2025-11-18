<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar Sesión | Proveedores</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>/public/images/favicon.ico">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/dist/css/adminlte_v1.min.css">
  <link rel="stylesheet" href="public/css/nieve/styles.css">

 <style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    height: 100vh;
    font-family: "Source Sans Pro", sans-serif;
    background-color: #f8f9fa;
    overflow: hidden;
  }

  .container1 {
    display: flex;
    width: 100%;
    height: 100vh;
  }

  /* === SECCIÓN IZQUIERDA (60%) === */
  .left-section {
    flex: 3; /* 60% */
    
    background-size: contain;   /* 🔹 Imagen completa visible */
    background-repeat: no-repeat;
    background-position: center;
    background-color: #ffffff;  /* 🔹 Fondo blanco si sobra espacio */
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Si usas <img> dentro de la sección */
  .left-section img {
    width: 100%;
  height: 100%;
  object-fit: cover;   /* 🔹 LLENA el contenedor */
  }

  /* === SECCIÓN DERECHA (40%) === */
  .right-section {
    flex: 2; /* 40% */
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: -3px 0 10px rgba(0, 0, 0, 0.05);
    position: relative;
  }

  /* === LOGIN BOX === */
  .login-box {
    width: 100%;
    max-width: 520px; /* un poco más amplio */
    background: transparent;
    padding: 20px;
    transform: translateY(-90px);
  }

  @media (max-height: 700px) {
  .login-box {
    transform: translateY(-20px); /* 🔹 Menor desplazamiento en pantallas más bajas */
  }
}

  .card {
    border: none;
    background: transparent;
    box-shadow: none;
  }

  /* === INPUTS === */
  .input {
    position: relative;
    width: 100%;
    margin: 15px auto;
  }

  #login span {
    position: absolute;
    display: block;
    color: rgb(64 62 62 / 30%);
    left: 10px;
    top: 13px;
    font-size: 20px;
  }

  input {
    width: 100%;
    padding: 10px 5px 10px 40px;
    border: 1px solid #ededed;
    border-radius: 4px;
    transition: 0.2s ease-out;
  }

  input:focus {
    padding: 10px 5px 10px 10px;
    outline: 0;
    border-color: #3C3C48;
  }

  /* === BOTÓN === */
  .btn-guardar {
    background-color: rgb(177, 11, 11);
    border: none;
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
  }

  .btn-guardar:hover {
    color: white;
    background-color: rgba(114, 0, 0, 1);
  }

  /* === RESPONSIVE (MÓVIL) === */
  @media (max-width: 768px) {
    .container1 {
      flex-direction: column;
    }

    .left-section {
      display: none;
    }

    .right-section {
      flex: 1;
      width: 100%;
      box-shadow: none;
    }

    .login-box {
      max-width: 100%;
      padding: 30px;
    }
  }
</style>

</head>

<body>
  <div class="container1">

    <div class="left-section">
        <img src="<?php base_url() ?>public/images/planta.webp" alt="Grupo Walworth" class="img-fluid" />
    </div>
    <div class="right-section">
      <div class="login-box">
        <div class="card p-3">
          <div class="card-body login-card-body">
            <div class="mb-5 text-center">
              <img src="<?php base_url() ?>/public/images/inval.png" alt="Proveedores" class="img-fluid" width="90%">
              <h5><b>Bienvenido!</b></h5>
            </div>



            <?php if (session('msg')) : ?>
              <div id="error" class="error">
                <div class="alert <?= session('msg.type') ?> alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <strong>Advertencia!</strong> <?= session('msg.body') ?>
                </div>
                <span></span>
              </div>
            <?php endif;

            $errors = [
              'password' => 'El password es Requerido.',
              'email'    => 'El Email es Requerido.'
            ];
            ?>


            <form id="login" action="<?= site_url('auth/check') ?>" method="post">
              <div class="input-group mb-3 input">
                <input type="text" id="email" name="email" placeholder="Usuario" value="<?= old('email') ?>">
                <span class="fa fa-user"></span>
              </div>
              <?php if (session('errors.email')) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  El Email es Requerido.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>
              <div class="input-group mb-3 input">
                <input type="password" id="password" name="password" placeholder="Contraseña">
                <span class="fas fa-lock"></span>
              </div>
              <?php if (session('errors.password')) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  La Contraseña es Requerida
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>
              <div class="text-center">
                <button type="submit" class="btn btn-guardar">Iniciar Sesión</button>
              </div>
            </form>
            <br>
            
            <hr>
            <div class="text-center">

              <span class="text-center"> <b>Versión</b> 2.3.0 - <?= date('Y'); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="<?= base_url() ?>/public/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url() ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>/public/dist/js/adminlte.min.js"></script>

  <script>
    $(".input").focusin(function() {
      $(this).find("span").animate({
        opacity: "0"
      }, 200);
    });

    $(".input").focusout(function() {
      $(this).find("span").animate({
        opacity: "1"
      }, 300);
    });
  </script>

</body>

</html>