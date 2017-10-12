## PHP API for Amazon Alexa Smart Home Skill (updated Version 3)


#### alexa_smarthomeskill_api - collection of PHP API for Alexa Smart Home Skill
 
- ###### alexa_discovery.php - implemets Discovery Interface. Provides PHP classes for
   - parsing discovery request from Amazon(AWS Lambda)
   - and building response with or without Endpoints(Smart Home devices) as described [here](https://developer.amazon.com/docs/device-apis/alexa-discovery.html)

- ###### alexa_header.php - Alexa Header 
   - parsing and building Alexa headers

- ###### alexa_payload.php - Alexa Payloads
   - payloads for responses

- ###### alexa_response.php - Alexa Response constructor
   - constructs response with event, header and given payload

- ###### alexa_endpoint.php - implements Endpoints Interfaces.
   - Endpoint represents a connected device associated with the customer’s device cloud account. (Smart Device)

#### aws_lambda.js : Node.js 6.10
   - Example code for AWS Lambda function to forward requests and responses from AWS to your server and back
   - copy or upload this file to your AWS Lambda function.
   - Don't forget to change **REMOTE_CLOUD_HOSTNAME** and **REMOTE_CLOUD_BASE_PATH** to the right paths

     ```javascript
     var https = require('https');
     var REMOTE_CLOUD_BASE_PATH = "/smarthome_skill/";
     var REMOTE_CLOUD_HOSTNAME = "yourdomain.com";
     ```


##### TODO:
  - [x] AWS Lambda code for forwarding request and responses 
  - [ ] Discovery example code - respond to Discover request with example devices
  - [ ] turnON, turnOFF example code for PowerSwitch Interface


