<?php

function guidv4()
{
    $data = openssl_random_pseudo_bytes(16);
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

class AlexaHeader implements JsonSerializable
{

    public $name;
    public $namespace;
    public $payloadVersion = "3";
    public $messageId;
    public $correlationToken = null;

    public function __construct() 
    {
        if (method_exists($this,$f='__construct'.func_num_args())) 
        {
            call_user_func_array(array($this,$f),func_get_args());
        } 
    }

    public function __construct3($namespace, $name, $correlationToken)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->correlationToken = $correlationToken;
        $this->messageId = guidv4();        
    }

    public function __construct2($namespace, $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->messageId = guidv4();        
    }

    public function __construct1( stdClass $object ) 
    {
        foreach($object as $property => &$value)
        {
            $this->$property = &$value;
            unset($object->$property);
        }
        unset($value);
        $object = (unset) $object;       
    }

    public function jsonSerialize() 
    {
        $ret = [
            'name' => $this->name,
            'namespace' => $this->namespace,
            'payloadVersion' => $this->payloadVersion,
            'messageId' => $this->messageId
        ];
        if(isset($this->correlationToken))
        {
            $ret['correlationToken'] = $this->correlationToken;
        }
        return $ret;
    }   
};


?>


