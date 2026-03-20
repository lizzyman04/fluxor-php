<?php use Fluxor\View; ?>

<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
Home - <?= $title ?? 'Fluxor' ?>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="container">
    <h1>🚀 <?= $message ?? 'Welcome' ?></h1>
    
    <div class="card">
        <h2>Quick Links</h2>
        <ul>
            <li><a href="/api/hello?name=Fluxor">Test API</a></li>
            <li><a href="/auth/login">Login Page</a></li>
            <li><a href="https://github.com/lizzyman04/fluxor" target="_blank">Documentation</a></li>
        </ul>
    </div>
</div>
<?php View::endSection(); ?>