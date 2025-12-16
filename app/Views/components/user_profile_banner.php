<?php
// User Profile Banner Component
// Expected variables: $user (array with name, email, phone, avatar_url)
$userName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
if (empty($userName)) {
    $userName = $user['email'] ?? 'User';
}
$userEmail = $user['email'] ?? '';
$userPhone = $user['phone'] ?? '';
$avatarUrl = $user['avatar_url'] ?? null;
?>

<div class="user-profile-banner">
    <div class="profile-banner-content">
        <div class="profile-avatar-container">
            <?php if ($avatarUrl): ?>
                <img src="<?= esc($avatarUrl) ?>" alt="Profile" class="profile-avatar-img">
            <?php else: ?>
                <div class="profile-avatar-placeholder">
                    <?= strtoupper(substr($userName, 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h2 class="profile-name"><?= esc($userName) ?></h2>
            <p class="profile-email"><?= esc($userEmail) ?></p>
            <?php if ($userPhone): ?>
                <p class="profile-phone"><?= esc($userPhone) ?></p>
            <?php endif; ?>
        </div>
        <div class="profile-actions">
            <a href="/app/account/edit" class="btn-edit-profile">Edit Profile</a>
        </div>
    </div>
</div>

