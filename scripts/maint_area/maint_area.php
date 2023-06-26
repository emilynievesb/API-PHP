<?php

namespace APP\maint_area;

use APP\db\connect;
use APP\getInstance;

class maint_area extends connect
{
    private $queryPost = 'INSERT INTO maint_area ( id_area, id_staff, id_position, id_journey) VALUES ( :area_id_fk, :staff_id_fk, :position_id_fk, :journey_id_fk)';
    private $queryPut = 'UPDATE maint_area SET id_area = :area_id_fk, id_staff = :staff_id_fk, id_position = :position_id_fk,id_journey  = :journey_id_fk WHERE  id = :id';
    private $queryGetAll = 'SELECT maint_area.id AS "id",
    maint_area.id_area AS "area_id_fk",
    areas.name_area AS "area_name_fk",
    maint_area.id_staff AS "staff_id_fk",
    staff.first_name AS "first_name_staff_fk",
    maint_area.id_position AS "position_id_fk",
    position.name_position AS "name_position_fk",
    maint_area.id_journey AS "journey_id_fk",
    journey.name_journey AS "name_journey_fk"
    FROM maint_area
    INNER JOIN areas ON maint_area.id_area = areas.id
    INNER JOIN staff ON maint_area.id_staff = staff.id
    INNER JOIN position ON maint_area.id_position = position.id
    INNER JOIN journey ON maint_area.id_journey = journey.id';
    private $queryDelete = 'DELETE FROM maint_area WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_area = 1, private $id_staff = 1, private $id_position = 1, private $id_journey = 1)
    {
        parent::__construct();

    }
    public function post_maint_area()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("area_id_fk", $this->id_area);
            $res->bindValue("staff_id_fk", $this->id_staff);
            $res->bindValue("position_id_fk", $this->id_position);
            $res->bindValue("journey_id_fk", $this->id_journey);

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

    public function update_maint_area($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("area_id_fk", $this->id_area);
            $res->bindValue("staff_id_fk", $this->id_staff);
            $res->bindValue("position_id_fk", $this->id_position);
            $res->bindValue("journey_id_fk", $this->id_journey);

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

    public function delete_maint_area($id)
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
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            print_r($this->message);
        }
    }

    public function getAll_maint_area()
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