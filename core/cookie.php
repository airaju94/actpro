<?php

/*
* -------------------------------------
* | CodeDynamic                       |
* -------------------------------------
* 
* @Author: A.I Raju
* @License: MIT
* @Copyright: CodeDynamic 2024
* @File: Cookie.php
* @Version: 2.0
* 
*/

/* Prevent Direct Access */
defined('BASE') or die('No Direct Access!');

/**
 * Cookie Management Class
 * 
 * Provides a secure and convenient way to manage HTTP cookies
 */
class Cookie {

    /**
     * Default cookie settings from configuration
     * @var array
     */
    protected static $defaults = [
        'httponly' => true,
        'secure' => true,
        'path' => '/',
        'domain' => '',
        'samesite' => 'Lax'
    ];

    /**
     * Private constructor to prevent instantiation
     */
    private function __construct() {}

    /**
     * Set a cookie
     * 
     * @param string $name Cookie name
     * @param mixed $value Cookie value
     * @param int|null $expiryDays Expiration in days (null for session cookie)
     * @param array $options Additional cookie options
     * @return bool True on success
     */
    public static function set(string $name, $value, ?int $expiryDays = null, array $options = []): bool {
        if (empty($name)) {
            throw new InvalidArgumentException('Cookie name cannot be empty');
        }

        // Merge with defaults
        $options = array_merge(self::$defaults, $options);
        
        // Calculate expiration time
        $expiry = $expiryDays === null ? 0 : time() + ($expiryDays * 86400);
        
        // Prepare cookie parameters
        $params = [
            'expires' => $expiry,
            'path' => $options['path'],
            'domain' => $options['domain'] ?: $_SERVER['HTTP_HOST'],
            'secure' => $options['secure'],
            'httponly' => $options['httponly'],
            'samesite' => $options['samesite']
        ];

        // Handle array/object values by JSON encoding
        $cookieValue = is_scalar($value) ? $value : json_encode($value);
        
        return setcookie($name, $cookieValue, $params);
    }

    /**
     * Check if a cookie exists
     * 
     * @param string $name Cookie name
     * @return bool
     */
    public static function has(string $name): bool {
        return isset($_COOKIE[$name]);
    }

    /**
     * Get a cookie value
     * 
     * @param string $name Cookie name
     * @param mixed $default Default value if cookie doesn't exist
     * @return mixed
     */
    public static function get(string $name, $default = null) {
        if (!self::has($name)) {
            return $default;
        }

        $value = $_COOKIE[$name];
        
        // Auto-detect and decode JSON values
        if (is_string($value) && ($decoded = json_decode($value, true))){
            return $decoded;
        }
        
        return $value;
    }

    /**
     * Delete a cookie
     * 
     * @param string $name Cookie name
     * @return bool True on success
     */
    public static function delete(string $name): bool {
        if (self::has($name)) {
            unset($_COOKIE[$name]);
        }
        
        return self::set($name, '', -1);
    }

    /**
     * Get all cookies
     * 
     * @return array
     */
    public static function getAll(): array {
        return $_COOKIE ?? [];
    }

    /**
     * Set default cookie options
     * 
     * @param array $options Array of default options
     * @return void
     */
    public static function setDefaults(array $options): void {
        self::$defaults = array_merge(self::$defaults, $options);
    }

}