<div class="container mt-4">
   <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fas fa-home"></i> Beranda</a></li>
         <li class="breadcrumb-item active" aria-current="page">Cari Kursus</li>
         <li class="breadcrumb-item active" aria-current="page"><?= $_GET['key'] ?></li>
      </ol>
   </nav>
</div>
<section>
   <div class="col-md-12 text-center py-3">
      <div class="container">
         <?php if ($kejuruan || $kilat) : ?>
            <?php if ($kejuruan) : ?>
               <div class="row">
                  <div class="col-md-12 text-left mb-2">
                     <h3>Kursus Kejuruan</h3>
                  </div>
               </div>
               <div class="row">
                  <?php
                  foreach ($kejuruan as $d) :
                     $kjr_image = '/no-image.jpg';
                     if ($d['kjr_image']) {
                        $kjr_image = '/kejuruan/' . $d['kjr_image'];
                     }
                  ?>
                     <div class="col-md-3 col-sm-12 mb-3">
                        <div class="card text-center" style="width: 100%;">
                           <img src="<?= STORAGEPATH . $kjr_image ?>" class="card-img-top" alt="<?= $d['kjr_nama'] ?>" style="max-height: 11.8rem;">
                           <div class="card-body text-left">
                              <h5 class="card-title text-left"><?= $d['kjr_nama'] ?></h5>
                              <p class="card-text text-left">Rp. <?= number_format($d['kjr_harga'], 2, ',', '.') ?></p>
                              <a href="<?= base_url('kejuruan/lihat/' . $d['kjr_slug']) ?>" class="btn btn-success btn-block"><i class="fas fa-eye"></i> Lihat</a>
                           </div>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
            <?php endif; ?>
            <?php if ($kilat) : ?>
               <div class="row">
                  <div class="col-md-12 text-left mb-2">
                     <h3>Kursus Kilat</h3>
                  </div>
               </div>
               <div class="row">
                  <?php
                  foreach ($kilat as $d) :
                     $kjr_image = '/no-image.jpg';
                     if ($d['kjr_image']) {
                        $kjr_image = '/kejuruan/' . $d['kjr_image'];
                     }
                  ?>
                     <div class="col-md-3 col-sm-12 mb-3">
                        <div class="card text-center" style="width: 100%;">
                           <img src="<?= STORAGEPATH . $kjr_image ?>" class="card-img-top" alt="<?= $d['kjr_nama'] ?>" style="max-height: 11.8rem;">
                           <div class="card-body text-left">
                              <h5 class="card-title text-left"><?= $d['kjr_nama'] ?></h5>
                              <p class="card-text text-left">Rp. <?= number_format($d['kjr_harga'], 2, ',', '.') ?></p>
                              <a href="<?= base_url('kilat/lihat/' . $d['kjr_slug']) ?>" class="btn btn-success btn-block"><i class="fas fa-eye"></i> Lihat</a>
                           </div>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
            <?php endif; ?>
         <?php else : ?>
            <div class="row">
               <div class="col-md-12">
                  <img class="mb-3" src="<?= base_url('/assets/images/search.svg') ?>" style="width: 50%; min-width: 13rem;" alt="No Result">
                  <h5>Pencarian tidak ada !!</h5>
               </div>
            </div>
         <?php endif; ?>
      </div>
   </div>
</section>