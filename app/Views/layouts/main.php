<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Playpass') ?></title>
    <style>
        /* Basic styling to approximate the provided designs. In a production
           implementation this would live in separate CSS files and use a
           design framework or utility classes. */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #0c0c0c;
            color: #ffffff;
        }
        header {
            background-color: #051429;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            justify-content: space-between;
        }
        header .logo {
            font-size: 1.4rem;
            font-weight: bold;
            color: #d8369f;
        }
        header nav a {
            color: #ffffff;
            margin-left: 15px;
            text-decoration: none;
            font-weight: 500;
        }
        main {
            padding: 20px;
        }
        footer {
            background-color: #051429;
            padding: 20px;
            color: #a0a0a0;
            font-size: 0.9rem;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            margin-top: 10px;
        }
        .btn-primary {
            background-color: #0d6efd;
        }
        .btn-secondary {
            background-color: #d8369f;
        }
        .error {
            color: #ff6b6b;
            font-size: 0.9rem;
        }
        .success {
            color: #4caf50;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">PLAYPASS</div>
        <nav>
            <a href="/">Home</a>
            <?php if (session()->get('logged_in')): ?>
                <a href="/account">Account</a>
                <a href="/logout">Logout</a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?= $this->renderSection('content') ?>
    </main>
    <footer>
        <p>&copy; <?= date('Y') ?> Playpass. All rights reserved.</p>
    </footer>
</body>
</html>