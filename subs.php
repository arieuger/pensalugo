<?php
$webmaster_email = "pensalugo@riseup.net";

$email_address   = $_REQUEST['email_address'];
$nome 			 = $_REQUEST['nome'];
$docidentidade   = $_REQUEST['docidentidade'];
$numconta		 = $_REQUEST['numconta'];
$titconta		 = $_REQUEST['titconta'];
$enderezo		 = $_REQUEST['enderezo'];
$codpostal		 = $_REQUEST['codpostal'];
$acceptprivacy   = $_REQUEST['acceptprivacy'];

$msg = 
"Email: " . $email_address . "\r\n" . 
"Nome: " . $nome . "\r\n" . 
"Documento de identidade: " . $docidentidade . "\r\n" . 
"Nº de conta: " . $numconta . "\r\n" . 
"Nome da persoa titular: " . $titconta . "\r\n" .
"Enderezo: " . $enderezo . "\r\n" .
"Código postal: " . $codpostal . "\r\n" .
"Acepta política de privacidade? " . $acceptprivacy;

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
	$response_array['mensaxe'] = 'Debes cubrir o formulario na páxina inicial';
    exit(json_encode($response_array));

} elseif ( isInjected($email_address) 
		|| isInjected($nome) 
		|| isInjected($docidentidade) 
		|| isInjected($numconta) 
		|| isInjected($titconta) 
		|| isInjected($enderezo)
		|| isInjected($codpostal) ) {
	header('Content-type: application/json');
	$response_array['status'] = 'error: injected'; 
	$response_array['mensaxe'] = 'Non podes intentar unha inxección SQL';
    exit(json_encode($response_array));
    return false;

} elseif (empty($email_address) 
	   || empty($nome) 
	   || empty($docidentidade) 
	   || empty($numconta)
	   || empty($titconta)
	   || empty($enderezo)
	   || empty($codpostal)) {
	header('Content-type: application/json');
	$response_array['status'] = 'error: empty';
	$response_array['mensaxe'] = 'Debes cubrir todos os campos do formulario';
	$result = false;
    exit(json_encode($response_array));

} elseif ($acceptprivacy != 'true') {
	header('Content-type: application/json');
	$response_array['status'] = 'error: privacy conditions';
	$response_array['mensaxe'] = 'Debes aceptar as condicións de privacidade para poder subscribirte';
    exit(json_encode($response_array));

} else {
	mail( "$webmaster_email", "Nova suscrición", $msg );

	header('Content-type: application/json');
	$response_array['status'] = 'ok'; 
    exit(json_encode($response_array));
}


?>