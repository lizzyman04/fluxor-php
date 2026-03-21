<?php use Fluxor\View; ?>
<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
<?= View::e($title) ?>
<?php View::endSection(); ?>

<?php View::section('styles'); ?>
<style>
    .login-container {
        max-width: 400px;
        margin: 0 auto;
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .btn {
        width: 100%;
        padding: 0.75rem;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn:hover {
        background: #4338ca;
    }

    .error {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .remember {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .remember input {
        width: auto;
    }

    .signup-link {
        text-align: center;
        margin-top: 1rem;
    }

    .signup-link a {
        color: #4f46e5;
        text-decoration: none;
    }

    .signup-link a:hover {
        text-decoration: underline;
    }
</style>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
<div class="login-container">
    <h1>Login</h1>

    <?php if (isset($error)): ?>
        <div class="error"
            style="background: #fee; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem; text-align: center;">
            <?= View::e($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/auth/login">
        <?= View::csrfField() ?>
        <input type="hidden" name="redirect" value="<?= View::e($redirect ?? '/') ?>">

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="remember">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn">Login</button>
    </form>

    <div class="signup-link">
        Don't have an account? <a href="/auth/signup">Register here</a>
    </div>
</div>
<?php View::endSection(); ?>