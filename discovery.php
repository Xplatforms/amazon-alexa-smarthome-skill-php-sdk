<?php

require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_discovery.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_endpoint.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_response.php');

$req = file_get_contents ( 'php://input' );
$json_data = json_decode($req);

header('Content-Type: application/json');

$alexa_discovery = AlexaDiscoveryRequest::fromJSON($json_data);
if($alexa_discovery == null)
{
    echo json_encode(new AlexaDiscoveryResponse(null));
    exit();
}
else
{

    $devices = new AlexaEndpoints();

    $iot_dev = new AlexaEndpoint("MyOwnIoT-Device-Version-1", "Friendly IoT Device name");
    $iot_dev->manufacturerName = "My DIY Device";
    $iot_dev->description = "This is very cool and usefull device";
    $iot_dev->add_displayCategories(AlexaEndpointDisplayCategories::SWITCH_ALEXA);

    $cookies = new AlexaEndpointCookies();
    $cookies->add_cookie("mykey", "this information is hidden from users.");
    $cookies->add_cookie("warning", "but dont store any confidential information here");
    $iot_dev->set_cookie($cookies);

    $iot_dev->add_capability(new AlexaCapabilityInterfaceAlexa());
    $iot_dev->add_capability(new AlexaCapabilityInterfacePowerController());
    $iot_dev->add_capability(new AlexaCapabilityInterfaceEndpointHealth());

    $devices->add($iot_dev);

    $devices_response = new AlexaDiscoveryResponse($devices);

    echo json_encode($devices_response);
}


?>
