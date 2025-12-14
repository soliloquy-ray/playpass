<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Playpass CMS</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f0f1a 0%, #151525 100%);
            padding: 20px;
        }
        .admin-login-card {
            background: linear-gradient(135deg, #151525 0%, #1a1a2e 100%);
            border: 1px solid rgba(216, 54, 159, 0.3);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }
        .admin-login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-login-logo img {
            height: 40px;
        }
        .admin-login-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
        }
        .admin-login-subtitle {
            text-align: center;
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        .admin-login-form .form-group {
            margin-bottom: 20px;
        }
        .admin-login-form label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .admin-login-form input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(26, 26, 38, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            color: var(--text-main);
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .admin-login-form input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(216, 54, 159, 0.15);
        }
        .admin-login-form input::placeholder {
            color: var(--text-muted);
        }
        .admin-login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary) 0%, #a01570 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
        }
        .admin-login-btn:hover {
            box-shadow: 0 4px 20px rgba(216, 54, 159, 0.4);
            transform: translateY(-2px);
        }
        .admin-login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .admin-login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }
        .admin-login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="admin-login-logo">
                <img src="/assets/logo.png" alt="Playpass">
            </div>
            
            <h1 class="admin-login-title">Admin Login</h1>
            <p class="admin-login-subtitle">Access the Content Management System</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" style="margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/admin/login" method="POST" class="admin-login-form">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="admin@playpass.ph" 
                        value="<?= old('email') ?>"
                        required 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        required
                    >
                </div>

                <button type="submit" class="admin-login-btn">
                    <i class="fas fa-sign-in-alt"></i> Sign In to Admin Panel
                </button>
            </form>

            <div class="admin-login-footer">
                <a href="/app">
                    <i class="fas fa-arrow-left"></i> Back to Main Site
                </a>
            </div>
        </div>
    </div>
</body>
</html>

