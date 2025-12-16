<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="register-container">
    <h1 class="register-title">REGISTER</h1>

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

    <form action="<?= site_url('app/register') ?>" method="post" class="register-form">
            <?= csrf_field() ?>

            <div class="form-group">
            <input type="text" id="name" name="name" class="register-input" 
                   value="<?= esc(old('name')) ?>" placeholder="Name" required>
            </div>

                <div class="form-group">
            <input type="date" id="birthdate" name="birthdate" class="register-input" 
                   value="<?= esc(old('birthdate')) ?>" placeholder="Birthday (optional)">
                </div>

                <div class="form-group">
            <input type="password" id="password" name="password" class="register-input" 
                   placeholder="Password" required>
            </div>

            <div class="form-group">
            <input type="email" id="email" name="email" class="register-input" 
                   value="<?= esc(old('email')) ?>" placeholder="Email Address" required>
            </div>

                <div class="form-group">
            <input type="text" id="phone" name="phone" class="register-input" 
                   value="<?= esc(old('phone')) ?>" placeholder="Mobile Number">
                </div>

                <div class="form-group">
            <input type="password" id="password_confirm" name="password_confirm" class="register-input" 
                   placeholder="Confirm Password" required>
            </div>

        <!-- What do you enjoy most? Section -->
        <div class="interests-section">
            <h2 class="interests-title">What do you enjoy most?</h2>
                <?php
                $interestOptions = [
                    'Movies', 'Series', 'Drama',
                    'Cartoons', 'Documentaries', 'Sports',
                    'Music', 'Podcast', 'Mobile Games',
                    'K-Drama', 'Anime', 'Reality Shows',
                        'Events', 'Concerts', 'Talk Show'
                    ];
                $selectedInterests = old('interests') ? (is_array(old('interests')) ? old('interests') : explode(',', old('interests'))) : [];
                ?>
            <div class="interests-grid">
                <?php foreach ($interestOptions as $interest): ?>
                    <label class="interest-button <?= in_array($interest, $selectedInterests) ? 'checked' : '' ?>">
                        <input type="checkbox" name="interests[]" value="<?= esc($interest) ?>"
                            <?= in_array($interest, $selectedInterests) ? 'checked' : '' ?>>
                        <span><?= esc($interest) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

        <!-- Terms and Conditions -->
        <div class="terms-section">
            <label class="terms-checkbox">
                <input type="checkbox" name="terms" value="1" required>
                <span>I agree to the <a href="#" class="terms-link">Privacy Policy</a> and <a href="#" class="terms-link">Terms and Conditions</a> of Playpass</span>
                </label>
            </div>

        <button type="submit" class="btn-register-submit">
            Register
            </button>
        </form>
</div>

<style>
.register-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 40px 20px 60px;
}

.register-title {
    text-align: center;
    font-size: 2rem;
    font-weight: 800;
    color: white;
    margin-bottom: 40px;
    letter-spacing: 2px;
}

.register-form .form-group {
    margin-bottom: 15px;
}

.register-input {
    width: 100%;
    padding: 14px 16px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 0.95rem;
    color: #333;
    transition: border-color 0.2s ease;
    font-family: inherit;
}

.register-input::placeholder {
    color: #999;
}

.register-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Date input styling */
.register-input[type="date"] {
    position: relative;
    color: #333;
}

.register-input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 0.6;
}

.register-input[type="date"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
}

/* Interests Section */
.interests-section {
    margin: 30px 0;
}

.interests-title {
    text-align: center;
    font-size: 1.2rem;
    font-weight: 600;
    color: white;
    margin-bottom: 20px;
    letter-spacing: 0.5px;
}

.interests-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 10px;
}

.interest-button {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 14px;
    background-color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    font-weight: 500;
    color: #333;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.interest-button:hover {
    background-color: #f5f5f5;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

.interest-button input[type="checkbox"] {
    display: none;
}

.interest-button:has(input[type="checkbox"]:checked) {
    background-color: #eff6ff;
    border: 2px solid #3b82f6;
}


.interest-button span {
    display: block;
    text-align: center;
}

/* Terms Section */
.terms-section {
    margin: 25px 0;
}

.terms-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    color: white;
    font-size: 0.9rem;
    line-height: 1.5;
}

.terms-checkbox input[type="checkbox"] {
    margin-top: 3px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    flex-shrink: 0;
    accent-color: #3b82f6;
}

.terms-link {
    color: #3b82f6;
    text-decoration: underline;
}

.terms-link:hover {
    color: #2563eb;
}

/* Register Button */
.btn-register-submit {
    width: 100%;
    padding: 14px 20px;
    background-color: #ec4899;
    background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-top: 10px;
    letter-spacing: 0.5px;
}

.btn-register-submit:hover {
    background: linear-gradient(135deg, #db2777 0%, #c026d3 100%);
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
    transform: translateY(-2px);
}

.btn-register-submit:active {
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 768px) {
    .register-container {
        padding: 30px 15px 50px;
    }

    .register-title {
        font-size: 1.6rem;
        margin-bottom: 30px;
    }

    .interests-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .interest-button {
        padding: 8px 12px;
        font-size: 0.85rem;
    }

    .interests-title {
        font-size: 1.1rem;
        margin-bottom: 15px;
    }

    .terms-checkbox {
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .interests-grid {
        grid-template-columns: 1fr;
    }

    .register-title {
        font-size: 1.4rem;
    }
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #10b981;
    color: white;
}

.alert-error {
    background-color: #ef4444;
    color: white;
}

.alert p {
    margin: 0;
    font-size: 0.9rem;
}
</style>

<script>
// Update interest button styling when checkbox changes
document.querySelectorAll('.interest-button input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            this.closest('.interest-button').classList.add('checked');
        } else {
            this.closest('.interest-button').classList.remove('checked');
        }
    });
});
</script>

<?= $this->endSection() ?>
