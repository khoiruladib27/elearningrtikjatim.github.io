<nav class="main-header navbar navbar-expand navbar-<?= getApp('app_topbar_theme')['conf_value'] ?> navbar-<?= getApp('app_topbar_color')['conf_value'] ?>">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
      <li class="nav-item">
         <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
   </ul>
   <?php
   $usr_foto = $_SESSION['system_users']['usr_foto'];
   if (!$usr_foto) {
      $usr_foto = STORAGEPATH . '/no-image.jpg';
   } else {
      $usr_foto = STORAGEPATH . '/user/' . $usr_foto;
   }
   ?>
   <!-- Right navbar links -->
   <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
         <a class="nav-link" data-toggle="dropdown" href="#">
            <img src="<?= $usr_foto; ?>" class="img-circle" style="max-width: 1.8rem; max-height: 1.8rem;">
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">
               <img src="<?= $usr_foto; ?>" class="img-circle" style="max-width: 5rem; max-height: 5rem; margin-bottom: 1rem;">
               <h5><?= $_SESSION['system_users']['usr_name']; ?></h5>
               <h6><?= $_SESSION['system_group']['grp_name']; ?></h6>
            </span>
            <div class="dropdown-divider"></div>
            <div class="dropdown-item dropdown-footer">
               <button id="btn-profil" data-id="<?= $_SESSION['system_users']['usr_id']; ?>" class="btn btn-block btn-sm btn-dark"><i class="fas fa-user mr-2"></i> Profil</button>
            </div>
            <div class="dropdown-item dropdown-footer">
               <a href="<?= base_url('admin/auth/logout') ?>" class="btn btn-sm btn-block btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
         </div>
      </li>
   </ul>
</nav>
<script>
   $(document).off("click", "#btn-profil")
      .on("click", "#btn-profil", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/profil/getByID",
            dataType: "JSON",
            data: {
               profil_usr_id: $(this).data('id')
            },
            success: function(data) {
               if (data.status == 1) {
                  data = data.data;
                  $('input[name="profil_usr_id"]').val(data.usr_id);
                  $('input[name="profil_usr_name"]').val(data.usr_name);
                  $('input[name="profil_usr_username"]').val(data.usr_username);
                  $('input[name="profil_usr_email"]').val(data.usr_email);
                  if (data.usr_foto) {
                     var usr_foto = "<?= STORAGEPATH ?>/user/" + data.usr_foto;
                  } else {
                     var usr_foto = "<?= STORAGEPATH ?>/no-image.jpg";
                  }
                  $('#blah-profil_usr_foto').attr("src", usr_foto);
                  $('#profilModal').modal('show');
                  $('#profilModalTitle').html('<i class="fas fa-edit"></i> Edit Data Profil');
                  $(document).off("click", "#profilModalSave").on("click", "#profilModalSave", function(e) {
                     simpanProfil();
                  });
               } else {
                  toastr.error(data.pesan);
               }
            }
         });
      });

   function simpanProfil() {
      var form_data = new FormData($('#form-profil')[0]);
      var link = BASE_URL + 'admin/profil/store';
      $.ajax({
         url: link,
         type: "POST",
         data: form_data,
         dataType: 'json',
         contentType: false,
         processData: false,
         success: function(data) {
            if (data.status == 1) {
               $('#profilModal').modal('hide');
               toastr.success(data.pesan);
               setTimeout(function() {
                  location.reload();
               }, 2000);
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
               $('#profilModal').modal('hide');
               toastr.error(data.pesan);
            }
         }
      });
   }

   $(document).off("hidden.bs.modal", "#profilModal")
      .on("hidden.bs.modal", "#profilModal", function(e) {
         $('.text-invalid').html('');
         $('#profilModalTitle').html('');
         $('input[name="usr_id"]').val('');
         $('input[name="usr_name"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="usr_username"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="usr_email"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="usr_password"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="usr_cpassword"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="usr_foto"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('#usr_group').val('').removeClass("is-valid").removeClass("is-invalid");
         $('#blah-usr_foto').attr("src", '<?= STORAGEPATH ?>' + '/no-image.jpg');
         $("#profilModalSave").prop("onclick", null).off("click");
      });
</script>
<!-- Modal -->
<div class="modal fade" id="profilModal" tabindex="-1" role="dialog" aria-labelledby="profilModalTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" id="profilModalDialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="profilModalTitle"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="profilModalBody">
            <form id="form-profil">
               <div class="row">
                  <div class="col-md-6">
                     <input type="hidden" name="profil_usr_id" value="">
                     <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" id="profil_usr_name" value="" name="profil_usr_name" class="form-control" placeholder="Nama Lengkap">
                        <span class="text-invalid" id="profil_usr_name_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="profil_usr_username" name="profil_usr_username" value="" class="form-control" placeholder="Username">
                        <span class="text-invalid" id="profil_usr_username_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Email</label>
                        <input type="text" id="profil_usr_email" name="profil_usr_email" value="" class="form-control" placeholder="Username">
                        <span class="text-invalid" id="profil_usr_email_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="profil_usr_password" name="profil_usr_password" class="form-control" placeholder="Password">
                        <span class="text-invalid" id="profil_usr_password_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Ulangi Password</label>
                        <input type="password" id="profil_usr_cpassword" name="profil_usr_cpassword" class="form-control" placeholder="Ulangi Password">
                        <span class="text-invalid" id="profil_usr_cpassword_error"></span>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Foto</label>
                        <div class="input-group">
                           <input onchange="readURL(this, 'profil_usr_foto');" name="profil_usr_foto" type="file" accept="image/gif, image/jpeg, image/png" id="profil_usr_foto">
                        </div>
                        <div class="invalid-feedback" id="profil_usr_foto_error"></div>
                        <div id="profil_usr_foto-display">
                           <img id="blah-profil_usr_foto" src="<?= STORAGEPATH ?>/no-image.jpg" alt="Mengambil Foto ..." class="mt-2" style="height: 200px;">
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="float: right;"><i class="fas fa-times-circle"></i> Close</button>
            <button type="button" class="btn btn-success" id="profilModalSave"><i class="fas fa-save"></i> Simpan</button>
         </div>
      </div>
   </div>
</div>