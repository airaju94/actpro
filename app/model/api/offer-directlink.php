<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    /* Security */
    Security::filterAll();
    require __DIR__.'/class/helper.php';
    require __DIR__.'/class/clicks.php';
    require __DIR__.'/class/visitor.php';
    require __DIR__.'/class/offers.php';

    /* Trying to getting the parameters from GET request or POST request */
    $param = count( $_GET ) > 0 ? $_GET:( count( $_POST ) > 0 ? $_POST:[] );

    $required_param = array( 'offer_id', 'source', 'medium', 'zone_id', 'cost' );

    if( Helper::getValidParam( $param, $required_param ) ){
        if( $visitor_id = Visitor::register( Helper::getValidParam( $param, Helper::getVisitorParam() ) ) ){
            $param['visitor_id'] = $visitor_id;
        }

        if( $click_id = Clicks::register( Helper::getValidParam( $param, Helper::getClicksParam() ) ) ){
            $param['click_id'] = $click_id;
        }

        if( isset( $param['offer_id'] ) && !empty( $param['offer_id'] ) && isset( $param['click_id'] ) && !empty( $param['click_id'] ) ){
            $offer_link = Offers::getOfferClickTrackingLink( $param['offer_id'], $param['click_id'] );
            if( $offer_link ){
                db::close();
                header( "Location: ".$offer_link );
                exit();
            }else{
                db::close();
                header( "Location: ".FALLBACK_URL );
                exit();
            }
        }
    }else{
        db::close();
        header( "Location: ".FALLBACK_URL );
        exit();
    }
