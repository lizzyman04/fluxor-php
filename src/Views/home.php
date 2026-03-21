<?php use Fluxor\View; ?>
<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
<?= View::e($title) ?>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="card">
    <h1>🚀 <?= View::e($message) ?></h1>
    <p>Fluxor is a lightweight PHP framework with file-based routing, inspired by Next.js.</p>
    <p>Get started by editing <code>app/router/index.php</code> and <code>src/Views/home.php</code>.</p>
</div>
<?php View::endSection(); ?>