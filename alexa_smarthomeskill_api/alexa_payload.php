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

?>