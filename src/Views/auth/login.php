<?php use Fluxor\View; ?>

<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
Login - Fluxor App
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="/auth/login">
        <?= View::csrfField() ?>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
<?php View::endSection(); ?>