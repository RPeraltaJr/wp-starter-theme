<?php 

global $wpdb;

// * Required (for sending confirmation email)
$company_name = ""; 

/* 
* ----------------------------------------------
* HTMLPurifier Plugin
* @source: http://htmlpurifier.org/demo.php
* HTML Purifier will not only remove all malicious code (better known as XSS) with a thoroughly audited, secure yet permissive whitelist, it will also make sure your documents are standards compliant, something only achievable with a comprehensive knowledge of W3C's specifications.
* ----------------------------------------------
*/

require __DIR__ . '/../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
// $clean_html = $purifier->purify($dirty_html);


/* 
* ----------------------------------------------
* Error Handling
* ----------------------------------------------
*/

$response = (object) [
	"error"    	=> false,
	"messages"  => array()
];

/* 
* ----------------------------------------------
* States
* ----------------------------------------------
*/

$states_array = array (
	'Alabama',
	'Alaska',
	'Arizona',
	'Arkansas',
	'California',
	'Colorado',
	'Connecticut',
	'Delaware',
	'District of Columbia',
	'Florida',
	'Georgia',
	'Hawaii',
	'Idaho',
	'Illinois',
	'Indiana',
	'Iowa',
	'Kansas',
	'Kentucky',
	'Louisiana',
	'Maine',
	'Maryland',
	'Massachusetts',
	'Michigan',
	'Minnesota',
	'Mississippi',
	'Missouri',
	'Montana',
	'Nebraska',
	'Nevada',
	'New Hampshire',
	'New Jersey',
	'New Mexico',
	'New York',
	'North Carolina',
	'North Dakota',
	'Ohio',
	'Oklahoma',
	'Oregon',
	'Pennsylvania',
	'Rhode Island',
	'South Carolina',
	'South Dakota',
	'Tennessee',
	'Texas',
	'Utah',
	'Vermont',
	'Virginia',
	'Washington',
	'West Virginia',
	'Wisconsin',
	'Wyoming'
);


/* 
* ----------------------------------------------
* Form
* ----------------------------------------------
*/

// form submission
if( isset($_POST['submit']) ):

	// required form fields
	$post_fields_array = array(
		"first_name",
		"last_name",
		"email",
		"zipcode",
		"phone",
	);

	$custom_questions_fields = [
        "cdl" 			=> "Do you have a CDL?",
        "experience" 	=> "Years of experience",
    ];
	$custom_questions = [];
	
	// declare table for submissions
	$table_name = "submissions";
	
	// additional columns to add
	$table_data = array(
		"post_status" 	=> "pending",
   		"form_url"    	=> $_SERVER['REQUEST_URI'],
		"ip_address"	=> $_SERVER['REMOTE_ADDR']
	);

	// validations
	foreach ($post_fields_array as $post_field) {
        if (empty($_POST[$post_field]) || strlen($_POST[$post_field]) < 1) {
            $response->error = true;
            $response->messages[] = "Fill input for all required fields.";
            
            if (!empty($_GET['testing']) && is_user_logged_in()) {
                $response->messages[] = "field: $post_field";
            }
        }

        switch ($post_field) {
            case "phone":
            case "zipcode":
                $$post_field = filter_var($_POST[$post_field], FILTER_SANITIZE_NUMBER_INT);
                $table_data[$post_field] = $$post_field;
                break;
            case "email":
                $$post_field = filter_var($_POST[$post_field], FILTER_SANITIZE_EMAIL);
                if (!empty($$post_field)) {
                    if (!filter_var($$post_field, FILTER_VALIDATE_EMAIL)) {
                        $response->error = true;
                        $response->messages[] = "Enter a valid email address.";
                    }
                }
                $table_data[$post_field] = $$post_field;
                break;
            default:
                $$post_field = filter_var($_POST[$post_field], FILTER_SANITIZE_STRING);
                $$post_field = ucwords(strtolower($$post_field));
                $table_data[$post_field] = $$post_field;
        }
    }

    foreach ($custom_questions_fields as $post_field => $label) {
        if (empty($_POST[$post_field]) || strlen($_POST[$post_field]) < 1) {
            $response->error = true;
            $response->messages[] = "<strong>$label</strong> is required.";
            
            if (!empty($_GET['testing']) && is_user_logged_in()) {
                $response->messages[] = "field: $post_field";
            }
        }

        $$post_field = filter_var($_POST[$post_field], FILTER_SANITIZE_STRING);
        $custom_questions[$post_field] = [
            "q" => $label,
            "a" => $$post_field,
        ];
    }

    $table_data["custom_questions"] = json_encode($custom_questions);


	// * check for accepted terms agreement
    if ($_POST['terms'] !== "Yes") {
        $response->error = true;
        $response->messages[] = "Please agree to our terms";
    }
	if ($last_name === "Testerson") {
        $table_data["post_status"] = "skip";
    }
    if ( strtolower($custom_questions['cdl']['a']) == "no") {
        $table_data["post_status"] = "skip";
    }
	
	// send mail if successful
	if ($response->error === false):

		// insert data into table
		$success = $wpdb->insert($table_name, $table_data);

		if ($success) {
            $subject = "Thank you so much for your interest in $company_name";
            $mail_applicant =
                "
				Hello $first_name,<br><br>
				Thank you so much for your interest in $company_name!<br><br>
				If you would like to speak to a recruiter now, please call $phone_number.<br><br>
				If you are ready to go through our full application process, <a href='$ats_link'>click here</a>.<br><br>
				Thank you,<br>
				$company_name
			";
            $to = $first_name . "<" . $email . ">";
            // message
            $message = "<html><head></head><body>$mail_applicant</body></html>";
            $headers = array(
                "Content-Type: text/html; charset=UTF-8",
                "From: $company_name <no-reply@{$_SERVER['HTTP_HOST']}>",
            );

            wp_mail($to, $subject, $message, $headers);
            wp_redirect($thankyou_page);
            exit();
        } else {
            $response->error = true;
            $response->messages[] = "There was an error. Please try again.";
		}
	endif;

endif;

?>
