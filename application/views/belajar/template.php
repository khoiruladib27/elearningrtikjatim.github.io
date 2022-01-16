<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title><?= $title ?> | <?= getApp('app_title')['conf_value'] ?></title>

   <!-- Favicons -->
   <link href="<?= STORAGEPATH ?>/system/<?= getApp('app_icons')['conf_value'] ?>" rel="icon">
   <link href="<?= STORAGEPATH ?>/system/<?= getApp('app_icons')['conf_value'] ?>" rel="apple-touch-icon">
   <!-- Google Font: Source Sans Pro -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   <!-- Font Awesome Icons -->
   <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>plugins/fontawesome-free/css/all.min.css">
   <!-- overlayScrollbars -->
   <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>dist/css/adminlte.min.css">
   <!-- Toastr -->
   <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>plugins/toastr/toastr.min.css">
   <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>css/style.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
   <!-- REQUIRED SCRIPTS -->
   <!-- jQuery -->
   <script src="<?= base_url('assets/admin/') ?>plugins/jquery/jquery.min.js"></script>
   <!-- Bootstrap -->
   <script src="<?= base_url('assets/admin/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
   <!-- overlayScrollbars -->
   <script src="<?= base_url('assets/admin/') ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
   <!-- AdminLTE App -->
   <script src="<?= base_url('assets/admin/') ?>dist/js/adminlte.js"></script>
   <script src="<?= base_url('assets/admin/') ?>js/custome.js"></script>
   <!-- Toastr -->
   <script src="<?= base_url('assets/admin/') ?>plugins/toastr/toastr.min.js"></script>
   <script>
      var BASE_URL = "<?= base_url(); ?>";
      toastr.options = {
         "closeButton": true,
         "positionClass": "toast-top-right",
      }
   </script>

   <div class="wrapper">
      <!-- Navbar -->
      <?php $this->load->view('belajar/include/navbar') ?>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <?php $this->load->view('belajar/include/sidebar') ?>
      <!-- Content Wrapper. Contains page content -->
      <?php $this->load->view($page); ?>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
         <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->

      <!-- Main Footer -->
      <footer class="main-footer">
         <strong>Copyright &copy; 2021 Simuda Courses</strong>
         <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
         </div>
      </footer>
      <!-- SweetAlert2 -->
      <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
      <!-- SweetAlert2 -->
      <script src="<?= base_url() ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
   </div>
   <!-- ./wrapper -->

</body>

</html>