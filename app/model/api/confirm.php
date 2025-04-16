<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    // Content type header
    header( 'Content-Type: application/json' );

    /* Security */
    Security::filterAll();
    require __DIR__.'/class/helper.php';

    /* Output Var array */
    $output = array();

    /* Trying to getting the parameters POST request */
    $param = isset( $_POST ) && count( $_POST ) > 0 ? $_POST:[];

    /* Filter all parameters to get required params.  */
    $param = Helper::getValidParam( $param, Helper::getConfirmParam() );
    if( $param && !Helper::validateRequest() ){
        echo json_encode( array(
            'status' => 'invalid',
            'title' => 'Invalid Request',
            'message' => 'The request is looks like invalid.'
        ));
        exit();
    }
    if( $param ){
        $visitor_id = (int)$param['visitor_id'];
        $required_conversion = (int)$param['required_conversion'];
        $conversions = 0;
        $query = db::query( "SELECT id FROM conv WHERE visitor_id='$visitor_id'" );
        if( $query->num_rows > 0){
            $conversions = $query->num_rows;
            if( $conversions === $required_conversion ){
                $convIds = [];
                while( $conv = $query->fetch_assoc() ){
                    $convIds[] = $conv['id'];
                }
                $output = array(
                    'status' => 'success',
                    'visitor_id' => $visitor_id,
                    'code' => 'V'.$visitor_id.'C'.implode( '-', $convIds )
                );
            }else{
                $output = array(
                    'status' => 'error',
                    'title' => 'Complete Total '.$required_conversion.' Offers',
                    'message' => 'You need to complete <b>'.($required_conversion - $conversions).'</b> more offers to get your confirmation code.<br /><br/>You have completed <b>'.$conversions.'</b> offers only.'
                );
            }
        }else{
            $output = array(
                'status' => 'error',
                'title' => 'No offer completed!',
                'message' => 'You need to complete <b>'.($required_conversion - $conversions).'</b> offers to get your confirmation code.'
            );
        }
    }else{
        $output = array(
            'status' => 'error',
            'title' => 'Invalid Request!',
            'message' => 'Invalid request, required information not found!'
        );
    }

    echo json_encode( $output );
    db::close();
    e::clear();
    exit;