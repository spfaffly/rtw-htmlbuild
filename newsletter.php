<?php
	/* Newsletter form script */
	// Set error level
	error_reporting(E_ALL ^ E_NOTICE);

	// Set defaults
	$error = false;
	$message = Array();

	// Check name
	if(isset($_GET['subscribeemail'])){

		if($_GET['subscribeemail'] == '' || $_GET['subscribeemail'] == 'Enter your emai address'){
			$error[] = 'Email field required.';
		}

		$headers = 'From: River to Well Website <info@rivertowell.com' . ">\r\n" . 'X-Mailer: PHP/' . phpversion();
		
		$message = "Email address to signup: " . $_GET['subscribeemail'] . "\r\n\r\n";
		
		// Send email if no error
		if ($error){
			echo json_encode(array('status' => 'failed', 'message' => 'Error in sending form.'));
		} else {
			$send_contact = mail('info@rivertowell.com', 'Newsletter Signup - River to Well', $message, $headers);
			if ($send_contact){
				echo json_encode(array('status' => 'ok', 'message' => 'Email Sent'));
			} else {
				echo json_encode(array('status' => 'failed', 'message' => 'Error in sending form.'));
			}
		}
	} else {
		echo json_encode(array('status' => 'failed', 'message' => 'Error in sending form.'));
	}
?>