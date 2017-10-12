<?php

class AlexaEndpointDisplayCategories 
{
    const ACTIVITY_TRIGGER = 'ACTIVITY_TRIGGER';
    const CAMERA = 'CAMERA';
    const DOOR = 'DOOR';
    const LIGHT = 'LIGHT';
    const OTHER = 'OTHER';
    const SCENE_TRIGGER = 'SCENE_TRIGGER';
    const SMARTLOCK = 'SMARTLOCK';
    const SMARTPLUG = 'SMARTPLUG';
    const SPEAKERS = 'SPEAKERS';
    const SWITCH_ALEXA = 'SWITCH';
    const TEMPERATURE_SENSOR = 'TEMPERATURE_SENSOR';
    const THERMOSTAT = 'THERMOSTAT';
    const TV = 'TV';    
};

class AlexaEndpointCookies implements JsonSerializable  
{
    public function add_cookie( $name, $value ) {$this->{$name} = $value;}
    public function jsonSerialize() {return get_object_vars($this);}
}

class AlexaEndpointProperty implements JsonSerializable  
{
    private $data;
    public function __construct( $data ) {$this->data = $data;}
    public function jsonSerialize() {return ['name' => $this->data];}
}

class AlexaEndpointProperties implements JsonSerializable 
{
    public $supported = array();

    public function __construct($props)
    {
        if(is_array($props))
        {
            foreach($props as $prop)array_push($this->supported, new AlexaEndpointProperty($prop));
        }
        else array_push($this->supported, new AlexaEndpointProperty($props));
    }

    public function add_property($value)
    {
        array_push($this->supported, new AlexaEndpointProperty($value));
    }

    public function jsonSerialize() 
    {
        return [
            'supported' => $this->supported
        ];
    }

};

class AlexaCapabilityInterface implements JsonSerializable 
{
    private $type = "AlexaInterface";
    public $interface;
    public $version = "3";
    public $properties = null;
    public $proactivelyReported = false;
    public $retrievable = false;

    public function jsonSerialize() 
    {
        return [
            'type' => $this->type,
            'interface' => $this->interface,
            'version' => $this->version,
            'properties' => $this->properties,
            'proactivelyReported' => $this->proactivelyReported,
            'retrievable' => $this->retrievable
        ];
    }
};

class AlexaCapabilityInterfaceAlexa implements JsonSerializable
{
    private $type = "AlexaInterface";
    private $interface = "Alexa";
    private $version = "3";

    public function jsonSerialize() 
    {
        return [
            'type' => $this->type,
            'interface' => $this->interface,
            'version' => $this->version
        ];
    }
};

class AlexaCapabilityInterfacePowerController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.PowerController";
        $this->proactivelyReported = true;
        $this->retrievable = true;
        $this->properties = new AlexaEndpointProperties("powerState");                
    }   
}

class AlexaCapabilityInterfaceEndpointHealth extends AlexaCapabilityInterface 
{
  
    public function __construct()
    {
        $this->interface = "Alexa.EndpointHealth";
        $this->proactivelyReported = true;
        $this->retrievable = true;
        $this->properties = new AlexaEndpointProperties("connectivity");                
    }   
}

class AlexaEndpoint implements JsonSerializable 
{
    // Only following chars & symbols accepted: [a-z][A-Z][0-9] _ - = # ; : ? @ &
    public $endpointId = ""; //max 256 chars
    public $manufacturerName = "";
    public $friendlyName = "";
    public $description = "";
    private $displayCategories = array(); 
    private $capabilities = array();
    private $cookie = null;    
    
    public function __construct( $endpointId, $name ) 
    {
        $this->endpointId = $endpointId;
        $this->friendlyName = $name;
    }

    public function set_cookie($cookies){$this->cookie = $cookies;}
    public function add_capability($cap){array_push($this->capabilities, $cap);}

    public function add_displayCategories($displayCategories)
    {
        if(is_array($displayCategories))
        {
            foreach($displayCategories as &$cat)
            {
                array_push($this->displayCategories, $cat);
            }
        }
        else array_push($this->displayCategories, $displayCategories);
    }

    public function jsonSerialize() 
    {
        return [
            'endpointId' => $this->endpointId,
            'manufacturerName' => $this->manufacturerName,
            'friendlyName' => $this->friendlyName,
            'description' => $this->description,
            'displayCategories' => $this->displayCategories,
            'capabilities' => $this->capabilities,
            'cookie' => $this->cookie == null? new stdClass(): $this->cookie
        ];
    }
};

class AlexaEndpoints implements JsonSerializable 
{
    private $endpoints = array();

    public function add(AlexaEndpoint $dev)
    {
        array_push($this->endpoints, $dev);
    }

    public function jsonSerialize() 
    {
        return new AlexaResponsePayload("endpoints", $this->endpoints);
    }
};


?>