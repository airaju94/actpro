<?php

/*
* -------------------------------------
* | CodeDynamic                       |
* -------------------------------------
* 
* @Author: A.I Raju
* @License: MIT
* @Copyright: CodeDynamic 2024
* @File: Model.php
* @Version: 2.0
* 
*/

/* Prevent Direct Access */
defined('BASE') or die('No Direct Access!');

/**
 * Model Class
 * 
 * Provides a secure and flexible model loading system with caching,
 * dependency injection, and better error handling.
 */
class Model {

    /**
     * Model file path
     * @var string|null
     */
    protected static $modelFile = null;

    /**
     * Base model path
     * @var string|null
     */
    protected static $modelPath = null;

    /**
     * Loaded models cache
     * @var array
     */
    protected static $loadedModels = [];

    /**
     * Model paths configuration
     * @var array
     */
    protected static $paths = [
        'admin' => '/admin/model/',
        'app' => '/app/model/'
    ];

    /**
     * Private constructor to prevent instantiation
     */
    private function __construct() {}

    /**
     * Check if a model exists
     * 
     * @param string $model Model name
     * @return bool True if exists, false otherwise
     */
    public static function has(string $model): bool {
        return file_exists(self::resolveModelPath($model));
    }

    /**
     * Get a model instance
     * 
     * @param string $model Model name
     * @param array $dependencies Optional dependencies to inject
     * @return mixed Model instance or false if not found
     * @throws RuntimeException If model cannot be loaded
     */
    public static function get(string $model, array $param = []) {
        // Check cache first
        if (isset(self::$loadedModels[$model])) {
            return self::$loadedModels[$model];
        }
        $modelPath = self::resolveModelPath($model);
        if (!file_exists($modelPath)) {
            throw new RuntimeException("Model {$model} not found at: {$modelPath}");
        }

        // Isolate scope for included file
        $loadModel = function($path, $param) {
            extract($param, EXTR_SKIP);
            return require_once $path;
        };

        try {
            $modelInstance = $loadModel($modelPath, $param);
            
            // Cache the loaded model
            self::$loadedModels[$model] = $modelInstance;
            
            return $modelInstance;
        } catch (Exception $e) {
            throw new RuntimeException("Failed to load model {$model}: " . $e->getMessage());
        }
    }

    /**
     * Resolve full path to model file
     * 
     * @param string $model Model name
     * @return string Full path to model file
     */
    protected static function resolveModelPath(string $model): string {
        $model = str_replace('.php', '', $model);
        $basePath = Config::has('isAdmin') && Config::get('isAdmin') 
            ? self::$paths['admin'] 
            : self::$paths['app'];
        
        return rtrim(Root, '/') . $basePath . $model . '.php';
    }

    /**
     * Set custom model paths
     * 
     * @param array $paths Array of paths ['admin' => path, 'app' => path]
     * @return void
     */
    public static function setPaths(array $paths): void {
        self::$paths = array_merge(self::$paths, $paths);
    }

    /**
     * Clear the model cache
     * 
     * @return void
     */
    public static function clearCache(): void {
        self::$loadedModels = [];
    }

    /**
     * Get all loaded models
     * 
     * @return array
     */
    public static function getLoadedModels(): array {
        return self::$loadedModels;
    }

}