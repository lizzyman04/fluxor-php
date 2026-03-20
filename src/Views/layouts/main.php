<?php use Fluxor\View; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= View::yield('title', 'Fluxor App') ?></title>
</head>
<body>
    <?= View::yield('content') ?>
</body>
</html>