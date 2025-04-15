<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    $viewData = array(
        'title' => 'ACTPRO Setup',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'setup'
    );
    echo View::render('tracking-system/setup', $viewData);
