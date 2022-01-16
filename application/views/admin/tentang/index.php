<style>
   #tb_data img {
      max-height: 5rem;
   }
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0"><?= $title ?></h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>" class="text-success">Dashboard</a></li>
                  <li class="breadcrumb-item active"><?= $title ?></li>
               </ol>
            </div>
         </div>
      </div>
   </div>

   <script src="<?= base_url('assets/plugins/') ?>tinymce/tinymce.min.js"></script>
   <script>
      tinymce.init({
         selector: '#tnt_isi',
         height: "480",
         plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak noneditable",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
         ],
         toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
         toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
         image_advtab: true,
         external_filemanager_path: BASE_URL + "assets/filemanager/",
         filemanager_title: "Responsive Filemanager",
         external_plugins: {
            "filemanager": BASE_URL + "assets/filemanager/plugin.min.js"
         },
         filemanager_access_key: "<?= $this->session->fm_key ?>",
         relative_urls: false,
         remove_script_host: false
      });
   </script>
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <form id="form-tentang" action="#">
                        <div class="row">
                           <div class="col-md-12">
                              <input type="hidden" name="tnt_id" value="<?= $data['tnt_id'] ?>">
                              <div class="form-group">
                                 <label>Gambar</label>
                                 <div class="input-group">
                                    <input onchange="readURL(this, 'tnt_image');" name="tnt_image" type="file" accept="image/gif, image/jpeg, image/png" id="tnt_image">
                                 </div>
                                 <div class="invalid-feedback" id="tnt_image_error"></div>
                                 <div id="tnt_image-display">
                                    <?php
                                    $tnt_image = 'no-image.jpg';
                                    if (!empty($data['tnt_image'])) {
                                       $tnt_image = 'tentang/' . $data['tnt_image'];
                                    }
                                    ?>
                                    <img id="blah-tnt_image" src="<?= STORAGEPATH . '/' . $tnt_image ?>" alt="Mengambil Foto ..." class="mt-2" style="max-height: 200px; max-width: 100%;">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label>Isi</label>
                                 <textarea name="tnt_isi" id="tnt_isi"><?= $data['tnt_isi'] ?></textarea>
                                 <span class="text-invalid" id="tnt_isi_error"></span>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <button type="submit" class="btn btn-success" id="dataModalSave"><i class="fas fa-save"></i> Simpan</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<!-- Page specific script -->
<script>
   $(document).off("submit", "#form-tentang")
      .on("submit", "#form-tentang", function(e) {
         event.preventDefault();
         simpan();
      });

   function simpan() {
      var form_data = new FormData($('#form-tentang')[0]);
      var link = BASE_URL + 'admin/tentang/store';
      $.ajax({
         url: link,
         type: "POST",
         data: form_data,
         dataType: 'json',
         contentType: false,
         processData: false,
         success: function(data) {
            if (data.status == 1) {
               toastr.success(data.pesan);
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
               toastr.error(data.pesan);
            }
         }
      });
   }

   $(document).off("hidden.bs.modal", "#dataModal")
      .on("hidden.bs.modal", "#dataModal", function(e) {
         $('.text-invalid').html('');
         $('#dataModalTitle').html('');
         $('input[name="sld_id"]').val('');
         $('input[name="sld_nama"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="sld_image"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('#blah-sld_image').attr("src", '<?= STORAGEPATH ?>' + '/no-image.jpg');
         $("#dataModalSave").prop("onclick", null).off("click");
      });
</script>