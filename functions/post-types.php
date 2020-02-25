<?php 

// Adds in post type for site settings
function create_posttypes() {
    
    $options = array(
        'label' => 'LP Settings',
        'show_ui' => true,
        'menu_icon' => 'dashicons-media-document',
        'supports' => array('title', 'thumbnail', 'revisions'),
    );
    register_post_type('landing_page', $options);
    
}
// hook
add_action('init', 'create_posttypes');
flush_rewrite_rules( false );