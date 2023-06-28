<?php
namespace APP\psychologist;

use APP\db\connect;
use APP\getInstance;

class psychologist extends connect
{
    private $queryPost = 'INSERT INTO psychologist (id_staff, id_route, id_academic_area_psycologist, id_psychologist, id_team_educator) VALUES (:staff_fk,:route_fk, :academic_area_fk, :psychologist_fk, :team_fk)';
    private $queryPut = 'UPDATE psychologist SET id_staff = :staff, id_route = :route_fk, id_academic_area_psycologist = :academic_area_fk, id_psychologist = :psychologist_fk, id_team_educator = :team_fk  WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_staff AS "staff", id_route AS "route_fk", id_academic_area_psycologist AS "academic_area_fk", id_psychologist AS "psychologist_fk", id_team_educator AS "team_fk" FROM psychologist';
    private $queryDelete = 'DELETE FROM psychologist WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_staff = 1, private $id_route = 1, private $id_academic_area_psycologist = 1, private $id_psychologist = 1, private $id_team_educator = 1)
    {
        parent::__construct();

    }
    public function post_psychologist()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("staff_fk", $this->id_staff);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("academic_area_fk", $this->id_academic_area_psycologist);
            $res->bindValue("psychologist_fk", $this->id_psychologist);
            $res->bindValue("team_fk", $this->id_team_educator);
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

    public function update_psychologist()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("staff_fk", $this->id_staff);
            $res->bindValue("route_fk", $this->id_route);
            $res->bindValue("academic_area_fk", $this->id_academic_area_psycologist);
            $res->bindValue("psychologist_fk", $this->id_psychologist);
            $res->bindValue("team_fk", $this->id_team_educator);
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

    public function delete_psychologist()
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

    public function getAll_psychologist()
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