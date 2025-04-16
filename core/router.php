<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    class Router {
        private $routes = [];
        private $prefix = '';
        private $patterns = [
            ':num' => '[0-9]+',
            ':str' => '[a-zA-Z\-]+',
            ':any' => '[^/]+',
            ':all' => '.*',
            ':id' => '[0-9]+',
            ':slug' => '[a-zA-Z0-9]+',
            ':file' => '[a-zA-Z0-9\-\_\.]+'
        ];
        private $errorHandler = null;
        private $notFoundHandler = null;

        /**
         * Add GET route
         * @param string $path
         * @param callable $handler
         * @return self
         */
        public function get($path, $handler) {
            return $this->add('GET', $path, $handler);
        }

        /**
         * Add POST route
         * @param string $path
         * @param callable $handler
         * @return self
         */
        public function post($path, $handler) {
            return $this->add('POST', $path, $handler);
        }

        /**
         * Add PUT route
         * @param string $path
         * @param callable $handler
         * @return self
         */
        public function put($path, $handler) {
            return $this->add('PUT', $path, $handler);
        }

        /**
         * Add DELETE route
         * @param string $path
         * @param callable $handler
         * @return self
         */
        public function delete($path, $handler) {
            return $this->add('DELETE', $path, $handler);
        }

        /**
         * Add route for any HTTP method
         * @param string $path
         * @param callable $handler
         * @return self
         */
        public function any($path, $handler) {
            return $this->add(['GET','POST','PUT','DELETE','PATCH','OPTIONS'], $path, $handler);
        }

        /**
         * Set route prefix for group
         * @param string $prefix
         * @param callable $callback
         */
        public function group($prefix, $callback) {
            $oldPrefix = $this->prefix;
            $this->prefix .= $prefix;
            $callback($this);
            $this->prefix = $oldPrefix;
        }

        /**
         * Add custom pattern
         * @param string $name
         * @param string $regex
         */
        public function pattern($name, $regex) {
            $this->patterns[$name] = $regex;
        }

        /**
         * Set 404 handler
         * @param callable $handler
         */
        public function notFound($handler) {
            $this->notFoundHandler = $handler;
        }

        /**
         * Set error handler
         * @param callable $handler
         */
        public function error($handler) {
            $this->errorHandler = $handler;
        }

        /**
         * Run the router
         */
        public function run() {
            try {
                $method = $_SERVER['REQUEST_METHOD'];
                $uri = parse_url(str_replace(ROOT_DIR, '', $_SERVER['REQUEST_URI']), PHP_URL_PATH);
                $uri = empty( $uri ) ? '/':$uri;
                $found = false;
                
                foreach ($this->routes as $route) {
                    if (in_array($method, $route['methods'])) {
                        $pattern = $this->buildPattern($route['path']);
                        if (preg_match($pattern, $uri, $matches)) {
                            $found = true;
                            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                            call_user_func_array($route['handler'], $params);
                            break;
                        }
                    }
                }

                if (!$found && $this->notFoundHandler) {
                    call_user_func($this->notFoundHandler);
                }
            } catch (Exception $e) {
                if ($this->errorHandler) {
                    call_user_func($this->errorHandler, $e);
                } else {
                    throw $e;
                }
            }
        }

        /**
         * Internal route addition
         * @param string|array $methods
         * @param string $path
         * @param callable $handler
         * @return self
         */
        private function add($methods, $path, $handler) {
            $methods = (array)$methods;
            $path = $this->prefix . $path;
            $this->routes[] = [
                'methods' => $methods,
                'path' => $path,
                'handler' => $handler
            ];
            return $this;
        }

        /**
         * Build regex pattern from route path
         * @param string $path
         * @return string
         */
        private function buildPattern($path) {
            $pattern = preg_replace_callback('/:([a-zA-Z0-9_]+)(\([^)]+\))?/', function($matches) {
                $key = $matches[1];
                if (isset($matches[2])) {
                    return '(?P<'.$key.'>'.substr($matches[2], 1, -1).')';
                }
                if (isset($this->patterns[$key])) {
                    return '(?P<'.$key.'>'.$this->patterns[$key].')';
                }
                return '(?P<'.$key.'>[^/]+)';
            }, $path);

            return '#^'.$pattern.'$#';
        }
    }

    /*******************************
     *  USAGE EXAMPLES
     *******************************

    // Create new router instance
    $router = new Router();

    // Simple GET route
    $router->get('/', function() {
        echo "Welcome home!";
    });

    // Route with parameter
    $router->get('/user/:id', function($id) {
        echo "User ID: " . htmlspecialchars($id);
    });

    // Custom pattern
    $router->pattern('slug', '[a-z0-9-]+');
    $router->get('/post/:slug', function($slug) {
        echo "Post slug: " . htmlspecialchars($slug);
    });

    // Group with prefix
    $router->group('/api', function($router) {
        $router->get('/users', function() {
            echo "API Users list";
        });
        
        $router->get('/users/:id', function($id) {
            echo "API User ID: " . htmlspecialchars($id);
        });
    });

    // POST route
    $router->post('/submit', function() {
        echo "Form submitted!";
    });

    // 404 handler
    $router->notFound(function() {
        header("HTTP/1.0 404 Not Found");
        echo "Page not found!";
    });

    // Error handler
    $router->error(function(Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo "Error: " . $e->getMessage();
    });

    // Run the router
    $router->run();

    */