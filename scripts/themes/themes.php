<?php

namespace APP\themes;

use APP\db\connect;
use APP\getInstance;

class themes extends connect
{
    private $queryPost = 'INSERT INTO themes ( id_chapter, start_date, end_date, description, duration_days, name_theme) VALUES ( :chapter_fk, :start_date, :end_date, :description, :days, :theme_name)';
    private $queryPut = 'UPDATE themes SET id_chapter = :chapter_fk, start_date = :start_date, end_date = :end_date,description  = :description,duration_days  = :days, name_theme  = :theme_name WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_chapter AS "chapter_fk", start_date AS "start_date", end_date AS "end_date", description AS "description", name_theme AS "theme_name",  FROM themes';
    private $queryDelete = 'DELETE FROM themes WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_chapter = 1, public $start_date = 1, public $end_date = 1, public $description = 1, public $duration_days = 1, private $name_theme = 1)
    {
        parent::__construct();

    }
    public function post_themes()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("chapter_fk", $this->id_chapter);
            $res->bindValue("start_date", $this->start_date);
            $res->bindValue("end_date", $this->end_date);
            $res->bindValue("description", $this->description);
            $res->bindValue("days", $this->duration_days);
            $res->bindValue("theme_name", $this->name_theme);
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

    public function update_themes()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("chapter_fk", $this->id_chapter);
            $res->bindValue("start_date", $this->start_date);
            $res->bindValue("end_date", $this->end_date);
            $res->bindValue("description", $this->description);
            $res->bindValue("days", $this->duration_days);
            $res->bindValue("theme_name", $this->name_theme);
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

    public function delete_themes()
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

    public function getAll_themes()
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