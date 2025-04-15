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
    $param = isset( $_POST ) && count( $_POST ) > 0 ? $_POST:[];

    /* Filter all parameters to get required params.  */
    $param = Helper::getValidParam( $param, Helper::getConvParam() );

    if( $param && !Helper::validateRequest( $param['click_id'] ) ){
        echo json_encode( array(
            'status' => 'invalid',
            'message' => 'invalid request',
        ));
        exit();
    }

    if( $param ){
        if( $conv_id = Conversion::register_actpro( $param ) ){
            $output = array(
                'status' => 'success',
            );
        }else{
            $output = array(
                'status' => 'error',
            );
        }
    }else{
        $output = array(
            'status' => 'error',
        );
    }

    db::close();
    echo json_encode( $output );
    $output = [];
    exit();
