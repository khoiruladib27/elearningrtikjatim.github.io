<nav class="main-header navbar navbar-expand navbar-<?= getApp('app_topbar_theme')['conf_value'] ?> navbar-<?= getApp('app_topbar_color')['conf_value'] ?>">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
      <li class="nav-item">
         <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
         <span class="nav-link text-white" style="font-weight: 800;"><?= $kejuruan['kjr_nama'] ?></span>
      </li>
   </ul>
   <?php
   $mbr_foto = $_SESSION['system_members']['mbr_foto'];
   if (!$mbr_foto) {
      $mbr_foto = STORAGEPATH . '/no-image.jpg';
   } else {
      $mbr_foto = STORAGEPATH . '/member/' . $mbr_foto;
   }
   ?>
   <!-- Right navbar links -->
   <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
         <a class="nav-link" data-toggle="dropdown" href="#">
            <img src="<?= $mbr_foto; ?>" class="img-circle" style="max-width: 1.8rem; max-height: 1.8rem;">
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">
               <img src="<?= $mbr_foto; ?>" class="img-circle" style="max-width: 5rem; max-height: 5rem; margin-bottom: 1rem;">
               <h5><?= $_SESSION['system_members']['mbr_name']; ?></h5>
            </span>
         </div>
      </li>
   </ul>
</nav>