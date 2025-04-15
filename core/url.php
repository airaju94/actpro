<?php

/*
* -------------------------------------
* | CodeDynamic                       |
* -------------------------------------
* 
* @Author: A.I Raju
* @License: MIT
* @Copyright: CodeDynamic 2024
* @File: Url.php
* @Version: 2.0
* 
*/

/* Prevent Direct Access */
defined('BASE') or die('No Direct Access!');

/**
 * URL Utility Class
 * 
 * Provides comprehensive URL manipulation and generation capabilities
 * with enhanced security and functionality.
 */
class Url {

    /**
     * Base URL of the application
     * @var string
     */
    private static $baseUrl = '';

    /**
     * Parsed URL components
     * @var array
     */
    private static $parseUrl = [];

    /**
     * Initialize URL components
     */
    private static function initialize(): void
    {
        if (empty(self::$baseUrl)) {
            self::setBaseUrl();
        }
    }

    /**
     * Set the base URL
     * 
     * @param string|null $baseUrl Custom base URL (optional)
     * @return string The base URL
     */
    public static function setBaseUrl(?string $baseUrl = null){
        /* URI Elements */
        $host = $_SERVER['HTTP_HOST'];
        $scheme = $_SERVER['REQUEST_SCHEME'];
        if( preg_match( '/(localhost|\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3})/is', $host ) ){
            $realBaseUrl = $scheme.'://'.$host.'/'.basename( Root );
        }else{
            $realBaseUrl = $scheme.'://'.$host;
        }

        if( empty( $baseUrl ) ){
            self::$baseUrl = $realBaseUrl;
        }else{
            self::$baseUrl = $baseUrl;
        }
        return self::$baseUrl;
    }

    /**
     * Get the current page URL
     * 
     * @return string Full URL of current page
     */
    public static function currentPageUrl(): string
    {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        return sprintf('%s://%s%s', $scheme, $host, $uri);
    }

    /**
     * Get the base URL
     * 
     * @return string The base URL
     */
    public static function baseUrl(): string
    {
        self::initialize();
        return self::$baseUrl;
    }

    /**
     * Generate a URL slug from string
     * 
     * @param string $string Input string
     * @param string $separator Word separator (default '-')
     * @return string Generated slug
     */
    public static function slug(string $string, string $separator = '-'): string
    {
        // Convert all characters to their ASCII equivalents
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        
        // Remove special characters
        $string = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $string);
        
        // Convert spaces and slashes to separator
        $string = strtolower(trim($string, $separator));
        $string = preg_replace("/[\/_|+ -]+/", $separator, $string);
        
        return $string;
    }

    /**
     * Get canonical URL for current page
     * 
     * @return string Canonical URL
     */
    public static function canonical(): string
    {
        self::parseUrl(self::currentPageUrl());
        $canonical = sprintf('%s://%s%s', 
            self::scheme(), 
            self::host(), 
            rtrim(self::path() ?? '', '/')
        );
        
        return $canonical . '/';
    }

    /**
     * Parse a URL into its components
     * 
     * @param string $url URL to parse
     */
    public static function parseUrl(string $url): void
    {
        self::$parseUrl = parse_url($url) ?: [];
    }

    /**
     * Get URL host component
     * 
     * @return string|null Host name or null if not available
     */
    public static function host(): ?string
    {
        return self::$parseUrl['host'] ?? null;
    }

    /**
     * Get URL scheme component
     * 
     * @return string|null Scheme (http/https) or null if not available
     */
    public static function scheme(): ?string
    {
        return self::$parseUrl['scheme'] ?? null;
    }

    /**
     * Get URL path component
     * 
     * @return string|null Path or null if not available
     */
    public static function path(): ?string
    {
        return self::$parseUrl['path'] ?? null;
    }

    /**
     * Get all URL components
     * 
     * @return array Parsed URL components
     */
    public static function parts(): array
    {
        return self::$parseUrl;
    }

    /**
     * Check if URL uses HTTPS
     * 
     * @return bool True if HTTPS, false otherwise
     */
    public static function isHttps(): bool
    {
        return self::scheme() === 'https';
    }

    /**
     * Get URL query string
     * 
     * @return string|null Query string or null if not available
     */
    public static function query(): ?string
    {
        return self::$parseUrl['query'] ?? null;
    }

    /**
     * Get URL fragment
     * 
     * @return string|null Fragment or null if not available
     */
    public static function fragment(): ?string
    {
        return self::$parseUrl['fragment'] ?? null;
    }

    /**
     * Redirect to a URL
     * 
     * @param string $url URL to redirect to
     * @param int $code HTTP status code (default 302)
     * @param bool $secure Convert to HTTPS if currently secure
     */
    public static function redirect(string $url, int $code = 302){
        header('Location: ' . $url, true, $code);
        exit;
    }

    /**
     * Build a URL from components
     * 
     * @param array $components URL components (scheme, host, path, query, fragment)
     * @return string Constructed URL
     */
    public static function build(array $components): string
    {
        $url = '';
        
        if (isset($components['scheme'])) {
            $url .= $components['scheme'] . '://';
        }
        
        if (isset($components['host'])) {
            $url .= $components['host'];
        }
        
        if (isset($components['path'])) {
            $url .= '/' . ltrim($components['path'], '/');
        }
        
        if (isset($components['query'])) {
            $url .= '?' . $components['query'];
        }
        
        if (isset($components['fragment'])) {
            $url .= '#' . $components['fragment'];
        }
        
        return $url;
    }
}