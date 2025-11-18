<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar Sesión | Walworth &reg;</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>/public/images/icon-180.png">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/public/dist/css/adminlte_v1.min.css">
  <link rel="stylesheet" href="/public/css/nieve/styles.css"></link>
  <style>
    
 body {
    background-image: url(./public/images/descarga_2.jpg);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    min-height: 100vh;
    margin: 0;
    font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
}

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
      display: block;
      border: 1px solid #ededed;
      border-radius: 4px;
      transition: 0.2s ease-out;
      color: rgba(#ededed, 10%);
    }

    input:focus {
      padding: 10px 5px 10px 10px;
      outline: 0;
      border-color: #3C3C48;
    }

    .img-login {
      width: 3rem;
      height: 1rem;
    }

    .btn-guardar {
      color: #fff;
      background-color: #1f2d3d;
      border-color: #1f2d3d;
      box-shadow: none;
      font-size: 1rem;
    }

    .btn-guardar:hover {
      color: #fff !important;
      background-color: #2d4158;
      border-color: #2d4158;
      box-shadow: none;
    }

    .login-box,
    .register-box {
      width: 420px;
    }

    .login-page, .register-page {
    align-items: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

  </style>
</head>

<body class="hold-transition login-page">
  <!-- <div class="login-logo" style="margin-bottom:11rem;">
      
      <img src="<?= base_url() ?>/public/images/WW-180Logo.png" alt="Grupo Walworth" class="img-fluid">
    </div> -->
  <div class="login-box">

    <!-- /.login-logo -->
    <div class="card p-3 shadow-2-strong" style="box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23) !important;">

      <div class="card-body login-card-body">
        <!--  <span class="login-box-msg">Sistema de Integración Empresarial</span> -->
        <div class="mb-5">
          <!-- <img src="<?= base_url() ?>/public/images/login-logo.png" alt="Grupo Walworth" class="img-fluid"> -->
          <img src="<?= base_url() ?>/public/images/logo_Walworth.png" alt="Grupo Walworth" class="img-fluid">
        </div>
        <?php

        use CodeIgniter\Email\Email;

        if (session('msg')) : ?>
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
        <form id="login" action="<?= base_url(route_to('signin')) ?>" method="post">

          <!-- <label for="email">Usuario:</label> -->
          <div class="input-group mb-3 input">
            <input type="text" class="" id="email" name="email" placeholder="Usuario" value="<?= old('email') ?>">
            <span class="fa fa-user"></span>
            <!-- <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div> -->
            <!-- <p>Colocar en <b>USUARIO</b>: Correo empresarial.</p> -->
          </div>
          <?php if (session('errors.email')) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              El Email es Requerido.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>
          <!-- <label for="password">Contraseña:</label> -->
          <div class="input-group mb-3 input">
            <input type="password" class="" id="password" name="password" placeholder="Contraseña">
            <span class="fas fa-lock"></span>
            <!-- <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div> -->
            <!-- <p style="font-size: 15px;">Colocar en <b>CONTRASEÑA</b>: Número de nómina.</p> -->
          </div>
          <?php if (session('errors.password')) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              La Contraseña es Requerida
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <div class="row">
            <!-- /.col -->
            <div class="col-12 text-center mb-5">
              <button type="submit" class="btn btn-guardar btn-block">Iniciar Sesión</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <hr>
        <div class="text-center">

          <span class="text-center"> <b>Versión</b> 2.1.0 - <?= date('Y'); ?></span>
        </div>

        <!--  <div class="social-auth-links text-center mb-3" style="border: 2px dotted black;background: #E9E9E9;">
          <h5 style="color: #E11F1C;">NOTA</h5>
          <p style="font-size: 14px;"style="font-size: 14px;">En caso de no contar con un correo empresarial, colocar número de nómina.</p>
           <p>- OR -</p>

          <a href=" <?php // $googleAuth; ?>" class="btn btn-block btn-danger">
          <i class="fab fa-google"></i>
             Iniciar Sesión con Google
          </a>
        </div>  -->
        <!-- /.social-auth-links -->

      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url() ?>/public/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url() ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>/public/dist/js/adminlte.min.js"></script>
  <script src="<?= base_url() ?>/public/js/nieve/snowfall.jquery.min.js"></script>
  <script src="<?= base_url() ?>/public/js/nieve/snowfall.min.js"></script>
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
<script>
$(document).ready(function() {
     //default options
    // snowFall.snow(document.body);
  //snowFall.snow(elementCollection, {image: "<?= base_url() ?>/public/images/flake.png", minSize: 10, maxSize:32});
        //   snowFall.snow(document.body, "clear");
       //     snowFall.snow(document.body, { image: "<?= base_url() ?>/public/images/flake.png", minSize: 10, maxSize:32});
    //     snowFall.snow(document.body, {shadow : true, flakeCount:200});
});
</script>

</body>

</html>