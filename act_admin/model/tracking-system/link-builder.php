<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );
    
    $viewData = array(
        'title' => 'Link Builder',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'link-builder'
    );
    echo View::render('tracking-system/link-builder', $viewData);
