<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    header( 'Content-Type: application/json' );

    Security::filterAll();
    $output = [];

    if( isset( $_POST['offer_id'] ) && !empty( $_POST['offer_id'] ) ){
        $offer_id = $_POST['offer_id'];
        if( db::has( 'offers', ['id' => $offer_id] ) ){
            if( db::delete('offers', ['id' => $offer_id]) ){
                e::set( 'Offer deleted successfuly!', 'success' );
                $output['status'] = 'success';
            }else{
                e::set( 'Offer not found!', 'error' );
                $output['status'] = 'error';
            }
        }else{
            e::set( 'Offer not found!', 'error' );
            $output['status'] = 'error';
        }
    }else{
        e::set( 'Unauthorized request!', 'error' );
        $output['status'] = 'error';
    }

    
    $output['e'] = e::get();
    echo json_encode( $output );