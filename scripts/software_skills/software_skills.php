<?php
namespace APP\software_skills;

use APP\db\connect;
use APP\getInstance;

class software_skills extends connect
{
    private $queryPost = 'INSERT INTO software_skills ( id_team_schedule , id_journey, id_trainer, id_location, id_subject) VALUES ( :schedule_id_fk, :journey_fk, :trainer_fk,:location_fk, :subject_fk)';
    private $queryPut = 'UPDATE software_skills SET id_team_schedule = :schedule_id_fk, id_journey = :journey_fk, id_trainer  = :trainer_fk, id_location = :location_fk, id_subject = :subject_fk WHERE  id = :id';
    private $queryCampos = 'SELECT column_name FROM information_schema.columns WHERE table_name = "software_skills" AND table_schema = "campusland"';
    private $queryGetAll = 'SELECT software_skills.id AS "id",
    software_skills.id_team_schedule AS "schedule_id_fk",
    software_skills.id_journey AS "journey_fk",
    software_skills.id_trainer AS "trainer_fk",
    software_skills.id_location AS "location_fk",
    software_skills.id_subject AS "subject_fk",
    journey.name_journey AS "name_journey_fk",
    subjects.name_subject AS "subject_name_fk",
    locations.name_location AS "location_name_fk"
    FROM software_skills
    INNER JOIN journey ON software_skills.id_journey = journey.id
    INNER JOIN locations ON software_skills.id_location = locations.id
    INNER JOIN subjects ON software_skills.id_subject = subjects.id';
    private $queryDelete = 'DELETE FROM software_skills WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_team_schedule = 1, private $id_journey = 1, private $id_trainer = 1, private $id_location = 1, private $id_subject = 1)
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
    public function post_software_skills()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("schedule_id_fk", $this->id_team_schedule);
            $res->bindValue("journey_fk", $this->id_journey);
            $res->bindValue("trainer_fk", $this->id_trainer);
            $res->bindValue("location_fk", $this->id_location);
            $res->bindValue("subject_fk", $this->id_subject);
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

    public function update_software_skills($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("schedule_id_fk", $this->id_team_schedule);
            $res->bindValue("journey_fk", $this->id_journey);
            $res->bindValue("trainer_fk", $this->id_trainer);
            $res->bindValue("location_fk", $this->id_location);
            $res->bindValue("subject_fk", $this->id_subject);
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

    public function delete_software_skills($id)
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

    public function getAll_software_skills()
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