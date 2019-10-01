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
};

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

    public function add_supported_property($value)
    {
        array_push($this->supported, new AlexaEndpointProperty($value));
    }

    public function add_property($name, $value)
    {
        $this->{$name} = $value;
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }

};

class AlexaCapabilityInterface implements JsonSerializable 
{
    private $type = "AlexaInterface";
    protected $interface;
    private $version = "3";

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }
};

class AlexaCapabilityInterfaceAlexa extends AlexaCapabilityInterface
{
    public function __construct()
    {
        $this->interface = "Alexa";
    }    
};

class AlexaCapabilityInterfacePowerController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.PowerController";
        $this->properties = new AlexaEndpointProperties("powerState");
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);
    }   
};

class AlexaCapabilityInterfaceEndpointHealth extends AlexaCapabilityInterface 
{  
    public function __construct()
    {
        $this->interface = "Alexa.EndpointHealth";
        $this->properties = new AlexaEndpointProperties("connectivity");      
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);          
    }   
};

class AlexaCapabilityInterfaceColorController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.ColorController";
        $this->properties = new AlexaEndpointProperties("color");  
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);              
    }   
};

class AlexaCapabilityInterfaceBrightnessController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.BrightnessController";
        $this->properties = new AlexaEndpointProperties("brightness");         
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);       
    }   
};

class AlexaCapabilityInterfaceColorTemperatureController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.ColorTemperatureController";
        $this->properties = new AlexaEndpointProperties("colorTemperatureInKelvin");   
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);             
    }   
};

class AlexaCapabilityInterfacePowerLevelController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.PowerLevelController";
        $this->properties = new AlexaEndpointProperties("powerLevel");  
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);              
    }   
};

class AlexaCapabilityInterfacePercentageController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.PercentageController";
        $this->properties = new AlexaEndpointProperties("percentage");    
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);            
    }   
};

class AlexaCapabilityInterfaceThermostatController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.ThermostatController";
        $props = new AlexaEndpointProperties("targetSetpoint");
        $props->add_supported_property("thermostatMode");
        $props->add_supported_property("upperSetpoint");
        $props->add_supported_property("lowerSetpoint");
        $props->properties->add_property("proactivelyReported", true);
        $props->properties->add_property("retrievable", true);
        $this->properties = $props;
    }   
};

class AlexaCapabilityInterfaceTemperatureSensor extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.TemperatureSensor";
        $this->properties = new AlexaEndpointProperties("temperature");   
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);             
    }   
};

class AlexaCapabilityInterfaceLockController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.LockController";
        $this->properties = new AlexaEndpointProperties("lockState");     
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);           
    }   
};

class AlexaCapabilityInterfaceChannelController extends AlexaCapabilityInterface 
{
    public function __construct()
    {
        $this->interface = "Alexa.ChannelController";
        $this->properties = new AlexaEndpointProperties("channel"); 
        $this->properties->add_property("proactivelyReported", true);
        $this->properties->add_property("retrievable", true);               
    }   
};

class AlexaCapabilityInterfaceSceneController implements JsonSerializable
{
    private $type = "AlexaInterface";
    private $interface = "Alexa.SceneController";
    private $version = "3";
    public $supportsDeactivation = true;
    public $proactivelyReported = true;

    public function jsonSerialize() 
    {
        return [
            'type' => $this->type,
            'interface' => $this->interface,
            'version' => $this->version,
            'supportsDeactivation' => $this->supportsDeactivation,
            'proactivelyReported' => $this->proactivelyReported
        ];
    }
};

class AlexaCapabilityInterfaceCameraStreamController implements JsonSerializable
{
    private $type = "AlexaInterface";
    private $interface = "Alexa.CameraStreamController";
    private $version = "3";
    public $cameraStreamConfigurations = null;

    /** To implement
     *  {
                                    "protocols": [
                                        "RTSP"
                                    ],
                                    "resolutions": [
                                        {
                                            "width": 1280,
                                            "height": 720
                                        }
                                    ],
                                    "authorizationTypes": [
                                        "NONE"
                                    ],
                                    "videoCodecs": [
                                        "H264"
                                    ],
                                    "audioCodecs": [
                                        "AAC"
                                    ]
}
     */

    public function jsonSerialize() 
    {
        return [
            'type' => $this->type,
            'interface' => $this->interface,
            'version' => $this->version,
            'cameraStreamConfigurations' => $this->cameraStreamConfigurations
        ];
    }
};

class AlexaEndpointOnlyID implements JsonSerializable 
{
    private $endpointId = "";

    public function __construct( $endpointId ) 
    {
        $this->endpointId = $endpointId;
    }

    public function jsonSerialize() 
    {
        return [
            'endpointId' => $this->endpointId
        ];
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
    public function add_capability($cap){if($cap != null)array_push($this->capabilities, $cap);}

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

    public function contains_displayCategory($category)
    {
        if(is_array($this->displayCategories))
        {
            foreach($this->displayCategories as &$cat)
            {
                if(strcmp($cat, $category) == 0)return true;
            }
        }
        return false;
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