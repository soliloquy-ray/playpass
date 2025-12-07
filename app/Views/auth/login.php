<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Login</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p class="success"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<?php if (isset($errors)): ?>
    <div class="error">
        <?php foreach ($errors as $error): ?>
            <p><?= esc($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="/login" method="post">
    <?= csrf_field() ?>
    <div>
        <label for="identifier">Email or mobile number</label><br>
        <input type="text" id="identifier" name="identifier" value="<?= esc(old('identifier')) ?>" required>
    </div>
    <div style="margin-top:10px;">
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required>
    </div>
    <div style="margin-top:20px;">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
    <div style="margin-top:10px;">
        <a href="/register" class="btn btn-secondary">Register</a>
    </div>
</form>

<?= $this->endSection() ?>