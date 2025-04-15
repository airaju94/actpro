<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    /* Security */
    Security::filterAll();
    require __DIR__.'/class/helper.php';
    require __DIR__.'/class/clicks.php';
    require __DIR__.'/class/offers.php';

    /* Output Var array */
    $output = array();

    /* Trying to getting the parameters from GET request or POST request */
    $param = count( $_GET ) > 0 ? $_GET:( count( $_POST ) > 0 ? $_POST:[] );

    /* Filter all parameters to get required params.  */
    $param = Helper::getValidParam( $param, Helper::getClicksParam() );

    if( $param ){
        if( $click_id = Clicks::register( $param ) ){
            $offerTrackingLink = Offers::getOfferClickTrackingLink( $param['offer_id'], $click_id );
            if( $offerTrackingLink ){
                db::close();
                header( "Location: $offerTrackingLink" );
                exit();
            }else{
                db::close();
                db::delete( 'clicks', ['id' => $click_id] );
                header( "Location: ".FALLBACK_URL );
                exit();
            }
        }else{
            db::close();
            header( "Location: ".FALLBACK_URL );
            exit();
        }
    }
