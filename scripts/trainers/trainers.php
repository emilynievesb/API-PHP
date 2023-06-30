<?php
namespace APP\trainers;

use APP\db\connect;
use APP\getInstance;

class trainers extends connect
{
    private $queryPost = 'INSERT INTO trainers (id_staff, id_level, id_route, id_academic_area, id_position, id_team_educator) VALUES (:staff_fk,:level_fk, :route_fk, :academic_area_fk, :position_fk, :team_fk)';
    private $queryPut = 'UPDATE trainers SET id_staff = :staff_fk, id_level = :level_fk, id_route = :route_fk, id_academic_area = :academic_area_fk,
    id_position = :position_fk, id_team_educator = :team_fk WHERE id = :id';
    private $queryCampos = 'SELECT column_name FROM information_schema.columns WHERE table_name = "trainers"';
    private $queryGetAll = 'SELECT  trainers.id AS "id",
    trainers.id_staff AS "staff_fk",
    trainers.id_level AS "level_fk",
    trainers.id_route AS "route_fk",
    trainers.id_academic_area AS "academic_area_fk",
    trainers.id_position AS "position_fk",
    trainers.id_team_educator AS "team_fk",
    staff.first_name AS "name_staff",
    levels.name_level AS "name_level_fk",
    routes.name_route AS "name_route_fk",
    position.name_position AS "name_position_fk",
    team_educators.name_rol AS "rol_team_educators_fk"
    FROM trainers
    INNER JOIN staff ON trainers.id_staff = staff.id
    INNER JOIN levels ON trainers.id_level = levels.id
    INNER JOIN routes ON trainers.id_route = routes.id
    INNER JOIN position ON trainers.id_position = position.id
    INNER JOIN team_educators ON trainers.id_team_educator = team_educators.id';
    private $queryDelete = 'DELETE FROM trainers WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_staff = 1, private $id_level = 1, private $id_route = 1, private $id_academic_area = 1, private $id_position = 1, private $id_team_educator = 1)
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
    public function post_trainers()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("staff_fk", $this->id_staff);
            $res->bindValue("level_fk", $this->id_level);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("academic_area_fk", $this->id_academic_area);
            $res->bindValue("position_fk", $this->id_position);
            $res->bindValue("team_fk", $this->id_team_educator);
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

    public function update_trainers($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("staff_fk", $this->id_staff);
            $res->bindValue("level_fk", $this->id_level);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("academic_area_fk", $this->id_academic_area);
            $res->bindValue("position_fk", $this->id_position);
            $res->bindValue("team_fk", $this->id_team_educator);
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

    public function delete_trainers($id)
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

    public function getAll_trainers()
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