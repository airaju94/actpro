<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );


    class Clicks {

        function __construct(){}

        public static function register( array $data ){
            if( count( $data ) == 0 || empty( $data['visitor_id'] ) || empty( $data['offer_id'] ) ){
                return false;
            }
            $visitor_id = $data['visitor_id'];
            $offer_id = $data['offer_id'];
            if( $click_id = db::insert( 'clicks', ['visitor_id' => $visitor_id, 'offer_id' => $offer_id ] ) ){
                return $click_id;
            }
        }

        public static function get( $click_id ){
            return !empty( $click_id ) ? db::query( "SELECT * FROM `clicks` WHERE `id` = '$click_id'" ):false;
        }

        public static function has( $click_id ){
            return !empty( $click_id ) ? db::has( 'clicks', ['id' => $click_id] ):false;
        }

        public static function delete( $click_id ){
            return !empty( $click_id ) ? db::delete('clicks', ['id' => $click_id]):false;
        }

        public static function update_is_converted( $click_id ){
            return !empty( $click_id ) ? db::update('clicks', ['is_converted' => '1'], ['id' => $click_id ]):false;
        }

    }