<?php

function guidv4()
{
    $data = openssl_random_pseudo_bytes(16);
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

class AWSResponsePayload implements JsonSerializable  
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

class AWSResponseHeader implements JsonSerializable 
{
    private $messageId;
    private $name;
    private $namespace;
    private $payloadVersion = "2";

    public function __construct( $name, $namespace ) 
    {
        $this->messageId = guidv4();        
        $this->namespace = $namespace;
        switch($name)
        {
            case 'TurnOnRequest':
            case 'TurnOffRequest':
            case 'SetPercentageRequest':
            case 'IncrementPercentageRequest':
            case 'DecrementPercentageRequest':
                $this->name = str_replace("Request", "Confirmation", $name);
                break;
                
            default:
                $this->name = str_replace("Request", "Response", $name);
                break;
        }
    }

    public function jsonSerialize() 
    {
        return [
            'messageId' => $this->messageId,
            'name' => $this->name,
            'namespace' => $this->namespace,
            'payloadVersion' => $this->payloadVersion
        ];
    }
};

class AWSResponse implements JsonSerializable 
{

    const TARGET_OFFLINE_ERROR = 'TargetOfflineError';
    const OPERATION_NOT_ALLOWED_ERROR = 'OperationNotAllowedForUserError';
    const NO_SUCH_TARGET_ERROR = 'NoSuchTargetError';

    private $header = null;
    private $payload = null;


    public function __construct( $name, $namespace, $payload ) 
    {
        $this->header = new AWSResponseHeader($name, $namespace);
        if($payload != null)$this->payload = new AWSResponsePayload($payload->payloadName(), $payload);       
    }

    public function jsonSerialize() 
    {
        return [
            'header' => $this->header,
            'payload' => $this->payload==null?new stdClass():$this->payload
        ];
    }
};


?>