# Views & Layouts

## Basic View

Create a view in `src/Views/home.php`:

```php
<h1><?= $title ?></h1>
<p><?= $message ?></p>
```

Render from a route:

```php
use Fluxor\Response;

Flow::GET()->do(function($req) {
    return Response::view('home', [
        'title' => 'Welcome',
        'message' => 'Hello World'
    ]);
});
```

## Layouts

Create a layout in `src/Views/layouts/main.php`:

```php
<?php use Fluxor\View; ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= View::yield('title', 'Default Title') ?></title>
</head>
<body>
    <?= View::yield('content') ?>
</body>
</html>
```

## Using Layouts

```php
<?php use Fluxor\View; ?>

<?php View::extend('layouts/main'); ?>

<?php View::section('title'); ?>
    My Page Title
<?php View::endSection(); ?>

<?php View::section('content'); ?>
    <h1>Page Content</h1>
<?php View::endSection(); ?>
```

## Sections

Define multiple named sections:

```php
<?php View::section('sidebar'); ?>
    <div class="sidebar">...</div>
<?php View::endSection(); ?>

<?php View::section('content'); ?>
    <main>...</main>
<?php View::endSection(); ?>
```

Yield them in layout:

```php
<div class="container">
    <?= View::yield('sidebar') ?>
    <?= View::yield('content') ?>
</div>
```

## Partials

Include other views:

```php
<?= View::include('components/header', ['title' => 'My Page']) ?>
```

## Escaping

```php
<?= View::e($userInput) ?>  // Escaped HTML
<?= View::raw($html) ?>      // Raw HTML (use carefully!)
```
