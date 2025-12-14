<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin') ?> - Playpass CMS</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-body">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <a href="/admin/dashboard" class="sidebar-logo">
                <img src="/assets/logo.png" alt="Playpass" style="height: 28px;">
                <span class="logo-text">CMS</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-title">Main</span>
                <a href="/admin/dashboard" class="nav-link <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Content</span>
                <a href="/admin/carousel" class="nav-link <?= strpos(uri_string(), 'admin/carousel') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-images"></i>
                    <span>Carousel Slides</span>
                </a>
                <a href="/admin/products" class="nav-link <?= strpos(uri_string(), 'admin/products') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="/admin/brands" class="nav-link <?= strpos(uri_string(), 'admin/brands') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span>Brands</span>
                </a>
                <a href="/admin/stories" class="nav-link <?= strpos(uri_string(), 'admin/stories') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-newspaper"></i>
                    <span>Stories</span>
                </a>
                <a href="/admin/promos" class="nav-link <?= strpos(uri_string(), 'admin/promos') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-percent"></i>
                    <span>Promos</span>
                </a>
                <a href="/admin/how-it-works" class="nav-link <?= strpos(uri_string(), 'admin/how-it-works') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-question-circle"></i>
                    <span>How It Works</span>
                </a>
                <a href="/admin/customer-support" class="nav-link <?= strpos(uri_string(), 'admin/customer-support') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-headset"></i>
                    <span>Customer Support</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Sales</span>
                <a href="/admin/orders" class="nav-link <?= strpos(uri_string(), 'admin/orders') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="/admin/vouchers" class="nav-link <?= strpos(uri_string(), 'admin/vouchers') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Vouchers</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Users</span>
                <a href="/admin/users" class="nav-link <?= strpos(uri_string(), 'admin/users') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Reports</span>
                <a href="/admin/reports" class="nav-link <?= strpos(uri_string(), 'admin/reports') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="/app" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                <span>View Site</span>
            </a>
            <a href="/admin/logout" class="nav-link logout-link">
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

    <script src="/assets/js/admin.js"></script>
</body>
</html>

