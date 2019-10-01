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
   
- ###### alexa_report.php - Alexa ChangeReport ans AsyncResponse responses implementation
   - asynchronous response for AlexaChangeReport(proactively reported states) and asynchronous Alexa Response after deferred reponse. This kind of responses should be sent to Alexa Event Gateway
   
- ###### alexa_discovery.php - Alexa Discovery request parser and response constructor
   - AlexaDiscoveryRequest parses discovery request forwarded from aws_lambda.js. And constructs response with event, header and given payload

- ###### alexa_endpoint.php - implements Endpoints Interfaces.
   - Endpoint represents a connected device associated with the customer’s device cloud account. (Smart Device)
   
- ###### alexa_context.php - implements Alexa Context class.
   - AlexaContext class represents Context object for example for Alexa StateReport response.
   
- ###### alexa_control.php - parser class for AlexaControl requests and directive
   - parses Control request and sets correlation token, directive and scope for future use.   
   
- ###### alexa_const_errors.php - Helpers for responsing errors on failed control requests 
   - AlexaError helper class constructs 'type' -> 'message' chain for given error type in constructor

#### aws_lambda.js : Node.js 6.10
   - Example code for AWS Lambda function to forward requests and responses from AWS to your server and back
   - copy or upload this file to your AWS Lambda function.
   - Don't forget to change **REMOTE_CLOUD_HOSTNAME** and **REMOTE_CLOUD_BASE_PATH** to the right paths

     ```javascript
     var https = require('https');
     var REMOTE_CLOUD_BASE_PATH = "/smarthome_skill/";
     var REMOTE_CLOUD_HOSTNAME = "yourdomain.com";
     ```
#### discovery.php
    This is an example of using Amazon Alexa Smart Home Skill PHP API.
    It simply parses AlexaDiscoveryRequest generates one Endpoint device and sends response back.
    


##### TODO:
  - [x] AWS Lambda code for forwarding request and responses 
  - [x] Discovery example code - respond to Discover request with example devices
  - [x] turnON, turnOFF example code for PowerSwitch Interface


