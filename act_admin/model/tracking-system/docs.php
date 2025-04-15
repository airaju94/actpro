<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    $viewData = array(
        'title' => 'ACTPRO Documentation',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'docs'
    );
    echo View::render('tracking-system/docs', $viewData);
