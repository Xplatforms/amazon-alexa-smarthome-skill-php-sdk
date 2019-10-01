<?php

require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_control.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_endpoint.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_response.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_report.php');
require_once(dirname(dirname(__FILE__)).'/alexa_smarthomeskill_api/alexa_const_errors.php');


$req = file_get_contents ( 'php://input' );
$json_data = json_decode($req);

header('Content-Type: application/json');

$not_found = false; // change it to true if you wish device not found response
$user_check_faild = false; // change to true if you wish user credential error response

$alexa_control = AlexaControlRequest::fromJSON($json_data);
if($alexa_control == null)
{
    //this should not happen. Maybe log error somewhere
    exit();
}
else
{
    switch($alexa_control->request_namespace())
    {
        case 'Alexa.PowerController':
        {
            // you should check $alexa_control->endpoint->endpointId here. Is it registered in your system at all?
            // also check if amazon user owns this smart device and can control it. Use $alexa_control->scope()->token for that
            // If smart device is not found in your system response with error and exit:
            if($not_found)
            {
                $err = new AlexaError(AlexaErrorTypes::NO_SUCH_ENDPOINT);
                $resp_error = new AlexaErrorResponse($alexa_control->endpoint->endpointId, $err->type, $err->msg);
                echo json_encode($resp_error);
                exit();
            }

            if($user_check_faild)
            {
                $err = new AlexaError(AlexaErrorTypes::INVALID_AUTHORIZATION_CREDENTIAL);
                $resp_error = new AlexaErrorResponse($alexa_control->endpoint->endpointId, $err->type, $err->msg);
                echo json_encode($resp_error);
                exit();
            }

            $context = new AlexaContext();

            if($alexa_control->todo() == 'TurnOn') 
            {
                //turn on your device and response with success
                $context->add_property(new AlexaContextProperty("Alexa.PowerController", "powerState", "ON", 500));
                
            }
            else if($alexa_control->todo() == 'TurnOff')
            {
                //turn off your device and response with success
                $context->add_property(new AlexaContextProperty("Alexa.PowerController", "powerState", "OFF", 500));
            }

            $context->add_property(new AlexaContextProperty("Alexa.EndpointHealth", "connectivity", new AlexaContextPropertyValue("value", "OK"), 6000));
            //actually scope is not needed for synchronous response. But should be set in async response
            $state = new AlexaAsyncResponse($context, $alexa_control->scope()->token, $alexa_control->endpoint->endpointId, $alexa_control->correlationToken() );

            echo json_encode($state);
            exit();

        }
        break;
        //add more cases. For example for Light control, etc
        default:
        break;

}

?>