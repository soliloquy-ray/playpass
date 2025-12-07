<div class="mobile-menu" id="mobile-menu" style="
    position: fixed;
    left: -100%;
    top: 60px;
    width: 80%;
    height: calc(100vh - 60px);
    background: var(--card-bg);
    transition: left 0.3s ease;
    z-index: 999;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
">
    <!-- Menu Close Button -->
    <button class="mobile-menu-close" style="
        align-self: flex-end;
        background: none;
        border: none;
        color: var(--text-main);
        font-size: 1.5rem;
        padding: 15px 20px;
        cursor: pointer;
    ">
        ✕
    </button>

    <!-- Menu Items -->
    <nav style="display: flex; flex-direction: column; gap: 0; flex: 1;">
        <a href="/" class="mobile-menu-item" style="
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        ">
            🏠 Home
        </a>
        <a href="/products" class="mobile-menu-item" style="
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        ">
            🛍️ Products
        </a>
        <a href="/products?category=games" class="mobile-menu-item" style="
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        ">
            🎮 Games
        </a>
        <a href="/products?category=streaming" class="mobile-menu-item" style="
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        ">
            📺 Streaming
        </a>

        <?php if (session()->get('logged_in')): ?>
            <hr style="margin: 10px 0; border: none; border-top: 1px solid var(--border-color);">
            
            <a href="/account" class="mobile-menu-item" style="
                padding: 15px 20px;
                border-bottom: 1px solid var(--border-color);
                color: var(--text-main);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 10px;
            ">
                👤 My Account
            </a>
            <a href="/account/orders" class="mobile-menu-item" style="
                padding: 15px 20px;
                border-bottom: 1px solid var(--border-color);
                color: var(--text-main);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 10px;
            ">
                📦 My Orders
            </a>
            <a href="/account/subscriptions" class="mobile-menu-item" style="
                padding: 15px 20px;
                border-bottom: 1px solid var(--border-color);
                color: var(--text-main);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 10px;
            ">
                ⭐ Subscriptions
            </a>
            <a href="/logout" class="mobile-menu-item" style="
                padding: 15px 20px;
                color: var(--danger);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 10px;
                margin-top: auto;
            ">
                🚪 Logout
            </a>
        <?php else: ?>
            <div style="margin-top: auto; padding: 20px; display: flex; flex-direction: column; gap: 10px;">
                <a href="/login" class="btn btn-secondary btn-large">
                    Login
                </a>
                <a href="/register" class="btn btn-primary btn-large">
                    Register
                </a>
            </div>
        <?php endif; ?>
    </nav>
</div>

<!-- Mobile Menu Backdrop -->
<div class="mobile-menu-backdrop" id="mobile-menu-backdrop" style="
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    z-index: 998;
"></div>

<style>
.mobile-menu.active {
    left: 0;
}

.mobile-menu-backdrop.active {
    display: block;
}

@media (min-width: 769px) {
    .mobile-menu,
    .mobile-menu-backdrop {
        display: none !important;
    }
}
</style>
