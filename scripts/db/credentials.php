<?php
// Si hay un atributo privado, solo se puede usar en la clase donde está, la herencia no puede acceder a él.
namespace APP\db;

abstract class credentials
{
    protected $host = 'localhost';
    // protected $host = '172.16.49.20';

    // private $user = 'sputnik';
    private $user = 'root';
    // private $password = 'Sp3tn1kC@';
    private $password = '';

    protected $dbname = 'campusland';

    public function __get($name)
    {
        return $this->{$name};
    }
}

?>