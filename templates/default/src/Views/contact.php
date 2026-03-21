<?php use Fluxor\View; ?>
<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
    Contact Us
<?php View::endSection(); ?>

<?php View::section('content'); ?>
    <div class="card">
        <h1>Contact Us</h1>
        <p>Have questions? We'd love to hear from you.</p>
        
        <form method="POST" action="/contact" style="margin-top: 2rem;">
            <?= View::csrfField() ?>
            <div style="margin-bottom: 1rem;">
                <label for="name" style="display: block; margin-bottom: 0.5rem;">Name:</label>
                <input type="text" name="name" id="name" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="email" style="display: block; margin-bottom: 0.5rem;">Email:</label>
                <input type="email" name="email" id="email" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="message" style="display: block; margin-bottom: 0.5rem;">Message:</label>
                <textarea name="message" id="message" rows="5" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
            </div>
            <button type="submit" class="btn">Send Message</button>
        </form>
    </div>
<?php View::endSection(); ?>