<?php


class SmartDeviceAdditionalApplianceDetails implements JsonSerializable
{
    private $extraDetail1 = "";
    private $extraDetail2 = "";
    private $extraDetail3 = "";
    private $extraDetail4 = "";

    public function __construct($extra1, $extra2, $extra3, $extra4 ) 
        {
            $this->extraDetail1 = $extra1;
            $this->extraDetail2 = $extra2;
            $this->extraDetail3 = $extra3;
            $this->extraDetail4 = $extra4;
        }

        public function jsonSerialize() 
        {
            return [
                'extraDetail1' => $this->extraDetail1,
                'extraDetail2' => $this->extraDetail2,
                'extraDetail3' => $this->extraDetail3,
                'extraDetail4' => $this->extraDetail4
            ];
        }

};

class SmartDevices implements JsonSerializable 
{
    private $devices = array();

    public function __construct( $devices ) 
    {
        if(!$devices)return;
        $this->devices = $devices;
    }

    public function payloadName(){return "discoveredAppliances";}

    public function addDevice($device)
    {
        array_push($this->devices, $device);
    }

    public function jsonSerialize() 
    {
        return  $this->devices;
    }
}

class SmartDeviceType
{
    const CAMERA = 'CAMERA';
    const LIGHT = 'LIGHT';
    const SMARTLOCK = 'SMARTLOCK';
    const SMARTPLUG = 'SMARTPLUG';
    const SWITCH_TYPE = 'SWITCH';
    const THERMOSTAT = 'THERMOSTAT';
    const ACTIVITY_TRIGGER = 'ACTIVITY_TRIGGER';
    const SCENE_TRIGGER = 'SCENE_TRIGGER';
};

class SmartDeviceActions implements JsonSerializable
{
    const decrementColorTemperature = "decrementColorTemperature";
    const decrementPercentage = "decrementPercentage";
    const decrementTargetTemperature = "decrementTargetTemperature";
    const getLockState = "getLockState";
    const getTargetTemperature = "getTargetTemperature";
    const getTemperatureReading = "getTemperatureReading";
    const incrementColorTemperature = "incrementColorTemperature";
    const incrementPercentage = "incrementPercentage";
    const incrementTargetTemperature = "incrementTargetTemperature";
    const retrieveCameraStreamUri = "retrieveCameraStreamUri";
    const setColor = "setColor";
    const setColorTemperature = "setColorTemperature";
    const setLockState = "setLockState";
    const setPercentage = "setPercentage";
    const setTargetTemperature = "setTargetTemperature";
    const turnOff = "turnOff";
    const turnOn = "turnOn";

    private $actions = array();

    public function __construct(  ) 
    {
         if(func_num_args() > 0)
         {
             foreach(func_get_args() as $arg)
             {
                 if(is_array($arg))
                 {
                     foreach($arg as $elem)array_push($this->actions, $elem);
                 }
                 else array_push($this->actions, $arg);
             }
         }       
    }

    public function addAction($action)
    {
        array_push($this->actions, $action);
    }

    public function jsonSerialize() 
    {
        return $this->actions;
    }
};

class SmartDevice implements JsonSerializable 
{
    // Only following chars & symbols accepted: [a-z][A-Z][0-9] _ - = # ; : ? @ &
    private $applianceId = ""; //max 256 chars
    private $manufacturerName = "My Own IoT Device";
    private $modelName = "My First IoT Device";
    private $version = "1.0";
    private $friendlyName = "";
    private $friendlyDescription = "";
    private $isReachable = true;
    private $actions = null;
    private $applianceTypes = array();
    private $additionalApplianceDetails = null;

    public function __construct( $appId, $name ) 
    {
        $this->set_applianceId($appId);
        $this->set_friendlyName($name);
    }

    public function set_applianceId($id)
    {
        $this->applianceId = $id;
    }

    public function get_applianceId(){return $this->applianceId;}

    public function set_manufacturerName($name){$this->manufacturerName = $name;}
    public function get_manufacturerName(){return $this->manufacturerName;}

    public function set_modelName($name){$this->modelName = $name;}
    public function get_modelName(){return $this->modelName;}

    public function set_version($ver){$this->version = $ver;}
    public function get_version(){return $this->version;}

    public function set_friendlyName($name){$this->friendlyName = $name;}
    public function get_friendlyName(){return $this->friendlyName;}

    public function set_friendlyDescription($desc){$this->friendlyDescription = $desc;}
    public function get_friendlyDescription(){return $this->friendlyDescription;}

    public function set_isReachable($reachable){$this->isReachable = $reachable;}
    public function get_isReachable(){return $this->manufacturerName;}

    public function set_actions($actions)
    {
        if(is_array($actions))
        {
            foreach($actions as $action)
            {
                $this->add_action($action);
            }        
        }
        else $this->actions = $actions;
    }

    public function add_action($action)
    {
        if($this->actions == null)$this->actions = new SmartDeviceActions();
        $this->actions->addAction($action);
    }

    public function get_actions(){return $this->actions;}

    public function set_applianceTypes($applianceTypes){$this->applianceTypes = $applianceTypes;}
    public function add_applianceType($applianceType){array_push($this->applianceTypes, $applianceType);}
    public function get_applianceTypes(){return $this->applianceTypes;}
    
    public function set_additionalApplianceDetails($details){$this->additionalApplianceDetails = $details;}
    public function get_additionalApplianceDetails(){return $this->additionalApplianceDetails;}

    public function jsonSerialize() 
    {
        return [
            'applianceId' => $this->applianceId,
            'manufacturerName' => $this->manufacturerName,
            'modelName' => $this->modelName,
            'version' => $this->version,
            'friendlyName' => $this->friendlyName,
            'friendlyDescription' => $this->friendlyDescription,
            'isReachable' => $this->isReachable,
            'actions' => $this->actions,
            'applianceTypes' => $this->applianceTypes,
            'additionalApplianceDetails' => $this->additionalApplianceDetails == null? new stdClass(): $this->additionalApplianceDetails
        ];
    }

};

?>
