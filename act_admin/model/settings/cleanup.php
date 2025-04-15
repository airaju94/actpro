<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );
    
    Security::filterAll();
    /**
     * Clean up database records based on time range
     * 
     * @param string $range Time range (today, yesterday, 7days, 30days, 60days, 90days)
     * @return array Array with success status and operation details
     */
    function cleanup( $range ){
        $tables = ['visitor', 'clicks', 'conv', 'stats'];
        // Validate range and set dates
        $dateConfig = [
            'today' => [
                'start' => date('Y-m-d'),
                'end' => date('Y-m-d'),
                'label' => 'today'
            ],
            'yesterday' => [
                'start' => date('Y-m-d', strtotime('-1 day')),
                'end' => date('Y-m-d', strtotime('-1 day')),
                'label' => 'yesterday'
            ],
            '7days' => [
                'start' => date('Y-m-d', strtotime('-7 days')),
                'end' => date('Y-m-d', strtotime('-1 day')),
                'label' => 'last 7 days'
            ],
            '30days' => [
                'start' => date('Y-m-d', strtotime('-30 days')),
                'end' => date('Y-m-d', strtotime('-1 day')),
                'label' => 'last 30 days'
            ],
            '60days' => [
                'start' => date('Y-m-d', strtotime('-60 days')),
                'end' => date('Y-m-d', strtotime('-1 day')),
                'label' => 'last 60 days'
            ],
            '90days' => [
                'start' => date('Y-m-d', strtotime('-90 days')),
                'end' => date('Y-m-d', strtotime('-1 day')),
                'label' => 'last 90 days'
            ],
            'all' => []
        ];
        
        if (!isset($dateConfig[$range])) {
            e::set( 'Invalid range specified. Valid options: today, yesterday, 7days, 30days, 60days, 90days', 'error' );
            return false;
        }
        
        $dates = $dateConfig[$range];
        
        // Process each table
        foreach ($tables as $table) {
            try {
                if( $range == 'all' ){
                    $sql = "DELETE FROM `$table`";
                }else{
                    if ($dates['start'] === $dates['end']) {
                        $sql = "DELETE FROM `$table` WHERE DATE(date) = '{$dates['start']}'";
                    } else {
                        $sql = "DELETE FROM `$table` WHERE DATE(date) BETWEEN '{$dates['start']}' AND '{$dates['end']}'";
                    }
                }

                $query = db::query($sql);
                
                if ($query === false) {
                    throw new Exception("Database query failed");
                }
                
                $affectedRows = mysqli_affected_rows(db::connect());
                
                e::set( number_format( $affectedRows )." Records deleted from $table", 'success' );
                
            } catch (Exception $e) {
    			e::set( "Failed to delete data from $table :".$e->getMessage(), 'error' );
            }
        }
        
        return true;
    }
    
    $output = [];
    if( isset( $_POST['range'] ) && !empty( $_POST['range'] ) ){
    	$range = $_POST['range'];
    	
    	if( cleanup( $range ) ){
    		$output = array(
    			'status' => 'success'
    		);
    	}else{
    		$output = array(
    			'status' => 'error'
    		);
    	}
    }
    
    
    if( !isAjax() ){
        $viewData = array(
            'title' => 'Clean Up Database',
            'header_code' => '',
            'footer_code' => '',
            'page' => 'cleanup'
        );
        echo View::render('settings/cleanup', $viewData);
    }else{
        header( 'Content-Type: application/json' );
        $output['e'] = e::get();
        echo json_encode( $output );
    }