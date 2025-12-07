<section class="customer-support">
    <div class="section-header">
        <h2><?= $title ?></h2>
        <p><?= $subtitle ?></p>
    </div>

    <div class="support-grid">
        <?php foreach ($supports as $support): ?>
            <div class="support-card">
                <div class="support-icon">
                    <?php
                        $icons = [
                            'chat' => '💬',
                            'email' => '✉️',
                            'phone' => '📞',
                            'faq' => '❓'
                        ];
                        echo $icons[$support['icon']] ?? '•';
                    ?>
                </div>
                <h3><?= $support['title'] ?></h3>
                <p><?= $support['description'] ?></p>
                <button class="btn btn-outline"><?= $support['cta'] ?></button>
            </div>
        <?php endforeach; ?>
    </div>
</section>
