<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Playpass') ?></title>
    <style>
        :root {
            --bg-color: #0c0c0c;
            --card-bg: #1a1a1a;
            --primary: #d8369f;
            --text-main: #ffffff;
            --text-muted: #a0a0a0;
            --header-bg: #051429;
        }
        
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            padding-bottom: 60px; /* Space for footer */
        }

        /* 1. Top CTA Bar */
        .top-cta {
            background-color: var(--primary);
            color: white;
            text-align: center;
            padding: 8px 10px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* 2. Mobile Header */
        header {
            background-color: var(--header-bg);
            height: 60px;
            padding: 0 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        .header-left { display: flex; align-items: center; gap: 15px; }
        .header-right { display: flex; align-items: center; gap: 15px; }

        .burger-icon {
            font-size: 1.4rem;
            cursor: pointer;
            color: #fff;
            display: flex;
            align-items: center;
        }

        .icon-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            display: flex;
            align-items: center;
        }

        .points-badge {
            background-color: rgba(255, 215, 0, 0.1);
            border: 1px solid rgba(255, 215, 0, 0.3);
            color: #ffd700;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        main {
            /* No padding here so the Carousel can be full width */
            width: 100%;
            overflow-x: hidden; 
        }

        footer {
            text-align: center;
            padding: 30px 20px;
            color: var(--text-muted);
            font-size: 0.8rem;
            background-color: #080808;
            margin-top: 40px;
            border-top: 1px solid #222;
        }
        
        /* Utility Classes */
        .container { padding: 0 15px; }
        .section-title { 
            font-size: 1.1rem; 
            margin: 25px 15px 15px 15px; 
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Links */
        a { color: inherit; text-decoration: none; }
    </style>
</head>
<body>

    <!-- 1. Top CTA Bar -->
    <div class="top-cta">
        GET 50% OFF ON YOUR FIRST TOP-UP!
    </div>

    <!-- 2. Mobile Menu Bar -->
    <header>
        <div class="header-left">
            <div class="burger-icon" aria-label="Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </div>
        </div>

        <div class="header-right">
            <!-- Search Icon -->
            <button class="icon-btn" aria-label="Search">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
            
            <?php if (session()->get('logged_in')): ?>
                <div class="points-badge">
                    <span>⚡</span> 
                    <?= number_format(session()->get('balance') ?? 0) ?>
                </div>
                <a href="/account" class="icon-btn" aria-label="Account">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </a>
            <?php else: ?>
                <a href="/login" style="color:#d8369f; text-decoration:none; font-size:0.85rem; font-weight:700; margin-left:5px;">LOGIN</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Playpass. All rights reserved.</p>
    </footer>

</body>
</html>