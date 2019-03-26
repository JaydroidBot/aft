# SMS Comms Module

This module integrates to the Africa Is Talking (AFT) API to send messages.

## File Structure

```
aft
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

1. Create a subdomain the `sms.gobeba.com`
2. Host the files there
3. On the AFT console, navigate to the appropriate app
4. Go to SMS > SMS Callback URLs > Delivery Reports
5. Set the callback url as `sms.gobeba.com/aft/callback.php`


