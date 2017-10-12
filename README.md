## PHP API for Amazon Alexa Smart Home Skill (updated Version 3)

- #### alexa_discovery.php - implemets Discovery Interface. Provides PHP classes for
   - parsing discovery request from Amazon(AWS Lambda)
   - and building response with or without Endpoints(Smart Home devices) as described [here](https://developer.amazon.com/docs/device-apis/alexa-discovery.html)

- #### alexa_header.php - Alexa Header 
   - parsing and building Alexa headers

- #### alexa_payload.php - Alexa Payloads
   - payloads for responses

- #### alexa_response.php - Alexa Response constructor
   - constructs response with event, header and given payload

- #### alexa_endpoint.php - implements Endpoints Interfaces.
   - Endpoint represents a connected device associated with the customer’s device cloud account. (Smart Device)




##### TODO:
	AWS Lambda code for forwarding request and responses 
	Discovery example code - respond to Discover request with example devices
	turnON, turnOFF example code for PowerSwitch Interface


