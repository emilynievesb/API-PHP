<?php
namespace APP;

class review_skills extends connect
{
    private $queryPost = 'INSERT INTO review_skills ( id_team_schedule , id_journey, id_tutor, id_location ) VALUES ( :team_schedule, :journey, :tutor, :location)';
    private $queryPut = 'UPDATE review_skills SET id_team_schedule = :team_schedule, id_journey = :journey, id_tutor = :tutor,id_location = :location WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_team_schedule AS "team_schedule", id_journey AS "journey", id_tutor AS "tutor", id_location AS "location" FROM review_skills';
    private $queryDelete = 'DELETE FROM review_skills WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(public $id = 1, public $id_team_schedule = 1, public $id_journey = 1, public $id_tutor = 1, public $id_location = 1)
    {
        parent::__construct();

    }
    public function postReviewSkills()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("id_team_schedule", $this->team_schedule);
            $res->bindValue("journey", $this->id_journey);
            $res->bindValue("tutor", $this->id_tutor);
            $res->bindValue("location", $this->id_location);
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

    public function updateReviewSkills()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("id_team_schedule", $this->team_schedule);
            $res->bindValue("journey", $this->id_journey);
            $res->bindValue("tutor", $this->id_tutor);
            $res->bindValue("location", $this->id_location);
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

    public function deleteReviewSkills()
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

    public function getAllReviewSkills()
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