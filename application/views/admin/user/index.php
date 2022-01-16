<style>
  #tb_user img {
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
              <table id="tb_user" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th style="width:5%;">No</th>
                    <th class="text-center" style="max-width:3rem;">Foto</th>
                    <th class="text-center" style="width: 20%;">Nama</th>
                    <th class="text-center" style="width: 15%;">Email</th>
                    <th class="text-center" style="width: 15%;">Username</th>
                    <th class="text-center" style="width: 5%;">Aktif</th>
                    <th class="text-center" style="width: 10%;">Aksi</th>
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
    tabel = $('#tb_user').DataTable({
      "processing": true,
      "responsive": true,
      "serverSide": true,
      language: table_language(),
      "autoWidth": false,
      "ordering": true, // Set true agar bisa di sorting
      "order": [
        [2, 'asc']
      ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
      "ajax": {
        "url": BASE_URL + "admin/user/view_data", // URL file untuk proses select datanya
        "type": "POST"
      },
      "deferRender": true,
      "aLengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100, "All"]
      ],
      "columns": [{
          data: 'usr_id',
          orderable: false,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          },
          className: "text-center",
        },
        {
          data: "usr_foto",
          orderable: false,
          render: function(data, type, row, meta) {
            if (data) {
              var foto = `<img src="<?= STORAGEPATH ?>/user/` + data + `">`;
            } else {
              var foto = `<img src="<?= STORAGEPATH ?>/no-image.jpg">`;
            }
            return foto;
          },
          className: "text-center",
        },
        {
          "data": "usr_name"
        },
        {
          "data": "usr_email"
        },
        {
          "data": "usr_username"
        },
        {
          "data": "usr_locked",
          "render": function(data, type, row, meta) {
            if (data == 1) {
              var btn = `<button type="button" class="btn btn-secondary btn-sm lock"  data-id="` + row.usr_id + `" data-lock="` + data + `"><i class="fas fa-lock"></i></button>`;
            } else {
              var btn = `<button type="button" class="btn btn-success btn-sm lock"  data-id="` + row.usr_id + `" data-lock="` + data + `"><i class="fas fa-lock-open"></i></button>`;
            }
            return btn;
          },
          className: "text-center",
        },
        {
          "data": "usr_id",
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
  $(document).off("click", "#tb_user button.lock")
    .on("click", "#tb_user button.lock", function(e) {
      $.ajax({
        type: "POST",
        url: BASE_URL + "admin/user/lock",
        dataType: "JSON",
        data: {
          usr_id: $(this).data('id'),
          usr_locked: $(this).data('lock')
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
  $(document).off("click", "#tb_user button.edit")
    .on("click", "#tb_user button.edit", function(e) {
      $.ajax({
        type: "POST",
        url: BASE_URL + "admin/user/getByID",
        dataType: "JSON",
        data: {
          usr_id: $(this).data('id')
        },
        success: function(data) {
          if (data.status == 1) {
            data = data.data;
            getRole(data.usr_group);
            $('input[name="usr_id"]').val(data.usr_id);
            $('input[name="usr_name"]').val(data.usr_name);
            $('input[name="usr_username"]').val(data.usr_username);
            $('input[name="usr_email"]').val(data.usr_email);
            $('#usr_deskripsi').html(data.usr_deskripsi);
            $('#usr_deskripsi').val(data.usr_deskripsi);
            $('input[name="usr_twitter"]').val(data.usr_twitter);
            $('input[name="usr_facebook"]').val(data.usr_facebook);
            $('input[name="usr_instagram"]').val(data.usr_instagram);
            if (data.usr_foto) {
              var usr_foto = "<?= STORAGEPATH ?>/user/" + data.usr_foto;
            } else {
              var usr_foto = "<?= STORAGEPATH ?>/no-image.jpg";
            }
            $('#blah-usr_foto').attr("src", usr_foto);
            $('#userModal').modal('show');
            $('#userModalTitle').html('<i class="fas fa-edit"></i> Edit Data Pengguna');
            $(document).off("click", "#userModalSave").on("click", "#userModalSave", function(e) {
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
      getRole();
      $('#userModal').modal('show');
      $('#userModalTitle').html('<i class="fas fa-plus-circle"></i> Tambah Data Pengguna');
      $(document).off("click", "#userModalSave").on("click", "#userModalSave", function(e) {
        simpan();
      });
    });
  $(document).off("click", "#tb_user button.hapus")
    .on("click", "#tb_user button.hapus", function(e) {
      $.ajax({
        type: "POST",
        url: BASE_URL + "admin/user/destroy",
        dataType: "JSON",
        data: {
          usr_id: $(this).data('id')
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

  function getRole(akses = null) {
    $.ajax({
      type: "get",
      url: "<?php echo base_url('admin/user/getGroup') ?>",
      success: function(data) {
        data = JSON.parse(data);
        data = data.data;
        $('#usr_group').html('<option value="">== Pilih Group ==</option>');
        $.each(data, function(i, val) {
          var t = `<option value="` + val.grp_id + `">` + val.grp_name + `</option>`;
          $('#usr_group').append(t);
        });
        $('#usr_group').val(akses);
      }
    });
  }

  function simpan() {
    var form_data = new FormData($('#form-user')[0]);
    var link = BASE_URL + 'admin/user/store';
    $.ajax({
      url: link,
      type: "POST",
      data: form_data,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function(data) {
        if (data.status == 1) {
          $('#userModal').modal('hide');
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
          $('#userModal').modal('hide');
          toastr.error(data.pesan);
        }
      }
    });
  }

  $(document).off("hidden.bs.modal", "#userModal")
    .on("hidden.bs.modal", "#userModal", function(e) {
      $('.text-invalid').html('');
      $('#userModalTitle').html('');
      $('input[name="usr_id"]').val('');
      $('input[name="usr_name"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="usr_username"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="usr_email"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="usr_password"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="usr_cpassword"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="usr_foto"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('#usr_group').val('').removeClass("is-valid").removeClass("is-invalid");
      $('#blah-usr_foto').attr("src", '<?= STORAGEPATH ?>' + '/no-image.jpg');
      $('#usr_deskripsi').html('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="usr_twitter"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="usr_facebook"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $('input[name="usr_instagram"]').val('').removeClass("is-valid").removeClass("is-invalid");
      $("#userModalSave").prop("onclick", null).off("click");
    });
</script>
<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" id="userModalDialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="userModalTitle"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="userModalBody">
        <form id="form-user">
          <div class="row">
            <div class="col-md-6">
              <input type="hidden" name="usr_id" value="">
              <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" id="usr_name" value="" name="usr_name" class="form-control" placeholder="Nama Lengkap">
                <span class="text-invalid" id="usr_name_error"></span>
              </div>
              <div class="form-group">
                <label>Username</label>
                <input type="text" id="usr_username" name="usr_username" value="" class="form-control" placeholder="Username">
                <span class="text-invalid" id="usr_username_error"></span>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="text" id="usr_email" name="usr_email" value="" class="form-control" placeholder="Username">
                <span class="text-invalid" id="usr_email_error"></span>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" id="usr_password" name="usr_password" class="form-control" placeholder="Password">
                <span class="text-invalid" id="usr_password_error"></span>
              </div>
              <div class="form-group">
                <label>Ulangi Password</label>
                <input type="password" id="usr_cpassword" name="usr_cpassword" class="form-control" placeholder="Ulangi Password">
                <span class="text-invalid" id="usr_cpassword_error"></span>
              </div>
              <div class="form-group">
                <label>Group</label>
                <select class="form-control select2" style="width: 100%" id="usr_group" data-placeholder="Pilih Group" name="usr_group">
                </select>
                <span class="text-invalid" id="usr_group_error"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="usr_deskripsi" id="usr_deskripsi" class="form-control" placeholder="Deskripsi Motivasi" rows="3"></textarea>
                <span class="text-invalid" id="usr_deskripsi_error"></span>
              </div>
              <div class="form-group">
                <label>Twitter</label>
                <input type="text" id="usr_twitter" name="usr_twitter" value="" class="form-control" placeholder="Link Twitter">
                <span class="text-invalid" id="usr_twitter_error"></span>
              </div>
              <div class="form-group">
                <label>Facebook</label>
                <input type="text" id="usr_facebook" name="usr_facebook" value="" class="form-control" placeholder="Link Facebook">
                <span class="text-invalid" id="usr_facebook_error"></span>
              </div>
              <div class="form-group">
                <label>Instagram</label>
                <input type="text" id="usr_instagram" name="usr_instagram" value="" class="form-control" placeholder="Link Instagram">
                <span class="text-invalid" id="usr_instagram_error"></span>
              </div>
              <div class="form-group">
                <label>Foto</label>
                <div class="input-group">
                  <input onchange="readURL(this, 'usr_foto');" name="usr_foto" type="file" accept="image/gif, image/jpeg, image/png" id="usr_foto">
                </div>
                <div class="invalid-feedback" id="usr_foto_error"></div>
                <div id="usr_foto-display">
                  <img id="blah-usr_foto" src="<?= STORAGEPATH ?>/no-image.jpg" alt="Mengambil Foto ..." class="mt-2" style="height: 200px;">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer text-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="float: right;"><i class="fas fa-times-circle"></i> Close</button>
        <button type="button" class="btn btn-success" id="userModalSave"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>