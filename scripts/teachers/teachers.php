<?php
namespace APP\teachers;

use APP\db\connect;
use APP\getInstance;

class teachers extends connect
{
    private $queryPost = 'INSERT INTO teachers (id_staff, id_route, id_academic_area, id_position, id_team_educator) VALUES (:staff_fk, :route_fk, :academic_area_fk, :position_fk, :team_educator_fk)';
    private $queryPut = 'UPDATE teachers SET id_staff = :staff_fk, id_route = :route_fk, id_academic_area = :academic_area_fk, id_position = :position_fk, id_team_educator = :team_educator_fk WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_staff  AS "staff_fk", id_route  AS "route_fk", id_academic_area  AS "academic_area_fk", id_position  AS "position_fk", id_team_educator  AS "team_educator_fk" FROM teachers';
    private $queryDelete = 'DELETE FROM teachers WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(public $id = 1, private $id_staff = 1, private $id_route = 1, private $id_academic_area = 1, private $id_position = 1, private $id_team_educator = 1)
    {
        parent::__construct();

    }
    public function post_teachers()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("staff_fk", $this->id_staff);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("academic_area_fk", $this->id_academic_area);
            $res->bindValue("position_fk", $this->id_position);
            $res->bindValue("team_educator_fk", $this->id_team_educator);
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

    public function update_teachers()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("staff_fk", $this->id_staff);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("academic_area_fk", $this->id_academic_area);
            $res->bindValue("position_fk", $this->id_position);
            $res->bindValue("team_educator_fk", $this->id_team_educator);
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

    public function delete_teachers()
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

    public function getAll_teachers()
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