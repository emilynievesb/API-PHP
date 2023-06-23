<?php
namespace APP;

trait getInstance
{
    public static $instance;
    public static function getInstance()
    {
        $arg = func_get_args();
        $arg = array_pop($arg);
        return (!(self::$instance instanceof self) || !empty($arg)) ? self::$instance = new static(...(array) $arg) : self::$instance;
    }
    //*Set y get mágicos...
    function __set($name, $value)
    {
        $this->$name = $value;
    }

}
?>