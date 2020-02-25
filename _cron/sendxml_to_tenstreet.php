<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = parse_ini_file(__DIR__ . "./../config.ini.php", true);

$database_address = "localhost";
$database_name = $config['database']['name'];
$database_user = $config['database']['user'];
$database_pass = $config['database']['pass'];

$db = new PDO("mysql:host=$database_address;dbname=$database_name", $database_user, $database_pass, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
));

// other variables
$page_title 		= ""; // string
$tenstreet_id 		= 0;  // int
$tenstreet_api_key 	= ""; // api key

// set date/time
// the date/time will display in the format: M/D/YYYY H:MM:SS AM/PM
date_default_timezone_set("America/New_York");
echo "========== " . date("Y-m-d H:i:s") . " ==========" . "\n";

// set tablename variable
$tablename = "submissions";

// sometimes records get stuck in a 'posting_now' post_status
// change those records back to 'pending' so they can be processed
$query = $db->prepare("UPDATE $tablename SET post_status='pending' WHERE post_status='posting_now'");
$query->execute();
$query->closeCursor();

// remove blank first_name apps and test apps
$query = $db->prepare("DELETE FROM $tablename WHERE post_status = 'pending' AND first_name=''");
$query->execute();
$query->closeCursor();

// remove duplicate apps
$query = $db->prepare("DELETE FROM $tablename WHERE post_status='pending' AND id NOT IN ( SELECT * FROM ( SELECT MIN(id) FROM $tablename WHERE post_status='pending' GROUP BY first_name, email, form_url) AS temp_table)");
$query->execute();
$query->closeCursor();

// get pending apps
$query = $db->prepare("SELECT * FROM $tablename WHERE post_status='pending'");
$query->execute();

$counter = 0;
while ($row = $query->fetch(PDO::FETCH_OBJ)) {
	$counter++;
	unset($xml_data, $response_xml);

	$queryUpdate = $db->prepare("UPDATE $tablename SET post_status = 'posting_now' WHERE id='{$row->id}'");
	$queryUpdate->execute();
	$queryUpdate->closeCursor();

	# build the XML you need here using $row to pull your variables and insert them into your XML.
	$xml = new DomDocument('1.0', 'UTF-8');
	$xml->formatOutput = true;

	// TenstreetData node
	$TenstreetData = $xml->createElement("TenstreetData");
	$xml->appendChild($TenstreetData);

	// * TenstreetData > Authentication node
    $Authentication = $xml->createElement("Authentication");
    $TenstreetData->appendChild($Authentication);

    // * TenstreetData > Authentication > ClientId node
    $ClientId = $xml->createElement("ClientId", 55);
    $Authentication->appendChild($ClientId);

    // * TenstreetData > Authentication > Password node
    $Password = $xml->createElement("Password", $tenstreet_api_key);
    $Authentication->appendChild($Password);

    // * TenstreetData > Authentication > Service node
    $Service = $xml->createElement("Service", "subject_upload");
    $Authentication->appendChild($Service);

	// TenstreetData > Mode node
	$Mode = $xml->createElement("Mode", "PROD");
	$TenstreetData->appendChild($Mode);

	// TenstreetData > Source node
	$tenstreet_source = "BayardLeadForm";
	$Source = $xml->createElement("Source", $tenstreet_source);
	$TenstreetData->appendChild($Source);

	// TenstreetData > CompanyId node
	$company_id = $tenstreet_id;
	$CompanyId = $xml->createElement("CompanyId", $company_id);
	$TenstreetData->appendChild($CompanyId);

	// TenstreetData > DriverId node
	$driver_id = "1001";
	$DriverId = $xml->createElement("DriverId", $driver_id);
	$TenstreetData->appendChild($DriverId);

	// TenstreetData > PersonalData node
	$PersonalData = $xml->createElement('PersonalData');
	$TenstreetData->appendChild($PersonalData);

	// TenstreetData > PersonalData > PersonName node
	$PersonName = $xml->createElement("PersonName");
	$PersonalData->appendChild($PersonName);

	// TenstreetData > PersonalData > PersonName > Prefix node
	$Prefix = $xml->createElement("Prefix");
	$PersonName->appendChild($Prefix);

	// TenstreetData > PersonalData > PersonName > GivenName node
	$GivenName = $xml->createElement("GivenName", $row->first_name);
	$PersonName->appendChild($GivenName);

	// TenstreetData > PersonalData > PersonName > FamilyName node
	$FamilyName = $xml->createElement("FamilyName", $row->last_name);
	$PersonName->appendChild($FamilyName);

	// TenstreetData > PersonalData > PostalAddress node
	$PostalAddress = $xml->createElement("PostalAddress");
	$PersonalData->appendChild($PostalAddress);

	// TenstreetData > PersonalData > PostalAddress > CountryCode node
	$CountryCode = $xml->createElement("CountryCode", "US");
	$PostalAddress->appendChild($CountryCode);

	// TenstreetData > PersonalData > PostalAddress > Municipality node
	$Municipality = $xml->createElement("Municipality", $row->city ?? "");
	$PostalAddress->appendChild($Municipality);

	// TenstreetData > PersonalData > PostalAddress > Region node
	$Region = $xml->createElement("Region", $row->state ?? "");
	$PostalAddress->appendChild($Region);

	// TenstreetData > PersonalData > PostalAddress > PostalCode
	$PostalCode = $xml->createElement("PostalCode", $row->zipcode ?? "");
	$PostalAddress->appendChild($PostalCode);

	// TenstreetData > PersonalData > PostalAddress > Address1
	// $street_address = $row['address'];
	// $Address1 = $xml->createElement("Address1", $street_address);
	// $PostalAddress->appendChild($Address1);

	// TenstreetData > PersonalData > ContactData node
	$ContactData = $xml->createElement("ContactData");
	$contactAttribute = $xml->createAttribute('PreferredMethod');
	$contactAttribute->value = 'PrimaryPhone';
	$ContactData->appendChild($contactAttribute);
	$PersonalData->appendChild($ContactData);

	// TenstreetData > PersonalData > ContactData > InternetEmailAddress node
	$InternetEmailAddress = $xml->createElement("InternetEmailAddress", $row->email ?? "");
	$ContactData->appendChild($InternetEmailAddress);

	// TenstreetData > PersonalData > ContactData > PrimaryPhone node
	$PrimaryPhone = $xml->createElement("PrimaryPhone", $row->phone ?? "");
	$ContactData->appendChild($PrimaryPhone);

	// TenstreetData > ApplicationData node
	$ApplicationData = $xml->createElement('ApplicationData');
	$TenstreetData->appendChild($ApplicationData);

	// TenstreetData > ApplicationData > AppReferrer node
	$AppReferrer = $xml->createElement('AppReferrer', (!empty($row->form_id) ? $row->form_id : "BayardLeadForm"));
	$ApplicationData->appendChild($AppReferrer);

	// TenstreetData > ApplicationData > DisplayFields node
	$DisplayFields = $xml->createElement('DisplayFields');
	$ApplicationData->appendChild($DisplayFields);

	// Tenstreet non-required questions
	if (!empty($row->custom_questions)) {
		$custom_questions = json_decode($row->$custom_questions);
		foreach($custom_questions as $id => $question) {
			// TenstreetData > ApplicationData > DisplayFields > DisplayField node
			$DisplayField = $xml->createElement('DisplayField');
			$DisplayFields->appendChild($DisplayField);
			// TenstreetData > ApplicationData > DisplayFields > DisplayField > DisplayId node
			$DisplayId = $xml->createElement('DisplayId', $id);
			$DisplayField->appendChild($DisplayId);
			// TenstreetData > ApplicationData > DisplayFields > DisplayField > DisplayPrompt node
			$DisplayPrompt = $xml->createElement('DisplayPrompt', $question->q);
			$DisplayField->appendChild($DisplayPrompt);
			// TenstreetData > ApplicationData > DisplayFields > DisplayField > DisplayValue node
			$DisplayValue = $xml->createElement('DisplayValue', $question->a);
			$DisplayField->appendChild($DisplayValue);
		}
	}

	$xml_data = $xml->saveXML();
	$xml_data_encode = utf8_encode($xml_data);

	// save xml to database
	$queryUpdate = $db->prepare("UPDATE $tablename SET xml_data = :xml_data WHERE id = '{$row->id}'");
	$queryUpdate->bindParam(":xml_data", $xml_data);	
	$queryUpdate->execute();
	$queryUpdate->closeCursor();

	// _ POST to Tenstreet
    $post_address = "https://dashboard.tenstreet.com/post/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $post_address);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf- 8'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data_encode);
    $response_xml = curl_exec($ch);
    curl_close($ch);

    $response = simplexml_load_string($response_xml);

    $update = $db->prepare(
        "UPDATE $tablename
        SET `post_status` = ?,
            `result` = ?
        WHERE `id` = ?"
    );
    $update->execute([
        $response->Status,
        json_encode($response),
        $row->id,
    ]);
}

echo "Processed submissions: $counter \n\n";