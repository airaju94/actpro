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
    require __DIR__.'/class/conversion.php';

    /* Output Var array */
    $output = array();

    /* Trying to getting the parameters from GET request or POST request */
    $param = count( $_GET ) > 0 ? $_GET:( count( $_POST ) > 0 ? $_POST:[] );

    /* Filter all parameters to get required params.  */
    $param = Helper::getValidParam( $param, Helper::getConvParam() );

    if( $param ){
        if( $conv_id = Conversion::register_s2s( $param ) ){
            $output = array(
                'status' => 'success',
                'conv_id' => $conv_id,
                'click_id' => $param['click_id'],
            );
        }else{
            $output = array(
                'status' => 'error',
                'conv_id' => false,
                'click_id' => false,
            );
        }
    }else{
        $output = array(
            'status' => 'error',
            'conv_id' => false,
            'click_id' => false,
        );
    }

    db::close();
    echo json_encode( $output );
    $output = [];
    exit();
