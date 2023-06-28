<?php
namespace APP\db;


interface enviroments
{
    public function __get($name);
}

// Al testear la conexión, al ser una clase abstracta, no se imprime el echo. Y para testearla es necesario
// instanciarla con new pq no usa getInstance, y pues hay que quitar el abstract
abstract class connect extends credentials implements enviroments
{
    // private $pass = "";
    // private $user = "root";
    protected $conx;
    function __construct(private $driver = 'mysql', private $port = 3306)
    {
        try {
            $this->conx = new \PDO($this->driver . ":host=" . $this->__get('host') . ";port=" . $this->port . "; dbname=" . $this->__get('dbname') . "; user=" . $this->user . "; password=" . $this->password);
            $this->conx->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $error) {
            echo "No me pude conectar" . $error->getMessage();
        }
    }

}

?>