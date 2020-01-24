<?php 
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );
    
    if( in_array($_SERVER['REMOTE_ADDR'], $whitelist) ):

        // ** error handling
        ini_set('display_errors', 1); // Report errors ON
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // ** version
        $version = rand();

    else:

        // ** error handling
        error_reporting(0); // Report errors OFF

        // ** version
        $version = "1.0.0";

    endif;

    $img_path = get_template_directory_uri() . "/assets/build/img";
