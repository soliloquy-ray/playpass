<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div style="max-width: 600px; margin: 40px auto; padding: 0 15px;">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 10px;">Create Your Account</h1>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">
            Join Playpass to access exclusive content
        </p>

        <?php if (isset($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $field => $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="input-dark" 
                       value="<?= esc(old('name')) ?>" placeholder="John Doe" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="input-dark" 
                           value="<?= esc(old('email')) ?>" placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label for="phone">Mobile Number</label>
                    <input type="text" id="phone" name="phone" class="input-dark" 
                           value="<?= esc(old('phone')) ?>" placeholder="09xxxxxxxxx">
                </div>
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate (Optional)</label>
                <input type="date" id="birthdate" name="birthdate" class="input-dark" 
                       value="<?= esc(old('birthdate')) ?>">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="input-dark" 
                           placeholder="••••••••" required>
                    <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 5px; display: block;">
                        At least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char
                    </small>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="input-dark" 
                           placeholder="••••••••" required>
                </div>
            </div>

            <!-- Interests -->
            <div class="form-group">
                <label>What are your interests?</label>
                <?php
                    $options = [
                        'Movies', 'Series', 'Drama', 'Cartoons', 'Documentaries', 'Sports',
                        'Music', 'Podcast', 'Mobile Games', 'K-Drama', 'Anime', 'Reality Shows',
                        'Events', 'Concerts', 'Talk Show'
                    ];
                    $selected = old('interests') ? (is_array(old('interests')) ? old('interests') : explode(',', old('interests'))) : [];
                ?>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                    <?php foreach ($options as $opt): ?>
                        <label style="display: flex; align-items: center; gap: 8px; background-color: var(--secondary); padding: 8px 14px; border-radius: 20px; cursor: pointer; border: 1px solid transparent; transition: all 0.2s ease;">
                            <input type="checkbox" name="interests[]" value="<?= $opt ?>"
                                <?= in_array($opt, $selected) ? 'checked' : '' ?>>
                            <span><?= esc($opt) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Terms -->
            <div style="margin-bottom: 25px;">
                <label style="display: flex; align-items: flex-start; gap: 12px; font-weight: normal; text-transform: none; letter-spacing: normal;">
                    <input type="checkbox" name="terms" value="1" style="margin-top: 2px; flex-shrink: 0;" required>
                    <span style="font-size: 0.9rem;">
                        I agree to the <a href="#" style="color: var(--primary);">Privacy Policy</a> and 
                        <a href="#" style="color: var(--primary);">Terms and Conditions</a> of Playpass
                    </span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-large">
                Create Account
            </button>
        </form>

        <hr style="border: none; border-top: 1px solid var(--border-color); margin: 25px 0;">

        <p style="text-align: center; color: var(--text-muted);">
            Already have an account?
            <a href="/login" style="color: var(--primary); font-weight: 700;">Login here</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>