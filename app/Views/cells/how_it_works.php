<section class="how-it-works">
    <div class="section-header">
        <h2><?= $title ?></h2>
        <p><?= $subtitle ?></p>
    </div>

    <div class="steps-container">
        <?php foreach ($steps as $index => $step): ?>
            <div class="step-card <?= $index === 0 ? 'active' : '' ?>">
                <div class="step-number"><?= $step['number'] ?></div>
                <h3 class="step-title"><?= $step['title'] ?></h3>
                <p class="step-description"><?= $step['description'] ?></p>
            </div>
            <?php if ($index < count($steps) - 1): ?>
                <div class="step-connector"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
