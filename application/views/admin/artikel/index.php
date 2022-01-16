<style>
  #tb_artikel img {
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
              <table id="tb_artikel" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th style="width:5%;">No</th>
                    <th class="text-center" style="width: 15%;">Aksi</th>
                    <th class="text-center" style="max-width:3rem;">Gambar</th>
                    <th class="text-center" style="width: 40%;">Judul</th>
                    <th class="text-center" style="width: 15%;">Tanggal Upload</th>
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
    tabel = $('#tb_artikel').DataTable({
      "processing": true,
      "responsive": true,
      "serverSide": true,
      language: table_language(),
      "autoWidth": false,
      "ordering": true, // Set true agar bisa di sorting
      "order": [
        [4, 'desc'],
        [3, 'asc']
      ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
      "ajax": {
        "url": BASE_URL + "admin/artikel/view_data", // URL file untuk proses select datanya
        "type": "POST"
      },
      "deferRender": true,
      "aLengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100, "All"]
      ],
      "columns": [{
          data: 'art_id',
          orderable: false,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          },
          className: "text-center",
        },
        {
          "data": "art_id",
          "render": function(data, type, row, meta) {
            var btn = ``;
            if (row.art_locked == 1) {
              btn += `<button type="button" class="ml-1 btn btn-secondary btn-sm lock"  data-id="` + row.art_id + `" data-lock="` + row.art_locked + `"><i class="fas fa-lock" title="Aktifkan"></i></button>`;
            } else {
              btn += `<button type="button" class="ml-1 btn btn-success btn-sm lock"  data-id="` + row.art_id + `" data-lock="` + row.art_locked + `"><i class="fas fa-lock-open" title="Kunci"></i></button>`;
            }
            if (row.art_headline == 1) {
              btn += `<button type="button" class="ml-1 btn btn-info btn-sm headline"  data-id="` + row.art_id + `" data-headline="` + row.art_headline + `"><i class="fas fa-star" title="Batalkan headline"></i></button>`;
            } else {
              btn += `<button type="button" class="ml-1 btn btn-info btn-sm headline"  data-id="` + row.art_id + `" data-headline="` + row.art_headline + `"><i class="far fa-star" title="Jadikan headline"></i></button>`;
            }
            btn += `<button type="button" class="ml-1 btn btn-warning btn-sm edit" data-id="` + row.art_id + `" title="Edit"><i class="fas fa-edit"></i></button>`;
            btn += `<button type="button" class="ml-1 btn btn-danger btn-sm hapus" data-id="` + row.art_id + `" title="Hapus"><i class="fas fa-trash"></i></button>`;
            return btn;
          },
          className: "text-center",
          orderable: false
        },
        {
          data: "art_gambar",
          orderable: false,
          render: function(data, type, row, meta) {
            if (data) {
              var foto = `<img src="<?= STORAGEPATH ?>/artikel/` + data + `">`;
            } else {
              var foto = `<img src="<?= STORAGEPATH ?>/no-image.jpg">`;
            }
            return foto;
          },
          className: "text-center",
        },
        {
          "data": "art_judul"
        },
        {
          "data": "art_tgl_upload"
        },
      ],
    });
  });
  $(document).off("click", "#tb_artikel button.lock")
    .on("click", "#tb_artikel button.lock", function(e) {
      $.ajax({
        type: "POST",
        url: BASE_URL + "admin/artikel/lock",
        dataType: "JSON",
        data: {
          art_id: $(this).data('id'),
          art_locked: $(this).data('lock')
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
  $(document).off("click", "#tb_artikel button.headline")
    .on("click", "#tb_artikel button.headline", function(e) {
      $.ajax({
        type: "POST",
        url: BASE_URL + "admin/artikel/headline",
        dataType: "JSON",
        data: {
          art_id: $(this).data('id'),
          art_headline: $(this).data('headline')
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
  $(document).off("click", "#tb_artikel button.edit")
    .on("click", "#tb_artikel button.edit", function(e) {
      $.ajax({
        type: "POST",
        url: BASE_URL + "admin/artikel/getByID",
        dataType: "JSON",
        data: {
          art_id: $(this).data('id')
        },
        success: function(data) {
          if (data.status == 1) {
            data = data.data;
            getKategori(data.art_ktg_id);
            $('input[name="art_id"]').val(data.art_id);
            $('input[name="art_judul"]').val(data.art_judul);
            tinyMCE.activeEditor.setContent(data.art_isi);
            if (data.art_gambar) {
              var art_gambar = "<?= STORAGEPATH ?>/artikel/" + data.art_gambar;
            } else {
              var art_gambar = "<?= STORAGEPATH ?>/no-image.jpg";
            }
            $('#blah-art_gambar').attr("src", art_gambar);
            $('#dataModal').modal('show');
            $('#dataModalTitle').html('<i class="fas fa-edit"></i> Edit Data Pengguna');
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
      getKategori();
      $('#dataModalTitle').html('<i class="fas fa-plus-circle"></i> Tambah Data Pengguna');
      $(document).off("click", "#dataModalSave").on("click", "#dataModalSave", function(e) {
        simpan();
      });
    });
  $(document).off("click", "#tb_artikel button.hapus")
    .on("click", "#tb_artikel button.hapus", function(e) {
      $.ajax({
        type: "POST",
        url: BASE_URL + "admin/artikel/destroy",
        dataType: "JSON",
        data: {
          art_id: $(this).data('id')
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

  function simpan() {
    tinyMCE.triggerSave();
    var form_data = new FormData($('#form-data')[0]);
    var link = BASE_URL + 'admin/artikel/store';
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

  function getKategori(ktg_id = null) {
    $.ajax({
      type: "get",
      url: "<?php echo base_url('admin/artikel/getKategori') ?>",
      success: function(data) {
        data = JSON.parse(data);
        data = data.data;
        $('#art_ktg_id').html('<option value="">== Pilih Kategori ==</option>');
        $.each(data, function(i, val) {
          var t = `<option value="` + val.ktg_id + `">` + val.ktg_nama + `</option>`;
          $('#art_ktg_id').append(t);
        });
        $('#art_ktg_id').val(ktg_id);
      }
    });
  }

  $(document).off("hidden.bs.modal", "#dataModal")
    .on("hidden.bs.modal", "#dataModal", function(e) {
      tinyMCE.activeEditor.setContent('');
      $('.text-invalid').html('');
      $('#dataModalTitle').html('');
      $('input[name="art_id"]').val('');
      $('input[name="art_judul"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="art_isi"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="art_gambar"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('#blah-art_gambar').attr("src", '<?= STORAGEPATH ?>' + '/no-image.jpg');
      $("#dataModalSave").prop("onclick", null).off("click");
    });
</script>

<script src="<?= base_url('assets/plugins/') ?>tinymce/tinymce.min.js"></script>
<script>
  tinymce.init({
    selector: '#art_isi',
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
              <input type="hidden" name="art_id" value="">
              <div class="form-group">
                <label>Judul</label>
                <input type="text" id="art_judul" value="" name="art_judul" class="form-control" placeholder="Judul">
                <span class="text-invalid" id="art_judul_error"></span>
              </div>
              <div class="form-group">
                <label>Isi</label>
                <textarea name="art_isi" id="art_isi"></textarea>
                <span class="text-invalid" id="art_isi_error"></span>
              </div>
              <div class="form-group">
                <label>Kategori</label>
                <select class="form-control select2" style="width: 100%" id="art_ktg_id" data-placeholder="Pilih Group" name="art_ktg_id">
                </select>
                <span class="text-invalid" id="art_ktg_id_error"></span>
              </div>
              <div class="form-group">
                <label>Foto</label>
                <div class="input-group">
                  <input onchange="readURL(this, 'art_gambar');" name="art_gambar" type="file" accept="image/gif, image/jpeg, image/png" id="art_gambar">
                </div>
                <div class="invalid-feedback" id="art_gambar_error"></div>
                <div id="art_gambar-display">
                  <img id="blah-art_gambar" src="<?= STORAGEPATH ?>/no-image.jpg" alt="Mengambil Foto ..." class="mt-2" style="height: 200px;">
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