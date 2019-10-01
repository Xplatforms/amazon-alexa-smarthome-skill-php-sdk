<?php
require_once(dirname(__FILE__)."/alexa_header.php");
require_once(dirname(__FILE__)."/alexa_payload.php");
require_once(dirname(__FILE__)."/alexa_response.php");
require_once(dirname(__FILE__)."/alexa_context.php");
require_once(dirname(__FILE__)."/alexa_state.php");

class AlexaChangeReportPayloadChangeCause implements JsonSerializable
{
    private $cause = null;

    public function __construct($change_type ) 
    {
        $this->cause = $change_type;
    }

    public function jsonSerialize() 
    {
        return [
            'type' => $this->cause
        ];
    }
}


class AlexaChangeReportPayloadChangeType implements JsonSerializable
{
    private $change_type = null;
    private $properties = null;

    public function __construct($change_type, $properties ) 
    {
        $this->change_type = $change_type;
        $this->properties = $properties;
    }

    public function jsonSerialize() 
    {
        return [
            'cause' => new AlexaChangeReportPayloadChangeCause($this->change_type),
            'properties' => $this->properties
        ];
    }
}


class AlexaChangeReportPayload implements JsonSerializable  
{
    private $properties = array();
    private $change_type = null;

    public function __construct($properties, $change_type ) 
    {
        $this->change_type = $change_type;
        array_push($this->properties, $properties);
    }

    public function addProperty(AlexaContextProperty $prop)
    {
        array_push($this->properties, $prop);
    }

    public function jsonSerialize() 
    {
        return [
            //'properties' => $this->properties,
            'change' => new AlexaChangeReportPayloadChangeType($this->change_type, $this->properties)
        ];
    }
}

class AlexaChangeReportResponse extends AlexaResponse
{
    private $context = null;
    private $payload = null;
    private $header = null;
    private $endpoint = null;

    public function __construct($context, $namespace, $name, $value, $change_type, $scope_token, $endpointId, $uncertaintyms = 0)
    {
        $this->payload = new AlexaChangeReportPayload(new AlexaContextProperty($namespace, $name, $value, intval($uncertaintyms)), $change_type);
        $this->header = new AlexaHeader("Alexa", "ChangeReport");
        //$this->event = new AlexaEvent($header, $payload);
        $this->endpoint = new AlexaStateEndpoint($scope_token, $endpointId);;
        //$this->event->endpoint = new AlexaStateEndpoint($scope_token, $endpointId);
        $this->context = $context;
    }

    public function addPayloadProperty(AlexaContextProperty $alexa_prop)
    {
        $this->payload->addProperty($alexa_prop);
    }

    public function jsonSerialize() 
    {
        $this->event = new AlexaEvent($this->header, $this->payload);
        $this->event->endpoint = $this->endpoint;
        return [
            'event' => $this->event,
            'context' => $this->context == null? new stdClass(): $this->context
        ];
    }
}

class AlexaAsyncResponse extends AlexaResponse
{
    private $context = null;

    public function __construct($context, $scope_token, $endpointId, $correlationToken )
    {
        //$payload = new AlexaChangeReportPayload(new AlexaContextProperty($namespace, $name, $value, intval($uncertaintyms)), $change_type);
        $header = new AlexaHeader("Alexa", "Response", $correlationToken);
        $this->event = new AlexaEvent($header, $payload);
        $this->event->endpoint = new AlexaStateEndpoint($scope_token, $endpointId);
        $this->context = $context;
    }

    public function jsonSerialize() 
    {
        return [
            'event' => $this->event,
            'context' => $this->context
        ];
    }
}

?>