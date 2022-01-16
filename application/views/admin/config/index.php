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
            <div class="card-body">
              <form id="form-data">
                <div class="card-body row justify-content-center">
                  <div class="col-md-10">
                    <?php foreach ($setting as $s) : ?>
                      <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label"><?= $s['conf_name'] ?></label>
                        <div class="col-sm-9">
                          <?php if ($s['conf_type'] == 'text') : ?>
                            <input type="text" class="form-control" <?= ($s['conf_value']) ? 'value="' . $s['conf_value'] . '"' : '' ?> name="<?= $s['conf_char'] ?>" id="<?= $s['conf_char'] ?>">
                          <?php elseif ($s['conf_type'] == 'textarea') : ?>
                            <textarea class="form-control" name="<?= $s['conf_char'] ?>" id="<?= $s['conf_char'] ?>" cols="30" rows="5"><?= ($s['conf_value']) ? $s['conf_value']  : '' ?></textarea>
                          <?php elseif ($s['conf_type'] == 'img') : ?>
                            <div class="custom-file">
                              <input onchange="readURL(this, '<?= $s['conf_char'] ?>');" accept="image/gif, image/jpeg, image/png" type="file" class="custom-file-input" name="<?= $s['conf_char'] ?>" id="<?= $s['conf_char'] ?>">
                              <label class="custom-file-label" for="customFile">Pilih file</label>
                            </div>
                            <div id="<?= $s['conf_char'] ?>-display">
                              <?= ($s['conf_value']) ? '<img id="blah-' . $s['conf_char'] . '" src="' . STORAGEPATH . '/system/' . $s['conf_value'] . '" alt="Mengambil Foto ..." class="mt-2" style="height: 200px;">' : '' ?>
                            </div>
                          <?php elseif ($s['conf_type'] == 'select') : ?>
                            <select class="form-control select2" style="width: 100%" id="usr_group" data-placeholder="<?= $s['conf_name'] ?>" id="<?= $s['conf_char'] ?>" name="<?= $s['conf_char'] ?>">
                              <?php foreach (json_decode($s['conf_option']) as $op => $val) : ?>
                                <option value="<?= $op ?>" <?= ($s['conf_value'] == $op) ? "selected" : "" ?>><?= $val ?></option>
                              <?php endforeach; ?>
                            </select>
                          <?php endif; ?>
                          <span class="text-invalid" id="<?= $s['conf_char'] ?>_error"></span>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <div class="col-md-5 col-12">
                    <button onclick="simpan()" type="button" class="btn btn-success btn-block"><i class="fas fa-save"></i> Simpan</button>
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

<script>
  function simpan() {
    var form_data = new FormData($('#form-data')[0]);
    var link = BASE_URL + 'admin/config/store';
    $.ajax({
      url: link,
      type: "POST",
      data: form_data,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function(data) {
        $.each(data.pesan, function(i, item) {
          $('#' + i + '_error').html(item.pesan);
          $('#' + i + '_error').show();
          if (item.status == 1) {
            $('#' + i + '_error').removeClass("text-invalid").addClass("text-valid");
            $('#' + i).removeClass("is-invalid").addClass("is-valid");
          } else {
            $('#' + i).removeClass("is-valid").addClass("is-invalid");
          }
        });
        setTimeout(() => {
          location.reload();
        }, 2000);
      }
    });
  }
</script>