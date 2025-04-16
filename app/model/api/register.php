<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    // output content type
    header( 'Content-Type: application/json' );
    
    /* Security */
    Security::filterAll();
    require __DIR__.'/class/helper.php';
    require __DIR__.'/class/visitor.php';

    /* Output Var array */
    $output = array();

    /* Trying to getting the parameters from POST request */
    $param = isset( $_POST ) && count( $_POST ) > 0 ? $_POST:[];

    /* Filter all parameters to get required params.  */
    $param = Helper::getValidParam( $param, Helper::getVisitorParam() );

    if( $param && !Helper::validateRequest() ){
        echo json_encode( array(
            'status' => 'invalid',
            'message' => 'invalid request',
        ));
        exit();
    }

    if( $param ){
        if( $visitor_id = Visitor::register( $param ) ){
            $output = array(
                'status' => 'success',
                'visitor_id' => $visitor_id
            );
        }else{
            $output = array(
                'status' => 'error',
                'visitor_id' => (int) Visitor::getVisitorId()
            );
        }
    }else{
        $output = array(
            'status' => 'error',
            'visitor_id' => (int) Visitor::getVisitorId()
        );
    }

    db::close();
    echo json_encode( $output );
    $output = [];
    exit();
