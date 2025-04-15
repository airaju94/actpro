<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    $viewData = array(
        'title' => 'Report: Clicks',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'clicks'
    );
    echo View::render('reports/clicks', $viewData);
