<?php use Fluxor\View; ?>
<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
<?= View::e($title) ?>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="card">
    <h1>About Fluxor</h1>
    <p>Fluxor is a modern PHP framework built with simplicity and performance in mind.</p>

    <h2>Key Features</h2>
    <ul>
        <li>🎯 File-based routing like Next.js</li>
        <li>💎 Elegant Flow syntax</li>
        <li>🚀 Blazing fast performance (boot under 10ms)</li>
        <li>🛡️ Security first (CSRF, XSS protection)</li>
        <li>📦 Zero dependencies</li>
        <li>🔧 Zero configuration</li>
        <li>🌍 Built-in .env support</li>
    </ul>

    <h2>Why Fluxor?</h2>
    <p>Fluxor was created for developers who want simplicity without sacrificing functionality. The code is transparent,
        there's no magic, and you can read everything.</p>

    <h2>Learn More</h2>
    <p>Visit the <a href="https://lizzyman04.github.io/fluxor-php">documentation</a> to learn more about building
        applications with Fluxor.</p>
</div>
<?php View::endSection(); ?>