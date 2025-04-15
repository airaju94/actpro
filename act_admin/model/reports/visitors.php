<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    $dimension = array('date', 'country', 'zone_id', 'source', 'medium', 'cost' );

    $viewData = array(
        'title' => 'Report: Visitors',
        'footer_code' => '',
        'header_code' => '',
        'dimension' => $dimension,
        'page' => 'visitors'
    );
    echo View::render('reports/visitors', $viewData);
