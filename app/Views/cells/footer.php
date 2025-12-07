<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Logo & Tagline Section -->
            <div class="footer-brand">
                <h3 class="footer-logo"><?= $logo ?></h3>
                <p><?= $tagline ?></p>
            </div>

            <!-- Links Columns -->
            <?php foreach ($columns as $column): ?>
                <div class="footer-column">
                    <h4><?= $column['title'] ?></h4>
                    <ul>
                        <?php foreach ($column['links'] as $link): ?>
                            <li><a href="<?= $link['url'] ?>"><?= $link['text'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Social Media -->
        <div class="footer-social">
            <h4>Follow Us</h4>
            <div class="social-links">
                <?php foreach ($social as $link): ?>
                    <a href="<?= $link['url'] ?>" class="social-icon" title="<?= ucfirst($link['icon']) ?>" target="_blank" rel="noopener">
                        <?php
                            $icons = [
                                'facebook' => 'f',
                                'twitter' => '𝕏',
                                'instagram' => '📷',
                                'discord' => '🎮'
                            ];
                            echo $icons[$link['icon']] ?? '•';
                        ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
        <p><?= $copyright ?></p>
    </div>
</footer>
