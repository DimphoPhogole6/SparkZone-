<?php
// config.php - project root

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'sparkzone');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'SparkZone');
define('BASE_URL', '/SparkZone/'); // adjust if hosted under different path

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
