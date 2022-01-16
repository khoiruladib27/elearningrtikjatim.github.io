<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12 text-center my-5">
               <img class="mb-3" src="<?= base_url('/assets/images/learning.jpg') ?>" style="width: 50%; min-width: 13rem;" alt="No Result">
               <h4>Selamat belajar !!</h4>
               <?php
               if (isset($kejuruan['materi'][0]['submateri'][0]['mtr_slug'])) :
                  $url = $kejuruan['materi'][0]['submateri'][0]['mtr_slug'];
               ?>
                  <a href="<?= base_url('belajar/' . $kejuruan['kjr_slug'] . '/' . $url) ?>" class="btn btn-success px-5">Mulai <i class="fas fa-arrow-circle-right"></i></a>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </section>
</div>