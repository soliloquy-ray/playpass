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
            
            <a href="<?= site_url('app') ?>" style="display: flex; align-items: center; gap: 5px; text-decoration: none;">
                <img src="<?= base_url('assets/logo.png') ?>" alt="Playpass" style="height: 24px;">
            </a>

            <nav class="desktop-nav">
                <a href="<?= site_url('app') ?>" class="desktop-nav-link <?= uri_string() == 'app' || uri_string() == 'app/home' ? 'active' : '' ?>">Home</a>
                <a href="<?= site_url('app/buy-now') ?>" class="desktop-nav-link <?= uri_string() == 'app/buy-now' ? 'active' : '' ?>">Buy Now</a>
                <a href="<?= site_url('app/stories') ?>" class="desktop-nav-link <?= uri_string() == 'app/stories' ? 'active' : '' ?>">Stories</a>
                <?php if (session()->get('logged_in')): ?>
                    <a href="<?= site_url('app/account') ?>" class="desktop-nav-link <?= uri_string() == 'app/account' ? 'active' : '' ?>">Account</a>
                <?php else: ?>
                    <a href="<?= site_url('app/login') ?>" class="desktop-nav-link <?= uri_string() == 'app/login' ? 'active' : '' ?>">Account</a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="header-right">
            <button class="icon-btn" style="background: none; border: none; color: white; font-size: 1.2rem;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            
            <!-- Cart Icon with Badge -->
            <button class="icon-btn cart-icon-btn" onclick="openCartSidebar()" style="background: none; border: none; color: white; font-size: 1.2rem; position: relative; cursor: pointer;">
                <i class="fa-solid fa-shopping-cart"></i>
                <span class="cart-badge" style="display: none; position: absolute; top: -8px; right: -8px; background: #d8369f; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; font-weight: 700; display: flex; align-items: center; justify-content: center;">0</span>
            </button>
            
            <?php if (session()->get('logged_in') && !empty($user)): ?>
            <!-- User Avatar (only shown when logged in) -->
            <a href="<?= site_url('app/account') ?>" class="user-avatar-link" style="display: flex; align-items: center; text-decoration: none;">
                <?php if (!empty($user['avatar_url'])): ?>
                    <img src="<?= esc($user['avatar_url']) ?>" alt="Profile" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #d8369f;">
                <?php else: ?>
                    <?php 
                        // Generate initials from user name
                        $firstName = $user['first_name'] ?? '';
                        $lastName = $user['last_name'] ?? '';
                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                        if (empty($initials)) {
                            $initials = strtoupper(substr($user['email'] ?? 'U', 0, 1));
                        }
                    ?>
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #d8369f, #051429); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: 700; border: 2px solid #d8369f;">
                        <?= esc($initials) ?>
                    </div>
                <?php endif; ?>
            </a>
            
            <a href="<?= site_url('app/logout') ?>" class="btn-signin">Sign Out</a>
            <?php else: ?>
            <a href="<?= site_url('app/login') ?>" class="btn-signin">Sign In</a>
            <?php endif; ?>
        </div>
    </div>

    <?= view('components/mobile_menu') ?>
</header>
