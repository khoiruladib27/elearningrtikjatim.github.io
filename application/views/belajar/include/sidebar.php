<style>
    .nav .nav-item {
        background-color: #eaeaea !important;
        border-radius: .2rem !important;
        margin-bottom: 0.2rem !important;
    }

    .nav-link.finish {
        background-color: #c0ffcf;
        color: #000;
    }

    .nav-link.finish i {
        color: #28a745 !important;
    }

    .nav-item.active {
        background-color: #d1e8d6 !important;
    }

    .nav-header {
        font-size: 1rem !important;
    }
</style>
<aside class="main-sidebar sidebar-<?= getApp('app_sidebar_theme')['conf_value'] ?>-<?= getApp('app_sidebar_color')['conf_value'] ?> elevation-2">
    <!-- Brand Logo -->
    <a href="<?= base_url() ?>" class="brand-link">
        <img src="<?= STORAGEPATH ?>/system/<?= getApp('app_icons')['conf_value'] ?>" alt="<?= getApp('app_names')['conf_value'] ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-dark"><?= getApp('app_title')['conf_value'] ?></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column pb-5" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= base_url('member') ?>" class="nav-link pl-1">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <?php foreach ($kejuruan['materi'] as $p) : ?>
                    <li class="nav-header">
                        <hr class="my-0">
                    </li>
                    <li class="nav-header"><i class="nav-icon fas fa-file mr-2"></i> <?= $p['mtr_nama']; ?></li>
                    <?php if ($p['submateri']) : ?>
                        <?php foreach ($p['submateri'] as $sp) : ?>
                            <li class="nav-item">
                                <?php if ($_SESSION['belajar_act'] == $sp['mtr_slug']) : ?>
                                    <a href="<?= base_url('belajar/' . $kejuruan['kjr_slug'] . '/' . $sp['mtr_slug']) ?>" class="nav-link active">
                                        <i class="fas fa-tasks mr-1"></i>
                                    <?php else : ?>
                                        <?php if ($sp['mdl_id']) : ?>
                                            <a href="<?= base_url('belajar/' . $kejuruan['kjr_slug'] . '/' . $sp['mtr_slug']) ?>" class="nav-link finish">
                                                <i class="fas fa-check-circle mr-1"></i>
                                            <?php else : ?>
                                                <a class="nav-link">
                                                    <i class="fas fa-lock mr-1"></i>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <p><?= $sp['mtr_nama'] ?></p>
                                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ($kejuruan['kls_selesai'] == 1) : ?>
                    <li class="nav-item">
                        <a href="<?= base_url('belajar/' . $kejuruan['kjr_slug'] . '/selesai') ?>" class="nav-link finish pl-1">
                            <i class="nav-icon fas fa-flag"></i>
                            <p>
                                Selesai
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>