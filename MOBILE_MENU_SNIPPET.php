<!-- Mobile Menu (Add this to main.php right after header) -->
<nav class="mobile-menu" id="mobileMenu" style="display: none; position: fixed; top: 60px; left: 0; right: 0; bottom: 0; background-color: var(--header-bg); z-index: 999; flex-direction: column; overflow-y: auto;">
    <div style="padding: 20px;">
        <!-- Search in Mobile Menu -->
        <div style="margin-bottom: 20px;">
            <input type="search" placeholder="Search..." class="input-dark" style="width: 100%; margin: 0;">
        </div>

        <!-- Main Navigation -->
        <nav style="display: flex; flex-direction: column; gap: 5px; margin-bottom: 20px;">
            <a href="/" class="mobile-menu-link" style="display: block; padding: 12px 15px; color: var(--text-main); text-decoration: none; border-radius: 6px; transition: all 0.2s ease;">
                🏠 Home
            </a>
            <a href="/products" class="mobile-menu-link" style="display: block; padding: 12px 15px; color: var(--text-main); text-decoration: none; border-radius: 6px; transition: all 0.2s ease;">
                🎮 Products
            </a>
            <a href="/articles" class="mobile-menu-link" style="display: block; padding: 12px 15px; color: var(--text-main); text-decoration: none; border-radius: 6px; transition: all 0.2s ease;">
                📰 Stories
            </a>
            <a href="/about" class="mobile-menu-link" style="display: block; padding: 12px 15px; color: var(--text-main); text-decoration: none; border-radius: 6px; transition: all 0.2s ease;">
                ℹ️ About
            </a>
            <a href="/contact" class="mobile-menu-link" style="display: block; padding: 12px 15px; color: var(--text-main); text-decoration: none; border-radius: 6px; transition: all 0.2s ease;">
                ✉️ Contact
            </a>
        </nav>

        <!-- Divider -->
        <hr style="border: none; border-top: 1px solid var(--border-color); margin: 20px 0;">

        <!-- Auth Section -->
        <?php if (session()->get('isLoggedIn')): ?>
            <div style="margin-bottom: 20px;">
                <a href="/account" class="mobile-menu-link" style="display: block; padding: 12px 15px; color: var(--text-main); text-decoration: none; border-radius: 6px; background-color: var(--card-bg); transition: all 0.2s ease; margin-bottom: 10px;">
                    👤 My Account
                </a>
                <a href="/account/orders" class="mobile-menu-link" style="display: block; padding: 12px 15px; color: var(--text-main); text-decoration: none; border-radius: 6px; transition: all 0.2s ease; margin-bottom: 10px;">
                    📦 My Orders
                </a>
                <a href="/logout" class="mobile-menu-link" style="display: block; padding: 12px 15px; color: var(--text-main); text-decoration: none; border-radius: 6px; transition: all 0.2s ease;">
                    🚪 Logout
                </a>
            </div>
        <?php else: ?>
            <div style="display: flex; gap: 10px;">
                <a href="/login" class="btn btn-secondary" style="flex: 1; text-align: center; padding: 10px; margin: 0;">Login</a>
                <a href="/register" class="btn btn-primary" style="flex: 1; text-align: center; padding: 10px; margin: 0;">Register</a>
            </div>
        <?php endif; ?>

        <!-- Footer Links in Menu -->
        <hr style="border: none; border-top: 1px solid var(--border-color); margin: 20px 0;">

        <div style="color: var(--text-muted); font-size: 0.85rem;">
            <a href="/privacy" style="display: block; padding: 8px 0; color: var(--text-muted);">Privacy Policy</a>
            <a href="/terms" style="display: block; padding: 8px 0; color: var(--text-muted);">Terms of Service</a>
            <a href="/faq" style="display: block; padding: 8px 0; color: var(--text-muted);">FAQ</a>
        </div>
    </div>
</nav>

<!-- Add this CSS to style.css for mobile menu visibility -->
<style>
    .mobile-menu.active {
        display: flex !important;
    }

    .mobile-menu-link:hover {
        background-color: var(--card-bg);
        color: var(--primary);
    }

    @media (min-width: 769px) {
        .mobile-menu {
            display: none !important;
        }

        .burger-icon {
            display: none !important;
        }
    }
</style>
