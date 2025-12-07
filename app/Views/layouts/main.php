<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Playpass') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <!-- 1. Top CTA Bar -->
    <div class="top-cta">
        GET 50% OFF ON YOUR FIRST TOP-UP!
    </div>

    <!-- 2. Mobile Menu Bar -->
    <header>
        <div class="header-left">
            <div class="burger-icon" id="burger-icon" aria-label="Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </div>
            <a href="/" style="font-weight: 800; font-size: 1.1rem; color: var(--primary);">PLAYPASS</a>
        </div>

        <div class="header-right">
            <!-- Search Icon -->
            <button class="icon-btn" aria-label="Search">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
            
            <?php if (session()->get('logged_in')): ?>
                <div class="points-badge">
                    <span>⚡</span> 
                    <?= number_format(session()->get('balance') ?? 0) ?>
                </div>
                <a href="/account" class="icon-btn" aria-label="Account">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </a>
            <?php else: ?>
                <a href="/login" style="color:#d8369f; text-decoration:none; font-size:0.85rem; font-weight:700; margin-left:5px;">LOGIN</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Mobile Menu -->
    <?= view('components/mobile_menu') ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= view_cell('App\Cells\FooterCell::render') ?>

    <script src="/assets/js/carousel.js"></script>
    <script src="/assets/js/ui.js"></script>
</body>
</html>