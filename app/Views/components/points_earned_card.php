<?php
// Points Earned Card Component
// Expected variables: $points (integer)
?>

<div class="points-earned-card">
    <h3 class="points-card-title">POINTS EARNED</h3>
    <div class="points-display">
        <span class="points-number"><?= number_format($points ?? 0) ?></span>
        <span class="points-label">POINTS</span>
    </div>
    <button class="btn-redeem-points" onclick="window.location.href='<?= site_url('app/account?action=redeem') ?>'">Redeem</button>
</div>

