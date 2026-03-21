<?php use Fluxor\View; ?>
<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
About Fluxor
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="card">
    <h1>About Fluxor</h1>
    <p>Fluxor is a modern PHP framework built with simplicity and performance in mind.</p>
    <h2>Key Features</h2>
    <ul>
        <li>🎯 File-based routing like Next.js</li>
        <li>💎 Elegant Flow syntax</li>
        <li>🚀 Blazing fast performance</li>
        <li>🛡️ Security first (CSRF, XSS protection)</li>
        <li>📦 Zero dependencies</li>
    </ul>
</div>
<?php View::endSection(); ?>