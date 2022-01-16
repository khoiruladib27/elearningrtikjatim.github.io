<div class="col-md-9 mt-4">
   <div class="card">
      <div class="card-header">
         <i class="fas fa-award"></i> Sertifikat
      </div>
      <div class="card-body">
         <?php if ($sertifikat) : ?>
            <?php foreach ($sertifikat as $st) : ?>
               <div class="row">
                  <div class="col-md-3">
                     <img src="<?= STORAGEPATH ?>/sertifikat/<?= $st['srt_img'] ?>" width="100%" alt="">
                  </div>
                  <div class="col-md-9">
                     <h5 class="mb-2"><?= $st['kjr_nama'] ?></h5>
                     <div class="mt-1 mb-2" style="color: #696666;">
                        <strong> <?= $st['jml_modul'] ?> </strong> modul telah selesai
                     </div>
                     <a download="<?= $st['srt_img']; ?>" href="<?= STORAGEPATH ?>/sertifikat/<?= $st['srt_img'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-award"></i> Download Sertifikat</a>
                     <hr class="my-2">
                     <div class="mt-2 mb-3" style="color: #696666;">
                        <p class="mt-2 mb-1">Diberikan Kepada :</p>
                        <strong class="mt-0"><?= $_SESSION['system_members']['mbr_name'] ?></strong> <span>| <?= $_SESSION['system_members']['mbr_email'] ?></span>
                        <p class="mt-1 tgl-license"><i>(<?= $st['srt_created_at'] ?>)</i></p>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <hr class="mt-0 mb-3">
                  </div>
               </div>
            <?php endforeach; ?>
         <?php else : ?>
            <div class="row">
               <div class="col-md-4 text-center">
                  <img src="<?= base_url('assets/images/kosong.jpg') ?>" width="75%" alt="">
               </div>
               <div class="col-md-8 text-md-left text-center">
                  <h5 class="mt-4 mb-3">Tidak ada data !!</h5>
               </div>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>