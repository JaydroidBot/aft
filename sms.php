<?php
    require 'vendor/autoload.php';
	require 'logger.php';
    use AfricasTalking\SDK\AfricasTalking;


    /**
     * Sends an SMS using bulk SMS provider APIs
     * @param String $phone the recepient phone number
     * @param String $message the sms message to be sent
     * @param String $orderId unique value for the request
     */
    function callSmsApi($phone, $message, $orderId) {

        // Set your app credentials
        $username = "name";
        $apiKey = "key";

        // Initialize classes
        $AT = new AfricasTalking($username, $apiKey);
        $log = new Logger("sent_sms_logs.txt");

        // Get the SMS service
        $sms = $AT->sms();

        // Set the numbers you want to send to in international format
        $recipients = $phone;

        // Set your shortCode or senderId
        $from = "GoBEBA"; 

        $at_result = NULL;
		$resultCodeMap = Array(
			"100" => "Processed",
			"101" => "Sent",
			"102" => "Queued",
			"401" => "RiskHold",
			"402" => "InvalidSenderId",
			"403" => "InvalidPhoneNumber",
			"404" => "UnsupportedNumberType",
			"405" => "InsufficientBalance",
			"406" => "UserInBlacklist",
			"407" => "CouldNotRoute",
			"500" => "InternalServerError",
			"501" => "GatewayError",
			"502" => "RejectedByGateway"
		);

        try {
            $result = $sms->send([
                'to' => $recipients,
                //'from'    => $from
                'message' => $message,
                'enqueue' => false
            ]);

            $recipient = $result['data']->SMSMessageData->Recipients[0];      

            //AT result
            $at_result = array(
	            "statusCode" => $recipient->statusCode,
	            "number" => $recipient->number,
	            "status" => $recipient->status,
	            "cost" => $recipient->cost,
	            "messageId" => $recipient->messageId,
	            "responseDescription" => $resultCodeMap[$recipient->statusCode]
            );

        } catch (Exception $e) {
            $at_result = array(
            	"error" => $e->getMessage()
            ); 
        }

		// Logs incase you need to debug AFT Response
		$log->insert(json_encode($at_result));

        return json_encode($at_result);
    }


    // Testing
    // $response = callSmsApi ("254713194216", "Testing SMS", NULL);
?>