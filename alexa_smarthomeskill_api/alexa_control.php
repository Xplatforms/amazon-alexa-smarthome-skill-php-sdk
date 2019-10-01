<?php

require_once(dirname(__FILE__)."/alexa_header.php");
require_once(dirname(__FILE__)."/alexa_payload.php");
require_once(dirname(__FILE__)."/alexa_response.php");

class AlexaControlRequest
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
        return new AlexaControlRequest($object);
    }

    private function __construct(stdClass &$object)
    {        
        $this->header = new AlexaHeader($object->directive->header);
        $this->endpoint = $object->directive->endpoint;
        $this->payload = $object->directive->payload;        
    }
};

?>