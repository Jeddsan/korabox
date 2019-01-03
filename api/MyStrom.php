<?php

class MyStrom
{
    private $ip;

    private $options = array(
        'http' => array(
            'protocol_version' => '1.1',
            'method' => 'GET'
        )
    );
    private $context;

    public function __construct($ip)
    {
        $this->ip = $ip;
        $this->context = stream_context_create($this->options);
    }

    public function on()
    {
        file_get_contents("http://" . $this->ip . "/relay?state=1", false, $this->context);
    }

    public function off()
    {
        file_get_contents("http://" . $this->ip . "/relay?state=0", false, $this->context);
    }

    public function toggle()
    {
        return json_decode(file_get_contents("http://" . $this->ip . "/toggle", false, $this->context), true)["relay"];
    }

    public function report()
    {
        return json_decode(file_get_contents("http://" . $this->ip . "/report", false, $this->context), true);
    }

    public function getRelay()
    {
        return self::report()["relay"];
    }

    public function getPower()
    {
        return self::report()["power"];
    }

    public function check()
    {
        $value = @file_get_contents("http://" . $this->ip . "/report", false, $this->context);
        if ($value === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}

?>
