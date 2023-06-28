<?php
namespace APP\team_schedule;

use APP\db\connect;
use APP\getInstance;

class team_schedule extends connect
{
    private $queryPost = 'INSERT INTO team_schedule (team_name, check_in_skills, check_out_skills,check_in_soft, check_out_soft, check_in_english, check_out_english, check_in_review, check_out_review, id_journey ) VALUES (:team_name, :check_in_skills, :check_out_skills, :check_in_soft, :check_out_soft, :check_in_english, :check_out_english, :check_in_review, :check_out_review, :journey_fk)';
    private $queryPut = 'UPDATE team_schedule SET team_name = :team_name, check_in_skills = :check_in_skills, check_out_skills = :check_out_skills, check_in_soft = :check_in_soft, check_out_soft = :check_out_soft, check_in_english = :check_in_english, check_out_english = :check_out_english, check_in_review = :check_in_review, check_out_review = :check_out_review, id_journey = :journey_fk WHERE  id = :id';
    private $queryCampos = 'SELECT column_name FROM information_schema.columns WHERE table_name = "team_schedule"';
    private $queryGetAll = 'SELECT  team_schedule.id AS "id",
    team_schedule.team_name AS "team_name",
    team_schedule.check_in_skills AS "check_in_skills",
    team_schedule.check_out_skills AS "check_out_skills",
    team_schedule.check_in_soft AS "check_in_soft",
    team_schedule.check_out_soft AS "check_out_soft",
    team_schedule.check_in_english AS "check_in_english",
    team_schedule.check_out_english AS "check_out_english",
    team_schedule.check_in_review AS "check_in_review",
    team_schedule.check_out_review AS "check_out_review",
    team_schedule.id_journey AS "id_journey_fk",
    journey.name_journey AS "journey_fk"
    FROM team_schedule
    INNER JOIN journey on team_schedule.id_journey= journey.id';
    private $queryDelete = 'DELETE FROM team_schedule WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, public $team_name = 1, public $check_in_skills = 1, public $check_out_skills = 1, public $check_in_soft = 1, public $check_out_soft = 1, public $check_in_english = 1, public $check_out_english = 1, public $check_in_review = 1, public $check_out_review = 1, private $id_journey = 1)
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
    public function post_team_schedule()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("team_name", $this->team_name);
            $res->bindValue("check_in_skills", $this->check_in_skills);
            $res->bindValue("check_out_skills", $this->check_out_skills);
            $res->bindValue("check_in_soft", $this->check_in_soft);
            $res->bindValue("check_out_soft", $this->check_out_soft);
            $res->bindValue("check_in_english", $this->check_in_english);
            $res->bindValue("check_out_english", $this->check_out_english);
            $res->bindValue("check_in_review", $this->check_in_review);
            $res->bindValue("check_out_review", $this->check_out_review);
            $res->bindValue("journey_fk", $this->id_journey);
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

    public function update_team_schedule($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("team_name", $this->team_name);
            $res->bindValue("check_in_skills", $this->check_in_skills);
            $res->bindValue("check_out_skills", $this->check_out_skills);
            $res->bindValue("check_in_soft", $this->check_in_soft);
            $res->bindValue("check_out_soft", $this->check_out_soft);
            $res->bindValue("check_in_english", $this->check_in_english);
            $res->bindValue("check_out_english", $this->check_out_english);
            $res->bindValue("check_in_review", $this->check_in_review);
            $res->bindValue("check_out_review", $this->check_out_review);
            $res->bindValue("journey_fk", $this->id_journey);
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

    public function delete_team_schedule($id)
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

    public function getAll_team_schedule()
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