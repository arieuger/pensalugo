<?php
$webmaster_email = "pensalugo@riseup.net";

$feedback_page = "index.html";
$error_page = "info.php";
$thankyou_page = "index.html";

$email_address = $_REQUEST['email_address'] ;
$comments = $_REQUEST['comments'] ;
/*$first_name = $_REQUEST['first_name'] ;
"First Name: " . $first_name . "\r\n" . */
$msg = 
"Email: " . $email_address . "\r\n" . 
"Comments: " . $comments ;

function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// If the user tries to access this script directly, redirect them to the feedback form,
if (!isset($_REQUEST['email_address'])) {
	header('Content-type: application/json');
	$response_array['status'] = 'error: acceso directo'; 
    exit(json_encode($response_array));
}

// If the form fields are empty, redirect to the error page.
elseif (/* empty($first_name) || */empty($email_address)) {
	header('Content-type: application/json');
	$response_array['status'] = 'error: empty'; 
    exit(json_encode($response_array));
}

/* 
If email injection is detected, redirect to the error page.
If you add a form field, you should add it here.
*/
elseif ( isInjected($email_address) /* || isInjected($first_name) */ || isInjected($comments) ) {
	header('Content-type: application/json');
	$response_array['status'] = 'error: injected'; 
    exit(json_encode($response_array));
}

// If we passed all previous tests, send the email then redirect to the thank you page.
else {

	mail( "$webmaster_email", "Nova suscrición", $msg );

	header('Content-type: application/json');
	$response_array['status'] = 'okk'; 
    exit(json_encode($response_array));
}
?>