<?php

namespace APP\thematic_units;

use APP\db\connect;
use APP\getInstance;

class thematic_units extends connect
{
    private $queryPost = 'INSERT INTO thematic_units ( id_route, name_thematics_units, start_date, end_date, description, duration_days) VALUES ( :route_fk, :unit_name, :start_date, :end_date, :description, :days)';
    private $queryPut = 'UPDATE thematic_units SET id_route = :route_fk, name_thematics_units = :unit_name, start_date = :start_date, end_date = :end_date, description  = :description, duration_days  = :days WHERE  id = :id';
    private $queryCampos = 'SELECT column_name FROM information_schema.columns WHERE table_name = "thematic_units" AND table_schema = "campusland"';
    private $queryGetAll = 'SELECT thematic_units.id AS "id",
    thematic_units.id_route AS "route_fk",
    thematic_units.name_thematics_units AS "unit_name",
    thematic_units.start_date AS "start_date",
    thematic_units.end_date AS "end_date",
    thematic_units.description AS "description",
    thematic_units.duration_days AS "duration_days",
    routes.name_route AS "name_route_fk"
    FROM thematic_units
    INNER JOIN routes ON thematic_units.id_route = routes.id';
    private $queryDelete = 'DELETE FROM thematic_units WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_route = 1, private $name_thematics_units = 1, public $start_date = 1, public $end_date = 1, public $description = 1, public $duration_days = 1)
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
    public function post_thematic_units()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("unit_name", $this->name_thematics_units);
            $res->bindValue("start_date", $this->start_date);
            $res->bindValue("end_date", $this->end_date);
            $res->bindValue("description", $this->description);
            $res->bindValue("days", $this->duration_days);
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

    public function update_thematic_units($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("unit_name", $this->name_thematics_units);
            $res->bindValue("start_date", $this->start_date);
            $res->bindValue("end_date", $this->end_date);
            $res->bindValue("description", $this->description);
            $res->bindValue("days", $this->duration_days);
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

    public function delete_thematic_units($id)
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

    public function getAll_thematic_units()
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