<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h3 class="my-0 ml-2"><i class="fas fa-book-open"></i> <?= $title ?></h3>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>" class="text-success">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="<?= base_url('admin/materi') ?>" class="text-success">Materi</a></li>
                  <li class="breadcrumb-item active"><?= $kejuruan['kjr_nama'] ?></li>
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
                  <div class="card-header row">
                     <div class="col-md-8">
                        <h4><?= $materi['mtr_nama'] ?></h4>
                     </div>
                     <div class="col-md-4 text-right">
                        <button class="btn btn-success btn-sm mb-2 tambah"><i class="fas fa-plus"></i> Tambah</button>
                        <a class="btn btn-secondary btn-sm mb-2" href="<?= base_url('admin/materi/kategori/' . $kejuruan['kjr_slug']) ?>"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                     </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body pt-2">
                     <table id="tb_data" class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th style="width:5%;">No</th>
                              <th class="text-center" style="width: 20%;">Aksi</th>
                              <th class="text-center" style="width: 50%;">Nama</th>
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
         "order": true,
         "ajax": {
            "url": BASE_URL + "admin/materi/view_data_detail", // URL file untuk proses select datanya
            "type": "POST",
            data: {
               mtr_id: `<?= $materi['mtr_id'] ?>`,
            },
         },
         "deferRender": true,
         "aLengthMenu": [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
         ],
         "columns": [{
               data: 'mtr_id',
               orderable: false,
               render: function(data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               },
               className: "text-center",
            },
            {
               "data": "mtr_id",
               "render": function(data, type, row, meta) {
                  var up_disabled = '';
                  var down_disabled = '';
                  var btn = ``;
                  if (row.mtr_order <= row.min_mtr_order) {
                     up_disabled = 'disabled';
                  }
                  if (row.mtr_order >= row.max_mtr_order) {
                     down_disabled = 'disabled';
                  }
                  btn += `<button type="button" class="mr-1 btn btn-info btn-sm to-down" data-id="` + row.mtr_id + `" data-order="` + row.mtr_order + `" title="Pindah Ke Bawah" ` + down_disabled + `><i class="fas fa-arrow-down"></i></button>`;
                  btn += `<button type="button" class="mr-1 btn btn-info btn-sm to-up" data-id="` + row.mtr_id + `" data-order="` + row.mtr_order + `" title="Pindah Ke Atas" ` + up_disabled + `><i class="fas fa-arrow-up"></i></button>`;
                  if (row.mtr_locked == 1) {
                     btn += `<button type="button" class="mr-1 btn btn-secondary btn-sm lock"  data-id="` + row.mtr_id + `" data-lock="` + row.mtr_locked + `" title="Aktifkan"><i class="fas fa-lock"></i></button>`
                  } else {
                     btn += `<button type="button" class="mr-1 btn btn-success btn-sm lock"  data-id="` + row.mtr_id + `" data-lock="` + row.mtr_locked + `" title="Kunci"><i class="fas fa-lock-open"></i></button>`
                  }
                  btn += `<button type="button" class="mr-1 btn btn-warning btn-sm edit" data-id="` + row.mtr_id + `"><i class="fas fa-edit" title="Edit"></i></button>`;
                  btn += `<button type="button" class="mr-1 btn btn-danger btn-sm hapus" data-id="` + row.mtr_id + `"><i class="fas fa-trash" title="Hapus"></i></button>`;
                  return btn;
               },
               className: "text-center",
               orderable: false
            },
            {
               "data": "mtr_nama",
               orderable: false
            },
         ],
      });
   });
   $(document).off("click", "#tb_data button.lock")
      .on("click", "#tb_data button.lock", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/materi/lock",
            dataType: "JSON",
            data: {
               mtr_id: $(this).data('id'),
               mtr_locked: $(this).data('lock')
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

   $(document).off("click", "#tb_data button.to-up")
      .on("click", "#tb_data button.to-up", function(e) {
         order($(this).data('id'), $(this).data('order'), 'up');
      });
   $(document).off("click", "#tb_data button.to-down")
      .on("click", "#tb_data button.to-down", function(e) {
         order($(this).data('id'), $(this).data('order'), 'down');
      });

   function order(mtr_id = '', mtr_order = '', mtr_arrow = '') {
      $.ajax({
         type: "POST",
         url: BASE_URL + "admin/materi/order",
         dataType: "JSON",
         data: {
            mtr_id: mtr_id,
            mtr_index: '<?= $materi['mtr_id'] ?>',
            mtr_order: mtr_order,
            mtr_arrow: mtr_arrow
         },
         success: function(data) {
            if (data.status == 1) {
               tabel.ajax.reload(null, true);
            } else {
               toastr.error(data.pesan);
            }
         }
      });
   }
   $(document).off("click", "#tb_data button.edit")
      .on("click", "#tb_data button.edit", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/materi/getByID",
            dataType: "JSON",
            data: {
               mtr_id: $(this).data('id')
            },
            success: function(data) {
               if (data.status == 1) {
                  data = data.data;
                  $('input[name="mtr_id"]').val(data.mtr_id);
                  $('input[name="mtr_nama"]').val(data.mtr_nama);
                  tinyMCE.activeEditor.setContent(data.mtr_isi);
                  $('#dataModal').modal('show');
                  $('#dataModalTitle').html('<i class="fas fa-edit"></i> Edit Detail Materi');
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
         $('#dataModal').modal('show');
         $('#dataModalTitle').html('<i class="fas fa-plus-circle"></i> Tambah Detail Materi');
         $(document).off("click", "#dataModalSave").on("click", "#dataModalSave", function(e) {
            simpan();
         });
      });
   $(document).off("click", "#tb_data button.hapus")
      .on("click", "#tb_data button.hapus", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/materi/destroy",
            dataType: "JSON",
            data: {
               mtr_id: $(this).data('id')
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
   $(document).off("submit", "#form-data")
      .on("submit", "#form-data", function(e) {
         event.preventDefault();
         simpan();
      });

   function simpan() {
      tinyMCE.triggerSave();
      var form_data = new FormData($('#form-data')[0]);
      var link = BASE_URL + 'admin/materi/store';
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
         $('input[name="mtr_id"]').val('');
         $('input[name="mtr_nama"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('#mtr_isi').val('').removeClass("is-valid").removeClass("is-invalid");
         $("#dataModalSave").prop("onclick", null).off("click");
      });
</script>
<script src="<?= base_url('assets/plugins/') ?>tinymce/tinymce.min.js"></script>
<script>
   tinymce.init({
      selector: '#mtr_isi',
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
<!-- Modal -->
<div class="modal fade" id="dataModal" role="dialog" aria-labelledby="dataModalTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-xl" id="dataModalDialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="dataModalTitle"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="dataModalBody">
            <form id="form-data">
               <div class="row">
                  <div class="col-md-12">
                     <input type="hidden" name="mtr_id" value="">
                     <input type="hidden" name="mtr_kjr_id" value="<?= $materi['mtr_kjr_id'] ?>">
                     <input type="hidden" name="mtr_index" value="<?= $materi['mtr_id'] ?>">
                     <div class="form-group">
                        <label>Nama Materi</label>
                        <input type="text" id="mtr_nama" value="" name="mtr_nama" class="form-control" placeholder="Nama Materi">
                        <span class="text-invalid" id="mtr_nama_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Isi</label>
                        <textarea name="mtr_isi" id="mtr_isi"></textarea>
                        <span class="text-invalid" id="mtr_isi_error"></span>
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