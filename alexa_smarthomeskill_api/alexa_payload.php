<?php

class AlexaDiscoveryRequestPayloadScope
{
    public $type;
    public $token;

    public function __construct(stdClass &$object)
    {
        foreach($object as $property => &$value)
        {
            $this->$property = &$value;
            unset($object->$property);
        }
        unset($value);
        $object = (unset) $object; 
    }
}

class AlexaResponsePayload implements JsonSerializable  
{
    private $data = null;
    private $name;

    public function __construct( $name, $data_class ) 
    {
        $this->name = $name;
        $this->data = $data_class;
    }

    public function jsonSerialize() 
    {
        return [
            $this->name => $this->data
        ];
    }
}

class AlexaAcceptGrantErrorResponsePayload implements JsonSerializable  
{
    private $type;
    private $message;

    public function __construct( $type, $message ) 
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function jsonSerialize() 
    {
        return [
            'type' => $this->type,
            'message' => $this->message
        ];
    }
}

class AlexaErrorResponsePayload extends AlexaAcceptGrantErrorResponsePayload{}

?>