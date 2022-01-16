<style>
   .list-materi {
      background-color: #e3ece5;
      border-radius: .2rem !important;
      margin-left: 10px;
      margin-right: 10px;
      padding: .4rem 1.25rem;
      margin-bottom: 10px;
   }
</style>
<div class="container mt-4">
   <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fas fa-home"></i> Beranda</a></li>
         <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url('kejuruan') ?>">Kursus Kejuruan</a></li>
         <li class="breadcrumb-item active" aria-current="page"><?= $kejuruan['kjr_nama'] ?></li>
      </ol>
   </nav>
</div>
<section>
   <div class="col-md-12 text-center py-3">
      <div class="container">
         <div class="row">
            <div class="col-md-4">
               <?php
               $kjr_image = '/no-image.jpg';
               if ($kejuruan['kjr_image']) {
                  $kjr_image = '/kejuruan/' . $kejuruan['kjr_image'];
               }
               ?>
               <img src="<?= STORAGEPATH . $kjr_image ?>" class="card-img-top" alt="<?= $kejuruan['kjr_nama'] ?>">
            </div>
            <div class="col-md-8">
               <p class="h4 text-left"><?= $kejuruan['kjr_nama'] ?></p>
               <p class="h5 text-left">Rp. <?= number_format($kejuruan['kjr_harga'], 2, ',', '.') ?></p>
               <hr class="mb-1 mt-3">
               <div class="row">
                  <div class="col-md-4 text-left">
                     <i class="fas fa-user"></i> <?= $kejuruan['usr_name'] ?>
                  </div>
                  <div class="col-md-4 text-left">
                     <i class="fas fa-calendar-alt"></i> <?= date_indo(explode(' ', $kejuruan['kjr_created_at'])[0]); ?>
                  </div>
                  <div class="col-md-4 text-left">
                     <i class="fas fa-eye"></i> <?= $kejuruan['kjr_hit'] ?> Dilihat
                  </div>
               </div>
               <div class="row py-md-5 py-3">
                  <div class="col-md-3">
                     <button onclick="gabung('<?= (isset($_SESSION['system_members']['mbr_id'])) ? $_SESSION['system_members']['mbr_id'] : '' ?>','<?= $kejuruan['kjr_id'] ?>')" class="btn btn-success btn-block"><i class="fas fa-book-reader"></i> Gabung Kelas</button>
                  </div>
               </div>
            </div>
         </div>
         <div class="row py-3">
            <div class="col-md-12">
               <ul class="nav nav-tabs">
                  <li class="nav-item">
                     <a class="nav-link active" data-id="deskripsi"><i class="fas fa-list-ul"></i> Deskripsi</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" data-id="materi"><i class="fas fa-book"></i> Materi</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" data-id="instruktur"><i class="fas fa-user-tie"></i> Instruktur</a>
                  </li>
               </ul>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="tab-content">
                  <div class="tab-pane active text-left" id="deskripsi">
                     <div class="container">
                        <?= $kejuruan['kjr_deskripsi'] ?>
                     </div>
                  </div>
                  <div class="tab-pane" id="instruktur">
                     <div class="row">
                        <div class="col-md-3 text-center">
                           <?php
                           $usr_foto = '/no-image.jpg';
                           if ($kejuruan['usr_foto']) {
                              $usr_foto = '/user/' . $kejuruan['usr_foto'];
                           }
                           ?>
                           <img src="<?= STORAGEPATH . $usr_foto ?>" style="max-width: 12rem; max-height: 14rem; width: auto;" class="card-img-top mb-2" alt="<?= $kejuruan['usr_name'] ?>">
                        </div>
                        <div class="col-md-8 text-left">
                           <p class="h4"><?= $kejuruan['usr_name'] ?></p>
                           <p><?= $kejuruan['usr_deskripsi'] ?></p>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane text-left" id="materi">
                     <div class="container">
                        <ul class="list-group list-group-flush mb-3">
                           <?php foreach ($kejuruan['materi'] as $materi) : ?>
                              <h2 class="mb-2 mt-3 h5"><i class="fa fa-file"></i> <?= $materi['mtr_nama'] ?></h2>
                              <?php foreach ($materi['submateri'] as $sub) : ?>
                                 <li class="list-group-item list-materi">
                                    <i class="fa fa-check-circle"></i> <?= $sub['mtr_nama'] ?>
                                 </li>
                              <?php endforeach; ?>
                           <?php endforeach; ?>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<script src="<?= base_url() ?>script/kejuruan/lihat.js"></script>