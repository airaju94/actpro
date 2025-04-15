<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );
    $viewData = array(
        'title' => 'Unlock the Mystery | Special Offers Inside',
        'header_code' => '',
        'footer_code' => '',
        'page' => 'home'
    );
    echo View::render('home', $viewData);
