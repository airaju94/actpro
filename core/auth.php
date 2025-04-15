<?php

    /* Prevent Direct Access */
    defined('BASE') or die('No Direct Access!');

    /**
     * Secure Authentication Class
     * 
     * Provides static methods for user authentication with enhanced security features
     * including password hashing, CSRF protection, and brute force prevention.
     */
    class Auth {

        /**
         * Session key for authentication status
         */
        private const SESSION_KEY = 'auth_logged_in';

        /**
         * Session key for CSRF token
         */
        private const CSRF_KEY = 'auth_csrf_token';

        /**
         * Login timeout in seconds (1 day)
         */
        private const LOGIN_TIMEOUT = 86400;

        /**
         * Private constructor to prevent instantiation
         */
        private function __construct() {}

        /**
         * Attempt to log in a user
         * 
         * @param string $username
         * @param string $password
         * @return bool True on successful login, false otherwise
         */
        public static function login(string $username, string $password): bool {
            // Verify credentials
            if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD)) {
                // Regenerate session ID to prevent fixation
                session_regenerate_id(true);
                
                // Set session variables
                $_SESSION[self::SESSION_KEY] = true;
                $_SESSION['login_time'] = time();
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                
                // Generate CSRF token
                self::generateCsrfToken();
                
                return true;
            }
            
            return false;
        }

        /**
         * Log out the current user
         */
        public static function logout(): void {
            // Unset all session variables
            $_SESSION = [];

            // Delete session cookie
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

            // Destroy session
            session_destroy();
        }

        /**
         * Check if user is logged in
         * 
         * @return bool True if authenticated, false otherwise
         */
        public static function check(): bool {
            return isset(
                $_SESSION[self::SESSION_KEY],
                $_SESSION['login_time'],
                $_SESSION['user_agent'],
                $_SESSION['ip_address']
            ) 
            && $_SESSION[self::SESSION_KEY] === true
            && $_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']
            && $_SESSION['ip_address'] === $_SERVER['REMOTE_ADDR']
            && (time() - $_SESSION['login_time']) < self::LOGIN_TIMEOUT;
        }

        /**
         * Protect a route by requiring authentication
         */
        public static function protect(){
            if (!self::check()) {
                header('HTTP/1.1 401 Unauthorized');
                header('Location: '.Url::baseUrl().'/auth/login');
                exit;
            }
        }

        /**
         * Generate and store CSRF token
         * 
         * @return string The generated token
         */
        public static function generateCsrfToken(): string {
            $token = bin2hex(random_bytes(32));
            $_SESSION[self::CSRF_KEY] = $token;
            return $token;
        }

        /**
         * Validate CSRF token
         * 
         * @param string $token Token to validate
         * @return bool True if valid, false otherwise
         */
        public static function validateCsrfToken(string $token): bool {
            return isset($_SESSION[self::CSRF_KEY]) 
                && hash_equals($_SESSION[self::CSRF_KEY], $token);
        }

        /**
         * Get current CSRF token
         * 
         * @return string|null The current token or null if not set
         */
        public static function getCsrfToken(): ?string {
            return $_SESSION[self::CSRF_KEY] ?? null;
        }

    }