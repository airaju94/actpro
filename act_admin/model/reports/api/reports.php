<?php
/**
 * Report - Generates analytical reports for CPA tracking system
 * 
 * Provides methods to generate cards, charts (including multi-dimension), and tables
 * with JSON output. Uses db::query() for database operations.
 */

/* Prevent Direct Access */
defined( 'BASE' ) or die( 'No Direct Access!' );

class Report {
    
    private static $date_range = [
        'today' => 'Today',
        'yesterday' => 'Yesterday', 
        '7days' => ' -7 Days',
        '14days' => '-14 Days',
        '30days' => '-30 Days',
        '60days' => '-60 Days',
        '90days' => '-90 Days'
    ];
    
    private static $colors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', 
        '#e74a3b', '#858796', '#5a5c69', '#2e59d9'
    ];

    public static function timeframe( string $timeframe ){
        if( isset( self::$date_range[strtolower( $timeframe )] ) ){
            return array(
                'from' => date( 'Y-m-d', strtotime( self::$date_range[strtolower( $timeframe )] ) ),
                'to' => $timeframe == 'yesterday' ? date("Y-m-d", strtotime($timeframe)):db_date(),
            );
        }
        return false;
    }

    /**
     * Converts MySQL result data into chart-ready format
     * 
     * @param mysqli_result $result MySQL result object
     * @param string $labelField Field name to use as labels (X-axis)
     * @param array $dataFields Array of field names to use as data series (Y-axis)
     * @param string $chartType Type of chart ('bar', 'line', 'pie', 'doughnut')
     * @return array Formatted chart data
     * 
     * Example Usages
     * ===============
     *  $chartData = chart(
     *      $result, 
     *      'month',          // Use 'month' field as labels
     *      ['sales', 'expenses'], // Use these fields as data series
     *      'bar'             // Chart type
     *   );
     */
    public static function chart($result, $labelField, $dataFields, $chartType = 'bar') {
        $chartData = [
            'labels' => [],
            'datasets' => [],
            'type' => $chartType
        ];
        
        // Initialize datasets
        foreach ($dataFields as $field) {
            $chartData['datasets'][] = [
                'label' => ucwords( $field ),
                'data' => [],
            ];
        }
        
        // Process each row
        while ($row = $result->fetch_assoc()) {
            // Add label
            $chartData['labels'][] = ucwords( ellipsis( $row[$labelField], 15 ) );
            
            // Add data to each dataset
            foreach ($dataFields as $index => $field) {
                $chartData['datasets'][$index]['data'][] = $row[$field];
            }
        }
        
        // // For pie/doughnut charts, we need a different structure
        // if ($chartType === 'pie' || $chartType === 'doughnut') {
        //     $newChartData = [
        //         'labels' => $chartData['labels'],
        //         'datasets' => [
        //             'data' => $chartData['datasets'][0]['data'],
        //             'label' => $chartData['datasets'][0]['label']
        //         ],
        //         'type' => $chartType
        //     ];
        //     return $newChartData;
        // }
        
        return $chartData;
    }

    /**
     * Converts MySQL result into an HTML table
     * 
     * @param mysqli_result $result MySQL result object
     * @param array $options Optional settings:
     *        - 'class' => CSS class for the table
     *        - 'headers' => Array of custom column headers
     *        - 'empty_message' => Message to display when no results
     *        - 'id' => ID attribute for the table
     *        - 'skip_columns' => Array of column names to exclude
     * @return string HTML table
     */
    public static function table($result, $options = []) {
        // Default options
        $defaults = [
            'class' => 'table table-striped',
            'headers' => null,
            'empty_message' => 'No records found',
            'id' => '',
            'skip_columns' => []
        ];
        $dollarSign = ['payout', 'earnings', 'cpv', 'epc', 'cost', 'cpm'];
        $options = array_merge($defaults, $options);

        $html = '';
        
        if ($result->num_rows === 0) {
            return "<div class='py-5 text-center'><i class='bi bi-info-circle-fill d-block me-3
></i><h3 class='d-block text-muted mt-2'>{$options['empty_message']}</h3></div>";
        }

        // Start table
        $id_attr = $options['id'] ? " id='{$options['id']}'" : '';
        $html .= "<table class='{$options['class']}'{$id_attr}>\n";

        // Get column names
        $fields = $result->fetch_fields();
        $columns = [];
        
        foreach ($fields as $field) {
            if (!in_array($field->name, $options['skip_columns'])) {
                $columns[] = $field->name;
            }
        }

        // Table headers
        $html .= "<thead><tr>\n";
        foreach ($columns as $index => $column) {
            $header = $options['headers'][$column] ?? ucwords(str_replace('_', ' ', $column));
            $html .= "<th>{$header}</th>\n";
        }
        $html .= "</tr></thead>\n";

        // Table body
        $html .= "<tbody>\n";
        while ($row = $result->fetch_assoc()) {
            $html .= "<tr>\n";
            foreach ($columns as $column) {
                $value = $row[$column] ?? '';
                $value = in_array(strtolower( $column ), $dollarSign) ? '$'.$value:$value;
                if( $column == 'id' || $column == 'payout' || $column == 'zone_id' ){
                    $html .= "<td class='fw-bold col-{$column}'>{$value}</td>\n";
                }else{
                    $html .= "<td>{$value}</td>\n";
                }
            }
            $html .= "</tr>\n";
        }
        $html .= "</tbody>\n";

        // Close table
        $html .= "</table>\n";

        return $html;
    }

    public static function cards( string $timeframe ){
        if( empty( $timeframe ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $from = $range['from']; $to = $range['to'];

        $cards = array(
                'visitor' => 0, 'clicks' => 0, 'conversion' => 0,
                'earnings' => 0.00, 'cr' => 0.00, 'epc' => 0.00,
                'ctr' => 0.00, 'epv' => 0.00
            );

        $query = db::query( "SELECT SUM(visitor) AS visitor, SUM(clicks) AS clicks, SUM(conversion) AS conversion, ROUND(SUM(earnings),2) AS earnings, ROUND(AVG(cr),2) AS cr, ROUND(AVG(epc),2) AS epc, ROUND(SUM(cost),2) AS cost FROM `stats` WHERE date BETWEEN '$from' AND '$to'" );
        if( $query->num_rows > 0 ){
            $stats = $query->fetch_assoc();
            if( $stats['visitor'] > 0 ){
                $cards['visitor'] = number_format( $stats['visitor'] );
                $cards['clicks'] = number_format( $stats['clicks'] );
                $cards['ctr'] = round( ((int)$stats['clicks'] / (int)$stats['visitor'] * 100), 2);
                $cards['conversion'] = number_format( $stats['conversion'] );
                $cards['earnings'] = $stats['earnings'];
                $cards['epc'] = $stats['epc'];
                $cards['epv'] = round( ((float)$stats['earnings'] / (int)$stats['visitor']), 3);
                $cards['cr'] = $stats['cr'];
                $cards['cost'] = $stats['cost'];
            }
        }

        $stats = '';
        return $cards;
    }

    public static function performance( string $timeframe, $order = 'ASC' ){
        if( empty( $timeframe ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $query = db::query( "SELECT date, visitor, clicks, conversion, earnings, cr, epc, cost FROM `stats` WHERE `date` BETWEEN '".$range['from']."' AND '".$range['to']."' GROUP BY date ORDER BY `date` $order" );
        if( $query->num_rows > 0 ){
            return self::chart($query, 'date', ['visitor', 'clicks', 'conversion', 'earnings', 'cr', 'epc', 'cost'], ($timeframe == 'today' || $timeframe == 'yesterday' ? 'bar':'line'));
        }
        return false;
    }

    public static function topCountries( string $timeframe, $limit = 10 ){
        if( empty( $timeframe ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $from = $range['from']; $to = $range['to'];
        $limit = $limit > 0 ? "LIMIT $limit":'';
        $query = db::query( "SELECT country, COUNT(DISTINCT id) as Conversion, ROUND( SUM( payout ), 2 ) as Earnings FROM conv WHERE date BETWEEN '$from' AND '$to' GROUP BY country ORDER BY Earnings DESC $limit"  );
        if( $query->num_rows > 0 ){
            return self::chart( $query, 'country', ['Conversion', 'Earnings'], 'doughnut' );
        }
        return false;
    }

    public static function topNetwork( string $timeframe, $limit = 10 ){
        if( empty( $timeframe ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $from = $range['from']; $to = $range['to'];
        $limit = $limit > 0 ? "LIMIT $limit":'';
        $query = db::query( "SELECT network, COUNT(DISTINCT id) as Conversion, ROUND( SUM( payout ), 2 ) as Earnings FROM conv WHERE date BETWEEN '$from' AND '$to' GROUP BY network ORDER BY Earnings DESC $limit"  );
        if( $query->num_rows > 0 ){
            return self::chart( $query, 'network', ['Conversion', 'Earnings'], 'bar' );
        }
        return false;
    }

    public static function topOffers( $limit = 10){
        $query = db::query( "SELECT title, clicks, conversion, revenue FROM offers ORDER BY revenue DESC LIMIT $limit"  );
        if( $query->num_rows > 0 ){
            return self::chart( $query, 'title', ['clicks', 'conversion', 'revenue'], 'bar' );
        }
        return false;
    }

    public static function topSource( string $timeframe, $limit = 10 ){
        if( empty( $timeframe ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $from = $range['from']; $to = $range['to'];
        $limit = $limit > 0 ? "LIMIT $limit":'';
        $query = db::query( "SELECT source, COUNT(DISTINCT id) as Conversion, ROUND( SUM( payout ), 2 ) as Earnings FROM conv WHERE date BETWEEN '$from' AND '$to' GROUP BY source ORDER BY Earnings DESC $limit"  );
        if( $query->num_rows > 0 ){
            return self::chart( $query, 'source', ['Conversion', 'Earnings'], 'doughnut' );
        }
        return false;
    }

    public static function topMedium( string $timeframe, $limit = 10 ){
        if( empty( $timeframe ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $from = $range['from']; $to = $range['to'];
        $limit = $limit > 0 ? "LIMIT $limit":'';
        $query = db::query( "SELECT medium, COUNT(DISTINCT id) as Conversion, ROUND( SUM( payout ), 2 ) as Earnings FROM conv WHERE date BETWEEN '$from' AND '$to' GROUP BY medium ORDER BY Earnings DESC $limit"  );
        if( $query->num_rows > 0 ){
            return self::chart( $query, 'medium', ['Conversion', 'Earnings'], 'doughnut' );
        }
        return false;
    }

    public static function topZoneId( string $timeframe, $limit = 10 ){
        if( empty( $timeframe ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $from = $range['from']; $to = $range['to'];
        $limit = $limit > 0 ? "LIMIT $limit":'';
        $query = db::query( "SELECT zone_id, COUNT(DISTINCT id) as Conversion, ROUND( SUM( payout ), 2 ) as Earnings FROM conv WHERE date BETWEEN '$from' AND '$to' GROUP BY zone_id ORDER BY Earnings DESC $limit"  );
        if( $query->num_rows > 0 ){
            return self::chart( $query, 'zone_id', ['Conversion', 'Earnings'], 'doughnut' );
        }
        return false;
    }

    public static function recentConversion( $table, $columns = [] ){
        $query = db::query( "SELECT ".implode( ", ", $columns ).",date FROM `$table` ORDER  BY id DESC LIMIT 30" );
        return self::table( $query );
    }

    public static function dynamicTable( $sql, $timeframe ){
        if( empty( $timeframe ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $from = $range['from']; $to = $range['to'];
        $sql = str_replace( [':from', ':to'], ["'$from'", "'$to'"], $sql );
        $query = db::query( $sql );
        return self::table( $query );
    }

    public static function dynamicChart( $sql, $timeframe, $labelField, $dataFields ){
        if( empty( $timeframe ) || empty($labelField) || empty($sql) || empty( $dataFields ) ){ return false; }
        $range = self::timeframe( $timeframe );
        $from = $range['from']; $to = $range['to'];
        $sql = str_replace( [':from', ':to'], ["'$from'", "'$to'"], $sql );
        $query = db::query( $sql );

        return self::chart( $query, $labelField, $dataFields, 'line' );
    }

}