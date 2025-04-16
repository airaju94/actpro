<?php

/*
* -------------------------------------
* | CodeDynamic                       |
* -------------------------------------
* 
* @Author: A.I Raju
* @License: MIT
* @Copyright: CodeDynamic 2024
* @File: Security.php
* @Version: 2.0
* 
*/

/* Prevent Direct Access */
defined('BASE') or die('No Direct Access!');

/**
 * Security Class
 * 
 * Provides comprehensive security utilities including input filtering,
 * XSS prevention, and request data sanitization.
 */
class Security {

    /**
     * Filtered GET data
     * @var array
     */
    protected static $getData = [];

    /**
     * Filtered POST data
     * @var array
     */
    protected static $postData = [];

    /**
     * Keys to exclude from filtering
     * @var array
     */
    protected static $excludedKeys = [];

    /**
     * Private constructor to prevent instantiation
     */
    private function __construct() {}

    /**
     * Get filtered GET data
     * 
     * @return array
     */
    public static function getData(): array {
        return self::$getData;
    }

    /**
     * Get filtered POST data
     * 
     * @return array
     */
    public static function postData(): array {
        return self::$postData;
    }

    /**
     * Set keys to exclude from filtering
     * 
     * @param array $keys Array of keys to exclude
     * @return void
     */
    public static function excludeFromFilter(array $keys): void {
        self::$excludedKeys = array_merge(self::$excludedKeys, $keys);
    }

    /**
     * Filter all input data (GET and POST)
     * 
     * @return void
     */
    public static function filterAll(): void {
        self::filterGet();
        self::filterPost();
    }

    /**
     * Filter GET data
     * 
     * @return void
     */
    public static function filterGet(): void {
        if (!empty($_GET)) {
            self::$getData = self::filterArray($_GET);
            $_GET = self::$getData;
        }
    }

    /**
     * Filter POST data
     * 
     * @return void
     */
    public static function filterPost(): void {
        if (!empty($_POST)) {
            self::$postData = self::filterArray($_POST);
            $_POST = self::$postData;
        }
    }

    /**
     * Filter an array of data
     * 
     * @param array $data Input data to filter
     * @return array Filtered data
     */
    protected static function filterArray(array $data): array {
        $filtered = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, self::$excludedKeys, true)) {
                $filtered[$key] = $value;
                continue;
            }

            $filtered[$key] = is_array($value) 
                ? self::filterArray($value) 
                : self::filterXSS($value);
        }
        
        return $filtered;
    }

    /**
     * XSS filter
     * 
     * @param mixed $data Input to filter
     * @return mixed Filtered output
     */
    public static function filterXSS($data) {
        if (is_array($data)) {
            return array_map([self::class, 'filterXSS'], $data);
        }

        if (!is_string($data)) {
            return $data;
        }

        // Remove null bytes
        $data = str_replace("\0", '', $data);

        // Convert special characters to HTML entities
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove dangerous patterns
        $dangerousPatterns = [
            // PHP tags
            '/<\?php/i', '/<\?=/i', '/<\?/i', '/\?>/i',
            // Script tags
            '/<script\b[^>]*>(.*?)<\/script>/is',
            // HTML tags with event handlers
            '/\bon\w+\s*=\s*("[^"]+"|\'[^\']+\'|[^>\s]+)/i',
            // JavaScript protocols
            '/javascript\s*:/i',
            // SQL injection patterns
            '/\b(?:OR|AND)\s+[\d\w]+\s*=\s*[\d\w]+\b/i',
            '/\b(?:WHERE)\s+[\d\w]+\s*=\s*[\d\w]+\b/i',
            // Other dangerous patterns
            '/<\s*(?:a|iframe|img)[^>]*>/i',
            '/alert\s*\(/i', '/prompt\s*\(/i', '/confirm\s*\(/i'
        ];

        $data = preg_replace($dangerousPatterns, '', $data);

        return $data;
    }

    /**
     * Get raw (unfiltered) GET data
     * 
     * @return array
     */
    public static function rawGetData(): array {
        return $_GET;
    }

    /**
     * Get raw (unfiltered) POST data
     * 
     * @return array
     */
    public static function rawPostData(): array {
        return $_POST;
    }

    /**
     * Reset all filtered data
     * 
     * @return void
     */
    public static function reset(): void {
        self::$getData = [];
        self::$postData = [];
        self::$excludedKeys = [];
    }

}