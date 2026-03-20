<?php
/**
 * Fluxor PHP Framework - Front Controller
 * 
 * This is the entry point for all HTTP requests.
 * The framework auto-detects base path and URL.
 */

// Load Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Initialize the Fluxor application
$app = new Fluxor\App();

// Run the application
$app->run();