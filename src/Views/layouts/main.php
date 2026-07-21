<?php use Fluxor\Core\View; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= View::yield('title', 'Fluxor App') ?></title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;line-height:1.6;background:#fff;min-height:100vh}
        .container{max-width:900px;margin:0 auto;padding:2rem}
        .logo{text-align:center;margin-bottom:1rem}
        .logo img{width:80px;height:80px}
        .card{background:#fff;border-radius:12px;padding:1rem;}
        .card h1{font-size:2rem;margin-bottom:1rem;color:#1a1a2e}
        .card p{color:#4a5568;margin-bottom:1rem}
        .card code{background:#f5f5f5;padding:0.2rem 0.5rem;border-radius:4px;font-family:monospace;font-size:0.9rem;color:#e53e3e}
        .card a{color:#667eea;text-decoration:none}
        .card a:hover{text-decoration:underline}
        footer{text-align:center;margin-top:2rem;color:#a0aec0;font-size:0.875rem}
        footer a{color:#667eea;text-decoration:none}
        footer a:hover{text-decoration:underline}
    </style>
    <?= View::yield('styles') ?>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="<?= asset('img/fluxor.svg') ?>" alt="Fluxor Logo">
        </div>
        <div class="card">
            <?= View::yield('content') ?>
        </div>
        <footer>
            <p>⚡ Fluxor PHP Framework | <a href="https://lizzyman04.com/fluxor-php">Docs</a> | <a href="https://github.com/lizzyman04/fluxor">GitHub</a></p>
        </footer>
    </div>
    <?= View::yield('scripts') ?>
</body>
</html>