<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Playpass') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php
    // Google Tag Manager
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
<body style="position: relative;">
    <?php if ($gtmConfig->isEnabled()): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= esc($gtmConfig->containerId) ?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php endif; ?>

    <?= view_cell('App\Cells\HeaderCell::render') ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= view_cell('App\Cells\FooterCell::render') ?>

    <!-- Cart Sidebar -->
    <?= view('cart_sidebar') ?>
    
    <!-- Checkout Modal -->
    <?= view('checkout_modal') ?>

    <script>
        const baseUrl = "<?= base_url() ?>";
        const csrfName = "<?= csrf_token() ?>";
        let csrfHash = "<?= csrf_hash() ?>";
    </script>
    <script src="<?= base_url('assets/js/carousel.js') ?>"></script>
    <script src="<?= base_url('assets/js/ui.js') ?>"></script>
    <script src="<?= base_url('assets/js/cart.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>