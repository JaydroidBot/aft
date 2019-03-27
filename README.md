# SMS Comms Module

This module integrates to the Africa Is Talking (AFT) API to send messages.

## File Structure

```
aft
├── logs // Log Files for debug
|	├── sent_sms_logs.txt
| 	├── callback_logs.txt 
|	└── database_logs.txt
|
├── vendor // Vendor Files
├── callback.php // AFT callback handler
├── sms.php // Main file with send SMS
├── logger.php // Logger class
└── README.md
```

## Using This Module

- The sms.php file contains the callSmsApi function that accepts ($phone, $message, $orderId)
- The callSmsApi function returns a JSON payload with the result of the operation
- The function creates logs of sent SMSs to sent_sms_logs.txt
- The getStatus function returns message details from the db.

## Getting message status
- Pass in `either one` of the following params:
	```
		$params = Array(
			"orderId" => "orderxyz",
			"sessionId" => "wxyz"
		)
	```
- The function will query the database using the provided param and return a JSON payload of
  the message details.

## AFT Callback

- For a case where the SMS is not recieved immediately by the recipient e.g if the recipient's 
  phone is on Airplane mode, the message status is marked as SENT. 
- As soon as AFT servers get an update from the Telco on the SMS deliver, they make a call to 
  callback.php which picks the reponse and updates the status of the sent message 
  i.e whether it was SUCCESSFUL or FAILED.
- The module creates logs of callback actions to callback_logs.txt 
- More logic can be inserted into the module as necessary e.g to display response to a user

## Logger
- The logger module create log files as required
- Simply instantiate `$log = new Logger("sent_sms_logs.txt");`
- Insert desired log `$log->insert(json_encode('This is a log'));`

## Database
- The logger module uses SQLite to write SMS responses from AFT


## Setting Up

1. Create a new bitnami app follow the official docs:
   ```
   https://docs.bitnami.com/aws/infrastructure/lamp/administration/create-custom-application-php/
   ```

2. Host the files there. In our case the app is called `sms`
	```
	You can use git to pull code from your repository into the server.
	```
3. On the AFT console, navigate to the appropriate app
4. Go to SMS > SMS Callback URLs > Delivery Reports
5. Set the callback url as `https://gobeba.com/sms/callback.php`


Notes:

```
Ensure the owner of the **htdocs** folder of your app is set as **daemon**. This is because the system
will need to be able to create app resources like the sqlite db and log files in order to function.

Use the unix command:

sudo chown daemon:daemon -R htdocs

```

