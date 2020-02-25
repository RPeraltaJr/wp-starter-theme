<?php
function send_report() 
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    global $wpdb;

    $tablename = "submissions";
    $from_date = date("Y-m-d", strtotime("-7 day")) . " 08:00:00";
    $to_date = date("Y-m-d") . " 08:00:00";

    // * get rejected submissions
    $query = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT
                `timestamp`,
                `first_name`,
                `last_name`,
                `phone`,
                `email`,
                `zipcode`,
                `custom_questions`,
                `email_me`,
                `text_me`,
                `form_url`,
                `form_id`
            FROM $tablename
            WHERE `post_status` = 'Accepted'
            AND (`timestamp` >= %s AND `timestamp` < %s)
            ", [
                $from_date,
                $to_date
            ]
        )
    );

    // echo 
    //     '<pre>',
    //     $wpdb->last_query,
    //     '</pre>';
    // echo 
    //     '<pre>',
    //     var_dump($query),
    //     '</pre>';
    // exit();

    // * create CSV file and email
    $columns = [
        "timestamp",
        "first_name",
        "last_name",
        "phone",
        "email",
        "zipcode",
        "custom_questions",
        "email_me",
        "text_me",
        "form_url",
        "form_id",
    ];
    $filename = WP_CONTENT_DIR . "/uploads/Accepted Submissions Weekly Report.csv";
    $stream = fopen($filename, "w");
    
    fputcsv($stream, $columns);

    foreach ($query as $row) {
        $csv_row = [];
        foreach ($columns as $column_type) {
            $csv_row[] = $row->$column_type;
        }
        fputcsv($stream, $csv_row);
    }

    $to = "";
    $subject = "Accepted Submissions Daily Report";
    $message = "Submissions are attached.";
    $headers[] = "From: noreply@example.com";
    $headers[] = "Bcc: myemail@example.com";
    wp_mail($to, $subject, $message, $headers, [$filename]);

    unlink($filename);
    echo "done";
    exit();
}
add_action('send_report', 'send_report');
if (!empty($_GET['send_report']) && is_user_logged_in()) {
    add_action('init', 'send_report');
}