<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

    Auth::logout();
    Url::redirect( Url::baseUrl().AUTH_LOGIN_URI );