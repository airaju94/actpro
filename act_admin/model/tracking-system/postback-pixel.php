<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );
    
    $viewData = array(
        'title' => 'Postback Pixel',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'postback-pixel'
    );
    echo View::render('tracking-system/postback-pixel', $viewData);
