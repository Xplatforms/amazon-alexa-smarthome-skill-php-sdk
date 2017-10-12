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
        if($payload != null)$this->payload = $payload;
    }

    public function jsonSerialize() 
    {
        return [
            'header' => $this->header,
            'payload' => $this->payload==null?new stdClass():$this->payload
        ];
    }

};

class AlexaResponse implements JsonSerializable
{
    private $event = null;

    public function __construct($namespace, $name, $payload ) 
    {
        $this->event = new AlexaEvent(new AlexaHeader($namespace, $name), $payload);      
    }

    public function jsonSerialize() 
    {
        return [
            'event' => $this->event
        ];
    }
};

?>