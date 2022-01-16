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
                     <table id="tb_menu" class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th style="width:5%;">No</th>
                              <th class="text-center" style="width: 15%;">Aksi</th>
                              <th class="text-center">Nama Menu</th>
                              <th class="text-center" style="width: 15%;">Link</th>
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
      tabel = $('#tb_menu').DataTable({
         "processing": true,
         "responsive": true,
         "serverSide": true,
         language: table_language(),
         "ordering": true, // Set true agar bisa di sorting
         "order": [
            [1, 'asc']
         ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
         "ajax": {
            "url": BASE_URL + "admin/menu/view_data", // URL file untuk proses select datanya
            "type": "POST"
         },
         "deferRender": true,
         "aLengthMenu": [
            [10, 25, 50, 100],
            [10, 25, 50, 100, "All"]
         ],
         "columns": [{
               data: 'mnu_id',
               orderable: false,
               render: function(data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               },
               className: "text-center",
            },
            {
               "data": "mnu_name"
            },
            {
               "data": "mnu_link",
               className: "text-center",
            },
            {
               "data": "mnu_id",
               "render": function(data, type, row, meta) {
                  var btn = `<button type="button" class="btn btn-warning btn-sm edit" data-id="` + data + `"><i class="fas fa-edit"></i></button>`;
                  btn += `<button type="button" class="btn btn-danger btn-sm hapus" data-id="` + data + `"><i class="fas fa-trash"></i></button>`;
                  return btn;
               },
               className: "text-center",
               orderable: false
            },
         ],
      });
   });

   $(document).off("click", "#tb_menu button.edit")
      .on("click", "#tb_menu button.edit", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/role/getByID",
            dataType: "JSON",
            data: {
               mnu_id: $(this).data('id')
            },
            success: function(data) {
               if (data.status == 1) {
                  data = data.data;
                  $('input[name="mnu_id"]').val(data.mnu_id);
                  $('input[name="grp_name"]').val(data.grp_name);
                  getMenu(data.mnu_id);
                  $('#dataModal').modal('show');
                  $('#dataModalTitle').html('<i class="fas fa-edit"></i> Edit Data Group');
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
         getMenu();
         $('#dataModalTitle').html('<i class="fas fa-plus-circle"></i> Tambah Data Group');
         $(document).off("click", "#dataModalSave").on("click", "#dataModalSave", function(e) {
            simpan();
         });
      });
   $(document).off("click", "#tb_menu button.hapus")
      .on("click", "#tb_menu button.hapus", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/role/destroy",
            dataType: "JSON",
            data: {
               mnu_id: $(this).data('id')
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

   $(document).off("click", ".check_mnu")
      .on("click", ".check_mnu", function(e) {
         console.log($(this).val());
         if ($(this).is(":checked")) {
            $('[mnuid="' + $(this).val() + '"]').attr("checked", "checked");
         } else {
            $('[mnuid="' + $(this).val() + '"]').attr("checked", false);
         }
      });

   function getMenu(mnu_id = null) {
      $.ajax({
         type: "POST",
         url: "<?php echo base_url('admin/role/getMenu') ?>",
         dataType: "JSON",
         data: {
            mnu_id: mnu_id
         },
         success: function(data) {
            var menu = data.data.menu;
            var akses = data.data.akses;
            $('#tb_menu tbody').html('');
            var no = 1;
            $.each(menu, function(i, val) {
               var t = '<tr>';
               t += `<td class="text-left" style="width: 50%;">`;
               t += `<div class="custom-control custom-checkbox">
                    <input type="checkbox" name="check[]" value="` + val.mnu_id + `" class="custom-control-input check_mnu" id="` + val.mnu_id + `">
                    <label class="custom-control-label" for="` + val.mnu_id + `">` + val.mnu_name + `</label>
                    </div>`;
               t += `</td>`;
               var submenu = val.submenu;
               t += `<td class="text-left" style="width: 50%;">`;
               $.each(submenu, function(i, val2) {
                  t += `<div class="custom-control custom-checkbox">
                        <input type="checkbox" name="check[]" value="` + val2.mnu_id + `" class="custom-control-input" id="` + val2.mnu_id + `" mnuid="` + val.mnu_id + `">
                        <label class="custom-control-label" for="` + val2.mnu_id + `">` + val2.mnu_name + `</label>
                        </div>`;
               });
               t += `</td>`;
               t += '</tr>';
               $('#tb_menu tbody').append(t);
               no++;
            });
            $.each(akses, function(i, val) {
               var submenu = val.submenu;
               $('#' + val.mnu_id).attr("checked", "checked");
               $.each(submenu, function(i, val2) {
                  $('#' + val2.mnu_id).attr("checked", "checked");
               });
            });
            if (mnu_id) {
               $('#tb_menu tbody').append('<input type="hidden" name="mnu_id" value="' + mnu_id + '">');
            }
         }
      });
   }

   function simpan() {
      var form_data = new FormData($('#form-data')[0]);
      var link = BASE_URL + 'admin/role/store';
      $.ajax({
         url: link,
         type: "POST",
         data: form_data,
         dataType: 'json',
         contentType: false,
         processData: false,
         success: function(data) {
            if (data.status == 1) {
               hideModal();
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
               hideModal();
               toastr.error(data.pesan);
            }
         }
      });
   }

   $(document).off("hidden.bs.modal", "#dataModal")
      .on("hidden.bs.modal", "#dataModal", function(e) {
         $('.text-invalid').html('');
         $('#dataModalTitle').html('');
         $('input[name="mnu_id"]').val('');
         $('input[name="grp_name"]').val('').removeClass("is-valid").removeClass("is-invalid");
         $("#dataModalSave").prop("onclick", null).off("click");
      });

   function hideModal() {
      $('#dataModal').modal('hide');
   }
</script>
<!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="dataModalTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" id="dataModalDialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="dataModalTitle"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form id="form-data">
               <div class="row">
                  <div class="col-md-12">
                     <input type="hidden" name="mnu_id" value="">
                     <div class="form-group">
                        <label>Nama Group</label>
                        <input type="text" id="grp_name" value="" name="grp_name" class="form-control" placeholder="Nama Lengkap">
                        <span class="text-invalid" id="grp_name_error"></span>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <table id="tb_menu" class="table table-striped dataTable">
                        <thead class="thead-light">
                           <tr>
                              <th class="text-center">Nama Menu</th>
                              <th class="text-center">Nama Sub Menu</th>
                           </tr>
                        </thead>
                        <tbody></tbody>
                     </table>
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