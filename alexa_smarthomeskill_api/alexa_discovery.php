<?php

require_once(dirname(__FILE__)."/alexa_header.php");
require_once(dirname(__FILE__)."/alexa_payload.php");
require_once(dirname(__FILE__)."/alexa_response.php");

class AlexaDiscoveryRequest
{
    public $header = null;
    public $payload = null;
    public $amazon_user_id;

    public static function fromJSON(stdClass &$object)
    {
        if(!isset($object->directive) || !isset($object->amazon_user))
        {
            return null;
        }
        return new AlexaDiscoveryRequest($object);
    }

    private function __construct(stdClass &$object)
    {        
        $this->header = new AlexaHeader($object->directive->header);
        $this->payload = new AlexaDiscoveryRequestPayloadScope($object->directive->payload->scope);
        $this->amazon_user_id = $object->amazon_user->user_id;
    }
};

class AlexaDiscoveryResponse implements JsonSerializable
{
    private $response = null;

    public function __construct($payload) 
    {
       $this->response = new AlexaResponse("Alexa.Discovery", "Discover.Response", $payload);
    }

    public function jsonSerialize() 
    {
        return $this->response;        
    }   
};



?>