<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Register</h1>

<?php if (isset($errors)): ?>
    <div class="error">
        <?php foreach ($errors as $field => $error): ?>
            <p><?= esc($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="/register" method="post">
    <?= csrf_field() ?>
    <div>
        <label for="name">Name</label><br>
        <input type="text" id="name" name="name" value="<?= esc(old('name')) ?>" required>
    </div>
    <div style="margin-top:10px;">
        <label for="birthdate">Birthdate (optional)</label><br>
        <input type="date" id="birthdate" name="birthdate" value="<?= esc(old('birthdate')) ?>">
    </div>
    <div style="margin-top:10px;">
        <label for="email">Email Address</label><br>
        <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" required>
    </div>
    <div style="margin-top:10px;">
        <label for="phone">Mobile Number</label><br>
        <input type="text" id="phone" name="phone" value="<?= esc(old('phone')) ?>">
    </div>
    <div style="margin-top:10px;">
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required>
    </div>
    <div style="margin-top:10px;">
        <label for="password_confirm">Confirm Password</label><br>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>
    <div style="margin-top:20px;">
        <p>What do you enjoy most?</p>
        <?php
            $options = [
                'Movies', 'Series', 'Drama', 'Cartoons', 'Documentaries', 'Sports',
                'Music', 'Podcast', 'Mobile Games', 'K-Drama', 'Anime', 'Reality Shows',
                'Events', 'Concerts', 'Talk Show'
            ];
            $selected = old('interests') ? explode(',', old('interests')) : [];
        ?>
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <?php foreach ($options as $opt): ?>
                <label style="background-color:#051429; padding:6px 12px; border-radius:20px; cursor:pointer;">
                    <input type="checkbox" name="interests[]" value="<?= $opt ?>" style="margin-right:5px;"
                        <?= in_array($opt, $selected) ? 'checked' : '' ?>>
                    <?= esc($opt) ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <div style="margin-top:10px;">
        <label>
            <input type="checkbox" name="terms" value="1" required>
            I agree to the Privacy Policy and Terms and Conditions of Playpass
        </label>
    </div>
    <div style="margin-top:20px;">
        <button type="submit" class="btn btn-secondary">Register</button>
    </div>
</form>

<?= $this->endSection() ?>