<?php

	/*
	* -------------------------------------
	* | CodeDynamic                       |
	* -------------------------------------
	* 
	* @Author: A.I Raju
	* @License: MIT
	* @Copyright: CodeDynamic 2024
	* @File: Error.php
	* @Version: 1
	* 
	*/
    
    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );


	/*
	* Error Class Start
	*/

    class e {
	
        protected static $errorType = array(
            'error' => ['class' => 'danger', 'icon' => 'bi bi-exclamation-triangle-fill'],
            'info' => ['class' => 'primary', 'icon' => 'bi bi-info-circle-fill'],
            'success' => ['class' => 'success', 'icon' => 'bi bi-check-circle-fill'],
            'warning' => ['class' => 'warning', 'icon' => 'bi bi-exclamation-triangle-fill'],
            'primary' => ['class' => 'primary', 'icon' => 'bi bi-stars me-2'],
            'dark' => ['class' => 'dark', 'icon' => 'bi bi-stars me-2'],
            'secondary' => ['class' => 'secondary', 'icon' => 'bi bi-stars me-2']
        );
        
        
        /* Default Construct */
        function __construct()
        {
            // Construct Code...
        }

        public static function set( string|int $e, string $type = 'error' ){
            $errorData = Session::has( 'e' ) ? json_decode( Session::get( 'e' ), true ):array();
            $e = empty( $e ) ? 'Undefined error!':$e;
            $errorData[] = array(
                'type' => strtolower($type),
                'message' => $e,
            );
            Session::set( 'e', json_encode( $errorData ) );
        }

        public static function get( $callback = false ){
            $errorData = Session::has( 'e' ) ? json_decode( Session::get( 'e' ), true ):false;
            if( $callback !== false && is_callable( $callback ) ){
               return call_user_func( $callback, $errorData );
            }else{
                return $errorData;
            }
        }

        public static function has(){
            $errorData = Session::has( 'e' ) ? json_decode( Session::get( 'e' ), true ):array();
            return !empty( $errorData ) && count( $errorData ) > 0 ? true:false;
        }

        public static function getErrorType( string $type ){
            $type = strtolower( $type );
            return isset( self::$errorType[$type] ) ? self::$errorType[$type]:self::$errorType['warning'];
        }
        
        public static function clear(){
            Session::delete('e');
        }
  
    }