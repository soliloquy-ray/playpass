<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="max-width: 500px; margin: 60px auto; padding: 0 15px;">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 10px;">Welcome Back</h1>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">
            Login to your Playpass account
        </p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="identifier">Email or Mobile Number</label>
                <input type="text" id="identifier" name="identifier" class="input-dark" 
                       value="<?= esc(old('identifier')) ?>" placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="input-dark" 
                       placeholder="••••••••" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; text-transform: none; letter-spacing: normal;">
                    <input type="checkbox" name="remember" id="remember">
                    Remember me
                </label>
                <a href="#" style="color: var(--primary); font-size: 0.9rem;">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-large">
                Login
            </button>
        </form>

        <hr style="border: none; border-top: 1px solid var(--border-color); margin: 25px 0;">

        <p style="text-align: center; color: var(--text-muted);">
            Don't have an account?
            <a href="/register" style="color: var(--primary); font-weight: 700;">Register here</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>