<footer class="site-footer-custom">
    <div class="footer-top">
        <div class="footer-content-wrapper">
            <div class="powered-by-label">Powered by</div>
            
            <div class="megamobile-logo">
                <svg width="180" height="40" viewBox="0 0 200 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 25 C 20 10, 50 10, 50 25" stroke="white" stroke-width="4" fill="none"/>
                    <text x="55" y="35" font-family="Arial, sans-serif" font-weight="bold" font-size="28" fill="white">megaMobile</text>
                    <path d="M20 35 Q 35 45 50 35" stroke="white" stroke-width="4" fill="none"/>
                </svg>
            </div>

            <nav class="footer-links">
                <a href="<?= site_url('privacy') ?>">Privacy Policy</a>
                <a href="<?= site_url('terms') ?>">Terms and Conditions</a>
                <a href="<?= site_url('faq') ?>">FAQ</a>
            </nav>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-address">
            <?php 
            $companyModel = new \App\Models\CompanyInfoModel();
            $companyInfo = $companyModel->getAll();
            $address = $companyInfo['address'] ?? '';
            $phone = $companyInfo['phone'] ?? '';
            $copyright = $companyInfo['copyright'] ?? '© Copyright 2024 Megamobile, Inc. All Rights Reserved';
            ?>
            <?= nl2br(esc($address)) ?><br>
            call: <?= esc($phone) ?>
        </div>
        
        <div class="footer-copyright">
            <?= esc($copyright) ?>
        </div>

        <div class="footer-playpass-logo">
            <span style="color: #ff0066; font-size: 1.2rem; margin-right: 2px;">❖</span>
            <span style="color: #3b82f6; font-weight: 800; font-size: 1.2rem; font-style: italic;">PLAYPASS</span>
        </div>
    </div>
</footer>
