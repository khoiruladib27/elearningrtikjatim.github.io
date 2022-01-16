<div class="col-md-9 mt-4">
   <div class="card">
      <div class="card-header">
         <i class="fas fa-book"></i> Kelas
      </div>
      <div class="card-body">
         <div id="alert" class="col-md-12"></div>
         <?php if ($kelas) : ?>
            <?php foreach ($kelas as $k) : ?>
               <div class="row">
                  <div class="col-md-3">
                     <?php
                     $foto = $k['kjr_image'];
                     if (!isset($foto)) {
                        $foto = STORAGEPATH . '/no-image.jpg';
                     } else {
                        $foto = STORAGEPATH . '/kejuruan/' . $foto;
                     }
                     ?>
                     <img src="<?= $foto ?>" width="100%" alt="">
                  </div>
                  <div class="col-md-9">
                     <h5 class="mb-2"><?= $k['kjr_nama']; ?></h5>
                     <?php if ($k['kls_lunas'] != null) : ?>
                        <?php if ($k['kls_locked'] == 0) : ?>
                           <?php
                           $persen = 0;
                           $jml_modul = $k['jml_modul'];
                           if ($jml_modul != 0) {
                              $persen = $k['wes_modul'] / $jml_modul * 100;
                           }
                           ?>
                           <div class="progress">
                              <div class="progress-bar progress-bar-striped bg-<?= ($persen == 100) ? 'success' : 'warning' ?>" role="progressbar" style="width: <?= $persen; ?>%;" aria-valuenow="<?= $persen; ?>" aria-valuemin="0" aria-valuemax="100"><?= number_format($persen, 0, ',', '.'); ?>%</div>
                           </div>
                           <div class="mt-1 mb-2" style="color: #696666;">
                              <strong> <?= $k['wes_modul'] ?> </strong> dari <strong> <?= $k['jml_modul'] ?> </strong> modul telah selesai
                           </div>
                           <a href="<?= base_url('belajar/' . $k['kjr_slug']) ?>" class="btn btn-success"><i class="fas fa-book-reader"></i> Lanjut Belajar</a>
                           <hr class="my-2">
                           <div class="mt-2 mb-3" style="color: #696666;">
                              <p class="mt-2 mb-1">Lisensi Kepada :</p>
                              <strong class="mt-0"><?= $k['mbr_name'] ?></strong> <span>| <?= $k['mbr_email'] ?></span>
                              <p class="mt-1 tgl-license"><i>(<?= $k['kls_lunas'] ?>)</i></p>
                           </div>
                        <?php else : ?>
                           <button class="btn btn-danger"><i class="fas fa-lock"></i> Kelas Non-aktif</button>
                        <?php endif; ?>
                     <?php else : ?>
                        <div class="mt-1 mb-2" style="color: #696666;">
                           <strong>Rp. <?= number_format($k['kjr_harga'], '2', ',', '.') ?> </strong>
                        </div>
                        <?php if ($k['kls_trf_id']) : ?>
                           <a href="<?= base_url('member/kelas/bayar/' . $k['kjr_slug']) ?>" class="btn btn-info"><i class="fas fa-money-bill-wave"></i> Dalam Proses Verifikasi</a>
                        <?php else : ?>
                           <a href="<?= base_url('member/kelas/bayar/' . $k['kjr_slug']) ?>" class="btn btn-success"><i class="fas fa-money-bill-wave"></i> Bayar</a>
                           <button type="button" onclick="hapus('<?= $k['kls_id'] ?>')" class="btn btn-danger"><i class="fas fa-trash"></i> Batal</button>
                        <?php endif; ?>
                     <?php endif; ?>
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
                  <a href="<?= base_url('kejuruan') ?>" class="btn btn-danger"><i class="fas fa-search"></i> Cari Kelas</a>
               </div>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>
<script>
   function hapus(kls_id) {
      $('#alert').html('');
      var link = BASE_URL + 'member/kelas/destroy';
      $.ajax({
         url: link,
         type: "POST",
         data: {
            kls_id: kls_id,
         },
         dataType: 'json',
         success: function(data) {
            if (data.status == 1) {
               $('#alert').html(`
                     <div class="alert alert-success" role="alert">
                        ` + data.pesan + `
                     </div>
                  `);
               setTimeout(function() {
                  window.location.href = BASE_URL + "member/kelas";
               }, 1000);
            } else {
               $('#alert').html(`
                     <div class="alert alert-danger" role="alert">
                        ` + data.pesan + `
                     </div>
                  `);
            }
         }
      });
   }
</script>