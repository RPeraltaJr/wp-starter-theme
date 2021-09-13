<?php 

// custom post types
include 'functions/post-types.php';

// database table setup
include 'functions/setup.php';

// send weekly reports
include 'functions/send-report.php';

// * global variables
function get_img_path() {
    return get_template_directory_uri() . "/assets/img";
}

// * enable widgets
if ( function_exists('register_sidebar') )
register_sidebar();
