<?php

    /* Prevent Direct Access */
    defined('BASE') or die('No Direct Access!');

    /* Admin Settings */
    
    // ADMIN_DIR: must use lowercase directory name
    define( 'ADMIN_DIR', '/'.strtolower( basename( __DIR__ ) ) );

    /* Auth Pages */
    define('AUTH_LOGIN_URI', '/auth/login');
    define('AUTH_LOGOUT_URI', '/auth/logout');
    $page_uri = str_replace( [ROOT_DIR, ADMIN_DIR], '', $_SERVER['REQUEST_URI'] );
    define( 'PAGE_URI', $page_uri );

    /* Important Settings */
    Url::setBaseUrl( Url::baseUrl().ADMIN_DIR );
    View::setPaths( ['admin' => ADMIN_DIR.'/view/'] );
    Model::setPaths( ['admin' => ADMIN_DIR.'/model/'] );

    /* Set system mode as isAdmin : true */
    Config::set('isAdmin', true);
    Session::start();
    /* Auth Check before access */
    if( !Auth::check() && PAGE_URI !== AUTH_LOGIN_URI ){
        if( isAjax() ){
            header('Content-Type: application/json');
            e::set( 'login required!', 'error' );
            echo json_encode( ['status' => 'error', 'e' => e::get() ] );
            e::clear();
            db::close();
            exit();
        }else{
            Auth::protect();
        }
    }

    function siteUrl(){
        return str_replace( ADMIN_DIR, '', Url::baseUrl() );
    }