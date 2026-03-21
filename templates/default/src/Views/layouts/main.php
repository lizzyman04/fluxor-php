<?php use Fluxor\View; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= View::yield('title', 'Fluxor App') ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        nav { background: #4f46e5; color: white; padding: 1rem; }
        nav a { color: white; text-decoration: none; margin-right: 1rem; }
        nav a:hover { text-decoration: underline; }
        main { min-height: 80vh; padding: 2rem 0; }
        footer { background: #f3f4f6; text-align: center; padding: 1rem; margin-top: 2rem; }
        .card { background: white; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 1rem 0; }
        .btn { display: inline-block; background: #4f46e5; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #4338ca; }
    </style>
    <?= View::yield('styles') ?>
</head>
<body>
    <nav>
        <div class="container">
            <a href="/">Home</a>
            <a href="/about">About</a>
            <a href="/contact">Contact</a>
        </div>
    </nav>
    <main>
        <div class="container">
            <?= View::yield('content') ?>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Fluxor PHP Framework. All rights reserved.</p>
        </div>
    </footer>
    <?= View::yield('scripts') ?>
</body>
</html>