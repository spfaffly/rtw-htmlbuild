<?php
	/* Contact Us form script */
	// Set error level
	// error_reporting(E_ALL);

	// Set defaults
	$error = false;
	$message = Array();

	// Check name
	if(isset($_GET['name']) && $_GET['email'] && $_GET['message']){

		if($_GET['name'] == '' || $_GET['name'] == 'Name'){
			$error[] = 'Name field required.';
		} elseif($_GET['email'] == '' || $_GET['email'] == 'Email'){
			$error[] = 'Email field required.';
		}

		$headers = 'From: ' . $_GET['name'] . ' <' .$_GET['email']  . ">\r\n" . 'X-Mailer: PHP/' . phpversion();

		$message = "Name: " . $_GET['name'] . "\r\n\r\n" .
		'Message: ' . $_GET['message'];

		// Send email if no error
		if ($error){
			echo json_encode(array('status' => 'failed', 'message' => 'Error in sending form.', 'longerror' => 'Error exists in form input fields. 1'));
		} else {
			$send_contact = mail('info@rivertowell.com', 'Contact Form - River to Well', $message, $headers);
			$send_contact = mail('shane@rivertowell.com', 'Contact Form - River to Well', $message, $headers);

			if ($send_contact){
				echo json_encode(array('status' => 'ok', 'message' => 'Email Sent'));
			} else {
				echo json_encode(array('status' => 'failed', 'message' => 'Error in sending form.', 'longerror' => 'Mail send error. Check logs.'));
			}
		}
	} else {
		echo json_encode(array('status' => 'failed', 'message' => 'Error in sending form.', 'longerror' => 'Error exists in form input fields. 2'));
	}
?>