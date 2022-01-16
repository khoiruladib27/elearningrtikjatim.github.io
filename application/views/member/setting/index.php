<?php
function to_tanggal($tanggal)
{
   $tgl = explode('-', $tanggal);
   $yyyy = $tgl[0];
   $mm = $tgl[1];
   $dd = $tgl[2];
   return $dd . "-" . $mm . "-" . $yyyy;
} ?>
<style>
   p {
      margin-top: 0;
      margin-bottom: 0 !important;
   }
</style>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<div class="col-md-9 mt-4">
   <div class="card">
      <div class="card-header">
         <i class="fas fa-cogs"></i> Setting
      </div>
      <div class="card-body">
         <div id="alert"></div>
         <form id="form-setting">
            <div class="row">
               <div class="col-md-6">
                  <input type="hidden" name="mbr_id" value="<?= $_SESSION['system_members']['mbr_id']; ?>">
                  <div class="form-group">
                     <label>Nama Lengkap</label>
                     <input type="text" id="mbr_name" value="<?= $_SESSION['system_members']['mbr_name']; ?>" name="mbr_name" class="form-control" placeholder="Nama Lengkap">
                     <span class="text-invalid" id="mbr_name_error"></span>
                  </div>
                  <div class="form-group">
                     <label for="ttl">Tempat, Tanggal Lahir</label>
                     <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tempat Lahir" value="<?= $_SESSION['system_members']['mbr_tempat_lahir'] ?>" name="mbr_tempat_lahir" id="mbr_tempat_lahir">
                        <div class="input-group-prepend px-2"></div>
                        <div class="input-group-prepend">
                           <input type="text" class="form-control" placeholder="dd-mm-yyyy" value="<?= to_tanggal($_SESSION['system_members']['mbr_tanggal_lahir']) ?>" name="mbr_tanggal_lahir" id="mbr_tanggal_lahir">
                        </div>
                     </div>
                     <span class="text-danger" id="mbr_tempat_lahir_error"></span>
                     <span class="text-danger" id="mbr_tanggal_lahir_error"></span>
                  </div>
                  <div class="form-group">
                     <label for="mbr_kota_asal">Kota Asal</label>
                     <input type="text" class="form-control" id="mbr_kota_asal" value="<?= $_SESSION['system_members']['mbr_kota_asal'] ?>" name="mbr_kota_asal" placeholder="Kota Asal">
                     <div class="text-danger" id="mbr_kota_asal_error"></div>
                  </div>
                  <div class="form-group">
                     <label>Username</label>
                     <input type="text" id="mbr_username" name="mbr_username" value="<?= $_SESSION['system_members']['mbr_username']; ?>" class="form-control" placeholder="Username">
                     <span class="text-invalid" id="mbr_username_error"></span>
                  </div>
                  <div class="form-group">
                     <label>Email</label>
                     <input type="text" id="mbr_email" name="mbr_email" value="<?= $_SESSION['system_members']['mbr_email']; ?>" class="form-control" placeholder="Username">
                     <span class="text-invalid" id="mbr_email_error"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Password</label>
                     <input type="password" id="mbr_password" name="mbr_password" class="form-control" placeholder="Password">
                     <span class="text-invalid" id="mbr_password_error"></span>
                  </div>
                  <div class="form-group">
                     <label>Ulangi Password</label>
                     <input type="password" id="mbr_cpassword" name="mbr_cpassword" class="form-control" placeholder="Ulangi Password">
                     <span class="text-invalid" id="mbr_cpassword_error"></span>
                  </div>
                  <?php
                  $foto = $_SESSION['system_members']['mbr_foto'];
                  if (!isset($foto)) {
                     $foto = STORAGEPATH . '/no-image.jpg';
                  } else {
                     $foto = STORAGEPATH . '/member/' . $foto;
                  }
                  ?>
                  <div class="form-group">
                     <label>Foto</label>
                     <div class="input-group">
                        <input onchange="readURL(this, 'mbr_foto');" name="mbr_foto" type="file" accept="image/gif, image/jpeg, image/png" id="mbr_foto">
                     </div>
                     <div class="invalid-feedback" id="mbr_foto_error"></div>
                     <div id="mbr_foto-display">
                        <img id="blah-mbr_foto" src="<?= $foto ?>" alt="Mengambil Foto ..." class="mt-2" style="height: 200px;">
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                  <button class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $('#mbr_tanggal_lahir').datepicker({
      uiLibrary: 'bootstrap4',
      format: 'dd-mm-yyyy'
   });
   $("#form-setting").submit(function(event) {
      simpan();
      event.preventDefault();
   });

   function simpan() {
      $('.text-danger').html('');
      $('#alert').html('');
      var form_data = new FormData($('#form-setting')[0]);
      var link = BASE_URL + 'member/setting/store';
      $.ajax({
         url: link,
         type: "POST",
         data: form_data,
         dataType: 'json',
         contentType: false,
         processData: false,
         success: function(data) {
            if (data.status == 1) {
               $('#alert').html(`
                  <div class="alert alert-success" role="alert">
                     ` + data.pesan + `
                  </div>`);
               setTimeout(function() {
                  window.location.href = BASE_URL + "member/setting";
               }, 1000);
            } else if (data.status == 3) {
               $.each(data.pesan, function(i, item) {
                  $('#' + i + '_error').html(item);
                  $('#' + i + '_error').show();
                  if (item) {
                     $('#' + i).removeClass("is-valid").addClass("is-invalid");
                  } else {
                     $('#' + i).removeClass("is-invalid").addClass("is-valid");
                  }
               });
            } else {
               $('#alert').html(`
                  <div class="alert alert-danger" role="alert">
                     ` + data.pesan + `
                  </div>`);
            }
         }
      });
   }
</script>