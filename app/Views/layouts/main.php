<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Playpass') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="position: relative;">

    <div class="top-cta">
        <i class="fa-solid fa-bolt" style="color: #ffd700;"></i> CODE AGAD. INSTANT DELIVERY VIA SMS/EMAIL!
    </div>

    <header>
        <div class="header-left">
            <div class="burger-icon" id="burger-icon">
                <i class="fa-solid fa-bars" style="font-size: 1.5rem;"></i>
            </div>
            
            <a href="/" style="display: flex; align-items: center; gap: 5px; text-decoration: none;">
                <img src="/assets/logo.png" alt="Playpass" style="height: 24px;"> <!-- <span style="font-weight: 800; font-size: 1.4rem; color: #3b82f6; font-style: italic;">PLAYPASS</span> -->
            </a>
        </div>

        <div class="header-right">
            <button class="icon-btn" style="background: none; border: none; color: white; font-size: 1.2rem;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            
            <div class="gold-star-icon">
                <img src="/assets/star-icon.png" alt="Rw" style="height: 28px;"> </div>

            <?php if (session()->get('logged_in')): ?>
                 <?php else: ?>
                <a href="/app/login" class="btn-signin">Sign In</a>
            <?php endif; ?>
        </div>

    <?= view('components/mobile_menu') ?>
    </header>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= view_cell('App\Cells\FooterCell::render') ?>

    <script src="/assets/js/carousel.js"></script>
    <script src="/assets/js/ui.js"></script>
</body>
</html>