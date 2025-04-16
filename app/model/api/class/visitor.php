<?php

    /* Prevent Direct Access */
    defined('BASE') or die('No Direct Access!');

    class Visitor {

        function __construct() {}

        public static function register( array $data ){
            $required_data = ['source', 'medium', 'zone_id', 'cost'];
            if( count( $data ) > 0 ){
                $data['ip'] = get_user_ip();
                $data['country'] = get_country();
                $data['referer'] = get_referer_host();
                $data['ua'] = get_ua();

                foreach( $required_data as $param ){
                    if( !isset( $data[$param] ) || empty( $data[$param] ) ){
                        if( $param === 'cost' ){
                            $data[$param] = 0;
                        }else{
                            $data[$param] = 'direct';
                        }
                    }
                }

                if( !db::has( 'visitor', ['ip' => get_user_ip(), 'date' => db_date()] ) ){
                    return db::insert( 'visitor', $data );
                }else{
                    return self::getVisitorId();
                }
            }
            return false;
        }

        public static function get( $visitor_id ){
            return !empty( $visitor_id ) ? db::query( "SELECT * FROM `visitor` WHERE `id` = '$visitor_id'" ):false;
        }

        /**
         *  @param Void 
         * @return Integer
         * Note: only return if visitor registered today.
         */
        public static function getVisitorId(){
            $getVisitor =  db::query( "SELECT * FROM `visitor` WHERE `ip` = '".get_user_ip()."' AND DATE(date) = '".db_date()."' LIMIT 1" );
            if( $getVisitor->num_rows > 0 ){
                return $getVisitor->fetch_assoc()['id'];
            }
            return false;
        }

        public static function has( $visitor_id){
            return !empty( $visitor_id ) ? db::has( 'visitor', ['id' => $visitor_id] ):false;
        }

        public static function delete( $visitor_id ){
            return !empty( $visitor_id ) ? db::delete( 'visitor', ['id' => $visitor_id] ):false;
        }

    }