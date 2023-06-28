<?php
namespace APP\campus;

use APP\db\connect;
use APP\getInstance;

class campus extends connect
{

    private $queryChild = "SELECT TABLE_NAME
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = 'campusland'";
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct()
    {
        parent::__construct();

    }
    public function get_tables()
    {
        try {
            $res = $this->conx->prepare($this->queryChild);
            $res->execute();
            $this->message = ["Code" => 200, "Message" => $res->fetchAll(\PDO::FETCH_ASSOC)];
        } catch (\PDOException $e) {
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            echo json_encode($this->message);
        }
    }
}
?>