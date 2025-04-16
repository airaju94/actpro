<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    // Content type header
    header( 'Content-Type: application/json' );

    /* Security */
    Security::filterAll();
    require __DIR__.'/class/helper.php';
    require __DIR__.'/class/clicks.php';
    require __DIR__.'/class/visitor.php';
    require __DIR__.'/class/offers.php';

    /* Output Var array */
    $output = array();

    /* Trying to getting the parameters POST request */
    $param = isset( $_POST ) && count( $_POST ) > 0 ? $_POST:[];

    /* Filter all parameters to get required params.  */
    $param = Helper::getValidParam( $param, Helper::getOfferParam() );

    if( $param && !Helper::validateRequest() ){
        echo json_encode( array(
            'status' => 'invalid',
            'message' => 'invalid request',
        ));
        exit();
    }

    if( $param && Visitor::has( $param['visitor_id'] ) ){
        $visitor_id = $param['visitor_id'];
        /* get Offer clicks */
        $getClicks = db::query( "SELECT * FROM `clicks` WHERE `visitor_id`='$visitor_id' AND DATE(date) = '".db_date()."' LIMIT 0,30" );
        $visitorOfferClicks = $getClicks->num_rows;
        $clickedOffers = ['conversion' => 0];
        if( $visitorOfferClicks > 0 ){
            while( $c = $getClicks->fetch_assoc() ){
                $clickedOffers['conversion'] = $c['is_converted'] ? ($clickedOffers['conversion'] + 1):$clickedOffers['conversion'];
                $clickedOffers['offers'][$c['offer_id']] = array(
                    'is_converted' => $c['is_converted'] ? true:false,
                ); 
            }
        }

        /* get Offers */
        $getOffers = Offers::getAll( 50 );
        $totalOffers = $getOffers->num_rows;
        $offers = [];
        if( $totalOffers > 0 ){
            while( $o = $getOffers->fetch_assoc() ){
                $offers[] = array(
                    'title' => $o['title'],
                    'description' => $o['description'],
                    'icon' => $o['icon'],
                    'category' => $o['category'],
                    'time' => $o['time'],
                    'completed' => number_format((int)$o['conversion']),
                    'tracking_link' => Offers::getOfferTrackingLink( $o['id'], $param['visitor_id'] ),
                    'is_clicked' => !isset( $clickedOffers['offers'][$o['id']] ) ? false:true,
                    'is_converted' => isset( $clickedOffers['offers'][$o['id']] ) ? $clickedOffers['offers'][$o['id']]['is_converted']:false,
                );
            }
        }

        $output = array(
            'status' => 'success',
            'visitor_id' => (int)$visitor_id,
            'total_clicks' => $visitorOfferClicks,
            'total_conversion' => $clickedOffers['conversion'],
            'offers' => $offers
        );

        $offers = [];
        $clickedOffers = [];
        db::close();

    }else{
        $output = array(
            'status' => 'error',
            'visitor_id' => false,
            'offers' => []
        );
    }


    echo json_encode( $output );
    $output = [];
    db::close();
    exit();