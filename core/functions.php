<?php

	/*
	* -------------------------------------
	* | CodeDynamic                       |
	* -------------------------------------
	* 
	* @Author: A.I Raju
	* @License: MIT
	* @Copyright: CodeDynamic 2024
	* @File: Common.php
	* @Version: 1
	* 
	*/

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );


    function get_user_ip() {
        $ip = '';
        // Check for shared/cloudflare/load balancer IPs first
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // HTTP_X_FORWARDED_FOR can contain multiple IPs (proxy chain)
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]); // First IP in the chain is client IP
        }
        // Validate the IP address
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }
        // Fallback to REMOTE_ADDR (but validate it too)
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }

    /**
     * Detects visitor's country using HTTP headers with multiple fallback methods
     * 
     * Priority order:
     * 1. Cloudflare's CF-IPCountry header (if using Cloudflare)
     * 2. Fastly's GeoIP header (if using Fastly)
     * 3. AWS's CloudFront-Viewer-Country header (if using AWS CloudFront)
     * 4. Standard GeoIP headers (GEOIP_*)
     * 5. Fallback to IP-based geolocation (requires extension)
     * 
     * @return string|null 2-letter country code (ISO 3166-1 alpha-2) or null if not detectable
     */
    function get_country() {
        // Common CDN/Proxy headers
        $cdnHeaders = [
            'HTTP_CF_IPCOUNTRY',    // Cloudflare
            'HTTP_CLOUDFRONT_VIEWER_COUNTRY', // AWS CloudFront
            'HTTP_FASTLY_CLIENT_COUNTRY',     // Fastly
            'GEOIP_COUNTRY_CODE',   // Apache mod_geoip
            'HTTP_X_COUNTRY_CODE',  // Custom header
        ];

        // Check CDN/proxy headers first
        foreach ($cdnHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $country = strtoupper(trim($_SERVER[$header]));
                if (preg_match('/^[A-Z]{2}$/', $country)) {
                    return $country;
                }
            }
        }

        return 'N/A';
    }

    function get_referer_host(){
        // Correct the common typo in $_SERVER['HTTP_REFERER'] (note: it's REFERER in specs)
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        
        if (empty($referer)) {
            return 'N/A';
        }
        // Parse URL and validate host component exists
        $components = parse_url($referer);
        if (!isset($components['host'])) {
            return 'N/A';
        }
        // Sanitize and normalize the host
        $host = strtolower($components['host']);
        return $host;
    }

	function get_ua(){
		return $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
	}

    /**
     * Check if the current request is an AJAX request
     * 
     * Properly detects AJAX requests by checking:
     * - The X-Requested-With HTTP header (most reliable)
     * - isAjax parameter in GET/POST (legacy support)
     * - HTTP_X_REQUESTED_WITH server variable
     * 
     * @return bool True if AJAX request, false otherwise
     */
    function isAjax(): bool {
        // Most reliable method - check X-Requested-With header
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return true;
        }

        // Check Content-Type header for JSON requests
        if (isset($_SERVER['CONTENT_TYPE']) && 
            strpos(strtolower($_SERVER['CONTENT_TYPE']), 'application/json') !== false) {
            return true;
        }

        // Legacy support for isAjax parameter
        if (isset($_GET['isAjax']) || isset($_POST['isAjax'])) {
            return true;
        }

        // Additional check for common AJAX headers
        $headers = getallheaders();
        if (isset($headers['X-Requested-With']) && 
            strtolower($headers['X-Requested-With']) === 'xmlhttprequest') {
            return true;
        }

        return false;
    }

	function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	function ellipsis( $string, $max ){
		if( mb_strlen( $string ) > $max ){
			return  mb_substr( $string, 0, $max ).' ...';
		}else{
			return $string;
		}
	}

	function validateForm( Array $formData, Array $exclude = [] ){
		if( is_array( $formData ) && count( $formData ) > 0 && is_array( $exclude ) ){
			$totalItem = count( $formData ) - count( $exclude );

			$pass = 0;
			foreach( $formData as $key => $value ){
				if( !in_array( $key, $exclude ) && $value !=='' ){
					$pass += 1;
				}
			}
			return $pass === $totalItem ? true:false;
		}
	}

	function getAgoTime( $date ){ 
		date_default_timezone_set("Asia/Dhaka");
		$now_timestamp = strtotime(date('Y-m-d H:i:s')); 
		$diff_timestamp = $now_timestamp - strtotime($date);
		
		if($diff_timestamp < 60){
			return 'few seconds ago'; 
		}
		else if($diff_timestamp>=60 && $diff_timestamp < 3600){
			return round($diff_timestamp/60).' minute ago'; 
		} 
		else if($diff_timestamp >=3600 && $diff_timestamp < 86400){
			return round($diff_timestamp/3600).' hours ago'; 
		} 
		else if($diff_timestamp >=86400 && $diff_timestamp<(86400*30)){
			return round($diff_timestamp/(86400)).' days ago'; 
		}
		else if($diff_timestamp >=(86400*30) && $diff_timestamp<(86400*365)){ return round($diff_timestamp/(86400*30)).' months ago'; } 
		else{ return round($diff_timestamp/(86400*365)).' years ago'; }
	}

	function numberFormat($number){
		$num = $number;
		if ($num < 1000) {
		$fr = number_format($num);
		}else  if ($num < 1000000) {
			$fr = number_format($num / 1000 ) . 'K';
		}else if ($num < 1000000000) {
			$fr = number_format($num / 1000000, 1) . 'M';
		}else {
		$fr = number_format($num / 1000000000, 1) . 'B';
		}
		return $fr;
	}

	function getMessage(){
		if( e::has() ){
			return e::get(function($errorData){
				$errorMessage = '';
				foreach( $errorData as $e ){
					$errorMessage .= '
						<script>
							window.addEventListener("load", () => {
								showToast("'.$e['message'].'", "'.$e['type'].'")
							})
						</script>
					';
				}
				return $errorMessage;
			});
		}
	}

	function displayMessage( string $message, string $type ){
		return '
			<div class="alert alert-'.e::getErrorType($type)['class'].' alert-dismissible mt-3 mb-3" role="alert">
				<div class="alert-message"><i class="'.e::getErrorType($type)['icon'].'"></i> '.$message.'</div>
			</div>
		';
	}

	function timestamp(){
		// Timestamp format: 2024-07-08 10:27:54
		return date('Y-m-d H:i:s');
	}

	function db_date( $date = ''){
		return empty( $date ) ? date("Y-m-d"):date("Y-m-d", strtotime($date));
	}

	function startsWith(string|int $startWith, string $string){
		$len = strlen($startWith);
		return (substr($string, 0, $len) === $startWith);
	}

	function isUrl( string $string ){
		if( preg_match('/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/', $string) ){
			return true;
		}else{
			return false;
		}
	}

    function getStringLinks( $text ){
        $regex = '/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/';
        return preg_replace_callback($regex, function ($matches) {
            return '<a href="'.$matches[0].'" rel="nofollow" target="_blank">'.parse_url( $matches[0] )['host'].'</a> ';
        }, $text);
    }

	function pagination($current, $totalRecords, $pageActionURl, $range = 4){
		// define the output
		$pageing = '';
		if( !empty( $current ) && !empty( $totalRecords ) && !empty( $pageActionURl ) ){
			for ($x = ($current - $range); $x < (($current + $range) + 1); $x++) {
			   if (($x > 0) && ($x <= $totalRecords)) {
				  if($x == $current) {
						$pageing .= '<li class="page-item active" aria-current="Page"><span class="page-link bg-secondary border-secondary">'.$x.'</span></li>
						';
				  } else {
					$pageing .= '<li class="page-item"><a class="page-link bg-dark border-dark text-light" href="'.$pageActionURl.$x.'" title="Page '.$x.'">'.$x.'</a></li>
					';
				  }
			   }
			}
			$output = '
				<nav aria-label="Pagination">
					<div class="text-center mb-2 text-secondary">Page: '.$current.' of '.$totalRecords.'</div>
				  <ul class="pagination pagination-md justify-content-center">
					'.$pageing.'
				  </ul>
				</nav>
			';
			return $output;
		}else{
			return false;
		}
		return false;
	}
	
