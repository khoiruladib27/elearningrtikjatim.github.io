<style>
   iframe {
      max-width: 100% !important;
   }
</style>
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12 text-center my-4">
               <div class="card">
                  <div class="card-header text-left">
                     <h5 class="m-0"><?= $materi['mtr_nama'] ?></h5>
                  </div>
                  <div class="card-body">
                     <?= $materi['mtr_isi'] ?>
                  </div>
                  <div class="card-footer text-md-right">
                     <button type="button" onclick="selesai('<?= $materi['mtr_id'] ?>')" class="btn btn-info"> Selesai & Lanjut <i class="fas fa-arrow-circle-right"></i></button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   function selesai(mtr_id) {
      var link = BASE_URL + 'member/belajar/selesai';
      $.ajax({
         url: link,
         type: "POST",
         data: {
            mtr_id: mtr_id,
         },
         dataType: 'json',
         success: function(data) {
            if (data.status == 1) {
               window.location.href = BASE_URL + "belajar/<?= $kejuruan['kjr_slug'] ?>/" + data.link;
            } else {
               Swal.fire({
                  icon: 'error',
                  title: data.pesan,
                  showConfirmButton: false,
                  timer: 1500
               });
            }
         }
      });
   }
</script>