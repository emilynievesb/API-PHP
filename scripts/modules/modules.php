<?php

namespace APP\modules;

use APP\db\connect;
use APP\getInstance;

class modules extends connect
{
    private $queryPost = 'INSERT INTO modules ( name_module, start_date, end_date, description, duration_days, id_theme) VALUES ( :module, :start_date, :end_date, :description, :days, :theme_fk)';
    private $queryPut = 'UPDATE modules SET name_module = :module, start_date = :start_date, end_date = :end_date,description  = :description,duration_days  = :days, id_theme  = :theme_fk WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", name_module AS "module", start_date AS "start_date", end_date AS "end_date", description AS "description", duration_days  = :days, id_theme AS "theme_fk",  FROM modules';
    private $queryDelete = 'DELETE FROM modules WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $name_module = 1, public $start_date = 1, public $end_date = 1, public $description = 1, public $duration_days = 1, private $id_theme = 1)
    {
        parent::__construct();

    }
    public function post_modules()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("module", $this->name_module);
            $res->bindValue("start_date", $this->start_date);
            $res->bindValue("end_date", $this->end_date);
            $res->bindValue("description", $this->description);
            $res->bindValue("days", $this->duration_days);
            $res->bindValue("theme_fk", $this->id_theme);
            /**Execute es para ejecutar */
            $res->execute();
            $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Inserted data", "res" => $res];
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];

        } finally {
            print_r($this->message);
        }
    }

    public function update_modules()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("module", $this->name_module);
            $res->bindValue("start_date", $this->start_date);
            $res->bindValue("end_date", $this->end_date);
            $res->bindValue("description", $this->description);
            $res->bindValue("days", $this->duration_days);
            $res->bindValue("theme_fk", $this->id_theme);
            /**Execute es para ejecutar */
            $res->execute();
            if ($res->rowCount() > 0) {
                $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Data updated"];
            } else {
                $this->message = ["Code" => 404, "Message" => "Data not founded"];

            }
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            print_r($this->message);
        }
    }

    public function delete_modules()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryDelete);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            $res->bindValue("id", $this->id);
            /**Execute es para ejecutar */
            $res->execute();

            if ($res->rowCount() > 0) {
                $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Data deleted"];
            } else {
                $this->message = ["Code" => 404, "Message" => "Data not founded"];
            }
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            print_r($this->message);
        }
    }

    public function getAll_modules()
    {
        try {
            $res = $this->conx->prepare($this->queryGetAll);
            $res->execute();
            $this->message = ["Code" => 200, "Message" => $res->fetchAll(\PDO::FETCH_ASSOC)];
        } catch (\PDOException $e) {
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            print_r($this->message);
        }
    }
}
?>