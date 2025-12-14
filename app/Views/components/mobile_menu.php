<div id="mobile-menu-backdrop" class="mobile-menu-backdrop"></div>

<div id="mobile-menu" class="mobile-menu">
    <nav class="mobile-nav">
        <a href="/app" class="mobile-link <?= uri_string() == 'app' ? 'active' : '' ?>">Home</a>
        <a href="/app/buy-now" class="mobile-link <?= uri_string() == 'app/buy-now' ? 'active' : '' ?>">Buy Now</a>
        <a href="/app/stories" class="mobile-link <?= uri_string() == 'app/stories' ? 'active' : '' ?>">Stories</a>
        
        <?php if (session()->get('logged_in')): ?>
            <a href="/app/account" class="mobile-link">Account</a>
            <a href="/app/logout" class="mobile-link">Logout</a>
        <?php else: ?>
            <a href="/app/login" class="mobile-link">Account</a> <?php endif; ?>
    </nav>
</div>