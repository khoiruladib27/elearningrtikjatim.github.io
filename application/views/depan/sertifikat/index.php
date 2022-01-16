<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="container mt-4">
   <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fas fa-home"></i> Beranda</a></li>
         <li class="breadcrumb-item active" aria-current="page">Cek Sertifikat</li>
      </ol>
   </nav>
</div>
<section>
   <div class="col-md-12 py-3">
      <div class="container">
         <div class="row">
            <?php if ($this->session->flashdata('msg')) : ?>
               <div class="col-md-12">
                  <div class="alert alert-danger" role="alert">
                     <?= $this->session->flashdata('msg'); ?>
                  </div>
               </div>
            <?php endif; ?>
            <div class="col-md-6 mb-3">
               <form action="<?= base_url('cert') ?>" method="POST">
                  <div class="form-group">
                     <h5 class="mb-3"><i class="fas fa-award"></i> Id Sertifikat</h5>
                     <?php
                     $value = '';
                     if (isset($_POST['srt_id'])) {
                        $value = $_POST['srt_id'];
                     } else if (isset($_GET['srt_id'])) {
                        $value =  $_GET['srt_id'];
                     }
                     ?>
                     <input type="text" id="srt_id" value="<?= $value ?>" name="srt_id" class="form-control" placeholder="Masukkan Id Sertifikat">
                  </div>
                  <div class="g-recaptcha" data-sitekey="<?= $this->config->item('google_key') ?>"></div>
                  <button type="submit" class="btn btn-danger px-5 mt-3"><i class="fas fa-search"></i> Cari</button>
               </form>
            </div>
            <?php if (isset($_POST['srt_id']) && !$this->session->flashdata('msg') && isset($_POST['g-recaptcha-response'])) : ?>
               <?php if (isset($sertifikat)) : ?>
                  <div class="col-md-6">
                     <img src="<?= STORAGEPATH ?>/sertifikat/<?= $sertifikat['srt_img'] ?>" alt="" style="max-width: 100%;">
                  </div>
               <?php else : ?>
                  <div class="col-md-6 text-center py-5" style="background-color: #e9ecef;">
                     <h3>Tidak ada data</h3>
                  </div>
               <?php endif; ?>
            <?php endif; ?>
         </div>
      </div>
   </div>
</section>
<script>
   grecaptcha.reset();
</script>