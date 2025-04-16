<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    class Helper {

        function __construct(){}

        public static function getValidParam( array $param, array $required = [] ){
            if( count( $param ) == 0 ){
                return false;
            }

            if( count( $param ) > 0 && count( $required ) > 0 ){
                $count = 0;
                $paramData = [];
                foreach( $required as $key ){
                    if( isset( $param[$key] ) && $param[$key] !='' ){
                        $paramData[$key] = $param[$key];
                        $count++;
                    }
                }
                return $count === count( $required ) ? $paramData:array();
            }

            if( count( $param ) > 0 && count( $required ) == 0 ){
                $count = 0;
                foreach( $param as $key => $value ){
                    if( $value !=='' ){
                        $count++;
                    }
                }
                return $count === count( $param ) ? true:array();
            }
        }

        public static function validateRequest(){
            $referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER']:'';
            if( empty( $referer ) ){ return false; }
            return true;
        }

        public static function getVisitorParam(){
            return ['source', 'medium', 'zone_id', 'cost'];
        }

        public static function getClicksParam(){
            return ['visitor_id', 'offer_id'];
        }

        public static function getConvParam(){
            return ['click_id', 'payout', 'network'];
        }

        public static function getOfferParam(){
            return ['visitor_id'];
        }

        public static function offerDirectlinkParam(){
            return ['offer_id', 'source', 'medium', 'zone_id', 'cost'];
        }
        public static function getConfirmParam(){
            return ['visitor_id', 'required_conversion'];
        }

    }