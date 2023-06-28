<?php
namespace APP\staff;

use APP\db\connect;
use APP\getInstance;

class staff extends connect
{
    private $queryPost = 'INSERT INTO staff ( doc , first_name, second_name, first_surname, second_surname, eps, id_area, id_city) VALUES ( :doc, :first_name, :second_name,:first_surname, :second_surname, :eps, :area_fk, :city_fk)';
    private $queryPut = 'UPDATE staff SET doc = :doc, first_name = :first_name, second_name  = :second_name, first_surname = :first_surname, second_surname = :second_surname, eps = :eps, id_area = :area_fk, id_city = :city_fk WHERE  id = :id';
    private $queryCampos = 'SELECT column_name FROM information_schema.columns WHERE table_name = "staff"';
    private $queryGetAll = 'SELECT  staff.id AS "id",
    staff.doc  AS "doc",
    staff.first_name AS "first_name",
    staff.second_name AS "second_name",
    staff.first_surname AS "first_surname",
    staff.second_surname AS "second_surname",
    staff.eps AS "eps",
    staff.id_area AS "id_area_fk",
    staff.id_city AS "id_city_fk",
    areas.name_area AS "name_area_fk",
    cities.name_city AS "name_city_fk"
    FROM staff
    INNER JOIN areas ON staff.id_area = areas.id
    INNER JOIN cities ON staff.id_city = cities.id';
    private $queryDelete = 'DELETE FROM staff WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, public $doc = 1, public $first_name = 1, public $second_name = 1, public $first_surname = 1, public $second_surname = 1, private $eps = 1, private $id_area = 1, private $id_city = 1)
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
    public function post_staff()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("doc", $this->doc);
            $res->bindValue("first_name", $this->first_name);
            $res->bindValue("second_name", $this->second_name);
            $res->bindValue("first_surname", $this->first_surname);
            $res->bindValue("second_surname", $this->second_surname);
            $res->bindValue("eps", $this->eps);
            $res->bindValue("area_fk", $this->id_area);
            $res->bindValue("city_fk", $this->id_city);
            /**Execute es para ejecutar */
            $res->execute();
            $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Inserted data", "res" => $res];
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            echo json_encode($this->message);
        }
    }

    public function update_staff($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("doc", $this->doc);
            $res->bindValue("first_name", $this->first_name);
            $res->bindValue("second_name", $this->second_name);
            $res->bindValue("first_surname", $this->first_surname);
            $res->bindValue("second_surname", $this->second_surname);
            $res->bindValue("eps", $this->eps);
            $res->bindValue("area_fk", $this->id_area);
            $res->bindValue("city_fk", $this->id_city);
            /**Execute es para ejecutar */
            $res->execute();
            if ($res->rowCount() > 0) {
                $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Data updated"];
            } else {
                $this->message = ["Code" => 404, "Message" => "Data not founded"];

            }
        } catch (\PDOException $e) {
            /**Message es un array asociativo */if ($e->getCode() == 23000) {
                $pattern = '/`([^`]*)`/';
                preg_match_all($pattern, $res->errorInfo()[2], $matches);
                $matches = array_values(array_unique($matches[count($matches) - 1]));
                $this->message = ["Code" => $e->getCode(), "Message" => "Error, no se puede actualizar ya que el id indicado de la llave foranea  no contiene registros asociados en la tabla padre"];
            } else {
                $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
            }
        } finally {
            echo json_encode($this->message);
        }
    }

    public function delete_staff($id)
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

    public function getAll_staff()
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