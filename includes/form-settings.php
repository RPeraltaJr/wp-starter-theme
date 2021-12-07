<?php 

global $wpdb;

function remove_http($url) {
	$disallowed = array('http://', 'https://');
	foreach($disallowed as $d) {
		if(strpos($url, $d) === 0) {
			return str_replace($d, '', $url);
		}
	}
	return $url;
}

// * Variables
$domain_name = remove_http(home_url());
$company_name = ""; // * Required (for sending confirmation email)

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

	// form fields
	// field name => required
	$post_fields_array = array(
		"first_name"	=> true,
		"last_name"		=> true,
		"email"			=> true,
		"zipcode"		=> true,
		"phone"			=> true,
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
	foreach ($post_fields_array as $post_field => $required) {
        if ( (empty($_POST[$post_field]) || strlen($_POST[$post_field]) < 1) && $required == true ) {
            $response->error = true;
            $response->messages[] = "<strong>{$post_field}</strong> is required.";
            
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
    if ($_POST['terms'] !== "Yes"):
        $response->error = true;
        $response->messages[] = "Please agree to our terms";
	endif;

	// * skip
	if ($last_name === "Testerson"):
        $table_data["post_status"] = "skip";
	endif;
	
	// * skip
    if ( strtolower($custom_questions['cdl']['a']) == "no"):
        $table_data["post_status"] = "skip";
	endif;

	// * honeypot (anti-spam)
    if( isset($_POST['a_password']) && trim($_POST['a_password']) !== "" ):
        $response->error = true;
        $response->messages[] = "Robot verification failed, please try again.";
    endif;

    	// * Validations
	// * prevent multiple submissions on the same day
	if (!is_user_logged_in()):
		$count = $wpdb->get_var(
		    $wpdb->prepare(
			"SELECT COUNT(*)
			FROM $table_name
			WHERE (`timestamp` LIKE %s) AND `email` = %s", [
			    "%%" . date("Y-m-d") . "%%",
			    $email,
			]
		    )
		);
		if($row_count > 0): 
			$response->error = true;
			$response->messages[] = "We have received your application and you will hear from our recruiters soon!";
		endif;
	endif;
	
	// send mail if successful
	if ($response->error === false):

		// insert data into table
		$success = $wpdb->insert($table_name, $table_data);

		if ($success) {
            /**
			 * -----------------------------------------
			 * Send to Applicant
			 * -----------------------------------------
			 */
            $subject = "Thank you so much for your interest in $company_name";
            $mail_applicant =
                "
				Hello $first_name,<br><br>
				Thank You for your interest in a career at $company_name.<br>
				We look forward to connecting with you.<br><br>
				If you meet our qualifications, one of our recruiters will reach out to you!<br><br>
				Thank you,<br>
				$company_name
			";
            $to = "{$first_name} {$last_name} <" . $email . ">";
            // message
            $message = "<html><head></head><body>$mail_applicant</body></html>";
            $headers = array(
                "Content-Type: text/html; charset=UTF-8",
                "From: $company_name <no-reply@{$domain_name}>",
            );
            wp_mail($to, $subject, $message, $headers);

			/**
			 * -----------------------------------------
			 * Send to Recruiter
			 * -----------------------------------------
			 */
			$subject = "$domain_name - New Applicant";
			$mail_applicant = "
				First Name: $first_name<br>
				Last Name: $last_name<br>
				Email: $email<br>
				Phone: $phone<br>
				Zip Code, City, or State: $location<br>
				Nursing Area of Interest: $area<br>
				Shift Preference: $shift<br>
			";
			if($last_name == 'Testerson'): 
				// echo '<pre>' . var_export($mail_applicant, true) . '</pre>'; exit;
				$to = [
					"dev@mail.com"
				];
			else: 
				$to = [
					"client@mail.com"
				];
			endif;

			// message
            $message = "<html><head></head><body>$mail_applicant</body></html>";
            $headers = array(
                "Content-Type: text/html; charset=UTF-8",
                "From: $company_name <no-reply@{$domain_name}>",
            );
            wp_mail($to, $subject, $message, $headers);
			if(empty($thankyou_page)): 
				$thankyou_page = home_url() . "/thank-you";
			endif;
            wp_redirect($thankyou_page);
            exit();
        } else {
            $response->error = true;
            $response->messages[] = "There was an error. Please try again.";
		}
	endif;

endif;

?>
