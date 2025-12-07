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
            <div class="burger-icon" aria-label="Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </div>
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

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <footer>
        <div class="container">
            <h4 style="margin-bottom: 15px;">Playpass</h4>
            <p style="margin-bottom: 15px;">The ultimate platform for digital products and subscriptions.</p>
            <div style="display: flex; gap: 15px; justify-content: center; margin-bottom: 20px; flex-wrap: wrap;">
                <a href="/" style="font-size: 0.9rem;">Home</a>
                <a href="/products" style="font-size: 0.9rem;">Products</a>
                <a href="/about" style="font-size: 0.9rem;">About</a>
                <a href="/contact" style="font-size: 0.9rem;">Contact</a>
                <a href="/privacy" style="font-size: 0.9rem;">Privacy</a>
            </div>
            <p style="border-top: 1px solid var(--border-color); padding-top: 15px; margin-top: 15px;">
                &copy; <?= date('Y') ?> Playpass. All rights reserved. | Made with ❤️ for gamers.
            </p>
        </div>
    </footer>

    <script src="/assets/js/carousel.js"></script>
    <script src="/assets/js/ui.js"></script>
</body>
</html>