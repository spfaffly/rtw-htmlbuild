<?php
	/* Contact Us form script */

	// Simple contact form
	// if($_POST['name'] == '' || $_POST['name'] == 'Name'){
	// 	$error[] = 'Name field required.';
	// } elseif($_POST['email'] == '' || $_POST['email'] == 'Email'){
	// 	$error[] = 'Email field required.';
	// }
	
	// $headers = 'From: ' . $_POST['name'] . ' <' .$_POST['email']  . ">\r\n" .
 //    'X-Mailer: PHP/' . phpversion();
	
	// $message = "Name: " . $_POST['name'] . "\r\n\r\n" .
	// "Phone: " . $_POST['phone'] . "\r\n\r\n" .
	// 'Message: ' . $_POST['message'];
	
	// // Send email if no error
	// if ($error){
	// 	header('Location: contact.html');
	// } else {
	// 	$send_contact = mail('info@rivertowell.com', 'Contact Form - River to Well', $message, $headers);
	// 	if ($send_contact){
	// 		//echo 'Email Sent!';
	// 	} else {
	// 		header('Location: contact.html');
	// 	}
	// }
	echo 'wut';

	error_reporting(E_ALL);
	ini_set('display_errors', 'On');

	// Set defaults
	$error = false;
	//$message = new Array();

	// Check name
	// if(isset($_POST['name'])){


	// }
?>