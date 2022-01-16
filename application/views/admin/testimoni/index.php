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
         "order": false,
         "ajax": {
            "url": BASE_URL + "admin/testimoni/view_data", // URL file untuk proses select datanya
            "type": "POST"
         },
         "deferRender": true,
         "aLengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
         ],
         "columns": [{
               data: 'tst_id',
               orderable: false,
               render: function(data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               },
               className: "text-center",
            },
            {
               "data": "tst_id",
               "render": function(data, type, row, meta) {
                  var up_disabled = '';
                  var down_disabled = '';
                  var btn = ``;
                  if (row.tst_order <= row.tst_min_order) {
                     up_disabled = 'disabled';
                  }
                  if (row.tst_order >= row.tst_max_order) {
                     down_disabled = 'disabled';
                  }
                  btn += `<button type="button" class="mr-1 btn btn-info btn-sm to-up" data-id="` + row.tst_id + `" data-order="` + row.tst_order + `" title="Pindah Ke Atas" ` + up_disabled + `><i class="fas fa-arrow-up"></i></button>`;
                  btn += `<button type="button" class="mr-1 btn btn-info btn-sm to-down" data-id="` + row.tst_id + `" data-order="` + row.tst_order + `" title="Pindah Ke Bawah" ` + down_disabled + `><i class="fas fa-arrow-down"></i></button>`;
                  if (row.tst_locked == 1) {
                     btn += `<button type="button" class="mr-1 btn btn-secondary btn-sm lock"  data-id="` + row.tst_id + `" data-lock="` + row.tst_locked + `" title="Aktifkan"><i class="fas fa-lock"></i></button>`
                  } else {
                     btn += `<button type="button" class="mr-1 btn btn-success btn-sm lock"  data-id="` + row.tst_id + `" data-lock="` + row.tst_locked + `" title="Kunci"><i class="fas fa-lock-open"></i></button>`
                  }
                  btn += `<button type="button" class="mr-1 btn btn-warning btn-sm edit" data-id="` + row.tst_id + `"><i class="fas fa-edit" title="Edit"></i></button>`;
                  btn += `<button type="button" class="mr-1 btn btn-danger btn-sm hapus" data-id="` + row.tst_id + `"><i class="fas fa-trash" title="Hapus"></i></button>`;
                  return btn;
               },
               className: "text-center",
               orderable: false
            },
            {
               "data": "tst_id",
               "render": function(data, type, row, meta) {
                  var img = '/no-image.jpg';
                  if (row.tst_image) {
                     img = `/testimoni/` + row.tst_image;
                  }
                  var btn = `<img src="<?= STORAGEPATH ?>` + img + `" style="max-width: 300px; max-height: 200px;">`;
                  return btn;
               },
               className: "text-center",
               orderable: false
            },
            {
               "data": "tst_nama",
               orderable: false
            },
         ],
      });
   });
   $(document).off("click", "#tb_data button.lock")
      .on("click", "#tb_data button.lock", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/testimoni/lock",
            dataType: "JSON",
            data: {
               tst_id: $(this).data('id'),
               tst_locked: $(this).data('lock')
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

   function order(tst_id = '', tst_order = '', tst_arrow = '') {
      $.ajax({
         type: "POST",
         url: BASE_URL + "admin/testimoni/order",
         dataType: "JSON",
         data: {
            tst_id: tst_id,
            tst_order: tst_order,
            tst_arrow: tst_arrow
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
            url: BASE_URL + "admin/testimoni/getByID",
            dataType: "JSON",
            data: {
               tst_id: $(this).data('id')
            },
            success: function(data) {
               if (data.status == 1) {
                  data = data.data;
                  $('input[name="tst_id"]').val(data.tst_id);
                  $('input[name="tst_nama"]').val(data.tst_nama);
                  $('#tst_isi').val(data.tst_isi);
                  if (data.tst_image) {
                     var tst_image = "<?= STORAGEPATH ?>/testimoni/" + data.tst_image;
                  } else {
                     var tst_image = "<?= STORAGEPATH ?>/no-image.jpg";
                  }
                  $('#blah-tst_image').attr("src", tst_image);
                  $('#dataModal').modal('show');
                  $('#dataModalTitle').html('<i class="fas fa-edit"></i> Edit Data Testimoni');
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
         $('#dataModalTitle').html('<i class="fas fa-plus-circle"></i> Tambah Data Testimoni');
         $(document).off("click", "#dataModalSave").on("click", "#dataModalSave", function(e) {
            simpan();
         });
      });
   $(document).off("click", "#tb_data button.hapus")
      .on("click", "#tb_data button.hapus", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/testimoni/destroy",
            dataType: "JSON",
            data: {
               tst_id: $(this).data('id')
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
   $(document).off("submit", "#form-testimoni")
      .on("submit", "#form-testimoni", function(e) {
         event.preventDefault();
         simpan();
      });

   function simpan() {
      var form_data = new FormData($('#form-testimoni')[0]);
      var link = BASE_URL + 'admin/testimoni/store';
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
         $('.text-invalid').html('');
         $('#dataModalTitle').html('');
         $('input[name="tst_id"]').val('');
         $('input[name="tst_nama"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('#tst_isi').val('').removeClass("is-valid").removeClass("is-invalid");
         $('input[name="tst_image"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $('#blah-tst_image').attr("src", '<?= STORAGEPATH ?>' + '/no-image.jpg');
         $("#dataModalSave").prop("onclick", null).off("click");
      });
</script>
<!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="dataModalTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" id="dataModalDialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="dataModalTitle"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="dataModalBody">
            <form id="form-testimoni" action="#">
               <div class="row">
                  <div class="col-md-12">
                     <input type="hidden" name="tst_id" value="">
                     <div class="form-group">
                        <label>Nama Testi</label>
                        <input type="text" id="tst_nama" value="" name="tst_nama" class="form-control" placeholder="Nama Testimoni">
                        <span class="text-invalid" id="tst_nama_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Isi Testimoni</label>
                        <textarea name="tst_isi" id="tst_isi" class="form-control" placeholder="Isi Testimoni" rows="3"></textarea>
                        <span class="text-invalid" id="tst_isi_error"></span>
                     </div>
                     <div class="form-group">
                        <label>Gambar</label>
                        <div class="input-group">
                           <input onchange="readURL(this, 'tst_image');" name="tst_image" type="file" accept="image/gif, image/jpeg, image/png" id="tst_image">
                        </div>
                        <div class="invalid-feedback" id="tst_image_error"></div>
                        <div id="tst_image-display">
                           <img id="blah-tst_image" src="<?= STORAGEPATH ?>/no-image.jpg" alt="Mengambil Foto ..." class="mt-2" style="max-height: 200px; max-width: 100%;">
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