<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="content-type" content="text/html; charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $this->renderSection('title'); ?> | Walworth &reg;</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>/public/images/icon-180.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
 <!--  <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"> -->
  <!-- iCheck -->
  <!--  <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">  -->
  <!-- JQVMap -->
 <!--   <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/jqvmap/jqvmap.min.css">  -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/public/dist/css/adminlte_v1.min.css">
  <!-- overlayScrollbars -->
   <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css"> 
  <!-- Daterange picker -->
   <!-- <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/daterangepicker/daterangepicker.css">  -->
  <!-- summernote -->
  <!-- <link rel="stylesheet" href="<?= base_url() ?>/public/plugins/summernote/summernote-bs4.min.css"> -->
  <link rel="stylesheet" href="<?= base_url() ?>/public/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/css/responsive.bootstrap4.min.css"> 
  <link rel="stylesheet" href="<?= base_url() ?>/public/css/animate.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/public/css/style.css">
  <link href="https://fonts.cdnfonts.com/css/roboto-condensed" rel="stylesheet">
<!-- <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> -->


  <style>
table.dataTable tbody tr:hover {
   background-color:rgba(210, 214, 222, .7) !important;
}
.font-tables{
  /* font-family: 'Roboto Condensed', sans-serif; */
 font-family: 'Roboto Condensed', sans-serif; 
 font-weight: 400;
 font-style: normal;
}
.sie-font{
  /* font-family: 'Roboto Condensed', sans-serif; */
 font-family: 'Roboto Condensed Light', sans-serif; 
}
.sie-font-bold{
  font-family: 'Roboto Condensed', sans-serif !important;
  font-weight: 700;
  font-style: normal;
}
h1{
  font-family:"Source Sans Pro";
  font-weight: 500;
}
h2,h3,h4,h5{
  font-family: 'Roboto Condensed', sans-serif !important;
  font-weight: 700 !important;
  font-style: normal;
}
.btn-guardar{
  font-family: 'Roboto Condensed', sans-serif !important;
  font-weight: 700;
  font-style: normal;
}
.btn-danger{
  font-family: 'Roboto Condensed', sans-serif !important;
  font-weight: 700 !important;
  font-style: normal;
}
.btn-item{
  font-family: 'Roboto Condensed', sans-serif !important;
  font-weight: 700 !important;
  font-style: normal;
}

</style>
  
  <?= $this->renderSection('css') ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed sie-font">
  <div class="wrapper">
    <?= $this->include('layout/header') ?>
    <?= $this->include('layout/menu') ?>
    <?= $this->renderSection('content') ?>
  </div>
  <?= $this->include('layout/footer') ?>
  <!-- jQuery -->
  <!-- <script src="<?= base_url() ?>/public/plugins/jquery/jquery.min.js"></script> -->
  <script src="<?= base_url() ?>/public/plugins/jquery/jquery-3.7.1.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?= base_url() ?>/public/plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
 
 
   <!-- daterangepicker -->
   <script src="<?= base_url() ?>/public/plugins/moment/moment.min.js"></script>
  <!-- <script src="<?= base_url() ?>/public/plugins/daterangepicker/daterangepicker.js"></script> -->
  <!-- Tempusdominus Bootstrap 4 -->
 <!--  <script src="<?= base_url() ?>/public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script> -->
  <!-- Summernote -->
 <!--  <script src="<?= base_url() ?>/public/plugins/summernote/summernote-bs4.min.js"></script> -->
  <!-- overlayScrollbars -->
  <script src="<?= base_url() ?>/public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  
  
  <!-- AdminLTE App -->
  <script src="<?= base_url() ?>/public/dist/js/adminlte.js"></script>
  
  <!-- DataTables  & Plugins -->
  <script src="<?= base_url() ?>/public/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() ?>/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url() ?>/public/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
 <!--  <script src="<?= base_url() ?>/public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script> -->
  <script src="<?= base_url() ?>/public/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
   <script src="<?= base_url() ?>/public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script> 
  <script src="<?= base_url() ?>/public/plugins/jszip/jszip.min.js"></script>
 <!--  <script src="<?= base_url() ?>/public/plugins/pdfmake/pdfmake.min.js"></script> -->
 <!--  <script src="<?= base_url() ?>/public/plugins/pdfmake/vfs_fonts.js"></script> -->
  <script src="<?= base_url() ?>/public/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <!-- <script src="<?= base_url() ?>/public/plugins/datatables-buttons/js/buttons.print.min.js"></script> -->
<!--   <script src="<?= base_url() ?>/public/plugins/datatables-buttons/js/buttons.colVis.min.js"></script> -->
 <!-- Bootstrap 4 -->
 <script src="<?= base_url() ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>/public/plugins/sweetalert2/sweetalert2@10.js"></script>
  <?= $this->renderSection('js') ?>

</body>

</html>