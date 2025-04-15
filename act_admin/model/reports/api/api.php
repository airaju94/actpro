<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );
    header( 'Content-Type: application/json' );
    Security::filterAll();
    require_once __DIR__.'/reports.php';

    $output = [];
    if( isset( $_GET['range'] ) &&
        !empty( $_GET['range'] ) && 
        isset( $_GET['report'] ) && 
        !empty( $_GET['report'] ) ){
        
        /**
         *
         * Start Generating report 
         * 
         */

        $range = $_GET['range'];
        $report = strtolower( $_GET['report'] );
        $dimension = isset( $_GET['dimension'] ) ? $_GET['dimension']:'';

        // where sql builder
        $whereSql = '';
        $whereArray = [];
        $where = isset( $_GET['where'] ) && !empty( $_GET['where'] ) ? $_GET['where']:false;
        if( $where ){
            $whereArray = explode( "_", $where );
            $whereSql = " AND ".$whereArray[0]."='".$whereArray[1]."' ";
        }

        $output['reportType'] = $report;
        $output['range'] = $range;

        /* Dashboard Overview Report */
        if( $report === 'dashboard' ){
            $output['cards'] = Report::cards( $range );

            $output['charts'] = array(
                'performance' => Report::performance( $range ),
                'topCountries' => Report::topCountries( $range ),
                'topNetwork' => Report::topNetwork( $range ),
                'topOffers' => Report::topOffers(),
                'topSource' => Report::topSource( $range ),
                'topMedium' => Report::topMedium( $range ),
                'topZoneId' => Report::topZoneId( $range )
            );
    
            $output['tables'] = array(
                'recentConversion' => Report::recentConversion('conv', ['id', 'visitor_id', 'offer_id', 'network', 'payout', 'ip', 'country', 'source', 'medium', 'zone_id']),
            );
        }

        if( $report === 'performance' ){
            $output['charts'] = array(
                'performanceChart' => Report::performance( $range ),
            );
            $output['tables'] = array(
                'performanceTable' => Report::dynamicTable(
                    "SELECT date,visitor,clicks,conversion,cr,epc,cost,earnings FROM stats WHERE date BETWEEN :from AND :to ORDER BY date DESC",
                    $range
                ),
            );
        }

        if( $report === 'conversions' && $dimension ){
            $output['charts'] = array(
                'conversionsChart' => Report::dynamicChart( "SELECT $dimension,COUNT(DISTINCT id ) as conversions, ROUND( SUM(payout), 2) as earnings, ROUND(CONCAT(ROUND( SUM( payout ), 2 ) / COUNT(DISTINCT id)), 2) as epc FROM conv WHERE date BETWEEN :from AND :to $whereSql GROUP BY $dimension ORDER BY date ASC, conversions DESC", $range, $dimension, ['conversions', 'epc', 'earnings']),
            );
            $output['tables'] = array(
                'conversionsTable' => Report::dynamicTable(
                    "SELECT $dimension,".(isset($whereArray[0]) ? $whereArray[0].',':'')."COUNT(DISTINCT id) as conversions, ROUND( SUM( payout ), 2 ) as earnings, ROUND(CONCAT(ROUND( SUM( payout ), 2 ) / COUNT(DISTINCT id)), 2) as epc FROM conv WHERE date BETWEEN :from AND :to $whereSql GROUP BY $dimension ORDER BY conversions DESC",
                    $range
                ),
            );
        }

        if( $report === 'visitors' && $dimension ){
            $output['charts'] = array(
                'visitorsChart' => Report::dynamicChart( "SELECT $dimension,COUNT(DISTINCT id ) AS visitors, ROUND( SUM(cost), 2) AS cost, ROUND(CONCAT(ROUND( SUM(cost), 2) / COUNT(DISTINCT id ), 4) * 1000) AS cpm FROM visitor WHERE date BETWEEN :from AND :to $whereSql GROUP BY $dimension ORDER BY visitors ASC", $range, $dimension, ['visitors', 'cpm', 'cost']),
            );
            $output['tables'] = array(
                'visitorsTable' => Report::dynamicTable(
                    "SELECT $dimension,".(isset($whereArray[0]) ? $whereArray[0].',':'')."COUNT(DISTINCT id) as visitors, ROUND( SUM( cost ), 4 ) as cost, ROUND(CONCAT(ROUND( SUM(cost), 4) / COUNT(DISTINCT id ), 4) * 1000) AS cpm FROM visitor WHERE date BETWEEN :from AND :to $whereSql GROUP BY $dimension ORDER BY visitors DESC",
                    $range
                ),
            );
        }

    }else{
        e::set( 'Invalid report parameter!', 'error' );
        $output = array(
            'status' => 'error',
            'e' => e::get(),
        );
    }


    
    echo json_encode( $output );
    $output = [];

