<?php

namespace APP\modules;

use APP\db\connect;
use APP\getInstance;

class modules extends connect
{
    private $queryPost = 'INSERT INTO modules (name_module, start_date, end_date, description, duration_days, id_theme) VALUES (:module, :start_date, :end_date, :description, :days, :theme_fk)';
    private $queryPut = 'UPDATE modules SET name_module = :module, start_date = :start_date, end_date = :end_date,description  = :description,duration_days  = :days, id_theme  = :theme_fk WHERE  id = :id';
    private $queryCampos = 'SELECT column_name FROM information_schema.columns WHERE table_name = "modules" AND table_schema = "campusland"';
    private $queryGetAll = 'SELECT modules.id AS "id",
    modules.name_module AS "module",
    modules.start_date AS "start_date",
    modules.end_date AS "end_date",
    modules.description AS "description",
    modules.duration_days AS "days",
    modules.id_theme AS "theme_fk",
    themes.name_theme AS "theme_name_fk"
    FROM modules
    INNER JOIN themes ON modules.id_theme = themes.id';
    private $queryDelete = 'DELETE FROM modules WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $name_module = 1, public $start_date = 1, public $end_date = 1, public $description = 1, public $duration_days = 1, private $id_theme = 1)
    {
        parent::__construct();

    }
    public function getCampos()
    {
        $res = $this->conx->prepare($this->queryCampos);
        try {
            /**Execute es para ejecutar */
            $res->execute();
            $campos = array();
            while ($row = $res->fetch(\PDO::FETCH_ASSOC)) {
                $campos[] = $row["column_name"];
            }
            $this->message = $campos;
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            return ($this->message);
        }
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
            /**Message es un array asociativo */if ($e->getCode() == 23000) {
                $pattern = '/`([^`]*)`/';
                preg_match_all($pattern, $res->errorInfo()[2], $matches);
                $matches = array_values(array_unique($matches[count($matches) - 1]));
                $this->message = ["Code" => $e->getCode(), "Message" => "Error, no se puede actualizar ya que el id indicado de la llave foranea no contiene registros asociados en la tabla $matches[4]", $matches];
            } else {
                $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
            }
        } finally {
            echo json_encode($this->message);
        }
    }

    public function update_modules($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
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
            if ($e->getCode() == 23000) {
                $pattern = '/`([^`]*)`/';
                preg_match_all($pattern, $res->errorInfo()[2], $matches);
                $matches = array_values(array_unique($matches[count($matches) - 1]));
                $this->message = ["Code" => $e->getCode(), "Message" => "Error, no se puede actualizar ya que el id indicado de la llave foranea  no contiene registros asociados en la tabla $matches[4]"];
            } else {
                $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
            }
        } finally {
            echo json_encode($this->message);
        }
    }

    public function delete_modules($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryDelete);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            $res->bindParam("id", $id);
            /**Execute es para ejecutar */
            $res->execute();

            if ($res->rowCount() > 0) {
                $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Data deleted"];
            } else {
                $this->message = ["Code" => 404, "Message" => "Data not founded"];
            }
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            if ($e->getCode() == 23000) {
                $pattern = '/`([^`]*)`/';
                preg_match_all($pattern, $res->errorInfo()[2], $matches);
                $matches = array_values(array_unique($matches[count($matches) - 1]));
                $this->message = ["Code" => $e->getCode(), "Message" => "Error, no se puede eliminar el id inicado ya que contiene registros asociados en la tabla $matches[1]"];
            } else {
                $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
            }
        } finally {
            echo json_encode($this->message);
        }
    }

    public function getAll_modules()
    {
        try {
            $res = $this->conx->prepare($this->queryGetAll);
            $res->execute();
            $campos = $this->getCampos();
            $this->message = ["Code" => 200, "Message" => $res->fetchAll(\PDO::FETCH_ASSOC), "Campos" => $campos];
        } catch (\PDOException $e) {
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            echo json_encode($this->message);
        }
    }
}
?>