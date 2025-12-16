<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="account-page-container">
    <!-- User Profile Banner -->
    <?= view('components/user_profile_banner', ['user' => $user]) ?>
    
    <!-- Points and Vouchers Section -->
    <div class="points-vouchers-section">
        <?= view('components/points_earned_card', ['points' => $points ?? 0]) ?>
        <?= view('components/vouchers_card', ['vouchers' => $vouchers ?? []]) ?>
    </div>
    
    <!-- Purchase History Section -->
    <?= view('components/purchase_history', [
        'transactions' => $transactions ?? [],
        'filterType' => $filterType ?? 'all',
        'dateFrom' => $dateFrom ?? null,
        'dateTo' => $dateTo ?? null
    ]) ?>
</div>

<?= $this->endSection() ?>
