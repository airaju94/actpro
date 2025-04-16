<?php

    /* Prevent Direct Access */
    defined('BASE') or die('No Direct Access!');

    class Offers {

        function __construct(){}


        public static function has( $offer_id ){
            return !empty( $offer_id ) ? db::has( 'offers', ['id' => $offer_id] ):false;
        }

        public static function get( $offer_id ){
            return !empty( $offer_id ) ? db::query( "SELECT * FROM `offers` WHERE `id` = '$offer_id' AND `status`='1'" ):false;
        }

        public static function isACTPRO( $offer_id ){
            return !empty( $offer_id ) ? db::has('offers', ['id' => $offer_id, 'status' => '1', 'network' => 'actpro']):false;
        }

        public static function getAll( $limit = 20 ){
            return db::query( "SELECT * FROM `offers` WHERE `status`='1' ORDER BY conversion DESC LIMIT 0,$limit" );
        }

        public static function delete( $offer_id ){
            return !empty( $offer_id ) ? db::delete( 'offers', ['id' => $offer_id] ):false;
        }

        public static function getOfferClickTrackingLink( $offer_id, $click_id ){
            if( empty( $offer_id ) || empty( $click_id ) ){
                return false;
            }
            if( $offer = self::get( $offer_id ) ){
                $offer_link = $offer->fetch_assoc()['link'];
                return str_replace( '{{click_id}}', $click_id, $offer_link );
            }
            return false;
        }

        public static function getOfferTrackingLink( $offer_id, $visitor_id ){
            if( empty( $offer_id ) || empty( $visitor_id ) ){
                return false;
            }
            return Url::baseUrl().'/api/click?offer_id='.$offer_id.'&visitor_id='.$visitor_id;
        }

    }