<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    class App {

        private static $icons = [
            "Survey" => "bi-clipboard2-data",
            "Contests" => "bi-trophy",
            "Sweepstakes" => "bi-gift",
            "Games" => "bi-controller",
            "App Install" => "bi-download",
            "Vouchers & Gift Cards" => "bi-credit-card",
            "Watch Videos" => "bi-play-circle",
            "Mobile Content" => "bi-phone",
            "Utilities" => "bi-tools",
            "Credit Cards" => "bi-credit-card-2-front",
            "Personal Loans" => "bi-cash-stack",
            "Insurance" => "bi-shield-check",
            "Proxy / VPN" => "bi-shield-lock",
            "Tax Help" => "bi-calculator",
            "Debt Relief" => "bi-piggy-bank",
            "Dating" => "bi-heart",
            "Free Trials" => "bi-arrow-repeat",
            "Sports" => "bi-trophy-fill",
            "Anivirus & Cleaners" => "bi-shield-fill-check",
            "Food & Cooking" => "bi-egg-fried",
            "Health & Beauty" => "bi-droplet",
            "School & Learning" => "bi-book",
            "Shopping & Retail" => "bi-cart",
            "Travel & Hotels" => "bi-geo-alt",
            "Others" => "bi-three-dots"
        ];

        private static $categories = [
            "Survey",
            "Contests",
            "Sweepstakes",
            "Games",
            "App Install",
            "Vouchers & Gift Cards",
            "Watch Videos",
            "Mobile Content",
            "Utilities",
            "Credit Cards",
            "Personal Loans",
            "Insurance",
            "Proxy / VPN",
            "Tax Help",
            "Debt Relief",
            "Dating",
            "Free Trials",
            "Sports",
            "Anivirus & Cleaners",
            "Food & Cooking",
            "Health & Beauty",
            "School & Learning",
            "Shopping & Retail",
            "Travel & Hotels",
            "Others",
        ];

        function __construct(){}

        public static function getCategory(){
            return self::$categories;
        }

        public static function getIcon( $category ){
            return isset( self::$icons[$category] ) ? self::$icons[$category]:'bi-box-arrow-up-right';
        }

        public static function getOffers(){
            $query = db::query( "SELECT * FROM offers ORDER BY date DESC" );
            $offers = '';
            if( $query->num_rows > 0 ){
                while( $row = $query->fetch_assoc() ){
                    $status = $row['status'] ? '<span class="badge bg-success offer-badge">Active</span>':'<span class="badge bg-danger offer-badge">Inactive</span>';
                    $offers .= '
                        <div id="offer-'.$row['id'].'">
                            <div class="card offer-card shadow-sm">
                                <div class="offer-image">
                                    '.(!empty($row['icon']) ? '<i class="bi '.$row['icon'].' text-secondary"></i>':'<i class="bi bi-box-arrow-up-right text-secondary"></i>').'
                                </div>
                                '.$status.'
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title fw-bold">'.$row['title'].'</h5>
                                            <p class="card-text text-muted mb-2 p-0 d-block">'.$row['description'].'</p>
                                            <small class="badge bg-secondary rounded-1 mt-1 fw-normal">#'.$row['id'].'</small>
                                            <small class="badge bg-secondary rounded-1 mt-1 fw-normal">'.$row['category'].'</small>
                                            <small class="badge bg-secondary rounded-1 mt-1 fw-normal">'.$row['time'].'</small>
                                            <small class="badge bg-secondary rounded-1 mt-1 fw-normal">'.ucwords($row['network']).'</small>
                                        </div>
                                    </div>
                                    <div class="row row-cols-4 g-3 mt-1 mb-1 offer-stats">
                                        <div class="col">
                                            <small class="text-muted">Clicks:</small>
                                            <h6 class="mb-0">'.number_format($row['clicks']).'</h6>
                                        </div>
                                        <div class="col">
                                            <small class="text-muted">Conv:</small>
                                            <h6 class="mb-0">'.number_format($row['conversion']).'</h6>
                                        </div>
                                        <div class="col">
                                            <small class="text-muted">EPC:</small>
                                            <h6 class="mb-0">$'.($row['revenue'] > 0 ? round(($row['revenue'] / $row['clicks'] ), 3):'0').'</h6>
                                        </div>
                                        <div class="col">
                                            <small class="text-muted">Rev:</small>
                                            <h6 class="mb-0">$'.round($row['revenue'], 2).'</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn btn-sm btn-outline-primary" onclick="get_link('.$row['id'].')">
                                            <i class="bi bi-link"></i> Get Link
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="edit_offer('.$row['id'].')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="delete_offer('.$row['id'].', this)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                }
            }
            return $offers;
        }
        

    }