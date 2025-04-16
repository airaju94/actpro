<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    header( 'Content-Type: application/json' );

    Security::filterAll();
    
    $offersParams = array('title', 'description', 'category', 'status', 'link', 'time', 'network');
    $output = [];
    $offerData = [];

    if( isset( $_GET['offer_id'] ) && !empty( $_GET['offer_id'] ) ){
        $offer_id = $_GET['offer_id'];
        if( db::has( 'offers', ['id' => $offer_id] ) ){
            $offer = db::query( "select title,description,status,category,link,time,network from offers where id='$offer_id'" );
            $offer = $offer->fetch_assoc();

            $output = array(
                'status' => 'success',
                'offer' => $offer
            );
        }else{
            e::set( 'Offer not found!', 'error' );
            $output['status'] = 'error';
        }
    }

    if( isset( $_POST ) && count( $_POST ) > 0 ){
        $offer_id = isset( $_POST['id'] ) && !empty( $_POST['id'] ) ? $_POST['id']:'';
        unset( $_POST['id'] );
        foreach( $_POST as $key => $value ){
            if( in_array( $key, $offersParams ) &&  !empty( $value ) ){
                if( $key === 'status' ){
                    $offerData['status'] = strtolower($value) == 'active' ? '1':'0';
                }else{
                    $offerData[$key] = $value;
                }
            }
        }

        if( count( $offerData ) === count( $offersParams ) && $offer_id ){
            if(preg_match( '/({{click_id}})/is', $offerData['link'] )){
                $offerData['icon'] = App::getIcon( $offerData['category'] );
                $offerData['network'] = strtolower( $offerData['network'] );
                if( db::has('offers', ['id' => $offer_id]) && db::update( 'offers', $offerData, ['id' => $offer_id] ) ){
                    e::set( 'Offer updated sucessfully', 'success' );
                    $output['status'] = 'success';
                }else{
                    e::set( 'Failed to update offer!', 'error' );
                    $output['status'] = 'error';
                }
            }else{
                e::set( '<b>{{click_id}}</b> Macro is missing!', 'error' );
                $output['status'] = 'error';
            }

        }else{
            e::set( 'Invalid form data!', 'error' );
            $output['status'] = 'error';
        }
    }



    $output['e'] = e::get();
    echo json_encode( $output );