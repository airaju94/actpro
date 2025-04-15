<?php

    /* Security Constrant */
    define( 'BASE', 'true' );

    // Database Configuration
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'act');

    // Admin Login Credentials
    define('ADMIN_USERNAME', 'admin');
    define('ADMIN_PASSWORD', password_hash('admin123', PASSWORD_DEFAULT));

    // Application Settings
    define('APP_NAME', 'ACT PRO');
    define('TIMEZONE', 'UTC');
    define('Root', __DIR__);
    define('ROOT_DIR', strtolower( basename( Root ) ) ); // must use lowercase directory name
    date_default_timezone_set(TIMEZONE);

    // Tracker Settings
    define( 'FALLBACK_URL', 'https://aolgames.xyz/' );

    // Error Reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 0);
    ini_set('error_log', __DIR__ . '/error.log');
