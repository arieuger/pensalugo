<?php
$webmaster_email = "pensalugo@riseup.net";

$email_address   = $_REQUEST['email_address'];
$nome 			 = $_REQUEST['nome'];
$docidentidade   = $_REQUEST['docidentidade'];
$numconta		 = $_REQUEST['numconta'];
$enderezo		 = $_REQUEST['enderezo'];
$acceptprivacy   = $_REQUEST['acceptprivacy'];

$msg = 
"Email: " . $email_address . "\r\n" . 
"Nome: " . $nome . "\r\n" . 
"Documento de identidade: " . $docidentidade . "\r\n" . 
"Nº de conta: " . $numconta . "\r\n" . 
"Enderezo: " . $enderezo "\r\n" .
"Acepta política de privacidade? " . $acceptprivacy == true ? "Si" : "Non";

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