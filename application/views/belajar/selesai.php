<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12 text-center my-5">
               <img class="mb-3" src="<?= base_url('/assets/images/selesai.jpg') ?>" style="width: 50%; min-width: 13rem;" alt="No Result">
               <h4>Selesai belajar !!</h4>
               <?php if($sertifikat) : ?>
                  <a href="<?= STORAGEPATH."/sertifikat/". $sertifikat['srt_img'] ?>" target="_blank" class="btn btn-danger px-3"><i class="fa fa-award"></i> Lihat Sertifikat</a>
               <?php else : ?>
                  <button id="srt_cetak" onclick="cetak('<?= $kejuruan['kjr_id']; ?>')" class="btn btn-danger px-3"><i class="fa fa-award"></i> Cetak Sertifikat</button>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   function cetak(kjr_id) {
      var link = BASE_URL + 'member/sertifikat/store';
      $.ajax({
         url: link,
         type: "POST",
         data: {
            kjr_id: kjr_id,
         },
         dataType: 'json',
         beforeSend: function(){
            $('#srt_cetak').html(`<i class="fas fa-spinner fa-spin"></i> Cetak Sertifikat`);
         },
         success: function(data) {
            if (data.status == 1) {
               window.open(BASE_URL + "storage/sertifikat/" + data.link, '_blank');
               location.reload();
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