<?php

namespace APP\optional_topics;

use APP\db\connect;
use APP\getInstance;

class optional_topics extends connect
{
    private $queryPost = 'INSERT INTO optional_topics ( id_topic, id_team, id_subject, id_camper, id_team_educator) VALUES ( :topic_fk, :team_fk, :subject_fk, :camper_fk, :educator_fk)';
    private $queryPut = 'UPDATE optional_topics SET id_topic = :topic_fk, id_team = :team_fk, id_subject = :subject_fk, id_camper  = :camper_fk, id_team_educator  = :educator_fk WHERE id = :id';
    private $queryCampos = 'SELECT column_name FROM information_schema.columns WHERE table_name = "optional_topics" AND table_schema = "campusland"';
    private $queryGetAll = 'SELECT  optional_topics.id AS "id",
    optional_topics.id_topic AS "topic_fk",
    optional_topics.id_team AS "team_fk",
    optional_topics.id_subject AS "subject_fk",
    optional_topics.id_camper AS "camper_fk",
    optional_topics.id_team_educator AS "team_educator_fk",
    topics.name_topic AS "nombre_topic_fk",
    team_educators.name_rol AS "rol_educator_fk",
    subjects.name_subject AS "name_subject_fk"
    FROM optional_topics
    INNER JOIN team_educators ON optional_topics.id_team_educator = team_educators.id
    INNER JOIN topics ON optional_topics.id_topic = topics.id
    INNER JOIN subjects ON optional_topics.id_subject = subjects.id';
    private $queryDelete = 'DELETE FROM optional_topics WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_topic = 1, public $id_team = 1, public $id_subject = 1, public $id_camper = 1, public $id_team_educator = 1)
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
    public function post_optional_topics()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("topic_fk", $this->id_topic);
            $res->bindValue("team_fk", $this->id_team);
            $res->bindValue("subject_fk", $this->id_subject);
            $res->bindValue("camper_fk", $this->id_camper);
            $res->bindValue("educator_fk", $this->id_team_educator);
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

    public function update_optional_topics($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("topic_fk", $this->id_topic);
            $res->bindValue("team_fk", $this->id_team);
            $res->bindValue("subject_fk", $this->id_subject);
            $res->bindValue("camper_fk", $this->id_camper);
            $res->bindValue("educator_fk", $this->id_team_educator);
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

    public function delete_optional_topics($id)
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

    public function getAll_optional_topics()
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