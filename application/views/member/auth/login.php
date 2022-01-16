<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/member/custome.css">
<div class="container-fluid pb-5" style="background-color: #109c30;">
   <div class="row justify-content-center">
      <div class="col-md-4 mt-4">
         <div class="card">
            <div class="card-header text-center py-4">
               <i class="fas fa-users"></i> Login dengan akun anda !
            </div>
            <div class="card-body">
               <div id="alert"></div>
               <form id="form-login">
                  <div class="form-group mb-3">
                     <label for="mbr_username">Username</label>
                     <div class="input-group">
                        <input type="text" class="form-control" placeholder="Username" id="mbr_username" name="mbr_username">
                        <div class="input-group-prepend">
                           <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                     </div>
                     <div class="text-danger" id="mbr_username_error"></div>
                  </div>
                  <div class="form-group mb-3">
                     <label for="mbr_password">Password</label>
                     <div class="input-group">
                        <input type="password" class="form-control" placeholder="Password" name="mbr_password" id="mbr_password">
                        <div class="input-group-prepend" id="eye-password">
                           <span class="input-group-text"><i id="v-password" class="fas fa-eye-slash"></i></span>
                        </div>
                     </div>
                     <div class="text-danger" id="mbr_password_error"></div>
                  </div>
                  <div class="g-recaptcha" data-sitekey="<?= $this->config->item('google_key') ?>"></div>
                  <div class="text-danger" id="gcaptcha_error"></div>
                  <button class="btn btn-warning btn-block mt-4"><i class="fas fa-sign-in-alt"></i> Masuk</button>
                  <a href="<?= base_url('member/auth/daftar'); ?>" class="btn btn-info btn-block"><i class="fas fa-user-edit"></i> Daftar</a>
               </form>
            </div>
            <div class="card-footer"></div>
         </div>
      </div>
   </div>
</div>

<script>
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
   $("#form-login").submit(function(event) {
      login();
      event.preventDefault();
   });

   function login() {
      $('.text-danger').html('');
      $('#alert').html('');
      var form_data = new FormData($('#form-login')[0]);
      var link = BASE_URL + 'member/auth/do_login';
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