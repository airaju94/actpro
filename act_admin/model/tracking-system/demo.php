<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    $viewData = array(
        'title' => 'ACTPRO DEMO',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'demo'
    );
    echo View::render('tracking-system/demo', $viewData);
