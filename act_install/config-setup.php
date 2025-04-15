<?php
    /* Security Constrant */
    define( 'BASE', 'true' );

    define('Root', __DIR__);
    define('ROOT_DIR', strtolower( basename( Root ) ) ); // must use lowercase directory name

    header( "Location: ".Url::baseUrl().'/act_install/' );