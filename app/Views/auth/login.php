<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="login-container">
    <h1 class="login-title">LOGIN</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($errors) && is_array($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
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

    <form action="<?= site_url('app/login') ?>" method="post" class="login-form">
        <?= csrf_field() ?>

        <div class="form-group">
            <input type="text" id="identifier" name="identifier" class="login-input" 
                   value="<?= esc(old('identifier')) ?>" placeholder="Email or mobile number" required>
        </div>

        <div class="form-group">
            <input type="password" id="password" name="password" class="login-input" 
                   placeholder="Password" required>
        </div>

        <div class="social-login-buttons">
            <a href="<?= site_url('app/auth/facebook') ?>" class="btn-social btn-facebook">
                <i class="fa-brands fa-facebook-f"></i>
                <span>Continue with Facebook</span>
            </a>
            <a href="<?= site_url('app/auth/google') ?>" class="btn-social btn-google">
                <svg class="google-icon" width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Continue with Google</span>
            </a>
        </div>

        <div class="forgot-password-wrapper">
            <a href="<?= site_url('app/forgot-password') ?>" class="forgot-password-link">Forgot Password</a>
        </div>

        <button type="submit" class="btn-login">
            Login
        </button>

        <a href="<?= site_url('app/register') ?>" class="btn-register">
            Register
        </a>
    </form>
</div>

<?= $this->endSection() ?>
