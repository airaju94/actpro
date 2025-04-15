<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    $dimension = array('date', 'payout', 'country', 'offer_id', 'zone_id', 'source', 'medium', 'network' );

    $viewData = array(
        'title' => 'Report: Conversions',
        'footer_code' => '',
        'header_code' => '',
        'dimension' => $dimension,
        'page' => 'conversions'
    );
    echo View::render('reports/conversions', $viewData);
