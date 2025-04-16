<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );


    if( isAjax() ){
        View::renderViewOnly();
    }

    $viewData = array(
        'title' => 'Manage Offers',
        'header_code' => '',
        'footer_code' => '',
        'offers' => App::getOffers(),
        'categories' => App::getCategory(),
        'page' => 'offers'
    );
    echo View::render('offers/offers', $viewData);
