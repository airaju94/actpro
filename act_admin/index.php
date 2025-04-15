<?php

    require_once __DIR__ . '/../autoload.php';
    require_once __DIR__ . '/admin.config.php';
    $router = new Router();

    /* Admin Panel */
    $router->group(ADMIN_DIR, function( $router ){
        /* Dashboard */
        $router->get('/', function() {
            Model::get('dashboard');
            
        });
        /* Reporting Router */
        $router->group('/reports', function( $router ){
            $router->get( '/api', function(){
                Model::get( 'reports/api/api' );
            });
            $router->get( '/performance', function(){
                Model::get( 'reports/performance' );
            });
            $router->get( '/conversions', function(){
                Model::get( 'reports/conversions' );
            });
            $router->get( '/visitors', function(){
                Model::get( 'reports/visitors' );
            });
        });

        /* Offers Managment */
        $router->group('/offers', function( $router ){
            $router->get( '', function(){
                Model::get( 'offers/offers' );
            });
            $router->post( '/add', function(){
                Model::get( 'offers/add' );
            });
            $router->any( '/edit', function(){
                Model::get( 'offers/edit' );
            });
            $router->post( '/delete', function(){
                Model::get( 'offers/delete' );
            });
        });

        $router->group('/auth', function( $router ){
            /* Auth Route */
            $router->get('/login', function() {
                Model::get('login');
            });
            $router->post('/login', function() {
                Model::get('login');
            });
            $router->get('/logout', function() {
                Model::get('logout');
            });
        });

        $router->group('/tracking-system', function( $router ){
            /* Tracking system Route */
            $router->get('/link-builder', function() {
                Model::get('tracking-system/link-builder');
            });
            $router->get('/postback-pixel', function() {
                Model::get('tracking-system/postback-pixel');
            });
            $router->get('/setup', function() {
                Model::get('tracking-system/setup');
            });
            $router->get('/demo', function() {
                Model::get('tracking-system/demo');
            });
            $router->get('/docs', function() {
                Model::get('tracking-system/docs');
            });
        });
        
        $router->group('/settings', function( $router ){
            /* Settings Route */
            $router->get('/cleanup', function() {
                Model::get('settings/cleanup');
            });
            $router->post('/cleanup', function() {
                Model::get('settings/cleanup');
            });
        });
        
    });

    $router->run();
    db::close();
    e::clear();