<?php
/**
 * Simple and Powerful Autoloader (No Namespaces)
 * 
 * Features:
 * - Supports multiple directories (action, core, reports, view, api, app)
 * - Custom file extensions
 * - Class name validation
 * - Case-insensitive file matching (for Windows compatibility)
 * - Performance optimized
 */
class Autoloader {
    private static $directories = [
        'action',
        'core', 
        'reports',
        'view',
        'api',
        'app'
    ];
    private static $extensions = ['.php'];
    private static $classPattern = '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/';
    
    /**
     * Initialize the autoloader
     */
    public static function init() {
        spl_autoload_register([__CLASS__, 'load']);
    }
    
    /**
     * Add additional directories to search
     * @param string|array $dir Directory path(s)
     */
    public static function addDirectory($dir) {
        if (is_array($dir)) {
            self::$directories = array_merge(self::$directories, $dir);
        } else {
            self::$directories[] = $dir;
        }
    }
    
    /**
     * Set file extensions to check
     * @param array $extensions Array of extensions with leading dot
     */
    public static function setExtensions(array $extensions) {
        self::$extensions = $extensions;
    }
    
    /**
     * The actual autoloading function
     * @param string $class Class name
     * @return bool Whether the class was loaded
     */
    public static function load($class) {
        // Validate class name
        if (!preg_match(self::$classPattern, $class)) {
            return false;
        }
        
        // Try each directory
        foreach (self::$directories as $dir) {
            $filePath = self::findFile($dir, $class);
            if ($filePath !== null) {
                require $filePath;
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Find the file for a class
     * @param string $directory Base directory
     * @param string $className Class name
     * @return string|null Full file path or null if not found
     */
    private static function findFile($directory, $className) {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;
        
        // Try direct class name match first
        foreach (self::$extensions as $ext) {
            $filePath = $baseDir . $className . $ext;
            if (file_exists($filePath)) {
                return $filePath;
            }
        }
        
        // Try lowercase version (for Windows compatibility)
        foreach (self::$extensions as $ext) {
            $filePath = $baseDir . strtolower($className) . $ext;
            if (file_exists($filePath)) {
                return $filePath;
            }
        }
        
        // Try subdirectories (for class groups)
        $files = glob($baseDir . '*.php');
        foreach ($files as $file) {
            if (strtolower(pathinfo($file, PATHINFO_FILENAME)) === strtolower($className)) {
                return $file;
            }
        }
        
        return null;
    }
}

// Initialize the autoloader
Autoloader::init();

/*******************************
 *  USAGE EXAMPLES
 *******************************

// In your config.php:
require __DIR__.'/autoloader.php';

// Example class structure:
// Root/core/Database.php       → class Database {}
// Root/action/UserAuth.php     → class UserAuth {}
// Root/view/Template.php       → class Template {}
// Root/api/Request.php         → class Request {}
// Root/app/Model.php           → class Model {}

// Optional configuration:
// 1. Add additional directories:
Autoloader::addDirectory('lib');
Autoloader::addDirectory(['helpers', 'utilities']);

// 2. Change file extensions:
Autoloader::setExtensions(['.class.php', '.inc.php', '.php']);

// The autoloader will automatically:
// - Search through all registered directories
// - Try different file extensions
// - Handle case differences (for Windows)
// - Load the first matching file found
*/

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/core/functions.php';
