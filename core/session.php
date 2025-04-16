<?php

/*
* -------------------------------------
* | CodeDynamic                       |
* -------------------------------------
* 
* @Author: A.I Raju
* @License: MIT
* @Copyright: CodeDynamic 2024
* @File: Session.php
* @Version: 2.0
* 
*/

/* Prevent Direct Access */
defined('BASE') or die('No Direct Access!');

/**
 * Secure Session Management Class
 * 
 * Provides a secure interface for managing PHP sessions with
 * enhanced security features and proper data handling.
 */
class Session {

    /**
     * @var bool Flag to track if session is started
     */
    private static $sessionStarted = false;

    /**
     * Initialize session with secure settings
     */
    public static function start(): void{
        if (!self::$sessionStarted) {
            // Set secure session parameters before starting
            ini_set('session.use_strict_mode', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_samesite', 'Lax');
            
            if (Config::has('sessionSecure') && Config::get('sessionSecure')) {
                ini_set('session.cookie_secure', 1);
            }

            // Set custom session name if configured
            if (Config::has('sessionName')) {
                session_name(Config::get('sessionName'));
            }

            // Set custom session save path if configured
            if (Config::has('sessionSavePath')) {
                session_save_path(Config::get('sessionSavePath'));
            }

            session_start();
            self::$sessionStarted = true;

            // Regenerate session ID to prevent fixation
            self::regenerate();
        }
    }

    /**
     * Set a session value
     * 
     * @param string $key Session key
     * @param mixed $value Session value
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Check if session key exists
     * 
     * @param string $key Session key
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Get a session value
     * 
     * @param string $key Session key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Get all session data
     * 
     * @return array
     */
    public static function getAll(): array
    {
        self::start();
        return $_SESSION ?? [];
    }

    /**
     * Delete a session value
     * 
     * @param string $key Session key
     * @return bool True if deleted, false if didn't exist
     */
    public static function delete(string $key): bool
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    /**
     * Add a value to a session array
     * 
     * @param string $key Session key
     * @param mixed $value Value to add
     */
    public static function add(string $key, $value): void
    {
        self::start();
        if (!isset($_SESSION[$key]) || !is_array($_SESSION[$key])) {
            $_SESSION[$key] = [];
        }
        $_SESSION[$key][] = $value;
    }

    /**
     * Destroy the session
     */
    public static function destroy(): void
    {
        if (self::$sessionStarted) {
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            session_destroy();
            self::$sessionStarted = false;
        }
    }

    /**
     * Regenerate session ID
     * 
     * @param bool $deleteOldSession Whether to delete old session
     */
    public static function regenerate(bool $deleteOldSession = true): void
    {
        self::start();
        session_regenerate_id($deleteOldSession);
    }

    /**
     * Flash a message to the session (available only on next request)
     * 
     * @param string $key Flash key
     * @param mixed $value Flash value
     */
    public static function flash(string $key, $value): void
    {
        self::set('_flash_'.$key, $value);
    }

    /**
     * Get a flashed message (and remove it)
     * 
     * @param string $key Flash key
     * @param mixed $default Default value if flash doesn't exist
     * @return mixed
     */
    public static function getFlash(string $key, $default = null)
    {
        $value = self::get('_flash_'.$key, $default);
        self::delete('_flash_'.$key);
        return $value;
    }

}