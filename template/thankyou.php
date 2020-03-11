<?php 
/*
Template Name: Thank You
*/

// * Look for referer
$referrer = $_SERVER['HTTP_REFERER'];
if(!$referrer): 
    wp_redirect(home_url());
endif;
