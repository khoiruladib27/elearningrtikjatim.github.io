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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= base_url('admin') ?>" class="nav-link <?= ($this->uri->uri_string() == "admin") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <?php $menu = getMenu(); ?>
                <?php foreach ($menu['data'] as $m) : ?>
                    <li class="nav-item <?= ($m['active']) ? 'menu-is-opening menu-open' : '' ?>">
                        <a href="<?= (!$m['submenu']) ? base_url($m['mnu_link']) : '' ?>" class="nav-link <?= $m['active'] ?>">
                            <i class="nav-icon <?= $m['mnu_icon'] ?>"></i>
                            <p>
                                <?= $m['mnu_name']; ?>
                                <?php if ($m['submenu']) : ?>
                                    <i class="right fas fa-angle-left"></i>
                                <?php endif; ?>
                            </p>
                        </a>
                        <?php if ($m['submenu']) : ?>
                            <?php foreach ($m['submenu'] as $sm) : ?>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url($sm['mnu_link']) ?>" class="nav-link <?= $sm['active'] ?>">
                                            <i class="<?= $sm['mnu_icon'] ?> nav-icon"></i>
                                            <p><?= $sm['mnu_name'] ?></p>
                                        </a>
                                    </li>
                                </ul>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</aside>