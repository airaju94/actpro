<?php
    require_once __DIR__ . '/autoload.php';
    $router = new Router();

    $router->get( '/', function(){
        Model::get( 'home' );
    });

    /* MAIN API Router */
    $router->group('/api', function( $router ){
        
        /* API Router Cross Headers */
        header('Access-Control-Allow-Origin: *');
        header( 'Access-Control-Allow-Headers: x-request-id,x-request-nonce,x-request-with' );
        
        $router->get( '/click', function(){
            Model::get( 'api/click' );
        });

        $router->get( '/offer/go', function(){
            Model::get( 'api/offer-directlink' );
        });

        $router->post( '/getOffers', function(){
            Model::get( 'api/getOffers' );
        });

        $router->post( '/px', function(){
            Model::get( 'api/actpro-postback' );
        });

        $router->any( '/airpx-s2s', function(){
            Model::get( 'api/postback-s2s' );
        });

        $router->post( '/register', function(){
            Model::get( 'api/register' );
        });

        $router->post( '/confirm', function(){
            Model::get( 'api/confirm' );
        });

    });


    $router->error(function(Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo "Error: " . $e->getMessage();
        // print_r( $e );
    });

    $router->run();
    db::close();