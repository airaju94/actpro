<?php

/*
* -------------------------------------
* | CodeDynamic                       |
* -------------------------------------
* 
* @Author: A.I Raju
* @License: MIT
* @Copyright: CodeDynamic 2024
* @File: Config.php
* @Version: 2.0
* 
*/

/* Prevent Direct Access */
defined('BASE') or die('No Direct Access!');

/**
 * Configuration Management Class
 * 
 * Provides a centralized way to manage application configuration settings
 * with support for different data types and persistence.
 */
class Config {

    /**
     * Configuration data storage
     * @var array
     */
    protected static $config = [];

    /**
     * Private constructor to prevent instantiation
     */
    private function __construct() {}

    /**
     * Set a configuration value
     * 
     * @param string|int $key Configuration key
     * @param mixed $value Configuration value
     * @return void
     */
    public static function set($key, $value): void {
        if (!is_string($key) && !is_int($key)) {
            throw new InvalidArgumentException('Config key must be string or integer');
        }
        
        self::$config[$key] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get a configuration value
     * 
     * @param string|int $key Configuration key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public static function get($key, $default = null) {
        if (!self::has($key)) {
            return $default;
        }

        $value = self::$config[$key];
        
        // Auto-detect and decode JSON values
        if (is_string($value) && ($decoded = json_decode($value, true)) !== null) {
            return $decoded;
        }
        
        return $value;
    }

    /**
     * Check if a configuration key exists
     * 
     * @param string|int $key Configuration key
     * @return bool
     */
    public static function has($key): bool {
        return array_key_exists($key, self::$config);
    }

    /**
     * Add a value to an array configuration
     * 
     * @param string|int $key Configuration key
     * @param mixed $value Value to add
     * @return void
     */
    public static function add($key, $value): void {
        if (!self::has($key)) {
            self::$config[$key] = [];
        }
        
        $current = self::get($key);
        if (!is_array($current)) {
            $current = [$current];
        }
        
        $current[] = $value;
        self::set($key, $current);
    }

    /**
     * Register a new configuration value if it doesn't exist
     * 
     * @param string|int $key Configuration key
     * @param mixed $value Configuration value
     * @return bool True if registered, false if already exists
     */
    public static function register($key, $value): bool {
        if (empty($key)) {
            throw new InvalidArgumentException('Config key cannot be empty');
        }

        if (!self::has($key)) {
            self::set($key, $value);
            return true;
        }
        
        return false;
    }

    /**
     * Update an existing configuration value
     * 
     * @param string|int $key Configuration key
     * @param mixed $value New value
     * @return bool True if updated, false if key didn't exist
     */
    public static function update($key, $value): bool {
        if (self::has($key)) {
            self::set($key, $value);
            return true;
        }
        
        return false;
    }

    /**
     * Get all configuration values
     * 
     * @return array
     */
    public static function getAll(): array {
        $result = [];
        
        foreach (self::$config as $key => $value) {
            $result[$key] = self::get($key);
        }
        
        return $result;
    }

    /**
     * Load multiple configuration values at once
     * 
     * @param array $configArray Associative array of configurations
     * @return void
     */
    public static function load(array $configArray): void {
        foreach ($configArray as $key => $value) {
            self::set($key, $value);
        }
    }

    /**
     * Remove a configuration value
     * 
     * @param string|int $key Configuration key
     * @return bool True if removed, false if key didn't exist
     */
    public static function delete($key): bool {
        if (self::has($key)) {
            unset(self::$config[$key]);
            return true;
        }
        
        return false;
    }

}