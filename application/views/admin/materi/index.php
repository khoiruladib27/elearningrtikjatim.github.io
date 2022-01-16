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
               <h3 class="my-0 ml-2"><i class="fas fa-book-reader"></i> <?= $title ?></h3>
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
            "url": BASE_URL + "admin/materi/view_data", // URL file untuk proses select datanya
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
                  btn += `<a href="<?= base_url('admin/materi/kategori/') ?>` + row.kjr_slug + `" class="mr-1 btn btn-purple btn-sm" data-id="` + row.kjr_id + `"><i class="fas fa-align-justify" title="Kategori"></i> Kategori</a>`;
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
</script>