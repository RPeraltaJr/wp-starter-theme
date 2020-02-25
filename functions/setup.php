<?php 

function create_custom_table()
{
    global $wpdb;
    $table_name = "submissions";

    $wpdb->query(
        "CREATE TABLE $table_name (
			id INT AUTO_INCREMENT PRIMARY KEY,
			timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			first_name VARCHAR(60) DEFAULT NULL,
			last_name VARCHAR(60) DEFAULT NULL,
			phone VARCHAR(60) DEFAULT NULL,
			email VARCHAR(60) DEFAULT NULL,
			zipcode VARCHAR(60) DEFAULT NULL,
			custom_questions TEXT DEFAULT NULL,
            email_me varchar(3) NOT NULL DEFAULT 'No',
            text_me varchar(3) NOT NULL DEFAULT 'No',
			form_url TEXT DEFAULT NULL,
            form_id VARCHAR(60) DEFAULT NULL,
			post_status VARCHAR(60) DEFAULT NULL,
            ip_address VARCHAR(60) DEFAULT NULL,
			result TEXT DEFAULT NULL,
			xml_data TEXT DEFAULT NULL
        )"
    );

    exit("table created");
}
if (!empty($_GET['create-custom-table']) && is_user_logged_in()) {
    add_action('init', 'create_custom_table');
}


function drop_custom_table()
{
    global $wpdb;
    $table_name = "submissions";

    $wpdb->query("DROP TABLE $table_name");
    exit("table dropped");
}
if (!empty($_GET['drop-custom-table']) && is_user_logged_in()) {
    add_action('init', 'drop_custom_table');
}