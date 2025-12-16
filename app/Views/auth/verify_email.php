<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="login-container">
    <h1 class="login-title">VERIFY YOUR EMAIL</h1>

    <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">
        We've sent a 6-digit verification code to <strong><?= esc($email ?? 'your email') ?></strong>
    </p>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <?= esc(session()->getFlashdata('error')) ?>
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

    <form action="<?= site_url('app/verify-email') ?>" method="post" class="login-form">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">

        <div class="form-group">
            <label for="otp" style="text-align: center; display: block; margin-bottom: 10px; color: white; font-weight: 600;">
                Enter Verification Code
            </label>
            <input type="text" id="otp" name="otp" class="login-input" 
                   placeholder="000000" maxlength="6" pattern="[0-9]{6}" 
                   style="text-align: center; font-size: 1.5rem; letter-spacing: 0.5rem; font-weight: 600;" 
                   required autofocus>
            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; text-align: center; margin-top: 5px;">
                6-digit code
            </small>
        </div>

        <button type="submit" class="btn-login">
            Verify Email
        </button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <form action="<?= site_url('app/resend-verification') ?>" method="post" style="display: inline;">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">
            <button type="submit" class="btn-resend" style="background: none; border: none; color: #3b82f6; cursor: pointer; text-decoration: underline; font-size: 0.9rem;">
                Resend Code
            </button>
        </form>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="<?= site_url('app/login') ?>" style="color: #3b82f6; font-size: 0.9rem;">Back to Login</a>
    </div>
</div>

<script>
// Auto-focus and format OTP input
document.getElementById('otp').addEventListener('input', function(e) {
    // Only allow numbers
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Auto-submit when 6 digits are entered
    if (this.value.length === 6) {
        // Small delay for better UX
        setTimeout(() => {
            this.form.submit();
        }, 100);
    }
});

// Paste handling
document.getElementById('otp').addEventListener('paste', function(e) {
    e.preventDefault();
    const paste = (e.clipboardData || window.clipboardData).getData('text');
    const numbers = paste.replace(/[^0-9]/g, '').slice(0, 6);
    this.value = numbers;
    
    if (numbers.length === 6) {
        setTimeout(() => {
            this.form.submit();
        }, 100);
    }
});
</script>

<?= $this->endSection() ?>
