<?php
namespace APP\campers;

use APP\db\connect;
use APP\getInstance;

class campers extends connect
{
    private $queryPost = 'INSERT INTO campers (id_team_schedule, id_route, id_trainer,id_psycologist, id_teacher, id_level, id_journey,id_staff) VALUES (:team_fk, :route_fk, :trainer_fk, :psycologist_fk, :teacher_fk, :level_fk, :journey_fk, :staff_fk)';
    private $queryPut = 'UPDATE campers SET id_team_schedule = :team_fk, id_route = :route_fk, id_trainer = :trainer_fk, id_psycologist = :psycologist_fk, id_teacher = :teacher_fk, id_level = :level_fk, id_journey = :journey_fk, id_staff = :staff_fk WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_team_schedule AS "team_fk", id_route AS "route_fk",id_trainer AS "trainer_fk",id_psycologist AS "psycologist_fk",id_teacher AS "teacher_fk",id_level AS "level_fk",id_journey AS "journey_fk",id_staff AS "staff_fk", FROM campers';
    private $queryDelete = 'DELETE FROM campers WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_team_schedule = 1, private $id_route = 1, private $id_trainer = 1, private $id_psycologist = 1, private $id_teacher = 1, private $id_level = 1, private $id_journey = 1, private $id_staff = 1)
    {
        parent::__construct();

    }
    public function post_campers()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("team_fk", $this->id_team_schedule);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("trainer_fk", $this->id_trainer);
            $res->bindValue("psycologist_fk", $this->id_psycologist);
            $res->bindValue("teacher_fk", $this->id_teacher);
            $res->bindValue("level_fk", $this->id_staff);
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

    public function update_campers()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("team_fk", $this->id_team_schedule);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("trainer_fk", $this->id_trainer);
            $res->bindValue("psycologist_fk", $this->id_psycologist);
            $res->bindValue("teacher_fk", $this->id_teacher);
            $res->bindValue("level_fk", $this->id_staff);
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

    public function delete_campers()
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
            if ($e->getCode() == 23000) {
                $pattern = '/`([^`]*)`/';
                preg_match_all($pattern, $res->errorInfo()[2], $matches);
                $matches = array_values(array_unique($matches[count($matches) - 1]));
                $mensaje = ["Code" => $e->getCode(), "Message" => "Error, no se puede eliminar el id inicado ya que contiene registros asociados en la tabla $matches[1]"];
            } else {
                $mensaje = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
            }
        } finally {
            print_r($mensaje);
        }
    }

    public function getAll_campers()
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