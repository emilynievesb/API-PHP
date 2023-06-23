<?php

namespace APP;

class admin_area extends connect
{
    private $queryPost = 'INSERT INTO admin_area ( id_area, id_staff, id_position, id_journeys) VALUES ( :area_id_fk, :staff_id_fk, :position_id_fk, :journeys_id_fk)';
    private $queryPut = 'UPDATE admin_area SET id_area = :area_id, id_staff = :staff_id_fk, id_position = :position_id_fk,id_journeys  = :journeys_id_fk WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_area AS "area_id_fk", id_staff AS "staff_id_fk", id_position AS "position_id_fk", id_journeys AS "journeys_id_fk" FROM admin_area';
    private $queryDelete = 'DELETE FROM admin_area WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_area = 1, private $id_staff = 1, private $id_position = 1, private $id_journeys = 1)
    {
        parent::__construct();

    }
    public function postAdminArea()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("area_id_fk", $this->id_area);
            $res->bindValue("staff_id_fk", $this->id_staff);
            $res->bindValue("position_id_fk", $this->id_position);
            $res->bindValue("journeys_id_fk", $this->id_journeys);

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

    public function updateAdminArea()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("area_id_fk", $this->id_area);
            $res->bindValue("staff_id_fk", $this->id_staff);
            $res->bindValue("position_id_fk", $this->id_position);
            $res->bindValue("journeys_id_fk", $this->id_journeys);
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

    public function deleteAdminArea()
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

    public function getAllAdminArea()
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