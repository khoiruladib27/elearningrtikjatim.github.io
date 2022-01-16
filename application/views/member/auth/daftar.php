<style>
   p {
      margin-top: 0;
      margin-bottom: 0 !important;
   }
</style>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/member/custome.css">
<div class="container-fluid pb-5" style="background-color: #109c30;">
   <div class="row justify-content-center">
      <div class="col-md-4 mt-4">
         <div class="card">
            <div class="card-header text-center py-4">
               <i class="fas fa-user-edit"></i> Masukkan data anda untuk mendaftar !
            </div>
            <div class="card-body">
               <div id="alert"> </div>
               <form id="form-daftar" method="post">
                  <div class="form-group">
                     <label for="mbr_name">Nama Lengkap</label>
                     <input type="text" class="form-control" id="mbr_name" name="mbr_name" placeholder="Nama Lengkap">
                     <div class="text-danger" id="mbr_name_error"></div>
                  </div>
                  <div class="form-group">
                     <label for="ttl">Tempat, Tanggal Lahir</label>
                     <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tempat Lahir" name="mbr_tempat_lahir" id="mbr_tempat_lahir">
                        <div class="input-group-prepend px-2"></div>
                        <div class="input-group-prepend">
                           <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="mbr_tanggal_lahir" id="mbr_tanggal_lahir">
                        </div>
                     </div>
                     <span class="text-danger" id="mbr_tempat_lahir_error"></span>
                     <span class="text-danger" id="mbr_tanggal_lahir_error"></span>
                  </div>
                  <div class="form-group">
                     <label for="mbr_kota_asal">Kota Asal</label>
                     <input type="text" class="form-control" id="mbr_kota_asal" name="mbr_kota_asal" placeholder="Kota Asal">
                     <div class="text-danger" id="mbr_kota_asal_error"></div>
                  </div>
                  <div class="form-group">
                     <label for="mbr_username">Username</label>
                     <input type="text" class="form-control" id="mbr_username" name="mbr_username" placeholder="Username">
                     <div class="text-danger" id="mbr_username_error"></div>
                  </div>
                  <div class="form-group">
                     <label for="mbr_email">Email</label>
                     <input type="text" class="form-control" id="mbr_email" name="mbr_email" placeholder="Email">
                     <div class="text-danger" id="mbr_email_error"></div>
                  </div>
                  <div class="form-group">
                     <label for="mbr_password">Password</label>
                     <div class="input-group">
                        <input type="password" class="form-control" placeholder="Password" id="mbr_password" name="mbr_password">
                        <div class="input-group-prepend" id="eye-password">
                           <span class="input-group-text"><i id="v-password" class="fas fa-eye-slash"></i></span>
                        </div>
                     </div>
                     <div class="text-danger" id="mbr_password_error"></div>
                  </div>
                  <div class="form-group">
                     <label for="mbr_cpassword">Ulangi Password</label>
                     <div class="input-group">
                        <input type="password" class="form-control" placeholder="Password" id="mbr_cpassword" name="mbr_cpassword">
                        <div class="input-group-prepend" id="eye-cpassword">
                           <span class="input-group-text"><i id="v-cpassword" class="fas fa-eye-slash"></i></span>
                        </div>
                     </div>
                     <div class="text-danger" id="mbr_cpassword_error"></div>
                  </div>
                  <div class="g-recaptcha" data-sitekey="<?= $this->config->item('google_key') ?>"></div>
                  <div class="text-danger" id="gcaptcha_error"></div>
                  <br />
                  <button class="btn btn-info btn-block"><i class="fas fa-user-edit"></i> Simpan</button>
               </form>
            </div>
            <div class="card-footer text-center">
               <a class="text-dark" href="<?= base_url('member') ?>"><i class="fas fa-arrow-left"></i> Login</a>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $('#mbr_tanggal_lahir').datepicker({
      uiLibrary: 'bootstrap4',
      format: 'dd-mm-yyyy'
   });
   $('#eye-password').on('click', function() {
      var a = $('#mbr_password').attr("type");
      if (a == 'password') {
         $('#mbr_password').attr("type", 'text');
         $('#v-password').attr("class", 'fas fa-eye');
      } else {
         $('#mbr_password').attr("type", 'password');
         $('#v-password').attr("class", 'fas fa-eye-slash');
      }
   });
   $('#eye-cpassword').on('click', function() {
      var a = $('#mbr_cpassword').attr("type");
      if (a == 'password') {
         $('#mbr_cpassword').attr("type", 'text');
         $('#v-cpassword').attr("class", 'fas fa-eye');
      } else {
         $('#mbr_cpassword').attr("type", 'password');
         $('#v-cpassword').attr("class", 'fas fa-eye-slash');
      }
   });
   $("#form-daftar").submit(function(event) {
      simpan();
      event.preventDefault();
   });

   function simpan() {
      $('.text-danger').html('');
      $('#alert').html('');
      var form_data = new FormData($('#form-daftar')[0]);
      var link = BASE_URL + 'member/auth/do_daftar';
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
                  window.location.href = BASE_URL + "member";
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
            grecaptcha.reset();
         }
      });
   }
</script>