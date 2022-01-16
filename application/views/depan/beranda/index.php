<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/OwlCarousel/owl.carousel.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/OwlCarousel/owl.theme.default.min.css">

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
   <ol class="carousel-indicators">
      <?php
      $no = 0;
      $act = 'active';
      foreach ($slider as $s) : ?>
         <li data-target="#carouselExampleIndicators" data-slide-to="<?= $no ?>" class="<?= $act ?>"></li>
      <?php
         $no++;
         $act = '';
      endforeach; ?>
   </ol>
   <div class="carousel-inner">
      <?php
      $act = 'active';
      foreach ($slider as $s) : ?>
         <div class="carousel-item <?= $act ?>">
            <a href="<?= $s['sld_link'] ?>">
               <img src="<?= STORAGEPATH ?>/slider/<?= $s['sld_image'] ?>" class="d-block w-100" alt="<?= $s['sld_nama'] ?>">
            </a>
         </div>
      <?php $act = '';
      endforeach; ?>
   </div>
   <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
   </a>
   <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
   </a>
</div>
<div class="container">
   <div class="row mt-4 mb-4">
      <div class="col-md-12 text-center">
         <h2 class="mb-4">Mitra Kami</h2>
         <div class="owl-carousel" id="carousel-mitra">
            <?php foreach ($mitra as $m) : ?>
               <div class="mx-3"><a href="<?= $m['mtr_link'] ?>" target="_blank"><img src="<?= STORAGEPATH ?>/mitra/<?= $m['mtr_image'] ?>" alt="<?= $m['mtr_nama'] ?>"></a></div>
            <?php endforeach; ?>
         </div>
      </div>
   </div>
</div>
<section style="background-color: #f9f9f9;">
   <div class="col-md-12 text-center py-4">
      <h2 class="mb-3">Kursus Kejuruan</h2>
      <div class="container">
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
         <div class="row mt-3">
            <div class="col-md-4 offset-md-4 col-sm-12"><a href="<?= base_url('kejuruan') ?>" class="btn btn-block btn-success">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a></div>
         </div>
      </div>
   </div>
</section>
<section>
   <div class="col-md-12 text-center py-4">
      <h2 class="mb-4">Kursus Kilat Bermanfaat</h2>
      <div class="container">
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
                        <a href="<?= base_url('kejuruan/lihat/' . $d['kjr_slug']) ?>" class="btn btn-warning btn-block"><i class="fas fa-eye"></i> Lihat</a>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
         <div class="row mt-3">
            <div class="col-md-4 offset-md-4 col-sm-12"><a href="<?= base_url('kilat') ?>" class="btn btn-block btn-warning">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a></div>
         </div>
      </div>
   </div>
</section>
<section id="tentang" style="background-color: #f9f9f9;">
   <div class="col-md-12 text-center py-4">
      <h2 class="mb-4">Tentang</h2>
      <div class="container">
         <div class="row">
            <div class="col-md-6 col-12">
               <img src="<?= STORAGEPATH ?>/tentang/<?= $tentang['tnt_image'] ?>" class="mb-3" style="width: 100%;" alt="Tentang">
            </div>
            <div class="col-md-6 col-12 text-left">
               <?= $tentang['tnt_isi'] ?>
            </div>
         </div>
      </div>
   </div>
</section>
<section>
   <div class="col-md-12 text-center py-4">
      <h2 class="mb-3">Testimoni Peserta</h2>
      <div class="container">
         <div class="row">
            <div class="owl-carousel" id="carousel-pembelajar">
               <?php foreach ($testimoni as $testi) :
                  $tst_image = '/no-image.jpg';
                  if ($testi['tst_image']) {
                     $tst_image = '/testimoni/' . $testi['tst_image'];
                  }
               ?>
                  <div class="col-md-4 col-sm-12">
                     <div class="card text-center w-100 pt-3" style="min-width: 17rem;">
                        <img src="<?= STORAGEPATH . $tst_image ?>" style="max-width: 40%; height: 100px; margin-left: 31%;" class="card-img-top rounded-circle mb-2" alt="<?= $testi['tst_nama'] ?>">
                        <b class="mt-2"><?= $testi['tst_nama'] ?></b>
                        <div class="card-body mt-0 pt-2">
                           <p class="card-text"><?= $testi['tst_isi'] ?></p>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      </div>
   </div>
</section>
<div class="jumbotron jumbotron-fluid mb-0" style="background: #001500 url(<?= base_url(); ?>storage/img/footer-bg.png) no-repeat right top">
   <div class="container">
      <p class="h3 mb-3" style="color: white; font-weight: 700;">Di LPK Muda Al-hidayah</p>
      <p class="h4" style="color: white; font-weight: 300;">Dapatkan akses materi kursus lebih mendalam dengan harga terjangkau, dibawakan oleh para praktisi terbaik, bersertifikat, dan tanpa periode kursus!</p>
   </div>
</div>

<script src="<?= base_url() ?>assets/plugins/OwlCarousel/owl.carousel.min.js"></script>
<script src="<?= base_url() ?>script/beranda/index.js"></script>