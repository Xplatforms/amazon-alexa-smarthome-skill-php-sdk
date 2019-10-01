<?php
require_once(dirname(__FILE__)."/alexa_header.php");
require_once(dirname(__FILE__)."/alexa_payload.php");
require_once(dirname(__FILE__)."/alexa_response.php");
require_once(dirname(__FILE__)."/alexa_context.php");

class AlexaEndpointScope implements JsonSerializable  
{
    private $type = "BearerToken";
    private $token;
    public function __construct($token)
    {
        $this->token = $token;
    }
    public function jsonSerialize() 
    {
        return [
            'type' => $this->type,
            'token' => $this->token
        ];
    }
};

class AlexaStateEndpoint implements JsonSerializable
{
    private $scope;
    private $endpointId;

    public function __construct($scope_token, $endpointId)
    {
        $this->scope = new AlexaEndpointScope($scope_token);
        $this->endpointId = $endpointId;
    }

    public function jsonSerialize() 
    {
        return [
            'scope' => $this->scope,
            'endpointId' => $this->endpointId
        ];
    }

};

class AlexaStateRequest
{
    public $header = null;
    public $endpoint = null;
    public $payload = null;

    public function request_namespace(){return $this->header->namespace;}
    public function todo(){return $this->header->name;}
    public function correlationToken(){return $this->header->correlationToken;}
    public function scope(){return $this->endpoint->scope;}

    public static function fromJSON(stdClass &$object)
    {
        if(!isset($object->directive))
        {
            return null;
        }
        return new AlexaStateRequest($object);
    }

    private function __construct(stdClass &$object)
    {        
        $this->header = new AlexaHeader($object->directive->header);
        $this->endpoint = $object->directive->endpoint;
        $this->payload = $object->directive->payload;        
    }
};

class AlexaStateResponse implements JsonSerializable
{
    public $event = null;
    public $context = null;

    public function __construct($correlationToken, AlexaContext $context, AlexaStateEndpoint $endpoint) 
    {
        $this->event = new AlexaEvent(new AlexaHeader("Alexa", "StateReport", $correlationToken), null); 
        $this->event->endpoint = $endpoint;
        $this->context = $context;
    }    

    public function jsonSerialize() 
    {
        return [
            'context' => $this->context,
            'event' => $this->event
        ];
    }
};


?>