<?php
namespace APP;

class english_skills extends connect
{
    private $queryPost = 'INSERT INTO english_skills ( id_team_schedule , id_journey, id_teacher, id_location, id_subject) VALUES ( :schedule_id_fk, :journey_fk, :teacher_fk,:location_fk, :email)';
    private $queryPut = 'UPDATE english_skills SET id_team_schedule = :schedule_id_fk, id_journey = :journey_fk, id_teacher  = :teacher_fk, id_location = :location_fk, id_subject = :email WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_team_schedule  AS "schedule_id_fk", id_journey AS "journey_fk", id_teacher AS "teacher_fk", id_location AS "location_fk", id_subject AS "email" FROM english_skills';
    private $queryDelete = 'DELETE FROM english_skills WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_team_schedule = 1, private $id_journey = 1, private $id_teacher = 1, private $id_location = 1, private $id_subject = 1)
    {
        parent::__construct();

    }
    public function postEmergencyContact()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("schedule_id_fk", $this->id_team_schedule);
            $res->bindValue("journey_fk", $this->id_journey);
            $res->bindValue("teacher_fk", $this->id_teacher);
            $res->bindValue("location", $this->id_location);
            $res->bindValue("email", $this->email);


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

    public function updateEmergencyContact()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("schedule_id_fk", $this->id_team_schedule);
            $res->bindValue("journey", $this->id_journey);
            $res->bindValue("teacher", $this->id_teacher);
            $res->bindValue("location", $this->id_location);
            $res->bindValue("email", $this->email);
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

    public function deleteEmergencyContact()
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

    public function getAllEmergencyContact()
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