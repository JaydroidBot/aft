<?php

	// Imports
	require 'database.php';

	// instantiate
	$log = new Logger("callback_logs.txt");
	$db = new smsDB();

	// Collect all reponse variables defining a default value
	// Source [https://build.at-labs.io/docs/sms%2Fnotifications]	
	$sessionId = isset($_POST["sessionId"]) ? $_POST["sessionId"] : 'Null';
	$phoneNumber = isset($_POST["phoneNumber"]) ? $_POST["phoneNumber"] : 'Null';
	$status = isset($_POST["status"]) ? $_POST["status"] : 'Null';
	$failureReason = isset($_POST["failureReason"]) ? $_POST["failureReason"] : 'Null';
	$retryCount = isset($_POST["retryCount"]) ? $_POST["retryCount"] : 'Null';
	$networkCode = isset($_POST["networkCode"]) ? $_POST["networkCode"] : 'Null';
	$payload = NULL;

	// Status description for users
	$statusDescription = Array(
		'Sent' => 'The message has successfully been sent by our network.',
		'Submitted' => 'The message has successfully been submitted to the MSP (Mobile Service Provider).',
		'Buffered' => 'The message has been queued by the MSP.',
		'Rejected' => 'The message has been rejected by the MSP. This is a final status.',
		'Success' => 'The message has successfully been delivered to the receiver’s handset. This is a final status.',
		'Failed' => 'The message could not be delivered to the receiver’s handset. This is a final status.'
	);
	
	// Bootstrap reponse JSON
	$payload = Array(
		'sessionId' => $sessionId,
		'phoneNumber' => $phoneNumber,
		'status' => $status,
		'failureReason' => $failureReason,
		'description' => $statusDescription[$status],
		'networkCode' => $networkCode, 
		'retryCount' => $retryCount
	);

	// $log->insert(json_encode($payload));

	if ($sessionId !== 'Null') {
		// Write to db
		$db->update($sessionId, $payload);
		$db->close();
	} else {
		$log->insert('Error: Something went wrong with the response');		
	}

	$log->insert(json_encode($_POST));
?>