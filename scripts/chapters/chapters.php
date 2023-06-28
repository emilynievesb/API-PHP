<?php
namespace APP\chapters;

use APP\db\connect;
use APP\getInstance;

class chapters extends connect
{
    private $queryPost = 'INSERT INTO chapters (id_thematic_units, 	name_chapter, start_date, description, duration_days) VALUES (:thematics_fk, :chapter, :date, :description, :duration_days)';
    private $queryPut = 'UPDATE chapters SET id_thematic_units = :thematics_fk, name_chapter = :chapter, start_date = :date, description = :description, duration_days = :duration_days WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_thematic_units AS "thematics_fk", name_chapter AS "chapter", start_date AS "date", description AS "description", duration_days AS "duration_days" FROM chapters';
    private $queryDelete = 'DELETE FROM chapters WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_thematic_units = 1, public $name_chapter = 1, public $start_date = 1, public $description = 1, public $duration_days = 1)
    {
        parent::__construct();
    }
    public function post_chapters()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("thematics_fk", $this->id_thematic_units);
            $res->bindValue("chapter", $this->name_chapter);
            $res->bindValue("date", $this->start_date);
            $res->bindValue("description", $this->description);
            $res->bindValue("duration_days", $this->duration_days);
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

    public function update_chapters()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("thematics_fk", $this->id_thematic_units);
            $res->bindValue("chapter", $this->name_chapter);
            $res->bindValue("date", $this->start_date);
            $res->bindValue("description", $this->description);
            $res->bindValue("duration_days", $this->duration_days);
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

    public function delete_chapters()
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

    public function getAll_chapters()
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