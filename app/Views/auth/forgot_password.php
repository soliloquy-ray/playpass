<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="login-container">
    <h1 class="login-title">FORGOT PASSWORD</h1>

    <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">
        Enter your email address and we'll send you a link to reset your password.
    </p>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error">
            <?php 
            $flashErrors = session()->getFlashdata('errors');
            if (is_array($flashErrors)):
                foreach ($flashErrors as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach;
            else: ?>
                <p><?= esc($flashErrors) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('app/forgot-password') ?>" method="post" class="login-form">
        <?= csrf_field() ?>

        <div class="form-group">
            <input type="email" id="email" name="email" class="login-input" 
                   value="<?= esc(old('email')) ?>" placeholder="Enter your email" required>
        </div>

        <button type="submit" class="btn-login">
            Send Reset Link
        </button>

        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= site_url('app/login') ?>" style="color: #3b82f6; font-size: 0.9rem;">Back to Login</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

