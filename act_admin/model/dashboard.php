<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    $viewData = array(
        'title' => 'Dashboard',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'dashboard'
    );
    echo View::render('dashboard', $viewData);
