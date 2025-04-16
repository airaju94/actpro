<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );
    Security::filterAll();
    if( Auth::check() ){
        Url::redirect( Url::baseUrl(), 302 );
    }

    if( isset( $_POST['username'] ) && isset( $_POST['password'] ) ){
        if( Auth::login( $_POST['username'], $_POST['password'] ) ){
            e::set('Welcome back Admin!', 'success');
            Url::redirect( Url::baseUrl(), 302 );
        }else{
            e::set( 'Invalid user name or password', 'error' );
        }
    }

    $viewData = array(
        'title' => 'Login',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'login'
    );
    View::noHeader();
    View::noFooter();
    echo View::render('login', $viewData);
