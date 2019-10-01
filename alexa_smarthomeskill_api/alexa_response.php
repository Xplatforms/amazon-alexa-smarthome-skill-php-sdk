<?php

require_once(dirname(__FILE__)."/alexa_header.php");
require_once(dirname(__FILE__)."/alexa_payload.php");

class AlexaEvent implements JsonSerializable
{
    private $header = null;
    private $payload = null;

    public function __construct( $header, $payload ) 
    {
        $this->header = $header;
        if($payload == null)$this->payload = new stdClass();
        else $this->payload = $payload;
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }

};

class AlexaResponse implements JsonSerializable
{
    public $event = null;

    public function __construct($namespace, $name, $payload, $correlationToken = null) 
    {
        $this->event = new AlexaEvent(new AlexaHeader($namespace, $name, $correlationToken), $payload);      
    }

    public function jsonSerialize() 
    {
        return [
            'event' => $this->event
        ];
    }
};

class AlexaErrorResponse implements JsonSerializable
{
    public $event = null;

    public function __construct($endpointId, $type, $msg) 
    {
        $payload = new AlexaErrorResponsePayload($type, $msg);
        $this->event = new AlexaEvent(new AlexaHeader("Alexa", "ErrorResponse"), $payload);  
        $this->event->endpoint = new AlexaEndpointOnlyID($endpointId);    
    }

    public function jsonSerialize() 
    {
        return [
            'event' => $this->event
        ];
    }
};

class AlexaDeferredResponse extends AlexaResponse
{
    public function __construct($correlationToken, $estimatedDeferralInSeconds)
    {
        $payload = new AlexaResponsePayload("estimatedDeferralInSeconds", intval($estimatedDeferralInSeconds));
        $header = new AlexaHeader("Alexa", "DeferredResponse", $correlationToken);
        $this->event = new AlexaEvent($header, $payload);
    }
}

class AlexaAcceptGrantResponse extends AlexaResponse
{
    public function __construct()
    {
        $this->event = new AlexaEvent(new AlexaHeader("Alexa.Authorization", "AcceptGrant.Response"), null);
    }
}

class AlexaAcceptGrantErrorResponse extends AlexaResponse
{
    public function __construct($message)
    {
        $payload = new AlexaAcceptGrantErrorResponsePayload("ACCEPT_GRANT_FAILED", $message);
        $this->event = new AlexaEvent(new AlexaHeader("Alexa.Authorization", "ErrorResponse"), $payload);
    }
}

?>