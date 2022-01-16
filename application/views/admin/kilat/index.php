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

   <!-- DataTables -->
   <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
   <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <button class="btn btn-success tambah"><i class="fas fa-plus"></i> Tambah</button>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="tb_data" class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th style="width:5%;">No</th>
                              <th class="text-center" style="width: 25%;">Aksi</th>
                              <th class="text-center" style="width: 25%;">Image</th>
                              <th class="text-center" style="width: 50%;">Nama</th>
                              <th class="text-center" style="width: 50%;">Harga</th>
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<!-- DataTables  & Plugins -->
<script src="<?= base_url('assets/admin/') ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url('assets/admin/') ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Page specific script -->
<script>
   var tabel = null;
   $(document).ready(function() {
      tabel = $('#tb_data').DataTable({
         "processing": true,
         "responsive": true,
         "serverSide": true,
         language: table_language(),
         "autoWidth": false,
         "ordering": true, // Set true agar bisa di sorting
         "order": [
            [3, 'asc'],
            [4, 'asc'],
         ],
         "ajax": {
            "url": BASE_URL + "admin/kilat/view_data", // URL file untuk proses select datanya
            "type": "POST"
         },
         "deferRender": true,
         "aLengthMenu": [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
         ],
         "columns": [{
               data: 'kjr_id',
               orderable: false,
               render: function(data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               },
               className: "text-center",
            },
            {
               "data": "kjr_id",
               "render": function(data, type, row, meta) {
                  var btn = ``;
                  if (row.kjr_locked == 1) {
                     btn += `<button type="button" class="mr-1 btn btn-secondary btn-sm lock"  data-id="` + row.kjr_id + `" data-lock="` + row.kjr_locked + `" title="Aktifkan"><i class="fas fa-lock"></i></button>`
                  } else {
                     btn += `<button type="button" class="mr-1 btn btn-success btn-sm lock"  data-id="` + row.kjr_id + `" data-lock="` + row.kjr_locked + `" title="Kunci"><i class="fas fa-lock-open"></i></button>`
                  }
                  btn += `<button type="button" class="mr-1 btn btn-warning btn-sm edit" data-id="` + row.kjr_id + `"><i class="fas fa-edit" title="Edit"></i></button>`;
                  btn += `<button type="button" class="mr-1 btn btn-danger btn-sm hapus" data-id="` + row.kjr_id + `"><i class="fas fa-trash" title="Hapus"></i></button>`;
                  return btn;
               },
               className: "text-center",
               orderable: false
            },
            {
               "data": "kjr_id",
               "render": function(data, type, row, meta) {
                  var img = '/no-image.jpg';
                  if (row.kjr_image) {
                     img = `/kejuruan/` + row.kjr_image;
                  }
                  var btn = `<img src="<?= STORAGEPATH ?>` + img + `" style="max-width: 200px; max-height: 125px;">`;
                  return btn;
               },
               className: "text-center",
               orderable: false
            },
            {
               "data": "kjr_nama"
            },
            {
               "data": "kjr_harga"
            },
         ],
      });
   });
   $(document).off("click", "#tb_data button.lock")
      .on("click", "#tb_data button.lock", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/kilat/lock",
            dataType: "JSON",
            data: {
               kjr_id: $(this).data('id'),
               kjr_locked: $(this).data('lock')
            },
            success: function(data) {
               if (data.status == 1) {
                  tabel.ajax.reload(null, true);
               } else {
                  toastr.error(data.pesan);
               }
            }
         });
      });

   function getPemateri(pemateri = null) {
      $.ajax({
         type: "get",
         url: "<?php echo base_url('admin/kilat/getPemateri') ?>",
         success: function(data) {
            data = JSON.parse(data);
            data = data.data;
            $('#kjr_pemateri').html('<option value="">== Pilih Pemateri ==</option>');
            $.each(data, function(i, val) {
               var t = `<option value="` + val.usr_id + `">` + val.usr_name + `</option>`;
               $('#kjr_pemateri').append(t);
            });
            $('#kjr_pemateri').val(pemateri);
         }
      });
   }
   $(document).off("click", "#tb_data button.edit")
      .on("click", "#tb_data button.edit", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/kilat/getByID",
            dataType: "JSON",
            data: {
               kjr_id: $(this).data('id')
            },
            success: function(data) {
               if (data.status == 1) {
                  data = data.data;
                  $('input[name="kjr_id"]').val(data.kjr_id);
                  $('input[name="kjr_nama"]').val(data.kjr_nama);
                  tinyMCE.activeEditor.setContent(data.kjr_deskripsi);
                  $('input[name="kjr_harga"]').val(data.kjr_harga);
                  getPemateri(data.kjr_pemateri);
                  if (data.kjr_image) {
                     var kjr_image = "<?= STORAGEPATH ?>/kejuruan/" + data.kjr_image;
                  } else {
                     var kjr_image = "<?= STORAGEPATH ?>/no-image.jpg";
                  }
                  $('#blah-kjr_image').attr("src", kjr_image);
                  $('#dataModal').modal('show');
                  $('#dataModalTitle').html('<i class="fas fa-edit"></i> Edit Data Kursus Kilat');
                  $(document).off("click", "#dataModalSave").on("click", "#dataModalSave", function(e) {
                     simpan();
                  });
               } else {
                  toastr.error(data.pesan);
               }
            }
         });
      });
   $(document).off("click", "button.tambah")
      .on("click", "button.tambah", function(e) {
         getPemateri();
         $('#dataModal').modal('show');
         $('#dataModalTitle').html('<i class="fas fa-plus-circle"></i> Tambah Data Kursus Kilat');
         $(document).off("click", "#dataModalSave").on("click", "#dataModalSave", function(e) {
            simpan();
         });
      });
   $(document).off("click", "#tb_data button.hapus")
      .on("click", "#tb_data button.hapus", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/kilat/destroy",
            dataType: "JSON",
            data: {
               kjr_id: $(this).data('id')
            },
            success: function(data) {
               if (data.status == 1) {
                  tabel.ajax.reload(null, true);
               } else {
                  toastr.error(data.pesan);
               }
            }
         });
      });
   $(document).off("submit", "#form-kilat")
      .on("submit", "#form-kilat", function(e) {
         event.preventDefault();
         simpan();
      });

   function simpan() {
      tinyMCE.triggerSave();
      var form_data = new FormData($('#form-kilat')[0]);
      var link = BASE_URL + 'admin/kilat/store';
      $.ajax({
         url: link,
         type: "POST",
         data: form_data,
         dataType: 'json',
         contentType: false,
         processData: false,
         success: function(data) {
            if (data.status == 1) {
               $('#dataModal').modal('hide');
               toastr.success(data.pesan);
               tabel.ajax.reload(null, true);
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
               $('#dataModal').modal('hide');
               toastr.error(data.pesan);
            }
         }
      });
   }

   $(document).off("hidden.bs.modal", "#dataModal")
      .on("hidden.bs.modal", "#dataModal", function(e) {
         tinyMCE.activeEditor.setContent('');
         $('.text-invalid').html('');
         $('#dataModalTitle').html('');
         $('input[name="kjr_id"]').val('');
         $('input[name="kjr_nama"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="kjr_harga"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="kjr_pemateri"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="kjr_image"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('#blah-kjr_image').attr("src", '<?= STORAGEPATH ?>' + '/no-image.jpg');
         $("#dataModalSave").prop("onclick", null).off("click");
      });
</script>
<!-- Modal -->
<div class="modal fade" id="dataModal" role="dialog" aria-labelledby="dataModalTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" id="dataModalDialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="dataModalTitle"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="dataModalBody">
            <form id="form-kilat" action="#">
               <div class="row">
                  <div class="col-md-12">
                     <input type="hidden" name="kjr_id" value="">
                     <div class="form-group">
                        <label>Nama Kursus Kilat</label>
                        <input type="text" id="kjr_nama" value="" name="kjr_nama" class="form-control" placeholder="Nama Kursus Kilat">
                        <span class="text-invalid" id="kjr_nama_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Harga</label>
                        <input type="number" id="kjr_harga" value="" name="kjr_harga" class="form-control" placeholder="Harga">
                        <span class="text-invalid" id="kjr_harga_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="kjr_deskripsi" id="kjr_deskripsi" class="form-control" placeholder="Deskripsi Kursus Kilat" rows="3"></textarea>
                        <span class="text-invalid" id="kjr_deskripsi_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Pemateri</label>
                        <select class="form-control select2" style="width: 100%" id="kjr_pemateri" data-placeholder="Pilih Pemateri" name="kjr_pemateri">
                        </select>
                        <span class="text-invalid" id="kjr_pemateri_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Image</label>
                        <div class="input-group">
                           <input onchange="readURL(this, 'kjr_image');" name="kjr_image" type="file" accept="image/gif, image/jpeg, image/png" id="kjr_image">
                        </div>
                        <div class="invalid-feedback" id="kjr_image_error"></div>
                        <div id="kjr_image-display">
                           <img id="blah-kjr_image" src="<?= STORAGEPATH ?>/no-image.jpg" alt="Mengambil Foto ..." class="mt-2" style="max-height: 200px; max-width: 100%;">
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="float: right;"><i class="fas fa-times-circle"></i> Close</button>
            <button type="button" class="btn btn-success" id="dataModalSave"><i class="fas fa-save"></i> Simpan</button>
         </div>
      </div>
   </div>
</div>

<script src="<?= base_url('assets/plugins/') ?>tinymce/tinymce.min.js"></script>
<script>
   tinymce.init({
      selector: '#kjr_deskripsi',
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