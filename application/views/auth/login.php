<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?> | <?= getApp('app_title')['conf_value'] ?></title>

    <!-- Favicons -->
    <link href="<?= STORAGEPATH ?>/system/<?= getApp('app_icons')['conf_value'] ?>" rel="icon">
    <link href="<?= STORAGEPATH ?>/system/<?= getApp('app_icons')['conf_value'] ?>" rel="apple-touch-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>css/style.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-success">
            <div class="card-header text-center">
                <img src="<?= STORAGEPATH ?>/system/<?= getApp('app_brand')['conf_value'] ?>" alt="<?= getApp('app_names')['conf_value'] ?>" class="brand-image" style="opacity: .8; max-width: 70%; max-height: 9rem;">
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masuk dengan akun anda</p>
                <div id="alert"> </div>
                <form id="form-data" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="text-invalid" id="username_error"></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text" id="lock">
                                <span id="loc" class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="text-invalid" id="password_error"></div>
                    </div>
                    <button class="btn btn-block btn-success">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </button>
                    <a href="<?= base_url(''); ?>" class="btn btn-block btn-secondary">
                        <i class="fas fa-arrow-circle-left mr-2"></i> Beranda
                    </a>
                </form>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="<?= base_url('assets/admin/') ?>plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/admin/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/admin/') ?>dist/js/adminlte.min.js"></script>
    <script>
        var BASE_URL = "<?= base_url(); ?>";
        $("#form-data").submit(function(event) {
            masuk();
            event.preventDefault();
        });
        $('#lock').on('click', function() {
            var a = $('#password').attr("type");
            if (a == 'password') {
                $('#password').attr("type", 'text');
                $('#loc').attr("class", 'fas fa-eye');
            } else {
                $('#password').attr("type", 'password');
                $('#loc').attr("class", 'fas fa-lock');
            }
        });

        function masuk() {
            var form_data = new FormData($('#form-data')[0]);
            var link = BASE_URL + 'admin/auth/login';
            $.ajax({
                url: link,
                type: "POST",
                data: form_data,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.text-invalid').html('');
                    $('.form-control').removeClass("is-invalid").removeClass("is-valid");
                },
                success: function(data) {
                    if (data.status == 1) {
                        $('#alert').html('<div class="alert alert-success" role="alert">' + data.pesan + '</div>')
                        setTimeout(function() {
                            window.location.href = BASE_URL + "admin";
                        }, 2000);
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
                        $('#alert').html('<div class="alert alert-danger" role="alert">' + data.pesan + '</div>')
                        setTimeout(function() {
                            $('#alert').html('');
                        }, 2000);
                    }
                }
            });
        }
    </script>
</body>

</html>