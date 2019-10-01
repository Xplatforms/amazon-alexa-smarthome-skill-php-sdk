<?php

require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_control.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_endpoint.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_response.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_report.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_state.php');


$req = file_get_contents ( 'php://input' );
$json_data = json_decode($req);

header('Content-Type: application/json');

$device_state_is_on = true; // change to false if you wish response with device state OFF
$device_is_online = true; // change to false if you wish response with device unreachable msg

$state_request = AlexaStateRequest::fromJSON($json_data);
if($state_request == null)
{
    //something gone wrong. log error somewhere
    exit();
}



$context_prop = new AlexaContextProperty("Alexa.PowerController", "powerState", $device_state_is_on?"ON":"OFF", 100);
$health_prop = new AlexaContextProperty("Alexa.EndpointHealth", "connectivity", new AlexaContextPropertyValue("value", $device_is_online?"OK":"UNREACHABLE"), 100);
$context = new AlexaContext();
$context->add_property($context_prop);
$context->add_property($health_prop);
$state_endpoint = new AlexaStateEndpoint($state_request->endpoint->scope->token, $state_request->endpoint->endpointId);
$state_report = new AlexaStateResponse($state_request->header->correlationToken, $context, $state_endpoint);

echo json_encode($state_report);

?>