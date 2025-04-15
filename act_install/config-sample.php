<?php

    /* Security Constrant */
    define( 'BASE', 'true' );

    // Database Configuration
    define('DB_HOST', '{{DB_HOST}}');
    define('DB_USER', '{{DB_USER}}');
    define('DB_PASS', '{{DB_PASSWORD}}');
    define('DB_NAME', '{{DB_NAME}}');

    // Admin Login Credentials
    define('ADMIN_USERNAME', '{{ADMIN_USER_NAME}}');
    define('ADMIN_PASSWORD', '{{ADMIN_PASSWORD_HASH}}');

    // Application Settings
    define('APP_NAME', '{{APP_ANME}}');
    define('TIMEZONE', '{{TIMEZONE}}');
    define('Root', __DIR__);
    define('ROOT_DIR', strtolower( basename( Root ) ) ); // must use lowercase directory name
    date_default_timezone_set(TIMEZONE);

    // Tracker Settings
    define( 'FALLBACK_URL', 'https://aolgames.xyz/' );

    // Error Reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error.log');
