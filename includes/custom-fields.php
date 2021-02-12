<?php
$custom_fields_array = array(
    // campaign info
    "thankyou_page",
    "phone_number",
    "ats_link",
    "form_id",
    "master_copy"
);

global $post;
$parent_page_id = $post->post_parent;
$parent_pages = get_post_ancestors($post->ID);
if (in_array("lp-thank", $page_type)) {
    // thank you page will grab campaign info from parent page
    foreach ($custom_fields_array as $custom_field) {
        $$custom_field = get_field($custom_field, $parent_page_id);
    }
} else {
    foreach ($custom_fields_array as $custom_field) {
        $$custom_field = get_field($custom_field);
    }
}

if (!empty($master_copy)) {
    $page_settings->master_copy = $master_copy;
}

$page_type[] = "post-" . $post->ID;
$test_mode = false;

// for local development
if ($_SERVER['SERVER_ADDR'] === "::1") {
    $test_mode = true;
    $thankyou_page = "/thank-you";
    $phone_number = "555-555-5555";
}
