<?php 
/*
Template Name: Home
*/

$page = (object) array(
    "type"      => "page-home",
    "name"      => str_replace('.php', '', basename($_SERVER['PHP_SELF'])),
    "meta"      => [
        "title" => "Home",
        "desc"  => "",
    ],
    "plugins"   => [],
);

include __DIR__ . "/../includes/site-settings.php";
include __DIR__ . "/../includes/form-settings.php";

// header
include __DIR__ . "/../components/global/header/header.php";

// navbar
include __DIR__ . "/../components/global/navbar/navbar.php";

// hero
include __DIR__ . "/../components/home/hero/hero.php";

// footer
include __DIR__ . "/../components/global/footer/footer-nav.php";
include __DIR__ . "/../components/global/footer/footer.php";