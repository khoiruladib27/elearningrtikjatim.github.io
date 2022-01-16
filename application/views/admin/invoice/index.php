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
               <h3 class="my-0 ml-2"><i class="fas fa-money-bill-wave"></i> <?= $title ?></h3>
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
                  <div class="card-body">
                     <table id="tb_data" class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th style="width:5%;">No</th>
                              <th class="text-center" style="width: 15%;">Aksi</th>
                              <th class="text-center" style="width: 15%;">Kode Invoice</th>
                              <th class="text-center" style="width: 20%;">Member</th>
                              <th class="text-center" style="width: 20%;">Kelas</th>
                              <th class="text-center" style="width: 15%;">Harga</th>
                              <th class="text-center" style="width: 15%;">Metode Pembayaran</th>
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
            "url": BASE_URL + "admin/invoice/view_data", // URL file untuk proses select datanya
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
                  if (row.kls_lunas == null) {
                     btn += `<button class="mr-1 btn btn-purple btn-sm lunaskan" data-id="` + row.kls_id + `"><i class="fas fa-money-bill-wave" title="Lunaskan"></i> Lunaskan</button>`;
                  } else {
                     if (row.kls_locked == 1) {
                        btn += `<button type="button" class="mr-1 btn btn-secondary btn-sm lock mb-2"  data-id="` + row.kls_id + `" data-lock="` + row.kls_locked + `" title="Aktifkan"><i class="fas fa-lock"></i></button>`
                     } else {
                        btn += `<button type="button" class="mr-1 btn btn-warning btn-sm lock mb-2"  data-id="` + row.kls_id + `" data-lock="` + row.kls_locked + `" title="Kunci"><i class="fas fa-lock-open"></i></button>`
                     }
                     btn += `<button class="mr-1 btn btn-success btn-sm"><i class="fas fa-check-square" title="Sudah Lunas"></i> Sudah Lunas</button>`;
                  }
                  return btn;
               },
               className: "text-center",
               orderable: false
            },
            {
               "data": "kls_id"
            },
            {
               "data": "mbr_name"
            },
            {
               "data": "kjr_nama"
            },
            {
               "data": "kjr_harga"
            },
            {
               "data": "trf_nama"
            },
         ],
      });
   });

   $(document).off("click", "#tb_data button.lock")
      .on("click", "#tb_data button.lock", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/invoice/lock",
            dataType: "JSON",
            data: {
               kls_id: $(this).data('id'),
               kls_locked: $(this).data('lock')
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
   $(document).off("click", "#tb_data button.lunaskan")
      .on("click", "#tb_data button.lunaskan", function(e) {
         $.ajax({
            type: "POST",
            url: BASE_URL + "admin/invoice/lunaskan",
            dataType: "JSON",
            data: {
               kls_id: $(this).data('id')
            },
            success: function(data) {
               if (data.status == 1) {
                  toastr.success(data.pesan);
                  tabel.ajax.reload(null, true);
               } else {
                  toastr.error(data.pesan);
               }
            }
         });
      });
</script>