<?php if (!empty($banner) && $banner['is_active']): ?>
<div class="top-cta">
    <?php if (!empty($banner['icon'])): ?>
        <i class="fa-solid <?= esc($banner['icon']) ?>" style="color: #ffd700;"></i>
    <?php else: ?>
        <i class="fa-solid fa-bolt" style="color: #ffd700;"></i>
    <?php endif; ?>
    <?= esc($banner['text']) ?>
</div>
<?php endif; ?>

<header>
    <div class="header-content">
        <div class="header-left">
            <div class="burger-icon" id="burger-icon">
                <i class="fa-solid fa-bars" style="font-size: 1.5rem;"></i>
            </div>
            
            <a href="/app" style="display: flex; align-items: center; gap: 5px; text-decoration: none;">
                <img src="/assets/logo.png" alt="Playpass" style="height: 24px;">
            </a>

            <nav class="desktop-nav">
                <a href="/app" class="desktop-nav-link <?= uri_string() == 'app' || uri_string() == 'app/home' ? 'active' : '' ?>">Home</a>
                <a href="/app/buy-now" class="desktop-nav-link <?= uri_string() == 'app/buy-now' ? 'active' : '' ?>">Buy Now</a>
                <a href="/app/stories" class="desktop-nav-link <?= uri_string() == 'app/stories' ? 'active' : '' ?>">Stories</a>
                <?php if (session()->get('logged_in')): ?>
                    <a href="/app/account" class="desktop-nav-link <?= uri_string() == 'app/account' ? 'active' : '' ?>">Account</a>
                <?php else: ?>
                    <a href="/app/login" class="desktop-nav-link <?= uri_string() == 'app/login' ? 'active' : '' ?>">Account</a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="header-right">
            <button class="icon-btn" style="background: none; border: none; color: white; font-size: 1.2rem;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            
            <div class="gold-star-icon">
                <img src="/assets/star-icon.png" alt="Rw" style="height: 28px;">
            </div>

            <?php if (session()->get('logged_in')): ?>
                <a href="/app/logout" class="btn-signin">Sign Out</a>
            <?php else: ?>
                <a href="/app/login" class="btn-signin">Sign In</a>
            <?php endif; ?>
        </div>
    </div>

    <?= view('components/mobile_menu') ?>
</header>
