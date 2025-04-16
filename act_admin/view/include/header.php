<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title ?> - <?php echo APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="<?php echo Url::baseUrl() ?>/view/css/style.css?v=1.2" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php if( isset( $header_code ) ): ?>
    <?php echo $header_code ?>
<?php endif; ?>
    <script>
        let reporting_api_endpoint = '<?php echo Url::baseUrl() ?>/reports/api';
        const baseUrl = '<?php echo Url::baseUrl() ?>';
    </script>
</head>
<body data-bs-theme="<?php echo Cookie::get('actpro_theme') ?? 'light'; ?>">
<?php if( !self::$noHeader ): ?>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4 class="text-center mb-0 fw-bold fs-5"><?php echo APP_NAME ?></h4>
            <p class="text-center mb-0" style="font-size:13px">Smart Tracking System</p>
        </div>
        <div class="px-3 py-4 sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/offers">
                        <i class="bi bi-box-seam"></i> Offers
                    </a>
                </li>
                <hr />
                <small class="text-secondary text-uppercase mb-1">Reports</small>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/reports/performance">
                        <i class="bi bi-graph-up"></i> Performance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/reports/conversions">
                        <i class="bi bi-check-circle"></i> Conversions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/reports/visitors">
                        <i class="bi bi-people-fill"></i> Visitors
                    </a>
                </li>
                <hr />
                <small class="text-secondary text-uppercase mb-1">Tracking System</small>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/tracking-system/link-builder">
                        <i class="bi bi-link-45deg"></i> Link builder
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/tracking-system/postback-pixel">
                        <i class="bi bi-link-45deg"></i> Postback Pixel
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/tracking-system/setup">
                        <i class="bi bi-code-slash"></i> Tracker Setup
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/tracking-system/demo">
                        <i class="bi bi-eyeglasses"></i> Demo
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/tracking-system/docs">
                        <i class="bi bi-file-earmark-text"></i> Docs
                    </a>
                </li>
                <hr />
                <small class="text-secondary text-uppercase mb-1">Settings</small>
                <li>
                    <a class="nav-link" href="<?php echo Url::baseUrl() ?>/settings/cleanup">
                        <i class="bi bi-trash"></i> Clean Up
                    </a>
                </li>
            </ul>
        </div>
    </div>



    <!-- Content -->
    <div class="content">

        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
            <div class="container-fluid">
                <button class="btn btn-sm btn-outline-light me-2 d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <a class="navbar-brand mx-auto" href="<?php echo Url::baseUrl() ?>"><?php echo APP_NAME ?></a>
                <div class="d-flex">
                    <button class="btn btn-sm btn-outline-light rounded-1 me-2" title="Switch Theme" id="switchTheme" onclick="switchTheme(this)">
                        <?php
                            if( Cookie::has('actpro_theme') ){
                                echo (Cookie::get('actpro_theme') === 'light' ? '<i class="bi bi-moon-stars"></i>':'<i class="bi bi-sun"></i>');
                            }else{
                                echo '<i class="bi bi-moon-stars"></i>';
                            }
                        ?>
                    </button>
                    <a class="btn btn-sm btn-outline-light" href="<?php echo Url::baseUrl().AUTH_LOGOUT_URI ?>"><i class="bi bi-box-arrow-right"></i></a>
                </div>
            </div>
        </nav>
        <br /><br />
        <div class="d-flex justify-content-between py-2 px-3 shadow-sm rounded-2 mt-2 sticky-top page-header">
            <div>
                <h1 id="pageTitle" class="fs-6 fw-bold p-0 mt-2 text-secondary"><?php echo $title ?></h1>
            </div>
        <?php if( isset( $page ) && $page === 'dashboard' || $page === 'performance' ): ?>
            <div>
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle mt-1 range-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">Today</button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item" data-range="today">Today</li>
                        <li class="dropdown-item" data-range="yesterday">Yesterday</li>
                        <li class="dropdown-item" data-range="7days">7 Days</li>
                        <li class="dropdown-item" data-range="30days">30 Days</li>
                        <li class="dropdown-item" data-range="60days">60 Days</li>
                        <li class="dropdown-item" data-range="90days">90 Days</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <?php if( $page == 'offers' ): ?>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#offerModal" onclick="add_offer()"><i class="bi bi-plus-lg me-1"></i> Add New Offer</button>
        <?php endif; ?>
        </div>
        <!-- Main Content -->
        <div class="container-fluid py-4 px-4" id="content">
<?php endif; ?>