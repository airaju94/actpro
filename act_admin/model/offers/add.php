<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    header( 'Content-Type: application/json' );

    Security::filterAll();
    
    $offersParams = array('title', 'description', 'category', 'status', 'link', 'time', 'network');

    $output = [];
    $offerData = [];

    if( isset( $_POST ) && count( $_POST ) > 0 ){
        foreach( $_POST as $key => $value ){
            if( in_array( $key, $offersParams ) &&  !empty( $value ) ){
                if( $key === 'status' ){
                    $offerData['status'] = strtolower($value) == 'active' ? '1':'0';
                }else{
                    $offerData[$key] = $value;
                }
            }
        }

        if( count( $offerData ) === count( $offersParams ) ){
            if(preg_match( '/({{click_id}})/is', $offerData['link'] )){
                $offerData['icon'] = App::getIcon( $offerData['category'] );
                $offerData['network'] = strtolower( $offerData['network'] );
                $offerData['conversion'] = mt_rand( 100, 999 );
                if( !db::has('offers', ['link' => $offerData['link']]) && db::insert( 'offers', $offerData ) ){
                    e::set( 'Offer added sucessfully', 'success' );
                    $output = array(
                        'status' => 'success',
                        'e' => e::get()
                    );
                }else{
                    e::set( 'Failed to add offer or offer may exists!', 'error' );
                    $output = array(
                        'status' => 'error',
                        'e' => e::get()
                    );
                }
            }else{
                e::set( '<b>{{click_id}}</b> Macro is missing!', 'error' );
                $output = array(
                    'status' => 'error',
                    'e' => e::get()
                );
            }

        }else{
            e::set( 'Invalid form data!', 'error' );
            $output = array(
                'status' => 'error',
                'e' => e::get()
            );
        }
    }else{
        e::set( 'Unauthorized request!' );
        $output = array(
            'status' => 'error',
            'e' => e::get()
        );
    }
    echo json_encode( $output );