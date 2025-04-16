<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    $viewData = array(
        'title' => 'Report: Performance',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'performance'
    );
    echo View::render('reports/performance', $viewData);
