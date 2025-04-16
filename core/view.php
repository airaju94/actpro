<?php

/*
* -------------------------------------
* | CodeDynamic                       |
* -------------------------------------
* 
* @Author: A.I Raju
* @License: MIT
* @Copyright: CodeDynamic 2024
* @File: View.php
* @Version: 2.0
* 
*/

/* Prevent Direct Access */
defined('BASE') or die('No Direct Access!');

/**
 * View Rendering Class
 * 
 * Provides a secure and flexible template rendering system
 * with support for layouts, partials, and view data.
 */
class View {

    /**
     * View data storage
     * @var array
     */
    protected static $viewData = [];

    /**
     * Main view file
     * @var string
     */
    protected static $mainView = '';

    /**
     * Whether to render view only (without layout)
     * @var bool
     */
    protected static $renderViewOnly = false;

    /**
     * Whether to render with out header or footer
     * @var bool
     */
    protected static $noHeader = false;
    protected static $noFooter = false;

    /**
     * Default layout file
     * @var string
     */
    protected static $defaultLayout = 'layout.php';

    /**
     * View paths configuration
     * @var array
     */
    protected static $paths = [
        'admin' => '/admin/view/',
        'app' => '/app/view/'
    ];

    /**
     * Private constructor to prevent instantiation
     */
    private function __construct() {}

    /**
     * Set render view only mode
     * 
     * @param bool $flag Whether to render view only
     * @return void
     */
    public static function renderViewOnly(bool $flag = true): void {
        self::$renderViewOnly = $flag;
    }

    public static function noHeader(): void{
        self::$noHeader = true;
    }

    public static function noFooter(): void{
        self::$noFooter = true;
    }

    /**
     * Set default layout file
     * 
     * @param string $layout Layout filename
     * @return void
     */
    public static function setDefaultLayout(string $layout): void {
        self::$defaultLayout = $layout;
    }

    /**
     * Set custom view paths
     * 
     * @param array $paths Array of paths ['admin' => path, 'app' => path]
     * @return void
     */
    public static function setPaths(array $paths): void {
        self::$paths = array_merge(self::$paths, $paths);
    }

    /**
     * Render a view with optional layout
     * 
     * @param string $view View filename
     * @param array $data View data
     * @return string Rendered content
     * @throws Exception If view file not found
     */
    public static function render(string $view, array $viewData = []): string {
        $viewFile = self::resolveViewPath($view);
        
        if (!file_exists($viewFile)) {
            throw new Exception("View file {$view} not found at: {$viewFile}");
        }

        self::$mainView = $view;
        self::$viewData = $viewData;

        // Start output buffering
        ob_start();
        
        try {
            extract($viewData, EXTR_SKIP);
            include $viewFile;
        } catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $content = ob_get_clean();

        if (self::$renderViewOnly) {
            return $content;
        }

        return self::renderLayout($content);
    }

    /**
     * Render a partial view
     * 
     * @param string $partial Partial view name
     * @param array $data Additional data for partial
     * @return void
     * @throws Exception If partial not found
     */
    public static function component(string $component, array $componentData = []): void {
        $componentFile = self::resolveViewPath( $component );
        
        if (!file_exists($componentFile)) {
            throw new Exception("Components {$component} not found at: {$componentFile}");
        }

        extract(array_merge(self::$viewData, $componentData), EXTR_SKIP);
        include $componentFile;
    }

    /**
     * Render the layout with main content
     * 
     * @param string $content Main view content
     * @return string Rendered layout with content
     * @throws Exception If layout not found
     */
    protected static function renderLayout(string $content): string {
        $layoutFile = self::resolveViewPath(self::$defaultLayout);
        
        if (!file_exists($layoutFile)) {
            throw new Exception("Layout file not found at: {$layoutFile}");
        }

        ob_start();
        try {
            extract(self::$viewData, EXTR_SKIP);
            include $layoutFile;
        } catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    /**
     * Resolve full path to view file
     * 
     * @param string $view View filename
     * @return string Full path to view file
     */
    protected static function resolveViewPath(string $view): string {
        $view = str_replace('.php', '', $view);
        $basePath = Config::has('isAdmin') && Config::get('isAdmin') 
            ? self::$paths['admin'] 
            : self::$paths['app'];
        
        return Root . $basePath . $view . '.php';
    }

    /**
     * Escape output for HTML
     * 
     * @param string $value Value to escape
     * @return string Escaped value
     */
    public static function escape(string $value): string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}