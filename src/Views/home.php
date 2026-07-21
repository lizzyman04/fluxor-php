<?php use Fluxor\Core\View; ?>
<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
<?= View::e($title) ?>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="card">
    <h1>🚀 <?= View::e($message) ?></h1>
    <p>Fluxor is a lightweight PHP framework with file-based routing, inspired by Next.js.</p>
    <p>Get started by editing <code>app/router/index.php</code> and <code>src/Views/home.php</code>.</p>

    <h2>📚 Documentation</h2>
    <p>Full documentation available at: <a href="https://lizzyman04.com/fluxor-php"
            target="_blank">lizzyman04.com/fluxor-php</a></p>

    <h2>📦 Need More Features?</h2>
    <p>Check out the full template with authentication, mailer, and uploader:</p>
    <p>Visit: <a href="https://github.com/lizzyman04/fluxor-mvc-template"
            target="_blank">github.com/lizzyman04/fluxor-mvc-template</a></p>
</div>
<?php View::endSection(); ?>