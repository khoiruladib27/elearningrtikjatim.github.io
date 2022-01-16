<div class="col-12 col-lg-3 mt-4">
   <div class="card">
      <div class="card-header">
         <i class="fas fa-user"></i> Member
      </div>
      <div class="card-body py-3">
         <?php
         $foto = $_SESSION['system_members']['mbr_foto'];
         if (!isset($foto)) {
            $foto = STORAGEPATH . '/no-image.jpg';
         } else {
            $foto = STORAGEPATH . '/member/' . $foto;
         }
         ?>
         <div class="row pt-0">
            <div class="col-3 text-center px-1">
               <img src="<?= $foto; ?>" class="rounded-circle" style="max-height: 4rem; width: auto; max-width: 100%;" alt="">
            </div>
            <div class="col-9 px-1">
               <p class="my-1" style="font-size: 1rem; font-weight: 500;"><?= $_SESSION['system_members']['mbr_name']; ?></p>
               <p class="my-0" style="font-size: .8rem;"><?= $_SESSION['system_members']['mbr_email'] ?></p>
            </div>
         </div>
      </div>
      <div class="card-header">
         <i class="fas fa-list-ul"></i> Menu
      </div>
      <div class="card-body">
         <ul class="list-group list-group-flush mb-3">
            <a href="<?= base_url('member') ?>">
               <li class="list-group-item list-materi <?php if ($_SESSION['member_act'] == 'dashboard') echo "active"; ?>">
                  <i class="fa fa-home"></i> Dashboard
               </li>
            </a>
            <a href="<?= base_url('member/kelas') ?>">
               <li class="list-group-item list-materi <?php if ($_SESSION['member_act'] == 'kelas') echo "active"; ?>">
                  <i class="fa fa-book"></i> Kelas Saya
               </li>
            </a>
            <a href="<?= base_url('member/sertifikat') ?>">
               <li class="list-group-item list-materi <?php if ($_SESSION['member_act'] == 'sertifikat') echo "active"; ?>">
                  <i class="fa fa-award"></i> Sertifikat
               </li>
            </a>
            <a href="<?= base_url('member/setting') ?>">
               <li class="list-group-item list-materi <?php if ($_SESSION['member_act'] == 'setting') echo "active"; ?>">
                  <i class="fa fa-cogs"></i> Setting
               </li>
            </a>
            <a href="<?= base_url('member/auth/logout') ?>">
               <li class="list-group-item list-materi">
                  <i class="fa fa-sign-out-alt"></i> Logout
               </li>
            </a>
         </ul>
      </div>
   </div>
</div>