<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin') ?> - Playpass CMS</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php
    // Google Tag Manager (optional for admin panel)
    $gtmConfig = config('GoogleTagManager');
    if ($gtmConfig->isEnabled()):
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?= esc($gtmConfig->containerId) ?>');</script>
    <!-- End Google Tag Manager -->
    <?php endif; ?>
</head>
<body class="admin-body">
    <?php if ($gtmConfig->isEnabled()): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= esc($gtmConfig->containerId) ?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php endif; ?>
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <a href="<?= site_url('admin/dashboard') ?>" class="sidebar-logo">
                <img src="<?= base_url('assets/logo.png') ?>" alt="Playpass" style="height: 28px;">
                <span class="logo-text">CMS</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-title">Main</span>
                <a href="<?= site_url('admin/dashboard') ?>" class="nav-link <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Content</span>
                <a href="<?= site_url('admin/banner') ?>" class="nav-link <?= strpos(uri_string(), 'admin/banner') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-flag"></i>
                    <span>Top Banner</span>
                </a>
                <a href="<?= site_url('admin/carousel') ?>" class="nav-link <?= strpos(uri_string(), 'admin/carousel') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-images"></i>
                    <span>Carousel Slides</span>
                </a>
                <a href="<?= site_url('admin/products') ?>" class="nav-link <?= strpos(uri_string(), 'admin/products') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="<?= site_url('admin/brands') ?>" class="nav-link <?= strpos(uri_string(), 'admin/brands') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span>Brands</span>
                </a>
                <a href="<?= site_url('admin/stories') ?>" class="nav-link <?= strpos(uri_string(), 'admin/stories') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-newspaper"></i>
                    <span>Stories</span>
                </a>
                <a href="<?= site_url('admin/promos') ?>" class="nav-link <?= strpos(uri_string(), 'admin/promos') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-percent"></i>
                    <span>Promos</span>
                </a>
                <a href="<?= site_url('admin/how-it-works') ?>" class="nav-link <?= strpos(uri_string(), 'admin/how-it-works') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-question-circle"></i>
                    <span>How It Works</span>
                </a>
                <a href="<?= site_url('admin/customer-support') ?>" class="nav-link <?= strpos(uri_string(), 'admin/customer-support') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-headset"></i>
                    <span>Customer Support</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Sales</span>
                <a href="<?= site_url('admin/orders') ?>" class="nav-link <?= strpos(uri_string(), 'admin/orders') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="<?= site_url('admin/vouchers') ?>" class="nav-link <?= strpos(uri_string(), 'admin/vouchers') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Vouchers</span>
                </a>
                <a href="<?= site_url('admin/first-purchase-promos') ?>" class="nav-link <?= strpos(uri_string(), 'admin/first-purchase-promos') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-gift"></i>
                    <span>First Purchase</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Users</span>
                <a href="<?= site_url('admin/users') ?>" class="nav-link <?= strpos(uri_string(), 'admin/users') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Reports</span>
                <a href="<?= site_url('admin/reports') ?>" class="nav-link <?= strpos(uri_string(), 'admin/reports') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= site_url('app') ?>" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                <span>View Site</span>
            </a>
            <a href="<?= site_url('admin/logout') ?>" class="nav-link logout-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <!-- Top Header -->
        <header class="admin-header">
            <div class="header-left">
                <h1 class="page-title"><?= esc($pageTitle ?? 'Dashboard') ?></h1>
            </div>
            <div class="header-right">
                <div class="admin-user">
                    <span class="user-name"><?= esc(session()->get('name') ?? 'Admin') ?></span>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="margin: 20px 30px 0;">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error" style="margin: 20px 30px 0;">
                <i class="fas fa-exclamation-circle"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Page Content -->
        <main class="admin-content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        const baseUrl = "<?= base_url() ?>";
    </script>
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>

