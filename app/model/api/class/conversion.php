<?php

    /* Prevent Direct Access */
    defined('BASE') or die('No Direct Access!');

    class Conversion {

        function __construct(){}

        public static function register_actpro( array $conv ){
            /* Validate Conversion Data */
            if( !self::validateConversion( $conv ) ){
                return false;
            }

            /* get clicks data */
            $click_id = $conv['click_id'];
            $clicks = Clicks::get( $click_id );
            if( $clicks->num_rows !== 1 ){
                return false;
            }

            $clicks = $clicks->fetch_assoc();
            /* get visitor data */
            $visitor_id = $clicks['visitor_id'];
            $visitor = Visitor::get( $visitor_id );
            
            if( $visitor->num_rows !== 1 ){
                return false;
            }

            $visitor = $visitor->fetch_assoc();

            /* check duplicate conversion */
            if( self::has( $click_id ) ){
                return false;
            }

            // check is custom act offers or not
            if( !Offers::isACTPRO( $clicks['offer_id'] ) ){
                return false;
            }

            /* now register conversion and update offer, clicks */
            $dataForInsert = array(
                'visitor_id' => $visitor_id,
                'click_id' => $click_id,
                'offer_id' => $clicks['offer_id'],
                'payout' => $conv['payout'],
                'network' => $conv['network'],
                'source' => $visitor['source'],
                'medium' => $visitor['medium'],
                'zone_id' => $visitor['zone_id'],
                'ip' => $visitor['ip'],
                'country' => $visitor['country'],
                'ua' => $visitor['ua'],
                'referer' => $visitor['referer'],
            );

            if( $convId = db::insert( 'conv', $dataForInsert ) ){
                return $convId;
            }

            return false;
        }

        public static function register_s2s( array $conv ){
            /* Validate Conversion Data */
            if( !self::validateConversion( $conv ) ){
                return false;
            }

            /* get clicks data */
            $click_id = $conv['click_id'];
            $clicks = Clicks::get( $click_id );
            if( $clicks->num_rows !== 1 ){
                return false;
            }

            $clicks = $clicks->fetch_assoc();
            /* get visitor data */
            $visitor_id = $clicks['visitor_id'];
            $visitor = Visitor::get( $visitor_id );
            
            if( $visitor->num_rows !== 1 ){
                return false;
            }

            $visitor = $visitor->fetch_assoc();

            /* check duplicate conversion */
            if( self::has( $click_id ) ){
                return false;
            }

            /* now register conversion and update offer, clicks */
            $dataForInsert = array(
                'visitor_id' => $visitor_id,
                'click_id' => $click_id,
                'offer_id' => $clicks['offer_id'],
                'payout' => $conv['payout'],
                'network' => $conv['network'],
                'source' => $visitor['source'],
                'medium' => $visitor['medium'],
                'zone_id' => $visitor['zone_id'],
                'ip' => $visitor['ip'],
                'country' => $visitor['country'],
                'ua' => $visitor['ua'],
                'referer' => $visitor['referer'],
            );

            if( $convId = db::insert( 'conv', $dataForInsert ) ){
                return $convId;
            }

            return false;
        }

        public static function has( $click_id ){
            return !empty( $click_id ) ? db::has('conv', ['click_id' => $click_id]):false;
        }

        public static function get( $conversion_id ){
            return !empty( $conversion_id ) ? db::query( "SELECT * FROM `conv` WHERE `id`='$conversion_id'" ):false;
        }

        public static function delete( $conversion_id ){
            return !empty( $conversion_id ) ? db::delete( 'conv', ['id' => $conversion_id] ):false;
        }

        public static function validateConversion( array $data ){
            if( count( $data ) === 0 ){
                return false;
            }

            $required_param = ['click_id', 'payout', 'network'];
            $count = 0;
            foreach( $required_param as $param ){
                if( isset( $data[$param] ) && $data[$param] !='' ){
                    $count++;
                }
            }
            return $count === count( $required_param ) ? true:false;
        }

    }